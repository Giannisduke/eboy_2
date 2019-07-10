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
    echo '<h5 class="woocommerce-loop-product_title"><a href="'.get_the_permalink().'">' . get_the_title() . '</a></h5>';
}


function pqrc_display_qr_code( $content ) {
    $current_post_id    = get_the_ID();
    $current_post_title = get_the_title( $current_post_id );
    $current_post_url   = urlencode( get_the_permalink( $current_post_id ) );
    $current_post_type  = get_post_type( $current_post_id );
    // Post Type Check
    $excluded_post_types = apply_filters( 'pqrc_excluded_post_types', array() );
    if ( in_array( $current_post_type, $excluded_post_types ) ) {
        return $content;
    }
    //Dimension Hook
    $dimension = apply_filters( 'pqrc_qrcode_dimension', '185x185' );
    //Image Attributes
    $image_attributes = apply_filters('pqrc_image_attributes',null);
    $image_src = sprintf( 'https://api.qrserver.com/v1/create-qr-code/?size=%s&ecc=L&qzone=1&data=%s', $dimension, $current_post_url );
    $content   .= sprintf( "<div class='qrcode'><img %s  src='%s' alt='%s' /></div>",$image_attributes, $image_src, $current_post_title );
    return $content;
}
add_action( 'woocommerce_before_shop_loop_item_title', 'pqrc_display_qr_code', 5 );

foreach ( array( 'pre_term_description' ) as $filter ) {
    remove_filter( $filter, 'wp_filter_kses' );
}
foreach ( array( 'term_description' ) as $filter ) {
    remove_filter( $filter, 'wp_kses_data' );
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
  echo '<a role="button" class="collapsed" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample"></a>';
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
