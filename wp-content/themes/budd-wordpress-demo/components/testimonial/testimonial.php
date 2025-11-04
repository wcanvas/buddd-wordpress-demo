<?php
/**
 * Component: Testimonial
 *
 * @package WCB
 *
 * @param array $args {
 *     @type string $preheading The preheading text.
 *     @type string $quote      The quote text.
 *     @type string $author     The author's name.
 *     @type string $class      Additional CSS classes for the component.
 * }
 */

defined( 'ABSPATH' ) || die();

use WCB\Functionalities\Component;

// Set default values.
$args += array(
	'preheading' => '',
	'quote'      => '',
	'author'     => '',
	'class'      => '',
);

// Validate and sanitize arguments.
$args['preheading'] = is_string( $args['preheading'] ) ? $args['preheading'] : '';
$args['quote']      = is_string( $args['quote'] ) ? $args['quote'] : '';
$args['author']     = is_string( $args['author'] ) ? $args['author'] : '';
$args['class']      = is_string( $args['class'] ) ? $args['class'] : '';

?>

<div class="wcb-flex wcb-flex-col wcb-items-center wcb-gap-y-6 <?php echo esc_attr( $args['class'] ); ?>">
	<?php
	if ( ! empty( $args['preheading'] ) ) {
		$wcb_preheading = new Component(
			'preheading',
			array(
				'text'  => $args['preheading'],
				'class' => 'wcb-text-color-30',
			)
		);
		$wcb_preheading->render();
	}
	?>

	<?php if ( ! empty( $args['quote'] ) ) : ?>
		<blockquote class="wcb-w-full">
			<p class="wcb-text-center wcb-font-font-4 wcb-font-bold wcb-text-3xl md:wcb-text-5xl wcb-leading-lh-2 wcb-tracking-[-0.0416em] wcb-text-color-30">
				<?php echo esc_html( $args['quote'] ); ?>
			</p>
		</blockquote>
	<?php endif; ?>

	<?php if ( ! empty( $args['author'] ) ) : ?>
		<p class="wcb-font-font-4 wcb-font-bold wcb-text-xl md:wcb-text-2xl wcb-leading-lh-3 wcb-text-color-30 wcb-text-center">
			<?php echo esc_html( $args['author'] ); ?>
		</p>
	<?php endif; ?>
</div>