<?php
/**
 * Plugin Name.
 *
 * @package   Woocommerce_Wootabs
 * @author    Mohsin Zunzunia <support@wpcream.com>
 * @license   GPL-2.0+
 * @link      http://wpcream.com
 * @copyright 2014 WPCream.com
 */

class Woocommerce_Wootabs {	

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *ταινιεσ ονλιν
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	const VERSION = '2.1.8';

	/**
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'woocommerce-wootabs';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		if ( current_user_can( 'manage_options' ) || current_user_can( 'manage_woocommerce' ) ){

			add_action( 'save_post', array( $this, 'wootabs_on_product_save' ), 999 );

			add_action( 'wp_ajax_wootabs_save_global_tabs', array( $this, 'wootabs_save_global_tabs' ) );
			add_action( 'wp_ajax_nopriv_wootabs_save_global_tabs', array( $this, 'wootabs_save_global_tabs' ) );
			
			add_action( 'wp_ajax_wootabs_save_product_tabs', array( $this, 'wootabs_save_product_tabs' ) );
			add_action( 'wp_ajax_nopriv_wootabs_save_product_tabs', array( $this, 'wootabs_save_product_tabs' ) );

			add_action( 'wp_ajax_wootabs_activate_license', array( $this, 'wootabs_activate_license' ) );
			add_action( 'wp_ajax_nopriv_wootabs_activate_license', array( $this, 'wootabs_activate_license' ) );

			add_action( 'wp_ajax_wootabs_deactivate_license', array( $this, 'wootabs_deactivate_license' ) );
			add_action( 'wp_ajax_nopriv_wootabs_deactivate_license', array( $this, 'wootabs_deactivate_license' ) );

			add_action( 'wp_ajax_wootabs_get_new_tab_asynch', 'wootabs_wp_editor::editor_html' );
			add_action( 'wp_ajax_nopriv_wootabs_get_new_tab_asynch', 'wootabs_wp_editor::editor_html' );

			add_filter( 'tiny_mce_before_init', 'wootabs_wp_editor::tiny_mce_before_init', 10, 2 );
			add_filter( 'quicktags_settings', 'wootabs_wp_editor::quicktags_settings', 10, 2 );
		}

		add_filter( 'woocommerce_product_tabs', array( $this, 'wootabs_product_front_tabs' ) );
	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {
		
		set_site_transient( 'update_plugins', '' );
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {

	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {

		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {

			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();
				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );
	
	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/*New RTL Check*/
		if ( is_rtl() ) {

						wp_enqueue_style( $this->plugin_slug . '-plugin-styles', WOOTABS_URI . 'public/assets/css/public-rtl.css', array(), self::VERSION );
		}
		else {

						wp_enqueue_style( $this->plugin_slug . '-plugin-styles', WOOTABS_URI . 'public/assets/css/public.css', array(), self::VERSION );
		}

