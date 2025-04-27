<?php
/* test norway billing 
add_filter( 'woocommerce_default_address_fields', 'custom_override_default_address_fields', 10, 1 );
function custom_override_default_address_fields($fields){
    global $woocommerce;
    $country = $woocommerce->customer->get_country();
    if($country !== 'US'){
        $fields['billing']['state']['required'] = false;
        $fields['shipping']['state']['required'] = false;
    }
    return $fields;
}*/
/*
add_filter( 'woocommerce_billing_fields', 'woo_filter_state_billing', 10, 1 );
add_filter( 'woocommerce_shipping_fields', 'woo_filter_state_shipping', 10, 1 );
function woo_filter_state_billing( $address_fields ) { 
  $address_fields['billing_state']['required'] = false;
	return $address_fields;
}
function woo_filter_state_shipping( $address_fields ) { 
	$address_fields['shipping_state']['required'] = false;
	return $address_fields;
}*/
/**
* @snippet Disable Postcode/ZIP Validation @ WooCommerce Checkout
* @how-to Watch tutorial @ https://businessbloomer.com/?p=19055
* @sourcecode https://businessbloomer.com/?p=20203
* @author Rodolfo Melogli
* @testedwith WooCommerce 3.3.3

 
add_filter( 'woocommerce_default_address_fields' , 'bbloomer_override_postcode_validation' );
 
function bbloomer_override_postcode_validation( $address_fields ) {
  global $woocommerce;
  $country = $woocommerce->customer->get_country();
if($country !== 'US') {
  $address_fields['postcode']['required'] = false;
  return $address_fields;
}
}*/
/* Hack descriptions tab
add_action('init','product_tab_function');
function product_tab_function (){
add_filter( 'woocommerce_product_tabs', function($tabs) {

	if(isset($tabs['description']))
		$tabs['description']['title'] = 'Specifications';

	return $tabs;
} );
}*/
/**
* filter translations, to replace some WooCommerce text with our own
* @param string $translation the translated text
* @param string $text the text before translation
* @param string $domain the gettext domain for translation
* @return string
*/
function wpse_77783_woo_bacs_ibn($translation, $text, $domain) {
    if ($domain == 'woocommerce') {
        switch ($text) {
            case 'BIC':
                $translation = 'BIC/SWIFT';
                break;
        }
    }

    return $translation;
}

add_filter('gettext', 'wpse_77783_woo_bacs_ibn', 10, 3);
//* TN - Remove Query String from Static Resources
function remove_css_js_ver( $src ) {
if( strpos( $src, '?ver=' ) )
$src = remove_query_arg( 'ver', $src );
return $src;
}
add_filter( 'style_loader_src', 'remove_css_js_ver', 10, 2 );
add_filter( 'script_loader_src', 'remove_css_js_ver', 10, 2 ); 

//* TN - Remove Query String from Static Resources
function remove_css_js_ver2( $src2 ) {
if( strpos( $src, '?v=' ) )
$src2 = remove_query_arg( 'v', $src2 );
return $src2;
}
add_filter( 'style_loader_src', 'remove_css_js_ver2', 10, 3 );
add_filter( 'script_loader_src', 'remove_css_js_ver2', 10, 3 ); 


function disable_wp_emojicons() {

  // all actions related to emojis
  remove_action( 'admin_print_styles', 'print_emoji_styles' );
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
  remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
  remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

  // filter to remove TinyMCE emojis
  add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
}
add_action( 'init', 'disable_wp_emojicons' );
function disable_emojicons_tinymce( $plugins ) {
  if ( is_array( $plugins ) ) {
    return array_diff( $plugins, array( 'wpemoji' ) );
  } else {
    return array();
  }
}
add_filter( 'searchwp_indexer_pre_process_content', 'my_searchwp_indexer_pre_process_content' );
// index WooCommerce product_variation SKUs with the parent post
function my_searchwp_index_woocommerce_variation_skus( $extra_meta, $post_being_indexed ) {
	// we only care about WooCommerce Products
	if ( 'product' !== get_post_type( $post_being_indexed ) ) {
		return $extra_meta;
	}
	// retrieve all the product variations
	$args = array(
		'post_type'       => 'product_variation',
		'posts_per_page'  => -1,
		'fields'          => 'ids',
		'post_parent'     => $post_being_indexed->ID,
	);
	$product_variations = get_posts( $args );
	if ( ! empty( $product_variations ) ) {
		// store all SKUs as a Custom Field with a key of 'my_product_variation_skus'
		$extra_meta['my_product_variation_skus'] = array();
		// loop through all product variations, grab and store the SKU
		foreach ( $product_variations as $product_variation ) {
			$extra_meta['my_product_variation_skus'][] = get_post_meta( absint( $product_variation ), '_sku', true );
		}
	}
	return $extra_meta;
}
add_filter( 'searchwp_extra_metadata', 'my_searchwp_index_woocommerce_variation_skus', 10, 2 );

/*eDIT MOVE*/

//fix cart details
add_filter( 'woocommerce_product_variation_title_include_attributes', '__return_false' );

function my_theme_enqueue_styles() {

    $parent_style = 'parent-style';

   // wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( $parent_style ), '1.0.0', true);
}
// reference: https://gist.github.com/jchristopher/0cad8418fd9477c57b53
function my_searchwp_custom_field_keys_variation_skus( $keys ) {
  $keys[] = 'my_product_variation_skus';
  
  return $keys;
}
 
add_filter( 'searchwp_custom_field_keys', 'my_searchwp_custom_field_keys_variation_skus', 10, 1 );
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

// Jetpack infinite scroll does it's own WP_Query, so we need to hijack it at runtime
function searchwp_infinite_scroll_query_args( $query_args ) {
	if( class_exists( 'SearchWPSearch' ) ) {
		$query = get_search_query();
		if( empty( $query ) ) {
			if( isset( $_GET['query_args']['s'] ) ) {
				$query = sanitize_text_field( $_GET['query_args']['s'] );
			}
		}
		if( !empty( $query ) ) {
			// piggyback the submitted query args
			$searchwp_args = $query_args;
			// customize for the SearchWP search class
			$searchwp_args['page']        = $query_args['paged'];
			$searchwp_args['terms']       = $query;
			$searchwp_args['engine']      = 'default'; // search engine name
			$searchwp_args['load_posts']  = false; // only want post IDs because we're going to send those back
			$searchwp_args['order']       = 'DESC';
			// fire the search
			$searchwp = new SearchWPSearch( $query_args );
			$hijacked_IDs = $searchwp->postIDs;
			// overwrite the original query vars to be what we want as per SearchWP
			$query_args['post__in'] = $hijacked_IDs;
			$query_args['orderby'] = 'post__in';
		}
	}
	return $query_args;
}
add_filter( 'infinite_scroll_query_args', 'searchwp_infinite_scroll_query_args' );
// also ensure the titles are highlighted as well
function my_searchwp_highlight_title( $title, $id ) {
	if( class_exists( 'SearchWP_Term_Highlight' ) ) {
		$query = get_search_query();
		if( empty( $query ) ) {
			if( isset( $_GET['query_args']['s'] ) ) {
				// Jetpack
				$query = sanitize_text_field( $_GET['query_args']['s'] );
			}
		}
		if( !empty( $query ) ) {
			$highlighter = new SearchWP_Term_Highlight();
			$title = $highlighter->apply_highlight( $title, $query );
			unset( $highlighter );
		}
	}
	return $title;
}
add_filter( 'the_title', 'my_searchwp_highlight_title', 10, 2 );
function my_searchwp_indexer_pre_process_content( $content ) {
	// TODO: manipulate $content in any way you want
	return str_replace( '–', '-', $content );	
	return $content;
}
//add_filter( 'searchwp_and_logic_only', '__return_true' );
//ADD VARIATION SKU TO CART DETAILS

add_filter( 'woocommerce_cart_item_name', 'showing_sku_in_cart_items', 99, 3 );
function showing_sku_in_cart_items( $item_name, $cart_item, $cart_item_key  ) {
    // The WC_Product object
    $product = $cart_item['data'];
    // Get the  SKU
    $sku = $product->get_sku();

    // When sku doesn't exist
    if(empty($sku)) return $item_name;

    // Add the sku
    $item_name .= '<br><small class="product-sku">' . __( "SKU: ", "woocommerce") . $sku . '</small>';

    return $item_name;
}

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
	
register_nav_menus( array(

		'main' => __( 'Main Menu', 'baseline-custom' ),

		'about' => __( 'About Sub Menu', 'baseline-custom' ),

		'services'  => __( 'Services Sub Menu', 'baseline-custom' ),

		'footer 1'  => __( 'Footer Menu 1', 'baseline-custom' ),

		'footer 2'  => __( 'Footer Menu 2', 'baseline-custom' ),

	) );
function wp_custom_new_menu() {
	register_nav_menu( 'services',__( 'Services Sub Menu' ) );
}
add_action( 'init', 'wp_custom_new_menu' );
//Code for Diameter variation

	
// Add Variation Settings
add_action( 'woocommerce_product_after_variable_attributes', 'variation_settings_fields', 10, 3 );
// Save Variation Settings
add_action( 'woocommerce_save_product_variation', 'save_variation_settings_fields', 10, 2 );

