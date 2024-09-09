<?php
/*
### LATEST INSU NEWS ITEM ###

add_action( 'widgets_init', create_function('', 'return register_widget("InsuWidgetLastPost");') );

class InsuWidgetLastPost extends WP_Widget {
	function __construct() {
		$widget_ops = array('classname' => 'InsuWidgetLastPost', 'description' => __('Insunews Last Post'));
		parent::__construct('InsuWidgetLastPost', __('Insunews Last Post'), $widget_ops);
	}

	function widget( $args, $instance ) {
		$query = new WP_Query('posts_per_page=1&post_type=insunews&orderby=post_date&order=DESC');
		if ($query->have_posts()) {
			while ($query->have_posts()) :
				$query->the_post();
				include sprintf('%s/../templates/InsuWidgetLastPost.php', dirname(__FILE__));
			endwhile;
		}
	}
}

### LATEST TIP ITEMS ###

add_action( 'widgets_init', create_function('', 'return register_widget("InsuWidgetLastTip");') );

class InsuWidgetLastTip extends WP_Widget {
  function __construct() {
    $widget_ops = array('classname' => 'InsuWidgetLastTip', 'description' => __('Insunews Last Tip'));
    parent::__construct('InsuWidgetLastTip', __('Insunews Last Tip'), $widget_ops);
  }

  function widget( $args, $instance ) {
    $query = new WP_Query('posts_per_page=1&post_type=insutips&orderby=post_date&order=DESC');
    if ($query->have_posts()) {
      while ($query->have_posts()) :
        $query->the_post();
        include sprintf('%s/../templates/InsuWidgetLastTip.php', dirname(__FILE__));
      endwhile;
    }
  }
}
*/