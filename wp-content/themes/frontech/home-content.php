<?php $kyma_theme_options = kyma_theme_options(); ?>
<section class="content_section extra_section">
	<div class="content row_spacer no_padding">
	<?php if ($kyma_theme_options['home_extra_title'] != "") { ?>
	<div class="main_title centered upper">
		<?php if ($kyma_theme_options['home_extra_title'] != "") { ?>
            <h2 id='extra-heading'><?php echo esc_html($kyma_theme_options['home_extra_title']); ?></h2>
			<div class="section-line">
				<span class="section-line-right"></span>
			</div><?php
        }?>
	</div>
	<?php } ?>
    <div class="container">
		<?php echo apply_filters('the_content', $kyma_theme_options['extra_content_home']); ?>
    </div>
	</div>
</section>