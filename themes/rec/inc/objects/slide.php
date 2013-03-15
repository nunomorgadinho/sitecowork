<?php
/**
 * Slide Post Type
 * 
 * @package REC
 * @since REC 1.2
 */


/**
 * Checks if it is accessed from Wordpress' index.php
 * @since 1.2
 */
if ( ! function_exists( 'add_action' ) ) {
	die( 'I\'m just a plugin. I must not do anything when called directly!' );

}


/**
 * Slide
 *
 * Worker Class
 *
 * @since 1.2
 */
class REC_Slide {
	
	/**
	 * Register post args used
	 */
	var $register_posts_args;
	
	/**
	 * Construct
	 * 
	 * @since 1.0
	 */
	function __construct() {
		add_action( 'init', array( &$this, 'init' ) );
		add_action( 'load-edit.php', array( &$this, 'load_edit' ) );
		add_filter( 'post_type_link', array( &$this, 'post_type_link' ), 10, 4 );
		
	}
	
	/**
	 * Initialize and register everything
	 * 
	 * @since 1.0
	 */
	function init() {
		
		$this->register_posts_args = array(
			'label' => __( 'Slides', 'rec' ),
			'labels' => array(
				'name' => __( 'Slides', 'rec' ),
				'singular_name'	=> __( 'Slide', 'rec' ),
				'add_new' => __( 'Add Slide', 'rec' ),
				'all_items' => __( 'Slides', 'rec' ),
				'add_new_item' => __( 'Add New', 'rec' ),
				'edit_item' => __( 'Edit Slide', 'rec' ),
				'view_item'	=> __( 'View Slide', 'rec' ),
				'search_items' => __( 'Search Slides', 'rec' ),
				'not_found' => __( 'Slides not found', 'rec' ),
				'not_found_in_trash' => __( 'Slides not found in Trash', 'rec' ),
				'parent_item_colon' => ',',
			),
			'messages' => array(
				'updated_view' => __( 'Slide updated. <a href="%s">View Slide</a>', 'rec' ),
				'updated' => __( 'Slide updated.', 'rec' ),
				'deleted' => __( 'Slide deleted.', 'rec' ),
				'published' => __( 'Slide published. <a href="%s">View Slide</a>', 'rec' ),
				'saved' => __( 'Slide saved.', 'rec' ),
				'submitted' => __( 'Slide submitted. <a target="_blank" href="%s">Preview Slide</a>', 'rec' ),
				'scheduled' => __( 'Slide scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Slide</a>', 'rec' ),
				'draft_updated' => __( 'Slide draft updated. <a target="_blank" href="%s">Preview Slide</a>', 'rec' ),
				'revision_restored' => __( 'Slide restored to revision from %s', 'rec' ),
			),
			'description'			=> '',
			'public'				=> false,
			'exclude_from_search'	=> true,
			'publicly_queryable'	=> false,
			'has_archive'			=> false,
			'rewrite'				=> false,
			'show_ui'				=> true,
			'show_in_nav_menus'		=> false,
			'show_in_menu'			=> true,
			'show_in_admin_bar'		=> false,
			'supports'				=> array( 'title', 'excerpt', 'thumbnail' ),
			'taxonomies' => array( 'slide_category' ),
			
			'meta_fields' => array(
				array( 'name' => 'url', 'type' => 'url', 'label' => __( 'Destination URL', 'rec' ), 'validate' => 'esc_url' ),
			),
			
			'after_title' => array(
				'fields' => array( 'url' ),
				'callback' => 'wp_form_table',
				'before' => '<div class="postbox">',
				'after' => '</div>',
			),
			
			'manage_edit_columns' => array(
				'cb' => null,
				'thumbnail' => '',
				'title' => __( 'Title', 'rec' ),
				'url' => __( 'Destination URL', 'rec' ),
				'taxonomy-slide_category' => null,
				'date' => __( 'Date' )
			),
			'manage_sortable_columns' => array(
				'title', 'date',
			),
			
			'custom_columns' => array( &$this, 'custom_columns_content' ),
			
		);
		
		register_post_type( 'slide', $this->register_posts_args );
	
	}
	
	function custom_columns_content( $column ) {
		global $post;
		
		switch ( $column ) {
			case 'thumbnail' :
				the_post_thumbnail( array( 300, 116 ) );
				break;
				
			case 'url' :
				echo make_clickable( get_post_meta( $post->ID, '_slide_url', true ) );
				break;
			
		}
		
	}
	
	function load_edit() {
		global $post_type;
		
		if ( ! in_array( 'slide', array( $post_type, $_GET['post_type'] ) ) )
			return;
		
		?>
		<style type="text/css">
			.manage-column.column-thumbnail { width: 300px; }
			.column-thumbnail.thumbnail img { border:1px solid #ccc; margin-bottom: 3px;}
		</style>
		<?php
	}
	
	function post_type_link( $post_link, $post, $leavename, $sample ) {
		if ( 'slide' == $post->post_type )
			return get_post_meta( $post->ID, '_slide_url', true );
		
		return $post_link;
		
	}


	/**
	 * Query slide post type
	 * 
	 * @since 1.0
	 */
	function query_slides( $query = array() ) {
		$this->query = wp_parse_args(
			$query, array(
				'post_type' => 'slide'
			)
		);
		
		$this->queried = new WP_Query( $this->query );
		return $this->queried;
		
	}
	
	/**
	 * Checks if there are slides for the given query
	 *
	 * @since 1.0
	 */
	function have_slides( $query = array() ) {
		if ( is_array( $query ) && array_key_exists( 'category', $query ) ) {
			$query['slide-category'] = $query['category'];
			
			unset( $query['slide-category'] );
			
		}
					
		if ( ! $this->queried instanceof WP_Query )
			$this->query_slides( $query );
		
		return ( $this->queried->have_posts() );
		
	}
	
	/**
	 * Sets global $post to slide
	 *
	 * @since 1.0
	 */
	function the_slide() {
		return ( $this->queried->the_post() );
		
	}
	
}


/** Global object to manipulate slide post type */
global $_slide_post_type;
$_slide_post_type = new REC_Slide();


function have_slides( $query = '' ) {
	global $_slide_post_type;
	return $_slide_post_type->have_slides( $query );
	
}

function the_slide() {
	global $_slide_post_type;
	return $_slide_post_type->the_slide();
	
}