function variation_settings_fields( $loop, $variation_data, $variation ) {
	
	
	woocommerce_wp_text_input( 
		array( 
			'id'          => '_thickness_field[' . $variation->ID . ']', 
			'label'       => __('Thickness', 'woocommerce' ), 
			'desc_tip'    => 'true',
			'description' => __( 'Enter the product thickness here.', 'woocommerce' ),
			'value'       => get_post_meta( $variation->ID, '_thickness_field', true ),
			'custom_attributes' => array(
							'step' 	=> 'any',
							'min'	=> '0'
						) 
		)
	);
	/*	
	woocommerce_wp_text_input( 
		array( 
			'id'          => '_text_field[' . $variation->ID . ']', 
			'label'       => __('Spec Download Link', 'woocommerce' ), 
			'placeholder' => 'Select A Product Option to Download Specifications',
			'desc_tip'    => 'true',
			'description' => __( 'Paste the link to the specification for the product.', 'woocommerce' ),
			'value'       => get_post_meta( $variation->ID, '_text_field', true ),
		)
	);*/
	

}
function save_variation_settings_fields( $post_id ) {
	
	
	$number_field = $_POST['_thickness_field'][ $post_id ];
	if( ! empty( $number_field ) ) {
		update_post_meta( $post_id, '_thickness_field', esc_attr( $number_field ) );
	}
		
/*	$text_field = $_POST['_text_field'][ $post_id ];
	if( ! empty( $text_field ) ) {
		update_post_meta( $post_id, '_text_field', esc_attr( $text_field ) );
	}*/
	
	
}


// Add New Variation Settings
add_filter( 'woocommerce_available_variation', 'load_variation_settings_fields' );

function load_variation_settings_fields( $variations ) {
	

	$variations['thickness_field'] = get_post_meta( $variations[ 'variation_id' ], '_thickness_field', true );
//	$variations['text_field'] = get_post_meta( $variations[ 'variation_id'], '_text_field', true );
		
	return $variations;
}

/*Code for Diameter variation
	
// Add Variation Settings
add_action( 'woocommerce_product_after_variable_attributes', 'variation_settings_fields', 10, 3 );
// Save Variation Settings
add_action( 'woocommerce_save_product_variation', 'save_variation_settings_fields', 10, 2 );

function variation_settings_fields( $loop, $variation_data, $variation ) {
	
	
	woocommerce_wp_text_input( 
		array( 
			'id'          => '_text_field[' . $variation->ID . ']', 
			'label'       => __('Spec Download Link', 'woocommerce' ), 
			'desc_tip'    => 'true',
			'description' => __( 'Paste the link to the specification for the product.', 'woocommerce' ),
			'value'       => get_post_meta( $variation->ID, '_text_field', true ),
		)
	);
	

}
function save_variation_settings_fields( $post_id ) {
	
	
	$text_field = $_POST['_text_field'][ $post_id ];
	if( ! empty( $text_field ) ) {
		update_post_meta( $post_id, '_text_field', esc_attr( $text_field ) );
	}
	
	
	
}


/* Add New Variation Settings
add_filter( 'woocommerce_available_variation', 'load_variation_settings_fields' );

function load_variation_settings_fields( $variations ) {
	

	$variations['number_field'] = get_post_meta( $variations[ 'variation_id' ], '_number_field', true );
	
		
	return $variations;
}
*/
// End > Code for Diameter variation

// Swap where the price and meta appear on a single product page

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );

add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 20 );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 10 );

// Swap where the meta and post appear on a single blog post

/*remove_action( 'storefront_loop_post', 'storefront_post_meta', 20 );
remove_action( 'storefront_loop_post', 'storefront_post_content', 30 );

add_action( 'storefront_loop_post', 'storefront_post_content', 20 );
add_action( 'storefront_loop_post', 'storefront_post_meta', 30 );*/

// Add topbar 


//change thumbnail size
add_filter( 'woocommerce_get_image_size_gallery_thumbnail', function( $size ) {
    return array(
    'width' => 300,
    'height' => 300,
    'crop' => 0,
    );
} );

/*add VAT field test


function customise_checkout_field($checkout)
{
    global $woocommerce;
    $country = $woocommerce->customer->get_country();
    if($country == 'BR'){
        $label = 'CPF# / CNPJ #';
        $required = 'true';
        $placeholder = 'Enter Your CPF# or CNPJ# to Complete Order';
        }
    else {
        $label = 'VAT / Tax ID (If Applicable)';
        $required = 'false';
        $placeholder = 'VAT Number or N/A';
    }
	echo '<div id="customise_checkout_field">';
	woocommerce_form_field('customised_field_name', array(
		'type' => 'text',
		'class' => array(
			'my-field-class form-row-wide'
		) ,
        /*'label' => __('VAT / Tax ID (If Applicable)') ,
		'placeholder' => __('VAT Number or N/A') ,
		'required' => false,
		'label' => $label ,
		'placeholder' => $placeholder ,
		'required' => $required,
	) , $checkout->get_value('customised_field_name'));

	echo '</div>';
}
add_action('woocommerce_after_checkout_billing_form', 'customise_checkout_field',10);
add_action('woocommerce_checkout_process', 'customise_checkout_field_process');
 
//function customise_checkout_field_process()
//{
	// if the field is set, if not then show an error message.
////	if (!$_POST['customised_field_name']) wc_add_notice(__('Please enter value.') , 'error');
//}

//add_action('woocommerce_checkout_update_order_meta', 'customise_checkout_field_update_order_meta');
 
function customise_checkout_field_update_order_meta($order_id) {
	if (!empty($_POST['customised_field_name'])) {
		update_post_meta($order_id, 'customised_field_name', sanitize_text_field($_POST['customised_field_name']));
	}
}
add_action('woocommerce_admin_order_data_after_billing_address','customise_checkout_field_display_admin_order_meta',10,1);
function customise_checkout_field_display_admin_order_meta($order){
	echo '<p><strong>'.__('VAT / Tax ID#').':</strong><br/>'. get_post_meta($order->get_id(),'customised_field_name',true).'</p>';
}
/*Edit uncomment 
add_action('woocommerce_checkout_update_order_meta', 'customise_checkout_field_update_order_meta');
 
function customise_checkout_field_update_order_meta($order_id)
{
	if (!empty($_POST['VAT_field_name'])) {
		update_post_meta($order_id, 'VAT_field_name', sanitize_text_field($_POST['VAT_field_name']));
	}
}*/
//add VAT field test

