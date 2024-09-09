<?php
/**
 * Plugin Name: CRM.art cookie plugin
 * Plugin URI: http://www.crmart.be
 * Description: Cookie popup plugin.
 * Version: 1.0.4
 * Author: CRM.art
 * Author URI: http://www.crmart.be
 */

function enqueue_scripts()
{
    wp_enqueue_style('crmart-cookies-stylesheet', plugin_dir_url(__FILE__) . 'includes/css/crmart-cookies.css');
    wp_enqueue_script('crmart-cookies-script', plugin_dir_url(__FILE__) . '/includes/js/crmart-cookies.js', array('jquery'));
}

add_action('wp_enqueue_scripts', 'enqueue_scripts');

function display_cookie_popup()
{
    $cookie = isset($_COOKIE["crmart_cookies"]) ? $_COOKIE['crmart_cookies'] : '';

//    if ($cookie !== 'agreed') {
        include('partials/cookies-display.php');
//    }
}

add_action('wp_footer', 'display_cookie_popup');

require 'plugin-update-checker/plugin-update-checker.php';
$MyUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://crmart.be/wp-update-server/?action=get_metadata&slug=crmart-cookies', //Metadata URL.
    __FILE__, //Full path to the main plugin file.
    'crmart-cookies' //Plugin slug. Usually it's the same as the name of the directory.
);