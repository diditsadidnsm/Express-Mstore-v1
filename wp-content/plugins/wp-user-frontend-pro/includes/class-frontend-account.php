<?php
/**
 * Pro functionality for frontend account page
 *
 * @since 2.8.2
 */
class WPUF_Frontend_Account_Pro {

    function __construct() {
        add_filter( 'wpuf_options_wpuf_my_account', array( $this, 'add_settings_options' ) );
        add_filter( 'wpuf_account_sections', array( $this, 'manage_account_sections' ) );
        add_filter( 'wpuf_my_account_tab_links', array( $this, 'manage_account_tab_links' ) );
        add_filter( 'wpuf_account_edit_profile_content', array( $this, 'edit_profile_content' ) );
    }


    /**
     * Add new settings options
     *
     * @return array $options
     */
    public function add_settings_options( $options ) {
        $options[] = array(
            'name'    => 'show_edit_profile_menu',
            'label'   => __( 'Edit Profile', 'wpuf-pro' ),
            'desc'    => __( 'Allow user to update their profile information from the account page', 'wpuf-pro' ),
            'type'    => 'checkbox',
            'default' => 'on'
        );

        $options[] = array(
            'name'    => 'edit_profile_form',
            'label'   => __( 'Profile Form', 'wpuf-pro' ),
            'desc'    => __( 'User will use this form to update their information from the account page,', 'wpuf-pro' ),
            'type'    => 'select',
            'options' => $this->get_profile_forms()
        );

        return $options;
    }


    /**
     * Get registration forms created by WPUF
     *
     * @return array $forms
     */
    public function get_profile_forms() {
        $args = array(
            'post_type' => 'wpuf_profile',
            'post_status' => 'any',
            'orderby'     => 'DESC',
            'order'       => 'ID'
        );

        $query = new WP_Query( $args );

        $forms = array(
            '-1' => __( 'Default Form', 'wpuf-pro' )
        );

        if ( $query->have_posts() ) {

            $i = 0;

            while ( $query->have_posts() ) {
                $query->the_post();

                $form = $query->posts[ $i ];

                $settings = get_post_meta( get_the_ID(), 'wpuf_form_settings', true );

                $forms[ $form->ID ] = $form->post_title;

                $i++;
            }
        }

        return $forms;
    }


    /**
     * Show/Hide frontend account section depending on Edit Profile option
     *
     * @return array $sections
     */
    public function manage_account_sections( $sections ) {
        $allow_profile_edit = wpuf_get_option( 'show_edit_profile_menu', 'wpuf_my_account', 'on' );

        if ( $allow_profile_edit != 'on' ) {
            foreach ($sections as $key => $value) {
                if ( $value['slug'] == 'edit-profile' ) {
                    unset($sections[$key]);
                }
            }
            return $sections;
        }

        return $sections;
    }

    /**
     * Show/Hide frontend account section depending on Edit Profile option
     *
     * @return array $sections
     */
    public function manage_account_tab_links( $links ) {
        $allow_profile_edit = wpuf_get_option( 'show_edit_profile_menu', 'wpuf_my_account', 'on' );

        if ( $allow_profile_edit != 'on' ) {
            unset( $links['edit-profile'] );
        }

        return $links;
    }


    /**
     * Display content on frontend account page
     *
     * @return string $content
     */
    public function edit_profile_content( $content ) {
        $edit_profile_form  = wpuf_get_option( 'edit_profile_form', 'wpuf_my_account', '-1' );

        if ( $edit_profile_form != '-1' ) {
            $content = do_shortcode('[wpuf_profile type="profile" id="'.$edit_profile_form.'"]');
        }
        return $content;
    }

}