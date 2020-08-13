<?php
/**
 * Plugin Name: GetResponse
 * Description: Subscribe a contact to GetResponse when a form is submited
 * Plugin URI: https://wedevs.com/wp-user-frontend-pro/
 * Thumbnail Name: getresponse.png
 * Author: weDevs
 * Version: 1.0
 * Author URI: https://wedevs.com
 */

/**
 * GetResponse CLass
 */
class WPUF_GetResponse {

    function __construct() {

        add_action( 'wpuf_admin_menu', array( $this, 'add_getresponse_menu' ) );
        add_action( 'wpuf_profile_form_tab', array( $this, 'add_tab_getresponse_form' ) );
        add_action( 'wpuf_profile_form_tab_content', array( $this, 'add_tab_content_profile_form' ) );

        add_action( 'init', array( $this, 'get_lists' ) );
        add_action( 'wpuf_after_register', array( $this, 'subscribe_user' ), 10, 3 );
    }

    /**
     * Require the GetResponse class if not exists
     *
     * @return void
     */
    public function require_getresponse() {
        if ( ! class_exists( 'GetResponse' ) ) {
            require_once dirname( __FILE__ ) . '/class-getresponse.php';
        }
    }

    /**
     * Add GetResponse Submenu in WPUF
     */
    public function add_getresponse_menu() {
        add_submenu_page( 'wp-user-frontend', __( 'GetResponse', 'wpuf-pro' ), __( 'GetResponse', 'wpuf-pro' ), 'manage_options', 'wpuf_getresponse', array($this, 'getresponse_page') );
    }

    /**
     * Submenu Call Back Page
     */
    public function getresponse_page() {
        require_once dirname( __FILE__ ) . '/templates/getresponse-template.php';
    }

    /**
     * Add GetResponse tab in Each form
     */
    public function add_tab_getresponse_form() {
        ?>
        <a href="#wpuf-metabox-getresponse" class="nav-tab" id="wpuf_getresponse-tab"><?php _e( 'GetResponse', 'wpuf-pro' ); ?></a>
        <?php
    }

    /**
     * Display settings option in tab content
     */
    public function add_tab_content_profile_form() {
        ?>
        <div id="wpuf-metabox-getresponse" class="group">
            <?php require_once dirname( __FILE__ ) . '/templates/getresponse-settings-tab.php'; ?>
        </div>
        <?php
    }

    /**
     * Fetch the udpated list from getresponse and save it
     *
     * @return array
     */
    public function get_lists() {

        $this->require_getresponse();

        $lists       = array();
        $gr_key      = $this->get_api_key();

        if ( isset( $gr_key ) ) {
            $getresponse = new GetResponse( $gr_key );
            $response    = $getresponse->getCampaigns();

            if ( !isset( $response->httpStatus ) ) {
                foreach ( $response as $list ) {
                    $lists[] = array(
                        'id'     => $list->campaignId,
                        'name'   => $list->name
                    );
                }

                update_option( 'wpuf_gr_lists', $lists );
            }
        }

    }

    /**
     * Get GetResponse API key
     */
    public function get_api_key() {

        $gr_api_key = get_option( 'wpuf_getresponse_api_key' );

        return $gr_api_key;
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

        if ( ! isset( $form_settings['enable_getresponse'] ) || $form_settings['enable_getresponse'] == 'no' ) {
            return;
        }

        $user          = get_user_by( 'id', $user_id );
        $list_selected = isset( $form_settings['getresponse_list'] ) ? $form_settings['getresponse_list'] : '';

        $this->require_getresponse();

        $gr_key = $this->get_api_key();

        $param = (object) array(
            'name'  => $user->user_nicename,
            'email' => $user->user_email,
            'campaign'  => array( 
                'campaignId' => $list_selected 
            ),
        );

        $getresponse = new GetResponse( $gr_key );

        $getresponse->addContact( $param );
    }
}

new WPUF_GetResponse();