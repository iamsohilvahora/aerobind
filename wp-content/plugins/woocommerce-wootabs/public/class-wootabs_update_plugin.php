<?php
/**
 * Woocommerce WooTabs Admin.
 *
 * @package   Woocommerce_Wootabs_Admin
 * @author    Makis Mourelatos <info@woomatic.com>
 * @license   GPL-2.0+
 * @link      http://woomatic.com
 * @copyright 2015 WooMatic.com
 */

class wootabs_update_plugin{

	protected static $instance = null;

	protected $plugin_slug = 'woocommerce-wootabs';

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
	 * Execute auto update hooks and actions
	 *
	 * @since     1.0.0
	 */
	public function woocommerce_wootabs_init_auto_update(){

		add_filter('pre_set_site_transient_update_plugins', array( $this, 'woocommerce_wootabs_update_check' ) );
		add_filter( 'plugins_api', array( $this, 'woocommerce_wootabs_plugin_info' ), 10, 3 );
		add_action( 'in_plugin_update_message-' . $this->plugin_slug . '/' . $this->plugin_slug .'.php', array( $this, 'woocommerce_wootabs_update_message' ), 10, 2 );
	}

	/**
	 * WP Filter: Check plugin new version existance
	 *
	 * @since     1.0.0
	 */
	public function woocommerce_wootabs_update_check($transient){

		if( empty( $transient->checked ) ){

			return $transient;
		}

		$plugin_slug = $this->plugin_slug;
		$envato_username 		= get_option('wootabs_envato_user_name') ? trim(get_option('wootabs_envato_user_name')) : "";
		$envato_secret_api_key 	= get_option('wootabs_user_envato_api_key') ? trim(get_option('wootabs_user_envato_api_key')) : "";
		$product_purchase_code 	= get_option('wootabs_product_license_key') ? trim(get_option('wootabs_product_license_key')) : "";
		$site_url 				= get_bloginfo( 'url' );
		$verified_code 		= get_option('wootabs_product_verified_code') ? trim(get_option('wootabs_product_verified_code')) : '';

		$args = array(
			'action' 		=> 'update-check',
			'plugin_name' 	=> $this->plugin_slug,
			'env_username'	=> $envato_username,
			'env_key'		=> $envato_secret_api_key,
			'license_code' 	=> $product_purchase_code,
			'site'			=> $site_url,
			'verified_code' => $verified_code,
			'version' 		=> $transient->checked[$plugin_slug . '/' . $plugin_slug .'.php']
		);

		$response = self::woocommerce_wootabs_call_service_api( $args );

		if( false !== $response ) {

			$transient->response[$plugin_slug . '/' . $plugin_slug .'.php'] = $response;
		}

		return $transient;
	}

	/**
	 * WP Filter: Retrieve plugin info
	 *
	 * @since     1.0.0
	 */
	public function woocommerce_wootabs_plugin_info( $false, $action, $args ) {

		$plugin_slug = $this->plugin_slug;

		if( $args->slug != $plugin_slug ) {

			return false;
		}

		$plugin_slug = $this->plugin_slug;
		$envato_username 		= get_option('wootabs_envato_user_name') ? trim(get_option('wootabs_envato_user_name')) : "";
		$envato_secret_api_key 	= get_option('wootabs_user_envato_api_key') ? trim(get_option('wootabs_user_envato_api_key')) : "";
		$product_purchase_code 	= get_option('wootabs_product_license_key') ? trim(get_option('wootabs_product_license_key')) : "";
		$site_url 				= get_bloginfo( 'url' );
		$verified_code 			= get_option('wootabs_product_verified_code') ? trim(get_option('wootabs_product_verified_code')) : '';

		$args = array(
			'action' => 'plugin_information',
			'plugin_name' 	=> $this->plugin_slug,
			'env_username'	=> $envato_username,
			'env_key'		=> $envato_secret_api_key,
			'license_code' 	=> $product_purchase_code,
			'site'			=> $site_url,
			'verified_code' => $verified_code
		);

		$response = self::woocommerce_wootabs_call_service_api( $args );

		return $response;
	}

	/**
	 * WP Action: Edit plugin update message
	 *
	 * @since     1.0.0
	 */
	public function woocommerce_wootabs_update_message( $plugin_data, $r ){

		if( !$r->package ){

			$codecanyon_update_url = esc_url( $r->url );

			$enable_auto_update_url = admin_url( 'admin.php?page=' . $this->plugin_slug . '&tab=wootabs_product_license' );

			echo '<style type="text/css" media="all">tr#wootabs.update + tr.plugin-update-tr td.plugin-update .update-message em{ display:none; }</style>';

			printf( __('%s Download new version from CodeCanyon %s or %s enable auto update.', $this->plugin_slug ),
				'<a href="' . $codecanyon_update_url . '" title="" target="_blank">',
				'</a>',
				'<a href="' . $enable_auto_update_url .'" title="">',
				'</a>'
				);
		}
	}

	/**
	 * Execute plugin service call
	 *
	 * @since     1.0.0
	 */
	public static function woocommerce_wootabs_call_service_api( $args ) {

		$request = wp_remote_post( WOOTABS_UPDATE_API_URL, array( 'body' => $args ) );

		if( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {

			return false;
		}

		$response = wp_remote_retrieve_body( $request );

		if( $response ){

			$response = unserialize( $response );

			if( is_object( $response ) ) {

				return $response;
			}
			else {

				return false;
			}
		}
		else {

			return false;
		}
	}

	public function upgrade_remove_encryption() {
		if( !get_option( 'wootabs_encrypt_removed' ) ) {

			$global = get_option( 'wootabs-global-tabs' );
			$global = unserialize( base64_decode( $global ) );
			update_option( 'wootabs-global-tabs', $global );

			$args = array(
				'post_type' => 'product',
				'post_status' => 'any',
				'meta_query' => array(
					array(
						'key' => 'wootabs-product-tabs',
						'compare' => 'EXISTS',
					),
				),
			);
			$query = new WP_Query( $args );
			foreach( $query->posts as $post ) {
				$local  = get_post_meta( $post->ID, 'wootabs-product-tabs', true );
				$local = unserialize( base64_decode( $local ) );
				update_post_meta( $post->ID, 'wootabs-product-tabs', $local );
			}

			update_option( 'wootabs_encrypt_removed', 1 );

		}
	}
}
