<?php
/**
 * Avater Field Class
 *
 * @since 3.1.0
 **/
class WPUF_Form_Field_Avater extends WPUF_Field_Contract {

	function __construct() {
        $this->name       = __( 'Avatar', 'wpuf-pro' );
        $this->input_type = 'avatar';
        $this->icon       = 'file-image-o';
    }

    /**
     * Render the Avatar field
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
        $has_avatar = false;

        if( isset( $post_id ) && $post_id != 0 ){
            $has_avatar     = true;
            $featured_image = get_avatar( $post_id );
        }

        $unique_id = sprintf( '%s-%d', $field_settings['name'], $form_id );

        ?>
        <li <?php $this->print_list_attributes( $field_settings ); ?>>
            <?php $this->print_label( $field_settings, $form_id ); ?>

            <div class="wpuf-fields">
                <div id="wpuf-<?php echo $unique_id; ?>-upload-container">
                    <div class="wpuf-attachment-upload-filelist" data-type="file" data-required="<?php echo $field_settings['required']; ?>">
                        <a id="wpuf-<?php echo $unique_id; ?>-pickfiles" data-form_id="<?php echo $form_id; ?>" class="button file-selector <?php echo ' wpuf_' . $field_settings['name'] . '_' . $form_id; ?>" href="#"><?php echo $field_settings['button_label']; ?></a>

                        <ul class="wpuf-attachment-list thumbnails">
                            <?php
                                if ( $has_avatar ) {
                                    $avatar = get_user_meta( $post_id, 'user_avatar', true );
                                    if ( $avatar ) {
                                        echo '<li>'.$featured_image;
                                        printf( '<br><a href="#" data-confirm="%s" class="btn btn-danger btn-small wpuf-button button wpuf-delete-avatar">%s</a>', __( 'Are you sure?', 'wpuf-pro' ), __( 'Delete', 'wpuf-pro' ) );
                                        echo '</li>';
                                    }
                                }
                            ?>
                        </ul>
                    </div>
                </div><!-- .container -->

                <?php $this->help_text( $field_settings ); ?>

            </div> <!-- .wpuf-fields -->

            <script type="text/javascript">
                ;(function($) {
                    $(document).ready( function(){
                        var uploader = new WPUF_Uploader('wpuf-<?php echo $unique_id; ?>-pickfiles', 'wpuf-<?php echo $unique_id; ?>-upload-container', <?php echo $field_settings['count']; ?>, '<?php echo $field_settings['name']; ?>', 'jpg,jpeg,gif,png,bmp', <?php echo $field_settings['max_size'] ?>);
                        wpuf_plupload_items.push(uploader);
                    });
                })(jQuery);
            </script>

        </li>

        <?php
    }

   /**
    * field helper function
    *
    * @return void
    **/
    public function print_list_attributes( $field ) {
        $label      = isset( $field['label'] ) ? $field['label'] : '';
        $el_name    = !empty( $field['name'] ) ? $field['name'] : '';
        $el_name = 'wpuf_' . $el_name;
        $class_name = !empty( $field['css'] ) ? ' ' . $field['css'] : '';
        $field_size = !empty( $field['width'] ) ? ' field-size-' . $field['width'] : '';

        printf( 'class="wpuf-el %s%s%s" data-label="%s"', $el_name, $class_name, $field_size, $label );
    }

   /**
    * Get field options setting
    *
    * @return array
    **/
    public function get_options_settings() {
        $default_options = $this->get_default_option_settings( false, array('dynamic') );

        $settings = array(
            array(
                'name'          => 'button_label',
                'title'         => __( 'Button Label', 'wpuf-pro' ),
                'type'          => 'text',
                'default'       => __( 'Select Image', 'wpuf-pro' ),
                'section'       => 'basic',
                'priority'      => 20,
                'help_text'     => __( 'Enter a label for the Avatar button', 'wpuf-pro' ),
            ),
            array(
                'name'          => 'max_size',
                'title'         => __( 'Max. file size', 'wpuf-pro' ),
                'type'          => 'text',
                'section'       => 'advanced',
                'priority'      => 20,
                'help_text'     => __( 'Enter maximum upload size limit in KB', 'wpuf-pro' ),
            ),

            array(
                'name'          => 'count',
                'title'         => __( 'Max. files', 'wpuf-pro' ),
                'type'          => 'text',
                'section'       => 'advanced',
                'priority'      => 21,
                'help_text'     => __( 'Number of images can be uploaded', 'wpuf-pro' ),
            ),
        );

        return array_merge( $default_options, $settings );
    }

    /**
     * Get the field props
     *
     * @return array
     **/
    public function get_field_props() {
        $defaults = $this->default_attributes();

        $props    = array(
            'input_type'    => 'image_upload',
            'required'      => 'yes',
            'button_label'  => __( 'Select Image', 'wpuf-pro' ),
            'name'          => 'avatar',
            'is_meta'       => 'no',
            'help'          => '',
            'css'           => '',
            'max_size'      => '1024',
            'count'         => '1',
            'id'            => 0,
            'is_new'        => true,
        );

        return array_merge( $defaults, $props );
    }

    /**
     * Prepare entry
     *
     * @param $field
     *
     * @return void
     **/
    public function prepare_entry( $field ) {
       return isset( $_POST['wpuf_files'][$field['name']] ) ? $_POST['wpuf_files'][$field['name']] : array();
    }

}
