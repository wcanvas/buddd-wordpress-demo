<?php
/**
 * Load posts using a custom REST API endpoint.
 *
 * @package WCB
 */

namespace WCB\Functionalities;

defined( 'ABSPATH' ) || die();

/**
 * Class to load more posts.
 */
class SimplePagination {
	/**
	 * SimplePagination construct function.
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_custom_endpoint' ) );
	}

	/**
	 * Register custom endpoint.
	 */
	public function register_custom_endpoint() {
		register_rest_route(
			'post-powers/v1',
			'simple-posts',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'load_more_posts' ),
				'permission_callback' => '__return_true', // Adjust permission callback as needed.
				'args'                => array(
					'post_type'      => array(
						'validate_callback' => function ( $param ) {
							return is_string( $param );
						},
					),
					'posts_per_page' => array(
						'validate_callback' => function ( $param ) {
							return is_numeric( $param );
						},
					),
					'page'           => array(
						'validate_callback' => function ( $param ) {
							return is_numeric( $param );
						},
					),
					'component'      => array(
						'validate_callback' => function ( $param ) {
							return is_string( $param );
						},
					),
				),
			)
		);
	}

	/**
	 * Callback function for loading more post via the custom REST API endpoint.
	 *
	 * @param WP_REST_Request $request The REST request object.
	 * @return array {
	 *     HTML content of the loaded posts.
	 *     @type string posts HTML content of the loaded posts.
	 *     @type bool hasMorePosts True if there are more posts to load, false otherwise.
	 * }
	 */
	public function load_more_posts( $request ) {
		$post_type      = $request->get_param( 'post_type' );
		$posts_per_page = $request->get_param( 'posts_per_page' );
		$page           = $request->get_param( 'page' );
		$component      = $request->get_param( 'component' );

		$args = array(
			'post_type'      => $post_type,
			'posts_per_page' => $posts_per_page,
			'paged'          => $page,
			'order'          => 'ASC',
		);

		$posts_query = new \WP_Query( $args );

		$post_data      = '';
		$has_more_posts = false;

		if ( $posts_query->have_posts() ) {
			while ( $posts_query->have_posts() ) {
				$posts_query->the_post();
				global $post;
				ob_start();
				get_template_part( $component, false, self::prepare_args( $post ) );
				$post_data .= ob_get_contents();
				ob_end_clean();
			}

			// Check if there are more posts to load.
			$has_more_posts = $posts_query->max_num_pages > $page;

			wp_reset_postdata();
		}

		return array(
			'posts'        => $post_data,
			'hasMorePosts' => $has_more_posts,
		);
	}


	/**
	 * SimplePagination::print(array $args).
	 *
	 * @param array $data args to use.
	 *
	 * $args:
	 * - post_type: Post type register name (post by default)[string].
	 * - post_per_page: Number of posts to load per page (2 by default)[int].
	 * - component: Template Part path (components/card-v2/card-v2 by default)[string].
	 * - load_btn_class: Class for the load more button (wcb-btn by default)[string].
	 */
	public static function print( $data = array() ) {

		$data += array(
			'post_type'      => 'post',
			'post_per_page'  => 2,
			'component'      => 'components/card-v2/card-v2',
			'load_btn_class' => 'wcb-btn',
		);

		$post_type      = $data['post_type'];
		$post_per_page  = $data['post_per_page'];
		$component      = $data['component'];
		$load_btn_class = $data['load_btn_class'];

		// Initial posts query for the first page.
		$args = array(
			'post_type'      => $post_type,
			'posts_per_page' => $post_per_page,
			'paged'          => 1,
			'order'          => 'ASC',
		);

		$css_pagination = implode(
			' ',
			array(
				'js-simple-pagination',
				'wcb-flex-col-center',
				'wcb-gap-10',
			)
		);

		$css_pagination_container = implode(
			' ',
			array(
				'js-simple-pagination__container',
				'wcb-flex-col-center',
				'wcb-gap-6',
			)
		);

		$posts = new \WP_Query( $args );
		if ( $posts->have_posts() ) {
			$html = '<div class="' . $css_pagination . '" post-type="' . esc_attr( $post_type ) . '"component="' . esc_attr( $component ) . '"post-per-page="' . esc_attr( $post_per_page ) . '"><div class="' . $css_pagination_container . '">';
			while ( $posts->have_posts() ) {
				$posts->the_post();
				global $post;
				ob_start();
				get_template_part( $component, false, self::prepare_args( $post ) );
				$html .= ob_get_contents();
				ob_end_clean();
			}
			wp_reset_postdata();
			$html .= '</div><button class="' . $load_btn_class . ' load-more-button"><span>Load More</span></button></div>';

			echo $html; //phpcs:ignore
		}
	}

	/**
	 * Prepare args for the template part.
	 *
	 * @param WP_Post $post Post object.
	 *
	 * @return array
	 */
	public static function prepare_args( $post ) {
		return array(
			'link'     => get_the_permalink( $post->ID ),
			'title'    => get_the_title( $post->ID ),
			'excerpt'  => has_excerpt( $post->ID ) ? get_the_excerpt( $post->id ) : wp_trim_words( get_the_content( $post->ID ), 25 ),
			'image_id' => get_post_thumbnail_id( $post->ID ),
		);
	}
}
