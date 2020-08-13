<?php
/**
 * Numeric Field Class
 *
 * @since 3.1.0
 **/
class WPUF_Form_Field_Numeric extends WPUF_Form_Field_Text {

    function __construct() {
        $this->name       = __( 'Numeric Field', 'wpuf-pro' );
        $this->input_type = 'numeric_text_field';
        $this->icon       = 'hashtag';
    }

    /**
     * Render the Numeric field
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
        $calculation_class = '';

        if ( isset( $field_settings['enable_calculation'] ) && $field_settings['enable_calculation'] && isset( $field_settings['formula_field'] ) && !empty( $field_settings['formula_field'] ) ) {
            $calculation_class = 'we-calc-result';
        }

        if ( isset( $post_id ) ) {
            if ( $this->is_meta( $field_settings ) ) {
                $value = $this->get_meta( $post_id, $field_settings['name'], $type );
            }
        } else {
            $value = $field_settings['default'];
        }

        $this->field_print_label( $field_settings, $form_id );

        ?>

        <div class="wpuf-fields wpuf-numeric_text_holder">
            <input
                class="textfield <?php echo 'wpuf_'.$field_settings['name'].'_'.$form_id; ?> <?php echo $calculation_class ?>"
                id="<?php echo $field_settings['name']; ?>"
                type="number"
                min="<?php echo $field_settings['min_value_field'];?>"
                max="<?php echo $field_settings['max_value_field'] == 0 ? '' : $field_settings['max_value_field']; ?>"
                step="<?php echo $field_settings['step_text_field']; ?>"
                data-required="<?php echo $field_settings['required'] ?>"
                data-type="text" name="<?php echo esc_attr( $field_settings['name'] ); ?>"
                placeholder="<?php echo esc_attr( $field_settings['placeholder'] ); ?>"
                value="<?php echo esc_attr( $value ) ?>"
                size="<?php echo esc_attr( $field_settings['size'] ) ?>" />

            <?php $this->help_text( $field_settings ); ?>
        </div>

        <?php $this->after_field_print_label();
    }

    /**
     * Get field options setting
     *
     * @return array
     */
    public function get_options_settings() {
        $default_options      = $this->get_default_option_settings();
        $default_text_options = $this->get_default_text_option_settings( true );

        $settings = array(
            array(
                'name'          => 'step_text_field',
                'title'         => __( 'Step', 'wpuf-pro' ),
                'type'          => 'text',
                'variation'     => 'number',
                'section'       => 'advanced',
                'priority'      => 9,
                'help_text'     => '',
            ),

            array(
                'name'          => 'min_value_field',
                'title'         => __( 'Min Value', 'wpuf-pro' ),
                'type'          => 'text',
                'variation'     => 'number',
                'section'       => 'advanced',
                'priority'      => 11,
                'help_text'     => '',
            ),

            array(
                'name'          => 'max_value_field',
                'title'         => __( 'Max Value', 'wpuf-pro' ),
                'type'          => 'text',
                'variation'     => 'number',
                'section'       => 'advanced',
                'priority'      => 13,
                'help_text'     => '',
            ),
        );


        return array_merge( $default_options,$default_text_options,$settings);
    }

    /**
     * Get the field props
     *
     * @return array
     */
    public function get_field_props() {
        $defaults = $this->default_attributes();
        $props    = array(
            'input_type'        => 'numeric_text',
            'step_text_field'   => '0',
            'min_value_field'   => '0',
            'max_value_field'   => '0',
            'show_in_post'      => 'yes',
            'hide_field_label'  => 'no',
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
