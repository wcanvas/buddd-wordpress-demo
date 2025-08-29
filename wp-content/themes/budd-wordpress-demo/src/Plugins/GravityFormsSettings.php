<?php
/**
 * Gravity Forms Settings
 *
 * @package WCB
 */

namespace WCB\Plugins;

defined( 'ABSPATH' ) || die();

/**
 * This class adds config to Gravity forms.
 */
class GravityFormsSettings {
	/**
	 * GravityFormsSettings construct function
	 */
	public function __construct() {
		add_filter( 'gform_confirmation_anchor', '__return_false' );
	}
}
