<?php
/**
 * Block: Navbar
 * 
 * @package WCB
 */

defined( 'ABSPATH' ) || die(); // Ensure that this line do not experiment modifications. it should be always the same.

use WCB\Block\BlockWrapper;
use WCB\Functionalities\Component;

// Get ACF fields
$wcb_logo             = get_field( 'logo' );
$wcb_logo_link        = get_field( 'logo_link' );
$wcb_navigation_links = get_field( 'navigation_links' );
$wcb_contact_button   = get_field( 'contact_button' );
$wcb_download_button  = get_field( 'download_button' );

// Validate ACF field values to prevent critical errors
$wcb_logo             = is_array( $wcb_logo ) ? $wcb_logo : array();
$wcb_logo_link        = is_array( $wcb_logo_link ) ? $wcb_logo_link : array( 'url' => '/', 'title' => 'Home' );
$wcb_navigation_links = is_array( $wcb_navigation_links ) ? $wcb_navigation_links : array();
$wcb_contact_button   = is_array( $wcb_contact_button ) ? $wcb_contact_button : array();
$wcb_download_button  = is_array( $wcb_download_button ) ? $wcb_download_button : array();

// Block wrapper attributes
$wcb_block_data = BlockWrapper::get_global_block_wrapper_data( $block );

$wcb_nav_link_classes = 'wcb-font-font-4 wcb-font-normal wcb-text-base wcb-leading-lh-9 wcb-text-color-3 hover:wcb-text-color-7';

?>

<header <?php echo wp_kses_post( $wcb_block_data ); ?> class="wcb-sticky wcb-top-0 wcb-w-full wcb-z-50 wcb-bg-color-30 wcb-transition-transform wcb-duration-300 wcb-ease-in-out wcb-translate-y-0">
	<div class="wcb-container wcb-flex wcb-items-center wcb-justify-between wcb-py-4 lg:wcb-py-5">
		<div class="wcb-flex wcb-items-center wcb-gap-16 xl:wcb-gap-24">
			<?php
			$wcb_logo_comp = new Component(
				'logo',
				array(
					'href'  => $wcb_logo_link['url'] ?? '/',
					'image' => $wcb_logo,
				)
			);
			$wcb_logo_comp->render();
			?>
			<?php if ( ! empty( $wcb_navigation_links ) ) : ?>
				<nav class="wcb-hidden lg:wcb-block">
					<ul class="wcb-flex wcb-items-center wcb-gap-10 wcb-list-none">
						<?php foreach ( $wcb_navigation_links as $wcb_nav_item ) : ?>
							<?php
							$wcb_link = is_array( $wcb_nav_item['link'] ) ? $wcb_nav_item['link'] : array();
							if ( ! empty( $wcb_link['title'] ) && ! empty( $wcb_link['url'] ) ) :
								?>
								<li>
									<?php
									$wcb_text_link_comp = new Component(
										'text-link',
										array(
											'href'  => $wcb_link['url'],
											'text'  => $wcb_link['title'],
											'class' => $wcb_nav_link_classes,
										)
									);
									$wcb_text_link_comp->render();
									?>
								</li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
				</nav>
			<?php endif; ?>
		</div>

		<div class="wcb-hidden lg:wcb-flex wcb-items-center wcb-gap-4">
			<?php if ( ! empty( $wcb_contact_button['title'] ) && ! empty( $wcb_contact_button['url'] ) ) : ?>
				<?php
				$wcb_contact_button_comp = new Component(
					'button',
					array(
						'title'   => $wcb_contact_button['title'],
						'href'    => $wcb_contact_button['url'],
						'target'  => $wcb_contact_button['target'],
						'variant' => 'outline',
					)
				);
				$wcb_contact_button_comp->render();
				?>
			<?php endif; ?>
			<?php if ( ! empty( $wcb_download_button['title'] ) && ! empty( $wcb_download_button['url'] ) ) : ?>
				<?php
				$wcb_download_button_comp = new Component(
					'button',
					array(
						'title'   => $wcb_download_button['title'],
						'href'    => $wcb_download_button['url'],
						'target'  => $wcb_download_button['target'],
						'variant' => 'primary',
					)
				);
				$wcb_download_button_comp->render();
				?>
			<?php endif; ?>
		</div>

		<button class="lg:wcb-hidden wcb-p-2 js-mobile-menu-toggle" aria-label="Toggle menu" aria-expanded="false">
			<span class="js-hamburger-icon">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M3 12H21" stroke="#232E26" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					<path d="M3 6H21" stroke="#232E26" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					<path d="M3 18H21" stroke="#232E26" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</span>
			<span class="js-close-icon wcb-hidden">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M18 6L6 18" stroke="#232E26" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					<path d="M6 6L18 18" stroke="#232E26" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</span>
		</button>
	</div>

	<!-- Mobile Menu -->
	<div class="lg:wcb-hidden wcb-bg-color-30 wcb-w-full wcb-transition-all wcb-duration-300 wcb-ease-in-out wcb-overflow-hidden wcb-max-h-0 js-mobile-menu">
		<nav class="wcb-container wcb-flex wcb-flex-col wcb-items-center wcb-gap-6 wcb-py-6">
			<?php if ( ! empty( $wcb_navigation_links ) ) : ?>
				<ul class="wcb-flex wcb-flex-col wcb-items-center wcb-gap-6 wcb-list-none">
					<?php foreach ( $wcb_navigation_links as $wcb_nav_item ) : ?>
						<?php
						$wcb_link = is_array( $wcb_nav_item['link'] ) ? $wcb_nav_item['link'] : array();
						if ( ! empty( $wcb_link['title'] ) && ! empty( $wcb_link['url'] ) ) :
							?>
							<li>
								<?php
								$wcb_text_link_comp = new Component(
									'text-link',
									array(
										'href'  => $wcb_link['url'],
										'text'  => $wcb_link['title'],
										'class' => $wcb_nav_link_classes,
									)
								);
								$wcb_text_link_comp->render();
								?>
							</li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			<div class="wcb-flex wcb-flex-col wcb-items-stretch wcb-gap-4 wcb-mt-4 wcb-w-full wcb-max-w-xs wcb-mx-auto">
				<?php if ( ! empty( $wcb_contact_button['title'] ) && ! empty( $wcb_contact_button['url'] ) ) : ?>
					<?php
					$wcb_contact_button_comp = new Component(
						'button',
						array(
							'title'   => $wcb_contact_button['title'],
							'href'    => $wcb_contact_button['url'],
							'target'  => $wcb_contact_button['target'],
							'variant' => 'outline',
						)
					);
					$wcb_contact_button_comp->render();
					?>
				<?php endif; ?>
				<?php if ( ! empty( $wcb_download_button['title'] ) && ! empty( $wcb_download_button['url'] ) ) : ?>
					<?php
					$wcb_download_button_comp = new Component(
						'button',
						array(
							'title'   => $wcb_download_button['title'],
							'href'    => $wcb_download_button['url'],
							'target'  => $wcb_download_button['target'],
							'variant' => 'primary',
						)
					);
					$wcb_download_button_comp->render();
					?>
				<?php endif; ?>
			</div>
		</nav>
	</div>
</header>