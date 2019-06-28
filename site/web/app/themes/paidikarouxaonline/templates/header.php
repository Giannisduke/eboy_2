<header class="banner container-fluid">
  <div class="row">
    <div class="col-12 upper_head">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="d-flex flex-row">
              <?php dynamic_sidebar('upper-header-left'); ?>
              

              <div class="p-2 ml-auto">Στατικές σελίδες</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row head py-1">
  <div class="container">
    <div class="d-flex flex-row align-items-center">
      <div class="p-2">
        <a class="brand" href="<?= esc_url(home_url('/')); ?>">
          <img class="logo" src='<?php echo esc_url( get_theme_mod( 'themeslug_logo' ) ); ?>' alt='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>'>
          <?php bloginfo('name'); ?>
        </a>
      </div>

      <div class="px-5">
        <nav class="nav-primary">
          <?php
          if (has_nav_menu('primary_navigation')) :
            wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav']);
          endif;
          ?>
        </nav>
      </div>

      <?php dynamic_sidebar('header-center'); ?>
    <div class="px-5 ml-auto">
      <img class="" <img src="<?= get_template_directory_uri(); ?>/dist/images/ico_member_m.svg">
        <a class="" href="<?= esc_url(home_url('/')); ?>">είσοδος</a>
        /
        <a class="" href="<?= esc_url(home_url('/')); ?>">εγγραφή</a>
    </div>
    </div>


  </div>
  </div>


</header>
