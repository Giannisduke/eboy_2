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


function collections_menu(){

    query_posts(array(
        'post_type' => 'post',
        'showposts' => -1,
        'facetwp' => true,
    )); ?>

<div class="col-8 facetwp-template text-center p-0">
  <?php

  global $post; ?>
  <ul class="list-unstyled">
  <?php while (have_posts()) : the_post(); ?>
     <li class="pb-5">
       <div class="container">
       <div class="row">
       <div class="col-12">
       <a class="" href="<?php the_permalink(); ?>"><h2><?php the_title(); ?></h2></a>
       <p><?php the_content(); ?></p>
       <div class="d-flex flex-row justify-content-center">
        <div class="pt-1">
          <?php if ( has_post_thumbnail() ) {
       $large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
       if ( ! empty( $large_image_url[0] ) ) {
           echo '<p>';
           echo '<a href="' . esc_url( $large_image_url[0] ) . '" title="' . the_title_attribute( array( 'echo' => 0 ) ) . '">';
           echo get_the_post_thumbnail( $post->ID, 'medium', array('class' => 'img-responsive'));
           echo '</a>';
           echo '</p>';

       }
   } ?>
        </div>
        <div class="p-0">
          <?php
          $images = get_field('photos');
          $size = 'thumbnail'; // (thumbnail, medium, large, full or custom size)
          if( $images ): ?>
          <div class="d-flex justify-content-start flex-wrap">

              <?php foreach( $images as $image ): ?>
                  <div class="pl-1 pt-1">
                    <?php echo wp_get_attachment_image( $image['ID'], $size ); ?>
                  </div>
              <?php endforeach; ?>
          </div>
          <?php endif; ?>

        </div>

      </div>


    </div>
    </div>
    </div>
     </li>
  		    <?php endwhile; ?>
        </ul>
</div>
<?php }
add_action ('collection', 'collections_menu', 10 );