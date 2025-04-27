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

    <script src="https://www.google.com/recaptcha/api.js"></script>

	<!--<script src="http://baselinedev.com/aerobind/wp-content/themes/baseline-custom/js/makefixed.js"></script>
    <script src="http://baselinedev.com/aerobind/wp-content/themes/baseline-custom/js/makefixed.min.js"></script>
<script>
jQuery(document).ready(function($)
		{
            var body = $('body');
			$(".slick-list").makeFixed();
			
			});	
			</script>-->
  <!--<script type="text/javascript" language="javascript">
function moveScrollerTop() {
    var move = function() {
        var st = jQuery(window).scrollTop();
        //var ot = jQuery("#aerostick").offset().top;
        var ot = '180';
        var s = jQuery("#aerostick");
        if(st > ot) {
            s.css({
                position: "fixed",
                top: "200px"
            });
        } else {
            if(st <= ot) {
                s.css({
                    position: "relative",
                    top: ""
                });
            }
        }
    };
    jQuery(window).scroll(move);
    move();
}

jQuery(function() {
    moveScrollerTop();
  });
</script>       -->   
            
<script type="text/javascript">

function HideContent(d) {
  document.getElementById(d).style.display = "none";
}

function ShowContent(d) {
  document.getElementById(d).style.display = "block";
}

			
</script>


	

</head>



<body <?php body_class(); ?>>



<!-- .site-header -->

<nav <?php if(is_product()) {echo 'id="stick"';} ?> class="navbar navbar-default navbar-fixed-top">

	<div class="container">

		<div class="navbar-header">

        	<button id="ChangeToggle" type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<div id="navbar-hamburger">
            	<span class="sr-only">Toggle navigation</span>

            	<span class="icon-bar"></span>

            	<span class="icon-bar"></span>

            	<span class="icon-bar"></span>
                </div>
                <div id="navbar-close" class="hidden">
          			<i class="fa fa-times" aria-hidden="true"></i>
        		</div>
          	</button>

            <a class="navbar-brand2" href="/aerobind/"><img src="<?php bloginfo('template_directory');?>/images/aerobind-logo.png" alt="Aerobind" /></a>

        </div>

        <!--This is menu with the click to drop down- currently the mobile one-->

       

        <div class="row navigator">

        <div class="container">

        	<div class="navbar-collapse collapse">

   
   
   
             

            	

				<?php

					//wp_nav_menu( array(

					//'theme_location' => 'main',

					//'menu_class'     => 'nav navbar-nav navbar-center',

					//) );

				?>
<?php
$username = "baseline_basedev";
$password = "Iv!UN]#hqT*i";
$hostname = "localhost";
$dbhandle = @mysql_connect($hostname, $username, $password);
$selected = mysql_select_db("baseline_aerobind_wp",$dbhandle) 
  or die("Could not select examples");
?>
 
               <ul class="nav navbar-nav navbar-center">

              		<li><a href="/aerobind/" <?php if ( is_front_page()) { echo 'class="active"'; } ?>>Home</a></li>

              		<li id="drop" class="dropdown">
                    
                    <a class="dropdown-toggle" data-toggle="dropdown"  aria-expanded="true" ><span id="dropdown_title">Products</span></a>
						
                    	<ul  id="cat"  class="dropdown-menu" >

                    	
						<li class="options">Scroll Down for Product Options <i class="fa fa-angle-down" aria-hidden="true"></i></li>

                        <li class="cat" onclick="ShowContent('cat_1'); HideContent('cat_2'); HideContent('cat_3'); HideContent('cat_4'); HideContent('cat_5'); HideContent('cat_6');  HideContent('cat');"><a href="#"><img  src="http://baselinedev.com/aerobind/wp-content/uploads/category-placeholder-150x150.jpg"/><br>
Ring Binders
<div class="overlay one">
	<?php $result = mysql_query("SELECT description FROM wp_term_taxonomy WHERE term_id = 112");
	//fetch tha data from the database
	$row = mysql_fetch_array($result);
   	echo "<p>".$row{'description'}."</p>";?>
