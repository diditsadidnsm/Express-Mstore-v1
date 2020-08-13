<?php

/**
 * Easy Digital Downloads post form template
 */
class WPUF_Post_Form_Template_EDD extends WPUF_Post_Form_Template {

    public function __construct() {
        $this->enabled     = class_exists( 'Easy_Digital_Downloads' );
        $this->title       = __( 'EDD Download', 'wpuf-pro' );
        $this->description = __( 'Create a simple or download for Easy Digital Downloads.', 'wpuf-pro' );
        $this->image       = WPUF_PRO_ASSET_URI . '/images/templates/edd.png';
        $this->form_fields = array(
            array(
                'input_type'      => 'text',
                'template'        => 'post_title',
                'required'        => 'yes',
                'label'           => 'Download Name',
                'name'            => 'post_title',
                'is_meta'         => 'no',
                'help'            => '',
                'css'             => '',
                'placeholder'     => 'Please enter your product name',
                'default'         => '',
                'size'            => '40',
                'wpuf_cond'       => $this->conditionals,
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type'      => 'taxonomy',
                'template'        => 'taxonomy',
                'required'        => 'yes',
                'label'           => 'Download Categories',
                'name'            => 'download_category',
                'is_meta'         => 'no',
                'help'            => 'Select a category for your download',
                'first'           => __( '- select -', 'wp-user-frontend' ),
                'css'             => '',
                'type'            => 'select',
                'orderby'         => 'name',
                'order'           => 'ASC',
                'exclude_type'    => 'exclude',
                'exclude'         => array(),
                'woo_attr'        => 'no',
                'woo_attr_vis'    => 'no',
                'options'         => array(),
                'wpuf_cond'       => $this->conditionals,
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type'       => 'textarea',
                'template'         => 'post_content',
                'required'         => 'yes',
                'label'            => 'Download Description',
                'name'             => 'post_content',
                'is_meta'          => 'no',
                'help'             => 'Write the full description of your download',
                'css'              => '',
                'rows'             => '5',
                'cols'             => '25',
                'placeholder'      => '',
                'default'          => '',
                'rich'             => 'yes',
                'insert_image'     => 'yes',
                'word_restriction' => '',
                'wpuf_cond'        => $this->conditionals,
                'wpuf_visibility'  => $this->get_default_visibility_prop()
            ),
            array(
                'input_type'      => 'textarea',
                'template'        => 'post_excerpt',
                'required'        => 'no',
                'label'           => 'Download Short Description',
                'name'            => 'post_excerpt',
                'is_meta'         => 'no',
                'help'            => 'Provide a short description of your download',
                'css'             => '',
                'rows'            => '5',
                'cols'            => '25',
                'placeholder'     => '',
                'default'         => '',
                'rich'            => 'no',
                'wpuf_cond'       => $this->conditionals,
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type'      => 'numeric_text',
                'template'        => 'numeric_text_field',
                'required'        => 'yes',
                'label'           => 'Regular Price',
                'name'            => 'edd_price',
                'is_meta'         => 'yes',
                'help'            => '',
                'css'             => '',
                'placeholder'     => 'Regular price of your download',
                'default'         => '',
                'size'            => '40',
                'step_text_field' => '0.01',
                'min_value_field' => '0',
                'max_value_field' => '',
                'wpuf_cond'       => $this->conditionals,
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type'      => 'image_upload',
                'template'        => 'featured_image',
                'count'           => '1',
                'required'        => 'yes',
                'label'           => 'Download Image',
                'name'            => 'featured_image',
                'button_label'    => __( 'Select Image', 'wpuf-pro' ),
                'is_meta'         => 'no',
                'help'            => 'Upload the main image of your download',
                'css'             => '',
                'max_size'        => '10240',
                'wpuf_cond'       => $this->conditionals,
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type'       => 'textarea',
                'template'         => 'textarea_field',
                'required'         => 'no',
                'label'            => 'Product Notes',
                'name'             => 'edd_product_notes',
                'is_meta'          => 'yes',
                'help'             => 'Add a product note',
                'css'              => '',
                'rows'             => '5',
                'cols'             => '25',
                'placeholder'      => '',
                'default'          => '',
                'rich'             => 'no',
                'word_restriction' => '',
                'wpuf_cond'        => $this->conditionals,
                'wpuf_visibility'  => $this->get_default_visibility_prop()
            ),
            array(
                'input_type'      => 'file_upload',
                'template'        => 'file_upload',
                'required'        => 'yes',
                'label'           => 'Downloadable Files',
                'name'            => 'edd_download_files',
                'is_meta'         => 'yes',
                'help'            => 'Chose your downloadable files',
                'css'             => '',
                'max_size'        => '1024',
                'count'           => '5',
                'extension'       => array('images','audio','video','pdf','office','zip','exe','csv'),
                'wpuf_cond'       => $this->conditionals,
                'wpuf_visibility' => $this->get_default_visibility_prop()
            )
        );

        $this->form_settings = array (
                'post_type'                  => 'download',
                'post_status'                => 'publish',
                'default_cat'                => '-1',
                'guest_post'                 => 'false',
                'message_restrict'           => 'This page is restricted. Please Log in / Register to view this page.',
                'redirect_to'                => 'post',
                'comment_status'             => 'open',
                'submit_text'                => 'Create Download',
                'submit_button_cond'  => array(
                    'condition_status' => 'no',
                    'cond_logic'       => 'any',
                    'conditions'       => array(
                        array(
                            'name'             => '',
                            'operator'         => '=',
                            'option'           => ''
                        )
                    )
                ),
                'edit_post_status'           => 'publish',
                'edit_redirect_to'           => 'same',
                'update_message'             => 'Download has been updated successfully. <a target="_blank" href="%link%">View Download</a>',
                'edit_url'                   => '',
                'update_text'                => 'Update Download',
                'form_template'              => __CLASS__,
                'notification'               => array(
                'new'                        => 'on',
                'new_to'                     => get_option( 'admin_email' ),
                'new_subject'                => 'New download has been created',
                'new_body'                   => 'Hi,
                A new download has been created in your site %sitename% (%siteurl%).

                Here is the details:
                Download Title: %post_title%
                Description: %post_content%
                Short Description: %post_excerpt%
                Author: %author%
                Post URL: %permalink%
                Edit URL: %editlink%',
                'edit'                       => 'off',
                'edit_to'                    => get_option( 'admin_email' ),
                'edit_subject'               => 'Download has been edited',
                'edit_body'                  => 'Hi,
                The download "%post_title%" has been updated.

                Here is the details:
                Download Title: %post_title%
                Description: %post_content%
                Short Description: %post_excerpt%
                Author: %author%
                Post URL: %permalink%
                Edit URL: %editlink%',
            ),
        );
    }

