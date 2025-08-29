<?php
/**
 * Component: FeatureCard
 *
 * @package WCB
 *
 * @param array $args {
 *     @type array  $image        The image data (url, alt).
 *     @type string $title        The title of the feature.
 *     @type string $description  The descriptive text for the feature.
 *     @type string $class        Additional CSS classes.
 * }
 */

defined( 'ABSPATH' ) || die(); // Ensure that this line do not experiment modifications. it should be always the same.

use WCB\Functionalities\Component;

// Set default values
$args += array(
	'image'       => array(),
	'title'       => '',
	'description' => '',
	'class'       => '',
);

// Validate and sanitize arguments to prevent critical errors
$args['image']       = is_array( $args['image'] ) ? $args['image'] : array();
$args['title']       = is_string( $args['title'] ) ? $args['title'] : '';
$args['description'] = is_string( $args['description'] ) ? $args['description'] : '';
$args['class']       = is_string( $args['class'] ) ? $args['class'] : '';

$wcb_image_url = isset( $args['image']['url'] ) && is_string( $args['image']['url'] ) ? $args['image']['url'] : '';
$wcb_image_alt = isset( $args['image']['alt'] ) && is_string( $args['image']['alt'] ) ? $args['image']['alt'] : $args['title'];
?>

<div class="wcb-text-left <?php echo esc_attr( $args['class'] ); ?>">
	<?php if ( ! empty( $wcb_image_url ) ) : ?>
		<div class="wcb-overflow-hidden wcb-rounded-radius-3">
			<?php
			$wcb_image_component = new Component(
				'image',
				array(
					'src'   => $wcb_image_url,
					'alt'   => $wcb_image_alt,
					'class' => 'wcb-w-full wcb-h-auto wcb-object-cover wcb-aspect-[4/5] wcb-transition-transform wcb-duration-300 wcb-ease-in-out hover:wcb-scale-105',
				)
			);
			$wcb_image_component->render();
			?>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $args['title'] ) ) : ?>
		<h3 class="wcb-font-font-3 wcb-text-xl wcb-font-medium wcb-text-color-7 wcb-mt-6">
			<?php echo esc_html( $args['title'] ); ?>
		</h3>
	<?php endif; ?>

	<?php if ( ! empty( $args['description'] ) ) : ?>
		<p class="wcb-font-font-3 wcb-text-base wcb-text-color-7 wcb-mt-2 wcb-leading-relaxed">
			<?php echo esc_html( $args['description'] ); ?>
		</p>
	<?php endif; ?>
</div>