<?php
add_shortcode('print_menu', 'print_menu_function');
?>

<?php
function print_menu_function()
{
    ob_start();
    ?>
    <div class="custom-main-menu">
        <div class="custom-main-menu-wrapper">
            <div class="custom-menu-item">
                <a href="<?php echo get_site_url(); ?>/over-ons">
                    <img src="/wp-content/themes/Avada-Child/assets/images/menu/icon_overons.png">
                    Over ons
                </a>
            </div>
            <div class="custom-menu-item">
                <a href="<?php echo get_site_url(); ?>/bank-crelan">
                    <img src="/wp-content/themes/Avada-Child/assets/images/menu/icon_bank.png">
                    Bank crelan
                </a>
            </div>
            <div class="custom-menu-item">
                <a href="<?php echo get_site_url(); ?>/sparen-beleggen" target="_blank">
                    <img src="/wp-content/themes/Avada-Child/assets/images/menu/icon_sparen.png">
                    Sparen & Beleggen
                </a>
            </div>
            <div class="custom-menu-item">
                <a href="<?php echo get_site_url(); ?>/kredieten" target="_blank">
                    <img src="/wp-content/themes/Avada-Child/assets/images/menu/icon_kredieten.png">
                    Kredieten
                </a>
            </div>
            <div class="custom-menu-item">
                <a href="<?php echo get_site_url(); ?>/verzekeringen" target="_blank">
                    <img src="/wp-content/themes/Avada-Child/assets/images/menu/icon_verzekeringen.png">
                    Kredieten
                </a>
            </div>
            <div class="custom-menu-item">
                <a href="<?php echo get_site_url(); ?>/schade" target="_blank">
                    <img src="/wp-content/themes/Avada-Child/assets/images/menu/icon_schade.png">
                    Schade
                </a>
            </div>
            <div class="custom-menu-item">
                <a href="<?php echo get_site_url(); ?>/nuttige-info">
                    <img src="/wp-content/themes/Avada-Child/assets/images/menu/icon_info.png">
                    Nuttige info
                </a>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
?>