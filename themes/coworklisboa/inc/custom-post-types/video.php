<?php
/**
 * Video Post Type
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
 * Video
 *
 * Worker Class
 *
 * @since 1.2
 */
class REC_Video {
	
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
		add_filter( 'after_title_meta_video', array( &$this, 'form_table_args' ) );
		add_action( 'edit_form_after_title', array( &$this, 'edit_form_after_title' ) );
		add_action( 'load-edit.php', array( &$this, 'load_edit' ) );
		
	}
	
	/**
	 * Initialize and register everything
	 * 
	 * @since 1.0
	 */
	function init() {
		
		$this->register_posts_args = array(
			'label' => __( 'Videos', 'rec' ),
			'labels' => array(
				'name' => __( 'Videos', 'rec' ),
				'singular_name'	=> __( 'Video', 'rec' ),
				'add_new' => __( 'Add Video', 'rec' ),
				'all_items' => __( 'Videos', 'rec' ),
				'add_new_item' => __( 'Add New', 'rec' ),
				'edit_item' => __( 'Edit Video', 'rec' ),
				'view_item'	=> __( 'View Video', 'rec' ),
				'search_items' => __( 'Search Videos', 'rec' ),
				'not_found' => __( 'Videos not found', 'rec' ),
				'not_found_in_trash' => __( 'Videos not found in Trash', 'rec' ),
				'parent_item_colon' => ',',
			),
			'messages' => array(
				'updated_view' => __( 'Video updated. <a href="%s">View Video</a>', 'rec' ),
				'updated' => __( 'Video updated.', 'rec' ),
				'deleted' => __( 'Video deleted.', 'rec' ),
				'published' => __( 'Video published. <a href="%s">View Video</a>', 'rec' ),
				'saved' => __( 'Video saved.', 'rec' ),
				'submitted' => __( 'Video submitted. <a target="_blank" href="%s">Preview Video</a>', 'rec' ),
				'scheduled' => __( 'Video scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Video</a>', 'rec' ),
				'draft_updated' => __( 'Video draft updated. <a target="_blank" href="%s">Preview Video</a>', 'rec' ),
				'revision_restored' => __( 'Video restored to revision from %s', 'rec' ),
			),
			'description'			=> '',
			'public'				=> true,
			'exclude_from_search'	=> false,
			'publicly_queryable'	=> true,
			'has_archive'			=> 'videos',
			'rewrite'				=> array(
					'slug' => 'videos',
					'with_front' => false,
			),
			'show_ui'				=> true,
			'show_in_nav_menus'		=> true,
			'show_in_menu'			=> true,
			'show_in_admin_bar'		=> false,
			'supports'	=> array( 'title', 'editor', 'thumbnail', 'comments', 'author', 'revisions' ),
			
			'meta_fields' => array(
				array(
					'name' => 'url',	
					'type' => 'url', 
					'label' => __( 'URL', 'rec' ),
					'attributes' => array(
						'readonly' => 'true'
					)
				),
				array(
					'name' => 'id',	
					'type' => 'text', 
					'label' => __( 'ID', 'rec' ),
					'attributes' => array(
						'readonly' => 'true'
					)
				),
				array(
					'name' => 'uploaded',	
					'type' => 'text', 
					'label' => __( 'Uploaded at', 'rec' ),
					'attributes' => array(
						'readonly' => 'true'
					)
				),
				array(
					'name' => 'duration',	
					'type' => 'text', 
					'label' => __( 'Duration', 'rec' ),
					'attributes' => array(
						'readonly' => 'true'
					),
					'callback_value' => array( &$this, 'return_duration' ),
					'handle_save' => false,
				),
				array(
					'name' => 'aspect_ratio',	
					'type' => 'text', 
					'label' => __( 'Aspect Ration', 'rec' ),
					'attributes' => array(
						'readonly' => 'true'
					)
				),
				array(
					'name' => 'service',	
					'type' => 'text', 
					'label' => __( 'Service', 'rec' ),
					'attributes' => array(
					)
				),
				array(
					'name' => 'thumbnail',
					'handle_save' => false,
				),
				array(
					'name' => 'stream_urls',
					'handle_save' => false, 
				),
				array(
					'name' => 'homepage_background',
					'type' => 'checkbox',
					'label' => __( 'Landing Page Background', 'rec' ),
					'options' => array(
						array(
							'value' => 'show_in_background',
							'label' => __( 'Show this video in the background.', 'rec' ),
						)
					)
				)
			),
			
			'after_title' => array(
				'fields' => array( 'id', 'url', 'uploaded', 'duration', 'aspect_ratio', 'homepage_background', 'service' ),
				'callback' => 'wp_form_table',
				'before' => '<div class="postbox">',
				'after' => '</div>',
			),
			
			'after_editor' => false,
			
			'manage_edit_columns' => array(
				'cb' => null,
				'thumbnail' => '',
				'title' => __( 'Video', 'rec' ),
				'url' => __( 'URL', 'rec' ),
				'duration' => __( 'Duration', 'rec' ),
				'author' => __( 'Author' ),
				'date' => __( 'Date' )
			),
			'manage_sortable_columns' => array(
				'title', 'date', 
				'url' => array(
					'meta_key' => '_video_url',
					'orderby' => 'meta_value',
				),
				'duration' => array(
					'meta_key' => '_video_duration',
					'orderby' => 'meta_value_num',
				),
				'author' => array(
					'orderby' => 'post_author',
				)
			),
			
			'custom_columns' => array( &$this, 'custom_columns_content' ),
			
		);
		
		register_post_type( 'video', $this->register_posts_args );
	
	}

	function return_duration( $value ) {
		if ( is_numeric( $value ) )
			return seconds_to_time( (int) $value, 'H:m:s' );
		return $value;
		
	}

	function edit_form_after_title() {
		global $post;
		
		if ( ! in_array( 'video', array( $post_type, $post->post_type ) ) )
			return;
		
		
	}
	
	function custom_columns_content( $column ) {
		global $post;
		
		switch ( $column ) {
			case 'thumbnail' :
				printf( '<img src="%s" width="80" height="80" />', get_post_meta( $post->ID, '_video_thumbnail', true ) );
				break;
				
			case 'url' :
				echo make_clickable( get_post_meta( $post->ID, '_video_url', true ) );
				break;
			
			case 'duration' :
				echo seconds_to_time( get_post_meta( $post->ID, '_video_duration', true ) );
				break;
				
			case 'author' :
				echo get_the_author();
				break;
			
		}
		
	}
	
	function load_edit() {
		global $post_type;
		
		if ( ! in_array( 'video', array( $post_type, $_GET['post_type'] ) ) )
			return;
		
		?>
		<style type="text/css">
			.manage-column.column-thumbnail { width: 90px; }
		</style>
		<?php
	}
	
	
	/**
	 * Get a given $post_id video
	 * 
	 * @since 1.2
	 * 
	 * @param int $post_id
	 * @return object
	 */
	function get_video( $post_id ) {
		$post_id = (int) $post_id;
		$post = get_post( $post_id );
		
		if ( is_wp_error( $post ) )
			return false;
		
		if ( is_object( $post ) ) {
			foreach ( $this->register_posts_args['meta_fields'] as $meta_field )
				$metas[ $meta_field['name'] ] = get_post_meta( $post->ID, "_video_{$meta_field['name']}", true );
			
			return (object) array_merge( (array) $post, $metas );
			
		}
		
	}
	
	/**
	 * Get a given $post_id video
	 * 
	 * @since 1.2
	 * 
	 * @param int $post_id
	 * @return object
	 */
	function get_background_video() {
		$post = get_posts( array(
			'post_type' => 'video',
			'numberposts' => 1,
			'orderby' => 'rand',
			'meta_query' => array(
				array(
					'key' => '_video_homepage_background',
					'value' => 'show_in_background'
					),
				array(
					'key' => '_video_service',
					'value' => 'youtube'
					)
				),
			)
		);
		
		if ( is_wp_error( $post ) )
			return false;
		
		if ( isset( $post[0] ) && is_object( $post[0] ) ) {
			foreach ( $this->register_posts_args['meta_fields'] as $meta_field )
				$metas[ $meta_field['name'] ] = get_post_meta( $post[0]->ID, "_video_{$meta_field['name']}", true );
			
			return (object) array_merge( (array) $post[0], $metas );
			
		}
		
		return false;
		
	}
	
	/**
	 * Add a new video
	 * 
	 * @since 1.2
	 * 
	 * @param int $post_id
	 * @return object
	 */
	function add_video( $data, $user_id = '' ) {
		
		if ( is_object( $data ) ) {
			$data = (array) $data;
			
		}
		
		if ( '' == $user_id )
			$user_id = get_current_user_id();
		
		$postarr = array(
			'post_title' => $data['title'],
			'post_content' => $data['description'],
			'post_type' => 'video',
			'post_status' => 'publish',
			'post_author' => (int) $user_id,
		);
		
		$video_id = wp_insert_post( $postarr );
		
		foreach ( $this->register_posts_args['meta_fields'] as $meta_field ) {
			if ( isset( $data[ $meta_field['name'] ] ) )
				add_post_meta( (int) $video_id, '_video_'.$meta_field['name'], $data[ $meta_field['name'] ] );
			
		}
		
		return $video_id;
		
	}
	
	/**
	 * Add a new video by URL
	 * 
	 * @since 1.2
	 * 
	 * @param string URL
	 * @return object
	 */
	function add_video_by_url( $url, $user_id = '' ) {
		//json_decode( file_get_contents( 'https://gdata.youtube.com/feeds/api/videos/TYIivzzteIk?v=2&alt=jsonc' ) );
		//http://youtu.be/TYIivzzteIk
		//http://www.youtube.com/watch?v=TYIivzzteIk&feature=related
		
		$video_id = $this->get_video_id_by_url( $url );
		
		if ( is_youtube_video( $url ) ) {
			$video = json_decode( file_get_contents( "https://gdata.youtube.com/feeds/api/videos/{$video_id}?v=2&alt=jsonc" ) );
			$streams = $this->get_youtube_streams( $url );
			$data = array(
				'id' => $video->data->id,
				'title' => $video->data->title,
				'uploaded' => format_time( $video->data->uploaded, 'mysql' ),
				'category' => $video->data->category,
				'description' => $video->data->description,
				'thumbnail' => $video->data->thumbnail->hqDefault,
				'url' => esc_url( $url ),
				'duration' => $video->data->duration,
				'aspect_ratio' => $video->data->aspectRatio,
				'stream_urls' => $streams,
				'service' => 'youtube',
			);
			
		} elseif ( is_vimeo_video( $url ) ) {
			$video = json_decode( file_get_contents( "http://vimeo.com/api/v2/video/{$video_id}.json" ) );
			$stream = $this->get_vimeo_stream_url( $url, $video_id );
			$data = array(
				'id' => $video[0]->id,
				'title' => $video[0]->title,
				'uploaded' => $video[0]->upload_date,
				'category' => $video->data->tags,
				'description' => $video[0]->description,
				'thumbnail' => $video[0]->thumbnail_large,
				'url' => esc_url( $url ),
				'duration' => $video[0]->duration,
				'aspect_ratio' => $video[0]->width."x".$video[0]->height,
				'stream_urls' => $stream,
				'service' => 'vimeo'
			);
			
		}
		
		return $this->add_video( $data, $user_id );
		
	}
	
	/**
	 * Add a new video by URL
	 * 
	 * @since 1.2
	 * 
	 * @param string URL
	 * @return object
	 */
	function get_video_id_by_url( $url ) {
		
		if ( false !== $pos = strpos( $url, 'youtu.be/' ) ) {
			$video_id = substr( $url, $pos+9 );
			
		} elseif ( false !== $pos = strpos( $url, '/watch?v=' ) ) {
			if ( false !== strpos( $url, '&') )
				$video_id = substr( $url, $pos+9, strpos( $url, '&')-($pos+9) );
			else
				$video_id = substr( $url, $pos+9 );
			
		} elseif ( false !== $pos = strpos( $url, 'vimeo.com/' ) ) {
			if ( false !== strpos( $url, '&') )
				$video_id = substr( $url, $pos+10, strpos( $url, '&')-($pos+8) );
			else
				$video_id = substr( $url, $pos+10 );
			
		}
		
		return $video_id;
		
	}
	
	/**
	 * Add a new video by URL
	 * 
	 * @since 1.2
	 * 
	 * @param string URL
	 * @return object
	 */
	function get_vimeo_stream_url( $url, $video_id ) {
		$conn = wp_remote_get( $url, array(
			'user-agent' => "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)",
			)
		);
		$html = $conn['body'];
		
		var_dump( $html );
		
		if ( ! preg_match( '#document\.getElementById\(\'player_(.+)\n#i', $content, $script ) )
			return new WP_Error( 'vm_error_loc', __( 'Error Locating Download URLs', 'rec' ) );
			
		preg_match( '#"timestamp":([0-9]+)#i', $script[1], $matches );
		$timestamp = $matches[1];

		preg_match('#"signature":"([a-z0-9]+)"#i', $script[1], $matches);
		$signature = $matches[1];
		
		return array(
			'low' => array(
				'MP4' => "http://player.vimeo.com/play_redirect?clip_id={$video_id}&sig={$signature}&time={$timestamp}&quality=lp",
				'WEBM' => '',
				'3GP' => '',
			),
			'medium' => array(
				'MP4' => "http://player.vimeo.com/play_redirect?clip_id={$video_id}&sig={$signature}&time={$timestamp}&quality=sd",
				'WEBM' => '',
				'3GP' => '',
			),
			'high' => array(
				'MP4' => "http://player.vimeo.com/play_redirect?clip_id={$video_id}&sig={$signature}&time={$timestamp}&quality=hd",
				'WEBM' => '',
				'3GP' => '',
			)
		);
			
		
	}
	
	/**
	 * Add a new video by URL
	 * 
	 * @since 1.2
	 * 
	 * @param string URL
	 * @return object
	 */
	function get_youtube_streams( $url ) {
		$conn = wp_remote_get( $url, array(
			'user-agent' => "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)",
			)
		);
		$html = $conn['body'];

		if ( strstr( $html, 'verify-age-thumb' ) )
			return new WP_Error( 'yt_adult_video', __( 'Adult Video Detected', 'rec' ) );

		if ( strstr( $html, 'das_captcha' ) )
			return new WP_Error( 'yt_das_captcha', __( 'Captcah Found please run on diffrent server', 'rec' ) );
		

		if ( ! preg_match( '/stream_map=(.[^&]*?)&/i', $html, $match ) )
			return new WP_Error( 'yt_error_loc', __( 'Error Locating Downlod URLs', 'rec' ) );

		if ( ! preg_match( '/stream_map=(.[^&]*?)(?:\\\\|&)/i', $html, $match ) )
			return new WP_Error( 'yt_error_loc', __( 'Error Locating Downlod URLs', 'rec' ) );
		
		$urls = explode( ',', urldecode( $match[1] ) );
		
		foreach( $urls as $url ) {
			if ( preg_match( '/itag=([0-9]+)/', $url, $itag ) 
				&& preg_match( '/sig=(.*?)&/', $url , $sig ) 
				&& preg_match( '/url=(.*?)&/', $url , $urlm ) ) {
					$urlm = urldecode( $urlm[1] );
					$video_links[ $itag[1] ] = $urlm.'&signature='.$sig[1];
				}
				
        }
        
		$video_type[13] = array( "13", "3GP", 'low', "Low Quality - 176x144" );
		$video_type[17] = array( "17", "3GP", 'medium', "Medium Quality - 176x144" );
		$video_type[36] = array( "36", "3GP", 'high', "High Quality - 320x240" );
		$video_type[5]  = array( "5", "FLV", 'low', "Low Quality - 400x226" );
		$video_type[6]  = array( "6", "FLV", 'medium', "Medium Quality - 640x360" );
		$video_type[34] = array( "34", "FLV", 'medium', "Medium Quality - 640x360" );
		$video_type[35] = array( "35", "FLV", 'high', "High Quality - 854x480" );
		$video_type[43] = array( "43", "WEBM", 'low', "Low Quality - 640x360" );
		$video_type[44] = array( "44", "WEBM", 'medium', "Medium Quality - 854x480" );
		$video_type[45] = array( "45", "WEBM", 'high', "High Quality - 1280x720" );
		$video_type[18] = array( "18", "MP4", 'medium', "Medium Quality - 480x360" );
		$video_type[22] = array( "22", "MP4", 'high', "High Quality - 1280x720" );
		$video_type[37] = array( "37", "MP4", 'high', "High Quality - 1920x1080" );
		$video_type[33] = array( "38", "MP4", 'high', "High Quality - 4096x230" );
		
		foreach( $video_type as $format => $meta ) {
            if ( isset( $video_links[ $format ] ) ) {
            	$streams[ $meta[2] ][ $meta[1] ] = $video_links[ $format ];
                //$videos[ $meta[2] ][] = array( 'ext' => strtolower( $meta[1] ), 'type' => $meta[3], 'url' => $video_links[ $format ] );
            } 
        }
		
		return $streams;
		
	}
	
}


/** Global object to manipulate video post type */
global $_video_post_type;
$_video_post_type = new REC_Video();


function add_video_by_url( $url, $user_id = '' ) {
	global $_video_post_type;
	return $_video_post_type->add_video_by_url( $url, $user_id );
	
}

function get_youtube_streams( $url ) {
	global $_video_post_type;
	return $_video_post_type->get_youtube_streams( $url );
	
}

function get_vimeo_stream_url( $url, $video_url ) {
	global $_video_post_type;
	return $_video_post_type->get_youtube_streams( $url );
	
}

function get_background_video() {
	global $_video_post_type;
	return $_video_post_type->get_background_video();
	
}

function is_vimeo_video( $url ) {
	return false !== strpos( $url, 'vimeo.com/' );
	
}

function is_youtube_video( $url ) {
	return ( false !== strpos( $url, 'youtube.com/watch?v=' ) || false !== strpos( $url, 'youtu.be/' ) );
	
}
