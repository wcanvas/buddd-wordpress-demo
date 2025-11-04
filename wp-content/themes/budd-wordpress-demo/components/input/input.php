<?php
/**
 * Component: Input
 *
 * @package WCB
 *
 * @param array $args {
 *     @type string $type        The input type (e.g., 'text', 'search').
 *     @type string $placeholder The placeholder text.
 *     @type string $class       Additional CSS classes for the wrapper.
 *     @type string $variant     The input style variant ('default', 'underline').
 * }
 */

defined( 'ABSPATH' ) || die();

// Set default values.
$args += array(
	'type'        => 'text',
	'placeholder' => '',
	'class'       => '',
	'variant'     => 'default',
);

// Validate and sanitize arguments.
$args['type']        = is_string( $args['type'] ) ? $args['type'] : 'text';
$args['placeholder'] = is_string( $args['placeholder'] ) ? $args['placeholder'] : '';
$args['class']       = is_string( $args['class'] ) ? $args['class'] : '';
$args['variant']     = is_string( $args['variant'] ) ? $args['variant'] : 'default';

if ( 'underline' === $args['variant'] ) : ?>
	<input
		type="<?php echo esc_attr( $args['type'] ); ?>"
		placeholder="<?php echo esc_attr( $args['placeholder'] ); ?>"
		class="js-search-input wcb-w-full wcb-bg-transparent wcb-border-0 wcb-border-b wcb-border-solid wcb-border-color-32 wcb-pb-3 wcb-text-color-30 placeholder:wcb-text-color-33 wcb-font-font-4 wcb-text-sm focus:wcb-ring-0 focus:wcb-border-color-42 wcb-transition-colors wcb-duration-300 <?php echo esc_attr( $args['class'] ); ?>"
	/>
<?php else : ?>
	<div class="wcb-relative <?php echo esc_attr( $args['class'] ); ?>">
		<input
			type="<?php echo esc_attr( $args['type'] ); ?>"
			placeholder="<?php echo esc_attr( $args['placeholder'] ); ?>"
			class="js-search-input wcb-w-full wcb-bg-color-42 wcb-rounded-full wcb-border-none wcb-py-3 wcb-pl-6 wcb-pr-12 wcb-text-color-3 placeholder:wcb-text-color-3 wcb-font-font-2 wcb-text-base focus:wcb-ring-2 focus:wcb-ring-color-10"
		/>
		<img
			src="https://ai-bot-v2.s3.us-east-2.amazonaws.com/generated/68348df8a2283e7a16d47b9b/68b1d530c444f85250015526/figma/25e4d94e-8f62-81e3-b594-c53f530d6409/1845:136.svg"
			alt="<?php esc_attr_e( 'Search icon', 'wcb' ); ?>"
			class="wcb-absolute wcb-right-5 wcb-top-1/2 -wcb-translate-y-1/2 wcb-w-5 wcb-h-5"
		/>
	</div>
<?php endif; ?>