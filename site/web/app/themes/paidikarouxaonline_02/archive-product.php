<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 *
 * Updated by Elvtn, LLC to include FacetWP markup.
 * https://elvtn.com
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

 ?>
 <div class="container text-center p-5">
 	<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
 		<h3 class="page-title"><?php woocommerce_page_title(); ?></h3>
 	<?php endif; ?>

 	<?php
 	/**
 	 * Hook: woocommerce_archive_description.
 	 *
 	 * @hooked woocommerce_taxonomy_archive_description - 10
 	 * @hooked woocommerce_product_archive_description - 10
 	 */
 	do_action( 'woocommerce_archive_description' );
 	?>
 </div>
 <div class="container-fluid p-0">
	 	 <?php do_action( 'woocommerce_before_shop_loop_navigation'); ?>
 </div>
   <div class="container-fluid shop">
		 <div class="row">
			 <div class="col-12">

			 </div>
			 <div class="col-3">

					<?php dynamic_sidebar('sidebar-products'); ?>

			 </div>
			 <div class="col-9">
 <?php
 if ( woocommerce_product_loop() ) {

 	/**
 	 * Hook: woocommerce_before_shop_loop.
 	 *
 	 * @hooked woocommerce_output_all_notices - 10
 	 * @hooked woocommerce_result_count - 20
 	 * @hooked woocommerce_catalog_ordering - 30
 	 */
 	do_action( 'woocommerce_before_shop_loop' );

 	woocommerce_product_loop_start();

 	if ( wc_get_loop_prop( 'total' ) ) {
 		while ( have_posts() ) {
 			the_post();

 			/**
 			 * Hook: woocommerce_shop_loop.
 			 *
 			 * @hooked WC_Structured_Data::generate_product_data() - 10
 			 */
 		//	do_action( 'woocommerce_shop_loop' );

 		wc_get_template_part( 'content', 'product' );

 		}
 	}

 	woocommerce_product_loop_end();

 	/**
 	 * Hook: woocommerce_after_shop_loop.
 	 *
 	 * @hooked woocommerce_pagination - 10
 	 */
 	do_action( 'woocommerce_after_shop_loop' );
 } else {
 	/**
 	 * Hook: woocommerce_no_products_found.
 	 *
 	 * @hooked wc_no_products_found - 10
 	 */
 	do_action( 'woocommerce_no_products_found' );
 }
?>
	</div>
 </div>
<?php
