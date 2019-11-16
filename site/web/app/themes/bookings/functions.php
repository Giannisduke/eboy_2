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

          'orderyby' => 'post_id',
          'order' => 'ASC' ));
  ?>



 <!--CAROUSEL indica  tors SECTION START HERE-->

  <?php $count = 0; while ( $loop->have_posts() ) : $loop->the_post(); ?>
      <div data-target="#cars-carousel" data-slide-to="<?php echo $count ?>" class="test col-lg-3 col-sm-6 <?php if($count == '0'){ echo 'active'; } ?>">

      <?php wc_get_template_part( 'content', 'single-product' );?>
    </div>
  <?php $count++; endwhile; wp_reset_postdata(); ?>

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

remove_action( 'woocommerce_before_single_product', 'wc_print_notices', 10 );
