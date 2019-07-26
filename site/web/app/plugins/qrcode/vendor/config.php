<?php // Install:
// composer require automattic/woocommerce

// Setup:
require __DIR__ . '/../vendor/autoload.php';

use Automattic\WooCommerce\Client;

/*$woocommerce = new Client(
    'http://kocomeishop.com/store', // Your store URL
    'ck_7830aa2ca31916bea498455042e4779ac94ad779', // Your consumer key
    'cs_583a5cc732fc2eb5a9f14a17bef0ee8a2b434826', // Your consumer secret
    [
        'wp_api' => true, // Enable the WP REST API integration
        'version' => 'wc/v2' // WooCommerce WP REST API version
    ]
);*/

define('_baseUrl','https://www.playground-inseoul.com/shop');


$woocommerce = [
    'url'=>_baseUrl."",
    'consumerKey'=>'ck_8ac59de659d9c91372cf810f280c2e3dd577ad85',
    'consumerSecret'=>'cs_ae523e90ff126bb8f1386deedd6fa3801fffdfe3',
    'wp_api'=>true,
    'version'=>'wc/v2'
];


?>