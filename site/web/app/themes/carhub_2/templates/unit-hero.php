
  <!-- First Parallax Section -->
  <div class="jumbotron">
    <div class="jumbotron-bg"><?php // echo facetwp_display( 'facet', 'map' ); ?></div>
    <div class="container jumbotron-container text-center hidden">
      <div class="row">
        <div class="col-12 text-center">
        <h1><?php printf( esc_html__( '%s', 'sage' ), get_bloginfo ( 'description' ) ); ?></h1>
        </div>
      </div>

      <div class="d-flex flex-row justify-content-center">
          <div class="">
            <input type="text" class="form-control-lg" autocomplete="off" id="startdate" value="" placeholder="Start Date" readonly>
          </div>
          <div class="px-2">
            <?php echo facetwp_display( 'facet', 'anailability' ); ?>
          </div>
          <div class="">
            <input type="text" class="form-control-lg" autocomplete="off" id="enddate" value="" placeholder="End Date" readonly>
          </div>
      </div>
      <p><?php do_action('carhub_product_carousel_2'); ?></p>
    </div>
  </div>
