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
class Iconic_WSSV_Product_Variation {
	/**
	 * Run.
	 */
	public static function init() {
		add_filter( 'woocommerce_product_variation_get_average_rating', array( __CLASS__, 'get_average_rating' ), 10, 2 );
		add_filter( 'woocommerce_product_is_visible', array( __CLASS__, 'is_visible' ), 10, 2 );
		add_filter( 'woocommerce_product_variation_title', array( __CLASS__, 'variation_title' ), 10, 4 );
		add_filter( 'woocommerce_cart_item_permalink', array( __CLASS__, 'cart_item_permalink' ), 10, 3 );
		add_filter( 'woocommerce_get_children', array( __CLASS__, 'remove_listing_only_variations' ), 10, 3 );
	}

	/**
	 * Filter is_visible.
	 *
	 * @param bool $visible
	 * @param int  $product_id
	 *
	 * @return bool
	 */
	public static function is_visible( $visible, $product_id ) {
		if ( get_post_type( $product_id ) !== 'product_variation' ) {
			return $visible;
		}

		$action  = filter_input( INPUT_POST, 'action' );
		$wc_ajax = filter_input( INPUT_GET, 'wc-ajax' );

		if ( $action === 'woocommerce_get_refreshed_fragments' || $wc_ajax === 'get_refreshed_fragments' ) {
			// If variation is in cart, always return true.
			return true;
		}

		$visibility = self::get_visibility( $product_id );

		if ( in_array( 'hidden', $visibility ) ) {
			return false;
		}

		$parent_id     = wp_get_post_parent_id( $product_id );
		$parent_status = get_post_status( $parent_id );

		if ( $parent_status !== 'publish' ) {
			return false;
		}

		return true;
	}

	/**
	 * Filter variation title.
	 *
	 * @param string $title
	 * @param        $product
	 * @param        $title_base
	 * @param        $title_suffix
	 *
	 * @return string
	 */
	public static function variation_title( $title, $product, $title_base, $title_suffix ) {
		$saved_title = get_post_meta( $product->get_id(), '_jck_wssv_display_title', true );

		if ( empty( $saved_title ) ) {
			return $title;
		}

		return $saved_title;
	}

	/**
	 * Set catalog visibility.
	 *
	 * @param int   $variation_id
	 * @param array $visibility
	 * @param bool  $meta_only
	 *
	 * @return bool
	 */
	public static function set_visibility( $variation_id, $visibility = null, $meta_only = false ) {
		$set_visibility = true;
		$current_visibility = get_post_meta( $variation_id, '_visibility', true );
		$visibility     = is_null( $visibility ) ? self::get_visibility( $variation_id ) : $visibility;

		sort( $visibility );

		update_post_meta( $variation_id, '_visibility', $visibility, $current_visibility );

		if ( $meta_only ) {
			return $set_visibility;
		}

		if ( version_compare( WC_VERSION, '3.0.0', '>=' ) ) {
			$set_visibility = false;
			$variation      = wc_get_product( $variation_id );
			$terms          = array();
			$visibility     = implode( '-', $visibility );

			switch ( $visibility ) {
				case 'catalog-filtered' :
					$terms[] = "exclude-from-search";
					break;
				case 'catalog-search' :
					$terms[] = "exclude-from-filtered";
					break;
				case 'catalog' :
					$terms[] = "exclude-from-search";
					$terms[] = "exclude-from-filtered";
					break;
				case 'filtered-search' :
					$terms[] = "exclude-from-catalog";
					break;
				case 'search' :
					$terms[] = "exclude-from-catalog";
					$terms[] = "exclude-from-filtered";
					break;
				case 'filtered' :
					$terms[] = "exclude-from-catalog";
					$terms[] = "exclude-from-search";
					break;
				case 'hidden' :
					$terms[] = "exclude-from-catalog";
					$terms[] = "exclude-from-search";
					$terms[] = "exclude-from-filtered";
					break;
			}

			if ( $variation ) {
				$stock_status = $variation->get_stock_status();
				if ( $stock_status === "outofstock" ) {
					$terms[] = "outofstock";
				}
			}

			if ( ! is_wp_error( wp_set_post_terms( $variation_id, $terms, 'product_visibility', false ) ) ) {
				delete_transient( 'wc_featured_products' );
				do_action( 'woocommerce_product_set_visibility', $variation_id, $terms );
				$set_visibility = true;
			}
		}

		return $set_visibility;
	}

