<?php

/**

 * @package WordPress

 * @subpackage Baseline Custom

 */

 

 if( function_exists('acf_add_options_page') ) {

	

	acf_add_options_page(array(

		'page_title' 	=> 'Theme General Settings',

		'menu_title'	=> 'Theme Settings',

		'menu_slug' 	=> 'theme-general-settings',

		'capability'	=> 'edit_posts',

		'redirect'		=> false

	));

	

	acf_add_options_sub_page(array(

		'page_title' 	=> 'Theme Header Settings',

		'menu_title'	=> 'Header',

		'parent_slug'	=> 'theme-general-settings',

	));

	

	acf_add_options_sub_page(array(

		'page_title' 	=> 'Theme Footer Settings',

		'menu_title'	=> 'Footer',

		'parent_slug'	=> 'theme-general-settings',

	));

	

}



/**

 * Twenty Sixteen only works in WordPress 4.4 or later.

 */

if ( version_compare( $GLOBALS['wp_version'], '4.4-alpha', '<' ) ) {

	require get_template_directory() . '/inc/back-compat.php';

}



if ( ! function_exists( 'twentysixteen_setup' ) ) :

/**

 * Sets up theme defaults and registers support for various WordPress features.

 *

 * Note that this function is hooked into the after_setup_theme hook, which

 * runs before the init hook. The init hook is too late for some features, such

 * as indicating support for post thumbnails.

 *

 * Create your own twentysixteen_setup() function to override in a child theme.

 *

 * @since Twenty Sixteen 1.0

 */

function twentysixteen_setup() {

	/*

	 * Make theme available for translation.

	 * Translations can be filed in the /languages/ directory.

	 * If you're building a theme based on Twenty Sixteen, use a find and replace

	 * to change 'twentysixteen' to the name of your theme in all the template files

	 */

	load_theme_textdomain( 'twentysixteen', get_template_directory() . '/languages' );



	// Add default posts and comments RSS feed links to head.

	add_theme_support( 'automatic-feed-links' );



	/*

	 * Let WordPress manage the document title.

	 * By adding theme support, we declare that this theme does not use a

	 * hard-coded <title> tag in the document head, and expect WordPress to

	 * provide it for us.

	 */

	add_theme_support( 'title-tag' );



	/*

	 * Enable support for Post Thumbnails on posts and pages.

	 *

	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails

	 */

	add_theme_support( 'post-thumbnails' );

	set_post_thumbnail_size( 1200, 9999 );



	// This theme uses wp_nav_menu() in two locations.

	//register_nav_menus( array(

		//'primary' => __( 'Primary Menu', 'twentysixteen' ),

		//'social'  => __( 'Social Links Menu', 'twentysixteen' ),

	//) );

	register_nav_menus( array(

		'main' => __( 'Main Menu', 'baseline-custom' ),

		'about' => __( 'About Sub Menu', 'baseline-custom' ),

		'services'  => __( 'Services Sub Menu', 'baseline-custom' ),

		'footer 1'  => __( 'Footer Menu 1', 'baseline-custom' ),

		'footer 2'  => __( 'Footer Menu 2', 'baseline-custom' ),

	) );



	/*

	 * Switch default core markup for search form, comment form, and comments

	 * to output valid HTML5.

	 */

	add_theme_support( 'html5', array(

		'search-form',

		'comment-form',

		'comment-list',

		'gallery',

		'caption',

	) );



	/*

	 * Enable support for Post Formats.

	 *

	 * See: https://codex.wordpress.org/Post_Formats

	 */

	add_theme_support( 'post-formats', array(

		'aside',

		'image',

		'video',

		'quote',

		'link',

		'gallery',

		'status',

		'audio',

		'chat',

	) );

	

	



	/*

	 * This theme styles the visual editor to resemble the theme style,

	 * specifically font, colors, icons, and column width.

	 */

	add_editor_style( array( 'css/editor-style.css', twentysixteen_fonts_url() ) );

}

endif; // twentysixteen_setup

add_action( 'after_setup_theme', 'twentysixteen_setup' );



/**

 * Sets the content width in pixels, based on the theme's design and stylesheet.

 *

 * Priority 0 to make it available to lower priority callbacks.

 *

 * @global int $content_width

 *

 * @since Twenty Sixteen 1.0

 */

