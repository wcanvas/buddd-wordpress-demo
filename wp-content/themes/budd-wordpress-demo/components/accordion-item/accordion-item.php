<?php
/**
 * Component: Accordion Item
 *
 * @package WCB
 *
 * @param array $args {
 *     @type string $title   The visible title of the accordion item.
 *     @type string $content The content that is revealed when the item is expanded.
 *     @type string $class   Additional CSS classes.
 * }
 */

defined( 'ABSPATH' ) || die(); // Ensure that this line do not experiment modifications. it should be always the same.

use WCB\Functionalities\Component;

// Set default values
$args += array(
	'title'   => '',
	'content' => '',
	'class'   => '',
);

// Validate and sanitize arguments to prevent critical errors
$args['title']   = is_string( $args['title'] ) ? $args['title'] : '';
$args['content'] = is_string( $args['content'] ) ? $args['content'] : '';
$args['class']   = is_string( $args['class'] ) ? $args['class'] : '';

if ( empty( $args['title'] ) ) {
	return;
}

// Icon URLs from the original React component
$icon_open_url   = 'https://ai-bot-v2.s3.us-east-2.amazonaws.com/generated/68348df8a2283e7a16d47b9b/68b1d530c444f85250015526/figma/25e4d94e-8f62-8137-a00e-f37a2d139535/1732:280.svg';
$icon_closed_url = 'https://ai-bot-v2.s3.us-east-2.amazonaws.com/generated/68348df8a2283e7a16d47b9b/68b1d530c444f85250015526/figma/25e4d94e-8f62-8137-a00e-f37a2d139535/1732:289.svg';
?>

<div class="js-accordion-item wcb-w-full wcb-border-b wcb-border-color-5 <?php echo esc_attr( $args['class'] ); ?>">
	<button
		class="js-accordion-trigger wcb-w-full wcb-flex wcb-justify-between wcb-items-center wcb-text-left wcb-py-5 wcb-cursor-pointer"
		aria-expanded="false"
	>
		<span class="wcb-font-font-4 wcb-font-bold wcb-text-lg wcb-leading-lh-6 wcb-text-color-3">
			<?php echo esc_html( $args['title'] ); ?>
		</span>
		<span class="wcb-w-6 wcb-h-6">
			<img
				src="<?php echo esc_url( $icon_open_url ); ?>"
				alt="<?php esc_attr_e( 'Close icon', 'wcanvas-boilerplate' ); ?>"
				class="js-accordion-icon-open wcb-w-full wcb-h-full"
			/>
			<img
				src="<?php echo esc_url( $icon_closed_url ); ?>"
				alt="<?php esc_attr_e( 'Open icon', 'wcanvas-boilerplate' ); ?>"
				class="js-accordion-icon-closed wcb-w-full wcb-h-full"
			/>
		</span>
	</button>
	<div class="js-accordion-content wcb-overflow-hidden" style="height: 0;">
		<div class="wcb-pb-6 wcb-font-font-4 wcb-font-normal wcb-text-base wcb-leading-lh-9 wcb-text-color-3">
			<?php echo wp_kses_post( $args['content'] ); ?>
		</div>
	</div>
</div>