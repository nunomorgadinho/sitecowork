<?php
/**
 * This is the template for displaying the homepage.
 *
 * @package rec
 * @since rec 1.0
 * @Template Name: Homepage
 */

get_header(); ?>

	<?php get_template_section( 'registration' ); ?>
	
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
	
			<div class="row-container">
		
				<?php get_template_section( 'sidebar', 'counter' ); ?>
				
				<?php get_template_section( 'homepage', 'slider' ); ?>
			
			</div>

			<div class="row-container">
				
				<!-- Tiles Section -->
				
				<?php if ( have_videos( array( 'posts_per_page' => 4 ) ) ) : ?>
				
				<section class="tiles-wrapper">
					<header>
						<h1 class="general-title"><?php _e( 'Latest Projects', 'rec' ); ?></h1>
						<div class="separator-title"></div>
					</header>
					
					<?php while ( have_videos() ) : the_video(); 	
						// Get content video part
						get_template_part( 'content', 'video' );
					

					endwhile; ?>
		
				</section>
				
				<?php endif; ?>
						
			</div>
		
			<?php if ( is_user_logged_in() ) : ?>
				
			<section class="load-more">
				<button class="load-button"><?php _e( 'Load more projects', 'rec' ); ?></button>
			</section>
		
			<?php else : ?>
				
			<section class="load-more">
				<button class="load-button"><?php _e( 'Register to load more', 'rec' ); ?></button>
				<button class="facebook-button"><?php _e( 'or enter with facebook', 'rec' ); ?></button>
			</section>
				
			<?php endif; ?>
			

		</div><!-- #content .site-content -->
	</div><!-- #primary .content-area -->

<?php get_footer(); ?>