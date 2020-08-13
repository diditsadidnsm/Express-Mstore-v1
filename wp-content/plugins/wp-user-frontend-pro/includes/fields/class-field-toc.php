<?php
/**
 * Toc Field Class
 *
 * @since 3.1.0
 **/
class WPUF_Form_Field_Toc extends WPUF_Form_Field_Text {

    function __construct() {
        $this->name       = __( 'Terms & Conditions', 'wpuf-pro' );
        $this->input_type = 'toc';
        $this->icon       = 'file-text';
    }

    /**
     * Render the toc field
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
        if ( $post_id ) {
            return;
        }

        ?>

        <li <?php $this->print_list_attributes( $field_settings ); ?>>
            <div class="wpuf-label">
                &nbsp;
            </div>

            <div data-required="<?php echo $field_settings['show_checkbox'] ? 'yes' : 'no' ?>" data-type="radio" class="wpuf-fields <?php echo ' wpuf_'.$field_settings['name'].'_'.$form_id; ?>">

                <label>
                    <?php if ( isset( $field_settings['show_checkbox'] ) && $field_settings['show_checkbox'] ) : ?>
                        <input type="checkbox" name="wpuf_accept_toc" required="required" />
                    <?php endif; ?>

                    <?php echo $field_settings['description']; ?>
                </label>
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
                'name'          => 'name',
                'title'         => __( 'Meta Key', 'wpuf-pro' ),
                'type'          => 'text',
                'section'       => 'basic',
                'priority'      => 10,
                'help_text'     => __( 'Name of the meta key this field will save to', 'wpuf-pro' ),
            ),

            array(
                'name'          => 'description',
                'title'         => __( 'Terms & Conditions', 'wpuf-pro' ),
                'type'          => 'textarea',
                'section'       => 'basic',
                'priority'      => 11,
            ),

            array(
                'name'          => 'show_checkbox',
                'type'          => 'checkbox',
                'options'       => array(
                    true        => __( 'Show checkbox', 'wpuf-pro' )
                ),
                'section'       => 'basic',
                'priority'      => 11,
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
        $props    = array(

            'input_type'        => 'toc',
            'template'          => 'toc',
            'label'             => '',
            'name'              => 'terms_and_conditions',
            'is_meta'           => 'yes',
            'description'       => __( 'I have read and agree to the <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a>', 'wpuf-pro' ),
            'show_checkbox'     => true,
            'css'               => '',
            'id'                => 0,
            'is_new'            => true,
            'show_in_post'      => 'yes',
            'hide_field_label'  => 'no',
            // 'label'         => '',
            // 'description'   => __( 'I have read and agree to the <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a>', 'wpuf-pro' ),
            // 'show_checkbox' => true,
             'wpuf_cond'     => ''
             // 'wpuf_cond'     => null

        );

        return $props;
    }

}
