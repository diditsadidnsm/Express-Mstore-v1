<?php
/*
Plugin Name: User Analytics
Plugin URI: http://wedevs.com/plugin/wp-user-frontend-pro/
Thumbnail Name: wpuf-ua.png
Description: Show user tracking info during post and registration from Frontend
Version: 1.1
Author: weDevs
Author URI: http://wedevs.com/
License: GPL2
*/

/**
 * Copyright (c) 2014 weDevs ( email: info@wedevs.com ). All rights reserved.
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

/**
 * WPUF_User_Analytics class
 *
 * @class WPUF_User_Analytics The class that holds the entire WPUF_User_Analytics plugin
 */
class WPUF_User_Analytics {

    /**
     * Constructor for the WPUF_User_Analytics class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     *
     * @uses is_admin()
     * @uses add_action()
     */
    public function __construct() {

        add_action( 'wpuf_add_post_after_insert', array( $this, 'save_user_anaytics_info' ), 10, 4 );
        add_action( 'user_register', array( $this, 'save_user_anaytics_on_registration' ), 10, 1 );

        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );

        add_action( 'edit_user_profile', array( $this, 'show_user_analytics_info' ), 11 );

        add_filter( 'wpuf_options_others', array( $this, 'user_analytics_fields' ) );
    }

    /**
     * Add user Analytics settings option
     *
     * @param array
     * @return array
     */
    public function user_analytics_fields( $settings_fields ){
        $user_analytics_fields = array(
            array(
                'name'  => 'ipstack_key',
                'label' => __( 'Ipstack API Key', 'wpuf-pro' ),
                'desc'  => __( '<a target="_blank" href="https://ipstack.com/dashboard">Register here</a> to get your free ipstack api key', 'wpuf-pro' ),
            ),
        );

        return array_merge( $settings_fields, $user_analytics_fields );
    }

    /**
     * Initializes the WPUF_User_Analytics() class
     *
     * Checks for an existing WPUF_User_Analytics() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new WPUF_User_Analytics();
        }

        return $instance;
    }

    /**
     * Check user Analytics info exist or not with post
     *
     * @param object  $post
     * @return boolean
     */
    function check_user_analytics_exist( $post ) {

        $form_id             = get_post_meta( $post->ID, '_wpuf_form_id', true );
        $user_analytics_info = get_post_meta( $post->ID, 'user_analytics_info', true );

        if ( empty( $form_id ) && empty( $user_analytics_info ) ) {
            return false;
        }

        return true;
    }

    /**
     * Hook CB for WPUF after post insert
     *
     * @param integer $post_id
     * @param integer $form_id
     * @param array   $form_settings
     * @param array   $form_vars
     * @return void
     */
    function save_user_anaytics_info( $post_id, $form_id, $form_settings, $form_vars ) {
        $user_analytics = $this->get_user_analytics_info();
        update_post_meta( $post_id, 'user_analytics_info', $user_analytics );
    }

    /**
     * Save User analytics info during registration
     *
     * @param integer $user_id
     * @return void
     */
    function save_user_anaytics_on_registration( $user_id ) {
        $user_analytics = $this->get_user_analytics_info();
        update_user_meta( $user_id, 'user_analytics_info_during_register', $user_analytics );
    }

    /**
     * Get User analytics info from telize.com apis
     *
     * @return array
     */
    function get_user_analytics_info() {
        global $wp_version;

        $client_ip = wpuf_get_client_ip();

        if ( $client_ip == "::1" ) {
            $externalContent = file_get_contents('http://checkip.dyndns.com/');
            preg_match('/Current IP Address: \[?([:.0-9a-fA-F]+)\]?/', $externalContent, $m);
            $client_ip = $m[1];
        }

        $ipstack_key = wpuf_get_option( 'ipstack_key', 'wpuf_general' );

        $url = 'http://api.ipstack.com/' . $client_ip . '?access_key=' . $ipstack_key;
        $params = array(
            'timeout'    => 30,
            'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url( '/' )
        );

        $response  = wp_remote_get( $url, $params );
        $user_data = wp_remote_retrieve_body( $response );

        if ( is_wp_error( $response ) || $response['response']['code'] != 200 ) {
            return false;
        }

        $user_info = (array) json_decode( $user_data );
        unset( $user_info['location'] );
        $user_analytics_info = array_merge( $user_info, array( 'browser_pc' => $_SERVER['HTTP_USER_AGENT'] ) );

        return $user_analytics_info;
    }

    /**
     * Add New Metabox called User Anlytics Info
     */
    function add_meta_box() {
        global $post;

        $post_types = get_post_types( array( 'public' => true ) );

        if ( !empty( $post ) ) {
            if ( $this->check_user_analytics_exist( $post ) ) {
                foreach ( $post_types as $post_type ) {
                    add_meta_box( 'show_user_analytics', __( 'Post Author Analytics Info', 'wpuf-pro' ), array( $this, 'render_meta_box_content' ), $post_type, 'advanced', 'high' );
                }
            }
        }
    }

    /**
     * CB fucntion for Metabox for displaying content
     *
     * @param object  $post
     * @return void
     */
    function render_meta_box_content( $post ) {
        $form_id = get_post_meta( $post->ID, '_wpuf_form_id', true );
        $user_analytics_info = get_post_meta( $post->ID, 'user_analytics_info', true ) ? get_post_meta( $post->ID, 'user_analytics_info', true ) : array();
            ?>
            <table class="form-table wpuf-user-analytics-listing">
                <?php if ( !empty( $user_analytics_info ) ) {
                    if ( isset( $user_analytics_info['success'] ) && $user_analytics_info['success'] == false ) { ?>
                        <tbody>
                            <tr>
                                <td><strong><?php echo $user_analytics_info['error']->info; ?></strong></td>
                            </tr>
                        <?php } else { ?>
                        <thead>
                        <tr>
                            <th><?php _e( 'User info title', 'wpuf-pro' ); ?></th>
                            <th><?php _e( 'Value', 'wpuf-pro' ); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ( $user_analytics_info as $key => $value ): ?>
                            <tr>
                                <td><strong><?php echo ucfirst( str_replace( '_', ' ', $key ) ); ?></strong></td>
                                <td><?php echo $value; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php } ?>

                <?php } ?>
                </tbody>
            </table>
            <style>
            .wpuf-user-analytics-listing td {
                font-size: 13px;
            }
            .wpuf-user-analytics-listing td, .wpuf-user-analytics-listing th {
                padding: 5px 8px;
            }
            </style>
        <?php
    }

    /**
     * Show User analytics info in profile edit page;
     *
     * @param object  $user
     * @return void
     */
    public function show_user_analytics_info( $user ) {

        $user_analytics_info = get_user_meta( $user->ID, 'user_analytics_info_during_register', true );

        if ( !$user_analytics_info && empty( $user_analytics_info ) ) {
            return;
        }
        if ( !$user_analytics_info['success'] ) {
            return;
        }
        ?>
            <hr>
            <h3>User Analytics Info</h3>

        <?php
            if ( array_key_exists( 'error', $user_analytics_info ) ) {
                echo '<p>' . __( 'No analytics data found', 'wpuf-pro' ) . '</p>';
                return;
            }
        ?>
            <table class="form-table wpuf-user-analytics-listing-profile">
                <thead>
                    <tr>
                        <th><?php _e( 'User info title', 'wpuf-pro' ); ?></th>
                        <th><?php _e( 'Value', 'wpuf-pro' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ( $user_analytics_info as $key => $value ): ?>
                    <tr>
                        <td><strong><?php echo ucfirst( str_replace( '_', ' ', $key ) ); ?></strong></td>
                        <td><?php echo $value; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <style>
            .wpuf-user-analytics-listing-profile{
                border: 1px solid #ccc;
                background-color: #fff;
            }

            .wpuf-user-analytics-listing-profile thead {
                border: 1px solid #ccc;
            }

            .wpuf-user-analytics-listing-profile tbody tr {
                border: 1px solid #ccc;
            }

            .wpuf-user-analytics-listing-profile td {
                font-size: 13px;
            }
            .wpuf-user-analytics-listing-profile td, .wpuf-user-analytics-listing-profile th {
                padding: 5px 8px;
            }
            </style>
        <?php
    }

} // WPUF_User_Analytics

$wpuf_ua = WPUF_User_Analytics::init();
