<?php
/**
 * Hero Slider Template Part
 */

// Get customizer settings
$hero_posts_limit = get_theme_mod('peblog_hero_posts_limit', 6);
$hero_category = get_theme_mod('peblog_hero_category', '');
$hero_more_text = get_theme_mod('peblog_hero_more_text', 'More');

// Build query args
$hero_args = array(
    'numberposts' => $hero_posts_limit,
    'post_status' => 'publish'
);

// Get hero posts - filter by category if selected
if (!empty($hero_category)) {
    $hero_args['category_name'] = $hero_category;
}

$hero_posts = get_posts($hero_args);

// If not enough posts, get more from all categories
if (count($hero_posts) < $hero_posts_limit) {
    $additional_args = array(
        'numberposts' => $hero_posts_limit - count($hero_posts),
        'post_status' => 'publish',
        'exclude' => wp_list_pluck($hero_posts, 'ID')
    );
    $additional_posts = get_posts($additional_args);
    $hero_posts = array_merge($hero_posts, $additional_posts);
}
?>

<!-- home design two start -->
<section class="home-two" id="home">
    <div class="swiper twoDesing">
        <span class="prev-slide-two"></span>
        <span class="next-slide-two"></span>
        <div class="swiper-wrapper">
            <?php foreach ($hero_posts as $index => $post) : ?>
                <div class="swiper-slide">
                    <div class="two-design-slide" style="--i: <?php echo $index + 1; ?>; background: url(<?php 
                        if (has_post_thumbnail($post->ID)) {
                            echo get_the_post_thumbnail_url($post->ID, 'full');
                        } else {
                            echo esc_url(get_template_directory_uri() . '/assets/images/two-design-' . (($index % 4) + 1) . '.jpg');
                        }
                    ?>) no-repeat center center / cover;">
                        <div class="slide-content">
                            <h2><?php echo esc_html($post->post_title); ?></h2>
                            <p><?php echo wp_trim_words($post->post_content, 20, '...'); ?></p>
                            <a href="<?php echo get_permalink($post->ID); ?>" class="more"><?php echo esc_html($hero_more_text); ?></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<!-- home design two end -->

<style>
.hero-slide-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.hero-slide-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 500px;
}

.placeholder-content h3 {
    color: white;
    font-size: 2rem;
    text-align: center;
}
</style>
