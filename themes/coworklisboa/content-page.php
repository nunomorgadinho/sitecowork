<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Rec
 * @since Rec 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<article class="box-middle">

	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

	</article>

</article><!-- #post-<?php the_ID(); ?> -->
