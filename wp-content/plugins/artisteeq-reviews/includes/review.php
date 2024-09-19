<?php
global $post;

add_shortcode('print_reviews', 'print_reviews_function');

/**
 * Print Review homepage
 *
 */
function print_reviews_function()
{
    global $post;
    global $wp_query;
    $ppp = 6;


    $args = array(
        'posts_per_page' => $ppp,
        'offset' => 0,
        'orderby' => 'post_date',
        'post_type' => 'review',
        'post_status' => 'publish',
    );

    $the_query = new WP_Query($args);
    ob_start();

    if ($the_query->have_posts()) :
        echo '<div class="reviews-swiper swiper-container swiper">';
        echo '<div class="reviews-wrapper swiper-wrapper">';
        while ($the_query->have_posts()) :
            $the_query->the_post(); ?>
            <div class="review swiper-slide">
                <div class="review-wrapper">
                    <span class="quote"><img src="/wp-content/themes/Avada-Child/assets/images/quote.png"> </span>
                    <div class="review-content">
                        <?php the_content(); ?>
                    </div>
                    <div class="review-name">
                        <p><?php print get_the_title($post->ID); ?></p>
                    </div>
                </div>
            </div>
        <?php

        endwhile;
        echo '</div>';
        echo '<div class="swiper-buttons"><div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div></div>';
        echo '</div>';
        wp_reset_postdata();
        return ob_get_clean();
    endif;
}