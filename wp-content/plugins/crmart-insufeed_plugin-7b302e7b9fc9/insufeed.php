<?php
/*
Plugin Name: Insufeed
Plugin URI: http://www.crmart.be
Description: Integrate Insufeed
Version: 1.0.1
Author: Dieter De Waele
Author URI: https://www.crmart.be
License: GPL2
*/

if (!class_exists('Insufeed')) {

	class Insufeed {
		protected static $instance = NULL;

		public static function get_instance() {
			if (null === self::$instance) self::$instance = new self;
			return self::$instance;
		}

		public function __construct() {
			add_action('admin_init', array(
				&$this,
				'admin_init'
			));
			add_action('admin_menu', array(
				&$this,
				'add_menu'
			));

			define('INSUFEED_CACHE_DIR', WP_CONTENT_DIR . '/insufeed_cache');
			$this->create_cache();

			register_activation_hook(__FILE__, array(
				$this,
				'activate'
			));
			register_deactivation_hook(__FILE__, array(
				$this,
				'deactivate'
			));
		}

		public function admin_init() {
			$this->init_settings();
		}

		private function create_cache() {
			$dir = INSUFEED_CACHE_DIR;

			if (is_dir($dir)) {
				return false;
			}

			$dirs = explode('/', $dir);
			$curr_path = '';

			foreach ($dirs as $dir) {
				if ($dir == '') {
					continue;
				}

				($curr_path == '') ? $curr_path = '/' . $dir : $curr_path .= '/' . $dir;

				if (!@is_dir($curr_path)) {
					if (@mkdir($curr_path, 0777)) {
						return true;
					}
				}
			}

			return false;
		}

		public function init_settings() {
			// insusite options
			register_setting('insusite-group', 'insusite_office_name');
			register_setting('insusite-group', 'insusite_office_about');
			register_setting('insusite-group', 'insusite_email');
			register_setting('insusite-group', 'insusite_street');
			register_setting('insusite-group', 'insusite_street_number');
			register_setting('insusite-group', 'insusite_zip');
			register_setting('insusite-group', 'insusite_city');
			register_setting('insusite-group', 'insusite_country');
			register_setting('insusite-group', 'insusite_fsma');
			register_setting('insusite-group', 'insusite_ondernemingsnummer');
			register_setting('insusite-group', 'insusite_rpr_locatie');
			register_setting('insusite-group', 'insusite_rpr_afdeling');
			register_setting('insusite-group', 'insusite_type_makelaar');
			register_setting('insusite-group', 'insusite_phone');
			register_setting('insusite-group', 'insusite_polisnummer');
			register_setting('insusite-group', 'insusite_registratienummer');
			register_setting('insusite-group', 'insusite_facebook');
			register_setting('insusite-group', 'insusite_linkedin');
			register_setting('insusite-group', 'insusite_instagram');

			// Openings houres
            register_setting('insusite-group', 'ma_vm');
            register_setting('insusite-group', 'ma_nm');
            register_setting('insusite-group', 'di_vm');
            register_setting('insusite-group', 'di_nm');
            register_setting('insusite-group', 'wo_vm');
            register_setting('insusite-group', 'wo_nm');
            register_setting('insusite-group', 'do_vm');
            register_setting('insusite-group', 'do_nm');
            register_setting('insusite-group', 'vr_vm');
            register_setting('insusite-group', 'vr_nm');
            register_setting('insusite-group', 'za_vm');
            register_setting('insusite-group', 'za_nm');

            register_setting('insusite-group', 'k2_ma_vm');
            register_setting('insusite-group', 'k2_ma_nm');
            register_setting('insusite-group', 'k2_di_vm');
            register_setting('insusite-group', 'k2_di_nm');
            register_setting('insusite-group', 'k2_wo_vm');
            register_setting('insusite-group', 'k2_wo_nm');
            register_setting('insusite-group', 'k2_do_vm');
            register_setting('insusite-group', 'k2_do_nm');
            register_setting('insusite-group', 'k2_vr_vm');
            register_setting('insusite-group', 'k2_vr_nm');
            register_setting('insusite-group', 'k2_za_vm');
            register_setting('insusite-group', 'k2_za_nm');

			// insufeed options
			register_setting('insufeed-group', 'insufeed_sitetype');
			register_setting('insufeed-group', 'insufeed_url');
			register_setting('insufeed-group', 'insufeed_public_key');
			register_setting('insufeed-group', 'insufeed_private_key');
			register_setting('insufeed-group', 'insufeed_news');
			register_setting('insufeed-group', 'insufeed_french');
			register_setting('insufeed-group', 'insufeed_tips');
			register_setting('insufeed-group', 'insufeed_links');
			register_setting('insufeed-group', 'insufeed_faq');
			register_setting('insufeed-group', 'insufeed_documentcenter');
			register_setting('insufeed-group', 'insufeed_documents_companies');
			register_setting('insufeed-group', 'insufeed_vet_categories');
			register_setting('insufeed-group', 'insufeed_overwrite');

			// Popup settings
            register_setting('insusite-group', 'insu_popup_active');
            register_setting('insusite-group', 'insu_popup_title');
            register_setting('insusite-group', 'insu_popup_text');
            register_setting('insusite-group', 'insu_popup_cta');
            register_setting('insusite-group', 'insu_popup_cta_link');
		}

		public function add_menu() {
			add_options_page('Insufeed settings', 'Insufeed Settings', 'manage_options', 'insufeed', array(
				&$this,
				'settings_page'
			));
		}

		public function settings_page() {
			include sprintf('%s/templates/settings.php', dirname(__FILE__));
		}

        public static function query($method) {
            if ($method == 'insu/companies' && is_file(INSUFEED_CACHE_DIR . '/companies.json')) {
                return file_get_contents(INSUFEED_CACHE_DIR . '/companies.json');
            }

            if (strpos($method, 'acties') !== false) {
                $file = INSUFEED_CACHE_DIR . '/' . str_replace('/', '_', $method) . '.json';
                if (is_file($file) && filectime($file) > (time() - 24 * 60 * 60)) {
                    return file_get_contents($file);
                }
            }

            if ($method == 'insu/documentcategories' && is_file(INSUFEED_CACHE_DIR . '/document_categories.json')) {
                return file_get_contents(INSUFEED_CACHE_DIR . '/document_categories.json');
            }

            $base_url = get_option('insufeed_url');

            // Vervanging van get_headers() → veilige cURL-check
            $ch = curl_init($base_url);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // TRUE in productie!
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $ssl_error = curl_error($ch);
            curl_close($ch);

            if ($http_code == 301) {
                $base_url = remove_http_from_url($base_url);
                $base_url = 'https://' . $base_url;
            } elseif ($http_code === 0) {
                error_log("⚠️ SSL fout bij ophalen headers van $base_url: $ssl_error");
                return false;
            }

            $url = $base_url . $method;
            $query = array(
                'client_id' => get_option('insufeed_public_key'),
                'secret'    => get_option('insufeed_private_key'),
            );
            $url .= '?' . http_build_query($query);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // TRUE in productie
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);

            if (ini_get('open_basedir') == '') {
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            }

            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            $contents = curl_exec($ch);
            $fetch_error = curl_error($ch);
            curl_close($ch);

            if ($contents) {
                if ($method == 'insu/companies') {
                    file_put_contents(INSUFEED_CACHE_DIR . '/companies.json', $contents);
                }

                if ($method == 'insu/documentcategories') {
                    file_put_contents(INSUFEED_CACHE_DIR . '/document_categories.json', $contents);
                }

                if (strpos($method, 'acties') !== false) {
                    $file = INSUFEED_CACHE_DIR . '/' . str_replace('/', '_', $method) . '.json';
                    file_put_contents($file, $contents);
                }

                return $contents;
            } else {
                error_log("❌ Ophalen van inhoud voor $method mislukt. Fout: $fetch_error");
            }

            return false;
        }

        public static function activate() {
			wp_schedule_event(time(), 'hourly', 'insufeed_cron');
		}

		public static function deactivate() {
			wp_clear_scheduled_hook('insufeed_cron');
		}
	}

	if (class_exists('Insufeed')) {
		$Insufeed = Insufeed::get_instance();
		add_action('insufeed_cron', 'insufeed_run_cron');
	} else {
		$Insufeed = new Insufeed();
		add_action('insufeed_cron', 'insufeed_run_cron');
	}

	function insufeed_run_cron() {
		if (get_option('insufeed_news') == 1) {
			$PostTypeInsufeed = PostTypeInsufeed::get_instance();
			$PostTypeInsufeed->import();
		}

		if (get_option('insufeed_tips') == 1) {
			$PostTypeInsutip = PostTypeInsutip::get_instance();
			$PostTypeInsutip->import();
		}

		if (get_option('insufeed_links') == 1) {
			$PostTypeInsulinks = PostTypeInsulinks::get_instance();
			$PostTypeInsulinks->import();
		}

		if (get_option('insufeed_faq') == 1) {
			$PostTypeInsufaq = PostTypeInsufaq::get_instance();
			$PostTypeInsufaq->import();
		}

		/* Rewrite insufeed cache companies */
        if (is_file(INSUFEED_CACHE_DIR . '/companies.json')) {
            print 'Rewrite insufeed cache companies' . PHP_EOL;

            $base_url = get_option('insufeed_url');

            // Gebruik cURL in plaats van get_headers() om SSL-verificatieproblemen op te vangen
            $ch = curl_init($base_url);
            curl_setopt($ch, CURLOPT_NOBODY, true); // Alleen headers ophalen
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Houd TRUE voor productie
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $result = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $ssl_error = curl_error($ch);
            curl_close($ch);

            if ($http_code == 301) {
                $base_url = remove_http_from_url($base_url);
                $base_url = 'https://' . $base_url;
            } elseif ($http_code === 0) {
                error_log("⚠️ SSL fout bij ophalen headers van $base_url: $ssl_error");
                return; // Stop verdere verwerking
            }

            // Voeg query toe
            $url = $base_url . 'insu/companies';
            $query = array(
                'client_id' => get_option('insufeed_public_key'),
                'secret' => get_option('insufeed_private_key'),
            );
            $url .= '?' . http_build_query($query);

            // Ophalen via cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Voor productie
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);

            if (ini_get('open_basedir') == '') {
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            }

            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            $contents = curl_exec($ch);
            $fetch_error = curl_error($ch);
            curl_close($ch);

            if ($contents) {
                $fh = fopen(INSUFEED_CACHE_DIR . '/companies.json', 'w+');
                fwrite($fh, $contents);
                fclose($fh);
            } else {
                error_log("❌ Ophalen van bedrijven mislukt voor URL: $url - fout: $fetch_error");
            }
        }

        /* WPFC cache clear after sync */
		if (isset($GLOBALS['wp_fastest_cache']) && method_exists($GLOBALS['wp_fastest_cache'], 'deleteCache')) {
			print 'Delete WPFC cache' . PHP_EOL;
			$GLOBALS['wp_fastest_cache']->deleteCache(true);
		}
	}

	/* Function to remove http:// or https:// from insufeed_url */
	function remove_http_from_url($url) {
		$disallowed = array(
			'http://',
			'https://'
		);
		foreach ($disallowed as $d) {
			if (strpos($url, $d) === 0) {
				return str_replace($d, '', $url);
			}
		}
		return $url;
	}
}

include sprintf('%s/classes/PostTypeInsufeed.php', dirname(__FILE__));
include sprintf('%s/classes/PostTypeInsutip.php', dirname(__FILE__));
include sprintf('%s/classes/PostTypeInsulinks.php', dirname(__FILE__));
include sprintf('%s/classes/PostTypeInsufaq.php', dirname(__FILE__));
include sprintf('%s/classes/InsuActions.php', dirname(__FILE__));
include sprintf('%s/classes/InsuWidgets.php', dirname(__FILE__));
include sprintf('%s/classes/InsuDocumentcenter.php', dirname(__FILE__));
include sprintf('%s/classes/InsuCijfers.php', dirname(__FILE__));
include sprintf('%s/classes/InsuAssur.php', dirname(__FILE__));
include sprintf('%s/classes/InsuLegal.php', dirname(__FILE__));

require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker('https://bitbucket.org/crmart/insufeed_plugin', __FILE__, 'insufeed-plugin');
$myUpdateChecker->setAuthentication(array( 'consumer_key' => 'SumdK59r9wbZYc98Fz', 'consumer_secret' => 'W2Jgnnk4jUuvdDL3WDKqyh65hf3Epmhk', ));
$myUpdateChecker->setBranch('master');