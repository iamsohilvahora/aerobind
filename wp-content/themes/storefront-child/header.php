<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<script>(function(a,s,y,n,c,h,i,d,e){s.className+=' '+y;h.start=1*new Date;
h.end=i=function(){s.className=s.className.replace(RegExp(' ?'+y),'')};
(a[n]=a[n]||[]).hide=h;setTimeout(function(){i();h.end=null},c);h.timeout=c;
})(window,document.documentElement,'async-hide','dataLayer',1000,
{'GTM-W56TW48':true});</script>
<!-- Twitter universal website tag code -->
<script>
!function(e,t,n,s,u,a){e.twq||(s=e.twq=function(){s.exe?s.exe.apply(s,arguments):s.queue.push(arguments);
},s.version='1.1',s.queue=[],u=t.createElement(n),u.async=!0,u.src='//static.ads-twitter.com/uwt.js',
a=t.getElementsByTagName(n)[0],a.parentNode.insertBefore(u,a))}(window,document,'script');
// Insert Twitter Pixel ID and Standard Event data below
twq('init','nzma3');
twq('track','PageView');
</script>
<!-- End Twitter universal website tag code -->
<link href="<?php bloginfo('template_directory');?>/includes/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://aerobind.com/wp-content/themes/storefront-child/includes/sidr-2.2.1/dist/stylesheets/jquery.sidr.dark.min.css">
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory');?>/includes/slick/slick.css"/>
<link href="https://fonts.googleapis.com/css?family=Montserrat|Open+Sans:300,400" rel="stylesheet"> 
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="google-site-verification" content="LJeOJPsS5IyKzsNqvoCe2CLPnaZAbZgPvsjvb06fCJI" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<title><?php wp_title(); ?></title>
    <!-- Include the Sidr JS -->
<!--new script testing doc-->

<?php wp_head(); ?>



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

<div id="page" class="hfeed site">
	
	<?php
	
	do_action( 'storefront_before_header' ); ?>
	<!--<header id="masthead" class="site-header" role="banner" style="<?php // storefront_header_styles(); ?>">
		<div class="col-full">
			<?php
			/**
			 * Functions hooked into storefront_header action
			 *
			 * @hooked storefront_skip_links                       - 0
			 * @hooked storefront_social_icons                     - 10
			 * @hooked storefront_site_branding                    - 20
			 * @hooked storefront_secondary_navigation             - 30
			 * @hooked storefront_product_search                   - 40
			 * @hooked storefront_primary_navigation_wrapper       - 42
			 * @hooked storefront_primary_navigation               - 50
			 * @hooked storefront_header_cart                      - 60
			 * @hooked storefront_primary_navigation_wrapper_close - 68
			 */
			do_action( 'storefront_header' ); ?>
		</div>
	</header> --> <!-- #masthead -->
    
    <!-- .site-header -->
<nav <?php if(is_product()) {echo 'id="stick"';} ?> class="navbar navbar-default navbar-fixed-top">
	<div class="container" id="containerHeader">
		<div class="navbar-header">
        	<button id="ChangeToggle" type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mainMenuToggle">
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
            <a class="navbar-brand2" href="/"><img src="<?php bloginfo('template_directory');?>/images/aerobind-logo.png" alt="Aerobind" /></a>
        </div>
        <!--This is menu with the click to drop down- currently the mobile one-->
       
        <div class="row navigator">
        <div class="container" id="headerulcontainer">
        	<div class="navbar-collapse collapse" id="mainMenuToggle">
 
				<?php
				
$username = "aerobind";
$password = "DSDlIQeyQ94VCdCsJo73";
$hostname = "127.0.0.1";
$dbhandle = @mysqli_connect($hostname, $username, $password);
$selected = mysqli_select_db($dbhandle,"wp_aerobind");

?>
 
               <ul class="nav navbar-nav navbar-center">
              		<li><a href="/" <?php if ( is_front_page()) { echo 'class="active"'; } ?>>Home</a></li>
              		<li id="drop" class="dropdown">
                    
                    <a class="dropdown-toggle" data-target="#drop" data-toggle="dropdown" id="area-expand"><span id="dropdown_title">Products</span></a>
						
                    	<ul  id="cat"  class="dropdown-menu" >
                    	
						<li class="options">Scroll Down for Product Options <i class="fa fa-angle-down" aria-hidden="true"></i></li>
                        <li class="cat" onclick="ShowContent('cat_1'); HideContent('cat_2'); HideContent('cat_3'); HideContent('cat_4'); HideContent('cat_5'); HideContent('cat_6');  HideContent('cat_7'); HideContent('cat');"><a href="#"><img style="border-radius:70px;" alt="Blue Combo" src="https://aerobind.com/wp-content/uploads/Blue-Combo-11-Ring-Aerobinder-IICategory-2.jpg"/><br>
Ring<br>
 Binders
<div class="overlay one">
	<?php $result = mysqli_query($dbhandle,"SELECT description FROM wp_term_taxonomy WHERE term_id = 112");
	//fetch tha data from the database
	$row = mysqli_fetch_array($result);
   	echo "<p>".$row{'description'}."</p>";?>
