<?php
/**
 * Class to keep track of hooks and filters tied into ACF plugin
 *
 * @package WCB
 */

namespace WCB\Plugins;

defined( 'ABSPATH' ) || die();

/**
 * This class adds support to the ACF plugin.
 */
class AcfSettings {
	/**
	 * AcfSettings construct function
	 */
	public function __construct() {
		add_filter( 'acf/settings/save_json', array( $this, 'save_local_json' ) );
		add_filter( 'acf/settings/load_json', array( $this, 'load_local_json' ) );
		add_action( 'acf/init', array( $this, 'google_maps_api_key' ) );
		add_filter( 'acf/blocks/wrap_frontend_innerblocks', array( $this, 'acf_should_wrap_innerblocks' ), 10 );
	}

	/**
	 * Save acf json path
	 */
	public function save_local_json() {
		return get_stylesheet_directory() . '/acf-json';
	}

	/**
	 * Load acf json path
	 *
	 * @param string $paths acf jsons path.
	 */
	public function load_local_json( $paths ) {
		// remove original path (optional).
		unset( $paths[0] );

		// append path.
		$paths[] = get_stylesheet_directory() . '/acf-json';

		// return.
		return $paths;
	}

	/**
	 * ACF Map Backend APIKEY
	 */
	public function google_maps_api_key() {
		acf_update_setting( 'google_api_key', get_field( 'api_key', 'option' ) );
	}

	/**
	 * Function to remove a the wrapper from the innerBlocks. Only in the front end. Back end editor will still have the wrapper.
	 */
	public function acf_should_wrap_innerblocks() {
		return false;
	}
}
