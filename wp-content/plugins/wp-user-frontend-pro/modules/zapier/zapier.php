<?php
/**
 * Plugin Name: Zapier
 * Description: Subscribe a contact to Zapier when a form is submited
 * Plugin URI: https://wedevs.com/wp-user-frontend-pro/
 * Thumbnail Name: zapier.png
 * Author: weDevs
 * Version: 1.0
 * Author URI: https://wedevs.com
 */

/**
 * Zapier CLass
 */
class WPUF_Zapier {

    function __construct() {

        add_action( 'wpuf_profile_form_tab', array( $this, 'add_tab_zapier_form' ) );
        add_action( 'wpuf_profile_form_tab_content', array( $this, 'add_tab_content_profile_form' ) );

        add_action( 'wpuf_after_register', array( $this, 'subscribe_user' ), 10, 3 );
    }

    /**
     * Require the Zapier class if not exists
     *
     * @return void
     */
    public function require_zapier() {
        if ( ! class_exists( 'Zapier' ) ) {
            require_once dirname( __FILE__ ) . '/class-zapier.php';
        }
    }

    /**
     * Add Zapier tab in Each form
     */
    public function add_tab_zapier_form() {
        ?>
        <a href="#wpuf-metabox-zapier" class="nav-tab" id="wpuf_zapier-tab"><?php _e( 'Zapier', 'wpuf-pro' ); ?></a>
        <?php
    }

    /**
     * Display settings option in tab content
     */
    public function add_tab_content_profile_form() {
        ?>
        <div id="wpuf-metabox-zapier" class="group">
            <?php require_once dirname( __FILE__ ) . '/templates/zapier-settings-tab.php'; ?>
        </div>
        <?php
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

        if ( ! isset( $form_settings['enable_zapier'] ) || $form_settings['enable_zapier'] == 'no' ) {
            return;
        }

        if ( isset( $_POST['action'] ) && $_POST['action'] != 'wpuf_submit_register' ) {
            return;
        }

        $postdata = $_POST;

        if ( empty( $postdata ) ) {
            return;
        }

        unset( $postdata['_wpnonce'] );
        unset( $postdata['_wp_http_referer'] );
        unset( $postdata['form_id'] );
        unset( $postdata['page_id'] );
        unset( $postdata['action'] );

        $webhook_url = $form_settings['zapier_webhook'];

        $this->require_zapier();

        $zapier = new Zapier( $webhook_url );

        $response = $zapier->call( json_encode( $postdata ) );

        return $response; 
    }
}

new WPUF_Zapier();
