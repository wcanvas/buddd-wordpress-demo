<?php
/**
 * Block: Footer
 * 
 * @package WCB
 */

defined( 'ABSPATH' ) || die(); // Ensure that this line do not experiment modifications. it should be always the same.

use WCB\Block\BlockWrapper;
use WCB\Functionalities\Component;

// Get ACF fields
$wcb_logo                   = get_field( 'logo' );
$wcb_logo_link              = get_field( 'logo_link' );
$wcb_navigation_columns     = get_field( 'navigation_columns' );
$wcb_newsletter_placeholder = get_field( 'newsletter_placeholder' );
$wcb_newsletter_button      = get_field( 'newsletter_button' );
$wcb_newsletter_disclaimer  = get_field( 'newsletter_disclaimer' );
$wcb_copyright_text         = get_field( 'copyright_text' );
$wcb_legal_links            = get_field( 'legal_links' );
$wcb_social_links           = get_field( 'social_links' );

// Validate ACF field values to prevent critical errors
$wcb_logo                   = is_array( $wcb_logo ) ? $wcb_logo : array();
$wcb_logo_link              = is_array( $wcb_logo_link ) ? $wcb_logo_link : array();
$wcb_navigation_columns     = is_array( $wcb_navigation_columns ) ? $wcb_navigation_columns : array();
$wcb_newsletter_placeholder = is_string( $wcb_newsletter_placeholder ) ? $wcb_newsletter_placeholder : '';
$wcb_newsletter_button      = is_array( $wcb_newsletter_button ) ? $wcb_newsletter_button : array();
$wcb_newsletter_disclaimer  = is_string( $wcb_newsletter_disclaimer ) ? $wcb_newsletter_disclaimer : '';
$wcb_copyright_text         = is_string( $wcb_copyright_text ) ? $wcb_copyright_text : '';
$wcb_legal_links            = is_array( $wcb_legal_links ) ? $wcb_legal_links : array();
$wcb_social_links           = is_array( $wcb_social_links ) ? $wcb_social_links : array();

$wcb_logo_link_url    = isset( $wcb_logo_link['url'] ) && is_string( $wcb_logo_link['url'] ) ? $wcb_logo_link['url'] : '#';
$wcb_button_title     = isset( $wcb_newsletter_button['title'] ) && is_string( $wcb_newsletter_button['title'] ) ? $wcb_newsletter_button['title'] : '';
$wcb_button_url       = isset( $wcb_newsletter_button['url'] ) && is_string( $wcb_newsletter_button['url'] ) ? $wcb_newsletter_button['url'] : '#';
$wcb_button_target    = isset( $wcb_newsletter_button['target'] ) && is_string( $wcb_newsletter_button['target'] ) ? $wcb_newsletter_button['target'] : '';
$wcb_nav_link_classes = 'wcb-font-font-4 wcb-text-sm wcb-text-color-30 wcb-font-normal wcb-leading-lh-10 hover:wcb-text-color-42';
$wcb_legal_link_classes = 'wcb-font-font-4 wcb-text-sm wcb-text-color-30 wcb-font-normal wcb-leading-lh-9 wcb-underline hover:wcb-no-underline';

// Block wrapper attributes  
$block_data = BlockWrapper::get_global_block_wrapper_data( $block );
?>

