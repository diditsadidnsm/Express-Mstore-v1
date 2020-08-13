<?php

/**
 * Tax Class
 *
 * @since 2.8.1
 *
 */

class WPUF_Tax {

    function __construct() {

        if ( !class_exists( 'CountryState') ){
            return;
        }

        add_filter( 'wpuf_settings_sections', array( $this, 'wpuf_tax_settings_tab' ) );
        add_filter( 'wpuf_settings_fields', array( $this, 'wpuf_tax_settings_content' ) );

        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );
    }

    /**
     *
     * Adds tax settings tab
     *
     */
    public function wpuf_tax_settings_tab( $settings ) {

        $tax_settings = array(
            array(
                'id'    => 'wpuf_payment_tax',
                'title' => __( 'Tax', 'wpuf-pro' ),
                'icon' => 'dashicons-media-text'
            )
        );

        return array_merge( $settings, $tax_settings);
    }

    /**
     *
     * Adds tax settings tab contents
     *
     */
    public function wpuf_tax_settings_content( $settings_fields ) {

        $cs = new CountryState();

        $countries = $cs->countries();

        $tax_settings_fields = array(
            'wpuf_payment_tax' => array(
                array(
                    'name'    => 'tax_help',
                    'label'   => __( 'Need help?', 'wpuf-pro' ),
                    'desc'    => sprintf( __( 'Visit the <a href="%s" target="_blank">Tax setup documentation</a> for guidance on how to setup tax.', 'wpuf-pro' ), 'https://wedevs.com/docs/wp-user-frontend-pro/settings/tax/' ),
                    'callback'    => 'wpuf_descriptive_text',
                ),
                array(
                    'name'    => 'enable_tax',
                    'label'   => __( 'Enable Tax', 'wpuf-pro' ),
                    'desc'    => __( 'Enable tax on payments', 'wpuf-pro' ),
                    'type'    => 'checkbox',
                    'default' => 'on',
                ),
                array(
                    'name'    => 'wpuf_base_country_state',
                    'label'   => '<strong>' . __( 'Base Country and State', 'wpuf-pro' ) . '</strong>',
                    'desc'    => __( 'Select your base country and state', 'wpuf-pro' ),
                    'callback'=> 'wpuf_base_country_state',
                ),
                array(
                    'name'    => 'wpuf_tax_rates',
                    'label'   => '<strong>' . __( 'Tax Rates', 'wpuf-pro' ) . '</strong>',
                    'desc'    => __( 'Add tax rates for specific regions. Enter a percentage, such as 5 for 5%', 'wpuf-pro' ),
                    'callback'=> 'wpuf_tax_rates',
                ),
                array(
                    'name'    => 'fallback_tax_rate',
                    'label'   => '<strong>' . __( 'Fallback Tax Rate', 'wpuf-pro' ) . '</strong>',
                    'desc'    => __( 'Customers not in a specific rate will be charged this tax rate. Enter a percentage, such as 5 for 5%', 'wpuf-pro' ),
                    'type'    => 'number',
                    'default' => 0,
                ),
                array(
                    'name'    => 'prices_include_tax',
                    'label'   => __( 'Show prices with tax', 'wpuf-pro' ),
                    'desc'    => __( 'If frontend prices will include tax or not', 'wpuf-pro' ),
                    'type'    => 'radio',
                    'default' => 'yes',
                    'options' => array(
                        'yes' => __( 'Show prices with tax', 'wpuf-pro' ),
                        'no'  => __( 'Show prices without tax', 'wpuf-pro' ),
                    ),
                ),
            )
        );

        return array_merge( $settings_fields, $tax_settings_fields );
    }

    /**
     *
     * Enqueue scripts
     *
     */
    public function enqueue_scripts() {
        wp_enqueue_script( 'wpuf-tax-js', WPUF_PRO_ASSET_URI . '/js/wpuf-tax.js', array('jquery'), false, true );
        wp_enqueue_style( 'wpuf-tax-css', WPUF_PRO_ASSET_URI . '/css/wpuf-tax.css' );
    }

}