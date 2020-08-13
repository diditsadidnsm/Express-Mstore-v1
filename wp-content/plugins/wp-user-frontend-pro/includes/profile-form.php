<?php
/**
 * Profile Forms or wpuf_profile form builder class
 *
 * @package WP User Frontend
 */

class WPUF_Admin_Profile_Form_Pro {
    /**
     * Form type of which we're working on
     *
     * @var string
     */
    private $form_type = 'profile';

    /**
     * Form settings key
     *
     * @var string
     */
    private $form_settings_key = 'wpuf_form_settings';

    /**
     * WP post types
     *
     * @var string
     */
    private $wp_post_types = array();

    /**
     * Add neccessary actions and filters
     *
     * @return void
     */
    public function __construct() {
        add_action( 'wpuf-form-builder-init-type-wpuf_profile', array( $this, 'init_pro' ) );
        add_action( 'init', array($this, 'register_post_type') );
        add_action( "load-user-frontend_page_wpuf-profile-forms", array( $this, 'profile_forms_builder_init' ) );
    }

    /**
     * Initialize the framework
     *
     * @since 2.5
     *
     * @return void
     */
    public function init_pro() {
        require_once WPUF_PRO_ROOT . '/admin/form-builder/class-wpuf-form-builder-pro.php';
        new WPUF_Admin_Form_Builder_Pro();
    }

    /**
     * Register form post types
     *
     * @return void
     */
    public function register_post_type() {
        $capability = wpuf_admin_role();

        register_post_type( 'wpuf_profile', array(
            'label'           => __( 'Registraton Forms', 'wpuf-pro' ),
            'public'          => false,
            'show_ui'         => false,
            'show_in_menu'    => false,
            'capability_type' => 'post',
            'hierarchical'    => false,
            'query_var'       => false,
            'supports'        => array('title'),
            'capabilities' => array(
                'publish_posts'       => $capability,
                'edit_posts'          => $capability,
                'edit_others_posts'   => $capability,
                'delete_posts'        => $capability,
                'delete_others_posts' => $capability,
                'read_private_posts'  => $capability,
                'edit_post'           => $capability,
                'delete_post'         => $capability,
                'read_post'           => $capability,
            ),
            'labels' => array(
                'name'               => __( 'Forms', 'wpuf-pro' ),
                'singular_name'      => __( 'Form', 'wpuf-pro' ),
                'menu_name'          => __( 'Registration Forms', 'wpuf-pro' ),
                'add_new'            => __( 'Add Form', 'wpuf-pro' ),
                'add_new_item'       => __( 'Add New Form', 'wpuf-pro' ),
                'edit'               => __( 'Edit', 'wpuf-pro' ),
                'edit_item'          => __( 'Edit Form', 'wpuf-pro' ),
                'new_item'           => __( 'New Form', 'wpuf-pro' ),
                'view'               => __( 'View Form', 'wpuf-pro' ),
                'view_item'          => __( 'View Form', 'wpuf-pro' ),
                'search_items'       => __( 'Search Form', 'wpuf-pro' ),
                'not_found'          => __( 'No Form Found', 'wpuf-pro' ),
                'not_found_in_trash' => __( 'No Form Found in Trash', 'wpuf-pro' ),
                'parent'             => __( 'Parent Form', 'wpuf-pro' ),
            ),
        ) );
    }

