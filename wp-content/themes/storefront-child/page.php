<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
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

<?php if ( has_nav_menu( 'services' ) && is_page('services') ) : ?>
<div class="container">
    <nav id="service-sub-navigation" class="sub-navigation php-sub-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Services Sub Menu', 'baseline-custom' ); ?>">
		<?php
		wp_nav_menu( array(
		'theme_location' => 'services',
		'menu_class'     => 'services',
		) );
		?>
	</nav><!-- .services-navigation -->
</div>
<?php endif; ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post();

				do_action( 'storefront_page_before' );

				get_template_part( 'content', 'page' );

				/**
				 * Functions hooked in to storefront_page_after action
				 *
				 * @hooked storefront_display_comments - 10
				 */
				do_action( 'storefront_page_after' );

			endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
do_action( 'storefront_sidebar' );
get_footer();
