<?php
/**
 * Blocks Setup.
 *
 * @package WCB
 */

namespace WCB\Block;

defined( 'ABSPATH' ) || die();

use WCB\Functionalities\GetSvg;

/**
 * Blocks Setup class.
 */
class Blocks {

	/**
	 * Construct method
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_custom_blocks' ) );
		add_action( 'block_categories_all', array( $this, 'register_block_categories' ), 10, 2 );
	}

	/**
	 * Automatically register every block inside blocks folder
	 */
	public function register_custom_blocks() {

		if ( file_exists( get_template_directory() . '/assets/build/block-manifest.php' ) ) {
			require_once get_template_directory() . '/assets/build/block-manifest.php';

			wp_register_block_types_from_metadata_collection(
				get_template_directory() . '/assets/build/blocks',
				get_template_directory() . '/assets/build/block-manifest.php'
			);
		}
	}

	/**
	 * Register custom blocks categories
	 * https://developer.wordpress.org/block-editor/reference-guides/filters/block-filters/#managing-block-categories
	 *
	 * @param array $categories array of categories.
	 */
	public function register_block_categories( $categories ) {

		array_unshift(
			$categories,
			array(
				'slug'  => 'wcb-blocks-navbars',
				'title' => __( 'Navbars', 'wcanvas-boilerplate' ),
			),
			array(
				'slug'  => 'wcb-blocks-footers',
				'title' => __( 'Footers', 'wcanvas-boilerplate' ),
			),
			array(
				'slug'  => 'wcb-blocks-heros',
				'title' => __( 'Heros', 'wcanvas-boilerplate' ),
			),
			array(
				'slug'  => 'wcb-blocks-testimonials',
				'title' => __( 'Testimonials', 'wcanvas-boilerplate' ),
			),
			array(
				'slug'  => 'wcb-blocks-tabbers',
				'title' => __( 'Tabbers', 'wcanvas-boilerplate' ),
			),
			array(
				'slug'  => 'wcb-blocks-team-members',
				'title' => __( 'Team Members', 'wcanvas-boilerplate' ),
			),
			array(
				'slug'  => 'wcb-blocks-cards',
				'title' => __( 'Cards', 'wcanvas-boilerplate' ),
			),
			array(
				'slug'  => 'wcb-blocks-logos',
				'title' => __( 'Logo Stripes', 'wcanvas-boilerplate' ),
			),
			array(
				'slug'  => 'wcb-blocks-left-right',
				'title' => __( 'Left Rights', 'wcanvas-boilerplate' ),
			),
			array(
				'slug'  => 'wcb-blocks-showcase',
				'title' => __( 'Post Showcase', 'wcanvas-boilerplate' ),
			),
			array(
				'slug'  => 'wcb-blocks-accordion',
				'title' => __( 'Accordion', 'wcanvas-boilerplate' ),
			),
			array(
				'slug'  => 'wcb-blocks-stats',
				'title' => __( 'Stats', 'wcanvas-boilerplate' ),
			),
			array(
				'slug'  => 'wcb-blocks-pricing',
				'title' => __( 'Pricing', 'wcanvas-boilerplate' ),
			),
			array(
				'slug'  => 'wcb-blocks-lists',
				'title' => __( 'Lists', 'wcanvas-boilerplate' ),
			),
			array(
				'slug'  => 'wcb-blocks-notification-bar',
				'title' => __( 'Notification Bars', 'wcanvas-boilerplate' ),
			),
			array(
				'slug'  => 'wcb-blocks-banners',
				'title' => __( 'Banners', 'wcanvas-boilerplate' ),
			),
			array(
				'slug'  => 'wcb-blocks-open-positions',
				'title' => __( 'Open Positions', 'wcanvas-boilerplate' ),
			),
			array(
				'slug'  => 'wcb-blocks-case-study',
				'title' => __( 'Case Study', 'wcanvas-boilerplate' ),
			),
			array(
				'slug'  => 'wcb-blocks',
				'title' => __( 'General Blocks', 'wcanvas-boilerplate' ),
			),
		);

		return $categories;
	}
}
