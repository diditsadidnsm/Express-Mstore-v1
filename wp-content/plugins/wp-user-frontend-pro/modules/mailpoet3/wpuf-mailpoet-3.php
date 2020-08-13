<?php
/*
Plugin Name: Mailpoet 3
Plugin URI: https://wedevs.com/wp-user-frontend-pro/
Thumbnail Name: mailpoet3.png
Description: Add subscribers to mailpoet mailing list when they registers via WP User Frontend Pro
Version: 1.0
Author: weDevs
Author URI: http://wedevs.com/
License: GPL2
*/


// don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * WPUF_Mailpoet class
 *
 * @class WPUF_Mailpoet The class that holds the entire WPUF_Mailpoet plugin
 */
class WPUF_Mailpoet_3 {

    /**
     * Constructor for the WPUF_Mailpoet class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     *
     * @uses add_action()
     */
    public function __construct() {

        add_action( 'wpuf_profile_form_tab', array( $this, 'add_tab_profile_form') );
        add_action( 'wpuf_profile_form_tab_content', array( $this, 'add_tab_content_profile_form') );

        add_action( 'wpuf_after_register', array( $this, 'subscribe_user_after_registration'), 10, 3 );

    }

    /**
     * Initializes the WPUF_Mailpoet() class
     *
     * Checks for an existing WPUF_Mailpoet() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new WPUF_Mailpoet_3();
        }

        return $instance;
    }


    /**
     * Add Mailpoet tab in Each form
     *
     * @return void
     */
    public function add_tab_profile_form() {
        ?>
            <a href="#wpuf-metabox-mailpoet-3" class="nav-tab" id="wpuf_mailpoet-3-tab"><?php _e( 'Mailpoet 3', 'wpuf-pro' ); ?></a>
        <?php
    }

    /**
     * Display settings option in tab content
     *
     * @return void
     */
    public function add_tab_content_profile_form() {
        ?>
        <div id="wpuf-metabox-mailpoet-3" class="group">
            <?php require_once dirname( __FILE__ ) . '/templates/mailpoet-3-settigs-tab.php'; ?>
        </div>
        <?php
    }

    /**
     * Send Subscribe request in Mailpoet
     *
     * @param  integer $user_id
     * @param  integer $form_id
     * @param  array $form_settings
     */
    public function subscribe_user_after_registration( $user_id, $form_id, $form_settings ) {
        if ( !is_plugin_active( 'mailpoet/mailpoet.php' ) ) {
            return;
        }

        if ( $form_settings['enable_mailpoet_3'] == 'no' ) {
            return;
        }

        $user = get_user_by( 'id', $user_id );

        $list_ids[] = $form_settings['mailpoet_3_list'];

        $subscriber_data = array(
            'email' => $user->user_email,
            'segments' => $list_ids,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'status' => 'subscribed'
        );

        try {
            $subscriber = \MailPoet\API\API::MP('v1')->subscribeToList( $user->user_email, $form_settings['mailpoet_3_list'] ); 
        } catch ( Exception $exception ) {
            return $exception->getMessage();
        }
    }

}

$baseplugin = WPUF_Mailpoet_3::init();