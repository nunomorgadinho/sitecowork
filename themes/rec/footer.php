<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package rec
 * @since rec 1.0
 */
?>

	</div><!-- #main .site-main -->
<section class="footer-group">		
<!-- Supporters -->

	<section class="footer-firstlevel">
		<header>
			<h1>
				<?php _e('Our supporters','rec'); ?>
			</h1>
		</header>
		<div class="separator-footer"></div>
		<a href="#"><img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/spain-logo.png"/></a>
	</section>

<!-- Main Footer -->

<section class="footer-main">
	<footer id="colophon" class="site-footer" role="contentinfo">
		
<!-- About us -->
		<section class="footer-about">
			<header>
				<h1><?php _e('About us', 'rec'); ?></h1>
			</header>
				<p><?php _e('Donec in tortor ligula, id interdum elit. Phasellus non iaculis dolor. Morbi placerat mi ac dolor condimentum placerat. 
					Vivamus eget odio mauris. Suspendisse potenti. Duis et dui massa. Nunc id urna massa.','rec'); ?>
				</p>
		</section>
<!-- Social Area -->
		<section class="footer-social">
			<header>
				<h1><?php _e('Talk to us', 'rec'); ?></h1>
			</header>
				<aside>
					<a href"#">
						<p>
							<span class="footer-facebook"><img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/facebook-footer.png"/></span>
							<?php _e('Like us on facebook','rec'); ?>
						</p>
					</a>
				</aside>
				<aside>
					<a href"#">
						<p>
							<span class="footer-mail"><img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/message-footer.png"/></span>
							<?php _e('We love emails','rec'); ?>
						</p>
					</a>
				</aside>
		</section>
<!-- Useful Information -->
		<section class="footer-info">
			<header>
				<h1><?php _e('Useful Information','rec'); ?></h1>
			</header>
				<aside>
					<a href"#">
						<p>
							<span class="footer-terms"><img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/terms-footer.png"/></span>
							<?php _e('Terms of use','rec'); ?>
						</p>
					</a>
				</aside>
			</section>
<!-- Search -->
			<section class="footer-search">
				<header>
					<h1><?php _e( 'Search', 'rec' ); ?></h1>
				</header>
				<aside class="search-element-footer">
					<?php dynamic_sidebar('Search'); ?>
				</aside>
			</section>

		</section>
	</footer><!-- #colophon .site-footer -->
</section>

</section>
</div><!-- #page .hfeed .site -->

<?php wp_footer(); ?>

</body>
</html>