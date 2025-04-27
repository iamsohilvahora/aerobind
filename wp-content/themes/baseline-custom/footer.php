<?php
/**
 * @package WordPress
 * @subpackage Baseline Custom
 */
?>

<!-- .site-footer -->
<footer id="colophon" class="site-footer" role="contentinfo">
	<div class="container">
    	<div class="row">
        	<div class="col-sm-4 col-md-3">
            	<p><a class="navbar-brand2" href="/aerobind/"><img src="<?php bloginfo('template_directory');?>/images/aerobind-logo-footer.jpg" alt="Aerobind" /></a></p>
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
            <div class="col-md-3 hidden-xs hidden-sm">
            	<?php dynamic_sidebar( 'mailing-list-form' ); ?>
            </div>
            <div class="col-sm-4 col-md-3">
            	<?php dynamic_sidebar( 'address-and-phone' ); ?>
            </div>
          	<div class="col-sm-4 col-md-3">
            	<?php dynamic_sidebar( 'hours-of-operation' ); ?>
            </div>
        </div>
        <div class="row copyright">
        	<div class="col-sm-6">
            	<p>&copy; <?php echo date('Y'); ?> Aerobind. All Rights Reserved. <a href="/aerobind/aerobind-sitemap/">Sitemap</a>.</p>
            </div>
            <div class="col-sm-6 seal">
            	<div class="AuthorizeNetSeal"><!-- (c) 2005, 2013. Authorize.Net is a registered trademark of CyberSource Corporation-->
      				<script language="javascript" type="text/javascript">// <![CDATA[
        			var ANS_customer_id="e709bb1e-6ef3-4fc4-8013-d8fb163d2ce9";// ]]>
					</script>
      				<script language="javascript" src="//verify.authorize.net/anetseal/seal.js" type="text/javascript"></script>
      			</div>
    		<div class="badges"></div>
            </div>
        </div>
    </div>
</footer>
<!-- .site-footer -->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="//code.jquery.com/jquery.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?php bloginfo('template_directory');?>/includes/bootstrap/js/bootstrap.min.js"></script>
<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';fnames[3]='MMERGE3';ftypes[3]='text';fnames[5]='MMERGE5';ftypes[5]='text';fnames[6]='MMERGE6';ftypes[6]='text';fnames[7]='MMERGE7';ftypes[7]='text';fnames[8]='MMERGE8';ftypes[8]='text';fnames[9]='MMERGE9';ftypes[9]='text';fnames[10]='MMERGE10';ftypes[10]='text';fnames[11]='MMERGE11';ftypes[11]='text';fnames[12]='MMERGE12';ftypes[12]='text';fnames[13]='MMERGE13';ftypes[13]='text';fnames[14]='MMERGE14';ftypes[14]='text';fnames[15]='MMERGE15';ftypes[15]='text';fnames[16]='MMERGE16';ftypes[16]='text';fnames[20]='MMERGE20';ftypes[20]='address';}(jQuery));var $mcj = jQuery.noConflict(true);</script>

<!--<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>-->
<script type="text/javascript" src="<?php bloginfo('template_directory');?>/includes/slick/slick.min.js"></script>

<script>
	//jQuery( ".panel .thumbnails a" ).removeClass( "first" );
	//jQuery( ".panel .thumbnails a" ).removeClass( "last" );
	//jQuery( ".woocommerce-tabs ul.tabs li" ).removeClass( "active" );
</script>

<script>
	jQuery( ".woocommerce-tabs ul.tabs" ).append( "<li><a href='#related-products'>Related Products</a></li>" );
	//jQuery( ".woocommerce-tabs" ).addClass( "navbar-fixed-top" );
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
	slidesToShow: 3,
  	slidesToScroll: 1,
  	autoplay: false,
	draggable: false
  });
});
</script>

<?php wp_footer(); ?>
</body>
</html>