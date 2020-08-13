<?php
/**
 *  Pro Fields Manager Class
 *
 * @since 3.1.0
 **/
class WPUF_Pro_Fields_Manager {

    function __construct() {

        add_filter( 'wpuf-form-fields', array( $this, 'register_fields' ) );

        add_filter( 'wpuf-form-fields-custom-fields', array( $this, 'add_to_custom_fields' ) );

        add_filter( 'wpuf-form-fields-others-fields', array( $this, 'add_to_others_fields' ) );

        add_filter( 'wpuf_field_get_js_settings', array( $this, 'add_conditional_field' ) );

        // step start fields
        add_action( 'wpuf_form_fields_top', array( $this, 'step_start_form_top' ), 10, 2 );

    }

    /**
     * Register pro fields
     *
     * @param  array $fields
     *
     * @return array
     */
    public function register_fields( $fields ) {

        if ( class_exists( 'WPUF_Field_Contract' ) ) {
            require_once dirname( __FILE__ ) . '/fields/class-field-repeat.php';
            require_once dirname( __FILE__ ) . '/fields/class-field-date.php';
            require_once dirname( __FILE__ ) . '/fields/class-field-file.php';
            require_once dirname( __FILE__ ) . '/fields/class-field-country.php';
            require_once dirname( __FILE__ ) . '/fields/class-field-numeric.php';
            require_once dirname( __FILE__ ) . '/fields/class-field-address.php';
            require_once dirname( __FILE__ ) . '/fields/class-field-gmap.php';
            require_once dirname( __FILE__ ) . '/fields/class-field-shortcode.php';
            require_once dirname( __FILE__ ) . '/fields/class-field-hook.php';
            require_once dirname( __FILE__ ) . '/fields/class-field-toc.php';
            require_once dirname( __FILE__ ) . '/fields/class-field-rating.php';
            require_once dirname( __FILE__ ) . '/fields/class-field-step.php';
            require_once dirname( __FILE__ ) . '/fields/class-field-embed.php';
            require_once dirname( __FILE__ ) . '/fields/class-field-really-simple-captcha.php';
            require_once dirname( __FILE__ ) . '/fields/class-field-avatar.php';
            require_once dirname( __FILE__ ) . '/fields/class-field-display-name.php';
            require_once dirname( __FILE__ ) . '/fields/class-field-first-name.php';
            require_once dirname( __FILE__ ) . '/fields/class-field-last-name.php';
            require_once dirname( __FILE__ ) . '/fields/class-field-nickname.php';
            require_once dirname( __FILE__ ) . '/fields/class-field-password.php';
            require_once dirname( __FILE__ ) . '/fields/class-field-user-bio.php';
            require_once dirname( __FILE__ ) . '/fields/class-field-user-email.php';
            require_once dirname( __FILE__ ) . '/fields/class-field-user-url.php';
            require_once dirname( __FILE__ ) . '/fields/class-field-username.php';

            $fields['user_login']            = new WPUF_Form_Field_Username();
            $fields['first_name']            = new WPUF_Form_Field_First_Name();
            $fields['last_name']             = new WPUF_Form_Field_Last_Name();
            $fields['display_name']          = new WPUF_Form_Field_Display_Name();
            $fields['nickname']              = new WPUF_Form_Field_Nickame();
            $fields['user_email']            = new WPUF_Form_Field_User_Email();
            $fields['user_url']              = new WPUF_Form_Field_User_Url();
            $fields['user_bio']              = new WPUF_Form_Field_User_Bio();
            $fields['password']              = new WPUF_Form_Field_Password();
            $fields['avatar']                = new WPUF_Form_Field_Avater();
            $fields['repeat_field']          = new WPUF_Form_Field_Repeat();
            $fields['date_field']            = new WPUF_Form_Field_Date();
            $fields['file_upload']           = new WPUF_Form_Field_File();
            $fields['country_list_field']    = new WPUF_Form_Field_Country();
            $fields['numeric_text_field']    = new WPUF_Form_Field_Numeric();
            $fields['address_field']         = new WPUF_Form_Field_Address();
            $fields['google_map']            = new WPUF_Form_Field_GMap();
            $fields['shortcode']             = new WPUF_Form_Field_Shortcode();
            $fields['action_hook']           = new WPUF_Form_Field_Hook();
            $fields['toc']                   = new WPUF_Form_Field_Toc();
            $fields['ratings']               = new WPUF_Form_Field_Rating();
            $fields['step_start']            = new WPUF_Form_Field_Step();
            $fields['embed']                 = new WPUF_Form_Field_Embed();
            $fields['really_simple_captcha'] = new WPUF_Form_Field_really_simple_captcha();
        }

        return $fields;
    }


