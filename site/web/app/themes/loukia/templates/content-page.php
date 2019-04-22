<div class="container pt-5">
<div class="row">
<div class="col-6  text-right">
  <img src="  <?php
    if ( has_post_thumbnail() ) {
    the_post_thumbnail_url();
  }  ?>" class="img-fluid" alt="Responsive image">

</div>
<div class="col-6">
<?php the_content(); ?>
</div>
</div>
</div>



<?php wp_link_pages(['before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']); ?>
