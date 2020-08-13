<?php
/*
Plugin Name: Mailchimp
Plugin URI: http://wedevs.com/
Thumbnail Name: wpuf-mailchimp.png
Description: Add subscribers to Mailchimp mailing list when they registers via WP User Frontend Pro
Version: 0.2.1
Author: weDevs
Author URI: http://wedevs.com/
License: GPL2
*/

/**
 * Copyright (c) 2014 weDevs (email: info@wedevs.com). All rights reserved.
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

// if ( is_admin() ) {
//    require_once dirname( __FILE__ ) . '/lib/wedevs-updater.php';
//    new WeDevs_Plugin_Update_Checker( plugin_basename( __FILE__ ), 'wpuf-mailchimp' );
// }

/**
 * WPUF_Mailchimp class
 *
 * @class WPUF_Mailchimp The class that holds the entire WPUF_Mailchimp plugin
 */
class WPUF_Mailchimp {

    /**
     * Constructor for the WPUF_Mailchimp class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     *
     * @uses is_admin()
     * @uses add_action()
     */
    public function __construct() {
        add_action( 'wpuf_admin_menu', array( $this, 'add_mailchimp_menu' ) );
        add_action( 'wpuf_profile_form_tab', array( $this, 'add_tab_profile_form' ) );
        add_action( 'wpuf_profile_form_tab_content', array( $this, 'add_tab_content_profile_form' ) );

        add_action( 'wpuf_after_register', array( $this, 'subscribe_user_after_registration' ), 10, 3 );

        // Loads frontend scripts and styles
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

    }

    /**
     * Initializes the WPUF_Mailchimp() class
     *
     * Checks for an existing WPUF_Mailchimp() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new WPUF_Mailchimp();
        }

        return $instance;
    }

    /**
     * Enqueue admin scripts
     *
     * Allows plugin assets to be loaded.
     *
     * @uses wp_enqueue_script()
     * @uses wp_enqueue_style
     */
    public function enqueue_scripts() {

        /**
         * All styles goes here
         */
        wp_enqueue_style( 'wpuf-mc-styles', plugins_url( 'css/style.css', __FILE__ ), false, date( 'Ymd' ) );

        /**
         * All scripts goes here
         */
        wp_enqueue_script( 'wpuf-mc-scripts', plugins_url( 'js/script.js', __FILE__ ), array( 'jquery' ), false, true );
    }

    /**
     * Add Mailchimp Submenu in WPUF
     */
    public function add_mailchimp_menu() {
        add_submenu_page( 'wp-user-frontend', __( 'Mailchimp', 'wpuf-pro' ), __( 'Mailchimp', 'wpuf-pro' ), 'manage_options', 'wpuf_mailchimp', array($this, 'mailchimp_page') );
    }

    /**
     * Submenu Call Back Page
     */
    public function mailchimp_page() {
        require_once dirname( __FILE__ ) . '/templates/mailchimp-template.php';
    }

    /**
     * Add Mailchimp tab in Each form
     */
    public function add_tab_profile_form() {
        ?>
        <a href="#wpuf-metabox-mailchimp" class="nav-tab" id="wpuf_mailchimp-tab"><?php _e( 'Mailchimp', 'wpuf-pro' ); ?></a>
        <?php
    }

    /**
     * Display settings option in tab content
     */
    public function add_tab_content_profile_form() {
        ?>
        <div id="wpuf-metabox-mailchimp" class="group">
            <?php require_once dirname( __FILE__ ) . '/templates/mailchimp-settings-tab.php'; ?>
        </div>
        <?php
    }

    /**
     * Send Subscribe request in Mailchimp
     * @param  integer $user_id
     * @param  integer $form_id
     * @param  array $form_settings
     */
    public function subscribe_user_after_registration( $user_id, $form_id, $form_settings ) {

        if ( ! isset( $form_settings['enable_mailchimp'] ) || $form_settings['enable_mailchimp'] == 'no' ) {
            return;
        }

        $wpuf_cond          = isset( $form_settings['integrations']['mailchimp']['wpuf_cond'] ) ? $form_settings['integrations']['mailchimp']['wpuf_cond'] : '';
        $conditional_logic  = isset( $wpuf_cond['condition_status'] ) ? $wpuf_cond['condition_status'] : 'no';
        $condition_name     = isset( $wpuf_cond['conditions']['name'] ) ? $wpuf_cond['conditions']['name'] : '';
        $condition_operator = isset( $wpuf_cond['conditions']['operator'] ) ? $wpuf_cond['conditions']['operator'] : '=';
        $condition_option   = isset( $wpuf_cond['conditions']['option'] ) ? $wpuf_cond['conditions']['option'] : '';
        $cond_field_value   = isset( $_POST[$condition_name] ) ? $_POST[$condition_name] : '';

        if ( $conditional_logic == 'yes' && !empty( $cond_field_value ) ) {
            $value = $cond_field_value;

            if ( is_array( $cond_field_value ) ) {
                if ( in_array($condition_option, $cond_field_value) )  {
                    $value = $condition_option;
                }
            }

            if ( $condition_operator == '=' && $condition_option != $value ) {
                return;
            }

            if ( $condition_operator == '!=' && $condition_option == $value ) {
                return;
            }
        }

        require_once dirname( __FILE__ ) . '/classes/mailchimp.php';
        $user = get_user_by( 'id', $user_id );

        $MailChimp = new MailChimp( get_option( 'wpuf_mailchimp_api_key' ) );

        $result = $MailChimp->call( 'lists/' . $form_settings['mailchimp_list'], array(
            'email_address' => $user->user_email,
            'status'        => 'subscribed',
            'merge_fields'  => array(
                'FNAME'         => $user->first_name,
                'LNAME'         =>$user->last_name,
            ),
            'double_optin'  => ( isset( $form_settings['enable_double_optin'] ) && $form_settings['enable_double_optin'] == 'yes' ) ? true : false
        ) );
    }

} // WPUF_Mailchimp

$wpuf_mailchimp = WPUF_Mailchimp::init();
