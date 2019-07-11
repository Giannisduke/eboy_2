<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * BeRocket Ajax Filters compatibility Class
 *
 * @since 1.1.14
 */
class Iconic_WSSV_Compat_BeRocket_Ajax_Filters {
	/**
	 * The current tax query.
	 *
	 * @var null|WP_Query|array
	 */
	static public $wc_tax_query = null;

	/**
	 * Init.
	 */
	public static function init() {
		if ( ! Iconic_WSSV_Core_Helpers::is_plugin_active( 'woocommerce-ajax-filters/woocommerce-filters.php' ) ) {
			return;
		}

		add_filter( 'pre_get_posts', array( __CLASS__, 'cache_wc_query' ), 99988 );
		add_filter( 'pre_get_posts', array( __CLASS__, 'restore_wc_query' ), 999999 );
	}

	/**
	 * We need to cache the query before "BeRocket´s Ajax Filters"
	 * plugin filters the query, so we can restore the visibility
	 * parameter later.
	 *
	 * @param WP_Query $query
	 *
	 * @return WP_Query
	 */
	public static function cache_wc_query( $query ) {
		if ( $query->get( 'wc_query' ) ) {
			self::$wc_tax_query = $query->get( 'tax_query' );
		}

		return $query;
	}

	/**
	 * We need to restore the visibility parameter after "BeRocket's
	 * Ajax Filters" removed it from the original query.
	 *
	 * @param WP_Query $query
	 *
	 * @return WP_Query
	 */
	public static function restore_wc_query( $query ) {
		if ( ! empty( self::$wc_tax_query ) ) {
			$visibility_query = self::get_product_visibility_query( self::$wc_tax_query );

			if ( ! empty( $visibility_query ) ) {
				$tax_query   = self::remove_existing_visibility_query( $query->get( 'tax_query' ) );
				$tax_query[] = $visibility_query;
				$query->set( 'tax_query', $tax_query );
			}

			self::$wc_tax_query = null;
		}

		return $query;
	}

	/**
	 * Get product visibility queries from a tax query array.
	 *
	 * @param array $tax_query
	 *
	 * @return array
	 */
	public static function get_product_visibility_query( $tax_query ) {
		if ( empty( $tax_query ) ) {
			return $tax_query;
		}

		$visibility_query = array();

		foreach ( $tax_query as $index => $visibility_queries ) {
			if ( isset( $visibility_queries['taxonomy'] ) && $visibility_queries['taxonomy'] === 'product_visibility' ) {
				$visibility_query[] = $visibility_queries;
			} elseif ( isset( $visibility_queries['relation'] ) ) {
				$visibility_query[] = self::get_product_visibility_query( $tax_query[ $index ] );
			}
		}

		return $visibility_query;
	}

	/**
	 * Remove product visibility queries from a tax query array.
	 *
	 * @param array $tax_query
	 *
	 * @return array
	 */
	public static function remove_existing_visibility_query( $tax_query ) {
		if ( empty( $tax_query ) ) {
			return $tax_query;
		}

		foreach ( $tax_query as $index => $visibility_queries ) {
			if ( isset( $visibility_queries['taxonomy'] ) && $visibility_queries['taxonomy'] === 'product_visibility' ) {
				unset( $tax_query[ $index ] );
				if ( count( $tax_query ) == 1 && isset( $tax_query['relation'] ) ) {
					unset( $tax_query['relation'] );
					break;
				}
			} elseif ( isset( $visibility_queries['relation'] ) ) {
				$tax_query[ $index ] = self::remove_existing_visibility_query( $tax_query[ $index ] );
				if ( empty( $tax_query[ $index ] ) ) {
					unset( $tax_query[ $index ] );
				}
			}
		}

		return $tax_query;
	}
}
