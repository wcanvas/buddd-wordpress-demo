<?php
/**
 * Component: Link
 *
 * @package WCB
 *
 * @param array $args {
 *     @type string $href    The URL for the link.
 *     @type string $text    The text content of the link.
 *     @type string $variant Variant of the component ('default' or 'dark').
 *     @type string $class   Additional CSS classes.
 * }
 */

defined( 'ABSPATH' ) || die(); // Ensure that this line do not experiment modifications. it should be always the same.

// Set default values
$args += array(
	'href'    => '#',
	'text'    => '',
	'variant' => 'default',
	'class'   => '',
);

// Validate and sanitize arguments to prevent critical errors
$args['href']    = is_string( $args['href'] ) ? $args['href'] : '#';
$args['text']    = is_string( $args['text'] ) ? $args['text'] : '';
$args['variant'] = is_string( $args['variant'] ) ? $args['variant'] : 'default';
$args['class']   = is_string( $args['class'] ) ? $args['class'] : '';

// Conditional logic for variants/states
$text_color  = 'wcb-text-color-25';
$arrow_color = '#A72126';
$font        = 'wcb-font-font-3';
$text_size   = 'wcb-text-sm';
$gap         = 'wcb-gap-1.5';

if ( 'dark' === $args['variant'] ) {
	$text_color  = 'wcb-text-color-3';
	$arrow_color = '#232E26';
	$font        = 'wcb-font-font-4';
	$text_size   = 'wcb-text-base';
	$gap         = 'wcb-gap-2';
}

if ( empty( $args['text'] ) ) {
	return;
}
?>

<a href="<?php echo esc_url( $args['href'] ); ?>" class="wcb-no-underline wcb-group wcb-cursor-pointer wcb-flex wcb-items-center wcb-font-semibold <?php echo esc_attr( $font ); ?> <?php echo esc_attr( $text_size ); ?> <?php echo esc_attr( $text_color ); ?> <?php echo esc_attr( $gap ); ?> <?php echo esc_attr( $args['class'] ); ?>">
	<span><?php echo esc_html( $args['text'] ); ?></span>
	<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg" class="wcb-w-4 wcb-h-4 wcb-transition-transform wcb-duration-300 group-hover:wcb-translate-x-1">
		<path d="M6.33398 1.33301L11.0007 5.99967L6.33398 10.6663M1.00065 5.99967H10.9173" stroke="<?php echo esc_attr( $arrow_color ); ?>" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
	</svg>
</a>