function storefront_add_topbar() {
	remove_action('storefront_header','storefront_skip_links',0);
	remove_action('storefront_header','storefront_social_icons',10);
	remove_action('storefront_header','storefront_site_branding',20);
	remove_action('storefront_header','storefront_secondary_navigation',30);
	remove_action('storefront_header','storefront_product_search',40);
	remove_action('storefront_header','storefront_primary_navigation_wrapper',42);
	remove_action('storefront_header','storefront_primary_navigation',50);
	remove_action('storefront_header','storefront_primary_navigation_wrapper_close',68);
	$username = DB_USER;//"aerobind";
	$password = DB_PASSWORD ;//"DSDlIQeyQ94VCdCsJo73";
	$hostname = DB_HOST;//"127.0.0.1";
	$dbhandle = mysqli_connect($hostname, $username, $password);
	$selected = mysqli_select_db($dbhandle,DB_NAME);	
	
if (!$selected) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
	
global $product;

if(stripos($_SERVER["REQUEST_URI"], '/product/') === false) 
{ 
	$is_product = 'no'; 
	//$url = str_replace('/', '/', $_SERVER["REQUEST_URI"]); 
	$url = $_SERVER["REQUEST_URI"];
}
else 
{ 
	$siteurl = 'https://aerobind.com';
	$is_product = 'yes'; 
	$url = $product->get_categories();
//	$url = wc_get_product_category();
	$url = str_replace('<a href=', '', $url);
	$url = str_replace('</a>', '', $url);
	$url = str_replace(' rel="tag">', '', $url);
	$url = str_replace(', ', '', $url);
	$url = str_replace($siteurl, '', $url);
	
	$temp = explode('"', $url);
	
	for($x=0; $x<sizeof($temp); $x++)
	{
	  if( (substr_count($temp[$x], '/') == 4) && (substr_count($temp[$x], 'brand') == 0) && (substr_count($temp[$x], 'aircraftoem') == 0) && (substr_count($temp[$x], 'products') == 0) )
	  {
	    $url = $temp[$x];
	  }
	}
}

$url_parts = explode("&", $url);
$url_parts = explode("/", $url_parts[0]);



//echo('<br><br><br><pre>'); 
  //print_r($product->get_categories());
  //print_r($url_parts); 
//echo('</pre>');

//echo '<br><br>previous url: ';

  function show_hide_content($term_id)
{
	$username = DB_USER;//"aerobind";
	$password = DB_PASSWORD ;//"DSDlIQeyQ94VCdCsJo73";
	$hostname = DB_HOST;//"127.0.0.1";
	$dbhandle = mysqli_connect($hostname, $username, $password);
	$selected = mysqli_select_db($dbhandle,DB_NAME);	
	$show_hide = 'ShowContent(\'child_row_' . $term_id . '\');';
	 
	$sql = "SELECT wp_terms.term_id AS term_id FROM wp_terms LEFT JOIN wp_term_taxonomy ON wp_terms.term_id = wp_term_taxonomy.term_id
	WHERE wp_term_taxonomy.taxonomy = 'product_cat' AND wp_term_taxonomy.parent=0 AND wp_terms.term_id !=183 AND wp_terms.term_id !=154 
	AND wp_terms.term_id !=153 AND wp_terms.term_id !=152 AND wp_terms.term_id !=151 AND wp_terms.term_id !=150 AND wp_terms.term_id !=113 
	AND wp_terms.term_id !=114 AND wp_terms.term_id !=116 AND wp_terms.term_id !=115 
	ORDER BY `wp_term_taxonomy`.`parent`  DESC";

	$result = mysqli_query($dbhandle, $sql);
	
	$count = mysqli_num_rows($result);
	  if (!$selected) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
	if($count > 0)
	{
	  while($data = mysqli_fetch_array($result))
	  { 
	    if($data['term_id'] != $term_id) $show_hide .= "HideContent('child_row_" . $data['term_id'] . "');";
	  }
	}
	return $show_hide; 
  } 
?>

<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" />

          
<nav class="navbar navbar-default navbar navbar-fixed-top">

<div id="mobile-header">
<h4 id="filter"> FILTER</h4>

    <a id="responsive-menu-button" href="#topbar">
    
    <div id="navbar-toggle" class="hamburger">
  <span class="line"></span>
  <span class="line"></span>
  <span class="line"></span>
</div>
 
</a>

</div>
</nav>-->

<div id="topbar" >
	<div class="col-full">
    	<nav class="navbar-one navbar-default" role="navigation"> 

  			<!-- Collect the nav links, forms, and other content for toggling --> 
  				<div class="collapse navbar-collapse "> 
                
    				<ul class="nav navbar-nav navbar-center"> 
      					<li class="all_parents_row" id="all_parents_row">
  <?php
	$sql = "SELECT wp_terms.*, wp_term_taxonomy.* FROM wp_terms LEFT JOIN wp_term_taxonomy ON wp_terms.term_id = wp_term_taxonomy.term_id
	WHERE wp_term_taxonomy.taxonomy = 'product_cat' AND wp_term_taxonomy.parent=0 AND wp_terms.term_id !=183 AND wp_terms.term_id !=154 
	AND wp_terms.term_id !=153 AND wp_terms.term_id !=152 AND wp_terms.term_id !=151 AND wp_terms.term_id !=150 AND wp_terms.term_id !=113 
	AND wp_terms.term_id !=114 AND wp_terms.term_id !=116 AND wp_terms.term_id !=115 
	ORDER BY `wp_term_taxonomy`.`parent`  DESC";
	$result = mysqli_query($dbhandle,$sql);
	$count = mysqli_num_rows($result);
	if($count > 0)
{
	while($data = mysqli_fetch_array($result))
	{
	  echo('<li class="parent_name ');
	  if($data['slug'] == $url_parts[2]) echo('active');
	  echo('" style="cursor:pointer;" ');
	  //echo('onClick="ShowContent(\'all_children_row\');');
	  echo(' ' . show_hide_content($data['term_id']) . ' "><a href="/product-category/' . $data['slug'] . '">' . $data['name'] . '</a></li>');
	}
}

  ?>
  
					</li> 
				</ul>
               
	    	</div>               
		</nav>
	</div>
</div>


	
    
  <li class="all_children_row" id="all_children_row" <?php if($url_parts[1] != 'product-category') { ?>style="display:none;"<?php } ?>>
  <div id="topbar">
	<div class = "second-level-filter-wrapper" style="max-width:1120px; padding-left:20px; padding-right:20px; margin:0 auto;">
    	<nav class="navbar-two navbar-default" role="navigation"> 

  			<!-- Collect the nav links, forms, and other content for toggling --> 
  				<div class="collapse navbar-collapse "> 
	
<?php
  
  $result = mysqli_query($dbhandle,$sql); 
  $count = mysqli_num_rows($result);
  if($count > 0)
  {
    while($data = mysqli_fetch_array($result))
    {
    	// echo "<pre>"; print_r($data); echo "</pre>";
	  if($data['term_id'] == 112) $filter_method = 'filter_ring-count'; // ring binders
  	  if($data['term_id'] == 120) $filter_method = 'filter_hole-count'; // divider tabs
  	  if($data['term_id'] == 119) $filter_method = 'filter_hole-count'; // sheet protectors
  	  if($data['term_id'] == 118) $filter_method = 'filter_hole-count'; // checklist covers
  	  if($data['term_id'] == 121) $filter_method = 'filter_punch-type'; // paper punches
/*	  if($data['term_id'] == 122) $filter_method = 'filter_hole-count'; // paper stock
	*/  

//  echo"disha"; 
//  echo"<pre>"; print_r($data['slug']);
//  echo"<pre>"; print_r($url_parts[2]);
// die();

	  if($data['slug'] == $url_parts[2]) {
	 

	  echo('<ul class="child_row" id="child_row_' . $data['term_id'] . '" ');
	  if($data['slug'] != $url_parts[2]) echo('style="display:none;"');
	  echo('>' );



	  $sql2 = "SELECT wp_terms.*, wp_term_taxonomy.* FROM wp_terms LEFT JOIN wp_term_taxonomy ON wp_terms.term_id = wp_term_taxonomy.term_id
      WHERE wp_term_taxonomy.taxonomy = 'product_cat' AND wp_term_taxonomy.parent=" . $data['term_id'] . "
      ORDER BY `wp_term_taxonomy`.`description` ASC";
	  $result2 = mysqli_query($dbhandle,$sql2);
	 $count2 = mysqli_num_rows($result2);
	  if($count2 > 0)
	  {
		echo('<div style="width:89%; float:left">');
		echo('<ul class="nav navbar-nav navbar-center" style="margin:0px !important; width:100%;">' );
		
	    while($data2 = mysqli_fetch_array($result2))
	    {
		  	echo('<li class="child_name');
			
			$slug2_parts = explode('-', $data2['slug']);
			$slug2 = '?' . $filter_method . '=' . $slug2_parts[0] . '-' . $slug2_parts[1];
			
			
			
			if(($slug2 == $url_parts[3]) || ($data2['slug'] == $url_parts[3]) )
			{
			  echo(' active');
			  $active_slug = $slug2;
			}
			
			echo('" style="cursor:pointer;" onClick="ShowContent(\'all_children_row\'); ' . show_hide_content($data['term_id']) . ' ">'); // ShowContent(\'attributes\');
			echo('<a href="/product-category/' . $data['slug'] . '/' . $slug2 . '">');
			  echo($data2['name']);
			echo('</a>');
			echo('</li>');
		  
	    }
		echo('</ul>');
		echo('</div>');
		
$url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "/";

$url = '/product-category/' . $data['slug'] . '/';
if(isset($active_slug)) $url .= $active_slug;

//echo 'active: ' . $active_slug;
		
		
		
	

if(stripos($url, '?') !== false) $seperator = '&';
else $seperator = '?';
		
		
		
if(($data['term_id'] == 112)) { ?> 

<!--Ring Binders --> 

<form>
<select onchange="javascript:location.href=this.value;">
  <option value=''>All Products</option>
  <optgroup label="Aircraft/OEM">
    
      <?php
	  $show = 'yes';
	  if( (isset($_GET['filter_ring-count'])) && (($_GET['filter_ring-count'] == '22-ring') || ($_GET['filter_ring-count'] == '9-ring') || ($_GET['filter_ring-count'] == '10-ring') || ($_GET['filter_ring-count'] == '12-ring') || ($_GET['filter_ring-count'] == '13-ring')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=boeing" <?php if($_GET['filter_aircraftoem'] == 'boeing') echo('selected="selected"'); ?>>Boeing</option>
      <?php } ?>
      
      <?php
	  $show = 'yes';
	  if( (isset($_GET['filter_ring-count'])) && (($_GET['filter_ring-count'] == '7-ring') || ($_GET['filter_ring-count'] == '9-ring') || ($_GET['filter_ring-count'] == '10-ring') || ($_GET['filter_ring-count'] == '12-ring') || ($_GET['filter_ring-count'] == '13-ring') || ($_GET['filter_ring-count'] == '1-ring')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=airbus" <?php if($_GET['filter_aircraftoem'] == 'airbus') echo('selected="selected"'); ?>>Airbus</option>
      <?php } ?>
    
      <?php
      $show = 'yes';
	  if( (isset($_GET['filter_ring-count'])) && (($_GET['filter_ring-count'] == '22-ring') || ($_GET['filter_ring-count'] == '9-ring') || ($_GET['filter_ring-count'] == '10-ring') || ($_GET['filter_ring-count'] == '11-ring') || ($_GET['filter_ring-count'] == '12-ring') || ($_GET['filter_ring-count'] == '13-ring') || ($_GET['filter_ring-count'] == '1-ring')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=gulfstream" <?php if($_GET['filter_aircraftoem'] == 'gulfstream') echo('selected="selected"'); ?>>Gulfstream</option>
      <?php } ?>
        
      <?php
      $show = 'yes';
	  if( (isset($_GET['filter_ring-count'])) && (($_GET['filter_ring-count'] == '22-ring') || ($_GET['filter_ring-count'] == '7-ring') || ($_GET['filter_ring-count'] == '9-ring') || ($_GET['filter_ring-count'] == '10-ring') || ($_GET['filter_ring-count'] == '12-ring') || ($_GET['filter_ring-count'] == '13-ring') || ($_GET['filter_ring-count'] == '1-ring')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=embraer" <?php if($_GET['filter_aircraftoem'] == 'embraer') echo('selected="selected"'); ?>>Embraer</option>
      <?php } ?>
    
      <?php
      $show = 'yes';
	  if( (isset($_GET['filter_ring-count'])) && (($_GET['filter_ring-count'] == '22-ring') || ($_GET['filter_ring-count'] == '7-ring') || ($_GET['filter_ring-count'] == '9-ring') || ($_GET['filter_ring-count'] == '10-ring') || ($_GET['filter_ring-count'] == '12-ring') || ($_GET['filter_ring-count'] == '13-ring') || ($_GET['filter_ring-count'] == '1-ring')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=bombardier" <?php if($_GET['filter_aircraftoem'] == 'bombardier') echo('selected="selected"'); ?>>Bombardier</option>
      <?php } ?>
    
  </optgroup>
  <optgroup label="Brand">
    
      <?php
      $show = 'yes';
	  if( (isset($_GET['filter_ring-count'])) && (($_GET['filter_ring-count'] == '7-ring') || ($_GET['filter_ring-count'] == '9-ring') || ($_GET['filter_ring-count'] == '10-ring') || ($_GET['filter_ring-count'] == '11-ring') || ($_GET['filter_ring-count'] == '12-ring') || ($_GET['filter_ring-count'] == '13-ring') || ($_GET['filter_ring-count'] == '22-ring')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_brand=aeroflex" <?php if($_GET['filter_brand'] == 'aeroflex') echo('selected="selected"'); ?>>AEROFLEX</option>
      <?php } ?>
	  
	  <?php
      $show = 'yes';
	  if( (isset($_GET['filter_ring-count'])) && (($_GET['filter_ring-count'] == '7-ring') || ($_GET['filter_ring-count'] == '9-ring') || ($_GET['filter_ring-count'] == '10-ring') || ($_GET['filter_ring-count'] == '11-ring') || ($_GET['filter_ring-count'] == '12-ring') || ($_GET['filter_ring-count'] == '13-ring') || ($_GET['filter_ring-count'] == '1-ring')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_brand=aerometal" <?php if($_GET['filter_brand'] == 'aerometal') echo('selected="selected"'); ?>>AEROMETAL</option>
      <?php } ?>
    
      <?php
      $show = 'yes';
	  if( (isset($_GET['filter_ring-count'])) && (($_GET['filter_ring-count'] == '22-ring') || ($_GET['filter_ring-count'] == '1-ring')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_brand=aerad" <?php if($_GET['filter_brand'] == 'aerad') echo('selected="selected"'); ?>>AERAD</option>
      <?php } ?>
    
      <?php
      $show = 'yes';
	  if( (isset($_GET['filter_ring-count'])) && (($_GET['filter_ring-count'] == '23-ring') || ($_GET['filter_ring-count'] == '1-ring')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_brand=aerobinder-ii" <?php if($_GET['filter_brand'] == 'aerobinder-ii') echo('selected="selected"'); ?>>AEROBINDER II</option>
      <?php } ?>
   
   </optgroup>  
</select>
</form>


<?php } else if($data['term_id'] == 120) { ?>

<!-- Divider Tabs -->

<form>
<select onchange="javascript:location.href=this.value;">
  <option value=''>All Products</option>
  
  <optgroup label="Aircraft/OEM">
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '22-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=boeing" <?php if($_GET['filter_aircraftoem'] == 'boeing') echo('selected="selected"'); ?>>Boeing</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '7-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=airbus" <?php if($_GET['filter_aircraftoem'] == 'airbus') echo('selected="selected"'); ?>>Airbus</option>
    <?php } ?>
      
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '11-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=gulfstream" <?php if($_GET['filter_aircraftoem'] == 'gulfstream') echo('selected="selected"'); ?>>Gulfstream</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=embraer" <?php if($_GET['filter_aircraftoem'] == 'embraer') echo('selected="selected"'); ?>>Embraer</option>
    <?php } ?>
      
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=bombardier" <?php if($_GET['filter_aircraftoem'] == 'bombardier') echo('selected="selected"'); ?>>Bombardier</option>
    <?php } ?>
      
  </optgroup>
  
  <optgroup label="Material">
    <option value="<?php echo $url . $seperator; ?>filter_material=card-stock" <?php if($_GET['filter_material'] == 'card-stock') echo('selected="selected"'); ?>>CARDSTOCK</option>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '7-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_material=synthetic" <?php if($_GET['filter_material'] == 'synthetic') echo('selected="selected"'); ?>>SYNTHETIC (NO TEAR)</option>
    <?php } ?>
      
  </optgroup>  
  
</select>
</form>


<?php } else if($data['term_id'] == 119) { ?>

<!-- Sheet Protectors -->

<form>
<select onchange="javascript:location.href=this.value;">
  <option value=''>All Products</option>
  <optgroup label="Aircraft/OEM">
    <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=boeing" <?php if($_GET['filter_aircraftoem'] == 'boeing') echo('selected="selected"'); ?>>Boeing</option>
    <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=airbus" <?php if($_GET['filter_aircraftoem'] == 'airbus') echo('selected="selected"'); ?>>Airbus</option>
    <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=gulfstream" <?php if($_GET['filter_aircraftoem'] == 'gulfstream') echo('selected="selected"'); ?>>Gulfstream</option>
    <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=embraer" <?php if($_GET['filter_aircraftoem'] == 'embraer') echo('selected="selected"'); ?>>Embraer</option>
    <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=bombardier" <?php if($_GET['filter_aircraftoem'] == 'bombardier') echo('selected="selected"'); ?>>Bombardier</option>
  </optgroup>
</select>
</form>

<?php }  else if($data['term_id'] == 118) { ?>

<!-- Checklist Covers -->

<form>
<select onchange="javascript:location.href=this.value;">
  <option value=''>All Products</option>
  <optgroup label="Aircraft/OEM">
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '12-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=boeing" <?php if($_GET['filter_aircraftoem'] == 'boeing') echo('selected="selected"'); ?>>Boeing</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '12-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=airbus" <?php if($_GET['filter_aircraftoem'] == 'airbus') echo('selected="selected"'); ?>>Airbus</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '11-hole') || ($_GET['filter_hole-count'] == '12-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=gulfstream" <?php if($_GET['filter_aircraftoem'] == 'gulfstream') echo('selected="selected"'); ?>>Gulfstream</option>
    <?php } ?>
      
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '12-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=embraer" <?php if($_GET['filter_aircraftoem'] == 'embraer') echo('selected="selected"'); ?>>Embraer</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '12-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=bombardier" <?php if($_GET['filter_aircraftoem'] == 'bombardier') echo('selected="selected"'); ?>>Bombardier</option>
    <?php } ?>
    
  </optgroup>
   <optgroup label="Cover Type">
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '12-hole') || ($_GET['filter_hole-count'] == '12-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_cover-type=flexible" <?php if($_GET['filter_cover-type'] == 'flexible') echo('selected="selected"'); ?>>FLEXIBLE</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '10-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_cover-type=rigid" <?php if($_GET['filter_cover-type'] == 'rigid') echo('selected="selected"'); ?>>RIGID</option>
    <?php } ?>
    
    <?php /*
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '11-hole') || ($_GET['filter_hole-count'] == '12-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_cover-type=smooth-rigid" <?php if($_GET['filter_cover-type'] == 'smooth-rigid') echo('selected="selected"'); ?>>SMOOTH RIGID</option>
    <?php }*/ ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '12-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_cover-type=plain" <?php if($_GET['filter_cover-type'] == 'plain') echo('selected="selected"'); ?>>NO POCKETS</option>
    <?php } ?>
      
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '12-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_cover-type=pockets" <?php if($_GET['filter_cover-type'] == 'pockets') echo('selected="selected"'); ?>>POCKETS</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '12-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_cover-type=window" <?php if($_GET['filter_cover-type'] == 'window') echo('selected="selected"'); ?>>WINDOW</option>
    <?php } ?>
      
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '12-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_cover-type=window-and-pocket" <?php if($_GET['filter_cover-type'] == 'window-and-pocket') echo('selected="selected"'); ?>>WINDOW AND POCKET</option>
    <?php } ?>
    
  </optgroup> 
  
</select>
</form>


<?php }  else if ($data['term_id'] == 121) { ?>

<!-- Paper Punches -->

<form>
<select onchange="javascript:location.href=this.value;">
  <option value=''>All Products</option>
  
  <optgroup label="Aircraft/OEM">
    <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=boeing" <?php if($_GET['filter_aircraftoem'] == 'boeing') echo('selected="selected"'); ?>>Boeing</option>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-pattern'])) && (($_GET['filter_hole-pattern'] == '7-hole') || ($_GET['filter_hole-pattern'] == '7-hole') || ($_GET['filter_hole-pattern'] == 'removable-die-')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=airbus" <?php if($_GET['filter_aircraftoem'] == 'airbus') echo('selected="selected"'); ?>>Airbus</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-pattern'])) && (($_GET['filter_hole-pattern'] == '11-hole') || ($_GET['filter_hole-pattern'] == '22-hole') || ($_GET['filter_hole-pattern'] == 'removable-die-')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=gulfstream" <?php if($_GET['filter_aircraftoem'] == 'gulfstream') echo('selected="selected"'); ?>>Gulfstream</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-pattern'])) && (($_GET['filter_hole-pattern'] == '7-hole') || ($_GET['filter_hole-pattern'] == '7-hole') || ($_GET['filter_hole-pattern'] == 'removable-die-')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=embraer" <?php if($_GET['filter_aircraftoem'] == 'embraer') echo('selected="selected"'); ?>>Embraer</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-pattern'])) && (($_GET['filter_hole-pattern'] == '7-hole') || ($_GET['filter_hole-pattern'] == '7-hole') || ($_GET['filter_hole-pattern'] == 'removable-die-')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=bombardier" <?php if($_GET['filter_aircraftoem'] == 'bombardier') echo('selected="selected"'); ?>>Bombardier</option>
    <?php } ?>
      
  </optgroup>

 <!--<optgroup label="Punch Type">
    
    <?php
    /*
      $show = 'yes';
	  if( (isset($_GET['filter_hole-pattern'])) && (($_GET['filter_hole-pattern'] == '7-hole') || ($_GET['filter_hole-pattern'] == '11-hole') || ($_GET['filter_hole-pattern'] == '22-hole') || ($_GET['filter_hole-pattern'] == 'removable-die-')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_punch-type=electric" <?php if($_GET['filter_punch-type'] == 'electric') echo('selected="selected"'); ?>>ELECTRIC</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-pattern'])) && (($_GET['filter_hole-pattern'] == '7-hole') || ($_GET['filter_hole-pattern'] == '11-hole') || ($_GET['filter_hole-pattern'] == '22-hole') || ($_GET['filter_hole-pattern'] == 'removable-die-')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_punch-type=light-duty-manual" <?php if($_GET['filter_punch-type'] == 'light-duty-manual') echo('selected="selected"'); ?>>LIGHT DUTY MANUAL</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-pattern'])) && (($_GET['filter_hole-pattern'] == '7-hole') || ($_GET['filter_hole-pattern'] == '7-hole') || ($_GET['filter_hole-pattern'] == 'removable-die-')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_punch-type=heavy-duty-manual" <?php if($_GET['filter_punch-type'] == 'heavy-duty-manual') echo('selected="selected"'); ?>>HEAVY DUTY MANUAL</option>
      <?php }*/ 
      ?>
      
  </optgroup> -->
 
</select>
</form>

<?php }  else if($data['term_id'] == 122) { ?>
  
<!-- Paper Stock -->

<form>
<select onchange="javascript:location.href=this.value;">
  <option value=''>All Products</option>
  <optgroup label="Page Size">                          
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_page-size=a4" <?php if($_GET['filter_page-size'] == 'a4') echo('selected="selected"'); ?>>A4</option>
    <?php } ?>
      
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '22-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_page-size=a5" <?php if($_GET['filter_page-size'] == 'a5') echo('selected="selected"'); ?>>A5</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '11-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_page-size=5-5-x-8-5" <?php if($_GET['filter_page-size'] == '5-5-x-8-5') echo('selected="selected"'); ?>>5.5 x 8.5</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '11-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_page-size=5-5-x-11" <?php if($_GET['filter_page-size'] == '5-5-x-11') echo('selected="selected"'); ?>>5.5 x 11</option>
    <?php } ?>
      
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_page-size=5-10-x-11" <?php if($_GET['filter_page-size'] == '5-10-x-11') echo('selected="selected"'); ?>>5.10 x 11</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_page-size=5-25-x-11" <?php if($_GET['filter_page-size'] == '5-25-x-11') echo('selected="selected"'); ?>>5.25 x 11</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_page-size=5-50-x-11" <?php if($_GET['filter_page-size'] == '5-50-x-11') echo('selected="selected"'); ?>>5.50 x 11</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '7-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_page-size=5-87-x-11" <?php if($_GET['filter_page-size'] == '5-87-x-11') echo('selected="selected"'); ?>>5.87 x 11</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_page-size=6-00-x-11" <?php if($_GET['filter_page-size'] == '6-00-x-11') echo('selected="selected"'); ?>>6.00 x 11</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '7-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_page-size=6-12-x-11" <?php if($_GET['filter_page-size'] == '6-12-x-11') echo('selected="selected"'); ?>>6.12 x 11</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_page-size=6-50-x-11" <?php if($_GET['filter_page-size'] == '6-50-x-11') echo('selected="selected"'); ?>>6.50 x 11</option>
    <?php } ?>
    
  </optgroup>
