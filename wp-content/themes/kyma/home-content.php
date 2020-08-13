<?php $kyma_theme_options = kyma_theme_options(); ?>
<section class="content_section extra_section">
	<div class="content row_spacer no_padding">
	<?php if ($kyma_theme_options['home_extra_title'] != "") { ?>
	<div class="main_title centered upper">
		<h2 id='extra-heading'><span class="line"><i class="fa fa-file-alt"></i></span><?php echo esc_attr($kyma_theme_options['home_extra_title']); ?>
		</h2>
	</div>
	<?php } ?>
    <div class="container">
		<?php echo apply_filters('the_content', $kyma_theme_options['extra_content_home']); ?>
    </div>
	</div>
</section>