<?php
/**
 * Template Name: Blog
 *
 * @package Frontech
 */
get_header();
get_template_part('breadcrumbs'); ?>
    <!-- Our Blog Grids -->
<?php $kyma_theme_options = kyma_theme_options(); ?>
    <section class="content_section">
        <div class="content row_spacer">
            <div class="page-sec-title main_title centered upper">
				<h2 id='blog-heading'><?php echo esc_html($kyma_theme_options['home_blog_title']); ?>
                </h2>
				<div class="section-line">
					<span class="section-line-right"></span>
				</div>
            </div><?php
            $frontech_blog_layout = $kyma_theme_options['blog_layout'];
            $frontech_imageSize = $frontech_blog_layout == "blogfull" ? 'kyma_home_post_full_thumb' : 'home_post_thumb';
            if ($frontech_blog_layout == "blogleft") {
                get_sidebar();
                $frontech_float = "f_right";
            } elseif ($frontech_blog_layout == "blogfull") {
                $frontech_float = "";
            } elseif ($frontech_blog_layout == "blogright") {
                $frontech_float = "f_left";
            }?>
            <!-- All Content -->
            <div class="content_spacer clearfix">
                <?php if ($frontech_blog_layout == "blogleft" || $frontech_blog_layout == "blogright"){ ?>
                <div class="content_block col-md-9 <?php echo esc_attr($frontech_float); ?> ">
                    <?php } ?>
                    <div class="hm_blog_list clearfix"><?php
                        $frontech_paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                        $frontech_args = array('post_type' => 'post', 'paged' => $frontech_paged);
                        $frontech_query = new WP_Query($frontech_args);
                        while ($frontech_query->have_posts()):
                            $frontech_query->the_post();
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
                    <?php if ($frontech_blog_layout == "blogleft" || $frontech_blog_layout == "blogright"){ ?>
                </div>
            <?php
            }
            if ($frontech_blog_layout == "blogright") {
                get_sidebar();
            } ?>
                <!-- All Content -->
            </div>
        </div>
    </section>
    <!-- End Our Blog Grids -->
<?php get_footer(); ?>