</div></a>
</li>
                        <li class="cat" onclick="ShowContent('cat_2'); HideContent('cat_1'); HideContent('cat_3'); HideContent('cat_4'); HideContent('cat_5'); HideContent('cat_6'); HideContent('cat_7'); HideContent('cat');" ><a href="#"><img style="border-radius:70px;" alt="Hole Divider Combo" src="https://aerobind.com/wp-content/uploads/7-Hole-Divider-Tab-Boeing-Fan.jpg"/><br>Divider <br>
 Tabs
                        <div class="overlay two">
	<?php $result = mysqli_query($dbhandle,"SELECT description FROM wp_term_taxonomy WHERE term_id = 120");
	//fetch tha data from the database
	$row = mysqli_fetch_array($result);
   	echo "<p>".$row{'description'}."</p>";?>
</div>
                        </a>
                       <!-- <ul class="submenu">
                            <li><a href="#">Hole Count</a></li>
                            <li><a href="#">Material</a></li>
                            <li><a href="#">Aircraft/OEM</a></li>
                            <li><a href="#">Custom Quote</a></li>
                        </ul> -->
                        </li>
                        <li class="cat" onclick="ShowContent('cat_3'); HideContent('cat_1'); HideContent('cat_2');  HideContent('cat_4'); HideContent('cat_5'); HideContent('cat_6'); HideContent('cat_7'); HideContent('cat');" ><a href="#"><img  alt="White Combo" src="https://aerobind.com/wp-content/uploads/IST11-2MM_Category.png"/><br>
Sheet <br>
Protectors
<div class="overlay three">
	<?php $result = mysqli_query($dbhandle,"SELECT description FROM wp_term_taxonomy WHERE term_id = 119");
	//fetch tha data from the database
	$row = mysqli_fetch_array($result);
   	echo "<p>".$row{'description'}."</p>";?>
</div>
</a>
<!--<ul class="submenu">
                            <li><a href="#">Hole Count</a></li>
                            <li><a href="#">Aircraft/OEM</a></li>
                            </ul>--></li>
                        <li class="cat" onclick="ShowContent('cat_4'); HideContent('cat_1'); HideContent('cat_2');  HideContent('cat_3'); HideContent('cat_5'); HideContent('cat_6'); HideContent('cat_7'); HideContent('cat');"><a href="#"><img  alt="Hole Covers Combo" src="https://aerobind.com/wp-content/uploads/22-Hole-Covers-ComboCategory-1.jpg"/><br>
Checklist <br>
Covers
<div class="overlay four">
	<?php $result = mysqli_query($dbhandle,"SELECT description FROM wp_term_taxonomy WHERE term_id = 118");
	//fetch tha data from the database
	$row = mysqli_fetch_array($result);
   	echo "<p>".$row{'description'}."</p>";?>
</div>
</a>
<!--<ul class="submenu">
                            <li><a href="#">Hole Count</a></li>
                            <li><a href="#">Aircraft/OEM</a></li>
                            <li><a href="#">Cover Type</a></li>
                            <li><a href="#">Checklist Covers</a></li>
                        </ul>-->
</li>
                        <li class="cat" onclick="ShowContent('cat_5'); HideContent('cat_1'); HideContent('cat_2');  HideContent('cat_3'); HideContent('cat_4'); HideContent('cat_6'); HideContent('cat_7'); HideContent('cat');"><a href="#"><img  alt="Paper Punches Combo" src="https://aerobind.com/wp-content/uploads/EPP1MU-Category.png"/><br>
Paper <br>
Punches
<div class="overlay five">
	<?php $result = mysqli_query($dbhandle,"SELECT description FROM wp_term_taxonomy WHERE term_id = 121");
	//fetch tha data from the database
	$row = mysqli_fetch_array($result);
   	echo "<p>".$row{'description'}."</p>";?>
</div>
</a>
<!--<ul class="submenu">
                            <li><a href="#">Hole Pattern</a></li>
                            <li><a href="#">Punch Type</a></li>
                            <li><a href="#">Aircraft/OEM</a></li>
                            </ul>--></li>
                        <li class="cat" onclick="ShowContent('cat_6'); HideContent('cat_1'); HideContent('cat_2');  HideContent('cat_3'); HideContent('cat_4'); HideContent('cat_5'); HideContent('cat_7'); HideContent('cat');"><a href="#"><img alt="Blue Checklist Combo" src="https://aerobind.com/wp-content/uploads/11-Ring-Pinting-Blue-Checklist-Asasembly-Tabs-1.jpg"/><br>
Paper <br>
Stock
<div class="overlay six">
	<?php $result = mysqli_query($dbhandle,"SELECT description FROM wp_term_taxonomy WHERE term_id = 122");
	//fetch tha data from the database
	$row = mysqli_fetch_array($result);
   	echo "<p>".$row{'description'}."</p>";?>
