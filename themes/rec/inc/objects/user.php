<?php
/**
 * User Wrapper class
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
 * @since 1.2
 */
class REC_User {
	
	/**
	 * Construct
	 * 
	 * @since 1.0
	 */
	function __construct() {
		add_action( 'init', array( &$this, 'init' ) );
		add_filter( 'user_contactmethods', array( &$this, 'user_contactmethods' ) );
		
	}
	
	/**
	 * Initialize and register everything
	 * 
	 * @since 1.0
	 */
	function init() {
		
	}
	
	/**
	 * User contact methods
	 */
	function user_contactmethods( $user_contactmethods ) {	
		$user_contactmethods['twitter'] = __( 'Twitter', 'rec' );
		$user_contactmethods['facebook'] = __( 'Facebook', 'rec' );
		$user_contactmethods['linkedin'] = __( 'LinkedIn', 'rec' );
		$user_contactmethods['youtube'] = __( 'YouTube', 'rec' );
		$user_contactmethods['vimeo'] = __( 'Vimeo', 'rec' );
		
		return $user_contactmethods;
	  
	}
	
}


/**
 * @since 1.2
 */
class WP_Query_Users extends WP_User_Query {
	
	/**
	 * @var object $user Current user position (index)
	 */
	var $current_user = -1;
	
	/**
	 * @var object $user Current user object
	 */
	var $user = false;
	
	/**
	 * @var bool $is_author_page If the loop is the main loop of an author page
	 */
	var $is_author_page = false;
	
	/**
	 * @var bool $in_the_loop
	 */
	var $in_the_loop = false;
	
	/**
	 * PHP5 constructor
	 *
	 * @since 3.1.0
	 *
	 * @param string|array $args The query variables
	 * @return WP_User_Query
	 */
	function __construct( $query = null ) {
		if ( ! empty( $query ) ) {
			$this->query_vars = wp_parse_args( $query, array(
				'blog_id' => $GLOBALS['blog_id'],
				'role' => '',
				'meta_key' => '',
				'meta_value' => '',
				'meta_compare' => '',
				'include' => array(),
				'exclude' => array(),
				'search' => '',
				'search_columns' => array(),
				'orderby' => 'registered',
				'order' => 'DESC',
				'offset' => '',
				'number' => '',
				'count_total' => true,
				'fields' => 'all',
				'who' => ''
			) );
			
			if ( array_key_exists( 'user_ID', $this->query_vars ) ) {
				$this->query_vars['search'] = $this->query_vars['user_ID'];
				$this->query_vars['search_column'] = array( 'ID' );
				
				unset( $this->query_vars['user_ID'] );
				
			}

			$this->prepare_query();
			$this->query();
		}
	}
	
	/**
	 * Retrieve the users based on query variables
	 * 
	 * @since 1.2
	 * @access public
	 * 
	 * @return array List of WP_User objects
	 */
	function get_users() {
		return $this->get_results();
		
	}
	
	/**
	 * Whether there are more users available in the loop.
	 * 
	 * @since 1.2
	 * @access public
	 * @uses do_action_ref_array() Calls 'loop_end' if loop is ended
	 * 
	 * @return bool True if users are available, false if end of loop
	 */
	function have_users() {
		if ( $this->current_user + 1 < $this->total_users ) {
			return true;
			
		} elseif ( $this->current_user + 1 == $this->total_users && $this->total_users > 0 ) {
			do_action_ref_array( 'loop_end', array( &$this ) );
			// Do some cleaning up after the loop
			$this->rewind_users();
			
		}
		
		$this->in_the_loop = false;
		return false;
		
	}
	
	/**
	 * Sets up the current user.
	 * 
	 * Retrieves the next user, sets up the user, sets the 'in the loop'
	 * property to true.
	 * 
	 * @since 1.2
	 * @access public
	 * @uses do_action_ref_array() Calls 'loop_start' if loop has just started
	 * 
	 * @return WP_User object
	 */
	function the_user() {
		global $user;
		$this->in_the_loop = true;
		
		if ( $this->current_user == -1 ) // Loop has just started
			do_action_ref_array( 'loop_start', array( &$this ) );
		
		$user = $this->next_user();
		
	}
	
