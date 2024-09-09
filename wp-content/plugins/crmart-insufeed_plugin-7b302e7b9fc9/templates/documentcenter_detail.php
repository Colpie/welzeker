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
$activeTitle = $links['activeTitle'];

//Retrieve documents
$activeCategory = get_query_var('list_action');
$companies = implode('+', get_option('insufeed_documents_companies'));

$documents = InsuDocumentcenter::getDocuments($activeCategory, $companies);
?>

<div class="container">
  <div class="row" role="main">
    <div class="span8">
      <h1><?php print $activeTitle; ?></h1>
      <?php if (!empty($documents)): ?>
      <?php foreach ($documents as $company) : ?>
        <div class="documentcenter_company">
          <h2 class="documentcenter_company_name"><?php print $company->company->name; ?></h2>
          <div class="documentcenter_body">
            <div class=""documentcenter_company_logo"><img src="<?php print $company->company->logo; ?>" /></div>
            <div class="documentcenter_documents">
              <ul>
              <?php foreach ($company->documents as $document) : ?>
                <li><a href="<?php print $document->url; ?>"><?php print $document->title; ?></a></li>
              <?php endforeach; ?>
              </ul>
            </div>
           </div>
        </div>
      <?php endforeach; ?>
      <?php else : ?>
      <?php print __('Er zijn geen documenten binnen deze categorie.'); ?>
      <?php endif; ?>
    </div>
    <div class="span4">
      <h2>Documenten</h2>
      <ul id="documentcenter_category_list">
        <?php print implode("\n", $links['items']); ?>
      </ul>
    </div>

  </div><!-- #content -->
</div><!-- #primary -->

<?php get_footer(); ?>