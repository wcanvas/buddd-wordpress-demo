<?php
/**
 * Security class file.
 *
 * @package WCB
 */

namespace WCB\Setup;

defined( 'ABSPATH' ) || die();

/**
 * Security class.
 */
class Security {

	/**
	 * Security constructor.
	 * Registers the action to create the 'Editor Extended' role on WordPress initialization.
	 */
	public function __construct() {
		// Hook to create the role when WordPress initializes.
		add_action( 'init', array( $this, 'create_editor_extended_role' ) );

		// Hook to restrict the plugin activity log page to admin users only.
		add_action( 'admin_menu', array( $this, 'restrict_activity_log_page_to_admins' ), 999 );
	}

	/**
	 * Creates the 'Editor Extended' role based on the Editor role.
	 * Adds capabilities to manage plugins (activate/deactivate only), view updates, access health status, theme options, and Gravity Forms.
	 * Also restricts plugin and core updates.
	 *
	 * @return void
	 */
	public function create_editor_extended_role() {
		// Get the capabilities of the Editor role.
		$editor = get_role( 'editor' );

		// Create a new role based on the Editor role.
		add_role( 'editor_extended', 'Editor Extended', $editor->capabilities );

		// Get the newly created role.
		$role = get_role( 'editor_extended' );

		// Capabilities to manage plugins: activate/deactivate, but not update them.
		$role->add_cap( 'activate_plugins' );
		$role->add_cap( 'deactivate_plugins' );
		$role->add_cap( 'update_plugins' ); // Prevent updating plugins.
		$role->add_cap( 'read' ); // Allow viewing updates.

		// Capabilities to view WordPress core updates but not install them.
		$role->add_cap( 'update_core' ); // Allows checking for core updates (view).
		$role->remove_cap( 'install_core' ); // Prevent installing core updates.

		// Access to Health Status.
		$role->add_cap( 'view_site_health_checks' );

		// Access to theme editor options (Gutenberg under Appearance).
		$role->add_cap( 'edit_theme_options' );

		// Access to Gravity Forms (if installed).
		$role->add_cap( 'gravityforms_edit_forms' ); // Allows editing forms.
		$role->add_cap( 'gravityforms_view_entries' ); // Allows viewing form entries.
		$role->add_cap( 'gravityforms_view_updates' ); // Allows viewing available Gravity Forms updates.

		// Allow the user to view the "updates" page, but not perform updates.
		$role->add_cap( 'view_updates' ); // Allows viewing pending updates for plugins and themes.
	}

	/**
	 * Restricts the plugin activity log page so that only administrators can see it.
	 *
	 * @return void
	 */
	public function restrict_activity_log_page_to_admins() {
		$user_data  = wp_get_current_user();
		$user_roles = $user_data->roles;
		// Check if the user is not an administrator.
		if ( ! in_array( 'administrator', $user_roles, true ) ) {
			// Remove the menu item.
			remove_menu_page( 'activity_log_page' );
		}
	}
}
