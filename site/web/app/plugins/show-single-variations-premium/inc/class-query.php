<?php

/**
 * Iconic_WSSV_Query Class.
 *
 * All methods for modify the WooCommerce Query.
 *
 * @since 1.1.5
 */
class Iconic_WSSV_Query {
	/**
	 * Variation IDs with missing parent
	 *
	 * @access protected
	 * @var array $variation_ids_with_missing_parent
	 */
	protected $variation_ids_with_missing_parent = null;

	/**
	 * Init.
	 */
	public static function init() {
		if ( is_admin() ) {
			return;
		}

		add_action( 'woocommerce_product_query', array( __CLASS__, 'add_variations_to_product_query' ), 50, 2 );
		add_filter( 'woocommerce_shortcode_products_query', array( __CLASS__, 'add_variations_to_shortcode_query', ), 10, 2 );
		add_filter( 'woocommerce_product_related_posts_query', array( __CLASS__, 'add_variations_to_related_products' ), 10, 2 );
		add_filter( 'posts_where', array( __CLASS__, 'posts_where' ), 10, 2 );
	}

	/**
	 * Add variations to the product query.
	 *
	 * @param WP_Query $q
	 * @param WC_Query $wc_query
	 */
	public static function add_variations_to_product_query( $q, $wc_query ) {
		if ( ! is_woocommerce() || ! $q->is_main_query() || empty( $q->query_vars['wc_query'] ) ) {
			return;
		}

		// Add product variations to the query

		$post_type   = (array) $q->get( 'post_type' );
		$post_type[] = 'product_variation';
		if ( ! in_array( 'product', $post_type ) ) {
			$post_type[] = 'product';
		}
		$q->set( 'post_type', array_filter( $post_type ) );

		// Don't get variations with unpublished parents

		$unpublished_variable_product_ids = self::get_unpublished_variable_product_ids();
		if ( ! empty( $unpublished_variable_product_ids ) ) {
			$post_parent__not_in = (array) $q->get( 'post_parent__not_in' );
			$q->set( 'post_parent__not_in', array_merge( $post_parent__not_in, $unpublished_variable_product_ids ) );
		}

		// Don't get variations with missing parents :(

		$variation_ids_with_missing_parent = self::get_variation_ids_with_missing_parent();
		if ( ! empty( $variation_ids_with_missing_parent ) ) {
			$post__not_in = (array) $q->get( 'post__not_in' );
			$q->set( 'post__not_in', array_merge( $post__not_in, $variation_ids_with_missing_parent ) );
		}

		if ( version_compare( WC_VERSION, '3.0.0', '<' ) ) {
			// update the meta query to include our variations

			$meta_query = (array) $q->get( 'meta_query' );
			$meta_query = self::update_meta_query( $meta_query );
			$q->set( 'meta_query', $meta_query );
		} else {
			// update the tax query to include our variations
			$tax_query = (array) $q->get( 'tax_query' );
			$tax_query = self::update_tax_query( $tax_query );
			$q->set( 'tax_query', $tax_query );
		}

		return;
	}

	/*
	 * Add variaitons to shortcode queries
	 *
	 * @param arr $query_args
	 * @param arr $shortcode_args
	 */
	public static function add_variations_to_shortcode_query( $query_args, $shortcode_args ) {
		// Add product variations to the query

		$post_type   = (array) $query_args['post_type'];
		$post_type[] = 'product_variation';

		$query_args['post_type'] = $post_type;

		// Don't get variations with unpublished parents

		$unpublished_variable_product_ids = self::get_unpublished_variable_product_ids();
		if ( $unpublished_variable_product_ids ) {
			$post_parent__not_in               = isset( $query_args['post_parent__not_in'] ) ? (array) $query_args['post_parent__not_in'] : array();
			$query_args['post_parent__not_in'] = array_merge( $post_parent__not_in, $unpublished_variable_product_ids );
		}

		// Don't get variations with missing parents :(

		$variation_ids_with_missing_parent = self::get_variation_ids_with_missing_parent();
		if ( $variation_ids_with_missing_parent ) {
			$post__not_in               = isset( $query_args['post__not_in'] ) ? (array) $query_args['post__not_in'] : array();
			$query_args['post__not_in'] = array_merge( $post__not_in, $variation_ids_with_missing_parent );
		}

		if ( version_compare( WC_VERSION, '3.0.0', '<' ) ) {
			// update the meta query to include our variations

			$meta_query               = (array) $query_args['meta_query'];
			$meta_query               = self::update_meta_query( $meta_query );
			$query_args['meta_query'] = $meta_query;
		} else {
			// update the tax query to include our variations

			$tax_query               = (array) $query_args['tax_query'];
			$tax_query               = self::update_tax_query( $tax_query );
			$query_args['tax_query'] = $tax_query;
		}

		return $query_args;
	}

