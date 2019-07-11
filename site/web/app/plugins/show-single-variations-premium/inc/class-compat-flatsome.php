<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Flatsome compatibility Class
 *
 * @since 1.1.10
 */
class Iconic_WSSV_Compat_Flatsome {
	/**
	 * Init.
	 */
	public static function init() {
		add_filter( 'iconic_wssv_button_args', array( __CLASS__, 'add_to_cart_button_args' ), 10, 2 );
		remove_action( 'flatsome_product_box_actions', 'flatsome_lightbox_button', 50 );
		add_action( 'flatsome_product_box_actions', array( __CLASS__, 'lightbox_button' ), 50 );
	}

	/**
	 * Add to cart button args.
	 *
	 * @param array      $args
	 * @param WC_Product $product
	 *
	 * @return array
	 */
	public static function add_to_cart_button_args( $args, $product ) {
		if ( flatsome_option( 'add_to_cart_icon' ) !== 'show' ) {
			return $args;
		}

		if ( ! isset( $args['attributes']['style'] ) ) {
			$args['attributes']['style'] = '';
		}

		$args['attributes']['style'] .= 'width:0;margin:0;';
		$args['button_text']         = '<div class="cart-icon tooltip absolute is-small" title="' . esc_attr( $args['button_text'] ) . '"><strong>+</strong></div>';

		return $args;
	}

	/**
	 * Use parent ID if it exists.
	 *
	 * Not ideal as it loads the parent with no pre-selection,
	 * but it's the best that can be done at the moment.
	 */
	public static function lightbox_button() {
		global $product;

		if ( $product->get_type() !== 'variation' && function_exists( 'flatsome_lightbox_button' ) ) {
			flatsome_lightbox_button();

			return;
		}

		if ( get_theme_mod( 'disable_quick_view', 0 ) ) {
			return;
		}

		// Run Quick View Script
		wp_enqueue_script( 'wc-add-to-cart-variation' );

		global $product;

		echo '  <a class="quick-view" data-prod="' . Iconic_WSSV_Product::get_parent_id( $product ) . '" href="#quick-view">' . __( 'Quick View', 'flatsome' ) . '</a>';
	}
}