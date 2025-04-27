<?php
/**
 * @package   Woocommerce_Wootabs
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */

class wootabs_wp_editor {

    private static $mce_settings = null;
    private static $qt_settings = null;

    /**
     * Return new WordPress editor
     *
     * @since     1.0.0
     *
     * @return    html
     */
    public static function editor_html() {

        $plugin_slug = 'woocommerce-wootabs';
        
        $content = stripslashes( $_POST['content'] );

        $editor_id = $_POST['id'];

        $global_tab = intval( $_POST['globalT'] );

        if( $global_tab ){

            $pcats_args = array(
                'hierarchical'  => true,
                'hide_empty'    => false,
                'pad_counts'    => true
            );

            $product_categories = get_terms( 'product_cat', $pcats_args );

            if( $product_categories ){

                $pccnt = count( $product_categories );

                foreach ( $product_categories as $key => $value ){

                    if( $key === 0 ){ ?>

                        <label class="wtab-label categs"><?php _e( 'Select products categories where tab will appear', $plugin_slug ); ?></label>
                        
                        <div class="gtcs-wrapper">

                        <label class="gwt-lbl"><input type="checkbox" name="wootabs_products_categories_<?php echo $editor_id;?>[]" class="gt_all_cats wootabs_products_categories" value="all" checked="checked" /><?php _e( "All categories", $plugin_slug ) ?></label>

                        <div class="sep-g-wootabs-cats" style="display:none;">

                            <?php
                    }

                    ?>

                    <p class="gwt-p"><label class="gwt-lbl"><input type="checkbox" name="wootabs_products_categories_<?php echo $editor_id;?>[]" class="wootabs_products_categories" value="<?php echo $value->term_id; ?>" /><?php echo $value->name; ?></label></p>
                    
                    <?php

                    if( $key + 1 == $pccnt ){ ?>

                        <br/>

                        <hr/>

                        </div><!--/ .sep-g-wootabs-cats -->
                            
                        </div><!--/ .gtcs-wrapper -->
                        
                        <?php

                    }
                }
            }

            ?>

            <br/>

            <hr/>

            <br/>
                                        
            <?php
        }
        
        wp_editor($content, $editor_id, array(
            'textarea_name' => $_POST['textarea_name'],
            'editor_class' => 'wtab-textarea'
        ) );

        if ( !empty(self::$qt_settings) ) {
            $options = self::_parse_init( self::$qt_settings );
            $qtInit = '';
            $qtInit .= "'$editor_id':{$options},";
            $qtInit = '{' . trim($qtInit, ',') . '}';
        } 
        else {
            $qtInit = '{}';
        }
        
        $qt_init = $qtInit;
        

        if ( !empty(self::$mce_settings) ) {
            $options = self::_parse_init( self::$mce_settings );
            $mceInit = '';
            $mceInit .= "'$editor_id':{$options},";
            $mceInit = '{' . trim($mceInit, ',') . '}';
        }
        else {
            $mceInit = '{}';
        }

        $mce_init = $mceInit;

        ?>
        <script type="text/javascript">
            tinyMCEPreInit.mceInit = jQuery.extend( tinyMCEPreInit.mceInit, <?php echo $mce_init ?>);
            tinyMCEPreInit.qtInit = jQuery.extend( tinyMCEPreInit.qtInit, <?php echo $qt_init ?>);
        </script>

        <?php

        die();
    }

    /**
     * Retrieve TinyMce editor settings
     *
     * @since     1.0.0
     *
     * @return    array
     */
    public static function tiny_mce_before_init( $mceInit, $editor_id ) {

        self::$mce_settings = $mceInit;
        return $mceInit;
    }

    /**
     * Retrieve editor quicktags settings
     *
     * @since     1.0.0
     *
     * @return    array
     */
    public static function quicktags_settings( $qtInit, $editor_id ) {
        
        self::$qt_settings = $qtInit;
        return $qtInit;
    }

    /**
     * Transform settings array into json string
     *
     * @since     1.0.0
     *
     * @return    string
     */

    private static function _parse_init($init) {

        $options = '';

        foreach ( $init as $k => $v ) {

            if ( is_bool($v) ) {

                $val = $v ? 'true' : 'false';
                $options .= $k . ':' . $val . ',';
                continue;
            } 
            elseif ( !empty($v) && is_string($v) && ( ('{' == $v{0} && '}' == $v{strlen($v) - 1}) || ('[' == $v{0} && ']' == $v{strlen($v) - 1}) || preg_match('/^\(?function ?\(/', $v) ) ) {
                $options .= $k . ':' . $v . ',';
                continue;
            }

            $options .= $k . ':"' . $v . '",';
        }

        return '{' . trim( $options, ' ,' ) . '}';
    }
}