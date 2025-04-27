var wootabs_unsaved_tabs = false,
	wt_textEditor,
	$_wootabs_license_str = {},
	clicked_publish_product = false,
	clicked_save_settings = false,
	product_categories = [];

$_wootabs_license_str.first_save_settings = wtAdminJsObj.texts.first_save_settings;
$_wootabs_license_str.already_activated = wtAdminJsObj.texts.already_activated;
$_wootabs_license_str.activation_error_ajax = wtAdminJsObj.texts.activation_error_ajax;
$_wootabs_license_str.deactivation_error_ajax = wtAdminJsObj.texts.deactivation_error_ajax;
$_wootabs_license_str.is_not_activated = wtAdminJsObj.texts.is_not_activated;

window.onbeforeunload = function(e) {

	"use strict";

	if( wootabs_unsaved_tabs ){
		
		return wtAdminJsObj.texts.unsaved_tabs_msg;		
	}
};

(function ( $ ) {

	"use strict";

	$(function () {

		if( !jQuery('.form-product-license').length ){
			
			wootabs_init_global_tabs();

			if( jQuery('#wootabs_are_global').length && parseInt( jQuery('#wootabs_are_global').val() ) ){

				jQuery('.wootabs-settings-save').on("click", function(){

					var cnt = 0,
						orderObj = [],
						k;

					if( !clicked_save_settings ){
						
						clicked_save_settings = true;

						jQuery('.loading-save-settings').fadeIn();

						jQuery( "#wootabs-order-wrapper li" ).each(function(){

							k = parseInt( jQuery(this).find('.tab-order-num').val(), 10);
							
							orderObj[k] = jQuery(this).find('.tab-order-num').attr('id');

							cnt = cnt + 1;

							if( cnt === jQuery( "#wootabs-order-wrapper li" ).length ){

								var addData = jQuery("<input>").attr("type", "hidden").attr("name", "wootabs-tabs-order").val( JSON.stringify( orderObj ) );

								jQuery('.wootabs-settings-form').append( jQuery( addData ) );

								wootabs_saveTabs();
							}
						});
					}
				});
			}
			else{

				jQuery('#publish').on('click', function(){

					var ret = true;

					if( !clicked_publish_product ){

						if( wootabs_unsaved_tabs ){

							jQuery('#publishing-action .spinner').fadeIn();

							clicked_publish_product = true;

							wootabs_saveTabs();

							ret = false;
						}
					}
					else{

						ret = false;
					}

					return ret;
				});

				jQuery('.popular-category').find('input[type="checkbox"]').on('change', function(){

					product_categories = [];

					wootabs_get_product_selected_cats();
				});
			}
		}
		else{

			wootabs_verify_purchase_code();
		}
	});

}(jQuery));

function wootabs_get_product_selected_cats(){

	"use strict";

	var cnt = 0, l = jQuery('.popular-category').length;

	jQuery('.popular-category').each(function(){

		if( wootabs_isChecked( jQuery(this).find('input[type="checkbox"]') ) && jQuery.inArray( jQuery(this).find('input[type="checkbox"]').val(), product_categories ) < 0 ){

			product_categories[ product_categories.length ] = jQuery(this).find('input[type="checkbox"]').val();
		}

		if( cnt + 1 === l ){

			wootabs_check_popgtabs_cats();
		}
		else{

			cnt = cnt + 1;
		}
	});
}

function wootabs_check_popgtabs_cats(){
	
	"use strict";

	var tabCats, i, found, l;

	jQuery('.wt-tab').each(function(){

		if( jQuery.trim( jQuery(this).find('.pop_gtrid').val() ) !== '' ){

			found = false;

			tabCats = jQuery(this).find('.pop_g_cats').val();

			tabCats = tabCats.split( ',' );
			
			l = tabCats.length;

			for( i=0; i<tabCats.length; i++ ){

				if( !found ){

					if( jQuery.inArray( tabCats[i], product_categories ) >= 0 || jQuery.inArray( 'all', tabCats ) >= 0 ){

						found = true;
					}
				}

				if( i+1 === l ){

					if( !found ){

						jQuery(this).css('display', 'none');
					}
					else{

						jQuery(this).css('display', 'inline-block');
					}

					wootabs_destroy_sortable();
					init_sortableTabs();
					init_sortable_tabsOrder();
					wootabs_tabs_events();
				}
			}
		}
	});
}

