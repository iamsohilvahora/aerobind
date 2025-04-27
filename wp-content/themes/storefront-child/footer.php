<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package storefront
 */
?>
		<!--</div><!-- .col-full -->
    	<?php if (is_home() || is_archive() || is_single() && !is_woocommerce()) : ?>
        	</div>
        <?php endif; ?>
	</div><!-- #content -->
	<?php do_action( 'storefront_before_footer' ); ?>
	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="col-full">
         
        <div class=" col-sm-4 col-md-3">
            	<p class="underline"><a class="navbar-brand2" href="/"><img src="<?php bloginfo('template_directory');?>/images/aerobind-logo-footer.jpg" alt="Aerobind" /></a></p>
                <?php if ( has_nav_menu( 'footer 1' ) ) : ?>
            	<?php
						wp_nav_menu( array(
						'theme_location' => 'footer 1',
						'menu_class'     => 'nav',
						) );
					?>
                    <?php endif; ?>
                    <?php if ( has_nav_menu( 'footer 2' ) ) : ?>
            	<?php
						wp_nav_menu( array(
						'theme_location' => 'footer 2',
						'menu_class'     => 'nav',
						) );
					?>
                    <?php endif; ?>
            </div>
       
            <div class=" col-sm-4 col-md-3"> 
            	<?php dynamic_sidebar( 'mailing-list-form' ); ?>
            </div>
            <div class=" col-sm-4 col-md-3">
            	<?php dynamic_sidebar( 'address-and-phone' ); ?>
            </div>
          	<div class=" col-sm-4 col-md-3 last-child">
            	<?php dynamic_sidebar( 'hours-of-operation' ); ?>
            </div>
            
<div class="clear"></div>
			<?php
			/**
			 * Functions hooked in to storefront_footer action
			 *
			 * @hooked storefront_footer_widgets - 10
			 * @hooked storefront_credit         - 20
			 */
			//do_action( 'storefront_footer' ); ?>
<div class="row copyright">
        	<div class="col-sm-6">
            	<p>&copy; <?php echo date('Y'); ?> Aerobind. All Rights Reserved. <a href="/aerobind-sitemap/">Sitemap</a>.</p>
            </div>
            <div class="col-sm-6 seal">
            	<div class="AuthorizeNetSeal"><!-- (c) 2005, 2013. Authorize.Net is a registered trademark of CyberSource Corporation-->
      				<script language="javascript" type="text/javascript">// <![CDATA[
        			var ANS_customer_id="e709bb1e-6ef3-4fc4-8013-d8fb163d2ce9";// ]]>
					</script>
      				<script language="javascript" src="https://verify.authorize.net/anetseal/seal.js" type="text/javascript"></script>
      			</div>
    		<div class="badges"></div>
            </div>
        </div>
		</div><!-- .col-full -->
	</footer><!-- #colophon -->
	<?php do_action( 'storefront_after_footer' ); ?>
</div><!-- #page -->
<!-- Include all compiled plugins (below), or include individual files as needed -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
<script src="<?php bloginfo('template_directory');?>/includes/bootstrap/js/bootstrap.min.js"></script>
<!--<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>-->
<script type="text/javascript" src="<?php bloginfo('template_directory');?>/includes/slick/slick.min.js"></script>
<script>
	//jQuery( ".panel .thumbnails a" ).removeClass( "first" );
	//jQuery( ".panel .thumbnails a" ).removeClass( "last" );
	//jQuery( ".woocommerce-tabs ul.tabs li" ).removeClass( "active" );
	//jQuery( ".woocommerce-tabs ul.tabs" ).append( "<li><a href='#related-products'>Related Products</a></li>" );
	//jQuery( ".woocommerce-tabs" ).addClass( "navbar-fixed-top" );
