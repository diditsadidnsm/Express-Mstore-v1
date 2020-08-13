<?php

/**
 * An example test case.
 */
Class WPUF_User_Listing_Test extends WP_UnitTestCase {


    function test_admin_guest() {
        $field = array(
            'all_user_role' => array('administrator'),
            'current_user_role' => array('guest')
        );

        $this->assertTrue( WPUF_User_Listing::can_user_see( 'administrator', $field, 'guest') );
    }

    function test_contrib_contrib() {
        $field = array(
            'all_user_role' => array('subscriber'),
            'current_user_role' => array('guest')
        );

        $this->assertFalse( WPUF_User_Listing::can_user_see( 'contributor', $field, 'contributor') );
    }

    function test_sub_guest() {
        $field = array(
            'all_user_role' => array('subscriber'),
            'current_user_role' => array('editor')
        );

        $this->assertFalse( WPUF_User_Listing::can_user_see( 'subscriber', $field, 'guest') );
    }

    function test_sub_sub() {
        $field = array(
            'all_user_role' => array( 'subscriber'),
            'current_user_role' => array('subscriber')
        );

        $this->assertTrue( WPUF_User_Listing::can_user_see( 'subscriber', $field, 'subscriber') );
    }
}