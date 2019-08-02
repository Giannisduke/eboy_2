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

function add_image_class($class){
    $class .= ' additional-class';
    return $class;
}
add_filter('get_image_tag_class','add_image_class');

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


####################################################
#    VIDEO
####################################################

function eboy_pro_front_carousel_indicators(){

?>
<?php
        if( have_rows('carousel') ):$counter = 0;?>

        <!--Indicators-->
        <ol class="carousel-indicators">

          <?php while( have_rows('carousel') ): the_row(); ?>

            <li data-target="#video-carousel" data-slide-to="<?php echo $counter;?>" class="myCarousel-target <?php if($counter === 0){ echo "active";} ?>"></li>



          <?php $counter++; endwhile; ?>

        </ol>
        <!--/.Indicators-->


        <?php endif; ?>

<?php
}

add_action('eboy_pro_front', 'eboy_pro_front_carousel_indicators', 20);




function eboy_pro_front_carousel(){


        if( have_rows('carousel') ):$counter = 0;?>
        <!--Carousel Wrapper-->
        <div id="video-carousel" class="carousel slide carousel-fade home-section" data-ride="carousel">

          <!--Slides-->
          <div class="carousel-inner" role="listbox">

                <?php while( have_rows('carousel') ): the_row();
                    $slide_title = get_sub_field('slide_title');
                    $slide_subtitle = get_sub_field('slide_subtitle');
                    $slide_image = get_sub_field('slide_image_background');
                    $slide_video = get_sub_field('slide_video');
                    $slide_external_video = get_sub_field('slide_external_video');
                    ?>
                    <div class="carousel-item <?php if($counter === 0){ echo "active";} ?>" data-slide-no="<?php echo $counter;?>" style="background: url('<?php echo $slide_image;?>') no-repeat center; background-size: cover;">

                      <?php if (get_sub_field('slide_external_video')) { ?>
                        <video class="video-fluid" loop="loop" controls="top" controlsList="nofullscreen nodownload noremoteplayback" id="player" preload="auto" playsinline controls muted>
                            <source src="<?php echo $slide_external_video;?>"  />
                              test
                        </video>

                        <?php
                      }


                      else {

                      }
                        ?>


                    </div>
                    <?php $counter++; endwhile; ?>


                      </div> <!--/.Slides-->
                    </div> <!--Carousel Wrapper-->

        <?php endif; ?>
<?php
}

add_action('eboy_pro_front', 'eboy_pro_front_carousel', 30);



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
      echo '<div class="d-flex flex-row justify-content-center"><div class="col-1 px-5"><p class="collapse" id="collapseExample" aria-expanded="false">' . $term->description . '</p></div></div>'; // WPCS: XSS ok.

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
  echo '<div class="d-flex flex-row justify-content-center"><div class="col-1 px-5"><a role="button" class="collapsed" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample"><img class="ico svg" src=" ' .get_template_directory_uri() .'/dist/images/arrow_down.svg"></a></div></div>';

}
add_action ('woocommerce_archive_description', 'woocommerce_taxonomy_archive_description_custom', 10 );

remove_filter('the_content', 'wpautop');



####################################################
#    VIDEO
####################################################

function pro_front_carousel_indicators(){

?>
<?php
        if( have_rows('carousel') ):$counter = 0;?>

        <!--Indicators-->
        <ol class="carousel-indicators">

          <?php while( have_rows('carousel') ): the_row(); ?>

            <li data-target="#video-carousel" data-slide-to="<?php echo $counter;?>" class="myCarousel-target <?php if($counter === 0){ echo "active";} ?>"></li>



          <?php $counter++; endwhile; ?>

        </ol>
        <!--/.Indicators-->


        <?php endif; ?>

<?php
}

add_action('pro_custom_front', 'pro_front_carousel_indicators', 20);




function pro_front_carousel(){


        if( have_rows('carousel') ):$counter = 0;?>
        <!--Carousel Wrapper-->
        <div id="video-carousel" class="carousel slide carousel-fade home-section" data-ride="carousel">

          <!--Slides-->
          <div class="carousel-inner" role="listbox">

                <?php while( have_rows('carousel') ): the_row();
                    $slide_title = get_sub_field('slide_title');
                    $slide_subtitle = get_sub_field('slide_subtitle');
                    $slide_image = get_sub_field('slide_image_background');
                    $slide_video = get_sub_field('slide_video');
                    $slide_external_video = get_sub_field('slide_external_video');
                    ?>
                    <div class="carousel-item <?php if($counter === 0){ echo "active";} ?>" data-slide-no="<?php echo $counter;?>" style="background: url('<?php echo $slide_image;?>') no-repeat center; background-size: cover;">

                      <?php if (get_sub_field('slide_external_video')) { ?>
                        <video class="video-fluid" controls="top" controlsList="nofullscreen nodownload noremoteplayback" id="player" preload="auto" playsinline controls muted>
                            <source src="<?php echo $slide_external_video;?>"  />
                        </video>

                        <?php
                      }


                      else {

                      }
                        ?>


                    </div>
                    <?php $counter++; endwhile; ?>


                      </div> <!--/.Slides-->
                    </div> <!--Carousel Wrapper-->

        <?php endif; ?>
<?php
}

