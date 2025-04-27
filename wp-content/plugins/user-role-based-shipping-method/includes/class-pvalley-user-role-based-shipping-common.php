<?php

defined('ABSPATH') || exit;		// Exit if accessed directly

if( ! class_exists('Pvalley_User_Role_Based_Shipping_Common') ) {

	class Pvalley_User_Role_Based_Shipping_Common {

		/**
		 * Guest role name.
		 */
		const GUEST_ROLE_NAME = "Guest";
		/**
		 * Guest role key to save in database, array key.
		 */
		const GUEST_ROLE_KEY = "guest";
		/**
		 * Array of active plugins.
		 */
		private static $active_plugins;

		/**
		 * Plugin settings.
		 */
		private static $settings;

		/**
		 * Initialize the active plugins.
		 */
		public static function init() {

			self::$active_plugins = (array) get_option( 'active_plugins', array() );

			if ( is_multisite() )
				self::$active_plugins = array_merge( self::$active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}

		/**
		 * Check whether woocommerce is active or not.
		 * @return boolean True if woocommerce is active else false.
		 */
		public static function woocommerce_active_check() {

			if ( ! self::$active_plugins ) self::init();

			return in_array( 'woocommerce/woocommerce.php', self::$active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', self::$active_plugins );
		}
		
		/**
		 * Get Plugin settings.
		 */
		public static function get_plugin_settings(){
			! empty(self::$settings) || self::$settings = get_option('pvalley_user_role_based_shipping', array());
			return self::$settings;
		}

	}

}