<?php
/**
 * Template Name: Front Template With full Shop
 */
?>

<?php while (have_posts()) : the_post(); ?>
    <?php get_template_part('templates/unit', 'hero'); ?>
  <?php get_template_part('templates/content', 'page'); ?>
  <?php

  if( have_rows('block_01') ):
      while ( have_rows('block_01') ) : the_row();
          $sub_value = get_sub_field('section_01');
          // Do something...
          echo $sub_value;
      endwhile;
  else :
      // no rows found
  endif;
?>
<?php endwhile; ?>
