<?php
/**
 * Featured Posts Template Part
 */

// Get featured posts based on customizer setting
$featured_category = get_theme_mod('peblog_featured_category', '');
$featured_args = array('numberposts' => 3);
if ($featured_category) {
    $featured_args['category_name'] = $featured_category;
}
$featured_posts = get_posts($featured_args);
?>

<!-- related articles design start -->
<section id="related-articles" class="related-articles">
    <h2 class="heading"><?php echo esc_html(get_theme_mod('peblog_featured_title', 'Featured Posts')); ?></h2>
    <div class="articles-container">
        <?php if (empty($featured_posts)) : ?>
            <div class="no-posts-message">
                <div class="no-posts-icon">📝</div>
                <h3>No Featured Posts Found</h3>
                <p>There are no posts to display in the featured section. Please add some posts or check your category settings.</p>
                <a href="<?php echo admin_url('post-new.php'); ?>" class="btn btn-primary">Create New Post</a>
            </div>
        <?php else : ?>
        <?php foreach ($featured_posts as $index => $post) : 
            $author = get_the_author_meta('display_name', $post->post_author);
            $post_date = get_the_date('d.m.Y', $post->ID);
            $categories = get_the_category($post->ID);
        ?>
            <article class="<?php echo $index === 0 ? 'left-article' : 'right-article'; ?>">
                <div class="related-post-card">
                    <div class="card-image">
                        <a href="<?php echo get_permalink($post->ID); ?>">
                            <?php if (has_post_thumbnail($post->ID)) : ?>
                                <?php echo get_the_post_thumbnail($post->ID, 'large', array('class' => 'post-thumbnail')); ?>
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
                        <?php if ($index === 0) : // Only show excerpt for left-article ?>
                        <div class="entry-expert">
                            <p><?php echo wp_trim_words($post->post_content, 15, '...'); ?></p>
                        </div>
                        <?php endif; ?>
                        <div class="entry-meta">
                            <div class="entry-tag">
                                <?php if ($categories) : ?>
                                    <?php 
                                    // Show only first category for right-article, all categories for left-article
                                    $categories_to_show = ($index === 0) ? $categories : array_slice($categories, 0, 1);
                                    
                                    // Dynamic color classes
                                    $color_classes = ['tag-main', 'tag-warning', 'tag-success', 'tag-danger', 'tag-info'];
                                    ?>
                                    <?php foreach ($categories_to_show as $category) : ?>
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
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
<!-- related articles design end -->

<style>
.post-thumbnail {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 8px;
}

.post-placeholder {
    width: 100%;
    height: 200px;
    background: #f8f9fa;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.placeholder-icon {
    font-size: 3rem;
    opacity: 0.5;
}
</style>
