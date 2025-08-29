<?php
/**
 * Component: BlogPostCard
 *
 * @package WCB
 *
 * @param array $args {
 *     @type string $image_url             URL of the post image.
 *     @type string $image_alt             Alt text for the post image.
 *     @type array  $tags                  Array of tags (category, read time).
 *     @type string $title                 The post title.
 *     @type string $description           The post description or excerpt.
 *     @type string $read_more_href        URL for the "Read More" link.
 *     @type string $variant               Card variant ('default' or 'featured').
 *     @type string $read_more_text        Text for the "Read More" link.
 *     @type string $description_text_color CSS class for the description text color.
 * }
 */

defined( 'ABSPATH' ) || die();

use WCB\Functionalities\Component;

// Set default values.
$args += array(
	'image_url'              => '',
	'image_alt'              => '',
	'tags'                   => array(),
	'title'                  => '',
	'description'            => '',
	'read_more_href'         => '#',
	'variant'                => 'default',
	'read_more_text'         => __( 'Read More', 'wcb' ),
	'description_text_color' => 'wcb-text-color-11',
);

// Validate and sanitize arguments.
$args['image_url']              = is_string( $args['image_url'] ) ? $args['image_url'] : '';
$args['image_alt']              = is_string( $args['image_alt'] ) ? $args['image_alt'] : '';
$args['tags']                   = is_array( $args['tags'] ) ? $args['tags'] : array();
$args['title']                  = is_string( $args['title'] ) ? $args['title'] : '';
$args['description']            = is_string( $args['description'] ) ? $args['description'] : '';
$args['read_more_href']         = is_string( $args['read_more_href'] ) ? $args['read_more_href'] : '#';
$args['variant']                = is_string( $args['variant'] ) ? $args['variant'] : 'default';
$args['read_more_text']         = is_string( $args['read_more_text'] ) ? $args['read_more_text'] : __( 'Read More', 'wcb' );
$args['description_text_color'] = is_string( $args['description_text_color'] ) ? $args['description_text_color'] : 'wcb-text-color-11';

$is_featured = 'featured' === $args['variant'];

$container_classes   = $is_featured ? 'wcb-flex wcb-flex-col' : 'wcb-flex wcb-flex-col md:wcb-flex-row';
$image_container_classes = $is_featured ? 'wcb-w-full' : 'wcb-w-full md:wcb-w-2/5';
$image_classes           = 'wcb-w-full wcb-h-full wcb-object-cover';
$content_classes         = $is_featured ? 'wcb-p-6 md:wcb-p-8' : 'wcb-p-6 md:wcb-p-8 wcb-flex-1';
$title_classes           = 'wcb-font-font-4 wcb-font-bold wcb-leading-lh-7 wcb-text-color-3 wcb-mb-4 ' . ( $is_featured ? 'wcb-text-2xl md:wcb-text-3xl' : 'wcb-text-xl md:wcb-text-2xl' );

$category_tag = null;
$read_time_tag = null;

foreach ( $args['tags'] as $tag ) {
	if ( isset( $tag['type'] ) && 'category' === $tag['type'] ) {
		$category_tag = $tag;
	}
	if ( isset( $tag['type'] ) && 'readTime' === $tag['type'] ) {
		$read_time_tag = $tag;
	}
}
?>

<div class="<?php echo esc_attr( $container_classes ); ?> wcb-rounded-radius-3 wcb-overflow-hidden wcb-bg-color-42">
	<?php if ( ! empty( $args['image_url'] ) ) : ?>
		<div class="<?php echo esc_attr( $image_container_classes ); ?>">
			<img src="<?php echo esc_url( $args['image_url'] ); ?>" alt="<?php echo esc_attr( $args['image_alt'] ); ?>" class="<?php echo esc_attr( $image_classes ); ?>" />
		</div>
	<?php endif; ?>

	<div class="<?php echo esc_attr( $content_classes ); ?>">
		<div class="wcb-flex wcb-items-center wcb-flex-wrap wcb-gap-4 wcb-mb-4">
			<?php if ( ! empty( $category_tag['text'] ) ) : ?>
				<span class="wcb-bg-color-35 wcb-text-color-3 wcb-text-sm wcb-leading-lh-9 wcb-px-3 wcb-py-1 wcb-rounded-md">
					<?php echo esc_html( $category_tag['text'] ); ?>
				</span>
			<?php endif; ?>
			<?php if ( ! empty( $read_time_tag['text'] ) ) : ?>
				<span class="wcb-font-font-4 wcb-font-semibold wcb-text-sm wcb-leading-lh-9 wcb-text-color-3">
					<?php echo esc_html( $read_time_tag['text'] ); ?>
				</span>
			<?php endif; ?>
		</div>
		<?php if ( ! empty( $args['title'] ) ) : ?>
			<h3 class="<?php echo esc_attr( $title_classes ); ?>">
				<?php echo esc_html( $args['title'] ); ?>
			</h3>
		<?php endif; ?>
		<?php if ( ! empty( $args['description'] ) ) : ?>
			<p class="wcb-font-font-4 wcb-font-normal wcb-text-base wcb-leading-lh-9 wcb-mb-6 <?php echo esc_attr( $args['description_text_color'] ); ?>">
				<?php echo esc_html( $args['description'] ); ?>
			</p>
		<?php endif; ?>
		<a href="<?php echo esc_url( $args['read_more_href'] ); ?>" class="wcb-flex wcb-items-center wcb-gap-2 wcb-font-font-4 wcb-font-semibold wcb-text-base wcb-leading-lh-9 wcb-text-color-3 wcb-no-underline wcb-group wcb-cursor-pointer">
			<span><?php echo esc_html( $args['read_more_text'] ); ?></span>
			<img
				src="https://ai-bot-v2.s3.us-east-2.amazonaws.com/generated/68348df8a2283e7a16d47b9b/68b1d530c444f85250015526/figma/25e4d94e-8f62-81e3-b594-c53f530d6409/1845:165.svg"
				alt="<?php esc_attr_e( 'Arrow icon', 'wcb' ); ?>"
				class="wcb-w-4 wcb-h-4 wcb-transition-transform wcb-duration-300 group-hover/item:wcb-translate-x-1"
			/>
		</a>
	</div>
</div>