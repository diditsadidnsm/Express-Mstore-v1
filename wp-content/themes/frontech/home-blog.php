<?php 
/**
 * The template for displaying blog posts section in custom home page.
 *
 * @package Frontech
 */
$kyma_theme_options 		= kyma_theme_options();
$kyma_blog_post_count   	= absint( $kyma_theme_options['home_load_post_num'] );
$kyma_load_post_button 		= intval( $kyma_theme_options['show_load_more_btn'] );
$kyma_blog_load_more_text 	= $kyma_theme_options['blog_load_more_text'];
$kyma_blog_more_loading_text = $kyma_theme_options['blog_more_loading']; 
$custom_excerpt_length 		= get_theme_mod('custom_excerpt_length', 55);

$home_blog_layout = 'content';
if(isset($kyma_theme_options['home_blog_layout'])){
	$home_blog_layout = $kyma_theme_options['home_blog_layout'];
}
 ?>
<section id="ft_hm_blog" class="content_section bg_gray">
    <div class="<?php echo $home_blog_layout; ?> row_spacer no_padding">
        <div class="main_title centered upper">
			<?php if ($kyma_theme_options['home_blog_title'] != "") { ?>
                <h2 id='blog-heading'><?php echo esc_html($kyma_theme_options['home_blog_title']); ?></h2>
				<div class="section-line">
					<span class="section-line-right"></span>
				</div><?php
            }?>
        </div>
        <div class="rows_container clearfix">
            <div class="hm_blog_grid">
                <!-- Filter Content -->
                <div class="hm_filter_wrapper masonry_grid_posts three_blocks">
                    <ul class="hm_filter_wrapper_con masonry ajax_posts">
					<?php
                        if(isset($kyma_theme_options['home_post_cat'])){
							$frontech_cat = $kyma_theme_options['home_post_cat'];
						}
                        $frontech_args = array('post_type' => 'post','post_status' => 'publish', 'posts_per_page' => $kyma_blog_post_count,'post__not_in' => get_option( 'sticky_posts' ), 'category__in'=>$frontech_cat);
                        query_posts($frontech_args);
                        if (query_posts($frontech_args)) {
                            $i = 1;
                            $j = 1;
                            while (have_posts()):the_post();
                            ?>
                            <li class="filter_item_block animated grid-item" data-animation-delay="<?php echo 300 * absint($i); ?>"
                                id="row-<?php echo absint($j); ?>" data-animation="rotateInUpLeft">
                                <div class="blog_grid_block">
                                    <div class="feature_inner">
                                        <div class="feature_inner_corners">
                                            <?php
                                            if (get_post_gallery()) {
                                                $frontech_gallery = get_post_gallery(get_the_ID(), false);?>
                                                <div class="feature_inner_btns">
                                                    <a href="#" class="expand_image btn frontech-btn"><i class="fa fa-expand"></i></a>
                                                    <a href="<?php echo esc_url(the_permalink()); ?>"
                                                       class="icon_link btn frontech-btn"><i class="fa fa-link"></i></a>
                                                </div>
                                                <div class="porto_galla"><?php
                                                foreach ($frontech_gallery['src'] as $frontech_src) {
                                                    ?>
                                                <a title="<?php the_title_attribute(); ?>"
                                                   href="<?php echo esc_url($frontech_src); ?>" class="feature_inner_ling">
                                                    <img src="<?php echo esc_url($frontech_src); ?>"
                                                         alt="<?php the_title_attribute(); ?>">
                                                    </a><?php
                                                }
                                                if (has_post_thumbnail()) {
                                                    $frontech_url = wp_get_attachment_url(get_post_thumbnail_id($post->ID)) ?>
                                                <a href="<?php echo esc_url($frontech_url); ?>" class="feature_inner_ling"
                                                   data-rel="magnific-popup">
                                                    <?php if( $home_blog_layout == 'content'){
												   the_post_thumbnail('kyma_home_post_image');
											   }else{
												   the_post_thumbnail('kyma_home_post_image_fluid');
											   }
                                               ?>
                                                    </a><?php } ?>
                                                </div><?php
                                            } elseif (has_post_thumbnail()) {
                                                $frontech_url = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
                                                ?>
                                                <div class="feature_inner_btns">
                                                    <a href="<?php echo esc_url($frontech_url); ?>" class="expand_image btn frontech-btn"><i
                                                            class="fa fa-expand"></i></a>
                                                    <a href="<?php echo esc_url(the_permalink()); ?>"
                                                       class="icon_link btn frontech-btn"><i class="fa fa-link"></i></a>
                                                </div>
                                            <div class="porto_galla">
												<a href="<?php echo esc_url($frontech_url); ?>" class="feature_inner_ling"
												   data-rel="magnific-popup">
												<?php 
												if( $home_blog_layout == 'content'){
												   the_post_thumbnail('kyma_home_post_image');
												}else{
												   the_post_thumbnail('kyma_home_post_image_fluid');
												}
												?>
												</a>
											</div><?php
                                            } ?>
                                        </div>
                                    </div>
                                    <div class="blog_grid_con">
                                        <h6 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
									<span class="meta">
										<span class="meta_part">
											<a href="<?php esc_url(the_permalink()); ?>">
												<i class="far fa-clock"></i>
												<span><?php echo esc_html(get_the_date(get_option('date_format'), get_the_ID())); ?></span>
											</a>
										</span>
										<span class="meta_part">
											<i class="far fa-comment"></i>
											<?php esc_url(comments_popup_link(esc_html__('No Comments', 'frontech'), esc_html__('1 Comment', 'frontech'), esc_html__('% Comments', 'frontech'))); ?> <?php esc_url(edit_post_link(esc_html__('Edit', 'frontech'), ' &#124; ', '')); ?>
										</span>
									</span>
                                        <?php
										if( ! empty( $post->post_excerpt ) ) {
											the_excerpt(); 
										} else {
											echo frontech_content($custom_excerpt_length);
										} ?>
                                    </div>
                                </div>
                                </li><?php $i != 3 ? $i++ : $i = 1;
                                if ($j % 3 == 0) {
                                    echo "<div class='clearfix'></div>";
                                }
                                $j++;
                            endwhile;
                        } ?>
                    </ul>
                </div>
				<!-- End Filter Content -->
				<?php if ( $kyma_load_post_button ) { ?>
                <div class="centered post-btn1 load-button" id="load-button">
                    <a class="btn_c append-button" data-loading-text="<i class='fas fa-spinner fa-spin'></i> <?php echo wp_kses_post( $kyma_blog_more_loading_text ); ?>">
                        <span class="btn_c_ic_a"><i class="fa fa-sync-alt"></i></span>
                        <span class="btn_c_t"><?php echo wp_kses_post( $kyma_blog_load_more_text ); ?></span>
                        <span class="btn_c_ic_b"><i class="fa fa-sync-alt"></i></span>
                    </a>
                </div>
				<?php } ?>
            </div>
            <!-- End blog grid -->
        </div>
    </div>
</section>