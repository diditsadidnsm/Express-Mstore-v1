<?php
/**
 * Shortcode Field Class
 *
 * @since 3.1.0
 **/
class WPUF_Form_Field_Shortcode extends WPUF_Field_Contract {

    function __construct() {
        $this->name       = __( 'Shortcode', 'wpuf-pro' );
        $this->input_type = 'shortcode';
        $this->icon       = 'file-code-o';
    }

    /**
     * Render the Shortcode field
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
        $value = '';
        ?>
            <li <?php $this->print_list_attributes( $field_settings ); ?>>
                <?php $this->print_label( $field_settings ); ?>

                <div class="wpuf-fields <?php echo ' wpuf_'.$field_settings['name'].'_'.$form_id; ?>">
                    <?php echo do_shortcode( $field_settings['shortcode'] ); ?>
                </div>
            </li>
        <?php
    }

    /**
     * It's a full width block
     *
     * @return boolean
     */
    public function is_full_width() {
        return true;
    }

    /**
     * Get field options setting
     *
     * @return array
     */
    public function get_options_settings() {
        $settings = array(
            array(
                'name'          => 'shortcode',
                'title'         => __( 'Shortcode', 'wpuf-pro' ),
                'type'          => 'text',
                'section'       => 'basic',
                'priority'      => 10,
                'help_text'     => __( 'Input your shortcode here', 'wpuf-pro' ),
            ),
        );

        return $settings;
    }

    /**
     * Get the field props
     *
     * @return array
     */
    public function get_field_props() {
        return array(
            'input_type'        => 'shortcode',
            'template'          => $this->get_type(),
            'label'             => $this->get_name(),
            'shortcode'         => '[your_shortcode]',
            'id'                => 0,
            'is_new'            => true,
            'is_meta'           => 'yes',
            'wpuf_cond'         => null
        );
    }
}
