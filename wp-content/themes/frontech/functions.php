<?php
/**
 * Frontech functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Frontech
 */
 
require_once( trailingslashit( get_stylesheet_directory() ) . 'customizer-button/class-customize.php' );

function frontech_enqueue_styles() {
	$kyma_theme_options = kyma_theme_options();
	$color_scheme = get_theme_mod('color_scheme_child', 'default');
	
    $parent_style = 'Kyma';

    $dep = array('bootstrap');
    if(file_exists(get_template_directory().'/css/plugins.css')){
    	$dep = array('kyma-plugins');
    }
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css', $dep );
    wp_enqueue_style( 'frontech',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
	
	wp_enqueue_style('responsive', get_stylesheet_directory_uri() . '/css/responsive.css');

	wp_enqueue_style('kyma-color-scheme', get_stylesheet_directory_uri() . '/css/colors/' . esc_attr($color_scheme) . '.css');
	
	$frontech_load_post_button = intval( $kyma_theme_options['show_load_more_btn'] );
	if ( $frontech_load_post_button ) {
		$frontech_blog_post_count   = absint( $kyma_theme_options['home_load_post_num'] );
		$frontech_blog_no_more_post_text = $kyma_theme_options['blog_no_more_post'];
		wp_enqueue_script( 'load-posts', get_stylesheet_directory_uri() . '/js/frontech-load-posts.js', array('jquery')	, '16092019', true );
		wp_localize_script( 'load-posts', 'frontech_load_more_posts_variable', array(
			'ajaxurl'  => admin_url( 'admin-ajax.php' ),
			'ppp'=> $frontech_blog_post_count,
			'noposts'  => $frontech_blog_no_more_post_text,
		) );
	}
}
add_action( 'wp_enqueue_scripts', 'frontech_enqueue_styles' );

// Add child theme settings to options array - UPDATED
function frontech_updateoption_child_settings() {

	// Set possible options names
	$kyma_theme_options  = 'kyma_theme_options';
	
	// Get options values (theme options)
	$options_free = get_option( $kyma_theme_options );

	// Get child settinsg values
	$options_child_settings = get_option( 'child_theme_option_updated' );

	// Only set child settings values if not already set 
	if ( $options_child_settings != 1 ) {
		$options_free['header_topbar_bg_color'] = '#dd3333';
		$options_free['callout_bg_color'] = '#dd3333';
		$options_free['slider_title_bg_color'] = 'rgba(0,0,0,.65)';
		$options_free['slider_subtitle_bg_color'] = 'rgba(0,0,0,.4)';
		$options_free['nav_color'] = '#dd3333';
		$options_free['back_to_top'] = '#dd3333';
		$options_free['footer_bg_color'] = '#2f2f2f';
		$options_free['color_scheme_child'] = '';
		
		// Add child settings to theme options array
		update_option( $kyma_theme_options, $options_free );
	}

	// Set the child settings flag
	update_option( 'child_theme_option_updated', 1 );

}
add_action( 'init', 'frontech_updateoption_child_settings', 999 );

function frontech_excerpt_more($more) {
    return '';
}
add_filter('excerpt_more', 'frontech_excerpt_more');

function frontech_the_excerpt( $excerpt ){
    $post = get_post();
    $excerpt .= '<a href="' . esc_url(get_permalink($post->ID)) . '" class="btn frontech-btn"><span></span>' . esc_html__('Read More', 'frontech') . '</a>';
    return $excerpt;
}
add_filter( 'the_excerpt', 'frontech_the_excerpt' );

function frontech_content($limit) {
	$post = get_post();
	$my_post = get_post($post->ID);
	$content = $my_post->post_content;
	 if (str_word_count($content)>=$limit) {
		 $more = '</br></br><a href="' . esc_url(get_permalink($post->ID)) . '" class="btn frontech-btn"><span></span>' . esc_html__('Read More', 'frontech') . '</a>';
		 $content = wp_trim_words( $content , $limit , $more);
	 }
	 return $content;
}

function frontech_custom_excerpt_length( $length ) {
	$custom = get_theme_mod('custom_excerpt_length', 55);
    if( $custom != '' ) {
        return $length = intval( $custom );
    } else {
        return $length;
    }
}
add_filter( 'excerpt_length', 'frontech_custom_excerpt_length', 999 );

function frontech_theme_setup()
{
	load_child_theme_textdomain('frontech', get_template_directory() . '/lang');
	
	/* Remove Parent Theme Filters */
	remove_filter('excerpt_more', 'kyma_excerpt_more');
	remove_filter('the_excerpt', 'kyma_the_excerpt' );
	
	/* Custom Header Support */
	add_theme_support( 'custom-header', apply_filters( 'kyma_custom_header_args', array(
		'default-text-color'     => 'ffffff',
	) ) );
	
	/* Kirki Config */
	Kirki::add_config( 'frontech_theme', array(
		'capability'    => 'edit_theme_options',
		'option_type'   => 'theme_mod',
	) );
	
	Kirki::add_field('frontech_theme', array(
		'settings'          => 'color_scheme_child',
		'label'             => esc_html__('Color scheme', 'frontech'),
		'description'       => esc_html__('Select a color scheme', 'frontech'),
		'section'           => 'kyma_general_section',
		'type'              => 'preset',
		'priority'          => 9,
		'default'           => 'default',
		'sanitize_callback' => 'esc_attr',
		'choices'    =>array(
			'default'=>array(
				'label' => esc_html__('Default','frontech'),
				'settings' => array(
					'kyma_theme_options[header_topbar_bg_color]' => '#dd3333',
					'kyma_theme_options[nav_color]' => '#dd3333',
					'kyma_theme_options[slider_title_bg_color]' => '#dd3333',
					'kyma_theme_options[callout_bg_color]' => '#dd3333',
					'kyma_theme_options[back_to_top]' => '#dd3333',
			   ),
			),
			'orange'=>array(
				'label' => esc_html__('Orange','frontech'),
				'settings' => array(
					'kyma_theme_options[header_topbar_bg_color]' => '#F86923',
					'kyma_theme_options[nav_color]' => '#F86923',
					'kyma_theme_options[slider_title_bg_color]' => '#F86923',
					'kyma_theme_options[callout_bg_color]' => '#F86923',
					'kyma_theme_options[back_to_top]' => '#F86923',
			   ),
			),
			'cyan'=>array(
				'label' => esc_html__('Cyan','frontech'),
				'settings' => array(
					'kyma_theme_options[header_topbar_bg_color]' => '#1ccdca',
					'kyma_theme_options[nav_color]' => '#1ccdca',
					'kyma_theme_options[slider_title_bg_color]' => '#1ccdca',
					'kyma_theme_options[callout_bg_color]' => '#1ccdca',
					'kyma_theme_options[back_to_top]' => '#1ccdca',
			   ),
			),
		)

	));
	
	Kirki::add_field('kyma_theme', array(
		'settings'          => 'slider_title_bg_color',
		'label'             => esc_html__('Slider Title Background Color', 'frontech'),
		'section'           => 'slider_sec',
		'type'              => 'color',
		'priority'          => 10,
		'default'           => 'rgba(0,0,0,.65)',
		'choices'           => array(
			'alpha' => true
		),
		'output'            => array(
			array(
				'element'  => '.title_big:before',
				'function' => 'style',
				'property' => 'background',
			),

		),
		'transport'         => 'auto',
	));
	
	Kirki::add_field('kyma_theme', array(
		'settings'          => 'slider_subtitle_bg_color',
		'label'             => esc_html__('Slider Subtitle Background Color', 'frontech'),
		'section'           => 'slider_sec',
		'type'              => 'color',
		'priority'          => 10,
		'default'           => 'rgba(0,0,0,.4)',
		'choices'           => array(
			'alpha' => true
		),
		'output'            => array(
			array(
				'element'  => '.small_subtitle:before',
				'function' => 'style',
				'property' => 'background-color',
			),

		),
		'transport'         => 'auto',

	));
	
	Kirki::add_field('kyma_theme', array(
		'settings'          => 'footer_bg_color',
		'label'             => esc_html__('Footer Background Color', 'frontech'),
		'section'           => 'footer_sec',
		'type'              => 'color',
		'default'           => '#2f2f2f',
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
		'settings'          => 'header_bg_color',
		'label'             => esc_html__('Header Background Color', 'frontech'),
		'section'           => 'header_image',
		'type'              => 'color',
		'default'           => '#232323',
		'priority'          => 10,
		'output'            => array(
			array(
				'element'  	=> '.light_header #navigation_bar, #navigation_bar',
				'property' 	=> 'background-color',
			),
			array(
				'element'	=>'#main_nav.has_mobile_menu #nav_menu:before',
				'property' 	=> 'background-color',
				'suffix'   	=> '!important'
			)
		),
		'transport'         => 'auto',
		'js_vars'           => array(
			array(
				'element'  => '.light_header #navigation_bar, #navigation_bar',
				'function' => 'css',
				'property' => 'background-color',
			),
			array(
				'element'	=>'#main_nav.has_mobile_menu #nav_menu:before',
				'property' 	=> 'background-color',
				'suffix'   	=> '!important'
			)
		),
		'sanitize_callback' => 'kyma_sanitize_color',
	));
	
	Kirki::add_field('kyma_theme', array(
		'settings'          => 'nav_text_color',
		'label'             => esc_html__('Header Menu Text Color', 'frontech'),
		'section'           => 'header_image',
		'type'              => 'color',
		'default'           => '#ffffff',
		'priority'          => 10,
		'output'            => array(
			array(
				'element'  	=> '.light_header #navy > li > a, #navy > li:not(.current_page_item):hover > a:not(.nav_trigger),#navy > ul > li > a:hover',
				'property' 	=> 'color',
				'suffix'    => '!important'
			)
		),
		'transport'         => 'auto',
		'js_vars'           => array(
			array(
				'element'  => '.light_header #navy > li > a, #navy > li:not(.current_page_item):hover > a:not(.nav_trigger),#navy > ul > li > a:hover',
				'function' => 'css',
				'property' => 'color',
			)
		),
		'sanitize_callback' => 'kyma_sanitize_color',
	));
	
	Kirki::add_field('frontech_theme', array(
		'settings'    => 'custom_excerpt_length',
		'label'       => esc_html__('Home Blog Content Length', 'frontech'),
		'description' => esc_html__('Length of the home blog content in words (Max Length 100).', 'frontech'),
		'section'     => 'blog_sec',
		'type'        => 'number',
		'priority'    => 40,
		'default'     => 55,
		'choices'     => array(
			'min'  => 1,
			'max'  => 100,
			'step' => 1,
		),
		'sanitize_callback'=>'kyma_sanitize_number',
	));
	
	Kirki::add_field('frontech_theme', array(
		'settings'          => 'contact_info_popup_enable',
		'label'             => esc_html__('Logo Popup Enable', 'frontech'),
		'description'       => esc_html__('Show/Hide logo popup', 'frontech'),
		'section'           => 'title_tagline',
		'type'              => 'switch',
		'priority'          => 40,
		'default'           => 0,
		'sanitize_callback' => 'kyma_sanitize_checkbox',
	));
	
	Kirki::add_field('frontech_theme', array(
		'settings'          => 'company_short_desc',
		'label'             => esc_html__('Company Description', 'frontech'),
		'section'           => 'title_tagline',
		'type'              => 'text',
		'priority'          => 40,
		'default'           => esc_html__('Lorem Ipsum is simply dummy text of the printing text','frontech'),
		'sanitize_callback' => 'kyma_sanitize_text',
	));
	
	Kirki::add_field('frontech_theme', array(
		'settings'          => 'company_address',
		'label'             => esc_html__('Company Address', 'frontech'),
		'section'           => 'title_tagline',
		'type'              => 'text',
		'priority'          => 40,
		'default'           => esc_html__('26, Lorem Ipsum is simply dummy','frontech'),
		'sanitize_callback' => 'kyma_sanitize_text',
	));
	
	Kirki::add_field('frontech_theme', array(
		'settings'          => 'company_map_url',
		'label'             => esc_html__('Company Map URL', 'frontech'),
		'section'           => 'title_tagline',
		'type'              => 'text',
		'priority'          => 40,
		'default'           => '',
		'sanitize_callback' => 'esc_url',
	));
	
	/* Social Link */
	Kirki::add_field('frontech_theme', array(
		'settings'          => 'social_pinterest_link',
		'label'             => esc_html__('Pinterest Profile URL', 'frontech'),
		'section'           => 'kyma_topbar_section',
		'type'              => 'url',
		'priority'          => 11,
		'default'           => '',
		'sanitize_callback' => 'esc_url',
	));
}
add_action('after_setup_theme', 'frontech_theme_setup');

function frontech_customize_register( $wp_customize ) {

	$wp_customize->remove_control('kyma_theme_options[color_scheme]');
	$wp_customize->remove_control('kyma_theme_options[nav_color]');
	$wp_customize->get_setting( 'header_textcolor' )->default = '#fff';
	$wp_customize->get_section('slider_sec')->description = sprintf(__('See this %1$s documentation%2$s about adding slider in frontech theme.'),'<a href="https://www.webhuntinfotech.com/fontech-wordpress-theme-documentation/#home_page_setup" target="_blank">','</a>');

}
add_action('customize_register', 'frontech_customize_register', 100 );

add_filter('next_post_link', 'frontech_post_link_attributes');
add_filter('previous_post_link', 'frontech_post_link_attributes');
function frontech_post_link_attributes($output) {
    $injection = 'class="btn frontech-btn"';
    return str_replace('<a href=', '<a '.$injection.' href=', $output);
}

/* Custom Sanitization Function  */
function frontech_sanitize_text($input) {
    return wp_kses_post(force_balance_tags($input));
}

/* Ajax Load Moe posts */
add_action('wp_ajax_nopriv_frontech_more_post_ajax', 'frontech_more_post_ajax');
add_action('wp_ajax_frontech_more_post_ajax', 'frontech_more_post_ajax');
if ( !function_exists( 'frontech_more_post_ajax' ) ) {
    function frontech_more_post_ajax(){
        $kyma_theme_options = kyma_theme_options();
		
		$home_blog_layout = 'content';
		if(isset($kyma_theme_options['home_blog_layout'])){
			$home_blog_layout = $kyma_theme_options['home_blog_layout'];
		}

        $ppp                 = (isset($_POST['ppp'])) ? $_POST['ppp'] : 3;
        $offset              = (isset($_POST['offset'])) ? $_POST['offset'] : 0;
        
        $args = array(
            'post_type'      => 'post',
            'posts_per_page' => $ppp,
            'offset'         => $offset,
			'post_status' 	 => 'publish'
        );
        if (isset($kyma_theme_options['home_post_cat']) && !empty($kyma_theme_options['home_post_cat'])) {
            $args['category__in'] = $kyma_theme_options['home_post_cat'];
        }

        $loop = new WP_Query($args);
        $out  = '';
        $i    = 1;
        if ($loop->have_posts()):
			ob_start();
            while ($loop->have_posts()):
                $loop->the_post(); ?>
				<li class="filter_item_block grid-item" data-animation-delay="<?php echo 300 * $i; ?>" data-animation="rotateInUpLeft">
					<div class="blog_grid_block">
						<div class="feature_inner">
							<div class="feature_inner_corners">
								<?php
								if (get_post_gallery()) {
									$gallery = get_post_gallery(get_the_ID(), false);?>
									<div class="feature_inner_btns">
										<a href="#" class="expand_image btn frontech-btn"><i class="fa fa-expand"></i></a>
										<a href="<?php echo esc_url(get_the_permalink()); ?>"
										   class="icon_link btn frontech-btn"><i class="fa fa-link"></i></a>
									</div>
									<div class="porto_galla"><?php
									foreach ($gallery['src'] as $src) {
										?>
									<a title="<?php the_title_attribute(); ?>"
									   href="<?php echo esc_url($src); ?>" class="feature_inner_ling">
										<img src="<?php echo esc_url($src); ?>"
											 alt="<?php the_title_attribute(); ?>">
										</a><?php
									}
									if (has_post_thumbnail()) {
										$url = wp_get_attachment_url(get_post_thumbnail_id($post->ID)) ?>
									<a href="<?php echo esc_url($url); ?>" class="feature_inner_ling"
									   data-rel="magnific-popup">
										<?php if( $home_blog_layout == 'content'){
										   the_post_thumbnail('kyma_home_post_image');
										}else{
										   the_post_thumbnail('kyma_home_post_image_fluid');
										}
                                        ?>
										</a><?php } ?>
									</div><?php
								} elseif (has_post_thumbnail()) {
									$url = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
									?>
									<div class="feature_inner_btns">
										<a href="<?php echo esc_url($url); ?>" class="expand_image btn frontech-btn"><i
												class="fa fa-expand"></i></a>
										<a href="<?php echo esc_url(get_the_permalink()); ?>"
										   class="icon_link btn frontech-btn"><i class="fa fa-link"></i></a>
									</div>
									<div class="porto_galla">
										<a href="<?php echo esc_url($url); ?>" class="feature_inner_ling"
										   data-rel="magnific-popup">
										<?php 
										if( $home_blog_layout == 'content'){
										   the_post_thumbnail('kyma_home_post_image');
										}else{
										   the_post_thumbnail('kyma_home_post_image_fluid');
										}
										?>
										</a>
									</div><?php
								} ?>
							</div>
						</div>
						<div class="blog_grid_con">
                             <h6 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
							<span class="meta">
								<span class="meta_part">
									<a href="<?php esc_url(the_permalink()); ?>">
										<i class="far fa-clock"></i>
										<span><?php echo esc_html(get_the_date(get_option('date_format'), get_the_ID())); ?></span>
									</a>
								</span>
								<span class="meta_part">
									<i class="far fa-comment"></i>
									<?php esc_url(comments_popup_link(esc_html__('No Comments', 'frontech'), esc_html__('1 Comment', 'frontech'), esc_html__('% Comments', 'frontech'))); ?> <?php esc_url(edit_post_link(esc_html__('Edit', 'frontech'), ' &#124; ', '')); ?>
								</span>
							</span>
                             <?php the_excerpt(); ?>
                         </div>
					</div>
				</li>
               <?php $i != 3 ? $i++ : $i = 1;
            endwhile;
			echo ob_get_clean();
        endif;
        wp_reset_postdata();
		die;
    }
}