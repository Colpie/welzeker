<?php

if (!class_exists('PostTypeInsulinks')) {
  class PostTypeInsulinks {
    const POST_TYPE = 'insulinks';
    protected static $instance = NULL;

    public static function get_instance() {
      if ( null === self::$instance ) self::$instance = new self;
      return self::$instance;
    }

    public function __construct() {
      add_action('init', array(&$this, 'init'));
      add_action('admin_init', array(&$this, 'insulinks_admin'));
    }

    public function init() {
      $this->create_post_type();
      add_action('save_post', array(&$this, 'save_post'));
      $this->create_taxonomy();
    }

    public function create_post_type() {
      register_post_type('insulinks', array(
        'labels' => array(
          'name' => __(sprintf('%s', ucwords(str_replace("_", " ", self::POST_TYPE)))),
          'singular_name' => __(ucwords(str_replace("_", " ", self::POST_TYPE))),
        ),
        'rewrite' => array('slug' => 'link'),
        'public' => true,
        'has_archive' => false,
        'supports' => array( 'title'),
        'menu_icon' => plugins_url( '../images/insulinks-icon.png', __FILE__ ),
      ));
    }

    public function insulinks_admin() {
      add_action('add_meta_boxes', array(&$this, 'add_meta_boxes'));
    }

    public function add_meta_boxes() {
      add_meta_box(
        'insulinks_link',
        'Link',
        array(&$this, 'insulinks_display_link'),
        'insulinks', 'normal', 'high'
      );

      add_meta_box(
        'insulinks_remote_id',
        'Remote ID',
        array(&$this, 'insulinks_display_remote_id'),
        'insulinks', 'normal', 'high'
      );
    }

    public function insulinks_display_remote_id($insunews_post) {
      print '<input type="text" name="insulinks_remote_id" id="insulinks_remote_id" value="' . @get_post_meta($insunews_post->ID, 'insulinks_remote_id', true) . '"/>';
    }

    public function insulinks_display_link($insunews_post) {
      print '<input type="text" name="insulinks_link" id="insulinks_link" value="' . @get_post_meta($insunews_post->ID, 'insulinks_link', true) . '"/>';
    }

    public function create_taxonomy() {
      register_taxonomy( 'links_cat',
        array('insulinks'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
        array('hierarchical' => true,     /* if this is true, it acts like categories */
          'labels' => array(
            'name' => __( 'Insulinks categorieën', 'wireframe' ), /* name of the custom taxonomy */
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
          'rewrite' => array( 'slug' => 'links' ),
        )
      );
    }

    public function import() {
      var_dump('START LINKS');

      //edit
      global $sitepress;

            $Insufeed = Insufeed::get_instance();

            //Import the links categories
            $categories = json_decode($Insufeed::query('insu/links/categories'));

            foreach ($categories as $category) {
                $name = $category->title;

                if (!term_exists($name, 'links_cat')) {
                    wp_insert_term(
                        $name,
                        'links_cat'
                    );
                }
            }

      global $user_ID;
      $items = json_decode($Insufeed::query('insu/links'));

      foreach ($items as $item) {
        print $item->id . "\n";
        $french = (int)get_option('insufeed_french');
        if (($french == 1 && $item->lng != 'fr') || $french == 0) {
          if (function_exists('icl_object_id') && get_option('insufeed_french') != 1) {
            $sitepress->switch_lang($item->lng);
          }

          $new_post = array(
            'post_title' => $item->title,
            'post_status' => 'publish',
            'post_date' => date('Y-m-d H:i:s', $item->created),
            'post_author' => $user_ID,
            'post_type' => self::POST_TYPE,
            'post_category' => array(0)
          );

          $sw_args = array(
            'post_type' => 'insulinks',
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'meta_query' => array(
              array(
                'key' => 'insulinks_remote_id',
                'value' => $item->id,
              ),
            ),
            'suppress_filters' => TRUE,
          );

          $query = new WP_Query($sw_args);

          if ($query->have_posts()) {
            while ($query->have_posts()) {
              $query->the_post();
              $new_post['ID'] = get_the_ID();

              //for older versions than wpml 3.2
              $_POST['icl_post_language'] = $language_code = $item->lng;

              wp_update_post($new_post);

              $post_id = get_the_ID();

              //in case posts are in the wrong language, run this code to change the language of the post
              if (function_exists('icl_object_id') && get_option('insufeed_french') != 1) {
                $def_trid = $sitepress->get_element_trid($post_id);
                $sitepress->set_element_language_details($post_id, 'post_insulinks', $def_trid, $language_code);
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
              $sitepress->set_element_language_details($post_id, 'post_insulinks', $def_trid, $language_code);
            }
          }

          update_post_meta($post_id, 'insulinks_remote_id', $item->id);
          update_post_meta($post_id, 'insulinks_link', $item->link);

          // assign term to link post
          // check if the term exists in the categories, this will return an array with term id & tax id when true
          $term = term_exists($item->cat, 'links_cat');

          if (!is_null($term)) {
            // get the term id of the correct language
            if (function_exists('icl_object_id') && get_option('insufeed_french') != 1) {
              $new_term_id = icl_object_id($term['term_id'], 'category', TRUE, $language_code); //this function is actually deprecated since WPML 3.2, below is the new function
              wp_set_post_terms($post_id, array($new_term_id), 'links_cat');
            } else {
              wp_set_post_terms($post_id, array($term['term_id']), 'link_cats');
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

      if (isset($_POST['post_type']) && $_POST['post_type'] == 'insulinks' && current_user_can('edit_post', $post_id)) {
        $metafields = $this->_meta();

        foreach($metafields as $field_name) {
          // Update the post's meta field
          update_post_meta($post_id, $field_name, $_POST[$field_name]);
        }
      }
    }

    private function _meta() {
      return array('insulinks_remote_id', 'insulinks_link');
    }
  }
}

if (class_exists('PostTypeInsulinks') && get_option('insufeed_links') == 1) {
  $PostTypeInsulinks = new PostTypeInsulinks();
}