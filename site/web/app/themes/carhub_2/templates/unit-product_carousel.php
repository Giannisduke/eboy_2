<?php
$args = array(
	'posts_per_page' => 5,
	'post_type' => 'product',
'facetwp' => true,
);
$the_query = new WP_Query ( $args );
$thumbnail_id   = get_post_thumbnail_id();
$thumbnail_url  = wp_get_attachment_image_src( $thumbnail_id, 'full', true );
$thumbnail_meta = get_post_meta( $thumbnail_id, '_wp_attatchment_image_alt', true );


?>

<?php if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post();
global $product;
$id = $product->get_id();
?>
<div class="carousel-item item <?php if ( $the_query->current_post == 0 ) : ?>active<?php endif; ?>">
<div class="container">
	<div class="row">
		<div class="col-12">
<?php do_action( 'woocommerce_shop_loop_item_title' ); ?>

test
		</div>
	</div>
</div>
</div>
<?php endwhile;	endif; ?>
<?php rewind_posts(); ?>
