<?php 
/*
* Plugin Name: Event Logger 
* Plugin URI: http://rec-videos.com
* Description: Log events in the DB
* Author: WidgiLabs
* Version: 1.0
* License: GPLv2
*/

//Define plugin directories
define( 'WP_EVENTSTATS_URL', WP_PLUGIN_URL.'/'.plugin_basename(dirname(__FILE__)) );
define( 'WP_EVENTSTATS_DIR', WP_PLUGIN_DIR.'/'.plugin_basename(dirname(__FILE__)) );

register_activation_hook(__FILE__, 'eventstats_activation');
function eventstats_activation() {
	eventstats_db_install();
}

// creates the database tables
function eventstats_db_install(){
	global $wpdb;

	/*
	 * stats table records events
	 * 		event_id - id
	 *		created - timestamp
	 *		event_type - play, like, dislike, complete, download, game error, report_flag, in-game-checkpoint, etc.
	 *		resource_id - game id that is being played, etc.
	 *		source_type - where the event happened: page template kind, iPhone, etc
	 *		source_id - the unique identifier for that source - permalink, iPhone UDID
	 *		user_id - the wordpress user id
	 *		client_useragent - the requester's user agent string,
	 *		client_ipaddress - the client's ip address
	 */ 
	$eventstats = "CREATE TABLE " . $wpdb->prefix . "log_events (
	event_id int(11) NOT NULL AUTO_INCREMENT,
	created TIMESTAMP DEFAULT NOW(), 
	event_type varchar(255) NOT NULL, 
	resource_id int(11) DEFAULT NULL,
	resource_type varchar(20) NOT NULL,
	source_id int(11) NOT NULL,
	source_type varchar(20) NOT NULL,
	user_id int(11) NOT NULL,
	client_useragent varchar(400) NOT NULL,
	client_ipaddress varchar(60) NOT NULL,
	status varchar(20) DEFAULT 'unread',
	PRIMARY KEY (event_id),
	KEY (user_id)
	);";

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	dbDelta($eventstats);
}


/* utilities */

/* 
 * _gfstats_play_sum_alltime
 * _gfstats_like_sum_alltime
 * _gfstats_dislike_sum_alltime
 */

function eventstats_log_event( $args ) {
	global $wpdb;
	
	extract( wp_parse_args( $args, 
		array(
			'event_type' => '',
			'resource_id' => 0,
			'resource_type' => '',
			'source_id' => 0,
			'source_type' => '',
			'user_id' => (int) get_current_user_id(),
			'client_ipaddress' => $_SERVER['REMOTE_ADDR'],
			'client_useragent' => $_SERVER['HTTP_USER_AGENT']
		)
	));
	
	$wpdb->query( $wpdb->prepare(
			"INSERT INTO " . $wpdb->prefix . "log_events
			( event_type, resource_id, resource_type, source_id, source_type, user_id, client_useragent, client_ipaddress )
			VALUES
			( %s, %d, %s, %d, %s, %d, %s, $s )
			", $event_type, $resource_id, $resource_type, $source_id, $source_type, $user_id, $client_useragent, $client_ipaddress
	));
	
	/* update global counts */
	// resource_id is our game id
	// event_type is play, like or dislike
	$custom_fields = get_post_custom($resource_id);
	$gfstats_play_sum_alltime = intval($custom_fields['_gfstats_play_sum_alltime'][0]);
	$gfstats_like_sum_alltime = intval($custom_fields['_gfstats_like_sum_alltime'][0]);
	$gfstats_dislike_sum_alltime = intval($custom_fields['_gfstats_dislike_sum_alltime'][0]);
	
	if ($event_type == "play") {
		if (!update_post_meta($resource_id, '_gfstats_play_sum_alltime', ($gfstats_play_sum_alltime+1))) {
			$newvalue=1;
			add_post_meta($resource_id, '_gfstats_play_sum_alltime', 1, true);
		} else {
			$newvalue=$gfstats_play_sum_alltime+1;
		}
		echo $newvalue;
	}

	if ($event_type == "like") {
		if (!update_post_meta($resource_id, '_gfstats_like_sum_alltime', ($gfstats_like_sum_alltime+1))) {
			$newvalue=1;
			add_post_meta($resource_id, '_gfstats_like_sum_alltime', 1, true);
		} else {
			$newvalue=$gfstats_like_sum_alltime+1;
		}
		echo $newvalue;
	}

	if ($event_type == "dislike") {
		if (!update_post_meta($resource_id, '_gfstats_dislike_sum_alltime', ($gfstats_dislike_sum_alltime+1))) {
			$newvalue=1;
			add_post_meta($resource_id, '_gfstats_dislike_sum_alltime', 1, true);
		} else {
			$newvalue=$gfstats_dislike_sum_alltime+1;
		}
		echo $newvalue;
	}
}
?>