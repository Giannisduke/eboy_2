<ul id="carousel" class="slider carousel" data-ride="carousel" data-interval="<?php if ( get_field('slider__speed','option') ) { the_field('slider__speed','option'); } else { echo '4000'; } ?>"><!-- add sub_field to change slide time --!>

	<?php if ( have_rows('slider__item','option') ) : ?> <!-- take slides from Options Page > slider__item -->
	<?php $count = 0 ?>
	<?php while ( have_rows('slider__item','option') ) : the_row(); ?>

		<?php $title = get_sub_field('slider__item--title','option'); ?> <!-- add title -->
		<?php $image = get_sub_field('slider__item--image','option'); ?> <!-- add image -->

		<li class="slider__item item<?php if ( !$count ) { echo ' active'; } ?>">
			<img class="slider__item--image" src="<?php echo $image['url']; ?>" alt="<?php echo $title; ?>" />
			<div class="carousel-caption">
				<h2 class="slider__item--title"><?php echo $title; ?></h2>
			</div>
		</li>

	<?php $count++; ?>
	<?php endwhile; ?>
	<?php endif; ?>

</ul>
