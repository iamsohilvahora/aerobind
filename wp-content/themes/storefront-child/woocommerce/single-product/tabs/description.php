<?php
/**
 * Description tab
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product;

$heading = esc_html( apply_filters( 'woocommerce_product_description_heading', __( 'Overview & Specifications', 'woocommerce' ) ) );

?>

<?php if ( $heading ): ?>
  <h2><?php echo $heading; ?></h2>
<?php endif; ?>
<div class="container">
	<?php the_content(); ?>
    <div class="row">
		<div class="col-sm-6 center main-image Specifications-draw" id="spec-drawing">
		
        	<?php
		if ( has_post_thumbnail() ) {
			$attachment_count = count( $product->get_gallery_attachment_ids() );
			$gallery          = $attachment_count > 0 ? '[product-gallery]' : '';
			$props            = wc_get_product_attachment_props( get_post_thumbnail_id(), $post );
			$image            = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
				'title'	 => $props['title'],
				'alt'    => $props['alt'],
			) );
			
			
			$attachment_ids = $product->get_gallery_attachment_ids();
			
			$attributes = $product->get_attributes();
			//if(isset($attributes['pa_diameter'])) echo 'DIAMETER!'; else echo "NO!";

			if(!isset($attributes['pa_diameter']))
			{
			  $x = 1;
			  foreach( $attachment_ids as $attachment_id ) 
			  {
  			    if($x == 3) $image = '<img src="' . wp_get_attachment_url( $attachment_id ) . '?resize=600%2C600" class="attachment-shop_single size-shop_single wp-post-image" alt="' . $props['alt'] . '" title="' . $props['title'] . '" width="600" height="600">';
			    $x++;
			  }
			}
			
			?>
            
            <div id="product_description_image" class="inner-diameter">
           
            </div>
            
            <?php
			
			if(!isset($attributes['pa_diameter']))
			{
			  /*echo apply_filters(
				'woocommerce_single_product_image_html',
				sprintf(
					'<a href="%s" itemprop="image" class="woocommerce-main-image zoom" title="%s" data-rel="prettyPhoto%s">%s</a>',
					esc_url( $props['url'] ),
					esc_attr( $props['caption'] ),
					$gallery,
					$image
				),
				$post->ID
			  );*/
			}
		} else {
			//echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), __( 'Placeholder', 'woocommerce' ) ), $post->ID );
		}	
	?>
        </div>
        <div class="col-sm-6 attributes">
        	<?php $product->list_attributes(); ?>
        </div>
	</div>
</div>
<div class="spec" id="tab-drawing">    
    <?php 
		$image = get_field('image_spec');
		if( !empty($image) ): ?>
		<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" />
	<?php endif; ?>
</div>