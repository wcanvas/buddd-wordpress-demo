<?php
/**
 * Component: Image
 *
 * @package WCB
 *
 * @param array $args {
 *     @type string $src      The URL of the image.
 *     @type string $alt      The alt text for the image.
 *     @type string $class    Additional CSS classes for the image.
 * }
 */

defined( 'ABSPATH' ) || die(); // Ensure that this line do not experiment modifications. it should be always the same.

// Set default values
$args += array(
	'src'   => '',
	'alt'   => '',
	'class' => '',
);

// Validate and sanitize arguments to prevent critical errors
$args['src']   = is_string( $args['src'] ) ? $args['src'] : '';
$args['alt']   = is_string( $args['alt'] ) ? $args['alt'] : '';
$args['class'] = is_string( $args['class'] ) ? $args['class'] : '';

if ( empty( $args['src'] ) ) {
	return;
}
?>

<img
	src="<?php echo esc_url( $args['src'] ); ?>"
	alt="<?php echo esc_attr( $args['alt'] ); ?>"
	class="<?php echo esc_attr( $args['class'] ); ?>"
/>