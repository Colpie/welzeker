<?php print $before_widget; ?>
<div class="insunews_item last">
	<h2 class="insunews_item_title"><a href="<?php print the_permalink(); ?>"><?php print the_title(); ?></a></h2>
	<div class="excerpt"><?php print the_excerpt(); ?></div>
	<div class="readmore"><a href="<?php print the_permalink(); ?>"><?php print __('Read more'); ?></a></div>
</div>
<div class="allnews"><a href="<?php print get_post_type_archive_link( 'insunews' ); ?>"><?php print __('All news'); ?></a></div>
<?php print $after_widget; ?>
