<?php
/*
 * @package: WBootStrap
 * @file: header.php
 */

?>
<!DOCTYPE html>
<!--[if IEMobile 7 ]> <html <?php language_attributes(); ?>class="no-js iem7"> <![endif]-->
<!--[if lt IE 7 ]> <html <?php language_attributes(); ?> class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html <?php language_attributes(); ?> class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html <?php language_attributes(); ?> class="no-js ie8"> <![endif]-->
<!--[if (gte IE 9)|(gt IEMobile 7)|!(IEMobile)|!(IE)]><!--><html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		
		<title><?php wp_title('', true, 'right'); ?></title>
				
		<meta name="viewport" content="width=device-width; initial-scale=1.0">

		<!-- media-queries.js (fallback) -->
		<!--[if lt IE 9]>
			<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>			
		<![endif]-->

		<!-- html5.js -->
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/bootstrap.css">
		<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/style.min.css">
		<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style.css">
		<style>
		  body {
			padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
		  }
		</style>
		<link href="<?php echo get_template_directory_uri(); ?>/assets/css/bootstrap-responsive.css" rel="stylesheet">

		<!-- icons -->
		<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon.ico">
		<link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/assets/images/apple-touch-icon.png">
		<link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_template_directory_uri(); ?>/assets/images/apple-touch-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_template_directory_uri(); ?>/assets/images/apple-touch-icon-114x114.png">
		
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
		
		<!-- wordpress head functions -->
		<?php wp_head(); ?>
		<!-- end of wordpress head -->
	</head>

	<body <?php body_class();?>>
	 <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="<?php bloginfo('url')?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo('sitename'); ?></a>
          <div class="nav-collapse">
				<?php //top menu 
					wp_nav_menu(array(
						'menu' => 'top_menu',
						'container' => false, 
						'menu_class' => 'nav', 
						'menu_id' => 'top_menu',
						'fallback_cb' =>'top_menu_fallback'
					)); 
				?>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
	
	<header class="jumbotron masthead" id="overview">
      <div class="inner">
        <div class="container">
			<div class="hero-unit">
			  <h1>Bootstrap, from Twitter</h1>
			  <p class="lead">
				Bootstrap is a toolkit from Twitter designed to kickstart development of webapps and sites.<br>
				It includes base CSS and HTML for typography, forms, buttons, tables, grids, navigation, and more.<br>
			  </p>
			  <p><strong>Nerd alert:</strong> Bootstrap is <a href="#less" title="Read about using Bootstrap with Less">built with Less</a> and was designed to work out of the gate with modern browsers in mind.</p>
			</div>
        </div><!-- /container -->
      </div>
    </header>
	
    <div class="container">