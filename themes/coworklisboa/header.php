<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Rec
 * @since Rec 1.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<!--[if IE 8]>
<link href="<?php echo get_bloginfo('stylesheet_directory'); ?>/ie8.css" rel="stylesheet" type="text/css">
<![endif]-->

<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	
<div id="page" class="hfeed site">
	<?php do_action( 'before' ); ?>

<div class="container_side_left">	
	<header id="masthead" class="site-header" role="banner">
		<!-- <hgroup>
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
		</hgroup> -->

		<nav role="navigation" class="site-navigation main-navigation">
			<h1 class="assistive-text"><?php _e( 'Menu', 'rec' ); ?></h1>
			<div class="assistive-text skip-link"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'rec' ); ?>"><?php _e( 'Skip to content', 'rec' ); ?></a></div>

			<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
			

				<ul class="social-links">
					<li class="facebook"><a href="http://www.facebook.com/Coworklisboalx?fref=ts" target="_blank"><?php _e('Facebook','rec'); ?></a></li>
					<li class="twitter"><a href="http://twitter.com/coworklisboa" target="_blank"><?php _e('Twitter','rec'); ?></a></li>
				</ul>
		</nav><!-- .site-navigation .main-navigation -->
	</header><!-- #masthead .site-header -->
</div>


	<div id="main" class="site-main">
