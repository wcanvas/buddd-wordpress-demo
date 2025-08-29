<?php
/**
 * Block: Hero Stack
 *
 * @package WCB
 */

defined( 'ABSPATH' ) || die();

use WCB\Block\BlockWrapper;
use WCB\Functionalities\Component;

// Get ACF fields
$wcb_main_heading_group = get_field( 'main_heading_group' );
$wcb_background_image   = get_field( 'background_image' );
$wcb_card_title         = get_field( 'card_title' );
$wcb_card_description   = get_field( 'card_description' );
$wcb_card_button        = get_field( 'card_button' );

// Validate ACF field values
$wcb_main_heading_group = is_array( $wcb_main_heading_group ) ? $wcb_main_heading_group : array();
$wcb_main_heading_level = isset( $wcb_main_heading_group['level'] ) && is_string( $wcb_main_heading_group['level'] ) ? $wcb_main_heading_group['level'] : 'h1';
$wcb_main_heading_text  = isset( $wcb_main_heading_group['text'] ) && is_string( $wcb_main_heading_group['text'] ) ? $wcb_main_heading_group['text'] : '';

$wcb_background_image = is_array( $wcb_background_image ) ? $wcb_background_image : array();
$wcb_image_src        = isset( $wcb_background_image['url'] ) && is_string( $wcb_background_image['url'] ) ? $wcb_background_image['url'] : '';
$wcb_image_alt        = isset( $wcb_background_image['alt'] ) && is_string( $wcb_background_image['alt'] ) ? $wcb_background_image['alt'] : '';

$wcb_card_title       = is_string( $wcb_card_title ) ? $wcb_card_title : '';
$wcb_card_description = is_string( $wcb_card_description ) ? $wcb_card_description : '';

$wcb_card_button    = is_array( $wcb_card_button ) ? $wcb_card_button : array();
$wcb_button_title   = isset( $wcb_card_button['title'] ) && is_string( $wcb_card_button['title'] ) ? $wcb_card_button['title'] : '';
$wcb_button_url     = isset( $wcb_card_button['url'] ) && is_string( $wcb_card_button['url'] ) ? $wcb_card_button['url'] : '#';
$wcb_button_target  = isset( $wcb_card_button['target'] ) && is_string( $wcb_card_button['target'] ) ? $wcb_card_button['target'] : '';

// Block wrapper attributes
$wcb_block_data = BlockWrapper::get_global_block_wrapper_data( $block );
?>

<section <?php echo wp_kses_post( $wcb_block_data ); ?>>
	<div class="wcb-bg-color-30 wcb-pt-12 wcb-pb-16 md:wcb-pt-16 md:wcb-pb-24 lg:wcb-pt-24 lg:wcb-pb-40">
		<div class="wcb-container">
			<?php
			if ( ! empty( $wcb_main_heading_text ) ) {
				$wcb_main_heading_comp = new Component(
					'heading',
					array(
						'as'    => $wcb_main_heading_level,
						'text'  => $wcb_main_heading_text,
						'class' => 'wcb-text-center wcb-text-color-3 wcb-font-font-3 wcb-text-3xl sm:wcb-text-4xl lg:wcb-text-size-1 wcb-font-medium wcb-leading-tight lg:wcb-leading-lh-4 wcb-max-w-4xl wcb-mx-auto',
					)
				);
				$wcb_main_heading_comp->render();
			}
			?>

			<div class="wcb-mt-8 md:wcb-mt-12">
				<div class="wcb-relative wcb-z-0">
					<?php
					if ( ! empty( $wcb_image_src ) ) {
						$wcb_image_comp = new Component(
							'image',
							array(
								'src'   => $wcb_image_src,
								'alt'   => $wcb_image_alt,
								'class' => 'wcb-rounded-radius-3 wcb-w-full wcb-object-cover wcb-aspect-[16/9] md:wcb-aspect-auto',
							)
						);
						$wcb_image_comp->render();
					}
					?>
				</div>

				<div class="wcb-relative wcb-px-4 md:wcb-px-8 wcb-z-10">
					<div class="wcb-bg-color-7 wcb-rounded-radius-3 wcb-text-center wcb-p-6 sm:wcb-p-8 md:wcb-p-12 lg:wcb-p-16 wcb-max-w-[864px] wcb-mx-auto wcb--mt-12 sm:wcb--mt-16 md:wcb--mt-24 lg:wcb--mt-32">
						<?php
						if ( ! empty( $wcb_card_title ) ) {
							$wcb_card_heading_comp = new Component(
								'heading',
								array(
									'as'    => 'h2',
									'text'  => $wcb_card_title,
									'class' => 'wcb-text-color-42 wcb-font-font-3 wcb-text-2xl sm:wcb-text-3xl lg:wcb-text-size-1 wcb-font-medium wcb-leading-tight lg:wcb-leading-lh-4',
								)
							);
							$wcb_card_heading_comp->render();
						}
						?>

						<?php if ( ! empty( $wcb_card_description ) ) : ?>
							<p class="wcb-text-color-33 wcb-font-font-3 wcb-text-base lg:wcb-text-lg wcb-mt-4 wcb-max-w-xl wcb-mx-auto wcb-leading-relaxed">
								<?php echo esc_html( $wcb_card_description ); ?>
							</p>
						<?php endif; ?>

						<?php if ( ! empty( $wcb_button_title ) ) : ?>
							<div class="wcb-mt-6 md:wcb-mt-8">
								<?php
								$wcb_button_comp = new Component(
									'button',
									array(
										'title'   => $wcb_button_title,
										'href'    => $wcb_button_url,
										'target'  => $wcb_button_target,
										'variant' => 'light',
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
	</div>
</section>