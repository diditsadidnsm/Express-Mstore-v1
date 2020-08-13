<?php
/*
Content Filter Class
*/

class WPUF_Content_Filter {

    public function __construct(){
        add_filter( 'wpuf_settings_sections', array( $this, 'content_filter_tab' ) );
        add_filter( 'wpuf_settings_fields', array( $this, 'content_filter_settings_content' ) );

        add_filter( 'wpuf_add_post_validate', array ( $this, 'filter_post' ) );

    }

    /* Add Content Restriction tab to settings */
    public function content_filter_tab( $settings ) {

        $filter_settings = array(
            array(
                'id'    => 'wpuf_content_restriction',
                'title' => __( 'Content Filtering', 'wpuf' ),
                'icon'  => 'dashicons-admin-network'
            )
        );

        return array_merge( $settings, $filter_settings);
    }

    /* Add Content Restriction settings tab contents */
    public function content_filter_settings_content( $settings_fields ) {

        $filter_settings_content = array(
            'wpuf_content_restriction' => array(
                array(
                    'name'    => 'enable_content_filtering',
                    'label'   => __( 'Enable Content Filtering', 'wpuf-pro' ),
                    'desc'    => __( 'Enable Content Filtering in frontend', 'wpuf-pro' ),
                    'type'    => 'checkbox',
                    'default' => 'off',
                ),
                array(
                    'name'     => 'keyword_dictionary',
                    'label'    => __( 'Keyword Dictionary', 'wpuf-pro' ),
                    'desc'     => __( 'Enter Keywords to Remove. Separate keywords with commas.', 'wpuf-pro' ),
                    'type'     => 'textarea',
                ),
                array(
                    'name'     => 'filter_contents',
                    'label'    => __( 'Filter main content', 'wpuf-pro' ),
                    'desc'     => __( 'Choose which content to filter.', 'wpuf-pro' ),
                    'type'     => 'multicheck',
                    'options'  => array(
                        'post_title'   => __( 'Post Titles', 'wpuf-pro' ),
                        'post_content' => __( 'Post Content', 'wpuf-pro' ),
                    ),
                    'default'  => array( 'post_content', 'post_title' ),
                ),
            )
        );

        return array_merge( $settings_fields, $filter_settings_content );
    }

    /**
     * Helper method to filter post
     *
     * @return bool|string
     */
    public function filter_post() {
        $filter_enabled = wpuf_get_option( 'enable_content_filtering', 'wpuf_content_restriction', 'off' );

        if ( 'off' == $filter_enabled ) {
            return;
        }

        $db_search_string = wpuf_get_option( 'keyword_dictionary', 'wpuf_content_restriction' );

        $keywords = array_map( 'trim', explode( ',', $db_search_string ) );
        $keywords = array_unique( $keywords );

        $postdata = $_POST;
        $blocked_words = $this->block_submission( $postdata, $keywords );

        if ( !empty( $blocked_words ) ) {
            if ( is_array( $blocked_words ) ) {
                return 'You are not allowed to use these words: ' . '<br><strong>' . implode( '</strong>,<strong>', $blocked_words ) . '</strong>' . '!';
            }
            return 'You have used following blocked words: ' . $blocked_words . '!';
        }

        return false;

    }

    /**
     * Helper function which blocks the dictionary words
     *
     * @param $postdata
     * @param $keywords
     * @return array|bool
     */

    public function block_submission( $postdata, $keywords ) {

        $contents_to_filter = wpuf_get_option( 'filter_contents', 'wpuf_content_restriction' );
        $blocked = array();

        if ( !isset( $contents_to_filter['post_content'] ) ) {
            unset( $postdata['post_content'] );
        }
        if ( !isset( $contents_to_filter['post_title'] ) ) {
            unset( $postdata['post_title'] );
        }

        for( $i = 0; $i < count( $keywords ); $i++ ) {
            if ( isset( $postdata['post_title'] ) ) {
                if ( stripos( $postdata['post_title'], $keywords[$i] ) !== false ) {
                    $blocked[] = $keywords[$i];
                }
            }
            if ( isset( $postdata['post_content'] ) ){
                if ( stripos( $postdata['post_content'], $keywords[$i] ) !== false ) {
                    $blocked[] = $keywords[$i];
                }
            }
        }

        if ( !empty( $blocked ) ) {
            return array_unique( $blocked );
        }

        return false;
    }

}
