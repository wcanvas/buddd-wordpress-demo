<?php
/**
 * Block: Pricing Table
 * 
 * @package WCB
 */

defined( 'ABSPATH' ) || die(); // Ensure that this line do not experiment modifications. it should be always the same.

use WCB\Block\BlockWrapper;
use WCB\Functionalities\Component;

// Get ACF fields
$wcb_tagline            = get_field( 'tagline' );
$wcb_heading_group      = get_field( 'heading_group' );
$wcb_description        = get_field( 'description' );
$wcb_plans              = get_field( 'plans' );
$wcb_feature_categories = get_field( 'feature_categories' );

// Validate ACF field values to prevent critical errors
$wcb_tagline            = is_string( $wcb_tagline ) ? $wcb_tagline : '';
$wcb_heading_group      = is_array( $wcb_heading_group ) ? $wcb_heading_group : array();
$wcb_description        = is_string( $wcb_description ) ? $wcb_description : '';
$wcb_plans              = is_array( $wcb_plans ) ? $wcb_plans : array();
$wcb_feature_categories = is_array( $wcb_feature_categories ) ? $wcb_feature_categories : array();

$wcb_heading     = isset( $wcb_heading_group['heading'] ) && is_string( $wcb_heading_group['heading'] ) ? $wcb_heading_group['heading'] : '';
$wcb_heading_tag = isset( $wcb_heading_group['heading_tag'] ) && is_string( $wcb_heading_group['heading_tag'] ) ? $wcb_heading_group['heading_tag'] : 'h2';

// Block wrapper attributes
$block_data = BlockWrapper::get_global_block_wrapper_data( $block );
?>

<section <?php echo wp_kses_post( $block_data ); ?>>
	<div class="wcb-bg-color-30 wcb-py-12 md:wcb-py-16 lg:wcb-py-24">
		<div class="wcb-container">
			<div class="wcb-text-center wcb-max-w-xl wcb-mx-auto">
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

				if ( ! empty( $wcb_heading ) ) {
					$wcb_heading_comp = new Component(
						'heading',
						array(
							'as'    => $wcb_heading_tag,
							'text'  => $wcb_heading,
							'class' => 'wcb-mt-4 wcb-text-3xl md:wcb-text-size-1 wcb-font-bold wcb-text-color-7',
						)
					);
					$wcb_heading_comp->render();
				}
				?>

				<?php if ( ! empty( $wcb_description ) ) : ?>
					<p class="wcb-mt-4 wcb-text-lg wcb-text-color-3"><?php echo esc_html( $wcb_description ); ?></p>
				<?php endif; ?>
			</div>

			<?php if ( ! empty( $wcb_plans ) ) : ?>
				<div class="wcb-mt-12 md:wcb-mt-16">
					<div class="wcb-grid wcb-grid-cols-1 md:wcb-grid-cols-<?php echo esc_attr( count( $wcb_plans ) ); ?>">
						<?php foreach ( $wcb_plans as $index => $plan ) : ?>
							<?php
							$wcb_plan_name        = isset( $plan['name'] ) && is_string( $plan['name'] ) ? $plan['name'] : '';
							$wcb_price            = isset( $plan['price'] ) && is_string( $plan['price'] ) ? $plan['price'] : '';
							$wcb_period           = isset( $plan['period'] ) && is_string( $plan['period'] ) ? $plan['period'] : '';
							$wcb_short_description = isset( $plan['short_description'] ) && is_string( $plan['short_description'] ) ? $plan['short_description'] : '';
							$wcb_button           = isset( $plan['button'] ) && is_array( $plan['button'] ) ? $plan['button'] : array();
							$wcb_card_class       = $index > 0 ? 'wcb-border-t wcb-border-color-4 md:wcb-border-t-0 md:wcb-border-l' : '';

							$wcb_simple_pricing_card = new Component(
								'simple-pricing-card',
								array(
									'plan_name'   => $wcb_plan_name,
									'price'       => $wcb_price,
									'period'      => $wcb_period,
									'description' => $wcb_short_description,
									'button'      => $wcb_button,
									'class'       => $wcb_card_class,
								)
							);
							$wcb_simple_pricing_card->render();
							?>
						<?php endforeach; ?>
					</div>

					<?php
					if ( ! empty( $wcb_feature_categories ) ) {
						$wcb_feature_table = new Component(
							'feature-table',
							array(
								'data'  => $wcb_feature_categories,
								'plans' => $wcb_plans,
							)
						);
						$wcb_feature_table->render();
					}
					?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>