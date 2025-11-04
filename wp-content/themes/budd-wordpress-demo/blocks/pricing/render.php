<?php
/**
 * Block: Pricing
 *
 * @package WCB
 */

defined( 'ABSPATH' ) || die(); // Ensure that this line do not experiment modifications. it should be always the same.

use WCB\Block\BlockWrapper;
use WCB\Functionalities\Component;

// Get ACF fields
$wcb_tagline              = get_field( 'tagline' );
$wcb_heading_group        = get_field( 'heading' );
$wcb_description          = get_field( 'description' );
$wcb_toggle_monthly_label = get_field( 'toggle_monthly_label' );
$wcb_toggle_yearly_label  = get_field( 'toggle_yearly_label' );
$wcb_includes_label       = get_field( 'includes_label' );
$wcb_pricing_plans        = get_field( 'pricing_plans' );

// Validate ACF fields
$wcb_tagline              = is_string( $wcb_tagline ) ? $wcb_tagline : '';
$wcb_heading_group        = is_array( $wcb_heading_group ) ? $wcb_heading_group : array();
$wcb_heading_text         = isset( $wcb_heading_group['text'] ) && is_string( $wcb_heading_group['text'] ) ? $wcb_heading_group['text'] : '';
$wcb_heading_level        = isset( $wcb_heading_group['level'] ) && is_string( $wcb_heading_group['level'] ) ? $wcb_heading_group['level'] : 'h2';
$wcb_description          = is_string( $wcb_description ) ? $wcb_description : '';
$wcb_toggle_monthly_label = is_string( $wcb_toggle_monthly_label ) ? $wcb_toggle_monthly_label : 'Monthly';
$wcb_toggle_yearly_label  = is_string( $wcb_toggle_yearly_label ) ? $wcb_toggle_yearly_label : 'Yearly';
$wcb_includes_label       = is_string( $wcb_includes_label ) ? $wcb_includes_label : 'Includes:';
$wcb_pricing_plans        = is_array( $wcb_pricing_plans ) ? $wcb_pricing_plans : array();

$block_data = BlockWrapper::get_global_block_wrapper_data( $block );
?>

<section <?php echo wp_kses_post( $block_data ); ?>>
	<div class="wcb-bg-color-30 wcb-py-16 md:wcb-py-20 lg:wcb-py-space-5">
		<div class="wcb-container">
			<div class="wcb-text-center wcb-max-w-2xl wcb-mx-auto">
				<?php
				if ( ! empty( $wcb_tagline ) ) {
					$wcb_preheading_comp = new Component(
						'preheading',
						array(
							'text' => $wcb_tagline,
						)
					);
					$wcb_preheading_comp->render();
				}

				if ( ! empty( $wcb_heading_text ) ) {
					$wcb_heading_comp = new Component(
						'heading',
						array(
							'as'    => $wcb_heading_level,
							'text'  => $wcb_heading_text,
							'class' => 'wcb-font-font-1 wcb-text-3xl md:wcb-text-size-1 wcb-font-bold wcb-text-color-7 wcb-mt-4',
						)
					);
					$wcb_heading_comp->render();
				}
				?>

				<?php if ( ! empty( $wcb_description ) ) : ?>
					<p class="wcb-font-font-3 wcb-text-base md:wcb-text-lg wcb-text-color-7 wcb-mt-4 md:wcb-mt-6">
						<?php echo esc_html( $wcb_description ); ?>
					</p>
				<?php endif; ?>
			</div>

			<?php if ( ! empty( $wcb_pricing_plans ) ) : ?>
				<div class="wcb-flex wcb-justify-center wcb-mt-8 md:wcb-mt-10">
					<?php
					$wcb_toggle_switch_comp = new Component(
						'toggle-switch',
						array(
							'monthly_label' => $wcb_toggle_monthly_label,
							'yearly_label'  => $wcb_toggle_yearly_label,
						)
					);
					$wcb_toggle_switch_comp->render();
					?>
				</div>

				<div class="wcb-grid wcb-grid-cols-1 lg:wcb-grid-cols-2 wcb-gap-6 md:wcb-gap-8 wcb-mt-12 md:wcb-mt-16 wcb-max-w-4xl wcb-mx-auto">
					<?php foreach ( $wcb_pricing_plans as $wcb_plan ) : ?>
						<?php
						$wcb_plan_card = new Component(
							'pricing-card',
							array(
								'icon'           => isset( $wcb_plan['icon'] ) && is_array( $wcb_plan['icon'] ) ? $wcb_plan['icon'] : array(),
								'plan_name'      => isset( $wcb_plan['plan_name'] ) && is_string( $wcb_plan['plan_name'] ) ? $wcb_plan['plan_name'] : '',
								'monthly_price'  => isset( $wcb_plan['monthly_price'] ) && is_string( $wcb_plan['monthly_price'] ) ? $wcb_plan['monthly_price'] : '',
								'yearly_price'   => isset( $wcb_plan['yearly_price'] ) && is_string( $wcb_plan['yearly_price'] ) ? $wcb_plan['yearly_price'] : '',
								'includes_label' => $wcb_includes_label,
								'features'       => isset( $wcb_plan['features'] ) && is_array( $wcb_plan['features'] ) ? $wcb_plan['features'] : array(),
								'button'         => isset( $wcb_plan['button'] ) && is_array( $wcb_plan['button'] ) ? $wcb_plan['button'] : array(),
							)
						);
						$wcb_plan_card->render();
						?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>