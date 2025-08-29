<?php
/**
 * Component: Preheading
 *
 * @package WCB
 *
 * @param array $args {
 *     @type string $text      The text content of the preheading.
 *     @type string $class     Additional CSS classes for the component.
 * }
 */

defined( 'ABSPATH' ) || die();

// Set default values.
$args += array(
	'text'  => '',
	'class' => '',
);

// Validate and sanitize arguments.
$args['text']  = is_string( $args['text'] ) ? $args['text'] : '';
$args['class'] = is_string( $args['class'] ) ? $args['class'] : '';

if ( empty( $args['text'] ) ) {
	return;
}

$base_classes = 'wcb-font-font-4 wcb-font-semibold wcb-text-base wcb-leading-lh-9 wcb-text-color-3 wcb-tracking-wider wcb-uppercase';
?>

<p class="<?php echo esc_attr( $base_classes ); ?> <?php echo esc_attr( $args['class'] ); ?>">
	<?php echo esc_html( $args['text'] ); ?>
</p>