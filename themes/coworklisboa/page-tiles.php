<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Rec
 * @since Rec 1.0
 * @Template Name: Tiles
 */

get_header(); ?>

		<div id="primary" class="content-area">
			<div id="content" class="site-content" role="main">

				
				<div class="global_container grey-fill">
					<a href="#"><?php _e('Want to know all the residents?','rec'); ?></a>
		    	</div>

				

							<?php get_template_part( 'content', 'tiles' ); ?>

						
				<div class="global_container red-fill">
					<a href="#"><?php _e('This wallpaper can be yours','rec'); ?></a>
		    	</div>

			</div><!-- #content .site-content -->
		</div><!-- #primary .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>