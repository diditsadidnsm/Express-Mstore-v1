<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Frontech
 */
?>
<!doctype html>
<!--[if lt IE 7 ]>
<html class="ie ie6 ie-lt10 ie-lt9 ie-lt8 ie-lt7 no-js" lang="en"> <![endif]-->
<!--[if IE 7 ]>
<html class="ie ie7 ie-lt10 ie-lt9 ie-lt8 no-js" lang="en"> <![endif]-->
<!--[if IE 8 ]>
<html class="ie ie8 ie-lt10 ie-lt9 no-js" lang="en"> <![endif]-->
<!--[if IE 9 ]>
<html class="ie ie9 ie-lt10 no-js" lang="en"> <![endif]-->
<!--[if gt IE 9]><!-->
<html class="no-js" <?php language_attributes(); ?>><!--<![endif]-->
<!-- the "no-js" class is for Modernizr. -->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
<?php 
	$kyma_theme_options = kyma_theme_options();
	
	$contact_info_popup = get_theme_mod('contact_info_popup_enable', 0);
	$company_short_desc = get_theme_mod('company_short_desc', esc_html__('Lorem Ipsum is simply dummy text of the printing text','frontech'));
	$company_address	= get_theme_mod('company_address', esc_html__('26, Lorem Ipsum is simply dummy','frontech'));
	$company_map_url	= get_theme_mod('company_map_url', '');
	$social_pinterest_link  = get_theme_mod('social_pinterest_link', '');
	
	wp_head(); ?>
</head>
<?php $frontech_class = "";
if ($kyma_theme_options['site_layout'] != "") {
    $frontech_class = 'site_boxed ';
}
$frontech_class .= isset($kyma_theme_options['headercolorscheme']) ? $kyma_theme_options['headercolorscheme'] : ''; ?>
<body <?php body_class("menu_button_mode preloader3 menu_button_mode " . $frontech_class); ?>>
<span id="stickymenu"
      style="display:none;"><?php echo isset($kyma_theme_options['headersticky']) ? absint($kyma_theme_options['headersticky']) : 1 ; ?></span>

<div id="preloader">
    <div class="spinner">
        <div class="sk-dot1"></div>
        <div class="sk-dot2"></div>
        <div class="rect3"></div>
        <div class="rect4"></div>
        <div class="rect5"></div>
    </div>