	/**
	 * Get unpublished variable product IDs
	 *
	 * Get's an array of product IDs where the product
	 * is variable and has not been published (i.e. is in the bin)
	 *
	 * @since 1.1.0
	 * @return mixed array
	 */
	public static function get_unpublished_variable_product_ids() {
		static $unpublished_variable_product_ids = null;

		if ( ! is_null( $unpublished_variable_product_ids ) ) {
			return $unpublished_variable_product_ids;
		}

		$unpublished_variable_product_ids = array();

		global $wpdb;

		$product_type = get_term_by( 'slug', 'variable', 'product_type' );

		if ( ! $product_type ) {
			return $unpublished_variable_product_ids;
		}

		$products = $wpdb->get_results( $wpdb->prepare(
			"
			SELECT *
			FROM $wpdb->posts 
			LEFT JOIN $wpdb->term_relationships
			ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id)
			WHERE 1=1 
			AND ( $wpdb->term_relationships.term_taxonomy_id IN (%d) )
			AND $wpdb->posts.post_type = 'product'
			AND ((
				$wpdb->posts.post_status = 'future' OR 
				$wpdb->posts.post_status = 'draft' OR 
				$wpdb->posts.post_status = 'pending' OR 
				$wpdb->posts.post_status = 'trash' OR 
				$wpdb->posts.post_status = 'auto-draft'
			))
			GROUP BY $wpdb->posts.ID
			ORDER BY $wpdb->posts.post_date DESC
			", $product_type->term_taxonomy_id ),
			ARRAY_A
		);

		$unpublished_variable_product_ids = wp_list_pluck( $products, 'ID' );

		return $unpublished_variable_product_ids;
	}

	/**
	 * Get variation IDs with missing parents
	 *
	 * @since 1.1.2
	 * @return mixed bool|array
	 */
	public static function get_variation_ids_with_missing_parent() {
		static $variation_ids_with_missing_parent = null;

		if ( ! is_null( $variation_ids_with_missing_parent ) ) {
			return $variation_ids_with_missing_parent;
		}

		global $wpdb;

		$variation_ids = $wpdb->get_results(
			"
			SELECT  p1.ID
			FROM $wpdb->posts p1
			WHERE p1.post_type = 'product_variation'
			AND p1.post_parent NOT IN (
				SELECT DISTINCT p2.ID
				FROM $wpdb->posts p2
				WHERE p2.post_type = 'product'
			)
			", ARRAY_A
		);

		$variation_ids_with_missing_parent = wp_list_pluck( $variation_ids, 'ID' );

		return $variation_ids_with_missing_parent;
	}

