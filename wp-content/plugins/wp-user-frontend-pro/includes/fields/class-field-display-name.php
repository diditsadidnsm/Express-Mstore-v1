<?php
/**
 * Display name Field Class
 *
 * @since 3.1.0
 **/
class WPUF_Form_Field_Display_Name extends WPUF_Field_Contract {

    function __construct() {
        $this->name       = __( 'Display Name', 'wpuf-pro' );
        $this->input_type = 'display_name';
        $this->icon       = 'user';
    }

    /**
     * Render the Display Name field
     *
     * @param  array  $field_settings
     *
     * @param  integer  $form_id
     *
     * @param  string  $type
     *
     * @param  integer  $post_id
     *
     * @return void
     */
    public function render( $field_settings, $form_id, $type = 'post', $post_id = null ) {
        if ( isset ( $post_id ) && $post_id != 0 ) {
            $value = $this->get_user_data( $post_id, $field_settings['name'] );
        } else {
            $value = $field_settings['default'];
        }

        ?>

        <li <?php $this->print_list_attributes( $field_settings ); ?>>
            <?php $this->print_label( $field_settings, $form_id ); ?>

            <div class="wpuf-fields">
                <input
                    class="textfield <?php echo 'wpuf_' . $field_settings['name'] . '_' . $form_id; ?>"
                    id="<?php echo $field_settings['name'] . '_' . $form_id; ?>"
                    type="text"
                    data-required="<?php echo $field_settings['required'] ?>"
                    data-type="text" name="<?php echo esc_attr( $field_settings['name'] ); ?>"
                    placeholder="<?php echo esc_attr( $field_settings['placeholder'] ); ?>"
                    value="<?php echo esc_attr( $value ) ?>"
                    size="<?php echo esc_attr( $field_settings['size'] ) ?>"
                />

                <span class="wpuf-wordlimit-message wpuf-help"></span>
                <?php $this->help_text( $field_settings ); ?>
            </div>
        </li>

        <?php
    }



   /**
    * Get field options setting
    *
    * @return void
    **/
    public function get_options_settings() {
        $default_options = $this->get_default_option_settings( false , array('dynamic') );
        $settings        = $this->get_default_text_option_settings( true );

        return array_merge( $default_options, $settings );
    }

    /**
     * Get the field props
     *
     * @return void
     **/
    public function get_field_props() {
        $defaults = $this->default_attributes();

        $props    = array(
            'input_type'    => 'text',
            'required'      => 'no',
            'name'          => 'display_name',
            'is_meta'       => 'no',
            'help'          => '',
            'css'           => '',
            'placeholder'   => '',
            'default'       => '',
            'size'          => 40,
            'id'            => 0,
            'is_new'        => true,
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
       return sanitize_text_field( trim( $_POST[$field['name']] ) );
    }

}
