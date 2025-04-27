<?php
/**
 * @package   Woocommerce_Wootabs
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */

$plugin_slug = 'woocommerce-wootabs';
$current_tabURL_slug = "?page=" . $plugin_slug;

function wootabs_admin_tabs( $current = 'wootabs_settings' ) {

	$plugin_slug = 'woocommerce-wootabs';

    $tabs = array(
    	'wootabs_settings' 		=> __( 'Settings', $plugin_slug ) ,
    	'wootabs_product_license'	=> __( 'Product license', $plugin_slug )
    );

    echo '<div id="icon-themes" class="icon32"><br></div>';

    echo '<h2 class="nav-tab-wrapper">';

    foreach( $tabs as $tab => $name ){

        $class = ( $tab == $current ) ? ' nav-tab-active' : '';

        if( $tab == "wootabs_settings" ){

        	$tab_str = "";
        }
        else{

        	$tab_str = "&tab=$tab";
        }

        echo "<a class='nav-tab$class' href='?page=" . $plugin_slug . $tab_str . "'>$name</a>";

    }
    echo '</h2>';
}

$ins = Woocommerce_Wootabs_Admin::get_instance();

$message = $ins->get_plugin_msg();
$errorMessage = $message['error'];
$message = $message['message'];

$updated_html = "";

if( $message ){

	$updated_classes = array('updated');

	if($errorMessage){

		$updated_classes[] = 'error';
	}

	$updated_html .= "<div class='" . implode( " ", $updated_classes ) . "'>";
	$updated_html .= $message;
	$updated_html .= "</div>";
}

?>

