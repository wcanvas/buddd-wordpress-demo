<?php
/**
 * Component: Heading
 *
 * @package WCB
 *
 * @param array $args {
 *     @type string $as        The HTML tag for the heading (h1-h6).
 *     @type string $class     Additional CSS classes for the component.
 *     @type string $text      The text content of the heading.
 * }
 */

defined( 'ABSPATH' ) || die();

// Set default values.
$args += array(
	'as'    => 'h2',
	'class' => '',
	'text'  => '',
);

// Validate and sanitize arguments.
$args['text']  = is_string( $args['text'] ) ? $args['text'] : '';
$args['class'] = is_string( $args['class'] ) ? $args['class'] : '';

// Validate the heading tag.
$allowed_tags = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' );
$args['as']   = is_string( $args['as'] ) && in_array( strtolower( $args['as'] ), $allowed_tags, true ) ? strtolower( $args['as'] ) : 'h2';

if ( empty( $args['text'] ) ) {
	return;
}

$tag = $args['as'];
?>

<<?php echo esc_attr( $tag ); ?> class="<?php echo esc_attr( $args['class'] ); ?>">
	<?php echo wp_kses_post( $args['text'] ); ?>
</<?php echo esc_attr( $tag ); ?>>