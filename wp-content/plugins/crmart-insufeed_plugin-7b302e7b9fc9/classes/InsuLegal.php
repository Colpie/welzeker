<?php

if (!class_exists('InsuLegal')) {
    class InsuLegal {

        public function __construct() {
            $this->init();
        }

        public function init() {
            add_shortcode('insu_legal_content', array(&$this, 'get_legal_content'));

            // content shortcodes
            add_shortcode('insu_site_name', array(&$this, 'get_site_name'));
            add_shortcode('insu_site_email', array(&$this, 'get_site_email'));
            add_shortcode('insu_street_nr', array(&$this, 'get_street_nr'));
            add_shortcode('insu_zip_city', array(&$this, 'get_zip_city'));
            add_shortcode('insu_country', array(&$this, 'get_country'));
            add_shortcode('insu_fsma', array(&$this, 'get_fsma'));
            add_shortcode('insu_rpr', array(&$this, 'get_rpr'));
            add_shortcode('insu_ondernemingsnummer', array(&$this, 'get_ondernemingsnummer'));

            add_shortcode('medi_polisnummer', array(&$this, 'get_polisnummer'));
            add_shortcode('medi_registratienummer', array(&$this, 'get_registratienummer'));
        }

        public function get_legal_content($atts) {

            $a = shortcode_atts( array(
                'page_type' => null,
                'lang' => null,
            ), $atts );

            if ($a['lang'] == null) {
                // check if wpml language is defined, else set lang to 'nl'
                if (defined('ICL_LANGUAGE_CODE')) {
                    $a['lang'] = ICL_LANGUAGE_CODE;
                } else {
                    $a['lang'] = 'nl';
                }
            }

            if ($a['page_type'] != null) {

                $lng = $a['lang'];

                if (get_option('insufeed_sitetype') == 1) {
                    $site_type = 'dierenarts';
                } else {
                    $site_type = 'verzekering';
                }

                $cms = 'wordpress';

                $page_type = $a['page_type'];

                $Insufeed = Insufeed::get_instance();

                $legal_content = json_decode($Insufeed::query('/insu/legal/' . $lng . '/' . $site_type . '/' . $cms . '/' . $page_type));

                return do_shortcode($legal_content);

            } else {

                return __('Cant retrieve the content, please define which content should be retrieved.');

            }

        }

        public function get_site_name() {

            return get_bloginfo('name');

        }

        public function get_site_email() {

            return get_option('insusite_email');

        }

        public function get_street_nr() {

            return get_option('insusite_street') . ' ' . get_option('insusite_street_number');

        }

        public function get_zip_city() {

            return get_option('insusite_city') . ' ' . get_option('insusite_zip');

        }

        public function get_country() {

            return get_option('insusite_country');

        }

        public function get_fsma() {

            return get_option('insusite_fsma');

        }

        public function get_rpr() {

            return get_option('insusite_rpr_locatie') . ', ' . get_option('insusite_rpr_afdeling');

        }

        public function get_ondernemingsnummer() {

            return get_option('insusite_ondernemingsnummer');

        }

        public function get_polisnummer() {

            return get_option('insusite_polisnummer');

        }

        public function get_registratienummer() {

            return get_option('insusite_registratienummer');

        }

    }
}

if (class_exists('InsuLegal')) {
    $InsuLegal = new InsuLegal();
}