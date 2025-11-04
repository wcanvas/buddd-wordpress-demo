<?php
/**
 * Component: MockupOne
 *
 * @package WCB
 */

defined( 'ABSPATH' ) || die();

use WCB\Functionalities\Component;

$wcb_filters = array( 'All', 'Best Sellers', 'Excess' );
$wcb_products = array(
	array(
		'img'  => 'https://placehold.co/48x48/F9D167/1B1A1A.webp?text=OW',
		'name' => 'Orange White Collar',
		'sku'  => 'SKU - PST456WW654',
	),
	array(
		'img'  => 'https://placehold.co/48x48/F5F5F6/1B1A1A.webp?text=T',
		'name' => 'Though',
		'sku'  => 'SKU - PS',
	),
	array(
		'img'  => 'https://placehold.co/48x48/D9EFDE/1B1A1A.webp?text=NT',
		'name' => 'Navy T-Shirt',
		'sku'  => 'SKU - PST456WW654',
	),
	array(
		'img'  => 'https://placehold.co/48x48/F5F5F6/1B1A1A.webp?text=S',
		'name' => 'Streetw',
		'sku'  => 'SKU - PS',
	),
);
?>

<div class="wcb-bg-color-42 wcb-rounded-xl wcb-shadow-2xl wcb-p-4 wcb-border wcb-border-color-38">
	<div class="wcb-flex wcb-items-center wcb-gap-2 wcb-border-b wcb-border-color-38 wcb-pb-3 wcb-mb-3 wcb-flex-wrap">
		<?php
		foreach ( $wcb_filters as $wcb_filter ) {
			$wcb_filter_button_comp = new Component(
				'filter-button',
				array(
					'text'            => $wcb_filter,
					'is_active'       => true,
					'show_close_icon' => true,
				)
			);
			$wcb_filter_button_comp->render();
		}
		?>
	</div>
	<div class="wcb-grid wcb-grid-cols-1 sm:wcb-grid-cols-2 wcb-gap-4">
		<?php foreach ( $wcb_products as $wcb_product ) : ?>
			<div class="wcb-flex wcb-items-center wcb-gap-3">
				<img src="<?php echo esc_url( $wcb_product['img'] ); ?>" alt="<?php echo esc_attr( $wcb_product['name'] ); ?>" class="wcb-w-12 wcb-h-12 wcb-rounded-md wcb-object-cover" />
				<div>
					<p class="wcb-font-font-3 wcb-font-medium wcb-text-sm wcb-text-color-7"><?php echo esc_html( $wcb_product['name'] ); ?></p>
					<p class="wcb-font-font-3 wcb-text-xs wcb-text-color-6"><?php echo esc_html( $wcb_product['sku'] ); ?></p>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>