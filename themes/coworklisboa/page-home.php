<?php
/*
Template Name: HomePage
*/
?>
<?php get_header(); ?>

		<div id="primary" class="content-area">
			<div id="content" class="site-content" role="main">
			
			<div class="global_container">
			
				<!-- Logotipo -->
			<div class="logo_container">
				<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/cwl_bw.png"/>
		    </div>

   			 <span class="clear"></span>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'home' ); ?>

				<?php endwhile; // end of the loop. ?>
				
				
			</div>	

			</div><!-- #content .site-content -->
		</div><!-- #primary .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?> 