</div></a>
</li>
                        <li class="cat" onclick="ShowContent('cat_2'); HideContent('cat_1'); HideContent('cat_3'); HideContent('cat_4'); HideContent('cat_5'); HideContent('cat_6'); HideContent('cat');" ><a href="#"><img  src="http://baselinedev.com/aerobind/wp-content/uploads/category-placeholder-150x150.jpg"/><br>Divider Tabs
                        <div class="overlay two">
	<?php $result = mysql_query("SELECT description FROM wp_term_taxonomy WHERE term_id = 120");
	//fetch tha data from the database
	$row = mysql_fetch_array($result);
   	echo "<p>".$row{'description'}."</p>";?>
</div>
                        </a>
                       <!-- <ul class = "submenu">
                            <li><a href="#">Hole Count</a></li>
                            <li><a href="#">Material</a></li>
                            <li><a href="#">Aircraft/OEM</a></li>
                            <li><a href="#">Custom Quote</a></li>
                        </ul> -->
                        </li>
                        <li class="cat" onclick="ShowContent('cat_3'); HideContent('cat_1'); HideContent('cat_2');  HideContent('cat_4'); HideContent('cat_5'); HideContent('cat_6'); HideContent('cat');" ><a href="#"><img  src="http://baselinedev.com/aerobind/wp-content/uploads/category-placeholder-150x150.jpg"/><br>
Sheet Protectors
<div class="overlay three">
	<?php $result = mysql_query("SELECT description FROM wp_term_taxonomy WHERE term_id = 119");
	//fetch tha data from the database
	$row = mysql_fetch_array($result);
   	echo "<p>".$row{'description'}."</p>";?>
</div>
</a>
<!--<ul class = "submenu">
                            <li><a href="#">Hole Count</a></li>
                            <li><a href="#">Aircraft/OEM</a></li>
                            </ul>--></li>
                        <li class="cat" onclick="ShowContent('cat_4'); HideContent('cat_1'); HideContent('cat_2');  HideContent('cat_3'); HideContent('cat_5'); HideContent('cat_6'); HideContent('cat');"><a href="#"><img  src="http://baselinedev.com/aerobind/wp-content/uploads/category-placeholder-150x150.jpg"/><br>
Checklist Covers
<div class="overlay four">
	<?php $result = mysql_query("SELECT description FROM wp_term_taxonomy WHERE term_id = 118");
	//fetch tha data from the database
	$row = mysql_fetch_array($result);
   	echo "<p>".$row{'description'}."</p>";?>
</div>
</a>
<!--<ul class = "submenu">
                            <li><a href="#">Hole Count</a></li>
                            <li><a href="#">Aircraft/OEM</a></li>
                            <li><a href="#">Cover Type</a></li>
                            <li><a href="#">Checklist Covers</a></li>
                        </ul>-->
</li>
                        <li class="cat" onclick="ShowContent('cat_5'); HideContent('cat_1'); HideContent('cat_2');  HideContent('cat_3'); HideContent('cat_4'); HideContent('cat_6'); HideContent('cat');"><a href="#"><img  src="http://baselinedev.com/aerobind/wp-content/uploads/category-placeholder-150x150.jpg"/><br>
Paper Punches
<div class="overlay five">
	<?php $result = mysql_query("SELECT description FROM wp_term_taxonomy WHERE term_id = 121");
	//fetch tha data from the database
	$row = mysql_fetch_array($result);
   	echo "<p>".$row{'description'}."</p>";?>
</div>
</a>
<!--<ul class = "submenu">
                            <li><a href="#">Hole Pattern</a></li>
                            <li><a href="#">Punch Type</a></li>
                            <li><a href="#">Aircraft/OEM</a></li>
                            </ul>--></li>
                        <li class="cat" onclick="ShowContent('cat_6'); HideContent('cat_1'); HideContent('cat_2');  HideContent('cat_3'); HideContent('cat_4'); HideContent('cat_5'); HideContent('cat');"><a href="#"><img  src="http://baselinedev.com/aerobind/wp-content/uploads/category-placeholder-150x150.jpg"/><br>
