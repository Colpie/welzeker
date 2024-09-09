<?php

if (!class_exists('InsuAssur')) {
  class InsuAssur {

    public function __construct() {
      $this->init();
    }

    public function init() {
      add_shortcode('assur_nl', array(&$this, 'render_nl'));
      add_shortcode('assur_fr', array(&$this, 'render_fr'));
    }

    protected function getAssur($lng) {
      $Insufeed = Insufeed::get_instance();
      $content = $Insufeed::query('insu/assur_' . $lng);

      return $content;
    }

    public function render_nl() {
      $content = $this->getAssur('nl');
      return $content;
    }

    public function render_fr() {
      $content = $this->getAssur('fr');
      return $content;
    }
  }
}

if (class_exists('InsuAssur')) {
  $InsuAssur = new InsuAssur();
}