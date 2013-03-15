<?php
/**
 * The Template for displaying all single posts.
 *
 * @package rec
 * @since rec 1.0
 * @Template name: Single Job
 */

get_header(); ?>

		<div id="primary" class="content-area">
			<div id="content" class="site-content" role="main">

					<section class="single-job-container">
						<h1><?php _e('NoBrand Opening Video','rec'); ?></h1>
						<a href="#"><div class="single-image-container"><img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/contest-image.png"/></div></a>
						
						<section class="single-info">
							<div class="single-userinfo">
							<a href="#"><img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/user-thumb.png"/></a>
							<p><span>Posted by </span><a href="#">Dylan Morais</a></p>
							<p>13, June 2012</p>
							</div>
							<div class="single-details">
								<h1>You win <span>1.000€</span></h1>
								<h1>Apply until <span>2, June 2013</span></h1>
							</div>
							<button class="contact-button">Apply to Job</button>
						</section>

						<section class="single-description">
							<p>You think water moves fast? You should see ice. It moves like it has a mind. Like it knows it killed the world once and got a taste for murder. After the avalanche, it took us a week to climb out. 
							Now, I don't know exactly when we turned on each other, but I know that seven of us survived the slide... and only five made it out. Now we took an oath, that I'm breaking now. 
							We said we'd say it was the snow that killed the other two, but it wasn't. Nature is lethal but it doesn't hold a candle to man.</p>
						</section>

					</section>

			</div><!-- #content .site-content -->
		</div><!-- #primary .content-area -->

<?php get_footer(); ?>