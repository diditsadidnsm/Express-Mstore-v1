<?php
/**
 * Step Field Class
 *
 * @since 3.1.0
 **/
class WPUF_Form_Field_Step extends WPUF_Form_Field_Text {

    function __construct() {
        $this->name       = __( 'Step Start', 'wpuf-pro' );
        $this->input_type = 'step_start';
        $this->icon       = 'step-forward';
    }

    /**
     * Render the Step field
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
        static $step_started = false;
        if ( $step_started ) { ?>
            </fieldset>
        <?php } ?>

        <fieldset class="wpuf-multistep-fieldset">
            <legend>
                <?php echo $field_settings['label'];?>
            </legend>
            <button class="wpuf-multistep-prev-btn btn btn-primary"><?php echo $field_settings['step_start']['prev_button_text']; ?></button>
            <button class="wpuf-multistep-next-btn btn btn-primary"><?php echo $field_settings['step_start']['next_button_text']; ?></button>

        <?php
        if ( ! $step_started ) {
            $step_started = true;
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
        return $settings = array(
            array(
                'name'          => 'step_start',
                'title'         => '',
                'type'          => 'step-start',
                'section'       => 'basic',
                'priority'      => 10,
                'help_text'     => '',
            )
        );
    }

    /**
     * Get the field props
     *
     * @return array
     */
    public function get_field_props() {
        $props = array(
            'input_type' => 'step_start',
            'is_meta'    => 'no',
            'template'   => $this->get_type(),
            'label'      => $this->get_name(),
            'id'         => 0,
            'is_new'     => true,
            'step_start' => array(
                'prev_button_text' => __( 'Previous', 'wpuf-pro' ),
                'next_button_text' => __( 'Next', 'wpuf-pro' )
            )
        );

        return $props;
    }

}
