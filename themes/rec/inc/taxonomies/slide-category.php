<?php

class Slide_Category {
	
	function __construct() {
	    register_taxonomy(
	        'slide_category',
	        'slide',
	        array(
	            'public' => false,
	            'show_ui' => true,
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
	            'rewrite' => false,
	            'hierarchical' => true,
	            'show_admin_column' => true
	        )
	    ); 
		
	}
	
}

function rec_slide_category_init() {
	global $_wp_rec_slide_category;
	$_wp_rec_slide_category = new Slide_Category();
	
}
add_action( 'init', 'rec_slide_category_init' );