Paper Stock
<div class="overlay six">
	<?php $result = mysql_query("SELECT description FROM wp_term_taxonomy WHERE term_id = 122");
	//fetch tha data from the database
	$row = mysql_fetch_array($result);
   	echo "<p>".$row{'description'}."</p>";?>
</div>
</a>
<!--<ul class = "submenu">
                            <li><a href="#">Page Size</a></li>
                            <li><a href="#">Hole Count</a></li>
                            <li><a href="#">Paper Weight</a></li>
                            <li><a href="#">Paper Stock</a></li>
                        </ul>--></li>
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

<?php //close the connection
mysql_close($dbhandle);
?>			

		</div>
        
</div>

</div>




      

<div class="holder">     	
<div id="cat_1" style="display:none;">
              		
<div class="column" onclick="HideContent('cat_1'); HideContent('cat_2'); HideContent('cat_3'); HideContent('cat_4'); HideContent('cat_5'); HideContent('cat_6'); ShowContent('cat');"><img  src="http://baselinedev.com/aerobind/wp-content/themes/baseline-custom/images/green.png" style="width:20px;"/>back</div>
<ul class="attributes">
                        <li><a href="#"><img  src="http://baselinedev.com/aerobind/wp-content/uploads/category-placeholder-150x150.jpg"/><br>
Ring Binders</a>
<!--<ul class = "submenu">
                            <li><a href="#">Ring Count</a></li>
                            <li><a href="#">Aircraft/OEM</a></li>
                            <li><a href="#">Brand</a></li>
                            <li><a href="#">Binders</a></li>
                        </ul>-->
</li>
                        <li><small>Ring Count</small>
                        <ul class="submenu">
                            <li><a href="#">1-Ring</a></li>
                            <li><a href="#">7-Ring</a></li>
                            <li><a href="#">9-Ring</a></li>
                            <li><a href="#">10-Ring</a></li>
                            <li><a href="#">11-Ring</a></li>
                            <li><a href="#">12-Ring</a></li>
                            <li><a href="#">13-Ring</a></li>
                            <li><a href="#">22-Ring</a></li>
                        </ul>
                        </li>
                        <li class="att-start">
<small>Aircraft/OEM</small>
<ul class = "submenu">
                            <li><a href="#">Airbus</a></li>
                            <li><a href="#">Dassault</a></li>
                            <li><a href="#">Boeing</a></li>
                            <li><a href="#">Gulfstream</a></li>
                            <li><a href="#">Jeppesen</a></li>
                            <li><a href="#">Learjet</a></li>
                            <li><a href="#">Embraer</a></li>
                            <li><a href="#">Hawker</a></li>
                            <li><a href="#">Beechcraft</a></li>
                            <li><a href="#">Cessna</a></li>
                            <li><a href="#">Bombardier</a></li>
                            <li><a href="#">Military</a></li>
                            
                            </ul></li>
                        <li class="att-start"><small>Brand</small>
<ul class = "submenu">
                            <li><a href="#">AEROMETAL</a></li>
                            <li><a href="#">AERAD</a></li>
                            <li><a href="#">AEROBINDER II</a></li>
                            
                        </ul>
</li>
                        <li class="att-start"><small>Custom Ring Binder</small>
<ul class = "submenu">
                            <li><a href="#">Get a Quote</a></li>
                          
                       </ul>		
</li>
</ul>


	</div>

<div id="cat_2" style="display:none;">

<div class="column" onclick="HideContent('cat_1'); HideContent('cat_2'); HideContent('cat_3'); HideContent('cat_4'); HideContent('cat_5'); HideContent('cat_6'); ShowContent('cat');"><img  src="http://baselinedev.com/aerobind/wp-content/themes/baseline-custom/images/green.png" style="width:20px;"/>back</div>
<ul class="attributes">
                        <li><a href="#"><img width="40" height="40" src="http://baselinedev.com/aerobind/wp-content/uploads/category-placeholder-150x150.jpg"/><br>
