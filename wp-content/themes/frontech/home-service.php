<?php 
/**
 * The template for displaying services section in custom home page.
 *
 * @package Frontech
 */
$kyma_theme_options = kyma_theme_options();
$home_service_layout = 'container';
if(isset($kyma_theme_options['home_service_layout'])){
	$home_service_layout = $kyma_theme_options['home_service_layout'];
}
?>
<section id="home-service" class="content_section bg_gray">
    <div class="container icons_spacer">
        <div class="main_title centered upper"><?php if ($kyma_theme_options['home_service_heading'] != ""){ ?>
            <h2 id="service_heading" class="section-title"><?php echo esc_html($kyma_theme_options['home_service_heading']);
                } ?></h2>
			<div class="section-line">
				<span class="section-line-right"></span>
			</div>	
        </div>
        <div class="icon_boxes_con style1 clearfix"><?php
            $frontech_col = 12 / (int)$kyma_theme_options['home_service_column'];
            $frontech_color = array('', 'color1', 'color2', 'color3');
            for ($i = 1; $i <= 4; $i++) {
                if ($kyma_theme_options['service_icon_' . $i] != "" || $kyma_theme_options['service_title_' . $i] != "" || $kyma_theme_options['service_text_' . $i] != "" ) { ?>
            <div class="service col-md-<?php echo esc_attr($frontech_col); ?>">
				<div class="service_box">
					
						<?php if ($kyma_theme_options['service_icon_' . $i] != "") { ?>
						<span class="icon"><i id="service-icon-<?php echo absint($i); ?>" class="<?php echo esc_attr($kyma_theme_options['service_icon_' . $i] . ' ' . $frontech_color[$i - 1]); ?> fa-4x"></i></span>
						<?php } ?>
                        <?php if ($kyma_theme_options['service_title_' . $i] != "") { ?>
                            <h3 id="service-title-<?php echo absint($i); ?>"><?php echo esc_html($kyma_theme_options['service_title_' . $i]); ?></h3><?php
                        }
                        if ($kyma_theme_options['service_text_' . $i] != "") {
                            ?>
                            <span id="service-btn-<?php echo absint($i); ?>"
                                  class="desc"><?php echo wp_kses_post($kyma_theme_options['service_text_' . $i]); ?></span><?php
                        }
                        if ($kyma_theme_options['service_link_' . $i] != "") {
                            ?>
						<div class="text-center">
							<a id="service-link-<?php echo absint($i); ?>"
							   href="<?php echo esc_url($kyma_theme_options['service_link_' . $i]); ?>"
							   class="btn frontech-btn"><span></span><?php esc_html_e('Read More', 'frontech'); ?></a><?php
							} ?>
						</div>
						
                </div>
                </div><?php }
            }
            ?>
        </div>
    </div>
</section>