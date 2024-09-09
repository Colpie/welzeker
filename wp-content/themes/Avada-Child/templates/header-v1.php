<?php
/**
 * Header-v1 template.
 *
 * @author     ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link       https://theme-fusion.com
 * @package    Avada
 * @subpackage Core
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>
<div class="fusion-header">
	<div class="fusion-row">
        <div class="col-lg-7 col-md-6 logo-section">
		    <?php avada_logo(); ?>
            <div class="socials">
                <a href="<?php echo get_option('insusite_facebook') ?>" target="_blank">
                    <img src="/wp-content/themes/Avada-Child/assets/images/icons/icon_facebook.png">
                </a>
                <a href="<?php echo get_option('insusite_instagram') ?>" target="_blank">
                    <img src="/wp-content/themes/Avada-Child/assets/images/icons/icon_insta.png">
                </a>
                <a href="<?php echo get_option('insusite_linkedin') ?>" target="_blank">
                    <img src="/wp-content/themes/Avada-Child/assets/images/icons/icon_linkedin.png">
                </a>
            </div>
        </div>
        <div class="col-lg-5 col-md-6 menu-section">
            <?php echo do_shortcode('[print_openings]'); ?>
        </div>
	</div>
</div>
<div class="fusion-menu-header">
    <div class="fusion-row menu-row">
        <div class="main-menu">
		    <?php avada_main_menu(); ?>
        </div>
    </div>
</div>
