<?php
/**
 * Rec bootstrap
 * Remove and tweaks WordPress functionalities
 *
 * @package Rec
 * @since Rec 1.0
 */


function rec_remove_default_objects_and_taxonomies() {
	remove_post_type( 'post' );
	remove_taxonomy( 'post_tag' );
	remove_taxonomy( 'category' );
	
}

add_action( 'init', 'rec_remove_default_objects_and_taxonomies', 10 ); 