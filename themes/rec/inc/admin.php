<?php
/**
 * Rec Admin
 * Recreate amdin functionalities
 *
 * @package Rec
 * @since Rec 1.0
 */


/**
 * Minimum Access Level for wp-admin/
 * 
 */
function rec_restrict_admin_access() {
	if ( ! current_user_can( 'access_admin' ) && $_SERVER['PHP_SELF'] != '/wp-admin/admin-ajax.php' )
		wp_die( 'You do not have permission to enter this.' ); //wp_redirect( rec_user_profile_uri() );
	
} 

add_action( 'admin_init', 'rec_restrict_admin_access', 1 );

/**
 * Remove some admin menu entries
 */
function rec_admin_menu() {
	remove_menu_page( 'edit.php' );
	remove_menu_page( 'edit-comments.php' );
	remove_submenu_page( 'themes.php', 'themes.php' );
	
	remove_menu_page( 'edit.php?post_type=slide' );
	add_submenu_page( 'themes.php', __( 'Slides', 'rec' ), __( 'Slides', 'rec' ), 'edit_posts', 'edit.php?post_type=slide' );
	
	remove_submenu_page( 'options-general.php', 'options-writing.php' );
	remove_submenu_page( 'options-general.php', 'options-discussion.php' );
	remove_submenu_page( 'options-general.php', 'members-settings' );
}

add_action( 'admin_menu', 'rec_admin_menu', 100 );


function rec_fix_parent_file( $parent_file = '' ) {
    global $pagenow, $post_type;

    switch ( $post_type ) {
		case 'slide' : $parent_file = 'themes.php'; break;
    }
 
    return $parent_file;
}

add_filter( 'parent_file', 'rec_fix_parent_file', 1 );

/**
 * Menu custom order
 */
function rec_admin_menu_order( $menu_order ) {
	if ( ! $menu_order )
		return true;
	
	return array( 
		'index.php', 'separator1', 'edit.php?post_type=video', 'edit.php?post_type=contest', 
		'edit.php?post_type=job', 'users.php', 'separator2', 'edit.php?post_type=page', 'upload.php', 'separator-last',
		'themes.php', 'plugins.php', 'tools.php', 'options-general.php' );
	
}

add_filter( 'menu_order', 'rec_admin_menu_order' );
add_filter( 'custom_menu_order', 'rec_admin_menu_order' );

/**
 * Remove / Add dashboard widgets
 */
function rec_remove_dashboard_widgets() {
	remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
	remove_action( 'welcome_panel', 'wp_welcome_panel' );
	
}

add_action( 'admin_init', 'rec_remove_dashboard_widgets' );