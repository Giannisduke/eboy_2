<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * @class    Iconic_WSSV_Menu_Order
 * @version  1.0.0
 * @author   Iconic
 */
class Iconic_WSSV_Menu_Order {
	/**
	 * Init.
	 */
	public static function init() {
		if ( is_admin() ) {
			return;
		}

		add_filter( 'posts_clauses', array( __CLASS__, 'order_by_menu_order_post_clauses' ) );
	}

	/**
	 * Is query for products and variations?
	 *
	 * @param string $where
	 *
	 * @return bool
	 */
	public static function query_has_products_and_variations( $where ) {
		preg_match( '/post_type IN \((.*?)\)/', $where, $matches, PREG_OFFSET_CAPTURE );

		if ( ! $matches ) {
			return false;
		}

		$post_types = isset( $matches[1] ) ? $matches[1][0] : null;

		if ( ! $post_types ) {
			return false;
		}

		$post_types = str_replace( array( '"', "'" ), '', $post_types );
		$post_types = str_replace( ', ', ',', $post_types );
		$post_types = explode( ',', $post_types );

		return in_array( 'product', $post_types ) && in_array( 'product_variation', $post_types );
	}

	/**
	 * Modify menu order post clauses.
	 *
	 * @param $args
	 *
	 * @return array
	 */
	public static function order_by_menu_order_post_clauses( $args ) {
		global $wp_query, $wpdb;

		// Don't change order on other admin pages, non woo pages, etc
		if ( is_admin() || ! self::query_has_products_and_variations( $args['where'] ) ) {
			return $args;
		}

		if ( empty( $args['orderby'] ) ) {
			return $args;
		}

		// Don't change order if it's not menu/title
		if (
			$args['orderby'] !== "{$wpdb->posts}.menu_order ASC, {$wpdb->posts}.post_title ASC" &&
			$args['orderby'] !== "{$wpdb->posts}.menu_order, {$wpdb->posts}.menu_order ASC, {$wpdb->posts}.post_title ASC"
		) {
			return $args;
		}

		/**
		 * Custom query to order by parent/child.
		 *
		 * 1. If parent = 0 and menu order = 0
		 * 2. Used a negative product ID and concat
		 *    .99999999999 to make it smaller than its variations
		 * 3. Otherwise, use the normal menu order.
		 * 4. If parent != 0 (product variation) concatenate the following.
		 * 5. If parent = 0, use the negative ID as per step 2. otherwise, use the menu order.
		 * 6. If parent = 0, use an inverted 11 digit padding. As these numbers are minus numbers, we need a higher
		 *    decimal to ensure the ordering is correct. Otherwise, pad out the menu_order with 11 0s.
		 *
		 * It works, but it ain't pretty.
		 */
		$args['join']    .= " LEFT JOIN {$wpdb->posts} parents ON {$wpdb->posts}.post_parent = parents.ID";
		$args['fields']  .= ", IF( 
			{$wpdb->posts}.post_parent=0, 
			IF(                                                                             /* [1] */
				{$wpdb->posts}.menu_order = 0, 
				CONCAT(                                                                     /* [2] */
					CAST( {$wpdb->posts}.ID as SIGNED )  * -1, 
					'.99999999999'
				), 
				{$wpdb->posts}.menu_order                                                   /* [3] */
			), 
			CONCAT(                                                                         /* [4] */
				IF(                                                                         /* [5] */
					parents.menu_order = 0, 
					CAST( parents.ID as SIGNED )  * -1, 
					parents.menu_order 
	 			), 
				'.', 
				IF(                                                                         /* [6] */
					parents.menu_order = 0, 
					Lpad( ( 100000000000 - ( {$wpdb->posts}.menu_order + 1 ) ), 11, '0' ),
					Lpad( {$wpdb->posts}.menu_order + 1, 11, '0' )
				)
			) 
		) AS 'iconic_wssv_order'";
		$args['orderby'] = "CAST( iconic_wssv_order as DECIMAL( 22, 11 ) ) asc";

		return $args;
	}
}