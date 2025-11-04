<?php
/**
 * Component: AccordionItem
 *
 * @package WCB
 *
 * @param array $args {
 *     @type string $title   The visible title of the accordion item.
 *     @type string $content The content that is revealed when the item is expanded.
 *     @type string $variant The style variant of the accordion. 'default' or 'dark'.
 *     @type string $class   Additional CSS classes.
 * }
 */

defined( 'ABSPATH' ) || die(); // Ensure that this line do not experiment modifications. it should be always the same.

use WCB\Functionalities\Component;

// Set default values
$args += array(
	'title'   => '',
	'content' => '',
	'variant' => 'default',
	'class'   => '',
);

// Validate and sanitize arguments to prevent critical errors
$args['title']   = is_string( $args['title'] ) ? $args['title'] : '';
$args['content'] = is_string( $args['content'] ) ? $args['content'] : '';
$args['variant'] = is_string( $args['variant'] ) ? $args['variant'] : 'default';
$args['class']   = is_string( $args['class'] ) ? $args['class'] : '';

if ( empty( $args['title'] ) ) {
	return;
}

// --- Style Definitions ---
$is_dark       = 'dark' === $args['variant'];
$border_color  = $is_dark ? 'wcb-border-color-31' : 'wcb-border-color-5';
$title_classes = 'wcb-font-font-4 wcb-font-bold wcb-text-lg wcb-leading-lh-6 ' . ( $is_dark ? 'wcb-text-color-30' : 'wcb-text-color-3' );
$content_classes = 'wcb-pb-6 wcb-font-font-4 wcb-font-normal wcb-text-base wcb-leading-lh-9 ' . ( $is_dark ? 'wcb-text-color-30' : 'wcb-text-color-3' );

// --- Icon URLs ---
$icon_dark_url   = 'https://ai-bot-v2.s3.us-east-2.amazonaws.com/generated/68348df8a2283e7a16d47b9b/68b1d530c444f85250015526/figma/25e4d94e-8f62-810d-99f3-fc827c1520df/1733:727.svg';
$icon_light_open = 'https://ai-bot-v2.s3.us-east-2.amazonaws.com/generated/68348df8a2283e7a16d47b9b/68b1d530c444f85250015526/figma/25e4d94e-8f62-8137-a00e-f37a2d139535/1732:280.svg';
$icon_light_closed = 'https://ai-bot-v2.s3.us-east-2.amazonaws.com/generated/68348df8a2283e7a16d47b9b/68b1d530c444f85250015526/figma/25e4d94e-8f62-8137-a00e-f37a2d139535/1732:289.svg';
?>

<div class="js-accordion-item wcb-w-full wcb-border-b <?php echo esc_attr( $border_color ); ?> <?php echo esc_attr( $args['class'] ); ?>">
	<button
		class="js-accordion-trigger wcb-w-full wcb-flex wcb-justify-between wcb-items-center wcb-text-left wcb-py-5 wcb-cursor-pointer wcb-bg-transparent"
		aria-expanded="false"
	>
		<span class="<?php echo esc_attr( $title_classes ); ?>">
			<?php echo esc_html( $args['title'] ); ?>
		</span>
		
		<?php if ( $is_dark ) : ?>
			<img
				src="<?php echo esc_url( $icon_dark_url ); ?>"
				alt="<?php esc_attr_e( 'Chevron icon', 'wcanvas-boilerplate' ); ?>"
				class="js-accordion-icon wcb-w-6 wcb-h-6"
			/>
		<?php else : ?>
			<span class="wcb-w-6 wcb-h-6">
				<img
					src="<?php echo esc_url( $icon_light_open ); ?>"
					alt="<?php esc_attr_e( 'Close icon', 'wcanvas-boilerplate' ); ?>"
					class="js-accordion-icon-open wcb-w-full wcb-h-full"
					style="display: none;"
				/>
				<img
					src="<?php echo esc_url( $icon_light_closed ); ?>"
					alt="<?php esc_attr_e( 'Open icon', 'wcanvas-boilerplate' ); ?>"
					class="js-accordion-icon-closed wcb-w-full wcb-h-full"
				/>
			</span>
		<?php endif; ?>

	</button>
	<div class="js-accordion-content wcb-h-0 wcb-overflow-hidden wcb-bg-transparent">
		<div class="<?php echo esc_attr( $content_classes ); ?>">
			<?php echo wp_kses_post( $args['content'] ); ?>
		</div>
	</div>
</div>