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

/* Custom Shoping Cart in the top */
    function paidikarouxaonline_wc_print_mini_cart() {
        ?>
            <?php if ( sizeof( WC()->cart->get_cart() ) > 0 ) : ?>
                <ul class="paidikarouxaonline-minicart-top-products">
                    <?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
                    $_product = $cart_item['data'];
                    // Only display if allowed
                    if ( ! apply_filters('woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) || ! $_product->exists() || $cart_item['quantity'] == 0 ) continue;
                    // Get price
                    $product_price = get_option( 'woocommerce_tax_display_cart' ) == 'excl' ? $_product->get_price_excluding_tax() : $_product->get_price_including_tax();
                    $product_price = apply_filters( 'woocommerce_cart_item_price_html', woocommerce_price( $product_price ), $cart_item, $cart_item_key );
                    ?>
                    <li class="paidikarouxaonline-mini-cart-product clearfix">
                        <span class="paidikarouxaonline-mini-cart-thumbnail">
                            <?php echo $_product->get_image(); ?>
                        </span>
                        <span class="paidikarouxaonline-mini-cart-info">
                            <a class="paidikarouxaonline-mini-cart-title" href="<?php echo get_permalink( $cart_item['product_id'] ); ?>">
                                <h4><?php echo apply_filters('woocommerce_widget_cart_product_title', $_product->get_title(), $_product ); ?></h4>
                            </a>
                            <?php echo apply_filters( 'woocommerce_widget_cart_item_price', '<span class="woffice-mini-cart-price">' . __('Unit Price', 'paidikarouxaonline') . ':' . $product_price . '</span>', $cart_item, $cart_item_key ); ?>
                            <?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="paidikarouxaonline-mini-cart-quantity">' . __('Quantity', 'woffice') . ':' . $cart_item['quantity'] . '</span>', $cart_item, $cart_item_key ); ?>
                        </span>
                    </li>
                    <?php endforeach; ?>
                </ul><!-- end .paidikarouxaonline-mini-cart-products -->
            <?php else : ?>

              <div class="d-flex flex-column justify-content-center text-center">
                  <div class=""><img src="<?= get_template_directory_uri(); ?>/dist/images/ico_cart_e.svg"></div>
                  <div class=""><?php _e( 'Empty cart.', 'paidikarouxaonline' ); ?></div>
              </div>

            <?php endif; ?>
            <?php if (sizeof( WC()->cart->get_cart()) > 0) : ?>
                <h4 class="text-center paidikarouxaonline-mini-cart-subtotal"><?php _e( 'Cart Subtotal', 'paidikarouxaonline' ); ?>: <?php echo WC()->cart->get_cart_subtotal(); ?></h4>
                <div class="text-center">
                    <a href="<?php echo WC()->cart->get_cart_url(); ?>" class="cart btn btn-default">
                        <i class="fa fa-shopping-cart" /> <?php _e( 'Cart', 'paidikarouxaonline' ); ?>
                    </a>
                    <a href="<?php echo WC()->cart->get_checkout_url(); ?>" class="alt checkout btn btn-default">
                        <i class="fa fa-credit-card" /> <?php _e( 'Checkout', 'paidikarouxaonline' ); ?>
                    </a>
                </div>
            <?php endif; ?>

        <?php
    }
add_shortcode ('paidikarouxaonline_top_cart', 'paidikarouxaonline_wc_print_mini_cart');

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
  //  $id=3594;
    $id=210;
    $post = get_post($id);
    $content = apply_filters('the_content', $post->post_content);
    echo $content;
    echo '</p>';
  }
  echo '<a role="button" class="collapsed" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample"></a>';
}
add_action ('woocommerce_archive_description', 'woocommerce_taxonomy_archive_description_custom', 10 );

remove_filter('the_content', 'wpautop');
