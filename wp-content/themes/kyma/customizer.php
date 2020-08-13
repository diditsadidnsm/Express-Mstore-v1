<?php
function kyma_upgrade_info()
{	
	wp_enqueue_style( 'fontawesome', get_template_directory_uri() . '/css/all.css' );

	wp_enqueue_script( 'fontawesome-iconpicker-js', get_template_directory_uri() . '/inc/icon-picker/js/fontawesome-iconpicker.min.js', array( 'jquery' ), '1.0.0', true );
	wp_enqueue_style( 'fontawesome-iconpicker', get_template_directory_uri() . '/inc/icon-picker/css/fontawesome-iconpicker.min.css' );
	wp_enqueue_script( 'iconpicker', get_template_directory_uri() . '/inc/icon-picker/js/iconpicker.js', array( 'jquery', 'fontawesome-iconpicker-js' ), '1.0.0', true );
}

add_action('customize_controls_enqueue_scripts', 'kyma_upgrade_info');
function kyma_customizer_preview_js()
{
    wp_enqueue_script('custom_css_preview', get_template_directory_uri() . '/js/customize-preview.js', array('customize-preview', 'jquery'));
}
add_action('customize_preview_init', 'kyma_customizer_preview_js');

/* Add Customizer Panel */
$kyma_theme_options = kyma_theme_options();
Kirki::add_config('kyma_theme', array(
    'capability'  => 'edit_theme_options',
    'option_type' => 'option',
    'option_name' => 'kyma_theme_options',
));
/* Genral Panel */
Kirki::add_panel('kyma_general_settings_panel', array(
    'priority' => 10,
    'title'    => __('General Settings', 'kyma'),
));
/* Home sections Panel */
Kirki::add_panel('kyma_home_panel', array(
    'priority'    => 20,
    'title'       => __('Home Sections', 'kyma'),
    'description' => __('Customize all Home sections', 'kyma'),
));
/* color scheme */
Kirki::add_section('kyma_general_section', array(
    'title'       => __('Home page/color scheme', 'kyma'),
    'panel'       => 'kyma_general_settings_panel',
    'priority'    => 10,
    'capability'  => 'edit_theme_options',
));
Kirki::add_field('kyma_theme', array(
	'settings'          => '_frontpage',
    'label'             => __('Show home page', 'kyma'),
    'section'           => 'kyma_general_section',
    'default'			=> $kyma_theme_options['_frontpage'],
    'type'              => 'switch',
));
Kirki::add_field('kyma_theme', array(
    'settings'          => 'color_scheme',
    'label'             => __('Color scheme', 'kyma'),
    'description'       => __('Select a color scheme', 'kyma'),
    'section'           => 'kyma_general_section',
    'type'              => 'preset',
    'priority'          => 9,
    'default'           => '',
    'sanitize_callback' => 'esc_attr',
    'choices'    =>array(
		''=>array(
			'label' => __('Default','kyma'),
			'settings' => array(
	            'kyma_theme_options[header_topbar_bg_color]' => '#1ccdca',
				'kyma_theme_options[nav_color]' => '#1ccdca',
				'kyma_theme_options[slider_title_bg_color]' => '#1ccdca',
				'kyma_theme_options[callout_bg_color]' => '#1ccdca',
				'kyma_theme_options[back_to_top]' => '#1ccdca',
           ),
		),
		'orange'=>array(
			'label' => __('Orange','kyma'),
			'settings' => array(
	            'kyma_theme_options[header_topbar_bg_color]' => '#F86923',
				'kyma_theme_options[nav_color]' => '#F86923',
				'kyma_theme_options[slider_title_bg_color]' => '#F86923',
				'kyma_theme_options[callout_bg_color]' => '#F86923',
				'kyma_theme_options[back_to_top]' => '#F86923',
           ),
		),
		'red'=>array(
			'label' => __('Red','kyma'),
			'settings' => array(
	            'kyma_theme_options[header_topbar_bg_color]' => '#dd3333',
				'kyma_theme_options[nav_color]' => '#dd3333',
				'kyma_theme_options[slider_title_bg_color]' => '#dd3333',
				'kyma_theme_options[callout_bg_color]' => '#dd3333',
				'kyma_theme_options[back_to_top]' => '#dd3333',
           ),
		),
    )

));
// top bar section
Kirki::add_section('kyma_topbar_section', array(
    'title'       => __('Top bar Options', 'kyma'),
    'description' => __('Customize site top bar', 'kyma'),
    'panel'       => 'kyma_general_settings_panel',
    'priority'    => 10,
    'capability'  => 'edit_theme_options',
));
Kirki::add_field('kyma_theme', array(
    'settings'          => 'header_topbar_bg_color',
    'label'             => __('Top Bar Background Color', 'kyma'),
    'description'       => __('Change Top bar Background Color', 'kyma'),
    'section'           => 'kyma_topbar_section',
    'type'              => 'color',
    'priority'          => 9,
    'default'           => '#1ccdca',
    'sanitize_callback' => 'kyma_sanitize_color',
    'output'            => array(
        array(
            'element'  => '.light_header .topbar,.light_header .top-socials > a > span.soc_name,.light_header .top-socials > a > span.soc_icon_bg,.light_header .top-socials span.soc_name:after, .light_header .top-socials span.soc_name:before',
            'function' => 'style',
            'property' => 'background-color',
        ),

    ),
    'transport'         => 'auto',

));

Kirki::add_field('kyma_theme', array(
    'settings'          => 'header_topbar_color',
    'label'             => __('Top Bar Font Color', 'kyma'),
    'description'       => __('Change Top bar Font Color', 'kyma'),
    'section'           => 'kyma_topbar_section',
    'type'              => 'color',
    'priority'          => 9,
    'default'           => '#fff',
    'sanitize_callback' => 'kyma_sanitize_color',
    'output'            => array(
        array(
            'element'  => '.top_details .title, .top_details .title a, .top_details > span > a, .top_details > span, .top_details > div, .top_details > div > a, .top-socials > a',
            'property' => 'color',
        ),
    ),
    'transport'         => 'postMessage',
    'js_vars'           => array(
        array(
            'element'  => '.top_details .title, .top_details .title a, .top_details > span > a, .top_details > span, .top_details > div, .top_details > div > a, .top-socials > a',
            'function' => 'css',
            'property' => 'color',
        ),
    ),
));

Kirki::add_field('kyma_theme', array(
    'settings'          => 'contact_info_header',
    'label'             => __('Header Contact Info', 'kyma'),
    'description'       => __('Show/Hide contact info bar in header', 'kyma'),
    'section'           => 'kyma_topbar_section',
    'type'              => 'switch',
    'priority'          => 10,
    'default'           => $kyma_theme_options['contact_info_header'],
    'sanitize_callback' => 'kyma_sanitize_checkbox',
));
Kirki::add_field('kyma_theme', array(
    'settings'          => 'contact_email',
    'label'             => __('Contact Email Address', 'kyma'),
    'section'           => 'kyma_topbar_section',
    'type'              => 'text',
    'priority'          => 10,
    'default'           => $kyma_theme_options['contact_email'],
    'sanitize_callback' => 'sanitize_email',
));
Kirki::add_field('kyma_theme', array(
    'settings'          => 'contact_phone',
    'label'             => __('Phone Number', 'kyma'),
    'section'           => 'kyma_topbar_section',
    'type'              => 'text',
    'priority'          => 10,
    'default'           => $kyma_theme_options['contact_phone'],
    'sanitize_callback' => 'kyma_sanitize_text',
));

Kirki::add_field('kyma_theme', array(
    'settings'          => 'social_media_header',
    'label'             => __('Enable Social Icon', 'kyma'),
    'description'       => __('Show/Hide social icons in header', 'kyma'),
    'section'           => 'kyma_topbar_section',
    'type'              => 'switch',
    'priority'          => 10,
    'default'           => $kyma_theme_options['social_media_header'],
    'sanitize_callback' => 'kyma_sanitize_checkbox',
));
Kirki::add_field('kyma_theme', array(
    'settings'          => 'social_facebook_link',
    'label'             => __('Facebook Profile URL', 'kyma'),
    'section'           => 'kyma_topbar_section',
    'type'              => 'url',
    'priority'          => 10,
    'default'           => $kyma_theme_options['social_facebook_link'],
    'sanitize_callback' => 'esc_url',
));

