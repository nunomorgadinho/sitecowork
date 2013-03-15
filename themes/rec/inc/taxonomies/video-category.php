<?php

class REC_Video_Category {
	
	function __construct() {
	    register_taxonomy(
	        'video-category',
	        'video',
	        array(
	            'labels' => array(
	                'name' => __( 'Categories' ),
	                'singular_name' => __( 'Category' ),
	                'menu_name' => __( 'Categories' ),
	                'search_items' => __( 'Search Categories' ),
	                'popular_items' => __( 'Popular Categories' ),
	                'all_items' => __( 'All Categories' ),
	                'edit_item' => __( 'Edit Category' ),
	                'update_item' => __( 'Update Category' ),
	                'add_new_item' => __( 'Add Category' ),
	                'new_item_name' => __( 'Category Name' ),
	                'add_or_remove_items' => __( 'Add or Remove Categories' ),
	                'choose_from_most_used' => __( 'Search between popular Categories' ),
	                ),
	            'public' => true,
	            'show_ui' => true,
	            'rewrite' => array(
					'with_front' => true,
					'slug' => 'categories/projects'
				),
	            'hierarchical' => true,
	        )
	    );
	
	}
	
}

function rec_video_category_init() {
	global $_wp_rec_video_category;
	$_wp_rec_video_category = new REC_Video_Category();
	
}
add_action( 'init', 'rec_video_category_init' );