function wootabs_uniqid(prefix, more_entropy) {

  if (typeof prefix === 'undefined') {
    prefix = '';
  }

  var retId;
  var formatSeed = function(seed, reqWidth) {
    seed = parseInt(seed, 10)
      .toString(16); 
    if (reqWidth < seed.length) { 
      return seed.slice(seed.length - reqWidth);
    }
    if (reqWidth > seed.length) { 
      return Array(1 + (reqWidth - seed.length))
        .join('0') + seed;
    }
    return seed;
  };
  
  if (!this.php_js) {
    this.php_js = {};
  }
  if (!this.php_js.uniqidSeed) {
    this.php_js.uniqidSeed = Math.floor(Math.random() * 0x75bcd15);
  }
  this.php_js.uniqidSeed++;

  retId = prefix;
  retId += formatSeed(parseInt(new Date()
    .getTime() / 1000, 10), 8);
  retId += formatSeed(this.php_js.uniqidSeed, 5);
  if (more_entropy) {

    retId += (Math.random() * 10)
      .toFixed(8)
      .toString();
  }

  return retId;
}

function wootabs_getNewTab_html(){

	'use strict';

	var add_TabNum, id, rid, nTab_title, $h, $ret;

	add_TabNum = jQuery('.wt-tab').length + 1;	

	id = 'tarea_' + (Math.random() + '').replace('0.', '');

	if( parseInt( jQuery('#wootabs_are_global').val(), 10 ) ){

		nTab_title = wtAdminJsObj.texts.global_tab_no + add_TabNum;

		rid = wootabs_uniqid( 'gt_', false );
	}
	else{

		nTab_title = wtAdminJsObj.texts.product_tab_no + add_TabNum;

		rid = '';
	}

	$h = '<li class="wt-tab">';

	if( parseInt( jQuery('#wootabs_are_global').val(), 10 ) ){

		$h += '<input type="hidden" name="gt_rid_' + add_TabNum + '" class="gt_rid" value="' + rid + '" />';
	}
	else{

		$h += '<input type="hidden" name="pop_gtrid_' + add_TabNum + '" class="pop_gtrid" value="' + rid + '" />';
	}

	$h += '<h4 class="wt-tabs-title"><span><i>' + nTab_title + '</i></span><div class="wootabs-handlediv"><br></div></h4>';
	$h += '<input type="button" class="button remove-tab-button" value="' + wtAdminJsObj.texts.remove + '" />';
	
	if( jQuery('.wootabs-avail-def-lang-html').length ){

		$h += '<select name="global-tab-language-' + add_TabNum + '" id="global-tab-language-' + add_TabNum + '" class="gtabs_lang_selection">';
		$h += jQuery('.wootabs-avail-def-lang-html').html();
		$h += '</select>';
	}

	$h += '<select class="enabled-global-tab green" name="enabled-global-tab_' + add_TabNum + '">\
				<option value="1" selected="selected">' + wtAdminJsObj.texts.enabled + '</option>\
				<option value="0">' + wtAdminJsObj.texts.disabled + '</option>\
			</select>\
			<div class="wt-tab-content">\
			<label for="inpt_' + add_TabNum + '" class="wtab-label">' + wtAdminJsObj.texts.tab_title + '</label>';
	$h += '<input type="text" name="inpt_' + add_TabNum + '" id="inpt_' + add_TabNum + '" value="' + nTab_title + '" class="wtab-inputt">';

	if( parseInt( jQuery('#wootabs_are_global').val(), 10 ) ){
		
		$h += '<label class="populate-global-label"><input type="checkbox" name="wootabs_gtab_populate_products_' + add_TabNum + '" class="wootabs_gtab_populate_products" value="" />' + wtAdminJsObj.texts.populate_str + '</label><hr/>';
	}

	$h += '<textarea id="' + id + '" name="' + id + '" class="wtab-textarea"></textarea>';
	$h += '</div>\
			</li>';

	$ret = {
		'html' 	: $h,
		'id'	: id
	};

	return $ret;
}

function wootabs_isChecked(e) {

	'use strict';

	var $r;

    if ( e.attr("checked") !== undefined ) {
        $r = 1;
    }
    else {
        $r = 0;
    }

    return $r;
}

