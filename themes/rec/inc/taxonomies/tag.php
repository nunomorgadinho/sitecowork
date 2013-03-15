<?php

class REC_Tag {
	
	function __construct() {
	    register_taxonomy(
	        'tag',
	        array( 'video', 'job', 'contest' ),
	        array(
	            'labels' => array(
	                'name' => __( 'Tags' ),
	                'singular_name' => __( 'Tag' ),
	                'menu_name' => __( 'Tags' ),
	                'search_items' => __( 'Search Tags' ),
	                'popular_items' => __( 'Popular Tags' ),
	                'all_items' => __( 'All Tags' ),
	                'edit_item' => __( 'Edit Tag' ),
	                'update_item' => __( 'Update Tag' ),
	                'add_new_item' => __( 'Add Tag' ),
	                'new_item_name' => __( 'Tag Name' ),
	                'add_or_remove_items' => __( 'Add or Remove Tags' ),
	                'choose_from_most_used' => __( 'Search between popular Tags' ),
	                ),
	            'public' => false,
	            'show_ui' => true,
	            'rewrite' => false,
	            'hierarchical' => false,
	        )
	    );
	
	}
	
}

function rec_tag_init() {
	global $_wp_rec_tag;
	$_wp_rec_tag = new REC_Tag();
	
}
add_action( 'init', 'rec_tag_init' );
