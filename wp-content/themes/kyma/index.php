<?php $kyma_theme_options = kyma_theme_options();
get_header();
get_template_part('breadcrumbs'); ?>
<!-- Our Blog Grids -->
<?php $kyma_theme_options = kyma_theme_options(); ?>
<section class="content_section">
	<div class="content row_spacer">
		<div class="main_title centered upper">
			<h2><span class="line"><i class="fa fa-pencil-alt"></i></span><?php echo esc_attr($kyma_theme_options['home_blog_title']); ?>
			</h2>
		</div><?php
		$blog_layout = $kyma_theme_options['blog_layout'];
		global $imageSize;
		$imageSize = $blog_layout == "blogfull" ? 'kyma_home_post_full_thumb' : 'kyma_home_post_thumb';
		if ($blog_layout == "blogleft") {
			get_sidebar();
			$float = "f_right";
		} elseif ($blog_layout == "blogfull") {
			$float = "";
		} elseif ($blog_layout == "blogright") {
			$float = "f_left";
		}?>
		<!-- All Content -->
		<div class="content_spacer clearfix">
			<?php if ($blog_layout == "blogleft" || $blog_layout == "blogright"){ ?>
			<div class="content_block col-md-9 <?php echo esc_attr($float); ?> ">
				<?php } ?>
				<div class="hm_blog_list clearfix"><?php
					while ($wp_query->have_posts()):
						$wp_query->the_post();
						get_template_part('blog', 'content');
					endwhile;
					wp_link_pages(); ?>
					<!-- End blog List -->
				</div>
				<!-- Pagination -->
				<div id="pagination" class="pagination">
					<?php kyma_pagination(); ?>
				</div>
				<!-- End Pagination -->
				<!-- End Content Block -->
				<?php if ($blog_layout == "blogleft" || $blog_layout == "blogright"){ ?>
			</div>
			<?php }
			if ($blog_layout == "blogright") {
				get_sidebar();
			} ?>
			<!-- All Content -->
		</div>
	</div>
</section>
<!-- End Our Blog Grids -->
<?php get_footer(); ?>