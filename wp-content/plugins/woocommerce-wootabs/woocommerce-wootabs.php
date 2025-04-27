<?php
/*
Plugin Name: WooTabs
Plugin URI: http://www.wootabs.com
Description: WooTabs, allows you to add extra tabs (as many as you want) to the WooCommerce Product Details page.
Author: WPCream.com
Version: 2.1.8
Author URI: http://wpcream.com
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$wootabs_purchase_url = 'http://codecanyon.net/item/wootabsadd-extra-tabs-to-woocommerce-product-page/7891253';
$wootabs_service_url = 'http://104.236.248.154/fd78fgd/wtbs/verify-purchase-code/';

$wootabs_deactivated_tab_title = __( 'NON VERIFIED', 'woocommerce-wootabs' );
$wootabs_deactivated_tab_content = __( 'NON VERIFIED CONTENT', 'woocommerce-wootabs' );


if ( ! defined( 'WOOTABS_UPDATE_API_URL' ) ) {
	define( 'WOOTABS_UPDATE_API_URL', $wootabs_service_url );
}

if ( ! defined( 'WOOTABS_PURCHASE_URL' ) ) {
	define( 'WOOTABS_PURCHASE_URL', $wootabs_purchase_url );
}

if ( ! defined( 'WOOTABS_DEACTIVATED_TAB_TITLE' ) ) {
	define( 'WOOTABS_DEACTIVATED_TAB_TITLE', $wootabs_deactivated_tab_title );
}

if ( ! defined( 'WOOTABS_DEACTIVATED_TAB_CONTENT' ) ) {
	define( 'WOOTABS_DEACTIVATED_TAB_CONTENT', $wootabs_deactivated_tab_content );
}

define( 'WOOTABS_URI', plugin_dir_url( __FILE__ ) );

define( 'WOOTABS_PATH', plugin_dir_path( __FILE__ ) );

register_activation_hook( __FILE__, array( 'Woocommerce_Wootabs', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Woocommerce_Wootabs', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Woocommerce_Wootabs', 'get_instance' ) );

require_once( plugin_dir_path( __FILE__ ) . 'public/class-woocommerce-wootabs.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-wootabs_wp_editor.php' );
require_once( plugin_dir_path( __FILE__ ) . 'public/class-wootabs_update_plugin.php' );

/**
 * Action hook - Execute on plugin loaded
 *
 * @since    1.0.0
 */

function on_woocommerce_wootabs_loaded(){

	$canView_settings = false;

	if( is_user_logged_in() ){

		global $current_user, $wpdb;

		$allowed_settings_role = array( "administrator" );

		$wootabs_enable_shop_manager_settings = get_option('wootabs-enable-shop-manager-settings') && get_option('wootabs-enable-shop-manager-settings') == "on" ? true : false;

		if( $wootabs_enable_shop_manager_settings ){

			$allowed_settings_role[] = "shop_manager";
		}

		$user = get_userdata( $current_user->ID );

		$capabilities = $user->{$wpdb->prefix . 'capabilities'};

		if ( !isset( $wp_roles ) ){

			$wp_roles = new WP_Roles();
		}

		foreach ( $wp_roles->role_names as $role => $name ){

			if ( array_key_exists( $role, $capabilities ) ){

				if( !$canView_settings && in_array( $role, $allowed_settings_role ) ){

					$canView_settings = true;
				}

			}

		}

	}

	if( $canView_settings ){

		Woocommerce_Wootabs_Admin::get_instance();
	}
}

function wootabs_compare_tabs_order($a, $b) {

    if ($a['order'] == $b['order']) return 0;
    return ($a['order'] < $b['order']) ? -1 : 1;
}

function wootabs_sort_tabs_byOrderNum( $arr = array() ){

	if( !empty($arr) ){

		uasort( $arr, 'wootabs_compare_tabs_order');
	}

	return $arr;
}

