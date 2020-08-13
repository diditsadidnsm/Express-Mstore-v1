<?php
/*
Plugin Name: QR Code
Plugin URI: http://wedevs.com/
Thumbnail Name: wpuf-qr.png
Description: Post Qr code generator plugin
Version: 0.1
Author: weDevs
Author URI: http://wedevs.com/
License: GPL2
*/

/**
 * Copyright (c) 2016 weDevs (email: info@wedevs.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

// don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;

define( 'WPUF_QR_DIR', plugins_url('/', __FILE__) );


/**
 * WPUF_QR_Code class
 *
 * @class WPUF_QR_Code The class that holds the entire WPUF_QR_Code plugin
 */
class WPUF_QR_Code {

    /**
     * Constructor for the WPUF_QR_Code class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     *
     * @uses register_activation_hook()
     * @uses register_deactivation_hook()
     * @uses is_admin()
     * @uses add_action()
     */

    public $model;

    public function __construct() {
        register_activation_hook( __FILE__, array( $this, 'activate' ) );
        register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

        add_filter( 'wpuf_custom_field_render', array( $this, 'render_custom_field_html' ), 10, 4 );

        $this->installation();

        // Loads frontend scripts and styles
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        // Loads admin scripts and styles
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        // Load admin scripts
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );

        add_action( 'wpuf-form-builder-enqueue-after-components', array( $this, 'wpuf_qr_code_components' ) );

        // Add custom button in form elemnt
        add_filter( 'wpuf-form-fields-others-fields', array( $this, 'add_custom_qr_button' ) );
        add_filter( 'wpuf-form-fields', array( $this, 'add_qr_button_field_settings' ) );

        add_action( 'wpuf-form-builder-add-js-templates', array( $this, 'wpuf_qr_code_templates' ) );

        // Create Custom Fomr element when button is pressed
        add_action( 'wpuf_admin_field_qr_code', array( $this, 'qr_code_new_form' ), 10, 2 );

        // Load custom tempalte for Editing Admin form
        add_action( 'wpuf_admin_template_post_qr_code', array( $this, 'edit_qr_code_form'),10, 3 );

        // Add Qr Meta when post is published
        add_action( 'wpuf_add_post_after_insert', array( $this, 'save_qr_code_with_post' ), 10, 4 );

        // Update Meta when piost edit
        add_action( 'wpuf_edit_post_after_update', array( $this, 'update_qr_code_with_post' ), 11, 4 );

        add_action( 'wpuf_draft_post_after_insert', array( $this, 'save_qr_code_with_post' ), 11, 4 );
        // Update User Meta when Update post
        add_action( 'wpuf_update_profile', array( $this, 'update_qr_code_with_user' ), 11, 4 );

        // Add Custom tab in Each form settings
        add_action( 'wpuf_post_form_tab', array( $this, 'add_from_tab' ), 10 );

