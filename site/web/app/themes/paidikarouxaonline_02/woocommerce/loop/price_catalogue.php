<?php
/**
 * Single Product Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;
?>
<div class="d-flex flex-row justify-content-between align-items-end">
  <div class="p-0 w-50">
		<?php echo do_shortcode("[ti_wishlists_addtowishlist loop=yes]"); ?>
	</div>

  <div class="p-0 w-50 align-self-center">
		<div class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price ml-auto' ) );?>"><?php echo catalogue_price();  ?>
			<span class="currency">
			<?php echo get_woocommerce_currency_symbol();  ?>
		</span>
		</div>
	</div>
</div>

</div>
