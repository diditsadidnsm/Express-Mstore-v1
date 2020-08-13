<?php
/**
 * Country Field Class
 *
 * @since 3.1.0
 **/
class WPUF_Form_Field_Country extends WPUF_Field_Contract {

    function __construct() {
        $this->name       = __( 'Country List', 'wpuf-pro');
        $this->input_type = 'country_list_field';
        $this->icon       = 'globe';
    }

    /**
     * Render the Country field
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
        if ( isset( $post_id ) && $post_id != 0) {
            if ( $this->is_meta( $field_settings ) ) {
                $value = $this->get_meta( $post_id, $field_settings['name'], $type );
            }
        } else {
            $value = isset( $field_settings['country_list']['name'] ) ? $field_settings['country_list']['name'] : '';
        }

        $list_visibility_option   = $field_settings['country_list']['country_list_visibility_opt_name'];
        $country_select_hide_list = isset( $field_settings['country_list']['country_select_hide_list'] ) && is_array( $field_settings['country_list']['country_select_hide_list'] )? $field_settings['country_list']['country_select_hide_list']:array();
        $country_select_show_list = isset( $field_settings['country_list']['country_select_show_list'] ) && is_array( $field_settings['country_list']['country_select_show_list'] )? $field_settings['country_list']['country_select_show_list']:array();

        $this->field_print_label( $field_settings, $form_id );

        ?>

        <div class="wpuf-fields">
            <select name="<?php echo $field_settings['name']; ?>">

            </select>

            <script>
                var field_name             = '<?php echo $field_settings['name'];?>';
                var countries              = <?php echo wpuf_get_countries( 'json' ); ?>;
                console.log(countries);
                var banned_countries       = JSON.parse('<?php echo json_encode($country_select_hide_list); ?>');
                var allowed_countries      = JSON.parse('<?php echo json_encode($country_select_show_list); ?>');
                var list_visibility_option = '<?php echo $list_visibility_option; ?>';
                var sel_country            = '<?php echo !empty( $value ) ? $value : '' ; ?>';
                var option_string          = '<option value=""><?php _e( "Select Country", "wpuf-pro" ); ?></option>';
                if( list_visibility_option == 'hide' ) {
                    for(country in countries){
                        if( jQuery.inArray(countries[country].code,banned_countries) != -1 ){
                            continue;
                        }
                        option_string = option_string + '<option value="'+ countries[country].code +'" ' + ( sel_country == countries[country].code ? 'selected':'' ) + ' >'+ countries[country].name +'</option>';
                    }
                }else if ( list_visibility_option == 'show' ) {
                    for(country in countries){
                        if( jQuery.inArray(countries[country].code,allowed_countries) != -1 ){
                            option_string = option_string + '<option value="'+ countries[country].code +'" ' + ( sel_country == countries[country].code ? 'selected':'' ) + ' >'+ countries[country].name +'</option>';
                        }
                    }
                }else {
                    for (country in countries) {
                        option_string = option_string + '<option value="'+ countries[country].code +'" ' + ( sel_country == countries[country].code ? 'selected':'' ) + ' >'+ countries[country].name +'</option>';
                    }
                }
                jQuery('select[name="'+ field_name +'"]').html(option_string);
            </script>

        </div>

        <?php $this->after_field_print_label();
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
                'name'          => 'country_list',
                'title'         => '',
                'type'          => 'country-list',
                'section'       => 'advanced',
                'priority'      => 22,
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
            'input_type'        => 'country_list',
            'country_list'  => array(
                'name'                              => '',
                'country_list_visibility_opt_name'  => 'all', // all, hide, show
                'country_select_show_list'          => array(),
                'country_select_hide_list'          => array()
            ),
            'show_in_post'      => 'yes',
            'hide_field_label'  => 'no',
            'is_meta'           => 'yes',
            'id'                => 0,
            'is_new'            => true,
        );

        return array_merge( $defaults, $props );
    }
}
