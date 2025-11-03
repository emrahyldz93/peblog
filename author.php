<?php get_header(); ?>

<!-- main design start -->
<main>
    <!-- Author Archive Section -->
    <section id="filter-cateogries">
        <div class="page-header">
            <div class="page-title">
                <h1><?php the_author(); ?></h1>
                <?php if (get_the_author_meta('description')) : ?>
                    <p><?php echo wp_strip_all_tags(get_the_author_meta('description')); ?></p>
                <?php else : ?>
                    <p>All posts by <?php the_author(); ?></p>
                <?php endif; ?>
            </div>
            <div class="page-shape">
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/shape.svg" alt="">
            </div>
        </div>

        <div class="filter-posts-container">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); 
                    $author = get_the_author_meta('display_name');
                    $post_date = get_the_date('d.m.Y');
                    $categories = get_the_category();
                ?>
                    <div class="filter-post" data-category="<?php echo esc_attr(get_the_category_list(', ', '', get_the_ID())); ?>">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium', array('class' => 'post-image')); ?>
                            </a>
                        <?php else : ?>
                            <div class="post-placeholder">
                                <div class="placeholder-icon">📝</div>
                            </div>
                        <?php endif; ?>
                        <div class="card-content">
                            <ul class="entry-meta">
                                <li class="post-author-image">
                                    <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>">
                                        <?php echo get_avatar(get_the_author_meta('ID'), 32); ?>
                                    </a>
                                </li>
                                <li class="post-author"><a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php echo esc_html($author); ?></a></li>
                                <li class="post-date"><span><?php echo $post_date; ?></span></li>
                            </ul>
                            <h4 class="entry-title"><?php the_title(); ?></h4>
                            <div class="entry-expert">
                                <p><?php echo wp_trim_words(get_the_content(), 15, '...'); ?></p>
                            </div>
                            <div class="entry-meta">
                                <div class="entry-tag">
                                    <?php if ($categories && !empty($categories)) : ?>
                                        <?php 
                                        // Show only first category
                                        $category = $categories[0];
                                        // Dynamic color classes
                                        $color_classes = ['tag-main', 'tag-warning', 'tag-success', 'tag-danger', 'tag-info'];
                                        $color_index = $category->term_id % count($color_classes);
                                        $color_class = $color_classes[$color_index];
                                        ?>
                                        <a href="<?php echo get_category_link($category->term_id); ?>" class="<?php echo $color_class; ?>"><?php echo esc_html($category->name); ?></a>
                                    <?php endif; ?>
                                </div>
                                <div class="entry-rate">
                                    <a href="<?php the_permalink(); ?>"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/icon/eye.svg" alt=""><?php echo peblog_get_post_views(get_the_ID()); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <div class="no-posts-message">
                    <div class="no-posts-icon">📝</div>
                    <h3>No Posts Found</h3>
                    <p>Sorry, this author hasn't published any posts yet.</p>
                    <a href="<?php echo esc_url(home_url()); ?>" class="btn btn-primary">Go Home</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if (have_posts()) : ?>
            <div class="pagination">
                <div class="pagination-container">
                    <?php if (get_previous_posts_link()) : ?>
                        <a href="<?php echo esc_url(get_pagenum_link(max(1, get_query_var('paged') - 1))); ?>" class="pagination-btn prev">
                            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/icon/prev.svg" alt="Previous" />
                            <span>Previous</span>
                        </a>
                    <?php endif; ?>
                    
                    <div class="pagination-numbers">
                        <?php
                        $paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
                        $max = intval($wp_query->max_num_pages);
                        
                        if ($paged >= 1) {
                            $links[] = $paged;
                        }
                        
                        if ($paged >= 3) {
                            $links[] = $paged - 1;
                            $links[] = $paged - 2;
                        }
                        
                        if (($paged + 2) <= $max) {
                            $links[] = $paged + 2;
                            $links[] = $paged + 1;
                        }
                        
                        if (isset($links)) {
                            sort($links);
                            foreach ((array)$links as $link) {
                                if ($link == $paged) {
                                    echo '<a href="#" class="active">' . $link . '</a>';
                                } else {
                                    echo '<a href="' . esc_url(get_pagenum_link($link)) . '">' . $link . '</a>';
                                }
                            }
                        }
                        ?>
                    </div>
                    
                    <?php if (get_next_posts_link()) : ?>
                        <a href="<?php echo esc_url(get_pagenum_link(min($max, get_query_var('paged') + 1))); ?>" class="pagination-btn next">
                            <span>Next</span>
                            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/icon/next.svg" alt="Next" />
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </section>
</main>
<!-- main design end -->

<?php get_footer(); ?>
