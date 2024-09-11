<?php
/**
 * Template used for single posts and other post-types
 * that don't have a specific template.
 *
 * @package Avada
 * @subpackage Templates
 */

// Do not allow directly accessing this file.
if (!defined('ABSPATH')) {
    exit('Direct script access denied.');
}
?>
<?php get_header(); ?>

    <section id="content" style="<?php echo esc_attr(apply_filters('awb_content_tag_style', '')); ?>">
        <?php if (fusion_get_option('blog_pn_nav')) : ?>
            <div class="single-navigation clearfix">
                <?php previous_post_link('%link', esc_attr__('Previous', 'Avada')); ?>
                <?php next_post_link('%link', esc_attr__('Next', 'Avada')); ?>
            </div>
        <?php endif; ?>

        <?php while (have_posts()) : ?>
            <?php the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
                <div class="default-banner">
                    <?php the_post_thumbnail('full'); ?>
                </div>
                <div class="post-content">
                    <h1><?php echo get_the_title(); ?></h1>
                    <?php the_content(); ?>
                    <a href="/actualiteit" class="fusion-button brown-button"><?php print __('Terug naar overzicht') ?></a>
                    <?php fusion_link_pages(); ?>
                </div>

                <?php
                // Get the post with ID 650
                $post_id = 650;
                $post = get_post($post_id);

                // Check if the post exists
                if ($post) {
                    // Apply content filters to format the content properly
                    $post_content = apply_filters('the_content', $post->post_content);

                    // Display the post content
                    echo $post_content;
                } else {
                    echo 'Post not found.';
                }
                ?>

            </article>
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
    </section>
<?php do_action('avada_after_content'); ?>
<?php
get_footer();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
