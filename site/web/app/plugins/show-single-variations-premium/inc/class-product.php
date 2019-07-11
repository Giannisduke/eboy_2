<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Iconic_WSSV_Product_Variation.
 *
 * @class    Iconic_WSSV_Product_Variation
 * @version  1.0.0
 * @author   Iconic
 */
class Iconic_WSSV_Product {
	/**
	 * Run.
	 */
	public static function init() {
		add_action( 'woocommerce_update_product', array( __CLASS__, 'on_update_product' ), 10, 2 );
		add_action( 'post_submitbox_misc_actions', array( __CLASS__, 'product_data_visibility' ), 20 );
	}

	/**
	 * On update product.
	 *
	 * @param int $product_id
	 */
	public static function on_update_product( $product_id ) {
		self::update_visibility( $product_id );
	}

	/**
	 * On update visibility.
	 *
	 * @param int $product_id
	 */
	public static function update_visibility( $product_id ) {
		if ( version_compare( WC_VERSION, '3.0.0', '<' ) ) {
			return;
		}

		$save = filter_input( INPUT_POST, 'save' );
		$action = filter_input( INPUT_POST, 'action' );

		if ( $save !== __( 'Update' ) && $action !== 'iconic_wssv_process_product_visibility' ) {
			return;
		}

		$product = wc_get_product( $product_id );

		if ( ! $product ) {
			return;
		}

		$visibility            = self::get_catalog_visibility( $product );
		$exclude_from_filtered = isset( $_POST['iconic_wssv_exclude_from_filtered'] );
		$visibility_terms      = self::get_visibility_term_slugs( $product->get_id() );

		if ( ( $exclude_from_filtered || $visibility === "hidden" ) ) {
			if ( ! in_array( "exclude-from-filtered", $visibility_terms ) ) {
				$visibility_terms[] = "exclude-from-filtered";
			}
		} else {
			$visibility_terms = JCK_WSSV::unset_item_by_value( $visibility_terms, "exclude-from-filtered" );
		}

		if ( ! is_wp_error( wp_set_post_terms( $product->get_id(), $visibility_terms, 'product_visibility', false ) ) ) {
			$visibility = self::get_catalog_visibility( $product );
			do_action( 'woocommerce_product_set_visibility', $product->get_id(), $visibility );
		}
	}

	/**
	 * Get catalog visibility.
	 *
	 * @param WC_Product $product
	 *
	 * @return string
	 */
	public static function get_catalog_visibility( $product ) {
		if ( method_exists( $product, 'get_catalog_visibility' ) ) {
			return $product->get_catalog_visibility();
		} else {
			return get_post_meta( $product->get_id(), '_visibility', true );
		}
	}

	/**
	 * Get parent ID.
	 *
	 * @param WC_Product $product
	 *
	 * @return int
	 */
	public static function get_parent_id( $product ) {
		if ( method_exists( $product, 'get_parent_id' ) ) {
			return $product->get_parent_id();
		} else {
			return $product->get_parent();
		}
	}

	public static function product_data_visibility() {
		if ( version_compare( WC_VERSION, '3.0.0', '<' ) ) {
			return;
		}

		global $post;

		if ( 'product' !== $post->post_type ) {
			return;
		}

		$visibility_terms = self::get_visibility_term_slugs( $post->ID );
		$exclude_from_filtered = in_array( 'exclude-from-filtered', $visibility_terms );
		?>
		<div class="misc-pub-section show_if_variable" style="display: none;">
			<input type="checkbox" name="iconic_wssv_exclude_from_filtered" id="iconic-wssv-exclude-from-filtered" <?php checked( $exclude_from_filtered ); ?> />
			<label for="iconic-wssv-exclude-from-filtered"><?php _e( 'Exclude from filtered results', 'iconic-wssv' ); ?></label>
		</div>
		<?php
	}

	/**
	 * Get visibility term slugs.
	 *
	 * @param int $product_id
	 *
	 * @return array
	 */
	public static function get_visibility_term_slugs( $product_id ) {
		$terms = wp_get_post_terms( $product_id, 'product_visibility' );

		return wp_list_pluck( $terms, 'slug' );
	}
}