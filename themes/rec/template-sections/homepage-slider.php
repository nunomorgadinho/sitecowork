<?php
/**
 * This is the template for displaying the homepage slider.
 *
 * @package rec
 * @since rec 1.0
 */

?>

<?php if ( have_slides( array( 'category' => 'homepage' ) ) ) : ?>

<!-- Slider Section -->
<section class="slider-home">
        <img class="claq" src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/claquete.png"/>
<div id="banner-fade">
    <ul class="bjqs">
    	
    	<?php while ( have_slides() ) : the_slide(); ?>
    		
        <li>
        	<section>
	        	<span class="slideroverimage"></span>
	        	<?php the_post_thumbnail( array( 700, 272 ) ); ?>
	        	<div class="slider-textcontainer">
		        	<h1><?php the_title(); ?></h1>
		        	<h2><?php the_excerpt(); ?></h2>
		        	<a href="<?php the_permalink(); ?>"><p><?php _e( 'Read More', 'rec' ); ?></p></a>
		        </div>
        	</section>
        </li>
        
        <?php endwhile; ?>
        
    </ul>
</div>
</section>

<?php endif; ?>