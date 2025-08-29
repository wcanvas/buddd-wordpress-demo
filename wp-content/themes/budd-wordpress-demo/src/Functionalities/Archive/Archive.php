<?php
/**
 * Archive
 *
 * @package WCB
 */

namespace WCB\Functionalities\Archive;

defined( 'ABSPATH' ) || die();

/**
 * Class Archive
 *
 * @package WCB\Functionalities
 */
class Archive {

	/**
	 * Post type.
	 *
	 * @var string
	 */
	private $post_type;

	/**
	 * Posts per page.
	 *
	 * @var int
	 */
	private $per_page;

	/**
	 * Current page.
	 *
	 * @var int
	 */
	private $current_page;

	/**
	 * Taxonomies filters.
	 *
	 * @var iterable
	 */
	private $taxonomies;

	/**
	 * WP Query result object.
	 *
	 * @var object
	 */
	private $query;

	/**
	 * Archive Query instance.
	 *
	 * @var ArchiveQuery
	 */
	private $archive_query;

	/**
	 * Component path.
	 *
	 * @var string
	 */
	private $component_name;

	/**
	 * Error component path.
	 *
	 * @var string
	 */
	private $error_component_name;

	/**
	 * Constructor.
	 *
	 * @param string $post_type The post type to query.
	 * @param string $component_name The component name to render.
	 * @param string $error_component_name The error component name to render.
	 */
	public function __construct( $post_type, $component_name = null, $error_component_name = null ) {
		$this->component_name       = $component_name;
		$this->error_component_name = $error_component_name;
		$this->setup( $post_type );
	}

	/**
	 * Setup Archive object.
	 *
	 * @param string $post_type The post type to query.
	 */
	private function setup( $post_type ) {
		$this->post_type  = $post_type;
		$this->taxonomies = get_object_taxonomies( $post_type, 'names' );
		$this->taxonomies = array_diff( $this->taxonomies, array( 'post_tag', 'post_format' ) );

		// Get the current page from the URL.
		$this->current_page = get_query_var( 'cpage' ) && get_query_var( 'cpage' ) >= 1 ? get_query_var( 'cpage' ) : 1;

		// Create query using ArchiveQuery.
		$this->archive_query = new ArchiveQuery( $post_type, array(), 'url' );
		$this->query         = $this->archive_query->query();
		$this->per_page      = $this->archive_query->get_posts_per_page();
	}

	/**
	 * Render the items.
	 */
	public function render_items() {
		if ( ! file_exists( get_template_directory() . '/components/' . $this->component_name . '/' . $this->component_name . '.php' ) ) {
			return;
		}

		/**
		 * Changes here should be also made in the ArchiveApi.php file.
		 */
		if ( $this->query->have_posts() ) {
			while ( $this->query->have_posts() ) {
				$this->query->the_post();

				get_template_part(
					'/components/' . $this->component_name . '/' . $this->component_name,
					null,
					array(
						'title'    => get_the_title(),
						'excerpt'  => get_the_excerpt(),
						'image_id' => get_post_thumbnail_id(),
						'cta_text' => 'Explore more',
						'cta_link' => get_the_permalink(),
					)
				);
			}
			wp_reset_postdata();
		}
	}

	/**
	 * Render the error component.
	 *
	 * @param string $error_text The error text to display.
	 */
	public function render_error( $error_text = 'No posts for the selected query.' ) {
		if ( ! file_exists( get_template_directory() . '/components/' . $this->error_component_name . '/' . $this->error_component_name . '.php' ) ) {
			return;
		}

			get_template_part(
				'/components/' . $this->error_component_name . '/' . $this->error_component_name,
				null,
				array(
					'show_error' => $this->query->have_posts(),
					'error_text' => $error_text,
				)
			);
	}

	/**
	 * Render the pagination (client-side pagination).
	 */
	public function render_pagination() {
		$total_pages  = $this->query->max_num_pages;
		$current_page = $this->current_page;
		$per_page     = $this->per_page;

		get_template_part(
			'components/archive-pagination/archive-pagination',
			null,
			array(
				'current_page' => $current_page,
				'total_pages'  => $total_pages,
				'per_page'     => $per_page,
			)
		);
	}

	/**
	 * Echo the archive container data.
	 */
	public function get_archive_data() {
		echo 'data-post-type="' . esc_attr( $this->post_type ) . '" data-component-name="' . esc_attr( $this->component_name ) . '"';
	}