function twentysixteen_content_width() {

	$GLOBALS['content_width'] = apply_filters( 'twentysixteen_content_width', 840 );

}

add_action( 'after_setup_theme', 'twentysixteen_content_width', 0 );



/**

 * Registers a widget area.

 *

 * @link https://developer.wordpress.org/reference/functions/register_sidebar/

 *

 * @since Twenty Sixteen 1.0

 */

function twentysixteen_widgets_init() {

	register_sidebar( array(

		'name'          => __( 'Blog Sidebar', 'twentysixteen' ),

		'id'            => 'sidebar-1',

		'description'   => __( 'Add widgets here to appear in your sidebar.', 'twentysixteen' ),

		'before_widget' => '<section id="%1$s" class="widget %2$s">',

		'after_widget'  => '</section>',

		'before_title'  => '<h4 class="widget-title">',

		'after_title'   => '</h4>',

	) );

	register_sidebar( array(

		'name'          => __( 'Filter', 'twentysixteen' ),

		'id'            => 'Filter',

		'description'   => __( 'Add widgets here to appear in your sidebar.', 'twentysixteen' ),

		'before_widget' => '<section id="%1$s" class="widget %2$s">',

		'after_widget'  => '</section>',

		'before_title'  => '<h4 class="widget-title">',

		'after_title'   => '</h4>',

	) );

	register_sidebar(array(

		'name'=> 'Mailing List Form',

		'id' => 'mailing-list-form',

		'before_widget' => '<div id="%1$s" class="widget %2$s">',

		'after_widget' => '</div>',

		'before_title' => '',

		'after_title' => '',

	));

	register_sidebar(array(

		'name'=> 'Address and Phone',

		'id' => 'address-and-phone',

		'before_widget' => '<div id="%1$s" class="widget %2$s">',

		'after_widget' => '</div>',

		'before_title' => '',

		'after_title' => '',

	));

	register_sidebar(array(

		'name'=> 'Hours of Operation',

		'id' => 'hours-of-operation',

		'before_widget' => '<div id="%1$s" class="widget %2$s">',

		'after_widget' => '</div>',

		'before_title' => '',

		'after_title' => '',

	));

	register_sidebar(array(

		'name'=> 'Product Search',

		'id' => 'search',

		'before_widget' => '<div id="%1$s" class="widget %2$s">',

		'after_widget' => '</div>',

		'before_title' => '',

		'after_title' => '',

	));

}

add_action( 'widgets_init', 'twentysixteen_widgets_init' );



function SearchFilter($query) {

if ($query->is_search) {

$query->set('post_type', 'post');

}

return $query;

}

add_filter('pre_get_posts','SearchFilter');



if ( ! function_exists( 'twentysixteen_fonts_url' ) ) :

/**

 * Register Google fonts for Twenty Sixteen.

 *

 * Create your own twentysixteen_fonts_url() function to override in a child theme.

 *

 * @since Twenty Sixteen 1.0

 *

 * @return string Google fonts URL for the theme.

 */

function twentysixteen_fonts_url() {

	$fonts_url = '';

	$fonts     = array();

	$subsets   = 'latin,latin-ext';



	/* translators: If there are characters in your language that are not supported by Merriweather, translate this to 'off'. Do not translate into your own language. */

	if ( 'off' !== _x( 'on', 'Merriweather font: on or off', 'twentysixteen' ) ) {

		$fonts[] = 'Merriweather:400,700,900,400italic,700italic,900italic';

	}



	/* translators: If there are characters in your language that are not supported by Montserrat, translate this to 'off'. Do not translate into your own language. */

	if ( 'off' !== _x( 'on', 'Montserrat font: on or off', 'twentysixteen' ) ) {

		$fonts[] = 'Montserrat:400,700';

	}



	/* translators: If there are characters in your language that are not supported by Inconsolata, translate this to 'off'. Do not translate into your own language. */

	if ( 'off' !== _x( 'on', 'Inconsolata font: on or off', 'twentysixteen' ) ) {

		$fonts[] = 'Inconsolata:400';

	}



	if ( $fonts ) {

		$fonts_url = add_query_arg( array(

			'family' => urlencode( implode( '|', $fonts ) ),

			'subset' => urlencode( $subsets ),

		), 'https://fonts.googleapis.com/css' );

	}



	return $fonts_url;

}

