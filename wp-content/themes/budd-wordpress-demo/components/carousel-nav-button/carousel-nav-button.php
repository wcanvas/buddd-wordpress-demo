<?php
/**
 * Component: CarouselNavButton
 *
 * @package WCB
 *
 * @param array $args {
 *     @type string $direction 'left' or 'right'.
 *     @type string $class     Additional CSS classes.
 * }
 */

defined( 'ABSPATH' ) || die(); // Ensure that this line do not experiment modifications. it should be always the same.

use WCB\Functionalities\Component;

// Set default values
$args += array(
	'direction' => 'left',
	'class'     => '',
);

// Validate and sanitize arguments to prevent critical errors
$args['direction'] = is_string( $args['direction'] ) && in_array( $args['direction'], array( 'left', 'right' ), true ) ? $args['direction'] : 'left';
$args['class']     = is_string( $args['class'] ) ? $args['class'] : '';

// Set icon and aria-label based on direction
if ( 'left' === $args['direction'] ) {
	$icon_src   = 'https://ai-bot-v2.s3.us-east-2.amazonaws.com/generated/68348df8a2283e7a16d47b9b/68b1d530c444f85250015526/figma/25e4d94e-8f62-81d3-b8c6-e6cdc1e9062f/1845:104.svg';
	$aria_label = __( 'Go to previous slide', 'wcanvas-boilerplate' );
} else {
	$icon_src   = 'https://ai-bot-v2.s3.us-east-2.amazonaws.com/generated/68348df8a2283e7a16d47b9b/68b1d530c444f85250015526/figma/25e4d94e-8f62-81d3-b8c6-e6cdc1e9062f/1845:108.svg';
	$aria_label = __( 'Go to next slide', 'wcanvas-boilerplate' );
}
?>

<button
	class="wcb-bg-transparent wcb-border wcb-border-color-43 wcb-rounded-full wcb-w-12 wcb-h-12 wcb-flex wcb-items-center wcb-justify-center wcb-cursor-pointer wcb-transition-opacity hover:wcb-opacity-80 <?php echo esc_attr( $args['class'] ); ?>"
	aria-label="<?php echo esc_attr( $aria_label ); ?>"
>
	<img src="<?php echo esc_url( $icon_src ); ?>" alt="<?php echo esc_attr( $args['direction'] . ' arrow' ); ?>" class="wcb-w-auto wcb-h-auto" />
</button>