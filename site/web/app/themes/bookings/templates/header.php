<header class="banner">
  <div class="container">
    <a class="brand" href="<?= esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
    <nav class="nav-primary">
      <?php
      if (has_nav_menu('primary_navigation')) :
        wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav']);
      endif;
      ?>
    </nav>
  </div>
  <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
    <h1 class="page-title"><?php woocommerce_page_title(); ?></h1>
  <?php endif; ?>

<?php //do_action( 'woocommerce_shop_loop' ); ?>


  <?php
  /**
   * Hook: woocommerce_archive_description.
   *
   * @hooked woocommerce_taxonomy_archive_description - 10
   * @hooked woocommerce_product_archive_description - 10
   */
  do_action( 'woocommerce_archive_description' );
  ?>
</header>
