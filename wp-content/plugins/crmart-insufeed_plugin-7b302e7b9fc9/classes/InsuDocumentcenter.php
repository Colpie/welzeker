<?php

if (!class_exists('InsuDocumentcenter')) {
    class InsuDocumentcenter
    {
        protected static $instance = NULL;

        public function __construct()
        {
            add_action('init', array(&$this, 'urls'));
            add_filter('query_vars', array(&$this, 'query_vars'));
            add_action('template_redirect', array(&$this, 'url_rewrite_templates'));

        }

        public static function get_instance()
        {
            if (null === self::$instance) self::$instance = new self;
            return self::$instance;
        }

        public static function getCompanies()
        {
            $Insufeed = Insufeed::get_instance();
            $contents = $Insufeed->query('insu/companies');

            $items = json_decode($contents);
            $companies = array();

            foreach ($items as $item) {
                $companies[$item->id] = array(
                    'title' => $item->title,
                    'logo' => $item->logo,
                    'url' => $item->url
                );
            }

            return $companies;
        }

        public static function getCategories()
        {
            $Insufeed = Insufeed::get_instance();
            $items = json_decode($Insufeed::query('insu/documentcategories'));

            return $items;
        }

        public static function getCategoriesAsLinks($categoriesData)
        {
            //Retrieve needed data
            $activeId = get_query_var('list_action');
            $links = array();

            foreach ($categoriesData as $category) {
                $encoded = urlencode(strtolower($category->name));
                $encoded = str_replace('+', '_', $encoded);

                if (defined('ICL_LANGUAGE_CODE')) {
                    $language = ICL_LANGUAGE_CODE;
                    if ($language == 'nl') {
                        $language = '';
                    } else {
                        $language .= '/';
                    }
                } else {
                    $language = '';
                }

                $categoryname = __($category->name);

                if ($activeId != $category->id) {
                    $links['items'][] = "<li><a href='/{$language}documentcenter/{$encoded}'>{$categoryname}</a></li>";
                } else {
                    $links['items'][] = "<li class='active'><a href='/{$language}documentcenter/{$encoded}'>{$categoryname}</a></li>";
                    $links['activeTitle'] = $category->name;
                }
            }

            return $links;
        }

        public static function getDocuments($category, $companies)
        {
            $language = get_locale();
            $language = explode('_', $language);
            $language_code = $language[0];

            if ($language_code != 'nl' && $language_code != 'fr') {
                $language_code = 'nl';
            }

            $Insufeed = Insufeed::get_instance();
            $contents = $Insufeed->query("insu/documents/{$language_code}/{$category}/{$companies}");

            return json_decode($contents);
        }

        function urls()
        {
            $categories = $this->getCategories();

            foreach ($categories as $category) {
                $encoded = urlencode(strtolower($category->name));
                $encoded = str_replace('+', '_', $encoded);

                add_rewrite_rule(
                    'documentcenter/' . $encoded . '/?',
                    'index.php?center_action=documentcenter&list_action=' . $category->id,
                    'top'
                );
            }

            /*add_rewrite_rule(
                'documentcenter/?',
                'index.php?center_action=documentcenter',
                'top'
            );*/

            add_rewrite_rule(
                'partners/?',
                'index.php?center_action=partners',
                'top'
            );
        }

        function query_vars($vars)
        {
            $vars[] = 'center_action';
            $vars[] = 'list_action';
            return $vars;
        }

        function url_rewrite_templates()
        {
            if (get_query_var('center_action') == 'documentcenter') {
                $list_action = get_query_var('list_action');

                if (!empty($list_action)) {
                    add_filter('template_include', function () {
                        if (file_exists(get_template_directory() . '/documentcenter_detail.php')) {
                            return get_template_directory() . '/documentcenter_detail.php';
                        } else {
                            return sprintf('%s/../templates/documentcenter_detail.php', dirname(__FILE__));
                        }
                    });
                }
            }

            if (get_query_var('center_action') == 'partners') {
                add_filter('template_include', function () {
                    if (file_exists(get_template_directory() . '/partners.php')) {
                        return get_template_directory() . '/partners.php';
                    } else {
                        return sprintf('%s/../templates/partners.php', dirname(__FILE__));
                    }
                });
            }
        }
    }
}

if (class_exists('InsuDocumentcenter') && get_option('insufeed_documentcenter')) {
    $InsuDocumentcenter = new InsuDocumentcenter();
}