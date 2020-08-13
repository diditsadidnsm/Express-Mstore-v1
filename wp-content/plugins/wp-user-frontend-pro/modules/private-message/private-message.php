<?php
/*
Plugin Name: Private Message
Plugin URI: http://wedevs.com/plugin/wp-user-frontend-pro/
Thumbnail Name: message.gif
Description: User to user message from Frontend
Version: 1.0
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

define( 'WPUF_PM_DIR', plugins_url('/', __FILE__) );

/**
 * WPUF_Private_Message class
 *
 * @class WPUF_Private_Message The class that holds the entire WPUF_Private_Message plugin
 */
class WPUF_Private_Message {

    /**
     * Constructor for the WPUF_Private_Message class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     *
     * @uses add_filter()
     * @uses add_action()
     */
    public function __construct() {
        add_filter( 'wpuf_account_sections', array( $this, 'user_message_menu' ) );
        add_action( 'wpuf_account_content_message', array( $this, 'user_message_section' ), 10, 2 );

        add_action( 'wp_ajax_wpuf_pm_route_data_index', array( $this, 'route_index' ) );
        add_action( 'wp_ajax_wpuf_pm_message_search', array( $this, 'message_search' ) );
        add_action( 'wp_ajax_wpuf_pm_delete_message', array( $this, 'message_delete' ) );
        add_action( 'wp_ajax_wpuf_pm_fetch_users', array( $this, 'fetch_users' ) );

        add_action( 'wp_ajax_wpuf_pm_route_data_message', array( $this, 'personal_message' ) );
        add_action( 'wp_ajax_wpuf_pm_message_send', array( $this, 'message_send' ) );
        add_action( 'wp_ajax_wpuf_pm_delete_single_message', array( $this, 'single_message_delete' ) );
    }