add_action('pro_custom_front', 'pro_front_carousel', 30);





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

function woocommerce_template_single_print() {
  wc_get_template( 'single-product/print.php' );

}

add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_print', 3);

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

//remove_action( 'woocommerce_before_single_product', 'wc_print_notices', 10 );
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10  );
add_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_coupon_form', 5  );


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

function product_remove() {
    global $wpdb, $woocommerce;
    session_start();
    $cart = WC()->instance()->cart;
    $cart_id = $_POST['product_id']; // This info is already the result of generate_cart_id method now
    /* $cart_id = $cart->generate_cart_id($id); // No need for this! :) */
    $cart_item_id = $cart->find_product_in_cart($cart_id);
    if($cart_item_id){
       $cart->set_quantity($cart_item_id,0);
    }
}
add_action( 'wp_ajax_product_remove', 'product_remove' );
add_action( 'wp_ajax_nopriv_product_remove', 'product_remove' );

function custom_remove_woo_checkout_fields( $fields ) {

   unset($fields['billing']['billing_company']);

    // remove order comment fields
    unset($fields['order']['order_comments']);
    unset($fields['billing']['billing_country']);
    unset( $fields['billing']['billing_address_2'] );
    unset( $fields['billing']['billing_country'] );
    unset( $fields['billing']['billing_state'] );

    return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'custom_remove_woo_checkout_fields' );

/**
 * Add Bootstrap form styling to WooCommerce fields
 *
 * @since  1.0
 * @refer  http://bit.ly/2zWFMiq
 */
function iap_wc_bootstrap_form_field_args ($args, $key, $value) {

  $args['input_class'][] = 'form-control';
  return $args;
}
add_filter('woocommerce_form_field_args','iap_wc_bootstrap_form_field_args', 10, 3);


add_filter('woocommerce_default_address_fields', 'custom_default_address_fields', 20, 1);
function custom_default_address_fields( $address_fields ){
  $domain = 'paidikarouxaonline';
    if( ! is_cart()){ // <== On cart page only
        // Change placeholder
        $address_fields['first_name']['placeholder'] = __( 'Fornavn', $domain );
        $address_fields['last_name']['placeholder']  = __( 'Efternavn', $domain );
        $address_fields['address_1']['placeholder']  = __( 'Adresse', $domain );
        $address_fields['state']['placeholder']      = __( 'Stat', $domain );
        $address_fields['postcode']['placeholder']   = __( 'Postnummer', $domain );
        $address_fields['city']['placeholder']       = __( 'By', $domain );

        // Change class
        $address_fields['first_name']['class'] = array('form-row-first test'); //  50%
        $address_fields['last_name']['class']  = array('form-row-last');  //  50%
        $address_fields['address_1']['class']  = array('form-row-first');  // 100%
        $address_fields['state']['class']      = array('form-row-last');  // 100%
        $address_fields['postcode']['class']   = array('form-row-first'); //  50%
        $address_fields['city']['class']       = array('form-row-last');  //  50%
    }
    return $address_fields;
}

/**
 * Pre-populate Woocommerce checkout fields
 */
add_filter('woocommerce_checkout_get_value', function($input, $key ) {
	global $current_user;
	switch ($key) :
		case 'billing_first_name':
		case 'shipping_first_name':
			return $current_user->first_name;
		break;

		case 'billing_last_name':
		case 'shipping_last_name':
			return $current_user->last_name;
		break;
		case 'billing_email':
			return $current_user->user_email;
		break;
		case 'billing_phone':
			return $current_user->phone;
		break;
	endswitch;
}, 10, 2);


