<?php
/**
 * @package rec
 * @since rec 1.0
 */
?>

<!-- Single Tile -->

<article class="single-smalltile">
	<img class="project-thumb" src="<?php echo get_post_thumbnail_url( array( 520, 192 ) ); ?>" width="250" height="92" />
	<div class="tile-info">
		<h1>
			<?php the_title(); ?>
		</h1>
		<div class="tile-description">
		<p class="tile-date"><?php echo get_the_date(); ?></p>
		<a class="edit-pen" href="#"><img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/edit-pen.png"/></a>

		</div>
	</div>
</article>

<!-- end of Single Tile -->