<?php
/**
 * Component: MockupThree
 *
 * @package WCB
 */

defined( 'ABSPATH' ) || die();

$wcb_steps = array(
	array(
		'name' => 'Draft',
		'done' => true,
	),
	array(
		'name' => 'Ordered',
		'done' => false,
	),
	array(
		'name' => 'Received',
		'done' => false,
	),
	array(
		'name' => 'Closed',
		'done' => false,
	),
);
?>

<div class="wcb-bg-color-42 wcb-rounded-xl wcb-shadow-2xl wcb-border wcb-border-color-38 wcb-max-w-sm wcb-mx-auto wcb-overflow-hidden">
	<div class="wcb-p-4 wcb-bg-gradient-to-r wcb-from-color-28 wcb-to-white">
		<h4 class="wcb-font-font-3 wcb-font-bold wcb-text-color-16">Purchase Order #7824</h4>
		<p class="wcb-font-font-3 wcb-text-sm wcb-text-color-16">Supplier Cosforama</p>
	</div>
	<div class="wcb-p-4 wcb-space-y-3 wcb-bg-white">
		<?php foreach ( $wcb_steps as $wcb_step ) : ?>
			<div class="wcb-flex wcb-items-center wcb-gap-3">
				<div class="wcb-w-5 wcb-h-5 wcb-rounded-full wcb-border-2 wcb-flex wcb-items-center wcb-justify-center <?php echo $wcb_step['done'] ? 'wcb-bg-color-15 wcb-border-color-15' : 'wcb-border-gray-300 wcb-bg-white'; ?>">
					<?php if ( $wcb_step['done'] ) : ?>
						<svg class="wcb-w-3 wcb-h-3 wcb-text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
					<?php endif; ?>
				</div>
				<span class="wcb-font-font-3 <?php echo $wcb_step['done'] ? 'wcb-text-color-15' : 'wcb-text-gray-400'; ?>"><?php echo esc_html( $wcb_step['name'] ); ?></span>
			</div>
		<?php endforeach; ?>
	</div>
</div>