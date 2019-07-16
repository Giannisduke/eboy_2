<?php
/**
 * Single Product Sale Flash
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/sale-flash.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product;

?>
<?php if ( $product->is_on_sale() ) : ?>
	<?php
				 echo '<div class="green-circle">';
				 echo '<span class="inner-text">';
				 echo esc_html__( 'Προσφορά!', 'woocommerce' );
				 echo '<span class="sales_price">';
				 	echo '</br>';
				 echo esc_html__(catalogue_sale_price());
				 echo esc_html__(get_woocommerce_currency_symbol());
				 echo '</span>';
				 echo '</span>';
				 echo '</div>';

	 ?>


<?php endif;

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
