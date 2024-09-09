<?php

/*
 * Plugin name: Insufeed Documentcenter with Search
 * Plugin URI: http://www.crmart.be
 * Description: Documentcenter v3
 * Version: 1.0
 * Author: Dieter De Waele
 * Author URI: http://www.crmart.be
 * License: GPL2
 */

if (!class_exists('insu_docusearch')) {
    class insu_docusearch
    {
        protected static $instance = null;

        public static function get_instance()
        {
            if (null === self::$instance) self::$instance = new self;
            return self::$instance;
        }

        public function __construct()
        {
            add_action('init', array(&$this, 'init'));
//			add_filter('query_vars', array(&$this, 'query_vars'));
//			add_action('template_redirect', array(&$this, 'url_rewrite_templates'));
            add_action('wp_ajax_nopriv_docucount', array(&$this, 'url_rewrite_templates'));
            add_action('wp_ajax_docucount', array(&$this, 'url_rewrite_templates'));
            add_action('wp_ajax_nopriv_docusearch', array(&$this, 'url_rewrite_templates'));
            add_action('wp_ajax_docusearch', array(&$this, 'url_rewrite_templates'));

        }

        public function init()
        {
            //Add shortcodes
            add_shortcode('docu_with_search', array(&$this, 'render_docucenter'));

            //Add Javascript
            wp_enqueue_script('script', plugin_dir_url(__FILE__) . 'js/script.js', array('jquery'));

            $translation_array = array(
                'select_partner' => __('You have to select a partner.'),
            );
            wp_localize_script('script', 'script_texts', $translation_array);

            //Create some URL's
//			$this->urls();
        }

        public function render_docucenter()
        {
            $output = '';

            //Get the companies
            $result = Insufeed::query('/insu/documentcompanies');
            $result = json_decode($result);

            $output .= '<div id="documentcenter">';

            $output .= '<form method="post" action="/" id="docusearch_form">';

            $output .= '<div class="documentcenter_searchitem">';
            $output .= '<label for="partner">' . __('Maatschappij') . '</label>';
            $output .= '<select id="partner" name="partner">';
            $output .= '<option value="0">' . __('Kies een maatschappij') . '</option>';

            $companies = get_option('insufeed_documents_companies');

            foreach ($result as $company) {
                if (in_array($company->id, $companies)) {
                    $output .= sprintf('<option value="%d">%s</option>', $company->id, $company->title);
                }
            }
            $output .= '</select>';
            $output .= '</div>';

            //Get the categories
            $result = Insufeed::query('/insu/documentcategories');
            $result = json_decode($result);

            //usort($result,'cmp');

            $output .= '<div class="documentcenter_searchitem">';
            $output .= '<label for="category">' . __('Categorie') . '</label>';
            $output .= '<select id="category" name="category">';
            $output .= '<option value="all">' . __('Kies een categorie') . '</option>';

            foreach ($result as $category) {
                $output .= sprintf('<option value="%d">%s</option>', $category->id, $category->name);
            }
            $output .= '</select>';
            $output .= '</div>';

            //Free text search
            $output .= '<div class="documentcenter_searchitem" id="freesearch_form" style="display: none;">';
            $output .= '<label for="free-search">' . __('Filter resultaten') . '</label>';
            $output .= '<input type="text" name="free-search" id="free-search" />';
            $output .= '</div>';

            $output .= '<button class="btn-main fusion-button" type="submit">' . __('Toon documenten') . '</button>';

            $output .= '</form>';

            $output .= sprintf('<div id="loader" style="display: none;"><img src="%s" /></div>', plugin_dir_url(__FILE__) . 'images/ajax-loader.gif');

            $output .= '<div id="nrresults"></div>';
            $output .= '<div id="results"></div>';

            $output .= '</div>';

            return $output;
        }

        function cmp($a, $b)
        {
            return strcmp($a->title, $b->title);
        }

        function urls()
        {
            add_rewrite_rule('docusearch/?', 'index.php?center_action=docusearch', 'top');
            add_rewrite_rule('docucount/?', 'index.php?center_action=docucount', 'top');
        }

        function query_vars($vars)
        {
            $vars[] = 'center_action';

            return $vars;
        }

        function url_rewrite_templates()
        {
            if ($_POST['action'] == 'docusearch') {
                $partner = $_POST['partner'];
                $category = $_POST['category'];
                $search = $_POST['freesearch'];

                if (is_numeric($partner) && (is_numeric($category) || $category == 'all')) {
                    $locale = get_locale();
                    $locale = explode('_', $locale);
                    $lng = $locale[0];

                    $insufeed = new Insufeed();
                    $result = $insufeed::query('insu/document_2015/' . $lng . '/' . $partner . '/' . $category . '/' . $search);
                    $result = json_decode($result);

                    $output = '';

                    $output .= '<ul>';
                    foreach ($result->documents as $document) {
                        $output .= sprintf('<li><a href="%s" target="_blank">%s</a></li>', $document->url, $document->title);
                    }
                    $output .= '</ul>';


                    print $output;
                    exit;
                }
            }

            if ($_POST['action'] == 'docucount') {
                $partner = $_POST['partner'];
                $category = $_POST['category'];
                $search = $_POST['freesearch'];

                if (is_numeric($partner) && (is_numeric($category) || $category == 'all')) {
                    $locale = get_locale();
                    $locale = explode('_', $locale);
                    $lng = $locale[0];

                    $insufeed = new Insufeed();
                    $result = $insufeed::query('insu/document_2015/' . $lng . '/' . $partner . '/' . $category . '/' . $search);
                    $result = json_decode($result);

                    if (count($result->documents) > 1) {
                        print count($result->documents) . ' ' . __('documenten gevonden.');
                    } else {
                        print count($result->documents) . ' ' . __('documenten gevonden.');
                    }
                    exit;
                }
            }
        }
    }
}

if (class_exists('insu_docusearch')) {
    $InsuDocusearch = new insu_docusearch();
}