Divider Tabs</a>
<!--<ul class = "submenu">
                            <li><a href="#">Ring Count</a></li>
                            <li><a href="#">Aircraft/OEM</a></li>
                            <li><a href="#">Brand</a></li>
                            <li><a href="#">Binders</a></li>
                        </ul>-->
</li>
                        <li><span class="border">Hole Count</span>
                        <ul class = "submenu">
                            <li><a href="#">7 Hole</a></li>
                            <li><a href="#">11 Hole</a></li>
                            <li><a href="#">22 Hole</a></li>                            
                        </ul>
                        </li>
                        <li class="att-start">
<small>Material</small>
<ul class = "submenu">
                            <li><a href="#">Cardstock</a></li>
                            <li><a href="#">Synthetic (no tear)</a></li>
                            </ul></li>
                        <li class="att-start">
<small>Aircraft/OEM</small>
<ul class = "submenu">
                            <li><a href="#">Boeing</a></li>
                            <li><a href="#">Airbus</a></li>
                            <li><a href="#">Embraer</a></li>
                            <li><a href="#">Hawker</a></li>
                            <li><a href="#">Beechcraft</a></li>
                            <li><a href="#">Cessna</a></li>
                            <li><a href="#">Bombardier</a></li>
                            <li><a href="#">Gulfstream</a></li>
                            <li><a href="#">Jeppesen</a></li>
                            
                        </ul>
</li>
                        <li class="att-start">
<small>Custom Divider Tabs</small>
<ul class = "submenu">
                            <li><a href="#">Get a Quote</a></li>
                          
                            </ul></li>
                       
            	</ul>

	</div>

<div id="cat_3" style="display:none;">


<div class="column" onclick="HideContent('cat_1'); HideContent('cat_2'); HideContent('cat_3'); HideContent('cat_4'); HideContent('cat_5'); HideContent('cat_6'); ShowContent('cat');"><img  src="http://baselinedev.com/aerobind/wp-content/themes/baseline-custom/images/green.png" style="width:20px;"/>back</div>
<ul class="attributes">
                        <li><a href="#"><img width="40" height="40" src="http://baselinedev.com/aerobind/wp-content/uploads/category-placeholder-150x150.jpg"/><br>
Sheet Protectors</a>
<!--<ul class = "submenu">
                            <li><a href="#">Ring Count</a></li>
                            <li><a href="#">Aircraft/OEM</a></li>
                            <li><a href="#">Brand</a></li>
                            <li><a href="#">Binders</a></li>
                        </ul>-->
</li>
                        <li><small>Hole Count</small>
                        <ul class = "submenu">
                            <li><a href="#">7 Hole</a></li>
                            <li><a href="#">10 Hole</a></li>
                            <li><a href="#">11 Hole</a></li>
                            <li><a href="#">22 Hole</a></li>
                        </ul>
                        </li>
                        <li class="att-start">
<small>Aircraft/OEM</small>
<ul class = "submenu">
                            <li><a href="#">Boeing</a></li>
                            <li><a href="#">Gulfstream</a></li>
                            <li><a href="#">Jeppesen</a></li>
                            <li><a href="#">Learjet</a></li>
                            <li><a href="#">Embraer</a></li>
                            <li><a href="#">Hawker</a></li>
                            <li><a href="#">Beechcraft</a></li>
                            <li><a href="#">Cessna</a></li>
                            <li><a href="#">Bombardier</a></li>
                            <li><a href="#">Airbus</a></li>
                         
                            </ul></li>
                        <li class="att-start">
<small>Custom Sheet Protectors</small>
<ul class = "submenu">
                            <li><a href="#">Get a Quote</a></li>
                          
                            </ul></li>
                       

              		

            	</ul>

		


	</div>
 
    <div id="cat_4" style="display:none;">


