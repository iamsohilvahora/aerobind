<?php

/**

 * The template for displaying the header

 * @package WordPress

 * @subpackage Baseline Custom

 */



?>

<!DOCTYPE html>

<html lang="en">

  <head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php wp_title(); ?></title>

    <link href="<?php bloginfo('template_directory');?>/includes/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

    <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory');?>/includes/slick/slick.css"/>

    <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/style.css" media="screen">
    
    <!--<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/includes/jquery-ui.css">-->

	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">  

	<link href="https://fonts.googleapis.com/css?family=Lato:300,400" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Raleway:500,600,700" rel="stylesheet">

	<?php wp_head(); ?>

    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>



<body <?php body_class(); ?>>



<!-- .site-header -->

<nav <?php if(is_product()) {echo 'id="stick"';} ?> class="navbar navbar-default navbar-fixed-top">

	<div class="container">

		<div class="navbar-header">

        	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">

            	<span class="sr-only">Toggle navigation</span>

            	<span class="icon-bar"></span>

            	<span class="icon-bar"></span>

            	<span class="icon-bar"></span>

          	</button>

            <a class="navbar-brand2" href="/aerobind/"><img src="<?php bloginfo('template_directory');?>/images/aerobind-logo.png" alt="Aerobind" /></a>

        </div>

        <!--This is menu with the click to drop down- currently the mobile one-->

        

        <div class="row navigator">

        <div class="container">

        	<div class="navbar-collapse collapse" aria-expanded="false">

             

            	

				<?php

					//wp_nav_menu( array(

					//'theme_location' => 'main',

					//'menu_class'     => 'nav navbar-nav navbar-center',

					//) );

				?>

               <ul class="nav navbar-nav navbar-center">

              		<li><a href="/aerobind/" <?php if ( is_front_page()) { echo 'class="active"'; } ?>>Home</a></li>

              		<li class="dropdown">
                    <a href="/aerobind/shop/" class="dropdown-toggle" data-toggle="dropdown" <?php if ( is_archive()) { echo ' class="active"'; } ?>>Products</a>

                    	<ul class="dropdown-menu">

                        	<?php //dynamic_sidebar( 'Filter' ); ?>

                        	<?php echo do_shortcode( '[ajax_product_filter data=filter:all:0]' ); ?>

                           <!--<li><a href="">TesT</a></li>
							<li><a href="">test</a></li>
                            <li><a href="">test</a></li>
                            <li><a href="">test</a></li>
                            <li><a href="">test</a></li>
                            <li><a href="">test</a></li> -->
                        </ul>

                    </li>

              		<li><a href="/aerobind/services/" <?php if ( is_page('services')) { echo ' class="active"'; } ?>>Services</a></li>

              		<li><a href="/aerobind/about-aerobind/" <?php if ( is_page('about-aerobind')) { echo ' class="active"'; } ?>>About</a></li>

              		<li><a href="/aerobind/contact/" <?php if ( is_page('contact')) { echo ' class="active"'; } ?>>Contact</a></li>

            	</ul>

			</div>

		</div>

	</div>

</nav>

<!-- .site-header -->



<?php if (is_front_page()) {?>



<!-- .site-jumbotron -->

<div class="jumbotron">

	<div class="embed-container">

    	<div class="embed-responsive embed-responsive-16by9">

            <video class="slider-video" width="1800" height="695" preload="auto" loop autoplay>

            	<source type="video/webm" src="http://baselinedev.com/aerobind/wp-content/uploads/Aeroflex-Checklist-Ring.webm">

                <source type="video/mp4" src="http://baselinedev.com/aerobind/wp-content/uploads/Aeroflex-Checklist-Ring.mp4">

                <source type="video/ogg" src="http://baselinedev.com/aerobind/wp-content/themes/baseline-custom/videos/big_buck_bunny.ogv">

            </video>

        </div>

	</div>

</div>

<!-- .site-jumbotron -->



<?php } ?>