<?php


/**
 * Show post thumbnail url
 *
 * @param string $thumb thumbnail name
 * @param string $default thumbnail default name
 * @return thumbnail url
 */
function _s_thumbnail_uri( $thumbnail = 'thumbnail', $post_id = null ) {
	global $post;

	if ( ! $post_id )
		$post_id = $post->ID;

	if ( has_post_thumbnail() ) {
		$src = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $thumbnail );
		return $src[0];

	} else {
		return get_template_directory_uri() . '/images/default-thumbnail.png';

	}

}

/**
 * Show category without style
 *
 * @param string $separator separator category
 * @return categoryes
 */
function _s_category( $separator = ', ' ) {

	$category = get_the_category();

	foreach( $category as $cat ) {
		$cats .= get_cat_name( $cat->category_parent ) . $separator;

	}

	return rtrim( $cats, $separator );

}


/**
 * Show taxonomy without style
 *
 * @param string $separator separator category
 * @return categoryes
 */
function _s_terms( $taxonomy, $post_id = null, $separator = ', ' ) {
	global $post;
	
	if ( ! $post_id )
		$post_id = $post->ID;
	
	
	return get_the_term_list( $post_id, $taxonomy, '', $separator, '' );

}

/**
 * Limit Quote
 *
 * @param integer $limit Limiting excerpt
 * @return excerpt
 */
function _s_excerpt( $limit = false, $excerpt = null ) {
	if ( ! $excerpt )
		$excerpt = get_the_excerpt();

	if ( ! $limit )
		$limit = apply_filters( 'excerpt_length', 30 );


	mb_internal_encoding( 'UTF-8' );

	if ( mb_strlen( $excerpt ) > $limit )
		$dots = apply_filters( 'excerpt_more', '[...]' );


	$excerpt = mb_substr( esc_html( $excerpt ), 0, $limit );

	return $excerpt;

}


/**
 * Show current page keywoard from tags
 *
 * @param string $separator separator tags
 * @return tags
 */
function _s_keywoard( $separator = ', ' ) {

	if ( get_the_tags() ) {
		$posttags = get_the_tags();

		foreach ( (array) $posttags as $tag ) {
			$tags .= $tag->name . $separator;

		}

		return $tags;

	}

}


if ( ! function_exists( '_s_google_plus_publisher' ) ):
/**
 * Return the Google Plus publisher ID
 * based on the author of the post
 * 
 * @since 1.1
 * 
 * @return string The Publisher ID
 */

function _s_google_plus_publisher() {
	return get_option( '_s_google_plus_publisher' );
	
}
endif; // _s_google_plus_publisher

if ( ! function_exists( '_s_paginated' ) ):
/**
 * Evaluate if actual query is paginated
 * 
 * @since 1.1
 * 
 * @return bool
 */
function _s_paginated() {
	global $wp_query;
	
	return ( $wp_query->max_num_pages > 1 );
	
}
endif; // _s_paginated


if ( ! function_exists( '_s_cache' ) ):
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
function _s_cache( $key, $data, $cachetime = 3600 ) {
	
	if ( is_wp_error( $data ) || empty( $data ) ) :
		return false;
	
	else :
		return set_transient( $key, $data, (int) $cachetime );
		
	endif;
	
}
endif; // _s_cache


/**
 * Return an array list of terms objects
 * @since 1.1
 * 
 */
function _s_get_all_terms( $orderby = 'count' ) {
	
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
	
	_s_cache( '_s_get_all_terms_orderby_' . $orderby, $terms, 3600 );
	
	return $terms;
	
}
