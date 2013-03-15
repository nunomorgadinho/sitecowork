<?php

/**
 * Some libraries to detect SPAM
 */
 

if ( ! function_exists( 'is_post_spam' ) ) :
/**
 * Checks if the provided post is SPAM
 * If it is marks as spam if second parameter in 'mark'
 * Note: It does not delete a post, just mark it as SPAM
 * 
 * @since 1.1
 * 
 * @param int|object $post Post object or post ID.
 * @param string $action 'mark' or null. Default is null.
 * 
 * @return bool|object Return bool true if was marked as SPAM, false if otherwise of wp_error if $post_id not found
 * 
 */
function is_post_spam( $post, $action = null ) {
	global $akismet_api_host, $akismet_api_port;
	
	if ( is_int( $post ) )
		$post = get_post( $post );
	
	// Check for post found
	if ( ! is_object( $post ) )
		return new WP_Error( 'no_post', __( 'No post found', '_s' ) );
	
	
	// Check wheter Akismet plugin is actived
	if ( ! function_exists( 'akismet_http_post' ) )
		return new WP_Error( 'no_akismet', __( 'No Akismet or not installed', '_s' ) );
	
	// Check if there is any Akismet API key
	if ( ! $wpcom_api_key = get_option( 'wordpress_api_key' ) )
		return new WP_Error( 'no_wpcom_api_key', __( 'No Akismet API key', '_s' ) );
	
	
	
	// Get and join the needed data
	$content['user_ip'] = preg_replace( '/[^0-9., ]/', '', $_SERVER['REMOTE_ADDR'] );
	$content['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
	$content['referrer'] = $_SERVER['HTTP_REFERER'];
	$content['blog'] = site_url();
	$content['comment_type'] = 'registration';
	$content['comment_author'] = get_the_author_meta( 'display_name', (int) $post->post_author );
	$content['comment_author_email'] = get_the_author_meta( 'user_email', (int) $post->post_author );
	$content['comment_content'] = $post->post_content;
	
	//var_dump( $content );
	
	// Build our query
	$query = '';
	foreach ( $content as $key => $data )
		if ( ! empty( $data ) ) 
			$query .= $key . '=' . urlencode( stripslashes( $data ) ) . '&';
	
	//var_dump( $query );
	
	// Send Data/Get response from the Akismet servers
	$response = akismet_http_post( $query, $akismet_api_host, '/1.1/comment-check', $akismet_api_port );
	
	//var_dump( $response );
	
	if ( empty( $response ) || ! is_array( $response ) )
		return new WP_Error( 'no_response', __( 'It seems that Akismet servers are really busy...', '_s' ) );
	
	
	if ( 'true' == $response[1] ) {
		if ( 'mark' == $action )
			wp_mark_post_spam( $post );
			
		update_option( 'akismet_spam_count', get_option( 'akismet_spam_count' ) + 1 );
		return true;
		
	}
	
	return false;

}
endif; //is_post_spam

if ( ! function_exists( 'wp_mark_post_spam' ) ) :
/**
 * Marks a post with the SPAM post status
 * 
 * @since 1.1
 * 
 * @param int|object $post Post object or post ID.
 * 
 * @return bool|object True if okay. wp_error is post not found.
 * 
 */
function wp_mark_post_spam( $post ) {
	
	if ( is_int( $post ) )
		$post = get_post( $post );
	
	// Check for post found
	if ( ! is_object( $post ) )
		return new WP_Error( 'no_post', __( 'No post found', '_s' ) );
	
	// Updates post
	$result = wp_update_post( 
		array( 
			'ID' => $post->ID, 
			'post_status' => 'spam'
	
		)
	
	);
	
	// If okay, fires the post transition 
	if ( $result )
		wp_transition_post_status( 'spam', $post->post_status, $post );
		
	return $result;

}
endif; //wp_mark_post_spam



if ( ! function_exists( 'is_user_spam' ) ) :
/**
 * Checks if the provided user data is SPAM
 * If it is marks as spam if second parameter in 'mark'
 * Note: It does not delete a post, just mark it as SPAM
 * 
 * @since 1.1
 * 
 * @param int|object $userdata User object or post ID.
 * @param string $action 'mark' or null. Default is null.
 * 
 * @return bool|object Return bool true if was marked as SPAM, false if otherwise of wp_error if $post_id not found
 * 
 */
function is_user_spam( $userdata, $action = null ) {
	global $akismet_api_host, $akismet_api_port;
	
	if ( is_int( $userdata ) )
		$userdata = get_user_by( 'id', $userdata );
	
	// Check for post found
	if ( ! is_object( $userdata ) )
		return new WP_Error( 'no_user', __( 'No user found', '_s' ) );
	
	
	// Check wheter Akismet plugin is actived
	if ( ! function_exists( 'akismet_http_post' ) )
		return new WP_Error( 'no_akismet', __( 'No Akismet or not installed', '_s' ) );
	
	// Check if there is any Akismet API key
	if ( ! $wpcom_api_key = get_option( 'wordpress_api_key' ) )
		return new WP_Error( 'no_wpcom_api_key', __( 'No Akismet API key', '_s' ) );
	
	
	
	// Get and join the needed data
	$content['user_ip'] = preg_replace( '/[^0-9., ]/', '', $_SERVER['REMOTE_ADDR'] );
	$content['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
	$content['referrer'] = $_SERVER['HTTP_REFERER'];
	$content['blog'] = site_url();
	$content['comment_type'] = 'registration';
	$content['comment_author'] = $userdata->display_name;
	$content['comment_author_email'] = $userdata->user_email;
	$content['comment_content'] = '';
	
	//var_dump( $content );
	
	// Build our query
	$query = '';
	foreach ( $content as $key => $data )
		if ( ! empty( $data ) ) 
			$query .= $key . '=' . urlencode( stripslashes( $data ) ) . '&';
	
	//var_dump( $query );
	
	// Send Data/Get response from the Akismet servers
	$response = akismet_http_post( $query, $akismet_api_host, '/1.1/comment-check', $akismet_api_port );
	
	//var_dump( $response );
	
	if ( empty( $response ) || ! is_array( $response ) )
		return new WP_Error( 'no_response', __( 'It seems that Akismet servers are really busy...', '_s' ) );
	
	
	if ( 'true' == $response[1] ) {
		if ( 'mark' == $action )
			wp_mark_user_spam( $post );
			
		update_option( 'akismet_spam_count', get_option( 'akismet_spam_count' ) + 1 );
		return true;
		
	}
	
	return false;

}
endif; //is_post_spam

if ( ! function_exists( 'wp_mark_user_spam' ) ) :
/**
 * Marks a post with the SPAM user status
 * 
 * @since 1.1
 * 
 * @param int|object $user Post object or post ID.
 * 
 * @return bool|object True if okay. wp_error is post not found.
 * 
 */
function wp_mark_user_spam( $user ) {
	
	if ( is_int( $user ) )
		$user = get_user_by( 'id', $user );
	
	// Check for post found
	if ( ! is_object( $user ) )
		return new WP_Error( 'no_post', __( 'No user found', '_s' ) );
	
	// Updates post
	$result = wp_update_post( 
		array( 
			'ID' => $user->ID, 
			'post_status' => 'spam'
	
		)
	
	);
	
	// If okay, fires the post transition 
	if ( $result )
		wp_transition_post_status( 'spam', $post->post_status, $post );
		
	return $result;

}
endif; //wp_mark_post_spam
