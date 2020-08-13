<?php
/**
 * Address Field Class
 *
 * @since 3.1.0
 **/
class WPUF_Form_Field_Address extends WPUF_Field_Contract {

    function __construct() {
        $this->name       = __( 'Address Field', 'wpuf-pro');
        $this->input_type = 'address_field';
        $this->icon       = 'address-card-o';
    }

    /**
     * Render the Address field
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
                $value               = $this->get_meta( $post_id, $field_settings['name'], $type );
                $address_fields_meta = isset( $value ) ? $value : array();
            }
        } else {
            $value               = '';
            $address_fields_meta = array();
        }

        $country_select_hide_list = isset( $field_settings['address']['country_select']['country_select_hide_list'] ) ? $field_settings['address']['country_select']['country_select_hide_list'] : array();
        $country_select_show_list = isset( $field_settings['address']['country_select']['country_select_show_list'] ) ? $field_settings['address']['country_select']['country_select_show_list'] : array();
        $list_visibility_option   = $field_settings['address']['country_select']['country_list_visibility_opt_name'];
        $this->field_print_label( $field_settings, $form_id );

        ?>

        <div class="wpuf-fields <?php echo ' wpuf_'.$field_settings['name'].'_'.$form_id; ?>">
            <?php foreach( $field_settings['address'] as $each_field => $field_array ) {
                switch ( $each_field ) {
                    case 'street_address':
                        $autocomplete = 'street-address address-line1';
                    break;

                    case 'street_address2':
                        $autocomplete = 'street-address address-line2';
                    break;

                    case 'city_name':
                        $autocomplete = 'street-address address-level2';
                    break;

                    case 'state':
                        $autocomplete = 'street-address address-level1';
                    break;

                    case 'zip':
                        $autocomplete = 'postal-code';
                    break;

                    case 'country_select':
                        $autocomplete = 'country country-name';
                    break;

                    default:
                        $autocomplete = $each_field;
                    break;
                } ?>

                <div class="wpuf-address-field <?php echo $each_field; ?>">
                    <?php if ( isset( $field_array['checked'] ) && !empty( $field_array['checked'] ) ) { ?>

                        <div class="wpuf-sub-fields">
                            <?php if ( in_array( $field_array['type'], array( 'text', 'hidden', 'email', 'password') ) ) { ?>
                                <input
                                type="<?php echo $field_array['type']; ?>"
                                name="<?php  echo $field_settings['name'] . '[' . $each_field . ']'; ?>"
                                value="<?php echo isset( $address_fields_meta[$each_field] )? esc_attr($address_fields_meta[$each_field]):$field_array['value']; ?>"
                                placeholder="<?php echo $field_array['placeholder']?>"
                                class="textfield"
                                size="40"
                                autocomplete='<?php echo $autocomplete; ?>' <?php echo isset( $field_array['required'] ) && !empty( $field_array['required'] ) ? 'required' : ''; ?> />

                            <?php } elseif ( in_array($field_array['type'],array('textarea','select') ) ) {

                                echo '<'.$field_array['type'].' name="'. $field_settings['name'] . '[' . $each_field . ']' . '" autocomplete="' . $autocomplete . '" '.( isset( $field_array['required'] ) && !empty( $field_array['required'] ) ? 'required' : '').'>';
                                echo '</'.$field_array['type'].'>';

                                if ( $each_field == 'country_select' ) {
                                    $address_fields_meta['country_select'] = isset($address_fields_meta['country_select']) ? $address_fields_meta['country_select']:$field_array['value'];
                                    ?>
                                    <script>
                                        var field_name             = '<?php echo $field_settings['name'] . '[' . $each_field . ']' ; ?>';
                                        var countries              = <?php echo wpuf_get_countries( 'json' ); ?>;
                                        var banned_countries       = JSON.parse('<?php echo json_encode( $country_select_hide_list ) ?>');
                                        var allowed_countries      = JSON.parse('<?php echo json_encode( $country_select_show_list ); ?>');
                                        var list_visibility_option = '<?php echo $list_visibility_option; ?>';
                                        var option_string          = '<option value=""><?php _e( "Select Country", "wpuf-pro" ); ?></option>';
                                        var sel_country            = '<?php echo isset($address_fields_meta['country_select'])?$address_fields_meta['country_select']:''; ?>';

                                        if ( list_visibility_option == 'hide' ) {
                                            for (country in countries){
                                                if ( jQuery.inArray(countries[country].code,banned_countries) != -1 ){
                                                    continue;
                                                }
                                                option_string = option_string + '<option value="'+ countries[country].code +'" ' + ( sel_country == countries[country].code ? 'selected':'' ) + ' >'+ countries[country].name +'</option>';
                                            }
                                        } else if( list_visibility_option == 'show' ) {
                                            for (country in countries){
                                                if ( jQuery.inArray(countries[country].code,allowed_countries) != -1 ) {
                                                    option_string = option_string + '<option value="'+ countries[country].code +'" ' + ( sel_country == countries[country].code ? 'selected':'' ) + ' >'+ countries[country].name +'</option>';
                                                }
                                            }
                                        } else {
                                            for (country in countries){
                                                option_string = option_string + '<option value="'+ countries[country].code +'" ' + ( sel_country == countries[country].code ? 'selected':'' ) + ' >'+ countries[country].name +'</option>';
                                            }
                                        }

                                        jQuery('select[name="'+ field_name +'"]').html(option_string);
                                    </script>
                                <?php }
                            } ?>
                        </div>

                        <label class="wpuf-form-sub-label">
                            <?php echo $field_array['label']; ?>
                            <span class="required"><?php echo ( isset( $field_array['required'] ) && !empty($field_array['required']) ) ? '*' : ''; ?></span>
                        </label>
                    <?php } ?>
                </div>

            <?php } ?>

            <div style="clear: both"><?php $this->help_text( $field_settings ); ?></div>
        </div>

        <?php $this->after_field_print_label();
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
        $default_options = $this->get_default_option_settings();

        $settings = array(
            array(
                'name'          => 'address',
                'title'         => __( 'Address Fields', 'wpuf-pro' ),
                'type'          => 'address',
                'section'       => 'advanced',
                'priority'      => 21,
                'help_text'     => '',
            )
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
            'input_type'        => 'address',
            'address_desc'  => '',
            'address'       => array(
                'street_address'    => array(
                    'checked'       => 'checked',
                    'type'          => 'text',
                    'required'      => 'checked',
                    'label'         => __( 'Address Line 1', 'wpuf-pro' ),
                    'value'         => '',
                    'placeholder'   => ''
                ),

                'street_address2'   => array(
                    'checked'       => 'checked',
                    'type'          => 'text',
                    'required'      => '',
                    'label'         => __( 'Address Line 2', 'wpuf-pro' ),
                    'value'         => '',
                    'placeholder'   => ''
                ),

                'city_name'         => array(
                    'checked'       => 'checked',
                    'type'          => 'text',
                    'required'      => 'checked',
                    'label'         => __( 'City', 'wpuf-pro' ),
                    'value'         => '',
                    'placeholder'   => ''
                ),

                'state'             => array(
                    'checked'       => 'checked',
                    'type'          => 'text',
                    'required'      => 'checked',
                    'label'         => __( 'State', 'wpuf-pro' ),
                    'value'         => '',
                    'placeholder'   => ''
                ),

                'zip'               => array(
                    'checked'       => 'checked',
                    'type'          => 'text',
                    'required'      => 'checked',
                    'label'         => __( 'Zip Code', 'wpuf-pro' ),
                    'value'         => '',
                    'placeholder'   => ''
                ),

                'country_select'    => array(
                    'checked'                           => 'checked',
                    'type'                              => 'select',
                    'required'                          => 'checked',
                    'label'                             => __( 'Country', 'wpuf-pro' ),
                    'value'                             => '',
                    'country_list_visibility_opt_name'  => 'all',
                    'country_select_hide_list'          => array(),
                    'country_select_show_list'          => array()
                )
            ),
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
        $entry_value = array();

        if ( isset( $_POST[ $field['name'] ] ) && is_array( $_POST[ $field['name'] ] ) ) {
            foreach ( $_POST[ $field['name'] ] as $address_field => $field_value ) {
                $entry_value[ $address_field ] = sanitize_text_field( $field_value );
            }
        }

        return $entry_value;
    }
}
