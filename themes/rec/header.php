<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package rec
 * @since rec 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<?php do_action( 'before' ); ?>
	<header id="masthead" class="site-header" role="banner">

		<nav role="navigation" class="site-navigation main-navigation">
			<a class="site-title" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/reclogo-home.png"/></a>
			<h1 class="assistive-text"><?php _e( 'Menu', 'rec' ); ?></h1>
			<!-- <div class="assistive-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'rec' ); ?>"><?php _e( 'Skip to content', 'rec' ); ?></a></div> -->
			<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
				
				<?php if(is_user_logged_in()) {
					?>
			<section class="user-area">
			<div class="user-box">
						<h1 class="user-title"><a href="#"><?php global $current_user;
	      				get_currentuserinfo();
	      				echo  $current_user->display_name ?></a></h1>
	      				<ul class="user-option-list">
							<li class="user-option"><a href="#"><?php _e('My Projects','rec'); ?></a></li>
							<li class="user-option"><a href="#"><?php _e('My Profile','rec'); ?></a></li>
							<li class="user-option"><a href="#"><?php _e('Add Contest','rec'); ?></a></li>
							<li class="user-option"><a href="#"><?php _e('Add Project','rec'); ?></a></li>
						</ul>
			</div>
				<section class="notification-section">
					<p>10</p> 
					<ul>
						<li>
							<a href"#">John liked your video!</a>
						</li>
						<li>
							Lily also liked your video.
						</li>
					</ul>
				</section>
			</section>
				<?php	
				} else {
					?>
			<section class="user-area">
					<div class="user-box">
					<h1 class="user-title"><a href"#"><?php _e('Register / Log in','rec'); ?></a></h1>
			</section>
					<?php
				} ?>
			<section class="header-search search-element"><?php dynamic_sidebar('Search'); ?></section>
		</nav><!-- .site-navigation .main-navigation -->
	</header><!-- #masthead .site-header -->
 		
	<div id="main" class="site-main">
