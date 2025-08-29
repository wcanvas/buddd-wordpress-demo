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
$wcb_image           = get_field( 'image' );
$wcb_image_position  = get_field( 'image_position' );
$wcb_preheading      = get_field( 'preheading' );
$wcb_heading_group   = get_field( 'heading_group' );
$wcb_description     = get_field( 'description' );
$wcb_features        = get_field( 'features' );
$wcb_button          = get_field( 'button' );

// Validate ACF field values to prevent critical errors
$wcb_image           = is_array( $wcb_image ) ? $wcb_image : array();
$wcb_image_position  = is_string( $wcb_image_position ) ? $wcb_image_position : 'left';
$wcb_preheading      = is_string( $wcb_preheading ) ? $wcb_preheading : '';
$wcb_heading_group   = is_array( $wcb_heading_group ) ? $wcb_heading_group : array();
$wcb_heading_text    = isset( $wcb_heading_group['text'] ) && is_string( $wcb_heading_group['text'] ) ? $wcb_heading_group['text'] : '';
$wcb_heading_level   = isset( $wcb_heading_group['level'] ) && is_string( $wcb_heading_group['level'] ) ? $wcb_heading_group['level'] : 'h2';
$wcb_description     = is_string( $wcb_description ) ? $wcb_description : '';
$wcb_features        = is_array( $wcb_features ) ? $wcb_features : array();
$wcb_button          = is_array( $wcb_button ) ? $wcb_button : array();

// Determine order classes based on image position
$wcb_image_order_class   = 'left' === $wcb_image_position ? 'lg:wcb-order-1' : 'lg:wcb-order-2';
$wcb_content_order_class = 'left' === $wcb_image_position ? 'lg:wcb-order-2' : 'lg:wcb-order-1';

// Block wrapper attributes
$block_data = BlockWrapper::get_global_block_wrapper_data( $block );
?>

<section <?php echo wp_kses_post( $block_data ); ?>>
	<div class="wcb-bg-color-7">
		<div class="wcb-container wcb-mx-auto wcb-py-16 lg:wcb-py-24">
			<div class="wcb-grid wcb-grid-cols-1 lg:wcb-grid-cols-2 wcb-gap-12 lg:wcb-gap-24 wcb-items-center">
				<?php if ( ! empty( $wcb_image['url'] ) ) : ?>
					<div class="wcb-order-2 <?php echo esc_attr( $wcb_image_order_class ); ?>">
						<?php
						$wcb_image_component = new Component(
							'image',
							array(
								'src'   => $wcb_image['url'],
								'alt'   => $wcb_image['alt'],
								'class' => 'wcb-rounded-radius-3 wcb-w-full wcb-h-auto wcb-object-cover',
							)
						);
						$wcb_image_component->render();
						?>
					</div>
				<?php endif; ?>

				<div class="wcb-flex wcb-flex-col wcb-gap-y-8 wcb-order-1 <?php echo esc_attr( $wcb_content_order_class ); ?>">
					<div>
						<?php if ( ! empty( $wcb_preheading ) ) : ?>
							<?php
							$wcb_preheading_component = new Component(
								'preheading',
								array(
									'text'  => $wcb_preheading,
									'class' => 'wcb-font-normal wcb-text-sm !wcb-text-color-30 wcb-tracking-[0.0714em] wcb-leading-lh-9 wcb-mb-4',
								)
							);
							$wcb_preheading_component->render();
							?>
						<?php endif; ?>

						<?php if ( ! empty( $wcb_heading_text ) ) : ?>
							<?php
							$wcb_heading_component = new Component(
								'heading',
								array(
									'as'    => $wcb_heading_level,
									'text'  => $wcb_heading_text,
									'class' => 'wcb-font-font-1 wcb-font-semibold wcb-text-4xl lg:wcb-text-size-1 wcb-leading-lh-4 wcb-text-color-30 wcb-mb-4',
								)
							);
							$wcb_heading_component->render();
							?>
						<?php endif; ?>

						<?php if ( ! empty( $wcb_description ) ) : ?>
							<p class="wcb-font-font-4 wcb-font-normal wcb-text-lg wcb-leading-lh-11 wcb-text-color-30">
								<?php echo esc_html( $wcb_description ); ?>
							</p>
						<?php endif; ?>
					</div>

					<?php if ( ! empty( $wcb_features ) ) : ?>
						<div class="wcb-w-full wcb-border-t wcb-border-color-32">
							<?php
							foreach ( $wcb_features as $wcb_feature ) {
								$wcb_feature_title   = isset( $wcb_feature['title'] ) && is_string( $wcb_feature['title'] ) ? $wcb_feature['title'] : '';
								$wcb_feature_content = isset( $wcb_feature['content'] ) && is_string( $wcb_feature['content'] ) ? $wcb_feature['content'] : '';

								if ( ! empty( $wcb_feature_title ) ) {
									$wcb_accordion_item_component = new Component(
										'accordion-item',
										array(
											'title'   => $wcb_feature_title,
											'content' => $wcb_feature_content,
										)
									);
									$wcb_accordion_item_component->render();
								}
							}
							?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $wcb_button['title'] ) && ! empty( $wcb_button['url'] ) ) : ?>
						<div class="wcb-mt-2">
							<?php
							$wcb_button_component = new Component(
								'button',
								array(
									'title'   => $wcb_button['title'],
									'href'    => $wcb_button['url'],
									'target'  => $wcb_button['target'],
									'variant' => 'light',
								)
							);
							$wcb_button_component->render();
							?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>