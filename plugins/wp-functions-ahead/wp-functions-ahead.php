<?php
/*
Plugin Name: Functions Ahead
Plugin URI: http://www.vcarvalho.com/
Version: 3.5.2
Text Domain: functions
Domain Path: /languages/
Author: lightningspirit
Author URI: http://profiles.wordpress.org/lightningspirit
Description: A set of proposed new functions and tweaks for future versions of WordPress
License: GPLv2
*/



// Checks if it is accessed from Wordpress' index.php
if ( ! function_exists( 'add_action' ) ) {
	die( 'I\'m just a plugin. I must not do anything when called directly!' );

}



// wp-includes/functions.php

if ( ! function_exists( '__return_empty_string' ) ) :
/**
 * Returns an empty string.
 *
 * Useful for returning an empty string to filters easily.
 *
 * @since 3.6
 * @see __return_empty_string()
 * @return string Empty string
 */
function __return_empty_string() {
	return '';
}
endif;


// wp-includes/template.php

if ( ! function_exists( 'is_paginated' ) ) :
/**
 * Evaluate if actual query is paginated
 * 
 * @since 1.1
 * 
 * @return bool
 */
function is_paginated() {
	global $wp_query;
	
	return ( $wp_query->max_num_pages > 1 );
	
}
endif;


// wp-admin/includes/dashboard.php

if ( ! function_exists( 'remove_dashboard_widget' ) ) :
/**
 * Removes dashboard widget
 * 
 * @since 3.5
 * @uses $wp_meta_boxes 
 *
 * @param string $id 
 * @param string $position
 * @param string $priority
 * @return boolean 
 */
function remove_dashboard_widget( $id, $position = 'normal', $priority = 'core' ) {
	global $wp_meta_boxes;
	
	if ( isset( $wp_meta_boxes['dashboard'][ $position ][ $priority ][ $id ] ) )
		unset( $wp_meta_boxes['dashboard'][ $position ][ $priority ][ $id ] );
	
	do_action( 'remove_dashboard_widget', $id, $position, $priority );
	
	return;
	
}
endif;


// wp-admin/includes/menu.php

if ( ! function_exists( 'wp_rename_admin_menu_item' ) ) :
/**
 * Change admin menu label
 *
 * Useful for renaming items in the admin menu.
 *
 * @since 3.6
 * @param $old_label
 * @param $new_label
 * @return void
 */
function wp_rename_admin_menu_item( $old_label, $new_label ) {
	global $menu;

	if ( ! is_array( $menu ) )
		return;
	
	array_walk( $menu, '_rename_admin_menu_item_walk', compact( 'old_label', 'new_label' ) );

}

function _rename_admin_menu_item_walk( &$item, $key, $labels ) {
	if ( $labels['old_label'] == $item[0] )
		$item[0] = $labels['new_label'];

}
endif;


// wp-includes/post.php

if ( ! function_exists( 'save_post_metas' ) ) :
/**
* Save post metas
* @since 3.6
*/
function save_post_metas( $post_id, $metas ) {
	foreach ( (array) $metas as $field => $value ) {
		$meta = get_post_meta( $post_id, $field, true );
			
		if ( $meta && '' == $value )
			delete_post_meta( $post_id, $field );
			
		elseif ( $meta && $value )
			update_post_meta( $post_id, $field, $value, $meta );
			
		else
			add_post_meta( $post_id, $field, $value );
			
	}

}
endif;


// wp-includes/post.php

if ( ! function_exists( 'unregister_taxonomy_from_object_type' ) ) :
/**
 * Remove an already registered taxonomy from an object type.
 *
 * @package WordPress
 * @subpackage Taxonomy
 * @since 3.5
 * @uses $wp_taxonomies Modifies taxonomy object
 *
 * @param string $taxonomy Name of taxonomy object
 * @param string $object_type Name of the object type
 * @return bool True if successful, false if not
 */
function unregister_taxonomy_from_object_type($taxonomy, $object_type) {

	global $wp_taxonomies;

	if ( !isset($wp_taxonomies[$taxonomy]) )
		return false;

	if ( ! get_post_type_object($object_type) )
		return false;

	foreach (array_keys($wp_taxonomies['category']->object_type) as $array_key) {
		if ($wp_taxonomies['category']->object_type[$array_key] == $object_type) {
			unset ($wp_taxonomies['category']->object_type[$array_key]);
			return true;
		}
	}
	return false;

}
endif;


