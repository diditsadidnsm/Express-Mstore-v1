<?php
/**
 * Rating Field Class
 *
 * @since 3.1.0
 **/
class WPUF_Form_Field_Rating extends WPUF_Form_Field_Dropdown {

    function __construct() {
        $this->name       = __( 'Ratings', 'wpuf-pro' );
        $this->input_type = 'ratings';
        $this->icon       = 'star-half-o';
    }

    /**
     * Render the Rating field
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
            $value = '';
        }

        $this->field_print_label( $field_settings, $form_id );

        ?>

        <div class="wpuf-fields <?php echo ' wpuf_'.$field_settings['name'].'_'.$form_id; ?>">
            <select name="<?php echo $field_settings['name']; ?>" class="wpuf-ratings">
                <?php foreach( $field_settings['options'] as $key => $option ) : ?>
                    <option value="<?php echo $key; ?>" <?php  echo $key == $value ? 'selected' : '' ; ?> ><?php echo $option; ?></option>
                <?php endforeach; ?>
            </select>

            <?php $this->help_text( $field_settings ); ?>
        </div>

        <script type="text/javascript">
            jQuery(function($) {
                $('.wpuf-ratings').barrating({
                    theme: 'css-stars'
                });
            });
        </script>

       <?php  if ( empty($post_id) ) { ?>
        <script type="text/javascript">
            jQuery(function($) {
                $(document).ready(function() {
                    $(".br-widget a").removeClass('br-selected');
                });
            });
        </script>
        <?php }
        $this->after_field_print_label();
    }

    /**
     * Get the field props
     *
     * @return array
     */
    public function get_field_props() {
        $defaults = $this->default_attributes();

        $props    = array(
            'input_type' => 'ratings',
            'selected'   => '',
            'options'  => array(
                '1' => __( '1', 'wpuf' ),
                '2' => __( '2', 'wpuf' ),
                '3' => __( '3', 'wpuf' ),
                '4' => __( '4', 'wpuf' ),
                '5' => __( '5', 'wpuf' )
            ),
            'is_meta'          => 'yes',
            'selected'         => array(),
            'inline'           => 'no',
            'id'               => 0,
            'is_new'           => true,
            'show_in_post'     => 'yes',
            'hide_field_label' => 'no',
        );

        return array_merge( $defaults, $props );
    }

    /**
     * Get field options setting
     *
     * @return array
     */
    public function get_options_settings() {
        $default_options          = $this->get_default_option_settings( true);
        $default_dropdown_options = array( $this->get_default_option_dropdown_settings() );

        return array_merge( $default_options, $default_dropdown_options );
    }

}