Kirki::add_field('kyma_theme', array(
    'settings'          => 'social_twitter_link',
    'label'             => __('Twitter Profile URL', 'kyma'),
    'section'           => 'kyma_topbar_section',
    'type'              => 'url',
    'priority'          => 10,
    'default'           => $kyma_theme_options['social_twitter_link'],
    'sanitize_callback' => 'esc_url',
));

Kirki::add_field('kyma_theme', array(
    'settings'          => 'social_instagram_link',
    'label'             => __('Instagram Profile URL', 'kyma'),
    'section'           => 'kyma_topbar_section',
    'type'              => 'url',
    'priority'          => 10,
    'default'           => $kyma_theme_options['social_instagram_link'],
    'sanitize_callback' => 'kyma_sanitize_text',
));

Kirki::add_field('kyma_theme', array(
    'settings'          => 'social_skype_link',
    'label'             => __('Skype username', 'kyma'),
    'desc'             => __('e.g. YourSkypeName?call', 'kyma'),
    'section'           => 'kyma_topbar_section',
    'type'              => 'text',
    'priority'          => 10,
    'default'           => $kyma_theme_options['social_skype_link'],
    'sanitize_callback' => 'sanitize_text_field',
));

Kirki::add_field('kyma_theme', array(
    'settings'          => 'social_youtube_link',
    'label'             => __('YouTube URL', 'kyma'),
    'section'           => 'kyma_topbar_section',
    'type'              => 'url',
    'priority'          => 10,
    'default'           => $kyma_theme_options['social_youtube_link'],
    'sanitize_callback' => 'esc_url',
));

Kirki::add_field('kyma_theme', array(
    'settings'          => 'social_vimeo_link',
    'label'             => __('Vimeo URL', 'kyma'),
    'section'           => 'kyma_topbar_section',
    'type'              => 'url',
    'priority'          => 10,
    'default'           => $kyma_theme_options['social_vimeo_link'],
    'sanitize_callback' => 'esc_url',
));
// Header Options
Kirki::add_field('kyma_theme', array(
    'type'              => 'color',
    'settings'          => 'nav_color',
    'label'             => __('Navigation Background Color', 'kyma'),
    'section'           => 'header_image',
    'default'           => '#1ccdca',
    'priority'          => 12,
    'sanitize_callback' => 'kyma_sanitize_color',
    'output'            => array(
        array(
            'element'  => '.menu_button_mode:not(.header_on_side) #navy > li.current_page_item > a, .menu_button_mode:not(.header_on_side) #navy > li.current_page_item:hover > a',
            'property' => 'background-color',
        ),
        array(
            'element'=>'#navy > li:not(.current_page_item):hover > a:not(.nav_trigger),#navy ul li a:hover',
            'property' => 'color',
            'suffix'   => '!important'
        )
    ),
    'transport'         => 'postMessage',
    'js_vars'           => array(
        array(
            'element'  => '.menu_button_mode:not(.header_on_side) #navy > li.current_page_item > a, .menu_button_mode:not(.header_on_side) #navy > li.current_page_item:hover > a',
            'function' => 'css',
            'property' => 'background-color',
        ),
        array(
            'element'=>'#navy > li:hover > a:not(.nav_trigger),#navy ul li a:hover',
            'function' => 'css',
            'property' => 'color',
            'suffix'   => '!important'
        )
    ),
));

Kirki::add_field('kyma_theme', array(
    'type'              => 'toggle',
    'settings'          => 'headersticky',
    'label'             => __('Fixed Header', 'kyma'),
    'description'       => __('Switch between fixed and static header', 'kyma'),
    'section'           => 'header_image',
    'default'           => $kyma_theme_options['headersticky'],
    'priority'          => 20,
    'sanitize_callback' => 'kyma_sanitize_checkbox',
));

Kirki::add_field('kyma_theme', array(
    'settings'          => 'logo_top_spacing',
    'label'             => __('Logo Top Spacing', 'kyma'),
    'section'           => 'title_tagline',
    'type'              => 'slider',
    'priority'          => 40,
    'default'           => 10,
    'choices'           => array(
        'max'  => 50,
        'min'  => -50,
        'step' => 1,
    ),
    'transport'         => 'auto',
    'output'            => array(
        array(
            'element'  => '#logo',
            'property' => 'margin-top',
            'units'    => 'px',
        ),

    ),
    'sanitize_callback' => 'kyma_sanitize_number',
));

/* Breadcrumb & title section  */
Kirki::add_section('breadcrumb_title', array(
	'title'       => esc_html__('Breadcrumbs & Title', 'kyma'),
	'description' => __('Customizer Breadcrumbs & Title', 'kyma'),
	'panel'       => 'kyma_general_settings_panel',
	'priority'    => 60,
	'capability'  => 'edit_theme_options',
));

Kirki::add_field('kyma_theme', array(
    'settings'          => 'crumb_and_title',
	'label'             => esc_html__('Breadcrumb Section Style', 'kyma'),
	'section'           => 'breadcrumb_title',
	'type'              => 'select',
	'priority'          => 9,
	'default'           => 'allow_both',
	'sanitize_callback' => 'esc_attr',
	'choices'           => array(
        'allow_both'  => __('Title Bar With Breadcrumbs','kyma'),
        'allow_title' => __('Title Bar Only','kyma'),
        'not_of_them' => __('Hide All','kyma'),
    ),
));

Kirki::add_field('kyma_theme', array(
    'settings'          => 'show_page_title',
    'label'             => esc_html__('Show Page title', 'kyma'),
    'section'           => 'breadcrumb_title',
    'type'              => 'switch',
    'priority'          => 10,
    'default'           => 1,
    'sanitize_callback' => 'kyma_sanitize_checkbox',
));
	
/* Typography */
Kirki::add_section('typography_sec', array(
    'title'       => __('Typography Section', 'kyma'),
    'description' => __('Here you can change Font Style of your site', 'kyma'),
    'panel'       => 'kyma_general_settings_panel',
    'priority'    => 60,
    'capability'  => 'edit_theme_options',
));

Kirki::add_field('kyma_theme', array(
    'type'        => 'typography',
    'settings'    => 'logo_font',
    'label'       => __('Logo Font Style', 'kyma'),
    'description' => __('Change logo font family and font style.', 'kyma'),
    'section'     => 'typography_sec',
    'default'     => array(
        'font-style'  => array('bold', 'italic'),
        'font-family' => 'lato',

    ),
    'priority'    => 10,
    'choices'     => array(
        'font-style'  => true,
        'font-family' => true,
        'font-size'   => true,
        'line-height' => true,
        'font-weight' => true,
    ),
    'output'      => array(
        array(
            'element' => '#logo .site-title a',
        ),
    ),
));
Kirki::add_field('kyma_theme', array(
    'type'        => 'typography',
    'settings'    => 'menu_font',
    'label'       => __('Menu Font Style', 'kyma'),
    'description' => __('Change Primary Menu font family and font style.', 'kyma'),
    'section'     => 'typography_sec',
    'default'     => array(
        'font-style'  => array('bold', 'italic'),
        'font-family' => "Open Sans",

    ),
    'priority'    => 10,
    'choices'     => array(
        'font-style'  => true,
        'font-family' => true,
        'font-size'   => true,
        'line-height' => true,
        'font-weight' => true,
    ),
    'output'      => array(
        array(
            'element' => '#navy > li > a > span',
        ),
    ),
));

