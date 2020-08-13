<?php
/**
 * Date Field Class
 *
 * @since 3.1.0
 **/
class WPUF_Form_Field_Date extends WPUF_Field_Contract {

    function __construct() {
        $this->name       = __( 'Date / Time', 'wpuf-pro' );
        $this->input_type = 'date_field';
        $this->icon       = 'calendar-o';
    }

    /**
     * Render the Date field
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
            if ( isset( $field_settings['default'] ) && !empty( $field_settings['default'] ) ) {
                $value = $field_settings['default'];
            } else {
                $value = '';
            }
        }

        $this->field_print_label( $field_settings, $form_id );
        // if date field is assigned as publish date
        if ( isset ( $field_settings['is_publish_time'] ) && $field_settings['is_publish_time'] == 'yes' ) {
            ?>
            <input type="hidden" name="wpuf_is_publish_time" value="<?php echo $field_settings['name']; ?>" />
            <?php
        }
        ?>

        <div class="wpuf-fields">
            <input id="wpuf-date-<?php echo $field_settings['name']; ?>" type="text" autocomplete="off" class="datepicker <?php echo ' wpuf_'.$field_settings['name'].'_'.$form_id; ?>" data-required="<?php echo $field_settings['required'] ?>" data-type="text" name="<?php echo esc_attr( $field_settings['name'] ); ?>" placeholder="<?php echo esc_attr( $field_settings['format'] ); ?>" value="<?php echo esc_attr( $value ) ?>" size="30" />
            <?php $this->help_text( $field_settings ); ?>
        </div>
        <script type="text/javascript">
            jQuery(function($) {
            <?php
                if ( !empty( $field_settings['mintime'] ) || !empty( $field_settings['maxtime'] ) ) {
                    $maxtime = !empty( $field_settings['maxtime'] ) ?  $field_settings['maxtime'] : '';
                    $mintime = !empty( $field_settings['mintime'] ) ?  $field_settings['mintime'] : '';
                    $maxtime = str_replace("/","-",$field_settings['maxtime']);
                    $mintime = str_replace("/","-",$field_settings['mintime']);
            ?>
                    var mindate = "<?php echo $mintime;?>";
                    var maxdate = "<?php echo $maxtime;?>";
                    var partsa =mindate.split('-');
                    var mindate = new Date(partsa[2], partsa[1] - 1, partsa[0]);
                    var parts =maxdate.split('-');
                    var mydateb = new Date(parts[2], parts[1] - 1, parts[0]);
                    var dateObj = {
                        dateFormat: ' <?php echo $field_settings["format"] ?> ',
                        minDate: mindate,
                        maxDate: mydateb,
                    };

                    <?php
                        if ( isset( $field_settings['time'] ) &&  $field_settings['time'] == 'yes' ) {
                        ?>
                            $("#wpuf-date-<?php echo $field_settings['name']; ?>").datetimepicker(dateObj);
                        <?php } else { ?>
                            $("#wpuf-date-<?php echo $field_settings['name']; ?>").datepicker(dateObj);
                        <?php }
                } else {
                        if ( isset( $field_settings['time'] ) && $field_settings['time'] == 'yes' ) { ?>
                            $("#wpuf-date-<?php echo $field_settings['name']; ?>").datetimepicker({ dateFormat: '<?php echo $field_settings["format"]; ?>' });
                            <?php } else { ?>
                            $("#wpuf-date-<?php echo $field_settings['name']; ?>").datepicker({ dateFormat: '<?php echo $field_settings["format"]; ?>' });
                    <?php }
                }   ?>
            });
        </script>

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
                'name'      => 'format',
                'title'     => __( 'Date Format', 'wpuf-pro' ),
                'type'      => 'text',
                'section'   => 'advanced',
                'priority'  => 23,
                'help_text' => __( 'The date format', 'wpuf-pro' ),
            ),

            array(
                'name'          => 'time',
                'title'         => '',
                'type'          => 'checkbox',
                'is_single_opt' => true,
                'options'       => array(
                    'yes'   => __( 'Enable time input', 'wpuf-pro' )
                ),
                'section'       => 'advanced',
                'priority'      => 24,
                'help_text'     => '',
            ),

            array(
                'name'          => 'mintime',
                'title'         => __( 'Enter minDate in number', 'wpuf-pro' ),
                'type'          => 'text',
                'section'       => 'advanced',
                'priority'      => 24,
                'help_text'     => '',
            ),

            array(
                'name'          => 'maxtime',
                'title'         => __( 'Enter maxDate in number', 'wpuf-pro' ),
                'type'          => 'text',
                'section'       => 'advanced',
                'priority'      => 24,
                'help_text'     => '',
            ),

            array(
                'name'          => 'is_publish_time',
                'title'         => '',
                'type'          => 'checkbox',
                'is_single_opt' => true,
                'options'       => array(
                    'yes'   => __( 'Set this as publish time input', 'wpuf-pro' )
                ),
                'section'       => 'advanced',
                'priority'      => 24,
                'help_text'     => '',
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
            'input_type'        => 'date',
            'format'            => 'dd/mm/yy',
            'is_meta'           => 'yes',
            'width'             => 'large',
            'format'            => 'dd/mm/yy',
            'id'                => 0,
            'is_new'            => true,
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
