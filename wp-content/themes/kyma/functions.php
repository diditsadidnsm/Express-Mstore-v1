<?php
/** Theme Name: Kyma
 *  Theme Core Functions and Codes
 **/
require get_template_directory() . '/functions/custom-header.php';
require get_template_directory() . '/functions/menu/default_menu_walker.php';
require get_template_directory() . '/functions/menu/kyma_nav_walker.php';
require_once dirname(__FILE__) . '/default_options.php';
require_once get_template_directory() . '/inc/class-tgm-plugin-activation.php';
require get_template_directory() . '/functions/customize/contact-widgets.php';
require get_template_directory() . '/functions/customize/recent-posts.php';
require_once plugin_dir_path( __FILE__ ) . '/inc/plugins/kyma-blocks/plugin.php';
if (!class_exists('Kirki')) {
    include_once dirname(__FILE__) . '/inc/kirki/kirki.php';
}
function kyma_customizer_config()
{
    $args = array(
        'url_path'     => get_template_directory_uri() . '/inc/kirki/',
        'capability'   => 'edit_theme_options',
        'option_type'  => 'option',
        'option_name'  => 'kyma_theme_options',
        'compiler'     => array(),
        'color_accent' => '#27bebe',
        'width'        => '23%',
        'description'  => __('Visit our site for more great Products.If you like this theme please rate us 5 star', 'kyma'),
    );
    return $args;
}

add_filter('kirki/config', 'kyma_customizer_config');
require get_template_directory() . '/customizer.php';
add_action('after_setup_theme', 'kyma_theme_setup');
global $kyma_theme_options;
function kyma_theme_setup()
{
    global $content_width;
    //content width
    if (!isset($content_width)) {
        $content_width = 835;
    }
    //px
    //supports featured image
    add_theme_support('post-thumbnails');
    load_theme_textdomain('kyma', get_template_directory() . '/lang');
    // image resize according to image layout
    add_image_size('kyma_home_post_image', 360, 231, true);
	add_image_size('kyma_home_post_image_fluid', 605, 377, true);
    add_image_size('kyma_related_post_thumb', 265, 170, true);
    add_image_size('kyma_home_post_thumb', 334, 215, true);
    add_image_size('kyma_home_post_full_thumb', 456, 293, true);
    add_image_size('kyma_single_post_image', 835, 428, true);
    add_image_size('kyma_single_post_full', 1140, 585, true);
    add_image_size('kyma_recent_widget_thumb', 90, 60, true);
    add_image_size('kyma_slider_post', 1349, 540, true);
    add_image_size('kyma_home_port_thumb', 360, 360, true);
    // This theme uses wp_nav_menu() in one location.
    register_nav_menu('primary', __('Primary Menu', 'kyma'));
    register_nav_menu('secondary', __('Secondary Menu', 'kyma'));
    // theme support
    $args = array('default-color' => '#ffffff');
    add_theme_support('custom-background', $args);
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('automatic-feed-links');
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    add_theme_support('title-tag');
    add_theme_support('custom-logo', array(
        'height'      => 75,
        'width'       => 150,
        'flex-width'  => true,
        'flex-height' => true,
        'header-text' => array('site-title', 'site-description'),
    ));

    // Add support for full and wide align images.
    add_theme_support( 'align-wide' );

    // Add support for editor styles.
    add_theme_support( 'editor-styles' );

    // Enqueue editor styles.
    add_editor_style( 'style-editor.css' );

    add_theme_support( 'responsive-embeds' );
    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
}

