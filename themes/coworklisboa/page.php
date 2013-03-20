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
 */

get_header(); ?>

		<div id="primary" class="content-area">
			<div id="content" class="site-content" role="main">

				
				<div class="global_container grey-fill">

					<a href=""><?php _e('This is a beta version','rec'); ?></a>

		    	</div>

				<div class="global_container">
			
						<!-- Logotipo -->
					<div class="logo_container">
						<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/cwl_bw.png"/>
				    </div>

		   			 <span class="clear"></span>

						<?php while ( have_posts() ) : the_post(); ?>

							<?php get_template_part( 'content', 'page' ); ?>

						<?php endwhile; // end of the loop. ?>
				
				
				</div>

				<div class="global_container red-fill">
					<a href="#"><?php _e('Did we tell you this is beta?','rec'); ?></a>
		    	</div>

			</div><!-- #content .site-content -->
		</div><!-- #primary .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>