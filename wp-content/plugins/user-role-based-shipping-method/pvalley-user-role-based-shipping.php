<?php
/**
 * Plugin Name: User role based shipping methods
 * Plugin URI: 
 * Description: It allows you to hide WooCommerce Shipping Methods based on user role and the destination Country.
 * Version: 2.0.1
 * Author: PluginValley
 * Author URI: 
 * Text Domain: pvalley-user-role-based-shipping
 * Requires PHP: 7.0
 * Requires at least: 5.0
 * WC requires at least: 3.0.0
 * WC tested up to: 6.5.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Define PVALLEY_URBS_PLUGIN_FILE.
 */
if ( ! defined( 'PVALLEY_URBS_PLUGIN_FILE' ) ) {
	define( 'PVALLEY_URBS_PLUGIN_FILE', __FILE__ );
}

/**
 * Dropdown class.
 */
class_exists(PvalleyDropdownOptions::class) || require_once "includes/common/PvalleyDropdownOptions.php";

/**
 * Different action on shipping methods.
 */
class_exists(PvalleyURBSActionOnShippingMethodType::class) || require_once "includes/PvalleyURBSActionOnShippingMethodType.php";

/**
 * Include common class
 */
class_exists('Pvalley_User_Role_Based_Shipping_Common') || require_once 'includes/class-pvalley-user-role-based-shipping-common.php';

/**
 *Include the main pvalley-user-role-based-shipping class
 */
class_exists( 'Pvalley_User_Role_Based_Shipping' ) || include_once dirname( __FILE__ ) . '/includes/class-pvalley-user-role-based-shipping.php';