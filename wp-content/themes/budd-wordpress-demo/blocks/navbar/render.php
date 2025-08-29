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
$wcb_logo_image       = get_field( 'logo' );
$wcb_logo_link        = get_field( 'logo_link' );
$wcb_navigation_links = get_field( 'navigation_links' );
$wcb_contact_button   = get_field( 'contact_button' );
$wcb_download_button  = get_field( 'download_button' );

// Validate ACF field values
$wcb_logo_image       = is_array( $wcb_logo_image ) ? $wcb_logo_image : array();
$wcb_logo_link        = is_array( $wcb_logo_link ) ? $wcb_logo_link : array();
$wcb_logo_link_url    = ! empty( $wcb_logo_link['url'] ) && is_string( $wcb_logo_link['url'] ) ? $wcb_logo_link['url'] : '/';
$wcb_navigation_links = is_array( $wcb_navigation_links ) ? $wcb_navigation_links : array();
$wcb_contact_button   = is_array( $wcb_contact_button ) ? $wcb_contact_button : array();
$wcb_download_button  = is_array( $wcb_download_button ) ? $wcb_download_button : array();

// Block wrapper attributes
$block_data = BlockWrapper::get_global_block_wrapper_data( $block );
?>

<section <?php echo wp_kses_post( $block_data ); ?>>
	<div class="wcb-container wcb-py-4">
		<header class="wcb-bg-color-30 wcb-rounded-3xl lg:wcb-rounded-radius-6 wcb-px-4 lg:wcb-px-6 wcb-py-3 wcb-relative">
			<div class="wcb-flex wcb-items-center wcb-justify-between">
				<?php
				$wcb_logo_comp = new Component(
					'logo',
					array(
						'href'  => $wcb_logo_link_url,
						'image' => $wcb_logo_image,
					)
				);
				$wcb_logo_comp->render();
				?>

				<div class="wcb-hidden lg:wcb-flex wcb-items-center wcb-gap-x-10">
					<?php if ( ! empty( $wcb_navigation_links ) ) : ?>
						<nav>
							<ul class="wcb-flex wcb-items-center wcb-gap-x-10 wcb-list-none wcb-p-0 wcb-m-0">
								<?php foreach ( $wcb_navigation_links as $wcb_nav_item ) : ?>
									<?php
									$wcb_link = isset( $wcb_nav_item['link'] ) && is_array( $wcb_nav_item['link'] ) ? $wcb_nav_item['link'] : array();
									if ( ! empty( $wcb_link['title'] ) && ! empty( $wcb_link['url'] ) ) :
										?>
										<li>
											<?php
											$wcb_text_link_comp = new Component(
												'text-link',
												array(
													'href' => $wcb_link['url'],
													'text' => $wcb_link['title'],
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
					<div class="wcb-flex wcb-items-center wcb-gap-x-4">
						<?php if ( ! empty( $wcb_contact_button['title'] ) && ! empty( $wcb_contact_button['url'] ) ) : ?>
							<?php
							$wcb_contact_btn_comp = new Component(
								'button',
								array(
									'href'    => $wcb_contact_button['url'],
									'title'   => $wcb_contact_button['title'],
									'target'  => $wcb_contact_button['target'] ?? '',
									'variant' => 'outline',
								)
							);
							$wcb_contact_btn_comp->render();
							?>
						<?php endif; ?>
						<?php if ( ! empty( $wcb_download_button['title'] ) && ! empty( $wcb_download_button['url'] ) ) : ?>
							<?php
							$wcb_download_btn_comp = new Component(
								'button',
								array(
									'href'    => $wcb_download_button['url'],
									'title'   => $wcb_download_button['title'],
									'target'  => $wcb_download_button['target'] ?? '',
									'variant' => 'primary',
								)
							);
							$wcb_download_btn_comp->render();
							?>
						<?php endif; ?>
					</div>
				</div>

				<div class="lg:wcb-hidden">
					<button class="js-navbar-toggle wcb-text-color-3 focus:wcb-outline-none wcb-bg-transparent wcb-border-none wcb-p-1 wcb-cursor-pointer" aria-label="Toggle menu" aria-expanded="false">
						<span class="js-navbar-open-icon">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M3 12H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M3 6H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M3 18H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</span>
						<span class="js-navbar-close-icon wcb-hidden">
							 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</span>
					</button>
				</div>
			</div>

			<div class="js-navbar-menu lg:wcb-hidden wcb-hidden wcb-mt-6 wcb-pb-4">
				<nav class="wcb-flex wcb-flex-col wcb-items-center wcb-gap-y-6">
					<?php if ( ! empty( $wcb_navigation_links ) ) : ?>
						<ul class="wcb-flex wcb-flex-col wcb-items-center wcb-gap-y-6 wcb-list-none wcb-p-0 wcb-m-0">
							<?php foreach ( $wcb_navigation_links as $wcb_nav_item ) : ?>
								<?php
								$wcb_link = isset( $wcb_nav_item['link'] ) && is_array( $wcb_nav_item['link'] ) ? $wcb_nav_item['link'] : array();
								if ( ! empty( $wcb_link['title'] ) && ! empty( $wcb_link['url'] ) ) :
									?>
									<li>
										<?php
										$wcb_text_link_comp = new Component(
											'text-link',
											array(
												'href' => $wcb_link['url'],
												'text' => $wcb_link['title'],
											)
										);
										$wcb_text_link_comp->render();
										?>
									</li>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<div class="wcb-flex wcb-flex-col wcb-items-stretch wcb-w-full wcb-max-w-xs wcb-gap-y-4 wcb-pt-6 wcb-border-t wcb-border-solid wcb-border-color-5">
						<?php if ( ! empty( $wcb_contact_button['title'] ) && ! empty( $wcb_contact_button['url'] ) ) : ?>
							<?php
							$wcb_contact_btn_comp = new Component(
								'button',
								array(
									'href'    => $wcb_contact_button['url'],
									'title'   => $wcb_contact_button['title'],
									'target'  => $wcb_contact_button['target'] ?? '',
									'variant' => 'outline',
								)
							);
							$wcb_contact_btn_comp->render();
							?>
						<?php endif; ?>
						<?php if ( ! empty( $wcb_download_button['title'] ) && ! empty( $wcb_download_button['url'] ) ) : ?>
							<?php
							$wcb_download_btn_comp = new Component(
								'button',
								array(
									'href'    => $wcb_download_button['url'],
									'title'   => $wcb_download_button['title'],
									'target'  => $wcb_download_button['target'] ?? '',
									'variant' => 'primary',
								)
							);
							$wcb_download_btn_comp->render();
							?>
						<?php endif; ?>
					</div>
				</nav>
			</div>
		</header>
	</div>
</section>