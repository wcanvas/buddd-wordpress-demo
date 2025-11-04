<?php
/**
 * Block: Section Cards
 *
 * @package WCB
 */

defined( 'ABSPATH' ) || die();

use WCB\Block\BlockWrapper;
use WCB\Functionalities\Component;

// Get ACF fields
$wcb_heading = get_field( 'heading' );
$wcb_cards   = get_field( 'cards' );

// Validate ACF field values
$wcb_heading_text = isset( $wcb_heading['text'] ) && is_string( $wcb_heading['text'] ) ? $wcb_heading['text'] : '';
$wcb_heading_tag  = isset( $wcb_heading['level'] ) && is_string( $wcb_heading['level'] ) ? $wcb_heading['level'] : 'h2';
$wcb_cards        = is_array( $wcb_cards ) ? $wcb_cards : array();

// Block wrapper attributes
$block_data = BlockWrapper::get_global_block_wrapper_data( $block );
?>

<section <?php echo wp_kses_post( $block_data ); ?>>
	<div class="wcb-bg-color-30 wcb-py-16 sm:wcb-py-24">
		<div class="wcb-container">
			<?php
			if ( ! empty( $wcb_heading_text ) ) {
				$wcb_heading_comp = new Component(
					'heading',
					array(
						'as'    => $wcb_heading_tag,
						'text'  => $wcb_heading_text,
						'class' => 'wcb-text-center wcb-font-font-1 wcb-text-3xl sm:wcb-text-4xl md:wcb-text-5xl wcb-font-medium wcb-text-color-7 wcb-max-w-3xl wcb-mx-auto wcb-mb-8 md:wcb-mb-12',
					)
				);
				$wcb_heading_comp->render();
			}
			?>

			<?php if ( ! empty( $wcb_cards ) ) : ?>
				<div class="wcb-grid wcb-grid-cols-1 lg:wcb-grid-cols-2 wcb-gap-6">
					<?php foreach ( $wcb_cards as $key => $wcb_card ) : ?>
						<?php
						// Validate card sub-fields.
						$wcb_title         = isset( $wcb_card['title'] ) && is_string( $wcb_card['title'] ) ? $wcb_card['title'] : '';
						$wcb_description   = isset( $wcb_card['description'] ) && is_string( $wcb_card['description'] ) ? $wcb_card['description'] : '';
						$wcb_image         = isset( $wcb_card['image'] ) && is_array( $wcb_card['image'] ) ? $wcb_card['image'] : array();
						$wcb_layout        = isset( $wcb_card['card_layout'] ) && is_string( $wcb_card['card_layout'] ) ? $wcb_card['card_layout'] : 'text_top';
						$wcb_is_full_width = isset( $wcb_card['is_full_width'] ) ? (bool) $wcb_card['is_full_width'] : false;

						// Determine which mockup to render based on loop index, cycling through 4 mockups.
						$wcb_mockup_id = ( $key % 4 ) + 1;

						ob_start();
						switch ( $wcb_mockup_id ) {
							case 1:
								$wcb_mockup_one_comp = new Component( 'mockup-one' );
								$wcb_mockup_one_comp->render();
								break;
							case 2:
								$wcb_mockup_two_comp = new Component( 'mockup-two', array( 'image' => $wcb_image ) );
								$wcb_mockup_two_comp->render();
								break;
							case 3:
								$wcb_mockup_three_comp = new Component( 'mockup-three' );
								$wcb_mockup_three_comp->render();
								break;
							case 4:
								$wcb_mockup_four_comp = new Component( 'mockup-four' );
								$wcb_mockup_four_comp->render();
								break;
						}
						$wcb_mockup_content = ob_get_clean();

						$wcb_wrapper_class = $wcb_is_full_width ? 'lg:wcb-col-span-2' : '';
						?>
						<div class="<?php echo esc_attr( $wcb_wrapper_class ); ?>">
							<?php
							$wcb_card_comp = new Component(
								'feature-showcase-card',
								array(
									'title'       => $wcb_title,
									'description' => $wcb_description,
									'layout'      => $wcb_layout,
									'children'    => $wcb_mockup_content,
								)
							);
							$wcb_card_comp->render();
							?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>