<?php 
/**
 * The template for displaying call to action section in custom home page.
 *
 * @package Frontech
 */
$kyma_theme_options = kyma_theme_options(); 
$callout_layout = 'box_sec';
if(isset($kyma_theme_options['callout_layout'])){
	$callout_layout = $kyma_theme_options['callout_layout'];
}?>
<section id='callout' class="content_section white_section bg_color3">
    <div class="welcome_banner full_colored">
        <div class="content <?php echo $callout_layout; ?> clearfix">
            <?php if ($kyma_theme_options['callout_title'] != "") { ?>
                <h3 id='callout-title'><?php echo esc_html($kyma_theme_options['callout_title']); ?></h3>
            <?php } ?>
			<a href="<?php echo esc_url($kyma_theme_options['callout_btn_link']); ?>" class="frontech-btn-black-lg btn-right frontech-btn"><?php echo esc_html($kyma_theme_options['callout_btn_text']); ?></span></a>
			<?php if ($kyma_theme_options['callout_description'] != "") { ?>
                <span
                    class="intro_text"><?php echo esc_html($kyma_theme_options['callout_description']); ?>
				</span>
			<?php } ?>
        </div>
    </div>
</section>