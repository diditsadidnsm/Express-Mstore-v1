<?php
/**
 *  QR_CODE Field Class
 *
 * @since 3.1.0
 **/
if(class_exists('WPUF_Field_Contract')) {

class WPUF_Form_Field_QR_Code extends WPUF_Field_Contract {

    function __construct() {
        $this->name       = __( 'QR Code', 'wpuf-pro');
        $this->input_type = 'qr_code';
        $this->icon       = 'address-card-o';
    }

    /**
     * Render the Qr field
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
        if( $field_settings['input_type'] != 'qr_code') {
            return;
        }

        $current_selected = '';

        if( isset( $post_id ) ) {
            $selected = $this->get_meta( $post_id, $field_settings['name'], $type, false );
            $current_selected = isset( $selected[0]['type']) ? $selected[0]['type'] : '';
        } else {
            $type = '';
            $field_settings['qr_type'] = isset( $field_settings['qr_type'] ) ? $field_settings['qr_type'] : array();
            $selected = isset( $field_settings['selected'] ) ? $field_settings['selected'] : array();
            $current_selected = isset( $selected[0]['type']) ? $selected[0]['type'] : '';
        }

        $this->field_print_label( $field_settings, $form_id );

        ?>

        <div class="wpuf-fields qr_code_wrap">

            <?php if ( $field_settings['qr_type'] && count( $field_settings['qr_type'] ) > 0 ) { ?>
                <p>
                    <select name="<?php echo $field_settings['name']; ?>[qr_code_type]"
                        data-type="select" id="<?php echo $field_settings['name']; ?>_type_id"
                        data-required="<?php echo $field_settings['required'] ?>"
                        class="qr_code_type_class" data-formfield="<?php echo $field_settings['name']; ?>"
                        data-posttype="<?php echo $type; ?> "
                        data-postid="<?php echo $post_id; ?>" >
                        <option value=""> - select -</option>
                        <?php
                        foreach ($field_settings['qr_type'] as $value => $option) {
                            $select = selected( $current_selected, $option, false );
                            ?>
                            <option value="<?php echo esc_attr( $option ); ?>" <?php echo esc_attr( $select ); ?>><?php echo strtoupper( $option ); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </p>
                <p class="apppend_data"></p>
                <?php
            }
            ?>
            <div class="wpuf-fields">
                <span class="wpuf-help"><?php echo $field_settings['help']; ?></span>
            </div>
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

        $settings = array(
            array(
                'name'      => 'qr_type',
                'title'     => __( 'Allowed Types', 'wpuf-pro' ),
                'type'      => 'checkbox',
                'section'   => 'advanced',
                'priority'  => 15,
                'help_text' => __( 'Some details text about the section', 'wpuf-pro' ),
                'options'   => array(
                    'url'       => __( 'URL', 'wpuf-pro' ),
                    'text'      => __( 'Text', 'wpuf-pro' ),
                    'geo'       => __( 'Location', 'wpuf-pro' ),
                    'sms'       => __( 'SMS', 'wpuf-pro' ),
                    'wifi'      => __( 'Wifi', 'wpuf-pro' ),
                    'card'      => __( 'Card', 'wpuf-pro' ),
                    'email'     => __( 'Email', 'wpuf-pro' ),
                    'calendar'  => __( 'Calendar', 'wpuf-pro' ),
                    'phone'     => __( 'Phone', 'wpuf-pro' ),
                )
            ),
        );

        return array_merge( $default_options, $settings );
    }

    /**
     * Get the field props
     *
     * @return array
     */
    public function get_field_props() {
        $defaults = $this->default_attributes();

        $props    = array(
            'input_type'        => 'qr_code',
            'template'          => 'qr_code',
            'required'          => 'no',
            'label'             => __( 'QR Code', 'wpuf-pro' ),
            'name'              => '',
            'is_meta'           => 'yes',
            'help'              => '',
            'css'               => '',
            'id'                => 0,
            'is_new'            => true,
            'show_in_post'      => 'yes',
            'hide_field_label'  => 'no',
            'qr_type'           => array( 'url', 'text' )
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
        $entry_value = array();

        return $entry_value;
    }
}

} else {

}
