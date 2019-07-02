<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>


<div class="row ">
<div class="col-3 test">
	<div class="row">
		<div class="col-12">
			<h4>Φύλλο</h4>
<?php  echo facetwp_display( 'facet', 'product_categories' ); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<h4>Ηλικία</h4>
<?php  echo facetwp_display( 'facet', 'product_age' ); ?>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<h4>Χρώμα</h4>
<?php  echo facetwp_display( 'facet', 'product_color' ); ?>
		</div>
	</div>
</div>
<div class="col-9 test">
	<div class="row facetwp-template">
