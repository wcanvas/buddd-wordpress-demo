<?php
/**
 * Component: Link
 *
 * @package WCB
 *
 * @param array $args {
 *     @type string $href  The URL for the link.
 *     @type string $text  The text content of the link.
 *     @type string $class Additional CSS classes.
 * }
 */

defined( 'ABSPATH' ) || die();

// Set default values
$args += array(
	'href'  => '#',
	'text'  => '',
	'class' => '',
);

// Validate and sanitize arguments
$args['href']  = is_string( $args['href'] ) ? $args['href'] : '#';
$args['text']  = is_string( $args['text'] ) ? $args['text'] : '';
$args['class'] = is_string( $args['class'] ) ? $args['class'] : '';

if ( empty( $args['text'] ) ) {
	return;
}
?>

<a href="<?php echo esc_url( $args['href'] ); ?>" class="wcb-text-color-25 wcb-font-font-3 wcb-font-semibold wcb-text-sm wcb-flex wcb-items-center wcb-gap-1.5 wcb-cursor-pointer wcb-no-underline <?php echo esc_attr( $args['class'] ); ?>">
	<?php echo esc_html( $args['text'] ); ?>
	<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M6.33398 1.33301L11.0007 5.99967L6.33398 10.6663M1.00065 5.99967H10.9173" stroke="#A72126" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
	</svg>
</a>