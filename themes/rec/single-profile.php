<?php
/**
 * The Template for displaying all single posts.
 *
 * @package rec
 * @since rec 1.0
 */

get_header(); ?>

		<div id="primary" class="content-area">
			<div id="content" class="site-content" role="main">
				
				<?php if ( have_users() ) : the_user(); ?>
				
				<div class="row-container">
					<section class="profile-details">
						<div class="image-profile-container">
						<div class="image-profile">
							<?php echo get_avatar( $user->ID, 240 ); ?>
						</div>
						</div>
						<header class="profile-basics">
							<h1><?php the_display_name(); ?>
							<button class="contact-button"><?php _e('Send a message','rec'); ?></button>
							</h1>
							<h2><?php the_user_category(); ?></h2>
						</header>
						<article>
							<p class="bio-text">
								<?php the_user_biography(); ?>
							</p>
							<aside>
								<section class="profile-counters">
									<div class="favourite-counter"><h1>16</h1><p><?php _e( 'Favourites', 'rec' ); ?></p></div>
									<!--<div class="contest-counter"><h1>20</h1><p>Contests</p></div>-->
									<div class="project-counter"><h1><?php echo get_user_counts()->projects; ?></h1><p><?php _e( 'Projects', 'rec' ); ?></p></div>
								</section>
							<div class="links-section">	
								<section class="profile-links">
									<a href="<?php echo esc_url( get_user_meta( $user->ID, 'facebook', true ) ); ?>"><div class="facebook-link"></div>
										<?php echo filter_url( get_user_meta( $user->ID, 'facebook', true ) ); ?>
									</a>
								</section>

								<section class="profile-links">
									<a href="<?php echo esc_url( get_user_meta( $user->ID, 'twitter', true ) ); ?>"><div class="twitter-link"></div>
										<?php echo filter_url( get_user_meta( $user->ID, 'twitter', true ) ); ?>
									</a>
								</section>
							</div>
							</aside>
						</article>
					</section>
				</div>

				<div class="row-container">
				
				<!-- Tiles Section -->
				
				<?php if ( have_videos( array( 'author' => $user->ID ) ) ) : ?>
				
				<section class="tiles-wrapper">
					<header>
						<h1 class="general-title"><?php _e( 'My Projects', 'rec' ); ?></h1>
						<div class="separator-title"></div>
					</header>		
					
					<?php while ( have_videos() ) : the_video();
						// Get the content video template part
						get_template_part( 'content', 'video' );
					
					endwhile; ?>
		
				</section>
				
				<?php endif; ?>

						
				</div>
				<section class="load-more">
					<button class="load-button"><?php _e( 'Load more projects', 'rec' ); ?></button>
				</section>

				<div class="row-container">
				
				<!-- Tiles Section -->
				
				<?php if ( have_videos( array( 'author' => $user->ID ) ) ) : ?>
				
				<section class="tiles-wrapper">
					<header>
						<h1 class="general-title"><?php _e( 'My Favourites', 'rec' ); ?></h1>
						<div class="separator-title"></div>
					</header>		
					
					<?php while ( have_videos() ) : the_video();
						// Get the content video template part
						get_template_part( 'content', 'minivideo' );
					
					endwhile; ?>
		
				</section>
						
				</div>
				<section class="load-more">
					<button class="load-button"><?php _e( 'Load more favourites', 'rec' ); ?></button>
				</section>
				
				<?php endif; ?>
				
				<?php endif; ?>

			</div><!-- #content .site-content -->
		</div><!-- #primary .content-area -->

<?php get_footer(); ?>