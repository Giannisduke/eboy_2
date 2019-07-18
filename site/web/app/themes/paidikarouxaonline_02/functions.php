<?php
/**
 * Sage includes
 *
 * The $sage_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 * @link https://github.com/roots/sage/pull/1042
 */
$sage_includes = [
  'lib/assets.php',    // Scripts and stylesheets
  'lib/extras.php',    // Custom functions
  'lib/setup.php',     // Theme setup
  'lib/titles.php',    // Page titles
  'lib/wrapper.php',   // Theme wrapper class
  'lib/customizer.php' // Theme customizer
];

foreach ($sage_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);

// Register Custom Navigation Walker
require_once get_template_directory() . '/class-wp-bootstrap-navwalker.php';

remove_action('welcome_panel', 'wp_welcome_panel');

add_filter('widget_text','do_shortcode');

function cc_mime_types( $mimes ){
    $mimes['svg'] = 'image/svg+xml';

    return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );


add_filter( 'facetwp_is_main_query', function( $is_main_query, $query ) {
	if ( isset( $query->query_vars['facetwp'] ) ) {
		$is_main_query = (bool) $query->query_vars['facetwp'];
	}
	return $is_main_query;
}, 10, 2 );

add_filter( 'get_search_query', function( $query ) {
    if ( function_exists( 'FWP' ) && '' == $query ) {
        return FWP()->facet->http_params['get']['s'];
    }
    return $query;
});

function themeslug_theme_customizer( $wp_customize ) {
    $wp_customize->add_section( 'eboy_theme_logo_section' , array(
    'title'       => __( 'Logo', 'paidikarouxaonline' ),
    'priority'    => 1,
    'description' => 'Upload a logo to replace the default site name and info',
) );
$wp_customize->add_setting( 'themeslug_logo' );
$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'themeslug_logo', array(
    'label'    => __( 'Logo', 'paidikarouxaonline' ),
    'section'  => 'eboy_theme_logo_section',
    'settings' => 'themeslug_logo',
    'extensions' => array( 'jpg', 'jpeg', 'gif', 'png', 'svg' ),
) ) );
}



remove_action( 'woocommerce_shop_loop_item_title','woocommerce_template_loop_product_title', 10 );
add_action('woocommerce_shop_loop_item_title', 'abChangeProductsTitle', 10 );
function abChangeProductsTitle() {
    echo '<div class="d-flex flex-row product-info"><div class="w-75"><h5 class="woocommerce-loop-product_title">' . get_the_title() . '</h5></div>';
}



remove_action ('woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
remove_action ('woocommerce_archive_description', 'woocommerce_product_archive_description', 10 );


function woocommerce_taxonomy_archive_description_custom() {
  global $product;
  if ( is_search() ) {
    return;
  }

  if ( is_product_taxonomy() && 0 === absint( get_query_var( 'paged' ) ) ) {
    $term = get_queried_object();

    if ( $term && ! empty( $term->description ) ) {
      echo '<p class="collapse" id="collapseExample" aria-expanded="false">' . $term->description . '</p>'; // WPCS: XSS ok.

    }
  }

  if ( is_shop() || is_page() ) {
    echo '<p class="collapse" id="collapseExample" aria-expanded="false">';
    $id=3594;
  //  $id=210;
    $post = get_post($id);
    $content = apply_filters('the_content', $post->post_content);
    echo $content;
    echo '</p>';
  }
  echo '<a role="button" class="collapsed" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><img class="ico svg-convert" src=" ' .get_template_directory_uri() .'/dist/images/arrow_down.svg"></a>';

}
add_action ('woocommerce_archive_description', 'woocommerce_taxonomy_archive_description_custom', 10 );

remove_filter('the_content', 'wpautop');

/**
* Change the “No products in the cart” message when hovering over the mini-cart
*
*/

function lar_text_strings( $translated_text, $text, $domain ) {
switch ( $translated_text ) {
case 'No products in the cart.' :
$translated_text = __( 'Αδειο Καλάθι', 'woocommerce' );
break;
}
return $translated_text;
}
add_filter( 'gettext', 'lar_text_strings', 20, 3 );


remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
add_action( 'woocommerce_before_single_product_summary', 'woocommerce_template_single_title', 5);

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 25 );


remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 15 );

remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );

function woocommerce_show_product_loop_sale_flash_custom() {
  wc_get_template( 'loop/sale-flash-catalogue.php' );
}

add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash_custom', 10 );

function woocommerce_template_loop_price_catalogue() {
  wc_get_template( 'loop/price_catalogue.php' );
}
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price_catalogue', 10 );