    /**
     * Run necessary processing after new post insert
     *
     * @param  int   $post_id
     * @param  int   $form_id
     * @param  array $form_settings
     *
     * @return void
     */
    public function after_insert( $post_id, $form_id, $form_settings ) {
        $this->handle_form_updates( $post_id, $form_id, $form_settings );
    }

    /**
     * Run necessary processing after editing a post
     *
     * @param  int   $post_id
     * @param  int   $form_id
     * @param  array $form_settings
     *
     * @return void
     */
    public function after_update( $post_id, $form_id, $form_settings ) {
        $this->handle_form_updates( $post_id, $form_id, $form_settings );
    }

    /**
     * Run the functions on update/insert
     *
     * @param  int $post_id
     * @param  int $form_id
     * @param  array $form_settings
     *
     * @return void
     */
    public function handle_form_updates( $post_id, $form_id, $form_settings ) {
        $this->update_downloadable_files( $post_id );
    }


    /**
     * Update the downloadable file array with appropriate meta values
     *
     * @param  int $post_id
     * @return void
     */
    function update_downloadable_files( $post_id ) {
        $files     = get_attached_media( '', $post_id );
        $edd_files = array();

        if ( !$files ) {
            update_post_meta( $post_id, 'edd_download_files', array() );
            return;
        }

        $index = 0;
        foreach ( $files as $file ) {
            $file_url = wp_get_attachment_url( $file->ID );
            $edd_files[$index++] = array(
                'file' => $file_url,
                'name' => basename( $file_url )
            );
        }

        update_post_meta( $post_id, 'edd_download_files', $edd_files );
    }
}
