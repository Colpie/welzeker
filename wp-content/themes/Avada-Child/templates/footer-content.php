<?php
/**
 * Footer content template.
 *
 * @author     ThemeFusion
 * @copyright  (c) Copyright by ThemeFusion
 * @link       https://theme-fusion.com
 * @package    Avada
 * @subpackage Core
 * @since      5.3.0
 */

$c_page_id = Avada()->fusion_library->get_page_id();
/**
 * Check if the footer widget area should be displayed.
 */
?>


    <div class="custom-footer">
        <div class="fusion-row">
            <div class="col-lg-7 col-md-12 col-12 footer-column footer-left-column">
                <div class="footer-inner-column">
                    <a href="https://www.crelan.be/nl/particulieren" target="_blank">
                        <img src="/wp-content/themes/Avada-Child/assets/images/logos/crelan.png">
                    </a>
                    <a href="https://www.brocom.be/nl/over-brocom/je-makelaar-je-beste-verzekering" target="_blank">
                        <img src="/wp-content/themes/Avada-Child/assets/images/logos/je_makelaar.png">
                    </a>
                    <a href="https://www.fvf.be/" target="_blank">
                        <img src="/wp-content/themes/Avada-Child/assets/images/logos/fvf.png">
                    </a>
                    <a href="https://aquilae.be/" target="_blank">
                        <img src="/wp-content/themes/Avada-Child/assets/images/logos/aq.png">
                    </a>
                </div>
            </div>
            <div class="col-lg-5 col-md-9 col-9 footer-column footer-right-column">
                <div class="footer-inner-column">
                    <?php
                    $footer_menu = wp_nav_menu(array('menu' => 'footer-menu'));
                    echo $footer_menu;
                    ?>
                    <span class="legal-info">
                         <p><?php echo get_option('insusite_office_name'); ?> | <?php echo get_option('insusite_ondernemingsnummer') ?></p>
                     </span>
                </div>
            </div>
        </div> <!-- fusion-row -->
    </div> <!-- fusion-footer-widget-area -->

<?php
// Displays WPML language switcher inside footer if parallax effect is used.
if ((defined('WPML_PLUGIN_FILE') || defined('ICL_PLUGIN_FILE')) && 'footer_parallax_effect' === Avada()->settings->get('footer_special_effects')) {
    global $wpml_language_switcher;
    $slot = $wpml_language_switcher->get_slot('statics', 'footer');
    if ($slot->is_enabled()) {
        echo $wpml_language_switcher->render($slot); // phpcs:ignore WordPress.Security.EscapeOutput
    }
}