<div class="column" onclick="HideContent('cat_1'); HideContent('cat_2'); HideContent('cat_3'); HideContent('cat_4'); HideContent('cat_5'); HideContent('cat_6'); ShowContent('cat');"><img  src="http://baselinedev.com/aerobind/wp-content/themes/baseline-custom/images/green.png" style="width:20px;"/>back</div>
<ul class="attributes">
                        <li><a href="#"><img width="40" height="40" src="http://baselinedev.com/aerobind/wp-content/uploads/category-placeholder-150x150.jpg"/><br>
Checklist Covers</a>
<!--<ul class = "submenu">
                            <li><a href="#">Ring Count</a></li>
                            <li><a href="#">Aircraft/OEM</a></li>
                            <li><a href="#">Brand</a></li>
                            <li><a href="#">Binders</a></li>
                        </ul>-->
</li>
                        <li><small>Hole Count</small>
                        <ul class = "submenu">
                            <li><a href="#">7 Hole</a></li>
                            <li><a href="#">10 Hole</a></li>
                            <li><a href="#">11 Hole</a></li>
                            <li><a href="#">12 Hole</a></li>
                            <li><a href="#">22 Hole</a></li>
                        </ul>
                        </li>
                        <li class="att-start">
<small>Aircraft/OEM</small>
<ul class = "submenu">
                            <li><a href="#">Boeing</a></li>
                            <li><a href="#">Gulfstream</a></li>
                            <li><a href="#">Jeppesen</a></li>
                            <li><a href="#">Learjet</a></li>
                            <li><a href="#">Embraer</a></li>
                            <li><a href="#">Hawker</a></li>
                            <li><a href="#">Beechcraft</a></li>
                            <li><a href="#">Cessna</a></li>
                            <li><a href="#">Bombardier</a></li>
                            <li><a href="#">Airbus</a></li>
                         
                            </ul></li>
                        <li class="att-start">
<small>Cover Type</small>
<ul class = "submenu">
                            <li><a href="#">Flexible</a></li>
                            <li><a href="#">Rigid</a></li>
                            <li><a href="#">Smooth Rigid</a></li>
                            <li><a href="#">Plain</a></li>
                            <li><a href="#">Pockets</a></li>
                            <li><a href="#">Window</a></li>
                            <li><a href="#">Window and Pocket</a></li>
                            
                        </ul>
</li>
                        <li class="att-start">
<small>Custom Checklist Covers</small>
<ul class = "submenu">
                            <li><a href="#">Get a Quote</a></li>
                          
                            </ul></li>
                 </ul>
</div>

    <div id="cat_5" style="display:none;">


<div class="column" onclick="HideContent('cat_1'); HideContent('cat_2'); HideContent('cat_3'); HideContent('cat_4'); HideContent('cat_5'); HideContent('cat_6'); ShowContent('cat');"><img  src="http://baselinedev.com/aerobind/wp-content/themes/baseline-custom/images/green.png" style="width:20px;"/>back</div>
<ul class="attributes">
                        <li><a href="#"><img width="40" height="40" src="http://baselinedev.com/aerobind/wp-content/uploads/category-placeholder-150x150.jpg"/><br>
Paper Punches</a>
<!--<ul class = "submenu">
                            <li><a href="#">Ring Count</a></li>
                            <li><a href="#">Aircraft/OEM</a></li>
                            <li><a href="#">Brand</a></li>
                            <li><a href="#">Binders</a></li>
                        </ul>-->
</li>
                        <li><small>Hole Pattern</small>
                        <ul class = "submenu">
                            <li><a href="#">7 Hole</a></li>
                            <li><a href="#">11 Hole</a></li>
                            <li><a href="#">22 Hole</a></li>
                            
                        </ul>
                        </li>
                        <li class="att-start"><small>
Punch Type</small>
<ul class = "submenu">
                            <li><a href="#">Electric</a></li>
                            <li><a href="#">Light Duty Manual</a></li>
                            <li><a href="#">Heavy Duty Manual</a></li>
                            </ul></li>
                        <li class="att-start"><small>
