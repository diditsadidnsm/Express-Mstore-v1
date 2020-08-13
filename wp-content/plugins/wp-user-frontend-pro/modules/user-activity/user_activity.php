<?php
/**
  Plugin Name: User Activity
  Plugin URI: https://wedevs.com/products/plugins/wp-user-frontend-pro/user-activity/
  Thumbnail Name: wpuf-activity.png
  Description: Handle user activity in frontend
  Version: 1.0.0
  Author: weDevs
  Author URI: https://wedevs.com
  License: GPL2
 */

/**
 * User Activity class for WP User Frontend PRO
 *
 * @author weDevs <info@wedevs.com>
 */
class WPUF_User_Activity {

    public function __construct() {

        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'wpuf_ud_profile_sections', array( $this, 'user_directory_profile_sections' ) );
        add_action( 'post_updated', array( $this, 'track_activity_update_post' ), 100, 3 );
        add_action( 'trash_post', array( $this, 'track_activity_trash_post' ), 100, 1 );
        add_action( 'delete_post', array( $this, 'track_activity_delete_post' ), 100, 1 );
        add_action( 'wp_insert_comment', array( $this, 'track_activity_comment_post' ), 100, 2 );
        add_action( 'admin_notices', array( $this, 'wpuf_ud_module_notice' ) );
        add_action( 'wpuf_add_post_after_insert', array( $this, 'monitor_form_posts' ), 100, 4 );

    }

    /**
     * Show admin notice when User Directory Module is not active
     *
     * @return void
     */

    function wpuf_ud_module_notice() {
        if ( ! class_exists('WPUF_User_Listing' ) ) {
        ?>
            <div class="notice notice-success is-dismissible">
                <p><?php _e( 'Please Activate User Directory module to use User Activity module.', 'wpuf-pro' ); ?></p>
            </div>
        <?php
        }
    }

    /**
     * Get curret post author
     *
     * @return mixed
     */
    public function get_profile_url() {
        $user     = wp_get_current_user();
        return get_author_posts_url( $user->ID );
    }

    /**
     * Backend post handler
     *
     * @return void
     */
    public function track_activity_update_post( $post_id, $post_after, $post_before  ) {
        $post = get_post( $post_id );

        if ( wp_is_post_autosave( $post_id ) ) {
            return;
        }

        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }

        if ( $post->post_type == 'nav_menu_item' ) {
            return;
        }

        if ( empty( $post->post_title ) || $post->post_title == 'Auto Draft' ) {
            return;
        }

        if ( $post_before->post_status == 'draft' && $post_after->post_status == 'publish' ) {
            $message = sprintf( __( 'published a post', 'wpuf-pro' ) );
            $this->log_activity( $post_id, $post->post_type, $message );
        } else {
            $message = sprintf( __( 'updated a post', 'wpuf-pro' ) );
            $this->log_activity( $post_id, $post->post_type, $message );
        }

        remove_action( 'post_updated', array( $this, 'track_activity_update_post' ), 100, 3 );
        return;
    }

    /**
     * Trashed post handler
     *
     * @return void
     */
    public function track_activity_trash_post( $post_id ) {
        $post = get_post( $post_id );

        if ( wp_is_post_autosave( $post_id ) ) {
            return;
        }

        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }

        if ( $post->post_type == 'nav_menu_item' ) {
            return;
        }

        if ( $post->post_title == 'Auto Draft' ) {
            return;
        }

        $message = sprintf( __( 'trashed a post', 'wpuf-pro' ) );
        $this->log_activity( $post_id, $post->post_type, $message );
    }

    /**
     * Deleted post handler
     *
     * @return void
     */
    public function track_activity_delete_post( $post_id ) {
        $post = get_post( $post_id );

        if ( wp_is_post_autosave( $post_id ) ) {
            return;
        }

        if ( wp_is_post_revision( $post_id ) ) {
            return;
        }

        if ( $post->post_type == 'nav_menu_item' ) {
            return;
        }

        if ( $post->post_title == 'Auto Draft' ) {
            return;
        }

        $message = sprintf( __( 'deleted a post', 'wpuf-pro' ) );

        $this->log_activity( $post_id, $post->post_type, $message );

        if ( did_action( 'delete_post' ) > 0 ) {
            remove_action( 'delete_post', array( $this, 'track_activity_delete_post' ), 100, 1 );
        }
    }

    /**
     * New Comment handler
     *
     * @return void
     */
    public function track_activity_comment_post( $comment_id, $comment_object ) {
        global $post;

        $user     = wp_get_current_user();
        $user_url = $this->get_profile_url();
        $post_id  = $comment_object->comment_post_ID;

        $message = sprintf( __( 'commented on a post', 'wpuf-pro' ) );

        $this->log_activity( $post_id, 'comment', $message );
    }

    /**
     * Form Posts handler
     *
     * @return void
     */
    public function monitor_form_posts( $post_id, $form_id, $form_settings, $form_vars ) {

        $post = get_post( $post_id );

        $user  = wp_get_current_user();
        $post_author = $user->display_name;

        if ( defined('DOING_AJAX') && DOING_AJAX && $_POST['action'] === 'wpuf_submit_post' ) {
            check_ajax_referer( 'wpuf_form_add' );

            $message = sprintf( __( 'published a post', 'wpuf-pro' ) );
            $this->log_activity(  $post_id, $post->post_type, $message );
            return;
        }
    }

    /**
     * Logs activity messages in database
     *
     * @return void
     */
    public function log_activity( $activity_id, $activity_type, $message ) {
        global $wpdb;

        $user    = wp_get_current_user();
        $time    = current_time( 'mysql' );

        $tablename = $wpdb->prefix . 'wpuf_activity';

        $data = array(
            'user_id'       => $user->ID,
            'activity_type' => $activity_type,
            'activity_id'   => $activity_id,
            'user_name'     => $user->display_name,
            'message'       => $message,
            'activity_time' => $time,
            'ip'            => preg_replace( '/[^0-9a-fA-F:., ]/', '', $_SERVER['REMOTE_ADDR'] )
        );

        $wpdb->insert( $tablename, $data);

    }

    /**
     * Enqueue Scripts and Styles
     *
     * @return void
     */
    public function enqueue_scripts() {
        wp_enqueue_style( 'wpuf-user-activity', plugins_url( 'css/user-activity.css', __FILE__ ) );
        wp_enqueue_script( 'wpuf-user-activity', plugins_url( 'js/user-activity.js', __FILE__ ) );
    }

    /**
     * Show Profile Tabs
     *
     * @return void
     */
    function user_directory_profile_sections() {
        global $wpdb;

        $post_per_page = 10; $total = ''; $current_about = ''; $current_activity = '';
        $page = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;

        if ( isset( $_GET['cpage'] ) ) {
            $current_activity = 'current';
        } else {
            $current_about = 'current';
        }

        $user_id = isset( $_GET['user_id'] ) ? $_GET['user_id'] : 1;
        $offset = ($page - 1) * $post_per_page;

        $table   = $wpdb->prefix . 'wpuf_activity';
        $total_count   = $wpdb->get_results( "SELECT COUNT(*) as act_count FROM $table WHERE user_id={$user_id}" );
        foreach( $total_count as $t_count ) {
            $total = $t_count->act_count;
        }
        $sql     = "SELECT *  FROM $table WHERE user_id={$user_id} LIMIT {$offset}, {$post_per_page}";
        $results = $wpdb->get_results( $sql );
        $timeline_date = '';
        ?>

        <div class="wpuf-profile-container">
            <ul class="wpuf-profile-tabs">
                <li class="tab-link <?php echo $current_about ?>" data-tab="about-tab">About</li>
                <li class="tab-link <?php echo $current_activity ?>" data-tab="activity-tab">Activity</li>
            </ul>

            <div id="about-tab" class="wpuf-profile-tab-content <?php echo $current_about ?>">
                <?php do_action( 'wpuf_ud_profile_about' );  ?>
            </div>
            <div id="activity-tab" class="wpuf-profile-tab-content <?php echo $current_activity ?>">
                <div class="wpuf-activity-table" >
                <?php foreach ( $results as $result ): ?>
                    <div class="wpuf-activity-row">
                        <?php
                            if ( $timeline_date != date('d-M', strtotime( $result->activity_time ) ) ) {
                                $timeline_date =  date('d-M', strtotime( $result->activity_time ) );
                                echo '<div class="wpuf-activity-date">';
                                echo $timeline_date;
                                echo '</div>';
                            }

                            $message = $this->get_activity_message( $result->user_id, $result->user_name, $result->activity_id, $result->activity_type, $result->message );

                            if ( $timeline_date == date('d-M', strtotime( $result->activity_time ) ) ) { ?>
                                <div class="wpuf-activity-column"><?php echo date('h:i A', strtotime( $result->activity_time ) ); ?></div>
                                <div class="wpuf-activity-column"><?php echo $message; ?></div>
                        <?php } ?>
                    </div>
                <?php endforeach ?>
                    <?php
                    echo '<div class="pagination">';
                    echo paginate_links( array(
                        'base' => add_query_arg( 'cpage', '%#%' ),
                        'format' => '',
                        'prev_text' => __('&laquo;'),
                        'next_text' => __('&raquo;'),
                        'total' => ceil( $total / $post_per_page ),
                        'current' => $page,
                        'type' => 'list'
                    ));
                    echo '</div>';
                    ?>
                </div>
            </div>
        </div>

        <?php

    }

    /**
     * Create database table
     *
     * @return void
     */
    public static function activation() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'wpuf_activity';
        $wpdb_collate = $wpdb->collate;
        $sql =
        "CREATE TABLE {$table_name} (
        id int(11) unsigned NOT NULL auto_increment,
        user_id varchar(11) NOT NULL,
        activity_id varchar(11) NOT NULL,
        activity_type varchar(20) NOT NULL,
        user_name varchar(191) NOT NULL,
        message varchar(255) NOT NULL,
        activity_time datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
        ip varchar(39) NOT NULL,
        PRIMARY KEY  (id)
        )
        COLLATE {$wpdb_collate}";

        if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name) {
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta( $sql );
        }
    }

    /**
     * Return Activity message
     *
     * @return string
     */
    function get_activity_message( $user_id, $user_name, $activity_id, $activity_type, $message ) {

        $message = sprintf( __( '<a href="%s">%s</a> %s <a href="%s">%s</a>.', 'wpuf-pro' ), get_author_posts_url( $user_id ), $user_name , $message, get_permalink( $activity_id ), get_the_title( $activity_id ) );

        return $message;
    }

}

/**
 * Return the instance
 *
 * @return \WPUF_User_Activity
 */
function wpuf_user_activity() {
    if ( !class_exists( 'WP_User_Frontend' ) ) {
        return;
    }

    new WPUF_User_Activity();

    wpuf_register_activation_hook( __FILE__, array( 'WPUF_User_Activity', 'activation' ) );
}

wpuf_user_activity();
