<aside id="sidebar" class="col-md-3 right_sidebar">
    <?php if (is_active_sidebar('sidebar-widget')) {
        dynamic_sidebar('sidebar-widget');
    } else {
        $args = array(
            'before_widget' => '<div class="widget_block">',
            'after_widget' => '</div>',
            'before_title' => '<h6 class="widget_title">',
            'after_title' => '</h6>'
        );
        the_widget('WP_Widget_Search', null, $args);
        the_widget('WP_Widget_Archives', null, $args);
        the_widget('WP_Widget_Tag_Cloud', null, $args);
    } ?>
</aside>