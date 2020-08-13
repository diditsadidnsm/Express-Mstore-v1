<?php
/**
 * Functions to provide support for the One Click Demo Import plugin (wordpress.org/plugins/one-click-demo-import)
 *
 * @package EduExpert
 */

/**
 * Set import files
 */
function eduexpert_set_import_files() {
    return array(
        array(
            'import_file_name'           => __('Dummy-data', 'eduexpert'),
            'local_import_file'          => trailingslashit( get_template_directory() ) . 'dummy-data/dummy-data.xml',
        ),
    );
}
add_filter( 'pt-ocdi/import_files', 'eduexpert_set_import_files' );

/**
 * Define actions that happen after import
 */
function eduexpert_actions_after_import() {

	//Assign the menu
    $main_menu = get_term_by( 'name', 'Menu 1', 'nav_menu' );
    set_theme_mod( 'nav_menu_locations', array(
            'primary' => $main_menu->term_id,
        )
    );

    //Asign the static front page and the blog page
    $front_page = get_page_by_title( 'Home' );
    $blog_page  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page -> ID );
    update_option( 'page_for_posts', $blog_page -> ID );

    //Assign the Front Page template
    update_post_meta( $front_page -> ID, '_wp_page_template', 'template-files/page_home.php' );

}
add_action( 'pt-ocdi/after_import', 'eduexpert_actions_after_import' );

/**
* Remove branding
*/
add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );