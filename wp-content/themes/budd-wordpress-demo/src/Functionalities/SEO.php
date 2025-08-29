<?php
/**
 * This class add static methods to fix SEO issues easily.
 *
 * @package WCB
 */

namespace WCB\Functionalities;

defined( 'ABSPATH' ) || die();

use WCB\Functionalities\WebpImages;

/**
 * SEO Search engine optimization
 *
 * Contains methods that improve the optimization of the web page.
 */
class SEO {

	/**
	 * This method allow you choose the heading tag (from h1 to h6)
	 * To use it you should call the method and clone de acf "Support: Heading" with these options:
	 * - Display: Group
	 * - Prefix Field Names: True
	 * EG:
	 * SEO::Heading(['field' => 'title', 'class' => 'wcb-title']);
	 *
	 * Also support post_id and option page!
	 * EG:
	 * SEO::Heading(['field' => 'title', 'class' => 'wcb-title', 'option'=> true]);
	 * SEO::Heading(['field' => 'title', 'class' => 'wcb-title', 'post_id'=> 15]);
	 *
	 * ARGS:
	 *
	 * @param array $args array to set your heading
	 * 'field'[string] -> The slug of your acf.
	 * 'class'[string] -> Css class.
	 * 'post_id'[int|null] -> If you need use it with an a post id.
	 * 'option'[bool] -> Brings an options page field if you set it like true.
	 *
	 * @return void
	 */
	public static function heading( $args = array() ) {
		$args += array(
			'field'   => 'title',
			'post_id' => null,
			'class'   => 'wcb-title',
			'option'  => false,
		);

		if ( ! $args['option'] ) {
			$field = get_field( $args['field'], $args['post_id'] ) ? get_field( $args['field'], $args['post_id'] ) : get_sub_field( $args['field'], $args['post_id'] );
		} else {
			$field = get_field( $args['field'], 'option' );
		}
		if ( $field ) {
			$field = array_values( $field );
			// Escape fields.
			$field[0]      = esc_html( $field[0] );
			$field[1]      = esc_html( $field[1] );
			$args['class'] = esc_attr( $args['class'] );

			// Print heading.
			echo wp_kses_post( "<{$field[0]} class='{$args['class']}'>{$field[1]}</{$field[0]}>" );

		}
	}

	/**
	 * This method allow you build more accessible <a> tags
	 * To use it you should call the method and use a Link acf type:
	 * EG:
	 * SEO::Button(['field' => 'button', 'class' => 'wcb-btn']);
	 *
	 * Also support post_id and option page!
	 * EG:
	 * SEO::Button(['field' => 'button', 'class' => 'wcb-btn', 'option'=> true]);
	 * SEO::Button(['field' => 'button', 'class' => 'wcb-btn', 'post_id'=> 15]);
	 *
	 * ARGS:
	 *
	 * @param array $args array to set your btn
	 * 'field'[string] -> The slug of your acf.
	 * 'class'[string] -> Css class.
	 * 'post_id'[int|null] -> If you need use it with an a post id.
	 * 'option'[bool] -> Brings an options page field if you set it like true.
	 * 'style'[string] -> The button you plan to use. See switch stmt below.
	 * @return void
	 */
	public static function button( $args = array() ) {
		$args += array(
			'field'   => 'button',
			'post_id' => null,
			'class'   => 'wcb-btn',
			'option'  => false,
			'style'   => 'primary',
		);

		if ( ! $args['option'] ) {
			$field = get_field( $args['field'], $args['post_id'] ) ? get_field( $args['field'], $args['post_id'] ) : get_sub_field( $args['field'], $args['post_id'] );
		} else {
			$field = get_field( $args['field'], 'option' );
		}

		if ( $field ) {
			get_template_part(
				'components/button/button',
				null,
				array(
					'url'    => $field['url'],
					'target' => $field['target'],
					'title'  => $field['title'],
					'class'  => $args['class'],
					'style'  => $args['style'],
				)
			);
		}
	}

	/**
	 * This method allow you to print adaptative picture tags.
	 * Also allows webp images in combination with EWWW plugin (https://ewww.io/).
	 *
	 * ARGS:
	 *
	 * @param array $args array to set your image.
	 * - 'imageId' [int] -> Image id (could be set from acf)
	 * - 'desktopSize' [string] -> Image size on desktop.
	 *      Reference native/custom WordPress resolutions:
	 *          thumbnail (150 x 150 pixels)
	 *          medium (maximum 300 x 300 pixels)
	 *          medium-large (maximum 768 x N pixels)
	 *          large (maximum 1024 x 1024 pixels)
	 *          xl (maximum 1500 x N pixels)
	 *          xxl (maximum 2000 x N pixels)
	 *          full (the original size of the uploaded image, avoid use it)
	 * - 'mobileSize' [string] -> Image size on mobile
	 * - 'class' [string] -> Picture class name.
	 * - 'lazyLoad' [bool] -> Activate or not lazy load.
	 */
	public static function image( $args = array() ) {
		$args += array(
			'imageId'     => '',
			'desktopSize' => 'large',
			'mobileSize'  => 'medium',
			'class'       => 'image',
			'lazyLoad'    => true,
		);

		$image = new WebpImages();
		$image->print_image( $args['imageId'], $args );
	}

