<?php
/**
 * Block: Cards With Icons
 * 
 * @package WCB
 */

defined( 'ABSPATH' ) || die(); // Ensure that this line do not experiment modifications. it should be always the same.

use WCB\Block\BlockWrapper;
use WCB\Functionalities\Component;

// Get ACF fields
$wcb_heading     = get_field( 'heading' );
$wcb_description = get_field( 'description' );
$wcb_features    = get_field( 'features' );

// Validate ACF field values to prevent critical errors
$wcb_heading_tag  = isset( $wcb_heading['heading_tag'] ) && is_string( $wcb_heading['heading_tag'] ) ? $wcb_heading['heading_tag'] : 'h2';
$wcb_heading_text = isset( $wcb_heading['text'] ) && is_string( $wcb_heading['text'] ) ? $wcb_heading['text'] : '';
$wcb_description  = is_string( $wcb_description ) ? $wcb_description : '';
$wcb_features     = is_array( $wcb_features ) ? $wcb_features : array();

// Block wrapper attributes  
$block_data = BlockWrapper::get_global_block_wrapper_data( $block );
?>

<section <?php echo wp_kses_post( $block_data ); ?>>
	<div class="wcb-bg-color-8">
		<div class="wcb-container wcb-py-16 md:wcb-py-20 lg:wcb-py-28">
			<div class="wcb-max-w-4xl wcb-mb-12 lg:wcb-mb-16 wcb-mx-auto wcb-text-center">
				<?php
				if ( ! empty( $wcb_heading_text ) ) {
					$wcb_heading_component = new Component(
						'heading',
						array(
							'as'    => $wcb_heading_tag,
							'text'  => $wcb_heading_text,
							'class' => 'wcb-font-font-4 wcb-font-bold wcb-text-4xl lg:wcb-text-[48px] wcb-leading-lh-2 wcb-tracking-[-0.0416em] wcb-text-color-30',
						)
					);
					$wcb_heading_component->render();
				}
				?>
				<?php if ( ! empty( $wcb_description ) ) : ?>
					<p class="wcb-font-font-4 wcb-font-normal wcb-text-lg wcb-leading-lh-11 wcb-text-color-30 wcb-mt-4">
						<?php echo esc_html( $wcb_description ); ?>
					</p>
				<?php endif; ?>
			</div>

			<?php if ( ! empty( $wcb_features ) ) : ?>
				<div class="wcb-grid wcb-grid-cols-1 md:wcb-grid-cols-2 lg:wcb-grid-cols-3 wcb-gap-x-8 wcb-gap-y-12 lg:wcb-gap-y-16">
					<?php
					foreach ( $wcb_features as $wcb_feature ) {
						// Validate repeater sub-fields
						$wcb_icon                = isset( $wcb_feature['icon'] ) && is_array( $wcb_feature['icon'] ) ? $wcb_feature['icon'] : array();
						$wcb_icon_url            = isset( $wcb_icon['url'] ) && is_string( $wcb_icon['url'] ) ? $wcb_icon['url'] : '';
						$wcb_icon_alt            = isset( $wcb_icon['alt'] ) && is_string( $wcb_icon['alt'] ) ? $wcb_icon['alt'] : '';
						$wcb_title               = isset( $wcb_feature['title'] ) && is_string( $wcb_feature['title'] ) ? $wcb_feature['title'] : '';
						$wcb_description_feature = isset( $wcb_feature['description'] ) && is_string( $wcb_feature['description'] ) ? $wcb_feature['description'] : '';

						$wcb_feature_item = new Component(
							'feature-item',
							array(
								'icon_url'    => $wcb_icon_url,
								'icon_alt'    => $wcb_icon_alt,
								'title'       => $wcb_title,
								'description' => $wcb_description_feature,
							)
						);
						$wcb_feature_item->render();
					}
					?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>