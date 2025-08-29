<?php
/**
 * Archive Query
 *
 * @package WCB
 */

namespace WCB\Functionalities\Archive;

defined( 'ABSPATH' ) || die();

/**
 * Class ArchiveQuery
 * Handles the construction and execution of WP_Query for archive functionality
 */
class ArchiveQuery {
	/**
	 * Number of posts per page.
	 *
	 * @var int
	 */
	const POSTS_PER_PAGE = 4;

	/**
	 * Post type.
	 *
	 * @var string
	 */
	private $post_type;

	/**
	 * Current page.
	 *
	 * @var int
	 */
	private $current_page = 1;

	/**
	 * Search query.
	 *
	 * @var string|null
	 */
	private $search_query = null;

	/**
	 * Order by value.
	 *
	 * @var string|null
	 */
	private $order_by = null;

	/**
	 * Order direction.
	 *
	 * @var string
	 */
	private $order = 'DESC';

	/**
	 * Tax query filters.
	 *
	 * @var array
	 */
	private $tax_filters = array( 'relation' => 'AND' );

	/**
	 * Constructor.
	 *
	 * @param string $post_type The post type to query.
	 * @param array  $data      The data to build the query from.
	 * @param string $source    The source of the data ('url' or 'api').
	 */
	public function __construct( $post_type, $data, $source = 'url' ) {
		$this->post_type = $post_type;

		if ( 'url' === $source ) {
			$this->process_url_data();
		} elseif ( 'api' === $source ) {
			$this->process_api_data( $data );
		}
	}

	/**
	 * Process data from URL parameters.
	 */
	private function process_url_data() {
		// Get page from URL.
		$page = get_query_var( 'cpage' );
		if ( $page ) {
			$this->set_page( $page );
		}

		// Get search from URL.
		$search = isset( $_GET['search'] ) ? sanitize_text_field( wp_unslash( $_GET['search'] ) ) :
				( isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '' );
		if ( $search ) {
			$this->set_search( $search );
		}

		// Get order from URL.
		if ( isset( $_GET['order_by'] ) ) {
			$this->set_order( sanitize_text_field( wp_unslash( $_GET['order_by'] ) ) );
		}

		// Get taxonomy filters from URL.
		$taxonomies = get_object_taxonomies( $this->post_type, 'names' );
		$taxonomies = array_diff( $taxonomies, array( 'post_tag', 'post_format' ) );

		foreach ( $taxonomies as $taxonomy ) {
			if ( isset( $_GET[ $taxonomy ] ) && 'all' !== $_GET[ $taxonomy ] ) {
				$terms = explode( ',', sanitize_text_field( wp_unslash( $_GET[ $taxonomy ] ) ) );
				$this->add_taxonomy_filter( $taxonomy, $terms );
			}
		}
	}

	/**
	 * Process data from API request.
	 *
	 * @param array $data API request data.
	 */
	private function process_api_data( $data ) {
		// Set page.
		if ( isset( $data['cpage'] ) ) {
			$this->set_page( $data['cpage'] );
		}

		// Process filters.
		if ( isset( $data['filters'] ) && is_array( $data['filters'] ) ) {
			foreach ( $data['filters'] as $filter ) {
				switch ( $filter['type'] ) {
					case 'taxonomy':
						$terms = array_map(
							function ( $value ) {
								return $value['slug'];
							},
							$filter['filter_value']
						);
						$this->add_taxonomy_filter( $filter['filter_name'], $terms );
						break;

					case 'search':
						$this->set_search( $filter['filter_value'][0]['slug'] );
						break;

					case 'order_by':
						$this->set_order( $filter['filter_value'][0]['slug'] );
						break;
				}
			}
		}
	}

	/**
	 * Set the current page.
	 *
	 * @param int $page Page number.
	 * @return self
	 */
	private function set_page( $page ) {
		$this->current_page = max( 1, intval( $page ) );
		return $this;
	}

	/**
	 * Set search query.
	 *
	 * @param string $search Search query.
	 * @return self
	 */
	private function set_search( $search ) {
		$this->search_query = $search;
		return $this;
	}

	/**
	 * Set order parameters.
	 *
	 * @param string $order_by Order by field.
	 * @return self
	 */
	private function set_order( $order_by ) {
		if ( $order_by ) {
			// Regular expression to match "orderby" and "order" parts.
			if ( preg_match( '/^(.*)_(ASC|DESC)$/i', $order_by, $matches ) ) {
				$this->order_by = $matches[1]; // Part before _ASC or _DESC.
				$this->order    = strtoupper( $matches[2] ); // ASC or DESC.
			} else {
				$this->order_by = $order_by;
			}
		}
		return $this;
	}

	/**
	 * Add taxonomy filter.
	 *
	 * @param string $taxonomy Taxonomy name.
	 * @param array  $terms    Terms to filter by.
	 * @return self
	 */
	private function add_taxonomy_filter( $taxonomy, $terms ) {
		if ( ! empty( $terms ) ) {
			$tax_query           = array(
				'taxonomy' => $taxonomy,
				'field'    => 'slug',
				'terms'    => $terms,
			);
			$this->tax_filters[] = $tax_query;
		}
		return $this;
	}

	/**
	 * Build query arguments.
	 *
	 * @return array
	 */
	private function build_query_args() {
		$args = array(
			'post_type'      => $this->post_type,
			'posts_per_page' => self::POSTS_PER_PAGE,
			'paged'          => $this->current_page,
			'post_status'    => 'publish',
		);

		// Add search if set.
		if ( $this->search_query ) {
			$args['s'] = $this->search_query;
		}

		// Add order parameters if set.
		if ( $this->order_by ) {
			$args['orderby'] = $this->order_by;
			$args['order']   = $this->order;
		}

		// Add taxonomy filters if any exist.
		if ( count( $this->tax_filters ) > 1 ) { // More than just the 'relation' key.
			$args['tax_query'] = $this->tax_filters;
		}

		return $args;
	}

	/**
	 * Execute the query.
	 *
	 * @return \WP_Query
	 */
	public function query() {
		return new \WP_Query( $this->build_query_args() );
	}

	/**
	 * Get posts per page value.
	 *
	 * @return int
	 */
	public function get_posts_per_page() {
		return self::POSTS_PER_PAGE;
	}
}
