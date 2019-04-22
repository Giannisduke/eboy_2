<section class="content white-background">

<div class="container-fluid">
<div class="row">
  <div class="col-2">
  <nav class="navbar navbar-full navbar-light navbar-left">

    <?php echo facetwp_display( 'facet', 'categories' );?>

  </nav>
  </div>
  <div class="col-8 facetwp-template text-center p-0">
<?php do_action ('post_front' ); ?>
    <?php echo facetwp_display( 'pager' ); ?>
  </div>


</div>
</div>
</section>