/* Full body typography */
Kirki::add_field('kyma_theme', array(
    'type'        => 'typography',
    'settings'    => 'site_font',
    'label'       => __('Site Font Style', 'kyma'),
    'description' => __('Change whole site font family and font style.', 'kyma'),
    'section'     => 'typography_sec',
    'default'     => array(
        'font-style'  => array('bold', 'italic'),
        'font-family' => "Open Sans",

    ),
    'priority'    => 10,
    'choices'     => array(
        'font-style'  => true,
        'font-family' => true,
    ),
    'output'      => array(
        array(
            'element' => 'body, h1, h2, h3, h4, h5, h6, p, em, blockquote, .main_title h2',
        ),
    ),
));
/* Home title typography */
Kirki::add_field('kyma_theme', array(
    'type'        => 'typography',
    'settings'    => 'site_title_font',
    'label'       => __('Home Sections Title Font', 'kyma'),
    'description' => __('Change font style of home service, home portfolio, home blog', 'kyma'),
    'section'     => 'typography_sec',
    'default'     => array(
        'font-style'  => array('bold', 'italic'),
        'font-family' => "Open Sans",

    ),
    'priority'    => 10,
    'choices'     => array(
        'font-style'  => true,
        'font-family' => true,
        'font-size'   => true,
        'line-height' => true,
        'font-weight' => true,
    ),
    'output'      => array(
        array(
            'element' => '.main_title h2',
        ),
    ),
));
/* footer options */
Kirki::add_section('footer_sec', array(
    'title'      => __('Footer Options', 'kyma'),
    'panel'      => 'kyma_general_settings_panel',
    'priority'   => 80,
    'capability' => 'edit_theme_options',
));
Kirki::add_field('kyma_theme', array(
    'settings'          => 'footer_layout',
    'label'             => __('Footer Layout', 'kyma'),
    'description'       => __('Change footer into 2, 3 or 4 column', 'kyma'),
    'section'           => 'footer_sec',
    'type'              => 'radio-image',
    'priority'          => 10,
    'default'           => $kyma_theme_options['footer_layout'],
    'transport'         => 'postMessage',
    'choices'           => array(
        2 => get_template_directory_uri() . '/images/footer-widgets-2.png',
        3 => get_template_directory_uri() . '/images/footer-widgets-3.png',
        4 => get_template_directory_uri() . '/images/footer-widgets-4.png',
    ),
    'sanitize_callback' => 'kyma_sanitize_number',
));
Kirki::add_field('kyma_theme', array(
    'settings'          => 'footer_bg_color',
    'label'             => __('Footer Background Color', 'kyma'),
    'section'           => 'footer_sec',
    'type'              => 'color',
    'default'           => '#191E21',
    'priority'          => 10,
    'output'            => array(
        array(
            'element'  => '#footer',
            'property' => 'background-color',
        ),
    ),
    'transport'         => 'postMessage',
    'js_vars'           => array(
        array(
            'element'  => '#footer',
            'function' => 'css',
            'property' => 'background-color',
        ),
    ),
    'sanitize_callback' => 'kyma_sanitize_color',
));

Kirki::add_field('kyma_theme', array(
    'settings'          => 'back_to_top',
    'label'             => __('Back to top icon background color', 'kyma'),
    'section'           => 'footer_sec',
    'type'              => 'color',
    'default'           => '#1ccdca',
    'priority'          => 10,
    'output'            => array(
        array(
            'element'  => '.hm_go_top',
            'property' => 'background-color',
        ),
    ),
    'transport'         => 'postMessage',
    'js_vars'           => array(
        array(
            'element'  => '.hm_go_top',
            'function' => 'css',
            'property' => 'background-color',
        ),
    ),
    'sanitize_callback' => 'kyma_sanitize_color',
));

Kirki::add_field('kyma_theme', array(
    'settings'          => 'footer_copyright',
    'label'             => __('Copyright Text', 'kyma'),
    'section'           => 'footer_sec',
    'type'              => 'text',
    'priority'          => 10,
    'default'           => $kyma_theme_options['footer_copyright'],
    'sanitize_callback' => 'kyma_sanitize_text',
));
Kirki::add_field('kyma_theme', array(
    'settings'          => 'developed_by_text',
    'label'             => __('Developed by Text', 'kyma'),
    'section'           => 'footer_sec',
    'type'              => 'text',
    'priority'          => 10,
    'default'           => $kyma_theme_options['developed_by_text'],
    'sanitize_callback' => 'kyma_sanitize_text',
));
Kirki::add_field('kyma_theme', array(
    'settings'          => 'developed_by_link_text',
    'label'             => __('Link Text', 'kyma'),
    'section'           => 'footer_sec',
    'type'              => 'text',
    'priority'          => 10,
    'default'           => $kyma_theme_options['developed_by_link_text'],
    'sanitize_callback' => 'kyma_sanitize_text',
));
Kirki::add_field('kyma_theme', array(
    'settings'          => 'developed_by_link',
    'label'             => __('Developed by Link', 'kyma'),
    'section'           => 'footer_sec',
    'type'              => 'url',
    'priority'          => 10,
    'default'           => $kyma_theme_options['developed_by_link'],
    'sanitize_callback' => 'esc_url',
));
/* site Layout */
Kirki::add_field('kyma_theme', array(
    'settings'          => 'site_layout',
    'label'             => __('Site Layout', 'kyma'),
    'description'       => __('Change your site layout to full width or boxed size.', 'kyma'),
    'section'           => 'background_image',
    'type'              => 'radio-image',
    'priority'          => 30,
    'transport'         => 'postMessage',
    'default'           => '',
    'sanitize_callback' => 'kyma_sanitize_text',
    'choices'           => array(
        ''           => get_template_directory_uri() . '/inc/kirki/assets/images/1c.png',
        'site_boxed' => get_template_directory_uri() . '/inc/kirki/assets/images/3cm.png',
    ),

));

/* Slider */
Kirki::add_section('slider_sec', array(
    'title'       => __('Slider Options', 'kyma'),
    'description' => __('Change slider text(s) and images', 'kyma'),
    'panel'       => 'kyma_home_panel',
    'priority'    => 160,
    'capability'  => 'edit_theme_options',
));

