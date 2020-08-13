<?php
/*
Plugin Name: HTML Email Templates
Plugin URI: http://wedevs.com/
Thumbnail Name: email-templates.png
Description: Send Email Notifications with HTML Template
Version: 1.0
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
 * WPUF_Email_Templates class
 *
 * @class WPUF_Email_Templates The class that holds the entire WPUF_Email_Templates plugin
 */
class WPUF_Email_Templates {

    /**
     * Constructor for the WPUF_Email_Templates class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     *
     * @uses is_admin()
     * @uses add_action()
     */
    function __construct() {
        add_filter( 'wp_mail_content_type', array( $this, 'mail_content_type' ), 10, 1 );
        add_filter( 'wp_mail_from', array( $this, 'custom_wp_mail_from' ), 10, 1 );
        add_filter( 'wp_mail_from_name', array( $this, 'custom_wp_mail_from_name' ), 10, 1 );
        add_filter( 'retrieve_password_title', array( $this, 'retrieve_password_title' ), 10 );
        add_filter( 'retrieve_password_message', array( $this, 'replace_retrieve_password_message' ), 10, 3 );
        add_filter( 'wpuf_settings_fields', array( $this, 'add_settings_fields' ), 10, 1 );
    }

    /**
     * Initializes the WPUF_Email_Templates() class
     *
     * Checks for an existing WPUF_Email_Templates() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new WPUF_Email_Templates();
        }

        return $instance;
    }

    /**
     *Set mail content type
     */
    public function mail_content_type( $content_type ) {
        return 'text/html';
    }

    /**
     *Set mail content type
     */
    public function custom_wp_mail_from( $email ) {
        $email = wpuf_get_option( 'from_address', 'wpuf_mails' );
        return $email;
    }

    /**
     *Set mail from name
     */
    public function custom_wp_mail_from_name( $name ) {
        $name = wpuf_get_option( 'from_name', 'wpuf_mails' );
        return $name;
    }

