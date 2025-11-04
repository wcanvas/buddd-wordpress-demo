<?php
/**
 * Block: Hero
 * 
 * @package WCB
 */

defined( 'ABSPATH' ) || die(); // Ensure that this line do not experiment modifications. it should be always the same.

use WCB\Block\BlockWrapper;
use WCB\Functionalities\Component;

// Get ACF fields
$wcb_heading_group = get_field( 'heading_group' );
$wcb_description   = get_field( 'description' );
$wcb_button        = get_field( 'button' );
$wcb_image         = get_field( 'image' );

// Validate ACF field values to prevent critical errors
$wcb_heading     = isset( $wcb_heading_group['heading'] ) && is_string( $wcb_heading_group['heading'] ) ? $wcb_heading_group['heading'] : '';
$wcb_heading_tag = isset( $wcb_heading_group['heading_tag'] ) && is_string( $wcb_heading_group['heading_tag'] ) ? $wcb_heading_group['heading_tag'] : 'h1';
$wcb_description = is_string( $wcb_description ) ? $wcb_description : '';
$wcb_button      = is_array( $wcb_button ) ? $wcb_button : array();
$wcb_image       = is_array( $wcb_image ) ? $wcb_image : array();

// Block wrapper attributes
$block_data = BlockWrapper::get_global_block_wrapper_data( $block );
?>

<section <?php echo wp_kses_post( $block_data ); ?>>
	<div class="wcb-bg-color-7">
		<div class="wcb-container wcb-py-16 md:wcb-py-20 lg:wcb-py-space-5">
			<div class="wcb-grid lg:wcb-grid-cols-2 wcb-gap-12 lg:wcb-gap-8 wcb-items-center">
				<div class="wcb-text-center lg:wcb-text-left">
					<?php
					if ( ! empty( $wcb_heading ) ) {
						$wcb_heading_component = new Component(
							'heading',
							array(
								'as'    => $wcb_heading_tag,
								'text'  => $wcb_heading,
								'class' => 'wcb-font-font-4 wcb-font-bold wcb-text-5xl md:wcb-text-7xl lg:wcb-text-size-2 wcb-text-color-30 wcb-leading-lh-1 wcb-tracking-[-0.02em] wcb-mb-6',
							)
						);
						$wcb_heading_component->render();
					}
					?>

					<?php if ( ! empty( $wcb_description ) ) : ?>
						<p class="wcb-font-font-4 wcb-font-normal wcb-text-lg wcb-text-color-30 wcb-leading-lh-11 wcb-max-w-lg wcb-mx-auto lg:wcb-mx-0">
							<?php echo esc_html( $wcb_description ); ?>
						</p>
					<?php endif; ?>

					<?php
					if ( ! empty( $wcb_button['title'] ) && ! empty( $wcb_button['url'] ) ) {
						?>
						<div class="wcb-mt-8">
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
						<?php
					}
					?>
				</div>
				<div class="wcb-flex wcb-justify-center lg:wcb-justify-end">
					<?php
					if ( ! empty( $wcb_image['url'] ) ) {
						$wcb_image_component = new Component(
							'image',
							array(
								'src'   => $wcb_image['url'],
								'alt'   => $wcb_image['alt'],
								'class' => 'wcb-rounded-radius-3 wcb-w-full wcb-max-w-lg lg:wcb-max-w-full wcb-object-cover',
							)
						);
						$wcb_image_component->render();
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>