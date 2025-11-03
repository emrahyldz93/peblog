<?php
/**
 * Latest Posts Slider Template Part
 */

// Get latest posts for slider based on customizer setting
$latest_category = get_theme_mod('peblog_latest_category', '');
$latest_args = array('numberposts' => 6);
if ($latest_category) {
    $latest_args['category_name'] = $latest_category;
}
$latest_posts = get_posts($latest_args);
?>

<!-- latest post design start -->
<section class="latest-post" id="latest-post">
    <div class="heading-container">
        <h2 class="heading"><?php echo esc_html(get_theme_mod('peblog_latest_title', 'Latest Posts')); ?></h2>
        <span class="prev-slide"></span>
        <span class="next-slide"></span>
    </div>
    <div class="swiper latest-post-slider">
        <div class="swiper-wrapper">
            <?php if (empty($latest_posts)) : ?>
                <div class="swiper-slide">
                    <div class="no-posts-message">
                        <div class="no-posts-icon">📝</div>
                        <h3>No Latest Posts Found</h3>
                        <p>There are no posts to display in the latest posts section. Please add some posts or check your category settings.</p>
                        <a href="<?php echo admin_url('post-new.php'); ?>" class="btn btn-primary">Create New Post</a>
                    </div>
                </div>
            <?php else : ?>
            <?php foreach ($latest_posts as $index => $post) : 
                $author = get_the_author_meta('display_name', $post->post_author);
                $post_date = get_the_date('d.m.Y', $post->ID);
                $categories = get_the_category($post->ID);
            ?>
                <div class="swiper-slide">
                    <article class="right-article">
                        <div class="related-post-card">
                            <div class="card-image">
                                <a href="<?php echo get_permalink($post->ID); ?>">
                                    <?php if (has_post_thumbnail($post->ID)) : ?>
                                        <?php echo get_the_post_thumbnail($post->ID, 'medium', array('class' => 'post-thumbnail')); ?>
                                    <?php else : ?>
                                        <div class="post-placeholder">
                                            <div class="placeholder-icon">📝</div>
                                        </div>
                                    <?php endif; ?>
                                </a>
                            </div>
                            <div class="card-content">
                                <ul>
                                    <li class="post-author-image">
                                        <a href="<?php echo get_author_posts_url($post->post_author); ?>">
                                            <?php echo get_avatar($post->post_author, 32); ?>
                                        </a>
                                    </li>
                                    <li class="post-author"><a href="<?php echo get_author_posts_url($post->post_author); ?>"><?php echo esc_html($author); ?></a></li>
                                    <li class="post-date"><span><?php echo $post_date; ?></span></li>
                                </ul>
                                <h3 class="entry-title"><?php echo esc_html($post->post_title); ?></h3>
                                <div class="entry-expert">
                                    <p><?php echo wp_trim_words($post->post_content, 15, '...'); ?></p>
                                </div>
                                <div class="entry-meta">
                                    <div class="entry-tag">
                                        <?php if ($categories) : ?>
                                            <?php 
                                            // Dynamic color classes
                                            $color_classes = ['tag-main', 'tag-warning', 'tag-success', 'tag-danger', 'tag-info'];
                                            ?>
                                            <?php foreach ($categories as $category) : ?>
                                                <?php 
                                                $color_index = $category->term_id % count($color_classes);
                                                $color_class = $color_classes[$color_index];
                                                ?>
                                                <a href="<?php echo get_category_link($category->term_id); ?>" class="<?php echo $color_class; ?>"><?php echo esc_html($category->name); ?></a>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="entry-rate">
                                        <a href="<?php echo get_permalink($post->ID); ?>">
                                            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/icon/eye.svg" alt="Views" />
                                            <?php echo peblog_get_post_views($post->ID); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>
<!-- latest post design end -->
