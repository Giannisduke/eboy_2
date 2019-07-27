<header>
<div class="navigation-wrap start-header start-style">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark">
          <?php dynamic_sidebar('upper-header-left'); ?>


        </nav>

      </div>
    </div>
  </div>
  <div class="container">


    <div class="row">
      <div class="col-12">

        <nav class="navbar navbar-expand-md px-0">

          <div class="mr-auto order-0">
            <a class="navbar-brand d-flex align-items-center" href="<?= esc_url(home_url('/')); ?>">
                <img class="svg" src='<?php echo esc_url( get_theme_mod( 'themeslug_logo' ) ); ?>' alt='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>'>
                <h1><?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?></h1>
            </a>
              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-collapse2">
                  <span class="navbar-toggler-icon"></span>
              </button>
          </div>
    <div class="navbar-collapse collapse order-1 dual-collapse2" id="navbarSupportedContent">
      <?php
    wp_nav_menu( array(
        'theme_location'  => 'primary_navigation',
        'depth'	          => 2, // 1 = no dropdowns, 2 = with dropdowns.
        'container'         => 'div',
        'container_class'   => 'collapse navbar-collapse',
        //'container_id'      => 'bs-example-navbar-collapse-1',
        'menu_class'        => 'nav navbar-nav',
        'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
        'walker'          => new WP_Bootstrap_Navwalker(),
      ) );

      ?>
  </div>
  <div class="mx-auto order-2 w-25">
    <?php dynamic_sidebar('header-center'); ?>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-collapse2">
          <span class="navbar-toggler-icon"></span>
      </button>
  </div>
    <div class="navbar-collapse collapse order-3 dual-collapse2 flex-row justify-content-end">
      <div class="btn-group" role="group" aria-label="Basic example">
        <button type="button" class="btn btn-outline-primary"><?php echo do_shortcode("[ti_wishlist_products_counter]"); ?></button>
        <?php woocommerce_mini_cart();?>
      </div>
    </div>

</nav>

    </div>
  </div>
</div>

</header>
