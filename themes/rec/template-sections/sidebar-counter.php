<?php
/**
 * This is the template for displaying the homepage sidebar counter.
 *
 * @package rec
 * @since rec 1.0
 */

?>

<!-- Counter Section -->

<section class="counter-sidebar">
	
	<div class="counter-row">
		<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/company-icon.png"/>
		<h1><?php _e('203','rec'); ?></h1>
		<p><?php _e('companies on REC','rec'); ?></p>
	</div>
		<div class="separator-sidebar"></div>

	<div class="counter-row">
		<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/project-icon.png"/>
		<h1><?php _e('203','rec'); ?></h1>
		<p><?php _e('project uploads','rec'); ?></p>
	</div>
		<div class="separator-sidebar"></div>

	<div class="counter-row">
		<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/videomaker-icon.png"/>
		<h1><?php _e('203','rec'); ?></h1>
		<p><?php _e('videomakers registered','rec'); ?></p>
	</div>
		<div class="separator-sidebar"></div>

</section>