<?php
/**
 * Block: Stats With image
 * 
 * @package WCB
 */

defined( 'ABSPATH' ) || die();

use WCB\Block\BlockWrapper;
use WCB\Functionalities\Component;

// Get ACF fields
$wcb_preheading     = get_field( 'preheading' );
$wcb_heading_group  = get_field( 'heading' );
$wcb_description    = get_field( 'description' );
$wcb_features       = get_field( 'features' );
$wcb_showcase_image = get_field( 'showcase_image' );

// Validate ACF field values
$wcb_preheading     = is_string( $wcb_preheading ) ? $wcb_preheading : '';
$wcb_heading_tag    = isset( $wcb_heading_group['tag'] ) && is_string( $wcb_heading_group['tag'] ) ? $wcb_heading_group['tag'] : 'h2';
$wcb_heading_text   = isset( $wcb_heading_group['text'] ) && is_string( $wcb_heading_group['text'] ) ? $wcb_heading_group['text'] : '';
$wcb_description    = is_string( $wcb_description ) ? $wcb_description : '';
$wcb_features       = is_array( $wcb_features ) ? $wcb_features : array();
$wcb_showcase_image = is_array( $wcb_showcase_image ) ? $wcb_showcase_image : array();

$wcb_block_data = BlockWrapper::get_global_block_wrapper_data( $block );
?>

<section <?php echo wp_kses_post( $wcb_block_data ); ?>>
	<div class="wcb-bg-color-30 wcb-rounded-radius-5 wcb-p-6 md:wcb-p-12 lg:wcb-p-16">
		<div class="wcb-container wcb-mx-auto">
			<div class="wcb-grid lg:wcb-grid-cols-2 wcb-gap-12 lg:wcb-gap-16 wcb-items-center">
				<div class="wcb-max-w-xl">
					<?php
					if ( ! empty( $wcb_preheading ) ) {
						$wcb_preheading_comp = new Component(
							'preheading',
							array(
								'text'  => $wcb_preheading,
								'class' => 'wcb-font-normal wcb-text-sm wcb-tracking-[0.0714em] wcb-text-color-3 wcb-mb-4',
							)
						);
						$wcb_preheading_comp->render();
					}

					if ( ! empty( $wcb_heading_text ) ) {
						$wcb_heading_comp = new Component(
							'heading',
							array(
								'as'    => $wcb_heading_tag,
								'text'  => $wcb_heading_text,
								'class' => 'wcb-font-font-1 wcb-font-semibold wcb-text-3xl md:wcb-text-size-1 wcb-leading-tight md:wcb-leading-lh-4 wcb-text-color-3 wcb-mb-4',
							)
						);
						$wcb_heading_comp->render();
					}
					?>

					<?php if ( ! empty( $wcb_description ) ) : ?>
						<p class="wcb-font-font-4 wcb-font-normal wcb-text-lg wcb-leading-lh-11 wcb-text-color-3 wcb-mb-8">
							<?php echo esc_html( $wcb_description ); ?>
						</p>
					<?php endif; ?>

					<?php if ( ! empty( $wcb_features ) ) : ?>
						<div class="wcb-w-full">
							<?php
							foreach ( $wcb_features as $feature ) {
								$wcb_feature_title = isset( $feature['title'] ) && is_string( $feature['title'] ) ? $feature['title'] : '';
								$wcb_feature_desc  = isset( $feature['description'] ) && is_string( $feature['description'] ) ? $feature['description'] : '';

								$wcb_accordion_item = new Component(
									'accordion-item',
									array(
										'title'   => $wcb_feature_title,
										'content' => $wcb_feature_desc,
									)
								);
								$wcb_accordion_item->render();
							}
							?>
						</div>
					<?php endif; ?>
				</div>

				<div>
					<?php
					// Hardcoded data for ChartCard as it's not in ACF fields.
					$wcb_chart_card_data = array(
						'iconUrl'      => "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='%23433771' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M3 3l7.07 16.97 2.51-7.39 7.39-2.51L3 3z'%3E%3C/path%3E%3Cpath d='M13 13l6 6'%3E%3C/path%3E%3C/svg%3E",
						'iconAlt'      => 'Conversion rate icon',
						'title'        => 'Conversion rate',
						'value'        => '12.32%',
						'valueIconUrl' => "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 24 24' fill='none' stroke='%235AA449' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M12 19V5M5 12l7-7 7 7'%3E%3C/path%3E%3C/svg%3E",
						'valueIconAlt' => 'Trend up arrow',
						'subtitle'     => 'Last month avg rate',
					);

					// Prepare the chart image (child of ChartCard) using output buffering.
					ob_start();
					if ( ! empty( $wcb_showcase_image['url'] ) ) {
						$wcb_image_comp = new Component(
							'image',
							array(
								'src'   => $wcb_showcase_image['url'],
								'alt'   => ! empty( $wcb_showcase_image['alt'] ) ? $wcb_showcase_image['alt'] : '',
								'class' => 'wcb-w-full wcb-h-auto',
							)
						);
						$wcb_image_comp->render();
					}
					$wcb_chart_image_html = ob_get_clean();

					// Prepare the ChartCard (child of FeatureShowcaseCard) using output buffering.
					ob_start();
					$wcb_chart_card = new Component(
						'chart-card',
						array(
							'icon_url'       => $wcb_chart_card_data['iconUrl'],
							'icon_alt'       => $wcb_chart_card_data['iconAlt'],
							'title'          => $wcb_chart_card_data['title'],
							'value'          => $wcb_chart_card_data['value'],
							'value_icon_url' => $wcb_chart_card_data['valueIconUrl'],
							'value_icon_alt' => $wcb_chart_card_data['valueIconAlt'],
							'subtitle'       => $wcb_chart_card_data['subtitle'],
							'children'       => $wcb_chart_image_html,
						)
					);
					$wcb_chart_card->render();
					$wcb_chart_card_html = ob_get_clean();

					// Render the final FeatureShowcaseCard.
					$wcb_feature_showcase_card = new Component(
						'feature-showcase-card',
						array(
							'children' => $wcb_chart_card_html,
						)
					);
					$wcb_feature_showcase_card->render();
					?>
				</div>
			</div>
		</div>
	</div>
</section>