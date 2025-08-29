<?php
/**
 * This class adds support for WebP image conversion and responsive picture tags.
 *
 * @package WCB
 */

namespace WCB\Functionalities;

defined( 'ABSPATH' ) || die();

/**
 * This class adds support for WebP image conversion and responsive picture tags.
 */
class WebpImages {

	/**
	 * Construct the class.
	 */
	public function __construct() {
		// Constructor logic, if needed.
	}

	/**
	 * Render the responsive image.
	 *
	 * @param int   $img_id Image ID.
	 * @param array $args   Array to set your image.
	 */
	public function print_image( $img_id, $args ) {
		// No image ID provided, handle accordingly (e.g., show a placeholder).
		if ( ! $img_id && is_admin() ) {
			$this->render_placeholder( $args );
			return; // No image ID provided and we are in admin page, render a placeholder image.
		} elseif ( ! $img_id ) {
			return; // No image ID provided, do nothing.
		}

		$img_tag     = wp_get_attachment_image( $img_id, $args['desktopSize'], false );
		$desktop_url = wp_get_attachment_image_src( $img_id, $args['desktopSize'] );

		if ( ! $desktop_url ) {
			return;
		}

		$desktop_url = $desktop_url[0];

		// No image attachment found, handle accordingly (e.g., show a placeholder).
		if ( ! $img_tag && is_admin() ) {
			$this->render_placeholder( $args );
			return; // No image attachment found and we are in admin page, render a placeholder image.
		} elseif ( ! $img_tag ) {
			return; // No image attachment found, do nothing.
		}

		$desktop = $this->get_image_url( $img_id, $args['desktopSize'] );
		$mobile  = $this->get_image_url( $img_id, $args['mobileSize'] );

		// Add loading attribute.
		$lazyload = $args['lazyLoad'] ? 'lazy' : 'eager';
		$img_tag  = wp_get_attachment_image( $img_id, $args['desktopSize'], false, array( 'loading' => $lazyload ) );

		// Replace urls.
		$img_tag    = str_replace( 'src="' . $desktop_url . '"', 'src="' . $desktop . '"', $img_tag );
		$class_name = esc_attr( $args['class'] );

		$this->render_picture_tag( $class_name, $desktop, $mobile, $img_tag );
	}

	/**
	 * Render the HTML for the picture tag.
	 *
	 * @param string $class_name    CSS class.
	 * @param string $desktop       Desktop image URL.
	 * @param string $mobile        Mobile image URL.
	 * @param string $img_tag       Image tag HTML.
	 */
	private function render_picture_tag( $class_name, $desktop, $mobile, $img_tag ) {
		$allowed_html = array(
			'picture' => array(
				'class' => array(),
				'style' => array(
					'background-image' => array(),
					'background-size'  => array(),
					'height'           => array(),
				),
			),
			'source'  => array(
				'srcset' => array(),
				'media'  => array(),
			),
			'img'     => array(
				'src'      => array(),
				'class'    => array(),
				'alt'      => array(),
				'decoding' => array(),
				'loading'  => array(),
				'width'    => array(),
				'height'   => array(),
				'sizes'    => array(),
			),
		);

		echo wp_kses(
			"<picture class='{$class_name}'>
                <source srcset='$mobile' media='(max-width: 768px)'>
                <source srcset='$desktop'>
                $img_tag
            </picture>",
			$allowed_html
		);
	}

