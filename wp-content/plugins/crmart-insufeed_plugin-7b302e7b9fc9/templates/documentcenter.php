<?php
/**
 * The Template for displaying the main screen of the documentcenter
 *
 * @package iTek
 */

get_header();

//Retrieve needed data
$categoriesData = InsuDocumentcenter::getCategories();
$links = InsuDocumentcenter::getCategoriesAsLinks($categoriesData);
?>

<div class="container">
  <div class="row" role="main">
    <div class="span8">
      <h1><?php print __('Documentcenter'); ?></h1>
    </div>
    <div class="span4">
      <h2><?php print __('Documenten') ;?></h2>
      <ul id="documentcenter_category_list">
      <?php print implode("\n", $links['items']); ?>
      </ul>
    </div>

  </div><!-- #content -->
</div><!-- #primary -->

<?php get_footer(); ?>