</select>
</form>

<?php } 

	    echo('</ul>');
		
	  }
	  
		/* echo('</nav>');
	    echo('</div>');
	    echo('</div>');
	    echo('</div>');*/
		
      } 
    }
  }
	  ?>
    

    </div>
  </nav>
 


  
     
</div>
</div>
</li>   
 
  <?php /*
  <li class="attribute_names" id="attributes" style="list-style:none; <?php if($is_product == 'no') { ?>display:none;<?php } ?>">
    <div>
	  <div style="max-width:1120px; padding-left:20px; padding-right:20px; margin:0 auto;">
    	<nav  style="overflow:hidden; padding-top:8px; padding-bottom:8px;"> 

  			<!-- Collect the nav links, forms, and other content for toggling --> 
  				


<?php

	


$formatted_attributes = array();

if( $is_product == 'yes' )
{
  $attributes = $product->get_attributes();
  $x = 0;

  foreach($attributes as $attr=>$attr_deets) 
  {
	if( isset( $attributes[ $attr ] ) || isset( $attributes[ 'pa_' . $attr ] ) ) 
    {
		$attribute = isset( $attributes[ $attr ] ) ? $attributes[ $attr ] : $attributes[ 'pa_' . $attr ];

        if ( $attribute['is_taxonomy'] ) 
		{
			$test[$x]['stuff'] = implode(', ', wc_get_product_terms( $product->id, $attribute['name'] ) );
			$test[$x]['name'] = ucwords(str_replace('pa_', '', str_replace('-', ' ', $attribute['name'])));
			
			if($test[$x]['name'] == 'Aircraftoem') $test[$x]['name'] = 'Aircraft/OEM';
			
			$x++;
        } 
		else $test = $attribute['value'];
    }
  }
  
  //echo('<pre>'); print_r($test); echo('</pre>');
  
  
  if(isset($test))
  {
	echo('<ul class="nav navbar-nav navbar-center" style="margin:0px !important;">');
    for($x=0; $x<sizeof($test); $x++)
    {
      echo('<li class="attribute_name" style="float:left;">' . $test[$x]['name'] . ': ' . $test[$x]['stuff'] . '</li>');
    }
    echo('</ul>');
  }
  
}


?>
 
   		  </nav>
		</div>
      </li>*/ ?>
	    
    </div>
  </div>
