<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class woocommerce_svi_general {

    private static $_this;

    /**
     * init
     *
     * @since 1.0.0
     * @return bool
     */
    public function __construct() {

        $this->api_url = 'https://www.rosendo.pt/index.php';
        $this->slug = 'SVIPRO';
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $this->SL_INSTANCE = str_replace($protocol, "", get_bloginfo('wpurl'));

        $this->license_site = get_option('svipro_softlicense_site');
        $this->options = get_option('woosvi_options');
        $this->license_key = $this->options['svipro_softlicense_key'];

        $this->handleLicenseUpdate();

        $this->plugin = 'smart-variations-images-pro/svipro.php';

        if ($this->license_key) {
            add_action('after_setup_theme', array($this, 'APTO_run_updater'));
        }

        add_action('redux/options/woosvi_options/saved', array($this, 'sviremove_key'));

        return $this;
    }

    function handleLicenseUpdate() {

        $license_key = get_option('svipro_softlicense_key');
        if ($license_key) {
            $license_site = get_option('svipro_softlicense_site');
            $this->options['svipro_softlicense_key'] = $license_key;
            update_option('woosvi_options', $this->options);
            $args = array('svipro_softlicense_key');
            $this->clearOptions($args);
        }
    }

    function svi_no_license() {
        ?>
        <div class="notice notice-error is-dismissible">
            <p><?php _e('<b>Smart Variations PRO</b> license is inactive for this site, please activate! <p><b>Changed the domain?</b> You can manage your license on my orders page <a href="https://www.rosendo.pt/private/orders/">here</a>. Release your old site to be able to use the key again on the new site.</p>', 'wc_svi'); ?></p>
        </div>
        <?php
    }

    function APTO_run_updater() {

        if (!$this->license_site || !preg_match("#" . $this->license_site . "#", $this->SL_INSTANCE)) {
            unset($this->options['svipro_softlicense_key']);
            update_option('woosvi_options', $this->options);
            $args = array('svipro_softlicense_key', 'svipro_softlicense_site');
            $this->clearOptions($args);
            return;
        }
        // Take over the update check

        add_filter('pre_set_site_transient_update_plugins', array($this, 'check_for_plugin_update'));

        // Take over the Plugin info screen
        add_filter('plugins_api', array($this, 'plugins_api_call'), 10, 3);
    }

    function activate_license($license_key = null) {

        if ($license_key == null)
            $license_key = $this->license_key;

        $args = array(
            'woo_sl_action' => 'activate',
            'licence_key' => $license_key,
            'product_unique_id' => $this->slug,
            'domain' => $this->SL_INSTANCE
        );


        $request_uri = $this->api_url . '?' . http_build_query($args, '', '&');
        $data = wp_remote_get($request_uri);
        if (is_array($data) && !empty($data['body'])) {
            $data_body = json_decode($data['body']);
            $data_body = $data_body[0];

            if (empty($data_body)) {

                $pos = strpos($data['body'], 'Unable to validate SVI PRO');

                if ($pos !== false) {
                    $data_body = (object) array(
                                'status' => 'error',
                                'status_code' => 'e111',
                                'message' => 'Unable to validate SVI PRO Key'
                    );
                }
            }

            if ($data_body->status = 'success' || $data_body->status_code == 'e113') {
                update_option('svipro_softlicense_site', $this->SL_INSTANCE);
            }


            return $data_body;
        }
    }

    function sviremove_key($args) {
        if (count($args) < 3 && $args['svipro_softlicense_key'] != '') {
            echo '<div class="saved_notice admin-notice notice-green">' . apply_filters("redux-saved-text-woosvi_options", '<strong>License is activated. Page will reload in 3 seconds.</strong>') . '</div>';

            echo "<script>setTimeout(function(){ window.location.reload(); }, 3000);</script>";
        }
        //svipre($args);
        //var_dump($args['deletekey']);
        //var_dump(boolval($args['deletekey']));
        if (isset($args['deletekey']) && !boolval($args['deletekey'])) {
            $res = $this->deactivate();

            if ($res->status == 'success') {
                delete_option('svipro_softlicense_key');
                delete_option('svipro_softlicense_site');
                delete_option('woosvi_options');
                echo '<div class="saved_notice admin-notice notice-green">' . apply_filters("redux-saved-text-woosvi_options", '<strong>' . $res->message . ' Page will reload in 3 seconds.</strong>') . '</div>';

                echo "<script>setTimeout(function(){ window.location.reload(); }, 3000);</script>";
            } else {
                echo '<div class="saved_notice admin-notice notice-red">' . apply_filters("redux-saved-text-woosvi_options", '<strong>' . $res->message . '</strong>') . '</div>';
            }
        }
    }

    function deactivate() {

        $args = array(
            'woo_sl_action' => 'deactivate',
            'licence_key' => $this->license_key,
            'product_unique_id' => $this->slug,
            'domain' => $this->SL_INSTANCE
        );

        $request_uri = $this->api_url . '?' . http_build_query($args, '', '&');
        $data = wp_remote_get($request_uri);

        if (is_array($data) && !empty($data['body'])) {
            if (is_wp_error($data) || $data['response']['code'] != 200)
                return;

            $data_body = json_decode($data['body']);
            $data_body = $data_body[0];

            if (isset($data_body->status)) {

                return $data_body;
            }
        }
    }

    public function check_for_plugin_update($checked_data) {

        if (empty($checked_data->checked) || !isset($checked_data->checked[$this->plugin]))
            return $checked_data;

        $request_string = $this->prepare_request('plugin_update');
        if ($request_string === FALSE)
            return $checked_data;


        // Start checking for an update
        $request_uri = $this->api_url . '?' . http_build_query($request_string, '', '&');
        $data = wp_remote_get($request_uri);

        if (is_array($data) && !empty($data['body'])) {

            if (is_wp_error($data) || $data['response']['code'] != 200)
                return $checked_data;

            $response_block = json_decode($data['body']);



            if (!is_array($response_block) || count($response_block) < 1) {
                return $checked_data;
            }

            //retrieve the last message within the $response_block
            $response_block = $response_block[count($response_block) - 1];
            $response = isset($response_block->message) ? $response_block->message : '';

            if (is_object($response) && !empty($response)) { // Feed the update data into WP updater
                //include slug and plugin data
                $response->slug = $this->slug;
                $response->plugin = $this->plugin;

                $checked_data->response[$this->plugin] = $response;
            }

            if ($response == 'Licence key not active for current domain') {
                $args = array('svipro_softlicense_key', 'svipro_softlicense_site');
                $this->clearOptions($args);
            }
        }
        return $checked_data;
    }

    public function plugins_api_call($def, $action, $args) {
        if (!is_object($args) || !isset($args->slug) || $args->slug != $this->slug)
            return false;


        //$args->package_type = $this->package_type;

        $request_string = $this->prepare_request($action, $args);
        if ($request_string === FALSE)
            return new WP_Error('plugins_api_failed', __('An error occour when try to identify the plugin.', 'apto') . '&lt;/p> &lt;p>&lt;a href=&quot;?&quot; onclick=&quot;document.location.reload(); return false;&quot;>' . __('Try again', 'apto') . '&lt;/a>');

        $request_uri = $this->api_url . '?' . http_build_query($request_string, '', '&');
        $data = wp_remote_get($request_uri);

        if (is_array($data) && !empty($data['body'])) {

            if (is_wp_error($data) || $data['response']['code'] != 200)
                return new WP_Error('plugins_api_failed', __('An Unexpected HTTP Error occurred during the API request.', 'apto') . '&lt;/p> &lt;p>&lt;a href=&quot;?&quot; onclick=&quot;document.location.reload(); return false;&quot;>' . __('Try again', 'apto') . '&lt;/a>', $data->get_error_message());

            $response_block = json_decode($data['body']);
            //retrieve the last message within the $response_block
            $response_block = $response_block[count($response_block) - 1];
            $response = $response_block->message;

            if (is_object($response) && !empty($response)) { // Feed the update data into WP updater
                //include slug and plugin data
                $response->slug = $this->slug;
                $response->plugin = $this->plugin;

                $response->sections = (array) $response->sections;
                $response->banners = (array) $response->banners;

                return $response;
            }
        }
    }

    public function prepare_request($action, $args = array()) {

        global $wp_version;

        return array(
            'woo_sl_action' => $action,
            'version' => SL_VERSION,
            'product_unique_id' => $this->slug,
            'licence_key' => $this->license_key,
            'domain' => $this->SL_INSTANCE,
            'wp-version' => $wp_version,
        );
    }

    function clearOptions($args) {
        foreach ($args as $value) {
            delete_option($value);
        }
    }

}

function woocommerce_svi_general() {
    global $woosvi_general;

    if (!isset($woosvi_general)) {
        $woosvi_general = new woocommerce_svi_general();
    }

    return $woosvi_general;
}