remove_action( 'woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

add_action( 'woocommerce_before_shop_loop_navigation', 'woocommerce_result_count', 10 );
add_action( 'woocommerce_before_shop_loop_navigation', 'woocommerce_catalog_ordering', 30 );





if (!function_exists('my_commonPriceHtml')) {

    function my_commonPriceHtml($price_amt, $regular_price, $sale_price) {
        $html_price = '<p class="price test">';
        //if product is in sale
        if (($price_amt == $sale_price) && ($sale_price != 0)) {
            $html_price .= '<ins>' . wc_price($sale_price) . '</ins>';
            $html_price .= '<del>' . wc_price($regular_price) . '</del>';
        }
        //in sale but free
        else if (($price_amt == $sale_price) && ($sale_price == 0)) {
            $html_price .= '<ins>Free!</ins>';
            $html_price .= '<del>' . wc_price($regular_price) . '</del>';
        }
        //not is sale
        else if (($price_amt == $regular_price) && ($regular_price != 0)) {
            $html_price .= '<ins>' . wc_price($regular_price) . '</ins>';
        }
        //for free product
        else if (($price_amt == $regular_price) && ($regular_price == 0)) {
            $html_price .= '<ins>Free!</ins>';
        }
        $html_price .= '</p>';
        return $html_price;
    }

}

add_filter('woocommerce_get_price_html', 'my_simple_product_price_html', 100, 2);

function my_simple_product_price_html($price, $product) {
    if ($product->is_type('simple')) {
        $regular_price = $product->get_regular_price();
        $sale_price = $product->get_sale_price();
        $price_amt = $product->get_price();
        return my_commonPriceHtml($price_amt, $regular_price, $sale_price);
    } else {
        return $price;
    }
}

add_filter('woocommerce_variation_sale_price_html', 'my_variable_product_price_html', 10, 2);
add_filter('woocommerce_variation_price_html', 'my_variable_product_price_html', 10, 2);

function my_variable_product_price_html($price, $variation) {
    $variation_id = $variation->variation_id;
    //creating the product object
    $variable_product = new WC_Product($variation_id);

    $regular_price = $variable_product->get_regular_price();
    $sale_price = $variable_product->get_sale_price();
    $price_amt = $variable_product->get_price();

    return my_commonPriceHtml($price_amt, $regular_price, $sale_price);
}

add_filter('woocommerce_variable_sale_price_html', 'my_variable_product_minmax_price_html', 10, 2);
add_filter('woocommerce_variable_price_html', 'my_variable_product_minmax_price_html', 10, 2);

function my_variable_product_minmax_price_html($price, $product) {
    $variation_min_price = $product->get_variation_price('min', true);
    $variation_max_price = $product->get_variation_price('max', true);
    $variation_min_regular_price = $product->get_variation_regular_price('min', true);
    $variation_max_regular_price = $product->get_variation_regular_price('max', true);

    if (($variation_min_price == $variation_min_regular_price) && ($variation_max_price == $variation_max_regular_price)) {
        $html_min_max_price = $price;
    } else {
        $html_price = '<p class="price">';
        $html_price .= '<ins>' . wc_price($variation_min_price) . '</ins>';
        $html_price .= '<del>' . wc_price($variation_min_regular_price) .'</del>';
        $html_min_max_price = $html_price;
    }

    return $html_min_max_price;
}


function catalogue_price() {
    global $product;
    if( $product->is_on_sale() ) {
        return $product->get_sale_price();

    }
    return $product->get_sale_price();
}

function catalogue_sale_price() {
    global $product;
    if( $product->is_on_sale() ) {
        return $product->get_regular_price();

    }
    return $product->get_regular_price();
}

/**
* shows percentage in flash sales
*/
add_filter( 'woocommerce_sale_flash', 'ask_percentage_sale', 11, 3 );
function ask_percentage_sale( $text, $post, $product ) {
    $discount = 0;
    if ( $product->get_type() == 'variable' ) {
        $available_variations = $product->get_available_variations();
        $maximumper = 0;
        for ($i = 0; $i < count($available_variations); ++$i) {
            $variation_id=$available_variations[$i]['variation_id'];
            $variable_product1= new WC_Product_Variation( $variation_id );
            $regular_price = $variable_product1->get_regular_price();
            $sales_price = $variable_product1->get_sale_price();
            if( $sales_price ) {
                $percentage= round( ( ( $regular_price - $sales_price ) / $regular_price ) * 100 ) ;
                if ($percentage > $maximumper) {
                    $maximumper = $percentage;
                }
            }
        }
        $text = '<span class="onsale">' . $maximumper  . '%</span>';
    } elseif ( $product->get_type() == 'simple' ) {
        $percentage = round( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 );
        $text = '<span class="onsale">' . $percentage . '%</span>';
    }
    return $text;
}

// Modify the default WooCommerce orderby dropdown
//
// Options: menu_order, popularity, rating, date, price, price-desc
// In this example I'm changing the default "Sort by newness" to "Sort by date: newest to oldest"
function patricks_woocommerce_catalog_orderby( $orderby ) {
	$orderby["date"] = __('Νεότερα', 'woocommerce');
  $orderby["oldest_to_recent"] = __('Παλαιότερα', 'woocommerce');
  $orderby["popularity"] = __('Δημοφιλή', 'woocommerce');
  $orderby["price"] = __('Φθηνότερα', 'woocommerce');
  $orderby["price-desc"] = __('Ακριβότερα', 'woocommerce');
  unset( $orderby['rating'] );
  unset( $orderby['menu_order'] );
	return $orderby;
}
add_filter( "woocommerce_catalog_orderby", "patricks_woocommerce_catalog_orderby", 20 );
