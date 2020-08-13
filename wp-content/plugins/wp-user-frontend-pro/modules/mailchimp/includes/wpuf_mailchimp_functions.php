<?php

require_once dirname( __FILE__ ) . '/../classes/mailchimp.php';

/**
 * Check and update API key and save lists in options table
 * @param  string  $api_key
 * @return boolean
 */
function save_mailchimp_api( $api_key ) {

    $MailChimp = new MailChimp( $api_key );
    $response = $MailChimp->call('lists');

    $lists = array();

    if( $response ) {
        foreach ( $response['lists'] as $value ) {
            $lists[] = array(
                'id' => $value['id'],
                'name' => $value['name'],
                'web_id' => $value['web_id']
            );
        }

        update_option( 'wpuf_mc_lists', $lists );
    }

    if( isset( $response['status'] ) && $response['status'] == 'error' ) {
        $resp = array(
            'message' => $response['error'],
            'status' => false
        );
    } else {


        $resp = array(
            'message' => __( 'Succesfully inserted', 'wpuf-pro' ),
            'status' => true
        );
    }

    return $resp;
}

/**
 * Refresh the lish of API
 */
function refresh_mailchimp_api_lists() {

    $MailChimp = new MailChimp( get_option( 'wpuf_mailchimp_api_key' ) );
    $response = $MailChimp->call('lists');

    $lists = array();

    if( $response ) {
        foreach ( $response['lists'] as $value ) {
            $lists[] = array(
                'id' => $value['id'],
                'name' => $value['name'],
                'web_id' => $value['web_id']
            );
        }

        update_option( 'wpuf_mc_lists', $lists );
    }
}
