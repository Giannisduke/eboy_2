<footer class="content-info">
  <div class="container">
    <div class="d-flex justify-content-between">
      <div class="p-2 test">
        <a class="brand" href="<?= esc_url(home_url('/')); ?>">
          <img class="logo" src='<?php echo esc_url( get_theme_mod( 'themeslug_logo' ) ); ?>' alt='<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>'>
          <?php bloginfo('name'); ?>
        </a>
      </div>
      <div class="p-2 test">Flex item 2</div>
      <div class="p-2 test">Flex item 3</div>
      <div class="p-2 test">Flex item 3</div>

    </div>

    <?php dynamic_sidebar('sidebar-footer'); ?>
  </div>
</footer>
