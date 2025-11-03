<?php get_header(); ?>

<!-- main design start -->
<main>
    <?php
    /**
     * Hook: peblog_hero_slider
     * 
     * Premium/Child themes can remove default action and add their own
     * @hooked peblog_render_hero_slider - 10
     */
    do_action('peblog_hero_slider');
    ?>
    
    <?php
    /**
     * Hook: peblog_featured_posts
     * 
     * Premium/Child themes can remove default action and add their own
     * @hooked peblog_render_featured_posts - 10
     */
    do_action('peblog_featured_posts');
    ?>
    
    <?php
    /**
     * Hook: peblog_latest_posts
     * 
     * Premium/Child themes can remove default action and add their own
     * @hooked peblog_render_latest_posts - 10
     */
    do_action('peblog_latest_posts');
    ?>
    
    <?php
    /**
     * Hook: peblog_most_read
     * 
     * Premium/Child themes can remove default action and add their own
     * @hooked peblog_render_most_read - 10
     */
    do_action('peblog_most_read');
    ?>
    
    <?php
    /**
     * Hook: peblog_category_filter
     * 
     * Premium/Child themes can remove default action and add their own
     * @hooked peblog_render_category_filter - 10
     */
    do_action('peblog_category_filter');
    ?>
</main>
<!-- main design end -->

<?php get_footer(); ?>