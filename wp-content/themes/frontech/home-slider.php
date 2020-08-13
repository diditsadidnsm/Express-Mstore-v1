<?php
/**
 * The template for displaying slider section in custom home page.
 *
 * @package Frontech
 */
$kyma_theme_options = kyma_theme_options();
if ($kyma_theme_options['home_slider_enabled'] == 1){
	if ($kyma_theme_options['slider_shortcode'] != "") {
		echo do_shortcode($kyma_theme_options['slider_shortcode']);
	}else if(isset($kyma_theme_options['home_slider_posts']) && !empty($kyma_theme_options['home_slider_posts']) ) {
	$frontech_text_length = $kyma_theme_options['slider_text_length']!="" ? $kyma_theme_options['slider_text_length'] : 8;
	$frontech_btn_txt = $kyma_theme_options['slider_btn_text'];?>
	<div id="kyma_owl_slider" class="owl-carousel">
	<?php $i = 1;
		foreach ($kyma_theme_options['home_slider_posts'] as $frontech_post_id) {
			$frontech_slider = get_post($frontech_post_id); ?>
			<div class="item">
				<?php echo get_the_post_thumbnail($frontech_slider->ID, 'kyma_slider_post', array('class' => 'img-responsive')); ?>
				<div class="owl_slider_con">
					<h3 id="slide-title-<?php echo absint($i); ?>" class="title_big" itemprop="alternativeHeadline"><span><?php echo esc_html($frontech_slider->post_title); ?></span></h3>
					
					<?php if($frontech_btn_txt!=""){ ?>
						<a id="slide-btn-<?php echo absint($i); ?>" href="<?php echo esc_url(get_post_permalink($frontech_slider->ID)); ?>" class="frontech-btn-lg btn-left frontech-btn" target="_self" itemprop="url" tabindex="0"><?php echo esc_html($frontech_btn_txt); ?></a>
					<?php } ?>
					
					<?php if($frontech_slider->post_content!=""){?>
						<h4 id="slide-subtitle-<?php echo absint($i); ?>" class="small_subtitle" itemprop="alternativeHeadline"><?php echo esc_html(wp_trim_words($frontech_slider->post_content, $frontech_text_length, '...')); ?></h4>
					<?php } ?>
				</div>
			</div>
		   <?php
			$i++;
		} ?>
	</div>	
<?php } else { ?>
<div id="kyma_owl_slider" class="owl-carousel">
	<?php for($i=1 ; $i<=3 ; $i++){ ?>
		<div class="item">
			<img src="<?php echo esc_url( get_stylesheet_directory_uri().'/images/frontech_slide'.$i.'.jpg' ); ?>" alt="Slide Title">
			<div class="owl_slider_con">
				<h3 id="slide-title-<?php echo absint( $i ); ?>" class="title_big" itemprop="alternativeHeadline"><span><?php esc_html_e('Lorem Ipsum is simply', 'frontech'); ?></span></h3>
				
				<a id="slide-btn-<?php echo absint( $i ); ?>" href="#" class="frontech-btn-lg btn-left frontech-btn" target="_self" itemprop="url" tabindex="0"><?php esc_html_e('Read More', 'frontech'); ?></a>
				
				<h4 id="slide-subtitle-<?php echo absint( $i ); ?>" class="small_subtitle" itemprop="alternativeHeadline"><?php esc_html_e('Lorem Ipsum is simply dummy text of the printing and industry...', 'frontech'); ?></h4>
			</div>
		</div>
	<?php } ?>
</div>
<?php }
} ?>