<?php
/**
 * Template Name: Contact Template
 */
?>
<div class="container">
  <div class="row">
    <div class="col-12">
      <?php while (have_posts()) : the_post(); ?>
<?php the_content(); ?>
      <?php endwhile; ?>

  </div>
  </div>
  <div class="row">
    <div class="col-12">
  <?php gravity_form( 1, false, false, false, '', false ); ?>
</div>
</div>
</div>
