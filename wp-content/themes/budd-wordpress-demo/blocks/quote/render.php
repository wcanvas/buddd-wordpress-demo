<?php
/**
 * Block: Quote
 *
 * @package WCB
 */

defined( 'ABSPATH' ) || die();

use WCB\Block\BlockWrapper;
use WCB\Functionalities\Component;

// Get ACF fields
$wcb_quote_text     = get_field( 'quote_text' );
$wcb_author_name    = get_field( 'author_name' );
$wcb_author_title   = get_field( 'author_title' );
$wcb_background_image = get_field( 'background_image' );

// Validate ACF field values to prevent critical errors
$wcb_quote_text     = is_string( $wcb_quote_text ) ? $wcb_quote_text : '';
$wcb_author_name    = is_string( $wcb_author_name ) ? $wcb_author_name : '';
$wcb_author_title   = is_string( $wcb_author_title ) ? $wcb_author_title : '';
$wcb_background_image = is_array( $wcb_background_image ) ? $wcb_background_image : array();

// Block wrapper attributes
$wcb_block_data = BlockWrapper::get_global_block_wrapper_data( $block );

?>

<section <?php echo wp_kses_post( $wcb_block_data ); ?>>
	<div class="wcb-relative wcb-bg-color-7 wcb-text-color-42 wcb-overflow-hidden">
		<?php if ( ! empty( $wcb_background_image['url'] ) ) : ?>
			<div class="wcb-absolute wcb-inset-0 wcb-z-0">
				<?php
				$wcb_image = new Component(
					'image',
					array(
						'src'   => $wcb_background_image['url'],
						'alt'   => ! empty( $wcb_background_image['alt'] ) ? $wcb_background_image['alt'] : 'Background',
						'class' => 'wcb-w-full wcb-h-full wcb-object-cover',
					)
				);
				$wcb_image->render();
				?>
				<div class="wcb-absolute wcb-inset-0 wcb-bg-color-3 wcb-opacity-75"></div>
			</div>
		<?php endif; ?>

		<div class="wcb-relative wcb-z-10 wcb-container wcb-py-16 sm:wcb-py-24 md:wcb-py-32">
			<div class="wcb-max-w-4xl wcb-mx-auto wcb-text-center wcb-font-font-1">
				<?php if ( ! empty( $wcb_quote_text ) ) : ?>
					<blockquote class="wcb-text-3xl sm:wcb-text-4xl md:wcb-text-5xl wcb-leading-tight md:wcb-leading-snug">
						<p>"<?php echo esc_html( $wcb_quote_text ); ?>"</p>
					</blockquote>
				<?php endif; ?>

				<?php if ( ! empty( $wcb_author_name ) || ! empty( $wcb_author_title ) ) : ?>
					<footer class="wcb-mt-6 md:wcb-mt-8">
						<?php if ( ! empty( $wcb_author_name ) ) : ?>
							<p class="wcb-font-semibold wcb-text-lg md:wcb-text-xl wcb-tracking-wide">
								<?php echo esc_html( $wcb_author_name ); ?>
							</p>
						<?php endif; ?>
						<?php if ( ! empty( $wcb_author_title ) ) : ?>
							<p class="wcb-text-base md:wcb-text-lg wcb-opacity-90 wcb-mt-1">
								<?php echo esc_html( $wcb_author_title ); ?>
							</p>
						<?php endif; ?>
					</footer>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>