	/**
	 * Set featured visibility.
	 *
	 * @param int  $variation_id
	 * @param bool $featured
	 * @param bool $meta_only
	 *
	 * @return bool
	 */
	public static function set_featured_visibility( $variation_id, $featured = null, $meta_only = false ) {
		$set_featured = true;
		$featured     = is_null( $featured ) ? Iconic_WSSV_Helpers::string_to_bool( get_post_meta( $variation_id, '_featured', true ) ) : $featured;

		if ( $featured ) {
			update_post_meta( $variation_id, '_featured', "yes" );
		} else {
			delete_post_meta( $variation_id, '_featured' );
		}

		if ( $meta_only ) {
			return $set_featured;
		}

		if ( version_compare( WC_VERSION, '3.0.0', '>=' ) ) {
			if ( $featured ) {
				$set_featured = wp_set_object_terms( $variation_id, 'featured', 'product_visibility', true );
			} else {
				$set_featured = wp_remove_object_terms( $variation_id, 'featured', 'product_visibility' );
			}
		}

		if ( is_wp_error( $set_featured ) ) {
			return false;
		}

		delete_transient( 'wc_featured_products' );

		return true;
	}

	/**
	 * Add main product taxonomies to variation.
	 *
	 * @param int $variation_id
	 */
	public static function set_taxonomies( $variation_id ) {
		$taxonomies = self::get_taxonomies();

		if ( empty( $taxonomies ) ) {
			return;
		}

		$parent_product_id = wp_get_post_parent_id( $variation_id );

		if ( $parent_product_id ) {
			foreach ( $taxonomies as $taxonomy ) {
				$terms = (array) wp_get_post_terms( $parent_product_id, $taxonomy, array( "fields" => "ids" ) );
				wp_set_post_terms( $variation_id, $terms, $taxonomy );
			}
		}
	}

	/**
	 * Get visibility.
	 *
	 * @param int $variation_id
	 *
	 * @return array
	 */
	public static function get_visibility( $variation_id ) {
		$visibility = get_post_meta( $variation_id, '_visibility', true );

		if ( ! is_array( $visibility ) || empty( $visibility ) ) {
			return array( "hidden" );
		}

		return $visibility;
	}

	/**
	 * Get featured visibility.
	 *
	 * @param int $variation_id
	 *
	 * @return bool
	 */
	public static function get_featured_visibility( $variation_id ) {
		return get_post_meta( $variation_id, '_featured', true );
	}

	/**
	 * Get add to cart setting.
	 *
	 * @param int $variation_id
	 *
	 * @return bool
	 */
	public static function get_add_to_cart( $variation_id ) {
		return get_post_meta( $variation_id, '_disable_add_to_cart', true );
	}

	/**
	 * Get listings only setting.
	 *
	 * @param int $variation_id
	 *
	 * @return bool
	 */
	public static function get_listings_only( $variation_id ) {
		return get_post_meta( $variation_id, '_listings_only', true );
	}

	/**
	 * Set total sales.
	 *
	 * @param int $variation_id
	 *
	 * @return bool
	 */
	public static function set_total_sales( $variation_id ) {
		$total_sales = self::get_variation_sales( $variation_id );
		update_post_meta( $variation_id, 'total_sales', $total_sales );

		do_action( 'iconic_wssv_set_total_sales', $variation_id, $total_sales );

		return true;
	}

	/**
	 * Get total variation sales
	 *
	 * @param int $variation_id
	 *
	 * @return int
	 */
	public static function get_variation_sales( $variation_id ) {
		global $wpdb;

		$total_sales = $wpdb->get_var(
			$wpdb->prepare(
				"
                SELECT SUM(`quantities`.`meta_value`)
                FROM `{$wpdb->prefix}woocommerce_order_itemmeta` as `itemmeta`
                 LEFT JOIN  `{$wpdb->prefix}woocommerce_order_itemmeta` AS  `quantities` ON `itemmeta`.`order_item_id` = `quantities`.`order_item_id`
                  AND `quantities`.`meta_key` = '_qty'
                 LEFT JOIN `{$wpdb->prefix}woocommerce_order_items` as `items` ON `items`.`order_item_id`=`itemmeta`.`order_item_id`
                WHERE `itemmeta`.`meta_key` = '_variation_id'
                 AND `itemmeta`.`meta_value` = %d
                ",
				$variation_id
			)
		);

		return apply_filters( 'iconic_wssv_variation_total_sales', $total_sales );
	}

