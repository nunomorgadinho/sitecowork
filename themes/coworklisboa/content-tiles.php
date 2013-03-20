<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Rec
 * @since Rec 1.0
 */
?>

<div class="global_wrapper">


<div class="image_container_left">
		<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/thumb.jpg"/>
		<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/thumb.jpg"/>
		<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/thumb.jpg"/>
		<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/thumb.jpg"/>
</div>



<div class="global_container">
			
						<!-- Logotipo -->
					<div class="logo_container">
						<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/cwl_bw.png"/>
				    </div>

		   			 <span class="clear"></span>


					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						
						<article class="box-middle">

							<header class="entry-header">
								<h1 class="entry-title"><?php the_title(); ?></h1>
							</header><!-- .entry-header -->

							<div class="entry-content">
								Olá bla bla bla!
								<?php the_content(); ?>
							</div><!-- .entry-content -->

						</article>

					</article><!-- #post-<?php the_ID(); ?> -->


</div>



<div class="image_container_right">
		<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/video-thumb.jpg"/>
</div>

</div>