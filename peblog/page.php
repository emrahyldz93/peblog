<?php get_header(); ?>

<!-- main design start -->
<main>
    <section class="single-post-section <?php echo esc_attr(get_theme_mod('peblog_sidebar_position', 'none')); ?>">
        <?php while (have_posts()) : the_post(); ?>
        <div class="articles-container">
            <article id="page-<?php the_ID(); ?>" <?php post_class('left-article single-post-article'); ?>>
                <div class="related-post-card">
                    <!-- Featured Image -->
                    <div class="card-image">
                        <a href="<?php the_permalink(); ?>">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('large', array('class' => 'post-thumbnail wp-post-image')); ?>
                            <?php else : ?>
                                <div class="post-placeholder">
                                    <div class="placeholder-icon">📝</div>
                                </div>
                            <?php endif; ?>
                        </a>
                    </div>
                    
                    <!-- Page Content -->
                    <div class="card-content">
                        <!-- Page Title -->
                        <h3 class="entry-title"><?php the_title(); ?></h3>
                        
                        <!-- Page Content -->
                        <div class="entry-expert">
                            <?php 
                            the_content();
                            
                            // Page links for paginated content
                            wp_link_pages(array(
                                'before' => '<div class="page-links">' . esc_html__('Pages:', 'peblog'),
                                'after'  => '</div>',
                            ));
                            ?>
                        </div>
                    </div>
                </div>
            </article>
            
            <?php 
            // Sidebar inside articles-container
            if (get_theme_mod('peblog_sidebar_page', false) && get_theme_mod('peblog_sidebar_position', 'none') !== 'none') {
                get_sidebar();
            }
            ?>
        </div>
        <?php endwhile; ?>
    </section>
</main>
<!-- main design end -->

<?php get_footer(); ?>
