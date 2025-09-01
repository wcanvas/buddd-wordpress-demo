<?php
/**
 * Block: Navbar
 *
 * @package WCB
 */

defined( 'ABSPATH' ) || die();

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
$wcb_logo_link        = is_array( $wcb_logo_link ) ? $wcb_logo_link : array();
$wcb_navigation_links = is_array( $wcb_navigation_links ) ? $wcb_navigation_links : array();
$wcb_contact_button   = is_array( $wcb_contact_button ) ? $wcb_contact_button : array();
$wcb_download_button  = is_array( $wcb_download_button ) ? $wcb_download_button : array();

$wcb_logo_url = isset( $wcb_logo_link['url'] ) && is_string( $wcb_logo_link['url'] ) ? $wcb_logo_link['url'] : '#';

$block_data = BlockWrapper::get_global_block_wrapper_data( $block );

?>

<section <?php echo wp_kses_post( $block_data ); ?>>
	<header class="js-navbar-header wcb-fixed wcb-top-0 wcb-left-0 wcb-right-0 wcb-z-50 wcb-transition-transform wcb-duration-300 wcb-translate-y-0">
		<div class="wcb-bg-color-30 wcb-max-w-[1440px] wcb-mx-auto wcb-rounded-b-radius-6">
			<div class="wcb-container wcb-py-4 lg:wcb-py-5">
				<div class="wcb-flex wcb-justify-between wcb-items-center">
					<?php
					$wcb_logo_comp = new Component(
						'logo',
						array(
							'href'  => $wcb_logo_url,
							'image' => $wcb_logo,
						)
					);
					$wcb_logo_comp->render();
					?>

					<nav class="wcb-hidden lg:wcb-flex wcb-items-center wcb-gap-x-8">
						<?php if ( ! empty( $wcb_navigation_links ) ) : ?>
							<ul class="wcb-flex wcb-items-center wcb-list-none wcb-gap-x-8">
								<?php foreach ( $wcb_navigation_links as $wcb_item ) : ?>
									<?php
									$wcb_link = isset( $wcb_item['link'] ) && is_array( $wcb_item['link'] ) ? $wcb_item['link'] : array();
									if ( ! empty( $wcb_link['title'] ) && ! empty( $wcb_link['url'] ) ) :
										?>
										<li>
											<?php
											$wcb_text_link_comp = new Component(
												'text-link',
												array(
													'href'  => $wcb_link['url'],
													'text'  => $wcb_link['title'],
													'class' => 'wcb-font-font-4 wcb-font-normal wcb-text-base wcb-text-color-3 hover:wcb-text-color-7 wcb-leading-lh-9',
												)
											);
											$wcb_text_link_comp->render();
											?>
										</li>
									<?php endif; ?>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>

						<div class="wcb-flex wcb-items-center wcb-gap-x-4">
							<?php if ( ! empty( $wcb_contact_button['title'] ) && ! empty( $wcb_contact_button['url'] ) ) : ?>
								<?php
								$wcb_contact_button_comp = new Component(
									'button',
									array(
										'href'    => $wcb_contact_button['url'],
										'title'   => $wcb_contact_button['title'],
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
										'href'    => $wcb_download_button['url'],
										'title'   => $wcb_download_button['title'],
										'target'  => $wcb_download_button['target'],
										'variant' => 'primary',
									)
								);
								$wcb_download_button_comp->render();
								?>
							<?php endif; ?>
						</div>
					</nav>

					<button class="js-menu-toggle lg:wcb-hidden wcb-p-2 wcb-z-50" aria-label="Toggle menu">
						<svg class="js-hamburger-icon wcb-text-color-3" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M3 12H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M3 6H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M3 18H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						<svg class="js-close-icon wcb-hidden wcb-text-color-3" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
					</button>
				</div>
			</div>
		</div>

		<div class="js-mobile-menu wcb-hidden lg:wcb-hidden wcb-fixed wcb-inset-0 wcb-bg-color-30 wcb-pt-24 wcb-px-4 wcb-z-40 wcb-h-screen wcb-overflow-y-auto">
			<nav class="wcb-flex wcb-flex-col wcb-items-center wcb-text-center wcb-gap-y-8 wcb-mt-4">
				<?php if ( ! empty( $wcb_navigation_links ) ) : ?>
					<ul class="wcb-flex wcb-flex-col wcb-items-center wcb-list-none wcb-gap-y-6">
						<?php foreach ( $wcb_navigation_links as $wcb_item ) : ?>
							<?php
							$wcb_link = isset( $wcb_item['link'] ) && is_array( $wcb_item['link'] ) ? $wcb_item['link'] : array();
							if ( ! empty( $wcb_link['title'] ) && ! empty( $wcb_link['url'] ) ) :
								?>
								<li>
									<?php
									$wcb_text_link_comp = new Component(
										'text-link',
										array(
											'href'  => $wcb_link['url'],
											'text'  => $wcb_link['title'],
											'class' => 'wcb-font-font-4 wcb-font-normal wcb-text-lg wcb-text-color-3 hover:wcb-text-color-7',
										)
									);
									$wcb_text_link_comp->render();
									?>
								</li>
							<?php endif; ?>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>

				<div class="wcb-flex wcb-flex-col wcb-items-stretch wcb-gap-y-4 wcb-w-full wcb-max-w-xs">
					<?php if ( ! empty( $wcb_contact_button['title'] ) && ! empty( $wcb_contact_button['url'] ) ) : ?>
						<?php
						$wcb_contact_button_comp = new Component(
							'button',
							array(
								'href'    => $wcb_contact_button['url'],
								'title'   => $wcb_contact_button['title'],
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
								'href'    => $wcb_download_button['url'],
								'title'   => $wcb_download_button['title'],
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
	<div class="wcb-h-[76px] lg:wcb-h-[90px]"></div>
</section>