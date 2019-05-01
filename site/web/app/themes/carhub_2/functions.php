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
  'lib/wp_bootstrap_navwalker.php' // Theme Bootstrap menu
];

foreach ($sage_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);


//declare your new menu
register_nav_menus( array(
    'primary' => __( 'Primary Menu', 'sage' ),
) );

// Add svg & swf support
function cc_mime_types( $mimes ){
    $mimes['svg'] = 'image/svg+xml';
    $mimes['swf']  = 'application/x-shockwave-flash';

    return $mimes;
}
add_filter( 'upload_mimes', 'cc_mime_types' );

//enable logo uploading via the customize theme page

function themeslug_theme_customizer( $wp_customize ) {
    $wp_customize->add_section( 'themeslug_logo_section' , array(
    'title'       => __( 'Logo', 'sage' ),
    'priority'    => 30,
    'description' => 'Upload a logo to replace the default site name and description in the header',
) );
$wp_customize->add_setting( 'themeslug_logo' );
$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'themeslug_logo', array(
    'label'    => __( 'Logo', 'sage' ),
    'section'  => 'themeslug_logo_section',
    'settings' => 'themeslug_logo',
    'extensions' => array( 'jpg', 'jpeg', 'gif', 'png', 'svg' ),
) ) );
}
add_action('customize_register', 'themeslug_theme_customizer');


 // Google maps api key
function my_acf_google_map_api( $api ){

	$api['key'] = 'AIzaSyAY55sLjGdZyuE5fX9gIH0NegqSeB24LEU';

	return $api;

}

add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');



// Facet query
add_filter( 'facetwp_is_main_query', function( $is_main_query, $query ) {
	if ( isset( $query->query_vars['facetwp'] ) ) {
		$is_main_query = (bool) $query->query_vars['facetwp'];
	}
	return $is_main_query;
}, 10, 2 );


/**
 * Will make it so that the date format when the calendar is not used is DD/MM/YYYY on a Bookable product.
 */
//add_filter( 'woocommerce_bookings_mdy_format' , '__return_false' );
/**
 * Will make the Bookings calender default to the month with the first available booking.
 */
//add_filter( 'wc_bookings_calendar_default_to_current_date', '__return_false' );

