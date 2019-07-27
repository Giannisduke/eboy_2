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




	<span class="woocommerce-mini-cart__total total">
		<?php echo WC()->cart->get_cart_contents_count(); ?>

		<button type="button" class="btn btn-outline-dark">
		<?php echo WC()->cart->get_cart_subtotal(); ?>
	</button>
	</span>
	<!-- Modal -->
	<div id="Modal" class="modal fade" role="dialog">
	  <div class="modal-dialog">

	<!-- Modal content-->
	<div class="modal-content">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal">&times;</button>
	    <h4 class="modal-title">Modal Header</h4>
	  </div>
	  <div class="modal-body">
	    <p>Some text in the modal.</p>
	  </div>
	  <div class="modal-footer">
	    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	  </div>
	</div>

	  </div>
	</div>

	<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>



<?php else : ?>
<button type="button" class="btn btn-outline-primary">
	<div class="d-flex flex-row">
 <img class="minicart align-self-end svg" src="<?= get_template_directory_uri(); ?>/dist/images/ico_cart_2_e.svg">
	<span class="cart_empty_message align-self-end"><?php _e( 'Καλαθι Αγορων.', 'woocommerce' ); ?></span>
</div>
</button>
<?php endif; ?>

<?php //do_action( 'woocommerce_after_mini_cart' ); ?>