endif;



/**

 * Handles JavaScript detection.

 *

 * Adds a `js` class to the root `<html>` element when JavaScript is detected.

 *

 * @since Twenty Sixteen 1.0

 */

function twentysixteen_javascript_detection() {

	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";

}

add_action( 'wp_head', 'twentysixteen_javascript_detection', 0 );



/**

 * Enqueues scripts and styles.

 *

 * @since Twenty Sixteen 1.0

 */

function twentysixteen_scripts() {

	// Add custom fonts, used in the main stylesheet.

	wp_enqueue_style( 'twentysixteen-fonts', twentysixteen_fonts_url(), array(), null );



	// Add Genericons, used in the main stylesheet.

	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.4.1' );



	// Theme stylesheet.

	wp_enqueue_style( 'twentysixteen-style', get_stylesheet_uri() );



	// Load the Internet Explorer specific stylesheet.

	wp_enqueue_style( 'twentysixteen-ie', get_template_directory_uri() . '/css/ie.css', array( 'twentysixteen-style' ), '20150930' );

	wp_style_add_data( 'twentysixteen-ie', 'conditional', 'lt IE 10' );



	// Load the Internet Explorer 8 specific stylesheet.

	wp_enqueue_style( 'twentysixteen-ie8', get_template_directory_uri() . '/css/ie8.css', array( 'twentysixteen-style' ), '20151230' );

	wp_style_add_data( 'twentysixteen-ie8', 'conditional', 'lt IE 9' );



	// Load the Internet Explorer 7 specific stylesheet.

	wp_enqueue_style( 'twentysixteen-ie7', get_template_directory_uri() . '/css/ie7.css', array( 'twentysixteen-style' ), '20150930' );

	wp_style_add_data( 'twentysixteen-ie7', 'conditional', 'lt IE 8' );



	// Load the html5 shiv.

	wp_enqueue_script( 'twentysixteen-html5', get_template_directory_uri() . '/js/html5.js', array(), '3.7.3' );

	wp_script_add_data( 'twentysixteen-html5', 'conditional', 'lt IE 9' );



	wp_enqueue_script( 'twentysixteen-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151112', true );



	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {

		wp_enqueue_script( 'comment-reply' );

	}



	if ( is_singular() && wp_attachment_is_image() ) {

		wp_enqueue_script( 'twentysixteen-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20151104' );

	}



	wp_enqueue_script( 'twentysixteen-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20151204', true );



	wp_localize_script( 'twentysixteen-script', 'screenReaderText', array(

		'expand'   => __( 'expand child menu', 'twentysixteen' ),

		'collapse' => __( 'collapse child menu', 'twentysixteen' ),

	) );

}

add_action( 'wp_enqueue_scripts', 'twentysixteen_scripts' );



/**

 * Adds custom classes to the array of body classes.

 *

 * @since Twenty Sixteen 1.0

 *

 * @param array $classes Classes for the body element.

 * @return array (Maybe) filtered body classes.

 */

function twentysixteen_body_classes( $classes ) {

	// Adds a class of custom-background-image to sites with a custom background image.

	if ( get_background_image() ) {

		$classes[] = 'custom-background-image';

	}



	// Adds a class of group-blog to sites with more than 1 published author.

	if ( is_multi_author() ) {

		$classes[] = 'group-blog';

	}



	// Adds a class of no-sidebar to sites without active sidebar.

	if ( ! is_active_sidebar( 'sidebar-1' ) ) {

		$classes[] = 'no-sidebar';

	}



	// Adds a class of hfeed to non-singular pages.

	if ( ! is_singular() ) {

		$classes[] = 'hfeed';

	}



	return $classes;

}

add_filter( 'body_class', 'twentysixteen_body_classes' );



/**

 * Converts a HEX value to RGB.

 *

 * @since Twenty Sixteen 1.0

 *

 * @param string $color The original color, in 3- or 6-digit hexadecimal form.

 * @return array Array containing RGB (red, green, and blue) values for the given

 *               HEX code, empty array otherwise.

 */

