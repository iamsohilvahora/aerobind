<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $tabs ) ) : ?>

<!DOCTYPE html>

<html lang="en">

  <head>
  
     <script
  src="https://code.jquery.com/jquery-3.1.1.min.js"
  integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
  crossorigin="anonymous"></script>
     <script src="http://baselinedev.com/aerobind/wp-content/themes/baseline-custom/js/jquery.sticky.js"></script>
	<script> 
	var jq = jQuery.noConflict();
		jq(document).ready(function(){
			jq("#testbaseline").sticky({topSpacing:0});
			
		});
	</script>
    </head>
   
<!--<div  class="woocommerce-tabs wc-tabs-wrapper">
class="tabs wc-tabs"-->


		<ul id="testbaseline" >
			<?php foreach ( $tabs as $key => $tab ) : ?>
				<li class="<?php echo esc_attr( $key ); ?>_tab">
					<a href="#tab-<?php echo esc_attr( $key ); ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ); ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
        
		<?php foreach ( $tabs as $key => $tab ) : ?>
            <a id="tab-<?php echo esc_attr( $key ); ?>"></a>
            <div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab" id="tab-<?php echo esc_attr( $key ); ?>">
            <!--<div class="container">-->
				<?php call_user_func( $tab['callback'], $key, $tab ); ?>
			<!--</div>-->
            </div>
		<?php endforeach; ?>
	</div>
  </html>  
<!--<script src="http://baselinedev.com/aerobind/wp-content/themes/baseline-custom/js/jquery.sticky.js"></script>
	<script> 
	var jq = jQuery.noConflict();
		jq(document).ready(function(){
			jq(".sticky-wrapper").sticky({topSpacing:0});
			
		});
	</script>-->
    
     
    
<?php endif; ?>


