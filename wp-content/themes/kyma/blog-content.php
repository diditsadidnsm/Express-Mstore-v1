<div <?php post_class('blog_grid_block clearfix'); ?>>
    <div class="feature_inner">
        <div class="feature_inner_corners">
            <?php
            $thumb = 0;
			$img_class = array('class' => 'img-responsive');
            global $imageSize;
            if (get_post_gallery()) {
                $icon = "far fa-images";
                $gallery = get_post_gallery(get_the_ID(), false);?>
                <div class="feature_inner_btns">
                    <a href="#" class="expand_image"><i class="fa fa-expand"></i></a>
                    <a href="<?php echo esc_url(get_the_permalink()); ?>" class="icon_link"><i
                            class="fa fa-link"></i></a>
                </div>
                <div class="porto_galla"><?php
                foreach ($gallery['src'] as $src) {
                    ?>
                <a title="<?php the_title_attribute(); ?>" href="<?php echo esc_url($src); ?>"
                   class="feature_inner_ling">
                    <img class="img-responsive" src="<?php echo esc_url($src); ?>" alt="<?php the_title_attribute(); ?>">
                    </a><?php
                }
                if (has_post_thumbnail()) {
                    $thumb = 1;
                    $url = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())) ?>
                <a href="<?php echo esc_url($url); ?>" title="<?php the_title_attribute(); ?>"
                   class="feature_inner_ling">
                    <?php the_post_thumbnail($imageSize, $img_class); ?>
                    </a><?php
                } ?>
                </div><?php
            } elseif (has_post_thumbnail() && $thumb != 1) {
                $icon = "far fa-image";
                $url = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())); ?>
                <div class="feature_inner_btns">
                    <a href="#" class="expand_image"><i class="fa fa-expand"></i></a>
                    <a href="<?php echo esc_url(get_the_permalink()); ?>" class="icon_link"><i
                            class="fa fa-link"></i></a>
                </div>
            <a href="<?php echo esc_url($url); ?>" title="<?php the_title_attribute(); ?>" class="feature_inner_ling"
               data-rel="magnific-popup">
                <?php the_post_thumbnail($imageSize, $img_class); ?>
                </a><?php
            }
            ?>
        </div>
    </div>
    <div class="blog_grid_con <?php if(has_post_thumbnail()) { echo 'width-60'; }?>">
        <h6 class="title"><a href="<?php the_permalink(); ?>" title="<?php esc_attr(the_title_attribute()); ?>"><?php the_title(); ?></a></h6>
		<span class="meta"><?php
            if (isset($icon)) {
                ?>
                <span class="meta_part">
                <a href="#">
                    <i class="<?php echo esc_attr($icon); ?>"></i>
                    <span><?php _e('Image','kyma'); ?></span>
                </a>
                </span><?php
            } ?>
            <span class="meta_part">
				<a href="#">
                    <i class="far fa-clock"></i>
                    <span><?php echo esc_attr(get_the_date(get_option('date_format'), get_the_ID())); ?></span>
                </a>
			</span><?php
            if (get_the_category_list() != '') {
                ?>
                <span class="meta_part">
                <i class="far fa-folder-open"></i>
					<span><?php echo get_the_category_list(','); ?></span>
                </span><?php
            } ?>
            <span class="meta_part">
					<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                        <i class="far fa-user"></i>
                        <span><?php esc_attr(the_author()); ?></span>
                    </a>
			</span>
		</span>
        <?php 
            the_excerpt();?>
    </div>
</div>