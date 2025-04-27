<?php

defined( 'ABSPATH' ) || exit;

if( ! class_exists('Pvalley_User_Role_Based_Shipping') ) {

	/**
	 * Main User Role Based Shipping Class.
	 * @class Pvalley_User_Role_Based_Shipping
	 */
	final class Pvalley_User_Role_Based_Shipping {
		
		/**
		 * Constructor
		 */
		public function __construct(){
			register_activation_hook( PVALLEY_URBS_PLUGIN_FILE, __CLASS__.'::activation_check' );
			// Load translations hook.
			add_action( 'init', array( $this, 'load_translation' ) );
			$this->init();
			add_filter( 'plugin_action_links_' . plugin_basename( PVALLEY_URBS_PLUGIN_FILE ), __CLASS__. '::plugin_action_links' );
		}

		/**
		 * Check on Activate Plugin.
		 */
		public static function activation_check() {
			$woocommerce_plugin_status = Pvalley_User_Role_Based_Shipping_Common::woocommerce_active_check();	// True if woocommerce is active.
			if( $woocommerce_plugin_status === false ) {
				deactivate_plugins( basename( PVALLEY_URBS_PLUGIN_FILE ) );
				wp_die( __("Oops! You tried installing the plugin to manage woocommerce shipping rates without activating woocommerce. Please install and activate woocommerce and then try again .", "pvalley-user-role-based-shipping" ), "", array('back_link' => 1 ));
			}
		}

		/**
		 * Load textdomain.
		 */
		public function load_translation() {
			load_plugin_textdomain( 'pvalley-user-role-based-shipping', false, dirname( plugin_basename( PVALLEY_URBS_PLUGIN_FILE ) ) . '/languages/' );
		}

		/**
		 * Initialize.
		 */
		public function init(){
			$this->settings = Pvalley_User_Role_Based_Shipping_Common::get_plugin_settings();
			$this->enable 	= ( ! empty($this->settings['general_settings_enable']) && $this->settings['general_settings_enable'] == 'yes' ) ? true : false;
			class_exists( 'Pvalley_User_Role_Based_Shipping_Settings') || include_once 'class-pvalley-user-role-based-shipping-settings.php';
			if( ! is_admin() && $this->enable ) {
				class_exists( 'Pvalley_User_Role_Based_Shipping_Manage_Shipping') || include_once 'class-pvalley-user-role-based-shipping-manage-shipping.php';
				new Pvalley_User_Role_Based_Shipping_Manage_Shipping();
			}
		}

		/**
		 * Plugin action link.
		 */
		public static function plugin_action_links( $links ) {
			$plugin_links = array(
				'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=pvalley_role_based_shipping' ) . '">' . __( 'Settings', 'pvalley-user-role-based-shipping' )
			);
			return array_merge( $plugin_links, $links );
		}

	}
}

new Pvalley_User_Role_Based_Shipping();