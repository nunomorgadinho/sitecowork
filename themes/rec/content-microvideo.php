<?php
/**
 * Mainly used to display related videos
 * 
 * @package rec
 * @since rec 1.0
 */
?>

<article class="sidebar-video">
	<span class="sidebar-overimage"></span>
	<h1><a href="<?php the_permalink(); ?>">
		<?php the_title(); ?>
	</a></h1>
	<?php the_post_thumbnail( array( 250, 84 ) ); ?>
</article>