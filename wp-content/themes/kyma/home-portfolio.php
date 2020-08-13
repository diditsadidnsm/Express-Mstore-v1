<?php $kyma_theme_options = kyma_theme_options();?>
<section id="portfolio" class="content_section">
    <div class="row_spacer clearfix">
        <div class="content">
            <div class="main_title centered upper"><?php if ($kyma_theme_options['port_heading'] != "") { ?>
                    <h2 id="port_head"><span class="line"><i
                            class="far fa-folder-open"></i></span><?php echo esc_attr($kyma_theme_options['port_heading']); ?>
                    </h2><?php
                } ?>
            </div>
        </div>
        <!-- Filter Content -->
        <div class="hm_filter_wrapper three_blocks project_text_nav has_sapce_portos nav_with_nums upper_title upper_title <?php echo $kyma_theme_options['home_port_layout']; ?>">
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
                                    <a href="#" class="expand_img"><?php _e('View Larger', 'kyma'); ?></a>
                                    <a href="<?php echo esc_url_raw($kyma_theme_options['portfolio_link_'.$i]);?>" class="detail_link"><?php _e('More Details', 'kyma'); ?></a>
                                </div>
                            </div><?php 
                            } ?>
                            <div class="porto_desc">
                                <h6 id="portfolio-title-<?php echo $i; ?>" class="name"><?php echo esc_attr($kyma_theme_options['portfolio_title_'.$i]);?></h6>
                            </div>
                        </div>
                    </div>
            <?php }} ?>
            </div>
        </div><?php
        ?>
        <!-- End Filter Content -->
    </div>
</section>