<?php
/**
 * File Field Class
 *
 * @since 3.1.0
 **/
class WPUF_Form_Field_File extends WPUF_Field_Contract {

    function __construct() {
        $this->name       = __( 'File Upload', 'wpuf-pro');
        $this->input_type = 'file_upload';
        $this->icon       = 'upload';
    }

    /**
     * Render the File field
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
        $allowed_ext = '';
        $extensions  = wpuf_allowed_extensions();
        $unique_id   = sprintf( '%s-%d', $field_settings['name'], $form_id );

        if ( is_array( $field_settings['extension'] ) ) {
            foreach ($field_settings['extension'] as $ext) {
                $allowed_ext .= $extensions[$ext]['ext'] . ',';
            }
        } else {
            $allowed_ext = '*';
        }

        if( isset( $post_id ) &&  $post_id != '0' ) {
            $uploaded_items = $post_id ? $this->get_meta( $post_id, $field_settings['name'], $type, false ) : array();

            if ( $uploaded_items ) {
                if( is_serialized( $uploaded_items[0] ) ) {
                    $uploaded_items = maybe_unserialize( $uploaded_items[0] );
                }

                if ( is_array( $uploaded_items[0] ) ) {
                    $uploaded_items = $uploaded_items[0];
                }
            }
        } else {
            $uploaded_items = array();
        }

        $this->field_print_label( $field_settings, $form_id );

        ?>

        <div class="wpuf-fields">
            <div id="wpuf-<?php echo $unique_id; ?>-upload-container">
                <div class="wpuf-attachment-upload-filelist" data-type="file" data-required="<?php echo $field_settings['required']; ?>">
                    <a id="wpuf-<?php echo $unique_id; ?>-pickfiles" data-form_id="<?php echo $form_id; ?>" class="button file-selector <?php echo ' wpuf_'.$field_settings['name'].'_'.$form_id; ?>" href="#"><?php _e( 'Select File(s)', 'wpuf-pro' ); ?></a>

                    <ul class="wpuf-attachment-list thumbnails">

                        <?php
                            if ( $uploaded_items ) {
                                foreach ($uploaded_items as $attach_id) {
                                    echo WPUF_Upload::attach_html( $attach_id, $field_settings['name'] );
                                    if ( is_admin() ) {
                                        printf( '<a href="%s">%s</a>', wp_get_attachment_url( $attach_id ), __( 'Download File', 'wpuf-pro' ) );
                                    }
                                }
                            }
                        ?>

                    </ul>
                </div>
            </div><!-- .container -->

            <?php $this->help_text( $field_settings ); ?>

        </div> <!-- .wpuf-fields -->

        <script type="text/javascript">
            jQuery(function($) {
                var uploader = new WPUF_Uploader('wpuf-<?php echo $unique_id; ?>-pickfiles', 'wpuf-<?php echo $unique_id; ?>-upload-container', <?php echo $field_settings['count']; ?>, '<?php echo $field_settings['name']; ?>', '<?php echo $allowed_ext; ?>', <?php echo $field_settings['max_size'] ?>);
                wpuf_plupload_items.push(uploader);
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
        $default_options      = $this->get_default_option_settings( true, array('dynamic') );

        $settings = array(
            array(
                'name'          => 'max_size',
                'title'         => __( 'Max. file size', 'wpuf-pro' ),
                'type'          => 'text',
                'variation'     => 'number',
                'section'       => 'advanced',
                'priority'      => 20,
                'help_text'     => __( 'Enter maximum upload size limit in KB', 'wpuf-pro' ),
            ),

            array(
                'name'          => 'count',
                'title'         => __( 'Max. files', 'wpuf-pro' ),
                'type'          => 'text',
                'variation'     => 'number',
                'section'       => 'advanced',
                'priority'      => 21,
                'help_text'     => __( 'Number of images can be uploaded', 'wpuf-pro' ),
            ),

            array(
                'name'          => 'extension',
                'title'         => __( 'Allowed Files', 'wpuf-pro' ),
                'title_class'   => 'label-hr',
                'type'          => 'checkbox',
                'options'       => array(
                    'images'    => __( 'Images (jpg, jpeg, gif, png, bmp)', 'wpuf-pro' ),
                    'audio'     => __( 'Audio (mp3, wav, ogg, wma, mka, m4a, ra, mid, midi)', 'wpuf-pro' ),
                    'video'     => __( 'Videos (avi, divx, flv, mov, ogv, mkv, mp4, m4v, divx, mpg, mpeg, mpe)', 'wpuf-pro' ),
                    'pdf'       => __( 'PDF (pdf)', 'wpuf-pro' ),
                    'office'    => __( 'Office Documents (doc, ppt, pps, xls, mdb, docx, xlsx, pptx, odt, odp, ods, odg, odc, odb, odf, rtf, txt)', 'weforms-pro' ),
                    'zip'       => __( 'Zip Archives (zip, gz, gzip, rar, 7z)', 'wpuf-pro' ),
                    'exe'       => __( 'Executable Files (exe)', 'wpuf-pro' ),
                    'csv'       => __( 'CSV (csv)', 'wpuf-pro' ),
                ),
                'section'       => 'advanced',
                'priority'      => 22,
                'help_text'     => '',
            ),

            array(
                'name'      => 'playable_audio_video',
                'title'     => __( 'Make Audio/Video files playable', 'wpuf-pro' ),
                'type'      => 'radio',
                'options'   => array(
                    'yes'   => __( 'Yes', 'wpuf-pro' ),
                    'no'    => __( 'No', 'wpuf-pro' ),
                ),
                'section'   => 'advanced',
                'priority'  => 22,
                'default'   => 'no',
                'inline'    => true,
                'help_text' => __( 'Make uploaded Audio/Video files playable in post', 'wpuf-pro' ),
            ),

            array(
                'name'          => 'preview_width',
                'title'         => __( 'Preview Width', 'wpuf-pro' ),
                'type'          => 'text',
                'section'       => 'advanced',
                'priority'      => 22,
                'help_text'     => __( 'Height in px (e.g: 123) for video player, only applicable if you make audio/video files playable.', 'wpuf-pro' ),
            ),

            array(
                'name'          => 'preview_height',
                'title'         => __( 'Preview Height', 'wpuf-pro' ),
                'type'          => 'text',
                'section'       => 'advanced',
                'priority'      => 22,
                'help_text'     => __( 'Height in px (e.g: 456) for video player, only applicable if you make audio/video files playable.', 'wpuf-pro' ),
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
            'input_type'                => 'file_upload',
            'is_meta'                   => 'yes',
            'max_size'                  => '1024',
            'count'                     => '1',
            'extension'                 => array( 'images', 'audio', 'video', 'pdf', 'office', 'zip', 'exe', 'csv' ),
            'playable_audio_video'      => 'no',
            'preview_width'             => '123',
            'preview_height'            => '456',
            'id'                        => 0,
            'is_new'                    => true,
            'show_in_post'              => 'yes',
            'hide_field_label'          => 'no',
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
       return isset( $_POST['wpuf_files'][$field['name']] ) ? $_POST['wpuf_files'][$field['name']] : array();
    }
}