    /**
     * Initiate form builder for wpuf_profile post type
     *
     * @since 2.5
     *
     * @return void
     */
    public function profile_forms_builder_init() {

        if ( ! isset( $_GET['action'] ) ) {
            return;
        }

        if ( 'add-new' === $_GET['action'] && empty( $_GET['id'] ) ) {
            $form_id = wpuf_create_sample_form( 'Sample Registration Form', 'wpuf_profile', true );
            $add_new_page_url = add_query_arg( array( 'id' => $form_id ), admin_url( 'admin.php?page=wpuf-profile-forms&action=edit' ) );
            wp_redirect( $add_new_page_url );
        }

        if ( ( 'edit' === $_GET['action'] ) && ! empty( $_GET['id'] ) ) {

            add_action( 'wpuf-form-builder-settings-tabs-profile', array( $this, 'add_settings_tabs' ) );
            add_action( 'wpuf-form-builder-settings-tab-contents-profile', array( $this, 'add_settings_tab_contents' ) );
            add_filter( 'wpuf-form-fields-section-before', array( $this, 'add_profile_field_section' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
            add_filter( 'wpuf-form-builder-js-root-mixins', array( $this, 'js_root_mixins' ) );
            add_action( 'wpuf-form-builder-js-deps', array( $this, 'js_dependencies' ) );
            add_filter( 'wpuf-form-builder-js-builder-stage-mixins', array( $this, 'js_builder_stage_mixins' ) );
            add_action( 'wpuf-form-builder-template-builder-stage-submit-area', array( $this, 'add_form_submit_area' ) );
            add_filter( 'wpuf-form-builder-i18n', array( $this, 'i18n' ) );

            do_action( 'wpuf-form-builder-init-type-wpuf_profile' );

            $settings = array(
                'form_type'         => 'profile',
                'post_type'         => 'wpuf_profile',
                'post_id'           => $_GET['id'],
                'form_settings_key' => $this->form_settings_key,
                'shortcodes'        => array(
                    array( 'name' => 'wpuf_profile', 'type' => 'registration' ),
                    array( 'name' => 'wpuf_profile', 'type' => 'profile' )
                )
            );

            new WPUF_Admin_Form_Builder( $settings );
        }
    }

    /**
     * Add settings tabs
     *
     * @since 2.5
     *
     * @return void
     */
    public function add_settings_tabs() {
        ?>

        <a href="#wpuf-metabox-settings" class="nav-tab"><?php _e( 'General', 'wpuf-pro' ); ?></a>
        <a href="#wpuf-metabox-settings-notification" class="nav-tab"><?php _e( 'Notification', 'wpuf-pro' ); ?></a>
        <a href="#wpuf-metabox-settings-reg-display-settings" class="nav-tab"><?php _e( 'Display Settings', 'wpuf-pro' ); ?></a>
        <a href="#wpuf-metabox-settings-registration" class="nav-tab"><?php _e( 'Registration', 'wpuf-pro' ); ?></a>
        <a href="#wpuf-metabox-settings-profile" class="nav-tab"><?php _e( 'Profile Update', 'wpuf-pro' ); ?></a>
        <?php do_action( 'wpuf_profile_form_tab' ); ?>

        <?php
    }

    /**
     * Add settings tabs
     *
     * @since 2.5
     *
     * @return void
     */
    public function add_settings_tab_contents() {
        ?>

        <div id="wpuf-metabox-settings" class="group">
            <?php $this->form_settings_general(); ?>
        </div>
        <div id="wpuf-metabox-settings-notification" class="group">
            <?php $this->form_settings_notification(); ?>
        </div>
        <div id="wpuf-metabox-settings-reg-display-settings" class="group">
            <?php $this->form_settings_reg_display_settings(); ?>
        </div>
        <div id="wpuf-metabox-settings-registration" class="group">
            <?php $this->form_settings_registration(); ?>
        </div>
        <div id="wpuf-metabox-settings-profile" class="group">
            <?php $this->form_settings_profile(); ?>
        </div>

        <?php do_action( 'wpuf_profile_form_tab_content' ); ?>

        <?php
    }

    /**
     * Displays settings on registration form builder
     *
     * @since 2.3.2
     *
     * @return void
     */
    public function form_settings_general() {
        global $post;

        $form_settings = wpuf_get_form_settings( $post->ID );

        $role_selected           = isset( $form_settings['role'] ) ? $form_settings['role'] : 'subscriber';

        // Multisteps
        $is_multistep_enabled    = isset( $form_settings['enable_multistep'] ) ? $form_settings['enable_multistep'] : '';
        $multistep_progress_type = isset( $form_settings['multistep_progressbar_type'] ) ? $form_settings['multistep_progressbar_type'] : 'step_by_step';

        $ms_ac_txt_color         = isset( $form_settings['ms_ac_txt_color'] ) ? $form_settings['ms_ac_txt_color'] : '#ffffff';
        $ms_active_bgcolor       = isset( $form_settings['ms_active_bgcolor'] ) ? $form_settings['ms_active_bgcolor'] : '#00a0d2';
        $ms_bgcolor              = isset( $form_settings['ms_bgcolor'] ) ? $form_settings['ms_bgcolor'] : '#E4E4E4';
        ?>
        <table class="form-table">

            <tr class="wpuf-post-type">
                <th><?php _e( 'User Role', 'wpuf-pro' ); ?></th>
                <td>
                    <select name="wpuf_settings[role]">
                        <?php
                        $user_roles = wpuf_get_user_roles();
                        foreach ( $user_roles as $role => $label ) {
                            printf('<option value="%s"%s>%s</option>', $role, selected( $role_selected, $role, false ), $label );
                        }
                        ?>
                    </select>

                    <p class="description"><?php _e( 'The user role of the newly registered user.', 'wpuf-pro' ); ?></p>
                </td>
            </tr>
            <tr class="wpuf_enable_multistep_section">
                <th><?php _e( 'Enable Multistep', 'wpuf-pro' ); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="wpuf_settings[enable_multistep]" value="yes" <?php checked( $is_multistep_enabled, 'yes' ); ?> />
                        <?php _e( 'Enable Multistep', 'wpuf-pro' ); ?>
                    </label>

                    <p class="description"><?php echo __( 'If checked, form will be displayed in frontend in multiple steps', 'wpuf-pro' ); ?></p>
                </td>
            </tr>
            <tr class="wpuf_multistep_content">
                <td colspan="2" style="padding: 15px 0;">
                    <h3><?php _e( 'Multistep Form Settings', 'wpuf-pro' ); ?></h3>
                </td>
            </tr>
            <tr class="wpuf_multistep_progress_type wpuf_multistep_content">
                <th><?php _e( 'Multistep Progressbar Type', 'wpuf-pro' ); ?></th>
                <td>
                    <label>
                        <select name="wpuf_settings[multistep_progressbar_type]">
                            <option value="progressive" <?php echo $multistep_progress_type == 'progressive'? 'selected':'' ;?>><?php _e( 'Progressbar', 'wpuf-pro' ); ?></option>
                            <option value="step_by_step" <?php echo $multistep_progress_type == 'step_by_step'? 'selected':'' ;?>><?php _e( 'Step by Step', 'wpuf-pro' ); ?></option>
                        </select>
                    </label>


                    <p class="description"><?php echo __( 'Choose how you want the progressbar', 'wpuf-pro' ); ?></p>
                </td>
            </tr>

            <tr class="wpuf_multistep_content">
                <th><?php _e( 'Active Text Color', 'wpuf-pro' ); ?></th>
                <td>
                    <label>
                        <input type="text" name="wpuf_settings[ms_ac_txt_color]" class="wpuf-ms-color" value="<?php echo $ms_ac_txt_color; ?>"  />

                    </label>

                    <p class="description"> <?php _e( 'Text color for active step.', 'wpuf-pro' ); ?></p>
                </td>
            </tr>
            <tr class="wpuf_multistep_content">
                <th><?php _e( 'Active Background Color', 'wpuf-pro' ); ?></th>
                <td>
                    <label>
                        <input type="text" name="wpuf_settings[ms_active_bgcolor]" class="wpuf-ms-color" value="<?php echo $ms_active_bgcolor; ?>"  />

                    </label>

                    <p class="description"> <?php _e( 'Background color for progressbar or active step.', 'wpuf-pro' ); ?></p>
                </td>
            </tr>
            <tr class="wpuf_multistep_content">
                <th><?php _e( 'Background Color', 'wpuf-pro' ); ?></th>
                <td>
                    <label>
                        <input type="text" name="wpuf_settings[ms_bgcolor]" class="wpuf-ms-color" value="<?php echo $ms_bgcolor; ?>"  />

                    </label>

                    <p class="description"> <?php _e( 'Background color for normal steps.', 'wpuf-pro' ); ?></p>
                </td>
            </tr>

            <?php do_action( 'wpuf_profile_setting', $form_settings, $post ); ?>
        </table>
        <?php
    }



    /**
     * Displays settings on registration form builder
     *
     * @since 2.3.2
     *
     * @return void
     */
    public function form_settings_notification() {
        global $post;

        $blogname                = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
        $form_settings           = wpuf_get_form_settings( $post->ID );
        $user_notification       = isset( $form_settings['user_notification'] ) ? $form_settings['user_notification'] : 'on';
        $admin_notification      = isset( $form_settings['admin_notification'] ) ? $form_settings['admin_notification'] : 'on';
        $notification_type       = isset( $form_settings['notification_type'] ) ? $form_settings['notification_type'] : 'email_verification';

        $verification_mail_body  = "Congrats! You are Successfully registered to {blogname}\r\n\r\n";
        $verification_mail_body .= "To activate your account, please click the link below\r\n";
        $verification_mail_body .= "{activation_link}\r\n\r\n";
        $verification_mail_body .= "Thanks!";

        $verification_subject    = isset( $form_settings['notification']['verification_subject'] ) ? $form_settings['notification']['verification_subject'] : __( 'Account Activation', 'wpuf-pro' );
        $verification_body       = isset( $form_settings['notification']['verification_body'] ) ? $form_settings['notification']['verification_body'] : $verification_mail_body;

        $welcome_mail_body       = "Hi %username%,\r\n\r\n";
        $welcome_mail_body      .= "Congrats! You are Successfully registered to ". $blogname ."\r\n\r\n";
        $welcome_mail_body      .= "Thanks";

        $welcome_email_subject   = isset( $form_settings['notification']['welcome_email_subject'] ) ? $form_settings['notification']['welcome_email_subject'] : __( 'Thank you for registering', 'wpuf-pro' );
        $welcome_email_body      = isset( $form_settings['notification']['welcome_email_body'] ) ? $form_settings['notification']['welcome_email_body'] : $welcome_mail_body;

        $pending_user_admin_notification       = "Username: %username% (%user_email%) has requested a username.\r\n\r\n";
        $pending_user_admin_notification      .= "To approve or deny this user access go to %pending_users%\r\n\r\n";
        $pending_user_admin_notification      .= "Thanks";

        $approved_user_admin_notification       = "Username: %username% (%user_email%) has requested a username.\r\n\r\n";
        $approved_user_admin_notification      .= "To pending or deny this user access go to %approved_users%\r\n\r\n";
        $approved_user_admin_notification      .= "Thanks";

        $admin_email_subject                   = isset( $form_settings['notification']['admin_email_subject'] ) ? $form_settings['notification']['admin_email_subject'] : __( 'New user registered on your site', 'wpuf-pro' );
        $pending_user_admin_notification       = isset( $form_settings['notification']['admin_email_body']['user_status_pending'] ) ? $form_settings['notification']['admin_email_body']['user_status_pending'] : $pending_user_admin_notification;
        $approved_user_admin_notification      = isset( $form_settings['notification']['admin_email_body']['user_status_approved'] ) ? $form_settings['notification']['admin_email_body']['user_status_approved'] : $approved_user_admin_notification;

        ?>

        <h3><?php _e( 'New User Notification', 'wpuf-pro' ); ?></h3>
        <table class="form-table">
            <tr>
                <th><?php _e( 'Enable user notification', 'wpuf-pro' ); ?></th>
                <td>
                    <label>
                        <input type="hidden" name="wpuf_settings[user_notification]" value="off">
                        <input type="checkbox" name="wpuf_settings[user_notification]" value="on"<?php checked( $user_notification, 'on' ); ?>>
                        <?php _e( 'Enable user notification', 'wpuf-pro' ); ?>
                    </label>
                </td>
            </tr>

            <tr class="wpuf-registration-notification-type">
                <th><?php _e( 'Notification Type', 'wpuf-pro' ); ?></th>
                <td>
                    <label for="wpuf_settings[notification_type]">
                        <input type="hidden" name="wpuf_settings[notification_type]" value="email_verification">

                        <input type="radio" id="notification_type_verification" name="wpuf_settings[notification_type]" value="email_verification" <?php checked( $notification_type, 'email_verification' ); ?>>
                        <?php _e( 'Email Verification', 'wpuf-pro' ); ?>

                        <input type="radio" id="notification_type_welcome_email" name="wpuf_settings[notification_type]" value="welcome_email" <?php checked( $notification_type, 'welcome_email' ); ?>>
                        <?php _e( 'Welcome Email', 'wpuf-pro' ); ?>
                    </label>

                    <p class="description"><?php _e( 'An email will be sent to the user after registration.', 'wpuf-pro' ); ?></p>
                </td>
            </tr>

            <tr class="wpuf-email-verification-settings-fields">
                <th><?php _e( 'Confirmation mail subject', 'wpuf-pro' ); ?></th>
                <td><input type="text" name="wpuf_settings[notification][verification_subject]" class="regular-text" value="<?php echo esc_attr( $verification_subject ) ?>"></td>
            </tr>

            <tr class="wpuf-email-verification-settings-fields">
                <th><?php _e( 'Confirmation mail body', 'wpuf-pro' ); ?></th>
                <td>
                    <textarea rows="8" cols="60" name="wpuf_settings[notification][verification_body]"><?php echo esc_textarea( $verification_body ) ?></textarea>
                    <p class="description"><?php _e( 'You may use: {username}{blogname}{activation_link}', 'wpuf-pro' ); ?></p>
                </td>
            </tr>

            <tr class="wpuf-welcome-email-settings-fields">
                <th><?php _e( 'Welcome mail subject', 'wpuf-pro' ); ?></th>
                <td><input type="text" name="wpuf_settings[notification][welcome_email_subject]" class="regular-text" value="<?php echo esc_attr( $welcome_email_subject ) ?>"></td>
            </tr>

            <tr class="wpuf-welcome-email-settings-fields">
                <th><?php _e( 'Welcome mail body', 'wpuf-pro' ); ?></th>
                <td>
                    <textarea rows="8" cols="60" name="wpuf_settings[notification][welcome_email_body]"><?php echo esc_textarea( $welcome_email_body ) ?></textarea>
                    <p class="description"><?php _e( 'You may use: %username% %user_email% %display_name% %user_status% %pending_users% %approved_users% %denied_users%', 'wpuf-pro' ); ?></p>
                </td>
            </tr>


            <?php do_action( 'wpuf_profile_setting_notification_user', $form_settings, $post ); ?>
        </table>

        <h3><?php _e( 'Admin Notification', 'wpuf-pro' ); ?></h3>
        <table class="form-table">
            <tr>
                <th><?php _e( 'Enable admin notification', 'wpuf-pro' ); ?></th>
                <td>
                    <label>
                        <input type="hidden" name="wpuf_settings[admin_notification]" value="off">
                        <input type="checkbox" name="wpuf_settings[admin_notification]" value="on"<?php checked( $admin_notification, 'on' ); ?>>
                        <?php _e( 'Enable admin notification', 'wpuf-pro' ); ?>
                    </label>
                </td>
            </tr>

            <tr class="wpuf-new-user-admin-notification subject">
                <th><?php _e( 'Subject', 'wpuf-pro' ); ?></th>
                <td><input type="text" name="wpuf_settings[notification][admin_email_subject]" class="regular-text" value="<?php echo esc_attr( $admin_email_subject ) ?>"></td>
            </tr>

            <tr class="wpuf-new-user-admin-notification content">
                <th><?php _e( 'Message', 'wpuf-pro' ); ?></th>
                <td>
                    <textarea rows="8" cols="60" id="wpuf_pending_user_admin_notification" name="wpuf_settings[notification][admin_email_body][user_status_pending]"><?php echo esc_textarea( $pending_user_admin_notification ) ?></textarea>
                    <textarea rows="8" cols="60" id="wpuf_approved_user_admin_notification" name="wpuf_settings[notification][admin_email_body][user_status_approved]"><?php echo esc_textarea( $approved_user_admin_notification ) ?></textarea>
                    <p class="description"><?php _e( 'You may use: %username% %user_email% %display_name% %user_status% %pending_users% %approved_users% %denied_users%', 'wpuf-pro' ); ?></p>
                </td>
            </tr>


            <?php do_action( 'wpuf_profile_setting_notification_admin', $form_settings, $post ); ?>
        </table>
        <?php
    }


    /**
     * Adds registration redirect tab content
     *
     * @since 2.3.2
     *
     * @return void
     */
    public function form_settings_registration() {
        global $post;

        $form_settings = wpuf_get_form_settings( $post->ID );

        $redirect_to        = isset( $form_settings['reg_redirect_to'] ) ? $form_settings['reg_redirect_to'] : 'post';

        if ( ! isset( $form_settings['reg_redirect_to'] ) ) {
            $redirect_to = isset( $form_settings['reg_redirect_to'] ) ? $form_settings['reg_redirect_to'] : 'post';
        }

        $message                = isset( $form_settings['message'] ) ? $form_settings['message'] : __( 'Registration successful', 'wpuf-pro' );
        $page_id                = isset( $form_settings['reg_page_id'] ) ? $form_settings['reg_page_id'] : 0;
        $url                    = isset( $form_settings['registration_url'] ) ? $form_settings['registration_url'] : '';

        if ( ! isset( $form_settings['registration_url'] ) ) {
            $url = isset( $form_settings['url'] ) ? $form_settings['url'] : '';
        }

        $submit_text            = isset( $form_settings['submit_text'] ) ? $form_settings['submit_text'] : __( 'Register', 'wpuf-pro' );
        $ms_ac_txt_color        = isset( $form_settings['ms_ac_txt_color'] ) ? $form_settings['ms_ac_txt_color'] : '#ffffff';
        $ms_active_bgcolor      = isset( $form_settings['ms_active_bgcolor'] ) ? $form_settings['ms_active_bgcolor'] : '#00a0d2';
        $ms_bgcolor             = isset( $form_settings['ms_bgcolor'] ) ? $form_settings['ms_bgcolor'] : '#E4E4E4';
        ?>
        <table class="form-table">
            <tr class="wpuf-reg-redirect-to">
                <th><?php _e( 'Redirect To', 'wpuf-pro' ); ?></th>
                <td>
                    <select name="wpuf_settings[reg_redirect_to]">
                    <?php
                    $redirect_options = array(
                        'same' => __( 'Same Page', 'wpuf-pro' ),
                        'page' => __( 'To a page', 'wpuf-pro' ),
                        'url' => __( 'To a custom URL', 'wpuf-pro' )
                        );

                    foreach ( $redirect_options as $to => $label ) {
                        printf('<option value="%s"%s>%s</option>', $to, selected( $redirect_to, $to, false ), $label );
                    }
                    ?>
                    </select>
                    <div class="description">
                        <?php _e( 'After successfull submit, where the page will redirect to', 'wpuf-pro' ) ?>
                    </div>
                </td>
            </tr>

            <tr class="wpuf-same-page">
                <th><?php _e( 'Registration success message', 'wpuf-pro' ); ?></th>
                <td>
                    <textarea rows="3" cols="40" name="wpuf_settings[message]"><?php echo esc_textarea( $message ); ?></textarea>
                </td>
            </tr>

            <tr class="wpuf-page-id">
                <th><?php _e( 'Page', 'wpuf-pro' ); ?></th>
                <td>
                    <select name="wpuf_settings[reg_page_id]">
                    <?php
                    $pages = get_posts(  array( 'numberposts' => -1, 'post_type' => 'page') );

                    foreach ($pages as $page) {
                        printf('<option value="%s"%s>%s</option>', $page->ID, selected( $page_id, $page->ID, false ), esc_attr( $page->post_title ) );
                    }
                    ?>
                </select>
            </td>
            </tr>

            <tr class="wpuf-url">
                <th><?php _e( 'Custom URL', 'wpuf-pro' ); ?></th>
                <td>
                    <input type="url" name="wpuf_settings[registration_url]" value="<?php echo esc_attr( $url ); ?>">
                </td>
            </tr>

            <tr class="wpuf-submit-text">
                <th><?php _e( 'Submit Button text', 'wpuf-pro' ); ?></th>
                <td>
                    <input type="text" name="wpuf_settings[submit_text]" value="<?php echo esc_attr( $submit_text ); ?>">
                </td>
            </tr>

            <?php do_action( 'wpuf_profile_setting_reg', $form_settings, $post ); ?>
        </table>
    <?php
    }

    /**
     * Adds profile update redirect tab content
     *
     * @since 2.3.2
     *
     * @return void
     */

    public function form_settings_profile() {
        global $post;

        $form_settings = wpuf_get_form_settings( $post->ID );

        $redirect_to             = isset( $form_settings['profile_redirect_to'] ) ? $form_settings['profile_redirect_to'] : 'post';

        if ( ! isset( $form_settings['reg_redirect_to'] ) ) {
            $redirect_to         = isset( $form_settings['profile_redirect_to'] ) ? $form_settings['profile_redirect_to'] : 'post';
        }

        $update_message          = isset( $form_settings['update_message'] ) ? $form_settings['update_message'] : __( 'Profile updated successfully', 'wpuf-pro' );
        $page_id                 = isset( $form_settings['profile_page_id'] ) ? $form_settings['profile_page_id'] : 0;
        $url                     = isset( $form_settings['profile_url'] ) ? $form_settings['profile_url'] : '';

        if ( ! isset( $form_settings['profile_url'] ) ) {
            $url = isset( $form_settings['url'] ) ? $form_settings['url'] : '';
        }


        $update_text             = isset( $form_settings['update_text'] ) ? $form_settings['update_text'] : __( 'Update Profile', 'wpuf-pro' );

        $ms_ac_txt_color         = isset( $form_settings['ms_ac_txt_color'] ) ? $form_settings['ms_ac_txt_color'] : '#ffffff';
        $ms_active_bgcolor       = isset( $form_settings['ms_active_bgcolor'] ) ? $form_settings['ms_active_bgcolor'] : '#00a0d2';
        $ms_bgcolor              = isset( $form_settings['ms_bgcolor'] ) ? $form_settings['ms_bgcolor'] : '#E4E4E4';
        ?>
        <table class="form-table">
            <tr class="wpuf-profile-redirect-to">
                <th><?php _e( 'Redirect To', 'wpuf-pro' ); ?></th>
                <td>
                    <select name="wpuf_settings[profile_redirect_to]">
                    <?php
                    $redirect_options = array(
                        'same' => __( 'Same Page', 'wpuf-pro' ),
                        'page' => __( 'To a page', 'wpuf-pro' ),
                        'url'  => __( 'To a custom URL', 'wpuf-pro' )
                    );

                    foreach ( $redirect_options as $to => $label ) {
                        printf('<option value="%s"%s>%s</option>', $to, selected( $redirect_to, $to, false ), $label );
                    }
                    ?>
                    </select>
                    <div class="description">
                        <?php _e( 'After successfull submit, where the page will redirect to', 'wpuf-pro' ) ?>
                    </div>
                </td>
            </tr>

            <tr class="wpuf-same-page">
                <th><?php _e( 'Update profile message', 'wpuf-pro' ); ?></th>
                <td>
                    <textarea rows="3" cols="40" name="wpuf_settings[update_message]"><?php echo esc_textarea( $update_message ); ?></textarea>
                </td>
            </tr>

            <tr class="wpuf-page-id">
                <th><?php _e( 'Page', 'wpuf-pro' ); ?></th>
                <td>
                    <select name="wpuf_settings[profile_page_id]">
                    <?php
                    $pages = get_posts(  array( 'numberposts' => -1, 'post_type' => 'page') );

                    foreach ( $pages as $page ) {
                        printf('<option value="%s"%s>%s</option>', $page->ID, selected( $page_id, $page->ID, false ), esc_attr( $page->post_title ) );
                    }
                    ?>
                    </select>
                </td>
            </tr>

            <tr class="wpuf-url">
                <th><?php _e( 'Custom URL', 'wpuf-pro' ); ?></th>
                <td>
                    <input type="url" name="wpuf_settings[profile_url]" value="<?php echo esc_attr( $url ); ?>">
                </td>
            </tr>

            <tr class="wpuf-update-text">
                <th><?php _e( 'Update Button text', 'wpuf-pro' ); ?></th>
                <td>
                    <input type="text" name="wpuf_settings[update_text]" value="<?php echo esc_attr( $update_text ); ?>">
                </td>
            </tr>

            <?php do_action( 'wpuf_profile_setting_profile', $form_settings, $post ); ?>
        </table>
    <?php
    }

    public function form_settings_reg_display_settings () {
        global $post;

        $form_settings  = wpuf_get_form_settings( get_the_ID() );
        $label_position = isset( $form_settings['label_position'] ) ? $form_settings['label_position'] : 'left';
        $theme_css      = isset( $form_settings['use_theme_css'] ) ? $form_settings['use_theme_css'] : 'wpuf-style';
        $form_layout    = isset( $form_settings['form_layout'] ) ? $form_settings['form_layout'] : 'layout1';
        ?>
        <table class="form-table">
            <tr class="wpuf-pro-label-position">
                <th><?php _e( 'Label Position', 'wpuf-pro' ); ?></th>
                <td>
                    <select name="wpuf_settings[label_position]">
                        <?php
                        $positions = array(
                            'above'  => __( 'Above Element', 'wpuf-pro' ),
                            'left'   => __( 'Left of Element', 'wpuf-pro' ),
                            'right'  => __( 'Right of Element', 'wpuf-pro' ),
                            'hidden' => __( 'Hidden', 'wpuf-pro' ),
                        );

                        foreach ($positions as $to => $label) {
                            printf('<option value="%s"%s>%s</option>', $to, selected( $label_position, $to, false ), $label );
                        }
                        ?>
                    </select>

                    <p class="description">
                        <?php _e( 'Where the labels of the form should display', 'wpuf-pro' ) ?>
                    </p>
                </td>
            </tr>

            <tr class="wpuf-override-theme-css">
                <th><?php _e( 'Use Theme CSS', 'wpuf' ); ?></th>
                <td>
                    <select name="wpuf_settings[use_theme_css]">
                        <?php
                        $options = array(
                            'wpuf-style'         => __( 'No', 'wpuf' ),
                            'wpuf-theme-style'   => __( 'Yes', 'wpuf' ),
                        );

                        foreach ($options as $to => $label) {
                            printf('<option value="%s"%s>%s</option>', $to, selected( $theme_css, $to, false ), $label );
                        }
                        ?>
                    </select>

                    <p class="description">
                        <?php _e( 'Selecting "Yes" will use your theme\'s style for form fields.', 'wpuf' ) ?>
                    </p>
                </td>
            </tr>

            <tr class="wpuf-form-layouts">
                <th><?php _e( 'Form Style', 'wpuf-pro' ); ?></th>
                <td>
                    <ul>
                        <?php
                            $layouts = array(
                                'layout1' => WPUF_PRO_ASSET_URI . '/images/forms/layout1.png',
                                'layout2' => WPUF_PRO_ASSET_URI . '/images/forms/layout2.png',
                                'layout3' => WPUF_PRO_ASSET_URI . '/images/forms/layout3.png'
                            );

                            foreach ($layouts as $key => $image) {
                                $active = '';

                                if ( $key == $form_layout ) {
                                    $active = 'active';
                                }

                                $output  = '<li class="' . $active . '">';
                                $output .= '<input type="radio" name="wpuf_settings[form_layout]" value="' . $key . '" ' . checked( $form_layout, $key, false ). '>';
                                $output .= '<img src="' . $image . '" alt="">';
                                $output .= '</li>';

                                echo $output;
                            }
                        ?>
                    </ul>
                </td>
            </tr>
        </table>

        <?php
    }

    /**
     * Add post fields in form builder
     *
     * @since 2.5
     *
     * @return array
     */
    public function add_profile_field_section() {
        $profile_fields = apply_filters( 'wpuf-form-builder-wp_profile-fields-section-post-fields', array(
            'user_login', 'first_name', 'last_name', 'display_name', 'nickname', 'user_email', 'user_url', 'user_bio', 'password', 'avatar'
        ) );

        return array(
            array(
                'title'     => __( 'Profile Fields', 'wpuf-pro' ),
                'id'        => 'profile-fields',
                'fields'    => $profile_fields
            )
        );
    }

    /**
     * Admin script form wpuf_forms form builder
     *
     * @since 2.5
     *
     * @return void
     */
    public function admin_enqueue_scripts() {
        wp_register_script(
            'wpuf-form-builder-wpuf-profile',
            WPUF_PRO_ASSET_URI . '/js/wpuf-form-builder-wpuf-profile.js',
            array( 'jquery', 'underscore', 'wpuf-vue', 'wpuf-vuex' ),
            WPUF_PRO_VERSION,
            true
            );
    }

    /**
     * Add dependencies to form builder script
     *
     * @since 2.5
     *
     * @param array $deps
     *
     * @return array
     */
    public function js_dependencies( $deps ) {
        array_push( $deps, 'wpuf-form-builder-wpuf-profile' );

        return $deps;
    }

    /**
     * Add mixins to root instance
     *
     * @since 2.5
     *
     * @param array $mixins
     *
     * @return array
     */
    public function js_root_mixins( $mixins ) {
        array_push( $mixins , 'wpuf_forms_mixin_root' );

        return $mixins;
    }

    /**
     * Add mixins to form builder builder stage component
     *
     * @since 2.5
     *
     * @param array $mixins
     *
     * @return array
     */
    public function js_builder_stage_mixins( $mixins ) {
        array_push( $mixins , 'wpuf_forms_mixin_builder_stage' );

        return $mixins;
    }

    /**
     * Add buttons in form submit area
     *
     * @since 2.5
     *
     * @return void
     */
    public function add_form_submit_area() {
        ?>
        <input @click.prevent="" type="submit" name="submit" :value="post_form_settings.submit_text">

        <a
        v-if="post_form_settings.draft_post"
        @click.prevent=""
        href="#"
        class="btn"
        id="wpuf-post-draft"
        >
        <?php _e( 'Save Draft', 'wpuf-pro' ); ?>
        </a>
        <?php
    }

    /**
     * i18n strings specially for Post Forms
     *
     * @since 2.5
     *
     * @param array $i18n
     *
     * @return array
     */
    public function i18n( $i18n ) {
        return array_merge( $i18n, array(
            'email_needed' => __( 'Profile Forms must have Email field', 'wpuf-pro' )
            ) );
    }

}
