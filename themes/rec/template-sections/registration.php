<?php
/**
 * This is the template for displaying the registration wrapper.
 *
 * @package rec
 * @since rec 1.0
 */

?>

<section class="subscription-wrapper">

	<!-- First level registration -->
	<section class="subscription-container">
		<header>
			<h1>
				<?php _e('Original video content created by awesome producers','rec'); ?>
			</h1>
		</header>
		<button class="subscribe-button"><?php _e('Register Now','rec'); ?></button>
		<button class="facebook-button"><?php _e('or via facebook','rec'); ?></button>
	</section>
	
	<!-- Second level registration -->
	<section class="subscription-container-form">
		<header>
			<h1>
				<?php _e('Registration','rec'); ?>
			</h1>
		</header>
		<form>
			<div class="form-section">
				<div class="form-group">
					<label><?php _e('Username','rec'); ?></label><input type="text">
				</div>
				<div class="form-group">
					<label><?php _e('E-mail','rec'); ?></label><input type="email">
				</div>
				<div class="form-group">
					<label><?php _e('Password','rec'); ?></label><input type="password">
				</div>
			</div>
			<div class="form-section">
				<div class="form-group location-form">
					<label><?php _e('Country','rec'); ?></label><input type="text">
					<label><?php _e('City','rec'); ?></label><input type="text">
				</div>
				<div class="form-group skills-form">
					<label><?php _e('Skills','rec'); ?></label><input type="text">
				</div>
				<div class="form-group">
					<label><?php _e('I accept the Terms and Conditions','rec'); ?></label><input type="checkbox">
				</div>
				<div class="submit-button-container">
				<button class="submit-button"><?php _e('Submit','rec'); ?></button>
				</div>
			</div>
		</form>
	</section>

	<!-- Third level registration -->
	<section class="subscription-container-notice">
		<header>
			<h1><?php _e('Thanks for registering!','rec'); ?></h1>
			<h2><?php _e('Check your e-mail for login information.','rec'); ?></h2>
		</header>
	</section>

</section>