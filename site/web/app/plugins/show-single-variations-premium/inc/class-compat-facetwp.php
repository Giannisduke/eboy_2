<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * FacetWP compatibility Class
 *
 * @since 1.1.10
 */
class Iconic_WSSV_Compat_FacetWP {
	/**
	 * Init.
	 */
	public static function init() {
		if ( ! Iconic_WSSV_Core_Helpers::is_plugin_active( 'facetwp/index.php' ) ) {
			return;
		}

		add_filter( 'facetwp_query_args', array( __CLASS__, 'query_args' ), 10, 2 );
		add_filter( 'facetwp_indexer_query_args', array( __CLASS__, 'indexer_query_args' ) );
		add_filter( 'facetwp_index_row', array( __CLASS__, 'index_variations' ), 10, 2 );
		add_filter( 'facetwp_index_row', array( __CLASS__, 'unindex_hidden_parents' ), 20, 2 );
		add_filter( 'woocommerce_is_filtered', array( __CLASS__, 'is_filtered' ) );
		add_filter( 'facetwp_indexer_post_facet_defaults', array( __CLASS__, 'indexer_post_facet_defaults' ), 10, 2 );
	}

	/**
	 * @param array            $query_args
	 * @param FacetWP_Renderer $renderer
	 *
	 * @return array
	 */
	public static function query_args( $query_args, $renderer ) {
		if ( ! is_array( $query_args['post_type'] ) || ! in_array( 'product_variation', $query_args['post_type'] ) ) {
			return $query_args;
		}

		if ( empty( $query_args['tax_query'] ) ) {
			return $query_args;
		}

		$query_args['tax_query'] = Iconic_WSSV_Query::update_tax_query( $query_args['tax_query'], true );

		return $query_args;
	}

	/**
	 * Indexer query args.
	 *
	 * If we're indexing a variation, and it has 'filtered' visibility, set the
	 * post type to 'product_variation' so that it's indexed on product save.
	 *
	 * By default 'product_variation' isn't included because it's not 'public'.
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	public static function indexer_query_args( $args ) {
		if ( $args['post_type'] !== 'any' ) {
			return $args;
		}

		if ( empty( $args['p'] ) ) {
			return $args;
		}

		$post_type = get_post_type( $args['p'] );

		if ( $post_type !== 'product_variation' ) {
			return $args;
		}

		$visibility = Iconic_WSSV_Product_Variation::get_visibility( $args['p'] );

		if ( in_array( 'filtered', $visibility ) ) {
			$args['post_type'] = 'product_variation';
		}

		return $args;
	}

	/**
	 * Index individual variations.
	 *
	 * @param array           $params
	 * @param FacetWP_Indexer $indexer
	 *
	 * @return array|bool
	 */
	public static function index_variations( $params, $indexer ) {
		if ( ! is_array( $params ) ) {
			return $params;
		}

		if ( ! self::is_product_or_variation( $params['post_id'] ) ) {
			return $params;
		}

		if ( $params['variation_id'] <= 0 || $params['post_id'] === $params['variation_id'] ) {
			return $params;
		}

		$visibility = Iconic_WSSV_Product_Variation::get_visibility( $params['variation_id'] );

		if ( in_array( 'filtered', $visibility ) ) {
			$new_params            = $params;
			$new_params['post_id'] = $params['variation_id'];

			$indexer->insert( $new_params );
		}

		return $params;
	}

	/**
	 * Unindex parent if it's hidden from filters.
	 *
	 * @param $params
	 * @param $indexer
	 *
	 * @return array|bool
	 */
	public static function unindex_hidden_parents( $params, $indexer ) {
		if ( ! is_array( $params ) ) {
			return $params;
		}

		if ( ! self::is_product_or_variation( $params['post_id'] ) ) {
			return $params;
		}

		if ( $params['post_id'] !== $params['variation_id'] ) {
			return $params;
		}

		$visibility_terms = Iconic_WSSV_Product::get_visibility_term_slugs( $params['post_id'] );

		if ( in_array( 'exclude-from-filtered', $visibility_terms ) ) {
			return false;
		}

		return $params;
	}

	/**
	 * Is product or variation.
	 *
	 * @param $id
	 *
	 * @return bool
	 */
	public static function is_product_or_variation( $id ) {
		$post_type = get_post_type( $id );

		if ( $post_type !== 'product' && $post_type !== 'product_variation' ) {
			return false;
		}

		return true;
	}

	/**
	 * Modify is filtered.
	 *
	 * @param bool $bool
	 *
	 * @return bool
	 */
	public static function is_filtered( $bool ) {
		if ( empty( $_GET ) ) {
			return $bool;
		}

		foreach ( $_GET as $key => $value ) {
			if ( strpos( $key, 'fwp_' ) === 0 || strpos( $key, '_' ) === 0 ) {
				$bool = true;
				break;
			}
		}

		return $bool;
	}

	/**
	 * Index "not for variation" attributes as taxonomies, not post meta.
	 *
	 * @param $defaults
	 * @param $args
	 *
	 * @return mixed
	 */
	public static function indexer_post_facet_defaults( $defaults, $args ) {
		if ( empty( $defaults['post_id'] ) ) {
			return $defaults;
		}

		$post_type = get_post_type( $defaults['post_id'] );

		if ( $post_type !== 'product_variation' ) {
			return $defaults;
		}

		if ( 0 !== strpos( $args['facet']['source'], 'tax/pa_' ) ) {
			return $defaults;
		}

		$parent_product_id = wp_get_post_parent_id( $defaults['post_id'] );

		if ( ! $parent_product_id ) {
			return $defaults;
		}

		$parent_product = wc_get_product( $parent_product_id );

		if ( ! $parent_product ) {
			return $defaults;
		}

		$attribute_name = str_replace( 'tax/', '', $args['facet']['source'] );
		$attributes     = $parent_product->get_attributes();

		if ( ! array_key_exists( $attribute_name, $attributes ) ) {
			return $defaults;
		}

		if ( $attributes[ $attribute_name ]->get_variation() ) {
			return $defaults;
		}

		$defaults['facet_source'] = $args['facet']['source'];

		return $defaults;
	}
}