<?php get_header(); ?>
    <section class="content_section">
        <div class="container row_spacer clearfix">
            <div class="content">
                <div class="main_desc centered">
                    <p>
                        <b><?php _e('Ooopps.!', 'kyma'); ?></b><?php _e('The Page you were looking for doesnt exist', 'kyma'); ?>
                    </p>
                </div>
                <div class="my_col_third on_the_center">
                    <div class="search_block large_search">
                        <form class="widget_search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                            <input type="search" class="serch_input" name="s" id="s"
                                   placeholder="<?php _e('Search...', 'kyma'); ?>">
                            <button type="submit" id="searchsubmit" class="search_btn">
                                <i class="fa fa-search"></i>
                            </button>
                            <div class="clear"></div>
                        </form>
                    </div>
                </div>
                <div class="page404">
                    <span><?php _e('404', 'kyma'); ?><span class="face404"></span></span>
                </div>
                <div class="centered">
                    <a href="<?php echo esc_url(home_url()); ?>" target="_self" class="btn_a medium_btn bottom_space">
                        <span><i class="in_left fa fa-home"></i><span><?php _e('Back To Home Page', 'kyma'); ?></span><i
                                class="in_right fa fa-home"></i></span>
                    </a>
                </div>
            </div>
        </div>
    </section>
<?php get_footer(); ?>