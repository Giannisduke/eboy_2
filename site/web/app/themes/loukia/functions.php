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


// Register Custom Navigation Walker (Soil)
require_once('Microdot_Walker_Nav_Menu.php');

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
    //  'showposts' => -1,
    "posts_per_page" => 5,
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
$tags = get_the_tags();
?>
  <article <?php post_class('justify-content-center'); ?>>

    <div class="container-fluid text-sm-left text-xl-center">
      <div class="d-flex flex-row justify-content-center">
        <div class="entry-meta">
        <h3 class="entry-title"><?php the_title(); ?></h2>
          <div class="entry-tags">
          <?php

               if ($tags){
                   foreach ($tags as $tag) {
                      // global $wp;
                       $fwplink = home_url($wp->request) . '/?_search=' . $tag->slug;
                       echo '#<span class="entry-tag" style="margin-right:1px"><a href="#">';
                       echo $tag->name;
                       echo '</a></span> ';
                   }
               }
           ?>
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
    <div id="post_carousel_<?php echo esc_html( $id ); ?>" class="carousel slide w-100 post-carousel" data-ride="carousel">
      <div class="carousel-inner w-100 post_carousel" role="listbox">
      <?php //going to wrap every 3 in this example
          if ( get_field( 'gallery' ) ): ?>
          <?php $index = 1; ?>
          <?php $totalNum = count( get_field('gallery') ); ?>
          <?php while ( have_rows( 'gallery' ) ): the_row(); ?>
          <? if ($index  == 1) : ?>
         <div class="carousel-item post_carousel no-gutters active">
         <div class="d-flex flex-row flex-wrap justify-content-center align-items-start grid">
            <div class="grid-sizer"></div>
           <a class="pan grid-item grid-item-landscape" data-big="<?php echo $slide_images[$index - 1]['sizes']['large'] ?>" href="#">
         <img src="<?php echo $slide_images[$index - 1]['sizes']['medium'] ?>" class="img-fluid pan" alt="Atelier Loukia - <?php the_title(); ?>">
          </a>




        <? elseif ($index  > 1) : ?>
        <a class="pan grid-item " data-big="<?php echo $slide_images[$index - 1]['sizes']['large'] ?>" href="#">
        <img src="<?php echo $slide_images[$index - 1]['sizes']['thumbnail'] ?>" class="img-fluid pan " alt="Atelier Loukia - <?php the_title(); ?>">
        </a>
       <? endif; ?>
        <? if ($index % 4 == 0) : ?>
        <? if ($index < $totalNum) : ?>


        </div>
        </div>
        <div class="carousel-item post_carousel no-gutters">
        <div class="d-flex flex-row flex-wrap justify-content-center align-items-start">

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



function logo() {
  if ( is_front_page() && is_home() ) {
  // Default homepage
  ?>
  <a class="" href="<?php echo esc_url( home_url( '/' ) ); ?>" >
  <h1>Atelier <span class="highlight">Loukia</span></h1>
  </a>
  <?php
} elseif ( is_front_page()){
  //Static homepage
?>
<a class="" href="<?php echo esc_url( home_url( '/' ) ); ?>" >
<h1>Atelier <span class="highlight">Loukia</span></h1>
</a>
<?php
  } elseif ( is_home()){

  //Blog page
?>
<a class="" href="<?php echo esc_url( home_url( '/' ) ); ?>" >
<h1>Atelier <span class="highlight">Loukia</span></h1>
</a>
<?php  } else {

  //everything else
  ?>
  <a class="" href="<?php echo esc_url( home_url( '/' ) ); ?>" >
  <h1>Atelier <span class="highlight">Loukia</span></h1>
  </a>
  <?php
  }

}
add_action ('loukia_header', 'logo', 30 );