add_action('wp_enqueue_scripts', 'kyma_enqueue_style');
function kyma_enqueue_style()
{   $kyma_theme_options = kyma_theme_options();
    wp_enqueue_style('kyma-plugins', get_template_directory_uri() . '/css/plugins.css');
    wp_enqueue_style('Kyma', get_stylesheet_uri());
    wp_enqueue_style('responsive', get_template_directory_uri() . '/css/responsive.css');
    if($kyma_theme_options['color_scheme']!=""){
        wp_enqueue_style('kyma-color-scheme', get_template_directory_uri() . '/css/colors/'.$kyma_theme_options['color_scheme'].'.css');
    }

    if (is_singular()) {
        wp_enqueue_script("comment-reply");
    }

    wp_enqueue_style('Oswald', '//fonts.googleapis.com/css?family=Oswald:400,700,300');
    wp_enqueue_style('lato', '//fonts.googleapis.com/css?family=Lato:300,300italic,400italic,600,600italic,700,700italic,800,800italic');
    wp_enqueue_style('open-sans', '//fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic');
    global $kyma_theme_options;
    if ($kyma_theme_options['portfolio_three_column']) {
        $custom_css = '@media (min-width: 992px) { .wl-gallery{ width:33.33% !important;} }';
        wp_add_inline_style('Kyma', $custom_css);
    }
    if ($kyma_theme_options['site_layout'] == 'site_boxed') {
        $custom_css = '#kyma_owl_slider .owl_slider_con { left: 57%; }';
        wp_add_inline_style('Kyma', $custom_css);
    }
}

add_action('wp_footer', 'kyma_enqueue_in_footer');
function kyma_enqueue_in_footer()
{   $kyma_theme_options = kyma_theme_options();
    wp_enqueue_script('plugins', get_template_directory_uri() . '/js/plugins.js', array('jquery'));
    wp_enqueue_script('functions', get_template_directory_uri() . '/js/functions.js', array('jquery'));

    $kyma_load_post_button = intval( $kyma_theme_options['show_load_more_btn'] );
	if ( $kyma_load_post_button ) {
		wp_enqueue_script( 'masonry', '', array('jquery'), '16082019', true );
		$kyma_blog_post_count   = absint( $kyma_theme_options['home_load_post_num'] );
		$kyma_blog_no_more_post_text = $kyma_theme_options['blog_no_more_post'];
		wp_enqueue_script( 'load-posts', get_template_directory_uri() . '/js/load-posts.js', array(), '16082019', true );
		wp_localize_script( 'load-posts', 'load_more_posts_variable', array(
			'ajaxurl'  => admin_url( 'admin-ajax.php' ),
			'ppp'=> $kyma_blog_post_count,
			'noposts'  => $kyma_blog_no_more_post_text,
		) );
	}
    wp_localize_script('functions', 'slider', array(
        'effect'    => $kyma_theme_options['home_slider_effect'],
    ));
}

// Read more tag to formatting in blog page
function kyma_content_more($read_more)
{
    return '<div class=""><a class="main-button" href="' . get_permalink() . '">' . __('Read More', 'kyma') . '<i class="fa fa-angle-right"></i></a></div>';
}
add_filter('the_content_more_link', 'kyma_content_more');

// Replaces the excerpt "more" text by a link
function kyma_excerpt_more($more){
    return '';
}
add_filter('excerpt_more', 'kyma_excerpt_more');

function kyma_the_excerpt( $excerpt ){
    $post = get_post();
	$excerpt .= '<a class="btn_a" href="' . esc_url(get_permalink($post->ID)) . '"><span><i class="in_left fa fa-angle-right"></i><span>' . __('Details', 'kyma') . '</span><i class="in_right fa fa-angle-right"></i></span></a>';
    return $excerpt;
}
add_filter( 'the_excerpt', 'kyma_the_excerpt');

function kyma_content() {
	$limit = 55;
	$post = get_post();
	$my_post = get_post($post->ID);
	$content = $my_post->post_content;
	 if (str_word_count($content)>=$limit) {
		 $more = '</br></br><a class="btn_a" href="' . esc_url(get_permalink()) . '"><span><i class="in_left fa fa-angle-right"></i><span>' . __('Details', 'kyma') . '</span><i class="in_right fa fa-angle-right"></i></span></a>';
		 $content = wp_trim_words( $content , $limit , $more);
	 }
	 return $content;
}

