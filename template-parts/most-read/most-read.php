<?php
/**
 * Most Read Section Template Part
 */

// Get custom selected post from Customizer, or default to most viewed post
$selected_post_id = get_theme_mod('peblog_most_read_post', '');

if (!empty($selected_post_id)) {
    // Use selected post
    $popular_posts = get_posts(array(
        'include' => array(absint($selected_post_id)),
        'numberposts' => 1,
        'post_status' => 'publish',
    ));
} else {
    // Default: Get most viewed post
    $popular_posts = get_posts(array(
        'numberposts' => 1,
        'post_status' => 'publish',
        'meta_key' => 'peblog_post_views',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
    ));
    
    // If no posts with views, fallback to most commented
    if (empty($popular_posts)) {
        $popular_posts = get_posts(array(
            'numberposts' => 1,
            'post_status' => 'publish',
            'orderby' => 'comment_count',
            'order' => 'DESC',
        ));
    }
}
?>

<!-- Most Read design start -->
<section id="related-articles" class="related-articles">
    <h2 class="heading">Most Read</h2>
    <div class="articles-container">
        <article class="left-article">
            <?php if (!empty($popular_posts)) : 
                $post = $popular_posts[0];
                $author = get_the_author_meta('display_name', $post->post_author);
                $post_date = get_the_date('d.m.Y', $post->ID);
                $categories = get_the_category($post->ID);
            ?>
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
                            <p><?php echo wp_trim_words($post->post_content, 20, '...'); ?></p>
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
            <?php else : ?>
                <div class="no-posts-message">
                    <div class="no-posts-icon">📝</div>
                    <h3>No Posts Found</h3>
                    <p>There are no posts to display in the most read section. Please add some posts to see them here.</p>
                    <a href="<?php echo admin_url('post-new.php'); ?>" class="btn btn-primary">Create New Post</a>
                </div>
            <?php endif; ?>
        </article>
        
        <div class="right-author" id="author">
            <div class="author-card">
                <div class="author-image">
                    <a href="<?php echo get_author_posts_url(1); ?>">
                        <?php 
                        $author_image = get_theme_mod('peblog_author_image', '');
                        if ($author_image) : ?>
                            <img src="<?php echo esc_url($author_image); ?>" alt="Author" />
                        <?php else : ?>
                            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/big-author.png" alt="Author" />
                        <?php endif; ?>
                    </a>
                </div>
                <h3 class="author-full-name"><?php echo esc_html(get_theme_mod('peblog_author_name', 'John Doe')); ?></h3>
                <span class="author-title"><?php echo esc_html(get_theme_mod('peblog_author_title', 'Blog Author')); ?></span>
                <div class="author-content">
                    <p><?php echo esc_html(get_theme_mod('peblog_author_description', 'Passionate about technology, design, and innovation. Sharing insights and experiences through this blog.')); ?></p>
                </div>
                <ul class="author-social-links">
                    <?php
                    $social_links = array(
                        'instagram' => get_theme_mod('peblog_instagram_url', ''),
                        'twitter' => get_theme_mod('peblog_twitter_url', ''),
                        'linkedin' => get_theme_mod('peblog_linkedin_url', ''),
                        'github' => get_theme_mod('peblog_github_url', ''),
                        'pinterest' => get_theme_mod('peblog_pinterest_url', ''),
                        'telegram' => get_theme_mod('peblog_telegram_url', ''),
                        'youtube' => get_theme_mod('peblog_youtube_url', ''),
                        'facebook' => get_theme_mod('peblog_facebook_url', ''),
                        'dribbble' => get_theme_mod('peblog_dribbble_url', ''),
                        'behance' => get_theme_mod('peblog_behance_url', ''),
                    );
                    
                    foreach ($social_links as $platform => $url) {
                        if (!empty($url)) {
                            $icon_path = esc_url(get_template_directory_uri() . '/assets/images/icon/' . $platform . '.svg');
                            $icon_exists = file_exists(get_template_directory() . '/assets/images/icon/' . $platform . '.svg');
                            if ($icon_exists) {
                                echo '<li class="' . esc_attr($platform) . '">';
                                echo '<a href="' . esc_url($url) . '" target="_blank" rel="noopener">';
                                echo '<img src="' . esc_url($icon_path) . '" alt="' . esc_attr(ucfirst($platform)) . '" />';
                                echo '</a>';
                                echo '</li>';
                            }
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
        
        <?php if (get_theme_mod('peblog_most_read_show_tags', true)) : ?>
        <div class="related-tags">
            <h3>Popular Tags</h3>
            <div class="tags">
                <?php
                $tags = get_tags(array('orderby' => 'count', 'order' => 'DESC', 'number' => 7));
                if ($tags) {
                    foreach ($tags as $tag) {
                        echo '<a href="' . get_tag_link($tag->term_id) . '" class="tag-light">' . esc_html($tag->name) . '</a>';
                    }
                }
                ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
<!-- Most read design end -->
