<?php
/**
 * Rec rewrite rules
 *
 * @package Rec
 * @since Rec 1.0
 */

function rec_template_redirect() {
	global $wp_query_users, $user;
	
	if ( is_author() ) {
		$wp_query_users = new WP_Query_Users(
			array(
				'user_ID' => get_query_var( 'author' ),
			)
		);
		$wp_query_users->is_author_page = true;
		
		include( get_template_directory() . '/single-profile.php' );
		exit;
		
	}
		
	
}

add_action( 'template_redirect', 'rec_template_redirect' );
