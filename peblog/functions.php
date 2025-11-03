<?php
/**
 * Peblog Theme Functions
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Theme setup
function peblog_setup() {
    // Add theme support for various features
    add_theme_support('automatic-feed-links');
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ));
    add_theme_support('custom-logo');
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('responsive-embeds');
    
    // Add support for editor styles
    add_theme_support('editor-styles');
    add_editor_style('assets/css/style.css');
    
    // Add support for wide and full alignment
    add_theme_support('align-wide');
    
    // Add support for custom color palette
    add_theme_support('editor-color-palette', array(
        array(
            'name' => __('Primary', 'peblog'),
            'slug' => 'primary',
            'color' => '#6366f1',
        ),
        array(
            'name' => __('Secondary', 'peblog'),
            'slug' => 'secondary',
            'color' => '#8b5cf6',
        ),
        array(
            'name' => __('Success', 'peblog'),
            'slug' => 'success',
            'color' => '#10b981',
        ),
        array(
            'name' => __('Warning', 'peblog'),
            'slug' => 'warning',
            'color' => '#f59e0b',
        ),
        array(
            'name' => __('Danger', 'peblog'),
            'slug' => 'danger',
            'color' => '#ef4444',
        ),
    ));
}
add_action('after_setup_theme', 'peblog_setup');

// Add default avatar
add_filter('avatar_defaults', function($avatars) {
    $avatar_url = get_template_directory_uri() . '/assets/images/big-author.png';
    $avatars[$avatar_url] = __('Peblog Author', 'peblog');
    return $avatars;
});

// Force custom avatar for users without avatar
add_filter('get_avatar', function($avatar, $id_or_email, $size, $default, $alt) {
    $avatar_url = get_template_directory_uri() . '/assets/images/big-author.png';
    
    // Get user ID
    $user_id = 0;
    if (is_numeric($id_or_email)) {
        $user_id = (int) $id_or_email;
    } elseif (is_object($id_or_email)) {
        $user_id = isset($id_or_email->user_id) ? (int) $id_or_email->user_id : 0;
    } else {
        $user = get_user_by('email', $id_or_email);
        if ($user) {
            $user_id = $user->ID;
        }
    }
    
    // Check if avatar is WordPress default placeholder
    if (strpos($avatar, 'mystery') !== false || strpos($avatar, 'gravatar.com/avatar') !== false) {
        $avatar = sprintf(
            '<img alt="%s" src="%s" class="avatar avatar-%d photo" height="%d" width="%d" />',
            esc_attr($alt),
            esc_url($avatar_url),
            esc_attr($size),
            esc_attr($size),
            esc_attr($size)
        );
    }
    
    return $avatar;
}, 999, 5);

// Add favicon and touch icons
function peblog_add_favicon() {
    $favicon = get_theme_mod('peblog_favicon', '');
    $apple_touch_icon = get_theme_mod('peblog_apple_touch_icon', '');
    
    if ($favicon) {
        echo '<link rel="icon" type="image/x-icon" href="' . esc_url($favicon) . '">' . "\n";
        echo '<link rel="shortcut icon" type="image/x-icon" href="' . esc_url($favicon) . '">' . "\n";
    }
    
    if ($apple_touch_icon) {
        echo '<link rel="apple-touch-icon" href="' . esc_url($apple_touch_icon) . '">' . "\n";
    }
}
add_action('wp_head', 'peblog_add_favicon');

// Post View Counter
function peblog_get_post_views($post_id) {
    $count = get_post_meta($post_id, 'peblog_post_views', true);
    return $count ? (int) $count : 0;
}

function peblog_set_post_views($post_id) {
    $count = peblog_get_post_views($post_id);
    $count++;
    update_post_meta($post_id, 'peblog_post_views', $count);
    return $count;
}

// Track post views
function peblog_track_post_views() {
    if (is_single() && !is_admin()) {
        global $post;
        if ($post) {
            $post_id = $post->ID;
            // Check if already counted in this session
            if (!isset($_SESSION['peblog_viewed_posts'])) {
                $_SESSION['peblog_viewed_posts'] = array();
            }
            
            // Only count once per session
            if (!in_array($post_id, $_SESSION['peblog_viewed_posts'])) {
                peblog_set_post_views($post_id);
                $_SESSION['peblog_viewed_posts'][] = $post_id;
            }
        }
    }
}
add_action('wp', 'peblog_track_post_views');

// Initialize session for view tracking
function peblog_init_session() {
    if (!session_id()) {
        session_start();
    }
}
add_action('init', 'peblog_init_session');


// Delete demo content when theme is deactivated
function peblog_delete_demo_content() {
    // Delete demo posts
    $demo_posts = get_posts(array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'peblog_demo_post',
                'value' => '1',
                'compare' => '='
            )
        )
    ));
    
    foreach ($demo_posts as $post) {
        wp_delete_post($post->ID, true);
    }
    
    // Delete demo categories (only if they have no other posts)
    $demo_categories = array('design', 'blockchain', 'software', 'machine-learning', 'game');
    foreach ($demo_categories as $cat_slug) {
        $term = get_term_by('slug', $cat_slug, 'category');
        if ($term) {
            $post_count = $term->count;
            // Only delete if created by demo and no posts
            if ($post_count == 0) {
                wp_delete_term($term->term_id, 'category');
            }
        }
    }
}
add_action('switch_theme', 'peblog_delete_demo_content');

// Demo Content Creator
function peblog_create_demo_content() {
    // Delete old demo content first
    peblog_delete_demo_content();
    
    // Reset the flag to allow recreation
    delete_option('peblog_demo_content_created');
    
    // Create categories
    $categories = array(
        'design' => 'Design',
        'blockchain' => 'Blockchain', 
        'software' => 'Software',
        'machine-learning' => 'Machine Learning',
        'game' => 'Game'
    );
    
    $category_ids = array();
    foreach ($categories as $slug => $name) {
        $term = wp_insert_term($name, 'category', array('slug' => $slug));
        if (!is_wp_error($term)) {
            $category_ids[$slug] = $term['term_id'];
        }
    }
    
    // Demo posts data with Lorem Picsum images (no copyright issues)
    $demo_posts = array(
        array(
            'title' => 'Want fluffy Japanese pancakes but can\'t fly to Tokyo?',
            'content' => 'Lorem ipsum dolor sit amet consectetur. Laoreet adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
            'excerpt' => 'Lorem ipsum dolor sit amet consectetur. Laoreet adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            'category' => 'design',
            'image_url' => 'https://picsum.photos/id/1015/1200/600',
            'featured' => true
        ),
        array(
            'title' => 'The Future of Web Design: Trends to Watch',
            'content' => 'Web design is constantly evolving, and staying ahead of the curve is crucial for any designer. In this comprehensive guide, we explore the latest trends that are shaping the future of web design. From dark mode implementations to micro-interactions, we cover everything you need to know to create modern, engaging websites.',
            'excerpt' => 'Explore the latest trends shaping the future of web design and learn how to implement them in your projects.',
            'category' => 'design',
            'image_url' => 'https://picsum.photos/id/1016/1200/600',
            'featured' => false
        ),
        array(
            'title' => 'Understanding Blockchain Technology',
            'content' => 'Blockchain technology has revolutionized the way we think about digital transactions and data security. This comprehensive guide covers the fundamentals of blockchain, its applications, and how it\'s changing various industries. Learn about smart contracts, decentralized applications, and the future of digital currencies.',
            'excerpt' => 'A comprehensive guide to understanding blockchain technology and its impact on modern digital systems.',
            'category' => 'blockchain',
            'image_url' => 'https://picsum.photos/id/1018/1200/600',
            'featured' => false
        ),
        array(
            'title' => 'Building Scalable Software Architecture',
            'content' => 'Creating software that can scale with your business is one of the biggest challenges developers face. This article explores proven strategies for building scalable software architecture, including microservices, containerization, and cloud-native approaches. Learn from real-world examples and best practices.',
            'excerpt' => 'Learn proven strategies for building software architecture that can scale with your business growth.',
            'category' => 'software',
            'image_url' => 'https://picsum.photos/id/1020/1200/600',
            'featured' => true
        ),
        array(
            'title' => 'Machine Learning in Healthcare',
            'content' => 'Machine learning is transforming healthcare in unprecedented ways. From diagnostic imaging to drug discovery, AI is helping medical professionals provide better patient care. This article explores current applications, challenges, and future possibilities of machine learning in healthcare.',
            'excerpt' => 'Discover how machine learning is revolutionizing healthcare and improving patient outcomes.',
            'category' => 'machine-learning',
            'image_url' => 'https://picsum.photos/id/1021/1200/600',
            'featured' => false
        ),
        array(
            'title' => 'Game Development with Unity',
            'content' => 'Unity has become the go-to platform for game developers worldwide. This comprehensive tutorial covers everything from basic game mechanics to advanced features. Learn how to create engaging games, optimize performance, and publish your creations to various platforms.',
            'excerpt' => 'Master Unity game development with this comprehensive guide covering everything from basics to advanced techniques.',
            'category' => 'game',
            'image_url' => 'https://picsum.photos/id/1022/1200/600',
            'featured' => false
        ),
        array(
            'title' => 'Advanced Web Development Techniques',
            'content' => 'Take your web development skills to the next level with advanced techniques and modern practices. This comprehensive guide covers performance optimization, security best practices, and cutting-edge technologies that will make your websites stand out.',
            'excerpt' => 'Learn advanced web development techniques to create faster, more secure, and more engaging websites.',
            'category' => 'software',
            'image_url' => 'https://picsum.photos/id/1024/1200/600',
            'featured' => true
        ),
        array(
            'title' => 'Creative Design Inspiration',
            'content' => 'Discover the latest trends in creative design and get inspired by innovative projects from around the world. This collection showcases the best in modern design, from minimalist interfaces to bold artistic expressions.',
            'excerpt' => 'Get inspired by the latest creative design trends and innovative projects from talented designers worldwide.',
            'category' => 'design',
            'image_url' => 'https://picsum.photos/id/1025/1200/600',
            'featured' => true
        )
    );
    
    // Create demo posts
    foreach ($demo_posts as $post_data) {
        $post_id = wp_insert_post(array(
            'post_title' => $post_data['title'],
            'post_content' => $post_data['content'],
            'post_excerpt' => $post_data['excerpt'],
            'post_status' => 'publish',
            'post_type' => 'post',
            'post_author' => 1,
            'post_date' => date('Y-m-d H:i:s', strtotime('-' . rand(1, 30) . ' days'))
        ));
        
        if ($post_id && !is_wp_error($post_id)) {
            // Mark as demo post
            update_post_meta($post_id, 'peblog_demo_post', '1');
            
            // Set category
            if (isset($category_ids[$post_data['category']])) {
                wp_set_post_categories($post_id, array($category_ids[$post_data['category']]));
            }
            
            // Set featured image from Lorem Picsum
            if (isset($post_data['image_url'])) {
                $attachment_id = peblog_attach_image_from_url($post_data['image_url'], $post_id, $post_data['title']);
                if ($attachment_id) {
                    set_post_thumbnail($post_id, $attachment_id);
                }
            }
            
            // Mark as featured if needed
            if ($post_data['featured']) {
                update_post_meta($post_id, 'hero_post', '1');
            }
        }
    }
    
    // Mark demo content as created
    update_option('peblog_demo_content_created', true);
}

// Helper function to attach images from URL
function peblog_attach_image_from_url($image_url, $post_id, $post_title = '') {
    // Download image from URL
    $image_data = wp_remote_get($image_url);
    
    if (is_wp_error($image_data)) {
        return false;
    }
    
    $image_body = wp_remote_retrieve_body($image_data);
    $image_type = wp_remote_retrieve_header($image_data, 'content-type');
    
    if (!$image_body || !$image_type) {
        return false;
    }
    
    // Generate filename
    $filename = sanitize_file_name($post_title) . '.jpg';
    
    // Upload image
    $upload_file = wp_upload_bits($filename, null, $image_body);
    
    if ($upload_file['error']) {
        return false;
    }
    
    // Create attachment
    $attachment = array(
        'post_mime_type' => $image_type,
        'post_title' => $post_title ? $post_title : 'Demo Image',
        'post_content' => '',
        'post_status' => 'inherit'
    );
    
    $attachment_id = wp_insert_attachment($attachment, $upload_file['file'], $post_id);
    
    if (!is_wp_error($attachment_id)) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload_file['file']);
        wp_update_attachment_metadata($attachment_id, $attachment_data);
        return $attachment_id;
    }
    
    return false;
}

// Helper function to attach images from file path (kept for backward compatibility)
function peblog_attach_image($image_path, $post_id) {
    if (!file_exists($image_path)) {
        return false;
    }
    
    $filename = basename($image_path);
    $upload_file = wp_upload_bits($filename, null, file_get_contents($image_path));
    
    if (!$upload_file['error']) {
        $wp_filetype = wp_check_filetype($filename, null);
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
            'post_content' => '',
            'post_status' => 'inherit'
        );
        
        $attachment_id = wp_insert_attachment($attachment, $upload_file['file'], $post_id);
        
        if (!is_wp_error($attachment_id)) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload_file['file']);
            wp_update_attachment_metadata($attachment_id, $attachment_data);
            return $attachment_id;
        }
    }
    
    return false;
}

// Hook to create demo content on theme activation
add_action('after_switch_theme', 'peblog_create_demo_content');

// Custom Menu Walker for Nested Menus
class Peblog_Walker_Nav_Menu extends Walker_Nav_Menu {
    
    // Start Level (ul)
    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"sub-menu\">\n";
    }
    
    // End Level (ul)
    function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
    
    // Start Element (li)
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        // Add dropdown class for parent items
        if (in_array('menu-item-has-children', $classes)) {
            $classes[] = 'has-dropdown';
        }
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= $indent . '<li' . $id . $class_names .'>';
        
        $attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
        $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
        $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
        $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';
        
        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a' . $attributes .'>';
        $item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
        
        // Add dropdown arrow for parent items
        if (in_array('menu-item-has-children', $classes)) {
            $item_output .= ' <span class="dropdown-arrow">
                <svg width="16px" height="16px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 9L14 14.1599C13.7429 14.4323 13.4329 14.6493 13.089 14.7976C12.7451 14.9459 12.3745 15.0225 12 15.0225C11.6255 15.0225 11.2549 14.9459 10.9109 14.7976C10.567 14.6493 10.2571 14.4323 10 14.1599L5 9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>';
        }
        
        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after : '';
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    
    // End Element (li)
    function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= "</li>\n";
    }
}

// Enqueue styles and scripts
function peblog_enqueue_assets() {
    // Main theme stylesheet
    wp_enqueue_style('peblog-style', get_stylesheet_uri(), array(), '1.0.0');
    
    // Original CSS files
    wp_enqueue_style('peblog-original-css', get_template_directory_uri() . '/assets/css/style.css', array(), '1.0.0');
    wp_enqueue_style('peblog-responsive-css', get_template_directory_uri() . '/assets/css/responsive.css', array('peblog-original-css'), '1.0.0');
    
    // Swiper CSS
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css', array(), '9.0.0');
    
    // Swiper JavaScript (load first)
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js', array(), '9.0.0', true);
    
    // Main JavaScript (load after Swiper)
    wp_enqueue_script('peblog-script', get_template_directory_uri() . '/assets/js/main.js', array('jquery', 'swiper-js'), '1.0.0', true);
    
    // Localize script for AJAX
    wp_localize_script('peblog-script', 'peblog_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('peblog_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'peblog_enqueue_assets');

// Register navigation menus
function peblog_register_menus() {
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'peblog'),
        'footer' => __('Footer Menu', 'peblog'),
    ));
}
add_action('init', 'peblog_register_menus');

// Register widget areas
function peblog_widgets_init() {
    register_sidebar(array(
        'name' => __('Sidebar', 'peblog'),
        'id' => 'peblog-sidebar',
        'description' => __('Add widgets here.', 'peblog'),
        'before_widget' => '<section id="%1$s" class="widget sidebar-widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}
add_action('widgets_init', 'peblog_widgets_init');

// Template Part Hooks
// These functions render the default template parts
// Premium/Child themes can use remove_action() and add_action() to replace them

function peblog_render_hero_slider() {
    get_template_part('template-parts/hero/hero-slider');
}
add_action('peblog_hero_slider', 'peblog_render_hero_slider', 10);

function peblog_render_featured_posts() {
    get_template_part('template-parts/featured-posts/featured-posts');
}
add_action('peblog_featured_posts', 'peblog_render_featured_posts', 10);

function peblog_render_latest_posts() {
    get_template_part('template-parts/latest-posts/latest-posts');
}
add_action('peblog_latest_posts', 'peblog_render_latest_posts', 10);

function peblog_render_most_read() {
    get_template_part('template-parts/most-read/most-read');
}
add_action('peblog_most_read', 'peblog_render_most_read', 10);

function peblog_render_category_filter() {
    get_template_part('template-parts/category-filter/category-filter');
}
add_action('peblog_category_filter', 'peblog_render_category_filter', 10);

// Custom post types removed - using standard WordPress posts only

// Custom Comment Callback
function peblog_comment_callback($comment, $args, $depth) {
    if ('div' === $args['style']) {
        $tag       = 'div';
        $add_below = 'comment';
    } else {
        $tag       = 'li';
        $add_below = 'div-comment';
    }
    ?>
    <<?php echo $tag; ?> <?php comment_class(empty($args['has_children']) ? '' : 'parent'); ?> id="comment-<?php comment_ID(); ?>">
    
    <div class="comment-body">
        <div class="comment-author-avatar">
            <?php if ($args['avatar_size'] != 0) {
                echo get_avatar($comment, $args['avatar_size']);
            } ?>
        </div>
        
        <div class="comment-content">
            <div class="comment-meta">
                <cite class="comment-author">
                    <?php echo get_comment_author_link(); ?>
                </cite>
                <span class="comment-date">
                    <?php
                    printf(
                        __('%1$s at %2$s', 'peblog'),
                        get_comment_date(),
                        get_comment_time()
                    );
                    ?>
                </span>
                <?php edit_comment_link(__('Edit', 'peblog'), '<span class="edit-link">', '</span>'); ?>
            </div>
            
            <?php if ($comment->comment_approved == '0') : ?>
                <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'peblog'); ?></em>
            <?php endif; ?>
            
            <div class="comment-text">
                <?php comment_text(); ?>
            </div>
            
            <?php
            comment_reply_link(array_merge($args, array(
                'add_below' => $add_below,
                'depth'     => $depth,
                'max_depth' => $args['max_depth'],
                'before'    => '<div class="reply">',
                'after'     => '</div>'
            )));
            ?>
        </div>
    </div>
    
    <?php
}

// Custom excerpt length
function peblog_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'peblog_excerpt_length');

// Custom excerpt more
function peblog_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'peblog_excerpt_more');

// Add custom body classes
function peblog_body_classes($classes) {
    if (is_home() || is_front_page()) {
        $classes[] = 'home-page';
    }
    return $classes;
}
add_filter('body_class', 'peblog_body_classes');

// WordPress Customizer
function peblog_customize_register($wp_customize) {
    // Home Settings Panel (Main)
    $wp_customize->add_panel('peblog_home_settings', array(
        'title' => __('Home Settings', 'peblog'),
        'description' => __('Customize all homepage sections and template parts', 'peblog'),
        'priority' => 25,
    ));
    
    // Hero Section (Under Home Settings)
    $wp_customize->add_section('peblog_hero', array(
        'title' => __('Hero Section', 'peblog'),
        'panel' => 'peblog_home_settings',
        'priority' => 10,
    ));
    
    // Hero Posts Limit
    $wp_customize->add_setting('peblog_hero_posts_limit', array(
        'default' => 6,
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('peblog_hero_posts_limit', array(
        'label' => __('Number of Hero Posts', 'peblog'),
        'description' => __('How many posts to display in the hero slider (recommended: 6 for 3D effect)', 'peblog'),
        'section' => 'peblog_hero',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 12,
            'step' => 1,
        ),
    ));
    
    // Hero Category Selection (Single Select)
    $wp_customize->add_setting('peblog_hero_category', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('peblog_hero_category', array(
        'label' => __('Hero Category', 'peblog'),
        'description' => __('Select category for hero slider posts (leave empty to show all categories)', 'peblog'),
        'section' => 'peblog_hero',
        'type' => 'select',
        'choices' => peblog_get_categories_choices(),
    ));
    
    // Hero More Button Text
    $wp_customize->add_setting('peblog_hero_more_text', array(
        'default' => 'More',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('peblog_hero_more_text', array(
        'label' => __('Hero More Button Text', 'peblog'),
        'description' => __('Text for the "More" button in hero slider', 'peblog'),
        'section' => 'peblog_hero',
        'type' => 'text',
    ));
    
    // Social Media Section
    $wp_customize->add_section('peblog_social', array(
        'title' => __('Social Media', 'peblog'),
        'priority' => 35,
    ));
    
    // Instagram URL
    $wp_customize->add_setting('peblog_instagram_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('peblog_instagram_url', array(
        'label' => __('Instagram URL', 'peblog'),
        'section' => 'peblog_social',
        'type' => 'url',
    ));
    
    // Twitter URL
    $wp_customize->add_setting('peblog_twitter_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('peblog_twitter_url', array(
        'label' => __('Twitter/X URL', 'peblog'),
        'section' => 'peblog_social',
        'type' => 'url',
    ));
    
    // LinkedIn URL
    $wp_customize->add_setting('peblog_linkedin_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('peblog_linkedin_url', array(
        'label' => __('LinkedIn URL', 'peblog'),
        'section' => 'peblog_social',
        'type' => 'url',
    ));
    
    // GitHub URL
    $wp_customize->add_setting('peblog_github_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('peblog_github_url', array(
        'label' => __('GitHub URL', 'peblog'),
        'section' => 'peblog_social',
        'type' => 'url',
    ));
    
    // Pinterest URL
    $wp_customize->add_setting('peblog_pinterest_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('peblog_pinterest_url', array(
        'label' => __('Pinterest URL', 'peblog'),
        'section' => 'peblog_social',
        'type' => 'url',
    ));
    
    // Telegram URL
    $wp_customize->add_setting('peblog_telegram_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('peblog_telegram_url', array(
        'label' => __('Telegram URL', 'peblog'),
        'section' => 'peblog_social',
        'type' => 'url',
    ));
    
    // YouTube URL
    $wp_customize->add_setting('peblog_youtube_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('peblog_youtube_url', array(
        'label' => __('YouTube URL', 'peblog'),
        'section' => 'peblog_social',
        'type' => 'url',
    ));
    
    // Facebook URL
    $wp_customize->add_setting('peblog_facebook_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('peblog_facebook_url', array(
        'label' => __('Facebook URL', 'peblog'),
        'section' => 'peblog_social',
        'type' => 'url',
    ));
    
    // Dribbble URL
    $wp_customize->add_setting('peblog_dribbble_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('peblog_dribbble_url', array(
        'label' => __('Dribbble URL', 'peblog'),
        'section' => 'peblog_social',
        'type' => 'url',
    ));
    
    // Behance URL
    $wp_customize->add_setting('peblog_behance_url', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('peblog_behance_url', array(
        'label' => __('Behance URL', 'peblog'),
        'section' => 'peblog_social',
        'type' => 'url',
    ));
    
    // Newsletter Section
    $wp_customize->add_section('peblog_newsletter', array(
        'title' => __('Newsletter', 'peblog'),
        'priority' => 40,
    ));
    
    // Newsletter Title
    $wp_customize->add_setting('peblog_newsletter_title', array(
        'default' => 'Join Our Newsletter',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('peblog_newsletter_title', array(
        'label' => __('Newsletter Title', 'peblog'),
        'section' => 'peblog_newsletter',
        'type' => 'text',
    ));
    
    // Newsletter Form Shortcode
    $wp_customize->add_setting('peblog_newsletter_shortcode', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('peblog_newsletter_shortcode', array(
        'label' => __('Newsletter Form Shortcode', 'peblog'),
        'description' => __('Enter your newsletter plugin shortcode here. Popular plugins: MailChimp for WordPress, MailPoet, Contact Form 7, etc. Example: [mc4wp_form] or [yikes-mailchimp form="1"]. Leave empty to show a fallback form.', 'peblog'),
        'section' => 'peblog_newsletter',
        'type' => 'text',
    ));
    
    // Featured Posts Section (Under Home Settings)
    $wp_customize->add_section('peblog_featured_posts', array(
        'title' => __('Featured Posts', 'peblog'),
        'panel' => 'peblog_home_settings',
        'priority' => 20,
    ));
    
    // Featured Posts Title
    $wp_customize->add_setting('peblog_featured_title', array(
        'default' => 'Featured Posts',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('peblog_featured_title', array(
        'label' => __('Featured Posts Title', 'peblog'),
        'section' => 'peblog_featured_posts',
        'type' => 'text',
    ));
    
    // Featured Posts Category
    $wp_customize->add_setting('peblog_featured_category', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('peblog_featured_category', array(
        'label' => __('Featured Posts Category', 'peblog'),
        'section' => 'peblog_featured_posts',
        'type' => 'select',
        'choices' => peblog_get_categories_choices(),
    ));
    
    // Latest Posts Section (Under Home Settings)
    $wp_customize->add_section('peblog_latest_posts', array(
        'title' => __('Latest Posts', 'peblog'),
        'panel' => 'peblog_home_settings',
        'priority' => 30,
    ));
    
    // Latest Posts Title
    $wp_customize->add_setting('peblog_latest_title', array(
        'default' => 'Latest Posts',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('peblog_latest_title', array(
        'label' => __('Latest Posts Title', 'peblog'),
        'section' => 'peblog_latest_posts',
        'type' => 'text',
    ));
    
    // Latest Posts Category
    $wp_customize->add_setting('peblog_latest_category', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('peblog_latest_category', array(
        'label' => __('Latest Posts Category', 'peblog'),
        'section' => 'peblog_latest_posts',
        'type' => 'select',
        'choices' => peblog_get_categories_choices(),
    ));
    
    // Most Read Section (Under Home Settings)
    $wp_customize->add_section('peblog_most_read', array(
        'title' => __('Most Read', 'peblog'),
        'panel' => 'peblog_home_settings',
        'priority' => 35,
    ));
    
    // Most Read Post Selection
    $wp_customize->add_setting('peblog_most_read_post', array(
        'default' => '',
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('peblog_most_read_post', array(
        'label' => __('Select Specific Post', 'peblog'),
        'description' => __('Choose a specific post to display in Most Read section. If empty, the most viewed post will be shown automatically.', 'peblog'),
        'section' => 'peblog_most_read',
        'type' => 'select',
        'choices' => peblog_get_posts_choices(),
    ));
    
    // Show Popular Tags in Most Read Section
    $wp_customize->add_setting('peblog_most_read_show_tags', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('peblog_most_read_show_tags', array(
        'label' => __('Show Popular Tags', 'peblog'),
        'description' => __('Display popular tags section in Most Read area', 'peblog'),
        'section' => 'peblog_most_read',
        'type' => 'checkbox',
    ));
    
    // Author Section
    $wp_customize->add_section('peblog_author', array(
        'title' => __('Author Information (Most Read)', 'peblog'),
        'panel' => 'peblog_home_settings',
        'priority' => 40,
    ));
    
    // Author Name
    $wp_customize->add_setting('peblog_author_name', array(
        'default' => 'John Doe',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('peblog_author_name', array(
        'label' => __('Author Name', 'peblog'),
        'section' => 'peblog_author',
        'type' => 'text',
    ));
    
    // Author Title
    $wp_customize->add_setting('peblog_author_title', array(
        'default' => 'Blog Author',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('peblog_author_title', array(
        'label' => __('Author Title', 'peblog'),
        'section' => 'peblog_author',
        'type' => 'text',
    ));
    
    // Author Description
    $wp_customize->add_setting('peblog_author_description', array(
        'default' => 'Passionate about technology, design, and innovation.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('peblog_author_description', array(
        'label' => __('Author Description', 'peblog'),
        'section' => 'peblog_author',
        'type' => 'textarea',
    ));
    
    // Author Image
    $wp_customize->add_setting('peblog_author_image', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'peblog_author_image', array(
        'label' => __('Author Image', 'peblog'),
        'section' => 'peblog_author',
    )));
    
    // Logo & Branding Section
    $wp_customize->add_section('peblog_logo', array(
        'title' => __('Logo & Branding', 'peblog'),
        'priority' => 20,
    ));
    
    // Site Logo
    $wp_customize->add_setting('peblog_logo', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'peblog_logo', array(
        'label' => __('Site Logo', 'peblog'),
        'description' => __('Upload your site logo. Recommended size: 200x60px', 'peblog'),
        'section' => 'peblog_logo',
    )));
    
    // Site Logo (White/Dark version)
    $wp_customize->add_setting('peblog_logo_white', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'peblog_logo_white', array(
        'label' => __('Site Logo (White/Dark)', 'peblog'),
        'description' => __('Upload white/dark version of your logo for dark mode. Recommended size: 200x60px', 'peblog'),
        'section' => 'peblog_logo',
    )));
    
    // Favicon
    $wp_customize->add_setting('peblog_favicon', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'peblog_favicon', array(
        'label' => __('Favicon', 'peblog'),
        'description' => __('Upload your favicon. Recommended size: 32x32px or 16x16px', 'peblog'),
        'section' => 'peblog_logo',
    )));
    
    // Apple Touch Icon
    $wp_customize->add_setting('peblog_apple_touch_icon', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'peblog_apple_touch_icon', array(
        'label' => __('Apple Touch Icon', 'peblog'),
        'description' => __('Upload Apple Touch Icon for iOS devices. Recommended size: 180x180px', 'peblog'),
        'section' => 'peblog_logo',
    )));
    
    // Header Section
    $wp_customize->add_section('peblog_header', array(
        'title' => __('Header Settings', 'peblog'),
        'priority' => 25,
    ));
    
    // Action Button Text
    $wp_customize->add_setting('peblog_action_button_text', array(
        'default' => 'Subscribe',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('peblog_action_button_text', array(
        'label' => __('Action Button Text', 'peblog'),
        'section' => 'peblog_header',
        'type' => 'text',
    ));
    
    // Action Button URL
    $wp_customize->add_setting('peblog_action_button_url', array(
        'default' => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('peblog_action_button_url', array(
        'label' => __('Action Button URL', 'peblog'),
        'section' => 'peblog_header',
        'type' => 'url',
    ));
    
    // Action Button Target
    $wp_customize->add_setting('peblog_action_button_target', array(
        'default' => '_self',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('peblog_action_button_target', array(
        'label' => __('Action Button Target', 'peblog'),
        'section' => 'peblog_header',
        'type' => 'select',
        'choices' => array(
            '_self' => __('Same Window', 'peblog'),
            '_blank' => __('New Window', 'peblog'),
        ),
    ));
    
    // Default Dark Mode
    $wp_customize->add_setting('peblog_default_dark_mode', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('peblog_default_dark_mode', array(
        'label' => __('Default Dark Mode', 'peblog'),
        'description' => __('Set dark mode as default when users first visit your site', 'peblog'),
        'section' => 'peblog_header',
        'type' => 'checkbox',
    ));
    
    // Single Page Section (Separate from Home Settings)
    $wp_customize->add_section('peblog_single_page', array(
        'title' => __('Single Page Settings', 'peblog'),
        'description' => __('Customize single post page settings, author card and sidebar', 'peblog'),
        'priority' => 55,
    ));
    
    // Show Author Card on Single Page
    $wp_customize->add_setting('peblog_show_author_card', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('peblog_show_author_card', array(
        'label' => __('Show Author Card', 'peblog'),
        'description' => __('Display author card below post content on single pages', 'peblog'),
        'section' => 'peblog_single_page',
        'type' => 'checkbox',
    ));
    
    // Single Page Author Card uses Customizer Author Info
    // (Uses the same settings from peblog_author section)
    
    // Sidebar Position (Inside Single Page Settings)
    $wp_customize->add_setting('peblog_sidebar_position', array(
        'default' => 'none',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('peblog_sidebar_position', array(
        'label' => __('Sidebar Position', 'peblog'),
        'description' => __('Choose where to display the sidebar', 'peblog'),
        'section' => 'peblog_single_page',
        'type' => 'select',
        'choices' => array(
            'none' => __('No Sidebar', 'peblog'),
            'left' => __('Left Side', 'peblog'),
            'right' => __('Right Side', 'peblog'),
        ),
    ));
    
    // Show Sidebar on Single Posts (Inside Single Page Settings)
    $wp_customize->add_setting('peblog_sidebar_single', array(
        'default' => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('peblog_sidebar_single', array(
        'label' => __('Show Sidebar on Single Posts', 'peblog'),
        'description' => __('Display sidebar on single post pages', 'peblog'),
        'section' => 'peblog_single_page',
        'type' => 'checkbox',
    ));
    
    // Show Sidebar on Pages (Inside Single Page Settings)
    $wp_customize->add_setting('peblog_sidebar_page', array(
        'default' => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('peblog_sidebar_page', array(
        'label' => __('Show Sidebar on Pages', 'peblog'),
        'description' => __('Display sidebar on static pages', 'peblog'),
        'section' => 'peblog_single_page',
        'type' => 'checkbox',
    ));
    
    // Show Post Tags
    $wp_customize->add_setting('peblog_show_post_tags', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('peblog_show_post_tags', array(
        'label' => __('Show Post Tags', 'peblog'),
        'description' => __('Display post tags below post content on single pages', 'peblog'),
        'section' => 'peblog_single_page',
        'type' => 'checkbox',
    ));
    
    // Social Share Buttons Section
    $wp_customize->add_setting('peblog_social_share_title', array(
        'default' => __('Share this post', 'peblog'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('peblog_social_share_title', array(
        'label' => __('Social Share Title', 'peblog'),
        'description' => __('Title text above social share buttons', 'peblog'),
        'section' => 'peblog_single_page',
        'type' => 'text',
    ));
    
    // Facebook Share
    $wp_customize->add_setting('peblog_share_facebook', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('peblog_share_facebook', array(
        'label' => __('Show Facebook Share Button', 'peblog'),
        'section' => 'peblog_single_page',
        'type' => 'checkbox',
    ));
    
    // Twitter/X Share
    $wp_customize->add_setting('peblog_share_twitter', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('peblog_share_twitter', array(
        'label' => __('Show Twitter/X Share Button', 'peblog'),
        'section' => 'peblog_single_page',
        'type' => 'checkbox',
    ));
    
    // LinkedIn Share
    $wp_customize->add_setting('peblog_share_linkedin', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('peblog_share_linkedin', array(
        'label' => __('Show LinkedIn Share Button', 'peblog'),
        'section' => 'peblog_single_page',
        'type' => 'checkbox',
    ));
    
    // WhatsApp Share
    $wp_customize->add_setting('peblog_share_whatsapp', array(
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('peblog_share_whatsapp', array(
        'label' => __('Show WhatsApp Share Button', 'peblog'),
        'section' => 'peblog_single_page',
        'type' => 'checkbox',
    ));
    
    // Telegram Share
    $wp_customize->add_setting('peblog_share_telegram', array(
        'default' => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('peblog_share_telegram', array(
        'label' => __('Show Telegram Share Button', 'peblog'),
        'section' => 'peblog_single_page',
        'type' => 'checkbox',
    ));
    
    // Pinterest Share
    $wp_customize->add_setting('peblog_share_pinterest', array(
        'default' => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('peblog_share_pinterest', array(
        'label' => __('Show Pinterest Share Button', 'peblog'),
        'section' => 'peblog_single_page',
        'type' => 'checkbox',
    ));
    
    // Category Icons Section
    $wp_customize->add_section('peblog_category_icons', array(
        'title' => __('Category Icons', 'peblog'),
        'priority' => 60,
    ));
    
    // All Categories Section (Under Home Settings)
    $wp_customize->add_section('peblog_all_categories', array(
        'title' => __('All Categories Section', 'peblog'),
        'panel' => 'peblog_home_settings',
        'priority' => 50,
    ));
    
    // All Categories Title
    $wp_customize->add_setting('peblog_all_categories_title', array(
        'default' => 'Explore Categories',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('peblog_all_categories_title', array(
        'label' => __('Section Title', 'peblog'),
        'description' => __('Change the title of the category filter section. Default: "Explore Categories"', 'peblog'),
        'section' => 'peblog_all_categories',
        'type' => 'text',
    ));
    
    // Number of Posts to Show
    $wp_customize->add_setting('peblog_category_posts_limit', array(
        'default' => 12,
        'sanitize_callback' => 'absint',
    ));
    $wp_customize->add_control('peblog_category_posts_limit', array(
        'label' => __('Number of Posts to Show', 'peblog'),
        'description' => __('How many posts to display in the category filter section', 'peblog'),
        'section' => 'peblog_all_categories',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 1,
            'max' => 50,
            'step' => 1,
        ),
    ));
    
    // Show Empty Categories
    $wp_customize->add_setting('peblog_show_empty_categories', array(
        'default' => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ));
    $wp_customize->add_control('peblog_show_empty_categories', array(
        'label' => __('Show Empty Categories', 'peblog'),
        'description' => __('Show categories that have no posts', 'peblog'),
        'section' => 'peblog_all_categories',
        'type' => 'checkbox',
    ));
    
    // Selected Category (Single Select)
    $wp_customize->add_setting('peblog_selected_category', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('peblog_selected_category', array(
        'label' => __('Filter by Category', 'peblog'),
        'description' => __('Select a category to filter posts (leave empty to show all categories)', 'peblog'),
        'section' => 'peblog_all_categories',
        'type' => 'select',
        'choices' => peblog_get_categories_choices(),
    ));
    
    // Get all categories and add icon settings for each
    $categories = get_categories(array('hide_empty' => false));
    foreach ($categories as $category) {
        $wp_customize->add_setting('peblog_category_icon_' . $category->slug, array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'peblog_category_icon_' . $category->slug, array(
            'label' => sprintf(__('%s Icon', 'peblog'), $category->name),
            'section' => 'peblog_category_icons',
        )));
    }
}
add_action('customize_register', 'peblog_customize_register');

// Get posts for customizer choices
function peblog_get_posts_choices() {
    $choices = array('' => __('Default (Most Viewed)', 'peblog'));
    $posts = get_posts(array(
        'numberposts' => -1,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
    ));
    
    foreach ($posts as $post) {
        $choices[$post->ID] = $post->post_title . ' (ID: ' . $post->ID . ')';
    }
    
    return $choices;
}

// Get categories for customizer choices
function peblog_get_categories_choices() {
    $categories = get_categories(array('hide_empty' => false));
    $choices = array('' => __('All Categories', 'peblog'));
    
    foreach ($categories as $category) {
        $choices[$category->slug] = $category->name;
    }
    
    return $choices;
}

// Admin Options Page
function peblog_admin_menu() {
    add_theme_page(
        __('Peblog Options', 'peblog'),
        __('Peblog Options', 'peblog'),
        'manage_options',
        'peblog-options',
        'peblog_admin_page'
    );
}
add_action('admin_menu', 'peblog_admin_menu');

function peblog_admin_page() {
    $customize_url = admin_url('customize.php');
    $theme_url = 'https://github.com/emrahyldz93/peblog';
    ?>
    <div class="wrap">
        <h1><?php _e('Peblog Options', 'peblog'); ?></h1>
        
        <div class="peblog-admin-content">
            <div class="peblog-admin-section">
                <h2><?php _e('Theme Information', 'peblog'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th><?php _e('Version', 'peblog'); ?></th>
                        <td>1.0.0</td>
                    </tr>
                    <tr>
                        <th><?php _e('Author', 'peblog'); ?></th>
                        <td>Emrah Yıldız</td>
                    </tr>
                    <tr>
                        <th><?php _e('License', 'peblog'); ?></th>
                        <td>GPL-2.0-or-later</td>
                    </tr>
                    <tr>
                        <th><?php _e('Requires', 'peblog'); ?></th>
                        <td>WordPress 6.0+, PHP 8.0+</td>
                    </tr>
                </table>
                <p>
                    <a href="<?php echo esc_url($theme_url); ?>" target="_blank" class="button button-secondary">
                        <?php _e('View on GitHub', 'peblog'); ?>
                    </a>
                    <a href="<?php echo esc_url($customize_url); ?>" class="button button-primary">
                        <?php _e('Customize Theme', 'peblog'); ?>
                    </a>
                </p>
            </div>
            
            <div class="peblog-admin-section">
                <h2><?php _e('Documentation', 'peblog'); ?></h2>
                <div class="peblog-docs">
                    <h3><?php _e('Quick Start', 'peblog'); ?></h3>
                    <ol>
                        <li><?php _e('Go to Appearance > Customize to configure your theme', 'peblog'); ?></li>
                        <li><?php _e('Create a menu in Appearance > Menus', 'peblog'); ?></li>
                        <li><?php _e('Add blog posts with categories and tags', 'peblog'); ?></li>
                        <li><?php _e('Upload your logo in Appearance > Customize > Site Identity', 'peblog'); ?></li>
                    </ol>
                    
                    <h3><?php _e('Theme Settings', 'peblog'); ?></h3>
                    <p><?php _e('All theme settings are located in Appearance > Customize:', 'peblog'); ?></p>
                    <ul>
                        <li><strong><?php _e('Home Settings', 'peblog'); ?></strong>: Hero Slider, Featured Posts, Latest Posts, Most Read, Explore Categories</li>
                        <li><strong><?php _e('Header Settings', 'peblog'); ?></strong>: Logo, Action Button</li>
                        <li><strong><?php _e('Single Page Settings', 'peblog'); ?></strong>: Author Card, Sidebar, Post Tags, Social Share</li>
                        <li><strong><?php _e('Social Media', 'peblog'); ?></strong>: Add your social media URLs</li>
                        <li><strong><?php _e('Newsletter', 'peblog'); ?></strong>: Add plugin shortcode (MailChimp, MailPoet, etc.)</li>
                    </ul>
                    
                    <h3><?php _e('Features', 'peblog'); ?></h3>
                    <ul>
                        <li><?php _e('Dark/Light Mode with localStorage', 'peblog'); ?></li>
                        <li><?php _e('Fully responsive design', 'peblog'); ?></li>
                        <li><?php _e('Swiper sliders for hero and latest posts', 'peblog'); ?></li>
                        <li><?php _e('Post views counter', 'peblog'); ?></li>
                        <li><?php _e('Social sharing buttons', 'peblog'); ?></li>
                        <li><?php _e('Newsletter integration', 'peblog'); ?></li>
                        <li><?php _e('Archive templates (category, tag, author, search)', 'peblog'); ?></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <style>
        .peblog-admin-content {
            max-width: 800px;
            margin-top: 20px;
        }
        .peblog-admin-section {
            background: #fff;
            padding: 20px;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .peblog-admin-section h2 {
            margin-top: 0;
        }
        .peblog-docs h3 {
            margin-top: 20px;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f0f0f1;
        }
        .peblog-docs h3:first-child {
            margin-top: 0;
        }
        .peblog-docs ol,
        .peblog-docs ul {
            margin-left: 20px;
        }
        .peblog-docs li {
            margin-bottom: 8px;
            line-height: 1.6;
        }
        </style>
    </div>
    <?php
}

