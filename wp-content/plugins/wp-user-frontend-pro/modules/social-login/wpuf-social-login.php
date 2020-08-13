<?php
/*
Plugin Name: Social Login & Registration
Plugin URI: http://wedevs.com/plugin/wp-user-frontend-pro/
Thumbnail Name: Social-Media-Login.png
Description: Add Social Login and registration feature in WP User Frontend
Version: 1.1
Author: weDevs
Author URI: http://wedevs.com/
License: GPL2
*/

/**
 * Copyright (c) 2017 weDevs ( email: info@wedevs.com ). All rights reserved.
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
 * WPUF_Social_Login
 *
 * @class WPUF_Social_Login The class that holds the entire WPUF_Social_Login plugin
 */

use Hybridauth\Exception\Exception;
use Hybridauth\Hybridauth;
use Hybridauth\HttpClient;
use Hybridauth\Storage\Session;

Class WPUF_Social_Login {

    private $callback;
    private $config;

    /**
     * Load automatically when class instantiated
     *
     * @since 2.4
     *
     * @uses actions|filter hooks
     */
    public function __construct() {
        $provider = ! empty( $_GET['wpuf_reg'] ) ? $_GET['wpuf_reg'] : '';

        if ( ! class_exists( 'Hybridauth' ) ) {
            if ( class_exists( 'WeDevs_Dokan' ) && class_exists( 'Dokan_Pro' ) ) {
                require_once DOKAN_PRO_INC . '/lib/hybridauth/autoload.php';
            } else {
                require_once dirname( __FILE__ ) . '/lib/hybridauth/autoload.php';
            }
        }

        $this->callback = site_url() . '/account/';

        add_filter( 'wpuf_settings_sections', array( $this, 'wpuf_social_settings_tab' ) );
        add_filter( 'wpuf_settings_fields', array( $this, 'wpuf_pro_social_api_fields' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) , 9 );

        //Hybrid auth action
        add_action( 'init', array( $this, 'init_session' ) );
        add_action( 'init', array( $this, 'monitor_autheticate_requests' ) );

        //add registration form selection metabox
        add_action( 'add_meta_boxes', array( $this, 'add_meta_box_reg_form_select' ) );
        add_action( 'save_post', array( $this, 'reg_form_selection_metabox_save' ), 10, 2 );

        // add social buttons on registration form
        add_action( 'wpuf_login_form_bottom', array( $this, 'render_social_logins' ) );
        add_action( 'wpuf_reg_form_bottom', array( $this, 'render_social_logins' ) );
        add_action( 'wpuf_add_profile_form_bottom', array( $this, 'render_social_logins' ) );

        $this->config   = $this->get_providers_config();

        if ( 'on' != wpuf_get_option( 'enabled', 'wpuf_social_api' ) ) {
           return;
        }
    }

    /**
     * Instantiate the class
     *
     * @since 2.6
     *
     * @return object
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new WPUF_Social_Login();
        }

        return $instance;
    }

    /**
    * Register all scripts
    *
    * @return void
    **/
    function enqueue_scripts() {
        // register styles
        wp_enqueue_style( 'wpuf-social-style', WPUF_PRO_ASSET_URI . '/css/jssocials.css' );
        // enqueue scripts
        wp_enqueue_script( 'wpuf-social-script', WPUF_PRO_ASSET_URI . '/js/jssocials.min.js', array( 'jquery' ), false, true );
    }

    /**
     * Initialize session at start
     */
    public function init_session() {
        if ( session_id() == '' ) {
            session_start();
        }
    }

    /**
     * Get configuration values for HybridAuth
     *
     * @return array
     */
    private function get_providers_config() {

        $config = [
            'callback' => $this->callback,
            'providers' => [
                "Google"     => [
                    "enabled" => true,
                    "keys"    => [ "id" => "", "secret" => "" ],
                ],
                "Facebook"    => [
                    "enabled"        => true,
                    "keys"           => [ "id" => "", "secret" => "" ],
                    "trustForwarded" => false,
                    "scope"          => "email, public_profile"
                ],
                "Twitter"    => [
                    "enabled"      => true,
                    "keys"         => [ "key" => "", "secret" => "" ],
                    "includeEmail" => true,
                ],
                "LinkedIn"   => [
                    "enabled" => true,
                    "keys"    => [ "id" => "", "secret" => "" ],
                ],
                "Instagram"   => [
                    "enabled" => true,
                    "keys"    => [ "id" => "", "secret" => "" ],
                ],
            ],
        ];

        //facebook config from admin
        $fb_id     = wpuf_get_option( 'fb_app_id', 'wpuf_social_api' );
        $fb_secret = wpuf_get_option( 'fb_app_secret', 'wpuf_social_api' );

        if ( $fb_id != '' && $fb_secret != '' ) {
            $config['providers']['Facebook']['keys']['id']     = $fb_id;
            $config['providers']['Facebook']['keys']['secret'] = $fb_secret;
        }

        //google config from admin
        $g_id     = wpuf_get_option( 'google_app_id', 'wpuf_social_api' );
        $g_secret = wpuf_get_option( 'google_app_secret', 'wpuf_social_api' );

        if ( $g_id != '' && $g_secret != '' ) {
            $config['providers']['Google']['keys']['id']     = $g_id;
            $config['providers']['Google']['keys']['secret'] = $g_secret;
        }
        //linkedin config from admin
        $l_id     = wpuf_get_option( 'linkedin_app_id', 'wpuf_social_api' );
        $l_secret = wpuf_get_option( 'linkedin_app_secret', 'wpuf_social_api' );

        if ( $l_id != '' && $l_secret != '' ) {
            $config['providers']['LinkedIn']['keys']['id']     = $l_id;
            $config['providers']['LinkedIn']['keys']['secret'] = $l_secret;
        }

        //Twitter config from admin
        $twitter_id     = wpuf_get_option( 'twitter_app_id', 'wpuf_social_api' );
        $twitter_secret = wpuf_get_option( 'twitter_app_secret', 'wpuf_social_api' );

        if ( $twitter_id != '' && $twitter_secret != '' ) {
            $config['providers']['Twitter']['keys']['key']    = $twitter_id;
            $config['providers']['Twitter']['keys']['secret'] = $twitter_secret;
        }

        /**
         * Filter the Config array of Hybridauth
         *
         * @since 1.0.0
         *
         * @param array $config
         */
        $config = apply_filters( 'wpuf_social_providers_config', $config );

        return $config;
    }

    /**
     * Monitors Url for Hauth Request and process Hauth for authentication
     *
     * @return void
     */
    public function monitor_autheticate_requests() {
        try {
            /**
             * Feed the config array to Hybridauth
             *
             * @var Hybridauth
             */
            $hybridauth = new Hybridauth( $this->config );

            /**
             * Initialize session storage.
             *
             * @var Session
             */
            $storage = new Session();

            /**
             * Hold information about provider when user clicks on Sign In.
             */
            $provider = ! empty( $_GET['wpuf_reg'] ) ? $_GET['wpuf_reg'] : '';

            if ( $provider ) {
                $storage->set( 'provider', $provider );
            }

            if ( $provider = $storage->get( 'provider' ) ) {
                $adapter = $hybridauth->authenticate( $provider );

                $storage->set('provider', null);
            }

            if ( ! isset( $adapter ) ) {
                return;
            }

            $user_profile = $adapter->getUserProfile();

            if ( ! $user_profile ) {
                wc_add_notice( __( 'Something went wrong! please try again', 'wpuf-pro' ), 'success' );
                wp_redirect( $this->callback );
            }

            $wp_user = get_user_by( 'email', $user_profile->email );

            if ( ! $wp_user ) {
                $this->register_new_user( $user_profile );
            } else {
                $this->login_user( $wp_user );
            }

        } catch ( Exception $e ) {
            echo $e->getMessage();
        }
    }

    /**
     * Filter admin menu settings section
     *
     * @param type $sections
     *
     * @return array
     */
    function wpuf_social_settings_tab ( $settings ) {

        $s_settings = array(
            array(
                'id'    => 'wpuf_social_api',
                'title' => __( 'Social Login', 'wpuf-pro' ),
                'icon' => 'dashicons-share'
            )
        );

        return array_merge( $settings, $s_settings);
    }

    /**
     * Render settings fields for admin settings section
     *
     * @param array $settings_fields
     *
     * @return array
     **/

    function wpuf_pro_social_api_fields ( $settings_fields ) {

        $social_settings_fields = array(
            'wpuf_social_api' => array(
                'enabled' => array(
                    'name'  => 'enabled',
                    'label' => __( 'Enable Social Login', 'wpuf-pro' ),
                    'type'  => "checkbox",
                    'desc'  => __( 'Enabling this will add Social Icons under registration form to allow users to login or register using Social Profiles', 'wpuf-pro' ),
                ),
                'facebook_app_label'  => array(
                    'name'  => 'fb_app_label',
                    'label' => __( 'Facebook App Settings', 'wpuf-pro' ),
                    'type'  => "html",
                    'desc'  => '<a target="_blank" href="https://developers.facebook.com/apps/">' . __( 'Create an App', 'wpuf-pro' ) . '</a> if you don\'t have one and fill App ID and App Secret below. Also replace the {{form_id}} in Redirect URI with the registration form id where you are using Social Login.',
                ),
                'facebook_app_url'    => array(
                    'name'  => 'fb_app_url',
                    'label' => __( 'Redirect URI', 'wpuf-pro' ),
                    'type'  => 'html',
                    'desc'  => "<input class='regular-text' type='text' disabled value='{$this->callback}'>",
                ),
                'facebook_app_id'     => array(
                    'name'  => 'fb_app_id',
                    'label' => __( 'App Id', 'wpuf-pro' ),
                    'type'  => 'text',
                ),
                'facebook_app_secret' => array(
                    'name'  => 'fb_app_secret',
                    'label' => __( 'App Secret', 'wpuf-pro' ),
                    'type'  => 'text',
                ),
                'twitter_app_label'   => array(
                    'name'  => 'twitter_app_label',
                    'label' => __( 'Twitter App Settings', 'wpuf-pro' ),
                    'type'  => 'html',
                    'desc'  => '<a target="_blank" href="https://apps.twitter.com/">' . __( 'Create an App', 'wpuf-pro' ) . '</a>' . __( 'if you don\'t have one and fill Consumer key and Consumer Secret below.', 'wpuf-pro' ),
                ),
                'twitter_app_url'     => array(
                    'name'  => 'twitter_app_url',
                    'label' => __( 'Callback URL', 'wpuf-pro' ),
                    'type'  => 'html',
                    'desc'  => "<input class='regular-text' type='text' disabled value='{$this->callback}'>",
                ),
                'twitter_app_id'      => array(
                    'name'  => 'twitter_app_id',
                    'label' => __( 'Consumer Key', 'wpuf-pro' ),
                    'type'  => 'text',
                ),
                'twitter_app_secret'  => array(
                    'name'  => 'twitter_app_secret',
                    'label' => __( 'Consumer Secret', 'wpuf-pro' ),
                    'type'  => 'text',
                ),
                'google_app_label'    => array(
                    'name'  => 'google_app_label',
                    'label' => __( 'Google App Settings', 'wpuf-pro' ),
                    'type'  => 'html',
                    'desc'  => '<a target="_blank" href="https://console.developers.google.com/project">' . __( 'Create an App', 'wpuf-pro' ) . '</a> if you don\'t have one and fill Client ID and Client secret below. Also replace the {{form_id}} in Redirect URI with the registration form id where you are using Social Login.',
                ),
                'google_app_url'      => array(
                    'name'  => 'google_app_url',
                    'label' => __( 'Redirect URI', 'wpuf-pro' ),
                    'type'  => 'html',
                    'desc'  => "<input class='regular-text' type='text' disabled value='{$this->callback}'>",
                ),
                'google_app_id'       => array(
                    'name'  => 'google_app_id',
                    'label' => __( 'Client ID', 'wpuf-pro' ),
                    'type'  => 'text',
                ),
                'google_app_secret'   => array(
                    'name'  => 'google_app_secret',
                    'label' => __( 'Client secret', 'wpuf-pro' ),
                    'type'  => 'text',
                ),
                'linkedin_app_label'  => array(
                    'name'  => 'linkedin_app_label',
                    'label' => __( 'Linkedin App Settings', 'wpuf-pro' ),
                    'type'  => 'html',
                    'desc'  => '<a target="_blank" href="https://www.linkedin.com/developer/apps">' . __( 'Create an App', 'wpuf-pro' ) . '</a>' . __( 'if you don\'t have one and fill Client ID and Client Secret below.', 'wpuf-pro' ),
                ),
                'linkedin_app_url'    => array(
                    'name'  => 'linkedin_app_url',
                    'label' => __( 'Redirect URL', 'wpuf-pro' ),
                    'type'  => 'html',
                    'desc'  => "<input class='regular-text' type='text' disabled value='{$this->callback}'>",
                ),
                'linkedin_app_id'     => array(
                    'name'  => 'linkedin_app_id',
                    'label' => __( 'Client ID', 'wpuf-pro' ),
                    'type'  => 'text',
                ),
                'linkedin_app_secret' => array(
                    'name'  => 'linkedin_app_secret',
                    'label' => __( 'Client Secret', 'wpuf-pro' ),
                    'type'  => 'text',
                )
            )
        );

        return array_merge( $settings_fields, $social_settings_fields );
    }

    /**
     * Render social login icons
     *
     * @return void
     */
    public function render_social_logins() {
        $configured_providers = [];

        //facebook config from admin
        $fb_id     = wpuf_get_option( 'fb_app_id', 'wpuf_social_api' );
        $fb_secret = wpuf_get_option( 'fb_app_secret', 'wpuf_social_api' );

        if ( $fb_id != '' && $fb_secret != '' ) {
            $configured_providers [] = 'facebook';
        }
        //google config from admin
        $g_id     = wpuf_get_option( 'google_app_id', 'wpuf_social_api' );
        $g_secret = wpuf_get_option( 'google_app_secret', 'wpuf_social_api' );

        if ( $g_id != '' && $g_secret != '' ) {
            $configured_providers [] = 'google';
        }
        //linkedin config from admin
        $l_id     = wpuf_get_option( 'linkedin_app_id', 'wpuf_social_api' );
        $l_secret = wpuf_get_option( 'linkedin_app_secret', 'wpuf_social_api' );

        if ( $l_id != '' && $l_secret != '' ) {
            $configured_providers [] = 'linkedin';
        }

        //Twitter config from admin
        $twitter_id     = wpuf_get_option( 'twitter_app_id', 'wpuf_social_api' );
        $twitter_secret = wpuf_get_option( 'twitter_app_secret', 'wpuf_social_api' );

        if ( $twitter_id != '' && $twitter_secret != '' ) {
            $configured_providers [] = 'twitter';
        }

        /**
         * Filter the list of Providers connect links to display
         *
         * @since 1.0.0
         *
         * @param array $providers
         */
        $providers = apply_filters( 'wpuf_social_provider_list', $configured_providers );

        $redirect_uri = preg_replace( "/^http:/i", "https:", $this->callback );

        $data = array(
            'base_url'  => $this->callback,
            'providers' => $providers,
            'pro'       => true
        );

        extract( $data );

        $current_url = "//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $p_id = url_to_postid( $current_url );
        $f_id = get_post_meta( $p_id, '_wpuf_profile_form_id', true );

        if ( ! is_user_logged_in() && ! empty( $configured_providers ) ) { ?>

            <hr>
            <div class="wpuf-social-login-text" style="text-align:center; font-weight: bold;"><?php __('You may also connect with', 'wpuf-pro'); ?></div>
            <br>
            <ul class="jssocials-shares">
                <?php foreach ( $providers as $provider ) : ?>
                    <li class="jssocials-share jssocials-share-<?php echo $provider ?>">
                        <a href="<?php echo add_query_arg( array( 'wpuf_reg' => $provider, 'form_id' => $f_id ), $this->callback ); ?>" class="jssocials-share-link">
                            <img src="<?php echo WPUF_PRO_ASSET_URI . '/images/social-icons/' . $provider . '.png'; ?>" class="jssocials-share-logo" alt=""> <?php echo ucfirst( $provider ); ?>
                        </a>
                    </li>
                <?php  endforeach; ?>
            </ul>
        <?php }
    }

    /**
     * Recursive function to generate a unique username.
     *
     * If the username already exists, will add a numerical suffix which will increase until a unique username is found.
     *
     * @param string $username
     *
     * @return string The unique username.
     */
    function generate_unique_username( $username ) {
        static $i;
        if ( null === $i ) {
            $i = 1;
        } else {
            $i++;
        }
        $username = preg_replace('/([^@]*).*/', '$1', $username);
        if ( !username_exists( $username ) ) {
            return $username;
        }
        $new_username = sprintf( '%s_%s', $username, $i );
        if ( !username_exists( $new_username ) ) {
            return $new_username;
        } else {
            return call_user_func( array( $this, 'generate_unique_username' ), $username );
        }
    }

    /**
     * Register a new user
     *
     * @param object $data
     *
     * @param string $provider
     *
     * @return void
     */
    private function register_new_user( $data ) {
        if( isset( $_GET['form_id'] ) ) {
            $form_id = $_GET['form_id'];
        }

        $form_settings = wpuf_get_form_settings( $form_id );
        $user_role     = isset( $form_settings['role'] ) ? $form_settings['role'] : 'subscriber';

        $uname = $data->email;
        if ( empty( $uname ) ) {
            $uname = $data->displayName;
        }

        $userdata = array(
            'user_login' => $this->generate_unique_username( $uname ),
            'user_email' => $data->email,
            'first_name' => $data->firstName,
            'last_name'  => $data->lastName,
            'role'       => $user_role,
        );

        $user_id = wp_insert_user( $userdata );

        if ( !is_wp_error( $user_id ) ) {
            $this->login_user( get_userdata( $user_id ) );
            wp_redirect( $this->callback );
            exit();
        }
    }

    /**
     * Log in existing users
     *
     * @param WP_User $wp_user
     *
     * return void
     */
    private function login_user( $wp_user ) {
        clean_user_cache( $wp_user->ID );
        wp_clear_auth_cookie();
        wp_set_current_user( $wp_user->ID );
        wp_set_auth_cookie( $wp_user->ID, true, false );
        update_user_caches( $wp_user );
    }

    /**
     * Meta box for all Post form selection
     *
     * Registers a meta box in public post types to select the desired WPUF Registration
     * form select box to assign a form id.
     *
     * @return void
     */
    public function add_meta_box_reg_form_select() {

        $post_types = get_post_types( array( 'public' => true ) );
        foreach ( $post_types as $post_type ) {
            add_meta_box( 'wpuf-select-reg-form', __('WPUF Registration Form', 'wpuf-pro'), array( $this, 'reg_form_selection_metabox' ), $post_type, 'side', 'high' );
        }
    }

    /**
     * Form selection meta box in post types
     *
     * Registered via $this->add_meta_box_form_select()
     *
     * @global object $post
     */
    public function reg_form_selection_metabox() {
        global $post;

        $forms = get_posts( array('post_type' => 'wpuf_profile', 'numberposts' => '-1' ) );
        $selected = get_post_meta( $post->ID, '_wpuf_profile_form_id', true );
        ?>

        <input type="hidden" name="wpuf_reg_form_select_nonce" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />

        <select name="wpuf_reg_form_select">
            <option value="">--</option>
            <?php foreach ($forms as $form) { ?>
            <option value="<?php echo $form->ID; ?>"<?php selected($selected, $form->ID); ?>><?php echo $form->post_title; ?></option>
            <?php } ?>
        </select>
        <?php
    }

    /**
     * Saves the form ID from form selection meta box
     *
     * @param int $post_id
     * @param object $post
     * @return int|void
     */
    public function reg_form_selection_metabox_save( $post_id, $post ) {
        if ( !isset($_POST['wpuf_reg_form_select'])) {
            return $post->ID;
        }

        if ( !wp_verify_nonce( $_POST['wpuf_reg_form_select_nonce'], plugin_basename( __FILE__ ) ) ) {
            return $post_id;
        }

        // Is the user allowed to edit the post or page?
        if ( !current_user_can( 'edit_post', $post->ID ) ) {
            return $post_id;
        }

        update_post_meta( $post->ID, '_wpuf_profile_form_id', $_POST['wpuf_reg_form_select'] );
    }

}

$wpuf_social_login = WPUF_Social_Login::init();
