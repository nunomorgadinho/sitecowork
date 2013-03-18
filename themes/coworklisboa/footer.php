<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Rec
 * @since Rec 1.0
 */
?>

	</div><!-- #main .site-main -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<?php do_action( 'rec_credits' ); ?>
			&copy <?php echo date('Y'); ?> <?php _e('CoworkLisboa','cowork');?>
		</div><!-- .site-info -->
	</footer><!-- #colophon .site-footer -->
</div><!-- #page .hfeed .site -->


<?php wp_footer(); ?>

</body>
</html>