// wp-includes/post.php

if ( ! function_exists( 'remove_post_type' ) ) :
/**
 * Removes post types
 * 
 * @since 3.5
 * @uses $wp_post_types 
 *
 * @param string $post_type Post type key, must not exceed 20 characters
 * @return boolean 
 */
function remove_post_type( $post_type ) {
	global $wp_post_types, $wp_rewrite, $wp;

	if ( !is_array($wp_post_types) )
		$wp_post_types = array();

	if ( ! post_type_exists( $post_type ) )
		return;

	foreach ( (array) $wp_post_types[$post_type]->taxonomies as $taxonomy ) {
		unregister_taxonomy_from_object_type( $taxonomy, $post_type );
	}
	
	if ( isset( $wp_post_types[ $post_type ] ) )
		unset( $wp_post_types[$post_type] );
	
	do_action( 'remove_post_type', $post_type );
	
	return;
	
}
endif;


// wp-includes/taxonomy.php

if ( ! function_exists( 'remove_taxonomy' ) ) :
/**
 * Removes taxonomies
 * 
 * @since 3.5
 * @uses $wp_taxonomies 
 *
 * @param string $taxonomy 
 * @return boolean 
 */
function remove_taxonomy( $taxonomy ) {
	global $wp_taxonomies, $wp;

	if ( !is_array($wp_taxonomies) )
		$wp_taxonomies = array();

	if ( ! taxonomy_exists( $taxonomy ) )
		return;
	
	
	if ( isset( $wp_taxonomies[$taxonomy]  ) )
		unset( $wp_taxonomies[$taxonomy] );
	
	do_action( 'remove_taxonomy', $taxonomy );
	
	return;
	
}
endif;


// wp-includes/functions.php

if ( ! function_exists( 'time_diference' ) ) :
/**
 * Computes the diference of unix times
 *
 * @since 1.1
 *
 * @param int $time_a The first time
 * @param int $time_b The second time
 * @param string $format Can take array, mysql, timestamp
 *
 * @return mixed
 *
 */
function time_diference( $time_a, $time_b, $format = 'array' ) {
	$timediff = $time_a - $time_b;

	switch ( strtolower( $format ) ) {

		case 'mysql' :
			return date( 'Y-m-d H:i:s', $timediff );
			break;

		case 'timestamp' :
			return abs( $timediff );
			break;

		case 'array' :
			return (object) array(
			'positive' => ( abs( $timediff ) == $timediff ? true : false ),
			'days' => abs( (int) ( $timediff / ( 24*60*60 ) ) ),
			'hours' => date( 'H', $timediff ),
			'minutes' => date( 'i', $timediff ),
			'seconds' => date( 's', $timediff ),
			);
			break;

	}

	return false;

}
endif;


// wp-includes/functions.php

if ( ! function_exists( 'format_time' ) ) :
/**
 * Computes the diference of unix times
 *
 * @since 1.1
 *
 * @param int|string $time The time string/int
 * @param string $return Can take mysql, default (format set in wp-admin), object or any other format
 * @param bool $i18n If returned attend to the current timezone (Default true)
 *
 * @return mixed
 *
 */
function format_time( $time, $format = '', $i18n = true ) {
	
	if ( ! is_numeric( $time ) )
		$time = strtotime( $time );
	
	
	switch ( strtolower( $format ) ) {

		case 'mysql' :
			return $i18n ? date_i18n( 'Y-m-d H:i:s', (int) $time ) : date( 'Y-m-d H:i:s', (int) $time );
			break;

		case 'object' :
			$std = new stdClass();
			
			foreach ( array( 
				'L', 'Y', 'y', 'W', 'F', 'm', 'M', 'n', 't', 'd', 'D', 'j', 'l', 'N', 'S', 'w', 'z',
				'a', 'A', 'B', 'g', 'G', 'h', 'H', 'i', 's', 'u', 'e', 'I', 'O', 'P', 'T', 'Z', 'r', 'U' ) as $ch )
			
				$std->$ch = $i18n ? date_i18n( $ch, (int) $time ) : date( $ch, (int) $time );
				
			return $std;
			break;
			
		case 'default' :
			return $i18n ? date_i18n( get_option( 'date_format' ), (int) $time ) : date( get_option( 'date_format' ), (int) $time );
			break;
			
		default :
			return $i18n ? date_i18n( $format, (int) $time ) : date( $format, (int) $time );
			break;

	}

	return false;

}
endif;


