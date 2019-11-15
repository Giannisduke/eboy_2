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

// Facet query
add_filter( 'facetwp_is_main_query', function( $is_main_query, $query ) {
    if ( '' !== $query->get( 'facetwp' ) ) {
        $is_main_query = (bool) $query->get( 'facetwp' );
    }
    return $is_main_query;
}, 10, 2 );

function bbloomer_redefine_products_per_page( $per_page ) {
  $per_page = 6;
  return $per_page;
}

// Fuction to display the FacetWP Pager
function print_facet_pagination(){
    echo facetwp_display( 'pager', 'true' );
}

// Function to add it to the template
//add_action('woocommerce_after_main_content', 'print_facet_pagination', 20 );

add_filter( 'loop_shop_per_page', 'bbloomer_redefine_products_per_page', 9999 );



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


remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'custom_loop_product_thumbnail', 10 );

//remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );


function custom_loop_product_thumbnail() {
    global $product;
    $size = 'medium';

    $image_size = apply_filters( 'single_product_archive_thumbnail_size', $size );

    //return $product ? $product->get_image( $image_size ) : '';
    echo $product ? $product->get_image( $image_size ) : '';
}

function carhub_carousel_start_1(){
  $loop = new WP_Query(array(
          'post_type' => 'product',
          'posts_per_page' => -1,
          'orderyby' => 'post_id',
          'order' => 'ASC' ));
  ?>

 <!--//CAROUSEL SLIDER SECTION END HERE-->
<div class="container">
<div class="row">
  <div class="col-12 ajax collapse" id="collapseExample">
test

</div>
</div>
</div>

 <!--CAROUSEL indicators SECTION START HERE-->
  <div class="row carousel-indicators pt-5">
  <?php $count = 0; while ( $loop->have_posts() ) : $loop->the_post(); ?>
      <div data-target="#cars-carousel" data-slide-to="<?php echo $count ?>" class="col-lg-3 col-sm-6 <?php if($count == '0'){ echo 'active'; } ?>">

      <?php wc_get_template_part( 'content', 'single-product' );?>
    </div>
  <?php $count++; endwhile; wp_reset_postdata(); ?>
</div>
<!--//CAROUSEL indicators SECTION END HERE-->
<?php }
add_action( 'carhub_carousel_start', 'carhub_carousel_start_1' , 20);


//remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
//remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );


function carhub_template_loop_product_link_open() {
  global $product;

  $link = apply_filters( 'woocommerce_loop_product_link', get_the_permalink(), $product );

  echo '<div class="card front test" data-target="#carscarousel">';
}
//add_action( 'woocommerce_before_shop_loop_item', 'carhub_template_loop_product_link_open', 10 );

function carhub_template_loop_product_link_close() {
  echo '</div>';

}
//add_action( 'woocommerce_after_shop_loop_item', 'carhub_template_loop_product_link_close', 5 );

// Cars Carousel
function eboy_bookings_carousel() {
  $loop = new WP_Query(array(
          'post_type' => 'product',
          'posts_per_page' => -1,
          'orderyby' => 'post_id',
          'order' => 'ASC',
          'facetwp' => true,
        ));
  ?>
  <div class="container">
    <div id="cars-carousel" class="row carousel slide text-center test" data-ride="carousel">
      <div class="carousel-inner " role="listbox" >

      </div>
    </div>
  </div>

<?php }
add_action( 'woocommerce_shop_loop', 'eboy_bookings_carousel', 10 );
