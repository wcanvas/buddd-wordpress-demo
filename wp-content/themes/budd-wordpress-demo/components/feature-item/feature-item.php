<?php
/**
 * Component: FeatureItem
 *
 * @package WCB
 *
 * @param array $args {
 *     @type string $icon_url      The URL of the icon image.
 *     @type string $icon_alt      The alt text for the icon image.
 *     @type string $title         The title of the feature.
 *     @type string $description   The description of the feature.
 *     @type string $class         Additional CSS classes.
 * }
 */

defined( 'ABSPATH' ) || die(); // Ensure that this line do not experiment modifications. it should be always the same.

use WCB\Functionalities\Component;

// Set default values
$args += array(
	'icon_url'    => '',
	'icon_alt'    => '',
	'title'       => '',
	'description' => '',
	'class'       => '',
);

// Validate and sanitize arguments
$args['icon_url']    = is_string( $args['icon_url'] ) ? $args['icon_url'] : '';
$args['icon_alt']    = is_string( $args['icon_alt'] ) ? $args['icon_alt'] : '';
$args['title']       = is_string( $args['title'] ) ? $args['title'] : '';
$args['description'] = is_string( $args['description'] ) ? $args['description'] : '';
$args['class']       = is_string( $args['class'] ) ? $args['class'] : '';

?>

<div class="wcb-flex wcb-flex-col wcb-items-center wcb-text-center <?php echo esc_attr( $args['class'] ); ?>">
	<?php
	if ( ! empty( $args['icon_url'] ) ) {
		$wcb_image = new Component(
			'image',
			array(
				'src'   => $args['icon_url'],
				'alt'   => $args['icon_alt'],
				'class' => 'wcb-h-20 wcb-w-20 wcb-mb-6 wcb-object-contain',
			)
		);
		$wcb_image->render();
	}
	?>
	<?php if ( ! empty( $args['title'] ) ) : ?>
		<h3 class="wcb-font-font-4 wcb-font-bold wcb-text-xl wcb-leading-lh-3 wcb-text-color-30 wcb-mb-4">
			<?php echo esc_html( $args['title'] ); ?>
		</h3>
	<?php endif; ?>
	<?php if ( ! empty( $args['description'] ) ) : ?>
		<p class="wcb-font-font-4 wcb-font-normal wcb-text-base wcb-leading-snug wcb-text-color-30">
			<?php echo esc_html( $args['description'] ); ?>
		</p>
	<?php endif; ?>
</div>