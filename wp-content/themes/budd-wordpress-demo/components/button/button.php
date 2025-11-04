<?php
/**
 * Component: Button
 *
 * @package WCB
 *
 * @param array $args {
 *     @type string $title   The text content of the button.
 *     @type string $href    The URL the button links to.
 *     @type string $target  The target attribute for the link.
 *     @type string $variant The button style variant.
 *     @type string $class   Additional CSS classes.
 * }
 */

defined( 'ABSPATH' ) || die(); // Ensure that this line do not experiment modifications. it should be always the same.

use WCB\Functionalities\Component;

// Set default values
$args += array(
	'title'   => '',
	'href'    => '#',
	'target'  => '',
	'variant' => 'primary',
	'class'   => '',
);

// Validate and sanitize arguments to prevent critical errors
$args['title']   = is_string( $args['title'] ) ? $args['title'] : '';
$args['href']    = is_string( $args['href'] ) ? $args['href'] : '#';
$args['target']  = is_string( $args['target'] ) ? $args['target'] : '';
$args['variant'] = is_string( $args['variant'] ) ? $args['variant'] : 'primary';
$args['class']   = is_string( $args['class'] ) ? $args['class'] : '';

if ( empty( $args['title'] ) ) {
	return;
}

$base_classes = 'wcb-inline-block wcb-text-center wcb-no-underline wcb-rounded-radius-6 wcb-py-3 wcb-px-8 wcb-text-base wcb-cursor-pointer wcb-transition-colors wcb-duration-300 wcb-w-full sm:wcb-w-auto';

$variant_classes = '';
switch ( $args['variant'] ) {
	case 'outline':
		$variant_classes = 'wcb-bg-transparent wcb-border wcb-border-solid wcb-border-color-3 wcb-text-color-3 hover:wcb-bg-color-3 hover:wcb-text-color-42 wcb-font-font-3 wcb-font-medium';
		break;
	case 'light':
		$variant_classes = 'wcb-bg-color-30 wcb-text-color-3 hover:wcb-brightness-95 wcb-font-font-3 wcb-font-medium';
		break;
	case 'outline-inverse':
		$variant_classes = 'wcb-bg-transparent wcb-border wcb-border-solid wcb-border-color-30 wcb-text-color-30 hover:wcb-bg-color-30 hover:wcb-text-color-7 wcb-font-font-4 wcb-font-normal';
		break;
	case 'primary':
	default:
		$variant_classes = 'wcb-bg-color-7 wcb-text-color-42 hover:wcb-bg-color-8 wcb-font-font-3 wcb-font-medium';
		break;
}
?>

<a href="<?php echo esc_url( $args['href'] ); ?>"
	target="<?php echo esc_attr( $args['target'] ); ?>"
	class="<?php echo esc_attr( $base_classes ); ?> <?php echo esc_attr( $variant_classes ); ?> <?php echo esc_attr( $args['class'] ); ?>">
	<?php echo esc_html( $args['title'] ); ?>
</a>