add_filter( 'facetwp_assets', function( $assets ) {
  $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
  //  $assets['wc-bookings-booking-form'] = WC_BOOKINGS_PLUGIN_URL . '/assets/js/booking-form' . $suffix . '.js';
  //  $assets['wc-bookings-date-picker'] = WC_BOOKINGS_PLUGIN_URL . '/assets/js/date-picker.js';

    return $assets;
});
remove_action ('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);





function additional_div_in_shop() {
    // Only on "shop" archives pages
    if( ! is_shop() ) return;
    // Output the div
    ?>



      <div class="row">
        <div class="col-12 text-center">
        <h1 class="header"><?php printf( esc_html__( '%s', 'sage' ), get_bloginfo ( 'description' ) ); ?></h1>
        </div>
      </div>

      <div class="d-flex flex-row justify-content-center">
          <div class="">
            <input type="text" class="form-control-lg" autocomplete="off" id="startdate" value="" placeholder="Start Date" readonly>
          </div>
          <div class="px-2">
            <?php echo facetwp_display( 'facet', 'anailability' ); ?>
          </div>
          <div class="">
            <input type="text" class="form-control-lg" autocomplete="off" id="enddate" value="" placeholder="End Date" readonly>
          </div>
      </div>


    <?php
}

add_action( 'woocommerce_archive_description', 'additional_div_in_shop', 5 );





function show_attributes_doors() {
global $product;
$product_id = $product->get_id();
$attribute_slug = 'doors';
$array = wc_get_product_terms( $product_id , 'pa_' . $attribute_slug, array( 'fields' => 'names' ) );
$text = array_shift( $array );
echo '<div class="cars-slider_item-option car-option-' . $attribute_slug . '"><h6>Doors:<span class="attribute">' . $text . '</span></h6></div>';
}
add_action( 'woocommerce_attribute', 'show_attributes_doors', 10 );
function show_attributes_passengers() {
global $product;
$product_id = $product->get_id();
$attribute_slug = 'passengers';
$array = wc_get_product_terms( $product_id , 'pa_' . $attribute_slug, array( 'fields' => 'names' ) );
$text = array_shift( $array );
echo '<div class="cars-slider_item-option car-option-' . $attribute_slug . '"><h6>passengers:<span class="attribute">' . $text . '</span></h6></div>';
}
add_action( 'woocommerce_attribute', 'show_attributes_passengers', 20 );
function show_attributes_luggages() {
global $product;
$product_id = $product->get_id();
$attribute_slug = 'luggages';
$array = wc_get_product_terms( $product_id , 'pa_' . $attribute_slug, array( 'fields' => 'names' ) );
$text = array_shift( $array );
echo '<div class="cars-slider_item-option car-option-' . $attribute_slug . '"><h6>luggages:<span class="attribute">' . $text . '</span></h6></div>';
}
add_action( 'woocommerce_attribute', 'show_attributes_luggages', 30 );
function show_attributes_transmission() {
global $product;
$product_id = $product->get_id();
$attribute_slug = 'transmission';
$array = wc_get_product_terms( $product_id , 'pa_' . $attribute_slug, array( 'fields' => 'names' ) );
$text = array_shift( $array );
echo '<div class="cars-slider_item-option car-option-' . $attribute_slug . '"><h6>transmission:<span class="attribute">' . $text . '</span></h6></div>';
}
add_action( 'woocommerce_attribute', 'show_attributes_transmission', 40 );

function carhub_carousel_start_1(){
  $loop = new WP_Query(array(
          'post_type' => 'product',
          'posts_per_page' => -1,
          'orderyby' => 'post_id',
          'order' => 'ASC' ));
  ?>
  <!--CAROUSEL SLIDER SECTION START HERE-->
    <div id="news-carousel" class="row carousel slide text-center facetwp-template" data-ride="carousel">
      <div class="carousel-inner " role="listbox" >
        <!-- The slideshow -->
      <?php $count = 0; while ( $loop->have_posts() ) : $loop->the_post(); ?>
        <?php if ( has_post_thumbnail() ) { ?>


                 <div class="carousel-item item <?php if($count == '0'){ echo 'active'; } ?>" data-slide-number="<?php echo $count ?>" data-url="<?php the_permalink(); ?>" >
                  <div class="row">
                   <div class="col-3 p-0 text-right">
                       <h2 class="car_title"><a href="<?php the_permalink(); ?>">
                         <?php



                          $financialYear = get_the_title();
                          $test = explode(' ',$financialYear);
                          echo $test[0]; //
                          echo "<br>";
                          echo $test[1]; //
                          echo "<br>";
                        //  echo $test[2]; //

                           ?>

                       </a></h2>
                   </div>

                <div class="col-6 p-0">
                    <?php the_post_thumbnail( 'large' ); ?>
                </div>
                <div class="col-3 p-0 text-left">
                    <?php do_action ( 'woocommerce_attribute' );?>
                </div>
              </div><!-- /item -->
          </div><!-- /carousel column -->

    <?php } ?>
    <!-- **************************************************************** -->
     <?php $count++; endwhile; wp_reset_postdata(); ?>



</div>
<!-- // End The slideshow -->
   <!-- Left and right controls -->
         <a class="carousel-control-prev" href="#news-carousel" role="button" data-slide="prev">
             <i class="fa fa-chevron-left"></i> </a>
         <a class="carousel-control-next" href="#news-carousel" role="button" data-slide="next">
             <i style="color: black;" class="fa fa-chevron-right"></i> </a>

 </div> <!-- Carousel 1 -->
 <!--//CAROUSEL SLIDER SECTION END HERE-->
 <!--CAROUSEL indicators SECTION START HERE-->

  <div class="row carousel-indicators pt-5" id="ads">
  <?php $count = 0; while ( $loop->have_posts() ) : $loop->the_post(); ?>
      <div data-target="#news-carousel" data-slide-to="<?php echo $count ?>" class="col-md-2 col-sm-6 <?php if($count == '0'){ echo 'active'; } ?>">

      <?php wc_get_template_part( 'content', 'single-product' );?>
    </div>
  <?php $count++; endwhile; wp_reset_postdata(); ?>
</div>
<!--//CAROUSEL indicators SECTION END HERE-->
<?php }
add_action( 'carhub_carousel_start', 'carhub_carousel_start_1' , 20);


remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

function carhub_template_loop_product_link_open() {
  global $product;

  $link = apply_filters( 'woocommerce_loop_product_link', get_the_permalink(), $product );

  echo '<div class="woocommerce-LoopProduct-link woocommerce-loop-product__link" data-target="#carscarousel">';
}
add_action( 'woocommerce_before_shop_loop_item', 'carhub_template_loop_product_link_open', 10 );

function carhub_template_loop_product_link_close() {
  echo '</div>';
}
add_action( 'woocommerce_after_shop_loop_item', 'carhub_template_loop_product_link_close', 5 );
