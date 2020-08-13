<?php
/*
  Plugin Name: Paid Membership Pro Integration
  Plugin URI: http://wedevs.com/
  Thumbnail Name: wpuf-pmpro.png
  Description: Membership Integration of WP User Frontend PRO with Paid Membership Pro
  Version: 0.2
  Author: Tareq Hasan
  Author URI: http://wedevs.com/
  License: GPL2
 */
/**
 * Copyright (c) 2013 Tareq Hasan (email: tareq@wedevs.com). All rights reserved.
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
if ( !defined( 'ABSPATH' ) )
    exit;

/**
 * WPUF_Pm_Pro class
 *
 * @class WPUF_Pm_Pro The class that holds the entire WPUF_Pm_Pro plugin
 */
class WPUF_Pm_Pro {

    const option_id = 'wpufpmpro';

    /**
     * Constructor for the WPUF_Pm_Pro class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     *
     * @uses add_action()
     */
    public function __construct() {
        add_action( 'pmpro_membership_level_after_other_settings', array( $this, 'post_count_field_insert' ) );
        add_action( 'pmpro_save_membership_level', array( $this, 'post_count_field_save' ) );
        add_action( 'pmpro_after_change_membership_level', array( $this, 'set_user_membership' ), 10, 2 );
        // add_action( 'personal_options_update', array($this, 'profile_update_expiry'), 99 );
        // add_action( 'edit_user_profile_update', array($this, 'profile_update_expiry'), 99 );
    }

    /**
     * Initializes the WPUF_Pm_Pro() class
     *
     * Checks for an existing WPUF_Pm_Pro() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;
        if ( !$instance ) {
            $instance = new WPUF_Pm_Pro();
        }
        return $instance;
    }

    /**
     * Helper function to retrieve the post count setting
     *
     * @param int $level_id
     * @return array|int
     */
    public function get_option( $level_id = false ) {
        $option = get_option( self::option_id, array() );
        if ( $level_id ) {
            return isset( $option[$level_id] ) ? $option[$level_id] : false;
        }
        return $option;
    }

    /**
     * Updates a post count level in options table
     *
     * @param int $level_id
     * @param int $post_count
     */
    function update_level_count( $level_id, $post_count ) {
        $option            = $this->get_option();
        $option[$level_id] = $post_count;
        update_option( self::option_id, $option );
    }

    /**
     * Updates the post count when membership level form updates
     *
     * @uses `pmpro_save_membership_level` action hook
     * @param int $level_id
     */
    function post_count_field_save( $level_id ) {
        $post_count = isset( $_POST['wpuf_post_count'] ) ? $_POST['wpuf_post_count'] : 0;
        $this->update_level_count( $level_id, $post_count );
    }

    /**
     * Shows the post count text field in the plugin membership level form
     *
     * @return void
     */
    function post_count_field_insert() {
        $level_id   = ( intval( $_GET['edit'] ) < 0 ) ? 0 : intval( $_GET['edit'] );
        $post_count = $level_id ? $this->get_option( $level_id ) : array();
        $post_types = WPUF_Subscription::init()->get_all_post_type();
        ?>
        <h3 class="topborder"><?php _e( 'WP User Frontend PRO', 'wpuf-pro' ); ?></h3>
        <table class="form-table">
            <tbody>
        <?php
        foreach ( $post_types as $key => $value ) {
            $count = isset( $post_count[$key] ) ? intval( $post_count[$key] ) : 0;
            ?>
                    <tr>
                        <th scope="row" valign="top"><label><?php printf( __( '%s Count', 'wpuf-pro' ), ucfirst( $value ) ); ?>:</label></th>
                        <td>
                            <input id="wpuf_post_count" name="wpuf_post_count[<?php echo $key; ?>]" type="text" size="5" value="<?php echo esc_attr( $count ) ?>" />
                            <span class="description">
            <?php _e( 'Enter -1 for unlimited.', 'wpuf-pro' ); ?>
                            </span>
                        </td>
                    </tr>
        <?php } ?>
            </tbody>
        </table>
        <?php
    }

    /**
     * Set user membership when a membership level changes in
     * Paid Membership Pro plugin
     *
     * @param int $level_id
     * @param int $user_id
     */
    function set_user_membership( $level_id, $user_id ) {
        $user_level = pmpro_getMembershipLevelForUser( $user_id );
        if ( $user_level ) {
            // Update expiry
            $date_string = sprintf( '%s %s', $user_level->expiration_number, $user_level->expiration_period );
            $expire_date = date( 'Y-m-d G:i:s', strtotime( $date_string ) );
            // Update post count
            $membership  = array(
                'pack_id'   => 0,
                'posts'     => $this->get_option( $level_id ),
                'status'    => null,
                'expire'    => $expire_date,
                'recurring' => 'no'
            );
            update_user_meta( $user_id, '_wpuf_subscription_pack', $membership );
        } else {
            update_user_meta( $user_id, '_wpuf_subscription_pack', false );
        }
    }

    /**
     * Update expiry time when user profile update from the admin panel
     *
     * @param int $user_id
     */
    function profile_update_expiry( $user_id ) {
        if ( !empty( $_POST['expires'] ) ) {
            $expiration_date = intval( $_REQUEST['expires_year'] ) . '-' . intval( $_REQUEST['expires_month'] ) . '-' . intval( $_REQUEST['expires_day'] );
            $expiration_date = date( 'Y-m-d G:i:s', strtotime( $expiration_date ) );
            update_user_meta( $user_id, 'wpuf_sub_validity', $expiration_date );
        }
    }

}

// WPUF_Pm_Pro
$wpuf_pmpro = WPUF_Pm_Pro::init();
