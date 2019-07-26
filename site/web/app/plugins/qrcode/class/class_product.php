<?php
require __DIR__ . '/../vendor/autoload.php';

use Automattic\WooCommerce\Client;

class product{
 private $text;
 public $woocommerce;
 private $parameter;

   public function setParameter($param){
       $this->parameter = $param;
   }
   public function getParameter(){
       return $this->parameter;
   }

    function __construct($woocommerce_API){
        $this->woocommerce = new Client($woocommerce_API['url'], // Your store URL
        $woocommerce_API['consumerKey'], // Your consumer key
        $woocommerce_API['consumerSecret'], // Your consumer secret
        [
            'wp_api'=>$woocommerce_API['wp_api'],// Enable the WP REST API integration
            'version'=>$woocommerce_API['version'],    // WooCommerce WP REST API version
            'query_string_auth' => true //for support Https
        ]);

    }

    public function checkstatus(){
        return $this->woocommerce->get('');
    }

    public function getAllProductList($perpage=10,$page=1,$status='any'){
        $param = [            
           'per_page'=>$perpage,
           'page'=>$page,
           'status'=> $status
        ];
      
        return $this->woocommerce->get('products',$param);
    }

    public function ProductCount(){
       $w = $this->woocommerce->get('products/count');
       return $w;
    }

    public function getProductItems($product_id){
        $text = "products/".$product_id;
       
        return $this->woocommerce->get($text);
    }
    
    public function getProductDescription($product_id){
        $w = $this->woocommerce->get('products/'.$product_id);
        return $w['description'];
    }

    public function getProductMata($product_id){
        $w = $this->getProductItems($product_id);
        for($i = 0 ;$i < count($w['meta_data']); $i++){
           if($w['meta_data'][$i]['key'] === 'yikes_woo_products_tabs'){
                return $w['meta_data'][$i]['value'];
             
           }
        }
    }

}