</div>
</a>
<!--<ul class="submenu">
                            <li><a href="#">Page Size</a></li>
                            <li><a href="#">Hole Count</a></li>
                            <li><a href="#">Paper Weight</a></li>
                            <li><a href="#">Paper Stock</a></li>
                        </ul>--></li>
                           <li class="cat" onclick="ShowContent('cat_7'); HideContent('cat_1'); HideContent('cat_2');  HideContent('cat_3'); HideContent('cat_4'); HideContent('cat_5'); HideContent('cat_6'); HideContent('cat');"><a href="#"><img style="border-radius:70px;" alt="Product Combo" src="https://aerobind.com/wp-content/uploads/All-Products-2.jpg"/><br>
All <br>
Products
<div class="overlay seven">
	<p>View All Aerobind Products</p>
</div>
</a>
<!--<ul class="submenu">
                            <li><a href="#">Page Size</a></li>
                            <li><a href="#">Hole Count</a></li>
                            <li><a href="#">Paper Weight</a></li>
                            <li><a href="#">Paper Stock</a></li>
                        </ul>--></li>
                            
                        </ul>
                    </li>
              		<li><a href="/qrh-services/" <?php if ( is_page(167)) { echo ' class="active"'; } ?>>Services</a></li>
              		<li><a href="/about-aerobind/" <?php if ( is_page('about-aerobind')) { echo ' class="active"'; } ?>>About</a></li>
              		<li><a href="/contact/" <?php if ( is_page('contact')) { echo ' class="active"'; } ?>>Contact</a></li>
			<li id="user-login"><a href="/my-account/" <?php if (is_page('my-account')) {echo ' class="active"'; } ?>><i class="fa fa-user-circle" aria-hidden="true" style="color:#009944;"></i>&nbsp;Login</a></li>
 
<li class="header-cart-li"><?php do_action('storefront_header'); ?></li>
</ul>
				<ul class="nav navbar-nav"> <li id="searchdrop" class="nav-item dropdown">
  <a class="dropdown-toggle" data-target="#searchdrop" data-toggle="dropdown"><span id="dropdown_title1">Search</span></a>
  <ul id="srch" class="dropdown-menu">            
<?php get_product_search_form(); ?>
</li>
				   </ul>  </ul> 
<?php //close the connection
mysqli_close($dbhandle);
?>			
		</div>
        
</div>
</div>
      
<div class="holder">     	
<div id="cat_1" class="header-cart-1">
              		
<div class="column" onclick="HideContent('cat_1'); HideContent('cat_2'); HideContent('cat_3'); HideContent('cat_4'); HideContent('cat_5'); HideContent('cat_6'); HideContent('cat_7'); ShowContent('cat');"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
<ul class="attributes">
                        <li class="ring-binder-img">
                          <a href="#"><img src="https://aerobind.com/wp-content/uploads/Blue-Combo-11-Ring-Aerobinder-IICategory-2.jpg"/><br>Ring Binders</a>
                        </li>
                        <li><small class="color-fff">Ring Count</small><br>
                        <ul class="submenu float">
                            <li><a href="/product-category/ring-binders/?filter_ring-count=1-ring">1-Ring</a></li>
                            <li><a href="/product-category/ring-binders/?filter_ring-count=7-ring">7-Ring</a></li>
                            <li><a href="/product-category/ring-binders/?filter_ring-count=9-ring">9-Ring</a></li>
                            <li><a href="/product-category/ring-binders/?filter_ring-count=10-ring">10-Ring</a></li>
                            <li><a href="/product-category/ring-binders/?filter_ring-count=11-ring">11-Ring</a></li>
                        </ul>
                        <ul class="submenu float">
                            <li><a href="/product-category/ring-binders/?filter_ring-count=12-ring">12-Ring</a></li>
                            <li><a href="/product-category/ring-binders/?filter_ring-count=13-ring">13-Ring</a></li>
                            <li><a href="/product-category/ring-binders/?filter_ring-count=22-ring">22-Ring</a></li>
                        </ul>
                        <div class="clear"></div>
                        </li>
                        
                        <li class="att-start">
						  <small class="color-fff">Aircraft/OEM</small><br>
						  <ul class="submenu float">
                            <li><a href="/product-category/ring-binders/?filter_aircraftoem=boeing">Boeing</a></li>
							<li><a href="/product-category/ring-binders/?filter_aircraftoem=airbus">Airbus</a></li>
                            <li><a href="/product-category/ring-binders/?filter_aircraftoem=gulfstream">Gulfstream</a></li>
							<li><a href="/product-category/ring-binders/?filter_aircraftoem=embraer">Embraer</a></li>
							<li><a href="/product-category/ring-binders/?filter_aircraftoem=bombardier">Bombardier</a></li>
                            <li><a href="/product-category/ring-binders/?filter_aircraftoem=jeppesen">Jeppesen</a></li>
                          </ul>
                          
						  <div class="clear"></div>
						</li>
                        
                        <li class="att-start"><small class="color-fff">Brand</small>
							<ul class="submenu">
                            <li><a href="/product-category/ring-binders/?filter_brand=aeroflex">AEROFLEX</a></li>
                            <li><a href="/product-category/ring-binders/?filter_brand=aerometal">AEROMETAL</a></li>
                            <li><a href="/product-category/ring-binders/?filter_brand=aerad">AERAD</a></li>
                            <li><a href="/product-category/ring-binders/?filter_brand=aerobinder-ii">AEROBINDER II</a></li>
                         	</ul>
						</li>
                        
                        <li class="att-start"><small class="color-fff">Custom Ring Binder</small>
							<ul class="submenu">
                            <li><a href="/services/#custom-ring-binders">Get a Quote</a></li>
                          	</ul>		
						</li>
