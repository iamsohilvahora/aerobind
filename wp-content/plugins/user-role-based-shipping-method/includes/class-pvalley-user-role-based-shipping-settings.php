<?php

defined('ABSPATH') || exit;		// Exit if accessed directly.

if( ! class_exists('Pvalley_User_Role_Based_Shipping_Settings') ) {
	class Pvalley_User_Role_Based_Shipping_Settings {
		/**
		 * Setup settings class
		 * @since  1.0
		 */
		public function __construct() {
				
			$this->id    = 'pvalley_role_based_shipping';
			$this->label = __( 'Role Baed Shipping', 'pvalley-user-role-based-shipping' );
					
			add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 50 );
			add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
			add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
			// For sections of settings tab
			add_action( 'woocommerce_sections_' . $this->id,      array( $this, 'output_sections' ) );
			// Add rate matrix html
			add_action( 'woocommerce_admin_field_ph_role_based_rule_matrix', array( $this, 'ph_role_based_rule_matrix' ) );
		}

		/**
		 * Add Setting Tab.
		 */
		public function add_settings_page($settings) {
			$settings[$this->id] = __( 'Role Based Shipping', 'pvalley-user-role-based-shipping');
			return $settings;
		}

		/**
		 * Section of the Settings
		 */
		public function get_sections() {
        
			$sections = array(
				''				=> __( 'General', 'pvalley-user-role-based-shipping' ),
				'rule_matrix' 	=> __( 'Rules', 'pvalley-user-role-based-shipping' )
			);
					
			return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
		}

		public function get_settings( $current_section = '' ){
			switch( $current_section) {
				case 'rule_matrix' :
						$settings = array(
							'rule_matrix_settings_title'	=>	array(
								'name' => __( 'Rule Matrix', 'pvalley-user-role-based-shipping' ),
								'type' => 'title',
								'desc' => '',
							),
							'ph_role_based_rule_matrix'	=>	array(
								'type'	=>	'ph_role_based_rule_matrix'
							),
							'rule_matrix_settings_section_end'	=>	array(
								'type' =>	'sectionend',
							),
						);
						break;
				
				default :
					$settings = array(
						'general_settings_title'	=>	array(
							'name' => __( 'General Settings', 'pvalley-user-role-based-shipping' ),
							'type' => 'title',
							'desc' => '',
							'id'   => 'pvalley_user_role_based_shipping[general_settings_title]',
						),
						'general_settings_enable'	=>	array(
							'name'	=>	__( 'Enable', 'pvalley-user-role-based-shipping' ),
							'type'	=>	'checkbox',
							'id'	=> 'pvalley_user_role_based_shipping[general_settings_enable]'
						),
						'general_settings_shipping_based_on'	=>	array(
							'name'		=>	__( 'Shipping Based On', 'pvalley-user-role-based-shipping' ),
							'type'		=>	'multiselect',
							'class'		=>	'wc-enhanced-select',
							'options'	=> array(
								'country'	=>	__( 'Country', 'pvalley-user-role-based-shipping'),
							),
							'id'	=>	'pvalley_user_role_based_shipping[general_settings_shipping_based_on]'
						),
						'general_settings_section_end'	=>	array(
							'type' =>	'sectionend',
							'id'   =>	'pvalley_user_role_based_shipping[general_settings_section_end]'
						),
					);
					break;
			}
			return $settings;
		}

		/**
		 * Output the settings
		 */
		public function output() {
				
			global $current_section;
			$settings = $this->get_settings( $current_section );
			WC_Admin_Settings::output_fields( $settings );
		}

		/**
		 * Output sections.
		 */
		public function output_sections() {
			global $current_section;
			$sections = $this->get_sections();
			if ( empty( $sections ) || 1 === sizeof( $sections ) ) {
				return;
			}
			echo '<ul class="subsubsub">';
			$array_keys = array_keys( $sections );
			foreach ( $sections as $id => $label ) {
				echo '<li><a href="' . admin_url( 'admin.php?page=wc-settings&tab=' . $this->id . '&section=' . sanitize_title( $id ) ) . '" class="' . ( $current_section == $id ? 'current' : '' ) . '">' . $label . '</a> ' . ( end( $array_keys ) == $id ? '' : '|' ) . ' </li>';
			}
			echo '</ul><br class="clear" />';
		}

		/**
		 * Save settings
		 */
		public function save() {
			
			global $current_section;
			global $current_tab;
			// Save Rule Matrix
			if( $current_section == 'rule_matrix' && $current_tab == 'pvalley_role_based_shipping' ) {
				$data = isset($_POST['pvalley_user_role_based_shipping_rule_matrix']) ? $_POST['pvalley_user_role_based_shipping_rule_matrix'] : array();
				foreach( $data as $key => $rule ) {
					// Check for is_numeric required for vulnerabilities
					if( empty($rule['user_roles']) || ! is_numeric($key)) {
						unset($data[$key]);
						continue;
					}
					
					if( ! isset($rule['countries']) ){
						$data[$key]['countries'] = array();
					}

					// Sanitize the input fields for security
					$data[$key]['user_roles'] = [];
					foreach($rule['user_roles'] as $user_role_val) {
						$data[$key]['user_roles'][] = sanitize_text_field($user_role_val);
					}
					
					$data[$key]['countries'] = [];
					foreach($rule['countries'] as  $country_val) {
						$data[$key]['countries'][] = sanitize_text_field($country_val);
					}

					$data[$key]["shipping_methods"] = sanitize_text_field($data[$key]["shipping_methods"]);
				}
				update_option( 'pvalley_user_role_based_shipping_rule_matrix', $data );
			}
			else {
				$settings = $this->get_settings( $current_section );
				WC_Admin_Settings::save_fields( $settings );
			}
		}

		/**
		 * Display Rule Matrix
		 */
		public function ph_role_based_rule_matrix() {
			require_once 'settings/html-rule-matrix.php';
		}
	}
}
new Pvalley_User_Role_Based_Shipping_Settings();