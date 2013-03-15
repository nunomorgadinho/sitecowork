<?php

/**
 * Ajax functions
 */
function reclanding_ajax_add_new_user_profile() {
	
	try {
		$name  = isset( $_REQUEST['name'] ) ? strip_tags( $_REQUEST['name'] ) : '';
		$email = isset( $_REQUEST['email'] ) ? strip_tags( $_REQUEST['email'] ) : '';
		$roles = isset( $_REQUEST['role'] ) ? strip_tags( $_REQUEST['role'] ) : '';
		$link[] = isset( $_REQUEST['firsturl'] ) ? esc_url( $_REQUEST['firsturl'] ) : '';
		$link[] = isset( $_REQUEST['secondurl'] ) ? esc_url( $_REQUEST['secondurl'] ) : '';
		$link[] = isset( $_REQUEST['thirdurl'] ) ? esc_url( $_REQUEST['thirdurl'] ) : '';
		
		if ( '' == $name )
			throw new Exception( __( 'Name cannot be empty.', 'rec' ), 1 );
			
		if ( ! is_email( $email ) )
			throw new Exception( __( 'Provide a valid email.', 'rec' ), 1 );
		
		if ( email_exists( $email ) )
			throw new Exception( __( 'This email is already registered.', 'rec' ), 1 );
		
		if ( '' == $roles )
			throw new Exception( __( 'Provide, at least, one role.', 'rec' ), 1 );
		
		
		$userdata = array(
			'user_pass' => wp_generate_password( 8, false ),
			'user_login' => sanitize_user( $email ),
			'user_email' => $email,
			'display_name' => $name,
			'first_name' => mb_substr( $name, 0, strpos( $name, ' ' ) ),
			'last_name' => mb_substr( $name, strpos( $name, ' ' )+1 ),
			'role' => 'user',
			'primary_blog' => get_current_blog_id(),
			'show_admin_bar_front' => 'false',
		);
		
		$user_id = wp_insert_user( $userdata );
		
		$roles = explode( ', ', $roles );
		wp_set_object_terms( $user_id, $roles, 'user_role' );
		
		foreach ( $link as $url ) {
			add_video_by_url( $url, $user_id );
		}
		
		throw new Exception( __( 'Your submition is done.', 'rec' ), 0 );
		
	} catch (Exception $e) {
		echo json_encode( array( 'message' => $e->getMessage(), 'error' => $e->getCode() ) );
		
	}
	die();
	
}

add_action( 'wp_ajax_add_new_user_profile', 'reclanding_ajax_add_new_user_profile' );
add_action( 'wp_ajax_nopriv_add_new_user_profile', 'reclanding_ajax_add_new_user_profile' );


function reclanding_ajax_check_for_email() {
	header('Content-type: application/json');
	
	if ( email_exists( strip_tags( $_REQUEST['email'] ) ) )
		echo json_encode( false );
	else
		echo json_encode( true );
	die();
}

add_action( 'wp_ajax_check_for_email', 'reclanding_ajax_check_for_email' );
add_action( 'wp_ajax_nopriv_check_for_email', 'reclanding_ajax_check_for_email' );

function reclanding_ajax_validate_url() {
	header('Content-type: application/json');
	
	if ( isset( $_REQUEST['firsturl'] ) ) {
		$url = strip_tags( $_REQUEST['firsturl'] );
		
	} elseif ( isset( $_REQUEST['secondurl'] ) ) {
		$url = strip_tags( $_REQUEST['secondurl'] );
		
	} elseif ( isset( $_REQUEST['thirdurl'] ) ) {
		$url = strip_tags( $_REQUEST['thirdurl'] );
		
	}
	
	if ( false === strpos( $url, 'youtube.com/watch?v=' ) && false === strpos( $url, 'youtu.be/' ) && false === strpos( $url, 'vimeo.com/' ) )
		echo json_encode( false );
	else
		echo json_encode( true );
	
	die();
	
}

add_action( 'wp_ajax_validate_url', 'reclanding_ajax_validate_url' );
add_action( 'wp_ajax_nopriv_validate_url', 'reclanding_ajax_validate_url' );


function reclanding_ajax_check_for_name_length() {
	header('Content-type: application/json');
	
	$name = trim( strip_tags( $_REQUEST['name'] ) );
	
	if ( strlen( $name ) > 3 &&  false !== strpos( $name, 0x20 ) )
		echo json_encode( true );
	else
		echo json_encode( false );
	
	die();
	
}

add_action( 'wp_ajax_check_for_name_length', 'reclanding_ajax_check_for_name_length' );
add_action( 'wp_ajax_nopriv_check_for_name_length', 'reclanding_ajax_check_for_name_length' );


function reclanding_ajax_get_video_background() {
	header('Content-type: application/json');
	$width  = $_REQUEST['width'];
	$height = $_REQUEST['height'];
	
	$url = get_background_video()->url;
	
	if ( is_youtube_video( $url ) )
		$streams = get_youtube_streams( $url );
	
	
	$html = sprintf( '
	<div class="video-viewport">
		<video preload="auto" controls="false" autoplay="true" muted="true" loop="loop" canplay="false" width="%1$s" height="%2$s">
			<source src="%3$s" type="video/webm">
			<source src="%4$s" type="video/mp4">
			<source src="%5$s" type="video/3gp">
		</video>
	</div>
	', $width, $height, $streams['high']['WEBM'],$streams['high']['MP4'], $streams['high']['3GP'] );
	
	echo json_encode( array(
		'html' => $html
		)
	);
	
	die();
	
}

add_action( 'wp_ajax_get_video_background', 'reclanding_ajax_get_video_background' );
add_action( 'wp_ajax_nopriv_get_video_background', 'reclanding_ajax_get_video_background' );
