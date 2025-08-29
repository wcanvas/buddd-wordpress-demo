<?php
/**
 * Block: Blog Archive
 *
 * @package WCB
 */

defined( 'ABSPATH' ) || die();

use WCB\Block\BlockWrapper;
use WCB\Functionalities\Component;

// Get ACF fields.
$wcb_posts_per_page = get_field( 'posts_per_page' );

// Validate ACF field values to prevent critical errors.
$wcb_posts_per_page = is_numeric( $wcb_posts_per_page ) ? (int) $wcb_posts_per_page : 8;

// Block wrapper attributes.
$block_data = BlockWrapper::get_global_block_wrapper_data( $block );

// WP_Query arguments.
$wcb_args = array(
	'post_type'      => 'post',
	'posts_per_page' => $wcb_posts_per_page,
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'order'          => 'DESC',
);

$wcb_query = new WP_Query( $wcb_args );
?>

<section <?php echo wp_kses_post( $block_data ); ?>>
	<div class="wcb-bg-color-30">
		<div class="wcb-container wcb-py-12 lg:wcb-py-20">
			<?php if ( $wcb_query->have_posts() ) : ?>
				<div class="wcb-grid wcb-grid-cols-1 lg:wcb-grid-cols-2 wcb-gap-x-8 wcb-gap-y-10">
					<?php
					while ( $wcb_query->have_posts() ) :
						$wcb_query->the_post();

						$wcb_post_id      = get_the_ID();
						$wcb_image_url    = get_the_post_thumbnail_url( $wcb_post_id, 'large' );
						$wcb_image_id     = get_post_thumbnail_id( $wcb_post_id );
						$wcb_image_alt    = $wcb_image_id ? get_post_meta( $wcb_image_id, '_wp_attachment_image_alt', true ) : get_the_title();
						$wcb_title        = get_the_title();
						$wcb_excerpt      = get_the_excerpt();
						$wcb_permalink    = get_permalink();
						$wcb_read_time    = get_field( 'read_time', $wcb_post_id );
						$wcb_categories   = get_the_category( $wcb_post_id );
						$wcb_category_tag = null;
						$wcb_read_time_tag = null;
						$wcb_tags         = array();

						if ( ! empty( $wcb_categories ) && is_array( $wcb_categories ) ) {
							$wcb_category_tag = array(
								'type' => 'category',
								'text' => $wcb_categories[0]->name,
							);
							$wcb_tags[]       = $wcb_category_tag;
						}

						if ( ! empty( $wcb_read_time ) && is_string( $wcb_read_time ) ) {
							$wcb_read_time_tag = array(
								'type' => 'readTime',
								'text' => $wcb_read_time,
							);
							$wcb_tags[]        = $wcb_read_time_tag;
						}

						$wcb_blog_post_card = new Component(
							'blog-post-card',
							array(
								'image_url'      => $wcb_image_url,
								'image_alt'      => $wcb_image_alt,
								'tags'           => $wcb_tags,
								'title'          => $wcb_title,
								'description'    => $wcb_excerpt,
								'read_more_href' => $wcb_permalink,
								'variant'        => 'default',
							)
						);
						$wcb_blog_post_card->render();
						?>
					<?php endwhile; ?>
				</div>
				<?php wp_reset_postdata(); ?>
			<?php endif; ?>
		</div>
	</div>
</section>