</li> 


<nav class="navbar navbar-default navbar navbar-fixed-top" id="filtermenu">
	<span style="padding-left: 62px; position:absolute; top:8px;">FILTER</span>
  <span class="hamburger" >&#9776;</span>
  <span class="cross" style="display:none">&#735;</span>

<div class="menu">
<div id="topbar">

	<div class="col-full" >
    	<nav class="navbar-one navbar-default" role="navigation"> 

  			<!-- Collect the nav links, forms, and other content for toggling --> 
  				<div> 
                
    				<ul class="nav navbar-nav navbar-center"> 
      					<li class="all_parents_row" id="all_parents_row">
  <?php
	$sql = "SELECT wp_terms.*, wp_term_taxonomy.* FROM wp_terms LEFT JOIN wp_term_taxonomy ON wp_terms.term_id = wp_term_taxonomy.term_id
	WHERE wp_term_taxonomy.taxonomy = 'product_cat' AND wp_term_taxonomy.parent=0 AND wp_terms.term_id !=183 AND wp_terms.term_id !=154 
	AND wp_terms.term_id !=153 AND wp_terms.term_id !=152 AND wp_terms.term_id !=151 AND wp_terms.term_id !=150 AND wp_terms.term_id !=113 
	AND wp_terms.term_id !=114 AND wp_terms.term_id !=116 AND wp_terms.term_id !=115 
	ORDER BY `wp_term_taxonomy`.`parent`  DESC";
	$result = mysqli_query($dbhandle,$sql);
	$count = mysqli_num_rows($result);
	if($count > 0)
{
	while($data = mysqli_fetch_array($result))
	{
	  echo('<li class="parent_name ');
	  if($data['slug'] == $url_parts[2]) echo('active');
	  echo('" style="cursor:pointer;" ');
	  //echo('onClick="ShowContent(\'all_children_row\');');
	  echo(' ' . show_hide_content($data['term_id']) . ' "><a href="/product-category/' . $data['slug'] . '">' . $data['name'] . '</a></li>');
	}
}

  ?>
  
					</li> 
				</ul>
               
	    	</div>               
		</nav>
	</div>
</div>


	
    
  <li class="all_children_row" id="all_children_row" <?php if($url_parts[1] != 'product-category') { ?>style="display:none;"<?php } ?>>
  <div id="topbar">
	<div class = "second-level-filter-wrapper" style="max-width:1120px; padding-left:20px; padding-right:20px; margin:0 auto;">
    	<nav class="navbar-two navbar-default" role="navigation"> 

  			<!-- Collect the nav links, forms, and other content for toggling --> 
  				<div> 
	
