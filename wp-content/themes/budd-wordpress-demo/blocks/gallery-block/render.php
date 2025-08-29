<?php
/**
 * Block: Gallery Block
 * 
 * @package WCB
 */

defined( 'ABSPATH' ) || die(); // Ensure that this line do not experiment modifications. it should be always the same.

use WCB\Block\BlockWrapper;
use WCB\Functionalities\Component;

// Get ACF fields
$wcb_heading    = get_field( 'heading' );
$wcb_subheading = get_field( 'subheading' );
$wcb_gallery    = get_field( 'gallery' );

// Validate ACF field values to prevent critical errors
$wcb_heading_text  = isset( $wcb_heading['text'] ) && is_string( $wcb_heading['text'] ) ? $wcb_heading['text'] : '';
$wcb_heading_level = isset( $wcb_heading['level'] ) && is_string( $wcb_heading['level'] ) ? $wcb_heading['level'] : 'h2';
$wcb_subheading    = is_string( $wcb_subheading ) ? $wcb_subheading : '';
$wcb_gallery       = is_array( $wcb_gallery ) ? $wcb_gallery : array();

// Block wrapper attributes  
$block_data = BlockWrapper::get_global_block_wrapper_data( $block );
?>

<section <?php echo wp_kses_post( $block_data ); ?>>
	<div class="wcb-bg-color-7 wcb-py-16 md:wcb-py-24">
		<div class="wcb-container">
			<?php if ( ! empty( $wcb_heading_text ) || ! empty( $wcb_subheading ) ) : ?>
				<div class="wcb-text-center wcb-mb-10 md:wcb-mb-12">
					<?php
					if ( ! empty( $wcb_heading_text ) ) {
						$wcb_heading_component = new Component(
							'heading',
							array(
								'as'    => $wcb_heading_level,
								'text'  => $wcb_heading_text,
								'class' => 'wcb-font-font-4 wcb-font-bold wcb-text-4xl md:wcb-text-5xl wcb-leading-lh-2 wcb-tracking-tighter wcb-text-color-30 wcb-mb-4',
							)
						);
						$wcb_heading_component->render();
					}
					?>
					<?php if ( ! empty( $wcb_subheading ) ) : ?>
						<p class="wcb-font-font-4 wcb-font-normal wcb-text-base md:wcb-text-lg wcb-leading-lh-11 wcb-text-color-30 wcb-max-w-md wcb-mx-auto">
							<?php echo esc_html( $wcb_subheading ); ?>
						</p>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $wcb_gallery ) ) : ?>
				<div class="wcb-relative">
					<div class="swiper js-gallery-slider">
						<div class="swiper-wrapper">
							<?php foreach ( $wcb_gallery as $wcb_item ) : ?>
								<?php
								$wcb_image = isset( $wcb_item['image'] ) && is_array( $wcb_item['image'] ) ? $wcb_item['image'] : null;
								if ( ! $wcb_image ) {
									continue;
								}
								?>
								<div class="swiper-slide">
									<?php
									$wcb_image_component = new Component(
										'image',
										array(
											'src'   => $wcb_image['url'] ?? '',
											'alt'   => $wcb_image['alt'] ?? '',
											'class' => 'wcb-w-full wcb-h-auto wcb-object-cover wcb-rounded-radius-3 wcb-aspect-[4/3]',
										)
									);
									$wcb_image_component->render();
									?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>

					<?php
					$wcb_prev_button = new Component(
						'carousel-nav-button',
						array(
							'direction' => 'left',
							'class'     => 'js-gallery-prev-button wcb-absolute wcb-top-1/2 -wcb-translate-y-1/2 wcb-left-4 wcb-z-10 wcb-hidden md:wcb-flex',
						)
					);
					$wcb_prev_button->render();

					$wcb_next_button = new Component(
						'carousel-nav-button',
						array(
							'direction' => 'right',
							'class'     => 'js-gallery-next-button wcb-absolute wcb-top-1/2 -wcb-translate-y-1/2 wcb-right-4 wcb-z-10 wcb-hidden md:wcb-flex',
						)
					);
					$wcb_next_button->render();
					?>
				</div>

				<div class="js-gallery-pagination wcb-flex wcb-justify-center wcb-items-center wcb-gap-2 wcb-mt-8"></div>
			<?php endif; ?>
		</div>
	</div>
</section>