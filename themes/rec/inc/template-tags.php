<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Rec
 * @since Rec 1.0
 */


/**
 * Template tags for Videos (aka Projects)
 */
function have_videos( $query = '' ) {
	global $_video_post_type;
	return $_video_post_type->have_videos( $query );
	
}

function have_related_videos( $query = '' ) {
	global $_video_post_type;
	return $_video_post_type->have_related_videos( $query );
	
}

function the_video() {
	global $_video_post_type;
	return $_video_post_type->the_video();
	
}

function get_video_url( $post = '', $clickable = '' ) {
	if ( '' == $post )
		global $post;
	
	$url = get_post_meta( $post->ID, '_video_buget', true );
	
	return $clickable ? make_clickable( esc_url( $url ) ) : $url ;
	
}

function the_video_url() {
	echo get_video_url();
}

function get_video_budget( $post = '' ) {
	if ( '' == $post )
		global $post;
	
	return number_format_i18n( get_post_meta( $post->ID, '_video_budget', true ) ) . '&euro;';
}

function the_video_budget() {
	echo get_video_budget();
}

function get_video_production( $post = '' ) {
	if ( '' == $post )
		global $post;
	
	return get_post_meta( $post->ID, '_video_production', true );
}

function the_video_production() {
	echo get_video_production();
}


function get_appreciation_count( $post = '' ) {
	if ( '' == $post )
		global $post;
	
	return get_post_meta( $post->ID, '_video_likecount', true );
}

function the_appreciation_count() {
	echo get_appreciation_count();
}



/**
 * User Template Tags
 */



function rec_user_profile_uri( $user_id = '' ) {
	if ( '' == $user_id )
		$user_id = get_current_user_id();
	
	return '';
	
}
