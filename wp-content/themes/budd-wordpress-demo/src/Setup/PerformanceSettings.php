<?php
/**
 * Class to keep track of hooks and filters for performance.
 *
 * @package WCB
 */

namespace WCB\Setup;

defined( 'ABSPATH' ) || die();

/**
 * Class to keep track of hooks and filters for performance.
 */
class PerformanceSettings {
	/**
	 * PerformanceSettings construct function
	 */
	public function __construct() {
		add_action( 'wp_footer', array( $this, 'disable_wp_embed' ) );
		$this->disable_emojis();
		add_filter( 'script_loader_tag', array( $this, 'defer_parsing_of_js' ), 10 );
	}

	/**
	 * Disable wp-embed
	 */
	public function disable_wp_embed() {
		wp_deregister_script( 'wp-embed' );
	}

	/**
	 * Disable emojis
	 */
	public function disable_emojis() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
	}

	/**
	 * Deferring scripts
	 *
	 * @param string $url of the js.
	 * @return string
	 */
	public function defer_parsing_of_js( $url ) {
		if ( is_admin() ) {
			return $url; // don't break WP Admin.
		}

		if ( false === strpos( $url, '.js' ) ) {
			return $url;
		}

		if ( strpos( $url, 'jquery.js' ) ) {
			return $url;
		}

		if ( strpos( $url, 'assets/build' ) ) {
			return str_replace( ' src', ' defer src', $url );
		}

		return $url;
	}
}
