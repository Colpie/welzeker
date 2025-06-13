<?php

if (!class_exists('PostTypeInsufeed')) {
    class PostTypeInsufeed
    {
        const INSU_POST_TYPE = 'insunews';
        const MEDI_POST_TYPE = 'post';
        protected static $instance = NULL;

        public static function get_instance()
        {
            if (null === self::$instance) self::$instance = new self;
            return self::$instance;
        }

        public function __construct()
        {
            add_action('init', array(&$this, 'init'));
            add_action('admin_init', array(&$this, 'insufeed_admin'));
        }

        public function init()
        {
            $this->create_post_type();
            add_action('save_post', array(&$this, 'save_post'));

        }

        public function create_post_type()
        {
            register_post_type('insunews', array(
                'labels' => array(
                    'name' => __('News', 'crmart'),
                    'singular_name' => __('News', 'crmart'),
                ),
                'rewrite' => array('slug' => 'nieuws'),
                'public' => true,
                'has_archive' => true,
                'show_in_rest' => true,
                'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),
                'menu_icon' => plugins_url('../images/insunews-icon.png', __FILE__),
            ));
        }

        public function insufeed_admin()
        {
            add_action('add_meta_boxes', array(&$this, 'add_meta_boxes'));
        }

        public function add_meta_boxes()
        {
            add_meta_box(
                'insufeed_remote_id',
                'Remote ID',
                array(&$this, 'insufeed_display_remote_id'),
                'insunews', 'normal', 'high'
            );

            add_meta_box(
                'insufeed_newsletter_image',
                'Newsletter image',
                array(&$this, 'insufeed_display_newsletter_image'),
                'insunews', 'normal', 'high'
            );
        }

        public function insufeed_display_remote_id($insunews_post)
        {
            print '<input type="text" name="insufeed_remote_id" id="insufeed_remote_id" value="' . @get_post_meta($insunews_post->ID, 'insufeed_remote_id', true) . '"/>';
        }

        public function insufeed_display_newsletter_image($insunews_post)
        {
            print '<input type="text" name="insufeed_newsletter_image" id="insufeed_newsletter_image" value="' . @get_post_meta($insunews_post->ID, 'insufeed_newsletter_image', true) . '" />';
        }

        /**
         *
         */
        public function import()
        {
            print 'Start sync news items' . PHP_EOL;

            global $sitepress;
            global $user_ID;

            $Insufeed = Insufeed::get_instance();
            $sitetype = get_option('insufeed_sitetype');
            $overwrite = get_option('insufeed_overwrite');
            $post_id = '';

            if (isset($sitetype) && $sitetype == 1) {
                /* Get categories link to news items */
                $insufeed_vet_cat_links = '';
                $args = array("hide_empty" => 0,
                    "type" => "post",
                    "orderby" => "name",
                    "order" => "ASC");
                $categoryList = get_categories($args);
                $insufeed_vet_categories = get_option('insufeed_vet_categories');

                /* Create parameter string for URL */
                if ($categoryList) {
                    foreach ($categoryList as $category) {
                        if (in_array($category->term_id, $insufeed_vet_categories)) {
                            $category_name = str_replace(' ', '%20', $category->name);
                            $insufeed_vet_cat_links .= $category_name . ',';
                        }
                    }
                    $insufeed_vet_cat_links = substr($insufeed_vet_cat_links, 0, -1);
                }
                $items = json_decode($Insufeed::query('medi/news/' . strtolower($insufeed_vet_cat_links)));
            } else {
                $items = json_decode($Insufeed::query('insu/news'));
            }

            if ($items) {
                //print 'Items not empty' . PHP_EOL;
                foreach ($items as $item) {
                    print 'start: ' . $item->id . ' ' . $item->title . PHP_EOL;

                    // skip french articles if option is enabled
                    if ($item->lng == 'fr' && get_option('insufeed_french') == 1) {
                        continue;
                    }

                    if ($sitetype == 1) { // MEDICOMMERCE
                        /* Get selected categories */
                        $categoryPostList = array();
                        $categoryList = get_categories();
                        foreach ($categoryList as $category) {
                            $categoryListArray[$category->term_id] = $category->name;
                        }
                        foreach ($item->categories as $categoryName) {
                            if (array_search($categoryName, $categoryListArray)) $categoryPostList[] = array_search($categoryName, $categoryListArray);
                        }

                        /* add template requirements for content-block */
                        require_once(get_stylesheet_directory() . '/insufeed/insufeed_content_header.php');
                        $post_content = add_insufeed_header() . $item->body;
                        require_once(get_stylesheet_directory() . '/insufeed/insufeed_content_footer.php');
                        $post_content = $post_content . add_insufeed_footer();

                        $post_type = self::MEDI_POST_TYPE;
                        $post_category = $categoryPostList;
                        $post_status = 'publish';
                        $author = 1;
                    } else { // INSUCOMMERCE
                        $post_content = $item->body;
                        $post_type = self::INSU_POST_TYPE;
                        $post_category = array(0);
                        $post_status = array('publish', 'draft', 'trash');
                        $author = $user_ID;
                        $_POST['icl_post_language'] = $language_code = $item->lng;

                        if (function_exists('icl_object_id') && get_option('insufeed_french') != 1) {
                            $sitepress->switch_lang($item->lng);
                        }

                    }

                    /* Fill new post variables*/
                    $title = $item->title;
                    $summary = $item->summary;
                    $created = $item->created;

                    /* Create new post */
                    $new_post = array(
                        'post_title' => $title,
                        'post_content' => $post_content,
                        'post_excerpt' => strip_tags($summary),
                        'post_status' => 'publish',
                        'post_date' => date('Y-m-d H:i:s', $created),
                        'post_author' => $author,
                        'post_type' => $post_type,
                        'post_category' => $post_category,
                    );

                    /* Create arguments */
                    $sw_args = array(
                        'post_type' => $post_type,
                        'post_status' => $post_status,
                        'posts_per_page' => 1,
                        'meta_query' => array(
                            array(
                                'key' => 'insufeed_remote_id',
                                'value' => $item->id,
                            )
                        ),
                        'suppress_filters' => TRUE,
                    );

                    $query = new WP_Query($sw_args);
                    if ($query->have_posts()) {
                        /* Update existing news item */
                        while ($query->have_posts()) {
                            $query->the_post();
                            //print 'Existing post gevonden: ' . get_the_ID() . PHP_EOL;
                            $new_post['ID'] = get_the_ID();

                            $status_switch = TRUE;
                            if ($sitetype == 1) { // MEDICOMMERCE
                                $current_status = get_post_status(get_the_ID());
                                if ($current_status == 'draft' && $current_status == 'trash') {
                                    $status_switch = FALSE;
                                }
                            }

                            if ($overwrite != 1 && $status_switch) {
                                //print 'Update news item : ' . $item->id . PHP_EOL;
                                wp_update_post($new_post);
                            } else {
                                //print 'Overwrite is turned off, dont update the post: ' . $item->id . PHP_EOL;
                            }

                            $post_id = get_the_ID();

                        }
                    } else {
                        /* 
                         * Insert new news item 
                         * If the item is French, also see if WPML is enabled + if the option 'insufeed_french' is 0
                         *
                        */
                        $proceed = false;

                        print 'ITEM LANG: ' . $item->lng . PHP_EOL;

                        if ($item->lng == 'fr') {

                            if (function_exists('icl_object_id') && get_option('insufeed_french') != 1) {
                                print 'proceeding to insert news item bcs its fr' . PHP_EOL;
                                $proceed = true;
                            }

                        } else {
                            print 'proceeding to insert news item bcs item lang is not fr' . PHP_EOL;;
                            $proceed = true;
                        }

                        if ($proceed == true) {
                            print 'Insert news item : ' . $item->id . PHP_EOL;
                            $post_id = wp_insert_post($new_post);
                        }
                    }

                    // reset loop
                    wp_reset_postdata();

                    // only proceed if we have a post id
                    if ($post_id) {

                        if ($sitetype == 2) { // INSUCOMMERCE
                            if ($language_code != 'nl' && function_exists('icl_object_id') && get_option('insufeed_french') != 1) {
                                    //$t_post_id = icl_object_id($post_id, 'insunews', false);
                                //if ($t_post_id == null) {
                                    print 'Add translation' . PHP_EOL;
                                    $def_trid = $sitepress->get_element_trid($post_id);
                                    $sitepress->set_element_language_details($post_id, 'post_insunews', $def_trid, $language_code);
                                //}
                            }
                        }

                        /* Add image to news item */
                        if ($item->og_image) {
                            //print 'Upload image to news item' . PHP_EOL;
                            $wordpress_upload_dir = wp_upload_dir();
                            $original_url = $item->og_image;
                            //print PHP_EOL . 'Debugging file name and paths:' . PHP_EOL;
                            //print 'Original URL: ' . $original_url . PHP_EOL;
                            $origin_clean_file_name_array = explode('?', $original_url);
                            $origin_clean_file_name = $origin_clean_file_name_array[0];
                            //print 'Original URL without "?": ' . $origin_clean_file_name . PHP_EOL;
                            $exploded_file_name = explode('.', $origin_clean_file_name);
                            $file_name = $item->title . '.' . strtolower(end($exploded_file_name));
                            //print 'File name with extension: ' . $file_name . PHP_EOL;
                            $file_name = strtolower(str_replace(' ', '-', $file_name));
                            //print 'File name str to lower: ' . $file_name . PHP_EOL;
                            $file_name = preg_replace('/[^A-Za-z0-9.\-]/', '', $file_name);
                            //print 'Replacing some things in file name: ' . $file_name . PHP_EOL;
                            $file_path = $wordpress_upload_dir['basedir'] . '/insufeed/news/' . $file_name;
                            //print 'File path: ' . $file_path . PHP_EOL;
                            $file_url = $wordpress_upload_dir['baseurl'] . '/insufeed/news/' . $file_name;
                            //print 'File url: ' . $file_url . PHP_EOL;
                            $dir_path = $wordpress_upload_dir['basedir'] . '/insufeed/news/';
                            //print 'Dir path: ' . $dir_path . PHP_EOL;
                            //$file_mime = get_mime_type($origin_clean_file_name);
                            $wp_filetype = wp_check_filetype($origin_clean_file_name, null);
                            $file_mime = $wp_filetype['type'];
                            //print 'File mime: ' . $file_mime . PHP_EOL . PHP_EOL;

                            $attachment = array(
                                'guid' => $file_url,
                                'post_mime_type' => $file_mime,
                                'post_title' => preg_replace('/\.[^.]+$/', '', $file_name),
                                'post_status' => 'inherit',
                                'post_content' => '',
                                'post_date' => date('Y-m-d H:i:s')
                            );

                            if ((file_exists($file_path) == false) || (filesize($file_path) == 0)) {
                                if (!file_exists($dir_path)) {
                                    mkdir($dir_path, 0755, true);
                                }

                                /* Get image from server */
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $original_url);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
                                $image_output = curl_exec($ch);
                                curl_close($ch);

                                if (file_put_contents($file_path, $image_output)) {
                                    $attachment_id = wp_insert_attachment($attachment, $file_path, $post_id);
                                    require_once(ABSPATH . 'wp-admin/includes/image.php');
                                    $attachment_data = wp_generate_attachment_metadata($attachment_id, $file_path);
                                    wp_update_attachment_metadata($attachment_id, $attachment_data);
                                    set_post_thumbnail($post_id, $attachment_id);
                                    print 'Copy successful for ' . $origin_clean_file_name . PHP_EOL;
                                } else {
                                    print 'Copy failed for ' . $origin_clean_file_name . PHP_EOL;
                                }
                            } else {

                                // file already exists, link it with the post
                                global $wpdb;
                                $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $file_url));
                                $attachment_id = $attachment[0];
                                set_post_thumbnail($post_id, $attachment_id);

                            }
                        } else {
                            //print 'No image found on server for news item : ' . $item->title . PHP_EOL;
                        }

                        print 'stop: ' . $item->id . ' ' . $item->title . PHP_EOL . PHP_EOL;
                        update_post_meta($post_id, 'insufeed_remote_id', $item->id);
                        update_post_meta($post_id, 'insufeed_newsletter_image', $item->newsletter_image);

                    }

                    // print message
                    //print 'Finished item: ' . $item->id . PHP_EOL . PHP_EOL;

                } // end foreach $items
            } else {
                //print 'No new news items found' . PHP_EOL;
            }

        }

        public function save_post($post_id)
        {
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return;
            }

            if (isset($_POST['post_type']) && $_POST['post_type'] == 'insunews' && current_user_can('edit_post', $post_id)) {
                $metafields = $this->_meta();

                foreach ($metafields as $field_name) {
                    // Update the post's meta field
                    update_post_meta($post_id, $field_name, $_POST[$field_name]);
                }
            }
        }

        private function _meta()
        {
            return array('insufeed_remote_id', 'insufeed_newsletter_image');
        }
    }
}