Kirki::add_field('kyma_theme', array(
    'settings'          => 'home_slider_enabled',
    'label'             => __('Enable Home Slider', 'kyma'),
    'section'           => 'slider_sec',
    'type'              => 'switch',
    'priority'          => 10,
    'default'           => 1,
    'sanitize_callback' => 'kyma_sanitize_checkbox',
));
Kirki::add_field('kyma_theme', array(
    'type'              => 'select',
    'settings'          => 'home_slider_posts',
    'label'             => __('Select Page to be Shown in Slider', 'kyma'),
    'section'           => 'slider_sec',
    'priority'          => 10,
    'default'           => array(),
    'choices'           =>  Kirki_Helper::get_posts(array('posts_per_page' => -1, 'orderby' => 'date', 'order' => 'DESC', 'post_type' => 'page', 'post_status' => 'publish')),
    'multiple'          => 4,
    'sanitize_callback' => 'kyma_sanitize_number',
));
Kirki::add_field('kyma_theme', array(
    'type'              => 'text',
    'settings'          => 'slider_text_length',
    'label'             => __('Number of words to be shown in slider description.', 'kyma'),
    'section'           => 'slider_sec',
    'priority'          => 10,
    'default'           => 8,
    'sanitize_callback' => 'absint',
));
Kirki::add_field('kyma_theme', array(
    'type'              => 'select',
    'settings'          => 'home_slider_effect',
    'label'             => __('Sliding Effect', 'kyma'),
    'section'           => 'slider_sec',
    'priority'          => 10,
    'default'           => 'fadeUp',
    'choices'           => array('fade'=>'fade', 'fadeUp'=>'fadeUp', 'backSlide'=>'backSlide', 'goDown'=>'goDown'),
    'sanitize_callback' => 'kyma_sanitize_selected',
));
Kirki::add_field('kyma_theme', array(
    'settings'          => 'slider_title_bg_color',
    'label'             => __('Slider Title Background Color', 'kyma'),
    'section'           => 'slider_sec',
    'type'              => 'color',
    'priority'          => 10,
    'default'           => '#1ccdca',
    'choices'           => array(
        'alpha' => true
    ),
    'output'            => array(
        array(
            'element'  => '.owl_text_a > span',
            'function' => 'style',
            'property' => 'background-color',
        ),

    ),
    'transport'         => 'auto',

));
Kirki::add_field('kyma_theme', array(
    'settings'          => 'slider_subtitle_bg_color',
    'label'             => __('Slider Subtitle Background Color', 'kyma'),
    'section'           => 'slider_sec',
    'type'              => 'color',
    'priority'          => 10,
    'default'           => 'rgba(0, 0, 0, 0.8)',
    'choices'           => array(
        'alpha' => true
    ),
    'output'            => array(
        array(
            'element'  => '.owl_text_c > span',
            'function' => 'style',
            'property' => 'background-color',
        ),

    ),
    'transport'         => 'auto',

));
Kirki::add_field('kyma_theme', array(
    'type'              => 'text',
    'settings'          => 'slider_btn_text',
    'label'             => __('Slider Button Text.', 'kyma'),
    'section'           => 'slider_sec',
    'priority'          => 10,
    'default'           => $kyma_theme_options['slider_btn_text'],
    'sanitize_callback' => 'kyma_sanitize_text',
));
Kirki::add_field('kyma_theme', array(
    'settings'          => 'or_slider_shortcode',
    'label'             => __('OR', 'kyma'),
    'section'           => 'slider_sec',
    'type'              => 'heading',
    'priority'          => 10,
    'sanitize_callback' => 'kyma_sanitize_text',
));
Kirki::add_field('kyma_theme', array(
    'settings'          => 'slider_shortcode',
    'label'             => __('Put Your Slider Shortcode Here', 'kyma'),
    'help'              => __('Here you can insert only slider shortcode here.', 'kyma'),
    'section'           => 'slider_sec',
    'type'              => 'text',
    'priority'          => 10,
    'default'           => '',
    'sanitize_callback' => 'kyma_sanitize_text',
));
/* Service Options */
Kirki::add_section('service_sec', array(
    'title'      => __('Service Options', 'kyma'),
    'panel'      => 'kyma_home_panel',
    'priority'   => 160,
    'capability' => 'edit_theme_options',
));
Kirki::add_field('kyma_theme', array(
    'settings'          => 'home_service_heading',
    'label'             => __('Home Service Heading', 'kyma'),
    'section'           => 'service_sec',
    'type'              => 'text',
    'priority'          => 10,
    'transport'         => 'postMessage',
    'default'           => $kyma_theme_options['home_service_heading'],
    'sanitize_callback' => 'kyma_sanitize_text',
    'partial_refresh'   => array(
        'home_service_heading' => array(
            'selector'        => '#service_heading',
            'render_callback' => function () {
                $options = get_option('kyma_theme_options');
                return $options['home_service_heading'];
            },
        ),
    ),
));
Kirki::add_field( 'kyma_theme', [
	'settings'    => 'home_service_layout',
	'label'       => esc_html__( 'Home Service Layout', 'kyma' ),
	'section'     => 'service_sec',
	'type'        => 'radio-buttonset',
	'default'     => $kyma_theme_options['home_service_layout'],
	'priority'    => 10,
	'choices'     => [
		'container'   => esc_html__( 'Box Width', 'kyma' ),
		'container-fluid'  => esc_html__( 'Full Width', 'kyma' ),
	],
] );
Kirki::add_field('kyma_theme', array(
    'settings'          => 'home_service_column',
    'label'             => __('Home Service Column', 'kyma'),
    'section'           => 'service_sec',
    'type'              => 'select',
    'priority'          => 10,
    'transport'         => 'postMessage',
    'default'           => 4,
    'choices'           => array(
        2 => __('Two Column', 'kyma'),
        3 => __('Three Column', 'kyma'),
        4 => __('Four Column', 'kyma'),
    ),
    'sanitize_callback' => 'kyma_sanitize_number',
));
for ($i = 1; $i <= 4; $i++) {
    Kirki::add_field('kyma_theme', array(
        'settings'          => 'service_heading_' . $i,
        'label'             => sprintf(__('Service %d', 'kyma'), $i),
        'section'           => 'service_sec',
        'type'              => 'heading',
        'priority'          => 10,
        'sanitize_callback' => 'kyma_sanitize_text',
    ));
    Kirki::add_field( 'kyma_theme', array(
		'type'        => 'generic',
		'settings'    => 'service_icon_' . $i,
		'label'             => sprintf(__('Service Icon %d', 'kyma'), $i),
		'section'     => 'service_sec',
		'default'     => $kyma_theme_options['service_icon_' . $i],
		'transport'         => 'postMessage',
		'choices'     => array(
			'type'     => 'text',
			'class'    => 'icp icp-auto',
			'data-placement'=>"bottomRight",
			'onmouseover'=>'popup_icon_picker()'
		),
	) );
    Kirki::add_field('kyma_theme', array(
        'settings'          => 'service_title_' . $i,
        'label'             => sprintf(__('Service Title %d', 'kyma'), $i),
        'section'           => 'service_sec',
        'type'              => 'text',
        'priority'          => 10,
        'transport'         => 'postMessage',
        'default'           => $kyma_theme_options['service_title_' . $i],
        'sanitize_callback' => 'kyma_sanitize_text',
        'partial_refresh'   => array(
            'service_title_' . $i => array(
                'selector'        => '#service-title-' . $i,
                'render_callback' => function () use ($i) {
                    $options = get_option('kyma_theme_options');
                    return $options['service_title_' . $i];
                },
            ),
        ),
    ));
    Kirki::add_field('kyma_theme', array(
        'settings'          => 'service_text_' . $i,
        'label'             => sprintf(__('Service Description %d', 'kyma'), $i),
        'section'           => 'service_sec',
        'type'              => 'textarea',
        'priority'          => 10,
        'transport'         => 'postMessage',
        'default'           => $kyma_theme_options['service_text_' . $i],
        'sanitize_callback' => 'kyma_sanitize_textarea',
        'partial_refresh'   => array(
            'service_text_' . $i => array(
                'selector'        => '#service-desc -' . $i,
                'render_callback' => function () use ($i) {
                    $options = get_option('kyma_theme_options');
                    return $options['service_text_' . $i];
                },
            ),
        ),
    ));
    Kirki::add_field('kyma_theme', array(
        'settings'          => 'service_link_' . $i,
        'label'             => sprintf(__('Service Link %d', 'kyma'), $i),
        'section'           => 'service_sec',
        'type'              => 'text',
        'priority'          => 10,
        'transport'         => 'postMessage',
        'default'           => $kyma_theme_options['service_link_' . $i],
        'sanitize_callback' => 'esc_url',
    ));
}
/* Portfolio */
Kirki::add_section('portfolio_sec', array(
    'title'      => __('Portfolio Options', 'kyma'),
    'panel'      => 'kyma_home_panel',
    'priority'   => 160,
    'capability' => 'edit_theme_options',
));
Kirki::add_field('kyma_theme', array(
    'settings'          => 'port_heading',
    'label'             => __('Home Portfolio Title', 'kyma'),
    'section'           => 'portfolio_sec',
    'type'              => 'text',
    'priority'          => 10,
    'transport'         => 'postMessage',
    'default'           => $kyma_theme_options['port_heading'],
    'sanitize_callback' => 'kyma_sanitize_text',
    'partial_refresh'   => array(
        'port_heading' => array(
            'selector'        => '#port_head',
            'render_callback' => function () {
                $options = get_option('kyma_theme_options');
                return $options['port_heading'];
            },
        ),
    ),
));
Kirki::add_field( 'kyma_theme', [
	'settings'    => 'home_port_layout',
	'label'       => esc_html__( 'Home Portfolio Layout', 'kyma' ),
	'section'     => 'portfolio_sec',
	'type'        => 'radio-buttonset',
	'default'     => $kyma_theme_options['home_port_layout'],
	'priority'    => 10,
	'choices'     => [
		'boxed_portos'   => esc_html__( 'Box Width', 'kyma' ),
		''  => esc_html__( 'Full Width', 'kyma' ),
	],
] );
for ($i = 1; $i <= 3; $i++) {
    Kirki::add_field('kyma_theme', array(
        'settings'          => 'portfolio_heading_' . $i,
        'label'             => sprintf(__('Portfolio %d', 'kyma'), $i),
        'section'           => 'portfolio_sec',
        'type'              => 'heading',
        'priority'          => 10,
        'sanitize_callback' => 'kyma_sanitize_text',
    ));
    Kirki::add_field( 'kyma_theme', array(
		'type'        => 'image',
		'settings'    => 'portfolio_image_'.$i,
		'label'       => esc_html__( 'Portfolio Image', 'kyma' ),
		'section'     => 'portfolio_sec',
		'default'     => $kyma_theme_options['portfolio_image_' . $i],
	) );
    Kirki::add_field('kyma_theme', array(
        'settings'          => 'portfolio_title_' . $i,
        'label'             => sprintf(__('Portfolio Title %d', 'kyma'), $i),
        'section'           => 'portfolio_sec',
        'type'              => 'text',
        'priority'          => 10,
        'transport'         => 'postMessage',
        'default'           => $kyma_theme_options['portfolio_title_' . $i],
        'sanitize_callback' => 'kyma_sanitize_text',
        'partial_refresh'   => array(
            'portfolio_title_' . $i => array(
                'selector'        => '#portfolio-title-' . $i,
                'render_callback' => function () use ($i) {
                    $options = get_option('kyma_theme_options');
                    return $options['portfolio_title_' . $i];
                },
            ),
        ),
    ));

    Kirki::add_field('kyma_theme', array(
        'settings'          => 'portfolio_link_' . $i,
        'label'             => sprintf(__('Portfolio Link %d', 'kyma'), $i),
        'section'           => 'portfolio_sec',
        'type'              => 'text',
        'priority'          => 10,
        'transport'         => 'postMessage',
        'default'           => $kyma_theme_options['portfolio_link_' . $i],
        'sanitize_callback' => 'esc_url',
    ));
}
 Kirki::add_field('kyma_theme', array(
    'settings'          => 'portfolio_plugin_heading',
    'label'             => __('Or Put Plugin Shortcode here', 'kyma'),
    'section'           => 'portfolio_sec',
    'type'              => 'heading',
    'priority'          => 10,
    'sanitize_callback' => 'kyma_sanitize_text',
));
Kirki::add_field('kyma_theme', array(
    'settings'          => 'portfolio_three_column',
    'label'             => __('Show Portfolio in Three Column', 'kyma'),
    'description'       => __('This option will work only if you are using <b>Photo Video Gallery Master</b> plugin to show portfolio content.', 'kyma'),
    'section'           => 'portfolio_sec',
    'type'              => 'checkbox',
    'priority'          => 10,
    'transport'         => 'postMessage',
    'default'           => 0,
    'sanitize_callback' => 'kyma_sanitize_checkbox',
));
Kirki::add_field('kyma_theme', array(
    'settings'          => 'portfolio_shortcode',
    'label'             => __('Put Your Gallery Shortcode here', 'kyma'),
    'section'           => 'portfolio_sec',
    'type'              => 'text',
    'priority'          => 10,
    'default'           => '',
    'sanitize_callback' => 'kyma_sanitize_text',
));
/* Blog Options */
Kirki::add_section('blog_sec', array(
    'title'      => __('Blog Options', 'kyma'),
    'panel'      => 'kyma_home_panel',
    'priority'   => 160,
    'capability' => 'edit_theme_options',
));
Kirki::add_field('kyma_theme', array(
    'settings'          => 'home_blog_title',
    'label'             => __('Home Blog Title', 'kyma'),
    'section'           => 'blog_sec',
    'type'              => 'text',
    'priority'          => 10,
    'transport'         => 'postMessage',
    'default'           => $kyma_theme_options['home_blog_title'],
    'sanitize_callback' => 'kyma_sanitize_text',
    'partial_refresh'   => array(
        'home_blog_title' => array(
            'selector'        => '#blog-heading',
            'render_callback' => function () {
                $options = get_option('kyma_theme_options');
                return $options['home_blog_title'];
            },
        ),
    ),
));
Kirki::add_field( 'kyma_theme', [
	'settings'    => 'home_blog_layout',
	'label'       => esc_html__( 'Home Blog Layout', 'kyma' ),
	'section'     => 'blog_sec',
	'type'        => 'radio-buttonset',
	'default'     => $kyma_theme_options['home_blog_layout'],
	'priority'    => 10,
	'choices'     => [
		'content'   => esc_html__( 'Box Width', 'kyma' ),
		'container-fluid'  => esc_html__( 'Full Width', 'kyma' ),
	],
] );
Kirki::add_field('kyma_theme', array(
    'settings'          => 'blog_layout',
    'label'             => __('Blog List Layout', 'kyma'),
    'description'       => __('Select Blog Layout', 'kyma'),
    'help'              => __('With this option you can select blog left sidebar,right sidebar and full width', 'kyma'),
    'section'           => 'blog_sec',
    'type'              => 'radio-image',
    'priority'          => 10,
    'default'           => $kyma_theme_options['blog_layout'],
    'choices'           => array(
        'blogleft'  => get_template_directory_uri() . '/inc/kirki/assets/images/2cl.png',
        'blogright' => get_template_directory_uri() . '/inc/kirki/assets/images/2cr.png',
        'blogfull'  => get_template_directory_uri() . '/inc/kirki/assets/images/1c.png',
    ),
    'sanitize_callback' => 'kyma_sanitize_text',

));
Kirki::add_field('kyma_theme', array(
    'settings'          => 'post_layout',
    'label'             => __('Single Post Layout', 'kyma'),
    'description'       => __('Select Post Layout', 'kyma'),
    'help'              => __('With this option you can select single post with left sidebar,right sidebar and full width', 'kyma'),
    'section'           => 'blog_sec',
    'type'              => 'radio-image',
    'priority'          => 10,
    'default'           => $kyma_theme_options['post_layout'],
    'choices'           => array(
        'postleft'  => get_template_directory_uri() . '/inc/kirki/assets/images/2cl.png',
        'postright' => get_template_directory_uri() . '/inc/kirki/assets/images/2cr.png',
        'postfull'  => get_template_directory_uri() . '/inc/kirki/assets/images/1c.png',
    ),
    'sanitize_callback' => 'kyma_sanitize_text',

));
$cats = array();
$categories = get_categories(array(
    'orderby' => 'name',
    'order'   => 'ASC',
));
foreach ($categories as $category) {
    $cats[$category->term_id] = $category->name;
}
Kirki::add_field('kyma_theme', array(
    'settings'          => 'home_post_cat',
    'label'             => __('Category', 'kyma'),
    'description'       => __('Show Posts On Blog Home According to Selected Categories', 'kyma'),
    'help'              => __('With this option you can show blog posts according your requirement', 'kyma'),
    'section'           => 'blog_sec',
    'type'              => 'select',
    'priority'          => 10,
    'default'           => 1,
    'multiple'          => 10,
    'choices'           => $cats,
    'sanitize_callback' => 'kyma_sanitize_number',
));
Kirki::add_field('kyma_theme', array(
    'settings'          => 'about_author_text',
    'label'             => __('About Author Title', 'kyma'),
    'section'           => 'blog_sec',
    'type'              => 'text',
    'priority'          => 10,
    'default'           => $kyma_theme_options['about_author_text'],
    'sanitize_callback' => 'kyma_sanitize_text',
));
Kirki::add_field('kyma_theme', array(
    'settings'          => 'related_post_text',
    'label'             => __('Related Post Text', 'kyma'),
    'section'           => 'blog_sec',
    'type'              => 'text',
    'priority'          => 10,
    'default'           => $kyma_theme_options['related_post_text'],
    'sanitize_callback' => 'kyma_sanitize_text',
));
Kirki::add_field('kyma_theme', array(
    'settings'          => 'show_load_more_btn',
    'label'             => __('Enable Load More Button.', 'kyma'),
    'section'           => 'blog_sec',
    'type'              => 'switch',
    'priority'          => 10,
    'default'           => 1,
    'sanitize_callback' => 'kyma_sanitize_checkbox',
));

