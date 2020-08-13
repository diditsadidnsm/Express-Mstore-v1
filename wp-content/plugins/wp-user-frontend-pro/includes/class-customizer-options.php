<?php
/**
 * WPUF_Pro_Customizer_Options class
 *
 * @since 2.8.2
 *
 * @package WPUF\Pro
 */

class WPUF_Pro_Customizer_Options{

    /**
     * Class constructor
     */
    public function __construct() {
        add_action( 'customize_register', array( $this, 'customizer_options' ) );
        add_action( 'wp_head', array( $this, 'save_customizer_options' ) );
    }

    public function save_customizer_options() {

        $button_label = get_theme_mod( 'wpuf_subs_button_label', false );
        $guest_label  = get_theme_mod( 'wpuf_subs_guest_button', false );
        $free_label   = get_theme_mod( 'wpuf_subs_free_button', false );
        $header_color = get_theme_mod( 'wpuf_subs_header_color', '#52B5D5' );
        $body_color   = get_theme_mod( 'wpuf_subs_body_color', '#4fbbda' );
        $footer_color = get_theme_mod( 'wpuf_subs_footer_color', '#eeeeee' );
        $text_color   = get_theme_mod( 'wpuf_subs_text_color', '#eeeeee' );
        $button_color = get_theme_mod( 'wpuf_subs_button_color', '#4fbbda' );
        $trial_body   = get_theme_mod( 'wpuf_subs_trial_color', '#eeeeee' );

        $subs_options = array(
            'logged_in_label' => $button_label,
            'logged_out_label'  => $guest_label,
            'free_label'   => $free_label,
            'header_color' => $header_color,
            'body_color'   => $body_color,
            'footer_color' => $footer_color,
            'text_color'   => $text_color,
            'button_color' => $button_color,
            'trial_body'   => $trial_body,
        );

        update_option( 'wpuf_subscription_settings', $subs_options  );

        $info_fields = array(
            'success'  => __( 'Success Color', 'wpuf' ),
            'error'    => __( 'Error Color', 'wpuf' ),
            'message'  => __( 'Message Color', 'wpuf' ),
            'info'     => __( 'Warning COlor', 'wpuf' ),
        );

        $info_options = array();
        foreach( $info_fields as $field => $label ) {
            $info_options[$field] = get_theme_mod( 'wpuf_messages_' . $field . '_settings' );
        }

        ?>

        <style>
            ul.wpuf_packs li{
                background-color: <?php echo $footer_color ?> !important;
            }
            ul.wpuf_packs .wpuf-sub-button a, ul.wpuf_packs .wpuf-sub-button a{
                background-color: <?php echo $button_color ?> !important;
                color: <?php echo $text_color ?> !important;
            }
            ul.wpuf_packs h3, ul.wpuf_packs h3{
                background-color:  <?php echo $header_color ?> !important;
                border-bottom: 1px solid <?php echo $header_color ?> !important;
                color: <?php echo $text_color ?> !important;
            }
            ul.wpuf_packs .wpuf-pricing-wrap .wpuf-sub-amount, ul.wpuf_packs .wpuf-pricing-wrap .wpuf-sub-amount{
                background-color:  <?php echo $body_color ?> !important;
                border-bottom: 1px solid <?php echo $body_color ?> !important;
                color: <?php echo $text_color ?> !important;
            }
            ul.wpuf_packs .wpuf-sub-body{
                background-color:  <?php echo $trial_body ?> !important;
            }

            .wpuf-success {
                background-color: <?php echo $info_options['success'] ?> !important;
                border: 1px solid <?php echo $info_options['success'] ?> !important;
            }
            .wpuf-error {
                background-color: <?php echo $info_options['error'] ?> !important;
                border: 1px solid <?php echo $info_options['error'] ?> !important;
            }
            .wpuf-message {
                background: <?php echo $info_options['message'] ?> !important;
                border: 1px solid <?php echo $info_options['message'] ?> !important;
            }
            .wpuf-info {
                background-color: <?php echo $info_options['info'] ?> !important;
                border: 1px solid <?php echo $info_options['info'] ?> !important;
            }
        </style>

        <?php
    }

