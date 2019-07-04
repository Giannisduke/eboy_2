<header class="container-fluid">
    <div class="top-bar">
        <div class="social-bar">
          <div class="col-12">
            <div class="d-flex flex-row">
              <div class="p-2">
                <img src="<?= get_template_directory_uri(); ?>/dist/images/ico_fb.svg">

                <img src="<?= get_template_directory_uri(); ?>/dist/images/ico_gplus.svg">
              </div>
              <?php dynamic_sidebar('upper-header-left'); ?>
              <?php dynamic_sidebar('upper-header-right'); ?>

                    <div class="ml-auto btn-group" role="group" aria-label="Basic example">
                      <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#exampleModal"><img class="" <img src="<?= get_template_directory_uri(); ?>/dist/images/ico_member_m.svg"></button>
                      <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#exampleModal">είσοδος</button>
                      <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#exampleModal">εγγραφή</button>
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
    <div class="p-0 ml-auto">
      <?php dynamic_sidebar('header-right'); ?>
    </div>
    </div>


  </div>
  </div>

  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Είσοδος</h5>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="card card-signin my-5">
    <div class="card-body">

      <form class="form-signin">
        <div class="form-label-group">
          <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
          <label for="inputEmail">Email address</label>
        </div>

        <div class="form-label-group">
          <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
          <label for="inputPassword">Password</label>
        </div>

        <div class="custom-control custom-checkbox mb-3">
          <input type="checkbox" class="custom-control-input" id="customCheck1">
          <label class="custom-control-label" for="customCheck1">Remember password</label>
        </div>
        <button class="btn btn-lg btn-primary btn-block text-uppercase" type="submit">Sign in</button>
        <hr class="my-4">
        <button class="btn btn-lg btn-google btn-block text-uppercase" type="submit"><i class="fab fa-google mr-2"></i> Sign in with Google</button>
        <button class="btn btn-lg btn-facebook btn-block text-uppercase" type="submit"><i class="fab fa-facebook-f mr-2"></i> Sign in with Facebook</button>
      </form>
    </div>
  </div>
        </div>

      </div>
    </div>
  </div>

</header>
