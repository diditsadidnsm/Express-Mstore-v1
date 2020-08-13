<?php
/**
 * Password Field Class
 *
 * @since 3.1.0
 **/
class WPUF_Form_Field_Password extends WPUF_Field_Contract {

	function __construct() {
        $this->name       = __('Password', 'wpuf-pro' );
        $this->input_type = 'password';
        $this->icon       = 'lock';
    }

    /**
     * Render the Password field
     *
     * @param  array  $field_settings
     * @param  integer  $form_id
     * @param  string  $type
     * @param  integer  $post_id
     *
     * @return void
     */
    public function render( $field_settings, $form_id, $type = 'post', $post_id = null ) {
        $value         = $field_settings['default'];
        $repeat_pass   = ( $field_settings['repeat_pass'] == 'yes' ) ? true : false;
        $pass_strength = ( $field_settings['pass_strength'] == 'yes' ) ? true : false;

        ?>

        <li <?php $this->print_list_attributes( $field_settings ); ?>>
            <?php $this->print_label( $field_settings, $form_id ); ?>

            <div class="wpuf-fields">
                <input
                    class="password <?php echo ' wpuf_'.$field_settings['name'].'_'.$form_id; ?>"
                    id="<?php echo $field_settings['name'].'_'.$form_id .'_1'; ?>"
                    type="password"
                    data-required="<?php echo $field_settings['required'] ?>"
                    data-type="password"
                    placeholder="<?php echo esc_attr( $field_settings['placeholder'] ); ?>"
                    value=""
                    size="<?php echo esc_attr( $field_settings['size'] ) ?>"
                    name="pass1"
                />

                <span class="wpuf-wordlimit-message wpuf-help"></span>
                <?php $this->help_text( $field_settings ); ?>
            </div>
        </li>

        <?php  if ( $repeat_pass ) { ?>

            <li>
                <div class="wpuf-label">
                    <label for="<?php echo isset( $field_settings['re_pass_label'] ) ? $field_settings['re_pass_label'] . '_' . $form_id : 'cls'; ?>"><?php echo $field_settings['re_pass_label'] . $this->required_mark( $field_settings ); ?></label>
                </div>

                <div class="wpuf-fields">
                    <input
                        id="<?php echo $field_settings['name'].'_'.$form_id .'_2'; ?>"
                        class="password <?php echo ' wpuf_'.$field_settings['name'].'_'.$form_id; ?>"
                        type="password"
                        data-required="<?php echo $field_settings['required'] ?>"
                        data-type="confirm_password" name="pass2"
                        placeholder="<?php echo esc_attr( $field_settings['placeholder'] ); ?>"
                        value=""
                        size="<?php echo esc_attr( $field_settings['size'] ) ?>"
                    />

                    <span class="wpuf-wordlimit-message wpuf-help"></span>
                    <?php  $this->help_text( $field_settings ); ?>
                </div>
            </li>

            <?php }

            if ( $pass_strength ) {
                wp_enqueue_script( 'zxcvbn' );
                wp_enqueue_script( 'password-strength-meter' );

                ?>

                <li>
                    <div class="wpuf-label">&nbsp;</div>
                    <div class="wpuf-fields">
                        <div class="pass-strength-result" id="pass-strength-result_<?php echo $form_id; ?>" style="display: block"><?php _e( 'Strength indicator', 'wp-user-frontend' ); ?></div>
                    </div>
                </li>

                <script type="text/javascript">
                    jQuery(function($) {
                        function check_pass_strength() {
                            var pass1 = $("#<?php echo $field_settings['name'].'_'.$form_id .'_1'; ?>").val(),
                                pass2 = $("#<?php echo $field_settings['name'].'_'.$form_id .'_2'; ?>").val(),
                                strength;

                            if ( typeof pass2 === undefined ) {
                                pass2 = pass1;
                            }

                            $("#pass-strength-result_<?php echo $form_id; ?>").removeClass('short bad good strong');
                            if (!pass1) {
                                $("#pass-strength-result_<?php echo $form_id; ?>").html(pwsL10n.empty);
                                return;
                            }

                            strength = wp.passwordStrength.meter(pass1, wp.passwordStrength.userInputBlacklist(), pass2);

                            switch (strength) {
                                case 2:
                                    $("#pass-strength-result_<?php echo $form_id; ?>").addClass('bad').html(pwsL10n.bad);
                                    break;
                                case 3:
                                    $("#pass-strength-result_<?php echo $form_id; ?>").addClass('good').html(pwsL10n.good);
                                    break;
                                case 4:
                                    $("#pass-strength-result_<?php echo $form_id; ?>").addClass('strong').html(pwsL10n.strong);
                                    break;
                                case 5:
                                    $("#pass-strength-result_<?php echo $form_id; ?>").addClass('short').html(pwsL10n.mismatch);
                                    break;
                                default:
                                    $("#pass-strength-result_<?php echo $form_id; ?>").addClass('short').html(pwsL10n['short']);
                            }
                        }

                        $("#<?php echo $field_settings['name'].'_'.$form_id .'_1'; ?>").val('').keyup(check_pass_strength);
                        $("#<?php echo $field_settings['name'].'_'.$form_id .'_2'; ?>").val('').keyup(check_pass_strength);
                        $("#pass-strength-result_<?php echo $form_id; ?>").show();
                    });
                </script>
            <?php }
    }