// wp-includes/functions.php

if ( ! function_exists( 'seconds_to_time' ) ) :
/**
 * Check if the file is an image
 *
 * @since 3.6
 */
function seconds_to_time( $seconds, $format = 'H:m:s' ) {
	// extract hours
	$hours = floor($seconds / (60 * 60));
	
	// extract minutes
	$divisor_for_minutes = $seconds % ( 60 * 60 );
	$minutes = floor( $divisor_for_minutes / 60 );
	
	// extract the remaining seconds
	$divisor_for_seconds = $divisor_for_minutes % 60;
	$seconds = ceil( $divisor_for_seconds );
	
	$format = str_replace( 'H', str_pad( $hours, 2, "0", STR_PAD_LEFT ), $format );
	$format = str_replace( 'm', str_pad( $minutes, 2, "0", STR_PAD_LEFT ), $format );
	$format = str_replace( 's', str_pad( $seconds, 2, "0", STR_PAD_LEFT ), $format );
	
	return $format;
	
}
endif;


// wp-includes/functions.php

if ( ! function_exists( 'filter_url' ) ) :
/**
 * Removes http(s):// and www. from a URL
 *
 * @since 3.6
 */
function filter_url( $url ) {
	return preg_replace( "/\s*[^:]+:\/\/(www.)?/", "$2", $url );
	
}
endif;



// wp-includes/functions.php

if ( ! function_exists( 'get_post_thumbnail_url' ) ) :
/**
 * Return post thumbnail url
 *
 * @param string $thumb thumbnail name
 * @param string $default thumbnail default name
 * @return thumbnail url
 */
function get_post_thumbnail_url( $thumbnail = 'thumbnail', $post_id = null, $use_default = true ) {
	global $post;

	if ( ! $post_id )
		$post_id = $post->ID;

	if ( has_post_thumbnail() ) {
		$src = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $thumbnail );
		return $src[0];

	} else {
		return apply_filters( 'get_post_thumbnail_url', get_template_directory_uri() . '/post-thumbnail.png' );

	}

}
endif;


// wp-includes/functions.php

if ( ! function_exists( 'concatenate' ) ) :
/**
 * Return a concatenated string
 *
 * @param integer $limit Limiting excerpt
 * @return excerpt
 */
function concatenate( $excerpt, $limit = false ) {
	
	if ( ! $limit )
		$limit = apply_filters( 'excerpt_length', 30 );


	mb_internal_encoding( 'UTF-8' );

	if ( mb_strlen( $excerpt ) > $limit )
		$dots = apply_filters( 'excerpt_more', '[...]' );


	$excerpt = mb_substr( esc_html( $excerpt ), 0, $limit );

	return $excerpt;

}
endif;


// wp-includes/template.php

if ( ! function_exists( 'get_template_section' ) ) :
/**
 * Include a template section
 *
 * @since 3.6
 */
function get_template_section( $slug, $name = null ) {
	do_action( "get_template_part_{$slug}", $slug, $name );

	$templates = array();
	if ( isset($name) )
		$templates[] = "template-sections/{$slug}-{$name}.php";

	$templates[] = "template-sections/{$slug}.php";

	locate_template($templates, true, false);
	
}
endif;


// wp-includes/media.php

if ( ! function_exists( 'wp_is_file_image' ) ) :
/**
 * Check if the file is an image
 *
 * @since 3.6
 */
function wp_is_file_image( $file ) {
	if ( @getimagesize( $file ) )
		return true;

	return false;

}
endif;


// wp-admin/link-template.php

if ( ! function_exists( 'get_action_post_link' ) ) :
/**
 * Returns a custom action post link action
 * 
 * @since 3.6
 */
function get_action_post_link( $post_id, $action = 'trash' ) {
	$post = get_post( $post_id);
	$post_type_object = get_post_type_object( $post->post_type );

	if ( $action == 'trash' )
		return get_delete_post_link( $post->ID );
	

	$admin_link = admin_url( sprintf( $post_type_object->_edit_link.'&amp;action='.$action, $post->ID ) );

	switch ( strtolower( $action ) ) {
		case 'edit' :
			return $admin_link;

		default :
			return wp_nonce_url( $admin_link, $action.'-'.$post->post_type.'_'.$post->ID );


	}

}
endif;
