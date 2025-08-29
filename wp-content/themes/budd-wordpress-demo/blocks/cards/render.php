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
$wcb_pre_heading   = get_field( 'pre_heading' );
$wcb_heading_group = get_field( 'heading_group' );
$wcb_button        = get_field( 'button' );
$wcb_feature_cards = get_field( 'feature_cards' );

// Validate ACF field values to prevent critical errors
$wcb_pre_heading   = is_string( $wcb_pre_heading ) ? $wcb_pre_heading : '';
$wcb_heading_group = is_array( $wcb_heading_group ) ? $wcb_heading_group : array();
$wcb_button        = is_array( $wcb_button ) ? $wcb_button : array();
$wcb_feature_cards = is_array( $wcb_feature_cards ) ? $wcb_feature_cards : array();

// Validate heading fields
$wcb_heading_text = isset( $wcb_heading_group['text'] ) && is_string( $wcb_heading_group['text'] ) ? $wcb_heading_group['text'] : '';
$wcb_heading_tag  = isset( $wcb_heading_group['level'] ) && is_string( $wcb_heading_group['level'] ) ? $wcb_heading_group['level'] : 'h2';

// Validate button fields
$wcb_button_title  = isset( $wcb_button['title'] ) && is_string( $wcb_button['title'] ) ? $wcb_button['title'] : '';
$wcb_button_url    = isset( $wcb_button['url'] ) && is_string( $wcb_button['url'] ) ? $wcb_button['url'] : '#';
$wcb_button_target = isset( $wcb_button['target'] ) && is_string( $wcb_button['target'] ) ? $wcb_button['target'] : '';

// Block wrapper attributes
$block_data = BlockWrapper::get_global_block_wrapper_data( $block );
?>

<section <?php echo wp_kses_post( $block_data ); ?>>
	<div class="wcb-bg-color-30 wcb-rounded-radius-5 wcb-py-16 lg:wcb-py-24">
		<div class="wcb-container">
			<div class="wcb-grid wcb-grid-cols-1 lg:wcb-grid-cols-12 wcb-gap-10 lg:wcb-gap-12 wcb-items-center">
				<div class="lg:wcb-col-span-4 wcb-text-center lg:wcb-text-left">
					<?php
					if ( ! empty( $wcb_pre_heading ) ) {
						$wcb_preheading_component = new Component(
							'preheading',
							array(
								'text'  => $wcb_pre_heading,
								'class' => 'wcb-mb-4',
							)
						);
						$wcb_preheading_component->render();
					}

					if ( ! empty( $wcb_heading_text ) ) {
						$wcb_heading_component = new Component(
							'heading',
							array(
								'as'    => $wcb_heading_tag,
								'text'  => $wcb_heading_text,
								'class' => 'wcb-font-font-3 wcb-text-3xl lg:wcb-text-size-1 wcb-text-color-7 wcb-font-medium wcb-leading-lh-4',
							)
						);
						$wcb_heading_component->render();
					}

					if ( ! empty( $wcb_button_title ) && ! empty( $wcb_button_url ) ) {
						?>
						<div class="wcb-mt-8">
						<?php
							$wcb_button_component = new Component(
								'button',
								array(
									'title'   => $wcb_button_title,
									'href'    => $wcb_button_url,
									'target'  => $wcb_button_target,
									'variant' => 'primary',
								)
							);
							$wcb_button_component->render();
						?>
						</div>
						<?php
					}
					?>
				</div>
				<div class="lg:wcb-col-span-8">
					<?php if ( ! empty( $wcb_feature_cards ) ) : ?>
						<div class="wcb-grid wcb-grid-cols-1 md:wcb-grid-cols-3 wcb-gap-8">
							<?php
							foreach ( $wcb_feature_cards as $wcb_card ) {
								$wcb_card_image       = isset( $wcb_card['image'] ) && is_array( $wcb_card['image'] ) ? $wcb_card['image'] : array();
								$wcb_card_title       = isset( $wcb_card['title'] ) && is_string( $wcb_card['title'] ) ? $wcb_card['title'] : '';
								$wcb_card_description = isset( $wcb_card['description'] ) && is_string( $wcb_card['description'] ) ? $wcb_card['description'] : '';

								$wcb_feature_card_component = new Component(
									'feature-card',
									array(
										'image'       => $wcb_card_image,
										'title'       => $wcb_card_title,
										'description' => $wcb_card_description,
									)
								);
								$wcb_feature_card_component->render();
							}
							?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>