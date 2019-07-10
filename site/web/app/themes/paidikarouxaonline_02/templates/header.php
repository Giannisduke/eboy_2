<header>
<div class="navigation-wrap start-header start-style">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <nav class="navbar navbar-expand-md navbar-classic">

          <a class="navbar-brand d-flex align-items-center" href="<?= esc_url(home_url('/')); ?>">
              <img  src='<?php echo esc_url( get_theme_mod( 'themeslug_logo' ) ); ?>' alt='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>'>
              <h1><?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?></h1>
          </a>

          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>


          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <?php
          wp_nav_menu( array(
            	'theme_location'  => 'primary_navigation',
            	'depth'	          => 2, // 1 = no dropdowns, 2 = with dropdowns.
              'container'         => 'div',
              'container_class'   => 'collapse navbar-collapse',
              'container_id'      => 'bs-example-navbar-collapse-1',
              'menu_class'        => 'nav navbar-nav',
            	'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
            	'walker'          => new WP_Bootstrap_Navwalker(),
            ) );

            ?>


        </div>
        <div class="col-7 p-0">
          <div class="d-flex flex-row justify-content-between align-items-center">

          <?php dynamic_sidebar('header-center'); ?>
          <div class="widget_shopping_cart_content"><?php woocommerce_mini_cart(); ?></div>

        </div>
        </nav>

      </div>
    </div>
  </div>
</div>
</header>
