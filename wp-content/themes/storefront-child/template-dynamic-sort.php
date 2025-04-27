<?php
/**
/**
 * The template for displaying custom pages.
 *
 * Template Name: Dynamic Sort
 *
 * @package storefront
 */

get_header(); ?><?php
/* <?php if ( has_nav_menu( 'about' ) && is_page('about-aerobind') ) : ?>
<div class="container">
	<nav id="sub-navigation" class="sub-navigation" role="navigation" aria-label="<?php esc_attr_e( 'About Sub Menu', 'baseline-custom' ); ?>">
		<?php
		wp_nav_menu( array(
		'theme_location' => 'about',
		'menu_class'     => 'about',
		) );
		?>
	</nav><!-- .about-navigation -->
</div>
<?php endif; ?> */ ?>

<?php /* if ( has_nav_menu( 'services' ) && is_page('services') ) : 
<div class="container">
    <nav id="service-sub-navigation" class="sub-navigation" style="padding-bottom:0px; margin-bottom:0px;" role="navigation" aria-label="<?php esc_attr_e( 'Services Sub Menu', 'baseline-custom' ); ?>">
		<?php
		wp_nav_menu( array(
		'theme_location' => 'services',
		'menu_class'     => 'services',
		) );
		?>
	</nav><!-- .services-navigation -->
</div>
<?php endif; */ 
	$post_slug = $post->post_name;
	$category = strtoupper(str_replace("-compatible","",$post_slug));
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<div class="container">
			<?php while ( have_posts() ) : the_post();
			
		/*		do_action( 'storefront_page_before' );*/

				get_template_part( 'content', 'page' );
				?>
			<h2 class="dynamic-category"><? echo $category?> Compatible QRH / Pilot Checklist Binders</h2>
			<div><div class="categorypage"><?php echo do_shortcode('[products category="ring-binders" columns="3" attribute="aircraftoem" terms="'.$category.'"]');?></div></div>
			<h2 class="dynamic-category"><? echo $category?> Compatible Checklist Covers</h2>
			<div><div class="categorypage"><?php echo do_shortcode('[products category="checklist-covers" columns="3" attribute="aircraftoem" terms="'.$category.'"]');?></div></div>
			<h2 class="dynamic-category"><? echo $category?> Compatible Index Divider Tabs</h2>
			<div><div class="categorypage"><?php echo do_shortcode('[products category="divider-tabs" columns="3" attribute="aircraftoem" terms="'.$category.'"]');?></div></div>
			<h2 class="dynamic-category"><? echo $category?> Compatible Printing Supplies</h2>
			<div><div class="categorypage"><?php echo do_shortcode('[products category="paper-punches" columns="3" attribute="aircraftoem" terms="'.$category.'"]');echo do_shortcode('[products category="paper-stock"]');?></div></div>
				<?
/*				do_action( 'woocommerce_before_shop_loop' );
				 while ( have_posts() ) : the_post(); ?>

					<?php wc_get_template_part( 'content', 'product' ); ?>

				<?php endwhile; // end of the loop. ?>
				<?php woocommerce_product_loop_end(); ?>
				<?php do_action( 'woocommerce_after_shop_loop' );
				/**
				 * Functions hooked in to storefront_page_after action
				 *
				 * @hooked storefront_display_comments - 10
				 
				do_action( 'storefront_page_after' );
*/
			endwhile; // End of the loop. ?>
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
do_action( 'storefront_sidebar' );
get_footer();
