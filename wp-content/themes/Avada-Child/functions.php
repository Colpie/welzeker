<?php

function theme_enqueue_styles()
{
    wp_enqueue_style('avada-parent-stylesheet', get_template_directory_uri() . '/style.css');

    //child styles and scripts
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/css/child.css');
    wp_enqueue_style('splitting-style', get_stylesheet_directory_uri() . '/css/splitting-cells.css');
    wp_enqueue_style('swiper-style', get_stylesheet_directory_uri() . '/css/swiper-style.css');


    wp_enqueue_script( 'child-script', get_stylesheet_directory_uri() . '/assets/js/child.js', array(), '1.0.0', true );
    wp_enqueue_script('scrollspy', get_stylesheet_directory_uri() . '/assets/js/scrollspy.js', array(), '1.0.0', true);
    wp_enqueue_script('splitting-script', get_stylesheet_directory_uri() . '/assets/js/splitting.js', array(), '1.0.0', true);


    // Import WOW
    wp_enqueue_script('wow', get_stylesheet_directory_uri() . '/assets/js/wow.min.js', array('jquery'));

    // Bootstrap
    wp_enqueue_style('bootstrap', get_stylesheet_directory_uri() . '/css/bootstrap/bootstrap.min.css');
//    wp_enqueue_script('boostrap-js', get_stylesheet_directory_uri() . '/assets/js/bootstrap/bootstrap.min.js', array('jquery'));

    // Font awesome
    wp_enqueue_style('font-awesome', get_stylesheet_directory_uri() . '/css/fontawesome/css/light.css');

    // Swiper slider js
    // wp_enqueue_script('swiper-script', get_stylesheet_directory_uri() . '/assets/js/swiper_custom.js', array(), '1.0.0', true);
    wp_enqueue_script('swiper', get_stylesheet_directory_uri() . '/assets/js/swiper-bundle.min.js', array(), '1.0.0', true);

    wp_enqueue_script('forms-script', get_stylesheet_directory_uri() . '/assets/js/forms.js', array(), '1.0.0', true);
}

add_action('wp_enqueue_scripts', 'theme_enqueue_styles', 99);

// Requires
require_once  'assets/includes/insu_shortcodes.php';
require_once  'assets/includes/openings_small.php';
require_once  'templates/_shortcodes.php';

/**
 * Filter body classes
 */
// Add page slug as body class

function add_slug_body_class($classes)
{
    global $post;
    if (isset($post)) {
        $classes[] = $post->post_type . '-' . $post->post_name;
    }

    if (is_single()) {
        foreach ((get_the_category($post->ID)) as $category) {
            // add category slug to the $classes array
            $classes[] = $category->category_nicename;
        }
    }

    return $classes;
}

add_filter('body_class', 'add_slug_body_class');

function option_schade()
{
    ob_start();
    include "templates/schade.php";
    return ob_get_clean();
}

add_shortcode('print_option_schade', 'option_schade');

function redirecting_404_to_home()
{
    if (is_404())
    {
        wp_safe_redirect(site_url());
        exit();
    }
};
add_action('template_redirect', 'redirecting_404_to_home');

function my_login_logo_one() {
    ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url(/wp-content/themes/Avada-Child/assets/images/login/artisteeq.gif);
            height: 194px;
            width: 250px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            padding-bottom: 30px;
        }
    </style>
    <?php
} add_action( 'login_enqueue_scripts', 'my_login_logo_one' );

function custom_login_page_background() {
    echo '<style type="text/css">
        body.login {
            background-color: #FFBF00;
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>';
}
add_action('login_enqueue_scripts', 'custom_login_page_background');
