<?php 
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Frontech
 */
get_header(); ?>
    <!-- Page Title -->
<?php get_template_part('breadcrumbs'); ?>
    <!-- End Page Title -->
    <!-- Our Blog Grids -->
    <section class="content_section">
    <div class="content">
    <div class="internal_post_con clearfix"><?php
    $kyma_theme_options = kyma_theme_options();
	$frontech_img_class = array('class' => 'img-responsive');
    $frontech_post_layout = $kyma_theme_options['post_layout'];
    $frontech_imageSize = $frontech_post_layout == "postfull" ? 'kyma_single_post_full' : 'kyma_single_post_image';
    if ($frontech_post_layout == "postleft") {
        get_sidebar();
        $frontech_float = "f_right";
    } elseif ($frontech_post_layout == "postfull") {
        $frontech_float = "";
    } elseif ($frontech_post_layout == "postright") {
        $frontech_float = "f_left";
        $frontech_imageSize = 'kyma_single_post_image';
    } else {
        $frontech_float = "f_left";
    }
    ?>
    <!-- All Content --><?php
    if ($frontech_post_layout == "postleft" || $frontech_post_layout == "postright"){
    ?>
    <div class="content_block col-md-9 <?php echo esc_attr($frontech_float); ?> "><?php
    } ?>
    <div class="hm_blog_full_list hm_blog_list clearfix">
    <!-- Post Container --><?php
    if (have_posts()):
        while (have_posts()): the_post(); ?>
        <div id="<?php echo absint( get_the_id() ); ?>" <?php post_class('clearfix'); ?> >
            <div class="feature_inner">
                <div class="feature_inner_corners">
                    <?php $frontech_thumb = 0;
                    $frontech_url = '';
                    global $frontech_imageSize;
                    if (get_post_gallery()) {
                        $frontech_gallery = get_post_gallery(get_the_ID(), false);?>
                        <div class="porto_galla">
                            <?php foreach ($frontech_gallery['src'] as $frontech_src) { ?>
                                <a title="<?php the_title_attribute(); ?>" href="<?php echo esc_url($frontech_src); ?>"
                                   class="feature_inner_ling">
                                    <img class="img-responsive" src="<?php echo esc_url($frontech_src); ?>" alt="<?php esc_attr(the_title_attribute()); ?>">
                                </a>
                            <?php
                            }
                            if (has_post_thumbnail()) {
                                $frontech_thumb = 1;
                                $frontech_url = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())); ?>
                                <a href="<?php echo esc_url($frontech_url); ?>" class="feature_inner_ling">
                                    <?php the_post_thumbnail($frontech_imageSize, $frontech_img_class); ?>
                                </a>
                            <?php } ?>
                        </div>
                    <?php
                    } elseif (has_post_thumbnail() && $frontech_thumb != 1) {
                        $frontech_url = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())); ?>
                        <a href="<?php echo esc_url($frontech_url); ?>" title="<?php esc_attr(the_title_attribute()); ?>"
                           class="feature_inner_ling" data-rel="magnific-popup">
                            <?php the_post_thumbnail($frontech_imageSize, $frontech_img_class); ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
			<div class="post_title_con">
                <h6 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
				<span class="meta">
					<span class="meta_part">
						<a href="#">
                            <i class="far fa-clock"></i>
                            <span><?php echo esc_html(get_the_date(get_option('date_format'), get_the_ID())); ?></span>
                        </a>
					</span>
					<span class="meta_part">
						<a href="#">
                            <i class="far fa-comment"></i>
                            <?php esc_url(comments_popup_link(esc_html__('No Comments', 'frontech'), esc_html__('1 Comment', 'frontech'), esc_html__('% Comments', 'frontech'))); ?> <?php esc_url(edit_post_link(esc_html__('Edit', 'frontech'), ' &#124; ', '')); ?>
                        </a>
					</span>

					<?php if (get_the_category_list() != '') { ?>
						<span class="meta_part">
							<i class="far fa-folder-open"></i>
							<span><?php echo get_the_category_list( esc_html__(', ', 'frontech') ); ?></span>
						</span>
					<?php } ?>
                    <span class="meta_part">
						<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                            <i class="far fa-user"></i>
                            <span><?php esc_html(the_author()); ?></span>
                        </a>
					</span>
				</span>
            </div>
            <div class="blog_grid_con">
                <?php the_content(); ?>
            </div>

            <!-- Next / Prev and Social Share-->
            <div class="post_next_prev_con clearfix">
                <!-- Next and Prev Post-->
                <div class="post_next_prev clearfix">
                    <?php next_post_link('%link', '<span class="t">' . esc_html__('Previous Post', 'frontech') . '</span>'); ?>
                    <?php previous_post_link('%link', '<span class="t">' . esc_html__('Next', 'frontech') . '</span>'); ?>
                </div>
                <!-- End Next and Prev Post-->

                <!-- Social Share-->
                <div class="single_pro_row">
                    <div id="share_on_socials">
                        <!-- <h6>Share:</h6> -->
                        <a class="facebook"
                           href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url(the_permalink()); ?>"
                           target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a class="twitter"
                           href="http://twitter.com/home?status=<?php echo esc_html(get_the_title()); ?>+<?php echo esc_url(the_permalink()); ?>"
                           target="_blank"><i class="fab fa-twitter"></i></a>
                        <a class="pinterest"
                           href="https://pinterest.com/pin/create/button/?url=<?php echo esc_url(the_permalink()); ?>&media=<?php echo esc_url($frontech_url); ?>&description=<?php echo esc_html(get_the_title()); ?>"
                           target="_blank"><i class="fab fa-pinterest"></i></a>
                        <a class="linkedin"
                           href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?PHP echo esc_url($frontech_url); ?>&amp;title=<?php echo esc_html(get_the_title()); ?>&amp;source=<?php echo esc_url(the_permalink()); ?>"
                           target="_blank"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <!-- End Social Share-->
            </div>
            <!-- End Next / Prev and Social Share-->

            <!-- Tags -->
            <?php if (get_the_tag_list() != '') { ?>
                <div class="small_title">
							<span class="small_title_con">
								<span class="s_icon"><i class="fa fa-tags"></i></span>
								<span class="s_text"><?php esc_html_e('Tags', 'frontech'); ?></span>
							</span>
                </div>
                <div class="tags_con">
                    <?php esc_attr(the_tags('', '', '')); ?>
                </div>
            <?php } ?>
            <!-- End Tags -->

            <!-- About the author -->
            <div class="about_auther">
                <div class="small_title">
								<span class="small_title_con">
									<span class="s_icon"><i class="fa fa-user"></i></span>
									<span class="s_text"><?php echo esc_html($kyma_theme_options['about_author_text']); ?></span>
								</span>
                </div>

                <div class="about_auther_con clearfix">
								<span class="avatar_img">
									<?php echo get_avatar(get_the_author_meta('ID'), 126); ?>
								</span>

                    <div class="about_auther_details">
                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"
                           class="auther_link"><?php esc_html(the_author()); ?></a>
									<span class="desc"><?php esc_html(the_author_meta('description')); ?>
									</span>

                        <div class="social_media clearfix"><?php 
						if(get_the_author_meta('facebook_profile')){?>
                            <a href="<?php esc_url(the_author_meta('facebook_profile')); ?>" target="_blank"
                               class="facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <?php 
						} 	if(get_the_author_meta('twitter_profile')!=""){?>
                            <a href="<?php esc_url(the_author_meta('twitter_profile')); ?>" target="_blank"
                               class="twitter">
                                <i class="fab fa-twitter"></i>
                            </a><?php 
                        }  if(get_the_author_meta('google_profile')){?>
                            <a href="<?php esc_url(the_author_meta('google_profile')); ?>" target="_blank"
                               class="googleplus">
                                <i class="fab fa-google-plus-g"></i>
                            </a>
                            <?php 
                        } if(get_the_author_meta('linkedin_profile')){?>
                            <a href="<?php esc_url(the_author_meta('linkedin_profile')); ?>" target="_blank"
                               class="linkedin">
                                <i class="fab fa-linkedin"></i>
                            </a><?php 
                        } ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End About the author -->
            </div><?php
        endwhile;
    endif;?>
    <!-- End Post Container -->
    <!-- Related Posts --><?php
    $frontech_tags = wp_get_post_tags(get_the_ID());
    $frontech_num = sizeOf($frontech_tags);
    $frontech_tagarr = array();
    for ($i = 0; $i < $frontech_num; $i++) {
        $frontech_tagarr[$i] = $frontech_tags[$i]->term_id;
    }
    if ($frontech_tags) {
        $frontech_args = array(
            'tag__in' => $frontech_tagarr,
            'post__not_in' => array(get_the_ID()),
            'ignore_sticky_posts' => 1
        );
        $frontech_query = new WP_Query($frontech_args);
        if ($frontech_query->have_posts()) {
            ?>
            <div class="related_posts">
            <div class="small_title">
							<span class="small_title_con">
								<span class="s_icon"><i class="fa fa-laptop"></i></span>
								<span
                                    class="s_text"><?php echo esc_html($kyma_theme_options['related_post_text']); ?></span>
							</span>
            </div>

            <div class="related_posts_con"><?php
                while ($frontech_query->have_posts()) {
                    $frontech_query->the_post();
                    if (get_post_gallery() || has_post_thumbnail()) {
                        $frontech_icon = "fa fa-image";
                    }?>
                    <div class="related_posts_slide">
                    <div class="related_img_con">
                        <a href="<?php the_permalink(); ?>" class="related_img">
                            <?php the_post_thumbnail('kyma_related_post_thumb', $frontech_img_class); ?>
                            <?php if (isset($frontech_icon)) { ?><span><i class="<?php echo esc_attr($frontech_icon); ?>"></i></span><?php } ?>
                        </a>
                    </div>
                    <a class="related_title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        <span
                            class="post_date"><?php echo esc_html(get_the_date(get_option('date_format'), get_the_ID())); ?></span>
                    </div><?php
                } ?>
            </div>
            </div><?php
        }
		wp_reset_query();
    } ?>
    <!-- End Related Posts -->
    <!-- Comments Container -->
    <?php comments_template('', true); ?>
    <!-- End Comments Container -->
    </div>
    <?php if ($frontech_post_layout == "postleft" || $frontech_post_layout == "postright"){ ?>
    </div>
    <?php } ?>
    <!-- End blog List -->
    <?php if ($frontech_post_layout == "postright") {
        get_sidebar();
    } ?>
    </div>
    </div>
    </section>
    <!-- End All Content -->
<?php get_footer(); ?>