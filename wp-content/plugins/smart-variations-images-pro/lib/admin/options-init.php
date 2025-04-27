<?php

if (!class_exists('Redux')) {
    return;
}

function sviremoveDemoModeLink() { // Be sure to rename this function to something more unique
    if (class_exists('ReduxFrameworkPlugin')) {
        remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::get_instance(), 'plugin_metalinks'), null, 2);
    }
    if (class_exists('ReduxFrameworkPlugin')) {
        remove_action('admin_notices', array(ReduxFrameworkPlugin::get_instance(), 'admin_notices'));
    }
}

add_action('init', 'sviremoveDemoModeLink');

$woosvi_options = get_option('woosvi_options');
// This is your option name where all the Redux data is stored.
$opt_name = "woosvi_options";

$args = array(
    'opt_name' => 'woosvi_options',
    'use_cdn' => TRUE,
    'dev_mode' => false,
    'forced_dev_mode_off' => false,
    'display_name' => 'SMART VARIATIONS IMAGES PRO',
    'display_version' => SL_VERSION,
    'page_slug' => 'woocommerce_svi',
    'page_title' => 'Smart Variations Images PRO for WooCommerce',
    'update_notice' => TRUE,
    'admin_bar' => TRUE,
    'menu_type' => 'submenu',
    'menu_title' => 'SVI PRO',
    'page_parent' => 'woocommerce',
    'customizer' => FALSE,
    'default_mark' => '*',
    'hints' => array(
        'icon' => 'el el-adjust-alt',
        'icon_position' => 'right',
        'icon_color' => 'lightgray',
        'icon_size' => 'normal',
        'tip_style' => array(
            'color' => 'light',
        ),
        'tip_position' => array(
            'my' => 'top left',
            'at' => 'bottom right',
        ),
        'tip_effect' => array(
            'show' => array(
                'duration' => '500',
                'event' => 'mouseover',
            ),
            'hide' => array(
                'duration' => '500',
                'event' => 'mouseleave unfocus',
            ),
        ),
    ),
    'output_tag' => TRUE,
    'cdn_check_time' => '1440',
    'page_permissions' => 'manage_woocommerce',
    'save_defaults' => TRUE,
    'database' => 'options',
    'transient_time' => '3600',
    'network_sites' => TRUE,
);

Redux::setArgs($opt_name, $args);

/*
 * ---> END ARGUMENTS
 */


/*
 *
 * ---> START SECTIONS
 *
 */