    /**
     * It's a full width block
     *
     * @return void
     **/
    public function is_full_width() {
        return true;
    }

   /**
    * Get field options setting
    *
    * @return array
    **/
    public function get_options_settings() {
        $default_options      = $this->get_default_option_settings( false, array('dynamic') );
        $default_text_options = $this->get_default_text_option_settings( true );

        $settings = array(

            array(
                'name'          => 'min_length',
                'title'         => __( 'Minimum password length', 'wpuf-pro' ),
                'type'          => 'text',
                'section'       => 'advanced',
                'priority'      => 23,
            ),

            array(
                'name'          => 'repeat_pass',
                'title'         => __( 'Password Re-type', 'wpuf-pro' ),
                'type'          => 'checkbox',
                'options'       => array( 'yes' => __( 'Require Password repeat', 'wpuf-pro' ) ),
                'is_single_opt' => true,
                'section'       => 'advanced',
                'priority'      => 24,
            ),

            array(
                'name'          => 're_pass_label',
                'title'         => __( 'Re-type password label', 'wpuf-pro' ),
                'type'          => 'text',
                'section'       => 'advanced',
                'priority'      => 25,
            ),

            array(
                'name'          => 'pass_strength',
                'title'         => __( 'Password Strength Meter', 'wpuf-pro' ),
                'type'          => 'checkbox',
                'options'       => array( 'yes' => __( 'Show password strength meter', 'wpuf-pro' ) ),
                'is_single_opt' => true,
                'section'       => 'advanced',
                'priority'      => 26,
            ),

        );

        return  array_merge( $default_options, $default_text_options,$settings);
    }

    /**
     * Get the field props
     *
     * @return array
     **/
    public function get_field_props() {
        $defaults = $this->default_attributes();

        $props    = array(
            'input_type'    => 'password',
            'name'          => 'password',
            'required'      => 'yes',
            'is_meta'       => 'no',
            'size'          => 40,
            'id'            => 0,
            'is_new'        => true,
            'min_length'    => 5,
            'repeat_pass'   => 'yes',
            're_pass_label' => 'Confirm Password',
            'pass_strength' => 'yes',
        );

        return array_merge( $defaults, $props );
    }

    /**
     * Prepare entry
     *
     * @param $field
     *
     * @return mixed
     */
    public function prepare_entry( $field ) {
       return sanitize_text_field(trim( $_POST[$field['name']]));
    }

}
