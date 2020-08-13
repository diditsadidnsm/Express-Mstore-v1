<?php
// if ( !class_exists( 'User_Directory_Settings_API' ) ) {
//     require_once dirname( __FILE__ ) . '/lib/class.user-directory-settings-api.php';
// }
/**
 * Show users all users
 *
 * @author Asaquzzaman
 */
class WPUF_Userlisting_Admin {

    private $meta_fields;
    private $post_num = 15;
    private $cmt_num = 5;
    private $user_meat;


    /**
     * __construct()
     *
     * Initial function of this Class or class controller.
     */
    function __construct() {
        // $this->settings_api = new User_Directory_Settings_API();
        // profile image size
        add_filter( 'wpuf_settings_sections', array($this, 'plugin_sections') );
        add_filter( 'wpuf_settings_fields', array($this, 'plugin_options') );
        add_action( 'admin_enqueue_scripts', array($this, 'userlisting_enqueue_scripts') );
        add_action( 'wpuf_admin_menu', array($this, 'admin_menu') );

        add_action( 'show_user_profile', array( $this, 'add_meta_fields' ), 20 );
        add_action( 'edit_user_profile', array( $this, 'add_meta_fields' ), 20 );

        add_action( 'personal_options_update', array( $this, 'save_meta_fields' ) );
        add_action( 'edit_user_profile_update', array( $this, 'save_meta_fields' ) );
    }

    public function plugin_sections($sections){
        $sections[] = array(
            'id'    => 'user_directory',
            'title' => __( 'User Directory', 'wpuf-pro' ),
            'icon' => 'dashicons-list-view'
        );
        return $sections;
    }

    function plugin_options($settings) {
        $sizes = array(
            '32' => '32 x 32',
            '48' => '48 x 48',
            '80' => '80 x 80',
            '128' => '128 x 128',
            '160' => '160 x 160',
            '192' => '192 x 192',
            '256' => '256 x 256'
        );


        $settings['user_directory'] = array(
            array(
                'name' => 'pro_img_size',
                'label' => __( 'Profile Gallery Image Size ', 'wpuf-userlisting' ),
                'desc' => __( 'Set the image size of picture gallery in frontend', 'wpuf-userlisting' ),
                'type' => 'select',
                'options' => wpuf_get_image_sizes(),
            ),
            array(
                'name' => 'avatar_size',
                'label' => __( 'Avatar Size ', 'wpuf-userlisting' ),
                'desc' => __( 'Set the image size of profile picture in frontend', 'wpuf-userlisting' ),
                'type' => 'select',
                'options' => $sizes,
            ),
            array(
                'name'    => 'profile_header_template',
                'label'   => __( 'Profile Header Template', 'wpuf-pro' ),
                'type'    => 'radio',
                'default' => 'layout',
                'options' => array(
                    'layout' => '<img class="profile-header" src="' . plugins_url( '/images/layout.png', __FILE__ ) . '" />',
                    'layout1' => '<img class="profile-header" src="' . plugins_url( '/images/layout1.png', __FILE__ ) . '" />',
                    'layout2' => '<img class="profile-header" src="' . plugins_url( '/images/layout2.png', __FILE__ ) . '" />',
                ),
            ),
            array(
                'name'    => 'user_listing_template',
                'label'   => __( 'User Listing Template', 'wpuf-pro' ),
                'type'    => 'radio',
                'default' => 'list',
                'options' => array(
                    'list' => '<img class="user-listing" src="' . plugins_url( '/images/list.png', __FILE__ ) . '" />',
                    'list1' => '<img class="user-listing" src="' . plugins_url( '/images/list1.png', __FILE__ ) . '" />',
                    'list2' => '<img class="user-listing" src="' . plugins_url( '/images/list2.png', __FILE__ ) . '" />',
                    'list3' => '<img class="user-listing" src="' . plugins_url( '/images/list3.png', __FILE__ ) . '" />',
                    'list4' => '<img class="user-listing" src="' . plugins_url( '/images/list4.png', __FILE__ ) . '" />',
                    'list5' => '<img class="user-listing" src="' . plugins_url( '/images/list5.png', __FILE__ ) . '" />',
                ),
            )
        );

        return $settings;
    }

    function admin_menu() {
        $capability = wpuf_admin_role();
        add_submenu_page( 'wp-user-frontend', __( 'User Listing', 'wpuf-userlisting' ), __( 'User Listing', 'wpuf-userlisting' ), $capability, 'wpuf_userlisting', array($this, 'plugin_page') );
    }

