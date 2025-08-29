<?php
/**
 * Component: PricingCard
 *
 * @package WCB
 *
 * @param array $args {
 *     @type array  $icon           The icon image data.
 *     @type string $plan_name      The name of the pricing plan.
 *     @type string $monthly_price  The monthly price.
 *     @type string $yearly_price   The yearly price.
 *     @type string $includes_label The label for the features list.
 *     @type array  $features       An array of features.
 *     @type array  $button         The button link data.
 *     @type string $class          Additional CSS classes.
 * }
 */

defined( 'ABSPATH' ) || die(); // Ensure that this line do not experiment modifications. it should be always the same.

use WCB\Functionalities\Component;

// Set default values
$args += array(
	'icon'           => array(),
	'plan_name'      => '',
	'monthly_price'  => '',
	'yearly_price'   => '',
	'includes_label' => '',
	'features'       => array(),
	'button'         => array(),
	'class'          => '',
);

// Validate and sanitize arguments to prevent critical errors
$args['icon']           = is_array( $args['icon'] ) ? $args['icon'] : array();
$args['plan_name']      = is_string( $args['plan_name'] ) ? $args['plan_name'] : '';
$args['monthly_price']  = is_string( $args['monthly_price'] ) ? $args['monthly_price'] : '';
$args['yearly_price']   = is_string( $args['yearly_price'] ) ? $args['yearly_price'] : '';
$args['includes_label'] = is_string( $args['includes_label'] ) ? $args['includes_label'] : '';
$args['features']       = is_array( $args['features'] ) ? $args['features'] : array();
$args['button']         = is_array( $args['button'] ) ? $args['button'] : array();
$args['class']          = is_string( $args['class'] ) ? $args['class'] : '';

$wcb_show_icon = ! empty( $args['icon']['url'] );

if ( ! function_exists( 'wcb_pricing_card_checkmark_icon' ) ) {
	/**
	 * Renders the checkmark SVG icon.
	 */
	function wcb_pricing_card_checkmark_icon() {
		return '<svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14.6668 1.33301L5.50016 10.4997L1.3335 6.33301" stroke="#232E26" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
	}
}
?>

<div class="wcb-relative wcb-bg-color-42 wcb-rounded-radius-5 wcb-p-6 md:wcb-p-8 lg:wcb-p-10 wcb-flex wcb-flex-col wcb-h-full <?php echo esc_attr( $args['class'] ); ?>">
	<?php if ( $wcb_show_icon ) : ?>
		<img src="<?php echo esc_url( $args['icon']['url'] ); ?>"
			alt="<?php echo esc_attr( $args['icon']['alt'] ); ?>"
			class="wcb-absolute wcb-top-6 wcb-right-6 md:wcb-top-8 md:wcb-right-8 wcb-w-8 wcb-h-8" />
	<?php endif; ?>

	<div class="wcb-flex-grow">
		<?php if ( ! empty( $args['plan_name'] ) ) : ?>
			<p class="wcb-font-font-3 wcb-text-base wcb-text-color-7"><?php echo esc_html( $args['plan_name'] ); ?></p>
		<?php endif; ?>

		<?php if ( ! empty( $args['monthly_price'] ) ) : ?>
			<p class="js-price wcb-font-font-1 wcb-text-3xl md:wcb-text-size-1 wcb-font-bold wcb-text-color-7 wcb-mt-2"
				data-monthly-price="<?php echo esc_attr( $args['monthly_price'] ); ?>"
				data-yearly-price="<?php echo esc_attr( $args['yearly_price'] ); ?>">
				<?php echo esc_html( $args['monthly_price'] ); ?>
			</p>
		<?php endif; ?>

		<hr class="wcb-border-t wcb-border-color-5 wcb-my-6 md:wcb-my-8" />

		<?php if ( ! empty( $args['includes_label'] ) ) : ?>
			<p class="wcb-font-font-3 wcb-text-sm wcb-text-color-7"><?php echo esc_html( $args['includes_label'] ); ?></p>
		<?php endif; ?>

		<?php if ( ! empty( $args['features'] ) ) : ?>
			<ul class="wcb-space-y-4 wcb-mt-4 wcb-list-none wcb-p-0">
				<?php foreach ( $args['features'] as $feature_item ) : ?>
					<?php $wcb_feature_text = is_array( $feature_item ) && isset( $feature_item['feature'] ) && is_string( $feature_item['feature'] ) ? $feature_item['feature'] : ''; ?>
					<?php if ( ! empty( $wcb_feature_text ) ) : ?>
						<li class="wcb-flex wcb-items-start wcb-font-font-3 wcb-text-base wcb-text-color-7">
							<span class="wcb-mr-3 wcb-shrink-0 wcb-pt-1"><?php echo wcb_pricing_card_checkmark_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<span><?php echo esc_html( $wcb_feature_text ); ?></span>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>

	<?php if ( ! empty( $args['button']['title'] ) && ! empty( $args['button']['url'] ) ) : ?>
		<div class="wcb-mt-6 md:wcb-mt-8">
			<?php
			$wcb_button = new Component(
				'button',
				array(
					'title'   => $args['button']['title'],
					'href'    => $args['button']['url'],
					'target'  => $args['button']['target'],
					'variant' => 'primary',
				)
			);
			$wcb_button->render();
			?>
		</div>
	<?php endif; ?>
</div>