<section <?php echo wp_kses_post( $block_data ); ?>>
	<div class="wcb-bg-color-7">
		<div class="wcb-container wcb-pt-12 wcb-pb-10 md:wcb-pt-24 md:wcb-pb-16">
			<div class="wcb-grid wcb-grid-cols-1 md:wcb-grid-cols-6 lg:wcb-grid-cols-12 wcb-gap-y-10 md:wcb-gap-y-12 md:wcb-gap-x-8">
				<div class="md:wcb-col-span-2 lg:wcb-col-span-3">
					<?php
					$wcb_logo_comp = new Component(
						'logo',
						array(
							'href'    => $wcb_logo_link_url,
							'variant' => 'inverse',
							'image'   => $wcb_logo,
						)
					);
					$wcb_logo_comp->render();
					?>
				</div>

				<?php if ( ! empty( $wcb_navigation_columns ) ) : ?>
					<?php foreach ( $wcb_navigation_columns as $wcb_column ) : ?>
						<?php
						$wcb_column_title = isset( $wcb_column['column_title'] ) && is_string( $wcb_column['column_title'] ) ? $wcb_column['column_title'] : '';
						$wcb_column_links = isset( $wcb_column['column_links'] ) && is_array( $wcb_column['column_links'] ) ? $wcb_column['column_links'] : array();
						?>
						<div class="md:wcb-col-span-2 lg:wcb-col-span-2">
							<?php
							$wcb_heading_comp = new Component(
								'heading',
								array(
									'as'    => 'h3',
									'text'  => $wcb_column_title,
									'class' => 'wcb-font-font-4 wcb-font-medium wcb-text-base wcb-leading-lh-5 wcb-text-color-30',
								)
							);
							$wcb_heading_comp->render();
							?>
							<?php if ( ! empty( $wcb_column_links ) ) : ?>
								<ul class="wcb-mt-4 wcb-space-y-3 wcb-list-none">
									<?php foreach ( $wcb_column_links as $wcb_link_item ) : ?>
										<?php
										$wcb_link = isset( $wcb_link_item['link'] ) && is_array( $wcb_link_item['link'] ) ? $wcb_link_item['link'] : array();
										if ( ! empty( $wcb_link ) ) :
											?>
											<li>
												<?php
												$wcb_text_link_comp = new Component(
													'text-link',
													array(
														'href'  => isset( $wcb_link['url'] ) ? $wcb_link['url'] : '#',
														'text'  => isset( $wcb_link['title'] ) ? $wcb_link['title'] : '',
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
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
				
				<div class="md:wcb-col-span-6 lg:wcb-col-span-5">
					<form class="js-newsletter-form wcb-flex wcb-flex-col sm:wcb-flex-row sm:wcb-items-end wcb-gap-4">
						<div class="wcb-flex-grow">
							<?php
							$wcb_input_comp = new Component(
								'input',
								array(
									'type'        => 'email',
									'placeholder' => $wcb_newsletter_placeholder,
									'variant'     => 'underline',
									'class'       => 'placeholder:wcb-text-xs',
								)
							);
							$wcb_input_comp->render();
							?>
						</div>
						<div class="wcb-flex-shrink-0 wcb-w-full sm:wcb-w-auto">
							<?php
							$wcb_button_comp = new Component(
								'button',
								array(
									'title'   => $wcb_button_title,
									'href'    => $wcb_button_url,
									'target'  => $wcb_button_target,
									'variant' => 'outline-inverse',
								)
							);
							$wcb_button_comp->render();
							?>
						</div>
					</form>
					<?php if ( ! empty( $wcb_newsletter_disclaimer ) ) : ?>
						<div class="wcb-mt-4 wcb-font-font-3 wcb-text-xs wcb-text-color-30 wcb-leading-lh-9">
							<?php echo wp_kses_post( $wcb_newsletter_disclaimer ); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<hr class="wcb-border-t wcb-border-color-32 wcb-my-8 md:wcb-my-12" />

			<div class="wcb-flex wcb-flex-col-reverse md:wcb-flex-row wcb-justify-between wcb-items-center wcb-gap-8">
				<div class="wcb-flex wcb-flex-col sm:wcb-flex-row wcb-items-center wcb-gap-4 sm:wcb-gap-6 wcb-text-center">
					<?php if ( ! empty( $wcb_copyright_text ) ) : ?>
						<span class="wcb-font-font-4 wcb-text-sm wcb-text-color-30 wcb-leading-lh-9">
							<?php echo esc_html( $wcb_copyright_text ); ?>
						</span>
					<?php endif; ?>
					<?php if ( ! empty( $wcb_legal_links ) ) : ?>
						<div class="wcb-flex wcb-flex-wrap wcb-justify-center wcb-gap-x-6 wcb-gap-y-2">
							<?php foreach ( $wcb_legal_links as $wcb_link_item ) : ?>
								<?php
								$wcb_link = isset( $wcb_link_item['link'] ) && is_array( $wcb_link_item['link'] ) ? $wcb_link_item['link'] : array();
								if ( ! empty( $wcb_link ) ) {
									$wcb_legal_link_comp = new Component(
										'text-link',
										array(
											'href'  => isset( $wcb_link['url'] ) ? $wcb_link['url'] : '#',
											'text'  => isset( $wcb_link['title'] ) ? $wcb_link['title'] : '',
											'class' => $wcb_legal_link_classes,
										)
									);
									$wcb_legal_link_comp->render();
								}
								?>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
				<?php if ( ! empty( $wcb_social_links ) ) : ?>
					<div class="wcb-flex wcb-items-center wcb-gap-4">
						<?php foreach ( $wcb_social_links as $wcb_social ) : ?>
							<?php
							$wcb_icon = isset( $wcb_social['icon'] ) && is_string( $wcb_social['icon'] ) ? $wcb_social['icon'] : '';
							$wcb_link = isset( $wcb_social['link'] ) && is_string( $wcb_social['link'] ) ? $wcb_social['link'] : '#';
							
							$wcb_social_icon_link_comp = new Component(
								'social-icon-link',
								array(
									'href' => $wcb_link,
									'icon' => $wcb_icon,
								)
							);
							$wcb_social_icon_link_comp->render();
							?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>