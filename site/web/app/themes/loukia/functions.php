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
  'lib/post-types.php'
];

foreach ($sage_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);



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
    'title'       => __( 'Logo', 'themeslug' ),
    'priority'    => 30,
    'description' => 'Upload a logo to replace the default site name and description in the header',
) );
$wp_customize->add_setting( 'themeslug_logo' );
$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'themeslug_logo', array(
    'label'    => __( 'Logo', 'themeslug' ),
    'section'  => 'themeslug_logo_section',
    'settings' => 'themeslug_logo',
    'extensions' => array( 'jpg', 'jpeg', 'gif', 'png', 'svg' ),
) ) );
}

add_action ('customize_register', 'themeslug_theme_customizer');


####################################################
#    VIDEO
####################################################

function loukia_front_carousel_indicators(){

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

add_action('loukia_custom_front', 'loukia_front_carousel_indicators', 20);




function loukia_front_carousel(){


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

add_action('loukia_custom_front', 'loukia_front_carousel', 30);



function title_meta(){
  query_posts(array(
      'post_type' => 'post',
      'showposts' => -1,
      'facetwp' => true
  ));
  ?>
<?php while (have_posts()) : the_post(); global $post;
$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
$slide_images = get_field('gallery');
$size = 'thumbnail'; // (thumbnail, medium, large, full or custom size)
$size_medium = 'medium'; // (thumbnail, medium, large, full or custom size)
global $post;
$id = get_the_ID();
?>
  <article <?php post_class('justify-content-center'); ?>>

    <div class="container-fluid text-center p-0 m-0">
      <div class="d-flex flex-row justify-content-center">
        <div class="entry-meta">
        <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <?php get_template_part('templates/entry-meta'); ?>

      <div class="entry-summary">
        <?php the_excerpt(); ?>
      </div>
      <div class="entry-content">
        <?php the_content(); ?>

      </div>

      <div class="entry-indicators d-flex flex-row justify-content-center">

        <?php if ( get_field( 'gallery' ) ): ?>
          <?php $index = 1; ?>
          <?php $totalNum = count( get_field('gallery') ); ?>
          <?php $counter = 0 ?>
                <!--Indicators-->
                <a class="carousel-control-prev" href="#post_carousel_<?php echo esc_html( $id ); ?>" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>


                  <?php while ( have_rows( 'gallery' ) ): the_row(); ?>

                    <? if ($index % 4 == 1) :  ?>

                        <? if ($index < $totalNum) : ?>
                        <div data-target="#post_carousel_<?php echo esc_html( $id ); ?>" data-slide-to="<?php echo esc_html( $counter++%4 ); ?>" class="myCarousel-target <?php if($counter === 0){ echo "active";} ?>"></div>

                        <? elseif ($index == $totalNum) : ?>

                      <? endif; ?>

                  <? endif; ?>

          <?php $index++; ?>
          <?php $counter++;  ?>




                <?php endwhile; ?>
                <a class="carousel-control-next" href="#post_carousel_<?php echo esc_html( $id ); ?>" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
                <?php endif; ?>

              <!--/.Indicators-->
      </div>

       </div>


      </div>
    <div id="post_carousel_<?php echo esc_html( $id ); ?>" class="carousel slide w-100" data-ride="carousel">

      <div class="carousel-inner w-100 post_carousel" role="listbox">
      <?php //going to wrap every 3 in this example
          if ( get_field( 'gallery' ) ): ?>

          <?php $index = 1; ?>
          <?php $totalNum = count( get_field('gallery') ); ?>


          <div class="carousel-item post_carousel no-gutters active">

              <div class="d-flex flex-row flex-wrap align-items-start">

                            <?php while ( have_rows( 'gallery' ) ): the_row(); ?>
                              <? if ($index  == 1) : ?>

                              <img src="<?php echo $slide_images[$index - 1]['sizes']['medium'] ?>" class="img-fluid gallery-image w-50 p-1" alt="Responsive image">


                            <? elseif ($index  > 1) : ?>

                            <img src="<?php echo $slide_images[$index - 1]['sizes']['thumbnail'] ?>" class="img-fluid gallery-image w-25 p-1" alt="Responsive image">

                              <? endif; ?>

                                <? if ($index % 4 == 0) : ?>
                                    <? if ($index < $totalNum) : ?>
                                    </div>
                                  </div>


                      <div class="carousel-item post_carousel no-gutters">
                          <div class="d-flex flex-row flex-wrap align-items-start">
                  <? elseif ($index == $totalNum) : ?>


                  </div>
                  <? endif; ?>

              <? endif; ?>

          <?php $index++; ?>
          <?php endwhile; ?>

      <?php endif; ?>

    </div>


    </div>



  </article>
<?php endwhile; ?>

 <?php }
 add_action ('post_front', 'title_meta', 10 );



function posts_normal() {
  // WP_Query arguments
$args = array(
  'post_type' => 'post',
  'showposts' => -1,
  'facetwp' => true
);

// The Query
$query = new WP_Query( $args );
global $post;
// The Loop
if ( $query->have_posts() ) {
	while ( $query->have_posts() ) {
		$query->the_post();
		// do something
    $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
    $slide_images = get_field('gallery');
    $size = 'thumbnail'; // (thumbnail, medium, large, full or custom size)

    $id = get_the_ID();

  ?>
      <article <?php post_class('justify-content-center'); ?>>
        <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <?php the_excerpt(); ?>
        <?php the_content(); ?>
        <?php if ( get_field( 'gallery' ) ): ?>
          <?php $index = 1; ?>
          <?php $totalNum = count( get_field('gallery') ); ?>
          <div id="post_carousel_<?php echo esc_html( $id ); ?>" class="carousel slide w-100" data-ride="carousel">

            <div class="carousel-inner w-100 d-flex flex-row flex-wrap align-items-start test post_carousel" role="listbox">
              <div class="carousel-item post_carousel no-gutters active">

              <?php while ( have_rows( 'gallery' ) ): the_row(); ?>

                    test
                <? if ($index % 4 == 0) : ?>
                    <? if ($index < $totalNum) : ?>

                    test_1
                  <? elseif ($index == $totalNum) : ?>
                </div>

                    test2

                    <? endif; ?>
                <? endif; ?>
                <?php $index++; ?>
                <?php endwhile; ?>

            </div>
        <? endif; ?>
      </article>
	<?php }
} else {
	// no posts found
    echo 'No test';
  }

// Restore original Post Data
wp_reset_postdata();
}
//add_action ('post_front', 'posts_normal', 10 );
