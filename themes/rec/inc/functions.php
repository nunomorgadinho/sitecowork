<?php


/**
 * Show current page keywoard from tags
 *
 * @param string $separator separator tags
 * @return tags
 */
function pop_keywoard( $separator = ', ' ) {

	if ( get_the_tags() ) {
		$posttags = get_the_tags();

		foreach ( (array) $posttags as $tag ) {
			$tags .= $tag->name . $separator;

		}

		return $tags;

	}

}



/**
 * Add cache (wrapper/helper)
 * 
 * @since 1.1
 * 
 * @param string $key
 * @param string $data
 * @param int $cachetime
 * @return bool
 */
function pop_set_cache( $key, $data, $cachetime = 3600 ) {
	
	if ( is_wp_error( $data ) || empty( $data ) ) :
		return false;
	
	else :
		return set_transient( $key, $data, (int) $cachetime );
		
	endif;
	
}


/**
 * Return an array list of terms objects
 * @since 1.1
 * 
 */
function pop_get_all_terms( $orderby = 'count' ) {
	
	if ( $terms = get_transient( '_s_get_all_terms_orderby_' . $orderby ) )
		return $terms;
		
		
	$terms = (array) get_terms( 'post_tag', array(
			'orderby' => $orderby,
			'hierarchical' => 0,
			'pad_counts' => 1,
			'cache_domain' => 'default_term_all',
		)
	);

	foreach ( $terms as $k => $t ) {
		$terms[ $k ] = array(
			'id'			=> $t->name,
			'text'			=> $t->name,
			'name' 			=> $t->name,
			'slug' 			=> $t->slug,
			'url' 			=> get_term_link( $t, 'post_tag' ),
			'description'	=> $t->description,
			'count'			=> $t->count,

		);

	}
	
	pop_set_cache( '_s_get_all_terms_orderby_' . $orderby, $terms, 3600 );
	
	return $terms;
	
}

/**
 * Sets the language
 */
function set_language(){

	$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	
	$request = $_SERVER["REQUEST_URI"];
	
	switch ($lang){
		case "pt":
			$url = get_bloginfo('siteurl').'/pt-pt/';
			
			if(!preg_match("/pt-pt/",$request) && !preg_match("/en/",$request))
				wp_safe_redirect($url);
			
			break;
		case "en":
		case "en-US":
		case "en-UK":
			$url = get_bloginfo('siteurl').'/en/';
			if(!preg_match("/en/",$request) && !preg_match("/pt-pt/",$request))
				wp_safe_redirect($url);
			break;
		
		default:
			//do nothing
		break;
	}
	
}