if (class_exists('PostTypeInsufeed') && get_option('insufeed_news') == 1) {
    $PostTypeInsufeed = new PostTypeInsufeed();
}


function get_mime_type($file)
{
    // our list of mime types
    $mime_types = array(
        "pdf" => "application/pdf",
        "exe" => "application/octet-stream",
        "zip" => "application/zip",
        "docx" => "application/msword",
        "doc" => "application/msword",
        "xls" => "application/vnd.ms-excel",
        "ppt" => "application/vnd.ms-powerpoint",
        "gif" => "image/gif",
        "png" => "image/png",
        "jpeg" => "image/jpg",
        "jpg" => "image/jpg",
        "mp3" => "audio/mpeg",
        "wav" => "audio/x-wav",
        "mpeg" => "video/mpeg",
        "mpg" => "video/mpeg",
        "mpe" => "video/mpeg",
        "mov" => "video/quicktime",
        "avi" => "video/x-msvideo",
        "3gp" => "video/3gpp",
        "css" => "text/css",
        "jsc" => "application/javascript",
        "js" => "application/javascript",
        "php" => "text/html",
        "htm" => "text/html",
        "html" => "text/html",
    );

    $explodedFile = explode('.', $file);
    $extension = strtolower(end($explodedFile));

    return $mime_types[$extension];
}

