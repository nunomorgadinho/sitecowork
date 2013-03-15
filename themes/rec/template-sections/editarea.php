<?php
/**
 * This is the template for displaying the editing section.
 *
 * @package rec
 * @since rec 1.0
 */

?>

<!-- Editing Section -->


<section class="edit-wrapper">
	<section class="edit-container">
		<div class="tabs">
			<nav>
				<ul class="edit-menu">
					<li class="edit-item"><a href="#tab-1">My Profile</a></li>
					<li class="edit-item"><a href="#tab-2">My Projects</a></li>
					<li class="edit-item"><a href="#tab-3">My Contests</a></li>
					<li class="edit-item"><a href="#tab-4">My Jobs</a></li>
				<aside class="messages-close">
				<a href="#">X</a>
				</aside>
				</ul>

			</nav>

<!-- Secção de Edição do Perfil -->
			<section id="tab-1" class="edit-profile">
				<div class="image-change">
					<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/image-profile.png"/>
					<button class="upload-image">Change...</button>
				</div>
				<form class="edit-profile-form">
					<div class="edit-main">
						<div class="edit-formgroup"><input class="edit-name" type="text"></div>
						<div class="edit-formgroup"><input class="edit-role" type="text"></div>
						<div class="edit-formgroup"><textarea rows="4"></textarea></div>
					</div>
					<div class="edit-url">

					<div class="edit-firstgroup">		
							<div class="edit-formgroup"><img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/facebook-link.png"/><input type="text"></div>
							<div class="edit-formgroup"><img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/twitter-link.png"/><input type="text"></div>
							<div class="edit-formgroup"><img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/linkedin-link.png"/><input type="text"></div>
							<div class="edit-formgroup"><img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/website-link.png"/><input type="text"></div>
					</div>
					<div class="edit-secondgroup">
							<div class="edit-formgroup"><img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/behance-link.png"/><input type="text"></div>
							<div class="edit-formgroup"><img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/youtube-link.png"/><input type="text"></div>
							<div class="edit-formgroup"><img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/vimeo-link.png"/><input type="text"></div>
							<div class="edit-formgroup"><img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/mail-link.png"/><input type="text"></div>
					</div>
					</div>
				</form>
			</section>

<!-- Fim da Secção de Edição do Perfil -->

<!-- Secção de Edição de Projectos -->			

			<section id="tab-2" class="edit-projects">
				<?php get_template_part( 'content', 'editvideo' ); ?>
				<?php get_template_part( 'content', 'editvideo' ); ?>
				<?php get_template_part( 'content', 'editvideo' ); ?>
				<?php get_template_part( 'content', 'editvideo' ); ?>
			</section>

			<section id="tab-3">Esta é a tab3!</section>
			<section id="tab-4">Esta é a tab4!</section>	
		</div>

				<section class="load-more">
					<button class="load-button"><?php _e( 'Save changes', 'rec' ); ?></button>
				</section>
	</section>
	
</section>