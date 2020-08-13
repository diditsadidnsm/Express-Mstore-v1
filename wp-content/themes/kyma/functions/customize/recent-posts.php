<?php
add_action('widgets_init', 'kyma_recent_posts_widgets');
function kyma_recent_posts_widgets()
{
    return register_widget('kyma_footer_recent_posts');
}

/**
 * Adds widget for recent Post in footer.
 */
class kyma_footer_recent_posts extends WP_Widget
{
    function __construct()
    {
        parent::__construct(
            'kyma_footer_recent_posts', //ID
            __('Kyma Recent Posts', 'kyma'), // Name
            array('description' => __('Display Recent posts on your sites', 'kyma'),) // Args
        );
    }

    public function widget($args, $instance)
    {
        $title = !empty($instance['title']) ? apply_filters('widget_title', $instance['title']) : 'Receent Posts';
        $number_of_posts = !empty($instance['number_of_posts']) ? apply_filters('widget_title', $instance['number_of_posts']) : 5;

        echo $args['before_widget'];
        if (!empty($title))
            echo $args['before_title'] . $title . $args['after_title']; ?>
        <?php $loop = new WP_Query(array('post_type' => 'post', 'showposts' => $number_of_posts));
        if ($loop->have_posts()) : ?>
            <ul class="recent_posts_list">
            <?php while ($loop->have_posts()) : $loop->the_post(); ?>
                <li class="clearfix">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                        <span class="recent_posts_img"><?php the_post_thumbnail('kyma_recent_widget_thumb'); ?></span>
                        <span><?php the_title(); ?></span>
                    </a>
                    <span
                        class="recent_post_detail"><?php echo esc_attr(get_the_date(get_option('date_format'), get_the_ID())); ?></span>
                    <span class="recent_post_detail"><?php esc_attr(the_tags('')); ?></span>
                </li>
            <?php endwhile; ?>
        <?php endif; ?>
        <?php
        echo $args['after_widget'];
    }

    public function form($instance)
    {
        if (isset($instance['title']) && isset($instance['number_of_posts'])) {
            $title = $instance['title'];
            $number_of_posts = $instance['number_of_posts'];
        } else {
            $title = __('Recent Post', 'kyma');
            $number_of_posts = 4;
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'kyma'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>"/>
        </p>
        <p>
            <label
                for="<?php echo esc_attr($this->get_field_id('number_of_posts')); ?>"><?php _e('Number of pages to show:', 'kyma'); ?></label>
            <input size="3" maxlength="2" id="<?php echo esc_attr($this->get_field_id('number_of_posts')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('number_of_posts')); ?>" type="text"
                   value="<?php echo esc_attr($number_of_posts); ?>"/>
        </p>
    <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['number_of_posts'] = (!empty($new_instance['number_of_posts'])) ? strip_tags($new_instance['number_of_posts']) : '';
        return $instance;
    }

}

?>