<?php
/**
 * Single Product Price, including microdata for SEO
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

?>
<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">

	<?php
	
		// add_filter( 'woocommerce_variable_sale_price_html', 'support_customization_variation_price_format', 10, 2 );
		// add_filter( 'woocommerce_variable_price_html', 'support_customization_variation_price_format', 10, 2 );

		/**
		 * Display only minimum price on variable products.
		 */
		function support_customization_variation_price_format( $price, $product ) {


			global $post;
			$id = $post->ID;

			$product_id = $id; // Defined product Id for testing

			$product = wc_get_product($product_id); // If needed

			$product_data = $product->get_meta_data($product_id);

			$data= get_post_meta($product_id,'_pricing_rules',true);

			foreach($data as $key => $value)
			{
				$amount = $value['rules'];
				
				foreach($amount as $value1){
					$array1[] = $value1['amount'];
				}

			}

			$countkeys = count(array_values(($array1)));
			$maxprice = max($array1);
			$minprice = min($array1);

			if($countkeys >= 5){
				$price = '<span class="woocommerce-Price-amount amount"><bdi>'. '$' . $minprice . '</bdi></span> - <span class="woocommerce-Price-amount amount"><bdi>' . '$' . $maxprice . '</bdi></span>' ;
			}else{
				$price = '<span class="woocommerce-Price-amount amount"><bdi>' . '$' . $maxprice . '</bdi></span>' ;
			}

			return $price;
		}
			
		
	?>
	
	<?php //do_action( 'woocommerce_product_meta_start' ); ?>

	<?php //if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

		<!--<p class="sku_wrapper"><?php //_e( 'SKU:', 'woocommerce' ); ?> <span class="sku" itemprop="sku"><?php //echo ( $sku = $product->get_sku() ) ? $sku : __( 'N/A', 'woocommerce' ); ?></span></p>-->

	<?php //endif; ?>
<p class="price"><?php echo $product->get_price_html(); ?></p>
 <p class="amount">
    	<!--<small>From</small><span id="price_amount">--><?php /* echo $product->get_price_html(); */ ?><!--</span>-->
    </p>

	<meta itemprop="price" content="<?php echo esc_attr( $product->get_display_price() ); ?>" />
	<meta itemprop="priceCurrency" content="<?php echo esc_attr( get_woocommerce_currency() ); ?>" />
	<link itemprop="availability" href="http://schema.org/<?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock'; ?>" />

</div>
<hr />