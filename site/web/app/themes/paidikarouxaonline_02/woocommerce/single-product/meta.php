<?php
/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
?>
<?php if( get_field('cotton') || get_field('polyester') ): ?>

	<div class="pieID--micro-skills pie-chart--wrapper shadow-sm bg-panel rounded p-4 mb-2">
		<h4>Υλικό</h4>
		<div class="pie-chart d-flex flex-row">
			<div class="pie-chart__pie"></div>
			<ul class="pie-chart__legend">
				<?php if( get_field('cotton')): ?>
				<li><em>Βαμβάκι: </em><span><?php the_field('cotton'); ?></span>%</li>
				<?php endif; ?>
				<?php if( get_field('polyester')): ?>
				<li><em>Πολυεστέρας: </em><span><?php the_field('polyester'); ?></span>%</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>


<?php endif; ?>