	/**
	 * Set up the next user and iterate current user index.
	 * 
	 * @since 1.2
	 * @access public
	 * 
	 * @return WP_User Next user
	 */
	function next_user() {
		$this->current_user++;
		$this->user = $this->results[ $this->current_user ];
		
		return $this->user;
		
	}
	
	/**
	 * Set up the previous user and iterate current user index.
	 * 
	 * @since 1.2
	 * @access public
	 * 
	 * @return WP_User Previous user
	 */
	function previous_user() {
		$this->current_user--;
		$this->user = $this->results[ $this->current_user ];
		
		return $this->user;
		
	}
	
	/**
	 * Rewind the users and reset user index.
	 * 
	 * @since 1.2
	 * @access public
	 */
	function rewind_users() {
		$this->current_user = -1;
		if ( $this->total_users > 0 )
			$this->user = $this->results[0];
		
	}
	
}


/** Global object to manipulate connection */
global $_rec_user;
$_rec_user = new REC_User();


/**
 * Whether there are more users available in the loop.
 * 
 * @since 1.2
 * @access public
 * @uses do_action_ref_array() Calls 'loop_end' if loop is ended
 * 
 * @return bool True if users are available, false if end of loop
 */
function have_users() {
	global $wp_query_users;
	return $wp_query_users->have_users();
	
}

/**
 * Sets up the current user.
 * 
 * Retrieves the next user, sets up the user, sets the 'in the loop'
 * property to true.
 * 
 * @since 1.2
 * 
 * @return WP_User object
 */
function the_user() {
	global $wp_query_users;
	return $wp_query_users->the_user();
	
}

/**
 * After looping through a separate query, this function restores
 * the $user global to the current user in the main query
 * 
 * @since 1.2
 * @uses $wp_query_users
 */
function wp_reset_userdata() {
	global $user, $wp_query_users;
	$user = $wp_query_users->user;  
}


/**
 * Get the user display name
 * 
 * @since 1.2
 * @uses $user
 */
function get_the_display_name( $user = '' ) {
	if ( '' == $user )
		global $user;
	
	return $user->data->display_name;
	
}

/**
 * Get the user display name
 * 
 * @since 1.2
 * @uses $user
 */
function the_display_name() {
	echo get_the_display_name();
}

/**
 * Get the user category
 * 
 * @since 1.2
 * @uses $user
 */
function get_the_user_category( $user = '', $sep = ', ' ) {
	if ( '' == $user )
		global $user;
	
	return implode( $sep, wp_get_object_terms( $user->ID, 'user_category', array( 'fields' => 'names' ) ) );
}

/**
 * The user category
 * 
 * @since 1.2
 * @uses $user
 */
function the_user_category() {
	echo get_the_user_category();
}

/**
 * Get the user biography
 * 
 * @since 1.2
 * @uses $user
 */
function get_the_user_biography( $user = '' ) {
	if ( '' == $user )
		global $user;
	
	return get_user_meta( $user->ID, 'description', true );
	
}

/**
 * The user biography
 * 
 * @since 1.2
 * @uses $user
 */
function the_user_biography() {
	echo get_the_user_biography();
	
}

/**
 * Return an object of number of user counts for each associated object
 * 
 * @since 1.2
 * @uses $user
 * 
 * @return object
 */
function get_user_counts( $user = '' ) {
	if ( '' == $user )
		global $user;
	
	$std = new stdClass;
	$std->messages   = count_user_objects( $user->ID, 'messages' );
	$std->favourites = count_user_objects( $user->ID, 'favourite' );
	$std->contests   = count_user_objects( $user->ID, 'contest' );
	$std->projects   = count_user_objects( $user->ID, 'video' );
	
	
	return $std;
}

/**
 * Return the number of user objects
 * 
 * @since 1.2
 * @uses $user
 */
function count_user_objects( $userid, $post_type = 'post' ) {
	global $wpdb;
	$count = $wpdb->get_var( 
		$wpdb->prepare( 
			"SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = %s AND post_author = %d AND post_status = 'publish'",
			$post_type, $userid
		)
	);
	return $count;
	
}