</ul>
	</div>
<div id="cat_2" class="header-cart-1">
<div class="column" onclick="HideContent('cat_1'); HideContent('cat_2'); HideContent('cat_3'); HideContent('cat_4'); HideContent('cat_5'); HideContent('cat_6'); HideContent('cat_7'); ShowContent('cat');"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
<ul class="attributes">
                        <li class="ring-binder-img"><a href="#"><img width="40" height="40"src="https://aerobind.com/wp-content/uploads/7-Hole-Divider-Tab-Boeing-Fan.jpg"/><br>Divider Tabs</a></li>
                        <li><small class="color-fff">Hole Count</small>
                        <ul class="submenu">
                            <li><a href="/product-category/divider-tabs/?filter_hole-count=7-hole">7 Hole</a></li>
                            <li><a href="/product-category/divider-tabs/?filter_hole-count=11-hole">11 Hole</a></li>
                            <li><a href="/product-category/divider-tabs/?filter_hole-count=22-hole">22 Hole</a></li>                            
                        </ul>
                        </li>
                        <li class="att-start">
                          <small class="color-fff">Material</small>
						  <ul class="submenu">
                            <li><a href="/product-category/divider-tabs/?filter_material=card-stock">Cardstock</a></li>
                            <li><a href="/product-category/divider-tabs/?filter_material=synthetic">Synthetic (no tear)</a></li>
                            </ul></li>
                            
                        <li class="att-start">
						  <small class="color-fff">Aircraft/OEM</small><br>
						  <ul class="submenu float">
                            <li><a href="/product-category/divider-tabs/?filter_aircraftoem=boeing">Boeing</a></li>
							<li><a href="/product-category/divider-tabs/?filter_aircraftoem=airbus">Airbus</a></li>
                            <li><a href="/product-category/divider-tabs/?filter_aircraftoem=gulfstream">Gulfstream</a></li>
							<li><a href="/product-category/divider-tabs/?filter_aircraftoem=embraer">Embraer</a></li>
							<li><a href="/product-category/divider-tabs/?filter_aircraftoem=bombardier">Bombardier</a></li>
                            <li><a href="/product-category/divider-tabs/?filter_aircraftoem=jeppesen">Jeppesen</a></li>
                          </ul>
                          
                          <div class="clear"></div>
						</li>
                        
                        <li class="att-start">
							<small class="color-fff">Custom Divider Tabs</small>
							<ul class="submenu">
                            <li><a href="/services/#divider-tabs" target="_blank">Get a Quote</a></li>
                            </ul>
                        </li>
                       
            	</ul>
	</div>
<div id="cat_3" class="header-cart-1">
<div class="column" onclick="HideContent('cat_1'); HideContent('cat_2'); HideContent('cat_3'); HideContent('cat_4'); HideContent('cat_5'); HideContent('cat_6'); HideContent('cat_7'); ShowContent('cat');"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
<ul class="attributes">
                        <li><a href="#"><img width="40" height="40" src="https://aerobind.com/wp-content/uploads/IST11-2MM_Category.png"/><br>Sheet Protectors</a></li>
                        <li><small class="color-fff">Hole Count</small>
                        <ul class="submenu">
                            <li><a href="/product-category/sheet-protectors/?filter_hole-count=7-hole">7 Hole</a></li>
                            <li><a href="/product-category/sheet-protectors/?filter_hole-count=10-hole">10 Hole</a></li>
                            <li><a href="/product-category/sheet-protectors/?filter_hole-count=11-hole">11 Hole</a></li>
                            <li><a href="/product-category/sheet-protectors/?filter_hole-count=22-hole">22 Hole</a></li>
                        </ul>
                        </li>
                        
                        
                        <li class="att-start">
						  <small class="color-fff">Aircraft/OEM</small><br>
						  <ul class="submenu float">
                            <li><a href="/product-category/sheet-protectors/?filter_aircraftoem=boeing">Boeing</a></li>
							<li><a href="/product-category/sheet-protectors/?filter_aircraftoem=airbus">Airbus</a></li>
                            <li><a href="/product-category/sheet-protectors/?filter_aircraftoem=gulfstream">Gulfstream</a></li>
							<li><a href="/product-category/sheet-protectors/?filter_aircraftoem=embraer">Embraer</a></li>
							<li><a href="/product-category/sheet-protectors/?filter_aircraftoem=bombardier">Bombardier</a></li>
                            <li><a href="/product-category/sheet-protectors/?filter_aircraftoem=jeppesen">Jeppesen</a></li>
                          </ul>
                          
						  <div class="clear"></div>
						</li>
                        
                                    	</ul>
	</div>
 
    <div id="cat_4" class="header-cart-1">
