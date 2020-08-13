<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Frontech
 */
get_header(); ?>
    <section class="content_section">
        <div class="container row_spacer clearfix">
            <div class="content">
                <div class="main_desc centered">
                    <p>
                        <b><?php esc_html_e('Ooopps.!', 'frontech'); ?></b><?php esc_html_e('The Page you were looking for doesnt exist', 'frontech'); ?>
                    </p>
                </div>
                <div class="my_col_third on_the_center">
                    <div class="search_block large_search">
                        <form class="widget_search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                            <input type="search" class="serch_input" name="s" id="s"
                                   placeholder="<?php echo esc_attr('Search...', 'frontech'); ?>">
                            <button type="submit" id="searchsubmit" class="search_btn">
                                <i class="fa fa-search"></i>
                            </button>
                            <div class="clear"></div>
                        </form>
                    </div>
                </div>
                <div class="page404">
                    <span><?php esc_html_e('404', 'frontech'); ?><span class="face404"></span></span>
                </div>
                <div class="centered">
					<a href="<?php echo esc_url(home_url()); ?>" class="frontech-btn-lg frontech-btn bottom_space" target="_self"><?php esc_html_e('Back To Home Page', 'frontech'); ?></span></a>
                </div>
            </div>
        </div>
    </section>
<?php get_footer(); ?>