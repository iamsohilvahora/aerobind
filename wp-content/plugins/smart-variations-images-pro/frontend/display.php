<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

global $post, $woocommerce, $product, $woosvi, $woosvi_class;
$image_class = 'images ';
if ($woosvi['sviforce_image'])
    $image_class = '';
?>
<div class="container">
	<div class="row">
		<div class="col-md-6">
			<div class="images">
	<?php
		the_title( '<h1 itemprop="name" class="hidden-md hidden-lg hidden-xl product_title entry-title">', '</h1>' ); ?>

<div class="whitespacesvi">&nbsp;</div>
<div id="woosvi_strap" class="<?php echo $image_class; ?>woosvi_images <?php echo $woosvi['custom_class']; ?>">
    <div class="sivmainloader">Loading...</div>
    <div id="woosvimain" class="svihidden <?php if ($woosvi['slider']) { ?>swiper-container svigallery-main<?php } ?>" dir="ltr">
        <?php if ($woosvi['slider']) { ?><div class="swiper-wrapper"></div><?php } ?>
    </div>

    <div id="woosvithumbs" class="svihidden <?php if ($woosvi['slider']) { ?>swiper-container svigallery-thumbs<?php } ?>" dir="ltr">
        <?php if ($woosvi['slider']) { ?><div class="swiper-wrapper"></div><?php } ?>
    </div>
</div>
 <div class="clear visible-sm visible-lg visible-xl"></div>
</div>
</div>