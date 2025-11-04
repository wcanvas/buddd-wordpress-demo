<?php
/**
 * Block: Full Width Banner
 *
 * @package WCB
 */

defined( 'ABSPATH' ) || die(); // Ensure that this line do not experiment modifications. it should be always the same.

use WCB\Block\BlockWrapper;
use WCB\Functionalities\Component;

// Get ACF fields
$wcb_heading = get_field( 'heading' );
$wcb_image   = get_field( 'image' );

// Validate ACF field values to prevent critical errors
$wcb_heading_text  = isset( $wcb_heading['text'] ) && is_string( $wcb_heading['text'] ) ? $wcb_heading['text'] : '';
$wcb_heading_level = isset( $wcb_heading['level'] ) && is_string( $wcb_heading['level'] ) ? $wcb_heading['level'] : 'h2';
$wcb_image         = is_array( $wcb_image ) ? $wcb_image : array();
$wcb_image_url     = isset( $wcb_image['url'] ) && is_string( $wcb_image['url'] ) ? $wcb_image['url'] : '';
$wcb_image_alt     = isset( $wcb_image['alt'] ) && is_string( $wcb_image['alt'] ) ? $wcb_image['alt'] : '';

// Block wrapper attributes
$block_data = BlockWrapper::get_global_block_wrapper_data( $block );

?>

<section <?php echo wp_kses_post( $block_data ); ?>>
	<div class="wcb-bg-color-30">
		<div class="wcb-container wcb-py-12 md:wcb-py-16 lg:wcb-py-24">
			<?php
			if ( ! empty( $wcb_heading_text ) ) {
				$wcb_heading_component = new Component(
					'heading',
					array(
						'as'    => $wcb_heading_level,
						'text'  => $wcb_heading_text,
						'class' => 'wcb-text-center wcb-font-font-1 wcb-text-color-3 wcb-text-[32px] md:wcb-text-size-1 wcb-font-normal wcb-max-w-4xl wcb-mx-auto wcb-leading-lh-4 md:wcb-leading-lh-12 wcb-mb-8 md:wcb-mb-10',
					)
				);
				$wcb_heading_component->render();
			}
			?>

			<?php
			if ( ! empty( $wcb_image_url ) ) {
				$wcb_image_component = new Component(
					'image',
					array(
						'src'   => $wcb_image_url,
						'alt'   => $wcb_image_alt,
						'class' => 'wcb-w-full wcb-h-auto wcb-rounded-radius-3',
					)
				);
				$wcb_image_component->render();
			}
			?>
		</div>
	</div>
</section>