        // Add content in custom tab
        add_action( 'wpuf_post_form_tab_content', array( $this, 'add_from_tab_content' ), 10 );


    }

    /**
     * Initializes the WPUF_QR_Code() class
     *
     * Checks for an existing WPUF_QR_Code() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new WPUF_QR_Code();
        }

        return $instance;
    }

    /**
     * Load Ajax file when plugin initialize
     */
    function installation () {
        require_once dirname(__FILE__).'/classes/ajax.php';
        new WPUF_Ajax_QR_Code();
    }

    /**
     * Placeholder for activation function
     *
     * Nothing being called here yet.
     */
    public function activate() {

    }

    /**
     * Placeholder for deactivation function
     *
     * Nothing being called here yet.
     */
    public function deactivate() {

    }

    /**
     * Enqueue scripts in admin panel
     */
    public function enqueue_styles () {
        wp_enqueue_style( 'wpuf-qr-code-admin-styles', plugins_url( 'css/style.css', __FILE__ ), false, date( 'Ymd' ) );
        wp_enqueue_script( 'wpuf-qr-code-admin-scripts', plugins_url( 'js/admin-script.js', __FILE__ ), array( 'jquery' ), false, true );
    }

    /**
     * Enqueue admin scripts
     *
     * Allows plugin assets to be loaded.
     *
     * @uses wp_enqueue_script()
     * @uses wp_localize_script()
     * @uses wp_enqueue_style
     */
    public function enqueue_scripts() {
        /**
         * All scripts goes here
         */
        wp_enqueue_script( 'wpuf-qr-code-scripts', plugins_url( 'js/script.js', __FILE__ ), array( 'jquery' ), false, true );


        /**
         * Example for setting up text strings from Javascript files for localization
         *
         * Uncomment line below and replace with proper localization variables.
         */
        $translation_array = array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) );

        wp_localize_script( 'wpuf-qr-code-scripts', 'wpufqrcode', $translation_array );
    }

    public function wpuf_qr_code_components() {
        wp_enqueue_script( 'wpuf-qr-code-components', plugins_url( 'js/index.js', __FILE__ ), array( 'wpuf-form-builder-mixins', 'wpuf-form-builder-components' ), WPUF_VERSION, true );
    }

    public function wpuf_qr_code_templates () {
        echo '<script type="text/x-template" id="tmpl-wpuf-form-qr_code">' . "\n";
        require_once dirname(__FILE__).'/templates/template.php';
        echo "\n" . '</script>' . "\n";
    }

    /**
     * Add custom button in form element
     */
    public function add_custom_qr_button( $fields ) {
        return array_merge( $fields, array( 'qr_code' ) );
    }

    public function add_qr_button_field_settings( $settings ) {

        if ( class_exists ( 'WPUF_Field_Contract' ) ) {
            require_once dirname(__FILE__).'/fields/class-field-qr-code.php';
            $settings['qr_code'] = new WPUF_Form_Field_QR_Code();
        }

        return $settings;
    }

    /**
     * Add form tab in form settings Admin panel
     */
    function add_from_tab() {
        ?>
        <a href="#wpuf-metabox-qr-code" class="nav-tab" id="wpuf-qr-code-tab"><?php _e( 'QR Code', 'wpuf-pro' ); ?></a>
        <?php
    }

    /**
     * Add content in form setting tab panel
     */
    function add_from_tab_content () {
        require_once dirname(__FILE__).'/templates/form-tab-content.php';
    }

    /**
     * Save Qr code meta in post meta
     * save qr meta corresponding form post
     * @param  integer $post_id
     *
     * @param  integer $form_id
     *
     * @param  array $form_settings
     *
     * @param  array $form_vars
     *
     * @return void
     */
    function save_qr_code_with_post( $post_id, $form_id, $form_settings, $form_vars ) {
        foreach ( $form_vars as $value ) {
            if( $value['input_type'] == 'qr_code' ) {
                $post_data = $_POST[$value['name']];
                $this->save_qr_meta( $post_data, $post_id, $value['name'], $form_settings );
            }
        }
    }

    /**
     * Update Qr meta for editing user
     *
     * @param  integer $user_id
     *
     * @param  integer $form_id
     *
     * @param  array $form_settings
     *
     * @param  array $form_vars
     *
     * @return void
     */
    function update_qr_code_with_user ( $user_id, $form_id, $form_settings, $form_vars ) {
        foreach ( $form_vars as $value ) {
            if( $value['input_type'] == 'qr_code' ) {
                $post_data = $_POST[$value['name']];
                $this->save_qr_meta_user( $post_data, $user_id, $value['name'], $form_settings );
            }
        }
    }

    /**
     *  Create Qr Code Image
     *
     * @param  array $post_data
     *
     * @param  integer $post_id
     *
     * @param  string $meta_key
     *
     * @param  array $form_settings
     *
     * @return void
     */
    function save_qr_meta_user( $post_data, $user_id, $meta_key, $form_settings ) {
        $type = $post_data['qr_code_type'];

        if( $type == '' && empty( $type ) ) {
            return;
        }

        $metadata = array(
            'type' => $post_data['qr_code_type'],
            'type_param' => $post_data['type_param']
        );

        update_user_meta( $user_id, $meta_key, $metadata );
    }



    /**
     * Update Qr meta for editing post
     *
     * @param  integer $post_id
     *
     * @param  integer $form_id
     *
     * @param  array $form_settings
     *
     * @param  array $form_vars
     *
     * @return void
     **/
    function update_qr_code_with_post ( $post_id, $form_id, $form_settings, $form_vars ) {
        foreach ( $form_vars as $value ) {
            if( $value['input_type'] == 'qr_code' ) {
                $post_data = $_POST[$value['name']];
                $this->save_qr_meta( $post_data, $post_id, $value['name'], $form_settings );
            }
        }
    }

    /**
     *  Create Qr Code Image
     *
     * @param  array $post_data
     *
     * @param  integer $post_id
     *
     * @param  string $meta_key
     *
     * @param  array $form_settings
     *
     * @return  void
     */
    function save_qr_meta( $post_data, $post_id, $meta_key, $form_settings ) {
        $type = $post_data['qr_code_type'];

        if( $type == '' && empty( $type ) ) {
            return;
        }

        $metadata = array(
            'type' => $post_data['qr_code_type'],
            'type_param' => $post_data['type_param']
        );

        update_post_meta( $post_id, $meta_key, $metadata );
    }

    /**
     * Generate Qr form tag in admin panel
     *
     * @param  string $type
     *
     * @param  integer $field_id
     *
     * @param  array  $values
     *
     * @return  void
     */
    function qr_code_new_form ( $type, $field_id, $values = array() ) {
        $qr_code_type_name  = sprintf( '%s[%d][qr_type][]', WPUF_Admin_Template::$input_name, $field_id );
        $qr_code_type_value = isset( $values['qr_type'] ) ? $values['qr_type'] : array();

        $qr_code_type = array(
            'url'      => __( 'URL', 'wpuf-pro' ),
            'text'     => __( 'Text', 'wpuf-pro' ),
            'geo'      => __( 'Location', 'wpuf-pro' ),
            'sms'      => __( 'SMS', 'wpuf-pro' ),
            'wifi'     => __( 'Wifi', 'wpuf-pro' ),
            'card'     => __( 'Card', 'wpuf-pro' ),
            'email'    => __( 'Email', 'wpuf-pro' ),
            'calendar' => __( 'Calendar', 'wpuf-pro' ),
            'phone'    => __( 'Phone', 'wpuf-pro' ),
        );
        ?>

        <li class="custom-field custom_image">
            <?php WPUF_Admin_Template::legend( __('Qr Code', 'wpuf-pro' ), $values, $field_id ); ?>
            <?php WPUF_Admin_Template::hidden_field( "[$field_id][input_type]", 'qr_code' ); ?>
            <?php WPUF_Admin_Template::hidden_field( "[$field_id][template]", 'qr_code' ); ?>
            <div class="wpuf-form-holder">
                <?php WPUF_Admin_Template::common( $field_id, '', true, $values ); ?>
                <div class="wpuf-form-rows">
                    <label><?php _e( 'Allowed Type for Qr Code', 'wpuf-pro' ); ?></label>
                    <div class="wpuf-form-sub-fields">
                        <?php foreach ($qr_code_type as $key => $value) {
                            ?>
                            <label>
                                <input type="checkbox" name="<?php echo $qr_code_type_name; ?>" value="<?php echo $key; ?>"<?php echo in_array( $key, $qr_code_type_value ) ? ' checked="checked"' : ''; ?>>
                                <?php printf( '%s', $value ); ?>
                            </label> <br />
                        <?php } ?>
                    </div>
                </div> <!-- .wpuf-form-rows -->
            </div>
        </li>
        <?php
    }

    /**
     * Render Edit Form in forntend
     *
     * @param  string $name
     *
     * @param  integer $count
     *
     * @param  string $input_field
     *
     * @return  void
     */
    function edit_qr_code_form ( $name, $count, $input_field ) {
        $this->qr_code_new_form ( $name, $count, $input_field );
    }


    function render_custom_field_html( $html , $value, $attr ,$form_settings ) {
        if ( $attr['input_type'] != 'qr_code' ) return;

        if ( !is_array( $value ) ) return;

        $qrtype = isset( $value[0]['type'] ) ? $value[0]['type'] : '';
        $qrval = '';

        switch ($qrtype) {
            case 'url':
                $qrval = $value[0]['type_param']['url'];
                break;
            case 'text':
                $qrval = $value[0]['type_param']['text'];
                break;
            case 'geo':
                $qrval = 'lat:'.$value[0]['type_param']['geo_lat'].',lon:'.$value[0]['type_param']['geo_long'];
                break;
            case 'sms':
                $qrval = 'sms:'.$value[0]['type_param']['sms_tel'].',message:'.$value[0]['type_param']['sms_message'];
                break;
            case 'wifi':
                $qrval = 'Type:'.$value[0]['type_param']['wifi_type'].',SSID:'.$value[0]['type_param']['wifi_ssid'].',Password:'.$value[0]['type_param']['wifi_password'];
                break;
            case 'card':
                $qrval = 'Name:'.$value[0]['type_param']['card_name'].',Company name:'.$value[0]['type_param']['card_firm'].',Phone Number : '.$value[0]['type_param']['card_tel']
                    .',Email:'.$value[0]['type_param']['card_email']
                .',Address:'.$value[0]['type_param']['card_address'].',URL:'.$value[0]['type_param']['card_url'].',Memo:'.$value[0]['type_param']['card_memo'];
                break;
            case 'email':
                $qrval = 'Email:'.$value[0]['type_param']['email_address'].',Subject:'.$value[0]['type_param']['email_subject'].',Message:'.$value[0]['type_param']['email_message'];
                break;
            case 'calendar':
                $qrval = 'Title:'.$value[0]['type_param']['calendar_title'].',Place:'.$value[0]['type_param']['calendar_place'].',Begin:'.$value[0]['type_param']['calendar_begin'].',End:'.$value[0]['type_param']['calendar_end'];
                break;
            case 'phone':
                $qrval = 'tel:'.$value[0]['type_param']['phone'];
                break;
        }

        $qr_size     = isset( $form_settings['size'] ) ? ( $form_settings['size'] > 500 ? 500 : $form_settings['size'] ) : 200;
        $qrcolor     = isset( $form_settings['qrcolor'] ) ? str_replace('#','', $form_settings['qrcolor']) : '000000';
        $hide_label  = isset( $attr['hide_field_label'] ) ? $attr['hide_field_label'] : 'no';

        $html = '';
        $html .= '<li>';

        if ( $hide_label == 'no' ) {
            $html .= '<label>' . $attr['label'] . ': </label> ';
        }
        return $html .= ' '.'<img style="-webkit-user-select: none" src="http://chart.apis.google.com/chart?cht=qr&amp;chs='.$qr_size.'x'.$qr_size.'&amp;chl='. $qrval.'&amp;chco='.$qrcolor.'">'.'</li>';
    }


} // WPUF_QR_Code

$wpufqrcode = WPUF_QR_Code::init();

/**
 *  Short code action [wpuf_qr]
 */
add_shortcode( 'wpuf_qr', 'qr_shortcode_render' );


/**
 * Callback function for wpuf_qr shortcode
 * @param  array $atts
 *
 * @return string (qr code image)
 */
function qr_shortcode_render( $atts ) {
    $qr_meta = get_post_meta( $atts['id'], $atts['metakey'] , false );

    return $qr_meta[0]['image'];
}

/**
 * Show Qr image using this function in any template
 * @param  integer $id
 *
 * @param  string $metakey
 *
 * @return string (qr code image)
 */
function show_wpuf_qrcode( $id, $metakey ) {
    $qr_meta = get_post_meta( $id, $metakey , false );

    return $qr_meta[0]['image'];
}