function wootabs_tabs_events(){

	jQuery('#wootabs-use-global-tabs').unbind("change").on("change", function(){
		
		wootabs_globalTabs_toggle();
	});

	jQuery('.wtab-textarea, .wtab-inputt').unbind("change").on("change", function(){
		
		wootabs_unsaved_tabs = true;

		if( jQuery(this).hasClass('wtab-inputt') ){

			jQuery(this).parents('.wt-tab').find('.wt-tabs-title > span').html( jQuery.trim( jQuery(this).val() ) );
		}

	});

	jQuery('.gtabs-title').unbind("click").on("click", function(){
		
		wootabs_allTabs_toggle( this );
	});

	jQuery('.wt-tab .wootabs-handlediv').unbind("click").on("click", function(){

		wootabs_tabContent_toggle( jQuery(this).parents('.wt-tabs-title') );
	});
	
	if( jQuery('.gtabs_lang_selection').length > 0 ) {
		jQuery('.wootabs-global-tabs').on('change', '.gtabs_lang_selection', function() {
			jQuery('.wootabs-settings-save').click();
			jQuery('body,html').animate({ scrollTop: jQuery('.wootabs-settings-save').offset().top }, 1000);
		});
	}
}

function wootabs_init_global_tabs(){

	'use strict';

	init_sortableTabs();

	init_sortable_tabsOrder();

	wootabs_globalTabs_toggle();

	init_tabsRemoval();

	init_saveTab_action();

	init_tabsAddition();

	wootabs_initGTabs_cats();

	wootabs_tabs_events();

	init_enableTab_action();
}

function wootabs_initGTabs_cats(){

	jQuery('.gt_all_cats').each(function(){

		if( jQuery(this).is(":checked") === true ){

			jQuery(this).parents('.gtcs-wrapper').find('.sep-g-wootabs-cats').hide('fast');
		}
	});	

	wootabs_onGTabs_cats_change();
}

function wootabs_onGTabs_cats_change(){

	jQuery('.gt_all_cats').unbind('change').on('change', function(){

		if( jQuery( this ).is(":checked") === true ){

			jQuery(this).parents('.gtcs-wrapper').find('.sep-g-wootabs-cats').slideUp();
		}
		else{

			jQuery(this).parents('.gtcs-wrapper').find('.sep-g-wootabs-cats').slideDown();
		}

	});
}

function init_enableTab_action(){

	'use strict';

	jQuery('.enabled-global-tab').each(function(){
		
		if( parseInt( jQuery(this).val(), 10 ) === 1 ){

			jQuery(this).removeClass("red").addClass("green");
		}
		else{

			jQuery(this).removeClass("green").addClass("red");
		}

	});

	jQuery('.enabled-global-tab').unbind("change").on("change", function(){

		if( parseInt( jQuery(this).val(), 10 ) === 1 ){

			jQuery(this).removeClass("red").addClass("green");
		}
		else{

			jQuery(this).removeClass("green").addClass("red");
		}

		wootabs_unsaved_tabs = true;
	});
}

function wootabs_saveTabs(){

	'use strict';

	var tabsCounter = 0,
		tabsLength = jQuery('.wt-tab').length,
		saveTabs = {},
		empty_array = "no_tabs";

	if( tabsLength ){

		jQuery('.wt-tab').each(function(){

			var this_gtab, thisTitle, thisContent, thisOrder, thisEnabled, tTextareaId, t_cats = [], tProdPop, tgtrid;

			this_gtab = this;

			thisTitle = jQuery.trim( jQuery(this).find('.wtab-inputt').val() );

			tTextareaId = jQuery(this).find('textarea').attr('id');

			if( tinyMCE.get( tTextareaId ) && jQuery( "#wp-" + tTextareaId + "-wrap" ).hasClass('tmce-active') ){
				
				thisContent = jQuery.trim( tinyMCE.get( tTextareaId ).getContent() );
			}
			else{

				thisContent =  jQuery.trim( jQuery( '#' + jQuery(this).find('textarea').attr('id') ).val() );
			}

			thisOrder = parseInt( tabsCounter, 10 );
			thisEnabled = parseInt( jQuery(this).find('.enabled-global-tab').val(), 10 );

			jQuery('input[name="' + jQuery(this_gtab).find('.wootabs_products_categories').attr('name') + '"]').each(function(){

				if( jQuery(this).is(":checked") ){

					t_cats.push( jQuery(this).val() );
				}

			});

			saveTabs[tabsCounter] = {
				'title' 	: 	thisTitle,
				'content' 	: 	thisContent,
				'order' 	: 	thisOrder,
				'enabled' 	:	thisEnabled, 
				'categories': 	t_cats.join( ',' )
			}

			if( jQuery('#wootabs_are_global').length && parseInt( jQuery('#wootabs_are_global').val() ) ){

				tProdPop = jQuery(this).find('.wootabs_gtab_populate_products').is(":checked") ? 1 : 0;
				tgtrid = jQuery.trim( jQuery(this).find('.gt_rid').val() );

				saveTabs[tabsCounter]['prod_pop'] = tProdPop;
				saveTabs[tabsCounter]['gtrid'] = tgtrid;
			}
			else{

				tgtrid = jQuery.trim( jQuery(this).find('.pop_gtrid').val() );
				saveTabs[tabsCounter]['pop_gtrid'] = tgtrid;
			}

			if( jQuery(this_gtab).find('.gtabs_lang_selection').length ){

				saveTabs[tabsCounter].lang = jQuery(this_gtab).find('.gtabs_lang_selection').val();
			}

			if( tabsCounter + 1 === jQuery('.wt-tab').length ){

				wootabs_f_saveTabs( saveTabs );
			}
			else{

				tabsCounter = tabsCounter + 1;
			}
		});
	}
	else{

		jQuery('.save-tab-button').fadeOut('fast');

		wootabs_f_saveTabs( empty_array );
	}
}

