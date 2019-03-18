<header class="banner">
  <nav class="navbar navbar-toggleable-md navbar-light bg-faded">

      <div class="collapse navbar-collapse" id="nav-left">
        <?php
        if (has_nav_menu('primary_navigation')) :
          wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav']);
        endif;
        ?>
      </div>

      <a class="navbar-brand mx-auto" href="<?= esc_url(home_url('/')); ?>">
         <div class="row text-center">
           <div class="col-12">
          <img src='<?php echo esc_url( get_theme_mod( 'themeslug_logo' ) ); ?>' alt='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>'>
        </div>

        </div>



      </a>

      <div class="collapse navbar-collapse">
        <?php
        if (has_nav_menu('primary_navigation')) :
          wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav']);
        endif;
        ?>
      </div>

      <!-- Mobile -->
      <div class="collapse" id="navbarMobile">
        <?php
        if (has_nav_menu('primary_navigation')) :
          wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav']);
        endif;
        ?>
          </div>
  </nav>
</header>