	/*
	 * Update meta query
	 *
	 * Add OR parameters to also search for variations with specific visibility
	 *
	 * @param array $meta_query]
	 * @return array
	 */
	public static function update_meta_query( $meta_query ) {
		$index = 0;

		if ( ! empty( $meta_query ) ) {
			foreach ( $meta_query as $index => $meta_query_item ) {
				if ( isset( $meta_query_item['key'] ) && $meta_query_item['key'] == "_visibility" ) {
					$meta_query[ $index ]             = array();
					$meta_query[ $index ]['relation'] = 'OR';

					$meta_query[ $index ]['visibility_visible'] = array(
						'key'     => '_visibility',
						'value'   => 'visible',
						'compare' => 'LIKE',
					);

					if ( is_search() ) {
						$meta_query[ $index ]['visibility_search'] = array(
							'key'     => '_visibility',
							'value'   => 'search',
							'compare' => 'LIKE',
						);
					} else {
						$meta_query[ $index ]['visibility_catalog'] = array(
							'key'     => '_visibility',
							'value'   => 'catalog',
							'compare' => 'LIKE',
						);
					}

					if ( is_filtered() ) {
						$meta_query[ $index ]['visibility_filtered'] = array(
							'key'     => '_visibility',
							'value'   => 'filtered',
							'compare' => 'LIKE',
						);
					}
				}
			}
		}

		return $meta_query;
	}

	/**
	 * Update tax query.
	 *
	 * @param array $tax_query
	 * @param bool  $filtered
	 *
	 * @return array
	 */
	public static function update_tax_query( $tax_query, $filtered = false ) {
		$exclude_from_filtered_term = get_term_by( 'slug', 'exclude-from-filtered', 'product_visibility' );

		if ( $exclude_from_filtered_term && ( $filtered || is_filtered() ) ) {
			if ( empty( $tax_query ) ) {
				$tax_query['relation'] = 'AND';
				$tax_query[]           = array(
					'taxonomy'         => 'product_visibility',
					'field'            => 'term_taxonomy_id',
					'terms'            => array( $exclude_from_filtered_term->term_taxonomy_id ),
					'operator'         => 'NOT IN',
					'include_children' => 1,
				);
			} else {
				$exclude_from_catalog_term = get_term_by( 'slug', 'exclude-from-catalog', 'product_visibility' );

				foreach ( $tax_query as $index => $tax_query_item ) {
					if ( ! is_array( $tax_query_item ) ) {
						continue;
					}

					if ( empty( $tax_query_item['taxonomy'] ) ) {
						continue;
					}

					if ( $tax_query_item['taxonomy'] !== 'product_visibility' ) {
						continue;
					}

					// Assign the current visibility query.
					$current_visibility_query                     = $tax_query[ $index ];
					$current_visibility_query['include_children'] = 1;
					$current_visibility_query['terms'][]          = $exclude_from_filtered_term->term_taxonomy_id;
					// Create another visibility query choice
					$alt_visibility_query = $current_visibility_query;

					// Replace 'exclude form catalog' with 'exclude form filtered'.
					foreach ( $alt_visibility_query['terms'] as $term_index => $term_id ) {
						if ( $term_id === $exclude_from_catalog_term->term_taxonomy_id ) {
							unset( $alt_visibility_query['terms'][ $term_index ] );
						}
					}

					// Use both queries with an 'OR' choice.
					$tax_query[ $index ]             = array();
					$tax_query[ $index ]['relation'] = 'OR';
					$tax_query[ $index ][]           = $alt_visibility_query;
					$tax_query[ $index ][]           = $current_visibility_query;
				}
			}
		}

		return $tax_query;
	}

	/**
	 * @param array $query
	 * @param int   $product_id
	 *
	 * @return array
	 */
	public static function add_variations_to_related_products( $query, $product_id ) {
		$find    = "AND p.post_type = 'product'";
		$replace = "AND ( p.post_type = 'product' OR p.post_type = 'product_variation' )";

		$query['where'] = str_replace( $find, $replace, $query['where'] );

		return $query;
	}

	/**
	 * Remove private variations from listings.
	 *
	 * @param $where
	 *
	 * @return mixed
	 */
	public static function posts_where( $where, $q ) {
		if ( is_admin() || ! $q->is_main_query() ) {
			return $where;
		}

		global $wpdb;

		$find    = "OR $wpdb->posts.post_author = 1 AND $wpdb->posts.post_status = 'private'";
		$replace = "OR ( $wpdb->posts.post_author = 1 AND $wpdb->posts.post_status = 'private' AND $wpdb->posts.post_type != 'product_variation' )";

		return str_replace( $find, $replace, $where );
	}
}