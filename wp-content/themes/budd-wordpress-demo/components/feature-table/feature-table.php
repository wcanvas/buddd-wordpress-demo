<?php
/**
 * Component: FeatureTable
 *
 * @package WCB
 *
 * @param array $args {
 *     @type array $data      Array of feature categories.
 *     @type array $plans     Array of pricing plans.
 * }
 */

defined( 'ABSPATH' ) || die(); // Ensure that this line do not experiment modifications. it should be always the same.

// Set default values
$args += array(
	'data'  => array(),
	'plans' => array(),
);

// Validate and sanitize arguments to prevent critical errors
$args['data']  = is_array( $args['data'] ) ? $args['data'] : array();
$args['plans'] = is_array( $args['plans'] ) ? $args['plans'] : array();

if ( empty( $args['data'] ) || empty( $args['plans'] ) ) {
	return;
}

$wcb_plan_count            = count( $args['plans'] );
$wcb_grid_template_columns = 'minmax(0, 2fr) repeat(' . $wcb_plan_count . ', minmax(0, 1fr))';
?>

<div class="wcb-border-t wcb-border-color-3 wcb-overflow-x-auto">
	<div class="wcb-min-w-max">
		<?php foreach ( $args['data'] as $category_index => $category ) : ?>
			<?php
			$wcb_category_title = isset( $category['category_title'] ) && is_string( $category['category_title'] ) ? $category['category_title'] : '';
			$wcb_features       = isset( $category['features'] ) && is_array( $category['features'] ) ? $category['features'] : array();
			$wcb_category_class = $category_index > 0 ? 'wcb-border-t wcb-border-color-3' : '';
			?>
			<div class="<?php echo esc_attr( $wcb_category_class ); ?>">
				<?php if ( ! empty( $wcb_category_title ) ) : ?>
					<h4 class="wcb-py-4 wcb-px-3 md:wcb-py-5 md:wcb-px-4 wcb-font-bold wcb-text-color-7 wcb-text-lg">
						<?php echo esc_html( $wcb_category_title ); ?>
					</h4>
				<?php endif; ?>

				<?php if ( ! empty( $wcb_features ) ) : ?>
					<div>
						<?php foreach ( $wcb_features as $feature ) : ?>
							<?php
							$wcb_feature_name = isset( $feature['feature_name'] ) && is_string( $feature['feature_name'] ) ? $feature['feature_name'] : '';
							$wcb_plan_values  = isset( $feature['plan_values'] ) && is_array( $feature['plan_values'] ) ? $feature['plan_values'] : array();
							?>
							<div class="wcb-grid wcb-border-t wcb-border-color-32" style="grid-template-columns: <?php echo esc_attr( $wcb_grid_template_columns ); ?>;">
								<div class="wcb-py-3 wcb-px-3 md:wcb-py-4 md:wcb-px-4 wcb-text-color-3 wcb-text-base wcb-flex wcb-items-center">
									<?php echo esc_html( $wcb_feature_name ); ?>
								</div>
								<?php foreach ( $wcb_plan_values as $plan_value ) : ?>
									<?php
									$wcb_value = isset( $plan_value['value'] ) && is_string( $plan_value['value'] ) ? $plan_value['value'] : '';
									?>
									<div class="wcb-py-3 wcb-px-3 md:wcb-py-4 md:wcb-px-4 wcb-text-color-3 wcb-text-base wcb-text-center wcb-border-l wcb-border-color-32 wcb-flex wcb-items-center wcb-justify-center">
										<?php echo esc_html( $wcb_value ); ?>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</div>