<div class="column" onclick="HideContent('cat_1'); HideContent('cat_2'); HideContent('cat_3'); HideContent('cat_4'); HideContent('cat_5'); HideContent('cat_6'); HideContent('cat_7'); ShowContent('cat');"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
<ul class="attributes">
                        <li><a href="#"><img width="40" height="40" src="https://aerobind.com/wp-content/uploads/22-Hole-Covers-ComboCategory-1.jpg"/><br>Checklist Covers</a></li>
                        <li><small class="color-fff">Hole Count</small>
                        <ul class="submenu">
                            <li><a href="/product-category/checklist-covers/?filter_hole-count=7-hole">7 Hole</a></li>
                            <li><a href="/product-category/checklist-covers/?filter_hole-count=10-hole">10 Hole</a></li>
                            <li><a href="/product-category/checklist-covers/?filter_hole-count=11-hole">11 Hole</a></li>
                            <li><a href="/product-category/checklist-covers/?filter_hole-count=12-hole">12 Hole</a></li>
                            <li><a href="/product-category/checklist-covers/?filter_hole-count=22-hole">22 Hole</a></li>
                        </ul>
                        </li>
                        
                        <li class="att-start">
						  <small class="color-fff">Aircraft/OEM</small><br>
						  <ul class="submenu float">
                            <li><a href="/product-category/checklist-covers/?filter_aircraftoem=boeing">Boeing</a></li>
							<li><a href="/product-category/checklist-covers/?filter_aircraftoem=airbus">Airbus</a></li>
                            <li><a href="/product-category/checklist-covers/?filter_aircraftoem=gulfstream">Gulfstream</a></li>
							<li><a href="/product-category/checklist-covers/?filter_aircraftoem=embraer">Embraer</a></li>
							<li><a href="/product-category/checklist-covers/?filter_aircraftoem=bombardier">Bombardier</a></li>
                            <li><a href="/product-category/checklist-covers/?filter_aircraftoem=jeppesen">Jeppesen</a></li>
                          </ul>
                          
						  <div class="clear"></div>
						</li>
                        
                        <li class="att-start">
						<small class="color-fff">Cover Type</small><br>
						  <ul class="submenu float">
                            <li><a href="/product-category/checklist-covers/?filter_cover-type=flexible">Flexible</a></li>
                            <li><a href="/product-category/checklist-covers/?filter_cover-type=rigid">Rigid</a></li>
                            <li><a href="/product-category/checklist-covers/?filter_cover-type=smooth-rigid">Smooth Rigid</a></li>
                            <li><a href="/product-category/checklist-covers/?filter_cover-type=front">Front</a></li>
                            <li><a href="/product-category/checklist-covers/?filter_cover-type=back">Back</a></li>
                            </ul>
                                                  <div class="clear"></div>
</li>
                        <li class="att-start">
<small class="color-fff">Custom Checklist Covers</small>
<ul class="submenu">
                            <li><a href="/services/#checklist-covers">Get a Quote</a></li>
                          
                            </ul></li>
                 </ul>
</div>
    <div id="cat_5" class="header-cart-1">
<div class="column" onclick="HideContent('cat_1'); HideContent('cat_2'); HideContent('cat_3'); HideContent('cat_4'); HideContent('cat_5'); HideContent('cat_6'); HideContent('cat_7'); ShowContent('cat');"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
<ul class="attributes">
                        <li><a href="#"><img width="40" height="40" src="https://aerobind.com/wp-content/uploads/EPP1MU-Category.png"/><br>Paper Punches</a></li>
                       <!-- <li>
                          <small style="color:#FFF;">Hole Pattern</small>
                          <ul class="submenu">
                            <li><a href="/product-category/paper-punches/?filter_hole-pattern=7-hole">7 Hole</a></li>
                            <li><a href="/product-category/paper-punches/?filter_hole-pattern=10-hole">10 Hole</a></li>
                            <li><a href="/product-category/paper-punches/?filter_hole-pattern=11-hole">11 Hole</a></li>
                            <li><a href="/product-category/paper-punches/?filter_hole-pattern=22-hole">22 Hole</a></li>
                          </ul>
                        </li> -->
                        <li class="att-start">
                          <small class="color-fff">Punch Type</small>
						  <ul class="submenu">
                            <li><a href="/product-category/paper-punches/?filter_punch-type=electric-">Electric</a></li>
                            <li><a href="/product-category/paper-punches/?filter_punch-type=removable-die">Removable Die</a></li>
                           <!--<li><a href="/product-category/paper-punches/?filter_punch-type=light-manual">Light Duty Manual</a></li>-->
                            <li><a href="/product-category/paper-punches/?filter_punch-type=heavy-manual">Heavy Duty Manual</a></li>
                          </ul>
                        </li>
                        <li class="att-start">
                          <small class="color-fff">Aircraft/OEM</small><br>
						  
                          
                          <ul class="submenu float">
                            <li><a href="/product-category/paper-punches/?filter_aircraftoem=boeing">Boeing</a></li>
							<li><a href="/product-category/paper-punches/?filter_aircraftoem=airbus">Airbus</a></li>
                            <li><a href="/product-category/paper-punches/?filter_aircraftoem=gulfstream">Gulfstream</a></li>
							<li><a href="/product-category/paper-punches/?filter_aircraftoem=embraer">Embraer</a></li>
							<li><a href="/product-category/paper-punches/?filter_aircraftoem=bombardier">Bombardier</a></li>
                            <li><a href="/product-category/paper-punches/?filter_aircraftoem=jeppesen">Jeppesen</a></li>
                          </ul>
                          
                          <div class="clear"></div></li>
                                              	</ul>
					</div>
    
    <div id="cat_6" class="header-cart-1">
