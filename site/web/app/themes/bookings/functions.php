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
  $per_page = 16;
  return $per_page;
}
add_filter( 'loop_shop_per_page', 'bbloomer_redefine_products_per_page', 9999 );

add_filter( 'woocommerce_enqueue_styles', '__return_false' );

//remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);

//wp_dequeue_style('init', 'wc-bookings-styles');

function custom_dequeue() {
    wp_dequeue_style('wc-bookings-styles');
    wp_dequeue_style('jquery-ui-style');

    //wp_deregister_style('et-gf-open-sans');

}

add_action( 'wp_enqueue_scripts', 'custom_dequeue', 9999 );
add_action( 'wp_head', 'custom_dequeue', 9999 );

// Fuction to display the FacetWP Pager
function print_facet_pagination(){
    echo facetwp_display( 'pager', 'true' );
}

// Function to add it to the template
//add_action('woocommerce_after_main_content', 'print_facet_pagination', 20 );

//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);


remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
function custom_show_product_images() { global $product;
  if ( is_shop() || is_product_category() ) { ?>
   test_4
   <figure class="car-thumb">
       <img class="img-fluid" src="<?php echo wp_get_attachment_image_src( $product->get_image_id(), 'medium')[0]; ?>" />
   </figure>
<?php }

  if ( is_product() )
   { ?>

    test_5
    <figure class="car-thumb">
        <img class="img-fluid" src="<?php echo wp_get_attachment_image_src( $product->get_image_id(), 'full')[0]; ?>" />
    </figure>
<?php }
    }
add_action( 'woocommerce_before_single_product_summary', 'custom_show_product_images', 20);

// First remove default wrapper
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);



remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
function eboy_product_summary() {
  global $product;
  echo '<div class="d-flex flex-row justify-content-between">';
  echo '<div>';
  echo '<h2>' . $product->get_name() . '</h2>';
  echo '</div>';
  echo '<div>';
  echo $product->get_price();
  echo '</div>';

  echo '</div>';

}
add_action('woocommerce_single_product_summary', 'eboy_product_summary', 5);

function eboy_wrapper_start() {
  echo '<main class="main facetwp-template">';
}

function eboy_wrapper_end() {
  echo '</main>';
}

// Then add new wrappers
add_action('woocommerce_before_main_content', 'eboy_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'eboy_wrapper_end', 10);

function eboy_carousel_open() { ?>
  <div id="cars-carousel" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">

  </div>
  <a class="carousel-control-prev" href="#cars-carousel" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#cars-carousel" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>


<?php }

// Then add new wrappers
add_action('woocommerce_before_main_content', 'eboy_carousel_open', 9);

function additional_div_in_shop() {
    // Only on "shop" archives pages
    if( ! is_shop() ) return;
    // Output the div
    ?>



      <div class="row description">
        <div class="col-12 text-center">
        <h1><?php printf( esc_html__( '%s', 'sage' ), get_bloginfo ( 'description' ) ); ?></h1>
        </div>
      </div>
      <div class="row justify-content-center calendar">
          <div class="col-12">
            test facet
            <?php echo facetwp_display( 'facet', 'product_categories' ); ?>
            <?php echo facetwp_display( 'facet', 'date_range' ); ?>

          </div>
      </div>


    <?php
}

add_action( 'woocommerce_before_main_content', 'additional_div_in_shop', 5 );


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