	/**
	 * Render two responsive images for desktop and mobile in the same picture tag.
	 *
	 * @param int   $desktop_img_id Image ID for desktop.
	 * @param int   $mobile_img_id  Image ID for mobile.
	 * @param array $args           Additional arguments for rendering.
	 */
	public function print_dual_image( $desktop_img_id, $mobile_img_id, $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'desktop_size' => 'large',
				'mobile_size'  => 'medium',
				'class'        => 'dual-image',
				'media_query'  => '(max-width: 768px)',
				'lazy_load'    => true,
			)
		);

		// Fill one image ID based on the other if one is missing.
		if ( ! $desktop_img_id && $mobile_img_id ) {
			$desktop_img_id = $mobile_img_id;
		} elseif ( ! $mobile_img_id && $desktop_img_id ) {
			$mobile_img_id = $desktop_img_id;
		}

		// No Image IDs provided, handle accordingly (e.g., show a placeholder).
		if ( is_admin() && ( ! $desktop_img_id || ! $mobile_img_id ) ) {
			$this->render_placeholder( $args );
			return;
		} elseif ( ! $desktop_img_id && ! $mobile_img_id ) {
			return;
		}

		$desktop_img_src = esc_url( wp_get_attachment_image_src( $desktop_img_id, $args['desktop_size'] )[0] );
		$mobile_img_src  = esc_url( wp_get_attachment_image_src( $mobile_img_id, $args['mobile_size'] )[0] );

		// Image attachment not found, handle accordingly (e.g., show a placeholder).
		if ( ( ! $desktop_img_src || ! $mobile_img_src ) && is_admin() ) {
			$this->render_placeholder( $args );
			return; // Image attachment not found and we are in admin page, render a placeholder image.
		} elseif ( ! $desktop_img_src || ! $mobile_img_src ) {
			return; // Image attachment not found, do nothing.
		}

		$lazyload = $args['lazy_load'] ? 'lazy' : 'eager';

		// Merge additional arguments with default attributes.
		$attributes = array_merge(
			array(
				'loading' => $lazyload,
				'class'   => 'dual-image',
			),
			$args
		);

		$desktop_img_tag = wp_get_attachment_image( $desktop_img_id, $args['desktop_size'], false, $attributes );

		$desktop = $this->get_image_url( $desktop_img_id, $args['desktop_size'] );
		$mobile  = $this->get_image_url( $mobile_img_id, $args['mobile_size'] );

		$desktop_img_tag = str_replace( 'src="' . $desktop_img_src . '"', 'src="' . $desktop . '"', $desktop_img_tag );
		$class           = esc_attr( $attributes['class'] );

		$this->render_dual_image_tag( $class, $desktop, $mobile, $desktop_img_tag, $args['media_query'] );
	}

	/**
	 * Render the HTML for the dual image tag.
	 *
	 * @param string $class_name        CSS class.
	 * @param string $desktop           Desktop image URL.
	 * @param string $mobile            Mobile image URL.
	 * @param string $desktop_img_tag   Desktop image tag HTML.
	 * @param string $media_query Custom media query string (e.g., "(max-width: 768px)").
	 */
	private function render_dual_image_tag( $class_name, $desktop, $mobile, $desktop_img_tag, $media_query ) {
		$allowed_html = array(
			'picture' => array(
				'class' => array(),
				'style' => array(
					'background-image' => array(),
					'background-size'  => array(),
					'height'           => array(),
				),
			),
			'source'  => array(
				'srcset' => array(),
				'media'  => array(),
			),
			'img'     => array(
				'src'      => array(),
				'class'    => array(),
				'alt'      => array(),
				'decoding' => array(),
				'loading'  => array(),
				'width'    => array(),
				'height'   => array(),
				'sizes'    => array(),
			),
		);

		echo wp_kses(
			"<picture class='{$class_name}'>
				<source srcset='{$mobile}' media='{$media_query}'>
				<source srcset='{$desktop}'>
				{$desktop_img_tag}
        	</picture>",
			$allowed_html
		);
	}

	/**
	 * Get the image URL with .webp extension if Webp Express plugin is enabled.
	 *
	 * @param int   $img_id   Image ID.
	 * @param array $img_size Image size.
	 *
	 * @return string Image URL.
	 */
	private function get_image_url( $img_id, $img_size ) {
		$file = wp_get_attachment_image_src( $img_id, $img_size )[0];

		return esc_url( $file );
	}

	/**
	 * Render a placeholder image.
	 *
	 * @param array $args Array of image arguments.
	 */
	private function render_placeholder( array $args ) {
		$class        = esc_attr( $args['class'] );
		$placeholder  = get_template_directory_uri() . '/assets/media/images/cards-placeholder.png';
		$allowed_html = array(
			'picture' => array( 'class' => array() ),
			'source'  => array(
				'srcset' => array(),
				'media'  => array(),
			),
			'img'     => array(
				'src'      => array(),
				'class'    => array(),
				'alt'      => array(),
				'decoding' => array(),
				'loading'  => array(),
				'width'    => array(),
				'height'   => array(),
				'sizes'    => array(),
			),
		);
		echo wp_kses(
			"<picture class='image-not-found {$class}'>
                <img src='{$placeholder}' alt='Placeholder Image'>
            </picture>",
			$allowed_html
		);
	}
}