    /**
     * Add Settings Fields
     */
    public function add_settings_fields( $settings_fields ) {

        $settings_fields['wpuf_mails'][] = array(
            'name'    => 'email_setting',
            'label'   => __( '<span class="dashicons dashicons-admin-generic"></span> Template Settings', 'wpuf-pro' ),
            'type'    => 'html',
            'class'   => 'email-setting',
        );

        $settings_fields['wpuf_mails'][] =array(
            'name'    => 'from_name',
            'label'   => __( '"From" name', 'wpuf-pro' ),
            'desc'    => __( 'How the sender name appears in outgoing WPUF emails.', 'wpuf-pro' ),
            'default' => get_bloginfo(),
            'type'    => 'text',
            'class'   => 'email-setting-option',
        );

        $settings_fields['wpuf_mails'][] = array(
            'name'    => 'from_address',
            'label'   => __( '"From" address', 'wpuf-pro' ),
            'desc'    => __( 'How the sender email appears in outgoing WPUF emails.', 'wpuf-pro' ),
            'default' => get_option( 'admin_email' ),
            'type'    => 'text',
            'class'   => 'email-setting-option',
        );

        $settings_fields['wpuf_mails'][] = array(
            'name'    => 'header_image',
            'label'   => __( 'Header Image', 'wpuf-pro' ),
            'desc'    => __( 'URL to an image you want to show in the email header. For best performance select image of max Width 200px', 'wpuf-pro' ),
            'default' => '',
            'type'    => 'file',
            'class'   => 'email-setting-option',
        );

        $settings_fields['wpuf_mails'][] = array(
            'name'    => 'footer_text',
            'label'   => __( 'Footer Text', 'wpuf-pro' ),
            'desc'    => __( 'The text to appear in the footer of WPUF emails.', 'wpuf-pro' ),
            'default' => get_bloginfo(),
            'type'    => 'textarea',
            'class'   => 'email-setting-option',
        );

        $settings_fields['wpuf_mails'][] = array(
            'name'    => 'base_color',
            'label'   => __( 'Base Color', 'wpuf-pro' ),
            'desc'    => __( 'The base color for WPUF email templates.', 'wpuf-pro' ),
            'default' => '#cccccc',
            'type'    => 'color',
            'class'   => 'email-setting-option',
        );

        $settings_fields['wpuf_mails'][] = array(
            'name'    => 'background_color',
            'label'   => __( 'Background color', 'wpuf-pro' ),
            'desc'    => __( 'The background color for WooCommerce email templates.', 'wpuf-pro' ),
            'default' => '#ffffff',
            'type'    => 'color',
            'class'   => 'email-setting-option',
        );

        $settings_fields['wpuf_mails'][] = array(
            'name'    => 'body_background_color',
            'label'   => __( 'Body Background color', 'wpuf-pro' ),
            'desc'    => __( 'The main body background color.', 'wpuf-pro' ),
            'default' => '#f5f5f5',
            'type'    => 'color',
            'class'   => 'email-setting-option',
        );

        $settings_fields['wpuf_mails'][] = array(
            'name'    => 'body_text_color',
            'label'   => __( 'Body text color', 'wpuf-pro' ),
            'desc'    => __( 'The main body text color.', 'wpuf-pro' ),
            'default' => '#000000',
            'type'    => 'color',
            'class'   => 'email-setting-option',
        );

        $settings_fields['wpuf_mails'][] = array(
            'name'    => 'reset_email_setting',
            'label'   => __( '<span class="dashicons dashicons-unlock"></span> Reset Email', 'wpuf-pro' ),
            'type'    => 'html',
            'class'   => 'reset-email-setting',
        );

        $settings_fields['wpuf_mails'][] = array(
            'name'    => 'reset_email_subject',
            'label'   => __( 'Password reset request mail subject', 'wpuf-pro' ),
            'desc'    => __( 'Subject for the email when any user on your website changes the password, the user will get password reset request notification email', 'wpuf-pro' ),
            'default' => __( 'Password Reset', 'wpuf-pro' ),
            'type'    => 'text',
            'class'   => 'reset-email-setting-option',
        );

        $settings_fields['wpuf_mails'][] = array(
            'name'    => 'reset_email_body',
            'label'   => __( 'Password reset request mail body', 'wpuf-pro' ),
            'desc'    => __( "Content for the email when any user on your website changes the password, the user will get password reset request notification email <br><strong>You may use: </strong><code>{username}</code><code>{blogname}</code><code>{password_reset_link}</code>", 'wpuf-pro' ),
            'default' => "Hello!

            You asked us to reset your password for your account.

            To reset your password, visit the following address:
            <a href='{password_reset_link}'>Click Here</a>

            Thanks!",
            'type'    => 'wysiwyg',
            'class'   => 'reset-email-setting-option',
        );

        $settings_fields['wpuf_mails'][] = array(
            'name'    => 'confirmation_email_setting',
            'label'   => __( '<span class="dashicons dashicons-email-alt"></span> Resend Confirmation Email', 'wpuf-pro' ),
            'type'    => 'html',
            'class'   => 'confirmation-email-setting',
        );

        $settings_fields['wpuf_mails'][] = array(
            'name'    => 'confirmation_mail_subject',
            'label'   => __( 'Subject', 'wpuf-pro' ),
            'desc'    => __( 'Subject for the confirmation email', 'wpuf-pro' ),
            'default' => 'Account Activation',
            'type'    => 'text',
            'class'   => 'confirmation-email-setting-option'
        );

        $settings_fields['wpuf_mails'][] = array(
            'name'    => 'confirmation_mail_body',
            'label'   => __( 'Body', 'wpuf-pro' ),
            'desc'    => __( "Content for the confirmation email <br><strong>You may use: </strong><code>{username}</code><code>{blogname}</code><code>{activation_link}</code>", 'wpuf-pro' ),
            'default' => "Congrats! You are Successfully registered to {blogname}

            To activate your account, please click the link below
            {activation_link}

            Thanks!",
            'type'    => 'wysiwyg',
            'class'   => 'confirmation-email-setting-option'
        );

        return $settings_fields;
    }

    /**
     *Set reset password title
     */
    public function retrieve_password_title( $title ) {
        $title = wpuf_get_option( 'reset_email_subject', 'wpuf_mails' );
        return $title;
    }

    /**
     * Returns the message body for the password reset mail.
     * Called through the retrieve_password_message filter.
     *
     * @param string  $message    Default mail message.
     * @param string  $key        The activation key.
     * @param string  $user_login The username for the user.
     * @param WP_User $user_data  WP_User object.
     *
     * @return string   The mail message to send.
     */
    public function replace_retrieve_password_message( $message, $key, $user_login ) {

        $subject    = wpuf_get_option( 'reset_email_subject', 'wpuf_mails' );
        $message    = wpuf_get_option( 'reset_email_body', 'wpuf_mails' );
        $blogname   = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

        $reset_link = add_query_arg( array(
            'action' => 'rp',
            'key'    => $key,
            'login'  => rawurlencode( $user_login )
        ), wp_login_url() );

        $field_search = array( '{username}', '{blogname}', '{password_reset_link}' );

        $field_replace = array(
            $user_login,
            $blogname,
            $reset_link
        );

        $message = str_replace( $field_search, $field_replace, $message );
        $message = get_formatted_mail_body( $message, $subject );

        return $message;

    }

} // WPUF_Email_Templates

$wpuf_email_templates = WPUF_Email_Templates::init();
