<?php
/**
 * Category Filter Template Part
 */

// Get customizer settings
$show_empty_categories = get_theme_mod('peblog_show_empty_categories', false);
$selected_category = get_theme_mod('peblog_selected_category', '');
$posts_limit = get_theme_mod('peblog_category_posts_limit', 12);

// Get categories based on settings
$category_args = array();
if (!$show_empty_categories) {
    $category_args['hide_empty'] = true;
} else {
    $category_args['hide_empty'] = false;
}

// If specific category is selected
if (!empty($selected_category)) {
    $category_args['slug'] = $selected_category;
}

$categories = get_categories($category_args);
?>

<!-- All Categories -->
<section id="filter-cateogries">
    <div class="filter-cat-container">
        <div class="category-title">
            <h2 class="heading"><?php echo esc_html(get_theme_mod('peblog_all_categories_title', 'Explore Categories')); ?></h2>
        </div>
        <div class="categories">
            <div class="category">
                <div class="category-box active" data-filter="all">
                    <div class="category-text">All</div>
                </div>
            </div>
            
            <?php
            $category_icons = array(
                'design' => 'design.svg',
                'blockchain' => 'blockchain.svg',
                'software' => 'software.svg',
                'machine-learning' => 'machine-learning.svg',
                'game' => 'game.svg'
            );
            
            foreach ($categories as $category) {
                $custom_icon = get_theme_mod('peblog_category_icon_' . $category->slug, '');
                $default_icon = isset($category_icons[strtolower($category->slug)]) ? $category_icons[strtolower($category->slug)] : 'software.svg';
                $icon_url = $custom_icon ? $custom_icon : esc_url(get_template_directory_uri() . '/assets/images/icon/' . $default_icon);
                ?>
                <div class="category">
                    <div class="category-box" data-filter="<?php echo esc_attr($category->slug); ?>">
                        <div class="category-icon">
                            <img src="<?php echo esc_url($icon_url); ?>" alt="<?php echo esc_attr($category->name); ?>" />
                        </div>
                        <div class="category-text"><?php echo esc_html($category->name); ?></div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>

    <div class="filter-posts-container">
        <?php
        // Get posts for filtering based on customizer setting
        $posts_args = array('numberposts' => $posts_limit);
        
        // Filter by category if selected
        if (!empty($selected_category)) {
            $posts_args['category_name'] = $selected_category;
        }
        
        $all_posts = get_posts($posts_args);
        
        if (empty($all_posts)) : ?>
            <div class="no-posts-message">
                <div class="no-posts-icon">📝</div>
                <h3>No Posts Found</h3>
                <p>There are no posts to display in the category filter section. Please add some posts to see them here.</p>
                <a href="<?php echo admin_url('post-new.php'); ?>" class="btn btn-primary">Create New Post</a>
            </div>
        <?php else : ?>
        <?php foreach ($all_posts as $post) {
            $post_categories = get_the_category($post->ID);
            $category_slugs = array();
            foreach ($post_categories as $cat) {
                $category_slugs[] = $cat->slug;
            }
            $category_class = implode(' ', $category_slugs);
            ?>
            <div class="filter-post" data-category="<?php echo esc_attr($category_class); ?>">
                <a href="<?php echo get_permalink($post->ID); ?>">
                    <?php if (has_post_thumbnail($post->ID)) : ?>
                        <?php echo get_the_post_thumbnail($post->ID, 'medium', array('class' => 'post-image')); ?>
                    <?php else : ?>
                        <div class="post-placeholder">
                            <div class="placeholder-icon">📝</div>
                        </div>
                    <?php endif; ?>
                </a>
                <div class="card-content">
                    <ul>
                        <li class="post-author-image">
                            <a href="<?php echo get_author_posts_url($post->post_author); ?>">
                                <?php echo get_avatar($post->post_author, 32); ?>
                            </a>
                        </li>
                        <li class="post-author"><a href="<?php echo get_author_posts_url($post->post_author); ?>"><?php echo get_the_author_meta('display_name', $post->post_author); ?></a></li>
                        <li class="post-date"><span><?php echo get_the_date('d.m.Y', $post->ID); ?></span></li>
                    </ul>
                    <h3 class="entry-title"><?php echo esc_html($post->post_title); ?></h3>
                    <div class="entry-expert">
                        <p><?php echo wp_trim_words($post->post_content, 15, '...'); ?></p>
                    </div>
                    <div class="entry-meta">
                        <div class="entry-tag">
                            <?php if ($post_categories && !empty($post_categories)) : ?>
                                <?php 
                                // Show only first category
                                $category = $post_categories[0];
                                // Dynamic color classes
                                $color_classes = ['tag-main', 'tag-warning', 'tag-success', 'tag-danger', 'tag-info'];
                                $color_index = $category->term_id % count($color_classes);
                                $color_class = $color_classes[$color_index];
                                ?>
                                <a href="<?php echo get_category_link($category->term_id); ?>" class="<?php echo $color_class; ?>"><?php echo esc_html($category->name); ?></a>
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
            <?php
        }
        ?>
        <?php endif; ?>
    </div>
</section>
<!-- All Categories end -->
