<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class woocommerce_svi_admin {

    private static $_this;

    /**
     * init
     *
     * @since 1.0.0
     * @return bool
     */
    public function __construct() {

        add_action('admin_init', array($this, 'reduxVerify'));

        add_action('admin_enqueue_scripts', array($this, 'load_admin_scripts'), 150);
        add_filter((is_multisite() ? 'network_admin_' : '') . 'plugin_action_links', array($this, 'plugin_action_links'), 10, 2);

        include_once( 'admin/admin-init.php' );
        add_filter('attachment_fields_to_edit', array($this, 'woo_svi_field'), 10, 2);
        add_filter('attachment_fields_to_save', array($this, 'woo_svi_field_save'), 10, 2);

        add_action('woocommerce_product_write_panel_tabs', array($this, 'sviproimages_section'));
        if ($this->version_check()) {
            add_action('woocommerce_product_data_panels', array($this, 'sviproimages_settings'));
        } else {
            add_action('woocommerce_product_write_panels', array($this, 'sviproimages_settings'));
        }

        add_action('wp_ajax_woosvi_reloadselect', array($this, 'reloadSelect_json'));

        add_action('save_post', array($this, 'save_woosvibulk_meta'), 10, 3);
        $role = get_role('shop_manager');
        $role->add_cap('manage_options');

        return true;
    }

    /**
     * Check if reduxFramework plugin is installed and running
     * 
     *
     * @since 1.0.0
     * @return 
     */
    function reduxVerify() {

        if (!is_plugin_active('redux-framework/redux-framework.php')) {
            add_action('admin_notices', array($this, 'sample_admin_notice__success'));
        }
    }

    /**
     * Adds the settings link under the plugin on the plugin screen.
     * 
     *
     * @since 1.0.0
     * @return 
     */
    public function plugin_action_links($links, $file) {
        if (is_array($links) && $file == 'smart-variations-images-pro/svipro.php') {
            $settings_link = '<a href="admin.php?page=woocommerce_svi">' . __("Settings", "wc_svi") . '</a>';
            array_unshift($links, $settings_link);
        }
        return $links;
    }

    /**
     * Sends notification to user if reduxFramework not installed or active
     * 
     *
     * @since 1.0.0
     * @return 
     */
    function sample_admin_notice__success() {
        ?>
        <div class="notice notice-error woosvi-notice-dismissed is-dismissible">
            <p>
            <h3> <strong>SVI NOTICE</strong></h3>Starting version 3.2, SVI requires the following plugin to be installed: <a href="<?php echo network_admin_url('plugin-install.php?tab=plugin-information&plugin=redux-framework&TB_iframe=true&width=600&height=600'); ?>">ReduxFramework</a> in order to work.
            <br>
            <h5>If you are pleased with the PRO version please leave a review <a href="https://www.rosendo.pt/product/smart-variations-images-pro-reviews/" target="_blank">here</a> so that I keep improving the version.</h5>
        </p>
        </div>
        <?php
    }

    /**
     * Dismiss notice
     * 
     *
     * @since 1.0.0
     * @return 
     */
    function woosvi_dismiss_notice() {
        update_option('woosvi-notice-dismissed', true);
        header("Content-type: application/json");
        echo json_encode(true);
        die();
    }

    /**
     * load admin scripts
     *
     * @since 1.0.0
     * @return bool
     */
    function load_admin_scripts() {
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        $screen = get_current_screen();
        if ($screen->post_type == 'product') {
            wp_enqueue_style('woo_svicss_admin', plugins_url('assets/css/woo_svi_admin.css', dirname(__FILE__)), null, null);
        }

        wp_enqueue_script('woo_svijs', plugins_url('assets/js/svi-admin-settings' . $suffix . '.js', dirname(__FILE__)), array('jquery'));
    }

    /**
     * Add menu items.
     */
    function svi_menu() {
        add_submenu_page('woocommerce', $this->title, $this->menutitle, 'manage_woocommerce', 'woocommerce_svi', array($this, 'options_page'));
    }

    /**
     * Add woovsi field to media uploader
     *
     * @param $form_fields array, fields to include in attachment form
     * @param $post object, attachment record in database
     * @return $form_fields, modified form fields
     */
    function woo_svi_field($form_fields, $post) {

        if (isset($_POST['post_id']) && $_POST['post_id'] != '0') {
            $variations = false;

            $attributes = get_post_meta($_POST['post_id'], '_product_attributes');

            if (!empty($attributes)) {
                $variations = true;

                $current = get_post_meta($post->ID, 'woosvi_slug_' . $_POST['post_id'], true);
                if (!$current)
                    $current = get_post_meta($post->ID, 'woosvi_slug', true);

                $html = "<select name='attachments[{$post->ID}][woosvi-slug]' id='attachments[{$post->ID}][woosvi-slug]' style='width:100%;'>";
                $html .= "<option value='' " . selected($current, '', false) . ">Select Variation Or None</option>";
                $html .= "<option value='sviproglobal' " . selected($current, 'sviproglobal', false) . ">SVI Global</option>";
                $existing = array();
                foreach ($attributes[0] as $att => $attribute) {

                    if ($attribute['is_taxonomy'] && $attribute['is_variation']) {


                        $terms = wp_get_post_terms($_POST['post_id'], urldecode($att), 'all');
                        if (!empty($terms)) {
                            $tax = get_taxonomy($att);

                            $html .= '<optgroup label="' . $tax->label . '">';
                            foreach ($terms as $tr => $term) {
                                $html .= "<option value='" . urldecode($term->slug) . "' " . selected($current, urldecode($term->slug), false) . ">" . $term->name . "</option>";

                                array_push($existing, urldecode($term->slug));
                            }
                            $html .= '</optgroup>';
                        }
                    } else if (!$attribute['is_taxonomy'] && $attribute['is_variation']) {

                        $values = str_replace(" ", "", $attribute['value']);
                        $terms = explode('|', $values);
                        $html .= '<optgroup label="' . $attribute['name'] . '">';
                        foreach ($terms as $tr => $term) {
                            $html .= "<option value='" . strtolower($term) . "' " . selected($current, strtolower($term), false) . ">" . $term . "</option>";
                            array_push($existing, strtolower($term));
                        }
                        $html .= '</optgroup>';
                    }
                }
                $html .= "</select>";

                if ($variations) {
                    $form_fields['woosvi-slug'] = array(
                        'label' => 'Variation',
                        'input' => 'html',
                        'html' => $html,
                        'application' => 'image',
                        'exclusions' => array(
                            'audio',
                            'video'
                        ),
                        'helps' => $helps . 'Choose the variation'
                    );
                } else {
                    $form_fields['woosvi-slug'] = array(
                        'label' => 'Variation',
                        'input' => 'html',
                        'html' => 'This product doesn\'t seem to be using any variations.',
                        'application' => 'image',
                        'exclusions' => array(
                            'audio',
                            'video'
                        ),
                        'helps' => 'Add variations to the product and Save'
                    );
                }
            }
        }
        return $form_fields;
    }

    /**
     * Save values of woovsi in media uploader
     *
     * @param $post array, the post data for database
     * @param $attachment array, attachment fields from $_POST form
     * @return $post array, modified post data
     */
    function woo_svi_field_save($post, $attachment) {

        if (isset($attachment['woosvi-slug'])) {
            update_post_meta($post['ID'], 'woosvi_slug_' . $_POST['post_id'], $attachment['woosvi-slug']);
        } else {
            delete_post_meta($post['ID'], 'woosvi_slug_' . $_POST['post_id']);
            delete_post_meta($post['ID'], 'woosvi_slug');
        }

        return $post;
    }

    /**
     * Add tab to WooCommerce Product
     * 
     *
     * @since 1.0.0
     * @return HTML
     */
    function sviproimages_section() {
        ?>
        <li class="box_tab show_if_variable"><a href="#sviproimages_tab_data" id="svibulkbtn"><?php _e('SVI Variations Gallery', 'woocommerce'); ?></a></li>
        <?php
    }

    /**
     * Builds Html with content of TAB
     * 
     *
     * @since 1.0.0
     * @return HTML
     */
    function sviproimages_settings() {

        echo '<div id="sviproimages_tab_data" class="panel woocommerce_options_panel wc-metaboxes-wrapper">';
        echo '<div class="wc-metabox">';
        $this->buildSelect();
        echo '</div>';
        echo '</div>';
    }

    /**
     * Builds the varitions display over AJAX Call
     * 
     *
     * @since 1.0.0
     * @return HTML
     */
    function reloadSelect_json() {
        $pid = $_POST['data'];

        $attributes = get_post_meta($pid, '_product_attributes');

        $return = '';
        if (count($attributes) < 1) {

            $return .= '<div id="message" class="inline notice woocommerce-message">';
            $return .= '<p>Before you can add images in bulk to a variation you need to add some variation attributes on the <strong>Attributes</strong> tab.</p>';
            $return .= '</div>';

            echo $return;
            die();
        }

        $return .= "<select id='sviprobulk' multiple='multiple'>";
        $return .= "<option value='sviproglobal'>SVI Global</option>";
        $existing = array();
        foreach ($attributes[0] as $att => $attribute) {

            if ($attribute['is_taxonomy'] && $attribute['is_variation']) {

                $terms = wp_get_post_terms($pid, urldecode($att), 'all');


                if (!empty($terms)) {
                    $tax = get_taxonomy($att);

                    $return .= '<optgroup label="' . $tax->label . '">';
                    foreach ($terms as $tr => $term) {
                        $return .= "<option value='" . urldecode($term->slug) . "'>" . $term->name . "</option>";
                        array_push($existing, urldecode($term->slug));
                    }
                    $return .= '</optgroup>';
                }
            } else if (!$attribute['is_taxonomy'] && $attribute['is_variation']) {

                $values = str_replace(" ", "", $attribute['value']);
                $terms = explode('|', $values);
                $return .= '<optgroup label="' . $attribute['name'] . '">';
                foreach ($terms as $tr => $term) {

                    $return .= "<option value='" . strtolower(urldecode($term)) . "'>" . urldecode($term) . "</option>";
                    array_push($existing, strtolower(urldecode($term)));
                }
                $return .= '</optgroup>';
            }
        }
        $return .= "</select>";
        //$return .= "</div>";


        echo $return;
        die();
    }

    /**
     * Builds the varitions display over AJAX Call
     * 
     *
     * @since 1.0.0
     * @return HTML
     */
    function buildSelect_json() {
        $pid = $_POST['data'];

        $attributes = get_post_meta($pid, '_product_attributes');

        $return = '';
        if (count($attributes) < 1) {

            $return .= '<div id="message" class="inline notice woocommerce-message">';
            $return .= '<p>Before you can add images in bulk to a variation you need to add some variation attributes on the <strong>Attributes</strong> tab.</p>';
            $return .= '</div>';

            echo $return;
            die();
        }

        $return .= '<div class="wc-metabox-content">';
        $return .= '<table cellspacing="0" cellpadding="0">';
        $return .= '<tr>';
        $return .= ' <td class="attribute_name">';
        $return .= '<strong>Assign Images to : </strong>';
        $return .= '</td>';
        $return .= '<td>';
        $return .= "<div id='sviselect_container'>";
        $return .= "<select id='sviprobulk' multiple='multiple'>";
        $return .= "<option value='sviproglobal'>SVI Global</option>";
        //$return .= "<option value='' selected='selected'>Choose variation to add images</option>";
        $existing = array();
        foreach ($attributes[0] as $att => $attribute) {

            if ($attribute['is_taxonomy'] && $attribute['is_variation']) {

                $terms = wp_get_post_terms($pid, urldecode($att), 'all');

                if (!empty($terms)) {
                    $tax = get_taxonomy($att);

                    $return .= '<optgroup label="' . $tax->label . '">';
                    foreach ($terms as $tr => $term) {
                        $return .= "<option value='" . urldecode($term->slug) . "'>" . $term->name . "</option>";
                        array_push($existing, urldecode($term->slug));
                    }
                    $return .= '</optgroup>';
                }
            } else if (!$attribute['is_taxonomy'] && $attribute['is_variation']) {

                $values = str_replace(" ", "", $attribute['value']);
                $terms = explode('|', $values);
                $return .= '<optgroup label="' . $attribute['name'] . '">';
                foreach ($terms as $tr => $term) {

                    $return .= "<option value='" . strtolower(urldecode($term)) . "'>" . urldecode($term) . "</option>";
                    array_push($existing, strtolower(urldecode($term)));
                }
                $return .= '</optgroup>';
            }
        }
        $return .= "</select>";
        $return .= "</div>";
        $return .= '<b><small><sup>*</sup>SVI Global: Use this variation too assign global images (displayed in all variations).</small></b> <button id="addsviprovariation" class="button fr plus">Add</button>';
        $return .= '</td>';
        $return .= '</tr>';
        $return .= '</table>';
        $return .= '</div>';

        $return .= '<div class="clear"></div>';

        $return .= '<div id="svigallery">';
        $return .= $this->output($pid, $existing);
        $return .= '</div>';

        $return .= '<div id="svipro_clone" class="postbox svi-woocommerce-product-images hidden" data-svigal="">';
        $return .= '<h2><span>Product Gallery</span><a href="#/" class="svi-pullright sviprobulk_remove">Remove</a></h2>';
        $return .= '<div class="inside">';
        $return .= '<div class="svipro-product_images_container">';
        $return .= '<ul class="product_images ui-sortable">';
        $return .= '</ul>';
        $return .= ' <input class="svipro-product_image_gallery" name="" value="" type="hidden">';
        $return .= '</div>';
        $return .= '<p class="add_product_images_svipro hide-if-no-js">';
        $return .= '<a href="#/" data-choose="Add Images to Product Gallery" data-update="Add to gallery" data-delete="Delete image" data-text="Delete">Add product gallery images</a>';
        $return .= ' </p>';
        $return .= '</div>';
        $return .= '</div>';


        echo $return;
        die();
    }

    /**
     * Builds the varitions display on product page load
     * 
     *
     * @since 1.0.0
     * @return HTML
     */
    function buildSelect($id = false) {
        global $post;

        $pid = $post->ID;


        $attributes = get_post_meta($pid, '_product_attributes');
        if (count($attributes) < 1) {

            echo '<div id="message" class="inline notice woocommerce-message">';
            echo '<p>Before you can add images in bulk to a variation you need to add some variation attributes on the <strong>Attributes</strong> tab.</p>';
            echo '</div>';
        }


        echo '<div class="wc-metabox-content">';
        echo '<table cellspacing="0" cellpadding="0">';
        echo '<tr>';
        echo ' <td class="attribute_name">';
        echo '<strong>Assign Images to : </strong>';
        echo '</td>';
        echo '<td>';
        echo "<div id='sviselect_container'>";
        echo "<select id='sviprobulk' multiple='multiple'>";
        echo "<option value='sviproglobal'>SVI Global</option>";
        //echo "<option value='' selected='selected'>Choose variation to add images</option>";
        $existing = array();
        array_push($existing, 'sviproglobal');
        foreach ($attributes[0] as $att => $attribute) {

            if ($attribute['is_taxonomy'] && $attribute['is_variation']) {

                $terms = wp_get_post_terms($pid, urldecode($att), 'all');
                if (!empty($terms)) {
                    $tax = get_taxonomy($att);

                    echo '<optgroup label="' . $tax->label . '">';
                    foreach ($terms as $tr => $term) {
                        echo "<option value='" . urldecode($term->slug) . "'>" . $term->name . "</option>";
                        array_push($existing, urldecode($term->slug));
                    }
                    echo '</optgroup>';
                }
            } else if (!$attribute['is_taxonomy'] && $attribute['is_variation']) {

                $values = str_replace(" ", "", $attribute['value']);
                $terms = explode('|', $values);
                echo '<optgroup label="' . $attribute['name'] . '">';
                foreach ($terms as $tr => $term) {

                    echo "<option value='" . strtolower(urldecode($term)) . "'>" . urldecode($term) . "</option>";
                    array_push($existing, strtolower(urldecode($term)));
                }
                echo '</optgroup>';
            }
        }
        echo "</select>";
        echo "</div>";
        echo '<b><small><sup>*</sup>SVI Global: Use this variation too assign global images (displayed in all variations).</small></b> <button id="addsviprovariation" class="button fr plus">Add</button>';
        echo '</td>';
        echo '</tr>';
        // echo '<tr><td colspan="2"><b><small><sup>*</sup>SVI Global: Use this variation too assign global images to be showed in all variations.</small></b></td></tr>';
        echo '</table>';
        echo '</div>';

        echo '<div class="clear"></div>';

        echo '<div id="svigallery">';


        echo $this->output($pid, $existing);
        echo '</div>';

        echo '<div id="svipro_clone" class="postbox svi-woocommerce-product-images hidden" data-svigal="">';
        echo '<h2><span>Product Gallery</span><a href="#/" class="svi-pullright sviprobulk_remove">Remove</a></h2>';
        echo '<div class="inside">';
        echo '<div class="svipro-product_images_container">';
        echo '<ul class="product_images ui-sortable">';
        echo '</ul>';
        echo ' <input class="svipro-product_image_gallery" name="" value="" type="hidden">';
        echo '</div>';
        echo '<p class="add_product_images_svipro hide-if-no-js">';
        echo '<a href="#/" data-choose="Add Images to Product Gallery" data-update="Add to gallery" data-delete="Delete image" data-text="Delete">Add product gallery images</a>';
        echo ' </p>';
        echo '</div>';
        echo '</div>';
    }

    /**
     * Returns the variations tab + images
     * 
     *
     * @since 1.0.0
     * @return HTML
     */
    function output($pid, $existing = false) {

        $return = '';

        if (metadata_exists('post', $pid, '_product_image_gallery')) {
            $product_image_gallery = explode(',', get_post_meta($pid, '_product_image_gallery', true));
        } else {
            // Backwards compat
            $attachment_ids = get_posts('post_parent=' . $pid . '&numberposts=-1&post_type=attachment&orderby=menu_order&order=ASC&post_mime_type=image&fields=ids&meta_key=_woocommerce_exclude_image&meta_value=0');
            $attachment_ids = array_diff($attachment_ids, array(get_post_thumbnail_id()));
            if ($attachment_ids)
                $product_image_gallery = explode(',', $attachment_ids);
        }

        if (count($product_image_gallery) < 1)
            return;

        $product_image_gallery = array_filter($product_image_gallery);

        $order = array();

        foreach ($product_image_gallery as $key => $value) {

            $woosvi_slug = get_post_meta($value, 'woosvi_slug_' . $pid, true);

            if (is_array($woosvi_slug)) {

                $data = array();

                foreach ($woosvi_slug as $k => $v) {

                    if (count($v) > 1) {
                        $data[] = implode('_svipro_', $v);
                    } else {
                        $data[] = $v;
                    }
                }

                $woosvi_slug = $data;
            }

            if (!$woosvi_slug)
                $woosvi_slug = get_post_meta($value, 'woosvi_slug', true);

            if (!$woosvi_slug)
                $woosvi_slug = 'nullsvi';




            if (is_array($woosvi_slug)) {

                foreach ($woosvi_slug as $k => $v) {
                    if (is_array($v))
                        $order[$v[0]][] = $value;
                    else
                        $order[$v][] = $value;
                }
            } else {
                $order[$woosvi_slug][] = $value;
            }
        }
        $count = 0;
        foreach ($order as $key => $attachments) {

            if ($key != 'nullsvi') {

                $variation = strpos($key, '_svipro_');

                if ($variation === false) {
                    $variation = $key;
                } else {
                    $variation = explode('_svipro_', $key);
                }

                $containsCombo = false;

                if (is_array($variation)) {
                    $contains = count(array_intersect($variation, $existing)) == count($variation);
                    $containsCombo = boolval($contains);
                }

                if (is_array($existing) && !in_array($variation, $existing) && !$containsCombo) {

                    if (count($attachments) > 0) {

                        foreach ($attachments as $attachment_id) {
                            delete_post_meta($attachment_id, 'woosvi_slug');
                            delete_post_meta($attachment_id, 'woosvi_slug_' . $pid);
                        }
                    }
                    break;
                }


                if (is_array($variation))
                    $title = implode(' + ', $variation) . ' Gallery';
                else
                    $title = ($variation == 'sviproglobal') ? 'Global Gallery' : $variation . ' Gallery';




                $return .= $this->outputOrder($title, $attachments, $key, $count);
                $count++;
            }
        }



        $key = 'nullsvi';
        $title = "No variations assigned";

        if (array_key_exists('nullsvi', $order))
            $return .= $this->outputOrder($title, $order['nullsvi'], $key);


        return $return;
    }

    /**
     * Builds the output order for the variations
     * 
     *
     * @since 1.0.0
     * @return HTML
     */
    function outputOrder($title, $attachments, $key, $count = 'null') {

        $return = '';


        if (count($attachments) > 0) {
            $return .= '<div id="svipro_' . $count . '" class="postbox svi-woocommerce-product-images" data-svigal="' . $key . '" data-svikey="' . $count . '">';
            $return .= '<h2><span>' . str_replace('_svipro_', ' + ', $title) . '</span> <a href="#/" class="svi-pullright sviprobulk_remove">Remove</a></h2>';
            $return .= '<div class="inside">';
            $return .= '<div class="svipro-product_images_container">';
            $return .= '<ul class="product_images ui-sortable">';
            $product_image_gallery_svi = '';

            foreach ($attachments as $attachment_id) {
                $attachment = wp_get_attachment_image($attachment_id, 'thumbnail');

                // if attachment is empty skip
                if (empty($attachment)) {
                    $update_meta = true;
                    continue;
                }

                $return .= '<li class="image" data-attachment_id="' . esc_attr($attachment_id) . '">' . $attachment . '<ul class="actions"><li><a href="#/" class="delete tips" data-tip="' . esc_attr__('Delete image', 'woocommerce') . '">' . __('Delete', 'woocommerce') . '</a></li></ul></li>';
            }
            $product_image_gallery_svi = implode(',', $attachments);

            $return .= '</ul>';
            $return .= '<input class="svipro-product_image_gallery" name="sviproduct_image_gallery[' . $key . ']" value="' . $product_image_gallery_svi . '" type="hidden">';
            $return .= '</div>';
            $return .= '<p class="add_product_images_svipro hide-if-no-js">';
            $return .= '<a href="#/" data-choose="Add Images to Product Gallery" data-update="Add to gallery" data-delete="Delete image" data-text="Delete">Add product gallery images</a>';
            $return .= '</p>';
            if ($key == 'sviproglobal')
                $return .= '<p><b>NOTICE: Images in this gallery will be showed in all variations.</b></p>';

            $return .= '</div>';
            $return .= '</div>';
        }

        return $return;
    }

    /**
     * Saves the variation information on Save
     * 
     *
     * @since 1.0.0
     * @return HTML
     */
    function save_woosvibulk_meta($post_id, $post, $update) {
        /*
         * In production code, $slug should be set only once in the plugin,
         * preferably as a class property, rather than in each function that needs it.
         */
        $post_type = get_post_type($post_id);

        // If this isn't a 'book' post, don't update it.
        if ("product" != $post_type)
            return;

        $attachment_ids = isset($_POST['product_image_gallery']) ? array_filter(explode(',', wc_clean($_POST['product_image_gallery']))) : array();

        foreach ($attachment_ids as $key => $value) {
            delete_post_meta($value, 'woosvi_slug');
            delete_post_meta($value, 'woosvi_slug_' . $post_id);
        }

        if (!isset($_POST['sviproduct_image_gallery']))
            return;

        $bulk = $_POST['sviproduct_image_gallery'];

        $keys = array();
        if ($bulk['nullsvi']) {
            foreach ($bulk['nullsvi'] as $value) {
                $ids = explode(',', wc_clean($value));
                foreach ($ids as $id) {
                    delete_post_meta($id, 'woosvi_slug');
                    delete_post_meta($id, 'woosvi_slug_' . $post_id);
                }
            }
        }

        $ordered = array();

        foreach ($bulk as $key => $value) {
            $array_key = explode(',', $value);

            if ($key != 'nullsvi' && $array_key !== '') {

                foreach ($array_key as $k => $v) {

                    $ordered[$v][] = explode('_svipro_', $key);
                }
            }
        }


        foreach ($ordered as $id => $value) {
            $res = update_post_meta($id, 'woosvi_slug_' . $post_id, $value);
        }
    }

    public static function version_check($version = '3.0') {
        if (class_exists('WooCommerce')) {
            global $woocommerce;
            if (version_compare($woocommerce->version, $version, ">=")) {
                return true;
            }
        }
        return false;
    }

}

//update_post_meta($id, 'woosvi_slug_' . $post_id, $key);
new woocommerce_svi_admin();

