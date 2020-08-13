<?php
/**
  Plugin Name: User Directory
  Plugin URI: https://wedevs.com/products/plugins/wp-user-frontend-pro/user-listing-profile/
  Thumbnail Name: wpuf-ul.png
  Description: Handle user listing and user profile in frontend
  Version: 1.1.1
  Author: weDevs
  Author URI: https://wedevs.com
  License: GPL2
 */

/**
 * User Listing class for WP User Frontend PRO
 *
 * @author weDevs <info@wedevs.com>
 */
class WPUF_User_Listing {

    private $shortcode_name = 'wpuf_user_listing';
    private $unique_meta;
    private $page_url;
    private $count_word = 10;
    private $avatar_size = 128;
    private $settings;
    private $total;

    function __construct() {

        define( 'WPUF_UD_ROOT', dirname( __FILE__ ) );

        add_action( 'init', array($this,'localization_setup' ) );
        add_filter( 'wpuf_ud_nav_urls', array( $this, 'profile_nav_menus' ) , 9 );
        add_action( 'wpuf_ud_profile_about', array( $this, 'user_directory_profile' ) );

        if ( is_admin() ) {
            require_once dirname (__FILE__) .'/userlisting-admin.php';
            new WPUF_Userlisting_Admin();
        } else {
            add_shortcode( $this->shortcode_name, array( $this, 'wpuf_user_listing_init' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        }

        register_activation_hook( __FILE__, array( $this, 'install_plugin' ) );

        add_filter( 'wpuf_page_shortcodes', array( $this, 'wpuf_add_user_listing_shortcode' ) );
    }

    /**
     * Initialize plugin for localization
     *
     * @uses load_plugin_textdomain()
     */
    public function localization_setup() {
        load_plugin_textdomain( 'wpuf_userlisting', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    function enqueue_scripts() {
        wp_enqueue_style( 'wpuf-user-listing', plugins_url( 'css/profile-listing.css', __FILE__ ) );
    }

    function install_plugin() {
        global $wp_roles;

        $role = array();
        if ( !$wp_roles ) {
            $wp_roles = new WP_Roles();
        }

        foreach ($wp_roles->get_names() as $key => $role_name) {
            $role[] = $key;
        }

        $current_user_role = array_merge( $role, array( 'guest' ) ); // add "guest" on viewer roles
        $user_listing      = $this->get_options();

        // bail out if existing options already there
        if ( $user_listing ) {
            return;
        }

        $query = array(
            'fields' => array(
                array(
                    'type' => 'section',
                    'label' => __( 'Username', 'wpuf-pro'),
                    'meta' => 'user_login',
                    'all_user_role' => $role,
                    'current_user_role' => $current_user_role,
                    'in_table' => true
                ),
                array(
                    'type' => 'meta',
                    'label' => __( 'First Name', 'wpuf-pro'),
                    'meta' => 'first_name',
                    'all_user_role' => $role,
                    'current_user_role' => $current_user_role,
                    'in_table' => true
                ),
                array(
                    'type' => 'meta',
                    'label' => __( 'Last Name', 'wpuf-pro'),
                    'meta' => 'last_name',
                    'all_user_role' => $role,
                    'current_user_role' => $current_user_role,
                    'in_table' => true
                ),
                array(
                    'type' => 'meta',
                    'label' => __( 'Nickname', 'wpuf-pro'),
                    'meta' => 'nickname',
                    'all_user_role' => $role,
                    'current_user_role' => $current_user_role,
                ),
                array(
                    'type' => 'meta',
                    'label' => __( 'E-mail', 'wpuf-pro'),
                    'meta' => 'user_email',
                    'all_user_role' => $role,
                    'current_user_role' => $current_user_role,
                ),
                array(
                    'type' => 'meta',
                    'label' => __( 'Website', 'wpuf-pro'),
                    'meta' => 'user_url',
                    'all_user_role' => $role,
                    'current_user_role' => $current_user_role,
                ),
                array(
                    'type' => 'meta',
                    'label' => __( 'Biographical Info', 'wpuf-pro'),
                    'meta' => 'description',
                    'all_user_role' => $role,
                    'current_user_role' => $current_user_role,
                ),
            ),
            'settings' => array(
                'avatar' => true
            )
        );

        update_option( 'wpuf_userlisting', $query );
    }

    /**
     * Callback method for WP User Frontend submenu
     *
     * @since 2.5
     *
     * @return void
     */
    function admin_menu_top() {
        $capability = wpuf_admin_role();

        $profile_builder_page = add_submenu_page( 'wp-user-frontend', __( 'Profile Builder', 'wpuf-pro' ), __( 'Profile Builder' ), $capability, 'wpuf-profile-builder', array( $this, 'wpuf_profile_builder_page' ) );
    }

    /**
     * Callback method for Profile Forms submenu
     *
     * @since 2.5
     *
     * @return void
     */
    function wpuf_profile_builder_page() {
        $add_new_page_url = admin_url( 'admin.php?page=wpuf-profile-builder' );
        ?>
            <div class="wrap">
                <?php do_action( 'wpuf-admin-profile-builder' ); ?>
                <?php include WPUF_ROOT . '/admin/form-builder/views/form-builder.php'; ?>
            </div>
        <?php
        // require_once WPUF_UD_ROOT . '/views/profile-form.php';
    }

    function profile_styles() {
        echo '<style type="text/css">
            .column-user_role { width:12% !important; overflow:hidden }
        </style>';
    }

    function wpuf_add_user_listing_shortcode( $array ) {
        $array['user-listing'] = array(
                'title'   => __( 'User Listing', 'wpuf-pro' ),
                'content' => '[wpuf_user_listing]'
            );
        return $array;
    }

    function is_sef_url_active() {
        global $wp_rewrite;

        if ( empty( $wp_rewrite->permalink_structure ) ) {
            return false;
        }

        return true;
    }

    function search_meta_field() {
        $user_meta      = $this->get_options();
        $this->settings = isset( $user_meta['settings'] ) ? $user_meta['settings'] : array();

        $search_meta = array();
        foreach ($user_meta['fields'] as $key => $val) {
            if ( $val['type'] == 'meta' && ( isset( $val['search_by'] ) && $val['search_by'] == 'yes' ) ) {
                $meta               = $this->get_meta($val);
                $search_meta[$meta] = $val['label'];
            }
        }

        if ( !$search_meta ) {
            $search_meta = array(
                'user_login'   => __( 'Username', 'wpuf-pro' ),
                'display_name' => __( 'Name', 'wpuf-pro' ),
            );
        }

        return $search_meta;
    }

    function sort_meta_field() {
        $user_meta      = $this->get_options();
        $this->settings = isset( $user_meta['settings'] ) ? $user_meta['settings'] : array();

        $sort_meta = array();
        foreach ($user_meta['fields'] as $key => $val) {
            if ( $val['type'] == 'meta' && ( isset( $val['sort_by'] ) && $val['sort_by'] == 'yes' ) ) {
                $meta               = $this->get_meta($val);
                $sort_meta[$meta] = $val['label'];
            }
        }

        if ( !$sort_meta ) {
            $sort_meta = array(
                'user_login'   => __( 'Username', 'wpuf-pro' ),
                'display_name' => __( 'Name', 'wpuf-pro' ),
            );
        }

        return $sort_meta;
    }

    function user_listing_search() {
        $search_meta       = $this->search_meta_field();
        $search_by         = isset( $_GET['search_by'] ) ? esc_attr( $_GET['search_by'] ) : '';
        $orderby           = isset( $_GET['order_by'] ) ? $_GET['order_by'] : 'login';
        $order             = isset( $_GET['order'] ) ? $_GET['order'] : 'ASC';
        $search_query      = isset( $_GET['search_field'] ) ? esc_attr( $_GET['search_field'] ) : '';

        $orderby_parameters = array(
            'user_login' => __( 'User Login', 'wpuf-pro'),
            'ID' => __( 'User ID', 'wpuf-pro'),
            'display_name' => __( 'Display Name', 'wpuf-pro'),
            'user_name' => __( 'User Name', 'wpuf-pro'),
            'user_nicename' => __( 'Nicename', 'wpuf-pro'),
            'user_registered' => __( 'Registered Date', 'wpuf-pro'),
            'post_count' => __( 'Post Count', 'wpuf-pro'),
        );

        $order_parameters = array(
            'ASC' => __( 'ASC', 'wpuf-pro'),
            'DESC' => __( 'DESC', 'wpuf-pro'),
        );

        ?>
        <form method="get" action="">
            <?php
                if( !$this->is_sef_url_active() ) { ?>
                    <input type="hidden" value="<?php the_ID(); ?>" name="page_id">
                <?php } ?>

            <label>
                <?php esc_attr_e( 'Search by: ', 'wpuf-pro' ); ?>
                <select class="search-by" name="search_by">
                    <option value="all"><?php _e("- all -", "wpuf-pro"); ?></option>
                    <?php
                    foreach($search_meta as $meta_key => $label ) {?>
                        <option value="<?php echo $meta_key; ?>" <?php echo $meta_key == $search_by ? 'selected="selected"' : ''; ?>><?php echo $label; ?></option>
                        <?php
                    }?>
                </select>
                <?php esc_attr_e( 'Orderby: ', 'wpuf-pro' ); ?>
                <select class="wpuf-users-order-by" name="order_by">
                    <?php foreach ($orderby_parameters as $key => $label ): ?>
                        <option value="<?php echo $key; ?>" <?php echo $key == $orderby ? 'selected="selected"' : ''; ?>><?php echo $label; ?></option>
                    <?php endforeach ?>
                </select>
                <?php esc_attr_e( 'Order: ', 'wpuf-pro' ); ?>
                <select class="wpuf-users-order" name="order">
                    <?php foreach ($order_parameters as $key => $label ): ?>
                        <option value="<?php echo $key; ?>" <?php echo $key == $order ? 'selected="selected"' : ''; ?>><?php echo $label; ?></option>
                    <?php endforeach ?>
                </select>
                <input type="text" placeholder="<?php esc_attr_e( 'Search here', 'wpuf-pro' ); ?>" name="search_field" value="<?php echo $search_query; ?>">
            </label>
            <input type="submit" class="button" name="wpuf_user_search" value="<?php esc_attr_e( 'Search', 'wpuf-pro' ); ?>">
        </form>

        <?php
    }

    function wpuf_user_listing_init( $atts ) {
        extract( shortcode_atts( array('role' => 'all', 'per_page' => '6', 'roles_exclude' => '' ), $atts ) );

        $user_id        = isset( $_GET['user_id'] ) ? absint( $_GET['user_id'] ) : 0;
        $this->page_url = get_permalink();
        $this->per_page = $per_page;
        $this->roles_exclude = $roles_exclude;

        ob_start();

        if ( $user_id ) {
            $this->show_profile( $user_id );
        } else {
            $all_user = $this->get_all_user( $role );
            $this->unique_meta = $this->unique_meta_field();
            $this->user_listing_search();
            ?>
            <div class="user-listing">
                <?php $this->user_listing_content($all_user); ?>
            </div>
            <?php

            echo $this->pagination();
        }

        return ob_get_clean();
    }

    function user_listing_content($all_user) {
        $user_listing_template = wpuf_get_option( 'user_listing_template', 'user_directory', 'list' );

        $list_class = '';
        switch ($user_listing_template) {
            case 'list1':
                $list_class = ' list-column-2 list-style-2';
                break;
            case 'list2':
                $list_class = ' list-column-3 list-style-3';
                break;
            case 'list3':
                $list_class = ' list-column-3 list-style-4';
                break;
            case 'list4':
                $list_class = ' list-column-4 list-style-3';
                break;
            case 'list5':
                $list_class = ' list-column-4 list-style-4';
                break;
        }

        switch ( $user_listing_template ) {
            case 'list':
                ?>
                <table class="wpuf-userlisting-table">
                   <?php $this->table_header(); ?>
                   <?php $this->list_content($all_user); ?>
                </table>
                <?php
                break;

            case 'list1':
            case 'list2':
            case 'list3':
            case 'list4':
            case 'list5':
                ?>
                <div class="user-list <?php echo $list_class; ?>">
                   <?php $this->list_content($all_user); ?>
                </div>
                <?php
                break;

            default:
                ?>
                <table class="wpuf-userlisting-table">
                   <?php $this->table_header(); ?>
                   <?php $this->list_content($all_user); ?>
                </table>
                <?php
                break;
        }
    }

    function pagination() {
        $pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
        $num_of_pages = ceil( $this->total / $this->per_page );
        $page_links = paginate_links( array(
            'base' => add_query_arg( 'pagenum', '%#%' ),
            'format' => '',
            'prev_text' => __( '&laquo;', 'aag' ),
            'next_text' => __( '&raquo;', 'aag' ),
            'total' => $num_of_pages,
            'current' => $pagenum
        ) );

        if ( $page_links ) {
            return '<div class="wpuf-pagination">' . $page_links . '</div>';
        }
    }

    function get_options() {
        return get_option( 'wpuf_userlisting', array() );
    }

    function table_header() {
        $user_meta = $this->get_options();
        $unique_meta = $this->unique_meta;

        if( isset( $user_meta['settings']['avatar'] ) && $user_meta['settings']['avatar'] == true ) { ?>

            <th><?php _e('Avatar', 'wpuf-pro'); ?></th>

            <?php
        }

        foreach( $unique_meta as $key=>$val) {
            ?>
                <th><?php echo $val; ?></th>
            <?php
        }
        ?>
        <th>&nbsp;</th>
        <?php
    }

    function list_content($all_user) {
        foreach( $all_user as $key => $val ) {
            $this->list_content_single( $val );
        }
    }

    function list_content_single( $user ) {
        $unique_meta           = $this->unique_meta;
        $user_listing_template = wpuf_get_option( 'user_listing_template', 'user_directory', 'list' );
        $avatar_size           = wpuf_get_option( 'avatar_size', 'user_directory', '160' );
        $user_status           = $this->is_approved( $user->ID );

        if ( !$user_status ) {
            return;
        }

        if ( 'list' == $user_listing_template ) {
            echo '<tr>';
            if( isset( $this->settings['avatar'] ) && $this->settings['avatar'] == true ) {
                ?>
                <td><?php  echo get_avatar( $user->user_email, 40); ?></td>
                <?php
            }

            foreach($unique_meta as $meta_key => $label ) {
                ?>
                <td>
                    <?php
                        if (is_array( $user->$meta_key ) && !empty( $user->$meta_key )) {
                            $output  = '<p>';
                            $output .= implode(', ', $user->$meta_key);
                            $output .= '</p>';

                            echo $output;
                        }else{
                            echo $user->$meta_key;
                        }
                    ?>
                </td>
                <?php
            }
            ?>

            <td><a href="<?php echo $this->get_user_link( $user->ID ); ?>"><?php _e('View Profile', 'wpuf-pro'); ?></a></td>

            </tr>
        <?php
        } else {
            ?>
            <div class="user-box">
                <a href="<?php echo $this->get_user_link( $user->ID ); ?>" class="box-container">
                    <div class="user-pic">
                        <?php if( isset( $this->settings['avatar'] ) && $this->settings['avatar'] == true ) {
                            echo get_avatar( $user->user_email, $avatar_size);
                        } ?>
                    </div>
                    <div class="user-info">
                        <?php
                        foreach($unique_meta as $meta_key => $label ) {
                            if ( $user->$meta_key ) {
                                if ( 'display_name' == $meta_key ) {
                                    ?>
                                    <h4 class="user-name"><?php echo $user->$meta_key; ?></h4>
                                    <?php
                                } elseif ( is_array( $user->$meta_key ) && !empty( $user->$meta_key ) ) {
                                    $output  = '<p>';
                                    $output .= $label . ': ' . implode(', ', $user->$meta_key);
                                    $output .= '</p>';

                                    echo $output;
                                } else {
                                    ?>
                                    <p><?php echo $label . ': ' . $user->$meta_key; ?></p>
                                    <?php
                                }
                            }
                        } ?>
                    </div>
                </a>
            </div>
            <?php
        }
    }

    function get_all_user( $user_role ) {
        $meta_user_results = array();
        $all_users         = array();
        $pagenum           = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
        $offset            =  ( $pagenum - 1 ) * $this->per_page;
        $orderby           = isset( $_GET['order_by'] ) ? $_GET['order_by'] : 'login';
        $order             = isset( $_GET['order'] ) ? $_GET['order'] : 'ASC';

        $args = array(
            'count_total'   => true,
            'number'        => $this->per_page,
            'offset'        => $offset,
            'role'          => 'all' != $user_role ? $user_role : '',
            'role__not_in'  => explode(",", $this->roles_exclude),
            'orderby'       => $orderby,
            'order'         => $order
        );

        if ( isset( $_GET['wpuf_user_search'] ) && !empty( $_GET['search_field'] ) ) {
            $search_query = trim( strip_tags( $_GET['search_field'] ) );

            if ( isset( $_GET['search_by'] ) && 'all' != $_GET['search_by'] && in_array( $_GET['search_by'], array( 'ID', 'user_login', 'user_nicename', 'user_email', 'user_url', 'display_name' ) ) ) {

                $args['search']         = '*' . $search_query . '*';
                $args['search_columns'] = array( $_GET['search_by'] );

            } elseif ( isset( $_GET['search_by'] ) && 'all' != $_GET['search_by'] && !in_array( $_GET['search_by'], array( 'ID', 'user_login', 'user_nicename', 'user_email', 'user_url', 'display_name' ) ) ) {

                $args['meta_query'] = array(
                    array(
                        'key'     => $_GET['search_by'],
                        'value'   => $search_query,
                        'compare' => 'LIKE'
                    )
                );

            } else {
                // search in default user fields
                $search_user = get_users( array( 'search' => '*' . $search_query . '*' ) );

                if ( !empty( $search_user ) ) {
                    $args['search'] = '*' . $search_query . '*';
                } else{
                    // search in user meta keys if the data not found in default fields
                    global $wpdb;

                    $select         = "SELECT distinct $wpdb->usermeta.meta_key FROM $wpdb->usermeta";
                    $user_meta_keys = $wpdb->get_results($select);

                    $args['meta_query']['relation'] = 'OR';

                    foreach ( $user_meta_keys as $meta_key ) {
                        $args['meta_query'][] = array(
                            'key'     => $meta_key->meta_key,
                            'value'   => $search_query,
                            'compare' => 'LIKE'
                        );
                    }
                }
            }

        }

        //only user query
        $users        = new WP_User_Query( $args );
        $users_total  = $users->total_users;
        $user_results = $users->get_results();

        //insersection meta and user query result
        foreach ( $user_results as $user_obj ) {
            $role = reset( $user_obj->roles );

            //filter user role
            if ( $user_role != 'all' && $role != strtolower($user_role) ) {
                continue;
            }

            $all_users[$user_obj->ID] = $user_obj;
        }

        unset($args['number']);
        unset($args['offset']);
        $users        = new WP_User_Query( $args );
        $this->total = $users->total_users;

        return $all_users;
    }

    function unique_meta_file() {
        $user_meta = $this->get_options();

        foreach($user_meta['fields'] as $key=>$val) {
            if($val['type'] == 'file') {
                $meta = $this->get_meta_file($val);
            }
        }

        return $meta;
    }

    function get_meta_file($val) {
        foreach( $val as $key=>$meta) {

            if ( $key == 'meta_key' && !empty($meta) && $val['type'] == 'file' ) {
                return $meta;
            } else if( $key == 'default_meta' && !empty($meta) && $val['type'] == 'file' ) {
                return $meta;
            }
        }
    }

    function unique_meta_field() {
        $user_meta      = $this->get_options();
        $this->settings = isset( $user_meta['settings'] ) ? $user_meta['settings'] : array();

        $unique_meta = array();
        foreach ($user_meta['fields'] as $key => $val) {
            if ( $val['type'] == 'meta' && ( isset( $val['in_table'] ) && $val['in_table'] == 'yes' ) ) {
                $meta               = $this->get_meta($val);
                $unique_meta[$meta] = $val['label'];
            }
        }

        if ( !$unique_meta ) {
            $unique_meta = array(
                'user_login'   => __( 'Username', 'wpuf-pro' ),
                'display_name' => __( 'Name', 'wpuf-pro' ),
            );
        }

        return $unique_meta;
    }

    function get_meta($val) {
        return $val['meta'];
    }

    function get_user_link( $user_id ) {
        return add_query_arg( array( 'user_id' => $user_id), $this->page_url );
    }

    function show_profile( $user_id ) {
        $userdata       = get_user_by( 'id', $user_id );
        $current_user   = wp_get_current_user();
        $profile_fields = $this->get_options();
        $this->settings = isset( $profile_fields['settings'] ) ? $profile_fields['settings'] : array();

        if ( !$profile_fields ) {
            return;
        }

        printf( '<a href="%s">%s</a>', get_permalink(), __( '&larr; Back to listing', 'wpuf-pro' ) );


        $user_status         = $this->is_approved( $user_id );
        $invalid_user_notice = __( 'User not found', 'wpuf-pro' );

        if ( !$user_status ) {
            echo '<div class="wpuf-info wpuf-restrict-message">' .$invalid_user_notice . '</div>';
            return;
        }

        echo '<div class="wpuf-user-profile-wrap">';


            $profile_header_template = wpuf_get_option( 'profile_header_template', 'user_directory', 'layout' );

            if ( 'layout' == $profile_header_template ) {

                if ( isset( $this->settings['avatar'] ) && $this->settings['avatar'] === true  ) {
                    ?>
                    <div class="wpuf-user-profile-avatar">
                        <?php echo get_avatar( $userdata->user_email, $this->avatar_size, '', $userdata->display_name); ?>
                    </div>
                    <?php
                }

            } else {
                $user_banner = get_user_meta( $user_id, 'profile_banner', true );
                $banner      = !empty( $user_banner ) ? absint( $user_banner ) : 0;
                $banner_url  = $banner ? wp_get_attachment_url( $banner ) : '';
                $profile_image_size = 'layout1' == $profile_header_template ?  $this->avatar_size : 80;
                ?>
                <div class="single-user-box <?php echo ( 'layout1' == $profile_header_template ) ?  'style-2' : ''; ?>">
                    <div class="banner">
                        <img src="<?php echo esc_url( $banner_url ); ?>" alt="">
                    </div>
                    <div class="user-container">
                        <div class="user-pic"><?php echo get_avatar( $userdata->user_email, $profile_image_size, '', $userdata->display_name); ?></div>
                        <div class="user-info">
                            <h4 class="user-name"><?php echo $userdata->display_name; ?></h4>
                        </div>
                    </div>

                </div>
            <?php }

            if ( class_exists( 'WPUF_User_Activity' ) ) {
                do_action( 'wpuf_ud_profile_sections' );
            } else {
                $this->user_directory_profile();
            }

    }

    function user_file( $field, $user_id ) {

        $images = get_user_meta( $user_id, $field['meta'] );
        $image_size = wpuf_get_option('pro_img_size', 'user_directory');

        echo '<div class="wpuf-profile-value">';

            if ( $images ) {

                echo '<ul class="wpuf-profile-gallery">';

                foreach ( $images as $attachment_id ) {
                    $thumb = wp_get_attachment_image( $attachment_id, $image_size );
                    $full_size = wp_get_attachment_url( $attachment_id );
                    printf( '<li><a href="%s">%s</a></li>', $full_size, $thumb );
                }

                echo '</ul>';

            } else {
                _e( 'Nohting found!', 'wpuf-pro' );
            }

        echo '</div>';
    }

    public static function can_user_see( $profile_role, $field, $user_role ) {

        // bail out if the current user role is not in the list
        if ( !in_array( $profile_role, $field['all_user_role'] ) ) {
            return false;
        }

        // check viewer role
        if ( !in_array( $user_role, $field['current_user_role'] ) ) {
            return false;
        }

        return true;
    }

    function social_list($field, $userdata) {

        echo '<ul class="wpuf-social-links wpuf-profile-value">';
        foreach ( $field['social_icon'] as $key => $icon ) {
            $user_data = $userdata->data;
            $social_key = $field['social_url'][$key];
            $url = $user_data->$social_key;

            // don't show empty urls
            if ( empty( $url ) ) {
                continue;
            }

            ?>
            <li>
                <a href="<?php echo esc_url( $url ); ?>"><img alt="social icon" src="<?php echo esc_url( $icon ); ?>"></a>
            </li>
            <?php
        }
        echo '</ul>';
    }

    function user_comments( $user_id, $post_type, $comment_count ) {

        $args = array(
            'user_id'       => $user_id,
            'order_by'      => 'post_date',
            'order'         => 'DESC',
            'post_type'     => $post_type,
            'post_status'   => 'publish',
            'number'        => $comment_count,
        );

        $comments = get_comments($args);

        if ( $comments ) {

            echo '<ul class="wpuf-user-comment">';
            foreach( $comments as $key=>$comment ) {
                ?>
                <li>
                    <?php echo wp_trim_words( $comment->comment_content , $this->count_word, '' ); ?>
                    <?php printf( '<a href="%s">%s</a>', get_comment_link($comment), __('...read more', 'wpuf-pro') ); ?>
                </li>
                <?php
            }
            echo '</ul>';

        } else {
            _e( 'No comments found', 'wpuf-pro' );
        }
    }

    function user_post($user_id, $post_type, $post_count) {

        $args = array(
            'author'        => $user_id,
            'post_type'         => $post_type,
            'posts_per_page'    => $post_count,
            'post_status'       => 'publish',
            'orderby'           => 'post_date',
            'order'             => 'DESC'
        );

        $posts = get_posts($args);
        echo '<ul class="wpuf-post-title">';
        foreach($posts as $key=>$obj) {
            ?>
            <li><a href="<?php echo get_permalink( $obj->ID ); ?>"><?php echo $obj->post_title; ?></a></li>
            <?php
        }
        echo '</ul>';

    }

    function profile_nav_menus( $nav_urls ) {
        $user_id = get_current_user_id();
        $nav_urls = array(
            array(
                'url'   => $this->get_user_link( $user_id ),
                'label' => __( 'About', 'wpuf-pro' ),
            ),
        );

        return $nav_urls;
    }

    function user_directory_profile() {

        echo '<ul class="wpuf-user-profile">';
            $user_id     = isset( $_GET['user_id'] ) ? $_GET['user_id'] : '';
            $user_status = $this->is_approved( $user_id );

            if ( !$user_status ) {
                return;
            }

            $userdata          = get_user_by( 'id', $user_id );
            $current_user      = wp_get_current_user();
            $profile_fields    = $this->get_options();
            $this->settings    = isset( $profile_fields['settings'] ) ? $profile_fields['settings'] : array();
            $profile_role      = isset( $userdata->roles[0] ) ? $userdata->roles[0] : '';
            $current_user_role = is_user_logged_in() ? $current_user->roles[0] : 'guest';

            do_action( 'wpuf_user_profile_before_content' );

            foreach ($profile_fields['fields'] as $key => $field) {

                if ( !self::can_user_see( $profile_role, $field, $current_user_role ) ) {
                    continue;
                }

                echo '<li>';

                switch ($field['type']) {
                    case 'meta':
                        if ( 'display_name' == $field['meta'] ) {
                            break;
                        }
                        $meta_key = $this->get_meta( $field );

                        if ( is_array( $userdata->$meta_key ) && !empty( $userdata->$meta_key ) ) {
                            $value = implode(', ', $userdata->$meta_key);
                        } else {
                            $value = trim( $userdata->$meta_key );
                        }

                        ?>
                        <label><?php echo $field['label']; ?>: </label>
                        <div class="wpuf-profile-value">
                            <?php echo $value ? make_clickable( $value ) : ' -- '; ?>
                        </div>
                        <?php
                        break;

                    case 'section':
                        ?>
                        <div class="wpuf-profile-section"><?php echo $field['label']; ?></div>
                        <?php
                        break;

                    case 'post':
                        ?>
                        <label><?php echo $field['label']; ?>:</label>

                        <div class="wpuf-profile-value"><?php $this->user_post($user_id, $field['post_type'], $field['count']); ?></div>
                        <?php
                        break;

                    case 'comment':
                        ?>
                        <label><?php echo $field['label']; ?>:</label>

                        <div class="wpuf-profile-value"><?php $this->user_comments($user_id, $field['post_type'], $field['count']); ?></div>
                        <?php
                        break;

                    case 'social':
                        echo '<label>&nbsp;</label>';
                        $this->social_list($field, $userdata);
                        break;

                    case 'file':
                        ?>
                        <label><?php echo $field['label']; ?>:</label>
                        <?php
                        $this->user_file($field, $user_id);
                        break;

                } // switch

                echo '</li>';
            } // foreach

            do_action( 'wpuf_user_profile_after_content' );
        echo '</ul>';
    }

    /**
     * Return user status
     *
     * @return boolean
     */
    function is_approved( $user_id ) {
        $user_status    = get_user_meta( $user_id, 'wpuf_user_status', true );

        if ( empty( $user_status ) || $user_status == 'approved' ) {
            return true;
        }
        return false;
    }
}

/**
 * Return the instance
 *
 * @return \WPUF_User_Listing
 */
function wpuf_user_listing() {
    if ( !class_exists( 'WP_User_Frontend' ) ) {
        return;
    }

    new WPUF_User_Listing();
}

wpuf_user_listing();
