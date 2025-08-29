<?php
/**
 * DisableComments
 *
 * @package WCB
 */

namespace WCB\Setup;

defined( 'ABSPATH' ) || die();

/**
 * This class disable comments all over the page
 */
class DisableComments {
	/**
	 * DisableComments construct function
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'disable_comments_post_types_support' ) );
		// Close comments on the front-end.
		add_filter( 'comments_open', '__return_false', 20, 2 );
		add_filter( 'pings_open', '__return_false', 20, 2 );
		// Hide existing comments.
		add_filter( 'comments_array', '__return_empty_array', 10, 2 );
		add_action( 'admin_menu', array( $this, 'remove_comments_admin' ) );
		add_action( 'init', array( $this, 'remove_comments_admin_bar' ) );
		add_action( 'wp_before_admin_bar_render', array( $this, 'comment_bar_render' ) );
	}

	/**
	 * This will disable support for comments and trackbacks in post types
	 */
	public function disable_comments_post_types_support() {
		// Redirect any user trying to access comments page.
		global $pagenow;

		if ( 'edit-comments.php' === $pagenow ) {
			wp_safe_redirect( admin_url() );
			exit;
		}

		// Remove comments metabox from dashboard.
		remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );

		// Disable support for comments and trackbacks in post types.
		foreach ( get_post_types() as $post_type ) {
			if ( post_type_supports( $post_type, 'comments' ) ) {
				remove_post_type_support( $post_type, 'comments' );
				remove_post_type_support( $post_type, 'trackbacks' );
			}
		}
	}

	/**
	 * Remove comments page in menu
	 */
	public function remove_comments_admin() {
		remove_menu_page( 'edit-comments.php' );
	}

	/**
	 * Remove comments links from admin bar
	 */
	public function remove_comments_admin_bar() {
		if ( is_admin_bar_showing() ) {
			remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
		}
	}

	/**
	 * Remove comments icon from toolbar
	 */
	public function comment_bar_render() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'comments' );
	}
}