<?php
  
  $result = mysqli_query($dbhandle,$sql); 
  $count = mysqli_num_rows($result);
  if($count > 0)
  {
    while($data = mysqli_fetch_array($result))
    {
	  if($data['term_id'] == 112) $filter_method = 'filter_ring-count'; // ring binders
  	  if($data['term_id'] == 120) $filter_method = 'filter_hole-count'; // divider tabs
  	  if($data['term_id'] == 119) $filter_method = 'filter_hole-count'; // sheet protectors
  	  if($data['term_id'] == 118) $filter_method = 'filter_hole-count'; // checklist covers
  	  if($data['term_id'] == 121) $filter_method = 'filter_punch-type'; // paper punches
  /*  if($data['term_id'] == 122) $filter_method = 'filter_hole-count'; // paper stock
	*/  
	  if($data['slug'] == $url_parts[2]) {
  
	  echo('<ul class="child_row" id="child_row_' . $data['term_id'] . '" ');
	  if($data['slug'] != $url_parts[2]) echo('style="display:none;"');
	  echo('>' );
	  
	  $sql2 = "SELECT wp_terms.*, wp_term_taxonomy.* FROM wp_terms LEFT JOIN wp_term_taxonomy ON wp_terms.term_id = wp_term_taxonomy.term_id
      WHERE wp_term_taxonomy.taxonomy = 'product_cat' AND wp_term_taxonomy.parent=" . $data['term_id'] . "
      ORDER BY `wp_term_taxonomy`.`parent`  ASC";
	  $result2 = mysqli_query($dbhandle,$sql2);
	  $count2 = mysqli_num_rows($result2);

	  if($count2 > 0)
	  {
		echo('<div style="width:89%; float:left">');
		echo('<ul class="nav navbar-nav navbar-center" style="margin:0px !important; width:100%;">' );
		
	    while($data2 = mysqli_fetch_array($result2))
	    {
		  	echo('<li class="child_name');
			
			$slug2_parts = explode('-', $data2['slug']);
			$slug2 = '?' . $filter_method . '=' . $slug2_parts[0] . '-' . $slug2_parts[1];
			$siteurl = 'https://aerobind.com/';
			
			
			if(($slug2 == $url_parts[3]) || ($data2['slug'] == $url_parts[3]) )
			{
			  echo(' active');
			  $active_slug = $slug2;
			}
			
			echo('" style="cursor:pointer;" onClick="ShowContent(\'all_children_row\'); ' . show_hide_content($data['term_id']) . ' ">'); // ShowContent(\'attributes\');
			echo('<a href="'.$siteurl.'product-category/' . $data['slug'] . '/' . $slug2 . '">');
			  echo($data2['name']);
			echo('</a>');
			echo('</li>');
		  
	    }
		echo('</ul>');
		echo('</div>');
		
$url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "/";

$url = 'https://aerobind.com/product-category/' . $data['slug'] . '/';
if(isset($active_slug)) $url .= $active_slug;

//echo 'active: ' . $active_slug;
		
		
		
	

if(stripos($url, '?') !== false) $seperator = '&';
else $seperator = '?';
		
		
		
if(($data['term_id'] == 112)) { ?> 

<!--Ring Binders --> 

<form>
<select onchange="javascript:location.href=this.value;">
  <option value=''>All Products</option>
  <optgroup label="Aircraft/OEM">
    
      <?php
	  $show = 'yes';
	  if( (isset($_GET['filter_ring-count'])) && (($_GET['filter_ring-count'] == '22-ring') || ($_GET['filter_ring-count'] == '9-ring') || ($_GET['filter_ring-count'] == '10-ring') || ($_GET['filter_ring-count'] == '12-ring') || ($_GET['filter_ring-count'] == '13-ring')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=boeing" <?php if($_GET['filter_aircraftoem'] == 'boeing') echo('selected="selected"'); ?>>Boeing</option>
      <?php } ?>
      
      <?php
	  $show = 'yes';
	  if( (isset($_GET['filter_ring-count'])) && (($_GET['filter_ring-count'] == '7-ring') || ($_GET['filter_ring-count'] == '9-ring') || ($_GET['filter_ring-count'] == '10-ring') || ($_GET['filter_ring-count'] == '12-ring') || ($_GET['filter_ring-count'] == '13-ring') || ($_GET['filter_ring-count'] == '1-ring')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=airbus" <?php if($_GET['filter_aircraftoem'] == 'airbus') echo('selected="selected"'); ?>>Airbus</option>
      <?php } ?>
    
      <?php
      $show = 'yes';
	  if( (isset($_GET['filter_ring-count'])) && (($_GET['filter_ring-count'] == '22-ring') || ($_GET['filter_ring-count'] == '9-ring') || ($_GET['filter_ring-count'] == '10-ring') || ($_GET['filter_ring-count'] == '11-ring') || ($_GET['filter_ring-count'] == '12-ring') || ($_GET['filter_ring-count'] == '13-ring') || ($_GET['filter_ring-count'] == '1-ring')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=gulfstream" <?php if($_GET['filter_aircraftoem'] == 'gulfstream') echo('selected="selected"'); ?>>Gulfstream</option>
      <?php } ?>
        
      <?php
      $show = 'yes';
	  if( (isset($_GET['filter_ring-count'])) && (($_GET['filter_ring-count'] == '22-ring') || ($_GET['filter_ring-count'] == '7-ring') || ($_GET['filter_ring-count'] == '9-ring') || ($_GET['filter_ring-count'] == '10-ring') || ($_GET['filter_ring-count'] == '12-ring') || ($_GET['filter_ring-count'] == '13-ring') || ($_GET['filter_ring-count'] == '1-ring')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=embraer" <?php if($_GET['filter_aircraftoem'] == 'embraer') echo('selected="selected"'); ?>>Embraer</option>
      <?php } ?>
    
      <?php
      $show = 'yes';
	  if( (isset($_GET['filter_ring-count'])) && (($_GET['filter_ring-count'] == '22-ring') || ($_GET['filter_ring-count'] == '7-ring') || ($_GET['filter_ring-count'] == '9-ring') || ($_GET['filter_ring-count'] == '10-ring') || ($_GET['filter_ring-count'] == '12-ring') || ($_GET['filter_ring-count'] == '13-ring') || ($_GET['filter_ring-count'] == '1-ring')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=bombardier" <?php if($_GET['filter_aircraftoem'] == 'bombardier') echo('selected="selected"'); ?>>Bombardier</option>
      <?php } ?>
    
  </optgroup>
  <optgroup label="Brand">
    
      <?php
      $show = 'yes';
	  if( (isset($_GET['filter_ring-count'])) && (($_GET['filter_ring-count'] == '7-ring') || ($_GET['filter_ring-count'] == '9-ring') || ($_GET['filter_ring-count'] == '10-ring') || ($_GET['filter_ring-count'] == '11-ring') || ($_GET['filter_ring-count'] == '12-ring') || ($_GET['filter_ring-count'] == '13-ring') || ($_GET['filter_ring-count'] == '22-ring')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_brand=aeroflex" <?php if($_GET['filter_brand'] == 'aeroflex') echo('selected="selected"'); ?>>AEROFLEX</option>
      <?php } ?>
	  
	  <?php
      $show = 'yes';
	  if( (isset($_GET['filter_ring-count'])) && (($_GET['filter_ring-count'] == '7-ring') || ($_GET['filter_ring-count'] == '9-ring') || ($_GET['filter_ring-count'] == '10-ring') || ($_GET['filter_ring-count'] == '11-ring') || ($_GET['filter_ring-count'] == '12-ring') || ($_GET['filter_ring-count'] == '13-ring') || ($_GET['filter_ring-count'] == '1-ring')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_brand=aerometal" <?php if($_GET['filter_brand'] == 'aerometal') echo('selected="selected"'); ?>>AEROMETAL</option>
      <?php } ?>
    
      <?php
      $show = 'yes';
	  if( (isset($_GET['filter_ring-count'])) && (($_GET['filter_ring-count'] == '22-ring') || ($_GET['filter_ring-count'] == '1-ring')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_brand=aerad" <?php if($_GET['filter_brand'] == 'aerad') echo('selected="selected"'); ?>>AERAD</option>
      <?php } ?>
    
      <?php
      $show = 'yes';
	  if( (isset($_GET['filter_ring-count'])) && (($_GET['filter_ring-count'] == '23-ring') || ($_GET['filter_ring-count'] == '1-ring')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_brand=aerobinder-ii" <?php if($_GET['filter_brand'] == 'aerobinder-ii') echo('selected="selected"'); ?>>AEROBINDER II</option>
      <?php } ?>
   
   </optgroup>  
</select>
</form>


<?php } else if($data['term_id'] == 120) { ?>

<!-- Divider Tabs -->

<form>
<select onchange="javascript:location.href=this.value;">
  <option value=''>All Products</option>
  
  <optgroup label="Aircraft/OEM">
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '22-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=boeing" <?php if($_GET['filter_aircraftoem'] == 'boeing') echo('selected="selected"'); ?>>Boeing</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '7-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=airbus" <?php if($_GET['filter_aircraftoem'] == 'airbus') echo('selected="selected"'); ?>>Airbus</option>
    <?php } ?>
      
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '11-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=gulfstream" <?php if($_GET['filter_aircraftoem'] == 'gulfstream') echo('selected="selected"'); ?>>Gulfstream</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=embraer" <?php if($_GET['filter_aircraftoem'] == 'embraer') echo('selected="selected"'); ?>>Embraer</option>
    <?php } ?>
      
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=bombardier" <?php if($_GET['filter_aircraftoem'] == 'bombardier') echo('selected="selected"'); ?>>Bombardier</option>
    <?php } ?>
      
  </optgroup>
  
  <optgroup label="Material">
    <option value="<?php echo $url . $seperator; ?>filter_material=card-stock" <?php if($_GET['filter_material'] == 'card-stock') echo('selected="selected"'); ?>>CARDSTOCK</option>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '7-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_material=synthetic" <?php if($_GET['filter_material'] == 'synthetic') echo('selected="selected"'); ?>>SYNTHETIC (NO TEAR)</option>
    <?php } ?>
      
  </optgroup>  
  
</select>
</form>


<?php } else if($data['term_id'] == 119) { ?>

<!-- Sheet Protectors -->

<form>
<select onchange="javascript:location.href=this.value;">
  <option value=''>All Products</option>
  <optgroup label="Aircraft/OEM">
    <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=boeing" <?php if($_GET['filter_aircraftoem'] == 'boeing') echo('selected="selected"'); ?>>Boeing</option>
    <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=airbus" <?php if($_GET['filter_aircraftoem'] == 'airbus') echo('selected="selected"'); ?>>Airbus</option>
    <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=gulfstream" <?php if($_GET['filter_aircraftoem'] == 'gulfstream') echo('selected="selected"'); ?>>Gulfstream</option>
    <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=embraer" <?php if($_GET['filter_aircraftoem'] == 'embraer') echo('selected="selected"'); ?>>Embraer</option>
    <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=bombardier" <?php if($_GET['filter_aircraftoem'] == 'bombardier') echo('selected="selected"'); ?>>Bombardier</option>
  </optgroup>
</select>
</form>

<?php }  else if($data['term_id'] == 118) { ?>

<!-- Checklist Covers -->

<form>
<select onchange="javascript:location.href=this.value;">
  <option value=''>All Products</option>
  <optgroup label="Aircraft/OEM">
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '12-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=boeing" <?php if($_GET['filter_aircraftoem'] == 'boeing') echo('selected="selected"'); ?>>Boeing</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '12-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=airbus" <?php if($_GET['filter_aircraftoem'] == 'airbus') echo('selected="selected"'); ?>>Airbus</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '11-hole') || ($_GET['filter_hole-count'] == '12-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=gulfstream" <?php if($_GET['filter_aircraftoem'] == 'gulfstream') echo('selected="selected"'); ?>>Gulfstream</option>
    <?php } ?>
      
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '12-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=embraer" <?php if($_GET['filter_aircraftoem'] == 'embraer') echo('selected="selected"'); ?>>Embraer</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '12-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=bombardier" <?php if($_GET['filter_aircraftoem'] == 'bombardier') echo('selected="selected"'); ?>>Bombardier</option>
    <?php } ?>
    
  </optgroup>
   <optgroup label="Cover Type">
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '12-hole') || ($_GET['filter_hole-count'] == '12-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_cover-type=flexible" <?php if($_GET['filter_cover-type'] == 'flexible') echo('selected="selected"'); ?>>FLEXIBLE</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '10-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_cover-type=rigid" <?php if($_GET['filter_cover-type'] == 'rigid') echo('selected="selected"'); ?>>RIGID</option>
    <?php } ?>
    
    <?php /*
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '11-hole') || ($_GET['filter_hole-count'] == '12-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_cover-type=smooth-rigid" <?php if($_GET['filter_cover-type'] == 'smooth-rigid') echo('selected="selected"'); ?>>SMOOTH RIGID</option>
    <?php } */?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '12-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_cover-type=plain" <?php if($_GET['filter_cover-type'] == 'plain') echo('selected="selected"'); ?>>NO POCKETS</option>
    <?php } ?>
      
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '12-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_cover-type=pockets" <?php if($_GET['filter_cover-type'] == 'pockets') echo('selected="selected"'); ?>>POCKETS</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '12-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_cover-type=window" <?php if($_GET['filter_cover-type'] == 'window') echo('selected="selected"'); ?>>WINDOW</option>
    <?php } ?>
      
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '10-hole') || ($_GET['filter_hole-count'] == '12-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_cover-type=window-and-pocket" <?php if($_GET['filter_cover-type'] == 'window-and-pocket') echo('selected="selected"'); ?>>WINDOW AND POCKET</option>
    <?php } ?>
    
  </optgroup> 
  
</select>
</form>


<?php }  else if ($data['term_id'] == 121) { ?>

<!-- Paper Punches -->

<form>
<select onchange="javascript:location.href=this.value;">
  <option value=''>All Products</option>
  
  <optgroup label="Aircraft/OEM">
    <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=boeing" <?php if($_GET['filter_aircraftoem'] == 'boeing') echo('selected="selected"'); ?>>Boeing</option>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-pattern'])) && (($_GET['filter_hole-pattern'] == '7-hole') || ($_GET['filter_hole-pattern'] == '7-hole') || ($_GET['filter_hole-pattern'] == 'removable-die-')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=airbus" <?php if($_GET['filter_aircraftoem'] == 'airbus') echo('selected="selected"'); ?>>Airbus</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-pattern'])) && (($_GET['filter_hole-pattern'] == '11-hole') || ($_GET['filter_hole-pattern'] == '22-hole') || ($_GET['filter_hole-pattern'] == 'removable-die-')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=gulfstream" <?php if($_GET['filter_aircraftoem'] == 'gulfstream') echo('selected="selected"'); ?>>Gulfstream</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-pattern'])) && (($_GET['filter_hole-pattern'] == '7-hole') || ($_GET['filter_hole-pattern'] == '7-hole') || ($_GET['filter_hole-pattern'] == 'removable-die-')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=embraer" <?php if($_GET['filter_aircraftoem'] == 'embraer') echo('selected="selected"'); ?>>Embraer</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-pattern'])) && (($_GET['filter_hole-pattern'] == '7-hole') || ($_GET['filter_hole-pattern'] == '7-hole') || ($_GET['filter_hole-pattern'] == 'removable-die-')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_aircraftoem=bombardier" <?php if($_GET['filter_aircraftoem'] == 'bombardier') echo('selected="selected"'); ?>>Bombardier</option>
    <?php } ?>
      
  </optgroup>
  
  <optgroup label="Punch Type">
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-pattern'])) && (($_GET['filter_hole-pattern'] == '7-hole') || ($_GET['filter_hole-pattern'] == '11-hole') || ($_GET['filter_hole-pattern'] == '22-hole') || ($_GET['filter_hole-pattern'] == 'removable-die-')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_punch-type=electric" <?php if($_GET['filter_punch-type'] == 'electric') echo('selected="selected"'); ?>>ELECTRIC</option>
    <?php } ?>
    
    <?php
    /*  $show = 'yes';
	  if( (isset($_GET['filter_hole-pattern'])) && (($_GET['filter_hole-pattern'] == '7-hole') || ($_GET['filter_hole-pattern'] == '11-hole') || ($_GET['filter_hole-pattern'] == '22-hole') || ($_GET['filter_hole-pattern'] == 'removable-die-')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_punch-type=light-duty-manual" <?php if($_GET['filter_punch-type'] == 'light-duty-manual') echo('selected="selected"'); ?>>LIGHT DUTY MANUAL</option>
    <?php } */ ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-pattern'])) && (($_GET['filter_hole-pattern'] == '7-hole') || ($_GET['filter_hole-pattern'] == '7-hole') || ($_GET['filter_hole-pattern'] == 'removable-die-')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_punch-type=heavy-duty-manual" <?php if($_GET['filter_punch-type'] == 'heavy-duty-manual') echo('selected="selected"'); ?>>HEAVY DUTY MANUAL</option>
      <?php } ?>
      
  </optgroup> 
  
