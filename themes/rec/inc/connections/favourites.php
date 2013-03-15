<?php
/**
 * Video<->User Connection (aka Favoutires/Appreciation)
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
class REC_Favourites_Connection {
	
	/**
	 * Construct
	 * 
	 * @since 1.0
	 */
	function __construct() {
		add_action( 'init', array( &$this, 'init' ) );
		
	}
	
	/**
	 * Initialize and register everything
	 * 
	 * @since 1.0
	 */
	function init() {
	
		$args = array(
			'name' => 'users_to_videos_as_favourites',
		    'from' => 'user',
		    'to' => 'video',
		    'prevent_duplicates' => false,
		    'cardinality' => 'many-to-many',
		    'fields' => array(
				'role' => array(
					'title' => __( 'Role in this project', 'inpnl' ),
				),
			),
			'admin_box' => array(
				'show' => 'any',
				'context' => 'side'
			),
			//'admin_column' => 'to',
			'title' => array( 
				'from' => __( 'Participants', 'inpnl' ), 
				'to' => __( 'Participating in these Projects', 'inpnl' ) 
			),
			'to_labels' => array(
				'singular_name' => __( 'Participant', 'inpnl' ),
				'search_items' => __( 'Search Users', 'inpnl' ),
				'not_found' => __( 'No Users found', 'inpnl' ),
				'create' => __( 'Associate User to this Project', 'inpnl' ),
			),
			'from_labels' => array(
				'singular_name' => __( 'Project', 'inpnl' ),
				'search_items' => __( 'Search Projects', 'inpnl' ),
				'not_found' => __( 'No Projects found', 'inpnl' ),
				'create' => __( 'Associate with the Project', 'inpnl' ),
			),
			
		);
		
		register_connection_type( $args );
		
	}

	/**
	 * Query participants
	 * 
	 * @since 1.0
	 */
	function query_participants( $query = array() ) {
		$this->query = wp_parse_args(
			$query, array(
				'orderby' => 'display_name',
				'order' => 'ASC'
			)
		);
		
		$this->queried = new WP_Query_Users( $this->query );
		return $this->queried;
		
	}

	/**
	 * Get total of users
	 */
	function get_total_participants() {
		return $this->queried->get_total();
		
	}
	
	/**
	 * Get connected users (aka participants)
	 */
	function have_video_participants( $post = '' ) {
		if ( '' == $post )
			global $post;
			
		if ( ! $this->queried instanceof WP_Query_Users )
			$this->query_participants(
				array(
					'connected_type' => 'videos_to_users_as_participants',
					'connected_items' => $post
				) 
			);
		
		return ( $this->queried->have_users() );
		
	}

	/**
	 * Get current video participant
	 */
	function the_video_participant() {
		return $this->queried->the_user();
		
	}
	
}


/** Global object to manipulate connection */
global $_favourites_connection;
$_favourites_connection = new REC_Favourites_Connection();

