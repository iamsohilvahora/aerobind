<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class woocommerce_svi_frontend {

    private static $_this;

    /**
     * contruct
     *
     * @since 1.0.0
     * @return bool
     */
    public function __construct() {


        $this->suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        add_action('wp', array($this, 'init'));
        return $this;
    }

    /**
     * run init to check if we are on product page
     *
     * @since 1.0.0
     * @return 
     */
    function init() {
        global $post;

        $this->prepVars();

        if (is_product() || is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'product_page')) {
            add_action('woocommerce_before_single_product', array($this, 'remove_hooks'));
            add_action('woocommerce_before_single_product_summary', array($this, 'show_product_images'), 20);
            add_action('wp_enqueue_scripts', array($this, 'load_scripts'), 150, 1);
        }

        if ($this->woosvi_options['svicart']) {
            add_filter('woocommerce_cart_item_thumbnail', array($this, 'filter_woocommerce_cart_item_thumbnail'), 150, 3);
        }
    }

    /**
     * Plugin path
     *
     * @since 1.0.0
     * @return html
     */
    function woo_svi_plugin_path() {
        return untrailingslashit(plugin_dir_path(dirname(__FILE__)));
    }

    /**
     * Loads the vars needed
     *
     * @since 1.1.1
     * @return instance object
     */
    function prepVars() {
        global $woosvi, $post;

        $options = get_option('woosvi_options');

        if ($options['disable_thumb']) {
            $options['slider_position'] = 0;
            $options['columns'] = 4;
            $options['hide_thumbs'] = false;
            $options['swselect'] = false;
            $options['variation_swap'] = false;
            $options['keep_thumbnails'] = false;
        }
        if ($options['hide_thumbs']) {
            $options['variation_swap'] = false;
            $options['keep_thumbnails'] = false;
        }

        unset($options['svipro_softlicense_key']);

        $this->woosvi_options = $options;

        if (is_product() || is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'product_page')) {
            $this->woosvi_options['gallery'] = $this->gallery();
        }
        $this->woosvi_options['jsversion'] = SL_VERSION;
        $woosvi = $this->woosvi_options;
    }

    function getShortcodeID($content) {
        global $wpdb;

        if (preg_match('/\[product_page (.+?)\]/', $content, $matches)) {

            $matches = array_pop($matches);
            $matches = explode(" ", $matches);
            $params = array();
            foreach ($matches as $d) {
                list($opt, $val) = explode("=", $d);
                $params[$opt] = trim($val, '"');
            }
            if (array_key_exists('sku', $params))
                $id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $params['sku']));
            else
                $id = $params['id'];

            return $id;
        }
    }

    /**
     * Load images to be used
     *
     * @since 1.0.0
     * @return array
     */
    public function gallery() {
        global $post;

        if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'product_page')) {

            $p_id = $this->getShortcodeID($post->post_content);

            $product = wc_get_product($p_id);
        } else {
            $p_id = $post->ID;
            $product = wc_get_product($p_id);
        }
        $slugs = $this->wpml($p_id, $product);

        $mid = get_post_thumbnail_id($p_id);

        if ($this->version_check())
            $attachment_ids = $product->get_gallery_image_ids();
        else
            $attachment_ids = $product->get_gallery_attachment_ids();

        $gallery_images = array();

        $slug_main = $this->wpml_slug($slugs, $mid, $p_id);

        $full = wp_get_attachment_image_src($mid, 'full');
        $title = get_the_title($mid);
        $k = 0;
        if ($full) {
            $gallery_images['thumbs'][] = array(
                'fullimg' => $this->imgtagger(wp_get_attachment_image($mid, apply_filters('single_product_large_thumbnail_size', 'shop_single'), 0, array(
                    'data-woosvislug' => $slug_main,
                    'data-svikey' => '0',
                    'data-svizoom-image' => $full[0],
                    'title' => $title
                                )
                )),
                'thumbimg' => $this->imgtagger(wp_get_attachment_image($mid, apply_filters('single_product_small_thumbnail_size', 'shop_thumbnail'), 0, array(
                    'data-woosvislug' => $slug_main,
                    'data-svikey' => '0',
                    'data-svizoom-image' => $full[0],
                    'title' => $title
                ))),
                'full' => $full,
                'large' => wp_get_attachment_image_src($mid, 'large'),
                'single' => wp_get_attachment_image_src($mid, apply_filters('single_product_large_thumbnail_size', 'shop_single')),
                'thumb' => wp_get_attachment_image_src($mid, 'thumbnail'),
                'woosvi_slug' => $slug_main,
                'title' => $title,
                'type' => 'main'
            );
            $k = 1;
        } else {
            $gallery_images['main_image'] = false;
        }
        if (0 < count($attachment_ids)) {

            foreach ($attachment_ids as $x => $id) {

                $woosvi_slug = $this->wpml_slug($slugs, $id, $p_id);
                $full = wp_get_attachment_image_src($id, 'full');
                $title = get_the_title($id);
                $gallery_images['thumbs'][] = array(
                    'fullimg' => $this->imgtagger(wp_get_attachment_image($id, apply_filters('single_product_large_thumbnail_size', 'shop_single'), 0, array(
                        'data-woosvislug' => $woosvi_slug,
                        'data-svikey' => $k,
                        'data-svizoom-image' => $full[0],
                        'title' => $title
                    ))),
                    'thumbimg' => $this->imgtagger(wp_get_attachment_image($id, apply_filters('single_product_small_thumbnail_size', 'shop_thumbnail'), 0, array(
                        'data-woosvislug' => $woosvi_slug,
                        'data-svikey' => $k,
                        'data-svizoom-image' => $full[0],
                        'title' => $title
                    ))),
                    'full' => $full,
                    'large' => wp_get_attachment_image_src($id, 'large'),
                    'single' => wp_get_attachment_image_src($id, apply_filters('single_product_large_thumbnail_size', 'shop_single')),
                    'thumb' => wp_get_attachment_image_src($id, 'thumbnail'),
                    'woosvi_slug' => $woosvi_slug,
                    'title' => $title,
                    'type' => 'thumb'
                );
                $k++;
            }
        }

        return $gallery_images;
    }

    /**
     * Break images tags to array to be used
     *
     * @since 1.0.0
     * @return array
     */
    function imgtagger($fullimg_tag) {
        preg_match_all('/(alt|title|src|data-woosvislug|data-svizoom-image|data-svikey|srcset|sizes|width|height|class)=("[^"]*")/i', $fullimg_tag, $fullimg_split);

        foreach ($fullimg_split[2] as $key => $value) {
            if ($value == '""')
                $fullimg_split[2][$key] = "";
            else
                $fullimg_split[2][$key] = str_replace('"', "", $value);
        }
        return array_combine($fullimg_split[1], $fullimg_split[2]);
    }

    /**
     * Get translated Slugs
     *
     * @since 1.0.0
     * @return array
     */
    function wpml($pid, $product) {
        global $sitepress;

        if ($product->is_type('simple'))
            return false;

        $slugs = array();

        if (class_exists('SitePress')) {

            $attributes = get_post_meta($pid, '_product_attributes');

            if (!empty($attributes)) {
                foreach ($attributes[0] as $att => $attribute) {

                    if ($attribute['is_taxonomy'] && $attribute['is_variation']) {
                        $terms = wp_get_post_terms($pid, urldecode($att), 'all');

                        foreach ($terms as $tr => $term) {
                            remove_filter('get_term', array($sitepress, 'get_term_adjust_id'), 1, 1);
                            $gtb = get_term(icl_object_id($term->term_id, urldecode($att), true, $sitepress->get_default_language()));

                            $slugs[urldecode($gtb->slug)] = urldecode($term->slug);
                            add_filter('get_term', array($sitepress, 'get_term_adjust_id'), 1, 1);
                        }
                    }
                }
            }

            $original = icl_object_id($pid, 'product', true, $sitepress->get_default_language());
            $attributes_original = get_post_meta($original, '_product_attributes');

            if (!empty($attributes_original)) {
                foreach ($attributes_original[0] as $att => $attribute) {

                    if (!$attribute['is_taxonomy'] && $attribute['is_variation']) {

                        if (array_key_exists($att, $attributes[0])) {
                            $values = str_replace(" ", "", $attributes[0][$att]['value']);
                            if (!empty($values)) {
                                $terms = explode('|', $values);

                                $values_original = str_replace(" ", "", $attribute['value']);
                                $terms_original = explode('|', $values_original);

                                foreach ($terms_original as $tr => $term) {
                                    $slugs[strtolower(urldecode($term))] = strtolower($terms[$tr]);
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $attributes = get_post_meta($pid, '_product_attributes');
            if (!empty($attributes)) {
                foreach ($attributes[0] as $att => $attribute) {

                    if ($attribute['is_taxonomy'] && $attribute['is_variation']) {
                        $terms = wp_get_post_terms($pid, urldecode($att), 'all');

                        foreach ($terms as $tr => $term) {

                            $gtb = get_term($term->term_id);

                            $slugs[urldecode($gtb->slug)] = urldecode($term->slug);
                        }
                    }
                }

                foreach ($attributes[0] as $att => $attribute) {

                    if (!$attribute['is_taxonomy'] && $attribute['is_variation']) {

                        $values = str_replace(" ", "", $attribute['value']);
                        if (!empty($values)) {

                            $terms = explode('|', $values);

                            $values_original = str_replace(" ", "", $attribute['value']);
                            $terms_original = explode('|', $values_original);

                            foreach ($terms_original as $tr => $term) {
                                $slugs[strtolower(urldecode($term))] = strtolower($terms[$tr]);
                            }
                        }
                    }
                }
            }
        }

        $slugs['sviproglobal'] = 'sviproglobal';

        return $slugs;
    }

    /**
     * Get translated Slug for attachments
     *
     * @since 1.0.0
     * @return array
     */
    function wpml_slug($slugs, $id, $pid, $encoded = true) {
        global $sitepress;

        $woosvi_slug = array();

        if (!$slugs)
            return '';

        if (class_exists('SitePress')) {

            $originalwoosvi_slug = get_post_meta(icl_object_id($id, 'attachment', true, $sitepress->get_default_language()), 'woosvi_slug_' . icl_object_id($pid, 'product', true, $sitepress->get_default_language()), true);

            if (!$originalwoosvi_slug)
                $originalwoosvi_slug = get_post_meta(icl_object_id($id, 'attachment', true, $sitepress->get_default_language()), 'woosvi_slug', true);


            if (!empty($originalwoosvi_slug)) {
                $matchesa = array();
                if (is_array($originalwoosvi_slug)) {
//HANDLE COMBO
                    foreach ($originalwoosvi_slug as $k => $v) {
                        $matchesa[] = $this->combo($v, $slugs);
                    }
                } else if (array_key_exists($originalwoosvi_slug, $slugs))
                    array_push($woosvi_slug, $slugs[$originalwoosvi_slug]);
                else
                    array_push($woosvi_slug, $originalwoosvi_slug);


                if (!empty($matchesa) && count($matchesa) > 0) {
                    $woosvi_slug = $matchesa;
                }
            } else {
                array_push($woosvi_slug, get_post_meta($id, 'woosvi_slug_' . icl_object_id($pid, 'product', true, $sitepress->get_default_language()), true));
                if (!empty($woosvi_slug))
                    array_push($woosvi_slug, get_post_meta($id, 'woosvi_slug', true));
            }
        } else {
            $originalwoosvi_slug = get_post_meta($id, 'woosvi_slug_' . $pid, true);

            if (!$originalwoosvi_slug)
                $originalwoosvi_slug = get_post_meta($id, 'woosvi_slug', true);


            if (!empty($originalwoosvi_slug)) {
                $matchesa = array();
                if (is_array($originalwoosvi_slug)) {
//HANDLE COMBO
                    foreach ($originalwoosvi_slug as $k => $v) {
                        $matchesa[] = $this->combo($v, $slugs);
                    }
                } else if (array_key_exists($originalwoosvi_slug, $slugs))
                    array_push($woosvi_slug, $slugs[$originalwoosvi_slug]);
                else
                    array_push($woosvi_slug, $originalwoosvi_slug);


                if (!empty($matchesa) && count($matchesa) > 0) {
                    $woosvi_slug = $matchesa;
                }
            } else {
                array_push($woosvi_slug, get_post_meta($id, 'woosvi_slug_' . $pid, true));
                if (!empty($woosvi_slug))
                    array_push($woosvi_slug, get_post_meta($id, 'woosvi_slug', true));
            }
        }

        $woosvi_slug = array_filter($woosvi_slug);
        if (!empty($woosvi_slug)) {
            if ($encoded)
                return json_encode($woosvi_slug, JSON_UNESCAPED_UNICODE);
            else
                return $woosvi_slug[0];
        } else {
            return '';
        }
    }

    public function combo($originalwoosvi_slug, $slugs) {
//HANDLE COMBO

        if (is_array($originalwoosvi_slug)) {
            $special = array();
            $combo = array();
            foreach ($originalwoosvi_slug as $key => $value) {
                if (!empty($slugs[(strtolower($value))])) {
                    array_push($combo, $slugs[urldecode(strtolower($value))]);
                    $special[urldecode(strtolower($value))] = urldecode(strtolower($value));
                }
            }
            $originalwoosvi_slug = $special;
        }

        if (is_array($originalwoosvi_slug) && boolval(count(array_intersect_key($originalwoosvi_slug, $slugs)) == count($originalwoosvi_slug))) { //COMBO
            $woosvi_slug = implode('_svipro_', $combo);
        } else if (is_string($originalwoosvi_slug) && array_key_exists($originalwoosvi_slug, $slugs)) {
            $woosvi_slug = $slugs[$originalwoosvi_slug];
        } else {
            $woosvi_slug = $originalwoosvi_slug;
        }


        return $woosvi_slug;
    }

    /**
     * Loads visualization page
     *
     * @since 1.1.1
     * @return instance object
     */
    public function show_product_images() {

        include($this->woo_svi_plugin_path() . '/frontend/display.php');
    }

    /**
     * load front-end scripts
     *
     * @since 1.0.0
     * @return bool
     */
    function load_scripts() {
        global $wp_styles, $woocommerce;

        $loads = array(
            'jquery',
        );

        if ($this->woosvi_options['lens']) {
            wp_enqueue_script('sviezlens', plugins_url('assets/vendor/elevatezoom-plus/js/jquery.ez-plus' . $this->suffix . '.js', dirname(__FILE__)), $loads, null, true);
            array_push($loads, 'sviezlens');
        }

        if ($this->woosvi_options['lightbox']) {
            if ($this->woosvi_options['lightbox_new']) {
# photoswipe
                $handle = 'photoswipe' . $this->suffix . '.js';
                $list = 'enqueued';

                if (!wp_script_is($handle, $list)) {
                    wp_enqueue_script('photoswipesvi-svi', $woocommerce->plugin_url() . '/assets/js/photoswipe/photoswipe' . $this->suffix . '.js', array('jquery'), $woocommerce->version, true);
                    wp_enqueue_script('photoswipe-ui-default-svi', $woocommerce->plugin_url() . '/assets/js/photoswipe/photoswipe-ui-default' . $this->suffix . '.js', array('jquery'), $woocommerce->version, true);

                    wp_enqueue_style('photoswipe-svi', $woocommerce->plugin_url() . '/assets/css/photoswipe/photoswipe.css');
                    wp_enqueue_style('photoswipe-default-skin-svi', $woocommerce->plugin_url() . '/assets/css/photoswipe/default-skin/default-skin.css');

                    array_push($loads, 'photoswipesvi-svi', 'photoswipe-ui-default-svi');
                }
            } else {
                if (!$this->woosvi_options['prettyphoto_themestyle']) {
# prettyPhoto
                    $handle = 'prettyPhoto' . $this->suffix . '.js';
                    $list = 'enqueued';

                    if (wp_script_is($handle, $list)) {
                        wp_dequeue_script('prettyPhoto');
                        wp_dequeue_script('prettyPhoto-init');
                        wp_dequeue_style('woocommerce_prettyPhoto_css');
                    }

                    wp_enqueue_script('prettyPhotosvi', plugins_url('assets/vendor/prettyPhoto/js/jquery.prettyPhoto' . $this->suffix . '.js', dirname(__FILE__)), $loads, '3.1.6', true);

                    if ($this->woosvi_options['prettyphoto_style'] == 'pp_woocommerce') {
                        wp_enqueue_style('woocommerce_prettyPhoto_csssvi', plugins_url('assets/vendor/prettyPhoto/css/prettyPhoto_woo' . $this->suffix . '.css', dirname(__FILE__)));
                    } else {
                        wp_enqueue_style('woocommerce_prettyPhoto_csssvi', plugins_url('assets/vendor/prettyPhoto/css/prettyPhoto' . $this->suffix . '.css', dirname(__FILE__)));
                    }
                    array_push($loads, 'prettyPhotosvi');
                }
            }
        }

        if ($this->woosvi_options['slider']) {
            wp_enqueue_script('sviswiper', plugins_url('assets/vendor/swiper/js/swiper.jquery' . $this->suffix . '.js', dirname(__FILE__)), null, true);
            wp_enqueue_style('sviswiper', plugins_url('assets/vendor/swiper/css/swiper' . $this->suffix . '.css', dirname(__FILE__)));
            array_push($loads, 'sviswiper');
        }


        if ($this->woosvi_options['ps_thumbs']) {
            $handle = 'flexslider' . $this->suffix . '.js';
            $list = 'enqueued';
            if (!wp_script_is($handle, $list)) {
                wp_enqueue_script('svi-flexslider', $woocommerce->plugin_url() . '/assets/js/flexslider/jquery.flexslider' . $this->suffix . '.js', null, true);
                wp_enqueue_style('svi-flexslider', '//cdnjs.cloudflare.com/ajax/libs/flexslider/2.6.3/flexslider.min.css', null);
                array_push($loads, 'svi-flexslider');
            }
        }

        wp_enqueue_script('sviImagesloaded', plugins_url('assets/vendor/imagesloaded/js/imagesloaded.pkgd' . $this->suffix . '.js', dirname(__FILE__)), null, true);
        array_push($loads, 'sviImagesloaded');
        wp_enqueue_script('woosvijs', plugins_url('assets/js/svi-frontend' . $this->suffix . '.js', dirname(__FILE__)), $loads, null, true);

        if (!$this->woosvi_options['slider'] && $this->woosvi_options['columns'] > 1 && $this->woosvi_options['slider_position'] > 0)
            $this->woosvi_options['columns'] = 1;

        wp_localize_script('woosvijs', 'WOOSVIDATA', $this->woosvi_options);

        $styles = null;
        $srcs = array_map('basename', (array) wp_list_pluck($wp_styles->registered, 'src'));
        $key_woocommerce = array_search('woocommerce.css', $srcs);

        if ($key_woocommerce) {
            $styles = array(
                $key_woocommerce,
            );
        }

        wp_enqueue_style('woo_svicss', plugins_url('assets/css/woo_svi' . $this->suffix . '.css', dirname(__FILE__)), $styles, null);
    }

    /**
     * Remove hooks for plugin to work properly
     *
     * @since 1.1.1
     * @return instance object
     */
    public function remove_hooks() {
        remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
        remove_action('woocommerce_before_single_product_summary_product_images', 'woocommerce_show_product_images', 20);
        remove_action('woocommerce_product_summary_thumbnails', 'woocommerce_show_product_thumbnails', 20);
        remove_action('woocommerce_before_single_product_summary', 'wowmall_woocommerce_show_product_images', 20); //Woomall support
        wp_deregister_script('wowmall-wc-single-product-gallery');
        wp_deregister_script('single-product-lightbox');
    }

    /**
     * Add 1st match of variation image to cart
     *
     * @since 1.0.0
     * @return html
     */
    function filter_woocommerce_cart_item_thumbnail($product_get_image, $cart_item, $cart_item_key) {

        if ($cart_item['variation_id'] > 0) {

            $found = false;
            $product = wc_get_product($cart_item['product_id']);
            if ($this->version_check())
                $attachment_ids = $product->get_gallery_image_ids();
            else
                $attachment_ids = $product->get_gallery_attachment_ids();

            $slugs = $this->wpml($cart_item['product_id'], $product);


            foreach ($attachment_ids as $attachment_id) {
                $woo_svi = $this->wpml_slug($slugs, $attachment_id, $cart_item['product_id'], false);

                $slugs_confirm = explode('_svipro_', $woo_svi);

                foreach ($slugs_confirm as $k => $v) {
                    $slugers[strtolower($v)] = strtolower($v);
                }

                $svislug = $this->combo($cart_item['variation'], $slugers);

                if ($svislug == $woo_svi) {
                    $image_title = $product->get_title();
                    $product_get_image = wp_get_attachment_image($attachment_id, apply_filters('single_product_small_thumbnail_size', 'shop_thumbnail'), 0, $attr = array(
                        'title' => $image_title,
                        'alt' => $image_title
                    ));
                    break;
                }
            }
        }

        if (!$found) {
            foreach ($cart_item['variation'] as $key => $value) {

                if (!$found) {

                    foreach ($attachment_ids as $attachment_id) {
                        $woo_svi = $this->wpml_slug($slugs, $attachment_id, $cart_item['product_id'], false);

                        if (str_replace(" ", "", strtolower($value)) == $woo_svi) {
                            $image_title = $product->get_title();
                            $product_get_image = wp_get_attachment_image($attachment_id, apply_filters('single_product_small_thumbnail_size', 'shop_thumbnail'), 0, $attr = array(
                                'title' => $image_title,
                                'alt' => $image_title
                            ));
                            $found = true;
                            break;
                        }
                    }
                }
            }
        }

        return $product_get_image;
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

    /**
     * public function to get instance
     *
     * @since 1.1.1
     * @return instance object
     */
    public function get_instance() {
        return self::$_this;
    }

}

function woosvi_class() {
    global $woosvi_class;

    if (!isset($woosvi_class)) {
        $woosvi_class = new woocommerce_svi_frontend();
    }

    return $woosvi_class;
}

// initialize
woosvi_class();
