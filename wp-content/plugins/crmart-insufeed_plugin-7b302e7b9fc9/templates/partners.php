<?php
  $logos = array();
  $companies = InsuDocumentcenter::getCompanies();

  foreach ($companies as $cid => $company) {
    if (in_array( $cid ,get_option('insufeed_documents_companies'))) $logos[] = $company;
  }
?>
<?php get_header(); ?>
<ul>
  <?php foreach ($logos as $logo) : ?>
  	<li><img src="<?php print $logo['logo']; ?>" alt="<?php print $logo['title']; ?>" /></li>
  <?php endforeach; ?>
</ul>
<?php get_footer(); ?>