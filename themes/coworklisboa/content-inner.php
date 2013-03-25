<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Rec
 * @since Rec 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<article class="box-middle inner-form">

	<p>You must be a resident coworker of any Coworklisboa’s workplace to use this feature. If so, log in with the email you provided to us and 
		choose a password. You’ll receive a confirmation email in your inbox. Use the link included and come back here.</p>

	<form>
		<input type="email" name="email" placeholder="email">
		<input type="password" name="pass" placeholder="password" >
		<input type="password" name="pass" placeholder="repeat password">
	</form>

	<p class="navigate"><a id="" class="navigate-link red" href="#"><?php _e('> send','cowork');?></a></p>	

	</article>

</article><!-- #post-<?php the_ID(); ?> -->