<div id="wootabs-top" class="wootabs-settings-wrapper">

	<h1 class="wootabs-settings-title"><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<?php

	$current_tab = "wootabs_settings";
	$current_tabURL_slug = "?page=" . $plugin_slug;

	if ( isset ( $_GET['tab'] ) ){

		wootabs_admin_tabs( $_GET['tab'] );

		if( $_GET['tab'] != "wootabs_settings" ){

			$current_tab = $_GET['tab'];
			$current_tabURL_slug .= "&tab=" . $current_tab;
		}
	}
	else{

		wootabs_admin_tabs('wootabs_settings');
	}

	?>

	<?php

	if( $updated_html ){

		echo $updated_html;
	}

	switch( $current_tab ){
		case "wootabs_settings":

		$wootabs_remove_data_on_uninstall = get_option('wootabs-remove-data-on-uninstall') && get_option('wootabs-remove-data-on-uninstall') == "on" ? 'checked="checked"' : "";
		$wootabs_enable_shop_manager_settings = get_option('wootabs-enable-shop-manager-settings') && get_option('wootabs-enable-shop-manager-settings') == "on" ? 'checked="checked"' : "";
		$wootabs_use_global_tabs = get_option('wootabs-use-global-tabs') && get_option('wootabs-use-global-tabs') == "on" ? 'checked="checked"' : "";

		$wootabs_global_tabs = get_option('wootabs-global-tabs') ? get_option('wootabs-global-tabs') : false;
		$wootabs_global_tabs = wootabs_is_encrypted( $wootabs_global_tabs ) ? unserialize( base64_decode( $wootabs_global_tabs ) ) : $wootabs_global_tabs;

		$wootabs_before_global = get_option('wootabs-before_default_tabs') ? get_option('wootabs-before_default_tabs') : 'no';

		$wootabs_global_tabs_pos = get_option('wootabs-global-tabs-position') && get_option('wootabs-global-tabs-position') == "begin" ? "begin" : "end";
		
		require_once( WOOTABS_PATH . 'admin/class-wootabs-walker-category-checklist.php' );

		$cat_args = array( 
			'taxonomy' => 'product_cat',
			'walker' => new WooTabs_Walker_Category_Checklist(),
			'echo' => false,
		);
		
		?>

		<form method="post" action="<?php echo $current_tabURL_slug; ?>" class="wootabs-settings-form">

			<?php

			wp_nonce_field('wootabs-update-settings');

			if( current_user_can( "manage_options" ) ){

				?>

				<div class="wootabs-asw">
					<label for="wootabs-enable-shop-manager-settings"><?php _e( 'Enable Access to Shop Manager', $plugin_slug ); ?></label>&nbsp;
					<input type="checkbox" id="wootabs-enable-shop-manager-settings" name="wootabs-enable-shop-manager-settings" <?php echo $wootabs_enable_shop_manager_settings; ?> />
				</div>

				<hr>

				<div class="wootabs-asw">
					<label for="wootabs-remove-data-on-uninstall"><?php _e( "Remove WooTabs Data on Plugin Uninstall", $plugin_slug ); ?></label>&nbsp;
					<input type="checkbox" id="wootabs-remove-data-on-uninstall" name="wootabs-remove-data-on-uninstall" <?php echo $wootabs_remove_data_on_uninstall; ?> />
				</div>

				<hr>

			<?php
			}

			?>
			<div class="wootabs-asw">
				<label for="wootabs-use-global-tabs"><?php _e( "Use Global Tabs", $plugin_slug ); ?></label>&nbsp;
				<input type="checkbox" id="wootabs-use-global-tabs" name="wootabs-use-global-tabs" <?php echo $wootabs_use_global_tabs; ?> />
			</div>

			<input type="hidden" name="action" value="update_wootabs_settings" />
		</form>

		<div class="wootabs-asw gtabs <?php if( $wootabs_use_global_tabs == "" ){ echo 'hidden'; } ?>">

			<hr>

			<div class="wootabs-global-tabs handle-tabs open gbl">

				<form class="tabs-creation-form">

					<input type="hidden" name="wootabs_are_global" id="wootabs_are_global" value="1">

					<?php

					if( !$wootabs_global_tabs ){
						?>

						<div id="wtabs-temp-editor">
							<?php wp_editor( "", "wttemp", array( 'editor_class' => 'wtab-textarea_pre' ) ); ?>
						</div>

						<?php
					}

					?>

					<ul id="wt-tab-wrapper">
						<?php

						if( $wootabs_global_tabs ){

							for( $i = 0; $i < count( $wootabs_global_tabs ); $i++ ){

								$title = esc_html( $wootabs_global_tabs[$i]['title'] );

								$tab_title = $title ? $title : "<i>" . __( "Global tab no.", $plugin_slug ) . ( $i + 1 ) . "</i>";

								$enabled_tabs = intval( $wootabs_global_tabs[$i]['enabled'] ) == 0 ? 0 : 1;

								$content = $wootabs_global_tabs[$i]['content'] ;

								$selected_tab_categories = explode(',',$wootabs_global_tabs[$i]['categories'] );

								//print_r($wootabs_global_tabs);

								if( isset( $wootabs_global_tabs[$i]['lang'] ) && trim( $wootabs_global_tabs[$i]['lang'] ) != '' ){

									$selected_lang = trim( $wootabs_global_tabs[$i]['lang'] );
								}
								else{

									$selected_lang = false;
								}

								if( !$selected_tab_categories || !is_array( $selected_tab_categories ) || empty( $selected_tab_categories ) || ( count($selected_tab_categories) == 1 && $selected_tab_categories[0] == '' ) ){

									$selected_tab_categories = array( 'all' );
								}

								$prodPop = $wootabs_global_tabs[$i]['prod_pop'] ? intval( $wootabs_global_tabs[$i]['prod_pop'] ) : 0;

								$rid = $wootabs_global_tabs[$i]['gtrid'] ? $wootabs_global_tabs[$i]['gtrid'] : uniqid( 'gt_', false );

								?>

								<li class="wt-tab">

								 	<input type="hidden" name="gt_rid_<?php echo $i; ?>" class="gt_rid" value="<?php echo $rid; ?>" />

									<h4 class="wt-tabs-title"><span><?php echo $tab_title; ?></span><div class="wootabs-handlediv"><br></div></h4>

									<input type="button" class="button remove-tab-button" value="<?php _e('Remove', $plugin_slug) ?>" />

									<?php

									if( has_action( 'global_wootabs_lang_selections' ) ){

										do_action( "global_wootabs_lang_selections", $i, $selected_lang );
									}

									?>

									<select class="enabled-global-tab" name="enabled-global-tab_<?php echo $i; ?>">
										<option value="1" <?php echo $enabled_tabs ? 'selected="selected"' : ''; ?>><?php _e( "Enabled", $plugin_slug ); ?></option>
										<option value="0" <?php echo !$enabled_tabs ? 'selected="selected"' : ''; ?>><?php _e( "Disabled", $plugin_slug ); ?></option>
									</select>

									<div class="wt-tab-content">

										<label for="inpt_<?php echo $i; ?>" class="wtab-label"><?php _e('Tab title', $plugin_slug) ?></label>
										<input type="text" name="inpt_<?php echo $i; ?>" id="inpt_<?php echo $i; ?>" value="<?php echo $title; ?>" class="wtab-inputt">

										<label class="populate-global-label"><input type="checkbox" name="wootabs_gtab_populate_products_<?php echo $i;?>[]" class="wootabs_gtab_populate_products" value="" <?php if( $prodPop ){ echo ' checked = "checked" '; } ?> /><?php _e( "Populate in products" ) ;?></label>

										<hr/>

										<?php
										
										$cat_args['popular_cats'] = array( 'wootabs_products_categories_' . $i );
										$cat_args['selected_cats'] = $selected_tab_categories;
										$cat_output = wp_terms_checklist( 0, $cat_args );
										
										$cat_output = apply_filters( 'wootabs_global_product_categories', $cat_output, $cat_args, $selected_lang );
										
										if( $cat_output ) { ?>
											
											<label class="wtab-label categs"><?php  _e( 'Select products categories where tab will appear', $plugin_slug ); ?></label>

											<div class="gtcs-wrapper">
											
												<label class="gwt-lbl"><input type="checkbox" name="wootabs_products_categories_<?php echo $i;?>[]" class="gt_all_cats wootabs_products_categories" value="all" <?php if( in_array( 'all', $selected_tab_categories ) ){ echo ' checked = "checked" ' ; } ?> /><?php _e( "All categories", $plugin_slug ) ?></label>

												<div class="sep-g-wootabs-cats">
												
													<ul class="list-wootabs-cats">
													
														<?php echo $cat_output; ?>
														
													</ul>
													
												</div><!--/ .sep-g-wootabs-cats -->

											</div><!--/ .gtcs-wrapper -->
										
											<?php
										}

										?>

										<br/>

										<hr/>

										<br/>

										<?php

										wp_editor( $content, "tarea_" . $i, $settings = array( 'editor_class' => 'wtab-textarea' ) );

										?>
									</div>

								</li>

								<?php
							}

						}

						?>
					</ul>

					<?php

					if( has_action( 'global_wootabs_default_lang_selections' ) ){

						do_action("global_wootabs_default_lang_selections", '%__123__%' );

					}
					?>

					<div class="submit wootabs-add-tab">

						<input type="button" class="button-primary add-tab-button" value="<?php _e('Add Tab', $plugin_slug) ?>" />
						<img src="<?php echo admin_url() . 'images/spinner.gif' ;?>" alt="<?php _e( 'loading', $plugin_slug ); ?>" class="loading-add-tab" />

						<?php /* ?>
						<input type="button" class="button save-tab-button <?php if( !$wootabs_global_tabs ){ echo 'hidden'; } ?>" value="<?php _e('Save Tabs', $plugin_slug) ?>" />
						<img src="<?php echo admin_url() . 'images/spinner.gif' ;?>" alt="<?php _e( 'loading', $plugin_slug ); ?>" class="loading-save-tabs" />
						<?php */ ?>

						<div class="wootabs-clearfix"></div>

					</div>

				</form>

			</div>

		</div>

		<div class="wootabs-tabs-order-wrapper">

			<label><?php _e( "Tabs order", $plugin_slug ); ?></label>

			<ul id="wootabs-order-wrapper">
				<?php

				$orderedTabs = wootabs_get_tabs_order();

				foreach ( $orderedTabs as $k => $v ) {

					?><li><span class="button-primary"><?php echo $v['title']; ?></span><input type="hidden" name="<?php echo $k; ?>-order-num" id="<?php echo $k; ?>-order-num" class="tab-order-num" value="<?php echo $v['title']; ?>"/></li><?php
				}
				?>
			</ul>

		</div><!--// .wootabs-tabs-order-wrapper -->

		<hr>

		<p class="submit">
			<input type="button" class="wootabs-settings-save button-primary" value="<?php _e('Save Settings', $plugin_slug) ?>" />
			<img src="<?php echo admin_url() . 'images/spinner.gif' ;?>" alt="<?php _e( 'loading', $plugin_slug ); ?>" class="loading-save-settings" />
			<br class="wootabs-clearfix"/>
		</p>

		<?php
		break;
		case "wootabs_product_license":

		$wootabs_product_license_key = get_option('wootabs_product_license_key') ? trim(get_option('wootabs_product_license_key')) : "";

		$wootabs_user_envato_api_key = get_option('wootabs_user_envato_api_key') ? trim(get_option('wootabs_user_envato_api_key')) : "";

		$wootabs_envato_user_name = get_option('wootabs_envato_user_name') ? trim(get_option('wootabs_envato_user_name')) : "";

		if( $wootabs_product_license_key == "" || $wootabs_user_envato_api_key == "" || $wootabs_envato_user_name == "" ){

			$can_activate = 0;
		}
		else{

			$can_activate = 1;
		}

		$verified_code = get_option('wootabs_product_verified_code') ? trim(get_option('wootabs_product_verified_code')) : '';

		$is_active_license = $verified_code == '' ? 0 : 1;

		$can_activate = $is_active_license ? 0 : $can_activate;

		$activated_disabled_inputs = $is_active_license ? 'disabled="disabled"' : '';

		$to_activate_class = $is_active_license == 1 ? "" : "to-activate";

		?>

		<form method="post" action="<?php echo $current_tabURL_slug; ?>" class="wootabs-settings-form form-product-license <?php echo $to_activate_class; ?>">

			<div class="updated license_key_info">
				<span class="dashicons dashicons-info"></span>
				<p><?php
					printf( __( "By verifying your product's license you qualified to enable auto updates of <b>WooTabs</b> plugin. The license (purchase) code may only be used for one WordPress site at a time. If you have previously activated your license code on another site, then you should deactivate it first or obtain a %snew one%s .", $plugin_slug ),
					"<a href='" . esc_url( WOOTABS_PURCHASE_URL ) . "' title='' target='_blank'>",
					"</a>"
					) ;
					?></p>
			</div>

			<div class="wootabs-asw">

				<label for="wootabs-user-envato-name" class="product-license-label"><?php _e( "Envato username", $plugin_slug ); ?></label>&nbsp;
				<input type="text" id="wootabs-user-envato-name" name="wootabs-user-envato-name" class="product-license-txt-input" value="<?php echo $wootabs_envato_user_name; ?>" <?php echo $activated_disabled_inputs; ?> />
			</div>

			<hr>

			<div class="wootabs-asw">

				<label for="wootabs-user-envato-api-key" class="product-license-label"><?php _e( "Secret API key", $plugin_slug ); ?></label>&nbsp;
				<input type="text" id="wootabs-user-envato-api-key" name="wootabs-user-envato-api-key" class="product-license-txt-input" value="<?php echo $wootabs_user_envato_api_key; ?>" <?php echo $activated_disabled_inputs; ?> />

				<?php

				$license_img = plugins_url( 'woocommerce-wootabs/assets/img/envato_secret_API_key.png' );

				?>

				<p class="where-find-license"><a href="<?php echo $license_img; ?>" title="" target="_blank" class="thickbox"><?php _e( "Where to find it?", $plugin_slug ); ?></a></p>
			</div>

			<hr>

			<div class="wootabs-asw">

				<label for="wootabs-product-license-key" class="product-license-label"><?php _e( "Purchase code", $plugin_slug ); ?></label>&nbsp;
				<input type="text" id="wootabs-product-license-key" name="wootabs-product-license-key" class="product-license-txt-input" value="<?php echo $wootabs_product_license_key; ?>" <?php echo $activated_disabled_inputs; ?> />

				<?php

				$license_img = plugins_url( 'woocommerce-wootabs/assets/img/envato-license-key.png' );

				?>

				<p class="where-find-license"><a href="<?php echo $license_img; ?>" title="" target="_blank" class="thickbox"><?php _e( "Where to find it?", $plugin_slug ); ?></a></p>
			</div>

			<hr>

			<div class="updated wootabs-license-messages">a</div>

			<p class="submit wootabs-license-buttons">
				<input type="submit" class="wootabs-product-license-settings-save button-primary" value="<?php _e('Save Settings', $plugin_slug) ?>" <?php echo $activated_disabled_inputs; ?> />

				<button class="wootabs-activate-license button"><?php _e( "Activate license", $plugin_slug ); ?></button>
				<button class="wootabs-deactivate-license button"><?php _e( "Deactivate license", $plugin_slug ); ?></button>

				<img class="wootabs-loading-license-activation" alt="loading" src="<?php echo admin_url("images/spinner.gif"); ?>">
			</p>

			<input type="hidden" name="action" value="update_wootabs_product_license_settings" />

			<input type="hidden" id="wootabs_can_activate" name="wootabs_can_activate" value="<?php echo esc_attr( $can_activate ); ?>" />
			<input type="hidden" id="wootabs_is_active_license" name="wootabs_is_active_license" value="<?php echo esc_attr( $is_active_license ); ?>" />

		</form>

		<?php
		break;
	}

	?>

</div>
