<?php
/**
 * Component: FilterButton
 *
 * @package WCB
 *
 * @param array $args {
 *     @type string $text           The text for the button.
 *     @type bool   $is_active      Whether the button is active.
 *     @type string $data_category  The category slug for the data attribute.
 *     @type bool   $show_close_icon Whether to show the close icon.
 * }
 */

defined( 'ABSPATH' ) || die();

// Set default values.
$args += array(
	'text'            => '',
	'is_active'       => false,
	'data_category'   => '',
	'show_close_icon' => false,
);

// Validate and sanitize arguments.
$args['text']            = is_string( $args['text'] ) ? $args['text'] : '';
$args['is_active']       = is_bool( $args['is_active'] ) ? $args['is_active'] : false;
$args['data_category']   = is_string( $args['data_category'] ) ? $args['data_category'] : '';
$args['show_close_icon'] = is_bool( $args['show_close_icon'] ) ? $args['show_close_icon'] : false;

if ( empty( $args['text'] ) ) {
	return;
}

$base_classes    = 'js-filter-button wcb-font-font-3 wcb-text-xs wcb-leading-tight wcb-rounded-full wcb-px-3 wcb-py-1.5 wcb-transition-colors wcb-duration-300 wcb-cursor-pointer wcb-border wcb-border-solid wcb-flex wcb-items-center wcb-gap-1.5';
$active_classes  = 'wcb-bg-color-15 wcb-text-color-27 wcb-border-color-15';
$inactive_classes = 'wcb-bg-transparent wcb-text-color-3 wcb-border-color-5 hover:wcb-bg-color-7 hover:wcb-text-color-30 hover:wcb-border-color-7';

$button_classes = $base_classes . ' ' . ( $args['is_active'] ? $active_classes : $inactive_classes );
?>

<button
	class="<?php echo esc_attr( $button_classes ); ?>"
	data-category="<?php echo esc_attr( $args['data_category'] ); ?>"
>
	<?php echo esc_html( $args['text'] ); ?>
	<?php if ( $args['show_close_icon'] ) : ?>
		<span class="wcb-opacity-80">
			<svg width="8" height="8" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M11 1L1 11M1 1L11 11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
		</span>
	<?php endif; ?>
</button>