<?php
/**
 * Block: Quote
 *
 * @package WCB
 */

defined( 'ABSPATH' ) || die();

use WCB\Block\BlockWrapper;
use WCB\Functionalities\Component;

// Get ACF fields.
$wcb_pre_heading      = get_field( 'pre_heading' );
$wcb_quote            = get_field( 'quote' );
$wcb_author           = get_field( 'author' );
$wcb_background_image = get_field( 'background_image' );
$wcb_show_logo_cloud  = get_field( 'show_logo_cloud' );
$wcb_logo_cloud_title = get_field( 'logo_cloud_title' );
$wcb_logos            = get_field( 'logos' );
$wcb_show_cta         = get_field( 'show_cta' );
$wcb_cta_button       = get_field( 'cta_button' );

// Validate ACF field values.
$wcb_pre_heading      = is_string( $wcb_pre_heading ) ? $wcb_pre_heading : '';
$wcb_quote            = is_string( $wcb_quote ) ? $wcb_quote : '';
$wcb_author           = is_string( $wcb_author ) ? $wcb_author : '';
$wcb_background_image = is_array( $wcb_background_image ) ? $wcb_background_image : array();
$wcb_show_logo_cloud  = is_bool( $wcb_show_logo_cloud ) ? $wcb_show_logo_cloud : false;
$wcb_logo_cloud_title = is_string( $wcb_logo_cloud_title ) ? $wcb_logo_cloud_title : '';
$wcb_logos            = is_array( $wcb_logos ) ? $wcb_logos : array();
$wcb_show_cta         = is_bool( $wcb_show_cta ) ? $wcb_show_cta : false;
$wcb_cta_button       = is_array( $wcb_cta_button ) ? $wcb_cta_button : array();

// Block wrapper attributes.
$block_data = BlockWrapper::get_global_block_wrapper_data( $block );

$wcb_bg_img_url = ! empty( $wcb_background_image['url'] ) ? $wcb_background_image['url'] : '';
$wcb_bg_img_alt = ! empty( $wcb_background_image['alt'] ) ? $wcb_background_image['alt'] : '';

?>

<section <?php echo wp_kses_post( $block_data ); ?>>
	<div class="wcb-relative wcb-bg-color-7">
		<?php if ( ! empty( $wcb_bg_img_url ) ) : ?>
			<div class="wcb-absolute wcb-inset-0 wcb-z-0">
				<img src="<?php echo esc_url( $wcb_bg_img_url ); ?>" alt="<?php echo esc_attr( $wcb_bg_img_alt ); ?>" class="wcb-w-full wcb-h-full wcb-object-cover" />
				<div class="wcb-absolute wcb-inset-0 wcb-bg-color-7/80"></div>
			</div>
		<?php endif; ?>

		<div class="wcb-relative wcb-z-10 wcb-container wcb-py-16 sm:wcb-py-20 lg:wcb-py-28">
			<div class="wcb-max-w-4xl wcb-mx-auto wcb-text-center">
				<?php
				$wcb_testimonial = new Component(
					'testimonial',
					array(
						'preheading' => $wcb_pre_heading,
						'quote'      => $wcb_quote,
						'author'     => $wcb_author,
					)
				);
				$wcb_testimonial->render();
				?>
			</div>

			<?php if ( $wcb_show_logo_cloud && ! empty( $wcb_logos ) ) : ?>
				<div class="wcb-mt-12 lg:wcb-mt-16">
					<?php
					$wcb_formatted_logos = array();
					foreach ( $wcb_logos as $item ) {
						$logo_image = isset( $item['logo'] ) && is_array( $item['logo'] ) ? $item['logo'] : array();
						if ( ! empty( $logo_image['url'] ) ) {
							$wcb_formatted_logos[] = array(
								'src' => $logo_image['url'],
								'alt' => ! empty( $logo_image['alt'] ) ? $logo_image['alt'] : '',
							);
						}
					}

					$wcb_logo_cloud = new Component(
						'logo-cloud',
						array(
							'title' => $wcb_logo_cloud_title,
							'logos' => $wcb_formatted_logos,
						)
					);
					$wcb_logo_cloud->render();
					?>
				</div>
			<?php endif; ?>

			<?php if ( $wcb_show_cta && ! empty( $wcb_cta_button['url'] ) && ! empty( $wcb_cta_button['title'] ) ) : ?>
				<div class="wcb-mt-8 wcb-text-center">
					<?php
					$wcb_button = new Component(
						'button',
						array(
							'title'   => $wcb_cta_button['title'],
							'href'    => $wcb_cta_button['url'],
							'target'  => ! empty( $wcb_cta_button['target'] ) ? $wcb_cta_button['target'] : '',
							'variant' => 'light',
						)
					);
					$wcb_button->render();
					?>
				</div>
			<?php endif; ?>

		</div>
	</div>
</section>