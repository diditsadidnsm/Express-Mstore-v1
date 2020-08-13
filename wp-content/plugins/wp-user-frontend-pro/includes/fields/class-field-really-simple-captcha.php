<?php
/**
 * Really Simple Captcha Field Class
 *
 * @since 3.1.0
 **/
class WPUF_Form_Field_really_simple_captcha extends WPUF_Field_Contract {

    function __construct() {
        $this->name       = __( 'Really Simple Captcha', 'wpuf-pro' );
        $this->input_type = 'really_simple_captcha';
        $this->icon       = 'check-circle-o';
    }

    /**
     * Render the text field
     *
     * @param  array  $field_settings
     *
     * @param  integer  $form_id
     *
     * @return void
    */
    public function render( $field_settings, $form_id, $type = 'post', $post_id = null ) {
        if ( $post_id ) {
            return;
        }

        if ( !class_exists( 'ReallySimpleCaptcha' ) ) {
            ?>
            <div class="wpuf-fields <?php  echo ' wpuf_'.$field_settings['name'].'_'.$form_id; ?>">
                <?php
                _e( 'Error: Really Simple Captcha plugin not found!', 'wpuf-pro' );
                ?>
            </div>
            <?php
            return;
        }

        $captcha_instance = new ReallySimpleCaptcha();
        $word             = $captcha_instance->generate_random_word();
        $prefix           = mt_rand();
        $image_num        = $captcha_instance->generate_image( $prefix, $word );

        ?>

        <div class="wpuf-fields <?php  echo ' wpuf_'.str_replace(" ","_",$field_settings['name']).'_'.$form_id; ?>">
            <img src="<?php echo plugins_url( 'really-simple-captcha/tmp/' . $image_num ); ?>" alt="Captcha" />
            <input type="text" name="rs_captcha" value="" />
            <input type="hidden" name="rs_captcha_val" value="<?php echo $prefix; ?>" />
        </div>

    <?php
    }

    /**
     * It's a full width block
     *
     * @return boolean
     **/
    public function is_full_width() {
        return true;
    }

    /**
     * Custom validator
     *
     * @return array
     **/
    public function get_validator() {
        return array(
            'callback'      => 'is_rs_captcha_active',
            'button_class'  => 'button-faded',
            'msg_title'     => __( 'Plugin dependency', 'wpuf-pro' ),
            'msg'           => sprintf(
                __( 'This field depends on <a href="%s" target="_blank">Really Simple Captcha</a> plugin. Install and activate it first.', 'wpuf-pro' ),
                'https://wordpress.org/plugins/really-simple-captcha/'
            ),
        );
    }


    /**
     * Get field options setting
     *
     * @return array
     */
    public function get_options_settings() {
        $settings = array(
        	array(
                'name'          => 'label',
                'title'         => __( 'Really Simple Captcha', 'wpuf-pro' ),
                'type'          => 'text',
                'section'       => 'basic',
                'priority'      => 10,
                'help_text'     => __( 'Title of the section', 'wpuf-pro' ),
            )
        );

        return $settings;
    }

    /**
     * Get the field props
     *
     * @return array
     */
    public function get_field_props() {
        $props = array(
            'input_type'      => $this->get_type(),
            'template'        => $this->get_type(),
            'label'           => '',
            'name'            => $this->get_name(),
            'id'              => 0,
            'is_new'          => true,
            'wpuf_cond'       => $this->default_conditional_prop(),
            'wpuf_visibility' => $this->get_default_visibility_prop(),
        );

        return $props;
    }
}
