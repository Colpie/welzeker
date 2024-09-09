<?php print $before_widget; ?>
<div class="insutips_item last" style="text-align: center;">
  <div class="image"><img src="/wp-content/plugins/insufeed/images/tip.png" width="128" /></div>
  <div class="tip">
	  <h2 class="insutips_item_tip"><a href="<?php print the_permalink(); ?>"><?php print the_title(); ?></a></h2>
  </div>
</div>
<div class="allnews"><a href="<?php print get_post_type_archive_link( 'insutip' ); ?>"><?php print __('All tips'); ?></a></div>
<?php print $after_widget; ?>