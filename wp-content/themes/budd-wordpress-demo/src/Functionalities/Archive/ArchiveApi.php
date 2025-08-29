<?php
/**
 * Archive API
 *
 * @package WCB
 */

namespace WCB\Functionalities\Archive;

defined( 'ABSPATH' ) || die();

use WP_REST_Server;

/**
 * This class add a brand new paginator with extra features
 */
class ArchiveApi {

	/**
	 * Api rest endpoints prefix
	 *
	 * @var string
	 */
	private $api_prefix;

	/**
	 * Registers an endpoint
	 */
	public function __construct() {
		$this->api_prefix = 'wcb-archive/v1';
		add_action( 'rest_api_init', array( $this, 'register_endpoint' ) );
	}

	/**
	 * Creates an endpoint
	 */
	public function register_endpoint() {
		register_rest_route(
			$this->api_prefix,
			'paged-posts',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'response_endpoint' ),
				'permission_callback' => '__return_true',
				'args'                => $this->get_rest_endpoint_paged_post_args(),
			)
		);
	}


	/**
	 * Returns args accepted by the endpoint
	 */
	private function get_rest_endpoint_paged_post_args() {
		return array(
			'post_type' => array(
				'validate_callback' => function ( $param, $request, $key ) {
					return is_string( $param ) && post_type_exists( $param ) && is_post_type_viewable( $param ) && 'attachment' !== $param;
				},
			),
			'component' => array(
				'validate_callback' => function ( $param, $request, $key ) {
					return is_string( $param ) && 'card' === $param; // Make sure the component is the right one.
				},
			),
			'cpage'     => array(
				'validate_callback' => function ( $param, $request, $key ) {
					return is_numeric( $param ) && $param >= 1;
				},
			),
			'filters'   => array(
				'validate_callback' => function ( $param, $request, $key ) {
					return is_array( $param );
				},
			),
		);
	}

	/**
	 * Gets triggered when a get request is made to 'post-powers/v1'
	 *
	 * @param array $data The data sent by the client.
	 * @return array $responseArray containing posts, status, etc.
	 */
	public function response_endpoint( $data ) {
		// Create query using ArchiveQuery.
		$archive_query = new ArchiveQuery( $data['post_type'], $data, 'api' );
		$full_posts    = $archive_query->query();

		// Parse php posts into txt.
		$post_data = '';

		while ( $full_posts->have_posts() ) {
			$full_posts->the_post();

			ob_start();

			get_template_part(
				"components/{$data['component']}/{$data['component']}",
				null,
				array(
					'title'    => get_the_title(),
					'excerpt'  => get_the_excerpt(),
					'image_id' => get_post_thumbnail_id(),
					'cta_text' => 'Explore more',
					'cta_link' => get_the_permalink(),
				)
			);
			$post_data .= ob_get_contents();

			ob_end_clean();
		}

		wp_reset_postdata();

		// Response.
		if ( '' !== $post_data ) {
			return array(
				'status'       => true,
				'posts'        => $post_data,
				'total_pages'  => intval( $full_posts->max_num_pages ),
				'current_page' => intval( $data['cpage'] ),
				'total_items'  => intval( $full_posts->found_posts ),
			);
		} else {
			return array(
				'status'        => false,
				'error_message' => 'No posts for the selected query.',
			);
		}
	}
}
