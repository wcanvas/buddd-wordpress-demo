<?php
/**
 * Component: Feature Showcase Card
 *
 * @package WCB
 *
 * @param array $args {
 *     @type string $title        Optional title for the card.
 *     @type string $description  Optional description for the card.
 *     @type string $layout       Layout variant: 'text_top', 'text_left', 'text_right'.
 *     @type string $class        Additional CSS classes.
 *     @type string $children     HTML content for the children.
 * }
 */

defined( 'ABSPATH' ) || die();

use WCB\Functionalities\Component;

// Set default values
$args += array(
	'title'       => '',
	'description' => '',
	'layout'      => 'text_top',
	'class'       => '',
	'children'    => '',
);

// Validate and sanitize arguments
$args['title']       = is_string( $args['title'] ) ? $args['title'] : '';
$args['description'] = is_string( $args['description'] ) ? $args['description'] : '';
$args['layout']      = is_string( $args['layout'] ) && in_array( $args['layout'], array( 'text_top', 'text_left', 'text_right' ), true ) ? $args['layout'] : 'text_top';
$args['class']       = is_string( $args['class'] ) ? $args['class'] : '';
$args['children']    = is_string( $args['children'] ) ? $args['children'] : '';

// Layout classes based on React component
$flex_container_classes = array(
	'text_left'  => 'wcb-flex-col lg:wcb-flex-row',
	'text_right' => 'wcb-flex-col lg:wcb-flex-row-reverse',
	'text_top'   => 'wcb-flex-col',
);

$text_container_classes = array(
	'text_left'  => 'lg:wcb-w-1/3',
	'text_right' => 'lg:wcb-w-1/2 wcb-text-left',
	'text_top'   => 'wcb-w-full wcb-text-left',
);

$children_container_classes = array(
	'text_left'  => 'lg:wcb-w-2/3',
	'text_right' => 'lg:wcb-w-1/2',
	'text_top'   => 'wcb-w-full',
);

$current_flex_class     = $flex_container_classes[ $args['layout'] ];
$current_text_class     = $text_container_classes[ $args['layout'] ];
$current_children_class = $children_container_classes[ $args['layout'] ];
?>

<div class="wcb-bg-white wcb-bg-opacity-10 wcb-p-6 md:wcb-p-10 wcb-rounded-radius-3 wcb-h-full wcb-backdrop-blur-sm wcb-border wcb-border-white wcb-border-opacity-20 <?php echo esc_attr( $args['class'] ); ?>">
	<div class="wcb-flex <?php echo esc_attr( $current_flex_class ); ?> wcb-gap-x-12 wcb-gap-y-8 wcb-h-full wcb-items-center">
		<?php if ( ! empty( $args['title'] ) || ! empty( $args['description'] ) ) : ?>
			<div class="<?php echo esc_attr( $current_text_class ); ?>">
				<?php
				if ( ! empty( $args['title'] ) ) {
					$wcb_heading_comp = new Component(
						'heading',
						array(
							'as'    => 'h3',
							'text'  => $args['title'],
							'class' => 'wcb-font-font-1 wcb-text-2xl lg:wcb-text-3xl xl:wcb-text-4xl wcb-font-normal wcb-text-color-7 wcb-mb-4 wcb-leading-tight',
						)
					);
					$wcb_heading_comp->render();
				}
				?>
				<?php if ( ! empty( $args['description'] ) ) : ?>
					<p class="wcb-font-font-1 wcb-text-base lg:wcb-text-lg wcb-text-color-7 wcb-leading-snug"><?php echo esc_html( $args['description'] ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<div class="<?php echo esc_attr( $current_children_class ); ?> wcb-w-full">
			<?php echo wp_kses_post( $args['children'] ); ?>
		</div>
	</div>
</div>