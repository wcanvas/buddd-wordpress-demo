<?php
/**
 * Block: Stats With Image
 *
 * @package WCB
 */

defined( 'ABSPATH' ) || die();

use WCB\Block\BlockWrapper;
use WCB\Functionalities\Component;

// Get ACF fields.
$wcb_preheading     = get_field( 'preheading' );
$wcb_heading_group  = get_field( 'heading' );
$wcb_description    = get_field( 'description' );
$wcb_features       = get_field( 'features' );
$wcb_showcase_image = get_field( 'showcase_image' );

// --- Validate ACF fields to prevent critical errors ---

// Preheading
$wcb_preheading = is_string( $wcb_preheading ) ? $wcb_preheading : '';

// Heading
$wcb_heading_group = is_array( $wcb_heading_group ) ? $wcb_heading_group : array();
$wcb_heading_text  = isset( $wcb_heading_group['text'] ) && is_string( $wcb_heading_group['text'] ) ? $wcb_heading_group['text'] : '';
$wcb_heading_level = isset( $wcb_heading_group['level'] ) && is_string( $wcb_heading_group['level'] ) ? $wcb_heading_group['level'] : 'h2';

// Description
$wcb_description = is_string( $wcb_description ) ? $wcb_description : '';

// Features (Repeater)
$wcb_features = is_array( $wcb_features ) ? $wcb_features : array();

// Showcase Image
$wcb_showcase_image = is_array( $wcb_showcase_image ) ? $wcb_showcase_image : array();
$wcb_image_url      = isset( $wcb_showcase_image['url'] ) && is_string( $wcb_showcase_image['url'] ) ? $wcb_showcase_image['url'] : '';
$wcb_image_alt      = isset( $wcb_showcase_image['alt'] ) && is_string( $wcb_showcase_image['alt'] ) ? $wcb_showcase_image['alt'] : ( isset( $wcb_showcase_image['title'] ) ? $wcb_showcase_image['title'] : '' );

// Block wrapper attributes.
$wcb_block_data = BlockWrapper::get_global_block_wrapper_data( $block );
?>

<section <?php echo wp_kses_post( $wcb_block_data ); ?>>
	<div class="wcb-bg-color-30 wcb-py-16 lg:wcb-py-24">
		<div class="wcb-container">
			<div class="wcb-grid lg:wcb-grid-cols-2 lg:wcb-gap-x-16 xl:wcb-gap-x-24 wcb-items-center">
				<div class="wcb-flex wcb-flex-col">
					<?php
					if ( ! empty( $wcb_preheading ) ) {
						$wcb_preheading_component = new Component(
							'preheading',
							array(
								'text'  => $wcb_preheading,
								'class' => 'wcb-font-normal wcb-text-sm wcb-tracking-[0.0714em]',
							)
						);
						$wcb_preheading_component->render();
					}
					?>

					<?php
					if ( ! empty( $wcb_heading_text ) ) {
						$wcb_heading_component = new Component(
							'heading',
							array(
								'as'    => $wcb_heading_level,
								'text'  => $wcb_heading_text,
								'class' => 'wcb-mt-2 wcb-font-font-1 wcb-font-semibold wcb-text-3xl lg:wcb-text-size-1 wcb-text-color-3 wcb-leading-lh-4',
							)
						);
						$wcb_heading_component->render();
					}
					?>

					<?php if ( ! empty( $wcb_description ) ) : ?>
						<p class="wcb-mt-4 wcb-font-font-4 wcb-text-lg wcb-text-color-3 wcb-leading-lh-11">
							<?php echo esc_html( $wcb_description ); ?>
						</p>
					<?php endif; ?>

					<?php if ( ! empty( $wcb_features ) ) : ?>
						<div class="wcb-mt-8 wcb-flex wcb-flex-col">
							<?php
							foreach ( $wcb_features as $wcb_feature ) {
								$wcb_feature_title       = isset( $wcb_feature['title'] ) && is_string( $wcb_feature['title'] ) ? $wcb_feature['title'] : '';
								$wcb_feature_description = isset( $wcb_feature['description'] ) && is_string( $wcb_feature['description'] ) ? $wcb_feature['description'] : '';

								$wcb_accordion_item = new Component(
									'accordion-item',
									array(
										'title'   => $wcb_feature_title,
										'content' => $wcb_feature_description,
										'variant' => 'default',
									)
								);
								$wcb_accordion_item->render();
							}
							?>
						</div>
					<?php endif; ?>
				</div>

				<?php if ( ! empty( $wcb_image_url ) ) : ?>
					<div class="wcb-mt-12 lg:wcb-mt-0">
						<?php
						$wcb_image_component = new Component(
							'image',
							array(
								'src'   => $wcb_image_url,
								'alt'   => $wcb_image_alt,
								'class' => 'wcb-w-full wcb-h-auto wcb-rounded-radius-3 wp-block-wcb-stats-with-image__image',
							)
						);
						$wcb_image_component->render();
						?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>