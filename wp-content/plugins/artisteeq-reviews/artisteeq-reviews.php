<?php
/*
Plugin Name: Artisteeq Reviews Plugin
Plugin URI: https://artisteeq.be/
Description: A custom reviews plugin for WordPress.
Version: 1.0.0
Author: Thomas Colpaert
Author URI: https://ctgraphics.be
*/

function enqueue_script_reviews()
{
    wp_enqueue_script('jquery');
    wp_enqueue_script('artisteeq-reviews-script', plugin_dir_url(__FILE__) . '/includes/js/script.js', array('jquery'), '1.0', true);

    wp_localize_script('artisteeq-reviews-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));

}

add_action('wp_enqueue_scripts', 'enqueue_script_reviews');


require_once 'includes/review.php';

function custom_review_plugin_register_post_type()
{
    $labels = array(
        'name' => 'Reviews',
        'singular_name' => 'Review',
        'menu_name' => 'Reviews',
        'name_admin_bar' => 'Review',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Review',
        'new_item' => 'New Review',
        'edit_item' => 'Edit Review',
        'view_item' => 'View Review',
        'all_items' => 'All Reviews',
        'search_items' => 'Search Reviews',
        'parent_item_colon' => 'Parent Reviews:',
        'not_found' => 'No Reviews found.',
        'not_found_in_trash' => 'No Reviews found in Trash.'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'reviews'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'thumbnail')
    );

    register_post_type('review', $args);
}

add_action('init', 'custom_review_plugin_register_post_type');
