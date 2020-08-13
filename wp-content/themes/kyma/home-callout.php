<?php $kyma_theme_options = kyma_theme_options(); ?>
<section id='callout' class="content_section white_section bg_color3">
    <div class="welcome_banner full_colored">
        <div class="content <?php echo $kyma_theme_options['callout_layout']; ?> clearfix">
            <?php if ($kyma_theme_options['callout_title'] != "") { ?>
                <h3 id='callout-title'><?php echo esc_attr($kyma_theme_options['callout_title']); ?></h3>
            <?php } ?>
            <a id="call_out_link" href="<?php echo esc_url($kyma_theme_options['callout_btn_link']); ?>" target="_self"
               class="btn_a f_right">
                <span><i class="in_left <?php echo esc_attr($kyma_theme_options['callout_btn_icon']); ?>"></i><span
                        id='callout-btn-text'><?php echo esc_attr($kyma_theme_options['callout_btn_text']); ?></span><i
                        class="in_right <?php echo esc_attr($kyma_theme_options['callout_btn_icon']); ?>"></i></span>
            </a><?php if ($kyma_theme_options['callout_description'] != "") { ?>
                <span
                    class="intro_text"><?php echo esc_attr($kyma_theme_options['callout_description']); ?></span><?php } ?>
        </div>
    </div>
</section>