<?php
/**
 * Component: MockupTwo
 *
 * @package WCB
 *
 * @param array $args {
 *     @type array $image ACF image array.
 * }
 */

defined( 'ABSPATH' ) || die();

use WCB\Functionalities\Component;

// Set default values
$args += array(
	'image' => array(),
);

// Validate and sanitize arguments
$args['image'] = is_array( $args['image'] ) ? $args['image'] : array();

$wcb_image_url = ! empty( $args['image']['url'] ) ? $args['image']['url'] : 'https://placehold.co/64x64/285B52/FFFFFF.webp?text=PS';
$wcb_image_alt = ! empty( $args['image']['alt'] ) ? $args['image']['alt'] : 'Polo-Shirt';
?>

<div class="wcb-relative wcb-h-48 md:wcb-h-56">
	<div class="wcb-absolute wcb-top-0 wcb-left-0 wcb-w-[250px] lg:wcb-w-[270px] wcb-bg-color-42 wcb-rounded-xl wcb-shadow-2xl wcb-p-4 wcb-flex wcb-items-center wcb-gap-3 wcb-z-10 wcb-border wcb-border-color-38 wcb-transform -wcb-rotate-3">
		<img src="<?php echo esc_url( $wcb_image_url ); ?>" alt="<?php echo esc_attr( $wcb_image_alt ); ?>" class="wcb-w-12 wcb-h-12 lg:wcb-w-16 lg:wcb-h-16 wcb-rounded-md wcb-object-cover"/>
		<div>
			<p class="wcb-font-font-3 wcb-font-semibold wcb-text-color-7 wcb-text-sm lg:wcb-text-base">Polo-Shirt Summer Tone</p>
			<p class="wcb-font-font-3 wcb-text-xs lg:wcb-text-sm wcb-text-color-6">SKU - PST456WW654</p>
			<div class="wcb-mt-2">
				<?php
				$wcb_link_comp = new Component(
					'link',
					array(
						'href' => '#',
						'text' => 'Order now',
					)
				);
				$wcb_link_comp->render();
				?>
			</div>
		</div>
	</div>
	<div class="wcb-absolute wcb-bottom-0 wcb-right-0 wcb-w-[240px] wcb-bg-color-40 wcb-rounded-xl wcb-shadow-2xl wcb-p-3 wcb-flex wcb-items-center wcb-gap-3 wcb-border wcb-border-color-38 wcb-transform wcb-rotate-2">
		<img src="https://placehold.co/48x48/A9D17B/FFFFFF.webp?text=PV" alt="Positive Vibe Shirt" class="wcb-w-12 wcb-h-12 wcb-rounded-md wcb-object-cover"/>
		<div>
			<p class="wcb-font-font-3 wcb-font-semibold wcb-text-sm wcb-text-color-7">Positive Vibe Shirt</p>
			<p class="wcb-font-font-3 wcb-text-xs wcb-text-color-6">SKU - PST4543GW654</p>
			<div class="wcb-mt-1">
				<?php
				$wcb_tag_comp = new Component(
					'tag',
					array(
						'text' => 'At Risk',
					)
				);
				$wcb_tag_comp->render();
				?>
			</div>
		</div>
	</div>
</div>