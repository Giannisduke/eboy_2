<section class="loukia_posts">
<?php if ( $the_query->have_posts() ) : while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

	<h1><?php the_title() ;?></h1>
	<?php the_excerpt(); ?>

<?php endwhile; else: ?>

	<p>Sorry, there are no posts to display</p>

<?php endif; ?>
</section>
