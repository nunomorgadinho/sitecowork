<?php
/**
 * @package rec
 * @since rec 1.0
 */
?>

<!-- Single Tile -->

<article class="single-tile">
	<img class="project-thumb" src="<?php echo get_post_thumbnail_url( array( 520, 192 ) ); ?>"/>
	<div class="tile-info">
		<h1>
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h1>
		<div class="tile-description">
		<div class="tile-descriptiontext">
			<?php the_excerpt(); ?>
		</div>

		<div class="tile-appreciation">
			<span class="appreciation-box"></span>
			<h4 class="appreciation-number"><?php the_appreciation_count(); ?></h4>
		</div>

		<?php echo get_avatar( get_the_author_meta('ID'), 50 ); ?>
		<p class="tile-author"><?php the_author_posts_link(); ?></p>
		<p class="tile-date"><?php echo get_the_date(); ?></p>

		<div class="tile-social">
			<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/tile-mail.png"/>
			<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/tile-facebook.png"/>
			<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/tile-twitter.png"/>
		</div>
		</div>
	</div>
</article>

<!-- end of Single Tile -->