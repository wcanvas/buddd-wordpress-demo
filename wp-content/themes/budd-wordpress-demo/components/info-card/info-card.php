<?php
/**
 * Component: InfoCard
 *
 * @package WCB
 *
 * @param array $args {
 *     @type string $image_url      The URL of the card image.
 *     @type string $image_alt      The alt text for the card image.
 *     @type string $title          The title of the card.
 *     @type string $description    The description of the card.
 *     @type string $class          Additional CSS classes.
 * }
 */

defined( 'ABSPATH' ) || die(); // Ensure that this line do not experiment modifications. it should be always the same.

use WCB\Functionalities\Component;

// Set default values
$args += array(
	'image_url'   => '',
	'image_alt'   => '',
	'title'       => '',
	'description' => '',
	'class'       => '',
);

// Validate and sanitize arguments to prevent critical errors
$args['image_url']   = is_string( $args['image_url'] ) ? $args['image_url'] : '';
$args['image_alt']   = is_string( $args['image_alt'] ) ? $args['image_alt'] : '';
$args['title']       = is_string( $args['title'] ) ? $args['title'] : '';
$args['description'] = is_string( $args['description'] ) ? $args['description'] : '';
$args['class']       = is_string( $args['class'] ) ? $args['class'] : '';

?>

<div class="wcb-flex wcb-flex-col wcb-max-w-[240px] wcb-mx-auto <?php echo esc_attr( $args['class'] ); ?>">
	<?php if ( ! empty( $args['image_url'] ) ) : ?>
		<div class="wcb-mb-6">
			<?php
			$wcb_image = new Component(
				'image',
				array(
					'src'   => $args['image_url'],
					'alt'   => $args['image_alt'],
					'class' => 'wcb-w-full wcb-aspect-[240/311] wcb-object-cover wcb-rounded-radius-3',
				)
			);
			$wcb_image->render();
			?>
		</div>
	<?php endif; ?>

	<div class="wcb-text-center">
		<?php if ( ! empty( $args['title'] ) ) : ?>
			<h3 class="wcb-font-font-4 wcb-font-semibold wcb-text-base wcb-leading-lh-5 wcb-text-color-3 wcb-mb-2">
				<?php echo esc_html( $args['title'] ); ?>
			</h3>
		<?php endif; ?>

		<?php if ( ! empty( $args['description'] ) ) : ?>
			<p class="wcb-font-font-4 wcb-font-normal wcb-text-sm wcb-leading-lh-10 wcb-text-color-3">
				<?php echo esc_html( $args['description'] ); ?>
			</p>
		<?php endif; ?>
	</div>
</div>