<?php
/**
 * Component: Tag
 *
 * @package WCB
 *
 * @param array $args {
 *     @type string $text  The text content of the tag.
 *     @type string $class Additional CSS classes.
 * }
 */

defined( 'ABSPATH' ) || die();

// Set default values
$args += array(
	'text'  => '',
	'class' => '',
);

// Validate and sanitize arguments
$args['text']  = is_string( $args['text'] ) ? $args['text'] : '';
$args['class'] = is_string( $args['class'] ) ? $args['class'] : '';

if ( empty( $args['text'] ) ) {
	return;
}
?>

<span class="wcb-inline-block wcb-bg-color-30 wcb-text-color-14 wcb-font-font-3 wcb-text-xs wcb-font-medium wcb-px-3 wcb-py-1 wcb-rounded-md <?php echo esc_attr( $args['class'] ); ?>">
	<?php echo esc_html( $args['text'] ); ?>
</span>