if (!$woosvi_options || !isset($woosvi_options['svipro_softlicense_key']) || !$woosvi_options['svipro_softlicense_key']) {
    Redux::setSection($opt_name, array(
        'title' => __('License Control', 'wc_svi'),
        'id' => 'licensecontrol',
        //'desc' => __('General settings', 'wc_svi'),
        'icon' => 'el el-home',
        'fields' => array(
            array(
                'id' => 'svipro_softlicense_key',
                'type' => 'text',
                'title' => __('License Key', 'wc_svi    '),
                'desc' => __('Please supply the license sent on your complete order email.', 'wc_svi'),
                //'desc' => __('This is the description field, again good for additional info.', 'wc_svi'),
                'validate_callback' => 'svipro_license_validation',
                //'validate' => 'not_empty',
                //'msg' => 'custom error message',
                'placeholder' => 'SVIPRO-XXXXXXXXXXXXX'
            ),
            array(
                'id' => 'info_normal_svi',
                'type' => 'info',
                'desc' => '<h4>You must ensure that your environment meets the following minimum conditions:</h4>
                                    <ul>
                                        <li>PHP 5.6.30 or later (current system version: <b>' . phpversion() . '</b>)</li>
                                        <li>WordPress 4.0 or later</li>
                                        <li>Can\'t activate license? Read FAQ about <a href="//www.rosendo.pt/question/cannot-activate-license/">Cannot activate license</a> </li>
    </ul>')
        ),
            )
    );
}

if ($woosvi_options['svipro_softlicense_key']) {

    Redux::setSection($opt_name, array(
        'title' => __('Global', 'wc_svi'),
        'id' => 'general',
        //'desc' => __('General settings', 'wc_svi'),
        'icon' => 'el el-home',
        'fields' => array(
            array(
                'id' => 'default',
                'type' => 'switch',
                'title' => __('Enable SVI', 'wc_svi'),
                'desc' => __('Activate or Deactivate SVI from running on your site.', 'wc_svi'),
                'on' => __('Enable', 'wc_svi'),
                'off' => __('Deactivate', 'wc_svi'),
                'default' => false,
            ),
            array(
                'id' => 'triger_match',
                'type' => 'switch',
                'required' => array('default', '=', '1'),
                'title' => __('Trigger Match', 'wc_svi'),
                'subtitle' => __('Read discription', 'wc_svi'),
                //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
                'desc' => __('Activating this option will make image swap occour only if spefic combination exist and match according to galleries created in Product, no grouping will occur.', 'wc_svi'),
                'default' => false,
            ),
            array(
                'id' => 'svicart',
                'type' => 'switch',
                'required' => array('default', '=', '1'),
                'title' => __('Cart Image', 'wc_svi'),
                //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
                'desc' => __('Display choosen variation image in cart/checkout instead of default Product image.', 'wc_svi'),
                'default' => false,
            ),
        )
    ));

    Redux::setSection($opt_name, array(
        'title' => __('Lightbox', 'wc_svi'),
        'id' => 'lightbox-svi',
        'fields' => array(
            array(
                'id' => 'lightbox',
                'type' => 'switch',
                'required' => array(
                    array('default', '=', '1'),
                ),
                'title' => __('Activate Lightbox', 'wc_svi'),
                //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
                //'desc' => __('Items set with a fold to this ID will hide unless this is set to the appropriate value.', 'wc_svi'),
                'default' => false,
            ),
            array(
                'title' => __('prettyPhoto Ligthbox Style', 'wc_svi'),
                //'subtitle' => __('Select your navigation color', 'wc_svi'),
                'desc' => __('Select your ligthbox style.', 'wc_svi'),
                'id' => 'prettyphoto_style',
                'required' => array(
                    array('lightbox', '=', '1'),
                    array('lightbox_new', '!=', '1'),
                    array('prettyphoto_themestyle', '!=', '1'),
                ),
                'default' => 'pp_woocommerce',
                'type' => 'select',
                'options' => array(
                    'pp_woocommerce' => 'WooCommerce Default',
                    'pp_default' => 'PrettyPhoto Default',
                    'light_rounded' => 'Light Rounded',
                    'light_square' => 'Light Square',
                    'dark_rounded' => 'Dark Rounded',
                    'dark_square' => 'Dark Square',
                    'facebook' => 'Facebook',
                )
            ),
            array(
                'id' => 'prettyphoto_themestyle',
                'type' => 'switch',
                'required' => array(
                    array('lightbox', '=', '1'),
                    array('lightbox_new', '!=', '1'),
                ),
                'title' => __('Use theme prettyPhoto style', 'wc_svi'),
                //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
                'desc' => __('Activate this option if your theme uses prettyPhoto as ligthbox and would like to use the themes style.', 'wc_svi'),
                'default' => false,
            ),
            array(
                'id' => 'lightbox_new',
                'type' => 'switch',
                'required' => array(
                    array('default', '=', '1'),
                    array('lightbox', '=', '1'),
                ),
                'title' => __('Lightbox Woo 3.0', 'wc_svi'),
                //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
                'desc' => __('Use the new WooCommerce 3.0 Ligthbox.', 'wc_svi'),
                'default' => false,
            ),
            array(
                'id' => 'ps_thumbs',
                'type' => 'switch',
                'required' => array(
                    array('lightbox', '=', '1'),
                    array('lightbox_new', '=', '1'),
                ),
                'title' => __('Show Thumbnails', 'wc_svi'),
                //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
                //'desc' => __('Use the new WooCommerce 3.0 Ligthbox.', 'wc_svi'),
                'default' => false,
            ),
            array(
                'id' => 'ps_close',
                'type' => 'switch',
                'required' => array(
                    array('lightbox', '=', '1'),
                    array('lightbox_new', '=', '1'),
                ),
                'title' => __('Show Close Button', 'wc_svi'),
                //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
                //'desc' => __('Use the new WooCommerce 3.0 Ligthbox.', 'wc_svi'),
                'default' => true,
            ),
            array(
                'id' => 'ps_caption',
                'type' => 'switch',
                'required' => array(
                    array('lightbox', '=', '1'),
                    array('lightbox_new', '=', '1'),
                ),
                'title' => __('Show Image Titles', 'wc_svi'),
                //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
                //'desc' => __('Use the new WooCommerce 3.0 Ligthbox.', 'wc_svi'),
                'default' => false,
            ),
            array(
                'id' => 'ps_fullscreen',
                'type' => 'switch',
                'required' => array(
                    array('lightbox', '=', '1'),
                    array('lightbox_new', '=', '1'),
                ),
                'title' => __('Show FullScreen Option', 'wc_svi'),
                //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
                //'desc' => __('Use the new WooCommerce 3.0 Ligthbox.', 'wc_svi'),
                'default' => false,
            ),
            array(
                'id' => 'ps_zoom',
                'type' => 'switch',
                'required' => array(
                    array('lightbox', '=', '1'),
                    array('lightbox_new', '=', '1'),
                ),
                'title' => __('Show Zoom Option', 'wc_svi'),
                //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
                //'desc' => __('Use the new WooCommerce 3.0 Ligthbox.', 'wc_svi'),
                'default' => false,
            ),
            array(
                'id' => 'ps_share',
                'type' => 'switch',
                'required' => array(
                    array('lightbox', '=', '1'),
                    array('lightbox_new', '=', '1'),
                ),
                'title' => __('Show Share Option', 'wc_svi'),
                //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
                //'desc' => __('Use the new WooCommerce 3.0 Ligthbox.', 'wc_svi'),
                'default' => false,
            ),
            array(
                'id' => 'ps_counter',
                'type' => 'switch',
                'required' => array(
                    array('lightbox', '=', '1'),
                    array('lightbox_new', '=', '1'),
                ),
                'title' => __('Show Counter Option', 'wc_svi'),
                //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
                //'desc' => __('Use the new WooCommerce 3.0 Ligthbox.', 'wc_svi'),
                'default' => false,
            ),
            array(
                'id' => 'ps_arrows',
                'type' => 'switch',
                'required' => array(
                    array('lightbox', '=', '1'),
                    array('lightbox_new', '=', '1'),
                ),
                'title' => __('Show Arrows Option', 'wc_svi'),
                //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
                //'desc' => __('Use the new WooCommerce 3.0 Ligthbox.', 'wc_svi'),
                'default' => true,
            ),
        )
    ));

    Redux::setSection($opt_name, array(
        'title' => __('Slider', 'wc_svi'),
        'id' => 'slider-subsection',
        //'desc' => __('For full documentation on validation, visit: ', 'wc_svi') . '<a href="//docs.reduxframework.com/core/the-basics/required/" target="_blank">docs.reduxframework.com/core/the-basics/required/</a>',
        'fields' => array(
            array(
                'id' => 'slider',
                'type' => 'switch',
                'required' => array(
                    array('default', '=', '1'),
                ),
                'title' => __('Activate Slider', 'wc_svi'),
                //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
                //'desc' => __('Activate slider', 'wc_svi'),
                'default' => false,
            ),
            array(
                'id' => 'slider_center',
                'type' => 'switch',
                'required' => array(
                    array('slider', '=', '1'),
                ),
                'title' => __('Deactivate Centered thumbnails', 'wc_svi'),
                //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
                'desc' => __('Thumbnails will be forced to start in begining of element.', 'wc_svi'),
                'default' => false,
            ),
            array(
                'id' => 'slider_navigation',
                'type' => 'switch',
                'required' => array(
                    array('slider', '=', '1'),
                ),
                'title' => __('Main Navigation', 'wc_svi'),
                'subtitle' => __('Add arrow navigation to main image.', 'wc_svi'),
                'default' => false,
            ),
            array(
                'id' => 'slider_navigation_thumb',
                'type' => 'switch',
                'required' => array(
                    array('slider', '=', '1'),
                ),
                'title' => __('Thumb Navigation', 'wc_svi'),
                'subtitle' => __('Add arrow navigation to thumbnails.', 'wc_svi'),
                'default' => false,
            ),
            /* array(
              'id' => 'static_thumb',
              'type' => 'switch',
              'required' => array(
              array('slider', '=', '1'),
              ),
              'title' => __('Disable thumbnail Slider', 'wc_svi'),
              'desc' => __('Removes slider in thumbnails, options in Thumbnails TAB', 'wc_svi'),
              'default' => false,
              ), */
            array(
                'title' => __('Nav Color', 'wc_svi'),
                //'subtitle' => __('Select your navigation color', 'wc_svi'),
                'desc' => __('Select your navigation color. Requires Main Navigation or Thumb navigation On.', 'wc_svi'),
                'id' => 'slider_navcolor',
                'required' => array(
                    array('slider', '=', '1'),
                ),
                'default' => 'blue',
                'type' => 'select',
                'options' => array(
                    'blue' => 'Blue',
                    '-white' => 'White',
                    '-black' => 'Black',
                )
            ),
            array(
                'id' => 'slider_autoslide',
                'type' => 'switch',
                'required' => array(
                    array('slider', '=', '1'),
                ),
                'title' => __('Auto Slide', 'wc_svi'),
                'subtitle' => __('Add auto sliding.', 'wc_svi'),
                'default' => false,
            ),
            array(
                'id' => 'slider_autoslide_ms',
                'type' => 'text',
                'required' => array('slider_autoslide', '=', '1'),
                'title' => __('Auto Slide time (ms)', 'wc_svi'),
                //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
                'desc' => __('Delay between transitions (in ms). If this parameter is not specified or is 0(zero), auto play will be 2500 (2, 5s)', 'wc_svi'),
                'validate' => 'numeric',
                'default' => '2500',
            ),
        )
    ));


    Redux::setSection($opt_name, array(
        'title' => __('Magnifier Lens', 'wc_svi'),
        'id' => 'lens-subsection',
        'fields' => array(
            array(
                'id' => 'lens',
                'type' => 'switch',
                'required' => array('default', '=', '1'),
                'title' => __('Activate Magnifier Lens', 'wc_svi'),
                //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
                //'desc' => __('Disabled on mobile phones.', 'wc_svi'),
                'default' => false,
            ),
            array(
                'title' => __('Lens Format', 'wc_svi'),
                //'desc' => __('Select thumnails position. Bottom, Left.',  'wc_svi'),
                'id' => 'lens_type',
                'required' => array('lens', '=', '1'),
                'default' => 'round',
                'type' => 'image_select',
                'options' => array(
                    'round' => plugins_url('admin/assets/img/sviRound.png', dirname(__FILE__)),
                    'square' => plugins_url('admin/assets/img/sviSquare.png', dirname(__FILE__)),
                )
            ),
            array(
                'title' => __('Disable Lens Zoom Contain', 'wc_svi'),
                //'desc' => __('Select thumnails position. Bottom, Left.',  'wc_svi'),
                'id' => 'containlenszoom',
                'required' => array('lens', '=', '1'),
                'type' => 'switch',
                'required' => array(
                    array('lens', '=', '1'),
                ),
                'desc' => __('NOTE: If active in some themes this option may not work properly.', 'wc_svi'),
                'default' => false,
            ),
            array(
                'id' => 'lens_easing',
                'type' => 'switch',
                'required' => array(
                    array('lens', '=', '1'),
                ),
                'title' => __('Lens Easing', 'wc_svi'),
                'desc' => __('Allows smooth scrool of image to Zoom Type Window & Inner', 'wc_svi'),
                'default' => false,
            ),
            array(
                'id' => 'lens_size',
                'type' => 'text',
                'required' => array('lens', '=', '1'),
                'title' => __('Lens Size', 'wc_svi'),
                //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
                'desc' => __('Lens size to be displayed, min:100 | max: 300.', 'wc_svi'),
                'validate' => 'numeric',
                'default' => '150',
            ),
            array(
                'id' => 'lens_border',
                'type' => 'color',
                'required' => array('lens', '=', '1'),
                'title' => __('Lens border Color', 'wc_svi'),
                'desc' => __('Pick a border color for the lens.', 'wc_svi'),
                'default' => '#888',
                'validate' => 'color',
            ),
            array(
                'title' => __('Zoom Type', 'wc_svi'),
                'id' => 'lens_zoomtype',
                'default' => 'lens',
                'type' => 'select',
                'required' => array(
                    array('lens', '=', '1'),
                ),
                'options' => array(
                    'lens' => __('Lens', 'wc_svi'),
                    'window' => __('Window', 'wc_svi'),
                    'inner' => __('Inner', 'wc_svi'),
                ),
            ),
            array(
                'id' => 'lens_scrollzoom',
                'type' => 'switch',
                'required' => array(
                    array('lens', '=', '1'),
                ),
                'title' => __('Zoom Effect', 'wc_svi'),
                'desc' => __('Allows Zoom with mouse scroll.', 'wc_svi'),
                'default' => false,
            ),
            array(
                'id' => 'lens_fade',
                'type' => 'switch',
                'required' => array(
                    array('lens', '=', '1'),
                ),
                'title' => __('Fade Effect', 'wc_svi'),
                'default' => false,
            ),
        )
    ));


    Redux::setSection($opt_name, array(
        'title' => __('Thumbails', 'wc_svi'),
        'id' => 'thumbs-subsection',
        //'desc' => __('For full documentation on validation, visit: ', 'wc_svi') . '<a href="//docs.reduxframework.com/core/the-basics/required/" target="_blank">docs.reduxframework.com/core/the-basics/required/</a>',
        'fields' => array(
            array(
                'id' => 'disable_thumb',
                'type' => 'switch',
                'required' => array(
                    array('default', '=', '1'),
                ),
                'title' => __('Disable Thumbnails', 'wc_svi'),
                'desc' => __('Disable thumbnails on product page', 'wc_svi'),
                'on' => __('Yes', 'wc_svi'),
                'off' => __('No', 'wc_svi'),
                'default' => false,
            ),
            array(
                'title' => __('Thumbnail Position', 'wc_svi'),
                'subtitle' => __('Select thumnails position. Bottom, Left or right.', 'wc_svi'),
                'desc' => __('Bottom, Left and Right positions, for thumbnails.', 'wc_svi'),
                'id' => 'slider_position',
                'required' => array(
                    array('disable_thumb', '!=', '1'),
                ),
                'default' => 0,
                'type' => 'image_select',
                'options' => array(
                    0 => plugins_url('admin/assets/img/sviBottom.png', dirname(__FILE__)),
                    1 => plugins_url('admin/assets/img/sviLeft.png', dirname(__FILE__)),
                    2 => plugins_url('admin/assets/img/sviRight.png', dirname(__FILE__))
                )
            ),
            array(
                'id' => 'columns',
                'type' => 'text',
                'required' => array(
                    array('disable_thumb', '!=', '1'),
                    array('slider_position', '=', '0'),
                ),
                'title' => __('Thumbnail Items', 'wc_svi'),
                //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
                'desc' => __('Number of thumbnails to be displayed by row, min:1 | max: 10.', 'wc_svi'),
                'validate' => 'numeric',
                'default' => '4',
            ),
            array(
                'id' => 'hide_thumbs',
                'type' => 'switch',
                'required' => array(
                    array('disable_thumb', '!=', '1'),
                ),
                'title' => __('Hidden Thumbnails', 'wc_svi'),
                //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
                'desc' => __('Thumbnails will be hidden until a variation as been selected.', 'wc_svi'),
                'default' => false,
            ),
            array(
                'id' => 'swselect',
                'type' => 'switch',
                'required' => array(
                    array('disable_thumb', '!=', '1'),
                    array('triger_match', '!=', '1')
                ),
                'title' => __('Select Swap', 'wc_svi'),
                //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
                'desc' => __('All selects will trigger the thumbnail swaps. Don\'t have to wait for select combination.', 'wc_svi'),
                'default' => false,
            ),
            /* array(
              'id' => 'notice5',
              'type' => 'info',
              'style' => 'warning',
              'required' => array(
              array('disable_thumb', '!=', '1'),
              array('keep_thumbnails', 'equals', '1'),
              ),
              //'subtitle' => __('Add arrow navigation to main image.', 'wc_svi'),
              'desc' => __('Select Swap disabled. To activate switch Keep Thumbnails visible <b>off</b>.', 'wc_svi')
              ), */
            array(
                'id' => 'variation_swap',
                'type' => 'switch',
                'required' => array(
                    array('disable_thumb', '!=', '1'),
                    array('hide_thumbs', '!=', '1'),
                ),
                'title' => __('Thumbnail Click Swap', 'wc_svi'),
                //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
                'desc' => __('Swap select box(es) to match variation on thumbnail click.', 'wc_svi'),
                'default' => false,
            ),
            array(
                'id' => 'keep_thumbnails',
                'type' => 'switch',
                'required' => array(
                    array('disable_thumb', '!=', '1'),
                    array('hide_thumbs', '!=', '1'),
                ),
                'title' => __('Keep Thumbnails visible', 'wc_svi'),
                //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
                'desc' => __('This option will keep thumbnails visible all the time. No changes will be made to the images.', 'wc_svi'),
                'default' => false,
            ),
            array(
                'id' => 'thumbnails_showactive',
                'type' => 'switch',
                'required' => array(
                    array('disable_thumb', '!=', '1'),
                ),
                'title' => __('Thumbnail Opacity', 'wc_svi'),
                //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
                'desc' => __('If active, current tumbnail will be faded.', 'wc_svi'),
                'default' => false,
            ),
            array(
                'id' => 'notice4',
                'type' => 'info',
                'style' => 'warning',
                'required' => array(
                    array('disable_thumb', '!=', '1'),
                    array('hide_thumbs', 'equals', '1'),
                ),
                //'subtitle' => __('Add arrow navigation to main image.', 'wc_svi'),
                'desc' => __('Thumbnail Click Swap disabled. To activate switch Hidden Thumbnails options <b>off</b>.', 'wc_svi')
            ),
        )
    ));

    Redux::setSection($opt_name, array(
        'title' => __('Layout Fixes', 'wc_svi'),
        'id' => 'fixes-subsection',
        //'desc' => __('For full documentation on validation, visit: ', 'wc_svi') . '<a href="//docs.reduxframework.com/core/the-basics/required/" target="_blank">docs.reduxframework.com/core/the-basics/required/</a>',
        'fields' => array(
            array(
                'id' => 'custom_class',
                'type' => 'text',
                'required' => array(
                    array('default', '=', '1'),
                ),
                'title' => __('Custom Class', 'wc_svi'),
                //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
                'desc' => __('Insert custom css class(es) to fit your theme needs.', 'wc_svi'),
            ),
            array(
                'id' => 'sviforce_image',
                'type' => 'switch',
                'required' => array(
                    array('default', '=', '1'),
                ),
                'title' => __('Remove Image class', 'wc_svi'),
                //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
                'desc' => __('Some theme force styling on image class that may break the layout.', 'wc_svi'),
                'default' => false,
            ),
            array(
                'id' => 'imagesloaded',
                'type' => 'switch',
                'required' => array(
                    array('default', '=', '1'),
                ),
                'title' => __('Image Loaded', 'wc_svi'),
                //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
                'desc' => __('Fix Image Loaded conflicts.', 'wc_svi'),
                'default' => false,
            ),
        )
    ));

    Redux::setSection($opt_name, array(
        'title' => __('License Control', 'wc_svi'),
        'id' => 'licensecontrol',
        //'desc' => __('General settings', 'wc_svi'),
        'icon' => 'el el-home',
        'fields' => array(
            array(
                'id' => 'svipro_softlicense_key',
                'type' => 'text',
                'title' => __('License Key', 'wc_svi    '),
                'desc' => __('Please supply the license sent on your complete order email.', 'wc_svi'),
                'readonly' => true,
                //'validate_callback' => 'svipro_license_validation',
                //'validate' => 'not_empty',
                //'msg' => 'custom error message',
                'placeholder' => 'SVIPRO-XXXXXXXXXXXXX'
            ),
            array(
                'id' => 'deletekey',
                'type' => 'switch',
                'title' => __('Delete License Key', 'wc_svi'),
                'desc' => __('Release SVI PRO license key from running on this site in order to use on another site.', 'wc_svi'),
                'on' => __('Active', 'wc_svi'),
                'off' => __('Release Key', 'wc_svi'),
                'default' => true,
            ),
            array(
                'id' => 'info_normal_svi',
                'type' => 'info',
                'desc' => '<h4>You must ensure that your environment meets the following minimum conditions:</h4>
                                    <ul>
                                        <li>PHP 5.6.30 or later (current system version: <b>' . phpversion() . '</b>)</li>
                                        <li>WordPress 4.0 or later</li>
                                        <li>Can\'t activate license? Read FAQ about <a href="//www.rosendo.pt/question/cannot-activate-license/">Cannot activate license</a> </li>
    </ul>')
        ),
            )
    );
}

if (!function_exists('svipro_license_validation')):

    function svipro_license_validation($field, $value, $existing_value) {

        $return['value'] = $existing_value;
        if ($value !== $existing_value) {

            $valid = new woocommerce_svi_general();

            $result = $valid->activate_license($value);

            switch ($result->status) {
                case 'success':
                    $return['value'] = $value;
                    break;
                case 'error':
                    if ($result->status_code == 'e113') {
                        $return['value'] = $value;
                    } else {
                        delete_option('woosvi_options');
                        $return['value'] = '';
                        $field['msg'] = $result->message;
                        $return['error'] = $field;
                    }
                    break;
            }
        }
        return $return;
    }

endif;


Redux::setSection($opt_name, array(
    'title' => __('Support', 'wc_svi'),
    'id' => 'info-svi',
    'fields' => array(
        array(
            'id' => 'info_support_svi',
            'type' => 'info',
            'desc' => 'All support for my plugins are provided as a free service at <a href="http://www.rosendo.pt" target="_blank">www.rosendo.pt</a>.<br>
Purchasing an addon from this site does not gain you priority over response times on the support system.<br>
<br>
<b>Please note that WordPress has a big history of conflicts between plugins.</b><br>
<br>
The support works, <b>Lisbon, Portugal time zone</b> form <b>9am to 6pm</b>.<br>
I\'m not here full-time so please be patient, I will try my best to help you out as much as I can.<br>
<h2>Steps:</h2>
<ul>
<li>- Go to <a href="http://www.rosendo.pt" target="_blank">www.rosendo.pt</a> and login</li>
<li>- On the right sidebar you will see an option saying <b><a href="http://www.rosendo.pt/submit-ticket/" target="_blank">Submit Ticket</a></b></li>
<li>- Please supply me with information such <b>credentials</b> to your <b>wp-admin</b> and optionally <b>direct FTP access to my plugin</b>.</li>
</ul>
<br>
<a href="http://www.rosendo.pt/terms-conditions/">Terms & Conditions</a>
<br>
<h2>Setup instructions</h2>
Please visit the free version of this plugin for instructions (view screenshoots), click <a href="https://wordpress.org/plugins/smart-variations-images/screenshots/">here</a>.
'),
    )
));

/*
     * <--- END SECTIONS
     */
