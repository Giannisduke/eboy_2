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

function product_carousel_2() {
              $args = array(
                'posts_per_page' => 5,
                'post_type' => 'product',
              'facetwp' => true,
            );
            $the_query = new WP_Query ( $args );
            $thumbnail_id   = get_post_thumbnail_id();
            $thumbnail_url  = wp_get_attachment_image_src( $thumbnail_id, 'full', true );
            $thumbnail_meta = get_post_meta( $thumbnail_id, '_wp_attatchment_image_alt', true );
            ?>

            <div id="carscarousel" class="carousel slide text-center facetwp-template" data-ride="carousel" data-interval="7000">

            <div class="carousel-inner">
	    <?php if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();
      global $product;
      $id = $product->get_id();
	    ?>

      <div class="carousel-item item <?php if ( $the_query->current_post == 0 ) : ?>active<?php endif; ?>">
  <div class="container">
    <div class="row">
<div class="col-2">
    <div class="carousel-caption text-left">
      <h2><?php the_title(); ?></h2>
      <p class="d-none d-sm-block"><?php the_excerpt(); ?></p>
      <a class="btn btn-primary product-preview" data-project-id="<?php echo $id ?>" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
        Book Now!
      </a>
    </div>
  </div>
  <div class="col-8">
    <?php if ( has_post_thumbnail() ) : ?>
    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
      <?php the_post_thumbnail('full', array('class' => 'img-fluid')); ?>
    </a>
    <?php endif; ?>

  </div>

  <div class="col-2">
<?php do_action ( 'woocommerce_attribute' );  ?>
    </div>

  </div>
  </div>
</div><!-- /.carousel-item -->
<!-- end first loop -->
<?php endwhile;	endif; ?>

<?php rewind_posts(); ?>
</div><!-- /.carousel-inner -->


                                <div class="row">
                                  <div class="col-12 collapse" id="collapseExample">
                                    test
                              <?php //echo wc_get_template_part( 'content', 'single-car' ); ?>
                              </div>
                              </div>


<div class="row">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="reservation-form">
          </div>
        </div>
      </div>
      <div class="row">
        <!-- Start Carousel Indicator Loop-->
    <?php if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?>


      <div data-project-id="<?php echo $id ?>" data-target="#carscarousel" data-slide-to="<?php echo $the_query->current_post; ?>" class="carousel p-2 col-6 col-lg-4 <?php if ( $the_query->current_post == 0 ) : ?>active<?php endif; ?>">
        <?php the_post_thumbnail('medium', array('class' => 'img-fluid car-thumb', 'data-href' => "'" . get_permalink() . "'", 'data-project-id' => "" . get_the_ID() . ""));?>
        <?php wc_get_template_part( 'content', 'single-product' );?>

      </div>

    <?php endwhile; endif; ?>

    </div>
  </div>
</div><!-- /.carousel-slide -->
<?
}
add_action('carhub_product_carousel_2', 'product_carousel_2', 20);





function additional_div_in_shop() {
    // Only on "shop" archives pages
    if( ! is_shop() ) return;
    // Output the div
    ?>

      <div class="row">
        <div class="col-12 text-center">
        <h1><?php printf( esc_html__( '%s', 'sage' ), get_bloginfo ( 'description' ) ); ?></h1>
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




function carhub_carousel_start_1(){ ?>
  <div id="carscarousel" class="carousel slide text-center facetwp-template" data-ride="carousel" >
    <div class="carousel-inner">
<?php
if ( wc_get_loop_prop( 'total' ) ) {

  while ( have_posts() ) {
    the_post();
    wc_get_template_part( 'content', 'product_carousel' );
  }
}
?>
</div>
<!-- Left and right controls -->
  <a class="carousel-control-prev" href="#carscarousel">
    <span class="carousel-control-prev-icon"></span>
  </a>
  <a class="carousel-control-next" href="#carscarousel">
    <span class="carousel-control-next-icon"></span>
  </a>
<?php }
add_action( 'carhub_carousel_start', 'carhub_carousel_start_1' , 10);


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
