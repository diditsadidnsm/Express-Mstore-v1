<?php

/**
 * Invoice Frontend class
 *
 * @since 2.6
 *
 * @author Tareq Hasan
 * @package WP User Frontend
 */
class WPUF_Invoice_Frontend {

    /**
     * Class constructor
     */
    public function __construct() {
        add_action( 'init', array( $this, 'add_pro_sections' ), 10, 2 );

        add_action( 'wpuf_account_content_invoices', array( $this, 'invoices_section' ), 10, 2 );

        add_filter( 'wpuf_account_sections', array( $this, 'display_pro_nav' ), 10, 1 );
    }

    public static function init() {
        if ( !self::$_instance ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Display the billing address and invoices nav
     *
     * @param  array  $sections
     * @param  string $current_section
     *
     * @since  2.6
     *
     * @return void
     */

    public function display_pro_nav( $sections ) {

        if ( is_user_logged_in() ) {
            $inv_section = array(
                array( 'slug' => 'invoices', 'label' => __( 'Invoices', 'wpuf-pro' ) ),
            );
            if ( wpuf_get_option( 'show_invoices', 'wpuf_payment_invoices', 'on' ) == 'on' ) {
                $sections = array_merge( $sections , $inv_section);
            }

            return $sections;
        }
    }

    /**
     * Hooks the invoices and billing address section
     *
     * @param  array  $sections
     * @param  string $current_section
     *
     * @since  2.6
     *
     * @return void
     */
    public function add_pro_sections() {
        if ( is_user_logged_in() ) {
            if (isset( $_REQUEST['section'] ) && $_REQUEST['section'] == 'invoices') {
                $sections        = wpuf_get_account_sections();
                $current_section = array();
                if ( ! empty( $current_section ) ) {
                    do_action( "wpuf_account_content_{$current_section['slug']}", $sections, $current_section );
                }
            }

        }
    }

    /**
     * Display the invoices download form
     *
     * @param  array  $sections
     * @param  string $current_section
     *
     * @since  2.6
     *
     * @return void
     */

    public function invoices_section( $sections, $current_section ) {
        self::wpuf_pro_load_template(
            "invoices.php",
            array( 'sections' => $sections, 'current_section' => $current_section )
        );
    }


    public static function wpuf_pro_load_template( $file, $args = array() ) {
        if ( $args && is_array( $args ) ) {
            extract( $args );
        }

        $child_theme_dir = get_stylesheet_directory() . '/wpuf/';
        $parent_theme_dir = get_template_directory() . '/wpuf/';
        $wpuf_dir = WPUF_PRO_INCLUDES . '/templates/';

        if ( file_exists( $child_theme_dir . $file ) ) {

            include $child_theme_dir . $file;

        } else if ( file_exists( $parent_theme_dir . $file ) ) {

            include $parent_theme_dir . $file;

        } else {
            include $wpuf_dir . $file;
        }
    }
}

new WPUF_Invoice_Frontend();
