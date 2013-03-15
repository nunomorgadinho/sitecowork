<?php
/**
 * The Template for displaying all single posts.
 *
 * @package rec
 * @since rec 1.0
 * @Template name: Single Video
 */

get_header(); ?>

		<div id="primary" class="content-area">
			<div id="content" class="site-content" role="main">
				
			<?php if ( have_posts() ) : the_post(); ?>

			<section class="video-wrapper">
				<section class="video-container">
					<div class="video-display">
						<?php the_post_thumbnail( array( 790, 444 ) ); ?>
					</div>
					<section class="video-meta">
						<h1><?php the_title(); ?></h1>
						<div><?php the_content(); ?></div>
						<div class="tile-appreciation">
							<h4 class="appreciation-number"><?php the_appreciation_count(); ?></h4>
							<span class="appreciation-box"></span>
						</div>
						<p class="tile-date"><?php the_time( get_option( 'date_format' ) ); ?></p>
						<div class="tile-social">
							<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/tile-mail.png"/>
							<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/tile-facebook.png"/>
							<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/tile-twitter.png"/>
						</div>
					</section>
				</section>
			</section>

			<section class="video-sidebar">
				<div class="firstsection-sidebar">
					<section class="video-participants">
						<h1 class="general-title video-creator"><?php _e('Creator','rec'); ?></h1>
						<div class="single-participant">
							<?php echo get_avatar( get_the_author_meta('ID'), 50 ); ?>
							<h1><?php _e('Posted by','rec'); ?></h1>
							<h2><?php the_author_posts_link(); ?></h2>
						</div>
					</section>

					<?php if ( have_video_participants() ) : ?>

					<section class="video-participants">
						<h1 class="general-title"><?php _e('Participants','rec'); ?></h1>
						
						<?php while ( have_video_participants() ) : the_video_participant(); ?>
							
						<div class="single-participant">
							<?php echo get_avatar( $user->ID, 50 ); ?>
							<h1><?php echo p2p_get_meta( $user->p2p_id, 'role', true ); ?></h1>
							<h2>
								<a href="<?php echo esc_url( get_author_posts_url( $user->ID ) ); ?>">
									<?php echo get_the_author(); ?>
								</a>
							</h2>
						</div>
						
						<?php endwhile; ?>
						
					</section>
					
					<?php endif; ?>
					
					<div class="separator-sidebar"></div>

					<section class="video-details">
						<div><h1><?php _e('Category','rec'); ?><span><?php the_terms( $post->ID, 'video-category' ); ?></span></h1></div>
						<div><h1><?php _e('Budget','rec'); ?><span><?php the_video_budget(); ?></span></h1></div>
						<div><h1><?php _e('Prodution','rec'); ?><span><?php the_video_production(); ?></span></h1></div>
					</section>
					<div class="separator-sidebar"></div>
				</div>

				<div class="secondsection-sidebar">
					<div class="related-sidebar">
						
						<?php if ( have_related_videos() ) : ?>						
						<h1 class="general-title"><?php _e('Related Videos','rec'); ?></h1>
						
						<?php while ( have_related_videos() ) : the_video(); 
							/** Get the video template part */
							get_template_part( 'content', 'microvideo' ); ?>
						
						<?php endwhile; endif; ?>
						
					</div>
				</div>
			</section>
			
			<?php else : get_template_part( 'no', 'results' ); endif; ?>

			</div><!-- #content .site-content -->
		</div><!-- #primary .content-area -->

<?php get_footer(); ?>