	/**
	 * This method allow you to print adaptative thumbnail picture tags.
	 * Also allows webp images in combination with EWWW plugin (https://ewww.io/).
	 *
	 * ARGS:
	 *
	 * @param array $args array to set your thumbnail.
	 *
	 * - 'postId' [int|null] -> Post Id
	 * - 'desktopSize' [string] -> Image size on desktop
	 *      Reference native/custom WordPress resolutions:
	 *          thumbnail (150 x 150 pixels)
	 *          medium (maximum 300 x 300 pixels)
	 *          large (maximum 1024 x 1024 pixels)
	 *          xl (maximum 1500 x N pixels)
	 *          xxl (maximum 2000 x N pixels)
	 *          full (the original size of the uploaded image, avoid use it)
	 * - 'mobileSize' [string] -> Image size on mobile
	 * - 'class' [string] -> Picture class name.
	 * - 'lazyLoad' [bool] -> Activate or not lazy load.
	 */
	public static function thumbnail( $args = array() ) {
		$args += array(
			'postId'      => null,
			'desktopSize' => 'large',
			'mobileSize'  => 'medium',
			'class'       => 'thumbnail',
			'lazyLoad'    => true,
		);

		$img_id    = get_post_thumbnail_id( $args['postId'] );
		$thumbnail = new WebpImages();
		$thumbnail->print_image( $img_id, $args );
	}

	/**
	 * This method allows you to print adaptive dual-image picture tags.
	 * Also allows webp images in combination with EWWW plugin (https://ewww.io/).
	 *
	 * ARGS:
	 *
	 * @param array $args array to set your dual image.
	 * - 'desktop_image_id' [int] -> Image ID for desktop.
	 * - 'mobile_image_id' [int] -> Image ID for mobile.
	 * - 'desktop_size' [string] -> Image size on desktop.
	 *      Reference native/custom WordPress resolutions:
	 *          thumbnail (150 x 150 pixels)
	 *          medium (maximum 300 x 300 pixels)
	 *          large (maximum 1024 x 1024 pixels)
	 *          xl (maximum 1500 x N pixels)
	 *          xxl (maximum 2000 x N pixels)
	 *          full (the original size of the uploaded image, avoid use it)
	 * - 'mobile_size' [string] -> Image size on mobile.
	 * - 'class' [string] -> Picture class name.
	 * - 'media_query' [string] -> Custom media query string (e.g., "(max-width: 768px)").
	 * - 'lazy_load' [bool] -> Enable lazy loading of images.
	 */
	public static function dual_image( $args = array() ) {
		$args += array(
			'desktop_image_id' => '',
			'mobile_image_id'  => '',
			'desktop_size'     => 'large',
			'mobile_size'      => 'medium',
			'class'            => 'dual-image',
			'media_query'      => '(max-width: 768px)',
			'lazy_load'        => true,
		);

		$dual_image = new WebpImages();
		$dual_image->print_dual_image(
			$args['desktop_image_id'],
			$args['mobile_image_id'],
			$args
		);
	}


	/**
	 * Displays a modal with the specified arguments.
	 *
	 * @param array $args An array of arguments for displaying the modal.
	 *
	 *     @type string $modal_template_path         The path to the modal template file. Default is 'components/modal/modal'.
	 *     @type array  $modal_template_args         The arguments for the modal template file. Default is an array with a key of modal_name (required) with an empty string.
	 *     @type string $modal_content_template_path The path to the modal content template file. Default is an empty string.
	 *     @type array  $modal_content_template_args The arguments for the modal content template file. Default is an empty array.
	 *
	 * @return void
	 */
	public static function modal( $args = array() ) {
		$args += array(
			'modal_template_path'         => 'components/modal/modal',
			'modal_template_args'         => array( 'modal_name' => '' ),
			'modal_content_template_path' => '',
			'modal_content_template_args' => array(),
		);

		// Check if modal_template_path exists.
		if ( ! file_exists( get_template_directory() . '/' . $args['modal_template_path'] . '.php' ) ) {
			return;
		}

		// Ceck if modal_template_args array key 'modal_name' is a string and is not empty.
		if ( ! is_string( $args['modal_template_args']['modal_name'] ) || empty( $args['modal_template_args']['modal_name'] ) ) {
			return;
		}

		get_template_part(
			$args['modal_template_path'],
			null,
			array(
				...$args['modal_template_args'],
				'modal_content' => ( function () use ( $args ) {

					if ( ! file_exists( get_template_directory() . '/' . $args['modal_content_template_path'] . '.php' ) ) {
						return;
					}

					ob_start();

						get_template_part( $args['modal_content_template_path'], null, $args['modal_content_template_args'] );

					return ob_get_clean();
				} )(),
			)
		);
	}
}
