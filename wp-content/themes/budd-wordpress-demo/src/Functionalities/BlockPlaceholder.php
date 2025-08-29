<?php
/**
 * Block Placeholder.
 *
 * @package WCB
 */

namespace WCB\Functionalities;

defined( 'ABSPATH' ) || die();

/**
 * Class BlockPlaceholder
 *
 * @package WCB\Functionalities
 */
class BlockPlaceholder {

	/**
	 * This functions renders a placeholder image for the block.
	 *
	 * @param string $block_name the name of the block, this string needs to match the name of the block.
	 * @param string $warning_string This string will be shown in the center of the image.
	 *
	 * @return void
	 */
	public static function render_placeholder( $block_name, $warning_string = 'This is a dynamic block, and its content will only be shown on the front end.' ) {
		?>
			<div class="wcb-block-placeholder">
				<img src="<?php echo esc_url( get_template_directory_uri() . '/blocks/' . $block_name . '/placeholder.png' ); ?>" alt="<?php echo esc_html( $block_name ); ?> placeholder">
				<span class="wcb-block-placeholder__text wcb-title wcb-title--heading-6"><?php echo esc_html( $warning_string ); ?></span>
			</div>
		<?php
	}
}
