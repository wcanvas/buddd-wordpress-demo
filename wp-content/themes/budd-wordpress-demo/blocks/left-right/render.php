<?php
/**
 * Block: Left Right
 * 
 * @package WCB
 */

defined( 'ABSPATH' ) || die(); // Ensure that this line do not experiment modifications. it should be always the same.

use WCB\Block\BlockWrapper;
use WCB\Functionalities\Component;

// Get ACF fields
$wcb_image          = get_field( 'image' );
$wcb_image_position = get_field( 'image_position' );
$wcb_preheading     = get_field( 'preheading' );
$wcb_heading_group  = get_field( 'heading' );
$wcb_description    = get_field( 'description' );
$wcb_features       = get_field( 'features' );
$wcb_button         = get_field( 'button' );

// Validate ACF field values to prevent critical errors
$wcb_image          = is_array( $wcb_image ) ? $wcb_image : array();
$wcb_image_position = is_string( $wcb_image_position ) ? $wcb_image_position : 'left';
$wcb_preheading     = is_string( $wcb_preheading ) ? $wcb_preheading : '';
$wcb_heading_group  = is_array( $wcb_heading_group ) ? $wcb_heading_group : array();
$wcb_description    = is_string( $wcb_description ) ? $wcb_description : '';
$wcb_features       = is_array( $wcb_features ) ? $wcb_features : array();
$wcb_button         = is_array( $wcb_button ) ? $wcb_button : array();

// Validate nested ACF fields
$wcb_heading_tag  = isset( $wcb_heading_group['level'] ) && is_string( $wcb_heading_group['level'] ) ? $wcb_heading_group['level'] : 'h2';
$wcb_heading_text = isset( $wcb_heading_group['text'] ) && is_string( $wcb_heading_group['text'] ) ? $wcb_heading_group['text'] : '';

// Block wrapper attributes
$block_data = BlockWrapper::get_global_block_wrapper_data( $block );

// Conditional classes
$wcb_image_order_class = 'right' === $wcb_image_position ? 'lg:wcb-order-last' : '';

?>

<section <?php echo wp_kses_post( $block_data ); ?>>
	<div class="wcb-bg-color-7">
		<div class="wcb-container wcb-mx-auto wcb-py-12 lg:wcb-py-24">
			<div class="wcb-grid wcb-grid-cols-1 lg:wcb-grid-cols-2 wcb-gap-10 lg:wcb-gap-20 wcb-items-center">
				<?php if ( ! empty( $wcb_image['url'] ) ) : ?>
					<div class="wcb-flex wcb-items-center wcb-justify-center <?php echo esc_attr( $wcb_image_order_class ); ?>">
						<?php
						$wcb_image_comp = new Component(
							'image',
							array(
								'src'   => $wcb_image['url'],
								'alt'   => $wcb_image['alt'],
								'class' => 'wcb-w-full wcb-h-auto wcb-object-cover wcb-rounded-radius-3',
							)
						);
						$wcb_image_comp->render();
						?>
					</div>
				<?php endif; ?>
				<div class="wcb-flex wcb-flex-col">
					<?php if ( ! empty( $wcb_preheading ) ) : ?>
						<?php
						$wcb_preheading_comp = new Component(
							'preheading',
							array(
								'text'  => $wcb_preheading,
								'class' => 'wcb-font-normal wcb-text-sm wcb-text-color-30',
							)
						);
						$wcb_preheading_comp->render();
						?>
					<?php endif; ?>

					<?php if ( ! empty( $wcb_heading_text ) ) : ?>
						<?php
						$wcb_heading_comp = new Component(
							'heading',
							array(
								'as'    => $wcb_heading_tag,
								'text'  => $wcb_heading_text,
								'class' => 'wcb-mt-4 wcb-font-font-1 wcb-font-semibold wcb-text-4xl wcb-leading-tight lg:wcb-text-size-1 lg:wcb-leading-lh-4 wcb-text-color-30',
							)
						);
						$wcb_heading_comp->render();
						?>
					<?php endif; ?>

					<?php if ( ! empty( $wcb_description ) ) : ?>
						<p class="wcb-mt-4 wcb-font-font-4 wcb-font-normal wcb-text-lg wcb-leading-lh-11 wcb-text-color-30">
							<?php echo esc_html( $wcb_description ); ?>
						</p>
					<?php endif; ?>

					<?php if ( ! empty( $wcb_features ) ) : ?>
						<div class="wcb-mt-6 wcb-flex wcb-flex-col">
							<?php foreach ( $wcb_features as $wcb_feature ) : ?>
								<?php
								$wcb_feature_title   = is_array( $wcb_feature ) && isset( $wcb_feature['title'] ) && is_string( $wcb_feature['title'] ) ? $wcb_feature['title'] : '';
								$wcb_feature_content = is_array( $wcb_feature ) && isset( $wcb_feature['content'] ) && is_string( $wcb_feature['content'] ) ? $wcb_feature['content'] : '';

								if ( ! empty( $wcb_feature_title ) ) {
									$wcb_accordion_item_comp = new Component(
										'accordion-item',
										array(
											'title'   => $wcb_feature_title,
											'content' => $wcb_feature_content,
											'variant' => 'dark',
										)
									);
									$wcb_accordion_item_comp->render();
								}
								?>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $wcb_button['url'] ) && ! empty( $wcb_button['title'] ) ) : ?>
						<div class="wcb-mt-8">
							<?php
							$wcb_button_comp = new Component(
								'button',
								array(
									'title'   => $wcb_button['title'],
									'href'    => $wcb_button['url'],
									'target'  => $wcb_button['target'],
									'variant' => 'light',
									'class'   => 'wcb-font-font-4 wcb-font-semibold',
								)
							);
							$wcb_button_comp->render();
							?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>