    public function customizer_options( $wp_customize ) {

        /* WPUF Subscription Customizer */

        $wp_customize->add_section( 'wpuf_subs_customize', array(
            'priority' => 10,
            'title' => __( 'Subscription', 'wpuf-pro' ),
            'description' => __( 'Customize Subscription Pack Styles', 'wpuf-pro' ),
            'panel' => 'wpuf_panel',
        ) );

        $button_label = wpuf_get_option( 'logged_in_label','wpuf_subscription_settings', false );
        $guest_label  = wpuf_get_option( 'logged_out_label','wpuf_subscription_settings', false );
        $free_label   = wpuf_get_option( 'free_label','wpuf_subscription_settings', false );
        $header_color = wpuf_get_option( 'header_color','wpuf_subscription_settings', '#52B5D5' );
        $body_color   = wpuf_get_option( 'body_color','wpuf_subscription_settings', '#4fbbda' );
        $footer_color = wpuf_get_option( 'footer_color','wpuf_subscription_settings', '#eeeeee' );
        $text_color   = wpuf_get_option( 'text_color','wpuf_subscription_settings', '#eeeeee' );
        $button_color = wpuf_get_option( 'button_color','wpuf_subscription_settings', '#4fbbda' );
        $trial_body   = wpuf_get_option( 'trial_body_color','wpuf_subscription_settings', '#eeeeee' );

        //Subscription Settings
        $wp_customize->add_setting( 'wpuf_subs_button_label' , array(
            'default'   => $button_label,
            'transport' => 'refresh',
        ) );
        $wp_customize->add_setting( 'wpuf_subs_guest_button' , array(
            'default'   => $guest_label,
            'transport' => 'refresh',
        ) );
        $wp_customize->add_setting( 'wpuf_subs_free_button' , array(
            'default'   => $free_label,
            'transport' => 'refresh',
        ) );
        $wp_customize->add_setting( 'wpuf_subs_header_color' , array(
            'default'   => $header_color,
            'transport' => 'refresh',
        ) );
        $wp_customize->add_setting( 'wpuf_subs_body_color' , array(
            'default'   => $body_color,
            'transport' => 'refresh',
        ) );
        $wp_customize->add_setting( 'wpuf_subs_footer_color' , array(
            'default'   => $footer_color,
            'transport' => 'refresh',
        ) );
        $wp_customize->add_setting( 'wpuf_subs_text_color' , array(
            'default'   => $text_color,
            'transport' => 'refresh',
        ) );
        $wp_customize->add_setting( 'wpuf_subs_button_color' , array(
            'default'   => $button_color,
            'transport' => 'refresh',
        ) );
        $wp_customize->add_setting( 'wpuf_subs_trial_color' , array(
            'default'   => $trial_body,
            'transport' => 'refresh',
        ) );

        //Subscription Controls
        $wp_customize->add_control( 'wpuf_subs_button_label_control',
            array(
                'label'    => __( 'Button Label',  'wpuf-pro' ),
                'section'  => 'wpuf_subs_customize',
                'settings' => 'wpuf_subs_button_label',
                'type'     => 'text',
            )
        );
        $wp_customize->add_control( 'wpuf_subs_guest_button_control',
            array(
                'label'    => __( 'Button Label (Guest users)', 'wpuf-pro' ),
                'section'  => 'wpuf_subs_customize',
                'settings' => 'wpuf_subs_guest_button',
                'type'     => 'text',
            )
        );
        $wp_customize->add_control( 'wpuf_subs_free_button_control',
            array(
                'label'    => __( 'Free pack Button' , 'wpuf-pro'),
                'section'  => 'wpuf_subs_customize',
                'settings' => 'wpuf_subs_free_button',
                'type'     => 'text',
            )
        );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wpuf_subs_header_color_control', array(
            'label'      => __( 'Header Color', 'wpuf-pro' ),
            'section'    => 'wpuf_subs_customize',
            'settings'   => 'wpuf_subs_header_color',
        ) ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wpuf_subs_body_color_control', array(
            'label'      => __( 'Body Color', 'wpuf-pro' ),
            'section'    => 'wpuf_subs_customize',
            'settings'   => 'wpuf_subs_body_color',
        ) ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wpuf_subs_footer_color_control', array(
            'label'      => __( 'Footer Color', 'wpuf-pro' ),
            'section'    => 'wpuf_subs_customize',
            'settings'   => 'wpuf_subs_footer_color',
        ) ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wpuf_subs_text_color_control', array(
            'label'      => __( 'Text Color', 'wpuf-pro' ),
            'section'    => 'wpuf_subs_customize',
            'settings'   => 'wpuf_subs_text_color',
        ) ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wpuf_subs_button_color_control', array(
            'label'      => __( 'Button Color', 'wpuf-pro' ),
            'section'    => 'wpuf_subs_customize',
            'settings'   => 'wpuf_subs_button_color',
        ) ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wpuf_subs_trial_color_control', array(
            'label'      => __( 'Trial Body Color', 'wpuf-pro' ),
            'section'    => 'wpuf_subs_customize',
            'settings'   => 'wpuf_subs_trial_color',
        ) ) );

        /* WPUF Error/Warning Messages Customizer */
        $wp_customize->add_section(
            'wpuf_customize_messages',
            array(
                'title'       => __( 'Notice Colors', 'wpuf' ),
                'priority'    => 21,
                'panel'       => 'wpuf_panel',
                'description' => __( 'These options let you customize the look of Info Messages like Error, Warning etc..', 'wpuf' ),
            )
        );

        // Info messages field controls.
        $info_fields = array(
            'success'  => __( 'Success Background', 'wpuf' ),
            'error'    => __( 'Error Background', 'wpuf' ),
            'message'  => __( 'Message Background', 'wpuf' ),
            'info'     => __( 'Info Background', 'wpuf' ),
        );
        $default_field_bg = array( '#dff0d8',  '#f2dede', '#fcf8e3', '#fef5be' );

        $idx = 0;
        foreach( $info_fields as $field => $label ) {
            $wp_customize->add_setting(
                'wpuf_messages_' . $field . '_settings',
                array(
                    'type'       => 'theme_mod',
                    'default'    => $default_field_bg[$idx++],
                    'section'    => 'wpuf_billing_address',
                    'transport'  => 'refresh',
                )
            );
            $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'wpuf_messages_' . $field . '_control', array(
                    /* Translators: %s field name. */
                    'label'    => sprintf( __( '%s field', 'wpuf' ), $label ),
                    'section'  => 'wpuf_customize_messages',
                    'settings' => 'wpuf_messages_' . $field . '_settings',
                )
            ) );
        }
    }
}
