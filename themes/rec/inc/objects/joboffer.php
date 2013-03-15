<?php
/**
 * Job Post Type
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
 * Job
 *
 * Worker Class
 *
 * @since 1.2
 */
class REC_Job {
	
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
		add_filter( 'after_title_meta_job', array( &$this, 'form_table_args' ) );
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
			'label' => __( 'Jobs', 'rec' ),
			'labels' => array(
				'name' => __( 'Jobs', 'rec' ),
				'singular_name'	=> __( 'Job', 'rec' ),
				'add_new' => __( 'Add Job', 'rec' ),
				'all_items' => __( 'Jobs', 'rec' ),
				'add_new_item' => __( 'Add New', 'rec' ),
				'edit_item' => __( 'Edit Job', 'rec' ),
				'view_item'	=> __( 'View Job', 'rec' ),
				'search_items' => __( 'Search Jobs', 'rec' ),
				'not_found' => __( 'Jobs not found', 'rec' ),
				'not_found_in_trash' => __( 'Jobs not found in Trash', 'rec' ),
				'parent_item_colon' => ',',
			),
			'messages' => array(
				'updated_view' => __( 'Job updated. <a href="%s">View Job</a>', 'rec' ),
				'updated' => __( 'Job updated.', 'rec' ),
				'deleted' => __( 'Job deleted.', 'rec' ),
				'published' => __( 'Job published. <a href="%s">View Job</a>', 'rec' ),
				'saved' => __( 'Job saved.', 'rec' ),
				'submitted' => __( 'Job submitted. <a target="_blank" href="%s">Preview Job</a>', 'rec' ),
				'scheduled' => __( 'Job scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Job</a>', 'rec' ),
				'draft_updated' => __( 'Job draft updated. <a target="_blank" href="%s">Preview Job</a>', 'rec' ),
				'revision_restored' => __( 'Job restored to revision from %s', 'rec' ),
			),
			'description'			=> '',
			'public'				=> true,
			'exclude_from_search'	=> false,
			'publicly_queryable'	=> true,
			'has_archive'			=> 'job-offers',
			'rewrite'				=> array(
					'slug' => 'job-offers',
					'with_front' => false,
			),
			'show_ui'				=> true,
			'show_in_nav_menus'		=> true,
			'show_in_menu'			=> true,
			'show_in_admin_bar'		=> false,
			'supports'				=> array( 'title', 'editor', 'thumbnail', 'author', 'revisions' ),
			'taxonomies' => array( 'job-category', 'tag' ),
			
			'meta_fields' => array(
				array(
					'name' => 'url',	
					'type' => 'url', 
					'label' => __( 'Job URL', 'rec' ),
					'validate' => 'esc_url',
				),
				array(
					'name' => 'budget',	
					'type' => 'number', 
					'label' => __( 'Budget', 'rec' )
				),
				array(
					'name' => 'production',	
					'type' => 'text', 
					'label' => __( 'Production', 'rec' )
				),
				array(
					'name' => 'date_creation',	
					'type' => 'date', 
					'label' => __( 'Date of Creation', 'rec' )
				),
				array(
					'name' => 'likecount',	
					'type' => 'number', 
					'label' => __( 'Apprectiation Count', 'rec' )
				),
			),
			
			'after_title' => array(
				'fields' => array( 'url', 'budget', 'production', 'date_creation', 'likecount' ),
				'callback' => 'wp_form_table',
				'before' => '<div class="postbox">',
				'after' => '</div>',
			),
			
			'after_editor' => false,
			
			'manage_edit_columns' => array(
				'cb' => null,
				'thumbnail' => '',
				'title' => __( 'Job', 'rec' ),
				'info' => __( 'Information', 'rec' ),
				'likecount' => __( 'Appreciations', 'rec' ),
				'author' => __( 'Author' ),
				'date' => __( 'Date' )
			),
			'manage_sortable_columns' => array(
				'title', 'date',
				'likecount' => array(
					'meta_key' => '_job_likecount',
					'orderby' => 'meta_value_num',
				),
				'author' => array(
					'orderby' => 'post_author',
				)
			),
			
