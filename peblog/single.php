<?php get_header(); ?>

<!-- main design start -->
<main>
    <section class="single-post-section <?php echo esc_attr(get_theme_mod('peblog_sidebar_position', 'none')); ?>">
        <?php while (have_posts()) : the_post(); ?>
        <div class="articles-container">
            <article id="post-<?php the_ID(); ?>" <?php post_class('left-article single-post-article'); ?>>
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
                    
                    <!-- Post Meta -->
                    <div class="card-content">
                        <ul>
                            <li class="post-author-image">
                                <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>">
                                    <?php echo get_avatar(get_the_author_meta('ID'), 32); ?>
                                </a>
                            </li>
                            <li class="post-author">
                                <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>">
                                    <?php the_author(); ?>
                                </a>
                            </li>
                            <li class="post-date">
                                <span><?php echo get_the_date('d.m.Y'); ?></span>
                            </li>
                        </ul>
                        
                        <!-- Post Title -->
                        <h3 class="entry-title"><?php the_title(); ?></h3>
                        
                        <!-- Post Content (entry-expert style) -->
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
                        
                        <!-- Post Meta (Categories) -->
                        <div class="entry-meta">
                            <div class="entry-tag">
                                <?php
                                $categories = get_the_category();
                                if ($categories) {
                                    // Dynamic color classes
                                    $color_classes = ['tag-main', 'tag-warning', 'tag-success', 'tag-danger', 'tag-info'];
                                    foreach ($categories as $category) {
                                        $color_index = $category->term_id % count($color_classes);
                                        $color_class = $color_classes[$color_index];
                                        ?>
                                        <a href="<?php echo get_category_link($category->term_id); ?>" class="<?php echo $color_class; ?>">
                                            <?php echo esc_html($category->name); ?>
                                        </a>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        
                        <!-- Social Share Buttons -->
                        <?php
                        $post_url = urlencode(get_permalink());
                        $post_title = urlencode(get_the_title());
                        $post_excerpt = urlencode(get_the_excerpt());
                        $share_title = get_theme_mod('peblog_social_share_title', __('Share this post', 'peblog'));
                        $share_buttons = array();
                        
                        if (get_theme_mod('peblog_share_facebook', true)) {
                            $share_buttons['facebook'] = array(
                                'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . $post_url,
                                'icon' => get_template_directory_uri() . '/assets/images/icon/facebook.svg',
                                'label' => __('Share on Facebook', 'peblog')
                            );
                        }
                        
                        if (get_theme_mod('peblog_share_twitter', true)) {
                            $share_buttons['twitter'] = array(
                                'url' => 'https://twitter.com/intent/tweet?url=' . $post_url . '&text=' . $post_title,
                                'icon' => get_template_directory_uri() . '/assets/images/icon/twitter.svg',
                                'label' => __('Share on Twitter', 'peblog')
                            );
                        }
                        
                        if (get_theme_mod('peblog_share_linkedin', true)) {
                            $share_buttons['linkedin'] = array(
                                'url' => 'https://www.linkedin.com/shareArticle?mini=true&url=' . $post_url . '&title=' . $post_title . '&summary=' . $post_excerpt,
                                'icon' => get_template_directory_uri() . '/assets/images/icon/linkedin.svg',
                                'label' => __('Share on LinkedIn', 'peblog')
                            );
                        }
                        
                        if (get_theme_mod('peblog_share_whatsapp', true)) {
                            $share_buttons['whatsapp'] = array(
                                'url' => 'https://wa.me/?text=' . $post_title . ' ' . $post_url,
                                'icon' => get_template_directory_uri() . '/assets/images/icon/whatsapp.svg',
                                'label' => __('Share on WhatsApp', 'peblog')
                            );
                        }
                        
                        if (get_theme_mod('peblog_share_telegram', false)) {
                            $share_buttons['telegram'] = array(
                                'url' => 'https://t.me/share/url?url=' . $post_url . '&text=' . $post_title,
                                'icon' => get_template_directory_uri() . '/assets/images/icon/telegram.svg',
                                'label' => __('Share on Telegram', 'peblog')
                            );
                        }
                        
                        if (get_theme_mod('peblog_share_pinterest', false)) {
                            $share_image = has_post_thumbnail() ? wp_get_attachment_image_src(get_post_thumbnail_id(), 'large')[0] : '';
                            $share_buttons['pinterest'] = array(
                                'url' => 'https://pinterest.com/pin/create/button/?url=' . $post_url . '&description=' . $post_title . ($share_image ? '&media=' . urlencode($share_image) : ''),
                                'icon' => get_template_directory_uri() . '/assets/images/icon/pinterest.svg',
                                'label' => __('Share on Pinterest', 'peblog')
                            );
                        }
                        
                        if (!empty($share_buttons)) :
                        ?>
                        <div class="social-share-buttons">
                            <?php if ($share_title) : ?>
                                <h4 class="share-title"><?php echo esc_html($share_title); ?></h4>
                            <?php endif; ?>
                            <ul class="share-buttons-list">
                                <?php foreach ($share_buttons as $platform => $button) : ?>
                                    <li class="share-button share-<?php echo esc_attr($platform); ?>">
                                        <a href="<?php echo esc_url($button['url']); ?>" 
                                           target="_blank" 
                                           rel="noopener noreferrer" 
                                           aria-label="<?php echo esc_attr($button['label']); ?>"
                                           class="share-link">
                                            <?php if (file_exists(get_template_directory() . '/assets/images/icon/' . $platform . '.svg')) : ?>
                                                <img src="<?php echo esc_url($button['icon']); ?>" alt="<?php echo esc_attr($button['label']); ?>" />
                                            <?php else : ?>
                                                <span class="share-icon"><?php echo esc_html(ucfirst($platform)); ?></span>
                                            <?php endif; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Post Tags -->
                        <?php if (get_theme_mod('peblog_show_post_tags', true)) : ?>
                            <?php
                            $tags = get_the_tags();
                            if ($tags) : ?>
                                <div class="post-tags">
                                    <h4 class="tags-title"><?php _e('Tags:', 'peblog'); ?></h4>
                                    <div class="tags-list">
                                        <?php foreach ($tags as $tag) : 
                                            // Dynamic color classes (same as categories)
                                            $color_classes = ['tag-main', 'tag-warning', 'tag-success', 'tag-danger', 'tag-info'];
                                            $color_index = $tag->term_id % count($color_classes);
                                            $color_class = $color_classes[$color_index];
                                        ?>
                                            <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="tag-link <?php echo esc_attr($color_class); ?>">
                                                <?php echo esc_html($tag->name); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <!-- Post Navigation -->
                        <nav class="post-navigation">
                            <div class="nav-previous">
                                <?php 
                                $prev_post = get_previous_post();
                                if ($prev_post) {
                                    echo '<a href="' . get_permalink($prev_post->ID) . '" class="nav-link nav-prev">';
                                    echo '<span class="nav-label">← Previous Post</span>';
                                    echo '<span class="nav-title">' . get_the_title($prev_post->ID) . '</span>';
                                    echo '</a>';
                                }
                                ?>
                            </div>
                            <div class="nav-next">
                                <?php 
                                $next_post = get_next_post();
                                if ($next_post) {
                                    echo '<a href="' . get_permalink($next_post->ID) . '" class="nav-link nav-next">';
                                    echo '<span class="nav-label">Next Post →</span>';
                                    echo '<span class="nav-title">' . get_the_title($next_post->ID) . '</span>';
                                    echo '</a>';
                                }
                                ?>
                            </div>
                        </nav>
                    </div>
                </div>
            </article>
            
            <!-- Author Card -->
            <?php if (get_theme_mod('peblog_show_author_card', true)) : ?>
            <div class="right-author single-author-card" id="author">
                <div class="author-card">
                    <div class="author-image">
                        <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>">
                            <?php 
                            $author_image = get_theme_mod('peblog_author_image', '');
                            if ($author_image) : ?>
                                <img src="<?php echo esc_url($author_image); ?>" alt="<?php the_author(); ?>" />
                            <?php else : ?>
                                <?php echo get_avatar(get_the_author_meta('ID'), 100); ?>
                            <?php endif; ?>
                        </a>
                    </div>
                    <div class="author-content-wrapper">
                        <h3 class="author-full-name"><?php echo esc_html(get_theme_mod('peblog_author_name', get_the_author())); ?></h3>
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
                                    $icon_path = get_template_directory_uri() . '/assets/images/icon/' . $platform . '.svg';
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
            </div>
            <?php endif; ?>
            
            <?php 
            // Sidebar inside articles-container
            if (get_theme_mod('peblog_sidebar_single', false) && get_theme_mod('peblog_sidebar_position', 'none') !== 'none') {
                get_sidebar();
            }
            ?>
        </div>
            <?php endwhile; ?>
            
        <!-- Comments Section -->
        <?php if (comments_open() || get_comments_number()) : ?>
            <div class="comments-section">
                <?php comments_template(); ?>
            </div>
        <?php endif; ?>
    </section>
</main>
<!-- main design end -->

<?php get_footer(); ?>