</script>
<script src="https://www.google.com/recaptcha/api.js"></script>
<script>
jQuery(document).ready(function(){
  jQuery('.case-study').slick({
     autoplay: true,
     autoplaySpeed: 4000,
});
});
</script>
<script>
jQuery(document).ready(function(){
  jQuery('.logos').slick({
    infinite: true,
	slidesToShow: 4,
  	slidesToScroll: 1,
  	autoplay: true,
	draggable: false,
  	autoplaySpeed: 2000,
  });
  jQuery('.scroll').slick({
    infinite: false,
	slidesToShow: 2,
  	slidesToScroll: 1,
  	autoplay: true,
	draggable: false,
	autoplaySpeed: 2000
	//arrows:true,
  });
});
</script>
<script>
jQuery(function() {
  jQuery('#ChangeToggle').click(function() {
    jQuery('#navbar-hamburger').toggleClass('hidden');
    jQuery('#navbar-close').toggleClass('hidden');  
  });
});
</script>
<script>
jQuery(document).ready(function() {
    jQuery('#topbar .navbar-default li').click(function() {
		jQuery('#topbar .navbar-default li').not(this).removeClass("active");
        jQuery(this).toggleClass('active');
    });
});
jQuery(document).ready(function() {
    jQuery('li.attribute_names .navbar-default ul li').click(function() {
        jQuery(this).addClass('active');
    });
});
jQuery(document).ready(function() {
    jQuery('li.child_name').click(function() {
        jQuery(this).addClass('active');
    });
});
/*
jQuery(document).ready(function() {
    jQuery('.wc-tabs li a').click(function() {
		
		setTimeout(function(){ 
			var y = jQuery(window).scrollTop();  //your current y position on the page
			jQuery(window).scrollTop(y-150);
		}, 50);
    });
});
*/
jQuery(document).ready(function() {
	jQuery('.wc-tabs li a').click(function(e) {
   		var activeDestination = jQuery(this).attr("href");
		jQuery('html,body').animate({
		scrollTop: jQuery(activeDestination).offset().top -150}, 1000);
		e.preventDefault();
	});
});
</script>
    
    
<script>
    /*jQuery('#responsive-menu-button').sidr({
		
      name: 'sidr-main',
      source: '#topbar, .child_row'
	 
    });*/
	
$( ".cross" ).hide();
$( ".menu" ).hide();
$( ".hamburger" ).click(function() {
$( ".menu" ).slideToggle( "slow", function() {
$( ".hamburger" ).hide();
$( ".cross" ).show();
});
});
$( ".cross" ).click(function() {
$( ".menu" ).slideToggle( "slow", function() {
$( ".cross" ).hide();
$( ".hamburger" ).show();
});
});
</script>
 <?php /* */ ?>
