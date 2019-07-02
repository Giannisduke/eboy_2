
<section class="description">
  <?php
  $my_excerpt = get_the_excerpt();
   if($my_excerpt !='') {
       the_excerpt();
   }
?>
<a class="btn btn-primary" data-toggle="collapse" href="#linkcollapse" aria-expanded="false" aria-controls="Collapse">
Περισσότερα
</a>


</section>

  <?php the_content(); ?>

<?php wp_link_pages(['before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']); ?>
