<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_filter( 'wpsf_register_settings_iconic_wssv', 'iconic_wssv_settings' );

/**
 * WooCommerce Show Single variations Settings
 *
 * @param array $wpsf_settings
 *
 * @return array
 */
function iconic_wssv_settings( $wpsf_settings ) {
	$wpsf_settings['sections']['tools'] = array(
		'tab_id'              => 'dashboard',
		'section_id'          => 'tools',
		'section_title'       => __( 'Tools', 'iconic-wssv' ),
		'section_description' => '',
		'section_order'       => 20,
		'fields'              => array(
			array(
				'id'       => 'process-product-visibility',
				'title'    => __( 'Process Product Visibility', 'iconic-wssv' ),
				'subtitle' => __( 'Run this to ensure the visibility of all products (including variations) reflects your settings.', 'iconic-wssv' ),
				'type'     => 'custom',
				'default'  => Iconic_WSSV_Settings::get_process_product_visibility_link(),
			),
		),
	);

	return $wpsf_settings;
}