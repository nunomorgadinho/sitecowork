<?php

/**
 * Connections Types
 *
 * @package WordPress
 * @subpackage Object Connection
 * @since 0.1
 * 
 */



if ( ! class_exists( 'WP_Connection_Types' ) ) :
/**
 * Connections with Posts 2 Posts plugin from scribu
 * 
 * @since 0.1
 */
class WP_Connection_Types {
		
	var $registers;
	
	var $connections;
	
	function __construct() {
		add_action( 'p2p_init', array( &$this, 'init' ) );
		add_filter( 'p2p_new_post_args', array( &$this, 'create_defaults' ), 10, 2 );
		
	}
	
	function init() {
		if ( is_array( $this->registers ) && ! empty( $this->registers ) ) {
			foreach ( $this->registers as $register ) {
				$this->register_connection_type( $register );
				
			}
			
		}
		
	}
	
	function register_connection_type( $args = array() ) {
		$this->connections[ $args['name'] ] = p2p_register_connection_type( $args );
		
	}
	
	/**
	 * Parameters when a new post is created by a P2P box.
	 * 
	 * By default, when you create a page directly from the P2P admin box, it's status will be 'draft'. 
	 * If you want it to be published immediately, you can do something like this:
	 * 
	 * [...]
	 * 'save_fields' => array(
	 * 		'from' => array(
	 * 			'post_status' => 'publish',
	 * 		)
	 * 		'to' => array(
	 * 			'post_status' => 'pending',
	 * 		)
	 * ),
	 * [...]
	 * 
	 * @since 0.1
	 */
	function create_defaults( $args, $ctype ) {
		$direction 		= $ctype->get_direction();
		$save_fields['to'] 	= isset( $ctype->save_fields['to'] ) 	? false : $ctype->save_fields['to'];
		$save_fields['from'] = isset( $ctype->save_fields['from'] ) ? false : $ctype->save_fields['from'];
		
		if ( $save_fields[ $direction ] ) {
			foreach ( (array) $save_fields[ $direction ] as $arg => $value ) {
				$args[ $arg ] = $value;
				
			}
			
		}
		
		return $args;
		
	}

}


global $_wp_connection_types;
$_wp_connection_types = new WP_Connection_Types;

function register_connection_type( $args ) {
	$args = wp_parse_args( $args, array(
		'name' => '',
		)
	);
	
	global $_wp_connection_types;
	
	if ( ! array_key_exists( $args['name'], (array) $_wp_connection_types->registers ) ) {
		$_wp_connection_types->registers[ $args['name'] ] = $args;
		return true;
		
	}
	return false;
	
}

endif;

