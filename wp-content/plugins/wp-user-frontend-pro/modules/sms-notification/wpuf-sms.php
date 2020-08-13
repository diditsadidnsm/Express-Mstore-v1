<?php
/**
 * Plugin Name: SMS Notification
 * Description: SMS notification for post
 * Plugin URI: https://wedevs.com/products/plugins/wp-user-frontend-pro/sms-notification/
 * Thumbnail Name: wpuf-sms.png
 * Author: weDevs
 * Author URI: http://wedevs.com/
 * Version: 0.1
 * License: GPL2
 * Text Domain: wpuf-sms-notification
 * Domain Path: languages
 *
 * Copyright (c) 2017 weDevs (email: info@wedevs.com). All rights reserved.
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
if ( ! defined( 'ABSPATH' ) ) exit;

require_once dirname (__FILE__) . '/class/gateways.php';

class WPUF_Admin_sms {
	function __construct() {

		add_action( 'wpuf_form_setting', array( $this, 'settins_form' ), 10, 2 );
		add_action( 'wpuf_profile_setting', array( $this, 'settings_registration' ), 10, 2 );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_script' ) );
		add_filter( 'wpuf_settings_sections', array( $this, 'sms_settings_section' ), 10, 1 );
		add_filter( 'wpuf_settings_fields', array( $this, 'wpuf_sms_settings_fields' ), 10, 1 );
		add_action( 'wpuf_add_post_after_insert', array( $this, 'send_sms_after_post' ), 10, 4);
		add_action( 'wpuf_after_register', array( $this, 'send_sms_after_registration'), 10, 3 );
	}

	function send_sms_after_registration( $user_id, $form_id, $form_settings ) {
		$this->send_sms( $form_id, $form_settings );
	}

	function send_sms_after_post( $post_id, $form_id, $form_settings, $form_vars ) {

		$this->send_sms( $form_id, $form_settings );
    }

    function send_sms( $form_id, $form_settings ) {

        $validate = $this->validation( $form_id, $form_settings );

        if ( ! $validate ) {
        	return;
        }

        $active_gateway = $form_settings['gateway'];

        do_action( 'wpuf_sms_via_' . $active_gateway, $form_settings );
    }

    function validation( $form_id, $form_settings ) {
    	$form_settings = get_post_meta( $form_id, 'wpuf_form_settings', true );
        if ( !isset( $form_settings['sms_enable'] ) || $form_settings['sms_enable'] != 'yes' ) {
        	return false;
        }

        $mob_number = isset( $form_settings['mob_number'] ) ? $form_settings['mob_number'] : '';
        if ( empty( $mob_number ) ) {
        	return false;
        }

        return true;
    }

	function wpuf_sms_settings_fields( $settings_fields ) {

		$settings_fields['wpuf_sms'] = apply_filters( 'wpuf_sms_field', array(

	            array(
	                'name' => 'clickatell_name',
	                'label' => __( 'Clickatell name', 'wpuf-pro' ),
	                'desc' => __( 'Clickatell name', 'wpuf-pro' ),
	                'type' => 'text',
	            ),

	            array(
	                'name' => 'clickatell_password',
	                'label' => __( 'Clickatell Password', 'wpuf-pro' ),
	                'desc' => __( 'Clickatell Password', 'wpuf-pro' ),
	                'type' => 'text',
	            ),

	            array(
	                'name' => 'clickatell_api',
	                'label' => __( 'Clickatell api', 'wpuf-pro' ),
	                'desc' => __( 'Clickatell api', 'wpuf-pro' ),
	                'type' => 'text',
	            ),
	            array(
	                'name' => 'smsglobal_name',
	                'label' => __( 'SMSGlobal Name', 'wpuf-pro' ),
	                'desc' => __( 'SMSGlobal Name', 'wpuf-pro' ),
	                'type' => 'text',
	            ),
	            array(
	                'name' => 'smsglobal_password',
	                'label' => __( 'SMSGlobal Passord', 'wpuf-pro' ),
	                'desc' => __( 'SMSGlobal Passord', 'wpuf-pro' ),
	                'type' => 'text',
	            ),
	            array(
	                'name' => 'nexmo_api',
	                'label' => __( 'Nexmo API', 'wpuf-pro' ),
	                'desc' => __( 'Nexmo API', 'wpuf-pro' ),
	                'type' => 'text',
	            ),
	            array(
	                'name' => 'nexmo_api_Secret',
	                'label' => __( 'Nexmo API Secret', 'wpuf-pro' ),
	                'desc' => __( 'Nexmo API Secret', 'wpuf-pro' ),
	                'type' => 'text',
	            ),

	            array(
	                'name' => 'twillo_number',
	                'label' => __( 'Twillo From Number', 'wpuf-pro' ),
	                'desc' => __( 'Twillo From Number', 'wpuf-pro' ),
	                'type' => 'text',
	            ),
	            array(
	                'name' => 'twillo_sid',
	                'label' => __( 'Twillo Account SID', 'wpuf-pro' ),
	                'desc' => __( 'Twillo Account SID', 'wpuf-pro' ),
	                'type' => 'text',
	            ),
	            array(
	                'name' => 'twillo_token',
	                'label' => __( 'Twillo Authro Token', 'wpuf-pro' ),
	                'desc' => __( 'Twillo Authro Token', 'wpuf-pro' ),
	                'type' => 'text',
	            )

	        )
		);

		return $settings_fields;
	}

	function sms_settings_section( $sections ) {
		$sections[] = array(
            'id' => 'wpuf_sms',
            'title' => __( 'SMS', 'wpuf-pro' ),
            'icon' => 'dashicons-format-status'
        );

        return $sections;
	}

	function enqueue_script() {
		wp_enqueue_script( 'wpuf_sms', plugins_url( 'assets/js/sms.js', __FILE__ ), array('jquery'), false, true  );
	}

	function settins_form( $form_settings, $post ) {
		$this->sms_form_generator( $form_settings, $post );
	}

	function settings_registration( $form_settings, $post ) {
		$this->sms_form_generator( $form_settings, $post );
	}

	function sms_form_generator( $form_settings, $post ) {

        $mob_number = isset( $form_settings['mob_number'] ) ? $form_settings['mob_number'] : '';
        $sms_enable = isset( $form_settings['sms_enable'] ) ? $form_settings['sms_enable'] : '';
        $getway_style = ( $sms_enable != 'yes' ) ? 'none' : '';
        $sms_gateway = isset( $form_settings['gateway'] ) ? $form_settings['gateway'] : false;
        $sender_name = isset( $form_settings['sms_sender_name'] ) ? $form_settings['sms_sender_name'] : '';
        $sms_body = isset( $form_settings['sms_body'] ) ? $form_settings['sms_body'] : '';
		?>
		 <tr>
            <th><?php _e( 'Mobile Message', 'wpuf-pro' ); ?></th>
                <td class="wpuf-mobile-message">
                    <label>
                        <input type="checkbox" class="wpuf-sms-enable" name="wpuf_settings[sms_enable]" <?php checked( 'yes', $sms_enable ); ?> value="yes">
                        <?php _e( 'Enable SMS', 'wpuf-pro' ) ?>
                    </label>
                    <div class="description"><?php _e( 'Send sms for per post', 'wpuf-pro' ); ?></div>
                </td>
            </tr>
            <tr class="wpuf-sms-wrap" style="display: <?php echo $getway_style; ?>;">
                <th><?php _e( 'Mobile Number', 'wpuf-pro' ); ?></th>
                <td>
                    <label>
                        <input type="text" name="wpuf_settings[mob_number]" placeholder="<?php _e( 'mobile number', 'wpuf-pro' ); ?>" value="<?php echo esc_attr( $mob_number ); ?>" />
                    </label>
                    <div class="description"><?php _e( 'SMS will be sent to this number', 'wpuf-pro' ); ?></div>
                </td>
            </tr>

            <tr class="wpuf-sms-wrap" style="display: <?php echo $getway_style; ?>;">
                <th><?php _e( 'Sender Name', 'wpuf-pro' ); ?></th>
                <td>
                    <label>
                        <input type="text" name="wpuf_settings[sms_sender_name]" placeholder="<?php _e( 'SMS sender name', 'wpuf-pro' ); ?>" value="<?php echo esc_attr( $sender_name ); ?>" />
                    </label>
                    <div class="description"><?php _e( 'SMS sender name', 'wpuf-pro' ); ?></div>
                </td>
            </tr>

            <tr class="wpuf-sms-wrap" style="display: <?php echo $getway_style; ?>;">
                <th><?php _e( 'SMS body', 'wpuf-pro' ); ?></th>
                <td>
                    <label>
                        <input type="text" name="wpuf_settings[sms_body]" placeholder="<?php _e( 'Text message', 'wpuf-pro' ); ?>" value="<?php echo esc_attr( $sms_body ); ?>" />
                    </label>
                    <div class="description"><?php _e( 'SMS body', 'wpuf-pro' ); ?></div>
                </td>
            </tr>

            <tr class="wpuf-sms-wrap" style="display: <?php echo $getway_style; ?>;">
                <th><?php _e( 'Gateway', 'wpuf-pro' ); ?></th>
                <td>
                    <select name="wpuf_settings[gateway]">
                        <?php
                        $gateways = $this->get_sms_gateway();

                        foreach ( $gateways as $key => $gateway ) {
                            printf('<option value="%s"%s>%s</option>', $key, selected( $key, $sms_gateway, false ), $gateway );
                        }
                        ?>
                    </select>
                </td>
            </tr>
        <?php
	}

	function get_sms_gateway( $gate_way = false ) {
        $sms_gate_way = array(
            'nexmo' => __( 'Nexmo', 'wpuf-pro' ),
            'twillo' => __( 'Twilio', 'wpuf-pro' ),
            'clickatell' => __( 'Clickatel', 'wpuf-pro' ),
            'smsglobal' => __( 'SMS global', 'wpuf-pro' )
        );

        if ( $gate_way ) {
            return $sms_gate_way[$gate_way];
        }

        return apply_filters( 'wpuf_sms_gateways', $sms_gate_way );

    }
}

new WPUF_Admin_sms();