/*
 * Kyma widget area
 */
add_action('widgets_init', 'kyma_widget');
function kyma_widget()
{
    /*sidebar*/
    $kyma_theme_options = kyma_theme_options();
    $col                = 12 / (int) $kyma_theme_options['footer_layout'];
    register_sidebar(array(
        'name'          => __('Sidebar Widget Area', 'kyma'),
        'id'            => 'sidebar-widget',
        'description'   => __('Sidebar widget area', 'kyma'),
        'before_widget' => '<div class="widget_block">',
        'after_widget'  => '</div>',
        'before_title'  => '<h6 class="widget_title">',
        'after_title'   => '</h6>',
    ));
    register_sidebar(array(
        'name'          => __('Footer Widget Area', 'kyma'),
        'id'            => 'footer-widget',
        'description'   => __('Footer widget area', 'kyma'),
        'before_widget' => '<div class="footer-widget-col col-md-' . $col . '">
                                <div class="footer_row">',
        'after_widget'  => '</div></div>',
        'before_title'  => '<h6 class="footer_title">',
        'after_title'   => '</h6>',
    ));
}

/* Breadcrumbs  */
function kyma_breadcrumbs()
{
    /* === OPTIONS === */
    $text['home']     = __('Home','kyma'); // text for the 'Home' link
    $text['category'] = __('Category "%s"','kyma'); // text for a category page
    $text['search']   = __('Search Results for "%s" Query','kyma'); // text for a search results page
    $text['tag']      = __('Posts Tagged "%s"','kyma'); // text for a tag page
    $text['author']   = __('Posted by %s','kyma'); // text for an author page
    $text['404']      = __('Error 404','kyma'); // text for the 404 page
    $text['page']     = __('Page %s','kyma'); // text 'Page N'
    $text['cpage']    = __('Comment Page %s','kyma'); // text 'Comment Page N'
    $wrap_before    = '<div class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">'; // the opening wrapper tag
    $wrap_after     = '</div><!-- .breadcrumbs -->'; // the closing wrapper tag
    $sep            = '<span class="breadcrumbs__separator">&nbsp;&nbsp;›&nbsp;&nbsp;</span>'; // separator between crumbs
    $before         = '<span class="breadcrumbs__current">'; // tag before the current crumb
    $after          = '</span>'; // tag after the current crumb
    $show_on_home   = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $show_home_link = 1; // 1 - show the 'Home' link, 0 - don't show
    $show_current   = 1; // 1 - show current page title, 0 - don't show
    $show_last_sep  = 1; // 1 - show last separator, when current page title is not displayed, 0 - don't show
    /* === END OF OPTIONS === */
    global $post;
    $home_url       = home_url('/');
    $link           = '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
    $link          .= '<a class="breadcrumbs__link" href="%1$s" itemprop="item"><span itemprop="name">%2$s</span></a>';
    $link          .= '<meta itemprop="position" content="%3$s" />';
    $link          .= '</span>';
    $parent_id      = ( $post ) ? $post->post_parent : '';
    $home_link      = sprintf( $link, $home_url, $text['home'], 1 );
    if ( is_home() || is_front_page() ) {
        if ( $show_on_home ) echo $wrap_before . $home_link . $wrap_after;
    } else {
        $position = 0;
        echo $wrap_before;
        if ( $show_home_link ) {
            $position += 1;
            echo $home_link;
        }
        if ( is_category() ) {
            $parents = get_ancestors( get_query_var('cat'), 'category' );
            foreach ( array_reverse( $parents ) as $cat ) {
                $position += 1;
                if ( $position > 1 ) echo $sep;
                echo sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
            }
            if ( get_query_var( 'paged' ) ) {
                $position += 1;
                $cat = get_query_var('cat');
                echo $sep . sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
                echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
            } else {
                if ( $show_current ) {
                    if ( $position >= 1 ) echo $sep;
                    echo $before . sprintf( $text['category'], single_cat_title( '', false ) ) . $after;
                } elseif ( $show_last_sep ) echo $sep;
            }
        } elseif ( is_search() ) {
            if ( get_query_var( 'paged' ) ) {
                $position += 1;
                if ( $show_home_link ) echo $sep;
                echo sprintf( $link, $home_url . '?s=' . get_search_query(), sprintf( $text['search'], get_search_query() ), $position );
                echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
            } else {
                if ( $show_current ) {
                    if ( $position >= 1 ) echo $sep;
                    echo $before . sprintf( $text['search'], get_search_query() ) . $after;
                } elseif ( $show_last_sep ) echo $sep;
            }
        } elseif ( is_year() ) {
            if ( $show_home_link && $show_current ) echo $sep;
            if ( $show_current ) echo $before . get_the_time('Y') . $after;
            elseif ( $show_home_link && $show_last_sep ) echo $sep;
        } elseif ( is_month() ) {
            if ( $show_home_link ) echo $sep;
            $position += 1;
            echo sprintf( $link, get_year_link( get_the_time('Y') ), get_the_time('Y'), $position );
            if ( $show_current ) echo $sep . $before . get_the_time('F') . $after;
            elseif ( $show_last_sep ) echo $sep;
        } elseif ( is_day() ) {
            if ( $show_home_link ) echo $sep;
            $position += 1;
            echo sprintf( $link, get_year_link( get_the_time('Y') ), get_the_time('Y'), $position ) . $sep;
            $position += 1;
            echo sprintf( $link, get_month_link( get_the_time('Y'), get_the_time('m') ), get_the_time('F'), $position );
            if ( $show_current ) echo $sep . $before . get_the_time('d') . $after;
            elseif ( $show_last_sep ) echo $sep;
        } elseif ( is_single() && ! is_attachment() ) {
            if ( get_post_type() != 'post' ) {
                $position += 1;
                $post_type = get_post_type_object( get_post_type() );
                if ( $position > 1 ) echo $sep;
                echo sprintf( $link, get_post_type_archive_link( $post_type->name ), $post_type->labels->name, $position );
                if ( $show_current ) echo $sep . $before . get_the_title() . $after;
                elseif ( $show_last_sep ) echo $sep;
            } else {
                $cat = get_the_category(); $catID = $cat[0]->cat_ID;
                $parents = get_ancestors( $catID, 'category' );
                $parents = array_reverse( $parents );
                $parents[] = $catID;
                foreach ( $parents as $cat ) {
                    $position += 1;
                    if ( $position > 1 ) echo $sep;
                    echo sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
                }
                if ( get_query_var( 'cpage' ) ) {
                    $position += 1;
                    echo $sep . sprintf( $link, get_permalink(), get_the_title(), $position );
                    echo $sep . $before . sprintf( $text['cpage'], get_query_var( 'cpage' ) ) . $after;
                } else {
                    if ( $show_current ) echo $sep . $before . get_the_title() . $after;
                    elseif ( $show_last_sep ) echo $sep;
                }
            }
        } elseif ( is_post_type_archive() ) {
            $post_type = get_post_type_object( get_post_type() );
            if ( get_query_var( 'paged' ) ) {
                $position += 1;
                if ( $position > 1 ) echo $sep;
                echo sprintf( $link, get_post_type_archive_link( $post_type->name ), $post_type->label, $position );
                echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
            } else {
                if ( $show_home_link && $show_current ) echo $sep;
                if ( $show_current ) echo $before . $post_type->label . $after;
                elseif ( $show_home_link && $show_last_sep ) echo $sep;
            }
        } elseif ( is_attachment() ) {
            $parent = get_post( $parent_id );
            $cat = get_the_category( $parent->ID ); $catID = $cat[0]->cat_ID;
            $parents = get_ancestors( $catID, 'category' );
            $parents = array_reverse( $parents );
            $parents[] = $catID;
            foreach ( $parents as $cat ) {
                $position += 1;
                if ( $position > 1 ) echo $sep;
                echo sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
            }
            $position += 1;
            echo $sep . sprintf( $link, get_permalink( $parent ), $parent->post_title, $position );
            if ( $show_current ) echo $sep . $before . get_the_title() . $after;
            elseif ( $show_last_sep ) echo $sep;
        } elseif ( is_page() && ! $parent_id ) {
            if ( $show_home_link && $show_current ) echo $sep;
            if ( $show_current ) echo $before . get_the_title() . $after;
            elseif ( $show_home_link && $show_last_sep ) echo $sep;
        } elseif ( is_page() && $parent_id ) {
            $parents = get_post_ancestors( get_the_ID() );
            foreach ( array_reverse( $parents ) as $pageID ) {
                $position += 1;
                if ( $position > 1 ) echo $sep;
                echo sprintf( $link, get_page_link( $pageID ), get_the_title( $pageID ), $position );
            }
            if ( $show_current ) echo $sep . $before . get_the_title() . $after;
            elseif ( $show_last_sep ) echo $sep;
        } elseif ( is_tag() ) {
            if ( get_query_var( 'paged' ) ) {
                $position += 1;
                $tagID = get_query_var( 'tag_id' );
                echo $sep . sprintf( $link, get_tag_link( $tagID ), single_tag_title( '', false ), $position );
                echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
            } else {
                if ( $show_home_link && $show_current ) echo $sep;
                if ( $show_current ) echo $before . sprintf( $text['tag'], single_tag_title( '', false ) ) . $after;
                elseif ( $show_home_link && $show_last_sep ) echo $sep;
            }
        } elseif ( is_author() ) {
            $author = get_userdata( get_query_var( 'author' ) );
            if ( get_query_var( 'paged' ) ) {
                $position += 1;
                echo $sep . sprintf( $link, get_author_posts_url( $author->ID ), sprintf( $text['author'], $author->display_name ), $position );
                echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
            } else {
                if ( $show_home_link && $show_current ) echo $sep;
                if ( $show_current ) echo $before . sprintf( $text['author'], $author->display_name ) . $after;
                elseif ( $show_home_link && $show_last_sep ) echo $sep;
            }
        } elseif ( is_404() ) {
            if ( $show_home_link && $show_current ) echo $sep;
            if ( $show_current ) echo $before . $text['404'] . $after;
            elseif ( $show_last_sep ) echo $sep;
        } elseif ( has_post_format() && ! is_singular() ) {
            if ( $show_home_link && $show_current ) echo $sep;
            echo get_post_format_string( get_post_format() );
        }
        echo $wrap_after;
    }
}

