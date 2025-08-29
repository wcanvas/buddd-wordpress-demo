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
$wcb_preheading         = get_field( 'preheading' );
$wcb_heading_group      = get_field( 'heading_group' );
$wcb_description        = get_field( 'description' );
$wcb_all_filter_label   = get_field( 'all_filter_label' );
$wcb_search_placeholder = get_field( 'search_placeholder' );
$wcb_featured_post_raw  = get_field( 'featured_post' );

// Validate ACF fields.
$wcb_preheading         = is_string( $wcb_preheading ) ? $wcb_preheading : 'Blog';
$wcb_heading_text       = isset( $wcb_heading_group['text'] ) && is_string( $wcb_heading_group['text'] ) ? $wcb_heading_group['text'] : 'Lorem ipsum dolor sit amet consectetur.';
$wcb_heading_level      = isset( $wcb_heading_group['level'] ) && is_string( $wcb_heading_group['level'] ) ? $wcb_heading_group['level'] : 'h2';
$wcb_description        = is_string( $wcb_description ) ? $wcb_description : 'Explore our collection of blog posts to learn more about the world of wine.';
$wcb_all_filter_label   = is_string( $wcb_all_filter_label ) ? $wcb_all_filter_label : 'All';
$wcb_search_placeholder = is_string( $wcb_search_placeholder ) ? $wcb_search_placeholder : 'Search';
$wcb_featured_post_list = is_array( $wcb_featured_post_raw ) ? $wcb_featured_post_raw : array();
$wcb_featured_post      = ! empty( $wcb_featured_post_list ) ? $wcb_featured_post_list[0] : null;

// Get categories for filters.
$wcb_categories = get_terms(
	array(
		'taxonomy'   => 'category',
		'hide_empty' => true,
	)
);
$wcb_categories = is_array( $wcb_categories ) ? $wcb_categories : array();

// Block wrapper attributes.
$block_data = BlockWrapper::get_global_block_wrapper_data( $block );

// Helper function to prepare post card args.
if ( ! function_exists( 'wcb_get_blog_post_card_args' ) ) {
	function wcb_get_blog_post_card_args( $post_id, $variant = 'default' ) {
		$post_id = is_numeric( $post_id ) ? (int) $post_id : 0;
		if ( ! $post_id ) {
			return array();
		}

		$tags        = array();
		$categories  = get_the_category( $post_id );
		$read_time   = get_field( 'read_time', $post_id );
		$description = get_the_excerpt( $post_id );

		if ( ! empty( $categories ) && is_array( $categories ) ) {
			$tags[] = array(
				'text' => $categories[0]->name,
				'type' => 'category',
			);
		}

		if ( ! empty( $read_time ) && is_string( $read_time ) ) {
			$tags[] = array(
				'text' => $read_time,
				'type' => 'readTime',
			);
		}

		return array(
			'variant'        => $variant,
			'image_url'      => get_the_post_thumbnail_url( $post_id, 'large' ),
			'image_alt'      => get_post_meta( get_post_thumbnail_id( $post_id ), '_wp_attachment_image_alt', true ),
			'tags'           => $tags,
			'title'          => get_the_title( $post_id ),
			'description'    => $description,
			'read_more_href' => get_permalink( $post_id ),
		);
	}
}
?>

<section <?php echo wp_kses_post( $block_data ); ?>>
	<div class="wcb-bg-color-30 wcb-py-16 md:wcb-py-24">
		<div class="wcb-container wcb-mx-auto">
			<div class="wcb-text-center wcb-max-w-3xl wcb-mx-auto wcb-mb-8 md:wcb-mb-12">
				<?php
				$wcb_preheading_comp = new Component(
					'preheading',
					array(
						'text' => $wcb_preheading,
					)
				);
				$wcb_preheading_comp->render();

				$wcb_heading_comp = new Component(
					'heading',
					array(
						'as'    => $wcb_heading_level,
						'text'  => $wcb_heading_text,
						'class' => 'wcb-font-font-4 wcb-font-bold wcb-text-4xl md:wcb-text-[48px] wcb-leading-lh-3 wcb-text-color-3 wcb-mt-4 wcb-mb-6',
					)
				);
				$wcb_heading_comp->render();
				?>
				<p class="wcb-font-font-4 wcb-font-normal wcb-text-base md:wcb-text-lg wcb-leading-lh-9 wcb-text-color-3">
					<?php echo esc_html( $wcb_description ); ?>
				</p>
			</div>

			<div class="wcb-flex wcb-flex-col lg:wcb-flex-row wcb-justify-between wcb-items-center wcb-gap-6 wcb-mb-8 md:wcb-mb-12">
				<div class="wcb-flex wcb-flex-wrap wcb-gap-2 wcb-justify-center">
					<?php
					$wcb_all_filter_btn = new Component(
						'filter-button',
						array(
							'text'          => $wcb_all_filter_label,
							'is_active'     => true,
							'data_category' => 'all',
						)
					);
					$wcb_all_filter_btn->render();

					foreach ( $wcb_categories as $wcb_category ) {
						$wcb_filter_btn = new Component(
							'filter-button',
							array(
								'text'          => $wcb_category->name,
								'data_category' => $wcb_category->slug,
							)
						);
						$wcb_filter_btn->render();
					}
					?>
				</div>
				<div class="wcb-relative wcb-w-full lg:wcb-max-w-xs">
					<?php
					$wcb_input_comp = new Component(
						'input',
						array(
							'type'        => 'search',
							'placeholder' => $wcb_search_placeholder,
							'class'       => 'wcb-w-full',
						)
					);
					$wcb_input_comp->render();
					?>
				</div>
			</div>

			<div class="js-posts-container wcb-grid wcb-grid-cols-1 lg:wcb-grid-cols-2 wcb-gap-8 wcb-items-start">
				<?php
				$wcb_featured_post_id = null;
				if ( $wcb_featured_post instanceof WP_Post ) {
					$wcb_featured_post_id = $wcb_featured_post->ID;

					$wcb_post_card_args = wcb_get_blog_post_card_args( $wcb_featured_post_id, 'featured' );
					$wcb_post_categories  = get_the_category( $wcb_featured_post_id );
					$wcb_category_slugs = ! empty( $wcb_post_categories ) ? wp_list_pluck( $wcb_post_categories, 'slug' ) : array();

					echo '<div class="js-post-item" data-category-slugs="' . esc_attr( implode( ' ', $wcb_category_slugs ) ) . '" data-search-content="' . esc_attr( strtolower( $wcb_post_card_args['title'] . ' ' . $wcb_post_card_args['description'] ) ) . '">';
					$wcb_featured_card = new Component( 'blog-post-card', $wcb_post_card_args );
					$wcb_featured_card->render();
					echo '</div>';
				}
				?>
				<div class="wcb-flex wcb-flex-col wcb-gap-8">
					<?php
					$wcb_recent_posts_args = array(
						'post_type'      => 'post',
						'posts_per_page' => -1,
						'post_status'    => 'publish',
					);

					if ( $wcb_featured_post_id ) {
						$wcb_recent_posts_args['post__not_in'] = array( $wcb_featured_post_id );
					}

					$wcb_recent_posts_query = new WP_Query( $wcb_recent_posts_args );

					if ( $wcb_recent_posts_query->have_posts() ) {
						while ( $wcb_recent_posts_query->have_posts() ) {
							$wcb_recent_posts_query->the_post();
							$wcb_post_id          = get_the_ID();
							$wcb_post_card_args   = wcb_get_blog_post_card_args( $wcb_post_id, 'default' );
							$wcb_post_categories  = get_the_category( $wcb_post_id );
							$wcb_category_slugs = ! empty( $wcb_post_categories ) ? wp_list_pluck( $wcb_post_categories, 'slug' ) : array();

							echo '<div class="js-post-item" data-category-slugs="' . esc_attr( implode( ' ', $wcb_category_slugs ) ) . '" data-search-content="' . esc_attr( strtolower( $wcb_post_card_args['title'] . ' ' . $wcb_post_card_args['description'] ) ) . '">';
							$wcb_default_card = new Component( 'blog-post-card', $wcb_post_card_args );
							$wcb_default_card->render();
							echo '</div>';
						}
						wp_reset_postdata();
					}
					?>
				</div>
			</div>
		</div>
	</div>
</section>