Kirki::add_field('kyma_theme', array(
    'settings'    => 'home_load_post_num',
    'label'       => __('Number of load more posts.', 'kyma'),
    'description'        => __('Number of posts you want to load on "Load More" button click', 'kyma'),
    'section'     => 'blog_sec',
    'type'        => 'number',
    'priority'    => 10,
    'default'     => 3,
    'choices'     => array(
        'min'  => 3,
        'max'  => 10,
        'step' => 1,
    ),
    'sanitize_callback'=>'kyma_sanitize_number',
    'active_callback'=>array(
        array(
                'setting'  => 'show_load_more_btn',
                'operator' => '==',
                'value'    => true,
            ),
    )
));

Kirki::add_field('kyma_theme', array(
    'settings'          => 'blog_string',
    'label'             => __('Strings Used in Home Blog section', 'kyma'),
    'section'           => 'blog_sec',
    'type'              => 'heading',
    'priority'          => 10,
    'sanitize_callback' => 'kyma_sanitize_text',
));
Kirki::add_field('kyma_theme', array(
    'settings'          => 'blog_load_more_text',
    'label'             => __('Load More Text', 'kyma'),
    'section'           => 'blog_sec',
    'type'              => 'text',
    'default'           =>$kyma_theme_options['blog_load_more_text'],
    'priority'          => 10,
    'sanitize_callback' => 'kyma_sanitize_text',
));
Kirki::add_field('kyma_theme', array(
    'settings'          => 'blog_more_loading',
    'label'             => __('Loading Text', 'kyma'),
    'section'           => 'blog_sec',
    'type'              => 'text',
    'default'           =>$kyma_theme_options['blog_more_loading'],
    'priority'          => 10,
    'sanitize_callback' => 'kyma_sanitize_text',
));
Kirki::add_field('kyma_theme', array(
    'settings'          => 'blog_no_more_post',
    'label'             => __('No more post Text', 'kyma'),
    'section'           => 'blog_sec',
    'type'              => 'text',
    'default'           =>$kyma_theme_options['blog_no_more_post'],
    'priority'          => 10,
    'sanitize_callback' => 'kyma_sanitize_text',
));
/* Extra Content */
Kirki::add_section('extra_sec', array(
    'title'      => __('Extra Content Options', 'kyma'),
    'panel'      => 'kyma_home_panel',
    'priority'   => 160,
    'capability' => 'edit_theme_options',
));
Kirki::add_field('kyma_theme', array(
    'settings'          => 'home_extra_title',
    'label'             => __('Extra Content Title', 'kyma'),
    'section'           => 'extra_sec',
    'type'              => 'text',
    'priority'          => 10,
    'transport'         => 'postMessage',
    'default'           => $kyma_theme_options['home_extra_title'],
    'sanitize_callback' => 'kyma_sanitize_text',
    'partial_refresh'   => array(
        'home_extra_title' => array(
            'selector'        => '#extra-heading',
            'render_callback' => function () {
                $options = get_option('kyma_theme_options');
                return $options['home_extra_title'];
            },
        ),
    ),
));
Kirki::add_field('kyma_theme', array(
    'settings'    => 'extra_content_home',
    'label'       => __('Put Your Extra Content Here', 'kyma'),
    'description' => __('This content will be shown on extra content section on Home.', 'kyma'),
    'section'     => 'extra_sec',
    'type'        => 'editor',
    'priority'    => 10,
    'transport'   => 'postMessage',
    'default'     => $kyma_theme_options['extra_content_home'],
));
/* Footer Callout */
Kirki::add_section('callout_sec', array(
    'title'      => __('Callout Options', 'kyma'),
    'panel'      => 'kyma_home_panel',
    'priority'   => 160,
    'capability' => 'edit_theme_options',
));
Kirki::add_field('kyma_theme', array(
    'settings'          => 'callout_bg_color',
    'label'             => __('Background Color', 'kyma'),
    'section'           => 'callout_sec',
    'type'              => 'color',
    'priority'          => 10,
    'transport'         => 'auto',
    'default'           => '#00a69b',
    'sanitize_callback' => 'kyma_sanitize_color',
    'output'            => array(
        array(
            'element'  => '.welcome_banner.full_colored, .welcome_banner.boxed_colored',
            'function' => 'style',
            'property' => 'background',
			'suffix'   => ' !important',
        ),
        array(
            'element'  => '.welcome_banner i.in_left,.welcome_banner i.in_right',
            'function' => 'style',
            'property' => 'color',
        ),
        array(
            'element'  => '.welcome_banner .btn_a:not(.color1):hover',
            'function' => 'style',
            'property' => 'color',
        ),
    ),
));
Kirki::add_field( 'kyma_theme', [
	'settings'    => 'callout_layout',
	'label'       => esc_html__( 'Call To Action Layout', 'kyma' ),
	'section'     => 'callout_sec',
	'type'        => 'radio-buttonset',
	'default'     => $kyma_theme_options['callout_layout'],
	'priority'    => 10,
	'choices'     => [
		'box_sec'   => esc_html__( 'Box Width', 'kyma' ),
		'full_sec'  => esc_html__( 'Full Width', 'kyma' ),
	],
] );
Kirki::add_field('kyma_theme', array(
    'settings'          => 'callout_title',
    'label'             => __('Call To Action Title', 'kyma'),
    'section'           => 'callout_sec',
    'type'              => 'text',
    'priority'          => 10,
    'transport'         => 'postMessage',
    'default'           => $kyma_theme_options['callout_title'],
    'sanitize_callback' => 'kyma_sanitize_text',
    'partial_refresh'   => array(
        'callout-title' => array(
            'selector'        => '#callout-title',
            'render_callback' => function () {
                $options = get_option('kyma_theme_options');
                return $options['callout_title'];
            },
        ),
    ),
));