</div>
<div id="main_wrapper">
    <header id="site_header">
        <div class="topbar <?php echo esc_attr($kyma_theme_options['topbarcolor']); ?>">
            <!-- class ( topbar_colored  ) -->
            <div class="content clearfix">
				<?php if ($kyma_theme_options['contact_info_header']) { ?>
                <div class="top_details clearfix f_left"><?php
                    if ($kyma_theme_options['contact_phone']) {
                        ?>
                        <span><i class="fa fa-phone"></i><span
                            class="title"><?php esc_html_e('Call Us :', 'frontech') ?></span><a href="tel:<?php echo esc_attr($kyma_theme_options['contact_phone']); ?>"><?php echo esc_html($kyma_theme_options['contact_phone']); ?></a>
                        </span><?php
                    }
                    if ($kyma_theme_options['contact_email']) {
                        ?>
                        <span><i class="far fa-envelope"></i><span
                                class="title"><?php esc_html_e('Email :', 'frontech') ?></span>
							<a href="mailto:<?php echo esc_attr($kyma_theme_options['contact_email']); ?>"><?php echo esc_attr($kyma_theme_options['contact_email']); ?></a></span>
                    <?php } ?>
                </div>
                <?php } if ($kyma_theme_options['social_media_header']) { ?>
                    <div class="top-socials box_socials f_right">
                    <?php if ($kyma_theme_options['social_facebook_link'] != '') { ?>
                    <a href="<?php echo esc_url($kyma_theme_options['social_facebook_link']); ?>" target="_blank">
                        <i class="fab fa-facebook-f"></i>
                        </a><?php
                    }
                    if ($kyma_theme_options['social_twitter_link'] != '') {
                        ?>
                    <a href="<?php echo esc_url($kyma_theme_options['social_twitter_link']); ?>" target="_blank">
                        <i class="fab fa-twitter"></i>
                        </a><?php
                    }
                    if ($kyma_theme_options['social_instagram_link'] != '') {
                        ?>
                    <a href="<?php echo esc_url($kyma_theme_options['social_instagram_link']); ?>" target="_blank">
                        <i class="fab fa-instagram"></i>
                        </a><?php
                    }
                    if ($kyma_theme_options['social_skype_link'] != '') {
                        ?>
                    <a href="skype:<?php echo esc_attr($kyma_theme_options['social_skype_link']); ?>">
                        <i class="fab fa-skype"></i>
                        </a><?php
                    }
                    if ($kyma_theme_options['social_vimeo_link'] != '') {
                        ?>
                    <a href="<?php echo esc_url($kyma_theme_options['social_vimeo_link']); ?>" target="_blank">
                        <i class="fab fa-vimeo-square"></i>
                        </a><?php
                    }
					if ($social_pinterest_link != '') {
                        ?>
                    <a href="<?php echo esc_url($social_pinterest_link); ?>" target="_blank">
                        <i class="fab fa-pinterest"></i>
                        </a><?php
                    }
                    if ($kyma_theme_options['social_youtube_link'] != '') {
                        ?>
                    <a href="<?php echo esc_url($kyma_theme_options['social_youtube_link']); ?>" target="_blank">
                        <i class="fab fa-youtube"></i>
                        </a><?php
                    } ?>
                    </div><?php
                } ?>
            </div>
            <!-- End content -->
			<span class="top_expande not_expanded">
				<i class="no_exp fa fa-angle-double-down"></i>
				<i class="exp fa fa-angle-double-up"></i>
			</span>
        </div>
        <!-- End topbar -->
        <div id="navigation_bar"
             style="<?php if (get_header_image()) : ?> background-image:url('<?php header_image();?>'); <?php endif; ?>">
            <div class="content">
				<div id="logo-container" class="logo-container hasInfoCard hasHoverMe">
					<div id="logo" class="site-logo logo" <?php if ($kyma_theme_options['logo_layout'] == "right") {
						echo 'style="float:right;"';
					} ?>>
						<?php the_custom_logo(); ?>
						<h3 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"  title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home" class="site-logo-anch"><?php echo esc_html(get_bloginfo('name')); ?></a></h3>
						<?php $frontech_description = get_bloginfo( 'description', 'display' );
							  if ( $frontech_description || is_customize_preview() ) {
							   echo '<p class="site-description">'. esc_html( $frontech_description ).'</p>';
						} ?>
						</a>
					</div>
					<?php if ( isset( $contact_info_popup ) && $contact_info_popup == true ) : ?>
					<div id="infocard" class="logo-infocard">
						<div class="custom ">
							<div class="row">
								<div class="col-sm-5">
									<div class="infocard-wrapper text-center">
										<?php if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) { ?>
											<p><?php the_custom_logo(); ?></p>
										<?php } else {  ?>
											<h3 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"  title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home" class="site-logo-anch"><?php echo esc_html(get_bloginfo('name')); ?></a></h3>
										<?php }  ?>
										<?php if ( isset( $company_short_desc ) && $company_short_desc != '' ) { ?>
											<p><?php echo esc_html( $company_short_desc ); ?></p>
										<?php } ?>
									</div>
								</div>
								<div class="col-sm-7">
									<div class="custom contact-details">
										<?php if ($kyma_theme_options['contact_info_header']) { 
												if ($kyma_theme_options['contact_phone']) { ?>
												<p> <strong>
													<?php esc_html_e('Call Us', 'frontech') ?>:&nbsp;</span><a href="tel:<?php echo esc_attr($kyma_theme_options['contact_phone']); ?>"><?php echo esc_html($kyma_theme_options['contact_phone']); ?></a>
												</strong><br>	
													<?php
												} ?>
												<?php 
												if ($kyma_theme_options['contact_email']) { ?>
												<?php esc_html_e('Email', 'frontech') ?>:&nbsp;	
													<a href="mailto:<?php echo esc_attr($kyma_theme_options['contact_email']); ?>"><?php echo esc_attr($kyma_theme_options['contact_email']); ?></a></p>
												<?php } ?>
										<?php } ?>
										<?php if ( isset( $company_address ) && $company_address != '' ) { ?>
										<p><b> <?php esc_html_e('Your Company Address', 'frontech') ?></b><br><?php echo wp_kses_post( $company_address ); ?></p>
										<?php } ?>
										<?php if ( isset($company_map_url) &&  $company_map_url != '' ) { ?>
										<a href="<?php echo esc_url($company_map_url); ?>" target="_blank" class="map-link"> 
											<i class="fa fa-map" aria-hidden="true"></i> 
											<span><?php esc_html_e('Open in Google Maps', 'frontech') ?></span> 
										</a>
										<?php } ?>
									</div>
									<div style="height:20px;"></div>
									<?php if ($kyma_theme_options['social_media_header']) { ?>
										<ul class="social-icons">
											<?php if ($kyma_theme_options['social_facebook_link'] != '') { ?>
												<li class="social-icons-li">
													<a href="<?php echo esc_url($kyma_theme_options['social_facebook_link']); ?>" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>
												</li>
												<?php
											} if ($kyma_theme_options['social_twitter_link'] != '') {
											?>
												<li class="social-icons-li">
													<a href="<?php echo esc_url($kyma_theme_options['social_twitter_link']); ?>" target="_blank" title="Twitter"><i class="fab fa-twitter"></i></a>
												</li>
												<?php
											} if ($kyma_theme_options['social_google_plus_link'] != '') {
											?>	
												<li class="social-icons-li">
													<a href="<?php echo esc_url($kyma_theme_options['social_google_plus_link']); ?>" target="_blank" title="Google Plus"><i class="fab fa-google-plus-g"></i></a>
												</li>
												<?php
											} if ($kyma_theme_options['social_skype_link'] != '') {
											?>
												<li class="social-icons-li">
													<a href="<?php echo esc_url($kyma_theme_options['social_skype_link']); ?>" target="_blank" title="Skype"><i class="fab fa-skype"></i></a>
												</li>
												<?php
											} if ($kyma_theme_options['social_vimeo_link'] != '') {
											?>
												<li class="social-icons-li">
													<a href="<?php echo esc_url($kyma_theme_options['social_vimeo_link']); ?>" target="_blank" title="Vimeo"><i class="fab fa-vimeo-square"></i></a>
												</li>
												<?php
											}if ( isset($social_pinterest_link) && $social_pinterest_link != '' ) {
											?>
												<li class="social-icons-li">
													<a href="<?php echo esc_url($social_pinterest_link); ?>" target="_blank" title="Pinterest"><i class="fab fa-pinterest"></i></a>
												</li>
												<?php
											} if ($kyma_theme_options['social_youtube_link'] != '') {
											?>
												<li class="social-icons-li">
													<a href="<?php echo esc_url($kyma_theme_options['social_youtube_link']); ?>" target="_blank" title="YouTube"><i class="fab fa-youtube"></i></a>
												</li>
												<?php
											}
											?>
										</ul>
									<?php } ?>
								</div>
							</div>
						</div>
					</div>
					<?php endif; ?>
				</div>
                <nav id="main_nav">
                    <div id="nav_menu">
                        <?php wp_nav_menu(array(
                                'theme_location' => 'primary',
                                'menu_class' => 'clearfix horizontal_menu',
                                'menu_id' => 'navy',
                                'fallback_cb' => 'kyma_fallback_page_menu',
                                'link_before' => '<span>',
                                'link_after' => '</span>',
                                'walker' => new kyma_nav_walker(),
                            )
                        ); ?>
                        <div class="mob-menu"></div>
                    </div>
                </nav>
                <!-- End Nav -->
                <div class="clear"></div>
            </div>
        </div>
    </header>
    <!-- End Main Header -->