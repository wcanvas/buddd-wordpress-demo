<?php
/**
 * Block: Blog
 *
 * @package WCB
 */

defined( 'ABSPATH' ) || die();

use WCB\Block\BlockWrapper;
use WCB\Functionalities\Component;

// Get ACF fields.
$wcb_preheading       = get_field( 'preheading' );
$wcb_heading_group    = get_field( 'heading' );
$wcb_description      = get_field( 'description' );
$wcb_all_filter_label = get_field( 'all_filter_label' );
$wcb_search_placeholder = get_field( 'search_placeholder' );
$wcb_featured_post_field = get_field( 'featured_post' );

// Validate ACF fields.
$wcb_preheading       = is_string( $wcb_preheading ) ? $wcb_preheading : 'Blog';
$wcb_heading_text     = isset( $wcb_heading_group['text'] ) && is_string( $wcb_heading_group['text'] ) ? $wcb_heading_group['text'] : 'Lorem ipsum dolor sit amet consectetur.';
$wcb_heading_level    = isset( $wcb_heading_group['level'] ) && is_string( $wcb_heading_group['level'] ) ? $wcb_heading_group['level'] : 'h2';
$wcb_description      = is_string( $wcb_description ) ? $wcb_description : 'Explore our collection of blog posts to learn more about the world of wine.';
$wcb_all_filter_label = is_string( $wcb_all_filter_label ) ? $wcb_all_filter_label : 'All';
$wcb_search_placeholder = is_string( $wcb_search_placeholder ) ? $wcb_search_placeholder : 'Search';
$wcb_featured_post_field = is_array( $wcb_featured_post_field ) ? $wcb_featured_post_field : array();

$wcb_featured_post_id = 0;

// --- Featured Post Query ---
$wcb_featured_post_obj = ! empty( $wcb_featured_post_field ) ? $wcb_featured_post_field[0] : null;

$wcb_args_featured = array(
	'post_type'      => 'post',
	'posts_per_page' => 1,
	'post_status'    => 'publish',
);

if ( $wcb_featured_post_obj instanceof WP_Post ) {
	$wcb_args_featured['post__in'] = array( $wcb_featured_post_obj->ID );
	$wcb_args_featured['orderby']  = 'post__in';
} else {
	$wcb_args_featured['orderby'] = 'date';
	$wcb_args_featured['order']   = 'DESC';
}

$wcb_featured_query = new WP_Query( $wcb_args_featured );

// Add a data attribute to the block for the JS to access the featured post ID.
$block_data = BlockWrapper::get_global_block_wrapper_data( $block );
if ( $wcb_featured_query->have_posts() ) {
	// Get the ID of the post that will be featured.
	$wcb_featured_post_id = $wcb_featured_query->posts[0]->ID;
	$block_data           = str_replace( 'class="', 'data-featured-post-id="' . esc_attr( $wcb_featured_post_id ) . '" class="', $block_data );
}

// NOTE: For the filtering and search functionality to work, an AJAX action 'wcb_filter_blog_posts' must be created.
// This action should accept 'category', 'search_term', and 'featured_post_id' parameters,
// query the posts, and return the HTML for the recent posts list.

?>