function wc_register_guests( $order_id ) {
  // get all the order data
  $order = new WC_Order($order_id);

  //get the user email from the order
  $order_email = $order->billing_email;

  // check if there are any users with the billing email as user or email
  $email = email_exists( $order_email );
  $user = username_exists( $order_email );

  // if the UID is null, then it's a guest checkout
  if( $user == false && $email == false ){
    // perform guest user actions here
// random password with 12 chars
$random_password = wp_generate_password();

// create new user with email as username & newly created pw
$user_id = wp_create_user( $order_email, $random_password, $order_email );
//WooCommerce guest customer identification
update_user_meta( $user_id, 'guest', 'yes' );

//user's billing data
update_user_meta( $user_id, 'billing_address_1', $order->billing_address_1 );
update_user_meta( $user_id, 'billing_address_2', $order->billing_address_2 );
update_user_meta( $user_id, 'billing_city', $order->billing_city );
update_user_meta( $user_id, 'billing_company', $order->billing_company );
update_user_meta( $user_id, 'billing_country', $order->billing_country );
update_user_meta( $user_id, 'billing_email', $order->billing_email );
update_user_meta( $user_id, 'billing_first_name', $order->billing_first_name );
update_user_meta( $user_id, 'billing_last_name', $order->billing_last_name );
update_user_meta( $user_id, 'billing_phone', $order->billing_phone );
update_user_meta( $user_id, 'billing_postcode', $order->billing_postcode );
update_user_meta( $user_id, 'billing_state', $order->billing_state );

// user's shipping data
update_user_meta( $user_id, 'shipping_address_1', $order->shipping_address_1 );
update_user_meta( $user_id, 'shipping_address_2', $order->shipping_address_2 );
update_user_meta( $user_id, 'shipping_city', $order->shipping_city );
update_user_meta( $user_id, 'shipping_company', $order->shipping_company );
update_user_meta( $user_id, 'shipping_country', $order->shipping_country );
update_user_meta( $user_id, 'shipping_first_name', $order->shipping_first_name );
update_user_meta( $user_id, 'shipping_last_name', $order->shipping_last_name );
update_user_meta( $user_id, 'shipping_method', $order->shipping_method );
update_user_meta( $user_id, 'shipping_postcode', $order->shipping_postcode );
update_user_meta( $user_id, 'shipping_state', $order->shipping_state );
// link past orders to this newly created customer
wc_update_new_customer_past_orders( $user_id );
  }

}

//call our wc_register_guests() function on the thank you page
add_action( 'woocommerce_thankyou', 'wc_register_guests', 10, 1 );

function mytheme_preview_email() {
    global $woocommerce;
    if ( ! is_admin() ) {
        return null;
    }
    $mailer = $woocommerce->mailer();
    $email_options = array();
    foreach ( $mailer->emails as $key => $obj ) {
        $email_options[$key] = $obj->title;
    }
    $in_order_id = isset( $_GET['order'] ) ? $_GET['order'] : '';
    $in_email_type = isset( $_GET['email_type'] ) ? $_GET['email_type'] : '';
    $order_number = is_numeric( $in_order_id ) ? (int) $in_order_id : '';
    $email_class = isset( $email_options[ $in_email_type ] ) ? $in_email_type : '';
    $order = $order_number ? wc_get_order( $order_number ) : false;
    $error = '';
    $email_html = '';
    if ( ! $in_order_id && ! $in_email_type ) {
        $error = '<p>Please select an email type and enter an order #</p>';
    } elseif ( ! $email_class ) {
        $error = '<p>Bad email type</p>';
    } elseif ( ! $order ) {
        $error = '<p>Bad order #</p>';
    } else {
        $email = $mailer->emails[$email_class];
        $email->object = $order;
        $email_html = apply_filters( 'woocommerce_mail_content', $email->style_inline( $email->get_content_html() ) );
    }
?>
<!DOCTYPE HTML>
<html>
<head></head>
<body>
<form method="get" action="<?php echo site_url(); ?>/wp-admin/admin-ajax.php">
    <input type="hidden" name="action" value="previewemail">
    <select name="email_type">
        <option value="--">Email Type</option>
        <?php
            foreach( $email_options as $class => $label ){
                if ( $email_class && $class == $email_class ) {
                    $selected = 'selected';
                } else {
                    $selected = '';
                }
            ?>
                <option value="<?php echo $class; ?>" <?php echo $selected; ?> ><?php echo $label; ?></option>
        <?php } ?>
        </select>
    <input type="text" name="order" value="<?php echo $order_number; ?>" placeholder="order #">
    <input type="submit" value="Go">
</form>
<?php
if ( $error ) {
    echo "<div class='error'>$error</div>";
} else {
    echo $email_html;
}
?>
</body>
</html>

<?php
    return null;
}
add_action('wp_ajax_previewemail', 'mytheme_preview_email');

// Adds instructions for order emails
function add_order_email_instructions( $order, $sent_to_admin ) {

  if ( ! $sent_to_admin ) {

    if ( 'cod' == $order->payment_method ) {
      // cash on delivery method
      echo '<p><strong>Instructions:</strong> Full payment is due immediately upon delivery. <em>Cash only, no exceptions</em>.</p>';
    } else {
      // other methods (ie credit card)
      echo '<p><strong>Instructions:</strong> Please look for "Madrigal Electromotive GmbH" on your next credit card statement.</p>';
    }
  }
}
add_action( 'woocommerce_email_before_order_table', 'add_order_email_instructions', 10, 2 );

function dotifollow_function() {
?>
  <ul class="products">
  	<?php
  		$args = array(
  			'post_type' => 'product',
  			'posts_per_page' => 12
  			);
  		$loop = new WP_Query( $args );
  		if ( $loop->have_posts() ) {
  			while ( $loop->have_posts() ) : $loop->the_post();
  				wc_get_template_part( 'content', 'product' );
  			endwhile;
  		} else {
  			echo __( 'No products found' );
  		}
  		wp_reset_postdata();
  	?>
  </ul><!--/.products-->
  <?php
}

add_shortcode('dotifollow', 'dotifollow_function');
