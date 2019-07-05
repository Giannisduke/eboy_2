<div class="navigation-wrap bg-light start-header start-style">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <nav class="navbar navbar-expand-md navbar-light">

          <a class="navbar-brand d-flex align-items-start" href="<?= esc_url(home_url('/')); ?>">
              <img  src='<?php echo esc_url( get_theme_mod( 'themeslug_logo' ) ); ?>' alt='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>'>
              <h1><?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?></h1>
          </a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <?php
            wp_nav_menu(array(
              'theme_location' => 'primary_navigation',
              'walker' => new Marrrion_Navwalker(),
              'container'         => 'ul', //To replace div wrapper with ul
              'menu_class'        => 'navbar-nav mr-auto py-4 py-md-0'//Add classes to your ul

            ));
            ?>
          <div class="d-flex align-items-center">
            Test
          </div>

          </div>

        </nav>
      </div>
    </div>
  </div>
</div>
