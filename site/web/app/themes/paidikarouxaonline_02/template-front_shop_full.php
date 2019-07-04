<?php
/**
 * Template Name: Front Template With full Shop
 */
?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/page', 'header'); ?>
  <?php get_template_part('templates/content', 'page'); ?>
  <div class="container">
  <div class="row">
    <div class="col-3">
    <?php echo facetwp_display('selections'); ?>
    <h4>Φύλλο</h4>
<?php  echo facetwp_display( 'facet', 'product_categories' ); ?>
    </div>
    <div class="col-9">
    <div class="row facetwp-template">
	<?php
		$args = array(
			'post_type' => 'product',
			'posts_per_page' => 12,
      'facetwp' => true
			);
		$loop = new WP_Query( $args );
		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) : $loop->the_post();
      ?>
      <div class="col-4">
    		<?php wc_get_template_part( 'content', 'product' ); ?>
      </div>
<?php
    	endwhile;
		} else {
			echo __( 'No products found' );
		}
		wp_reset_postdata();
	?>
</div><!--/.products-->
</div>
</div>
</div>
<?php endwhile; ?>
