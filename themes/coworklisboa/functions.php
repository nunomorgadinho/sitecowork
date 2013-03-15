<?php
/**
 * Rec functions and definitions
 *
 * @package Rec
 * @since Rec 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Rec 1.0
 */
if ( ! isset( $content_width ) )
	$content_width = 640; /* pixels */

if ( ! function_exists( 'rec_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since Rec 1.0
 */
function rec_setup() {
		
	require( get_template_directory() . '/inc/ajax.php' );
	require( get_template_directory() . '/inc/spam.php' );
	require( get_template_directory() . '/inc/functions.php' );
	
	/**
	 * Custom post types wrapper
	 */
	require( get_template_directory() . '/inc/libs/class-custom-post-types.php' );
	/**
	 * Require P2P connections wrapper-class
	 */
	require( get_template_directory() . '/inc/libs/class-object-connections.php' );
	
	/**
	 * Load custom post types
	 */
	foreach ( glob( get_template_directory() . '/inc/custom-post-types/*.php' ) as $post_type )
		include $post_type;
	
	/**
	 * Load custom post types
	 */
	foreach ( glob( get_template_directory() . '/inc/object-connections/*.php' ) as $connection )
		include $connection;
	
	/**
	 * Require user tweaks
	 */
	require( get_template_directory() . '/inc/user.php' );
	require( get_template_directory() . '/inc/taxonomies/user-role.php' );

	/**
	 * Custom template tags for this theme.
	 */
	require( get_template_directory() . '/inc/template-tags.php' );

	/**
	 * Custom functions that act independently of the theme templates
	 */
	require( get_template_directory() . '/inc/extras.php' );

	/**
	 * Customizer additions
	 */
	require( get_template_directory() . '/inc/customizer.php' );

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on Rec, use a find and replace
	 * to change 'rec' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'rec', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'rec' ),
	) );

	/**
	 * Enable support for Post Formats
	 */
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );
}
endif; // rec_setup
add_action( 'after_setup_theme', 'rec_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since Rec 1.0
 */
function rec_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Sidebar', 'rec' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h1 class="widget-title">',
		'after_title' => '</h1>',
	) );
}
add_action( 'widgets_init', 'rec_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function rec_scripts() {
	wp_enqueue_style( 'jquery-ui', 'http://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css' );
	wp_enqueue_style( 'style', get_stylesheet_uri() );
	
	wp_enqueue_script( 'validate', 'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/jquery.validate.min.js', array( 'jquery', 'jquery-form' ) );
	wp_enqueue_script( 'rec', get_template_directory_uri() . '/js/rec.js', array( 'validate', 'jquery-ui-autocomplete' ) );
	wp_localize_script( 'rec', 'rec', array(
		'minlength' => __( 'Provide at least {0} characters.', 'rec' ),
		'maxlength' => __( 'Maximum of {0} characters reached.', 'rec' ),
		'invalid_name' => __( 'Name cannot be empty.', 'rec' ),
		'name_incomplete' => __( 'Provide at least First and Last names.', 'rec' ),
		'invalid_email' => __( 'Provide a valid email.', 'rec' ),
		'email_exists' => __( 'This email is already registered.', 'rec' ),
		'invalid_role' => __( 'Provide, at least, one role.', 'rec' ),
		'invalid_url' => __( "Copy/paste links for your 3 best YouTube/Vimeo videos here.", 'rec' ),
		'validate_name_ajax' => admin_url( 'admin-ajax.php?action=check_for_name_length' ),
		'validate_email_ajax' => admin_url( 'admin-ajax.php?action=check_for_email' ),
		'available_roles' => get_terms( 'user_role', array( 'fields' => 'names', 'hide_empty' => false ) ),
		'get_video_background' => admin_url( 'admin-ajax.php?action=get_video_background' ),
		'valid_url' => admin_url( 'admin-ajax.php?action=validate_url' ),
		'validate_url' => __( 'Only YouTube.com and Vimeo.com videos.', 'rec' ),
		)
	);

	
	wp_enqueue_script( 'small-menu', get_template_directory_uri() . '/js/small-menu.js', array( 'jquery' ), '20120206', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}
}
add_action( 'wp_enqueue_scripts', 'rec_scripts' );


// enabled use of var ajaxurl on frontend
add_action('wp_head','rec_ajaxurl');
function rec_ajaxurl() {
	?>
<script type="text/javascript">
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>
<?php
}

/**
 * Implement the Custom Header feature
 */
//require( get_template_directory() . '/inc/custom-header.php' );



/* quiz answer */
add_action('wp_ajax_nopriv_request_invite', 'request_invite_callback');
add_action('wp_ajax_request_invite', 'request_invite_callback');

/**
 * change_personal_data PERFIL DE BELEZA
 *
 */
function request_invite_callback(){
	
	$myEmailVariable=$_POST['user_email'] ;
	
	
	
	//in this array firstname and lastname are optional
	$userData=array(
			'email'=>$myEmailVariable,
			'firstname'=>'',
			'lastname'=>'');
	
	$data=array(
			'user'=>$userData,
			'user_list'=>array('list_ids'=>array(1))
	);
	
	$userHelper=&WYSIJA::get('user','helper');
	$res = $userHelper->addSubscriber($data);
	
	
	if($res)
	{
		$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);	
		switch ($lang){
			case "pt":
				include("inc/welcome-user-notification-pt.php");
				break;
			case "en":
			case "en-US":
			case "en-UK":
				include("inc/welcome-user-notification-en.php");
				break;
			default:
				include("inc/welcome-user-notification.php");
				break;
		}
		
		$message = $html;
		
		add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
		
		$res = wp_mail($myEmailVariable, __('Bienvenido a REC!','rec'), $message);

		
	}
	else {
			
//		die(json_encode($userHelper->getMsgs()));
		die(__('Por favor, introduzca un correo electrónico válido','rec'));
	}
	
	die(1);
}



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
