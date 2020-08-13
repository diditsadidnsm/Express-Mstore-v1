<?php
/**
 * The Events Calendar Integration Template
 *
 * @since 2.9
 */
class WPUF_Post_Form_Template_Events_Calendar extends WPUF_Post_Form_Template {

    public function __construct() {
        parent::__construct();

        $this->enabled     = class_exists( 'Tribe__Events__Main' );
        $this->title       = __( 'The Events Calendar', 'wpuf-pro' );
        $this->description = __( 'Form for creating events. The Events Calendar plugin is required.', 'wpuf-pro' );
        $this->image       = WPUF_ASSET_URI . '/images/templates/post.png';
        $this->form_fields = array(
            array(
                'input_type'      => 'text',
                'template'        => 'post_title',
                'required'        => 'yes',
                'label'           => __( 'Event Title', 'wpuf-pro' ),
                'name'            => 'post_title',
                'is_meta'         => 'no',
                'default'         => '',
                'size'            => '',
                'placeholder'     => __( 'Please enter your event title', 'wpuf-pro' ),
                'wpuf_cond'       => $this->conditionals,
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type'      => 'textarea',
                'template'        => 'post_content',
                'required'        => 'no',
                'label'           => __( 'Event details', 'wpuf-pro' ),
                'name'            => 'post_content',
                'is_meta'         => 'no',
                'default'         => '',
                'help'            => __( 'Write the full description of your event', 'wpuf-pro' ),
                'rows'            => '5',
                'cols'            => '25',
                'rich'            => 'yes',
                'insert_image'    => 'yes',
                'wpuf_cond'       => $this->conditionals,
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type'      => 'image_upload',
                'template'        => 'featured_image',
                'count'           => '1',
                'required'        => 'no',
                'label'           => __( 'Featured Image', 'wpuf-pro' ),
                'button_label'    => 'Featured Image',
                'name'            => 'featured_image',
                'is_meta'         => 'no',
                'help'            => __( 'Upload the main image of your event', 'wpuf-pro' ),
                'max_size'        => '1024',
                'wpuf_cond'       => $this->conditionals,
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type'      => 'taxonomy',
                'template'        => 'taxonomy',
                'required'        => 'no',
                'label'           => __( 'Event Categories', 'wpuf-pro' ),
                'name'            => 'tribe_events_cat',
                'is_meta'         => 'no',
                'width'           => 'small',
                'type'            => 'select',
                'first'           => '- select -',
                'show_inline'     => 'inline',
                'orderby'         => 'name',
                'order'           => 'ASC',
                'exclude'         => array(),
                'wpuf_cond'       => $this->conditionals,
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type'      => 'textarea',
                'template'        => 'post_excerpt',
                'required'        => 'no',
                'label'           => __( 'Short Description', 'wpuf-pro' ),
                'name'            => 'post_excerpt',
                'is_meta'         => 'no',
                'default'         => '',
                'placeholder'     => '',
                'help'            => __( 'Provide a short description of this event (optional)', 'wpuf-pro' ),
                'rows'            => '5',
                'cols'            => '25',
                'rich'            => 'no',
                'wpuf_cond'       => $this->conditionals,
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type'      => 'text',
                'template'        => 'post_tags',
                'required'        => 'no',
                'label'           => __( 'Event Tags', 'wpuf-pro' ),
                'name'            => 'tags',
                'is_meta'         => 'no',
                'default'         => '',
                'placeholder'     => '',
                'help'            => __( 'Separate tags with commas.', 'wpuf-pro' ),
                'size'            => '40',
                'wpuf_cond'       => $this->conditionals,
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type'      => 'section_break',
                'template'        => 'section_break',
                'label'           => __( 'TIME & DATE', 'wpuf-pro' ),
                'description'     => '',
                'wpuf_cond'       => $this->conditionals,
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type'      => 'date',
                'template'        => 'date_field',
                'required'        => 'yes',
                'label'           => __( 'Event Start', 'wpuf-pro' ),
                'name'            => '_EventStartDate',
                'is_meta'         => 'yes',
                'help'            => '',
                'width'           => 'large',
                'format'          => 'yy-mm-dd',
                'time'            => 'yes',
                'wpuf_cond'       => $this->conditionals,
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type'      => 'date',
                'template'        => 'date_field',
                'required'        => 'yes',
                'label'           => __( 'Event End', 'wpuf-pro' ),
                'name'            => '_EventEndDate',
                'is_meta'         => 'yes',
                'help'            => '',
                'width'           => 'large',
                'format'          => 'yy-mm-dd',
                'time'            => 'yes',
                'wpuf_cond'       => $this->conditionals,
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type'      => 'radio',
                'template'        => 'radio_field',
                'required'        => 'no',
                'label'           => __( 'All Day Event', 'wpuf-pro' ),
                'name'            => '_EventAllDay',
                'is_meta'         => 'yes',
                'inline'          => 'yes',
                'options'         => array(
                'yes'             => 'Yes',
                'no'              => 'No',
                ) ,
                'wpuf_cond'       => $this->conditionals,
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type'      => 'section_break',
                'template'        => 'section_break',
                'label'           => __( 'VENUE INFORMATION', 'wpuf-pro' ),
                'wpuf_cond'       => $this->conditionals,
                'description'     => '',
                'name'            => 'undefined_copy',
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type'      => 'radio',
                'template'        => 'radio_field',
                'required'        => 'no',
                'label'           => __( 'Venue', 'wpuf-pro' ),
                'name'            => 'venue',
                'is_meta'         => 'yes',
                'selected'        => 'find_a_venue',
                'inline'          => 'yes',
                'options'         => array(
                'find_a_venue'    => __( 'Find a Venue', 'wpuf-pro' ),
                'create'          => __( 'Create', 'wpuf-pro' ),
                ),
                'wpuf_cond'       => $this->conditionals,
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type' => 'select',
                'template'   => 'dropdown_field',
                'required'   => 'no',
                'label'      => __( 'Find a Venue', 'wpuf-pro' ),
                'name'       => '_EventVenueID',
                'is_meta'    => 'yes',
                'first'      => '- select -',
                'options'    => array(),
                'wpuf_cond'  => array(
                    'condition_status' => 'yes',
                    'cond_field'       => array(
                        0 => 'venue',
                    ),
                    'cond_operator' => array(
                        0 => '=',
                    ),
                    'cond_option' => array(
                        0 => 'find_a_venue',
                    ),
                    'cond_logic' => 'all',
                ),
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type'  => 'text',
                'template'    => 'text_field',
                'required'    => 'yes',
                'label'       => __( 'Venue Name', 'wpuf-pro' ),
                'name'        => 'venue_name',
                'is_meta'     => 'yes',
                'default'     => '',
                'placeholder' => '',
                'size'        => 40,
                'wpuf_cond'   => array(
                    'condition_status' => 'yes',
                    'cond_field'       => array(
                        0 => 'venue',
                    ) ,
                    'cond_operator' => array(
                        0 => '=',
                    ) ,
                    'cond_option' => array(
                        0 => 'create',
                    ) ,
                    'cond_logic' => 'all',
                ),
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type' => 'address',
                'template' => 'address_field',
                'required' => 'no',
                'label' => __( 'Venue Address', 'wpuf-pro' ),
                'name' => 'venue_address',
                'is_meta' => 'yes',
                'help' => '',
                'wpuf_cond' => array(
                    'condition_status' => 'yes',
                    'cond_field' => array(
                        0 => 'venue',
                    ) ,
                    'cond_operator' => array(
                        0 => '=',
                    ) ,
                    'cond_option' => array(
                        0 => 'create',
                    ) ,
                    'cond_logic' => 'all',
                ),
                'address_desc' => '',
                'address' => array(
                    'street_address' => array(
                        'checked' => 'checked',
                        'type' => 'text',
                        'required' => '',
                        'label' => __( 'Address', 'wpuf-pro' ),
                        'value' => '',
                        'placeholder' => '',
                    ) ,
                    'street_address2' => array(
                        'checked' => '',
                        'type' => 'text',
                        'required' => '',
                        'label' => __( 'Address Line 2', 'wpuf-pro' ),
                        'value' => '',
                        'placeholder' => '',
                    ) ,
                    'city_name' => array(
                        'checked' => 'checked',
                        'type' => 'text',
                        'required' => '',
                        'label' => __( 'City', 'wpuf-pro' ),
                        'value' => '',
                        'placeholder' => '',
                    ) ,
                    'state' => array(
                        'checked' => 'checked',
                        'type' => 'text',
                        'required' => '',
                        'label' => __( 'State', 'wpuf-pro' ),
                        'value' => '',
                        'placeholder' => '',
                    ) ,
                    'zip' => array(
                        'checked' => 'checked',
                        'type' => 'text',
                        'required' => '',
                        'label' => __( 'Postal Code', 'wpuf-pro' ),
                        'value' => '',
                        'placeholder' => '',
                    ) ,
                    'country_select' => array(
                        'checked' => 'checked',
                        'type' => 'select',
                        'required' => '',
                        'label' => __( 'Country', 'wpuf-pro' ),
                        'value' => '',
                        'country_list_visibility_opt_name' => 'all',
                        'country_select_hide_list' => array() ,
                        'country_select_show_list' => array() ,
                    ) ,
                ),
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type' => 'numeric_text',
                'template' => 'numeric_text_field',
                'required' => 'no',
                'label' => __( 'Venue Phone', 'wpuf-pro' ),
                'name' => 'venue_phone',
                'is_meta' => 'yes',
                'width' => 'large',
                'size' => 40,
                'default' => '',
                'placeholder' => '',
                'help' => '',
                'step_text_field' => '0',
                'min_value_field' => '0',
                'max_value_field' => '0',
                'wpuf_cond' => array(
                    'condition_status' => 'yes',
                    'cond_field' => array(
                        0 => 'venue',
                    ) ,
                    'cond_operator' => array(
                        0 => '=',
                    ) ,
                    'cond_option' => array(
                        0 => 'create',
                    ) ,
                    'cond_logic' => 'all',
                ),
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type' => 'url',
                'template' => 'website_url',
                'required' => 'no',
                'label' => __( 'Venue Website', 'wpuf-pro' ),
                'name' => 'venue_website',
                'is_meta' => 'yes',
                'default' => '',
                'placeholder' => 'yes',
                'width' => 'large',
                'open_window' => 'same',
                'size' => 40,
                'wpuf_cond' => array(
                    'condition_status' => 'yes',
                    'cond_field' => array(
                        0 => 'venue',
                    ) ,
                    'cond_operator' => array(
                        0 => '=',
                    ) ,
                    'cond_option' => array(
                        0 => 'create',
                    ) ,
                    'cond_logic' => 'all',
                ),
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type' => 'checkbox',
                'template' => 'checkbox_field',
                'required' => 'no',
                'label' => '',
                'name' => '_EventShowMap',
                'is_meta' => 'yes',
                'inline' => 'yes',
                'options' => array(
                    1 => __( 'Show Google Map', 'wpuf-pro' ),
                ),
                'wpuf_cond' => array(
                    'condition_status' => 'yes',
                    'cond_field' => array(
                        0 => 'venue',
                    ) ,
                    'cond_operator' => array(
                        0 => '=',
                    ) ,
                    'cond_option' => array(
                        0 => 'yes',
                    ) ,
                    'cond_logic' => 'all',
                ),
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type' => 'checkbox',
                'template' => 'checkbox_field',
                'required' => 'no',
                'label' => '',
                'name' => '_EventShowMapLink',
                'is_meta' => 'yes',
                'inline' => 'yes',
                'options' => array(
                    1 => __( 'Show Google Maps Link', 'wpuf-pro' ),
                ),
                'wpuf_cond' => array(
                    'condition_status' => 'yes',
                    'cond_field' => array(
                        0 => 'venue',
                    ) ,
                    'cond_operator' => array(
                        0 => '=',
                    ) ,
                    'cond_option' => array(
                        0 => 'yes',
                    ) ,
                    'cond_logic' => 'all',
                ),
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type'      => 'section_break',
                'template'        => 'section_break',
                'label'           => __( 'ORGANIZERS', 'wpuf-pro' ),
                'description'     => '',
                'wpuf_cond'       => $this->conditionals,
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type' => 'radio',
                'template'   => 'radio_field',
                'required'   => 'no',
                'label'      => __( 'Organizer', 'wpuf-pro' ),
                'name'       => 'organizer',
                'is_meta'    => 'yes',
                'selected'   => 'find_organizer',
                'inline'     => 'yes',
                'options'    => array(
                    'find_organizer' => 'Find an Organizer',
                    'create'         => 'Create',
                ),
                'wpuf_cond'       => $this->conditionals,
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type' => 'select',
                'template'   => 'dropdown_field',
                'required'   => 'no',
                'label'      => __( 'Find an Organizer', 'wpuf-pro' ),
                'name'       => '_EventOrganizerID',
                'is_meta'    => 'yes',
                'first'      => '- select -',
                'options'    => array(),
                'wpuf_cond'  => array(
                    'condition_status' => 'yes',
                    'cond_field' => array(
                        0 => 'organizer',
                    ),
                    'cond_operator' => array(
                        0 => '=',
                    ),
                    'cond_option' => array(
                        0 => 'find_organizer',
                    ),
                    'cond_logic' => 'all',
                ),
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type'  => 'text',
                'template'    => 'text_field',
                'required'    => 'yes',
                'label'       => __( 'Organizer Name', 'wpuf-pro' ),
                'name'        => 'organizer_name',
                'is_meta'     => 'yes',
                'placeholder' => '',
                'default'     => '',
                'size'        => 40,
                'wpuf_cond'   => array(
                    'condition_status' => 'yes',
                    'cond_field' => array(
                        0 => 'organizer',
                    ) ,
                    'cond_operator' => array(
                        0 => '=',
                    ) ,
                    'cond_option' => array(
                        0 => 'create',
                    ) ,
                    'cond_logic' => 'all',
                ),
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type'      => 'numeric_text',
                'template'        => 'numeric_text_field',
                'required'        => 'no',
                'label'           => __( 'Organizer Phone', 'wpuf-pro' ),
                'name'            => 'organizer_phone',
                'is_meta'         => 'yes',
                'default'         => '',
                'placeholder'     => '',
                'help'            => '',
                'width'           => 'large',
                'size'            => 40,
                'step_text_field' => '0',
                'min_value_field' => '0',
                'max_value_field' => '0',
                'wpuf_cond'       => array(
                    'condition_status' => 'yes',
                    'cond_field' => array(
                        0 => 'organizer',
                    ) ,
                    'cond_operator' => array(
                        0 => '=',
                    ) ,
                    'cond_option' => array(
                        0 => 'create',
                    ) ,
                    'cond_logic' => 'all',
                ),
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type'  => 'url',
                'template'    => 'website_url',
                'required'    => 'no',
                'label'       => __( 'Organizer Website', 'wpuf-pro' ),
                'name'        => 'organizer_website',
                'is_meta'     => 'yes',
                'default'     => '',
                'placeholder' => '',
                'width'       => 'large',
                'open_window' => 'same',
                'size'        => 40,
                'wpuf_cond'   => array(
                    'condition_status' => 'yes',
                    'cond_field' => array(
                        0 => 'organizer',
                    ) ,
                    'cond_operator' => array(
                        0 => '=',
                    ) ,
                    'cond_option' => array(
                        0 => 'create',
                    ) ,
                    'cond_logic' => 'all',
                ),
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type'  => 'email',
                'template'    => 'email_address',
                'required'    => 'no',
                'label'       => __( 'Organizer Email', 'wpuf-pro' ),
                'name'        => 'organizer_email',
                'is_meta'     => 'yes',
                'default'     => '',
                'placeholder' => '',
                'width'       => 'large',
                'size'        => 40,
                'wpuf_cond'   => array(
                    'condition_status' => 'yes',
                    'cond_field' => array(
                        0 => 'organizer',
                    ) ,
                    'cond_operator' => array(
                        0 => '=',
                    ) ,
                    'cond_option' => array(
                        0 => 'create',
                    ) ,
                    'cond_logic' => 'all',
                ),
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type'      => 'section_break',
                'template'        => 'section_break',
                'label'           => __( 'EVENT WEBSITE', 'wpuf-pro' ),
                'description'     => '',
                'wpuf_cond'       => $this->conditionals,
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type'      => 'url',
                'template'        => 'website_url',
                'required'        => 'no',
                'label'           => __( 'Event Website', 'wpuf-pro' ),
                'name'            => '_EventURL',
                'is_meta'         => 'yes',
                'placeholder'     => '',
                'default'         => '',
                'width'           => 'large',
                'size'            => 40,
                'wpuf_cond'       => $this->conditionals,
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type'      => 'section_break',
                'template'        => 'section_break',
                'label'           => __( 'EVENT COST', 'wpuf-pro' ),
                'description'     => '',
                'wpuf_cond'       => $this->conditionals,
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type'      => 'text',
                'template'        => 'text_field',
                'required'        => 'no',
                'label'           => __( 'Currency Symbol', 'wpuf-pro' ),
                'name'            => '_EventCurrencySymbol',
                'is_meta'         => 'yes',
                'default'         => '',
                'placeholder'     => '',
                'size'            => '',
                'wpuf_cond'       => $this->conditionals,
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
            array(
                'input_type'      => 'text',
                'template'        => 'text_field',
                'required'        => 'no',
                'label'           => __( 'Cost', 'wpuf-pro' ),
                'name'            => '_EventCost',
                'is_meta'         => 'yes',
                'default'         => '',
                'placeholder'     => '',
                'size'            => '',
                'help'            => 'Enter a 0 for events that are free or leave blank to hide the field.',
                'wpuf_cond'       => $this->conditionals,
                'wpuf_visibility' => $this->get_default_visibility_prop()
            ),
        );

        $this->form_settings = array (
            'post_type'                  => 'tribe_events',
            'post_status'                => 'publish',
            'default_cat'                => '-1',
            'guest_post'                 => 'false',
            'message_restrict'           => __( 'This page is restricted. Please Log in / Register to view this page.', 'wpuf-pro' ),
            'redirect_to'                => 'post',
            'comment_status'             => 'open',
            'submit_text'                => __( 'Create Event', 'wpuf-pro' ),
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
            'update_message'             => __( 'Event has been updated successfully. <a target="_blank" href="%link%">View event</a>', 'wpuf-pro' ),
            'edit_url'                   => '',
            'update_text'                => __( 'Update Event', 'wpuf-pro' ),
            'form_template'              => __CLASS__,
            'notification'               => array(
                'new'                        => 'on',
                'new_to'                     => get_option( 'admin_email' ),
                'new_subject'                => 'New event has been created',
                'new_body'                   => 'Hi,
A new event has been created in your site %sitename% (%siteurl%).

Here is the details:
Event Title: %post_title%
Description: %post_content%
Short Description: %post_excerpt%
Author: %author%
Post URL: %permalink%
Edit URL: %editlink%',
                'edit'                       => 'off',
                'edit_to'                    => get_option( 'admin_email' ),
                'edit_subject'               => 'Post has been edited',
                'edit_body'                  => 'Hi,
The event "%post_title%" has been updated.

Here is the details:
Event Title: %post_title%
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
        $data = isset( $_POST ) ? $_POST : '';

        if ( isset( $data['venue'] ) && $data['venue'] == 'create' ) {
            $this->create_venue($data, $post_id);
        }

        if ( isset( $data['organizer'] ) && $data['organizer'] == 'create' ) {
            $this->create_organizer($data, $post_id);
        }
    }

    /**
     * Creat venue for the events calendar plugin
     *
     * @param  int $data
     * @param  int $post_id
     *
     * @return void
     */
    public function create_venue( $data, $post_id ) {
        $venue_name             = isset( $data['venue_name'] ) ? $data['venue_name'] : '';
        $venue_location         = isset( $data['venue_address'] ) ? $data['venue_address'] : '';

        $venue_address          = isset( $venue_location['street_address'] ) ? $venue_location['street_address'] : '';
        $venue_city             = isset( $venue_location['city_name'] ) ? $venue_location['city_name'] : '';
        $venue_country          = isset( $venue_location['country_select'] ) ? $venue_location['country_select'] : '';
        $venue_province         = isset( $venue_location['state'] ) ? $venue_location['state'] : '';
        $venue_state            = isset( $venue_location['state'] ) ? $venue_location['state'] : '';
        $venue_state_province   = isset( $venue_location['state'] ) ? $venue_location['state'] : '';
        $venue_zip              = isset( $venue_location['zip'] ) ? $venue_location['zip'] : '';

        $venue_phone            = isset( $data['venue_phone'] ) ? $data['venue_phone'] : '';
        $venue_show_map         = isset( $data['_EventShowMap'] ) ? $data['_EventShowMap'][0] : 'false';
        $venue_map_link         = isset( $data['_EventShowMapLink'] ) ? $data['_EventShowMapLink'][0] : 'false';
        $venue_url              = isset( $data['venue_website'] ) ? $data['venue_website'] : '';

        $postarr = array(
            'post_type'    => 'tribe_venue',
            'post_status'  => 'publish',
            'post_title'   => $venue_name,
            'meta_input'   => array(
                '_VenueAddress'         => $venue_address,
                '_VenueCity'            => $venue_city,
                '_VenueCountry'         => $venue_country,
                '_VenueProvince'        => $venue_province,
                '_VenueState'           => $venue_state,
                '_VenueStateProvince'   => $venue_state_province,
                '_VenueZip'             => $venue_zip,
                '_VenuePhone'           => $venue_phone,
                '_VenueURL'             => $venue_url,
                '_VenueShowMap'         => $venue_show_map,
                '_VenueShowMapLink'     => $venue_map_link
            ),
        );

        $venue_id = wp_insert_post( $postarr );

        update_post_meta( $post_id, '_EventVenueID', $venue_id );
    }

    /**
     * Creat organizer for the events calendar plugin
     *
     * @param  int $data
     * @param  int $post_id
     *
     * @return void
     */
    public function create_organizer( $data, $post_id ) {
        $organizer_name     = isset( $data['organizer_name'] ) ? $data['organizer_name'] : '';
        $organizer_phone    = isset( $data['organizer_phone'] ) ? $data['organizer_phone'] : '';
        $organizer_website  = isset( $data['organizer_website'] ) ? $data['organizer_website'] : '';
        $organizer_email    = isset( $data['organizer_email'] ) ? $data['organizer_email'] : '';

        $postarr = array(
            'post_type'    => 'tribe_organizer',
            'post_status'  => 'publish',
            'post_title'   => $organizer_name,
            'meta_input'   => array(
                '_OrganizerPhone'   => $organizer_phone,
                '_OrganizerWebsite' => $organizer_website,
                '_OrganizerEmail'   => $organizer_email,
            ),
        );

        $organizer_id = wp_insert_post( $postarr );

        update_post_meta( $post_id, '_EventOrganizerID', $organizer_id );
    }
}
