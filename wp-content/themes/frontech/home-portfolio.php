<?php 
/**
 * The template for displaying portfolio section in custom home page.
 *
 * @package Frontech
 */
$kyma_theme_options = kyma_theme_options();
$home_port_layout = 'boxed_portos';
if(isset($kyma_theme_options['home_port_layout'])){
	$home_port_layout = $kyma_theme_options['home_port_layout'];
}
?>
<section id="portfolio" class="content_section">
    <div class="row_spacer clearfix">
        <div class="content">
            <div class="main_title centered upper"><?php if ($kyma_theme_options['port_heading'] != "") { ?>
                <h2 id="service_heading" class="section-title"><?php echo esc_html($kyma_theme_options['port_heading']);
                ?></h2>
				<div class="section-line">
					<span class="section-line-right"></span>
				</div>
					
			<?php
                } ?>
            </div>
        </div>
        <!-- Filter Content -->
        <div class="hm_filter_wrapper three_blocks project_text_nav has_sapce_portos nav_with_nums upper_title upper_title <?php echo $home_port_layout; ?>">
            <div class="hm_filter_wrapper_con"><?php
               if ($kyma_theme_options['portfolio_shortcode'] != "") {
                    echo do_shortcode($kyma_theme_options['portfolio_shortcode']);
                }else{
                    for ($i = 1; $i <= 3; $i++) {?>
                    <div class="filter_item_block">
                        <div class="porto_block">
                            <?php if($kyma_theme_options['portfolio_image_'.$i]!=""){?>
                            <div class="porto_type">
                                <a data-rel="magnific-popup" href="<?php echo esc_url_raw($kyma_theme_options['portfolio_image_'.$i]); ?>">
                                    <img src="<?php echo esc_url_raw($kyma_theme_options['portfolio_image_'.$i]); ?>"  alt="<?php echo esc_attr($kyma_theme_options['portfolio_title_'.$i]);?>">
                                </a>
                                <div class="porto_nav">
                                    <a href="#" class="expand_img btn frontech-btn"><?php esc_html_e('View Larger', 'frontech'); ?></a>
                                    <a href="<?php echo esc_url_raw($kyma_theme_options['portfolio_link_'.$i]);?>" class="detail_link btn frontech-btn"><?php esc_html_e('More Details', 'frontech'); ?></a>
                                </div>
                            </div><?php 
                            } ?>
                            <div class="porto_desc">
                                <h6 id="portfolio-title-<?php echo absint( $i ); ?>" class="name"> <a href="<?php echo esc_url_raw($kyma_theme_options['portfolio_link_'.$i]);?>"> <?php echo esc_html($kyma_theme_options['portfolio_title_'.$i]);?> </a> </h6>
                            </div>
                        </div>
                    </div>
            <?php }} ?>
            </div>
        </div>
        <!-- End Filter Content -->
    </div>
</section>