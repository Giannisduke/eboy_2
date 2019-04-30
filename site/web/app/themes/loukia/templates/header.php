<div id='slider'>
	<div class="d-flex align-items-start flex-column">
  <div class="p-2 mb-auto"><?php
		wp_nav_menu(array(
		  'theme_location' => 'primary',
		  'walker' => new Microdot_Walker_Nav_Menu(),
		  'container' => false,
		  'items_wrap' => '<ul class="list-unstyled pt-3">%3$s</ul>'
		));
		?>
	</div>
  <div class="p-0 w-100">
		<div class="d-flex flex-row flex-wrap align-items-end">
  <div class="p-1">
		<a class="p-2 social-sharing-icon social-sharing-icon-facebook" target="_new" href="https://www.facebook.com/LoukiaAtelier/"></a>
	</div>
  <div class="p-1">
				<a class="p-2 social-sharing-icon social-sharing-icon-instagram" target="_new" href="https://www.facebook.com/LoukiaAtelier/"></a>
	</div>
  <div class="p-1">
		Powered by: <a href="https://eboy.gr">eboy.gr</a>
	</div>
</div>

	</div>

</div>

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
