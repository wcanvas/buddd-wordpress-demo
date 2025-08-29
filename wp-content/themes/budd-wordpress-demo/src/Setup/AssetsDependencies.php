<?php
/**
 * Assets Dependencies
 *
 * @package WCB
 */

namespace WCB\Setup;

defined( 'ABSPATH' ) || die();

/**
 * Adds third-party resources to our theme.
 */
class AssetsDependencies {
	/**
	 * Construct method.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'scripts' ), 0 );
		add_action( 'init', array( $this, 'register_theme_custom_components_styles' ) );
	}

	/**
	 * Register scripts/styles into our theme.
	 */
	public function scripts() {
		$dependencies_path = get_template_directory() . '/dependencies.json';
		if ( file_exists( $dependencies_path ) ) {
			$dependencies = json_decode( file_get_contents( $dependencies_path ), true );

			foreach ( $dependencies as $handle => $dependency ) {
				if ( ! empty( $dependency['jsPath'] ) ) {
					$js_url  = get_template_directory_uri() . '/' . $dependency['jsPath'];
					$js_path = get_template_directory() . '/' . $dependency['jsPath'];

					wp_register_script(
						$handle,
						$js_url,
						null,
						filemtime( $js_path ),
						array(
							'strategy' => 'defer',
						)
					);
				}

				if ( ! empty( $dependency['cssPath'] ) ) {
					$css_url  = get_template_directory_uri() . '/' . $dependency['cssPath'];
					$css_path = get_template_directory() . '/' . $dependency['cssPath'];
					wp_register_style(
						$handle,
						$css_url,
						null,
						filemtime( $css_path )
					);
				}
			}
		}
	}

	/**
	 * Register all Block components dependencies.
	 */
	public function register_theme_custom_components_styles() {
		// Define the base path to the components directory.
		$components_dir = get_template_directory() . '/assets/build/components';

		// Use glob to get all directories inside the components folder.
		$component_folders = glob( $components_dir . '/*', GLOB_ONLYDIR );

		// Loop through each component folder.
		foreach ( $component_folders as $folder ) {
			// Get the base name of the directory (the component name).
			$component_name = basename( $folder );

			// Define the paths to style.scss and editor.scss.
			$style_path        = $folder . '/style-frontend.css';
			$editor_style_path = $folder . '/index.css';

			/**
			 * If file exist register on the frontend and backend. (backend handle is different, so we can register as a dependency of the editor style if exists). This way we can use one handle for both editor and frontend styles on the block's block.json file.
			 *  */
			if ( file_exists( $style_path ) ) {
				if ( ! is_admin() ) {
					$style_handle        = 'wcb-' . $component_name . '-component';
					$style_uri           = get_template_directory_uri() . '/assets/build/components/' . $component_name . '/style-frontend.css';
					$absolute_style_path = get_template_directory() . '/assets/build/components/' . $component_name . '/style-frontend.css';
					wp_register_style( $style_handle, $style_uri, array(), filemtime( $absolute_style_path ) );
					wp_style_add_data( $style_handle, 'path', $absolute_style_path );

					continue; // Bail early if we are on the frontend.
				} else {
					// If there is a backend file change the name to make it a dependency of the backend file.
					$change_name_handle = file_exists( $editor_style_path ) ? '-front' : '';

					$style_handle        = 'wcb-' . $component_name . '-component' . $change_name_handle;
					$style_uri           = get_template_directory_uri() . '/assets/build/components/' . $component_name . '/style-frontend.css';
					$absolute_style_path = get_template_directory() . '/assets/build/components/' . $component_name . '/style-frontend.css';
					wp_register_style( $style_handle, $style_uri, array(), filemtime( $absolute_style_path ) );
					wp_style_add_data( $style_handle, 'path', $absolute_style_path );
				}
			}

			if ( is_admin() && file_exists( $editor_style_path ) ) {
				$render_dependencies = file_exists( $style_path ) ? array( 'wcb-' . $component_name . '-component-front' ) : array();

				$editor_style_handle        = 'wcb-' . $component_name . '-component';
				$editor_style_uri           = get_template_directory_uri() . '/assets/build/components/' . $component_name . '/index.css';
				$absolute_editor_style_path = get_template_directory() . '/assets/build/components/' . $component_name . '/index.css';
				wp_register_style( $editor_style_handle, $editor_style_uri, $render_dependencies, filemtime( $absolute_editor_style_path ) );
				wp_style_add_data( $editor_style_handle, 'path', $absolute_editor_style_path );
			}
		}
	}
}
