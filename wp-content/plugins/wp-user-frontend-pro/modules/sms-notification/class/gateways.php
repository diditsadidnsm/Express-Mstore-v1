<?php

/**
 * SMS Gateway handler class
 *
 * @author wpuf
 */
class wpuf_SMS_Gateways {

    private static $_instance;

    /**
     * Gateway slug
     *
     * @param string $provider name of the gateway
     */
    function __construct() {
        add_action( 'wpuf_sms_via_smsglobal', array($this, 'smsGlobalAPI') );
        add_action( 'wpuf_sms_via_clickatell', array($this, 'clickatellAPI') );
        add_action( 'wpuf_sms_via_twillo', array($this, 'twilio_api') );
        add_action( 'wpuf_sms_via_nexmo', array($this, 'nexmo_api') );
    }

    public static function instance() {
        if ( !self::$_instance ) {
            self::$_instance = new wpuf_SMS_Gateways();
        }

        return self::$_instance;
    }



    /**
     * Sends SMS via SMSGlobal api
     *
     * @uses `wpuf_sms_via_smsglobal` filter to fire
     *
     * @param array $sms_data
     * @return boolean
     */
    function smsGlobalAPI( $sms_data ) {
        $response = array(
            'success' => false,

        );

        $username = wpuf_get_option( 'smsglobal_name', 'wpuf_sms' );
        $password = wpuf_get_option( 'smsglobal_password', 'wpuf_sms' );
        $from = wpuf_get_option( $sms_data['sms_sender_name'], 'wpuf_sms' );

        //bail out if no username or password given
        if ( empty( $username ) || empty( $password ) ) {
            return $response;
        }

        $content = 'action=sendsms' .
                '&user=' . rawurlencode( $username ) .
                '&password=' . rawurlencode( $password ) .
                '&to=' . rawurlencode( $sms_data['mob_number'] ) .
                '&from=' . rawurlencode( $from ) .
                '&text=' . rawurlencode( $sms_data['sms_body'] );

        $smsglobal_response = file_get_contents( 'http://www.smsglobal.com.au/http-api.php?' . $content );

        //Sample Response
        //OK: 0; Sent queued message ID: 04b4a8d4a5a02176 SMSGlobalMsgID:6613115713715266
        //ERROR: 8 - Invalid Mobile Number
        //ERROR 13 - Invalid Mobile Number

        $explode_response = explode( 'SMSGlobalMsgID:', $smsglobal_response );
/*
        if ( count( $explode_response ) == 2 ) {
            $response = array(
                'success' => true,
            );
        }

        return $response;*/
    }

    /**
     * Sends SMS via Clickatell api
     *
     * @uses `wpuf_sms_via_clickatell` filter to fire
     *
     * @param type $sms_data
     * @return boolean
     */
    function clickatellAPI( $sms_data ) {

        $username = wpuf_get_option( 'clickatell_name', 'wpuf_sms' );
        $password = wpuf_get_option( 'clickatell_password', 'wpuf_sms' );
        $api_key = wpuf_get_option( 'clickatell_api', 'wpuf_sms' );

        //bail out if nothing provided
        if ( empty( $username ) || empty( $password ) || empty( $api_key ) ) {
            return $response;
        }

        // auth call
        $baseurl = "http://api.clickatell.com";
        $url = sprintf( '%s/http/auth?user=%s&password=%s&api_id=%s', $baseurl, $username, $password, $api_key );

        // do auth call
        $ret = file( $url );

        // explode our response. return string is on first line of the data returned
        $sess = explode( ":", $ret[0] );
        if ( $sess[0] == "OK" ) {

            $sess_id = trim( $sess[1] ); // remove any whitespace
            $url = sprintf( '%s/http/sendmsg?session_id=%s&to=%s&text=%s', $baseurl, $sess_id, $sms_data['mob_number'], $sms_data['sms_body'] );

            // do sendmsg call
            $ret = file( $url );
            $send = explode( ":", $ret[0] );

            if ( $send[0] == "ID" ) {
                $response = array(
                    'success' => true,
                    //'code' => $sms_data['code'],
                    //'message' => wpuf_sms_get_option( 'sms_sent_msg' )
                );
            }
        }

        //return $response;
    }

    /**
     * Sends SMS via Nexmo api
     *
     * @uses `wpuf_sms_via_nexmo` filter to fire
     *
     * @param type $sms_data
     * @return boolean
     */
    function nexmo_api( $sms_data ) {

        $response = array(
            'success' => false,
            //'message' => wpuf_sms_get_option( 'sms_sent_error' )
        );

        $username = wpuf_get_option( 'nexmo_api', 'wpuf_sms' );
        $password = wpuf_get_option( 'nexmo_api_Secret', 'wpuf_sms' );
        $from = $sms_data['sms_sender_name'];

        $url = 'http://rest.nexmo.com/sms/json';
        $args = array(
            'body' => array(
                'username' => $username,
                'password' => $password,
                'from' => $from,
                'to' => $sms_data['mob_number'],
                'type' => 'text',
                'text' => $sms_data['sms_body']
            )
        );

        $response_obj = wp_remote_post( $url, $args );

        //WP_User_Frontend::log( 'sms_message', print_r( $response_obj, true ) );

        /*if ( !is_wp_error( $response_obj ) ) {
            $api_response = json_decode( wp_remote_retrieve_body( $response_obj ) );

            //success
            if ( $api_response->messages[0]->status == '0' ) {
                $response = array(
                    'success' => true,
                    'code' => $sms_data['code'],
                    'message' => wpuf_sms_get_option( 'sms_sent_msg' )
                );
            }
        }

        return $response;*/
    }

    /**
     * Sends SMS via Twillo api
     *
     * @uses `wpuf_sms_via_twillo` filter to fire
     *
     * @param type $sms_data
     *
     * @return string|object message SID or WP_Error
     */
    function twilio_api( $sms_data ) {
        $sid    = wpuf_get_option( 'twillo_sid', 'wpuf_sms' );
        $token  = wpuf_get_option( 'twillo_token', 'wpuf_sms' );
        $from   = wpuf_get_option( 'twillo_number', 'wpuf_sms' );
        $to     = $sms_data['mob_number'];

        // twilio requires + at the beginning
        if ( ! preg_match( '/^\+/', $to ) ) {
            $to = '+' . $to;
        }

        require_once dirname( __FILE__ ) . '/../lib/Twilio/autoload.php';

        $client = new Twilio\Rest\Client($sid, $token);

        try {
            $message = $client->messages->create(
                $to, // Text this number
                array(
                    'from' => $from, // From a valid Twilio number
                    'body' => $sms_data['sms_body']
                )
            );

            return $message->sid;

        } catch (Exception $e) {
            return new WP_Error( 'error_sending_sms_using_twilio', $e->getMessage() );
        }
    }

}

new wpuf_SMS_Gateways();