function wootabs_f_saveTabs( saveTabs ){

	'use strict';

	if( jQuery('#wootabs_are_global').length && parseInt( jQuery('#wootabs_are_global').val() ) ){	// Global tabs

		jQuery.ajax({
			type: "post",
			url: wtAdminJsObj.ajax_url,
			data: {
				action: 'wootabs_save_global_tabs',
				d: saveTabs
			},
			success:function(data, textStatus, XMLHttpRequest){

				jQuery('.loading-save-tabs').fadeOut();
				wootabs_unsaved_tabs = false;

				if( clicked_save_settings ){

					jQuery('.wootabs-settings-form').submit();

					clicked_save_settings = false;
				}
			},
			error:function(data, textStatus, XMLHttpRequest){

				jQuery('.loading-save-tabs').fadeOut();
				console.log('error ajax - WooTabs save global tabs');
			}
		});
	}
	else{	// Product tabs

		jQuery.ajax({
			type: "post",
			url: wtAdminJsObj.ajax_url,
			dataType: 'json',
			data: {
				action: 'wootabs_save_product_tabs',
				d: saveTabs,
				id: jQuery('#wootabs_product_id').val()
			},
			success:function(data, textStatus, XMLHttpRequest){

				jQuery('.loading-save-tabs').fadeOut();
				wootabs_unsaved_tabs = false;

				if( parseInt( data.errors ) === 0 ){

					jQuery('#wootabs_product_tab_value').val( jQuery.trim( data.new_value ) );
				}

				if( clicked_publish_product ){

					jQuery('form#post').submit();

					clicked_publish_product = false;
				}
			},
			error:function(data, textStatus, XMLHttpRequest){

				jQuery('.loading-save-tabs').fadeOut();
				console.log('error ajax - WooTabs save product tabs');
			}
		});
	}
}

function init_saveTab_action(){

	'use strict';

	jQuery(".save-tab-button").unbind('click').on('click', function(){
		
		wootabs_saveTabs();

		jQuery('.loading-save-tabs').fadeIn();
	});
}

function wtabs_remove_temp_editor(){

	'use strict';

	if( jQuery('#wtabs-temp-editor').length ){

		tinymce.remove('#wttemp');
		jQuery('#wttemp').remove();
		
		setTimeout(function(){
			jQuery('#wtabs-temp-editor').remove();	
		},500);

	}
	else{

		wootabs_saveTabs();
	}
}

