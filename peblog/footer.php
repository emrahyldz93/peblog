<!-- footer start -->
<footer id="footer">
    <div class="footer-container">
        <div class="footer-box">
            <div class="footer-logo">
                <?php
                $custom_logo_white = get_theme_mod('peblog_logo_white', '');
                if ($custom_logo_white) {
                    echo '<img src="' . esc_url($custom_logo_white) . '" alt="' . get_bloginfo('name') . '" />';
                } else {
                    echo '<span class="site-logo-text">Peblog</span>';
                }
                ?>
            </div>
            <div class="footer-text">
                <p><?php echo get_bloginfo('description') ?: 'PeBlog Theme'; ?></p>
            </div>
        </div>
        
        <div class="footer-box">
            <div class="footer-title">
                <h3>Follow My Socials</h3>
            </div>
            <div class="social-links">
                <ul class="footer-social-links">
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
        
        <div class="footer-box">
            <div class="footer-title">
                <h3><?php echo esc_html(get_theme_mod('peblog_newsletter_title', 'Join Our Newsletter')); ?></h3>
            </div>
            <div class="newsletter-form">
                <?php
                $newsletter_shortcode = get_theme_mod('peblog_newsletter_shortcode', '');
                if (!empty($newsletter_shortcode)) {
                    // Show shortcode if provided
                    echo do_shortcode($newsletter_shortcode);
                } else {
                    // Demo form for visual preview
                    ?>
                    <form>
                        <input type="email" placeholder="Enter your email" />
                        <input type="submit" value="Subscribe" />
                    </form>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
    
    <div class="copyright">
        <span>Copyright © <?php echo date('Y'); ?> <?php echo get_bloginfo('name'); ?>. All Rights Reserved.</span>
    </div>
</footer>
<!-- footer end -->

<?php wp_footer(); ?>
</body>
</html>