Kirki::add_field('kyma_theme', array(
    'settings'          => 'callout_description',
    'label'             => __('Call To Action Description', 'kyma'),
    'section'           => 'callout_sec',
    'type'              => 'textarea',
    'priority'          => 10,
    'transport'         => 'postMessage',
    'default'           => $kyma_theme_options['callout_description'],
    'sanitize_callback' => 'kyma_sanitize_textarea',
    'partial_refresh'   => array(
        'callout-description' => array(
            'selector'        => '.intro_text',
            'render_callback' => function () {
                $options = get_option('kyma_theme_options');
                return $options['callout_description'];
            },
        ),
    ),
));
Kirki::add_field('kyma_theme', array(
    'settings'          => 'callout_btn_icon',
    'label'             => __('Call To Action Button Icon', 'kyma'),
    'section'           => 'callout_sec',
    'type'              => 'generic',
    'priority'          => 10,
    'default'           => $kyma_theme_options['callout_btn_icon'],
    'choices'     => array(
		'type'     => 'text',
		'class'    => 'icp icp-auto',
		'data-placement'=>"bottomRight",
		'onmouseover'=>'popup_icon_picker()'
	),
    'sanitize_callback' => 'kyma_sanitize_text',
));
Kirki::add_field('kyma_theme', array(
    'settings'          => 'callout_btn_text',
    'label'             => __('Call To Action Button Text', 'kyma'),
    'section'           => 'callout_sec',
    'type'              => 'text',
    'priority'          => 10,
    'transport'         => 'postMessage',
    'default'           => $kyma_theme_options['callout_btn_text'],
    'sanitize_callback' => 'kyma_sanitize_text',
));

Kirki::add_field('kyma_theme', array(
    'settings'          => 'callout_btn_link',
    'label'             => __('Call To Action Button URL', 'kyma'),
    'section'           => 'callout_sec',
    'type'              => 'url',
    'priority'          => 10,
    'transport'         => 'postMessage',
    'default'           => $kyma_theme_options['callout_btn_link'],
    'sanitize_callback' => 'esc_url',
));

