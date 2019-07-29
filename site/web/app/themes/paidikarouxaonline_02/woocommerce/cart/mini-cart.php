<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//do_action( 'woocommerce_before_mini_cart' ); ?>

<?php if ( ! WC()->cart->is_empty() ) : ?>



			<a class="btn btn-outline-primary btn-lg w-50 minicart" href="<?php echo get_permalink( wc_get_page_id( 'checkout' ) ); ?>" role="button">
		<div class="d-flex flex-row align-items-center">
			<div class="p-0 w-25">
	 <img class="svg" src="<?= get_template_directory_uri(); ?>/dist/images/ico_cart_2_e.svg">
	</div>
	<div class="pt-2">
		<span class="cart_empty_message">

			<?php echo WC()->cart->get_cart_subtotal(); ?>
		</span>
		</div>
	</div>
</a>




	<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>



<?php else : ?>


	<a class="btn btn-outline-primary btn-lg w-50 minicart" href="#" role="button">
	<div class="d-flex flex-row align-items-center">
		<div class="p-0 w-25">
 <img class="svg" src="<?= get_template_directory_uri(); ?>/dist/images/ico_cart_2_e.svg">
</div>
<div class="pt-2">
	<span class="cart_empty_message"><?php _e( 'Αγορες.', 'woocommerce' ); ?></span>
	</div>
</div>
</a>

<?php endif; ?>


	  </div>
	</div>
<?php //do_action( 'woocommerce_after_mini_cart' ); ?>