function kyma_comments($comments, $args, $depth)
{
    extract($args, EXTR_SKIP);
    if ('div' == $args['style']) {
        $tag       = 'div';
        $add_below = 'comment';
    } else {
        $tag       = 'li';
        $add_below = 'div-comment';
    }
    ?>
    <li class="comments single_comment">
    <div class="comment-container comment-box">
        <div class="trees_number">1</div>
        <?php if ($args['avatar_size'] != 0) {
        echo get_avatar($comments, $args['avatar_size']);
    }
    ?>
        <div class="comment_content">
            <h4 class="author_name"><?php printf('%s', esc_attr(get_comment_author()));?></h4>
                <span class="comment_meta">
                    <a href="#">
                        <time
                            datetime="<?php comment_time( 'c' ); ?>"><?php printf(__('%1$s at %2$s', 'kyma'), get_comment_date(), get_comment_time());?></time>
                    </a>
                </span><?php
if ($comments->comment_approved == '0') {
        ?>
            <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'kyma');?></em><br/>
        </div><?php } else {
        ?>
        <div class="comment_said_text">
            <?php comment_text();?>
        </div>
        <?php comment_reply_link(array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'])));
    }
    ?>
    </div></div><?php
}

if (!function_exists('kyma_pagination')) {
    function kyma_pagination()
    {
        $prev_arrow = is_rtl() ? '<i class="<i class="fa fa-angle-right"></i>' : '<i class="fa fa-angle-left"></i>';
        $next_arrow = is_rtl() ? '<i class="fa fa-angle-left"></i>' : '<i class="fa fa-angle-right"></i>';
        global $wp_query;
        $total = $wp_query->max_num_pages;
        $big   = 999999999; // need an unlikely integer
        if ($total > 1) {
            if (!$current_page = get_query_var('paged')) {
                $current_page = 1;
            }

            if (get_option('permalink_structure')) {
                $format = 'page/%#%/';
            } else {
                $format = '&paged=%#%';
            }
            echo paginate_links(array(
                'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format'    => $format,
                'current'   => max(1, get_query_var('paged')),
                'total'     => $total,
                'mid_size'  => 3,
                'type'      => 'list',
                'prev_text' => $prev_arrow,
                'next_text' => $next_arrow,
            ));
        }
    }

}
/* add social links to admin profile */
function add_to_author_profile($contactmethods)
{
    $contactmethods['facebook_profile'] = 'Facebook Profile URL';
    $contactmethods['twitter_profile']  = 'Twitter Profile URL';
    $contactmethods['linkedin_profile'] = 'Linkedin Profile URL';
    $contactmethods['email']   = 'Email';
    return $contactmethods;
}
add_filter('user_contactmethods', 'add_to_author_profile', 10, 1);
/* get image alt and caption text */
function kyma_get_attachment( $attachment_id ) {

    $attachment = get_post( $attachment_id );
    return array(
        'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
        'caption' => $attachment->post_excerpt,
        'description' => $attachment->post_content,
        'href' => get_permalink( $attachment->ID ),
        'src' => $attachment->guid,
        'title' => $attachment->post_title
    );
}
/* TGMPA register */
add_action('tgmpa_register', 'kyma_register_required_plugins');
function kyma_register_required_plugins()
{
    /*
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(
        // This is an example of how to include a plugin bundled with a theme.
        array(
            'name'     => 'Universal Slider', // The plugin name.
            'slug'     => 'fusion-slider', // The plugin slug (typically the folder name).
            'required' => false, // If false, the plugin is only 'recommended' instead of required.
        ),
        array(
            'name'     => 'Photo Video Gallery Master', // The plugin name.
            'slug'     => 'photo-video-gallery-master', // The plugin slug (typically the folder name).
            'required' => false, // If false, the plugin is only 'recommended' instead of required.
        ),
        array(
            'name'     => 'Ultimate Gallery Master', // The plugin name.
            'slug'     => 'ultimate-gallery-master', // The plugin slug (typically the folder name).
            'required' => false, // If false, the plugin is only 'recommended' instead of required.
        ),
        array(
            'name'     => 'Social Media Gallery', // The plugin name.
            'slug'     => 'social-media-gallery', // The plugin slug (typically the folder name).
            'required' => false, // If false, the plugin is only 'recommended' instead of required.
        ),
    );
    $config = array(
        'id'           => 'kyma', // Unique ID for hashing notices for multiple instances of kyma.
        'default_path' => '', // Default absolute path to bundled plugins.
        'menu'         => 'kyma-install-plugins', // Menu slug.
        'parent_slug'  => 'themes.php', // Parent menu slug.
        'capability'   => 'edit_theme_options', // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
        'has_notices'  => true, // Show admin notices or not.
        'dismissable'  => true, // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '', // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false, // Automatically activate plugins after installation or not.
        'message'      => '', // Message to output right before the plugins table.
    );
    kyma($plugins, $config);
}

remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
add_action('woocommerce_before_main_content', 'kyma_theme_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'kyma_theme_wrapper_end', 10);
function kyma_theme_wrapper_start()
{
    echo '<section class="content_section">
            <div class="content">
                <div class="internal_post_con clearfix">
                    <div class="content_block col-md-9">
                        <div class="hm_blog_full_list hm_blog_list clearfix">';
}

function kyma_theme_wrapper_end()
{
    ?>
    </div></div><?php get_sidebar(); ?></div></div></section>
<?php }

/* Ajax Load Moe posts */
add_action('wp_ajax_nopriv_kyma_more_post_ajax', 'kyma_more_post_ajax');
add_action('wp_ajax_kyma_more_post_ajax', 'kyma_more_post_ajax');
if ( !function_exists( 'kyma_more_post_ajax' ) ) {
    function kyma_more_post_ajax(){
        $kyma_theme_options = kyma_theme_options();
        $ppp                 = (isset($_POST['ppp'])) ? $_POST['ppp'] : 3;
        $offset              = (isset($_POST['offset'])) ? $_POST['offset'] : 0;
        
        $args = array(
            'post_type'      => 'post',
            'posts_per_page' => $ppp,
            'offset'         => $offset,
			'post_status' 	=> 'publish'
        );
        if (isset($kyma_theme_options['home_post_cat']) && !empty($kyma_theme_options['home_post_cat'])) {
            $args['category__in'] = $kyma_theme_options['home_post_cat'];
        }

        $loop = new WP_Query($args);
        $out  = '';
        $i    = 1;
        if ($loop->have_posts()):
            while ($loop->have_posts()):
                $loop->the_post();
				ob_start();
				$icon = '';
               ?>
				<li class="filter_item_block grid-item" data-animation-delay="<?php echo 300 * $i; ?>" data-animation="rotateInUpLeft">
					<div class="blog_grid_block">
						<div class="feature_inner">
							<div class="feature_inner_corners">
								<?php
								if (has_post_thumbnail()) {
									$icon = 'far fa-image';
									$url = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
									?>
									<div class="feature_inner_btns">
										<a href="<?php echo esc_url($url); ?>" class="expand_image"><i
												class="fa fa-expand"></i></a>
										<a href="<?php echo esc_url(get_the_permalink()); ?>"
										   class="icon_link"><i class="fa fa-link"></i></a>
									</div>
									<div class="porto_galla">
										<a href="<?php echo esc_url($url); ?>" class="feature_inner_ling"
									   data-rel="magnific-popup">
										<?php 
											if($kyma_theme_options['home_blog_layout'] == 'content'){
											   the_post_thumbnail('kyma_home_post_image');
											}else{
											   the_post_thumbnail('kyma_home_post_image_fluid');
											} ?>
										</a>
									</div>	
										<?php
								} ?>
							</div>
						</div>
						<div class="blog_grid_con">
							<?php if (isset($icon) && $icon!='') { ?>
							<a href="" class="blog_grid_format"><i class="<?php echo esc_attr($icon); ?>"></i></a>
							<?php } ?>
							<h6 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
						<span class="meta">
							<span
								class="meta_part"><?php echo esc_attr(get_the_date(get_option('date_format'), get_the_ID())); ?></span>
							<span class="meta_slash">/</span>
							<span
								class="meta_part"><?php esc_url(comments_popup_link(__('No Comments', 'kyma'), __('1 Comment', 'kyma'), __('% Comments', 'kyma'))); ?> <?php esc_url(edit_post_link(__('Edit', 'kyma'), ' &#124; ', '')); ?></span>
							<span class="meta_slash">/</span>
							<span class="meta_part"><?php echo get_the_category_list(','); ?></span>
						</span>
							<?php the_excerpt(); ?>
						</div>
					</div>
				</li>
               <?php $i != 3 ? $i++ : $i = 1;
            endwhile;
        endif;
        wp_reset_postdata();
		echo ob_get_clean();die;
    }
}