    function userlisting_enqueue_scripts( $page ) {

        if ( in_array( $page, array( 'profile.php', 'user-edit.php' )) ) {
            wp_enqueue_media();
        }

        if ( $page != 'user-frontend_page_wpuf-settings' && $page != 'user-frontend_page_wpuf_userlisting' ) {
            return;
        }

        //Stylesheet
        wp_enqueue_style( 'userlisting_style', plugins_url( 'css/admin.css', __FILE__ ) );

        //JS scripts
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'underscore' );
        wp_enqueue_script( 'jquery-ui' );
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_media();
        wp_enqueue_script( 'wpuf-userlisting', plugins_url( 'js/userlisting.js', __FILE__ ) );
        wp_localize_script( 'wpuf-userlisting', 'wpufUserlisting', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'success_message' => __( 'Congrats', 'wpuf' ),
            'nonce' => wp_create_nonce( 'wpuf_userlisting' )
        ) );
    }

    /**
     * Add fields to user profile
     *
     * @param WP_User $user
     *
     * @return void|false
     */
    function add_meta_fields( $user ) {
        $user_banner = get_user_meta( $user->ID, 'profile_banner', true );
        $banner                = !empty( $user_banner ) ? absint( $user_banner ) : 0;
        ?>

        <table class="form-table">
            <tbody>
                <tr>
                    <th><?php _e( 'Profile Banner', 'wpuf-pro' ); ?></th>
                    <td>
                        <div class="profile-banner">
                            <div class="image-wrap<?php echo $banner ? '' : ' banner-hide'; ?>">
                                <?php $banner_url = $banner ? wp_get_attachment_url( $banner ) : ''; ?>
                                <input type="hidden" class="profile-file-field" value="<?php echo $banner; ?>" name="profile_banner">
                                <img class="profile-banner-img" src="<?php echo esc_url( $banner_url ); ?>">

                                <a class="close remove-profile-banner-image">&times;</a>
                            </div>

                            <div class="button-area<?php echo $banner ? ' banner-hide' : ''; ?>">
                                <a href="#" class="profile-banner-drag button button-primary"><?php _e( 'Upload banner', 'wpuf-pro' ); ?></a>
                                <p class="description"><?php _e( '(Upload a banner for your profile. Banner size is (825x300) pixels. )', 'wpuf-pro' ); ?></p>
                            </div>
                        </div> <!-- .profile-banner -->
                    </td>
                </tr>

                <?php do_action( 'profile_meta_fields', $user ); ?>

            </tbody>
        </table>

        <style type="text/css">
        .banner-hide { display: none; }
        .button-area { padding-top: 100px; }
        .profile-banner {
            border: 4px dashed #d8d8d8;
            height: 255px;
            margin: 0;
            overflow: hidden;
            position: relative;
            text-align: center;
            max-width: 700px;
        }
        .profile-banner img { max-width:100%; }
        .profile-banner .remove-profile-banner-image {
            position:absolute;
            width:100%;
            height:270px;
            background:#000;
            top:0;
            left:0;
            opacity:.7;
            font-size:100px;
            color:#f00;
            padding-top:70px;
            display:none
        }
        .profile-banner:hover .remove-profile-banner-image {
            display:block;
            cursor: pointer;
        }
        </style>

        <script type="text/javascript">
        jQuery(function($){
            var Banner_Settings = {

                init: function() {
                    $('a.profile-banner-drag').on('click', this.imageUpload);
                    $('a.remove-profile-banner-image').on('click', this.removeBanner);
                },

                imageUpload: function(e) {
                    e.preventDefault();

                    var file_frame,
                        self = $(this);

                    if ( file_frame ) {
                        file_frame.open();
                        return;
                    }

                    // Create the media frame.
                    file_frame = wp.media.frames.file_frame = wp.media({
                        title: jQuery( this ).data( 'uploader_title' ),
                        button: {
                            text: jQuery( this ).data( 'uploader_button_text' )
                        },
                        multiple: false
                    });

                    file_frame.on( 'select', function() {
                        var attachment = file_frame.state().get('selection').first().toJSON();

                        var wrap = self.closest('.profile-banner');
                        wrap.find('input.profile-file-field').val(attachment.id);
                        wrap.find('img.profile-banner-img').attr('src', attachment.url);
                        $('.image-wrap', wrap).removeClass('banner-hide');

                        $('.button-area').addClass('banner-hide');
                    });

                    file_frame.open();

                },

                removeBanner: function(e) {
                    e.preventDefault();

                    var self = $(this);
                    var wrap = self.closest('.image-wrap');
                    var instruction = wrap.siblings('.button-area');

                    wrap.find('input.profile-file-field').val('0');
                    wrap.addClass('banner-hide');
                    instruction.removeClass('banner-hide');
                },
            };

            Banner_Settings.init();
        });
        </script>
        <?php
    }

    /**
     * Save user data
     *
     * @param int $user_id
     *
     * @return void
     */
    function save_meta_fields( $user_id ) {

        update_user_meta( $user_id, 'profile_banner', intval( $_POST['profile_banner'] ) );

        do_action( 'process_directory_meta_fields', $user_id );
    }

    /**
     *
     * activation or initial function
     */
    function plugin_page() {

        echo '<div class="wrap">';
        //process submited value
        $this->process_submission();

        $this->form_builder();
        echo '</div>';
    }

    /**
     *
     *
     */
    function show_form() {

        $user_meta = $this->meta_fields;
        $this->user_meta = $user_meta;

        if ( ! $user_meta ) {
            return;
        }

        if( !is_array($user_meta['fields']) || !count($user_meta['fields']) > 0 ) return;

        foreach ($user_meta['fields'] as $key => $val) {

            $this->li_wrap_open( $val );

            switch ($val['type']) {

                case 'meta':
                    $this->print_meta( $key, $val );
                    break;

                case 'comment':
                    $this->print_comment( $key, $val );
                    break;

                case 'section':
                    $this->print_section( $key, $val );
                    break;

                case 'post':
                    $this->print_post( $key, $val );
                    break;
                case 'file':
                    $this->print_file( $key, $val );
                    break;
                case 'social':
                    $this->print_social( $key, $val );
                    break;
            }

            $this->li_wrap_close();
        }
    }

    function li_wrap_open( $value) {
        $label = isset( $value['label'] ) ? $value['label'] : '';
        $type = isset( $value['type'] ) ? ucfirst($value['type']) . ': <strong>' . $label . '</strong>' : $label;
        ?>
        <li>
            <div class="wpuf-legend">
                <div class="wpuf-label"><?php echo $type; ?></div>
                <div class="wpuf-actions">
                    <a href="#" class="wpuf-remove"><?php _e( 'Remove', 'wpuf-pro' ); ?></a>
                    <a href="#" class="wpuf-toggle"><?php _e( 'Toggle', 'wpuf-pro' ); ?></a>
                </div>
            </div>

            <div class="wpuf-form-holder">
        <?php
    }

    function li_wrap_close() {
        ?>
            </div> <!-- .wpuf-form-holder -->
        </li>
        <?php
    }

    function print_meta( $key, $val ) {
        $meta_key = isset( $val['meta'] ) ? esc_attr( $val['meta'] ) : '';
        $label = isset( $val['label'] ) ? esc_attr( $val['label'] ) : '';

        // var_dump($meta_key, $val);
        ?>
        <div class="wpuf-form-rows">
            <label><?php _e( 'Label', 'wpuf-pro' ); ?></label>

            <input type="hidden" value="type_meta" name="wpuf_pf_field[type][<?php echo $key; ?>]">
            <input type="text" value="<?php echo $label; ?>" name="wpuf_pf_field[label][<?php echo $key; ?>]">
        </div>

        <div class="wpuf-form-rows">
            <label><?php _e( 'Meta Key', 'wpuf-pro' ); ?></label>

            <div class="wpuf-form-sub-fields">

                <select name="wpuf_pf_field[meta][<?php echo $key; ?>]">
                    <option value="">- select -</option>
                    <optgroup label="<?php _e( 'Profile Fields', 'wpuf-pro' ); ?>">
                        <?php $this->default_meta_dropdown( $meta_key, $val ); ?>
                    </optgroup>
                    <optgroup label="<?php _e( 'Meta Keys', 'wpuf-pro' ); ?>">
                        <?php $this->custom_meta_key( $meta_key, $val ); ?>
                    </optgroup>
                </select>
            </div>
        </div>

        <?php
        //for all user
        $this->user_role_template('all_user_role', __( 'Profile User Role', 'wpuf-pro' ), $val, $key);
        //current user
        $this->user_role_template('current_user_role', 'Viewer Role', $val, $key);

        $this->meta_in_table( $key, $val );
    }

    function print_section( $key, $val ) {
        $label = isset( $val['label'] ) ? esc_attr( $val['label'] ) : '';
        ?>
        <div class="wpuf-form-rows">
            <label><?php _e( 'Label', 'wpuf-pro' ); ?></label>

            <input type="text" hidden  value="type_section" name="wpuf_pf_field[type][<?php echo $key; ?>]">
            <input type="text" value="<?php echo $label; ?>" name="wpuf_pf_field[label][<?php echo $key; ?>]">
        </div>

        <?php
            //for all user
            $this->user_role_template('all_user_role', __( 'Profile User Role', 'wpuf-pro' ), $val, $key);
            //current user
            $this->user_role_template('current_user_role', 'Viewer Role', $val, $key);

    }

    function print_post( $key, $val ) {
        $label = isset( $val['label'] ) ? esc_attr( $val['label'] ) : '';
        $count = isset( $val['count'] ) ? esc_attr( $val['count'] ) : 5;
        ?>
        <div class="wpuf-form-rows">
            <label><?php _e( 'Label', 'wpuf-pro' ); ?></label>

            <input value="<?php echo $label; ?>" type="text" name="wpuf_pf_field[label][<?php echo $key; ?>]">
            <input type="hidden" value="type_post" name="wpuf_pf_field[type][<?php echo $key; ?>]">
        </div>

        <div class="wpuf-form-rows">
            <label><?php _e( 'Post type', 'wpuf-pro' ); ?></label>

            <select name="wpuf_pf_field[post_type][<?php echo $key; ?>]">
                <?php $this->post_type( $key, $val ); ?>
            </select>
        </div>

        <div class="wpuf-form-rows">
            <label><?php _e( 'Post Count', 'wpuf-pro' ); ?></label>

            <input type="text" value="<?php echo $count; ?>" name="wpuf_pf_field[count][<?php echo $key; ?>]">
        </div>
        <?php
            //for all user
            $this->user_role_template('all_user_role', __( 'Profile User Role', 'wpuf-pro' ), $val, $key);
            //current user
            $this->user_role_template('current_user_role', 'Viewer Role', $val, $key);
        ?>
        <?php
    }

    function print_social( $key, $val ) {
        if ( isset( $val['social_icon']['wpuf_userlisting'] ) && $val['social_icon']['wpuf_userlisting'] == 'wpuf_userlisting') {
            $val['social_icon'] = array( '' => '' );
            $vals='';
        }else {
            $vals = $val;
        }
        ?>
        <input type="hidden" value="type_social" name="wpuf_pf_field[type][<?php echo $key; ?>]">
        <?php
            //for all user
            $this->user_role_template('all_user_role', __( 'Profile User Role', 'wpuf-pro' ), $vals, $key);
            //current user
            $this->user_role_template('current_user_role', 'Viewer Role', $vals, $key);
        ?>
        <div style="padding: 10px 0;"></div>
        <?php $this->print_social_icon_url($val); ?>
        <?php
    }

    function print_social_icon_url($val) {

        if( is_array( $val['social_icon'] ) && count( $val['social_icon'] ) >0 ) {
            $loop_val = $val['social_icon'];
        } else if( is_array( $val['social_url'] ) && count( $val['social_icon'] ) >0 ) {
            $loop_val = $val['social_url'];
        } else {
            return;
        }

        foreach( $loop_val as $key => $url_icon ) {
            ?>
            <div class="wpuf-form-rows">
                <label><?php _e('Icon URL', 'wpuf-pro'); ?></label>

                <div class="wpuf-form-sub-fields">
                    <input type="text" class="wpuf-file-field" value="<?php echo esc_url( $val['social_icon'][$key] ); ?>" name="wpuf_pf_field[social_icon][]">

                    <a href="#" class="button wpuf-file-upload"><?php _e('Upload Icon', 'wpuf-pro'); ?></a>

                    <?php $meta_key =  isset( $val['social_url'][$key] ) ? $val['social_url'][$key] : ''; ?>

                    <span class="wpuf-social-url">
                        <label><?php _e('Profile URL', 'wpuf-pro'); ?></label>
                        <select name="wpuf_pf_field[social_url][]">
                            <option value="">- select -</option>
                            <optgroup label="<?php _e( 'Profile Fields', 'wpuf-pro' ); ?>">
                                <?php $this->default_meta_dropdown( $meta_key, '' ); ?>
                            </optgroup>
                            <optgroup label="<?php _e( 'Meta Keys', 'wpuf-pro' ); ?>">
                                <?php $this->custom_meta_key( $meta_key, '' ); ?>
                            </optgroup>
                        </select>
                    </span>

                    <span class="wpuf-social-actions">
                        <a href="#" data-social_field_type="#wpuf-extr-social-field" class="social-row-add button">+</a>
                        <a href="#" data-close_social="rmv_social" class="del-social button">-</a>
                    </span>
                </div>
            </div>
            <?php
        }
    }

    function print_file( $key, $val) {
        $meta_key = isset( $val['meta'] ) ? esc_attr( $val['meta'] ) : '';
        $label = isset( $val['label'] ) ? esc_attr( $val['label'] ) : '';
        ?>
        <div class="wpuf-form-rows">
            <label><?php _e( 'Label', 'wpuf-pro' ); ?></label>

            <input type="hidden" value="type_file" name="wpuf_pf_field[type][<?php echo $key; ?>]">
            <input type="text" value="<?php echo $label; ?>" name="wpuf_pf_field[label][<?php echo $key; ?>]">
        </div>

        <div class="wpuf-form-rows">
            <label><?php _e( 'Meta Key', 'wpuf-pro' ); ?></label>

            <div class="wpuf-form-sub-fields">

                <select name="wpuf_pf_field[meta][<?php echo $key; ?>]">
                    <option value="">- select -</option>
                    <optgroup label="<?php _e( 'Profile Fields', 'wpuf-pro' ); ?>">
                        <?php $this->default_meta_dropdown( $meta_key, $val ); ?>
                    </optgroup>
                    <optgroup label="<?php _e( 'Meta Keys', 'wpuf-pro' ); ?>">
                        <?php $this->custom_meta_key( $meta_key, $val ); ?>
                    </optgroup>
                </select>
            </div>
        </div>

        <?php
        //for all user
        $this->user_role_template('all_user_role', __( 'Profile User Role', 'wpuf-pro' ), $val, $key);
        //current user
        $this->user_role_template('current_user_role', 'Viewer Role', $val, $key);
    }

    function print_comment( $key, $val ) {
        $label = isset( $val['label'] ) ? esc_attr( $val['label'] ) : '';
        $count = isset( $val['count'] ) ? esc_attr( $val['count'] ) : 5;
        ?>
        <div class="wpuf-form-rows">
            <label><?php _e( 'Label', 'wpuf-pro' ); ?></label>

            <input placeholder="" value="<?php echo $label; ?>" type="text" name="wpuf_pf_field[label][<?php echo $key; ?>]">
        </div>

        <div class="wpuf-form-rows">
            <label><?php _e( 'Post type Comment', 'wpuf-pro' ); ?></label>

            <input type="hidden" value="type_comment" name="wpuf_pf_field[type][<?php echo $key; ?>]">
            <select name="wpuf_pf_field[post_type][<?php echo $key; ?>]">
                <?php $this->post_type( $key, $val ); ?>
            </select>
        </div>

        <div class="wpuf-form-rows">
            <label><?php _e( 'Comment count', 'wpuf-pro' ); ?></label>

            <input value="<?php echo $count; ?>" type="text" name="wpuf_pf_field[count][<?php echo $key; ?>]">
        </div>

        <?php
            //for all user
            $this->user_role_template('all_user_role', __( 'Profile User Role', 'wpuf-pro' ), $val, $key);
            //current user
            $this->user_role_template('current_user_role', 'Viewer Role', $val, $key);

    }


    function user_role_template( $field_name = '', $label = '', $user_meta = '', $dbkey = null ) {

        $count = ( $dbkey === null ) ? '<%= count %>' : $dbkey;

        // var_dump($field_name, $label, $user_meta, $dbkey);
        ?>
        <div class="wpuf-form-rows">
            <label><?php _e( $label, 'wpuf-pro' ); ?></label>

            <div class="wpuf-form-sub-fields">
                <ul class="wpuf-role">
                    <?php
                    $roles = $this->get_user_roles();

                    foreach ($roles as $key => $role_name) {
                    ?>
                        <li>
                            <label>
                                <?php
                                $checked = false;
                                if ( !isset( $user_meta[$field_name] ) ) {
                                    // on inserting the field
                                    $checked = true;

                                } else {
                                    $checked = in_array( $key, $user_meta[$field_name] );
                                }
                                ?>
                                <input type="checkbox" <?php echo checked( $checked ); ?>  value="<?php echo esc_attr( $key ); ?>" name="wpuf_pf_field[<?php echo $field_name; ?>][<?php echo $count; ?>][<?php echo $key; ?>]">
                                <?php echo $role_name; ?>
                            </label>
                        </li>

                    <?php } ?>

                    <?php if ( $field_name != 'all_user_role' ) { ?>
                        <?php //var_dump( $user_meta); ?>
                        <li>
                            <label>
                                <?php
                                $checked = false;
                                if ( !isset( $user_meta['current_user_role'] ) ) {
                                    // on inserting the field
                                    $checked = true;

                                } elseif ( in_array('guest', $user_meta['current_user_role'] ) ) {
                                    $checked = true;
                                }
                                ?>

                                <input type="checkbox" <?php checked( $checked ); ?>  value="guest" name="wpuf_pf_field[<?php echo $field_name; ?>][<?php echo $count; ?>][guest]">
                                <?php echo __( 'Guest', 'wpuf-pro' ); ?>
                            </label>
                        </li>
                    <?php } ?>
                </ul>

                <p class="description">
                    <?php if ( $field_name == 'all_user_role' ) { ?>
                        Show this field if the currenty viewed user profile has one of these role
                    <?php } else { ?>
                        Show this field if the viewer (current logged in user or guest) has one of these role.
                    <?php } ?>
                </p>
            </div>
        </div>
        <?php
    }

    /**
     *
     *
     */
    function checked( $user_meta, $role, $field_name ) {
        if ( is_array($user_meta) ) {

            if(count( $user_meta[$field_name] ) > 0) {
                foreach ($user_meta[$field_name] as $val) {

                    if ( strtolower( $val ) == strtolower( $role ) )
                        return 'checked';
                }
            } else {
                return '';
            }
        } else {
            return 'checked';
        }
    }

    function meta_in_table( $key, $value ) {
        $in_table = isset( $value['in_table'] ) ? 'yes' : 'no';
        $search_by = isset( $value['search_by'] ) ? 'yes' : 'no';
        $sort_by = isset( $value['sort_by'] ) ? 'yes' : 'no';
        $show_class = ( 'no' == $in_table ) ? ' wpuf-hide' : '';
        ?>
        <div class="wpuf-form-rows">
            <div class="wpuf-form-sub-fields">
            <label class="full-width show">
                <input type="checkbox" <?php checked( $in_table, 'yes' ); ?> value="yes" name="wpuf_pf_field[in_table][<?php echo $key; ?>]">
                <?php _e( 'Show in user listing table', 'wpuf-pro' ); ?>
            </label>
            &nbsp;&nbsp;
            <label class="full-width search-by <?php echo $show_class; ?>">
                <input type="checkbox" <?php checked( $search_by, 'yes' ); ?> value="yes" name="wpuf_pf_field[search_by][<?php echo $key; ?>]">
                <?php _e( 'Search by this meta in user listing table', 'wpuf-pro' ); ?>
            </label>
            <!-- &nbsp;&nbsp;
            <label class="full-width sort-by <?php echo $show_class; ?>">
                <input type="checkbox" <?php checked( $sort_by, 'yes' ); ?> value="yes" name="wpuf_pf_field[sort_by][<?php echo $key; ?>]">
                <?php _e( 'Sort by this meta listing table', 'wpuf-pro' ); ?>
            </label> -->
            </div>
        </div>
        <?php
    }

    /**
     *
     *
     */
    function get_user_roles() {
        global $wp_roles;

        if ( !$wp_roles ) {
            $wp_roles = new WP_Roles();
        }

        return $wp_roles->get_names();
    }

    /**
     * Select meta data from $_POST
     *
     */
    function get_meta_type( $key ) {

        $type_meta = array();
        $meta_post = $_POST['wpuf_pf_field'];

        $type_meta['type'] = 'meta';
        $type_meta['label'] = $meta_post['label'][$key];
        $type_meta['meta'] = $meta_post['meta'][$key];
        $type_meta['all_user_role'] = isset( $meta_post['all_user_role'][$key] ) ? $meta_post['all_user_role'][$key] : array();
        $type_meta['current_user_role'] = isset( $meta_post['current_user_role'][$key] ) ? $meta_post['current_user_role'][$key] : array();

        if ( isset( $meta_post['in_table'][$key] ) ) {
            $type_meta['in_table'] = 'yes';
            if ( isset( $meta_post['search_by'][$key] ) ) {
                $type_meta['search_by'] = 'yes';
            }
            if ( isset( $meta_post['sort_by'][$key] ) ) {
                $type_meta['sort_by'] = 'yes';
            }
        }

        return $type_meta;
    }

    /**
     * Select meta data from $_POST
     *
     */
    function get_file_type( $key ) {

        $type_meta = array();
        $meta_post = $_POST['wpuf_pf_field'];

        $type_meta['type'] = 'file';
        $type_meta['label'] = $meta_post['label'][$key];
        $type_meta['meta'] = $meta_post['meta'][$key];
        $type_meta['all_user_role'] = $meta_post['all_user_role'][$key];
        $type_meta['current_user_role'] = $_POST['wpuf_pf_field']['current_user_role'][$key];

        if ( empty( $type_meta['meta'] ) ) {
            return;
        }

        return $type_meta;
    }

    /**
     *
     *
     */
    function get_section_type($key) {

        if( empty( $_POST['wpuf_pf_field']['label'][$key]) ) {
            return;
        }

        $type_section = array();
        $section_post = $_POST['wpuf_pf_field'];

        $type_section['type'] = 'section';
        $type_section['label'] = $section_post['label'][$key];
        $type_section['all_user_role'] = isset( $section_post['all_user_role'][$key] ) ? $section_post['all_user_role'][$key] : array();
        $type_section['current_user_role'] = isset( $_POST['wpuf_pf_field']['current_user_role'][$key] ) ? $_POST['wpuf_pf_field']['current_user_role'][$key] : array();

        return $type_section;
    }

    /**
     *
     *
     */
    function get_post_type($key) {

        $type_post = array();
        $post_post = $_POST['wpuf_pf_field'];


        $type_post['type'] = 'post';
        $type_post['post_type'] = $post_post['post_type'][$key];
        $type_post['label'] = $post_post['label'][$key];
        $type_post['count'] = empty( $post_post['count'][$key] ) ? $this->post_num : $post_post['count'][$key];
        $type_post['all_user_role'] = is_array($post_post['all_user_role'][$key]) ? $post_post['all_user_role'][$key] : array();
        $type_post['current_user_role'] = $_POST['wpuf_pf_field']['current_user_role'][$key];

        return $type_post;
    }

    function get_comment_type($key) {

        $type_comment = array();
        $comment_post = $_POST['wpuf_pf_field'];

        $type_comment['type'] = 'comment';

        $type_comment['post_type'] = $comment_post['post_type'][$key];
        $type_comment['label'] = $comment_post['label'][$key];

        $type_comment['count'] = empty( $comment_post['count'][$key] ) ? $this->cmt_num : $comment_post['count'][$key];
        $type_comment['all_user_role'] = is_array( $comment_post['all_user_role'][$key] ) ? $comment_post['all_user_role'][$key] : array();
        $type_comment['current_user_role'] = $_POST['wpuf_pf_field']['current_user_role'][$key];

        return $type_comment;
    }

    function get_social_type($key) {
        $type_social = array();

        if(  empty( $_POST['wpuf_pf_field']['social_icon'][0] ) && empty($_POST['wpuf_pf_field']['social_url'][0]) ) {
            return;
        }

        $type_social['type'] = 'social';
        $type_social['social_url'] = is_array( $_POST['wpuf_pf_field']['social_url'] ) ? $_POST['wpuf_pf_field']['social_url'] : array();
        $type_social['social_icon'] = is_array( $_POST['wpuf_pf_field']['social_icon'] ) ? $_POST['wpuf_pf_field']['social_icon'] : array();
        $type_social['all_user_role'] = $_POST['wpuf_pf_field']['all_user_role'][$key];
        $type_social['current_user_role'] = $_POST['wpuf_pf_field']['current_user_role'][$key];

        return $type_social;
    }

    /**
     *
     *
     */
    function process_submission() {

        $query_val = array();

        if ( !isset( $_POST['wpuf_userlinstin_nonce'] ) ||  !wp_verify_nonce( $_POST['wpuf_userlinstin_nonce'], 'wpuf_userlisting' ) ) {
            return;
        }

        // var_dump($_POST['wpuf_pf_field']);
        if ( isset( $_POST['wpuf_pf_field']['type']) ) {

            foreach( $_POST['wpuf_pf_field']['type'] as $key => $val) {

                switch( $val ) {
                    case 'type_meta':
                        $query_val[] = $this->get_meta_type($key);
                        break;
                    case 'type_post':
                        $query_val[] = $this->get_post_type($key);
                        break;
                    case 'type_comment':
                        $query_val[] = $this->get_comment_type($key);
                        break;
                    case 'type_section':
                        $query_val[] = $this->get_section_type($key);
                        break;
                    case 'type_social':
                        $query_val[] = $this->get_social_type($key);
                        break;
                    case 'type_file':
                        $query_val[] = $this->get_file_type($key);
                        break;
                }
            }
        }

        if( isset($_POST['wpuf_pf_field']['settings']['show_avatar']) ) {
            $fields['settings'] = array( 'avatar' => true );
        }

        $fields['fields'] = $query_val;

        foreach($fields['fields'] as $key=>$val) {
            if(empty($val)) {
                unset($fields['fields'][$key]);
            }
        }

        update_option( 'wpuf_userlisting', $fields );

        echo '<div class="updated fade"><p><strong>' . __( 'Fields are updated.', 'wpuf-pro' ) . '</strong></p></div>';
    }

    /**
     *
     *
     */
    function form_builder() {
        $this->meta_fields = get_option( 'wpuf_userlisting', array() );

        if ( isset( $this->meta_fields['settings']['avatar'] ) && $this->meta_fields['settings']['avatar'] == true ) {
            $show_avatar = true;
        } else {
            $show_avatar = false;
        }

        ?>
        <h2><?php _e( 'WP User Frontend Pro: User Listing', 'wpuf-pro' ); ?></h2>

        <ul class="wpuf-menu-field">
            <li class="header"><?php _e( 'Click to Add Items', 'wpuf-pro' ); ?></li>
            <li><a href="#" data-field_type="#wpuf-userlisting-meta" class="wpuf-add-field button"><?php _e( 'Add Meta field', 'wpuf-pro' ); ?></a></li>
            <li><a href="#" data-field_type="#wpuf-userlistion-section" class="wpuf-section-field button"><?php _e( 'Add Section', 'wpuf-pro' ); ?></a></li>
            <li><a href="#" data-field_type="#wpuf-userlistion-postype" class="wpuf-postype-field button"><?php _e( 'Add Post Type', 'wpuf-pro' ); ?></a></li>
            <li><a href="#" data-field_type="#wpuf-userlistion-comment" class="wpuf-comment-field button"><?php _e( 'Add Comment', 'wpuf-pro' ); ?></a></li>
            <li><a href="#" data-field_type="#wpuf-userlisting-social" class="wpuf-social-field button"><?php _e( 'Social', 'wpuf-pro' ); ?></a></li>
            <li><a href="#" data-field_type="#wpuf-userlisting-file" class="wpuf-file-field button"><?php _e( 'Image / File', 'wpuf-pro' ); ?></a></li>
            <?php do_action( 'wpuf-userlisting-itemlist' ); ?>
        </ul>

        <div class="wpuf-form">
            <form method="post" action="">
                <?php wp_nonce_field( 'wpuf_userlisting', 'wpuf_userlinstin_nonce' ); ?>
                <div class="wpuf-avatar">
                    <span class="wpuf-userlisting-toggle button" ><?php _e( 'Toggle Fields','wpuf-pro' ); ?></span>
                    <label>
                        <p>
                            <label>
                                <input type="checkbox" <?php checked( $show_avatar, true ); ?> value="yes" name="wpuf_pf_field[settings][show_avatar]">
                                <?php _e( 'Show Avatar', 'wpuf-pro' ); ?>
                            </label>
                        </p>
                    </label>

                </div>
                <ul id="wpuf-all-field">
                    <?php $this->show_form(); ?>
                </ul>

                <input type="submit" name="wpuf_save_button" value="<?php _e( 'Save Changes', 'wpuf-pro' ); ?>" class="button button-primary">
            </form>
        </div>

        <!--for post_type-->
        <script type="text/template" id="wpuf-userlistion-postype">

            <?php $this->li_wrap_open( array('label' => __( 'Post Listing', 'wpuf-pro' ) ) ); ?>

            <?php $this->print_post( '<%= count %>', '' ); ?>
            <?php $this->li_wrap_close(); ?>

        </script>

        <!--for comment-->
        <script type="text/template" id="wpuf-userlistion-comment">

            <?php $this->li_wrap_open( array('label' => 'Comment') ); ?>

            <?php $this->print_comment( '<%= count %>', '' ); ?>
            <?php $this->li_wrap_close(); ?>
        </script>

        <!--for section-->
        <script type="text/template" id="wpuf-userlistion-section">

            <?php $this->li_wrap_open( array('label' => __( 'Section', 'wpuf-pro') ) ); ?>

            <?php $this->print_section('<%= count %>', '' ); ?>
            <?php $this->li_wrap_close(); ?>
        </script>

        <!--for meta -->
        <script type="text/template" id="wpuf-userlisting-meta">

            <?php $this->li_wrap_open( array('label' => __( 'Meta Key', 'wpuf-pro') ) ); ?>
            <?php $this->print_meta('<%= count %>', '' ); ?>
            <?php $this->li_wrap_close();
            ?>
        </script>

        <!--for file -->
        <script type="text/template" id="wpuf-userlisting-file">

            <?php $this->li_wrap_open( array('label' => 'Image / File') ); ?>
            <?php $this->print_file('<%= count %>', '' ); ?>
            <?php $this->li_wrap_close();
            ?>
        </script>

        <script type="text/template" id="wpuf-userlisting-social">

            <?php $this->li_wrap_open( array('label' => 'Social Profiles') ); ?>
            <?php $this->print_social( '<%= count %>', array('social_icon' => array('wpuf_userlisting' => 'wpuf_userlisting') ) ); ?>


            <?php $this->li_wrap_close(); ?>
        </script>

        <script type="text/template" id="wpuf-extr-social-field">
            <?php $this->social_icon_row_template(); ?>
        </script>
        <?php

        do_action( 'wpuf-userlisting-templates', $this );
    }

    function social_icon_row_template() {
        ?>
        <div class="wpuf-form-rows">
            <label><?php _e('Icon URL', 'wpuf-pro'); ?></label>

            <div class="wpuf-form-sub-fields">
                <input type="text" class="wpuf-file-field" value="" name="wpuf_pf_field[social_icon][]">

                <a href="#" class="button wpuf-file-upload"><?php _e('Upload Icon', 'wpuf-pro'); ?></a>

                <span class="wpuf-social-url">
                    <label><?php _e('Profile URL', 'wpuf-pro'); ?></label>

                    <select name="wpuf_pf_field[social_url][]">
                        <option value="">- select -</option>
                        <optgroup label="<?php _e( 'Profile Fields', 'wpuf-pro' ); ?>">
                            <?php $this->default_meta_dropdown(); ?>
                        </optgroup>
                        <optgroup label="<?php _e( 'Meta Keys', 'wpuf-pro' ); ?>">
                            <?php $this->custom_meta_key(); ?>
                        </optgroup>
                    </select>
                </span>

                <span class="wpuf-social-actions">
                    <a href="#" data-social_field_type="#wpuf-extr-social-field" class="social-row-add button">+</a>
                    <a href="#" data-close_social="rmv_social" class="del-social button">-</a>
                </span>
            </div>
        </div>
        <?php
    }

    /**
     *
     *
     */
    function post_type( $dbkey = '', $user_meta = '' ) {

        $post_type = get_post_types();

        unset( $post_type['attachment'] );
        unset( $post_type['revision'] );
        unset( $post_type['nav_menu_item'] );
        unset( $post_type['wpuf_forms'] );
        unset( $post_type['wpuf_profile'] );

        foreach ( $post_type as $key => $val ) {

            if ( isset( $user_meta['post_type'] ) && strtolower( $user_meta['post_type'] ) == strtolower( $key ) ) {
                $select = 'selected';
            } else {
                $select = '';
            }
            ?>

            <option <?php echo $select; ?> value="<?php echo $key; ?>"><?php echo $val; ?></option>

            <?php
        }
    }

    /**
     *
     *
     */
    function custom_meta_key( $dbkey = '', $user_meta = '' ) {
        global $wpdb;
        $query = $wpdb->get_results( "SELECT DISTINCT(meta_key) FROM {$wpdb->usermeta}
            WHERE
            meta_key != 'admin_color'
            AND meta_key != 'wp_user_level'
            AND meta_key != 'wp_capabilities'
            AND meta_key != 'user_role'
            AND meta_key != 'dismissed_wp_pointers'
            AND meta_key != 'users_per_page'
            AND meta_key != 'wp_dashboard_quick_press_last_post_id'
            AND meta_key != 'wp_post_formats_post'
            AND meta_key != 'wp_nav_menu_recently_edited'
            AND meta_key != 'use_ssl'
            AND meta_key NOT LIKE 'closedpostboxes%'
            AND meta_key NOT LIKE 'meta-box-order_%'
            AND meta_key NOT LIKE 'metaboxhidden_%'
            AND meta_key NOT LIKE 'screen_layout_%'
            AND meta_key NOT LIKE 'wp_user-settings%'", ARRAY_A
        );

        $fields = array(
            'Username' => 'user_login',
            'First Name' => 'first_name',
            'Last Name' => 'last_name',
            'Nickname' => 'nickname',
            'E-mail' => 'user_email',
            'Website' => 'user_url',
            'Biographical Info' => 'description'
        );

        foreach ($query as $val) {
            $option_val = array_diff( $val, $fields );

            if ( count( $option_val ) > 0 ) {
                ?>
                <option value="<?php echo esc_attr( $option_val['meta_key'] ); ?>"<?php selected( $dbkey, $option_val['meta_key'] ); ?>><?php echo $option_val['meta_key']; ?>
                <?php
            }
        }
    }

    /**
     *
     *
     */
    function default_meta_dropdown( $dbkey = '', $user_meta = '' ) {
        $fields = array(
            'Username' => 'user_login',
            'First Name' => 'first_name',
            'Last Name' => 'last_name',
            'Display Name' => 'display_name',
            'Nickname' => 'nickname',
            'E-mail' => 'user_email',
            'Website' => 'user_url',
            'Biographical Info' => 'description',
        );

        foreach ($fields as $key => $val) {
            ?>
            <option value="<?php echo esc_attr( $val ); ?>"<?php selected( $dbkey, $val ); ?>><?php echo $key; ?></option>
            <?php
        }
    }

    function actions() {
        ?>
        <div class="wpuf-actions">
            <span class="wpuf-drag-drop">
                <img src="<?php echo plugins_url( 'images/move.png', __FILE__ ); ?>">
            </span>
            <span class="wpuf-cross"><?php _e( 'X', 'wpuf-pro' ); ?></span>
        </div>
        <?php
    }


}


function wpuful_del() {
    delete_option('wpuf_userlisting');
    echo "done";
}