<div class="column" onclick="HideContent('cat_1'); HideContent('cat_2'); HideContent('cat_3'); HideContent('cat_4'); HideContent('cat_5'); HideContent('cat_6'); HideContent('cat_7'); ShowContent('cat');"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
<ul class="attributes">
                        <li>
                          <a href="#"><img width="40" height="40" src="https://aerobind.com/wp-content/uploads/11-Ring-Pinting-Blue-Checklist-Asasembly-Tabs-1.jpg"/><br>Paper Stock</a>
                        </li> 
                        
                        <li>
                          <small class="color-fff">Page Size</small><br>
                          <ul class="submenu float">
                            <li><a href="/product/pilot-checklist-paper/">A4</a></li>
                            <li><a href="/product/pilot-checklist-paper/">A5</a></li>
                            <li><a href="/product/pilot-checklist-paper/">5.5 x 8.5</a></li>
                            <li><a href="/product/pilot-checklist-paper/">5.5 x 11</a></li>
                            <li><a href="/product/pilot-checklist-paper/">5.10 x 11</a></li>
                          </ul>
                          <ul class="submenu float">
                            <li><a href="/product/pilot-checklist-paper/">5.25 x 11</a></li>
                            <li><a href="/product/pilot-checklist-paper/">5.50 x 11</a></li>
                            <li><a href="/product/pilot-checklist-paper/">5.87 x 11</a></li>
                            <li><a href="/product/pilot-checklist-paper/">6.00 x 11</a></li>
                            <li><a href="/product/pilot-checklist-paper/">6.12 x 11</a></li>
                          </ul>
                          <ul class="submenu float">
                            <li><a href="/product/pilot-checklist-paper/">6.50 x 11</a></li>
			    <li><a href="/product/pilot-checklist-paper/">8.50 x 11</a></li>	
                          </ul>
                          <div class="clear"></div>
                        </li>
                        
                        <li class="att-start">
                          <small class="color-fff">Hole Count</small>
						  <ul class="submenu">
                            <li><a href="/product/pilot-checklist-paper/">7-Hole Compatible</a></li>
                            <li><a href="/product/pilot-checklist-paper/">11-Hole Compatible</a></li>
                            <li><a href="/product/pilot-checklist-paper/">22-Hole Compatible</a></li>
                          </ul>
                        </li>
                        
                                     
                        <li class="att-start"><small class="color-fff">Custom Paper Stock</small>
						  <ul class="submenu">
                            <li><a href="/product/pilot-checklist-paper/">Order Custom Stock</a></li>
                          </ul>
                        </li>
	</ul>
  </div>
  
  <div id="cat_7" class="header-cart-1">