<?php wp_footer(); ?>		
	<script>
		/*	 else if(document.getElementById('pa_paper_thickness'))	
		  {	  var element3 = document.getElementById("pa_paper_thickness");
			  var element3_name = 'Thickness';
			  var element3_id = 'tr_pa_paper_thickness';
			  document.getElementById("element3_name").innerHTML = element3_name;
			  document.getElementById("element3_desc").innerHTML = 'Select variation above';
		  } */

	  	if(document.getElementById("woosvithumbs")) SetDescriptionImage();
		SetDescriptionText();
		function SetDescriptionImage()
		{  
		setTimeout(function() 
		{ // SET THE INITIAL "DESCRIPTION/IMAGE" DPENDING ON VARIATION
		  var str = document.getElementById("woosvithumbs").innerHTML, 
		  m,
    	  urls = [], 
    	  rex = /<img.*?src="([^">]*\/([^">]*?))".*?>/g; 
   		  while ( m = rex.exec( str ) ) { urls.push( m[1] ); }
		  if(urls[3] != null) 
		  {
  			  var image1 = urls[3].replace("-300x300", "");
			  var image2 = image1.replace("?resize=300%2C300", "?resize=800%2C800");
			  var image3 = image2.replace("-300x300", "");
			  var image4 = image3.replace("?resize=300%2C300", "?resize=800%2C800");
			  var image5 = image4.replace("-300x300", "");
			  var image6 = image5.replace("?resize=300%2C300", "?resize=800%2C800");
			  document.getElementById("product_description_image").innerHTML = '<img src="' + image6 + '" width="600" height="600">';
		  }else if(document.getElementById("product_description_image"))
		  {document.getElementById("product_description_image").innerHTML = ' ';
		  }},900);}		
		function SetDescriptionText()
		{if(document.getElementById('pa_diameter')){
			  var element = document.getElementById("pa_diameter");
			  var element_name = 'Diameter';
			  var element_id = 'tr_pa_diameter';
			  document.getElementById("element_name").innerHTML = element_name;
			  document.getElementById("element_desc").innerHTML = 'Select your diameter above';
			  if(document.getElementById('pa_spine-color')) 
		  	  { var element2 = document.getElementById("pa_spine-color");
			    var element2_name = 'Spine Color';
			    var element2_id = 'tr_pa_spine-color';
			    document.getElementById("element2_name").innerHTML = element2_name;
			    document.getElementById("element2_desc").innerHTML = 'Select your spine color above';
		  	  } else {element2 = 'N/A'; element2_id = 'N/A';}}
		  else if(document.getElementById('pa_circumference')) 
		  {	  var element = document.getElementById("pa_circumference");
			  var element_name = 'Circumference';
			  var element_id = 'tr_pa_circumference';
			  document.getElementById("element_name").innerHTML = element_name;
			  document.getElementById("element_desc").innerHTML = 'Select your circumference above';
			  if(document.getElementById('pa_color'))
			  { var element2 = document.getElementById("pa_color");
			    var element2_name = 'Color';
			    var element2_id = 'tr_pa_color';
			    document.getElementById("element2_name").innerHTML = element2_name;
			    document.getElementById("element2_desc").innerHTML = 'Select Your Ring Color Above'; }
			  else { element2 = 'N/A'; element2_id = 'N/A'; } }
		  else if(document.getElementById('pa_hole-count')) 
		  {	  var element = document.getElementById("pa_hole-count");
			  var element_name = 'Hole Count';
			  var element_id = 'tr_pa_hole-count';
			  document.getElementById("element_name").innerHTML = element_name;
			  document.getElementById("element_desc").innerHTML = 'Select your hole count above';
			  element2 = 'N/A';
			  element2_id = 'N/A';  }
		  else if(document.getElementById('pa_cover-type') && document.getElementById('pa_cover-dimensions'))
		  {	  var element = document.getElementById("pa_cover-type");
			  var element_name = 'Cover Type';
			  var element_id = 'tr_pa_cover-type';
			  document.getElementById("element_name").innerHTML = element_name;
			  document.getElementById("element_desc").innerHTML = 'Select your cover type above'; 
			  if(document.getElementById('pa_cover-dimensions')) 
		  	  { var element2 = document.getElementById("pa_cover-dimensions");
			    var element2_name = 'Cover Dimensions';
			    var element2_id = 'tr_pa_cover-dimensions';
			    document.getElementById("element2_name").innerHTML = element2_name;
			    document.getElementById("element2_desc").innerHTML = 'Select your page cover dimensions above';
		  	  } else { element2 = 'N/A'; element2_id = 'N/A';}}
		  else if(document.getElementById('pa_material') && document.getElementById('pa_page-size')) 
		  {	  var element = document.getElementById("pa_material");
			  var element_name = 'Material';
			  var element_id = 'tr_pa_material';
			  document.getElementById("element_name").innerHTML = element_name;
			  document.getElementById("element_desc").innerHTML = 'Select your material above';
			  if(document.getElementById("pa_page-size"))
			  {	var element2 = document.getElementById("pa_page-size");
				var element2_name = 'Page Size';
				var element2_id = 'tr_pa_page-size';
				document.getElementById("element2_name").innerHTML = element2_name;
				document.getElementById("element2_desc").innerHTML = 'Select your page size above'; }
			  else { element2 = 'N/A'; element2_id = 'N/A'; }}
		  else if(document.getElementById('pa_material')) 
		  {   var element = document.getElementById("pa_material");
			  var element_name = 'Material';
			  var element_id = 'tr_pa_material';
			  document.getElementById("element_name").innerHTML = element_name;
			  document.getElementById("element_desc").innerHTML = 'Select your material above';		  
			  element2 = 'N/A';
			  element2_id = 'N/A'; }
		  else if(document.getElementById('pa_color')) 
		  {	  var element = document.getElementById("pa_color");
			  var element_name = 'Color';
			  var element_id = 'tr_pa_color';
			  document.getElementById("element_name").innerHTML = element_name;
			  document.getElementById("element_desc").innerHTML = 'Select your color above';
			  if(document.getElementById('pa_cover-type')) 
		  	  { var element2 = document.getElementById("pa_cover-type");
			    var element2_name = 'Cover Type';
			    var element2_id = 'tr_pa_cover-type';
			    document.getElementById("element2_name").innerHTML = element2_name;
			    document.getElementById("element2_desc").innerHTML = 'Select your page cover type above';
		  	  } else { element2 = 'N/A'; element2_id = 'N/A'; } }
		  else if(document.getElementById('pa_voltage') && document.getElementById('pa_hole-pattern'))
		  {	  var element = document.getElementById("pa_voltage");
			  var element_name = 'Voltage';
			  var element_id = 'tr_pa_voltage';
			  document.getElementById("element_name").innerHTML = element_name;
			  document.getElementById("element_desc").innerHTML = 'Select your voltage above';
			  if(document.getElementById('pa_hole-pattern')) 
		  	  { var element2 = document.getElementById("pa_hole-pattern");
			    var element2_name = 'Hole Pattern';
			    var element2_id = 'tr_pa_hole-pattern';
			    document.getElementById("element2_name").innerHTML = element2_name;
			    document.getElementById("element2_desc").innerHTML = 'Select your hole pattern above'; } else { element2 = 'N/A'; element2_id = 'N/A'; } }  
		  else if(document.getElementById('pa_hole-pattern')) 
		  {   var element = document.getElementById("pa_hole-pattern");
			  var element_name = 'Hole Pattern';
			  var element_id = 'tr_pa_hole-pattern';
			  document.getElementById("element_name").innerHTML = element_name;
			  document.getElementById("element_desc").innerHTML = 'Select your hole pattern above';
			  element2 = 'N/A';
			  element2_id = 'N/A'; } 
		  else if(document.getElementById('pa_paper-weight')) {
			  var element = document.getElementById("pa_paper-weight");
			  var element_name = 'Paper Weight';
			  var element_id = 'tr_pa_paper-weight';
			  document.getElementById("element_name").innerHTML = element_name;
			  document.getElementById("element_desc").innerHTML = 'Select your paper weight above';
			  element2 = 'N/A';
			  element2_id = 'N/A';
			  document.getElementById("download_tr").style.display='none'; }
		  else if(document.getElementById('pa_page-size')) 
		  {	  var element = document.getElementById("pa_page-size");
			  var element_name = 'Page Size';
			  var element_id = 'tr_pa_page-size';
			  document.getElementById("element_name").innerHTML = element_name;
			  document.getElementById("element_desc").innerHTML = 'Select your page size above';
			  element2 = 'N/A';
			  element2_id = 'N/A'; }
 	/*	  else if(document.getElementById('pa_paper_thickness'))	
		  {	  var element3 = document.getElementById("pa_paper_thickness");
			  var element3_name = 'Thickness';
			  var element3_id = 'tr_pa_paper_thickness';
			  document.getElementById("element3_name").innerHTML = element3_name;
			  document.getElementById("element3_desc").innerHTML = 'Select variation above';
		  } */
		  else if(document.getElementById('pa_voltage'))
		  {	  var element = document.getElementById("pa_voltage");
			  var element_name = 'Voltage';
			  var element_id = 'tr_pa_voltage';
			  document.getElementById("element_name").innerHTML = element_name;
			  document.getElementById("element_desc").innerHTML = 'Select your voltage above';
		  	  element2 = 'N/A';
			  element2_id = 'N/A'; }
		  else if(document.getElementById('pa_cover-type')) /******************  edit   ***************************************/
		  {	  var element = document.getElementById("pa_cover-type");
			  var element_name = 'Cover Type';
			  var element_id = 'tr_pa_cover-type';
			  document.getElementById("element_name").innerHTML = element_name;
			  document.getElementById("element_desc").innerHTML = 'Select your page cover type above';
			  var element2 = 'N/A';
			  var element2_id = 'N/A'; }
		  else if(document.getElementById('pa_spine-color')) 
		  {	  var element = document.getElementById("pa_spine-color");
			  var element_name = 'Spine Color';
			  var element_id = 'tr_pa_spine-color';
			  document.getElementById("element_name").innerHTML = element_name;
			  document.getElementById("element_desc").innerHTML = 'Select your spine color above';
			  element2 = 'N/A';
			  element2_id = 'N/A'; }
			  else if(document.getElementById('pa_tab-style')) 
		  {	  var element = document.getElementById("pa_tab-style");
			  var element_name = 'Tab Style';
			  var element_id = 'tr_pa_tab-style';
			  document.getElementById("element_name").innerHTML = element_name;
			  document.getElementById("element_desc").innerHTML = 'Select your tab style above';
			  element2 = 'N/A';
			  element2_id = 'N/A'; }  
		  else {
			  element = 'N/A';
			  element_id = 'N/A';
			  element2 = 'N/A';
			  element2_id = 'N/A'; }
		  if(element_id != 'N/A') document.getElementById(element_id.toString()).innerHTML = '';
		  if(element2_id != 'N/A') document.getElementById(element2_id.toString()).innerHTML = '';
	/*	  if(element3_id != 'N/A') document.getElementById(element3_id.toString()).innerHTML = '';*/
		  if(element2_id === 'N/A') document.getElementById("element2_desc").parentNode.style.display='none';
		  if(element2_id === 'N/A') document.getElementById("element2_name").parentNode.style.display='none';
		/*  if(element3_id === 'N/A') document.getElementById("element3_desc").parentNode.style.display='none';
		  if(element3_id === 'N/A') document.getElementById("element3_name").parentNode.style.display='none'; */
		if(element_id != 'N/A') 
		{ element.addEventListener("change", function(e) {	
			if(document.getElementById('pa_paper-weight')) 
			{ document.getElementById("element_desc").innerHTML = element.value.replace("-", "# "); PaperPunchThickness(); }
            else if(document.getElementById('tr_pa_cover-thickness') && document.getElementById('tr_pa_hole-count'))
            { document.getElementById("element_desc").innerHTML = element.value.replace("-", " "); CoverThickness2(); }/*CoverThickness2() */	
			else if(document.getElementById('pa_material') && document.getElementById('pa_page-size'))
			{ document.getElementById("element_desc").innerHTML = element.value.replace("-"," ");}
			else if(document.getElementById('pa_page-size')) 
			{document.getElementById("element_desc").innerHTML = element.value.replace("-", ".").replace("-","&quot ").replace("-"," ").replace("-",".").replace("8.5","8.5&quot ").replace("11","11&quot");}
			else if(document.getElementById('pa_cover-type') && document.getElementById('pa_cover-dimensions'))
			{document.getElementById("element_desc").innerHTML = element.value.replace("-", " "); }
			else if(document.getElementById('pa_diameter'))
			{document.getElementById("element_desc").innerHTML = element.value.replace("-",".").replace("-"," ").replace("-"," ").replace("-",".").replace("-"," ");}
			else {// UPDATE THE "ATTRIBUTE TEXT" DEPENDING ON VARIATION
			document.getElementById("element_desc").innerHTML = element.value.replace("-", " ");}
			// UPDATE THE "PRICE" DPENDING ON VARIATION
			setTimeout(function() 
			{ if( (document.getElementById("woocommerce-variation-price")) && (document.getElementById("woocommerce-variation-price").innerHTML.length > 10) )
			  {	//alert(document.getElementById("woocommerce-variation-price").innerHTML.length);
			    var temp_price = document.querySelector('woocommerce-Price-amount').innerHTML.replace(/<(?:.|\n)*?>/gm, '');
			  	var temp_price_2 = temp_price.replace('$', '');
			  	var temp_price_3 = temp_price_2.trim();
			  	document.querySelector('woocommerce-Price-amount').innerHTML = '<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>' + temp_price_3 + '</span>–<span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>' + temp_price_3 + '</span>'; }}, 100);		
			// UPDATE THE "DESCRIPTION/IMAGE" DEPENDING ON VARIATION
			setTimeout(function() 
			{	var str = document.getElementById("woosvithumbs").innerHTML, 
				m, 
				urls = [], 
				rex = /<img.*?src="([^">]*\/([^">]*?))".*?>/g; 
				while ( m = rex.exec( str ) ) { urls.push( m[1] ); }
				if(urls[3] != null) 
				{	  var image1 = urls[3].replace("-300x300", "");
			  var image2 = image1.replace("?resize=300%2C300", "?resize=800%2C800");
					var image3 = image2.replace("-300x300", "");
					var image4 = image3.replace("?resize=300%2C300", "?resize=555%2C558");
			  		document.getElementById("product_description_image").innerHTML = '<a href="' + image4 + '"title data-rel="prettyPhoto[product-gallery]" class="woocommerce-main-image zoom" download><img src="' + image4 + '" width="600" height="600"></a>';
				document.getElementById("woosvithumbs").style.display = 'inherit';}
				else if(document.getElementById("product_description_image"))
				{document.getElementById("product_description_image").innerHTML = ' ';}}, 1000);  }, false);}
			if(element2_id != 'N/A') {
		  element2.addEventListener("change", function(e) {
			if(document.getElementById('pa_page-size') && document.getElementById('pa_paper-weight')) 
			{document.getElementById("element2_desc").innerHTML = element2.value.replace("-", "# ");}
			else if(document.getElementById('pa_material') && document.getElementById('pa_page-size'))
			{document.getElementById("element2_desc").innerHTML = element2.value.replace("-", ".").replace("-","&quot ").replace("-"," ").replace("-",".").replace("8.5","8.5&quot ").replace("11","11&quot");}  
			else if(document.getElementById('pa_cover-type') && document.getElementById('pa_cover-dimensions'))
			{document.getElementById("element2_desc").innerHTML = element2.value.replace("-",".").replace("-"," ").replace("-"," ").replace("-",".").replace("-"," ");
			CoverThickness();}
			else if(document.getElementById('pa_hole_count') && document.getElementById('pa_cover-type'))
				{
					CoverThickness();
				}
			else if(document.getElementById('pa_color') && document.getElementById('pa_cover-type'))
			{document.getElementById("element2_desc").innerHTML = element2.value.replace("-"," ").replace("-"," ");
			CoverThickness(); }
			  	/*	else if(document.getElementById('pa_cover-type'))
			{ CoverThickness(); }*/
			else {
			// UPDATE THE "ATTRIBUTE TEXT" DEPENDING ON VARIATION
			document.getElementById("element2_desc").innerHTML = element2.value.replace("-"," ");}				
			// UPDATE THE "DESCRIPTION/IMAGE" DEPENDING ON VARIATION
			setTimeout(function() 
			{ var str = document.getElementById("woosvithumbs").innerHTML, 
				m, urls = [], rex = /<img.*?src="([^">]*\/([^">]*?))".*?>/g; 
				while ( m = rex.exec( str ) ) { urls.push( m[1] ); }
				if(urls[3] != null) 
				{  var image1 = urls[3].replace("-300x300", "");
			  var image2 = image1.replace("?resize=300%2C300", "?resize=800%2C800");
					var image3 = image2.replace("-300x100", "");
					var image4 = image3.replace("?resize=300%2C300", "?resize=555%2C558");
			  		document.getElementById("product_description_image").innerHTML = '<a href="' + image4 + '"title data-rel="prettyPhoto[product-gallery]" class="woocommerce-main-image zoom" download><img src="' + image4 + '" width="600" height="600"></a>';
					document.getElementById("woosvithumbs").style.display = 'inherit';}
				else if(document.getElementById("product_description_image"))
				{document.getElementById("product_description_image").innerHTML = ' ';}}, 1000);}, false);} } 	
		/* working	*/
		if(document.getElementById('tr_pa_paper-thickness')) PaperPunchThickness();	
		if(document.getElementById('tr_pa_paper-thickness')) {	
			var element = document.getElementById("pa_paper-weight"); 
	/*		element.addEventListener("change", function(e) {	
				PaperPunchThickness();	*/
		}/*)}*/
		function PaperPunchThickness() {
		setTimeout(function() 
		{  var element = document.getElementById("pa_paper-weight");
			var thickness = '';
			if(document.getElementById('pa_paper-weight')) {
				var weight = document.getElementById('pa_paper-weight').selectedIndex;
				/*alert(weight);*/
				if( weight == 1 /*'20# (75gsm)'*/ ) {
				var thickness = '0.004 Inches';	
				document.getElementById('tr_pa_paper-thickness').innerHTML = '<th>Paper Thickness</th><td><p>'+ thickness +'</p>'
				}
				else if( weight == 2 /*'28# (100gsm)'*/ ) {
				var thickness = '0.0045 Inches';	
				document.getElementById('tr_pa_paper-thickness').innerHTML = '<th>Paper Thickness</th><td><p>'+ thickness +'</p>'
				}
				else if( weight == 3 /*'65# (170gsm)'*/ ) {
				var thickness = '0.0085 Inches';	
				document.getElementById('tr_pa_paper-thickness').innerHTML = '<th>Paper Thickness</th><td><p>'+ thickness +'</p>'
				}
				else if( weight == 4 /*'80# (215gsm)'*/ ) {
				var thickness = '0.011 Inches';	
				document.getElementById('tr_pa_paper-thickness').innerHTML = '<th>Paper Thickness</th><td><p>'+ thickness +'</p>'
				}
			/*	else if( weight = '28# (100gsm)' ) {
				var thickness = '0.02';	
				document.getElementById('tr_pa_paper-thickness').innerHTML = '<th>Paper Thickness</th><td><p>'+ thickness +'</p>'
				} */			
				else document.getElementById('tr_pa_paper-thickness').innerHTML = '<th>Paper Thickness</th><td><p>N/A</p>';
			}
		}, 600);
		}
		
		function CoverThickness2() {
		setTimeout(function() 
		{  var holecount = document.getElementById("pa_hole-count");
			var thickness = '';
			if(document.getElementById('pa_hole-count')) {
				var hole = holecount.options[holecount.selectedIndex].text;
				if( hole == '12 Hole') {
				var thickness = '0.045 Inches';
				document.getElementById('tr_pa_cover-thickness').innerHTML = '<th>Cover Thickness</th><td><p>'+ thickness +'</p>'
				}
                else if(hole == '22 Hole') {
                    var thickness = '0.045 Inches';
				document.getElementById('tr_pa_cover-thickness').innerHTML = '<th>Cover Thickness</th><td><p>'+ thickness +'</p>'
                }
                else if( hole == '10 Hole') {
                   var thickness = '0.04 Inches';
				document.getElementById('tr_pa_cover-thickness').innerHTML = '<th>Cover Thickness</th><td><p>'+ thickness +'</p>'
				}
				else document.getElementById('tr_pa_cover-thickness').innerHTML = '<th>Cover Thickness</th><td><p>N/A</p>';
			}
		}, 600);
		}
		if(document.getElementById('tr_pa_cover-thickness')) CoverThickness();	
		if(document.getElementById('tr_pa_cover-thickness')) {	
			var covertype = document.getElementById("pa_cover-type"); 
		}
		function CoverThickness() {
		setTimeout(function() 
		{  var covertype = document.getElementById("pa_cover-type");
			var thickness = '';
			if(document.getElementById('pa_cover-type')) {
				var type = covertype.options[covertype.selectedIndex].text;
				/*alert(weight);*/
				if( type == 'Flexible' ) {
				var thickness = '0.04 Inches';
				document.getElementById('tr_pa_cover-thickness').innerHTML = '<th>Cover Thickness</th><td><p>'+ thickness +'</p>'
				}
				else if( type == 'Window' || type == 'Window and Pocket' || type == 'No Pockets' || type == '2 Pockets' || type == 'Rigid' ) {
				var thickness = '0.045 Inches';	
				document.getElementById('tr_pa_cover-thickness').innerHTML = '<th>Cover Thickness</th><td><p>'+ thickness +'</p>'
				}
				/*else if( type == 3 /*'65# (170gsm)' ) {
				var thickness = '0.0085 Inches';	
				document.getElementById('tr_pa_paper-thickness').innerHTML = '<th>Cover Thickness</th><td><p>'+ thickness +'</p>'
				}
				else if( type == 4 /*'80# (215gsm)' ) {
				var thickness = '0.011 Inches';	
				document.getElementById('tr_pa_paper-thickness').innerHTML = '<th>Cover Thickness</th><td><p>'+ thickness +'</p>'
				}*/
			/*	else if( weight = '28# (100gsm)' ) {
				var thickness = '0.02';	
				document.getElementById('tr_pa_paper-thickness').innerHTML = '<th>Paper Thickness</th><td><p>'+ thickness +'</p>'
				} */			
				else document.getElementById('tr_pa_cover-thickness').innerHTML = '<th>Cover Thickness</th><td><p>N/A</p>';
			}
		}, 600);
		}
        
		</script>
 <?php /* */ ?>
</body>
</html>