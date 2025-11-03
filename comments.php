<?php
/**
 * Comments Template
 *
 * This template displays comments and comment form for single posts
 */
 
// Security check
if (post_password_required()) {
    return;
}
?>

<div class="comments-area" id="comments">
    <?php if (have_comments()) : ?>
        <h2 class="comments-title">
            <?php
            $comments_number = get_comments_number();
            if ($comments_number === 1) {
                echo '1 Comment';
            } else {
                echo $comments_number . ' Comments';
            }
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments(array(
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 48,
                'callback'    => 'peblog_comment_callback',
            ));
            ?>
        </ol>

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
            <nav class="comment-navigation">
                <div class="nav-previous">
                    <?php previous_comments_link(__('← Older Comments', 'peblog')); ?>
                </div>
                <div class="nav-next">
                    <?php next_comments_link(__('Newer Comments →', 'peblog')); ?>
                </div>
            </nav>
        <?php endif; ?>

    <?php endif; ?>

    <?php
    // Comment form
    comment_form(array(
        'title_reply'          => __('Leave a Comment', 'peblog'),
        'title_reply_to'       => __('Leave a Reply to %s', 'peblog'),
        'cancel_reply_link'    => __('Cancel Reply', 'peblog'),
        'label_submit'         => __('Post Comment', 'peblog'),
        'submit_button'        => '<input name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" />',
        'submit_field'         => '<div class="form-submit">%1$s %2$s</div>',
        'format'               => 'html5',
    ));
    ?>
</div>

