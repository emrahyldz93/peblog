<?php
/**
 * Sidebar Template
 *
 * This template displays the sidebar with widgets
 */
?>

<aside id="sidebar" class="sidebar">
    <?php if (is_active_sidebar('peblog-sidebar')) : ?>
        <?php dynamic_sidebar('peblog-sidebar'); ?>
    <?php else : ?>
        <div class="sidebar-widget">
            <h3 class="widget-title"><?php _e('Search', 'peblog'); ?></h3>
            <?php get_search_form(); ?>
        </div>
        
        <div class="sidebar-widget">
            <h3 class="widget-title"><?php _e('Recent Posts', 'peblog'); ?></h3>
            <ul>
                <?php
                $recent_posts = wp_get_recent_posts(array(
                    'numberposts' => 5,
                    'post_status' => 'publish'
                ));
                foreach ($recent_posts as $post) :
                    ?>
                    <li>
                        <a href="<?php echo get_permalink($post['ID']); ?>">
                            <?php echo esc_html($post['post_title']); ?>
                        </a>
                    </li>
                <?php endforeach; wp_reset_query(); ?>
            </ul>
        </div>
        
        <div class="sidebar-widget">
            <h3 class="widget-title"><?php _e('Categories', 'peblog'); ?></h3>
            <ul>
                <?php wp_list_categories(array('title_li' => '')); ?>
            </ul>
        </div>
    <?php endif; ?>
</aside>

