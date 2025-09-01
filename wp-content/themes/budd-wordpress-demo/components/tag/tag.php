<?php
/**
 * Component: Tag
 *
 * @package WCB
 *
 * @param array $args {
 *     @type string $text      The text content of the tag.
 *     @type string $variant   Variant of the component ('default' or 'gray').
 *     @type string $class     Additional CSS classes.
 * }
 */

defined( 'ABSPATH' ) || die(); // Ensure that this line do not experiment modifications. it should be always the same.

// Set default values
$args += array(
	'text'    => '',
	'variant' => 'default',
	'class'   => '',
);

// Validate and sanitize arguments to prevent critical errors
$args['text']    = is_string( $args['text'] ) ? $args['text'] : '';
$args['variant'] = is_string( $args['variant'] ) ? $args['variant'] : 'default';
$args['class']   = is_string( $args['class'] ) ? $args['class'] : '';

// Conditional logic for variants/states
switch ( $args['variant'] ) {
	case 'gray':
		$variant_classes = 'wcb-bg-color-35 wcb-text-color-3';
		break;
	default:
		$variant_classes = 'wcb-bg-color-30 wcb-text-color-14';
		break;
}

if ( empty( $args['text'] ) ) {
	return;
}
?>

<span class="wcb-inline-block wcb-font-font-4 wcb-font-medium wcb-text-sm wcb-px-3 wcb-py-1 wcb-rounded-md <?php echo esc_attr( $variant_classes ); ?> <?php echo esc_attr( $args['class'] ); ?>">
	<?php echo esc_html( $args['text'] ); ?>
</span>