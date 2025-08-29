<?php
/**
 * Component: TextLink
 *
 * @package WCB
 *
 * @param array $args {
 *     @type string $href    The URL the link points to.
 *     @type string $text    The text content of the link.
 *     @type string $class   Additional CSS classes.
 * }
 */

defined( 'ABSPATH' ) || die(); // Ensure that this line do not experiment modifications. it should be always the same.

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

$wcb_base_classes   = 'wcb-no-underline wcb-cursor-pointer wcb-transition-colors wcb-duration-300';
$wcb_default_styles = 'wcb-font-font-3 wcb-text-base wcb-text-color-3 hover:wcb-text-color-7';

$wcb_final_classes = $wcb_base_classes . ' ' . ( ! empty( $args['class'] ) ? $args['class'] : $wcb_default_styles );

?>
<a href="<?php echo esc_url( $args['href'] ); ?>" class="<?php echo esc_attr( $wcb_final_classes ); ?>">
	<?php echo esc_html( $args['text'] ); ?>
</a>