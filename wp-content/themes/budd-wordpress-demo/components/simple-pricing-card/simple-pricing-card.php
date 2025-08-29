<?php
/**
 * Component: SimplePricingCard
 *
 * @package WCB
 *
 * @param array $args {
 *     @type string $plan_name        The name of the plan.
 *     @type string $price            The price of the plan.
 *     @type string $period           The billing period.
 *     @type string $description      A short description of the plan.
 *     @type array  $button           The button link array.
 *     @type string $class            Additional CSS classes.
 * }
 */

defined( 'ABSPATH' ) || die(); // Ensure that this line do not experiment modifications. it should be always the same.

use WCB\Functionalities\Component;

// Set default values
$args += array(
	'plan_name'   => '',
	'price'       => '',
	'period'      => '',
	'description' => '',
	'button'      => array(),
	'class'       => '',
);

// Validate and sanitize arguments to prevent critical errors
$args['plan_name']   = is_string( $args['plan_name'] ) ? $args['plan_name'] : '';
$args['price']       = is_string( $args['price'] ) ? $args['price'] : '';
$args['period']      = is_string( $args['period'] ) ? $args['period'] : '';
$args['description'] = is_string( $args['description'] ) ? $args['description'] : '';
$args['button']      = is_array( $args['button'] ) ? $args['button'] : array();
$args['class']       = is_string( $args['class'] ) ? $args['class'] : '';

$wcb_button_title  = isset( $args['button']['title'] ) && is_string( $args['button']['title'] ) ? $args['button']['title'] : '';
$wcb_button_href   = isset( $args['button']['url'] ) && is_string( $args['button']['url'] ) ? $args['button']['url'] : '#';
$wcb_button_target = isset( $args['button']['target'] ) && is_string( $args['button']['target'] ) ? $args['button']['target'] : '';
?>

<div class="wcb-bg-color-42 wcb-p-6 md:wcb-p-8 wcb-text-center wcb-flex wcb-flex-col <?php echo esc_attr( $args['class'] ); ?>">
	<?php if ( ! empty( $args['plan_name'] ) ) : ?>
		<h3 class="wcb-text-xl wcb-font-semibold wcb-text-color-7"><?php echo esc_html( $args['plan_name'] ); ?></h3>
	<?php endif; ?>

	<div class="wcb-mt-4">
		<?php if ( ! empty( $args['price'] ) ) : ?>
			<span class="wcb-text-3xl md:wcb-text-size-1 wcb-font-bold wcb-text-color-7 wcb-leading-lh-1"><?php echo esc_html( $args['price'] ); ?></span>
		<?php endif; ?>
		<?php if ( ! empty( $args['period'] ) ) : ?>
			<p class="wcb-text-base wcb-text-color-3 wcb-mt-1"><?php echo esc_html( $args['period'] ); ?></p>
		<?php endif; ?>
	</div>

	<?php if ( ! empty( $args['description'] ) ) : ?>
		<p class="wcb-mt-6 wcb-text-base wcb-text-color-3 wcb-flex-grow"><?php echo esc_html( $args['description'] ); ?></p>
	<?php endif; ?>

	<div class="wcb-mt-8">
		<?php
		if ( ! empty( $wcb_button_title ) ) {
			$wcb_button = new Component(
				'button',
				array(
					'title'   => $wcb_button_title,
					'href'    => $wcb_button_href,
					'target'  => $wcb_button_target,
					'variant' => 'primary',
				)
			);
			$wcb_button->render();
		}
		?>
	</div>
</div>