<?php
/**
 * Component: SocialIconLink
 *
 * @package WCB
 *
 * @param array $args {
 *     @type string $href      The URL for the social media profile.
 *     @type string $icon      The social media icon to display ('facebook', 'instagram', 'twitter', 'linkedin').
 *     @type string $class     Additional CSS classes.
 * }
 */

defined( 'ABSPATH' ) || die(); // Ensure that this line do not experiment modifications. it should be always the same.

// Set default values
$args += array(
	'href'  => '#',
	'icon'  => '',
	'class' => '',
);

// Validate and sanitize arguments to prevent critical errors
$args['href']  = is_string( $args['href'] ) ? $args['href'] : '#';
$args['icon']  = is_string( $args['icon'] ) ? $args['icon'] : '';
$args['class'] = is_string( $args['class'] ) ? $args['class'] : '';

if ( empty( $args['icon'] ) ) {
	return;
}

$wcb_icon_map = array(
	'facebook'  => 'https://ai-bot-v2.s3.us-east-2.amazonaws.com/generated/68348df8a2283e7a16d47b9b/68b1d530c444f85250015526/figma/25e4d94e-8f62-8173-b42c-eda4be163f57/519:8758.svg',
	'instagram' => 'https://ai-bot-v2.s3.us-east-2.amazonaws.com/generated/68348df8a2283e7a16d47b9b/68b1d530c444f85250015526/figma/25e4d94e-8f62-8173-b42c-eda4be163f57/519:8760.svg',
	'twitter'   => 'https://ai-bot-v2.s3.us-east-2.amazonaws.com/generated/68348df8a2283e7a16d47b9b/68b1d530c444f85250015526/figma/25e4d94e-8f62-8173-b42c-eda4be163f57/519:8762.svg',
	'linkedin'  => 'https://ai-bot-v2.s3.us-east-2.amazonaws.com/generated/68348df8a2283e7a16d47b9b/68b1d530c444f85250015526/figma/25e4d94e-8f62-8173-b42c-eda4be163f57/519:8764.svg',
);

$wcb_icon_src = isset( $wcb_icon_map[ $args['icon'] ] ) ? $wcb_icon_map[ $args['icon'] ] : '';

if ( empty( $wcb_icon_src ) ) {
	return;
}
?>

<a
	href="<?php echo esc_url( $args['href'] ); ?>"
	target="_blank"
	rel="noopener noreferrer"
	class="wcb-cursor-pointer wcb-transition-opacity wcb-duration-300 hover:wcb-opacity-75 <?php echo esc_attr( $args['class'] ); ?>"
>
	<img src="<?php echo esc_url( $wcb_icon_src ); ?>" alt="<?php echo esc_attr( $args['icon'] ); ?> logo" class="wcb-w-6 wcb-h-6" />
</a>