function twentysixteen_hex2rgb( $color ) {

	$color = trim( $color, '#' );



	if ( strlen( $color ) === 3 ) {

		$r = hexdec( substr( $color, 0, 1 ).substr( $color, 0, 1 ) );

		$g = hexdec( substr( $color, 1, 1 ).substr( $color, 1, 1 ) );

		$b = hexdec( substr( $color, 2, 1 ).substr( $color, 2, 1 ) );

	} else if ( strlen( $color ) === 6 ) {

		$r = hexdec( substr( $color, 0, 2 ) );

		$g = hexdec( substr( $color, 2, 2 ) );

		$b = hexdec( substr( $color, 4, 2 ) );

	} else {

		return array();

	}



	return array( 'red' => $r, 'green' => $g, 'blue' => $b );

}



/**

 * Custom template tags for this theme.

 */

require get_template_directory() . '/inc/template-tags.php';



/**

 * Customizer additions.

 */

require get_template_directory() . '/inc/customizer.php';



/**

 * Add custom image sizes attribute to enhance responsive image functionality

 * for content images

 *

 * @since Twenty Sixteen 1.0

 *

 * @param string $sizes A source size value for use in a 'sizes' attribute.

 * @param array  $size  Image size. Accepts an array of width and height

 *                      values in pixels (in that order).

 * @return string A source size value for use in a content image 'sizes' attribute.

 */

function twentysixteen_content_image_sizes_attr( $sizes, $size ) {

	$width = $size[0];



	840 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 62vw, 840px';



	if ( 'page' === get_post_type() ) {

		840 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';

	} else {

		840 > $width && 600 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 61vw, (max-width: 1362px) 45vw, 600px';

		600 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';

	}



	return $sizes;

}

add_filter( 'wp_calculate_image_sizes', 'twentysixteen_content_image_sizes_attr', 10 , 2 );



/**

 * Add custom image sizes attribute to enhance responsive image functionality

 * for post thumbnails

 *

 * @since Twenty Sixteen 1.0

 *

 * @param array $attr Attributes for the image markup.

 * @param int   $attachment Image attachment ID.

 * @param array $size Registered image size or flat array of height and width dimensions.

 * @return string A source size value for use in a post thumbnail 'sizes' attribute.

 */

function twentysixteen_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {

	if ( 'post-thumbnail' === $size ) {

		is_active_sidebar( 'sidebar-1' ) && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 60vw, (max-width: 1362px) 62vw, 840px';

		! is_active_sidebar( 'sidebar-1' ) && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 88vw, 1200px';

	}

	return $attr;

}

add_filter( 'wp_get_attachment_image_attributes', 'twentysixteen_post_thumbnail_sizes_attr', 10 , 3 );



/**

 * Modifies tag cloud widget arguments to have all tags in the widget same font size.

 *

 * @since Twenty Sixteen 1.1

 *

 * @param array $args Arguments for tag cloud widget.

 * @return array A new modified arguments.

 */

function twentysixteen_widget_tag_cloud_args( $args ) {

	$args['largest'] = 1;

	$args['smallest'] = 1;

	$args['unit'] = 'em';

	return $args;

}



//add_action('woocommerce_before_main_content', 'my_theme_wrapper_start', 10);

//add_action('woocommerce_after_main_content', 'my_theme_wrapper_end', 10);



//function my_theme_wrapper_start() {

  //echo '<section id="main">';

//}



//function my_theme_wrapper_end() {

  //echo '</section>';

//}



remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);

remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action( 'after_setup_theme', 'woocommerce_support' );

function woocommerce_support() {

    add_theme_support( 'woocommerce' );

}

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );



add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 10 );

add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 20 );



remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 10 );



// Change number or products per row to 3

add_filter('loop_shop_columns', 'loop_columns');

if (!function_exists('loop_columns')) {

	function loop_columns() {

		return 3; // 3 products per row

	}

}

//add_action( 'wp_enqueue_scripts', 'load_theme_scripts' );

//function load_theme_scripts() {

    //global $wp_scripts; 

    //$wp_scripts->registered[ 'wc-single-product' ]->src = get_template_directory_uri() . '/woocommerce/assets/js/frontend/single-product.min.js';

    //}

// Custom code

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);

remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);