	/**
	 * Inherit parent rating.
	 *
	 * @param float      $value
	 * @param WC_Product $product
	 *
	 * @return float
	 */
	public static function get_average_rating( $value, $product ) {
		$parent_product = wc_get_product( $product->get_parent_id() );

		if ( ! $parent_product ) {
			return $value;
		}

		return $parent_product->get_average_rating();
	}

	/**
	 * Set variation title.
	 *
	 * @param int    $variation_id
	 * @param string $title
	 */
	public static function set_title( $variation_id, $title ) {
		if ( empty( $title ) ) {
			return;
		}

		global $wpdb;

		$allowed_html = Iconic_WSSV_Helpers::wp_kses_allowed_html_title();
		$title        = wp_kses( $title, $allowed_html );

		update_post_meta( $variation_id, '_jck_wssv_display_title', $title );
		$wpdb->update( $wpdb->posts, array( 'post_title' => $title ), array( 'ID' => $variation_id ) );
	}

	/**
	 * Refresh title based on meta.
	 *
	 * @param int $variation_id
	 */
	public static function refresh_title( $variation_id ) {
		global $wpdb;

		$title = get_post_meta( $variation_id, '_jck_wssv_display_title', true );

		if ( ! $title ) {
			return;
		}

		$wpdb->update( $wpdb->posts, array( 'post_title' => $title ), array( 'ID' => $variation_id ) );
	}

	/**
	 * Set add to cart.
	 *
	 * @param int  $variation_id
	 * @param bool $add_to_cart
	 */
	public static function set_add_to_cart( $variation_id, $add_to_cart ) {
		self::set_checkbox_meta( $variation_id, '_disable_add_to_cart', $add_to_cart );
	}

	/**
	 * Set listings only.
	 *
	 * @param int  $variation_id
	 * @param bool $listings_only
	 */
	public static function set_listings_only( $variation_id, $listings_only ) {
		self::set_checkbox_meta( $variation_id, '_listings_only', $listings_only );
	}

	/**
	 * Set generic checkbox meta.
	 *
	 * @param $variation_id
	 * @param $key
	 * @param $value
	 */
	public static function set_checkbox_meta( $variation_id, $key, $value ) {
		if ( ! $value ) {
			delete_post_meta( $variation_id, $key );

			return;
		}

		update_post_meta( $variation_id, $key, $value );
	}

	/**
	 * Get variation taxonomies.
	 *
	 * @param int $parent_product_id
	 *
	 * @return array
	 */
	public static function get_taxonomies( $parent_product_id = null ) {
		return apply_filters( 'iconic_wssv_variation_taxonomies', array(
			'product_cat',
			'product_tag',
		) );
	}

	/**
	 * Ignore custom visibility setting in cart.
	 *
	 * @param string $permalink
	 * @param array  $cart_item
	 * @param string $cart_item_key
	 *
	 * @return string
	 */
	public static function cart_item_permalink( $permalink, $cart_item, $cart_item_key ) {
		$_product = $cart_item['data'];

		remove_filter( 'woocommerce_product_is_visible', array( __CLASS__, 'is_visible' ), 10 );
		// If variation is in cart, always return true.
		$permalink = $_product->get_permalink( $cart_item );
		add_filter( 'woocommerce_product_is_visible', array( __CLASS__, 'is_visible' ), 10, 2 );

		return $permalink;
	}

	/**
	 * Remove listing only variations.
	 *
	 * @param $children
	 * @param $product
	 * @param $deprecated
	 *
	 * @return bool
	 */
	public static function remove_listing_only_variations( $children, $product, $deprecated ) {
		if ( is_admin() || empty( $children ) ) {
			return $children;
		}

		foreach ( $children as $i => $variation_id ) {
			$listing_only = self::get_listings_only( $variation_id );

			if ( ! $listing_only ) {
				continue;
			}

			unset( $children[ $i ] );
		}

		return $children;
	}
}