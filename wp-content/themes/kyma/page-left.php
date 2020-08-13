<?php //Template Name: Page With Left Sidebar
get_header(); ?>
<!-- Page Title -->
<?php get_template_part('breadcrumbs'); ?>
    <!-- End Page Title -->
    <!-- Our Blog Grids -->
    <section class="content_section">
        <div class="content">
            <div class="internal_post_con clearfix">
                <?php
                $imageSize = 'kyma_single_post_image';
                if (get_post_gallery()):
					$icon = 'fa fa-photo';
				elseif (has_post_thumbnail()):
					$icon = 'fa fa-image';
				endif;
				get_sidebar(); ?>
                <!-- All Content -->
                <div class="content_block col-md-9">
                    <div class="hm_blog_full_list hm_blog_list clearfix">
                        <!-- Post Container -->
                        <?php
                        if (have_posts()):
                        while (have_posts()):
                        the_post(); ?>
                        <div id="<?php get_the_id(); ?>" <?php post_class('clearfix'); ?> >
                            <div class="post_title_con">
                                <h6 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
                            </div>
                            <?php if (isset($icon)) { ?>
							<div class="post_format_con">
								<span>
									<a href="#">
										<i class="<?php echo esc_attr($icon); ?>"></i>
									</a>
								</span>
							</div>
                            <?php } ?>
                            <div class="feature_inner">
                                <div class="feature_inner_corners">
                                    <?php $thumb = 0;
                                    $url = '';
                                    if (get_post_gallery()) {
                                        $gallery = get_post_gallery(get_the_ID(), false);?>
                                        <div class="porto_galla">
                                            <?php foreach ($gallery['src'] as $src) { ?>
                                                <a title="<?php the_title_attribute(); ?>"
                                                   href="<?php echo esc_url($src); ?>" class="feature_inner_ling">
                                                    <img src="<?php echo esc_url($src); ?>"
                                                         alt="<?php the_title_attribute(); ?>">
                                                </a>
                                            <?php
                                            }
                                            if (has_post_thumbnail()) {
                                                $thumb = 1;
                                                $url = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())); ?>
                                                <a href="<?php echo esc_url($url); ?>" class="feature_inner_ling">
                                                    <?php the_post_thumbnail($imageSize); ?>
                                                </a>
                                            <?php } ?>
                                        </div>
                                    <?php
                                    } elseif (has_post_thumbnail() && $thumb != 1) {
                                        $url = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())); ?>
                                        <a href="<?php echo esc_url($url); ?>" title="<?php the_title_attribute(); ?>"
                                           class="feature_inner_ling">
                                            <?php the_post_thumbnail($imageSize); ?>
                                        </a>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="blog_grid_con">
                                <?php the_content(); ?>
                            </div>
                        </div>
                        <!-- End Next / Prev and Social Share-->
                        <!-- End About the author -->
                    </div><?php
                    endwhile;
                    endif;
                    ?>
                    <!-- End Post Container -->
                    <!-- Comments Container -->
                    <?php comments_template('', true); ?>
                    <!-- End Comments Container -->
                </div>
                <!-- End blog List -->
            </div>
    </section>
    <!-- End All Content -->
<?php get_footer(); ?>