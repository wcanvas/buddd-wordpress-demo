<?php
/**
 * Component: ToggleSwitch
 *
 * @package WCB
 *
 * @param array $args {
 *     @type string $monthly_label The label for the monthly option.
 *     @type string $yearly_label  The label for the yearly option.
 *     @type string $class         Additional CSS classes.
 * }
 */

defined( 'ABSPATH' ) || die(); // Ensure that this line do not experiment modifications. it should be always the same.

use WCB\Functionalities\Component;

// Set default values
$args += array(
	'monthly_label' => 'Monthly',
	'yearly_label'  => 'Yearly',
	'class'         => '',
);

// Validate and sanitize arguments to prevent critical errors
$args['monthly_label'] = is_string( $args['monthly_label'] ) ? $args['monthly_label'] : 'Monthly';
$args['yearly_label']  = is_string( $args['yearly_label'] ) ? $args['yearly_label'] : 'Yearly';
$args['class']         = is_string( $args['class'] ) ? $args['class'] : '';

$base_button_classes = 'wcb-px-4 sm:wcb-px-6 wcb-py-2 wcb-text-sm wcb-font-medium wcb-rounded-full wcb-transition-colors wcb-duration-300 wcb-cursor-pointer';
$active_classes      = 'wcb-bg-color-7 wcb-text-color-42';
$inactive_classes    = 'wcb-bg-transparent wcb-text-color-7';
?>

<div class="js-toggle-switch wcb-flex wcb-items-center wcb-bg-color-42 wcb-p-1 wcb-rounded-full wcb-w-fit wcb-font-font-3 <?php echo esc_attr( $args['class'] ); ?>">
	<button class="js-toggle-monthly <?php echo esc_attr( $base_button_classes ); ?> <?php echo esc_attr( $active_classes ); ?>">
		<?php echo esc_html( $args['monthly_label'] ); ?>
	</button>
	<button class="js-toggle-yearly <?php echo esc_attr( $base_button_classes ); ?> <?php echo esc_attr( $inactive_classes ); ?>">
		<?php echo esc_html( $args['yearly_label'] ); ?>
	</button>
</div>