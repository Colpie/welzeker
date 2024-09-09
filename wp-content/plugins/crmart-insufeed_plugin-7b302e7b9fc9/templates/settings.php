<?php
if (isset($_GET['settings-updated']) && $_GET['settings-updated'] == true) {
    if (is_file(INSUFEED_CACHE_DIR . '/companies.json')) {
        unlink(INSUFEED_CACHE_DIR . '/companies.json');
    }
}
?>
<div class="wrap">

    <h2>Insufeed settings</h2>

    <?php
    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'insusite_options';
    ?>


    <h2 class="nav-tab-wrapper">
        <a href="?page=insufeed&tab=insusite_options"
           class="nav-tab <?php echo $active_tab == 'insusite_options' ? 'nav-tab-active' : ''; ?>">Insusite</a>
        <?php if(current_user_can('administrator')): ?>
        <a href="?page=insufeed&tab=insufeed_options"
           class="nav-tab <?php echo $active_tab == 'insufeed_options' ? 'nav-tab-active' : ''; ?>">Insufeed</a>
        <?php endif; ?>
    </h2>


    <form method="post" action="options.php">

        <?php
        if ($active_tab == 'insusite_options'):
            ?>

            <?php @settings_fields('insusite-group'); ?>
            <?php //@do_settings_fields('insusite-group');
            ?>

            <h2>Office setting</h2>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label>Office name</label></th>
                    <td><input type="text" name="insusite_office_name" id="insusite_office_name"
                               value="<?php echo get_option('insusite_office_name'); ?>" size="60"/></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label>E-mail</label></th>
                    <td><input type="text" name="insusite_email" id="insusite_email"
                               value="<?php echo get_option('insusite_email'); ?>" size="60"/></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label>Telefoonnummer</label></th>
                    <td><input type="text" name="insusite_phone" id="insusite_phone"
                               value="<?php echo get_option('insusite_phone'); ?>" size="60"/></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label>Street</label></th>
                    <td><input type="text" name="insusite_street" id="insusite_street"
                               value="<?php echo get_option('insusite_street'); ?>" size="60"/></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label>Number</label></th>
                    <td><input type="text" name="insusite_street_number" id="insusite_street_number"
                               value="<?php echo get_option('insusite_street_number'); ?>" size="60"/></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label>ZIP</label></th>
                    <td><input type="text" name="insusite_zip" id="insusite_zip"
                               value="<?php echo get_option('insusite_zip'); ?>" size="60"/></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label>City</label></th>
                    <td><input type="text" name="insusite_city" id="insusite_city"
                               value="<?php echo get_option('insusite_city'); ?>" size="60"/></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label>Country</label></th>
                    <td><input type="text" name="insusite_country" id="insusite_country"
                               value="<?php echo get_option('insusite_country'); ?>" size="60"/></td>
                </tr>
            </table>

            <h2>Insusite settings</h2>

            <table class="form-table">

                <?php

                $sitetype = (get_option('insufeed_sitetype'));
                // if sitetype is vet
                if (isset($sitetype) && $sitetype == 1):

                    ?>

                    <tr valign="top">
                        <th scope="row"><label>Polisnummer</label></th>
                        <td><input type="text" name="insusite_polisnummer" id="insusite_polisnummer"
                                   value="<?php echo get_option('insusite_polisnummer'); ?>" size="60"/></td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><label>Registratienummer</label></th>
                        <td><input type="text" name="insusite_registratienummer" id="insusite_registratienummer"
                                   value="<?php echo get_option('insusite_registratienummer'); ?>" size="60"/></td>
                    </tr>

                <?php else: ?>

                    <tr valign="top">
                        <th scope="row"><label>FSMA nr</label></th>
                        <td><input type="text" name="insusite_fsma" id="insusite_fsma"
                                   value="<?php echo get_option('insusite_fsma'); ?>" size="60"/></td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><label>Ondernemingsnummer</label></th>
                        <td><input type="text" name="insusite_ondernemingsnummer" id="insusite_ondernemingsnummer"
                                   value="<?php echo get_option('insusite_ondernemingsnummer'); ?>" size="60"/></td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><label>RPR Locatie</label></th>
                        <td><input type="text" name="insusite_rpr_locatie" id="insusite_rpr_locatie"
                                   value="<?php echo get_option('insusite_rpr_locatie'); ?>" size="60"/></td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><label>RPR Afdeling</label></th>
                        <td><input type="text" name="insusite_rpr_afdeling" id="insusite_rpr_afdeling"
                                   value="<?php echo get_option('insusite_rpr_afdeling'); ?>" size="60"/></td>
                    </tr>

                    <tr valign="top">
                        <th scope="row"><label>Type makelaar</label></th>
                        <td><input type="text" name="insusite_type_makelaar" id="insusite_type_makelaar"
                                   value="<?php echo get_option('insusite_type_makelaar'); ?>" size="60"/></td>
                    </tr>

                <?php endif ?>

            </table>

            <h2>Social links</h2>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label>Facebook link</label></th>
                    <td><input type="text" name="insusite_facebook" id="insusite_facebook"
                               value="<?php echo get_option('insusite_facebook'); ?>" size="60"/></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label>Linkedin link</label></th>
                    <td><input type="text" name="insusite_linkedin" id="insusite_linkedin"
                               value="<?php echo get_option('insusite_linkedin'); ?>" size="60"/></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label>Instagram link</label></th>
                    <td><input type="text" name="insusite_instagram" id="insusite_instagram"
                               value="<?php echo get_option('insusite_instagram'); ?>" size="60"/></td>
                </tr>
            </table>

            <h2>Office about</h2>
             <textarea  name="insusite_office_about" id="insusite_office_about" rows="5" cols="90" style="max-width: 100%;" /><?php echo get_option('insusite_office_about'); ?></textarea>

            <h2>Openingsuren Kantoor</h2>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label>Maandag</label></th>
                <td style="width: 200px"><input type="text" name="ma_vm" id="ma_vm" value="<?php echo get_option('ma_vm') ?>" size="30"></td>
                <td><input type="text" name="ma_nm" id="ma_nm" value="<?php echo get_option('ma_nm') ?>" size="30"></td>
            </tr>

            <tr valign="top">
                <th scope="row"><label>Dinsdag</label></th>
                <td style="width: 200px"><input type="text" name="di_vm" id="di_vm" value="<?php echo get_option('di_vm') ?>" size="30"></td>
                <td><input type="text" name="di_nm" id="di_nm" value="<?php echo get_option('di_nm') ?>" size="30"></td>
            </tr>

            <tr valign="top">
                <th scope="row"><label>Woensdag</label></th>
                <td style="width: 200px"><input type="text" name="wo_vm" id="wo_vm" value="<?php echo get_option('wo_vm') ?>" size="30"></td>
                <td><input type="text" name="wo_nm" id="wo_nm" value="<?php echo get_option('wo_nm') ?>" size="30"></td>
            </tr>

            <tr valign="top">
                <th scope="row"><label>Donderdag</label></th>
                <td style="width: 200px"><input type="text" name="do_vm" id="do_vm" value="<?php echo get_option('do_vm') ?>" size="30"></td>
                <td><input type="text" name="do_nm" id="do_nm" value="<?php echo get_option('do_nm') ?>" size="30"></td>
            </tr

            <tr valign="top">
                <th scope="row"><label>Vrijdag</label></th>
                <td style="width: 200px"><input type="text" name="vr_vm" id="vr_vm" value="<?php echo get_option('vr_vm') ?>" size="30"></td>
                <td><input type="text" name="vr_nm" id="vr_nm" value="<?php echo get_option('vr_nm') ?>" size="30"></td>
            </tr>

            <tr valign="top">
                <th scope="row"><label>Zaterdag</label></th>
                <td style="width: 200px"><input type="text" name="za_vm" id="za_vm" value="<?php echo get_option('za_vm') ?>" size="30"></td>
                <td><input type="text" name="za_nm" id="za_nm" value="<?php echo get_option('za_nm') ?>" size="30"></td>
            </tr>

        </table>

            <h2>Openingsuren Kantoor 2</h2>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label>Maandag</label></th>
                    <td style="width: 200px"><input type="text" name="k2_ma_vm" id="k2_ma_vm" value="<?php echo get_option('k2_ma_vm') ?>" size="30"></td>
                    <td><input type="text" name="k2_ma_nm" id="k2_ma_nm" value="<?php echo get_option('k2_ma_nm') ?>" size="30"></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label>Dinsdag</label></th>
                    <td style="width: 200px"><input type="text" name="k2_di_vm" id="k2_di_vm" value="<?php echo get_option('k2_di_vm') ?>" size="30"></td>
                    <td><input type="text" name="k2_di_nm" id="k2_di_nm" value="<?php echo get_option('k2_di_nm') ?>" size="30"></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label>Woensdag</label></th>
                    <td style="width: 200px"><input type="text" name="k2_wo_vm" id="k2_wo_vm" value="<?php echo get_option('k2_wo_vm') ?>" size="30"></td>
                    <td><input type="text" name="k2_wo_nm" id="k2_wo_nm" value="<?php echo get_option('k2_wo_nm') ?>" size="30"></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label>Donderdag</label></th>
                    <td style="width: 200px"><input type="text" name="k2_do_vm" id="k2_do_vm" value="<?php echo get_option('k2_do_vm') ?>" size="30"></td>
                    <td><input type="text" name="k2_do_nm" id="k2_do_nm" value="<?php echo get_option('k2_do_nm') ?>" size="30"></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label>Vrijdag</label></th>
                    <td style="width: 200px"><input type="text" name="k2_vr_vm" id="k2_vr_vm" value="<?php echo get_option('k2_vr_vm') ?>" size="30"></td>
                    <td><input type="text" name="k2_vr_nm" id="k2_vr_nm" value="<?php echo get_option('k2_vr_nm') ?>" size="30"></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label>Zaterdag</label></th>
                    <td style="width: 200px"><input type="text" name="k2_za_vm" id="k2_za_vm" value="<?php echo get_option('k2_za_vm') ?>" size="30"></td>
                    <td><input type="text" name="k2_za_nm" id="k2_za_nm" value="<?php echo get_option('k2_za_nm') ?>" size="30"></td>
                </tr>

            </table>

        <h2>Popup</h2>

        <?php $check = isset($_POST['insu_popup_active']) ? "checked" : "unchecked"; ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label>Activeer</label></th>
                <td><input type="checkbox" name="insu_popup_active" id="insu_popup_active" <?php if (get_option('insu_popup_active')==true) echo 'checked="checked" '; ?>></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label>Titel</label></th>
                <td><input type="text" name="insu_popup_title" id="insu_popup_title" value="<?php echo get_option('insu_popup_title') ?>" size="60"></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label>Tekst</label></th>
                <td><input type="text" name="insu_popup_text" id="insu_popup_text" value="<?php echo get_option('insu_popup_text') ?>" size="60"></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label>Call to action</label></th>
                <td><input type="text" name="insu_popup_cta" id="insu_popup_cta" value="<?php echo get_option('insu_popup_cta') ?>" size="60"></td>
            </tr>

            <tr valign="top">
                <th scope="row"><label>Link achter call to action</label></th>
                <td><input type="text" name="insu_popup_cta_link" id="insu_popup_cta_link" value="<?php echo get_option('insu_popup_cta_link') ?>" size="60"></td>
            </tr>
        </table>

        <?php elseif ($active_tab == 'insufeed_options'): ?>


            <?php @settings_fields('insufeed-group'); ?>
            <?php //@do_settings_fields('insufeed-group'); ?>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label>Site type</label></th>
                    <td>
                        <label>Vet</label><input type='radio'
                                                 name='insufeed_sitetype' <?php checked(get_option('insufeed_sitetype'), 1); ?>
                                                 value='1'>&nbsp;&nbsp;&nbsp;
                        <label>Insu</label><input type='radio'
                                                  name='insufeed_sitetype' <?php checked(get_option('insufeed_sitetype'), 2); ?>
                                                  value='2'>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="insufeed_url">Insufeed URL</label></th>
                    <td><input type="text" name="insufeed_url" id="insufeed_url"
                               value="<?php echo get_option('insufeed_url'); ?>" size="60"/></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="insufeed_public_key">Client id</label></th>
                    <td><input type="text" name="insufeed_public_key" id="insufeed_public_key"
                               value="<?php echo get_option('insufeed_public_key'); ?>" size="60"/></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="insufeed_private_key">Secret</label></th>
                    <td><input type="text" name="insufeed_private_key" id="insufeed_private_key"
                               value="<?php echo get_option('insufeed_private_key'); ?>" size="60"/></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="insufeed_news">Sync medi/insu news</label></th>
                    <td><input type="checkbox" name="insufeed_news" id="insufeed_news"
                               value="1"<?php print checked(1, get_option('insufeed_news'), false); ?>/></td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="insufeed_overwrite">Not overwrite medi/insu news</label></th>
                    <td><input type="checkbox" name="insufeed_overwrite" id="insufeed_overwrite"
                               value="1"<?php print checked(1, get_option('insufeed_overwrite'), false); ?>/></td>
                </tr>

                <?php
                $sitetype = (get_option('insufeed_sitetype'));
                // if sitetype is vet
                if (isset($sitetype) && $sitetype == 1) {
                    $args = array("hide_empty" => 0,
                        "type" => "post",
                        "orderby" => "name",
                        "order" => "ASC");
                    $categoryList = get_categories($args);
                    $insufeed_vet_categories = get_option('insufeed_vet_categories');
                    if (!is_array($insufeed_vet_categories)) {
                        $insufeed_vet_categories = array();
                    } ?>
                    <?php if ($categoryList) { ?>
                        <tr>
                            <th scope="row"><label for="insufeed_documents_companies">Categorieën</label></th>
                            <td>
                                <?php foreach ($categoryList as $category) { ?>
                                    <input type="checkbox" name="insufeed_vet_categories[]"
                                           value="<?php echo $category->term_id; ?>" <?php print checked(true, in_array($category->term_id, $insufeed_vet_categories), false); ?>/>&nbsp;&nbsp;
                                    <?php echo $category->name; ?>
                                    <br/>
                                <?php }
                                ?></td>
                        </tr>
                    <?php } ?>
                    <?php
                } else {
                    ?>
                    <tr valign="top">
                        <th scope="row"><label for="insufeed_french">Franse berichten niet importeren</label></th>
                        <td><input type="checkbox" name="insufeed_french" id="insufeed_french"
                                   value="1"<?php print checked(1, get_option('insufeed_french'), false); ?>/></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="insufeed_tips">Sync insutips</label></th>
                        <td><input type="checkbox" name="insufeed_tips" id="insufeed_tips"
                                   value="1"<?php print checked(1, get_option('insufeed_tips'), false); ?>/></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="insufeed_links">Sync insulinks</label></th>
                        <td><input type="checkbox" name="insufeed_links" id="insufeed_links"
                                   value="1"<?php print checked(1, get_option('insufeed_links'), false); ?>/></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="insufeed_faq">Sync insufaq</label></th>
                        <td><input type="checkbox" name="insufeed_faq" id="insufeed_faq"
                                   value="1"<?php print checked(1, get_option('insufeed_faq'), false); ?>/></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="insufeed_documentcenter">Include documentcenter</label></th>
                        <td><input type="checkbox" name="insufeed_documentcenter" id="insufeed_documentcenter"
                                   value="1"<?php print checked(1, get_option('insufeed_documentcenter'), false); ?>/>
                        </td>
                    </tr>
                    <?php if (get_option('insufeed_documentcenter') == 1) : ?>
                        <tr>
                        <th scope="row"><label for="insufeed_documents_companies">Companies</label></th>
                        <td>
                        <?php
                        $insufeed_documentcenter = InsuDocumentcenter::get_instance();
                        $companies = $insufeed_documentcenter->getCompanies();
                        $insufeed_documents_companies = get_option('insufeed_documents_companies');
                        if (!is_array($insufeed_documents_companies)) {
                            $insufeed_documents_companies = array();
                        }
                        ?>
                        <?php foreach ($companies as $cid => $company) : ?>
                            <input type="checkbox" name="insufeed_documents_companies[]"
                                   value="<?php print $cid; ?>"<?php print checked(true, in_array($cid, $insufeed_documents_companies), false); ?>/> <?php print $company['title']; ?>
                            <br/>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </td>
                    </tr>
                <?php } ?>

            </table>

        <?php endif // active tab ?>

        <?php @submit_button(); ?>
    </form>
</div>