    /**
     * Register fields to custom field section
     *
     * @param array $fields
     */
    public function add_to_custom_fields( $fields ) {
        $pro_fields = array(
            'repeat_field', 'date_field', 'file_upload', 'country_list_field',
            'numeric_text_field', 'address_field', 'google_map', 'step_start',
            'embed'
        );

        return array_merge( $fields, $pro_fields );
    }

    /**
     * Register fields to others field section
     *
     * @param array $fields
     */
    public function add_to_others_fields( $fields ) {
        $pro_fields = array(
            'shortcode', 'action_hook', 'toc', 'ratings','really_simple_captcha'
        );

        return array_merge( $fields, $pro_fields );
    }

    /**
     * Add conditional field settings
     *
     * @param array $settings
     */
    public function add_conditional_field( $settings ) {
        $settings['settings'][] = array(
            'name'           => 'wpuf_cond',
            'title'          => __( 'Conditional Logic', 'wpuf-pro' ),
            'type'           => 'conditional-logic',
            'section'        => 'advanced',
            'priority'       => 30,
            'help_text'      => '',
        );

        return $settings;
    }

    /**
     * [step_start_form_top description]
     *
     * @param  \WeForms_Form $form
     * @param  array $form_fields
     *
     * @return void
     */
    public function step_start_form_top( $form, $form_fields ) {
        $settings     = $form->get_settings();
        $is_multistep = isset( $settings['enable_multistep'] ) && $settings['enable_multistep'];

        if ( ! $is_multistep ) {
            return;
        }

        if ( isset( $settings['multistep_progressbar_type'] ) && $settings['multistep_progressbar_type'] == 'progressive' ) {
            wp_enqueue_script('jquery-ui-progressbar');
        }

        if ( isset( $settings['enable_multistep'] ) && $settings['enable_multistep'] == 'yes' ) {
            $ms_ac_txt_color   = isset( $settings['ms_ac_txt_color'] ) ? $settings['ms_ac_txt_color'] : '#ffffff';
            $ms_active_bgcolor = isset( $settings['ms_active_bgcolor'] ) ? $settings['ms_active_bgcolor'] : '#00a0d2';
            $ms_bgcolor        = isset( $settings['ms_bgcolor'] ) ? $settings['ms_bgcolor'] : '#E4E4E4';
            ?>
            <style type="text/css">
                .wpuf-form-add .wpuf-form .wpuf-multistep-progressbar ul.wpuf-step-wizard li,
                .wpuf-form-add .wpuf-form .wpuf-multistep-progressbar.ui-progressbar {
                    background-color:  <?php echo $ms_bgcolor; ?>;
                    background:  <?php echo $ms_bgcolor; ?>;
                }
                .wpuf-form-add .wpuf-form .wpuf-multistep-progressbar ul.wpuf-step-wizard li::after{
                    border-left-color: <?php echo $ms_bgcolor; ?>;
                }
                .wpuf-form-add .wpuf-form .wpuf-multistep-progressbar ul.wpuf-step-wizard li.active-step,
                .wpuf-form-add .wpuf-form .wpuf-multistep-progressbar .ui-widget-header{
                    color: <?php echo $ms_ac_txt_color; ?>;
                    background-color:  <?php echo $ms_active_bgcolor; ?>;
                }
                .wpuf-form-add .wpuf-form .wpuf-multistep-progressbar ul.wpuf-step-wizard li.active-step::after {
                    border-left-color: <?php echo $ms_active_bgcolor; ?>;
                }
                .wpuf-form-add .wpuf-form .wpuf-multistep-progressbar.ui-progressbar .wpuf-progress-percentage{
                    color: <?php echo $ms_ac_txt_color; ?>;
                }
            </style>
            <input type="hidden" name="wpuf_multistep_type" value="<?php echo $settings['multistep_progressbar_type'] ?>"/>

            <?php

            if ( $settings['multistep_progressbar_type'] == 'step_by_step' ){
                ?>
                <!-- wpuf-multistep-progressbar -->
                <div class="wpuf-multistep-progressbar"> </div>
            <?php
            } else {
                ?>
                <div class="wpuf-multistep-progressbar"> </div>
            <?php

            }
        }
    }
}
