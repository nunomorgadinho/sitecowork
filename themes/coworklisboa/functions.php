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
	
	wp_enqueue_script( 'rec', get_template_directory_uri() . '/js/rec.js');
	
	
//	wp_enqueue_script( 'small-menu', get_template_directory_uri() . '/js/small-menu.js', array( 'jquery' ), '20120206', true );
	wp_enqueue_script( 'sliding-form', get_template_directory_uri() . '/js/sliding.form.js', array( 'jquery' ), '20120206', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
	//	wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}
	
	
	
	wp_register_script( 'ajax-chosen',get_template_directory_uri(). '/js/chosen/ajax-chosen.jquery.js', array('jquery', 'chosen') );
	wp_register_script( 'chosen', get_template_directory_uri(). '/js/chosen/chosen.jquery.js', array('jquery'));
	
	wp_enqueue_script( 'wc-chosen',  get_template_directory_uri() . '/assets/js/frontend/chosen-frontend.js', array( 'chosen' ),  '',true );
	wp_enqueue_style( 'woocommerce_chosen_styles', get_template_directory_uri() . '/js/chosen/chosen.css' );
	
	
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






add_action('wp_ajax_nopriv_new_submit', 'new_submit_callback');
add_action('wp_ajax_new_submit', 'new_submit_callback');

/**
 * New user is checking for invite.
 * check if user already exists. If so return false otherwise
 */
function new_submit_callback(){
	global $wpdb;

	$submit_data = $_POST['submit_data'];
	$params = array();
	parse_str($submit_data, $params);
	
	$to = get_bloginfo('admin_email');
	$subject = 'Inscrição através do site do Coworklisboa';
	
	/* mail the message */	
	include("inc/welcome-user-notification-en.php");
	$message = $html;
	
	$message = str_replace('NAME', $params['name'], $message);
	$message = str_replace('ADDRESS', $params['address'], $message);
	$message = str_replace('EMAIL', $params['email'], $message);
	$message = str_replace('MOBILE', $params['mobile'], $message);
	
	$message = str_replace('WORKPLACE', $params['workplace'], $message);
	$message = str_replace('PLAN', $params['plan'], $message);
	$message = str_replace('LIFE', $params['life'], $message);
	
	$quote = random_quote();
	$message = str_replace('QUOTE', $quote, $message);
	
	add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
	$res = wp_mail($to, $subject, $message);
	
	die($meta);
}

function random_quote()
{
	$num = rand(1, 6);
	//Based on the random number, gives a quote
	switch ($num)
	{
		case 1:
			return "Time is money";
			
		case 2:
			return "An apple a day keeps the doctor away";
			
		case 3:
			return "Elmo loves dorthy";
			
		case 4:
			return "Off to see the wizard";
			
		case 5:
			return "Tomorrow is another day";
			
		case 6:
			return "Cowork extravangaza!";
	}
}
