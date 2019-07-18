<?php
/**
 * Template Name: Front Template With full Shop
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'header'); ?>
  <?php //get_template_part('templates/content', 'page'); ?>

<?php endwhile; ?>
