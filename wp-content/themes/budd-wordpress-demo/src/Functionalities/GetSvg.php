<?php
/**
 * Print svg from code.
 *
 * @package WCB
 */

namespace WCB\Functionalities;

defined( 'ABSPATH' ) || die();

/**
 * Print svg from code.
 */
class GetSvg {
	/**
	 * Default base path for SVG icons.
	 */
	const DEFAULT_SVG_PATH = 'assets/media/icons/';

	/**
	 * Renders an SVG file by its name.
	 *
	 * @param string $svg_name The name of the SVG file without extension.
	 * @param string $custom_path Optional. Custom path to look for SVGs. Defaults to DEFAULT_SVG_PATH.
	 * @param bool   $echo_svg Optional. Whether to echo the SVG content. Defaults to true.
	 * @return void
	 */
	public static function render( string $svg_name, string $custom_path = '', bool $echo_svg = true ) {
		// Sanitize the SVG name.
		$svg_name = sanitize_file_name( $svg_name );

		// Determine the path to use.
		$base_path = ! empty( $custom_path ) ? trailingslashit( $custom_path ) : self::DEFAULT_SVG_PATH;
		$full_path = get_template_directory() . '/' . $base_path . $svg_name . '.svg';

		// Check if file exists.
		if ( ! file_exists( $full_path ) ) {
			if ( true === WP_DEBUG ) {
				printf( '<!-- SVG file not found -->' );
			}
			return;
		}

		// Get and echo the SVG content.
		$svg_content = file_get_contents( $full_path );
		if ( false === $svg_content ) {
			if ( true === WP_DEBUG ) {
				printf( '<!-- Failed to read SVG file -->' );
			}
			return;
		}

		if ( true === $echo_svg ) {
			echo wp_kses(
				$svg_content,
				array(
					'svg'   => array(
						'class'           => true,
						'aria-hidden'     => true,
						'aria-labelledby' => true,
						'role'            => true,
						'xmlns'           => true,
						'width'           => true,
						'height'          => true,
						'viewbox'         => true,
						'style'           => true,
						'fill'            => true,
						'stroke'          => true,

					),
					'g'     => array( 'fill' => true ),
					'title' => array( 'title' => true ),
					'path'  => array(
						'd'         => true,
						'fill'      => true,
						'stroke'    => true,
						'clip-rule' => true,
						'fill-rule' => true,
					),
				)
			);
		} else {
			return $svg_content;
		}
	}
}
