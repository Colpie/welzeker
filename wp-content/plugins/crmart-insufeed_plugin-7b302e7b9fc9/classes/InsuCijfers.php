<?php

if (!class_exists('InsuCijfers')) {
  class InsuCijfers {

    public function __construct() {
      $this->init();
    }

    public function init() {
      add_shortcode('insucijfers_cijfers_indexen', array(&$this, 'render'));
    }

    public static function getCijfers() {
      $Insufeed = Insufeed::get_instance();
      $items = json_decode($Insufeed::query('insu/cijfers'));

      return $items;
    }

    public function render() {
      $items = $this->getCijfers();

      ob_start();

      foreach ($items as $item) {
        echo '<div class="cijfers_block">';
          echo '<h2>' . $item->title . '</h2>';
          echo '<div class="cijfers_content">';
            echo $item->body;
          echo '</div>';
        echo '</div>';

      }

      return ob_get_clean();
    }

  }
}

if (class_exists('InsuCijfers')) {
  $InsuCijfers = new InsuCijfers();
}