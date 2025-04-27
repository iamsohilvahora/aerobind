<?php

defined('ABSPATH') || exit;		// Exit if accessed directly

if( ! class_exists('Pvalley_User_Role_Based_Shipping_Manage_Shipping') ) {
	/**
	 * Class to manage shipping on Cart and Checkout Page.
	 */
	final class Pvalley_User_Role_Based_Shipping_Manage_Shipping {

		/**
		 * General Settings.
		 */
		private static $settings;
		/**
		 * Rule Matrix.
		 */
		private static $rule_matrix;

		/**
		 * Constructor.
		 */
		public function __construct() {
			! empty(self::$settings) || self::$settings = Pvalley_User_Role_Based_Shipping_Common::get_plugin_settings();
			! empty(self::$rule_matrix) || self::$rule_matrix = get_option('pvalley_user_role_based_shipping_rule_matrix');
			$this->shipping_based_on = ! empty(self::$settings['general_settings_shipping_based_on']) ? self::$settings['general_settings_shipping_based_on'] : array();
			add_filter( 'woocommerce_package_rates', array( $this, 'manage_shipping_methods'), 10, 2 );
		}

		/**
		 * Manage Shipping methods on Cart and Checkout Page.
		 */
		public function manage_shipping_methods( $shipping_rates, $package) {
			$this->current_user = wp_get_current_user();
			$rules = $this->filter_rule( $this->current_user, $package );
			if( ! empty($rules) ) {
				$rule = current($rules);		// Consider first rule only.
				$shipping_method_to_hide = array_map( 'trim', explode( ';', $rule['shipping_methods']) );
				// Required for compatibility upto  1.0.5, By default rule method applies on instance id, before 1.0.6 there was no actionOnShippingMethod
				$actionOnShippingMethod = array_key_exists("actionOnShippingMethod", $rule) ? $rule["actionOnShippingMethod"]: PvalleyURBSActionOnShippingMethodType::METHOD_ID;
				foreach( $shipping_rates as $key => $shipping_rate ) {
					$shipping_method_id = $shipping_rate->get_id();
					if(
						($actionOnShippingMethod == PvalleyURBSActionOnShippingMethodType::METHOD_ID && in_array($shipping_rate->get_id(), $shipping_method_to_hide))
						|| ($actionOnShippingMethod == PvalleyURBSActionOnShippingMethodType::LABEL && in_array($shipping_rate->get_label(), $shipping_method_to_hide))
					) {
						unset($shipping_rates[$key]);
					}
				}
			}
			return $shipping_rates;
		}

		/**
		 * Filter Rules
		 */
		public function filter_rule( $current_user, $package ) {
			$current_user_role 	= ( array ) $current_user->roles;
			if( empty($current_user_role) ) {
				$current_user_role = array( Pvalley_User_Role_Based_Shipping_Common::GUEST_ROLE_KEY );
			}
			$shipping_country 	= $package['destination']['country'];
			$filtered_rules = array();
			foreach( self::$rule_matrix as $rule ) {
				$common_roles = array_intersect($current_user_role, $rule['user_roles']);
				// If Rule matched based on user role
				if( ! empty($common_roles) ) {
					if( ! empty($this->shipping_based_on) ) {
						foreach( $this->shipping_based_on as $shipping_based_on ) {
							switch( $shipping_based_on ) {
								case 'country' :
										if( empty($rule['countries']) || in_array( $shipping_country, $rule['countries']) ) {
											$filtered_rules[] = $rule;
										}
										break;
							}
						}
					}
					else{
						$filtered_rules[] = $rule;
					}

				}
			}
			return $filtered_rules;
		}
	}
}