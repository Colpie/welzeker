<?php

if (!class_exists('InsuActions')) {
	class InsuActions {

		public function __construct() {
			$this->init();
		}

		public function init() {
			add_shortcode('actions_lv', array(&$this, 'large_vertical'));
	    	add_shortcode('actions_sv', array(&$this, 'small_vertical'));
	    	add_shortcode('actions_lh', array(&$this, 'large_horizontal'));
	    	add_shortcode('actions_sh', array(&$this, 'small_horizontal'));
		}

		public function large_vertical() {
      return $this->render_action('lv');
		}

		public function small_vertical() {
			return $this->render_action('sv');
    }

    public function large_horizontal() {
      return $this->render_action('lh');
    }

    public function small_horizontal() {
      return $this->render_action('sh');
    }

    private function render_action($format) {
	    $actions = $this->retreiveActions($format);
	    $templateFound = FALSE;

	    if (!empty($actions)) {
		    //Try most detailed template like 'insuaction_sv.php'
		    $located = locate_template( 'insuactions_' . $format . '.php' );

		    if ( empty( $located ) ) {
			    //If most detailed is not found, try to find the global insuactions.php
			    $located = locate_template( 'insuactions.php' );

			    if ( !empty( $located ) ) {
				    $templateFound = 'insuactions.php';
			    }
		    } else {
			    $templateFound = 'insuactions_' . $format . '.php';
		    }

		    //A template was found during the code above, render this template
		    if ($templateFound) {
			    ob_start();
			    include(locate_template($templateFound));
			    $res = ob_get_contents();
			    ob_end_clean();
			    return $res;
		    }

		    //If none of the templates above are found, just render default output
        $output = '';
        $output .= '<div class="actions">';

        foreach ($actions as $action) {

          $output .= '<div class="banner">';
          $output .= '<div class="fusion-row title-row">';
//            $output .= sprintf('<div class="image"><a href="%s"><img src="%s" /></a></div>', $action->link, $action->image);
            $output .= sprintf('<h2 class="actiontitle">%s</h2>', $action->title);
            $output .= '</div>';
            $output .= sprintf('<div class="image"><div class="%s"><img src="%s" /></div></div>', $action->link, $action->image);

            if (!empty($action->subtitle)) {
              $output .= sprintf('<h3 class="actionsubtitle">%s</h3>', $action->subtitle);
            }

//            $output .= sprintf('<div class="readmore"><a href="%s" target="_blank">%s</a></div>', $action->link, __('Read more'));

          $output .= '</div>';
        }

        $output .= '</div>';

        return $output;
	    }
    }

		private function retreiveActions($format) {
			$language = get_locale();
			$language = explode('_', $language);
			$language_code = $language[0];

			if ($language_code != 'nl' && $language_code != 'fr') {
				$language_code = 'nl';
			}

			$Insufeed = Insufeed::get_instance();

            $sitetype = (get_option('insufeed_sitetype'));
            if (isset($sitetype) && $sitetype == 1) $actions = json_decode($Insufeed::query('medi/acties/' . $language_code . '/' . $format));
			else $actions = json_decode($Insufeed::query('insu/acties/' . $language_code . '/' . $format));

            return $actions;
		}
	}
}

if (class_exists('InsuActions')) {
	$InsuActions = new InsuActions();
}