/* Home Page Customizer */
Kirki::add_section('home_customize_section', array(
    'title'      => __('Home Page Reorder Sections', 'kyma'),
    'panel'      => 'kyma_home_panel',
    'priority'   => 160,
    'capability' => 'edit_theme_options',
));
Kirki::add_field('kyma_theme', array(
    'type'     => 'sortable',
    'settings' => 'home_sections',
    'label'    => __('Here You can reorder your homepage section', 'kyma'),
    'section'  => 'home_customize_section',
    'default'  => array(
        'service',
        'portfolio',
        'blog',
        'callout',
    ),
    'choices'  => array(
        'service'   => esc_attr__('Service Section', 'kyma'),
        'portfolio' => esc_attr__('Portfolio Section', 'kyma'),
        'blog'      => esc_attr__('Blog Section', 'kyma'),
        'content'   => esc_attr__('Extra Content Section', 'kyma'),
        'callout'   => esc_attr__('Callout Section', 'kyma'),
    ),
    'priority' => 10,
));

function kyma_sanitize_text($input)
{
    return wp_kses_post(force_balance_tags($input));
}

function kyma_sanitize_checkbox($checked)
{
    return ((isset($checked) && (true == $checked || 'on' == $checked)) ? true : false);
}

/**
 * Sanitize number options
 */
function kyma_sanitize_number($value)
{
    if (is_array($value)) {
        foreach ($value as $key => $val) {
            $v[$key] = is_numeric($val) ? $val : intval($val);
        }
        return $v;
    } else {
        return (is_numeric($value)) ? $value : intval($value);
    }
}
function kyma_sanitize_selected($value)
{
    if ($value[0] == '') {
        return $value = '';
    } else {
        return wp_kses_post($value);
    }
}
function kyma_sanitize_color($color)
{

    if ($color == "transparent") {
        return $color;
    }

    $named = json_decode('{"transparent":"transparent", "aliceblue":"#f0f8ff","antiquewhite":"#faebd7","aqua":"#00ffff","aquamarine":"#7fffd4","azure":"#f0ffff", "beige":"#f5f5dc","bisque":"#ffe4c4","black":"#000000","blanchedalmond":"#ffebcd","blue":"#0000ff","blueviolet":"#8a2be2","brown":"#a52a2a","burlywood":"#deb887", "cadetblue":"#5f9ea0","chartreuse":"#7fff00","chocolate":"#d2691e","coral":"#ff7f50","cornflowerblue":"#6495ed","cornsilk":"#fff8dc","crimson":"#dc143c","cyan":"#00ffff", "darkblue":"#00008b","darkcyan":"#008b8b","darkgoldenrod":"#b8860b","darkgray":"#a9a9a9","darkgreen":"#006400","darkkhaki":"#bdb76b","darkmagenta":"#8b008b","darkolivegreen":"#556b2f", "darkorange":"#ff8c00","darkorchid":"#9932cc","darkred":"#8b0000","darksalmon":"#e9967a","darkseagreen":"#8fbc8f","darkslateblue":"#483d8b","darkslategray":"#2f4f4f","darkturquoise":"#00ced1", "darkviolet":"#9400d3","deeppink":"#ff1493","deepskyblue":"#00bfff","dimgray":"#696969","dodgerblue":"#1e90ff", "firebrick":"#b22222","floralwhite":"#fffaf0","forestgreen":"#228b22","fuchsia":"#ff00ff", "gainsboro":"#dcdcdc","ghostwhite":"#f8f8ff","gold":"#ffd700","goldenrod":"#daa520","gray":"#808080","green":"#008000","greenyellow":"#adff2f", "honeydew":"#f0fff0","hotpink":"#ff69b4", "indianred ":"#cd5c5c","indigo ":"#4b0082","ivory":"#fffff0","khaki":"#f0e68c", "lavender":"#e6e6fa","lavenderblush":"#fff0f5","lawngreen":"#7cfc00","lemonchiffon":"#fffacd","lightblue":"#add8e6","lightcoral":"#f08080","lightcyan":"#e0ffff","lightgoldenrodyellow":"#fafad2", "lightgrey":"#d3d3d3","lightgreen":"#90ee90","lightpink":"#ffb6c1","lightsalmon":"#ffa07a","lightseagreen":"#20b2aa","lightskyblue":"#87cefa","lightslategray":"#778899","lightsteelblue":"#b0c4de", "lightyellow":"#ffffe0","lime":"#00ff00","limegreen":"#32cd32","linen":"#faf0e6", "magenta":"#ff00ff","maroon":"#800000","mediumaquamarine":"#66cdaa","mediumblue":"#0000cd","mediumorchid":"#ba55d3","mediumpurple":"#9370d8","mediumseagreen":"#3cb371","mediumslateblue":"#7b68ee", "mediumspringgreen":"#00fa9a","mediumturquoise":"#48d1cc","mediumvioletred":"#c71585","midnightblue":"#191970","mintcream":"#f5fffa","mistyrose":"#ffe4e1","moccasin":"#ffe4b5", "navajowhite":"#ffdead","navy":"#000080", "oldlace":"#fdf5e6","olive":"#808000","olivedrab":"#6b8e23","orange":"#ffa500","orangered":"#ff4500","orchid":"#da70d6", "palegoldenrod":"#eee8aa","palegreen":"#98fb98","paleturquoise":"#afeeee","palevioletred":"#d87093","papayawhip":"#ffefd5","peachpuff":"#ffdab9","peru":"#cd853f","pink":"#ffc0cb","plum":"#dda0dd","powderblue":"#b0e0e6","purple":"#800080", "red":"#ff0000","rosybrown":"#bc8f8f","royalblue":"#4169e1", "saddlebrown":"#8b4513","salmon":"#fa8072","sandybrown":"#f4a460","seagreen":"#2e8b57","seashell":"#fff5ee","sienna":"#a0522d","silver":"#c0c0c0","skyblue":"#87ceeb","slateblue":"#6a5acd","slategray":"#708090","snow":"#fffafa","springgreen":"#00ff7f","steelblue":"#4682b4", "tan":"#d2b48c","teal":"#008080","thistle":"#d8bfd8","tomato":"#ff6347","turquoise":"#40e0d0", "violet":"#ee82ee", "wheat":"#f5deb3","white":"#ffffff","whitesmoke":"#f5f5f5", "yellow":"#ffff00","yellowgreen":"#9acd32"}', true);

    if (isset($named[strtolower($color)])) {
        /* A color name was entered instead of a Hex Value, convert and send back */
        return $named[strtolower($color)];
    }

    $color = str_replace('#', '', $color);
    if (strlen($color) == 3) {
        $color = $color . $color;
    }
    if (preg_match('/^[a-f0-9]{6}$/i', $color)) {
        return '#' . $color;
    }
    //$this->error = $this->field;
    return false;
}

function kyma_sanitize_textarea($value)
{
    return wp_kses_post(force_balance_tags($value));
}

