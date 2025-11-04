<?php
/**
 * Component: Logo Cloud
 *
 * @package WCB
 *
 * @param array $args {
 *     @type string $title The title of the logo cloud.
 *     @type array  $logos Array of logos. Each logo is an array with 'src' and 'alt'.
 *     @type string $class Additional CSS classes for the component.
 * }
 */

defined( 'ABSPATH' ) || die();

use WCB\Functionalities\Component;

// Set default values.
$args += array(
	'title' => '',
	'logos' => array(),
	'class' => '',
);

// Validate and sanitize arguments.
$args['title'] = is_string( $args['title'] ) ? $args['title'] : '';
$args['logos'] = is_array( $args['logos'] ) ? $args['logos'] : array();
$args['class'] = is_string( $args['class'] ) ? $args['class'] : '';

?>

<div class="wcb-flex wcb-flex-col wcb-items-center wcb-gap-y-8 <?php echo esc_attr( $args['class'] ); ?>">
	<?php if ( ! empty( $args['title'] ) ) : ?>
		<p class="wcb-logo-cloud-title wcb-font-medium wcb-text-xl wcb-leading-lh-5 wcb-text-color-30 wcb-max-w-md wcb-text-center">
			<?php echo esc_html( $args['title'] ); ?>
		</p>
	<?php endif; ?>

	<?php if ( ! empty( $args['logos'] ) ) : ?>
		<div class="wcb-flex wcb-flex-wrap wcb-justify-center wcb-items-center wcb-gap-x-8 md:wcb-gap-x-12 wcb-gap-y-8">
			<?php foreach ( $args['logos'] as $logo ) : ?>
				<?php
				// Validate logo data.
				$logo_src = isset( $logo['src'] ) && is_string( $logo['src'] ) ? $logo['src'] : '';
				$logo_alt = isset( $logo['alt'] ) && is_string( $logo['alt'] ) ? $logo['alt'] : '';

				if ( ! empty( $logo_src ) ) {
					$wcb_image = new Component(
						'image',
						array(
							'src'   => $logo_src,
							'alt'   => $logo_alt,
							'class' => 'wcb-h-8 wcb-w-auto wcb-object-contain',
						)
					);
					$wcb_image->render();
				}
				?>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>