<section <?php echo wp_kses_post( $block_data ); ?>>
	<div class="wcb-bg-color-30 wcb-py-12 md:wcb-py-24">
		<div class="wcb-container">
			<div class="wcb-max-w-4xl wcb-mx-auto wcb-text-center wcb-mb-8 md:wcb-mb-12">
				<?php
				if ( ! empty( $wcb_preheading ) ) {
					$wcb_preheading_comp = new Component(
						'preheading',
						array(
							'text'  => $wcb_preheading,
							'class' => 'wcb-mb-4',
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
							'class' => 'wcb-font-font-1 wcb-text-4xl md:wcb-text-5xl wcb-font-bold wcb-text-color-3 wcb-leading-tight wcb-mb-6',
						)
					);
					$wcb_heading_comp->render();
				}
				?>
				<?php if ( ! empty( $wcb_description ) ) : ?>
					<p class="wcb-text-base md:wcb-text-lg wcb-text-color-3">
						<?php echo esc_html( $wcb_description ); ?>
					</p>
				<?php endif; ?>
			</div>

			<div class="wcb-flex wcb-flex-col lg:wcb-flex-row wcb-justify-between wcb-items-center wcb-mb-8 md:wcb-mb-12 wcb-gap-6 lg:wcb-gap-8">
				<div class="js-filters-container wcb-flex wcb-flex-wrap wcb-items-center wcb-justify-center wcb-gap-2">
					<?php
					$wcb_all_filter_comp = new Component(
						'filter-button',
						array(
							'text'          => $wcb_all_filter_label,
							'is_active'     => true,
							'data_category' => 'all',
						)
					);
					$wcb_all_filter_comp->render();

					$wcb_categories = get_terms(
						array(
							'taxonomy'   => 'category',
							'hide_empty' => true,
						)
					);

					if ( is_array( $wcb_categories ) && ! is_wp_error( $wcb_categories ) ) {
						foreach ( $wcb_categories as $wcb_category ) {
							$wcb_filter_button_comp = new Component(
								'filter-button',
								array(
									'text'          => $wcb_category->name,
									'is_active'     => false,
									'data_category' => $wcb_category->slug,
								)
							);
							$wcb_filter_button_comp->render();
						}
					}
					?>
				</div>
				<div class="wcb-w-full sm:wcb-max-w-xs">
					<?php
					$wcb_input_comp = new Component(
						'input',
						array(
							'type'        => 'search',
							'placeholder' => $wcb_search_placeholder,
						)
					);
					$wcb_input_comp->render();
					?>
				</div>
			</div>

			<div class="js-posts-grid wcb-grid wcb-grid-cols-1 lg:wcb-grid-cols-2 wcb-gap-8 wcb-items-start">
				<div>
					<?php
					if ( $wcb_featured_query->have_posts() ) {
						while ( $wcb_featured_query->have_posts() ) {
							$wcb_featured_query->the_post();

							$wcb_post_categories = get_the_category();
							$wcb_category_tag    = ! empty( $wcb_post_categories ) ? array(
								'type' => 'category',
								'text' => $wcb_post_categories[0]->name,
							) : null;

							$wcb_read_time    = get_field( 'read_time' );
							$wcb_read_time_tag = ! empty( $wcb_read_time ) ? array(
								'type' => 'readTime',
								'text' => $wcb_read_time,
							) : null;

							$wcb_tags = array_filter( array( $wcb_category_tag, $wcb_read_time_tag ) );

							$wcb_featured_card_comp = new Component(
								'blog-post-card',
								array(
									'variant'                => 'featured',
									'image_url'              => get_the_post_thumbnail_url( get_the_ID(), 'large' ),
									'image_alt'              => get_the_post_thumbnail_caption() ?: get_the_title(),
									'tags'                   => $wcb_tags,
									'title'                  => get_the_title(),
									'description'            => get_the_excerpt(),
									'read_more_href'         => get_the_permalink(),
									'description_text_color' => 'wcb-text-color-3',
								)
							);
							$wcb_featured_card_comp->render();
						}
						wp_reset_postdata();
					}
					?>
				</div>
				<div class="js-recent-posts-list wcb-flex wcb-flex-col wcb-gap-8">
					<?php
					// --- Recent Posts Query ---
					$wcb_args_recent = array(
						'post_type'      => 'post',
						'posts_per_page' => 2,
						'post_status'    => 'publish',
						'orderby'        => 'date',
						'order'          => 'DESC',
					);

					if ( $wcb_featured_post_id > 0 ) {
						$wcb_args_recent['post__not_in'] = array( $wcb_featured_post_id );
					}

					$wcb_recent_query = new WP_Query( $wcb_args_recent );

					if ( $wcb_recent_query->have_posts() ) {
						while ( $wcb_recent_query->have_posts() ) {
							$wcb_recent_query->the_post();

							$wcb_post_categories = get_the_category();
							$wcb_category_tag    = ! empty( $wcb_post_categories ) ? array(
								'type' => 'category',
								'text' => $wcb_post_categories[0]->name,
							) : null;

							$wcb_read_time    = get_field( 'read_time' );
							$wcb_read_time_tag = ! empty( $wcb_read_time ) ? array(
								'type' => 'readTime',
								'text' => $wcb_read_time,
							) : null;

							$wcb_tags = array_filter( array( $wcb_category_tag, $wcb_read_time_tag ) );

							$wcb_recent_card_comp = new Component(
								'blog-post-card',
								array(
									'variant'                => 'default',
									'image_url'              => get_the_post_thumbnail_url( get_the_ID(), 'medium' ),
									'image_alt'              => get_the_post_thumbnail_caption() ?: get_the_title(),
									'tags'                   => $wcb_tags,
									'title'                  => get_the_title(),
									'description'            => get_the_excerpt(),
									'read_more_href'         => get_the_permalink(),
									'description_text_color' => 'wcb-text-color-3',
								)
							);
							$wcb_recent_card_comp->render();
						}
						wp_reset_postdata();
					} else {
						// No recent posts found.
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>