	/**
	 * Render the filters, filter_type can be checkbox or select (TODO).
	 *
	 * @param string $filter_type The type of filter to render.
	 * @param string $single_taxonomy The taxonomy slug to render.
	 */
	public function render_filters( $filter_type = 'checkbox', $single_taxonomy = null ) {
		global $wpdb;

		// If single_taxonomy is set, only render that taxonomy.
		$loop_taxonomies = $single_taxonomy ? array( $single_taxonomy ) : $this->taxonomies;

		foreach ( $loop_taxonomies as $taxonomy ) {
			$results = array();

			$results = $wpdb->get_col(
				$wpdb->prepare(
					"
						SELECT DISTINCT tt.term_id
						FROM {$wpdb->term_relationships} AS tr
						INNER JOIN {$wpdb->term_taxonomy} AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
						INNER JOIN {$wpdb->posts} AS p ON tr.object_id = p.ID
						WHERE tt.taxonomy = %s
						AND p.post_type = %s
						AND p.post_status = 'publish'
					",
					$taxonomy,
					$this->post_type
				)
			);

			if ( empty( $results ) ) {
				continue;
			}

			$terms = get_terms(
				array(
					'taxonomy'   => $taxonomy,
					'include'    => $results,
					'hide_empty' => false,
				)
			);

			if ( empty( $terms ) ) {
				continue;
			}

			$terms_array = array();

			/**
			 * TODO: For the learn CPT we need to check every PARENT post before and then create an associative array with the terms and counter value. After that we need to rest to the count in the specific term to remove term if only applied to a parent post.
			 */
			$empty_terms_count = 0;

			foreach ( $terms as $term ) {
				if ( ! $term->count ) {
					++$empty_terms_count;
					continue;
				}

				$terms_array[] = array(
					'slug'  => $term->slug,
					'label' => $term->name,
				);
			}

			if ( count( $terms ) === $empty_terms_count ) {
				continue;
			}

			$taxonomy_object = get_taxonomy( $taxonomy );
			$taxonomy_label  = $taxonomy_object->label;

			get_template_part(
				'components/archive-pagination/archive-filter-' . $filter_type,
				null,
				array(
					'taxonomy_name'  => $taxonomy,
					'taxonomy_label' => $taxonomy_label,
					'terms'          => $terms_array,
				)
			);
		}
	}

	/**
	 * Render the selected filters by checking the url. This only renders the taxonomy filters. (No pagination or sort)
	 */
	public function render_selected_filters() {
		$selected_terms = array();

		foreach ( $this->taxonomies as $taxonomy ) {
			if ( isset( $_GET[ $taxonomy ] ) && 'all' !== $_GET[ $taxonomy ] ) {
				$terms = explode( ',', sanitize_text_field( wp_unslash( $_GET[ $taxonomy ] ) ) );

				foreach ( $terms as $term_slug ) {
					$term = get_term_by( 'slug', $term_slug, $taxonomy );
					if ( $term ) {
						$selected_terms[] = array(
							'taxonomy' => $taxonomy,
							'slug'     => $term->slug,
							'label'    => $term->name,
						);
					}
				}
			}
		}

		// If we have a search_query merge it with the selected terms.
		if ( isset( $_GET['search'] ) ) {
			$search_query     = sanitize_text_field( wp_unslash( $_GET['search'] ) );
			$selected_terms[] = array(
				'taxonomy' => 'search',
				'slug'     => $search_query,
				'label'    => $search_query,
			);
		}

		get_template_part(
			'components/archive-pagination/archive-filter-selected',
			null,
			array(
				'terms' => $selected_terms,
			)
		);
	}

	/**
	 * Render archive meta info (showing x - y of z results).
	 *
	 * Ex: Showing 1-8 of 24
	 */
	public function render_meta_info() {
		$current_page   = $this->current_page;
		$posts_per_page = $this->per_page;

		$start_post = ( $current_page - 1 ) * $posts_per_page + 1;
		$end_post   = min( $current_page * $posts_per_page, $this->query->found_posts );

		$total_posts = $this->query->found_posts;

		get_template_part(
			'components/archive-pagination/archive-filter-meta-info',
			null,
			array(
				'start_post'  => $start_post,
				'end_post'    => $end_post,
				'total_posts' => $total_posts,
			)
		);
	}

	/**
	 * Render the search input.
	 */
	public function render_search() {
		$search_query = isset( $_GET['search'] ) ? sanitize_text_field( wp_unslash( $_GET['search'] ) ) : '';

		get_template_part(
			'components/archive-pagination/archive-filter-search',
			null,
			array(
				'search_query' => $search_query,
			)
		);
	}

	/**
	 * Render the static filter provided, filter_type can be select or checkbox (TODO).
	 *
	 * @param string $filter_type The type of filter to render.
	 * @param string $static_filter the filter array. Ex:
	 * array(
	 *  'filter_slug' => "order_by",
	 *  'filter_label' => "Order by",
	 *  'filter_options' => array(
	 *      array(
	 *          'slug' => 'date_DESC',
	 *          'label' => 'Date',
	 *      ),
	 *      array(
	 *          'slug' => 'title_ASC',
	 *          'label' => 'Title',
	 *      )
	 *  )
	 * ).
	 */
	public function render_static_filter( $filter_type = 'select', $static_filter = array() ) {
		if ( ! is_array( $static_filter ) || empty( $static_filter ) || ! isset( $static_filter['filter_slug'] ) || ! isset( $static_filter['filter_label'] ) || ! isset( $static_filter['filter_options'] ) ) {
			return;
		}

		get_template_part(
			'components/archive-pagination/archive-filter-' . $filter_type,
			null,
			$static_filter
		);
	}
}