function init_tabsAddition(){

	'use strict';

	wtabs_remove_temp_editor();

	setTimeout(function(){
	
		wt_textEditor = jQuery('#grab-editor').html();

		jQuery('.add-tab-button').on( "click", function(){

			jQuery('.loading-add-tab').fadeIn();

			var x = wootabs_getNewTab_html();

			jQuery.ajax({	
				type: "post",
				dataType: "html",
				url: wtAdminJsObj.ajax_url,
				data: {
					action: 'wootabs_get_new_tab_asynch',
					textarea_name: x.id,
					id : x.id,
					content: "",
					globalT: parseInt( jQuery('#wootabs_are_global').val(), 10 )
				},
				success:function(data, textStatus, XMLHttpRequest){

					if( jQuery("#wt-tab-wrapper").append( x.html ) ){

						if( jQuery( '#' + x.id ).replaceWith(data) ){

							tinyMCE.init(tinyMCEPreInit.mceInit[x.id]);

						    tinyMCEPreInit.qtInit[x.id] = JSON.parse( JSON.stringify(tinyMCEPreInit.qtInit[ x.id ] ) );
						    tinyMCEPreInit.qtInit[x.id].id = x.id;
						    
						    setTimeout(function(){
							    new QTags(tinyMCEPreInit.qtInit[ x.id ]);
							    QTags._buttonsInit();
							    switchEditors.go( x.id, 'html' );
							}, 1000);
						}

						wootabs_unsaved_tabs = true;

						wootabs_onGTabs_cats_change();
						init_enableTab_action();
						init_tabsRemoval();
						init_saveTab_action();
						wootabs_tabs_events();

						jQuery('.loading-add-tab').fadeOut();
						jQuery('.save-tab-button').fadeIn();
					}						

				},
				error:function(data, textStatus, XMLHttpRequest){

					console.log('error ajax - WooTabs create tab');
					jQuery('.loading-add-tab').fadeOut();
				}
			});

		});

	}, 1000);
}

function init_tabsRemoval(){

	'use strict';

	jQuery('.remove-tab-button').unbind('click').on( "click", function(){

		var r = confirm( wtAdminJsObj.remove_tab_confirm_msg );

		if( r === true ){

			jQuery(this).parents('.wt-tab').slideUp( 'fast', function(){

				jQuery(this).remove();

				wootabs_saveTabs();
			});
		}
	});
}

function wootabs_destroy_sortable(){

	if ( jQuery( "#wt-tab-wrapper" ).data( 'sortable' ) ){
	
	    jQuery( "#wt-tab-wrapper" ).sortable("destroy");
	}
}

function init_sortableTabs(){

	'use strict';

	var textareaID;

	jQuery( "#wt-tab-wrapper" ).sortable({
		cursor: "move",
		scroll: false,
		start:function( event, ui ){
			tinymce.remove();
		},
		stop:function( event, ui ){

			jQuery('.wtab-textarea').each(function(){

				textareaID = jQuery(this).attr('id');
				switchEditors.go( textareaID, tinyMCEPreInit.mceInit[textareaID].mode );
			});

			wootabs_unsaved_tabs = true;
		},
		handle: ".wt-tabs-title",
		placeholder: "wootabs-sortable-placeholder",
	    opacity: '0.8'
	});
}

function init_sortable_tabsOrder(){

	'use strict';

	var textareaID;

	var cnt = 0;

	jQuery( "#wootabs-order-wrapper li" ).each(function(){

		jQuery(this).find('.tab-order-num').val( cnt );

		cnt = cnt + 1;
	});
	
	jQuery( "#wootabs-order-wrapper" ).sortable({
		cursor: "move",
		scroll: false,
		revert: 350,
		start:function( event, ui ){
			tinymce.remove();
			ui.placeholder.height(ui.helper.height() - 2 );
			ui.placeholder.width(ui.helper.width() - 2 );
		},
		stop:function( event, ui ){

			var cnt = 0;

			jQuery( "#wootabs-order-wrapper li" ).each(function(){

				jQuery(this).find('.tab-order-num').val( cnt );

				cnt = cnt + 1;
			});
		},
		placeholder: "wootabs-sortable-tabs-placeholder",
	    opacity: '0.8'
	});
}

function wootabs_globalTabs_toggle(){

	'use strict';

	if( wootabs_isChecked( jQuery('#wootabs-use-global-tabs') ) ){

		jQuery('.wootabs-asw.gtabs, .wt-gpos').removeClass('hidden');
	}
	else{

		jQuery('.wootabs-asw.gtabs, .wt-gpos').addClass('hidden');
	}
}

function wootabs_allTabs_toggle(e){

	'use strict';

	if( jQuery(e).parents('.handle-tabs').hasClass('open') ){

		jQuery(e).parents('.handle-tabs').removeClass('open');
	}
	else{

		jQuery(e).parents('.handle-tabs').addClass('open');
	}
}

function wootabs_tabContent_toggle(e){
	
	'use strict';

	if( jQuery(e).parents('.wt-tab').hasClass('wt-open') ){

		jQuery(e).parents('.wt-tab').removeClass('wt-open');
	}
	else{

		var tid = jQuery(e).parents('.wt-tab').find('textarea').attr('id');

		jQuery(e).parents('.wt-tab').addClass('wt-open');
		
		if(  tinyMCE.get( tid ) ){

	    	tinyMCE.get( tid ).getBody().focus();
	    }
	    else if( jQuery( '#' + tid ).length ){

	    	jQuery( '#' + tid ).focus();
	    }

	}
}

