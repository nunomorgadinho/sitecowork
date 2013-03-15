<?php
/**
 * Rec functions and definitions
 *
 * @package Rec
 * @since Rec 1.0
 */
 
/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Rec 1.0
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */
	
/** 
 * Disable WordPress Admin Bar for all users but admins.
 *  
 */
if ( ! current_user_can( 'administrator' ) )
	show_admin_bar( false );



if ( ! function_exists( 'rec_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since Rec 1.0
 */
function rec_setup() {
	/**
	 * Load bootstrap
	 */
	require( get_template_directory() . '/inc/bootstrap.php' );
	
	/**
	 * Load libraries
	 */
	foreach ( glob( get_template_directory() . '/inc/libs/*.php' ) as $lib )
		include $lib;
	
	/**
	 * Load custom post types
	 */
	foreach ( glob( get_template_directory() . '/inc/objects/*.php' ) as $post_type )
		include $post_type;
	
	/**
	 * Load custom connections
	 */
	foreach ( glob( get_template_directory() . '/inc/connections/*.php' ) as $connection )
		include $connection;
	
	/**
	 * Load custom taxonomies
	 */
	foreach ( glob( get_template_directory() . '/inc/taxonomies/*.php' ) as $taxonomy )
		include $taxonomy;
	

	/**
	 * Load custom functions.
	 */
	require( get_template_directory() . '/inc/functions.php' );

	/**
	 * Load Ajax entrypoints
	 */
	require( get_template_directory() . '/inc/ajax.php' );
	
	/**
	 * Rewrite rules
	 */
	require( get_template_directory() . '/inc/rewrite.php' );

	/**
	 * Custom template tags for this theme.
	 */
	require( get_template_directory() . '/inc/template-tags.php' );

	/**
	 * Custom functions that act independently of the theme templates
	 */
	require( get_template_directory() . '/inc/extras.php' );

	/**
	 * Customizer additions
	 */
	if ( is_admin() )
		require( get_template_directory() . '/inc/customizer.php' );

	/**
	 * Admin additions
	 */
	if ( is_admin() )
		require( get_template_directory() . '/inc/admin.php' );

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on Rec, use a find and replace
	 * to change 'rec' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'rec', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'rec' ),
	) );
	
}
endif; // rec_setup
add_action( 'after_setup_theme', 'rec_setup' );


/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since Rec 1.0
 */
function rec_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar', 'rec' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );

	register_sidebar (array (
		'name' => __( 'Search', 'rec'),
		'id' => 'sidebar-2',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		));
}

add_action( 'widgets_init', 'rec_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function rec_scripts() {


	wp_enqueue_style( 'style', get_stylesheet_uri() );
	wp_enqueue_style( 'sliser-style', get_stylesheet_directory_uri() . '/bjqs.css' );
	
	wp_enqueue_script( 'small-menu', get_template_directory_uri() . '/js/small-menu.js', array( 'jquery' ), '20120206', true );

	//wp_deregister_script( 'jquery-ui' );
	//wp_register_script( 'jquery-ui', 'http://code.jquery.com/ui/1.9.0/jquery-ui.js');
	wp_enqueue_script( 'jquery-ui-tabs' );


	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}
	
	wp_enqueue_script( 'slider', get_template_directory_uri() . '/js/bjqs.min.js', array( 'jquery' ), '2013021401' );
	wp_enqueue_script( 'underscore-string', 'https://raw.github.com/edtsech/underscore.string/master/dist/underscore.string.min.js' );
	
	wp_enqueue_script( 'application', get_template_directory_uri() . '/js/application.js', array( 'jquery', 'backbone', 'underscore' ), '2013021401' );
	//wp_enqueue_script( 'recslider', get_template_directory_uri() . '/js/slider.js', array( 'application' ), '2013021401' );
	
	wp_enqueue_script( 'rec', get_template_directory_uri() . '/js/rec.js', array(), '2013021401', true );
	

}
add_action( 'wp_enqueue_scripts', 'rec_scripts' );