		/* Deprecated RTL Check

		if( is_product() ){

			if ( get_bloginfo( 'text_direction' ) == 'ltr' ) {
			
				wp_enqueue_style( $this->plugin_slug . '-plugin-styles', WOOTABS_URI . 'public/assets/css/public.css', array(), self::VERSION );
		}
			else{
				wp_enqueue_style( $this->plugin_slug . '-plugin-styles', WOOTABS_URI . 'public/assets/css/public-rtl.css', array(), self::VERSION );
			}

		}

		*/
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		if( is_product() ){

			wp_enqueue_script( $this->plugin_slug . '-plugin-script', WOOTABS_URI . 'public/assets/js/public.js', array( 'jquery' ), self::VERSION );

		}
	}

	/**
	 * Register and enqueue admin-facing style sheet.
	 *
	 * @since    1.1.4
	 */
	public function admin_enqueue_styles() {
		
		if ( current_user_can( 'manage_options' ) || current_user_can( 'manage_woocommerce' ) ){

			if( strstr($_SERVER['REQUEST_URI'], 'wp-admin/post.php') || strstr($_SERVER['REQUEST_URI'], 'wp-admin/post-new.php?post_type=product') ) {

				/* Deprecated RTL Check

				if ( get_bloginfo( 'text_direction' ) == 'ltr' ) {

					wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( $this->plugin_slug . '/admin/assets/css/admin.css' ), array(), Woocommerce_Wootabs::VERSION );
				}
				else{

					wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( $this->plugin_slug . '/admin/assets/css/admin-rtl.css' ), array(), Woocommerce_Wootabs::VERSION );
				
				}
				*/
			/*New RTL Check*/
			if ( is_rtl() ) {

						wp_enqueue_style( $this->plugin_slug .'-admin-styles', WOOTABS_URI . 'admin/assets/css/admin-rtl.css', array(), Woocommerce_Wootabs::VERSION );
			}
			else {
						wp_enqueue_style( $this->plugin_slug .'-admin-styles', WOOTABS_URI . 'admin/assets/css/admin.css', array(), Woocommerce_Wootabs::VERSION );
			}


			}
		}
	}

	/**
	 * Register and enqueues admin-facing JavaScript files.
	 *
	 * @since    1.1.4
	 */
	public function admin_enqueue_scripts() {
		
		if ( current_user_can( 'manage_options' ) || current_user_can( 'manage_woocommerce' ) ){

			if( strstr($_SERVER['REQUEST_URI'], 'wp-admin/post.php') || strstr($_SERVER['REQUEST_URI'], 'wp-admin/post-new.php?post_type=product') ) {

				wp_enqueue_script( $this->plugin_slug . '-admin-script', WOOTABS_URI . 'admin/assets/js/admin.js', array( 'jquery' ), Woocommerce_Wootabs::VERSION );
				
				//wp_enqueue_script( 'wootabs-jquery-ui', WOOTABS_URI . 'admin/assets/js/jquery-ui-custom-1.11.2.min.js', array( 'jquery' ), Woocommerce_Wootabs::VERSION );
				
				wp_enqueue_script( 'jquery-ui' );
				
				$admin_jsobject = array(
									'remove_tab_confirm_msg'	=>	__( 'Are you sure you want to remove this tab?', $this->plugin_slug ),
									'ajax_url' 	=> admin_url( 'admin-ajax.php' ),
									'texts'		=> array(
														'remove' => __( 'Remove', $this->plugin_slug ),
														'enabled' => __( 'Enabled', $this->plugin_slug ),
														'disabled' => __( 'Disabled', $this->plugin_slug ),
														'tab_title'	=> __( 'Tab title', $this->plugin_slug ),
														'global_tab_no'	=> __( 'Global tab no.', $this->plugin_slug ),
														'product_tab_no' => __( 'Tab no.', $this->plugin_slug ),
														'unsaved_tabs_msg' => __( 'You have un-saved tabs.', $this->plugin_slug )
													)
								);

				wp_localize_script( $this->plugin_slug . '-admin-script', 'wtAdminJsObj', $admin_jsobject );
				
			}
		}
	}

	/**
	 * Hook - Add WooTabs (tabs) in products pages
	 *
	 * @since    1.0.0
	 */
	public function wootabs_product_front_tabs( $tabs ){
		
		global $post;
		
		$verified_product = get_option('wootabs_product_verified_code') ? trim(get_option('wootabs_product_verified_code')) : '';

		if( !$verified_product || $verified_product == '' ) {

			$tabs[ 'wootab_non_verified' ] = array(
				'title' 	=> WOOTABS_DEACTIVATED_TAB_TITLE,
				'priority' 	=> 1000,
				'callback' 	=> array( $this, 'woo_new_product_tab_content' ),
				'content'	=> WOOTABS_DEACTIVATED_TAB_CONTENT
			);
		} else {

			$wootabs_use_global_tabs = get_option('wootabs-use-global-tabs') && get_option('wootabs-use-global-tabs') == "on" ? true : false;
			
			/*if( is_user_logged_in() || current_user_can('manage_options') ){
				
				$wootabs_use_global_tabs = true;
			}*/
			
			$wootabs_pop_global_tabs_temp = $wootabs_use_global_tabs && get_option('wootabs-global-tabs') ? get_option('wootabs-global-tabs') : false;
			$wootabs_pop_global_tabs_temp = wootabs_is_encrypted( $wootabs_pop_global_tabs_temp ) ? unserialize( base64_decode( $wootabs_pop_global_tabs_temp ) ) : $wootabs_pop_global_tabs_temp;
			
			$wootabs_pop_global_tabs = array();
			$wootabs_pop_global_tabs_ids = array();

			if( $wootabs_pop_global_tabs_temp ){

				foreach( $wootabs_pop_global_tabs_temp as $x => $y ){
					
					if( intval( $y['prod_pop'] ) == 1 && intval( $y['enabled'] ) == 1 ){

						$wootabs_pop_global_tabs[] = $y;
						$wootabs_pop_global_tabs_ids[] = $y['gtrid'];
					}
				}
			}
			
			$wootabs_pop_global_tabs = apply_filters( 'wootabs_pop_global_tabs', $wootabs_pop_global_tabs );
			$wootabs_pop_global_tabs_ids = apply_filters( 'wootabs_pop_global_tabs_ids', $wootabs_pop_global_tabs_ids, $wootabs_pop_global_tabs );

			$post_categories_obj = wp_get_post_terms( $post->ID, 'product_cat' );
			
			$post_categories = array();

			foreach ( $post_categories_obj as $key => $value ) {
				
				$post_categories[] = $value->term_id;
			}

			$wootabs_global_tabs = get_option('wootabs-global-tabs') ? get_option('wootabs-global-tabs') : false;
			$wootabs_global_tabs = wootabs_is_encrypted( $wootabs_global_tabs ) ? unserialize( base64_decode( $wootabs_global_tabs ) ) : $wootabs_global_tabs;

			$product_additional_tabs = get_post_meta( $post->ID, 'wootabs-product-tabs' );

			if( $product_additional_tabs && isset( $product_additional_tabs[0]) ) {
				$product_additional_tabs = wootabs_is_encrypted( $product_additional_tabs[0] ) ? unserialize( base64_decode( $product_additional_tabs[0] ) ) : $product_additional_tabs[0];
			}

			$orderedTabs = wootabs_get_tabs_order();

			foreach( $orderedTabs as $k => $v ) {
				
				if( strpos( $k, 'description' ) === 0 ) {
					if( isset( $tabs['description']) ) {
						$tabs['description']['priority'] = $v['order'];
					}
				} elseif( strpos($k, 'addinfo' ) === 0 ) {
					if( isset( $tabs['additional_information'] ) ){
						$tabs['additional_information']['priority'] = $v['order'];
					}
				} elseif( strpos($k, 'reviews' ) === 0 ) {
					if( isset( $tabs['reviews'] ) ){
						$tabs['reviews']['priority'] = $v['order'];
					}
				} elseif( strpos($k, 'wootabs-global' ) === 0 ) {
					if( $wootabs_use_global_tabs && $wootabs_global_tabs ) {
						$cnt = 0;
						foreach ( $wootabs_global_tabs as $key => $value ) {
							$pass_productsPop = intval( $value['prod_pop'] ) == 1 ? 0 : 1;
							if( $pass_productsPop ){
								$pass_lang = true;
								if( has_filter( 'wootabs_check_global_tabs_lang' ) && isset( $value['lang'] ) && trim( $value['lang'] ) != 'all' ) {
									$pass_lang = apply_filters('wootabs_check_global_tabs_lang', trim( $value['lang'] ), $pass_lang );
								}
								if( $pass_lang && intval( $value['enabled'] ) ) {
									$display_tab = false;
									$selected_categories = explode( ',', $value['categories'] );
									if( in_array( 'all', $selected_categories ) || ( count( $selected_categories ) == 1 && $selected_categories[0] == '' ) ) {
										$display_tab = true;
									} else {
										foreach ( $selected_categories as $k2 => $v2) {			
											if( $display_tab == false ){
												if( in_array( intval( $v2 ), $post_categories ) ) {
													$display_tab = true;
												}

											}
										}
									}
									if( $display_tab ) {
										$tabs[ 'wootab_' . ($v['order'] + $cnt) ] = array(
											'title' 	=> $value['title'],
											'priority' 	=> $v['order'] + $cnt,
											'callback' 	=> array( $this, 'woo_new_product_tab_content' ),
											'content'	=> $value['content'],
											'categories'=> $selected_categories
										);
										$cnt++;
									}
								}
							}
						}
					}
				} elseif( strpos( $k, 'wootabs-products' ) === 0 ) {
					
					$passed_pop_gtrid = array();
					$cnt = 0;

					if( !empty( $product_additional_tabs ) ) {

						foreach ( $product_additional_tabs as $key => $value) {
							
							$enabled_tab = intval($value['enabled']);
							$the_tile = $value['title'];
							$pop_gtrid = $value['pop_gtrid'] ? $value['pop_gtrid'] : '';
							if( !$pop_gtrid || in_array( $pop_gtrid, $wootabs_pop_global_tabs_ids ) ) {
								
								$display_tab = true;

								if( $pop_gtrid != '' ) {

									$display_tab = false;

									$passed_pop_gtrid[] = $pop_gtrid;

									foreach ($wootabs_pop_global_tabs_temp as $kl => $vl) {
										
										if( $pop_gtrid == $vl['gtrid'] ){
											
											$the_tile = $vl['title'];

											$selected_categories = explode( ',', $vl['categories'] );

											if( in_array( 'all', $selected_categories ) || ( count( $selected_categories ) == 1 && $selected_categories[0] == '' ) ) {
												$display_tab = true;
											} else {
												foreach ( $selected_categories as $k2 => $v2) {
													if( $display_tab == false ) {
														if( in_array( intval( $v2 ), $post_categories ) ){
															$display_tab = true;
														}
													}
												}
											}
										}
									}
								}							
								if( $display_tab && $enabled_tab ) {

									$tabs[ 'wootab_' . ( $v['order'] + $cnt ) ] = array(
										'title' 	=> $the_tile,
										'priority' 	=> $v['order'] + $cnt,
										'callback' 	=> array( $this, 'woo_new_product_tab_content' ),
										'content'	=> $value['content']
									);
									$cnt++;
								}
							}
						}
					}

					if( !empty( $wootabs_pop_global_tabs ) ) {

						foreach( $wootabs_pop_global_tabs as $g => $h ) {

							if( !in_array( $h['gtrid'], $passed_pop_gtrid ) ) {
								$enabled_tab = intval( $h['enabled'] );
								if( $enabled_tab ){
									$pass_lang = true;
									if( has_filter( 'wootabs_check_global_tabs_lang' ) && isset( $h['lang'] ) && trim( $h['lang'] ) != 'all' ) {
										$pass_lang = apply_filters('wootabs_check_global_tabs_lang', trim( $h['lang'] ), $pass_lang );
									}
									if( $pass_lang ) {
										$display_tab = false;
										$selected_categories = explode( ',', $h['categories'] );
										if( in_array( 'all', $selected_categories ) || ( count( $selected_categories ) == 1 && $selected_categories[0] == '' ) ) {
											$display_tab = true;
										} else {
											foreach ( $selected_categories as $k2 => $v2 ) {
												if( $display_tab == false ){
													if( in_array( intval( $v2 ), $post_categories ) ){
														$display_tab = true;
													}
												}
											}
										}	
									}
									
									if( $display_tab ){
										$tabs[ 'wootab_' . ($v['order'] + $cnt) ] = array(
											'title' 	=> $h['title'],
											'priority' 	=> $v['order'] + $cnt,
											'callback' 	=> array( $this, 'woo_new_product_tab_content' ),
											'content'	=> $h['content']
										);
										$cnt++;
									}
								}
							}
						}
					}
				}
			}

		}
		return $tabs;
	}

	/**
	 * Display front-end WooTabs content.
	 *
	 * @since     1.0.0
	 */
	public function woo_new_product_tab_content( $key, $tab ) {

		$content = apply_filters( 'the_content', $tab['content'] );
		
		$content = htmlentities( $content );

		$content = html_entity_decode( $content );

		print_r($content);
	}

	/**
	 * Save global tabs - Ajax call
	 *
	 * @since     1.0.0
	 */
	public function wootabs_save_global_tabs(){

		if( isset( $_POST['d'] ) ){

			$tabs_data = wp_unslash( $_POST['d'] );

			if( $tabs_data == "no_tabs" ){

				$saveTabs = "";
			}
			else{

				$saveTabs = $tabs_data;
			}

			update_option( 'wootabs-global-tabs', $saveTabs );
			
		}

		die();
	}

	/**
	 * Save single product tabs - Ajax call
	 *
	 * @since     1.0.0
	 */
	public function wootabs_save_product_tabs(){

		$ret = array();
		$ret['new_value'] = '';
		$ret['errors'] = 0;

		if( isset( $_POST['d'] ) && isset( $_POST['id'] ) ){

			$product_id = intval( $_POST['id'] );

			if( $product_id ){
				
				$tabs_data = wp_unslash( $_POST['d'] );

				if( $tabs_data == "no_tabs" ){

					$saveTabs = "";
				}
				else{

					$saveTabs = $tabs_data;
				}

				update_post_meta( $product_id, 'wootabs-product-tabs', $saveTabs );

				$ret['new_value'] = json_encode( get_post_meta( $product_id, 'wootabs-product-tabs', true ) );
				
			}
		}

		die( json_encode($ret) );
	}

	/**
	 * Save product tabs on updating product post - Hook
	 *
	 * @since     1.0.0
	 */
	public function wootabs_on_product_save( $post_id ){
		
		if ( wp_is_post_revision( $post_id ) ){

			return;
		}

		$postType = get_post_type( $post_id );

		if( $postType == 'product' ){

			if( isset( $_POST['wootabs_product_tab_value'] ) ){
				update_post_meta( $post_id, 'wootabs-product-tabs', json_decode( wp_unslash( $_POST['wootabs_product_tab_value'] ), true ) );
			}
			
		}
	}

	/**
	 * Activate Product License
	 *
	 * @since    1.2.4
	 *
	 * @return    boolean
	 */

	public function wootabs_activate_license(){
		
		$success_msg = __( "License activated successful", $this->plugin_slug );
		$fail_msg = __( "An error occured on license activation. Please try again.", $this->plugin_slug );

		$return = array();
		$return['error'] = 1;
		$return['msg'] = $fail_msg;

		$envato_username 		= get_option('wootabs_envato_user_name') ? trim(get_option('wootabs_envato_user_name')) : "";
		$envato_secret_api_key 	= get_option('wootabs_user_envato_api_key') ? trim(get_option('wootabs_user_envato_api_key')) : "";
		$product_purchase_code 	= get_option('wootabs_product_license_key') ? trim(get_option('wootabs_product_license_key')) : "";
		$site_url 				= get_bloginfo( 'url' );

		if( $envato_username != "" && $envato_secret_api_key != "" && $product_purchase_code != "" ){
			
			$args = array(
				'action' 		=> 'activate-license',
				'plugin_name' 	=> $this->plugin_slug,
				'env_username'	=> $envato_username,
				'env_key'		=> $envato_secret_api_key,
				'license_code' 	=> $product_purchase_code,
				'site'			=> $site_url
			);

			if( version_compare(phpversion(), '5.3.0') >= 0 ){

				$t = wootabs_update_plugin::get_instance();

				$response = $t::woocommerce_wootabs_call_service_api( $args );
			}
			else{

				$t = new wootabs_update_plugin;			

				$response = $t->woocommerce_wootabs_call_service_api( $args );
			}

			if( false !== $response && $response->error == false && $response->verified_code ){

				update_option( 'wootabs_product_verified_code' , $response->verified_code );

				$return['error'] = 0;
				$return['msg'] = $success_msg;
				set_site_transient( 'update_plugins', '' );
				set_site_transient( 'plugin_slugs', '' );
			}
			else{

			}

		}

		$return = json_encode($return);

		print_r( $return );

		die();
	}

	/**
	 * Deactivate Product License
	 *
	 * @since    1.2.4
	 *
	 * @return    boolean
	 */

	public function wootabs_deactivate_license(){

		$success_msg = __( "License deactivated successful", $this->plugin_slug );
		$fail_msg = __( "An error occured on license deactivation. Please try again.", $this->plugin_slug );

		$return = array();
		$return['error'] = 1;
		$return['msg'] = $fail_msg;

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

			if( false !== $response ){
				
				if( $response->error ){

					switch( $response->etype ){
						case 'e1':
						break;
						case 'e2':
						case 'e3':
							update_option( 'wootabs_product_verified_code' , '' );
							set_site_transient( 'update_plugins', '' );
							set_site_transient( 'plugin_slugs', '' );
						break;
					}
				}
				else{

					update_option( 'wootabs_product_verified_code' , '' );
					set_site_transient( 'update_plugins', '' );
					set_site_transient( 'plugin_slugs', '' );

					$return['error'] = 0;
					$return['msg'] = $success_msg;
				}
			}
		}

		$return = json_encode($return);

		print_r( $return );

		die();
	}
}