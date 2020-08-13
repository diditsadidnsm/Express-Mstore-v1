<?php

add_action( 'wp_enqueue_scripts', 'twx_custom_styles' );
function twx_custom_styles( $customizer_style ) {
	
	
	/*--- All primary color classes ---*/
	
	//Background color
	$primary_color = get_theme_mod( 'primary_color', '#FFAC00' );
	$customizer_style .= 'button, input[type="submit"], input[type="button"], input[type="reset"], .to-top, #site-navigation .sub-menu li:hover > a, .banner-button { background-color: ' . $primary_color . ' }';
	
	//Color
	$customizer_style .= 'a, h1 a, h2 a, h3 a, h4 a, h5 a, h6 a, .banner-button:hover, button:hover, input[type="submit"]:hover, input[type="button"]:hover, input[type="reset"]:hover, .to-top:hover, .special .meta-post .fa, .hentry .meta-post a:hover, .special h2.title-post a:hover, .widget-section .widgettitle, .default-testimonials .client-info .client .client-name, .type-team.type-b .team-social li a, .type-team .team-content .name, #site-navigation ul li a:hover, #site-navigation ul li a:hover { color: ' . $primary_color . ' }';
	
	//Border color
	$customizer_style .= '.banner-button, input[type="text"]:focus, input[type="email"]:focus, textarea:focus, input[type="number"]:focus, input[type="password"]:focus, input[type="tel"]:focus, input[type="date"]:focus, input[type="datetime"]:focus, input[type="datetime-local"]:focus, input[type="month"]:focus, input[type="time"]:focus, input[type="week"]:focus, input[type="url"]:focus, input[type="search"]:focus, input[type="color"]:focus, button, input[type="button"], input[type="reset"], input[type="submit"], .divider-separator, .type-team.type-b .team-social li a { border-color: ' . $primary_color . ' } ';
	
	
	
	/*--- All customizer options styles ---*/
	
	//Site title color
	$site_title_color = get_theme_mod( 'site_title_color', '#ffffff' );
	$customizer_style .= '.site-title a, .site-title a:hover { color: ' . esc_attr( $site_title_color ) . '; } ';
	
	//Tagline color
	$tagline_color = get_theme_mod( 'tagline_color', '#ffffff' );
	$customizer_style .= '.site-description { color: ' . esc_attr($tagline_color) . '; } ';
	
	//Banner background
	$banner_type = get_theme_mod( 'banner_type', 'image' );
	$banner_bg = '';
	if ( $banner_type == 'image' ) {
		$banner_bg = 'background: url('.get_theme_mod( 'banner_bg_img', get_stylesheet_directory_uri() . '/images/banner1.jpg' ) . ');';
	}elseif( $banner_type == 'bg-color' ){
		$banner_bg = 'background: ' . get_theme_mod( 'banner_bg_color', '#000000' ) . ';';
	}elseif( $banner_type == 'bg-gradient' ){
		$banner_bg_gradient_1 = get_theme_mod( 'banner_bg_gradient_1', '#1C76A8' );
		$banner_bg_gradient_2 = get_theme_mod( 'banner_bg_gradient_2', '#3DC5EF' );
		$banner_bg_gradient_3 = get_theme_mod( 'banner_bg_gradient_3', '#A4DAF6' );
		
		$banner_bg = 'background: -ms-linear-gradient(to bottom right, ' . $banner_bg_gradient_1 . ', ' . $banner_bg_gradient_2 . ', ' . $banner_bg_gradient_3 . '); background: -o-linear-gradient(to bottom right, ' . $banner_bg_gradient_1 . ', ' . $banner_bg_gradient_2 . ', ' . $banner_bg_gradient_3 . '); background: -moz-linear-gradient(to bottom right, ' . $banner_bg_gradient_1 . ', ' . $banner_bg_gradient_2 . ', ' . $banner_bg_gradient_3 . '); background: -webkit-linear-gradient(to bottom right, ' . $banner_bg_gradient_1 . ', ' . $banner_bg_gradient_2 . ', ' . $banner_bg_gradient_3 . '); background: linear-gradient(to bottom right, ' . $banner_bg_gradient_1 . ', ' . $banner_bg_gradient_2 . ', ' . $banner_bg_gradient_3 . ');';
	}
	
	$banner_bg_size = get_theme_mod('banner_bg_size', 'cover');
	$banner_bg_pos = get_theme_mod('banner_bg_pos', 'top-center !important');
	$banner_bg_pos = str_replace('-',' ',$banner_bg_pos);
	$banner_height = ( is_front_page() ? get_theme_mod('banner_height', '700') : 350 );
	$banner_bg_repeat = '';
	
	if( get_theme_mod('banner_bg_repeat', 1) != 0 ){
		$banner_bg_repeat = ' background-repeat: no-repeat;';
	}
	
	$customizer_style .= '.header-background, .other-header { ' . $banner_bg . ' background-size: ' . esc_attr($banner_bg_size) . '; background-position: ' . esc_attr($banner_bg_pos) . ';' . $banner_bg_repeat . ' height: ' . esc_attr($banner_height) . 'px; }';
	
	//Page wrapper padding
	$pg_top_padding = get_theme_mod( 'page_top_padding', '80' );
	$pg_bottom_padding = get_theme_mod( 'page_bottom_padding', '80' );
	$customizer_style .= '.page-wrap { padding-top: ' . intval($pg_top_padding) . 'px; padding-bottom: ' . intval($pg_bottom_padding) . 'px; }';
	
	//Theme text color
	$theme_text_color = get_theme_mod( 'theme_text_color', '#8e88aa' );
	$customizer_style .= 'body { color:' . esc_attr($theme_text_color) . '}';
	
	//Body background color
	$body_bg = get_theme_mod( 'background_color', '#ffffff' );
	$customizer_style .= 'body { background-color: ' . esc_attr($body_bg) . '}';
	
	
	
	/*--- Apply all the customizer styles ---*/
	
	if( $customizer_style ){
		wp_add_inline_style( 'customizer-style', $customizer_style );
	}
	
}