function wootabs_get_default_tabs_order(){

	$plugin_slug = 'woocommerce-wootabs';

	$defaultTabsOrder = array(
		'description' => array(
			'order'	=> 100,
			'title'	=> __( "Description",$plugin_slug ),
		),
		'addinfo' => array(
			'order'	=> 200,
			'title'	=> __( "Additional Information",$plugin_slug ),
		),
		'reviews' => array(
			'order'	=> 300,
			'title'	=> __( "Reviews",$plugin_slug ),
		),
		'wootabs-global' => array(
			'order'	=> 400,
			'title'	=> __( "Global Wootabs",$plugin_slug ),
		),
		'wootabs-products' => array(
			'order'	=> 500,
			'title'	=> __( "Product Wootabs",$plugin_slug ),
		)
	);

	$wootabs_before_defaults = get_option('wootabs-before_default_tabs') ? get_option('wootabs-before_default_tabs') : false;

	$wootabs_global_tabs_before_products_tabs = get_option('wootabs-global-tabs-position') ? get_option('wootabs-global-tabs-position') : false;

	if( $wootabs_before_defaults !== false || $wootabs_global_tabs_before_products_tabs != false ){

		if( $wootabs_before_defaults == 'no' ){

			$defaultTabsOrder['wootabs-products']['order'] = 500;
		}
		else{

			$defaultTabsOrder['wootabs-products']['order'] = 50;
		}

		if( $wootabs_global_tabs_before_products_tabs == 'begin' ){

			$defaultTabsOrder['wootabs-global']['order'] = $defaultTabsOrder['wootabs-products']['order'] - 10;
		}
		else{

			$defaultTabsOrder['wootabs-global']['order'] = $defaultTabsOrder['wootabs-products']['order'] + 10;
		}

		// Used in previous plugin version [1.2.3]
		delete_option( 'wootabs-before_default_tabs' );
		delete_option( 'wootabs-global-tabs-position' );
	}

	return $defaultTabsOrder;
}

function wootabs_get_tabs_order(){

	$defaultTabsOrder = wootabs_get_default_tabs_order();

	$wootabs_savedTabsOrder = get_option('wootabs-tabs-order') ? trim(get_option('wootabs-tabs-order')) : false;

	if( $wootabs_savedTabsOrder != false ){

		$savedOrders = json_decode( $wootabs_savedTabsOrder );

		foreach( $savedOrders as $a => $b ){

			if( strpos($b, 'description') === 0 ){

				$defaultTabsOrder['description']['order'] = $a * 100;
			}
			elseif( strpos($b, 'addinfo') === 0 ){

				$defaultTabsOrder['addinfo']['order'] = $a * 100;
			}
			elseif( strpos($b, 'reviews') === 0 ){

				$defaultTabsOrder['reviews']['order'] = $a * 100;
			}
			elseif( strpos($b, 'wootabs-global') === 0 ){

				$defaultTabsOrder['wootabs-global']['order'] = $a * 100;
			}
			elseif( strpos($b, 'wootabs-products') === 0 ){

				$defaultTabsOrder['wootabs-products']['order'] = $a * 100;
			}
		}

	}

	$ret = wootabs_sort_tabs_byOrderNum( $defaultTabsOrder );

	return $ret;
}

if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-woocommerce-wootabs-admin.php' );

	add_action( 'plugins_loaded', 'on_woocommerce_wootabs_loaded' );

	$update_plugin = new wootabs_update_plugin;
	$update_plugin->woocommerce_wootabs_init_auto_update();
	add_action( 'init', array( $update_plugin, 'upgrade_remove_encryption' ) );
}

function wootabs_is_encrypted( $string ) {

	if( is_array( $string ) ) return false;

	$decoded = base64_decode( $string, true );
    // Check if there is no invalid character in strin
    if( !preg_match( '/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $string ) ) return false;

    // Decode the string in strict mode and send the responce
     if( !base64_decode( $string, true ) ) return false;

    // Encode and compare it to origional one
    if( base64_encode( $decoded ) != $string ) return false;

    return true;
}
