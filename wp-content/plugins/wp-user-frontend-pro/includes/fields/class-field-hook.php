<?php
/**
 * Hook Field Class
 *
 * @since 3.1.0
 **/
class WPUF_Form_Field_Hook extends WPUF_Field_Contract {

    function __construct() {
        $this->name       = __( 'Action Hook', 'wpuf-pro' );
        $this->input_type = 'action_hook';
        $this->icon       = 'anchor';
    }

    /**
     * Render the Action Hook field
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
        if ( !empty( $field_settings['label'] ) ) {
            do_action( $field_settings['label'], $form_id, $post_id, $field_settings );
        }
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
                'name'          => 'label',
                'title'         => __( 'Hook Name', 'wpuf-pro' ),
                'type'          => 'text',
                'section'       => 'basic',
                'priority'      => 10,
                'help_text'     => __( 'Name of the hook', 'wpuf-pro' ),
            ),

            array(
                'name'          => 'help_text',
                'title'         => '',
                'type'          => 'html_help_text',
                'section'       => 'basic',
                'priority'      => 11,
                'text'          => sprintf( __( 'An option for developers to add dynamic elements they want. It provides the chance to add whatever input type you want to add in this form. This way, you can bind your own functions to render the form to this action hook. You\'ll be given 3 parameters to play with: $form_id, $post_id, $form_settings.', 'wpuf-pro' ) )
                                   . '<pre>add_action(\'HOOK_NAME\', \'your_function_name\', 10, 3 );<br>'
                                   . 'function your_function_name( $form_id, $post_id, $form_settings ) {<br>'
                                   . '    // do what ever you want<br>'
                                   . '}</pre>',
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
            'input_type'        => 'action_hook',
            'template'          => $this->get_type(),
            'label'             => 'YOUR_CUSTOM_HOOK_NAME',
            'id'                => 0,
            'is_new'            => true,
            'is_meta'           => "yes",
            'wpuf_cond'         => null
        );

        return $props;
    }
}