function wootabs_on_license_activation_complete( msg, error ){

	jQuery('.wootabs-license-buttons').removeClass('activating');

	if( !error ){
			
		jQuery('.form-product-license').removeClass('to-activate');

		jQuery('.form-product-license input[type="text"]').attr('disabled', 'disabled');
		jQuery('.form-product-license .wootabs-product-license-settings-save').attr('disabled', 'disabled');

		jQuery('#wootabs_is_active_license').val( 1 );
		jQuery('#wootabs_can_activate').val( 0 );
	}
	
	if( msg ){
	
		wootabs_display_license_message( msg, error );
	}
}

function wootabs_on_license_deactivation_complete( msg, error ){
	
	jQuery('.wootabs-license-buttons').removeClass('activating');

	if( !error ){

		jQuery('.form-product-license').addClass('to-activate');

		jQuery('.form-product-license input[type="text"]').removeAttr('disabled');
		jQuery('.form-product-license .wootabs-product-license-settings-save').removeAttr('disabled');

		jQuery('#wootabs_is_active_license').val( 0 );
		jQuery('#wootabs_can_activate').val( 1 );
	}

	if( msg ){
	
		wootabs_display_license_message( msg, error );
	}
}

function wootabs_verify_purchase_code(){

	jQuery('.wootabs-activate-license').on("click", function(){

		var can_activate = parseInt( jQuery('#wootabs_can_activate').val(), 10 ),
			is_already_active = parseInt( jQuery('#wootabs_is_active_license').val(), 10 );

		if( is_already_active === 1 ){
			
			wootabs_display_license_message( $_wootabs_license_str.already_activated, 1 );

			wootabs_on_license_deactivation_complete();
		}
		else{

			if( can_activate === 1 ){

				jQuery('.wootabs-license-buttons').addClass('activating');

				jQuery.ajax({
					type: "post",
					url: wtAdminJsObj.ajax_url,
					dataType: "json",
					data: {
						action: 'wootabs_activate_license'
					},
					success:function(data, textStatus, XMLHttpRequest){

						console.log( data );

						if( data.error === 1 ){
							
						}
						else{

						}

						wootabs_on_license_activation_complete( data.msg, data.error );
					},
					error:function(data, textStatus, XMLHttpRequest){

						console.log( data );
						console.log( textStatus );

						console.log('error ajax - WooTabs activate license');

						wootabs_on_license_activation_complete( $_wootabs_license_str.activation_error_ajax, 1 );
					}
				});
			}
			else{

				wootabs_display_license_message( $_wootabs_license_str.first_save_settings, 1 );
			}
		}

		return false;
	});

	jQuery('.wootabs-deactivate-license').on("click", function(){
		
		var is_already_active = parseInt( jQuery('#wootabs_is_active_license').val(), 10 );

		if( is_already_active === 1 ){
		
			jQuery('.wootabs-license-buttons').addClass('activating');

			jQuery.ajax({
				type: "post",
				url: wtAdminJsObj.ajax_url,
				dataType: "json",
				data: {
					action: 'wootabs_deactivate_license'
				},
				success:function(data, textStatus, XMLHttpRequest){

					console.log( data );

					if( data.error === 1 ){

					}
					else{

					}

					wootabs_on_license_deactivation_complete( data.msg, data.error );
				},
				error:function(data, textStatus, XMLHttpRequest){

					console.log('error ajax - WooTabs deactivate license');
					wootabs_on_license_deactivation_complete( $_wootabs_license_str.deactivation_error_ajax, 1 );
				}
			});
		}
		else{

			wootabs_display_license_message( $_wootabs_license_str.is_not_activated, 1 );

			wootabs_on_license_activation_complete();
		}

		return false;		
	});
}

function wootabs_hide_license_message(){

	jQuery( '.wootabs-license-messages' ).fadeOut();
}

function wootabs_display_license_message( c, error ){

	if( c ){
		
		if( error ){

			jQuery( '.wootabs-license-messages' ).addClass('error');
		}
		else{

			jQuery( '.wootabs-license-messages' ).removeClass('error');
		}

		jQuery( '.wootabs-license-messages' ).html( c ).fadeIn();
	}
}