function kyma_customize_register($wp_customize)
{
    wp_enqueue_style('customizercustom_css', get_template_directory_uri() . '/css/customizer.css');

    $wp_customize->add_section('kyma_pro', array(
        'title'    => __('Upgrade to Kyma Premium', 'kyma'),
        'priority' => 999,
    ));

    $wp_customize->add_setting('kyma_pro', array(
        'default'           => null,
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control(new Kyma_Pro_Control($wp_customize, 'kyma_pro', array(
        'label'    => __('Kyma Premium', 'kyma'),
        'section'  => 'kyma_pro',
        'settings' => 'kyma_pro',
        'priority' => 1,
    )));

    // Move background color setting alongside background image.
    $wp_customize->get_control('background_color')->section  = 'background_image';
    $wp_customize->get_control('background_color')->priority = 20;

    $wp_customize->get_control('header_textcolor')->section  = 'header_image';
    $wp_customize->get_control('header_textcolor')->priority = 11;

// Change control title and description.
    $wp_customize->get_section('background_image')->title       = esc_html__('Site Layout & Background', 'kyma');
    $wp_customize->get_section('background_image')->description = esc_html__('Site Layout & Background Options', 'kyma');

    $wp_customize->get_section('title_tagline')->title = esc_html__('Site Logo/Title/Tagline', 'kyma');
// Change header image section title & priority.
    $wp_customize->get_section('header_image')->title = esc_html__('Header Options', 'kyma');

// move general control into general panel
    $wp_customize->get_section('static_front_page')->panel = 'kyma_general_settings_panel';
    $wp_customize->get_section('title_tagline')->panel     = 'kyma_general_settings_panel';
    $wp_customize->get_section('background_image')->panel  = 'kyma_general_settings_panel';
    $wp_customize->get_section('header_image')->panel      = 'kyma_general_settings_panel';

// Selective refresh.
    $wp_customize->get_setting('blogname')->transport         = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport  = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';
	
	$wp_customize->get_control('header_textcolor')->label = esc_html__( 'Logo Text Color', 'kyma' );

    $wp_customize->selective_refresh->add_partial('blogname', array(
        'selector'        => '.site-title a',
        'render_callback' => function () {return get_bloginfo('name', 'display');},
    ));

    $wp_customize->selective_refresh->add_partial('blogdescription', array(
        'selector'        => '.site-description',
        'render_callback' => function () {return get_bloginfo('description', 'display');},
    ));
}
add_action('customize_register', 'kyma_customize_register', 11);



/* Kyma Pro control */
if (class_exists('WP_Customize_Control') && !class_exists('Kyma_Pro_Control')):
    class Kyma_Pro_Control extends WP_Customize_Control
{

        /**
         * Render the content on the theme customizer page
         */
        public function render_content()
    {
            ?>
                                    <label style="overflow: hidden; zoom: 1;">
                                        <div class="col-md-2 col-sm-6 upsell-btn">
                                                <a style="margin-bottom:20px;margin-left:20px;" href="http://www.webhuntinfotech.com/webhunt_theme/kyma-advanced-premium-wordpress-theme/" target="blank" class="btn btn-success btn"><?php _e('Upgrade to Kyma Premium', 'kyma');?> </a>
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <img class="kyma_img_responsive " src="<?php echo get_template_directory_uri() . '/images/Kyma_Pro.png' ?>">
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <h3 style="margin-top:10px;margin-left: 20px;text-decoration:underline;color:#333;"><?php echo _e('kyma Premium - Features', 'kyma'); ?></h3>
                                                <ul style="padding-top:10px">
                                                    <li class="upsell-kyma"> <div class="dashicons dashicons-yes"></div> <?php _e('Responsive Design', 'kyma');?> </li>
                                                    <li class="upsell-kyma"> <div class="dashicons dashicons-yes"></div> <?php _e('Beautiful & Amazing Shortcodes', 'kyma');?> </li>
                                                    <li class="upsell-kyma"> <div class="dashicons dashicons-yes"></div> <?php _e('Side Menu Header', 'kyma');?> </li>
                                                    <li class="upsell-kyma"> <div class="dashicons dashicons-yes"></div> <?php _e('Dark Menu/submenu', 'kyma');?> </li>
                                                    <li class="upsell-kyma"> <div class="dashicons dashicons-yes"></div> <?php _e('More than 48 Page Templates', 'kyma');?> </li>
                                                    <li class="upsell-kyma"> <div class="dashicons dashicons-yes"></div> <?php _e('3 Types of sliders', 'kyma');?> </li>
                                                    <li class="upsell-kyma"> <div class="dashicons dashicons-yes"></div> <?php _e('4 Types Service Sections', 'kyma');?> </li>
                                                    <li class="upsell-kyma"> <div class="dashicons dashicons-yes"></div> <?php _e('3 Types Portfolio Sections', 'kyma');?> </li>
                                                    <li class="upsell-kyma"> <div class="dashicons dashicons-yes"></div> <?php _e('3 Types Testimonial Sections', 'kyma');?> </li>
                                                    <li class="upsell-kyma"> <div class="dashicons dashicons-yes"></div> <?php _e('3 Types Our Features Sections', 'kyma');?> </li>
                                                    <li class="upsell-kyma"> <div class="dashicons dashicons-yes"></div> <?php _e('2 Types Client Sections', 'kyma');?> </li>
                                                    <li class="upsell-kyma"> <div class="dashicons dashicons-yes"></div> <?php _e('3 Types Team Sections', 'kyma');?> </li>
                                                    <li class="upsell-kyma"> <div class="dashicons dashicons-yes"></div> <?php _e('3 Magical Fun Facts Style', 'kyma');?> </li>
                                                    <li class="upsell-kyma"> <div class="dashicons dashicons-yes"></div> <?php _e('3 types Pricing Tables', 'kyma');?> </li>
                                                    <li class="upsell-kyma"> <div class="dashicons dashicons-yes"></div> <?php _e('3 Types Footer Callouts', 'kyma');?> </li>
                                                    <li class="upsell-kyma"> <div class="dashicons dashicons-yes"></div> <?php _e('3 Different Types of Blog Templates', 'kyma');?> </li>
                                                    <li class="upsell-kyma"> <div class="dashicons dashicons-yes"></div> <?php _e('4 Types of Portfolio Templates', 'kyma');?></li>
                                                    <li class="upsell-kyma"> <div class="dashicons dashicons-yes"></div> <?php _e('Stylish Custom Widgets', 'kyma');?> </li>
                                                    <li class="upsell-kyma"> <div class="dashicons dashicons-yes"></div> <?php _e('Redux Options Panel', 'kyma');?> </li>
                                                    <li class="upsell-kyma"> <div class="dashicons dashicons-yes"></div> <?php _e('Unlimited Colors Scheme', 'kyma');?></li>
                                                    <li class="upsell-kyma"> <div class="dashicons dashicons-yes"></div> <?php _e('Patterns Background', 'kyma');?>   </li>
                                                    <li class="upsell-kyma"> <div class="dashicons dashicons-yes"></div> <?php _e('WPML Compatible', 'kyma');?>   </li>
                                                    <li class="upsell-kyma"> <div class="dashicons dashicons-yes"></div> <?php _e('Woo-commerce Compatible', 'kyma');?>
                                                    <li class="upsell-kyma"> <div class="dashicons dashicons-yes"></div> <?php _e('Portfolio layout with Isotope effect', 'kyma');?> </li>
                                                    <li class="upsell-kyma"> <div class="dashicons dashicons-yes"></div> <?php _e('Coming Soon/Maintenance Mode Option', 'kyma');?> </li>
                                                    <li class="upsell-kyma"> <div class="dashicons dashicons-yes"></div> <?php _e('Translation Ready', 'kyma');?> </li>
                                                    <li class="upsell-kyma"> <div class="dashicons dashicons-yes"></div> <?php _e('Free Updates', 'kyma');?> </li>
                                                    <li class="upsell-kyma"> <div class="dashicons dashicons-yes"></div> <?php _e('Quick Support', 'kyma');?> </li>
                                                </ul>
                                        </div>
                                        <div class="col-md-2 col-sm-6 upsell-btn upsell-btn-bottom">
                                                <a style="margin-bottom:20px;margin-left:20px;" href="http://www.webhuntinfotech.com/webhunt_theme/kyma-advanced-premium-wordpress-theme/" target="blank" class="btn btn-success btn"><?php _e('Upgrade to Kyma Premium', 'kyma');?> </a>
                                        </div>

                                        <p>
                                            <?php
    printf(__('If you Like our Products , Please Rate us 5 star on %1$sWordPress.org%2$s.  We\'d really appreciate it! </br></br>  Thank You', 'kyma'), '<a target="" href="https://wordpress.org/support/view/theme-reviews/kyma?filter=5">', '</a>');
            ?>
                                        </p>
                                    </label>
                                    <?php
    }
    }

endif;
add_action('customize_register', function ($wp_customize) {
    class kyma_Customize_Heading extends WP_Customize_Control
    {
        public $type = 'heading';

        public function render_content()
        {
            if (!empty($this->label)): ?>
                <h3 class="kyma-accordion-section-title"><?php echo esc_html($this->label); ?></h3>
            <?php endif;

            if ($this->description) {?>
                <span class="description customize-control-description">
                <?php echo wp_kses_post($this->description); ?>
                </span>
            <?php }
        }
    }
   
    // Register our custom control with Kirki
    add_filter('kirki/control_types', function ($controls) {
        $controls['heading'] = 'kyma_Customize_Heading';
        return $controls;
    });
});
?>