Aircraft/OEM</small>
<ul class = "submenu">
                            <li><a href="#">Boeing</a></li>
                            <li><a href="#">Gulfstream</a></li>
                            <li><a href="#">Jeppesen</a></li>
                            <li><a href="#">Learjet</a></li>
                            <li><a href="#">Embraer</a></li>
                            <li><a href="#">Hawker</a></li>
                            <li><a href="#">Beechcraft</a></li>
                            <li><a href="#">Cessna</a></li>
                            <li><a href="#">Bombardier</a></li>
                            <li><a href="#">Airbus</a></li>
                         
                            </ul></li>
                        <li class="att-start"><small>
Custom Paper Punches</small>
<ul class = "submenu">
                            <li><a href="#">Get a Quote</a></li>
                          
                            </ul></li>
                       	</ul>
</div>
    
    <div id="cat_6" style="display:none;">


<div class="column" onclick="HideContent('cat_1'); HideContent('cat_2'); HideContent('cat_3'); HideContent('cat_4'); HideContent('cat_5'); HideContent('cat_6'); ShowContent('cat');"><img  src="http://baselinedev.com/aerobind/wp-content/themes/baseline-custom/images/green.png" style="width:20px;"/>back</div>
<ul class="attributes">
                        <li><a href="#"><img width="40" height="40" src="http://baselinedev.com/aerobind/wp-content/uploads/category-placeholder-150x150.jpg"/><br>
Paper Stock</a>
<!--<ul class = "submenu">
                            <li><a href="#">Ring Count</a></li>
                            <li><a href="#">Aircraft/OEM</a></li>
                            <li><a href="#">Brand</a></li>
                            <li><a href="#">Binders</a></li>
                        </ul>-->
</li> 
                        <li><small>Page Size</small>
                        <ul class = "submenu">
                            <li><a href="#">5.5 x 8.5</a></li>
                            <li><a href="#">A5</a></li>
                            <li><a href="#">5.75 x 11</a></li>
                            <li><a href="#">A4</a></li>
                            <li><a href="#">5.10 x 11</a></li>
                             <li><a href="#">5.25 x 11</a></li>
                            <li><a href="#">5.50 x 11</a></li>
                            <li><a href="#">5.87 x 11</a></li>
                            <li><a href="#">6.00 x 11</a></li>
                            <li><a href="#">6.12 x 11</a></li>
                            <li><a href="#">6.50 x 11</a></li>
                        </ul>
                        </li>
                        <li class="att-start"><small>
Hole Count</small>
<ul class = "submenu">
                            <li><a href="#">7-Hole</a></li>
                            <li><a href="#">11-Hole</a></li>
                            <li><a href="#">22-Hole</a></li>
                            </ul></li>
                        <li class="att-start"><small>
Paper Weight</small>
<ul class = "submenu">
                            <li><a href="#">Paper Weight </a></li>
                                                      
                        </ul>
</li>
                        <li class="att-start"><small>
Custom Paper Stock</small>
<ul class = "submenu">
                            <li><a href="#">Get a Quote</a></li>
                          
                            </ul>
                          </li>
	</ul>
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
<script>


			
					
$(".column").click(function(){
	$("a[data-toggle]").attr("aria-expanded", "true");
	$("#drop").attr("class", "dropdown open");
   //$("a[data-toggle]").attr("data-toggle", "update");
   $("a[data-toggle]").removeAttr("data-toggle");
   
});

$(".cat").click(function(){
$("#dropdown_title").click(function(){
	   $("a[data-toggle]").removeAttr("data-toggle");
	   $("#drop").attr("class", "dropdown open");
	   
	   $("a[data-toggle]").attr("data-toggle", "dropdown");
	   $("a[data-toggle]").attr("aria-expanded", "true");
	   
	   $("#cat").css({display: "block"});
	   $("#cat_1").css({display: "none"});
	   $("#cat_2").css({display: "none"});
	   $("#cat_3").css({display: "none"});
	   $("#cat_4").css({display: "none"});
	   $("#cat_5").css({display: "none"});
	   $("#cat_6").css({display: "none"});
	   
	   $( "#dropdown_title" ).click(function() {
  $( "#cat" ).toggle();
});
   });
});


		
</script>



</body>



</html>

<?php } ?>