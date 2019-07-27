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
  'lib/customizer.php', // Theme customizer
  'templates/facetwp-custom.php' // Theme customizer
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


function eboy_display_categories() {
global $post;
$brands_id = get_term_by('slug', 'αγόρι', 'product_cat');

$terms = get_the_terms($post->ID, 'product_cat');
foreach ($terms as $term) {
    if($term->parent === $brands_id->term_id) {
        echo $term->name;

        break;
    }
}
}
//add_action('woocommerce_shop_loop_item_title', 'eboy_display_categories', 5 );


remove_action( 'woocommerce_shop_loop_item_title','woocommerce_template_loop_product_title', 10 );

function abChangeProductsTitle() {
  global $post, $product;
  $color = $product->get_attribute( 'pa_color' );

$cats = get_the_terms( $post->ID, 'product_cat' );
    echo '<div class="card-body product-info"><h5 class="card-title">';


//    echo get_the_title();
//    echo $color;
//    echo '</h5>';

}
add_action('woocommerce_shop_loop_item_title', 'abChangeProductsTitle', 5 );


function abChangeProductsTitle_2() {
  global $post;
  $brands_id = get_term_by('slug', 'αγόρι', 'product_cat');

  $terms = get_the_terms($post->ID, 'product_cat');
  foreach ($terms as $term) {
      if($term->parent === $brands_id->term_id) {
          //echo $term->name;
          echo $term->description;
          echo ', ';
          break;
      }
  }

}
add_action('woocommerce_shop_loop_item_title', 'abChangeProductsTitle_2', 10 );

function abChangeProductsTitle_3() {
  global $post, $product;
  $color = $product->get_attribute( 'pa_color' );

$cats = get_the_terms( $post->ID, 'product_cat' );
    //echo '<div class="card-body product-info"><h5 class="card-title">';


    echo get_the_title() . ', ';
    echo $color;
    echo '</h5>';

}
add_action('woocommerce_shop_loop_item_title', 'abChangeProductsTitle_3', 15 );


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




function woocommerce_before_mini_cart_open() {
?>
  <div class="d-flex flex-row justify-content-end">
<?php
}

add_action( 'woocommerce_before_mini_cart', 'woocommerce_before_mini_cart_open', 5 );


function woocommerce_before_mini_cart_close() {
?>
</div>
<?php
}

add_action( 'woocommerce_after_mini_cart', 'woocommerce_before_mini_cart_close', 5 );


//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
//add_action( 'woocommerce_before_single_product_summary', 'woocommerce_template_single_title', 15);
function woocommerce_template_single_manufacturer() {
  wc_get_template( 'single-product/manufacturer.php' );

}

add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_manufacturer', 2);

//  remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
//add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 25 );



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

remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 5 );

remove_action( 'woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

add_action( 'woocommerce_before_shop_loop_navigation', 'woocommerce_result_count', 10 );
add_action( 'woocommerce_before_shop_loop_navigation', 'woocommerce_catalog_ordering', 30 );

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

function woocommerce_template_loop_product_thumbnail_card() {
  global $product;
  /* grab the url for the full size featured image */
   $featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'woocommerce_thumbnail');



   echo '<img src="'.esc_url($featured_img_url).'" class="card-img-top img-overlay" alt="...">';
   echo '<div class="fa fa-plus project-overlay"></div>';
}
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail_card', 10 );

remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation', 10 );



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
    else  {
        return $product->get_regular_price();
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
        $text = '<span class="onsale">-' . $maximumper  . '%</span>';
    } elseif ( $product->get_type() == 'simple' ) {
        $percentage = round( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 );
        $text = '<span class="onsale">' . $percentage . 'fwf</span>';
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

add_filter('woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text');

function woo_custom_cart_button_text() {
return __('Αγορά', 'woocommerce');
}

function wc_remove_all_quantity_fields( $return, $product ) {
    return true;
}
add_filter( 'woocommerce_is_sold_individually', 'wc_remove_all_quantity_fields', 10, 2 );

//shortcode for mini-cart
function jma_woo_minicart($atts){
	ob_start();
	global $woocommerce;

	echo '<a class="cart-contents" href="' . ' $woocommerce->cart->get_cart_url()' . '" title="View your shopping cart">';
	echo sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count) . ' - ' . $woocommerce->cart->get_cart_total() . '</a>';

	$x = ob_get_contents();
	ob_end_clean();
	return $x;
}
add_shortcode('jma_woo_minicart','jma_woo_minicart');

// Ensure cart contents update when products are added to the cart via AJAX (place the following in functions.php)
function woocommerce_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;

	ob_start();

	?>
	<a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e('View your shopping cart', 'woothemes'); ?>"><?php echo sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);?> - <?php echo $woocommerce->cart->get_cart_total(); ?></a>
	<?php

	$fragments['a.cart-contents'] = ob_get_clean();

	return $fragments;

}
add_filter('add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');

// Add Shortcode
function custom_mini_cart() {

	echo '<a href="#" class="dropdown-back" data-toggle="dropdown"> ';
	    echo '<i class="fa fa-shopping-cart" aria-hidden="true"></i>';
	    echo '<div class="basket-item-count" style="display: inline;">';
	        echo '<span class="cart-items-count count">';
	            echo WC()->cart->get_cart_contents_count();
	        echo '</span>';
	    echo '</div>';
	echo '</a>';
	echo '<ul class="dropdown-menu dropdown-menu-mini-cart">';
	        echo '<li> <div class="widget_shopping_cart_content">';
	                  woocommerce_mini_cart();
	            echo '</div></li></ul>';

}
add_shortcode( 'custom-mini-cart', 'custom_mini_cart' );

add_filter('woocommerce_product_tabs', 'woocommerce_product_tabs_remove_qr_code', 20);
function woocommerce_product_tabs_remove_qr_code($tabs){
    if(isset($tabs['qr_code_tab'])){
        unset($tabs['qr_code_tab']);
    }
    return $tabs;
}

function button_qr_code() {
    global $product;
    ?>
    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal">
      Εκτύπωση Κάρτας
    </button>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Εκτύπωση Κάρτας</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="d-flex flex-row">
              <div class="p-0">
                <?php
                if (get_wc_product_qr_code_src($product->get_id())) {
                    echo '<div class="wc-qr-codes-container">';
                    echo '<img class="wcqrc-qr-code-img" src="' . get_wc_product_qr_code_src($product->get_id()) . '" alt="QR Code" />';
                    echo '</div>';
                } ?>
              </div>
              <div class="p-0"><div class="d-flex flex-column">
                <div class="px-3"><?php the_title( '<h3 class="product_title entry-title">', '</h3>' );?></div>
                <div class="p-0"><?php echo $product->get_price_html(); ?></div>

              </div>
            </div>

            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Κλεισιμο</button>
            <button type="button" class="btn btn-primary">Εκτύπωση</button>
          </div>
        </div>
      </div>
    </div>
<?php }
//add_action('woocommerce_single_product_summary', 'button_qr_code', 10);
