<?php
/**
 * This class includes methods for handling image related tasks.
 *
 * @package WCB
 */

namespace WCB\Setup;

defined( 'ABSPATH' ) || die();

/**
 * This class includes methods for handling image related tasks.
 */
class ImageHandler {

	/**
	 * ImageHandler constructor.
	 */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'add_image_sizes' ) );
	}

	/** Add image sizes to the theme */
	public function add_image_sizes() {
		add_image_size( 'medium-large', 768, 9999 );
		add_image_size( 'xl', 1500, 9999 );
		add_image_size( 'xxl', 2000, 9999 );
	}
}
