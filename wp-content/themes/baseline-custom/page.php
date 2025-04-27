<?php
/**
 * The template for displaying pages
 * @package WordPress
 * @subpackage Baseline Custom
 */

get_header(); ?>

<?php if ( has_nav_menu( 'about' ) && is_page('about-aerobind') ) : ?>
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
<?php endif; ?>

<?php if ( has_nav_menu( 'services' ) && is_page('services') ) : ?>
<div class="container1">
    <nav id="sub-navigation" class="sub-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Services Sub Menu', 'baseline-custom' ); ?>">
		<?php
		wp_nav_menu( array(
		'theme_location' => 'services',
		'menu_class'     => 'services',
		) );
		?>
	</nav><!-- .services-navigation -->
</div>
<?php endif; ?>

<?php if (is_front_page() || is_page('about-aerobind') || is_page('services')) {?>
<div id="content">
	<?php the_content(); ?>
</div><!-- .content -->
<?php } else { ?>
<div id="content">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
        	<?php the_content(); ?>
        	<?php } ?>
    		</div>
		</div>
	</div><!-- .container -->
</div><!-- .content -->

<?php get_footer(); ?>