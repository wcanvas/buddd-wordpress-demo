<?php
/**
 * Block: Blog Grid
 * 
 * @package WCB
 */

defined( 'ABSPATH' ) || die(); // Ensure that this line do not experiment modifications. it should be always the same.

use WCB\Block\BlockWrapper;
use WCB\Functionalities\Component;

// Get ACF fields
$wcb_preheading      = get_field( 'preheading' );
$wcb_heading_group   = get_field( 'heading_group' );
$wcb_description     = get_field( 'description' );
$wcb_source_type     = get_field( 'source_type' );
$wcb_selected_posts  = get_field( 'posts' );
$wcb_view_all_button = get_field( 'view_all_button' );

// Validate ACF field values to prevent critical errors
$wcb_preheading      = is_string( $wcb_preheading ) ? $wcb_preheading : '';
$wcb_heading_group   = is_array( $wcb_heading_group ) ? $wcb_heading_group : array();
$wcb_heading_text    = isset( $wcb_heading_group['text'] ) && is_string( $wcb_heading_group['text'] ) ? $wcb_heading_group['text'] : '';
$wcb_heading_level   = isset( $wcb_heading_group['level'] ) && is_string( $wcb_heading_group['level'] ) ? $wcb_heading_group['level'] : 'h2';
$wcb_description     = is_string( $wcb_description ) ? $wcb_description : '';
$wcb_source_type     = is_bool( $wcb_source_type ) ? $wcb_source_type : true;
$wcb_selected_posts  = is_array( $wcb_selected_posts ) ? $wcb_selected_posts : array();
$wcb_view_all_button = is_array( $wcb_view_all_button ) ? $wcb_view_all_button : array();

// Block wrapper attributes
$block_data = BlockWrapper::get_global_block_wrapper_data( $block );

// Setup query arguments
$wcb_args = array();
if ( ! $wcb_source_type && ! empty( $wcb_selected_posts ) ) {
	// Manual selection
	$wcb_post_ids = wp_list_pluck( $wcb_selected_posts, 'ID' );
	$wcb_args     = array(
		'post_type'      => 'post',
		'post__in'       => $wcb_post_ids,
		'orderby'        => 'post__in',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
	);
} else {
	// Automatic fetch (latest 4 posts)
	$wcb_args = array(
		'post_type'      => 'post',
		'posts_per_page' => 4,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'post_status'    => 'publish',
	);
}

$wcb_query = new WP_Query( $wcb_args );
?>

<section <?php echo wp_kses_post( $block_data ); ?>>
	<div class="wcb-bg-color-30 wcb-py-16 lg:wcb-py-24">
		<div class="wcb-container">
			<div class="wcb-text-center wcb-max-w-2xl wcb-mx-auto">
				<?php
				if ( ! empty( $wcb_preheading ) ) {
					$wcb_preheading_comp = new Component(
						'preheading',
						array(
							'text' => $wcb_preheading,
						)
					);
					$wcb_preheading_comp->render();
				}

				if ( ! empty( $wcb_heading_text ) ) {
					$wcb_heading_comp = new Component(
						'heading',
						array(
							'as'    => $wcb_heading_level,
							'text'  => $wcb_heading_text,
							'class' => 'wcb-font-font-4 wcb-font-bold wcb-text-3xl lg:wcb-text-size-1 wcb-leading-lh-7 wcb-text-color-3 wcb-mt-4',
						)
					);
					$wcb_heading_comp->render();
				}
				?>
				<?php if ( ! empty( $wcb_description ) ) : ?>
					<p class="wcb-font-font-4 wcb-text-base md:wcb-text-lg wcb-text-color-11 wcb-mt-6">
						<?php echo esc_html( $wcb_description ); ?>
					</p>
				<?php endif; ?>
			</div>

			<?php if ( $wcb_query->have_posts() ) : ?>
				<div class="wcb-grid wcb-grid-cols-1 lg:wcb-grid-cols-2 wcb-gap-8 wcb-mt-12 lg:wcb-mt-16">
					<?php
					while ( $wcb_query->have_posts() ) {
						$wcb_query->the_post();

						$wcb_tags       = array();
						$wcb_categories = get_the_category();
						if ( ! empty( $wcb_categories ) ) {
							$wcb_tags[] = array(
								'text' => $wcb_categories[0]->name,
								'type' => 'category',
							);
						}

						$wcb_read_time = get_field( 'read_time', get_the_ID() );
						if ( ! empty( $wcb_read_time ) && is_string( $wcb_read_time ) ) {
							$wcb_tags[] = array(
								'text' => $wcb_read_time,
								'type' => 'readTime',
							);
						}

						$wcb_image_url = get_the_post_thumbnail_url( get_the_ID(), 'large' );
						$wcb_image_id  = get_post_thumbnail_id( get_the_ID() );
						$wcb_image_alt = $wcb_image_id ? get_post_meta( $wcb_image_id, '_wp_attachment_image_alt', true ) : get_the_title();

						$wcb_blog_post_card = new Component(
							'blog-post-card',
							array(
								'image_url'      => $wcb_image_url,
								'image_alt'      => $wcb_image_alt,
								'tags'           => $wcb_tags,
								'title'          => get_the_title(),
								'description'    => get_the_excerpt(),
								'read_more_href' => get_permalink(),
								'variant'        => 'default',
								'read_more_text' => __( 'Read more', 'wcb' ),
							)
						);
						$wcb_blog_post_card->render();
					}
					wp_reset_postdata();
					?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $wcb_view_all_button['title'] ) && ! empty( $wcb_view_all_button['url'] ) ) : ?>
				<div class="wcb-text-center wcb-mt-12 lg:wcb-mt-16">
					<?php
					$wcb_button_comp = new Component(
						'button',
						array(
							'title'   => $wcb_view_all_button['title'],
							'href'    => $wcb_view_all_button['url'],
							'target'  => $wcb_view_all_button['target'] ?? '',
							'variant' => 'outline',
						)
					);
					$wcb_button_comp->render();
					?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>