<div class="column" onclick="HideContent('cat_1'); HideContent('cat_2'); HideContent('cat_3'); HideContent('cat_4'); HideContent('cat_5'); HideContent('cat_6'); HideContent('cat_7'); ShowContent('cat');"><i class="fa fa-chevron-left" aria-hidden="true"></i></div>
<ul class="attributes">
                        <li class="ring-binder-img">
                          <a href="#"><img width="40" height="40" src="https://aerobind.com/wp-content/uploads/All-Products-2.jpg"/><br>All Products</a>
                        </li> 
                        
                        <li class="att-start"><small class="color-fff">Hole Count</small><br>
                        
			<ul class="submenu float">
			    <li><a href="/shop/?filter_hole-count=1-hole">1-Hole</a></li>
                            <li><a href="/shop/?filter_hole-count=7-hole">7-Hole</a></li>
			    <li><a href="/shop/?filter_hole-count=9-hole">9-Hole</a></li>
                            <li><a href="/shop/?filter_hole-count=10-hole">10-Hole</a></li>
	   		    <li><a href="/shop/?filter_hole-count=11-hole">11-Hole</a></li>
			</ul>
			<ul class="submenu float">
			    <li><a href="/shop/?filter_hole-count=12-hole">12-Hole</a></li>
			    <li><a href="/shop/?filter_hole-count=13-hole">13-Hole</a></li>
                            <li><a href="/shop/?filter_hole-count=22-hole">22-Hole</a></li>
                        </ul>
			<div class="clear"></div>
                        </li>
                        
                        <li class="att-start">
						  <small class="color-fff">Aircraft/OEM</small><br>
						  <ul class="submenu float">
                            <li><a href="/shop/?filter_aircraftoem=boeing">Boeing</a></li>
							<li><a href="/shop/?filter_aircraftoem=airbus">Airbus</a></li>
                            <li><a href="/shop/?filter_aircraftoem=gulfstream">Gulfstream</a></li>
							<li><a href="/shop/?filter_aircraftoem=embraer">Embraer</a></li>
							<li><a href="/shop/?filter_aircraftoem=bombardier">Bombardier</a></li>
                            <li><a href="/shop/?filter_aircraftoem=jeppesen">Jeppesen</a></li>
                          </ul>
                          
						  <div class="clear"></div>
						</li>
                        
						<li class="att-start"><small class="color-fff">Page Size</small><br>
						  <ul class="submenu float">
                            <li><a href="/shop/?filter_page-size=8-5-inch">8.5"</a></li>
                            <li><a href="/shop/?filter_page-size=11-inch">11"</a></li>
                            <li><a href="/shop/?filter_page-size=a4">A4</a></li>
                            <li><a href="/shop/?filter_page-size=a5">A5</a></li>
							<li><a href="/shop/?filter_page-size=5-10-x-11">5.10" x 11"</a></li>
                          </ul>
                          <ul class="submenu float">
                            <li><a href="/shop/?filter_page-size=5-25-x-11">5.25" x 11"</a></li>
                            <li><a href="/shop/?filter_page-size=5-5-x-8-5">5.5" x 8.5"</a></li>
                            <li><a href="/shop/?filter_page-size=5-50-x-11">5.50" x 11"</a></li>
                            <li><a href="/shop/?filter_page-size=5-75-x-11">5.75" x 11"</a></li>
                            <li><a href="/shop/?filter_page-size=5-87-x-11">5.87" x 11"</a></li>
                          </ul>
                          <ul class="submenu float">
                            <li><a href="/shop/?filter_page-size=6-00-x-11">6.00" x 11"</a></li>
                            <li><a href="/shop/?filter_page-size=6-12-x-11">6.12" x 11"</a></li>
                            <li><a href="/shop/?filter_page-size=6-50-x-11">6.50" x 11"</a></li>
			    <li><a href="/shop/?filter_page-size=8-5-x-11">8.50" x 11"</a></li>
                        </ul>
                        <div class="clear"></div>
					</li>
                    
	</ul>
  </div>
  </div>
</nav>
<script src="//cdn.jsdelivr.net/jquery/2.2.0/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/jquery.sidr/2.2.1/jquery.sidr.min.js"></script>
<?php //if (is_shop() || is_product() || is_product_category()) {?>
<!--<div id="searchbox">
<form>
  <input type="text" name="s" value="<?php //echo($_GET['s']); ?>" />
  <input type="hidden" name="post_type" value="product" />
  <input type="submit" value="Search"/>
