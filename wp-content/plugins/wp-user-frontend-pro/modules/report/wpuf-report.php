<?php
/*
Plugin Name: Reports
Plugin URI: https://wedevs.com/wp-user-frontend-pro/
Thumbnail Name: reports.png
Description: Show various reports in WP User Frontend menu
Version: 1.0
Author: weDevs
Author URI: http://wedevs.com/
License: GPL2
*/


// don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * WPUF_Report class
 *
 * @class WPUF_Report The class that holds the entire WPUF_Report plugin
 */
class WPUF_Report {

    /**
     * Constructor for the WPUF_Report class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     *
     * @uses add_action()
     */
    public function __construct() {
        add_action( 'wpuf_admin_menu', array( $this, 'add_report_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
    }

    /**
     * Initializes the WPUF_Report() class
     *
     * Checks for an existing WPUF_Report() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new WPUF_Report();
        }

        return $instance;
    }

    /**
     * Add Report Submenu in WPUF
     */
    public function add_report_menu() {
        add_submenu_page( 'wp-user-frontend', __( 'Reports', 'wpuf-pro' ), __( 'Reports', 'wpuf-pro' ), 'manage_options', 'wpuf_reports', array($this, 'report_page') );
    }

    /**
     * Submenu Call Back Page
     */
    public function report_page() {
        require_once dirname( __FILE__ ) . '/templates/report-template.php';
    }

    public function register_scripts() {
        wp_enqueue_style( 'wpuf-report-cs', plugins_url( '/css/wpuf-report.css', __FILE__ ) );
        wp_enqueue_script( 'wpuf-report-js', plugins_url( '/js/wpuf-report.js', __FILE__ ), array('jquery'), false, false );
        wp_enqueue_script( 'wpuf-chart-js', plugins_url( '/lib/chartjs-php/chart.js', __FILE__ ), array('jquery'), false, false );
        wp_enqueue_script( 'wpuf-chart-js-driver', plugins_url( '/lib/chartjs-php/driver.js', __FILE__ ), array('jquery'), false, false );

        wp_enqueue_style( 'wpuf-datepicker', plugins_url( '/css/datepicker.css', __FILE__ ) );
        wp_enqueue_script( 'jquery-ui-datepicker' );
    }

    public static function require_lib() {
        require_once dirname( __FILE__ ) . '/lib/chartjs-php/chartjs.php';
    }

}

$baseplugin = WPUF_Report::init();
