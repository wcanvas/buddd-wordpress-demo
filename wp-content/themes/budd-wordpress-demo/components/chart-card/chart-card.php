<?php
/**
 * Component: Chart Card
 *
 * @package WCB
 *
 * @param array $args {
 *     @type string $icon_url       URL for the main icon.
 *     @type string $icon_alt       Alt text for the main icon.
 *     @type string $title          Card title.
 *     @type string $value          The main value/stat to display.
 *     @type string $value_icon_url URL for the value icon (e.g., trend arrow).
 *     @type string $value_icon_alt Alt text for the value icon.
 *     @type string $subtitle       Subtitle text below the value.
 *     @type string $children       HTML content for the children (the chart image).
 *     @type string $class          Additional CSS classes.
 * }
 */

defined( 'ABSPATH' ) || die();

use WCB\Functionalities\Component;

// Set default values
$args += array(
	'icon_url'       => '',
	'icon_alt'       => '',
	'title'          => '',
	'value'          => '',
	'value_icon_url' => '',
	'value_icon_alt' => '',
	'subtitle'       => '',
	'children'       => '',
	'class'          => '',
);

// Validate and sanitize arguments
$args['icon_url']       = is_string( $args['icon_url'] ) ? $args['icon_url'] : '';
$args['icon_alt']       = is_string( $args['icon_alt'] ) ? $args['icon_alt'] : '';
$args['title']          = is_string( $args['title'] ) ? $args['title'] : '';
$args['value']          = is_string( $args['value'] ) ? $args['value'] : '';
$args['value_icon_url'] = is_string( $args['value_icon_url'] ) ? $args['value_icon_url'] : '';
$args['value_icon_alt'] = is_string( $args['value_icon_alt'] ) ? $args['value_icon_alt'] : '';
$args['subtitle']       = is_string( $args['subtitle'] ) ? $args['subtitle'] : '';
$args['children']       = is_string( $args['children'] ) ? $args['children'] : '';
$args['class']          = is_string( $args['class'] ) ? $args['class'] : '';
?>

<div class="wcb-bg-color-42 wcb-rounded-radius-3 wcb-p-6 wcb-shadow-lg <?php echo esc_attr( $args['class'] ); ?>">
	<div class="wcb-flex wcb-flex-wrap wcb-justify-between wcb-items-start wcb-gap-4 wcb-mb-6">
		<div class="wcb-flex wcb-items-center wcb-gap-x-3">
			<div class="wcb-flex-shrink-0 wcb-w-10 wcb-h-10 wcb-rounded-full wcb-bg-color-10/10 wcb-flex wcb-items-center wcb-justify-center">
				<?php
				if ( ! empty( $args['icon_url'] ) ) {
					$wcb_icon = new Component(
						'image',
						array(
							'src'   => $args['icon_url'],
							'alt'   => $args['icon_alt'],
							'class' => 'wcb-w-5 wcb-h-5',
						)
					);
					$wcb_icon->render();
				}
				?>
			</div>
			<p class="wcb-font-font-3 wcb-font-medium wcb-text-base wcb-text-color-16"><?php echo esc_html( $args['title'] ); ?></p>
		</div>
		<div class="wcb-text-right wcb-flex-grow sm:wcb-flex-grow-0">
			<div class="wcb-flex wcb-items-center wcb-justify-end wcb-gap-x-1">
				<?php
				if ( ! empty( $args['value_icon_url'] ) ) {
					$wcb_value_icon = new Component(
						'image',
						array(
							'src'   => $args['value_icon_url'],
							'alt'   => $args['value_icon_alt'],
							'class' => 'wcb-w-5 wcb-h-5',
						)
					);
					$wcb_value_icon->render();
				}
				?>
				<p class="wcb-font-font-3 wcb-font-semibold wcb-text-2xl wcb-text-color-16"><?php echo esc_html( $args['value'] ); ?></p>
			</div>
			<p class="wcb-font-font-3 wcb-text-xs wcb-text-color-16 wcb-opacity-70 wcb-mt-1"><?php echo esc_html( $args['subtitle'] ); ?></p>
		</div>
	</div>
	<div>
		<?php echo wp_kses_post( $args['children'] ); ?>
	</div>
</div>