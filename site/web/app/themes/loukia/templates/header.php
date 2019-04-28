<div id='slider'>		<?php
		wp_nav_menu(array(
		  'theme_location' => 'primary',
		  'walker' => new Microdot_Walker_Nav_Menu(),
		  'container' => false,
		  'items_wrap' => '<ul class="list-unstyled pt-3">%3$s</ul>'
		));
		?>
</div>
<header class="banner  align-top">
  <nav id="topNav" class="navbar navbar-toggleable-md navbar-inverse bg-inverse d-flex justify-content-between align-content-start">
  <div class="p-0 d-md-none align-self-start col-2">
		<button class="hamburger hamburger--arrowturn" type="button">
			<span class="hamburger-box">
				<span class="hamburger-inner"></span>
			</span>
		</button>
	</div>
  <div class="p-0 d-none d-md-block align-self-start col-2">
		<button class="hamburger hamburger--arrowturn" type="button">
			<span class="hamburger-box">
				<span class="hamburger-inner"></span>
			</span>Menu
		</button>
	</div>
  <div class="p-0 align-self-center col-8 text-center ">
		<div class="d-flex flex-row flex-wrap justify-content-center">
		<div class="p-0">
			<?php do_action ('loukia_header' ); ?>

		</div>
		<div class="p-0 tags">
			<?php echo facetwp_display( 'facet', 'search' );?>
			</div>
			</div>
	</div>
	<div class="p-0 col-2 align-self-start">

</div>
</nav>
</header>
