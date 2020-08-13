<?php
/**
 * Embed Field Class
 *
 * @since 3.1.0
 **/
class WPUF_Form_Field_Embed extends WPUF_Field_Contract {

    function __construct() {
        $this->name       = __( 'Embed', 'wpuf-pro' );
        $this->input_type = 'embed';
        $this->icon       = 'address-card-o';
    }

    /**
     * Render the Embed field
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
        if ( isset( $post_id ) &&  $post_id != '0' ) {
            if ( $this->is_meta( $field_settings ) ) {
                $value = $this->get_meta( $post_id, $field_settings['name'], $type );
            }
        } else {
            $value = $field_settings['default'];
        }

        $this->field_print_label( $field_settings, $form_id );

        ?>

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

        <?php $this->after_field_print_label();
    }

    /**
     * Get field options setting
     *
     * @return array
     **/
    public function get_options_settings(){
        $default_options = $this->get_default_option_settings( true );

        $settings = array(
            array(
                'name'          => 'preview_width',
                'title'         => __( 'Preview Width', 'wpuf-pro' ),
                'type'          => 'text',
                'section'       => 'basic',
                'priority'      => 21,
                'help_text'     => __( 'Height in px (e.g: 123)', 'wpuf-pro' ),
            ),

            array(
                'name'          => 'preview_height',
                'title'         => __( 'Preview Height', 'wpuf-pro' ),
                'type'          => 'text',
                'section'       => 'basic',
                'priority'      => 22,
                'help_text'     => __( 'Height in px (e.g: 456)', 'wpuf-pro' ),
            )

        );

        return array_merge( $default_options, $settings );
    }

    /**
     * Get the field props
     *
     * @return array
     **/
    public function get_field_props(){
        $defaults = $this->default_attributes();

        $props=array(
            'input_type'        => 'url',
            'is_meta'           => 'yes',
            'preview_width'     => '123',
            'preview_height'    => '456',
            'show_in_post'      => 'yes',
            'hide_field_label'  => 'no',
        );

        return array_merge( $defaults, $props );
    }

}
