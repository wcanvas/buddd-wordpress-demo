<?php
/**
 * Component: Logo
 *
 * @package WCB
 *
 * @param array $args {
 *     @type string $href      The URL the logo links to.
 *     @type string $variant   Variant of the component ('default' or 'inverse').
 *     @type string $class     Additional CSS classes.
 *     @type array  $image     ACF image array.
 * }
 */

defined( 'ABSPATH' ) || die(); // Ensure that this line do not experiment modifications. it should be always the same.

// Set default values
$args += array(
	'href'    => '#',
	'variant' => 'default',
	'class'   => '',
	'image'   => array(),
);

// Validate and sanitize arguments
$args['href']    = is_string( $args['href'] ) ? $args['href'] : '#';
$args['variant'] = is_string( $args['variant'] ) ? $args['variant'] : 'default';
$args['class']   = is_string( $args['class'] ) ? $args['class'] : '';
$args['image']   = is_array( $args['image'] ) ? $args['image'] : array();

$wcb_color_class = 'default' === $args['variant'] ? 'wcb-text-color-3' : 'wcb-text-color-30';
?>
<a href="<?php echo esc_url( $args['href'] ); ?>" class="wcb-flex wcb-items-center wcb-gap-2.5 wcb-no-underline wcb-cursor-pointer <?php echo esc_attr( $wcb_color_class ); ?> <?php echo esc_attr( $args['class'] ); ?>">
	<?php if ( ! empty( $args['image']['url'] ) ) : ?>
		<img src="<?php echo esc_url( $args['image']['url'] ); ?>" alt="<?php echo esc_attr( ! empty( $args['image']['alt'] ) ? $args['image']['alt'] : 'TrailHive Logo' ); ?>" class="wcb-h-auto wcb-max-h-8">
	<?php else : ?>
		<svg width="32" height="27" viewBox="0 0 24 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg" class="wcb-w-6 wcb-h-auto md:wcb-w-8">
			<path d="M16.4147 19.5L11.9397 12.3333L7.46473 19.5H0.291016L9.70837 5.83333L13.0117 10.25L15.3397 6.91667L11.9397 2.16667L23.541 19.5H16.4147Z" />
		</svg>
		<span class="wcb-font-font-4 wcb-font-bold wcb-text-2xl md:wcb-text-[32px] wcb-leading-none -wcb-tracking-[0.03em]">TrailHive</span>
	<?php endif; ?>
</a>