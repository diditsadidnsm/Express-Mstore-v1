<?php
$kyma_theme_options = kyma_theme_options();
if ($kyma_theme_options['home_slider_enabled'] == 1){
	if ($kyma_theme_options['slider_shortcode'] != "") {
		echo do_shortcode($kyma_theme_options['slider_shortcode']);
	}else if(isset($kyma_theme_options['home_slider_posts']) && !empty($kyma_theme_options['home_slider_posts']) ) {
	 $text_length = $kyma_theme_options['slider_text_length']!="" ? $kyma_theme_options['slider_text_length'] : 8;
	 $btn_txt = $kyma_theme_options['slider_btn_text'];?>
<div id="kyma_owl_slider" class="owl-carousel">
<?php $i = 1;
	foreach ($kyma_theme_options['home_slider_posts'] as $post_id) {
		$slider = get_post($post_id); ?>
		<div class="item">
		<?php echo get_the_post_thumbnail($slider->ID, 'kyma_slider_post', array('class' => 'img-responsive')); ?>
		<div class="owl_slider_con">
		<span class="owl_text_a">
			<span class="slider-title">
				<span id="slide-title-<?php echo $i; ?>"><?php echo esc_attr($slider->post_title); ?></span>
			</span>
		</span>
			<?php if($slider->post_content!=""){?>
			<span class="owl_text_c"><span class="slider-subtitle" id="slide-subtitle-<?php echo $i; ?>"><?php echo esc_attr(wp_trim_words($slider->post_content, $text_length, '...')); ?></span></span>
			<?php if($btn_txt!=""){?>
			<span class="owl_text_d">
				<a id="slide-description-<?php echo $i; ?>" href="<?php echo esc_url(get_post_permalink($slider->ID)); ?>" target="_self" class="btn_a btn btn-default">
			<?php echo esc_attr($btn_txt); ?>
				</a>
			</span><?php 
			}
		} ?>
		</div>
		</div><?php
		$i++;
	} ?>
</div>	
<?php } else { ?>
<div id="kyma_owl_slider" class="owl-carousel">
	<?php for($i=1 ; $i<=3 ; $i++){ ?>
		<div class="item">
			<img src="<?php echo get_template_directory_uri(); ?>/images/slide1.jpg" alt="Slide Title">
			<div class="owl_slider_con">
				<span class="owl_text_a">
					<span>
						<span><?php _e('Kyma Theme IS The Best', 'kyma'); ?></span>
					</span>
				</span>
				<span class="owl_text_c"><span><?php _e('Lorem Ipsum is simply dummy text of the printing and industry...', 'kyma'); ?></span></span>
				<span class="owl_text_d">
					<a href="#" target="_self" class="btn_a">
						<?php _e('Read More', 'kyma'); ?>
					</a>
				</span>
			</div>
		</div>
	<?php } ?>
</div>
<?php }
} ?>