</form>
</div>-->
<?php //} ?>
<!-- .site-header -->
    <?php 
    if (is_front_page()) { ?>
        <!-- .site-jumbotron -->
        <div class="jumbotron">
        	<div class="embed-container">
            	<div class="embed-responsive embed-responsive-16by9">
                   	<video id="jumbo" autoplay="true" loop="true" muted="true" playsinline="true" class="slider-video" width=“1280” height=“720” >
                        <source type="video/mp4" src="https://player.vimeo.com/external/208700251.hd.mp4?s=c71d3685f4527b4713508942dfe62828dcd3810e&profile_id=119">
                        </video>
                </div>
        	</div>
        </div><?php 
    } ?>
    <script>
        if(document.getElementById("jumbo")) {
        setTimeout(function()
         {
        	document.getElementById("jumbo").play();
         }, 0);
        };
        if(document.getElementById("jumbo")) {
        var iterations = 1;
        	
        document.getElementById("jumbo").addEventListener('ended', function () {    

            if (iterations < 3) {       

                this.currentTime = 0;
                this.play();
                iterations ++;
        	}
        		}, false);
        }
    </script>

    <?php 
    if (is_shop() || is_product() || is_product_category() || is_home() || is_archive() || is_single() || is_page('category')||  is_front_page() || is_page('qrh-services') || is_page('warranty-returns') || is_page('about-aerobind') || is_page('contact') || is_page('services')){ ?>

        <script>

        /*$(document).ready(function()
        		{
        			 The easy way to enable MakeFixed 
        			$(".slick-list").makeFixed();
        			
        			});	*/	
             /* $("#navbar-close").click(function(){
            				
            	$(".holder").css({display: "none"});
            }); 
            	 $("#navbar-hamburger").click(function(){
            				
            	$(".holder").css({display: "block"});
            }); 

            $( "#navbar-close" ).click(function() {
              $( ".holder" ).hide();
            });

             $('#navbar-close').click(function(){
                $('#cat_1').toggle();
            })
        		*/			
            $(".column").click(function(){
            	$("a[data-toggle]").attr("aria-expanded", "true");
            	$("#drop").attr("class", "dropdown open");
               //$("a[data-toggle]").attr("data-toggle", "update");
               $("a[data-toggle]").removeAttr("data-toggle");
               
            });
           //  $("#dropdown_title").click(function(){
        	  //  $("a[data-toggle]").removeAttr("data-toggle");
        	  //  $("#drop").attr("class", "dropdown open");
        	   
        	  //  $("a[data-toggle]").attr("data-toggle", "dropdown");
        	  //  $("a[data-toggle]").attr("aria-expanded", "true");
        	   
        	  //  $("#cat").css({display: "block"});
        	  //  $("#cat_1").css({display: "none"});
        	  //  $("#cat_2").css({display: "none"});
        	  //  $("#cat_3").css({display: "none"});
        	  //  $("#cat_4").css({display: "none"});
        	  //  $("#cat_5").css({display: "none"});
        	  //  $("#cat_6").css({display: "none"});
        	  //   $("#cat_7").css({display: "none"});
                
           // });
            $( "#dropdown_title" ).click(function() {
                $( "#cat" ).toggle();
                if($('#drop').hasClass('dropdown open')){
                    $(this).removeClass('open');
                    $("#drop #cat").css("display","none");
                }
                else{
                    $(this).addClass('open');
                    $("#drop #cat").css("display","block");
                }
            });
           
           $("#navbar-close").click(function(){
        	   $("a[data-toggle]").removeAttr("data-toggle");
        	   //$("#drop").attr("class", "dropdown open");

              if($('#drop').hasClass('dropdown open')){
                $(this).removeClass('open');
                $("#drop #cat").css("display","none");
              }
              else{
                $(this).addClass('open');
              }
        	   
        	   $("a[data-toggle]").attr("data-toggle", "dropdown");
        	   $("a[data-toggle]").attr("aria-expanded", "true");
        	   
        	  // $("#cat").css({display: "block"});
        	   $("#cat_1").css({display: "none"});
        	   $("#cat_2").css({display: "none"});
        	   $("#cat_3").css({display: "none"});
        	   $("#cat_4").css({display: "none"});
        	   $("#cat_5").css({display: "none"});
        	   $("#cat_6").css({display: "none"});
        	    $("#cat_7").css({display: "none"});
        	   
        	   $( "#dropdown_title" ).click(function() {
                    $( "#cat" ).toggle();
                });
           });
           
            $(".hamburger").click(function(e) {
                $(this).toggleClass('close');
            });

           /*$( "#responsive-menu-button" ).click(function() {
        	   $(".sidr-class-child_name").css({display: "none"});
        	    $(".sidr-class-child_name sidr-class-active").css({display: "block !important"});


            if ($(".sidr-class-child_name").hasClass("active")){    //add # to smartphone-off selector
                $(".sidr-class-child_name sidr-class-active").css({display: "block !important"});
            };
           });*/

          //  $("#navbar-hamburger").click(function () {
          //   if ($(this).hasClass("hidden")) {
          //     $(this).removeClass("hidden");
          //   };
          // });
          // $("#navbar-close").click(function () {
          //   if ($(this).hasClass("hidden")) {
          //     $(this).removeClass("hidden");
          //   };
          // });


          $('.navbar-header #ChangeToggle.navbar-toggle').click(function(){
              $('body').toggleClass('no-scroll');
          });
          // $('.navbar-header #navbar-close').click(function(){
          //     $('body').removeClass('no-scroll');
          // });

          /*hide product menu on scroll of the page*/
            window.onscroll = function() {scrollFunction()};

            var el = document.getElementById('area-expand');
            function scrollFunction() {
              if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
                document.getElementById("cat").style.display = "none";
                document.getElementById("drop").classList.remove("open");
                document.getElementById("dropdown_title").classList.remove("open");
                el.ariaExpanded = "false";
              } 
            }
        </script> 
    <?php 
    } ?>
    <?php if (is_product() || is_product_category()){ ?>
        <script>
        document.getElementsByClassName('parent_name').onclick = function() {
            var className = ' ' + myButton.className + ' ';
            this.className = ~className.indexOf(' active ') ?
                                 className.replace(' active ', ' ') :
                                 this.className + ' active';
        }
        $("#all_parents_row").click(function(){
        $(".child_name").css({display: "block !important"});
        });
        </script>
    <?php } ?>

<!-- .site-jumbotron -->
<?php 
// } 
?>
	<?php
	/**
	 * Functions hooked in to storefront_before_content
	 *
	 * @hooked storefront_header_widget_region - 10
	 
	do_action( 'storefront_before_content' ); */?>
	<div id="content" class="site-content" tabindex="-1">

	<?php if ( is_page('qrh-services') ) : ?>
    <span class="anchor" id="service-anchor"></span>
    <?php else: ?>
    <span class="anchor"></span>
    <?php endif ?>    
     
    
    <?php if (is_home() || is_archive() || is_single() && !is_woocommerce()) : ?>
    	<div class="container">
    <?php endif; ?>
		<!--<div class="col-full">-->
		<?php
		/**
		 * Functions hooked in to storefront_content_top
		 *
		 * @hooked woocommerce_breadcrumb - 10
		 */
				
		do_action( 'storefront_content_top' );