    /**
     * Initializes the WPUF_Private_Message() class
     *
     * Checks for an existing WPUF_Private_Message() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new WPUF_Private_Message();
        }

        return $instance;
    }

    /**
     * Placeholder for activation function
     *
     * Nothing being called here yet.
     */
    public static function activate() {
        global $wpdb;

        $collate = '';

        if ( $wpdb->has_cap( 'collation' ) ) {
            if ( ! empty($wpdb->charset ) ) {
                $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
            }

            if ( ! empty($wpdb->collate ) ) {
                $collate .= " COLLATE $wpdb->collate";
            }
        }

        $table_schema = array(
            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}wpuf_message` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `from` int(11) NOT NULL,
                `to` int(11) NOT NULL,
                `message` longtext,
                `status` BIT NOT NULL DEFAULT 0,
                `from_del` BIT NOT NULL DEFAULT 0,
                `to_del` BIT NOT NULL DEFAULT 0,
                `created` datetime NOT NULL,
                PRIMARY KEY (`id`),
                key `from` (`from`),
                key `to` (`to`)
            ) $collate;",
        );

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        foreach ( $table_schema as $table ) {
            dbDelta( $table );
        }
    }

    /**
     * Check user Analytics info exist or not with post
     *
     * @param array  $sections
     *
     * @return array
     */
    function user_message_menu( $sections ) {
        $sections = array_merge( $sections, array( array( 'slug' => 'message', 'label' => 'Message' ) ) );
        return $sections;
    }

    /**
     * Check user Analytics info exist or not with post
     *
     * @param array  $sections
     * @param string  $current_section
     *
     * @return boolean
     */
    function user_message_section( $sections, $current_section ) {
        $this->enqueue_scripts();

        add_action( 'wp_footer',  array( $this, 'render_user_list' ) );

        require_once dirname( __FILE__ ) . '/templates/message.php';
    }

    private function enqueue_scripts() {
        // @todo: NEED OPTIMIZATION
        $prefix = '';
        wp_register_script( 'wpuf-vue', WPUF_ASSET_URI . '/vendor/vue/vue' . $prefix . '.js', array(), WPUF_VERSION, true );
        wp_register_script( 'wpuf-vuex', WPUF_ASSET_URI . '/vendor/vuex/vuex' . $prefix . '.js', array( 'wpuf-vue' ), WPUF_VERSION, true );
        wp_register_script( 'wpuf-vue-router', WPUF_ASSET_URI . '/vendor/vue-router/vue-router' . $prefix . '.js', array( 'wpuf-vue' ), WPUF_VERSION, true );

        wp_enqueue_style( 'wpuf-private-message', WPUF_PM_DIR . '/assets/css/frontend.css', [], false );
        wp_enqueue_script( 'wpuf-private-message', WPUF_PM_DIR . '/assets/js/frontend.js', ['jquery', 'wpuf-vue', 'wpuf-vuex', 'wpuf-vue-router'], false, true );

        wp_localize_script( 'wpuf-private-message', 'wpufPM', [
            'ajaxurl' => admin_url( 'admin-ajax.php' )
        ] );
    }

    /**
     * Render user list in the modal
     *
     * @return void
     */
    public function render_user_list() {
        include dirname( __FILE__ ) . '/templates/modal.php';
    }

    public function route_index() {
        $data['messages'] = $this->get_messages();

        wp_send_json_success( $data );
    }

    public function message_search() {
        $args = [
            's' => ! empty( $_GET['content'] ) ? $_GET['content'] : ''
        ];

        sleep(0.1);
        $data = [
            'messages' => $this->get_messages( $args )
        ];

        wp_send_json_success( $data );
    }

    public function fetch_users() {
        $data = [
            'list' => $this->users_list( $_POST['s'] )
        ];

        wp_send_json_success( $data );
    }

    public function users_list( $s='' ) {
        $users_query = new WP_User_Query( array(
            'search'         => '*'.esc_attr( $s ).'*',
            'search_columns' => array(
                'user_login',
                'user_nicename',
                'user_email',
                'user_url',
            ),
        ) );
        $users = $users_query->get_results();
        ob_start();
        ?>
        <?php foreach ($users as $user) { ?>
            <li class="user">
                <a href="<?php echo '#/user/'.$user->data->ID; ?>"><?php echo get_avatar($user->data->ID, 80). '<br>' .$user->data->user_login; ?></a>
            </li>
        <?php }
        return ob_get_clean();
    }

    public function get_messages( $args = [] ) {
        global $wpdb;

        $sql = "SELECT * FROM " . $wpdb->prefix . "wpuf_message WHERE ";
        $sql .= !empty( $args['s'] ) ? "`message` LIKE '%" . $args['s'] . "%' AND " : '';
        $sql .= "((`from` = %d AND `from_del` = 0) OR (`to` = %d AND `to_del` = 0)) ORDER BY `created` DESC";

        $sql = $wpdb->prepare( $sql, get_current_user_id(), get_current_user_id() );

        $results = $wpdb->get_results( $sql );
        $users = array();
        foreach ( $results as $value ) {
            $user_id = get_current_user_id() == $value->from ? $value->to : $value->from;
            if ( !in_array( $user_id, $users ) ) {
                $users[] = $user_id;
            }
        }

        $users = count( $users ) > 10 ? array_slice( $users, 0, 10)  : $users;
        $messages = array();
        foreach ( $users as $user_id) {
            $sql = $wpdb->prepare( "SELECT *
                FROM " . $wpdb->prefix . "wpuf_message
                WHERE ((`from` = %d AND `from_del` = 0) OR (`to` = %d AND `to_del` = 0)) AND (`from` = %d OR `to` = %d) ORDER BY created DESC LIMIT 1", get_current_user_id(), get_current_user_id(), $user_id, $user_id );

            $results = $wpdb->get_results( $sql );

            foreach ($results as $key => $value) {
                $status = 'single';
                if ( get_current_user_id() == $value->from ) {
                    $user_id = $value->to;
                } else {
                    $user_id = $value->from;
                    if ( 0 == $value->status ) {
                        $status = 'single unread';
                    }
                }

                $user_info = get_userdata( $user_id );
                $messages[] = array(
                    'user_id' => $user_id,
                    'user_name' => $user_info->user_login,
                    'message' => $value->message,
                    'status' => $status,
                    'time' => date("M d,g:i a", strtotime( $value->created ) ),
                    'del_img' => WPUF_ASSET_URI . '/images/del-pm.png',
                );
            }
        }

        return $messages;
    }

    public function message_delete(){
        global $wpdb;

        $sql = $wpdb->prepare( "SELECT `id`, `from` FROM " . $wpdb->prefix . "wpuf_message
                 WHERE ((`from` = %d AND `from_del` = 0) OR (`to` = %d AND `to_del` = 0)) AND (`from` = %d OR `to` = %d)", get_current_user_id(), get_current_user_id(), $_GET['id'], $_GET['id'] );

        $results = $wpdb->get_results( $sql );

        foreach ($results as $result) {

            $update_row = get_current_user_id() == $result->from ? 'from_del' : 'to_del';

            $sql = $wpdb->prepare( "UPDATE " . $wpdb->prefix . "wpuf_message SET `" . $update_row . "`= 1 WHERE `id` = %d", $result->id );

            $update = $wpdb->get_results( $sql );
        }

        $data['messages'] = $this->get_messages();

        wp_send_json_success( $data );
    }

    public function personal_message() {
        global $wpdb;
        $data = [];

        $sql = $wpdb->prepare( "UPDATE " . $wpdb->prefix . "wpuf_message SET `status`= 1 WHERE `to` = %d AND `from` = %d", get_current_user_id(), $_GET['userId'] );

        $results = $wpdb->get_results( $sql );

        $sql = $wpdb->prepare( "SELECT *
                FROM " . $wpdb->prefix . "wpuf_message
                 WHERE ((`from` = %d AND `from_del` = 0) OR (`to` = %d AND `to_del` = 0)) AND (`from` = %d OR `to` = %d)", get_current_user_id(), get_current_user_id(), $_GET['userId'], $_GET['userId'] );

        $results = $wpdb->get_results( $sql );

        $response = array();

        foreach ($results as $key => $value) {
            $chat_class = 'chat';
            if( get_current_user_id() == $value->from ) {
                $chat_class = 'chat darker';
            }

            $user_info = get_userdata( $value->from );
            $response[] = array(
                'message_id' => $value->id,
                'user_id' => $value->from,
                'user_name' => $user_info->user_login,
                'avatar' => esc_url( get_avatar_url( $user_id ) ),
                'message' => $value->message,
                'time' => date("M d,g:i a", strtotime( $value->created ) ), //full time: "F j, Y, g:i a"
                'chat_class' => $chat_class,
                'del_img' => WPUF_ASSET_URI . '/images/del-pm.png',
            );

        }

        $chat_with = get_userdata( $_GET['userId'] );
        $data['chat_with'] = $chat_with->user_login;
        $data['messages'] = $response;

        wp_send_json_success( $data );
    }

    public function message_send() {
        global $wpdb;

        $sql = $wpdb->prepare( "INSERT INTO " . $wpdb->prefix . "wpuf_message
        (`from`, `to`, `message`, `status`, `from_del`, `to_del`, `created`)
        VALUES ('%d', '%d', '%s', 0, 0, 0, '%s')", get_current_user_id(), $_GET['userId'], $_GET['message'], wpuf_date2mysql( date("M d,g:i a") ) );

        $results = $wpdb->get_results( $sql );
        $this->personal_message();
    }

    public function single_message_delete(){
        global $wpdb;

        $sql = $wpdb->prepare( "SELECT *
                FROM " . $wpdb->prefix . "wpuf_message
                 WHERE `id` = %d", $_GET['id'] );

        $result = $wpdb->get_row( $sql );
        $update_row = get_current_user_id() == $result->from ? 'from_del' : 'to_del';

        $sql = $wpdb->prepare( "UPDATE " . $wpdb->prefix . "wpuf_message SET `" . $update_row . "`= 1 WHERE `id` = %d", $_GET['id'] );

        $result = $wpdb->get_results( $sql );
        $this->personal_message();
    }

} // WPUF_Private_Message

$wpuf_ua = WPUF_Private_Message::init();
wpuf_register_activation_hook( __FILE__, array( 'WPUF_Private_Message', 'activate' ) );
