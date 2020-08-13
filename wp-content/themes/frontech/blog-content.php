<?php
/**
 * The template used for displaying page/post content in blog.php, page.php, index.php, archive.php etc.
 *
 * @package Frontech
 */
?>

<div <?php post_class('blog_grid_block clearfix'); ?>>
    <div class="feature_inner">
        <div class="feature_inner_corners">
            <?php
            $frontech_thumb = 0;
			$frontech_img_class = array('class' => 'img-responsive');
            global $frontech_imageSize;
            if (get_post_gallery()) {
                $frontech_gallery = get_post_gallery(get_the_ID(), false);?>
                <div class="feature_inner_btns">
                    <a href="#" class="expand_image btn frontech-btn"><i class="fa fa-expand"></i></a>
                    <a href="<?php echo esc_url(the_permalink()); ?>" class="icon_link btn frontech-btn"><i
                            class="fa fa-link"></i></a>
                </div>
                <div class="porto_galla"><?php
                foreach ($frontech_gallery['src'] as $frontech_src) {
                    ?>
                <a title="<?php the_title_attribute(); ?>" href="<?php echo esc_url($frontech_src); ?>"
                   class="feature_inner_ling">
                    <img class="img-responsive" src="<?php echo esc_url($frontech_src); ?>" alt="<?php the_title_attribute(); ?>">
                    </a><?php
                }
                if (has_post_thumbnail()) {
                    $frontech_thumb = 1;
                    $frontech_url = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())) ?>
                <a href="<?php echo esc_url($frontech_url); ?>" title="<?php the_title_attribute(); ?>"
                   class="feature_inner_ling">
                    <?php the_post_thumbnail($frontech_imageSize, $frontech_img_class); ?>
                    </a><?php
                } ?>
                </div><?php
            } elseif (has_post_thumbnail() && $frontech_thumb != 1) {
                $frontech_url = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())); ?>
                <div class="feature_inner_btns">
                    <a href="#" class="expand_image btn frontech-btn"><i class="fa fa-expand"></i></a>
                    <a href="<?php echo esc_url(the_permalink()); ?>" class="icon_link btn frontech-btn"><i
                            class="fa fa-link"></i></a>
                </div>
            <a href="<?php echo esc_url($frontech_url); ?>" title="<?php the_title_attribute(); ?>" class="feature_inner_ling"
               data-rel="magnific-popup">
                <?php the_post_thumbnail($frontech_imageSize, $frontech_img_class); ?>
                </a><?php
            }
            ?>
        </div>
    </div>
    <div class="blog_grid_con <?php if(has_post_thumbnail()) { echo 'width-60'; }?>">
        <h6 class="title"><a href="<?php the_permalink(); ?>" title="<?php esc_attr(the_title_attribute()); ?>"><?php the_title(); ?></a></h6>
		<span class="meta">
            <span class="meta_part">
				<a href="#">
                    <i class="far fa-clock"></i>
                    <span><?php echo esc_attr(get_the_date(get_option('date_format'), get_the_ID())); ?></span>
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
                        <span><?php esc_attr(the_author()); ?></span>
                    </a>
			</span>
		</span>
        <?php 
            the_excerpt();?>
    </div>
</div>