<?php
/**
 * @package   Woocommerce_Wootabs
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if( get_option('wootabs-remove-data-on-uninstall') && get_option('wootabs-remove-data-on-uninstall') == "on" ){
	
	global $wpdb;

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	$wpdb->query("DELETE FROM " . $wpdb->postmeta . " WHERE meta_key LIKE 'wootabs-%'");

	delete_option("wootabs-remove-data-on-uninstall");
	delete_option("wootabs-enable-shop-manager-settings");
	delete_option("wootabs-use-global-tabs");
	delete_option("wootabs-global-tabs");
	delete_option("wootabs-tabs-order");

	// Clean verification data & deactivate plugin if activated

	$verified_product = get_option('wootabs_product_verified_code') ? trim(get_option('wootabs_product_verified_code')) : '';

	if( !$verified_product || $verified_product == '' ){

		$envato_username 		= get_option('wootabs_envato_user_name') ? trim(get_option('wootabs_envato_user_name')) : "";
		$envato_secret_api_key 	= get_option('wootabs_user_envato_api_key') ? trim(get_option('wootabs_user_envato_api_key')) : "";
		$product_purchase_code 	= get_option('wootabs_product_license_key') ? trim(get_option('wootabs_product_license_key')) : "";
		$site_url 				= get_bloginfo( 'url' );
		$verified_code 			= get_option('wootabs_product_verified_code') ? trim(get_option('wootabs_product_verified_code')) : "";

		if( $envato_username != "" && $envato_secret_api_key != "" && $product_purchase_code != "" && $verified_code != "" ){
			
			$args = array(
				'action' 		=> 'deactivate-license',
				'plugin_name' 	=> $this->plugin_slug,
				'env_username'	=> $envato_username,
				'env_key'		=> $envato_secret_api_key,
				'license_code' 	=> $product_purchase_code,
				'site'			=> $site_url,
				'verified_code' => $verified_code
			);

			if( version_compare(phpversion(), '5.3.0') >= 0 ){

				$t = wootabs_update_plugin::get_instance();

				$response = $t::woocommerce_wootabs_call_service_api( $args );
			}
			else{

				$t = new wootabs_update_plugin;			

				$response = $t->woocommerce_wootabs_call_service_api( $args );
			}
			
		}
	}

	delete_option("wootabs_envato_user_name");
	delete_option("wootabs_user_envato_api_key");
	delete_option("wootabs_product_license_key");
	delete_option("wootabs_product_verified_code");
}