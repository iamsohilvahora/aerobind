<?php

// var_dump( basename(dirname(__FILE__)) );

/*add_filter ('pre_set_site_transient_update_plugins', 'display_transient_update_plugins');
function display_transient_update_plugins ($transient)
{
    $obj = new stdClass();
    $obj->slug = 'woocommerce-wootabs';
    $obj->new_version = '2.0';
    $obj->url = 'http://localhost/custom_plugins_API/';
    $obj->package = 'http://localhost/custom_plugins_API/';
    
    $transient['plugin_directory/plugin_file.php'] = $obj;
    
    return $transient;
}*/