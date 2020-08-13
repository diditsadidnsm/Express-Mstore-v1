<?php
$kyma_theme_options = kyma_theme_options();
$kyma_blog_post_count   = absint( $kyma_theme_options['home_load_post_num'] );
$kyma_load_post_button = intval( $kyma_theme_options['show_load_more_btn'] );
$kyma_blog_load_more_text = $kyma_theme_options['blog_load_more_text'];
$kyma_blog_more_loading_text = $kyma_theme_options['blog_more_loading']; ?>
<section class="content_section bg_gray">
    <div class="<?php echo $kyma_theme_options['home_blog_layout']; ?> row_spacer no_padding">
        <div class="main_title centered upper"><?php if ($kyma_theme_options['home_blog_title'] != "") { ?>
                <h2 id='blog-heading'><span class="line"><i
                        class="fa fa-edit"></i></span><?php echo esc_attr($kyma_theme_options['home_blog_title']); ?>
                </h2><?php
            }?>
        </div>
        <div class="rows_container clearfix">
            <div class="hm_blog_grid">
                <!-- Filter Content -->
                <div class="hm_filter_wrapper masonry_grid_posts three_blocks">
                    <ul class="hm_filter_wrapper_con masonry ajax_posts"><?php
                        if(isset($kyma_theme_options['home_post_cat'])){
						$cat = $kyma_theme_options['home_post_cat'];
						}
                        $args = array('post_type' => 'post','post_status' => 'publish', 'posts_per_page' => $kyma_blog_post_count,'post__not_in' => get_option( 'sticky_posts' ), 'category__in'=>$cat);
                        query_posts($args);
                        if (query_posts($args)) {
                            $i = 1;
                            $j = 1;
                            while (have_posts()):the_post();
								$icon = '';
                                ?>
                            <li class="filter_item_block animated grid-item" data-animation-delay="<?php echo 300 * $i; ?>" data-animation="rotateInUpLeft">
                                <div class="blog_grid_block">
								<?php if (has_post_thumbnail()) {
									$icon = 'far fa-image';	?>
                                    <div class="feature_inner">
                                        <div class="feature_inner_corners">
                                            <?php
											
                                                $url = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
                                                ?>
                                                <div class="feature_inner_btns">
                                                    <a href="<?php echo esc_url($url); ?>" class="expand_image"><i
                                                            class="fa fa-expand"></i></a>
                                                    <a href="<?php echo esc_url(get_the_permalink()); ?>"
                                                       class="icon_link"><i class="fa fa-link"></i></a>
                                                </div>
												<div class="porto_galla">
													<a href="<?php echo esc_url($url); ?>" class="feature_inner_ling"
												   data-rel="magnific-popup">
												   <?php if($kyma_theme_options['home_blog_layout'] == 'content'){
													   the_post_thumbnail('kyma_home_post_image');
												   }else{
													   the_post_thumbnail('kyma_home_post_image_fluid');
												   }
												   ?>
													</a>
												</div>	
                                        </div>
                                    </div>
								<?php } ?>
                                    <div class="blog_grid_con">
										<?php if( isset($icon) && $icon!='' ) { ?>
                                        <a href="" class="blog_grid_format"><i class="<?php echo esc_attr($icon); ?>"></i></a>
										<?php } ?>
                                        <h6 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
									<span class="meta">
										<span
                                            class="meta_part"><?php echo esc_attr(get_the_date(get_option('date_format'), get_the_ID())); ?></span>
										<span class="meta_slash">/</span>
										<span
                                            class="meta_part"><?php esc_url(comments_popup_link(__('No Comments', 'kyma'), __('1 Comment', 'kyma'), __('% Comments', 'kyma'))); ?> <?php esc_url(edit_post_link(__('Edit', 'kyma'), ' &#124; ', '')); ?></span>
										<span class="meta_slash">/</span>
										<span class="meta_part"><?php echo get_the_category_list(','); ?></span>
									</span>
                                        <?php 
										if( ! empty( $post->post_excerpt ) ) {
											echo the_excerpt(); 
										} else {
											echo kyma_content();
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