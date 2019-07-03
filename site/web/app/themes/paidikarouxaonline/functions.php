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
  'lib/theme-customize.php'
];

foreach ($sage_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);

function cc_mime_types( $mimes ){
    $mimes['svg'] = 'image/svg+xml';

    return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );

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

add_action ('customize_register', 'themeslug_theme_customizer');


add_filter( 'facetwp_is_main_query', function( $is_main_query, $query ) {
	if ( isset( $query->query_vars['facetwp'] ) ) {
		$is_main_query = (bool) $query->query_vars['facetwp'];
	}
	return $is_main_query;
}, 10, 2 );




remove_action('welcome_panel', 'wp_welcome_panel');

add_filter('widget_text','do_shortcode');


//// BREADCRUMB START ////
 function the_breadcrumb() {

  $showOnHome = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
  $delimiter = '&#8728;'; // delimiter between crumbs
  $home = 'Home'; // text for the 'Home' link
  $showCurrent = 1; // 1 - show current post/page title in breadcrumbs, 0 - don't show
  $before = '<span class="current">'; // tag before the current crumb
  $after = '</span>'; // tag after the current crumb

  global $post;
  $homeLink = get_bloginfo('url');

  if (is_home() || is_front_page()) {

    if ($showOnHome == 1) echo '<span class="align-text-bottom"><a href="' . $homeLink . '">' . $home . '</a></span>';

  } else {

    echo '<span class="align-text-bottom"><a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';

    if ( is_category() ) {
      $thisCat = get_category(get_query_var('cat'), false);
      if ($thisCat->parent != 0) echo get_category_parents($thisCat->parent, TRUE, ' ' . $delimiter . ' ');
      echo $before . 'Archive by category "' . single_cat_title('', false) . '"' . $after;

    } elseif ( is_search() ) {
      echo $before . 'Search results for "' . get_search_query() . '"' . $after;

    } elseif ( is_day() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('d') . $after;

    } elseif ( is_month() ) {
      echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
      echo $before . get_the_time('F') . $after;

    } elseif ( is_year() ) {
      echo $before . get_the_time('Y') . $after;

    } elseif ( is_single() && !is_attachment() ) {
      if ( get_post_type() != 'post' ) {
        $post_type = get_post_type_object(get_post_type());
        $slug = $post_type->rewrite;
        echo '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>';
        if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
      } else {
        $cat = get_the_category(); $cat = $cat[0];
        $cats = get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
        if ($showCurrent == 0) $cats = preg_replace("#^(.+)\s$delimiter\s$#", "$1", $cats);
        echo $cats;
        if ($showCurrent == 1) echo $before . get_the_title() . $after;
      }

    } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
      $post_type = get_post_type_object(get_post_type());
      echo $before . $post_type->labels->singular_name . $after;

    } elseif ( is_attachment() ) {
      $parent = get_post($post->post_parent);
      $cat = get_the_category($parent->ID); $cat = $cat[0];
      echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
      echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>';
      if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;

    } elseif ( is_page() && !$post->post_parent ) {
      if ($showCurrent == 1) echo $before . get_the_title() . $after;

    } elseif ( is_page() && $post->post_parent ) {
      $parent_id  = $post->post_parent;
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
        $parent_id  = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      for ($i = 0; $i < count($breadcrumbs); $i++) {
        echo $breadcrumbs[$i];
        if ($i != count($breadcrumbs)-1) echo ' ' . $delimiter . ' ';
      }
      if ($showCurrent == 1) echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;

    } elseif ( is_tag() ) {
      echo $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;

    } elseif ( is_author() ) {
       global $author;
      $userdata = get_userdata($author);
      echo $before . 'Articles posted by ' . $userdata->display_name . $after;

    } elseif ( is_404() ) {
      echo $before . 'Error 404' . $after;
    }

    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
      echo __('Page') . ' ' . get_query_var('paged');
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
    }

    echo '</span>';

  }
} // end the_breadcrumb()

//// BREADCRUMB END ////
add_filter( 'facetwp_pager_html', function( $output, $params ) {
    $output = '<nav aria-label="Resources Pagination"><ul class="pagination mt-1 justify-content-center">';
    $page = $params['page'];
    $i = 1;
    $total_pages = $params['total_pages'];
    $limit = ($total_pages >= 5) ? 3 : $total_pages;
    $prev_disabled = ($params['page'] <= 1) ? 'disabled' : '';
    $output .= '<li class="page-item ' . $prev_disabled . '"><a class="facetwp-page page-link" data-page="' . ($page - 1) . '">Prev</a></li>';
    $loop = ($limit) ? $limit : $total_pages;
    while($i <= $loop) {
      $active = ($i == $page) ? 'active' : '';
      $output .= '<li class="page-item ' . $active . '"><a class="facetwp-page page-link" data-page="' . $i . '">' . $i . '</a></li>';
      $i++;
    }
    if($limit && $total_pages > '3') {
      $output .= ($page > $limit && $page != ($total_pages - 1) && $page <= ($limit + 1)) ? '<li class="page-item active"><a class="facetwp-page page-link" data-page="' . $page . '">' . $page . '</a></li>' : '';
      $output .= '<li class="page-item disabled"><a class="facetwp-page page-link">...</a></li>';
      $output .= ($page > $limit && $page != ($total_pages - 1) && $page > ($limit + 1)) ? '<li class="page-item active"><a class="facetwp-page page-link" data-page="' . $page . '">' . $page . '</a></li>' : '';
      $output .= ($page > $limit && $page != ($total_pages - 1) && $page != ($total_pages - 2) && $page > ($limit + 1)) ? '<li class="page-item disabled"><a class="facetwp-page page-link">...</a></li>' : '';
      $active = ($page == ($total_pages - 1)) ? 'active' : '';
      $output .= '<li class="page-item ' . $active . '"><a class="facetwp-page page-link" data-page="' . ($total_pages - 1) .'">' . ($total_pages - 1) .'</a></li>';
    }
    $next_disabled = ($page >= $total_pages) ? 'disabled' : '';
    $output .= '<li class="page-item ' . $next_disabled . '"><a class="facetwp-page page-link" data-page="' . ($page + 1) . '">Next</a></li>';
    $output .= '</ul></nav>';
    return $output;
}, 10, 2 );

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


remove_action ( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title' );

function paidikarouxa_template_loop_product_title() {


 the_title( '<h3 class=”product_title entry-title”>', '</h3>' );


}
add_action ( 'paidikarouxa_shop_loop_item_title', 'paidikarouxa_template_loop_product_title', 20 );

add_filter( 'loop_shop_per_page', 'bbloomer_redefine_products_per_page', 9999 );

add_action( 'init', 'wpse325327_add_excerpts_to_pages' );
function wpse325327_add_excerpts_to_pages() {
    add_post_type_support( 'page', 'excerpt' );
}

add_filter( 'the_content', 'bts_filter_content_sample' );
function bts_filter_content_sample( $content ) {
  $html_segment_start = '<section class="content"><div class="collapse content" id="linkcollapse">>';
  $html_segment_end = '</div></section>';
  $content = $html_segment_start . $content . $html_segment_end;
return $content;
}


function woocommerce_facet_template_loop() {
echo facetwp_display('selections');
}
add_action ('woocommerce_before_shop_loop', 'woocommerce_facet_template_loop', 5 );
