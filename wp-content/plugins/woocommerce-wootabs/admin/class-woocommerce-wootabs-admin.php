<?php
/**
 * Woocommerce WooTabs Admin.
 *
 * @package   Woocommerce_Wootabs_Admin
 * @author    Makis Mourelatos <info@wpcream.com>
 * @license   GPL-2.0+
 * @link      http://wpream.com
 * @copyright 2015 wpcream.com
 */

class Woocommerce_Wootabs_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Set plugin actions message
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	public $plugin_has_msg = null;

	/**
	 * Set plugin actions error flag
	 *
	 * @since    1.0.0
	 *
	 * @var      boolean
	 */
	public $plugin_error_flag = false;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		/*
		 * Call $plugin_slug from public plugin class.
		 */
		$plugin = Woocommerce_Wootabs::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
		add_filter( 'parent_file', array( $this, 'fix_admin_submenus_active_highlight') );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

		add_action( 'add_meta_boxes', array( $this, 'wootabs_products_meta_boxes' ), 10 );
		add_action( 'wp_loaded', array( $this, 'execute_before_wp_header' ) );
	}

	/**
	 * Retrieve messages info
	 *
	 * @since    1.0.0
	 *
	 */

	public function get_plugin_msg() {

		$msg = $this->plugin_has_msg;
		$error = $this->plugin_error_flag;

		$ret = array();
		$ret["message"] = $msg;
		$ret["error"] = $error;

		return $ret;
	}

	/**
	 * Set messages info
	 *
	 * @since    1.0.0
	 *
	 */

	public function set_plugin_msg( $d, $er ) {

		$this->plugin_has_msg = $d;
		$this->plugin_error_flag = $er;
	}

	/**
	 * Init products meta boxes
	 *
	 * @since    1.0.0
	 *
	 */

	public function wootabs_products_meta_boxes(){

		add_meta_box( 'wootabs_single_product_tabs', __( 'WooTabs', $this->plugin_slug ) , array( $this, 'wootabs_single_product_tabs_callback'), 'product' );
	}

	/**
	 * Return products WooTabs meta box html content
	 *
	 * @since     1.0.0
	 *
	 * @return    html
	 */

	public function wootabs_single_product_tabs_callback(){
			
		global $post;

		$t = get_the_terms( $post->ID, 'product_cat' );

		$productSelected_categories = array();

		if( $t ){
		
			foreach ($t as $key => $value) {
				
				$productSelected_categories[] = $value->term_id;
			}
		}

		$plugin_slug = $this->plugin_slug;

		$wootabs_use_global_tabs = get_option('wootabs-use-global-tabs') && get_option('wootabs-use-global-tabs') == "on" ? true : false;
		$wootabs_pop_global_tabs_temp = $wootabs_use_global_tabs && get_option('wootabs-global-tabs') ? get_option('wootabs-global-tabs') : false;
		
		$wootabs_pop_global_tabs_temp = wootabs_is_encrypted( $wootabs_pop_global_tabs_temp ) ? unserialize( base64_decode( $wootabs_pop_global_tabs_temp ) ) : $wootabs_pop_global_tabs_temp;

		$wootabs_pop_global_tabs = array();
		$wootabs_pop_global_tabs_ids = array();
		
		if( $wootabs_pop_global_tabs_temp ){

			foreach( $wootabs_pop_global_tabs_temp as $x => $y ){
				
				if( intval( $y['prod_pop'] ) == 1 ){
					$wootabs_pop_global_tabs[] = $y;
					$wootabs_pop_global_tabs_ids[] = $y['gtrid'];
				}
			}
		}
		
		
		$wootabs_pop_global_tabs = apply_filters( 'wootabs_pop_global_tabs_admin', $wootabs_pop_global_tabs );
		$wootabs_pop_global_tabs_ids = apply_filters( 'wootabs_pop_global_tabs_ids_admin', $wootabs_pop_global_tabs_ids, $wootabs_pop_global_tabs );
		
		?>

		<div class="tabs-creation-form single_product">

			<input type="hidden" name="wootabs_are_global" id="wootabs_are_global" value="0">
			<input type="hidden" name="wootabs_product_id" id="wootabs_product_id" value="<?php echo $post->ID; ?>">

			<?php

			$wootabs_product_tabs_pre = get_post_meta( $post->ID, 'wootabs-product-tabs', true );
			
			$wootabs_product_tabs_pre = wootabs_is_encrypted( $wootabs_product_tabs_pre ) ? unserialize( base64_decode( $wootabs_product_tabs_pre ) ) : $wootabs_product_tabs_pre;

			?>

			<input type="hidden" name="wootabs_product_tab_value" id="wootabs_product_tab_value" value="<?php echo esc_attr( json_encode( $wootabs_product_tabs_pre ) ); ?>">

			<?php
			
			$wootabs_product_tabs = false;

			if( !$wootabs_product_tabs ){

				$wootabs_product_tabs = $wootabs_product_tabs_pre;
			}

			if( !$wootabs_product_tabs ){
				?>

				<div id="wtabs-temp-editor">
					<?php wp_editor( "", "wttemp", array( 'editor_class' => 'wtab-textarea_pre' ) ); ?>
				</div>

				<?php
			}
			
			$wootabs_product_tabs = !is_array( $wootabs_product_tabs ) ? array() : $wootabs_product_tabs;
			?>

			<ul id="wt-tab-wrapper">

				<?php

				if( $wootabs_product_tabs || $wootabs_pop_global_tabs ){

					$passed_pop_gtrid = array();

					$cnt =0;

					for( $i = 0; $i < count( $wootabs_product_tabs ); $i++ ){
						
						$title = esc_html( $wootabs_product_tabs[$i]['title'] );

						$tab_title = $title ? $title : "<i>" . __( "Tab no.", $plugin_slug ) . ( $i + 1 ) . "</i>";

						$enabled_tabs = intval( $wootabs_product_tabs[$i]['enabled'] ) == 0 ? 0 : 1;

						$content = $wootabs_product_tabs[$i]['content'];

						$pop_gtrid = $wootabs_product_tabs[$i]['pop_gtrid'] ? $wootabs_product_tabs[$i]['pop_gtrid'] : '';

						$categories = '';

						if( $pop_gtrid == '' || in_array( $pop_gtrid, $wootabs_pop_global_tabs_ids ) ){
							
							if( $pop_gtrid != '' ){

								$inSelectedCats = false;

								$passed_pop_gtrid[] = $pop_gtrid;
								
								foreach ($wootabs_pop_global_tabs_temp as $key => $value) {
									
									if( $pop_gtrid == $value['gtrid'] ){
										
										$title = $value['title'];
										$categories = $value['categories'];
										$tab_title = $title;
									}
								}

								if( empty($productSelected_categories) ){	// All categories

									$inSelectedCats = true;
								}
								else{

									$categories_array = explode( ',', $categories );

									foreach ( $productSelected_categories as $oo => $ee) {
										
										if( !$inSelectedCats ){

											if( in_array( $ee, $categories_array ) || in_array( 'all', $categories_array ) ){

												$inSelectedCats = true;
											}
										}
									}
								}
							}
							else{

								$inSelectedCats = true;
							}

							?>
							
							<li class="wt-tab" <?php if( !$inSelectedCats ){ echo ' style="display:none;" '; } ?>>
								
								<h4 class="wt-tabs-title"><span><?php echo $tab_title; ?></span><div class="wootabs-handlediv"><br></div></h4>

								<input type="hidden" name="pop_gtrid_<?php echo $i; ?>" class="pop_gtrid" value="<?php echo $pop_gtrid; ?>"/>
								
								<?php 
								if( $pop_gtrid == '' ){ ?>
									<input type="button" class="button remove-tab-button" value="<?php _e('Remove', $plugin_slug) ?>" />
									<?php 
								}
								else{ ?>
									<input type="hidden" name="pop_g_cats_<?php echo $i; ?>" class="pop_g_cats" value="<?php echo $categories; ?>" />
									<?php
								}?>

								<select class="enabled-global-tab" name="enabled-global-tab_<?php echo $i; ?>" <?php /* if( $pop_gtrid != '' ){echo ' disabled="disabled" '; }*/ ?>>
									<option value="1" <?php echo $enabled_tabs ? 'selected="selected"' : ''; ?>><?php _e( "Enabled", $plugin_slug ); ?></option>
									<option value="0" <?php echo !$enabled_tabs ? 'selected="selected"' : ''; ?>><?php _e( "Disabled", $plugin_slug ); ?></option>
								</select>

								<div class="wt-tab-content">
									<label for="inpt_<?php echo $i; ?>" class="wtab-label"><?php _e('Tab title', $plugin_slug) ?></label>
									<input type="text" name="inpt_<?php echo $i; ?>" id="inpt_<?php echo $i; ?>" value="<?php echo $title; ?>" class="wtab-inputt"  <?php if( $pop_gtrid != '' ){echo ' disabled="disabled" '; }?>>
									<?php wp_editor( $content, "tarea_" . $i, $settings = array( 'editor_class' => 'wtab-textarea' ) ); ?>
								</div>

							</li>
							
							<?php

							$cnt = $cnt + 1;
						}
					}

					if( !empty($wootabs_pop_global_tabs) ){

						$counter = 0;

						foreach( $wootabs_pop_global_tabs as $g => $h ){

							if( !in_array( $h['gtrid'], $passed_pop_gtrid ) ){

								$counter = $counter + 1;

								$title = esc_html( $h['title'] );

								$tab_title = $title ? $title : "<i>" . __( "Global tab no.", $plugin_slug ) . $counter . "</i>";

								$enabled_tabs = intval( $h['enabled'] ) == 0 ? 0 : 1;

								$content = $h['content'];

								$pop_gtrid = $h['gtrid'];

								$categories = $h['categories'];

								$inSelectedCats = false;

								if( empty($productSelected_categories) ){	// All categories

									$inSelectedCats = true;
								}
								else{

									$categories_array = explode( ',', $categories );

									foreach ( $productSelected_categories as $oo => $ee) {
										
										if( !$inSelectedCats ){

											if( in_array( $ee, $categories_array ) || in_array( 'all', $categories_array ) ){

												$inSelectedCats = true;
											}
										}

									}
								}
								
								?>
							
								<li class="wt-tab" <?php if( !$inSelectedCats ){ echo ' style="display:none;" '; } ?>>
									
									<h4 class="wt-tabs-title"><span><?php echo $tab_title; ?></span><div class="wootabs-handlediv"><br></div></h4>

									<input type="hidden" name="pop_gtrid_<?php echo ( $cnt + $counter ); ?>" class="pop_gtrid" value="<?php echo $pop_gtrid; ?>"/>
									
									<input type="hidden" name="pop_g_cats_<?php echo $i; ?>" class="pop_g_cats" value="<?php echo $categories; ?>" />

									<select class="enabled-global-tab" name="enabled-global-tab_<?php echo ( $cnt + $counter ); ?>">
										<option value="1" <?php echo $enabled_tabs ? 'selected="selected"' : ''; ?>><?php _e( "Enabled", $plugin_slug ); ?></option>
										<option value="0" <?php echo !$enabled_tabs ? 'selected="selected"' : ''; ?>><?php _e( "Disabled", $plugin_slug ); ?></option>
									</select>

									<div class="wt-tab-content">

										<label for="inpt_<?php echo ( $cnt + $counter ); ?>" class="wtab-label"><?php _e('Tab title', $plugin_slug) ?></label>
										<input type="text" name="inpt_<?php echo ( $cnt + $counter ); ?>" id="inpt_<?php echo ( $cnt + $counter ); ?>" value="<?php echo $title; ?>" class="wtab-inputt" disabled="disabled">

										<?php wp_editor( $content, "tarea_" . ( $cnt + $counter ), $settings = array( 'editor_class' => 'wtab-textarea' ) ); ?>
									</div>

								</li>
								
								<?php
							}
						}
					}

				}
				
				?>
			</ul>
			
			<div class="submit wootabs-add-tab">
				
				<input type="button" class="button-primary add-tab-button" value="<?php _e('Add Tab', $plugin_slug) ?>" />
				<img src="<?php echo admin_url() . 'images/spinner.gif' ;?>" alt="<?php _e( 'loading', $plugin_slug ); ?>" class="loading-add-tab" />

				<input type="button" class="button save-tab-button <?php if( $cnt + $counter == 0 ){ echo 'hidden-savebutton'; } ?>" value="<?php _e('Save Tabs', $plugin_slug) ?>" />
				<img src="<?php echo admin_url() . 'images/spinner.gif' ;?>" alt="<?php _e( 'loading', $plugin_slug ); ?>" class="loading-save-tabs" />
				
				<div class="wootabs-clearfix"></div>

			</div>

			<div class="wootabs-clearfix"></div>
		</div>

		<?php
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
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {

			wp_enqueue_style('thickbox');

			//New RTL Check
if ( is_rtl() ) {
wp_enqueue_style( $this->plugin_slug .'-admin-styles', WOOTABS_URI . 'admin/assets/css/admin-rtl.css', array(), Woocommerce_Wootabs::VERSION );
}
else {
wp_enqueue_style( $this->plugin_slug .'-admin-styles', WOOTABS_URI . 'admin/assets/css/admin.css', array(), Woocommerce_Wootabs::VERSION );
}

	//		if ( get_bloginfo( 'text_direction' ) == 'ltr' ) {
//
	//			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( $this->plugin_slug . '/admin/assets/css/admin.css' ), array(), Woocommerce_Wootabs::VERSION );
	//		}
	//		else{

	//			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( $this->plugin_slug . '/admin/assets/css/admin-rtl.css' ), array(), Woocommerce_Wootabs::VERSION );
	//		}
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();		

		if ( $this->plugin_screen_hook_suffix == $screen->id ) {

			wp_enqueue_script('thickbox');

			wp_enqueue_script( $this->plugin_slug . '-admin-script', WOOTABS_URI . 'admin/assets/js/admin.js', array( 'jquery' ), Woocommerce_Wootabs::VERSION );
			
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
													'unsaved_tabs_msg' => __( 'You have un-saved tabs.', $this->plugin_slug ),
													'first_save_settings' =>__( "You have to save your setting before activating license", $this->plugin_slug ),
													'already_activated' =>__( "License code is already activated", $this->plugin_slug ),
													'activation_error_ajax' =>__( "An error occured on license activation. Please try again.", $this->plugin_slug ),
													'deactivation_error_ajax' =>__( "An error occured on license deactivation. Please try again.", $this->plugin_slug ),
													'is_not_activated' =>__( "Your license is not activated", $this->plugin_slug ),
													'populate_str' => __( 'Populate in products', $this->plugin_slug )
												)
							);

			wp_localize_script( $this->plugin_slug . '-admin-script', 'wtAdminJsObj', $admin_jsobject );
			
			//wp_enqueue_script( 'wootabs-jquery-ui', WOOTABS_URI . 'admin/assets/js/jquery-ui-custom-1.11.2.min.js', array( 'jquery' ), Woocommerce_Wootabs::VERSION );
			wp_enqueue_script( 'jquery-ui' );
		}

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		if (!current_user_can( 'manage_options' )){
			
			$editor = get_role('shop_manager');
			$editor->remove_cap('manage_options');

			$admin_capability = "manage_woocommerce";
		}
		else{

			$admin_capability = "manage_options";
		}

		$this->plugin_screen_hook_suffix = add_menu_page(
											__( 'WooTabs', $this->plugin_slug ), 
											__( 'WooTabs', $this->plugin_slug ), 
											$admin_capability,
											$this->plugin_slug, 
											array( $this, 'display_plugin_admin_page' ),
											'', 
											39
										);

		add_submenu_page( $this->plugin_slug, __( 'Settings', $this->plugin_slug ), __( 'Settings', $this->plugin_slug ), $admin_capability, 'admin.php?page=' . $this->plugin_slug );
		add_submenu_page( $this->plugin_slug, __( 'Product license', $this->plugin_slug ), __( 'Product license', $this->plugin_slug ), $admin_capability, 'admin.php?page=' . $this->plugin_slug . '&tab=wootabs_product_license' );
		remove_submenu_page( $this->plugin_slug, $this->plugin_slug );
	}

	/**
	 * Highlight admin submenu items when are in active statement
	 *
	 * @since    1.2.4
	 */
	public function fix_admin_submenus_active_highlight( $parent_file ) {

		global $submenu_file;

	    if (isset($_GET['page']) && $_GET['page'] == $this->plugin_slug ){

	    	if (isset($_GET['tab']) && $_GET['tab'] == 'wootabs_product_license' ){

	    		$submenu_file = 'admin.php?page=' . $this->plugin_slug . '&tab=wootabs_product_license';
	    	}
	    	else{
	    		
	    		$submenu_file = 'admin.php?page=' . $this->plugin_slug;
	    	}
	    }

	    return $parent_file;
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'admin.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);
	}

	/**
	 * Check if user can display settings
	 *
	 * @since    1.2.4
	 *
	 * @return    boolean
	 */

	public function user_can_view_settings( $allowed_settings_role = array() ){

		$canView_settings = false;

		if( is_user_logged_in() ){
			
			global $current_user, $wpdb;

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

		return $canView_settings;
	}

	/**
	 * Hook - Execute before wp_header()
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	public function execute_before_wp_header(){

		if( isset( $_POST['action'] ) ){

			switch( $_POST['action'] ){
				case "update_wootabs_settings":

					if( is_user_logged_in() ){

						global $current_user, $wpdb;

						$user = get_userdata( $current_user->ID );

						$capabilities = $user->{$wpdb->prefix . 'capabilities'};

						if ( !isset( $wp_roles ) ){
						
							$wp_roles = new WP_Roles();
						}
						
						$allowed_settings_role = array( "administrator" );
						
						$wootabs_enable_shop_manager_settings = get_option('wootabs-enable-shop-manager-settings') && get_option('wootabs-enable-shop-manager-settings') == "on" ? true : false;

						if( $wootabs_enable_shop_manager_settings ){

							$allowed_settings_role[] = "shop_manager";
						}
						
						$canView_settings = $this->user_can_view_settings( $allowed_settings_role );
						
						foreach ( $wp_roles->role_names as $role => $name ){

							if ( array_key_exists( $role, $capabilities ) ){

								if( !$canView_settings && in_array( $role, $allowed_settings_role ) ){

									$canView_settings = true;
								}

							}
						}

						if( $canView_settings ){

							if( current_user_can( "manage_options" ) ){	// Not allow shop manager to save specific setting
								
								if( isset( $_POST['wootabs-enable-shop-manager-settings'] )  ){

									$shop_manager_settings = wp_unslash( $_POST['wootabs-enable-shop-manager-settings'] ) == "on" ? "on" : "";
								}
								else{

									$shop_manager_settings = "";						
								}

								$a = update_option( 'wootabs-enable-shop-manager-settings', $shop_manager_settings );
								
								if( isset( $_POST['wootabs-remove-data-on-uninstall'] )  ){

									$remove_onUninstall = wp_unslash( $_POST['wootabs-remove-data-on-uninstall'] ) == "on" ? "on" : "";
								}
								else{

									$remove_onUninstall = "";						
								}
							}

							$b = update_option( 'wootabs-remove-data-on-uninstall', $remove_onUninstall );

							if( isset( $_POST['wootabs-use-global-tabs'] )  ){

								$use_globalTabs = wp_unslash( $_POST['wootabs-use-global-tabs'] ) == "on" ? "on" : "";
							}
							else{

								$use_globalTabs = "";						
							}

							$c = update_option( 'wootabs-use-global-tabs', $use_globalTabs );

							if( isset( $_POST['wootabs-tabs-order'] )  ){

								$tabsOrder = wp_unslash( $_POST['wootabs-tabs-order'] );
							}
							else{

								$defaultOrder = array( 'description', 'addinfo', 'reviews', 'wootabs-global', 'wootabs-products' );

								$tabsOrder = json_encode( $defaultOrder );
							}
	
							$d = update_option( 'wootabs-tabs-order', $tabsOrder );

							$success_message = __( "Settings saved successful.", $this->plugin_slug );
							$this->set_plugin_msg( $success_message, 0 );
						}
					}
				break;
				case "update_wootabs_product_license_settings":

					if( is_user_logged_in() ){

						$allowed_settings_role = array( "administrator", "shop_manager" );

						$canView_settings = $this->user_can_view_settings( $allowed_settings_role );						

						if( $canView_settings ){

							$wootabs_envato_user_name 	 = isset( $_POST['wootabs-user-envato-name'] ) 	  ? wp_unslash( $_POST['wootabs-user-envato-name'] ) : "";
							$wootabs_user_envato_api_key = isset( $_POST['wootabs-user-envato-api-key'] ) ? wp_unslash( $_POST['wootabs-user-envato-api-key'] ) : "";
							$wootabs_product_license_key = isset( $_POST['wootabs-product-license-key'] ) ? wp_unslash( $_POST['wootabs-product-license-key'] ) : "";

							$a = update_option( 'wootabs_envato_user_name'   , $wootabs_envato_user_name    );
							$b = update_option( 'wootabs_user_envato_api_key', $wootabs_user_envato_api_key );
							$c = update_option( 'wootabs_product_license_key', $wootabs_product_license_key );							

							$success_message = __( "Settings saved successful.", $this->plugin_slug );
							$this->set_plugin_msg( $success_message, 0 );
						}

					}
				break;
			}
		}

	}

}