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

?>
<?php
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

function collections_menu(){

    query_posts(array(
        'post_type' => 'post',
        'showposts' => -1,
        'facetwp' => true,
    )); ?>

<div class="col-9 facetwp-template text-center p-0">
  <?php

  global $post; ?>
  <ul class="list-unstyled">
  <?php while (have_posts()) : the_post(); ?>
     <li class="pb-5">


         <article <?php post_class('row justify-content-center'); ?>>

             <div class="col-12 entry-meta">
             <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
             <?php //get_template_part('templates/entry-meta'); ?>

           <div class="entry-summary">
             <?php the_excerpt(); ?>
           </div>
            </div>

              <?php if ( has_post_thumbnail() ) {

           $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
           $slide_images = get_field('gallery');
           $size = 'thumbnail'; // (thumbnail, medium, large, full or custom size)
           $size_medium = 'medium'; // (thumbnail, medium, large, full or custom size)
           $i = 0;
                if( $slide_images ):
                  $count = count( $slide_images );
                  echo '<div class="col-5 px-0 ">';
                 echo '<a href="' . esc_url( $large_image_url[0] ) . '" title="' . the_title_attribute( array( 'echo' => 0 ) ) . '">';
                 echo get_the_post_thumbnail( $post->ID, 'medium', array('class' => 'img-fluid p-1'));
                 echo '</a>';
                 echo '</div>';
                      echo '<div class="col-6 d-flex flex-wrap p-0">';
                    foreach( $slide_images as $slide_image ):$i++;
                    if( $i > 4)
                    {
                      break;
                    }
                  echo wp_get_attachment_image( $slide_image['ID'], $size, "", ["class" => "img-fluid p-1"] );
                  endforeach;
                  echo '</div>';

                  else:
                    echo '<div class="col-5 px-0 entry-meta">';
                   echo '<a href="' . esc_url( $large_image_url[0] ) . '" title="' . the_title_attribute( array( 'echo' => 0 ) ) . '">';
                   echo get_the_post_thumbnail( $post->ID, 'medium', array('class' => 'img-fluid p-1'));
                   echo '</a>';
                   echo '</div>';
                  endif;

       }
       else{

         $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
         $slide_images = get_field('gallery');
         $size = 'thumbnail'; // (thumbnail, medium, large, full or custom size)
         $size_medium = 'medium'; // (thumbnail, medium, large, full or custom size)

          $i = 0;
              if( $slide_images ):
                $count = count( $slide_images );
                    echo '<div class="col-12 d-flex flex-wrap justify-content-center mergin-left-2 p-0">';
                  foreach( $slide_images as $slide_image ):$i++;
                  if( $i > 4)
            			{
            				break;
            			}
                echo wp_get_attachment_image( $slide_image['ID'], $size, "", ["class" => "img-fluid p-1"] );
                endforeach;
                echo '</div>';

        if ($count > 4) {

        echo '<div class="col-12 collapse mergin-left-2" id="collapseExample">';
$counter = 0;
foreach ($slide_images as $slide_image) {
  $counter++;
  if ($counter > 4) {
    echo wp_get_attachment_image( $slide_image['ID'], $size, "", ["class" => "img-fluid p-1"] );

  }
}
          echo '</div>';
          echo '<div class="col-12 p-3">';
          echo '  <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                  Button with data-target
                </button>';
          echo '</div>';
               }
                endif;
} ?>
         </article>
     </li>
  		    <?php endwhile; ?>
        </ul>

<?php }
add_action ('collection', 'collections_menu', 10 );


function title_meta(){
  query_posts(array(
      'post_type' => 'post',
      'showposts' => -1,
      'facetwp' => true,
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
    <div class="col-12 entry-meta">
    <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <?php //get_template_part('templates/entry-meta'); ?>

  <div class="entry-summary">
    <?php the_excerpt(); ?>
  </div>
  <div class="entry-content">
    <?php the_content(); ?>
  </div>
   </div>
    <div class="container-fluid text-center p-0 m-0">
    <div id="post_carousel_<?php echo esc_html( $id ); ?>" class="carousel slide w-100" data-ride="carousel">
      <div class="carousel-inner w-100" role="listbox">
      <?php //going to wrap every 3 in this example
          if ( get_field( 'gallery' ) ): ?>

          <?php $index = 1; ?>
          <?php $totalNum = count( get_field('gallery') ); ?>

          <div class="carousel-item  no-gutters active">
            <div class="container">
              <div class="row">
                <?php if ( has_post_thumbnail() ) {

                  echo get_the_post_thumbnail( $post->ID, 'medium', array('class' => 'img-fluid p-1'));
                } ?>
          <?php while ( have_rows( 'gallery' ) ): the_row(); ?>
            <div class="col-2 p-1">
              <img src="<?php echo $slide_images[$index - 1]['url'] ?>" class="img-fluid gallery-image" alt="Responsive image">
            </div>
              <? if ($index % 4 == 0) : ?>
                  <? if ($index < $totalNum) : ?>

              </div>
            </div>
          </div>
                      <div class="carousel-item  no-gutters">
                        <div class="container">
                          <div class="row">
                  <? elseif ($index == $totalNum) : ?>

                        </div>
                      </div>
                    </div>
                  <? endif; ?>

              <? endif; ?>

          <?php $index++; ?>
          <?php endwhile; ?>

      <?php endif; ?>

    </div>

        <a class="carousel-control-prev" href="post_carousel_<?php echo esc_html( $id ); ?>" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="post_carousel_<?php echo esc_html( $id ); ?>" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>


</div>

  </article>
<?php endwhile; ?>
 <?php }
add_action ('post_front', 'title_meta', 10 );
