<?php
/**
 * Block: Cards
 * 
 * @package WCB
 */

defined( 'ABSPATH' ) || die(); // Ensure that this line do not experiment modifications. it should be always the same.

use WCB\Block\BlockWrapper;
use WCB\Functionalities\Component;

// Get ACF fields
$wcb_preheading    = get_field( 'preheading' );
$wcb_heading_group = get_field( 'heading' );
$wcb_cta_button    = get_field( 'cta_button' );
$wcb_feature_cards = get_field( 'feature_cards' );

// Validate ACF field values to prevent critical errors
$wcb_preheading    = is_string( $wcb_preheading ) ? $wcb_preheading : '';
$wcb_heading_group = is_array( $wcb_heading_group ) ? $wcb_heading_group : array();
$wcb_cta_button    = is_array( $wcb_cta_button ) ? $wcb_cta_button : array();
$wcb_feature_cards = is_array( $wcb_feature_cards ) ? $wcb_feature_cards : array();

// Validate heading fields
$wcb_heading_text = isset( $wcb_heading_group['text'] ) && is_string( $wcb_heading_group['text'] ) ? $wcb_heading_group['text'] : '';
$wcb_heading_tag  = isset( $wcb_heading_group['level'] ) && is_string( $wcb_heading_group['level'] ) ? $wcb_heading_group['level'] : 'h2';

// Validate button fields
$wcb_button_title  = isset( $wcb_cta_button['title'] ) && is_string( $wcb_cta_button['title'] ) ? $wcb_cta_button['title'] : '';
$wcb_button_url    = isset( $wcb_cta_button['url'] ) && is_string( $wcb_cta_button['url'] ) ? $wcb_cta_button['url'] : '';
$wcb_button_target = isset( $wcb_cta_button['target'] ) && is_string( $wcb_cta_button['target'] ) ? $wcb_cta_button['target'] : '';

// Block wrapper attributes  
$block_data = BlockWrapper::get_global_block_wrapper_data( $block );
?>

<section <?php echo wp_kses_post( $block_data ); ?>>
	<div class="wcb-bg-color-30 wcb-rounded-radius-5">
		<div class="wcb-container wcb-py-16 lg:wcb-py-24">
			<div class="wcb-grid wcb-grid-cols-1 lg:wcb-grid-cols-12 lg:wcb-gap-x-8 xl:wcb-gap-x-12 wcb-items-start">
				<div class="lg:wcb-col-span-4 wcb-text-center lg:wcb-text-left wcb-mb-12 lg:wcb-mb-0">
					<?php
					if ( ! empty( $wcb_preheading ) ) {
						$wcb_preheading_comp = new Component(
							'preheading',
							array(
								'text'  => $wcb_preheading,
								'class' => 'wcb-mb-4',
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
								'class' => 'wcb-font-font-4 wcb-font-semibold wcb-text-[32px] md:wcb-text-[38px] wcb-leading-lh-3 -wcb-tracking-[0.0084em] wcb-text-color-3 wcb-mb-8',
							)
						);
						$wcb_heading_comp->render();
					}

					if ( ! empty( $wcb_button_title ) && ! empty( $wcb_button_url ) ) {
						$wcb_button_comp = new Component(
							'button',
							array(
								'title'   => $wcb_button_title,
								'href'    => $wcb_button_url,
								'target'  => $wcb_button_target,
								'variant' => 'primary',
							)
						);
						$wcb_button_comp->render();
					}
					?>
				</div>
				<div class="lg:wcb-col-span-8">
					<?php if ( ! empty( $wcb_feature_cards ) ) : ?>
						<div class="wcb-grid wcb-grid-cols-1 sm:wcb-grid-cols-2 lg:wcb-grid-cols-3 wcb-gap-8">
							<?php
							foreach ( $wcb_feature_cards as $wcb_card ) {
								$wcb_image       = isset( $wcb_card['image'] ) && is_array( $wcb_card['image'] ) ? $wcb_card['image'] : array();
								$wcb_image_url   = isset( $wcb_image['sizes']['medium_large'] ) ? $wcb_image['sizes']['medium_large'] : ( isset( $wcb_image['url'] ) ? $wcb_image['url'] : '' );
								$wcb_image_alt   = isset( $wcb_image['alt'] ) ? $wcb_image['alt'] : '';
								$wcb_title       = isset( $wcb_card['title'] ) && is_string( $wcb_card['title'] ) ? $wcb_card['title'] : '';
								$wcb_description = isset( $wcb_card['description'] ) && is_string( $wcb_card['description'] ) ? $wcb_card['description'] : '';

								$wcb_info_card = new Component(
									'info-card',
									array(
										'image_url'   => $wcb_image_url,
										'image_alt'   => $wcb_image_alt,
										'title'       => $wcb_title,
										'description' => $wcb_description,
									)
								);
								$wcb_info_card->render();
							}
							?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>