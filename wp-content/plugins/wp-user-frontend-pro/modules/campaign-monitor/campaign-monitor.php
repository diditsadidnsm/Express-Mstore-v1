<?php
/**
 * Plugin Name: Campaign Monitor
 * Description: Subscribe a contact to Campaign Monitor when a form is submited
 * Plugin URI: https://wedevs.com/wp-user-frontend-pro/
 * Thumbnail Name: campaign_monitor.png
 * Author: weDevs
 * Version: 1.0
 * Author URI: https://wedevs.com
 */

/**
 * Campaign Monitor Integration class
 */
class WPUF_Campaign_Monitor {

    function __construct() {

        add_action( 'wpuf_admin_menu', array( $this, 'add_campaign_monitor_menu' ) );
        add_action( 'wpuf_profile_form_tab', array( $this, 'add_tab_campaign_monitor_form' ) );
        add_action( 'wpuf_profile_form_tab_content', array( $this, 'add_tab_content_profile_form' ) );

        add_action( 'init', array( $this, 'get_lists' ) );
        add_action( 'wpuf_after_register', array( $this, 'subscribe_user' ), 10, 3 );
    }

    /**
     * Get the API key
     *
     * @return string
     */
    private function get_api_key() {

        $api_key = get_option( 'wpuf_campaign_monitor_api_key' );

        return $api_key;
    }

    /**
     * Add Campaign Monitor Submenu in WPUF
     */
    public function add_campaign_monitor_menu() {
        add_submenu_page( 'wp-user-frontend', __( 'Campaign Monitor', 'wpuf-pro' ), __( 'Campaign Monitor', 'wpuf-pro' ), 'manage_options', 'wpuf_campaign_monitor', array($this, 'campaign_monitor_page') );
    }

    /**
     * Submenu Call Back Page
     */
    public function campaign_monitor_page() {
        require_once dirname( __FILE__ ) . '/templates/campaign-monitor-template.php';
    }

    /**
     * Add Campaign Monitor tab in Each form
     */
    public function add_tab_campaign_monitor_form() {
        ?>
        <a href="#wpuf-metabox-campaign_monitor" class="nav-tab" id="wpuf_campaign_monitor-tab"><?php _e( 'Campaign Monitor', 'wpuf-pro' ); ?></a>
        <?php
    }

    /**
     * Display settings option in tab content
     */
    public function add_tab_content_profile_form() {
        ?>
        <div id="wpuf-metabox-campaign_monitor" class="group">
            <?php require_once dirname( __FILE__ ) . '/templates/campaign-monitor-settings-tab.php'; ?>
        </div>
        <?php
    }

    /**
     * Require the campaign monitor class if not exists
     *
     * @return void
     */
    public function require_campaign_monitor() {
        if ( ! class_exists( 'CS_REST_General' ) ) {
            require_once dirname( __FILE__ ) . '/cm-php-sdk/csrest_general.php';
        }
    }

    /**
     * Fetch the udpated list from campaign-monitor and save it
     *
     * @return void
     */
    public function get_lists() {

        $api_key = $this->get_api_key();

        $this->require_campaign_monitor();
        $auth = array( 'api_key' => $api_key );
        $client_lists = $list_object = $lists = array();

        $cm_general = new CS_REST_General( $auth );
        $result = $cm_general->get_clients();

        if ( $result->http_status_code === 200 ) {
            foreach ( $result->response as $client ) {
                if ( !class_exists('CS_REST_Clients') ) {
                    require_once dirname( __FILE__ ) . '/cm-php-sdk/csrest_clients.php';
                }
                $client_class = new CS_REST_Clients( $client->ClientID, $auth );
                $client_lists[] = $client_class->get_lists();
            }
        }

        foreach ( $client_lists as $list ) {
            foreach ($list->response as $list_obj) {
                $list_object[] = $list_obj;
            }
        }

        foreach ($list_object as $list) {
            $lists[] = array(
                'id'     => $list->ListID,
                'name'   => $list->Name,
            );
        }

        update_option( 'wpuf_camp_monitor_lists', $lists );
    }

    /**
     * Subscribe a user when a form is submitted
     *
     * @param  int $form_id
     * @param  int $page_id
     * @param  array $form_settings
     *
     * @return void
     */
    public function subscribe_user( $user_id, $form_id, $form_settings ) {

        if ( ! isset( $form_settings['enable_campaign_monitor'] ) || $form_settings['enable_campaign_monitor'] == 'no' ) {
            return;
        }

        $user          = get_user_by( 'id', $user_id );
        $list_selected = isset( $form_settings['campaign_monitor_list'] ) ? $form_settings['campaign_monitor_list'] : '';

        $this->require_campaign_monitor();
        $auth = array( 'api_key' => $this->get_api_key() );

        if ( !class_exists('CS_REST_Subscribers') ) {
            require_once dirname( __FILE__ ) . '/cm-php-sdk/csrest_subscribers.php';
        }

        $wrap = new CS_REST_Subscribers( $list_selected, $auth );

        $result = $wrap->add(array(
            'EmailAddress' => $user->user_email,
            'Name' => $user->display_name,
            'CustomFields' => array(),
            'Resubscribe' => true
        ));
    }
}

new WPUF_Campaign_Monitor();