</select>
</form>

<?php }  else if($data['term_id'] == 122) { ?>
  
<!-- Paper Stock -->

<form>
<select onchange="javascript:location.href=this.value;">
  <option value=''>All Products</option>
  <optgroup label="Page Size">                          
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_page-size=a4" <?php if($_GET['filter_page-size'] == 'a4') echo('selected="selected"'); ?>>A4</option>
    <?php } ?>
      
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '22-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_page-size=a5" <?php if($_GET['filter_page-size'] == 'a5') echo('selected="selected"'); ?>>A5</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '11-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_page-size=5-5-x-8-5" <?php if($_GET['filter_page-size'] == '5-5-x-8-5') echo('selected="selected"'); ?>>5.5 x 8.5</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '11-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_page-size=5-5-x-11" <?php if($_GET['filter_page-size'] == '5-5-x-11') echo('selected="selected"'); ?>>5.5 x 11</option>
    <?php } ?>
      
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_page-size=5-10-x-11" <?php if($_GET['filter_page-size'] == '5-10-x-11') echo('selected="selected"'); ?>>5.10 x 11</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_page-size=5-25-x-11" <?php if($_GET['filter_page-size'] == '5-25-x-11') echo('selected="selected"'); ?>>5.25 x 11</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_page-size=5-50-x-11" <?php if($_GET['filter_page-size'] == '5-50-x-11') echo('selected="selected"'); ?>>5.50 x 11</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '7-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_page-size=5-87-x-11" <?php if($_GET['filter_page-size'] == '5-87-x-11') echo('selected="selected"'); ?>>5.87 x 11</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_page-size=6-00-x-11" <?php if($_GET['filter_page-size'] == '6-00-x-11') echo('selected="selected"'); ?>>6.00 x 11</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '7-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_page-size=6-12-x-11" <?php if($_GET['filter_page-size'] == '6-12-x-11') echo('selected="selected"'); ?>>6.12 x 11</option>
    <?php } ?>
    
    <?php
      $show = 'yes';
	  if( (isset($_GET['filter_hole-count'])) && (($_GET['filter_hole-count'] == '7-hole') || ($_GET['filter_hole-count'] == '22-hole')) ) $show = 'no';
	  if($show == 'yes') { ?>
      <option value="<?php echo $url . $seperator; ?>filter_page-size=6-50-x-11" <?php if($_GET['filter_page-size'] == '6-50-x-11') echo('selected="selected"'); ?>>6.50 x 11</option>
    <?php } ?>
    
  </optgroup>
</select>
</form>

<?php } 

	    echo('</ul>');
		
	  }
	  
		/* echo('</nav>');
	    echo('</div>');
	    echo('</div>');
	    echo('</div>');*/
		
      } 
    }
  }
	  ?>
    

    </div>
  </nav>
  
</div>
</div>
</li>
 </div>

</nav> 

    </div>
  </div>
</li>
  

	<script type='text/javascript'>
	var doc = document.documentElement;
	doc.setAttribute('data-useragent', navigator.userAgent);
	</script>
  			
    <?php
}

add_action( 'storefront_before_header', 'storefront_add_topbar' );


/*
  Replace PayPal Logo on checkout
 __________________________________ */

function paypal_checkout_icon () {
 return '/wp-content/uploads/paymenticons.jpg';
}
add_filter('woocommerce_paypal_icon','paypal_checkout_icon');


/**
 * WooCommerce Extra Feature
 * --------------------------
 *
 * Register a shortcode that creates a product categories dropdown list
 *
 * Use: [product_categories_dropdown orderby="title" count="0" hierarchical="0"]
 *
 */
add_shortcode( 'product_categories_dropdown', 'woo_product_categories_dropdown' );
function woo_product_categories_dropdown( $atts ) {
  extract(shortcode_atts(array(
    'show_count'         => '0',
    'hierarchical'  => '0',
    'orderby' 	    => ''
    ), $atts));
	
	ob_start();
	
	$c = $count;
	$h = $hierarchical;
	$o = ( isset( $orderby ) && $orderby != '' ) ? $orderby : 'order';
		
	// Stuck with this until a fix for http://core.trac.wordpress.org/ticket/13258
	woocommerce_product_dropdown_categories( $c, $h, 0, $o );
	?>

	<script type='text/javascript'>
	
	/* <![CDATA[ */
		var product_cat_dropdown = document.getElementById("dropdown_product_cat");
		function onProductCatChange() {
			if ( product_cat_dropdown.options[product_cat_dropdown.selectedIndex].value !=='' ) {
				location.href = "<?php echo home_url(); ?>/?product_cat="+product_cat_dropdown.options[product_cat_dropdown.selectedIndex].value;
			}
		}
		product_cat_dropdown.onchange = onProductCatChange;
	/* ]]> */
	</script>
    
  


	<?php
	
	return ob_get_clean();
	
}

add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );

function enqueue_parent_styles() {
   wp_enqueue_style( 'child-theme', get_stylesheet_directory_uri().'/includes/sidr-2.2.1/dist/stylesheets/jquery.sidr.dark.min.css' );
}

add_action( 'wp_enqueue_scripts', 'load_theme_scripts' );

function load_theme_scripts() {

    global $wp_scripts; 

    // $wp_scripts->registered[ 'wc-single-product' ]->src = 'https://aerobind.com/wp-content/themes/storefront-child/woocommerce/assets/js/frontend/single-product.min.js';
	
	$wp_scripts->registered[ 'wc-single-product' ]->src = "<?php echo get_template_directory_uri().'/woocommerce/assets/js/frontend/single-product.min.js'; ?>"; 
	
	//get_template_directory_uri() . '/woocommerce/assets/js/frontend/single-product.min.js';

    }
