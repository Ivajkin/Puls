<!DOCTYPE html>

<!--[if IEMobile 7]><html class="no-js ie iem7" <?php language_attributes(); ?> dir="ltr"><![endif]-->
<!--[if lte IE 6]><html class="no-js ie lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?> dir="ltr"><![endif]-->
<!--[if (IE 7)&(!IEMobile)]><html class="no-js ie lt-ie9 lt-ie8" <?php language_attributes(); ?> dir="ltr"><![endif]-->
<!--[if IE 8]><html class="no-js ie lt-ie9" <?php language_attributes(); ?> dir="ltr"><![endif]-->
<!--[if (gte IE 9)|(gt IEMobile 7)]><html class="no-js ie" <?php language_attributes(); ?> dir="ltr" prefix="content: http://purl.org/rss/1.0/modules/content/ dc: http://purl.org/dc/terms/ foaf: http://xmlns.com/foaf/0.1/ og: http://ogp.me/ns# rdfs: http://www.w3.org/2000/01/rdf-schema# sioc: http://rdfs.org/sioc/ns# sioct: http://rdfs.org/sioc/types# skos: http://www.w3.org/2004/02/skos/core# xsd: http://www.w3.org/2001/XMLSchema#"><![endif]-->
<!--[if !IE]><!--><html class="no-js" <?php language_attributes(); ?> dir="ltr" prefix="content: http://purl.org/rss/1.0/modules/content/ dc: http://purl.org/dc/terms/ foaf: http://xmlns.com/foaf/0.1/ og: http://ogp.me/ns# rdfs: http://www.w3.org/2000/01/rdf-schema# sioc: http://rdfs.org/sioc/ns# sioct: http://rdfs.org/sioc/types# skos: http://www.w3.org/2004/02/skos/core# xsd: http://www.w3.org/2001/XMLSchema#"><!--<![endif]-->


	<head>
		<meta charset="utf-8">

		<?php // Google Chrome Frame for IE ?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		<title><?php wp_title(''); ?></title>

		<?php // mobile meta (hooray!) ?>
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="width">
		<meta name="viewport" content="width=device-width"/>

		<?php // icons & favicons (for more: http://www.jonathantneal.com/blog/understand-the-favicon/) ?>
		<link rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/library/images/apple-icon-touch.png">
		<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.png">
		<!--[if IE]>
			<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">
		<![endif]-->
		<?php // or, set /favicon.ico for IE10 win ?>
		<meta name="msapplication-TileColor" content="#f01d4f">
		<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/library/images/win8-tile-icon.png">

		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

		<?php // wordpress head functions ?>
		<?php wp_head(); ?>
		<?php // end of wordpress head ?>

		<?php // drop Google Analytics Here ?>
		<?php // end analytics ?>

        <style type="text/css" class="before-after">
        </style>
	</head>

	<body <?php body_class(); ?>>

		<div id="container" class="<?php
        global $vars;
        echo omega_layout($vars['condition']);?>">

          <div class="h-container">
           <div class="h-wrapper">
			<header class="header" role="banner">

				<div id="inner-header" class="wrap cf">

					<?php // to use a image just replace the bloginfo('name') with your img src and remove the surrounding <p> ?>
					<div class="branding">
                        <p class="phone"><a href="tel:+74212640033">Телефон: 64-00-33</a></p>
                        <a href="<?php echo home_url(); ?>" rel="nofollow"><h1><?php bloginfo('name'); ?></h1></a>
                        <h2>Слоган Компании</h2>
                    </div>
					<?php // if you'd like to use the site description you can un-comment it below ?>
					<?php // bloginfo('description'); ?>


					<nav role="navigation">
						<?php wp_nav_menu(array(
    					'container' => false,                           // remove nav container
    					'container_class' => 'menu cf',                 // class of container (should you choose to use it)
    					'menu' => __( 'The Main Menu', 'bonestheme' ),  // nav name
    					'menu_class' => 'nav top-nav cf',               // adding custom nav class
    					'theme_location' => 'main-nav',                 // where it's located in the theme
    					'before' => '',                                 // before the menu
        			'after' => '',                                  // after the menu
        			'link_before' => '',                            // before each link
        			'link_after' => '',                             // after each link
        			'depth' => 0,                                   // limit the depth of the nav
    					'fallback_cb' => ''                             // fallback function (if there is one)
						)); ?>

					</nav>

				</div>
                <h2><span>Строительство сооружений</span><br /><span>любой сложности</span></h2>
			</header>
