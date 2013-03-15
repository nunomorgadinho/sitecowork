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

		<div class="tile-appreciation">
			<span class="appreciation-box"></span>
			<h4 class="appreciation-number"><?php the_appreciation_count(); ?></h4>
		</div>

		<p class="tile-author"><?php _e( 'by', 'rec' ); ?> <?php the_author_posts_link(); ?></p>
		<p class="tile-date"><?php echo get_the_date(); ?></p>

		</div>
		<div class="tile-social">
			<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/tile-mail.png"/>
			<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/tile-facebook.png"/>
			<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/tile-twitter.png"/>
		</div>
	</div>
</article>

<!-- end of Single Tile -->