/**
 * @snippet       WooCommerce Disable Payment Gateway for a Specific Country
 * @how-to        Watch tutorial @ https://businessbloomer.com/?p=19055
 * @sourcecode    https://businessbloomer.com/?p=164
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 2.4.7
 
 
function payment_gateway_disable_country( $available_gateways ) {
global $woocommerce;
if ( isset( $available_gateways['authorizenet'] ) && $woocommerce->customer->get_country() <> 'US' && $woocommerce->customer->get_country() <> 'CA') {
unset( $available_gateways['authorizenet'] );
} else if ( isset( $available_gateways['paypal'],$available_gateways['bacs'] ) && $woocommerce->customer->get_country() == 'US' || $woocommerce->customer->get_country() == 'CA') {
unset( $available_gateways['paypal'],$available_gateways['bacs'] );
}
return $available_gateways;
}
 
add_filter( 'woocommerce_available_payment_gateways', 'payment_gateway_disable_country' );
*/

/**
 * Custom PayPal button text
 *
 */
add_filter( 'gettext', 'ld_custom_paypal_button_text', 20, 3 );
function ld_custom_paypal_button_text( $translated_text, $text, $domain ) {
switch ( $translated_text ) {
case 'Proceed to PayPal' :
$translated_text = __( 'Proceed to Credit Card / PayPal', 'woocommerce' );
break;
}
return $translated_text;
}

/* Hack 'removable die' option
add_filter( '',function($link) {
	if(isset($link[' ']))
		$link[' '][' '] = "Removable Die";
} ); */
add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 3;
	}
}
/*
function my_searchwp_term_in( $term, $engine, $original_term ) {
	
	// $term is an array because other filters may have broadened the search already (e.g. LIKE Terms extension)
	// $original_term is the original, unaltered term
	
	// if someone searched for "NYC" we need to convert it to "New York City"
	// because we never write "NYC" and always write "New York City"
//	if ( 'nyc' == strtolower( $original_term ) ) {
//		$term = explode( ' ', 'New York City' ); // keep it an array
		return str_replace( array( '-', '-' ), ' ', $term );
//	}
	
//	return $term;
}
add_filter( 'searchwp_term_in', 'my_searchwp_term_in' );
*/
/*
function my_searchwp_indexer_pre_process_content( $content ) {
	
	// TODO: manipulate $content in any way you want
	
	return $content;
}
add_filter( 'searchwp_indexer_pre_process_content', 'my_searchwp_indexer_pre_process_content' );
function hs5262_searchwp_indexer_pre_process_content( $content ) {
	// Replace em-dashes and en-dashes with hyphens
	return str_replace( array( '-', '&mdash' ), '&ndash', $content );
}
add_filter( 'searchwp_indexer_pre_process_content', 'hs5262_searchwp_fix_dashes' );
add_filter( 'searchwp_terms', 'hs5262_searchwp_fix_dashes' ); */

/* Dynamic Product Page Code */

add_action( 'storefront_page_after', 'dynamicproduct', 10 );
function dynamicproduct() {
	global $post;
	$post_slug = $post->post_name;

	if ($post_slug != '' && is_page_template('template-dynamic-sort.php') ) {
		echo 'success';
		$dyn_filter = ucwords(str_replace("-"," ",$post_slug));
/*		echo $dyn_filter;
		$username = "aerobind";
		$password = "DSDlIQeyQ94VCdCsJo73";
		$hostname = "127.0.0.1";
		$dbhandle = @mysqli_connect($hostname, $username, $password);
		$selected = mysqli_select_db("wp_aerobind",$dbhandle);
	//	include_once $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php';
	//	$link = mysqli_connect('p:'.DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
//		$sql = "SELECT regions, images FROM 'wp_2_mapsvg_database_2163'";
//		$result = $link->query($sql);
//		$count = mysqli_query("SELECT COUNT() FROM $sql");
//		echo $result;
//		echo $count;
		//take count of items matching page slug variable for region and loop through to display a table or <ul> of images and links
/*		foreach($sql->$count as $row) {
			echo "<tr>";
			echo "<td>" . $row['regions'] . "</td>";
			echo "<td>" . $row['images'] . "</td>";
			echo "</tr>";
		} 
		
		echo '<div class="container"><h2>'.$dyn_filter.' Products</h2><div class="container">';
	//	$result = $link->query("SELECT * FROM wp_2_mapsvg_database_2163 WHERE regions LIKE '%Hornwood%'");
	//	$results = array();
	//	echo $results;
		$sql = "SELECT wp_terms.term_id AS term_id FROM wp_terms LEFT JOIN wp_term_taxonomy ON wp_terms.term_id = wp_term_taxonomy.term_id WHERE wp_term_taxonomy.taxonomy = 'product_tag' AND wp_terms.slug='".$dyn_filter."' ORDERBY 'product_tag' DESC";
		$result = mysqli_query($sql); 
  		$count = mysqli_num_rows($result);
  		if($count > 0)
  		{
//			foreach($link->query("SELECT * FROM wp_terms WHERE name LIKE '%".$dyn_filter."%'") as $row) {
			  while($data = mysqli_fetch_array($result)) {
	//		foreach($link->query("SELECT * FROM wp_2_mapsvg_database_2163 WHERE region_link = 'the-eastern-plains'") as $row) {
	//			while ($row = mysqli_fetch_assoc($result)) {
		//		$results[] = $row;
		//		echo $row;
		//		$count = count($row);
		//		for ( $i = 0; $i <= $count; $i++ ) {
		$post1 = $row['post_id'];
		$perma = get_permalink($post1);
		$img_gallery =   get_the_post_thumbnail_url($post1);
					echo "<div class='gallery-list'>";
						echo "<a class='gallery-link' href='".$perma."'>";
						echo "<div class='gallery-title'><p>" . $row['dataTitle'] . "</p></div>";
						echo "<div class='gallery-thumbnail'><img src=" . $img_gallery . "></div>";
						echo "</a>";
					echo "</div>";
		//		}
			}
		echo "</div></div>"; 
		}
	}
	}*/
	$newcat = 'filter_aircraftoem='.str_replace("compatible","",$post_slug);
	$category = $newcat;
	}
}

/**
 * Prevent PO box shipping
 */
add_action('woocommerce_after_checkout_validation', 'deny_pobox_postcode');

function deny_pobox_postcode( $posted ) {
  global $woocommerce;

  $address  = ( isset( $posted['shipping_address_1'] ) ) ? $posted['shipping_address_1'] : $posted['billing_address_1'];
  $postcode = ( isset( $posted['shipping_postcode'] ) ) ? $posted['shipping_postcode'] : $posted['billing_postcode'];

  $replace  = array(" ", ".", ",");
  $address  = strtolower( str_replace( $replace, '', $address ) );
  $postcode = strtolower( str_replace( $replace, '', $postcode ) );

  if ( strstr( $address, 'pobox' ) || strstr( $postcode, 'pobox' ) ) {
    wc_add_notice( sprintf( __( "Sorry, we cannot ship to PO BOX addresses.") ) ,'error' );
  }
}
/**
 * @snippet       WooCommerce Add fee to checkout for a gateway ID
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @testedwith    WooCommerce 3.7
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
  
// Part 1: assign fee
  
add_action( 'woocommerce_cart_calculate_fees', 'bbloomer_add_checkout_fee_for_gateway' );
  
function bbloomer_add_checkout_fee_for_gateway() {
    // $total = WC()->cart->cart_contents_total + WC()->cart->shipping_total;
    $total = WC()->cart->cart_contents_total;
    /*echo "<script type='text/javascript'>alert('$total');</script>";*/
    $chosen_gateway = WC()->session->get( 'chosen_payment_method' );
     if (( $chosen_gateway == 'bacs')  && ( $total < 500 ))  {
      WC()->cart->add_fee( 'Wire Transfer Fee for Orders Below $500', 48.00 );
   }
}
  
// Part 2: reload checkout on payment gateway change
  
add_action( 'woocommerce_review_order_before_payment', 'bbloomer_refresh_checkout_on_payment_methods_change' );
  
function bbloomer_refresh_checkout_on_payment_methods_change(){
    ?>
    <script type="text/javascript">
        (function($){
            $( 'form.checkout' ).on( 'change', 'input[name^="payment_method"]', function() {
                $('body').trigger('update_checkout');
            });
        })(jQuery);
    </script>
    <?php
}

//Add My Account Page Custom Bodt Class
add_filter( 'body_class','my_body_classes' );
function my_body_classes( $classes ) {
 
    if ( is_account_page() ) {
     
        $classes[] = 'login-nav-hide';
         
    }
     
    return $classes;
     
}

//add class in thank you page
add_filter( 'woocommerce_thankyou', 'misha_thank_you_title' );
 
	function misha_thank_you_title( $old_title ){ ?>
	
	<script type="text/javascript">

	 	$('.woocommerce').addClass('container');

	</script>
	<?php
	}

//Disable auto update for plugins
add_filter( 'auto_update_plugin', '__return_false' );

//Disable auto update for themes
add_filter( 'auto_update_theme', '__return_false' );

/* Update Variation Product price in Listing and Detail Page */
function aerobind_change_product_price_display( $price ) {
		if (!is_cart()) {
			global $post;
			$id = $post->ID;

			$product_id = $id; // Defined product Id for testing

			$product = wc_get_product($product_id); // If needed

			$product_data = $product->get_meta_data($product_id);

			$data= get_post_meta($product_id,'_pricing_rules',true);
			if(!empty($data))
			{
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

			$price = '<span class="woocommerce-Price-amount amount"><bdi>'. '$' . $minprice . '</bdi></span> - <span class="woocommerce-Price-amount amount"><bdi>' . '$' . $maxprice . '</bdi></span>' ;
		}
			return $price;
	}
	return $price;
  }
  add_filter( 'woocommerce_get_price_html', 'aerobind_change_product_price_display' );
  add_filter( 'woocommerce_cart_item_price', 'aerobind_change_product_price_display' );