<?php
/**
 * Plugin Name: ConvertKit
 * Description: Subscribe a contact to ConvertKit when a form is submited
 * Plugin URI: https://wedevs.com/wp-user-frontend-pro/
 * Thumbnail Name: convertkit.png
 * Author: weDevs
 * Version: 1.0
 * Author URI: https://wedevs.com
 */

/**
 * ConvertKit CLass
 */
class WPUF_ConvertKit {

    function __construct() {

        add_action( 'wpuf_admin_menu', array( $this, 'add_convertkit_menu' ) );
        add_action( 'wpuf_profile_form_tab', array( $this, 'add_tab_convertkit_form' ) );
        add_action( 'wpuf_profile_form_tab_content', array( $this, 'add_tab_content_profile_form' ) );

        add_action( 'init', array( $this, 'get_lists' ) );
        add_action( 'wpuf_after_register', array( $this, 'subscribe_user' ), 10, 3 );
    }

    /**
     * Require the convertkit class if not exists
     *
     * @return void
     */
    public function require_convertkit() {
        if ( ! class_exists( 'ConvertKit' ) ) {
            require_once dirname( __FILE__ ) . '/class-convertkit.php';
        }
    }

    /**
     * Add ConvertKit Submenu in WPUF
     */
    public function add_convertkit_menu() {
        add_submenu_page( 'wp-user-frontend', __( 'ConvertKit', 'wpuf-pro' ), __( 'ConvertKit', 'wpuf-pro' ), 'manage_options', 'wpuf_convertkit', array($this, 'convertkit_page') );
    }

    /**
     * Submenu Call Back Page
     */
    public function convertkit_page() {
        require_once dirname( __FILE__ ) . '/templates/convertkit-template.php';
    }

    /**
     * Add ConvertKit tab in Each form
     */
    public function add_tab_convertkit_form() {
        ?>
        <a href="#wpuf-metabox-convertkit" class="nav-tab" id="wpuf_convertkit-tab"><?php _e( 'ConvertKit', 'wpuf-pro' ); ?></a>
        <?php
    }

    /**
     * Display settings option in tab content
     */
    public function add_tab_content_profile_form() {
        ?>
        <div id="wpuf-metabox-convertkit" class="group">
            <?php require_once dirname( __FILE__ ) . '/templates/convertkit-settings-tab.php'; ?>
        </div>
        <?php
    }

    /**
     * Fetch the udpated list from convertkit and save it
     *
     * @return array
     */
    public function get_lists() {

        $this->require_convertkit();

        $lists      = array();
        $ck_api_key = get_option( 'wpuf_convertkit_api_key' );

        if ( !empty( $ck_api_key ) ) {
            $convertkit = new ConvertKit( $ck_api_key );
            $response   = $convertkit->getForms();
            $response = json_decode( $response,true );

            if ( !isset( $response->error_message ) ) {
                foreach ( $response['forms'] as $list ) {
                    $lists[] = array(
                        'id'     => $list['id'],
                        'name'   => $list['name']
                    );
                }

                update_option( 'wpuf_ck_lists', $lists );
            }
        }
    }


    /**
     * Subscribe a user when a form is submitted
     *
     * @param  int $user_id
     * @param  int $form_id
     * @param  array $form_settings
     *
     * @return void
     */
    public function subscribe_user( $user_id, $form_id, $form_settings ) {
        if ( ! isset( $form_settings['enable_convertkit'] ) || $form_settings['enable_convertkit'] == 'no' ) {
            return;
        }

        if( empty( $form_settings['convertkit_list'] ) || $form_settings['convertkit_list'] == '' ) {
            return;
        }

        $user          = get_user_by( 'id', $user_id );
        $selected_form = get_option( 'wpuf_ck_lists' );
        // $selected_form = $selected_form[0]['id'];
        $selected_form = $form_settings['convertkit_list'];
        $this->require_convertkit();

        $ck_api_key      = get_option( 'wpuf_convertkit_api_key' );
        $ck_secret_key   = get_option( 'wpuf_convertkit_secret_key' );
        $ck_double_optin = get_option( 'wpuf_convertkit_double_opt' );
        $convertkit = new ConvertKit( $ck_api_key );

        $response = $convertkit->subscribeToAForm( $selected_form, $user->user_email, $user->display_name, $ck_double_optin );
    }
}

new WPUF_ConvertKit();
