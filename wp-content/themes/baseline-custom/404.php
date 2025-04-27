<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header(); ?>
<div class="container">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<section class="error-404 not-found">
					<h1 class="page-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'twentysixteen' ); ?></h1>
			<!-- .page-header -->
				<div class="page-content">
					<p><?php _e( 'It looks like nothing was found at this location.', 'twentysixteen' ); ?></p>

					
                    <div class="clear"></div>
                    
                    <div class="row">
                    	<div class="col-sm-6 col-md-4">
                        <h3>Browse Our Site Map</h3>
                        	<ul class="moremargin nostyle">
	<li><a href="/aerobind/"><strong>Home</strong></a></li>
	<li><a href="/aerobind/about-aerobind/"><strong>About Aerobind</strong></a></li>
	<li><a href="/aerobind/services/"><strong>Services</strong></a></li>
        <li><a href="/aerobind/category/press-releases/"><strong>Press Releases</strong></a></li>
	<li><a href="/aerobind/category/aerobind-news/"><strong>Aerobind News</strong></a></li>
	<li><a href="/aerobind/contact/"><strong>Contact Aerobind</strong></a></li>
</ul>
                        </div>
                        <div class="col-sm-6 col-md-4 second">
                        	<ul class="moremargin nostyle">
                            <li><strong>Products</strong>
           <ul>
              	<li><a href="/aerobind/product-category/binders/"><strong>Binders</strong></a></li>
	        <li><a href="/aerobind/product-category/divider-tabs/"><strong>Divider Tabs</strong></a></li>
	        <li><a href="/aerobind/product-category/sheet-protectors/"><strong>Sheet Protectors</strong></a></li>
	        <li><a href="/aerobind/product-category/covers/"><strong>Covers</strong></a></li>
	        <li><a href="/aerobind/product-category/paper-punches/"><strong>Paper Punches</strong></a></li>
	        <li><a href="/aerobind/product-category/paper-stock/"><strong>Paper Stock</strong></a></li>
           </ul>
</li></ul>
                        </div>
                        <div class="visible-sm clear"></div>
                        <div class="col-md-4">
                        	<h3>Or, Try a Search</h3>
                       	  <?php dynamic_sidebar( 'search' ); ?>
                        </div>
                    </div>
			  </div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- .site-main -->

	</div><!-- .content-area -->
</div>
<?php get_footer(); ?>
