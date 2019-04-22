<div id='slider'>		<?php
		wp_nav_menu(array(
		  'theme_location' => 'primary',
		  'walker' => new Microdot_Walker_Nav_Menu(),
		  'container' => false,
		  'items_wrap' => '<ul class="list-unstyled pt-3">%3$s</ul>'
		));
		?>
</div>
<header class="banner">
  <nav id="topNav" class="navbar navbar-toggleable-sm navbar-inverse bg-inverse">
    <button class="hamburger hamburger--arrowturn" type="button">
      <span class="hamburger-box">
        <span class="hamburger-inner"></span>
      </span>Menu
    </button>
    <a class="navbar-brand mx-auto text-center" href="<?= esc_url(home_url('/')); ?>">
      <img class="logo" src='<?php echo esc_url( get_theme_mod( 'themeslug_logo' ) ); ?>' alt='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>'>
    </a>
    <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="#">About</a>
            </li>
        </ul>
    </div>
</nav>
</header>