			'custom_columns' => array( &$this, 'custom_columns_content' ),
			
		);
		
		register_post_type( 'job', $this->register_posts_args );
	
	}

	function return_duration( $value ) {
		if ( is_numeric( $value ) )
			return seconds_to_time( (int) $value, 'H:m:s' );
		return $value;
		
	}

	function edit_form_after_title() {
		global $post;
		
		if ( ! in_array( 'job', array( $post_type, $post->post_type ) ) )
			return;
		
		
	}
	
	function custom_columns_content( $column ) {
		global $post;
		
		switch ( $column ) {
			case 'thumbnail' :
				the_post_thumbnail( array( 80 ) );
				break;
				
			case 'info' :
				printf( 
					'<p><span class="url">%1$s</span></p><p>
					<span class="budget"><b>'.__( 'Budget', 'rec' ).':</b> %2$s &euro;</span><br>
					<span class="production"><b>'.__( 'Production', 'rec' ).':</b> %3$s</span><br>
					<span class="date_creation"><b>'.__( 'Date Creation', 'rec' ).':</b> %4$s</span></p>',
					make_clickable( get_post_meta( $post->ID, '_job_url', true ) ), 
					number_format_i18n( get_post_meta( $post->ID, '_job_budget', true ) ),
					get_post_meta( $post->ID, '_job_production', true ),
					format_time( get_post_meta( $post->ID, '_job_date_creation', true ) )
				);
				break;
			
			case 'likecount' :
				echo number_format_i18n( get_post_meta( $post->ID, '_job_likecount', true ) );
				break;
				
			case 'author' :
				echo get_the_author();
				break;
			
		}
		
	}
	
	function load_edit() {
		global $post_type;
		
		if ( ! in_array( 'job', array( $post_type, $_GET['post_type'] ) ) )
			return;
		
		?>
		<style type="text/css">
			.manage-column.column-thumbnail { width: 90px; }
			.column-thumbnail.thumbnail img { border:1px solid #ccc;}
			.manage-column.column-likecount { width: 10%; }
			.manage-column.column-info { width: 30%; }
		</style>
		<?php
	}


	/**
	 * Query job post type
	 * 
	 * @since 1.0
	 */
	function query_jobs( $query = array() ) {
		$this->query = wp_parse_args(
			$query, array(
				'post_type' => 'job'
			)
		);
		
		$this->queried = new WP_Query( $this->query );
		return $this->queried;
		
	}
	
	/**
	 * Checks if there are jobs for the given query
	 *
	 * @since 1.0
	 */
	function have_jobs( $query = array() ) {
		
		if ( ! $this->queried instanceof WP_Query )
			$this->query_jobs( $query );
		
		return ( $this->queried->have_posts() );
		
	}
	
	/**
	 * Checks if there are related jobs for the given query
	 *
	 * @since 1.0
	 */
	function have_related_jobs( $query = array() ) {
		global $post;
		
		if ( ! $this->queried instanceof WP_Query )
			$this->query_jobs( array_merge(
				array( 
					'tax_query' => array(
						array(
							'taxonomy' => 'tag',
							'field' => 'slug',
							'terms' => wp_get_object_terms( $post->ID, 'tag', array( 'fields' => 'slugs' ) ),
							'operator' => 'IN',
							) 
						)
					),
				(array) $query
				)
			);
			
		return ( $this->queried->have_posts() );
		
	}
	
	/**
	 * Sets global $post to job
	 *
	 * @since 1.0
	 */
	function the_job() {
		return ( $this->queried->the_post() );
		
	}
	
	/**
	 * Get a given $post_id job
	 * 
	 * @since 1.2
	 * 
	 * @param int $post_id
	 * @return object
	 */
	function get_job( $post_id ) {
		$post_id = (int) $post_id;
		$post = get_post( $post_id );
		
		if ( is_wp_error( $post ) )
			return false;
		
		if ( is_object( $post ) ) {
			foreach ( $this->register_posts_args['meta_fields'] as $meta_field )
				$metas[ $meta_field['name'] ] = get_post_meta( $post->ID, "_job_{$meta_field['name']}", true );
			
			return (object) array_merge( (array) $post, $metas );
			
		}
		
	}
	
	/**
	 * Get a given $post_id job
	 * 
	 * @since 1.2
	 * 
	 * @param int $post_id
	 * @return object
	 */
	function get_background_job() {
		$post = get_posts( array(
			'post_type' => 'job',
			'numberposts' => 1,
			'orderby' => 'rand',
			'meta_query' => array(
				array(
					'key' => '_job_homepage_background',
					'value' => 'show_in_background'
					),
				array(
					'key' => '_job_service',
					'value' => 'youtube'
					)
				),
			)
		);
		
		if ( is_wp_error( $post ) )
			return false;
		
		if ( isset( $post[0] ) && is_object( $post[0] ) ) {
			foreach ( $this->register_posts_args['meta_fields'] as $meta_field )
				$metas[ $meta_field['name'] ] = get_post_meta( $post[0]->ID, "_job_{$meta_field['name']}", true );
			
			return (object) array_merge( (array) $post[0], $metas );
			
		}
		
		return false;
		
	}
	
	/**
	 * Add a new job
	 * 
	 * @since 1.2
	 * 
	 * @param int $post_id
	 * @return object
	 */
	function add_job( $data, $user_id = '' ) {
		
		if ( is_object( $data ) ) {
			$data = (array) $data;
			
		}
		
		if ( '' == $user_id )
			$user_id = get_current_user_id();
		
		$postarr = array(
			'post_title' => $data['title'],
			'post_content' => $data['description'],
			'post_type' => 'job',
			'post_status' => 'publish',
			'post_author' => (int) $user_id,
		);
		
		$job_id = wp_insert_post( $postarr );
		
		foreach ( $this->register_posts_args['meta_fields'] as $meta_field ) {
			if ( isset( $data[ $meta_field['name'] ] ) )
				add_post_meta( (int) $job_id, '_job_'.$meta_field['name'], $data[ $meta_field['name'] ] );
			
		}
		
		return $job_id;
		
	}
	
	/**
	 * Add a new job by URL
	 * 
	 * @since 1.2
	 * 
	 * @param string URL
	 * @return object
	 */
	function add_job_by_url( $url, $user_id = '' ) {
		//json_decode( file_get_contents( 'https://gdata.youtube.com/feeds/api/jobs/TYIivzzteIk?v=2&alt=jsonc' ) );
		//http://youtu.be/TYIivzzteIk
		//http://www.youtube.com/watch?v=TYIivzzteIk&feature=related
		
		$job_id = $this->get_job_id_by_url( $url );
		
		if ( is_youtube_job( $url ) ) {
			$job = json_decode( file_get_contents( "https://gdata.youtube.com/feeds/api/jobs/{$job_id}?v=2&alt=jsonc" ) );
			$streams = $this->get_youtube_streams( $url );
			$data = array(
				'id' => $job->data->id,
				'title' => $job->data->title,
				'uploaded' => format_time( $job->data->uploaded, 'mysql' ),
				'category' => $job->data->category,
				'description' => $job->data->description,
				'thumbnail' => $job->data->thumbnail->hqDefault,
				'url' => esc_url( $url ),
				'duration' => $job->data->duration,
				'aspect_ratio' => $job->data->aspectRatio,
				'stream_urls' => $streams,
				'service' => 'youtube',
			);
			
		} elseif ( is_vimeo_job( $url ) ) {
			$job = json_decode( file_get_contents( "http://vimeo.com/api/v2/job/{$job_id}.json" ) );
			$stream = $this->get_vimeo_stream_url( $url, $job_id );
			$data = array(
				'id' => $job[0]->id,
				'title' => $job[0]->title,
				'uploaded' => $job[0]->upload_date,
				'category' => $job->data->tags,
				'description' => $job[0]->description,
				'thumbnail' => $job[0]->thumbnail_large,
				'url' => esc_url( $url ),
				'duration' => $job[0]->duration,
				'aspect_ratio' => $job[0]->width."x".$job[0]->height,
				'stream_urls' => $stream,
				'service' => 'vimeo'
			);
			
		}
		
		return $this->add_job( $data, $user_id );
		
	}
	
	/**
	 * Add a new job by URL
	 * 
	 * @since 1.2
	 * 
	 * @param string URL
	 * @return object
	 */
	function get_job_id_by_url( $url ) {
		
		if ( false !== $pos = strpos( $url, 'youtu.be/' ) ) {
			$job_id = substr( $url, $pos+9 );
			
		} elseif ( false !== $pos = strpos( $url, '/watch?v=' ) ) {
			if ( false !== strpos( $url, '&') )
				$job_id = substr( $url, $pos+9, strpos( $url, '&')-($pos+9) );
			else
				$job_id = substr( $url, $pos+9 );
			
		} elseif ( false !== $pos = strpos( $url, 'vimeo.com/' ) ) {
			if ( false !== strpos( $url, '&') )
				$job_id = substr( $url, $pos+10, strpos( $url, '&')-($pos+8) );
			else
				$job_id = substr( $url, $pos+10 );
			
		}
		
		return $job_id;
		
	}
	
	/**
	 * Add a new job by URL
	 * 
	 * @since 1.2
	 * 
	 * @param string URL
	 * @return object
	 */
	function get_vimeo_stream_url( $url, $job_id ) {
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
				'MP4' => "http://player.vimeo.com/play_redirect?clip_id={$job_id}&sig={$signature}&time={$timestamp}&quality=lp",
				'WEBM' => '',
				'3GP' => '',
			),
			'medium' => array(
				'MP4' => "http://player.vimeo.com/play_redirect?clip_id={$job_id}&sig={$signature}&time={$timestamp}&quality=sd",
				'WEBM' => '',
				'3GP' => '',
			),
			'high' => array(
				'MP4' => "http://player.vimeo.com/play_redirect?clip_id={$job_id}&sig={$signature}&time={$timestamp}&quality=hd",
				'WEBM' => '',
				'3GP' => '',
			)
		);
			
		
	}
	
	/**
	 * Add a new job by URL
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
			return new WP_Error( 'yt_adult_job', __( 'Adult Job Detected', 'rec' ) );

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
					$job_links[ $itag[1] ] = $urlm.'&signature='.$sig[1];
				}
				
        }
        
		$job_type[13] = array( "13", "3GP", 'low', "Low Quality - 176x144" );
		$job_type[17] = array( "17", "3GP", 'medium', "Medium Quality - 176x144" );
		$job_type[36] = array( "36", "3GP", 'high', "High Quality - 320x240" );
		$job_type[5]  = array( "5", "FLV", 'low', "Low Quality - 400x226" );
		$job_type[6]  = array( "6", "FLV", 'medium', "Medium Quality - 640x360" );
		$job_type[34] = array( "34", "FLV", 'medium', "Medium Quality - 640x360" );
		$job_type[35] = array( "35", "FLV", 'high', "High Quality - 854x480" );
		$job_type[43] = array( "43", "WEBM", 'low', "Low Quality - 640x360" );
		$job_type[44] = array( "44", "WEBM", 'medium', "Medium Quality - 854x480" );
		$job_type[45] = array( "45", "WEBM", 'high', "High Quality - 1280x720" );
		$job_type[18] = array( "18", "MP4", 'medium', "Medium Quality - 480x360" );
		$job_type[22] = array( "22", "MP4", 'high', "High Quality - 1280x720" );
		$job_type[37] = array( "37", "MP4", 'high', "High Quality - 1920x1080" );
		$job_type[33] = array( "38", "MP4", 'high', "High Quality - 4096x230" );
		
		foreach( $job_type as $format => $meta ) {
            if ( isset( $job_links[ $format ] ) ) {
            	$streams[ $meta[2] ][ $meta[1] ] = $job_links[ $format ];
                //$jobs[ $meta[2] ][] = array( 'ext' => strtolower( $meta[1] ), 'type' => $meta[3], 'url' => $job_links[ $format ] );
            } 
        }
		
		return $streams;
		
	}
	
}


/** Global object to manipulate job post type */
global $_job_post_type;
$_job_post_type = new REC_Job();


