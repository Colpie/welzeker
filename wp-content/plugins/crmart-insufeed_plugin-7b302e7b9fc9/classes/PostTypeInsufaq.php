<?php

if (!class_exists('PosttypeInsufaq')) {
  class PostTypeInsufaq {
    const POST_TYPE = 'insufaq';
    protected static $instance = NULL;

    public static function get_instance() {
      if ( null === self::$instance ) self::$instance = new self;
      return self::$instance;
    }

    public function __construct() {
      add_action('init', array(&$this, 'init'));
      add_action('admin_init', array(&$this, 'insufaq_admin'));
    }

    public function init() {
      $this->create_post_type();
      add_action('save_post', array(&$this, 'save_post'));
      $this->create_taxonomy();
    }

    public function create_post_type() {
      register_post_type('insufaq', array(
        'labels' => array(
          //'name' => __(sprintf('%s', ucwords(str_replace("_", " ", self::POST_TYPE)))),
                              //'singular_name' => __(ucwords(str_replace("_", " ", self::POST_TYPE))),
                              'name' => __('Faq', 'insusite'),
                              'singular_name' => __('Faq', 'insusite'),
        ),
        'rewrite' => array('slug' => 'faq'),
        'public' => true,
        'has_archive' => 'frequently-asked-questions',
        'supports' => array( 'title', 'editor', 'thumbnail'),
        'menu_icon' => plugins_url( '../images/insufaq-icon.png', __FILE__ ),
      ));
    }

    public function insufaq_admin() {
      add_action('add_meta_boxes', array(&$this, 'add_meta_boxes'));
    }

    public function add_meta_boxes() {
      add_meta_box(
        'insufaq_remote_id',
        'Remote ID',
        array(&$this, 'insufaq_display_remote_id'),
        'insufaq', 'normal', 'high'
      );
    }

    public function insufaq_display_remote_id($insunews_post) {
      print '<input type="text" name="insufaq_remote_id" id="insufaq_remote_id" value="' . @get_post_meta($insunews_post->ID, 'insufaq_remote_id', true) . '"/>';
    }

    public function create_taxonomy() {
      register_taxonomy( 'faq_cat',
        array('insufaq'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
        array('hierarchical' => true,     /* if this is true, it acts like categories */
          'labels' => array(
            'name' => __( 'Insufaq categorieën', 'wireframe' ), /* name of the custom taxonomy */
            'singular_name' => __( 'Categorie', 'wireframe' ), /* single taxonomy name */
            'search_items' =>  __( 'Zoek categorieën', 'wireframe' ), /* search title for taxomony */
            'all_items' => __( 'Alle categorieën', 'wireframe' ), /* all title for taxonomies */
            'parent_item' => __( 'Parent categorie', 'wireframe' ), /* parent title for taxonomy */
            'parent_item_colon' => __( 'Parent categorie:', 'wireframe' ), /* parent taxonomy title */
            'edit_item' => __( 'Bewerk categorie', 'wireframe' ), /* edit custom taxonomy title */
            'update_item' => __( 'Bewerk categorie', 'wireframe' ), /* update title for taxonomy */
            'add_new_item' => __( 'Nieuwe categorie', 'wireframe' ), /* add new title for taxonomy */
            'new_item_name' => __( 'Nieuwe categorie naam', 'wireframe' ) /* name title for taxonomy */
          ),
          'show_admin_column' => true,
          'show_ui' => true,
          'query_var' => true,
          'rewrite' => array( 'slug' => 'faqcenter' ),
        )
      );
    }

    /*public function use_latest_wpml_object_function(){

      $plugin_path = ABSPATH . 'wp-content/plugins/sitepress-multilingual-cms/sitepress.php';
      $wpml_plugin_info = get_plugin_data( $plugin_path );

      $current_wpml_version = $wpml_plugin_info['Version'];

      $version_splitted = explode('.', $current_wpml_version);

      if( ( $version_splitted[0] >= 3 ) && ( $version_splitted[1] >= 2) ){

        return true;

      }else{

        return false;

      }

    }*/

    public function import() {
      var_dump('START FAQ');

      //wpml sitepress
      global $sitepress;

            $Insufeed = Insufeed::get_instance();

            //Import the tips categories
            $categories = json_decode($Insufeed::query('insu/faq/categories'));

            foreach ($categories as $category) {
                $name = $category->title;

                if (!term_exists($name, 'faq_cat')) {
                    wp_insert_term(
                        $name,
                        'faq_cat'
                    );
                }
            }

      global $user_ID;
      $items = json_decode($Insufeed::query('insu/faq'));

      foreach ($items as $item) {
        print $item->id . "\n";

        $french = (int)get_option('insufeed_french');
        if (($french == 1 && $item->lng != 'fr') || $french == 0) {
          if (function_exists('icl_object_id') && get_option('insufeed_french') != 1) {
            $sitepress->switch_lang($item->lng);
          }

          $new_post = array(
            'post_title' => $item->title,
            'post_content' => $item->body,
            'post_status' => 'publish',
            'post_date' => date('Y-m-d H:i:s', $item->created),
            'post_author' => $user_ID,
            'post_type' => self::POST_TYPE,
            'post_category' => array(0)
          );

          $sw_args = array(
            'post_type' => 'insufaq',
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'meta_query' => array(
              array(
                'key' => 'insufaq_remote_id',
                'value' => $item->id,
              )
            ),
            'suppress_filters' => TRUE,
          );

          $query = new WP_Query($sw_args);

          if ($query->have_posts()) {
            while ($query->have_posts()) {
              $query->the_post();
              $new_post['ID'] = get_the_ID();

              $_POST['icl_post_language'] = $language_code = $item->lng;

              wp_update_post($new_post);

              $post_id = get_the_ID();

              //in case posts are in the wrong language, run this code to change the language of the post
              if (function_exists('icl_object_id') && get_option('insufeed_french') != 1) {
                $def_trid = $sitepress->get_element_trid($post_id);
                $sitepress->set_element_language_details($post_id, 'post_insufaq', $def_trid, $language_code);
              }

            }
          }
          else {
            var_dump('not found: ' . $item->id);

            //for older versions than wpml 3.2
            $_POST['icl_post_language'] = $language_code = $item->lng;

            $post_id = wp_insert_post($new_post);

            //change language of the new post:
            if (function_exists('icl_object_id') && get_option('insufeed_french') != 1) {
              $def_trid = $sitepress->get_element_trid($post_id);
              $sitepress->set_element_language_details($post_id, 'post_insufaq', $def_trid, $language_code);
            }
          }

          update_post_meta($post_id, 'insufaq_remote_id', $item->id);

          // assign term to post
          // check if the term exists in the categories, this will return an array with term id & tax id when true
          $term = term_exists($item->cat, 'faq_cat');

          if (!is_null($term)) {
            // get the term id of the correct language

            if (function_exists('icl_object_id') && get_option('insufeed_french') != 1) {
              $new_term_id = icl_object_id($term['term_id'], 'category', TRUE, $language_code); //this function is actually deprecated since WPML 3.2, below is the new function
              wp_set_post_terms($post_id, array($new_term_id), 'faq_cat');
            } else {
              wp_set_post_terms($post_id, array($term['term_id']), 'faq_cat');
            }
            //$new_term_id = apply_filters( 'wpml_object_id', $term['term_id'], 'category', FALSE, $language_code );

            // assign term id to post


          }
        }
      }
    }

    public function save_post($post_id) {
      if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
      }

      if (isset($_POST['post_type']) && $_POST['post_type'] == 'insufaq' && current_user_can('edit_post', $post_id)) {
        $metafields = $this->_meta();

        foreach($metafields as $field_name) {
          // Update the post's meta field
          update_post_meta($post_id, $field_name, $_POST[$field_name]);
        }
      }
    }

    private function _meta() {
      return array('insufaq_remote_id');
    }
  }
}

if (class_exists('PostTypeInsufaq') && get_option('insufeed_faq') == 1) {
  $PostTypeInsufaq = new PostTypeInsufaq();
}