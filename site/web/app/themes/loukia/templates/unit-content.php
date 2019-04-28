<section class="content white-background">

<div class="container-fluid">
<div class="row">
  <div class="col-md-2 col-sm-12">
  <nav class="nav flex-column flex-nowrap navbar-left">

    <?php echo facetwp_display( 'facet', 'categories' );?>

  </nav>
  </div>
  <div class="col-md-10 col-lg-8 facetwp-template text-center p-0">

<?php do_action ('post_front' ); ?>
    <?php //echo facetwp_display( 'pager' ); ?>
  </div>


</div>
</div>
</section>
