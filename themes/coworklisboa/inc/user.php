<?php

/**
 * User Tweaks
 */

function rec_user_contactmethods( $user_contactmethods ) {	
	$user_contactmethods['twitter'] = __( 'Twitter Username', 'rec' );
	$user_contactmethods['facebook'] = __( 'Facebook Username', 'rec' );
	$user_contactmethods['linkedin'] = __( 'LinkedIn URL', 'rec' );
	$user_contactmethods['youtube'] = __( 'YouTube Username', 'rec' );
	$user_contactmethods['vimeo'] = __( 'Vimeo Plus ID', 'rec' );
	
	$user_contactmethods['url_1'] = __( 'Submitted URL', 'rec' );
	$user_contactmethods['url_2'] = __( 'Submitted URL', 'rec' );
	$user_contactmethods['url_3'] = __( 'Submitted URL', 'rec' );

	return $user_contactmethods;
  
}
add_filter( 'user_contactmethods', 'rec_user_contactmethods' );


