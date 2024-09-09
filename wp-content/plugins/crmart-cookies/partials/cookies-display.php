<?php
$cookie = isset($_COOKIE["crmart_cookies"]) ? $_COOKIE['crmart_cookies'] : '';
$display = "";

if ($cookie == 'agreed') {
    $display = 'style="display:none"';
}

$cookie_statistic = isset($_COOKIE["statistic_cookies"]) ? $_COOKIE['statistic_cookies'] : 'new';
$cookie_marketing = isset($_COOKIE["marketing_cookies"]) ? $_COOKIE['marketing_cookies'] : 'new';

$checked_statistic = "checked";
$checked_marketing = "checked";

if ($cookie_statistic != 'agreed' && $cookie_statistic != 'new') {
    $checked_statistic = "";
}

if ($cookie_marketing != 'agreed' && $cookie_marketing != 'new') {
    $checked_marketing = "";
}

?>

<div id="cookie-popup" class="cookie-popup fadeInUp animated" <?php print $display?>>
    <div class="main-cookies">
        <p class="title"><?php print __('Cookies maken je surfervaring beter!') ?></p>
        <p>
            <?php print __('Deze website maakt gebruik van functionele, analystische en marketingcookies.') ?>
        <br><?php print __('Lees meer in '); ?><a href="<?php print get_site_url() ?>/cookiebeleid"
               class="primary-color cookie-link"><?php print __('ons cookiebeleid.') ?></a>
        </p>

        <div class="main-cookie-window">

        </div>

        <!--        Preferences-->

        <div class="cookie-preferences">
            <div class="content">
                <ul>
                    <li style="opacity: .5;">Functionele cookies (<a style="opacity: .5; pointer-events: none" href="<?php print get_site_url() ?>/cookiebeleid">meer info</a>)
                        <span class="input-type">
                         <input type="checkbox" class="css-checkbox" id="functional" checked>
                        </span>
                    </li>
                    <li>Statistische cookies (<a href="<?php print get_site_url() ?>/cookiebeleid">meer info</a>)
                        <span class="input-type">
                            <input type="checkbox" class="css-checkbox" id="statistic" <?php if($cookie_statistic == 'agreed') {  print $checked_statistic;  }?>>
                        </span>
                    </li>
                    <li>Marketingcookies (<a href="<?php print get_site_url() ?>/cookiebeleid">meer info</a>)
                        <span class="input-type">
                            <input type="checkbox" class="css-checkbox" id="marketing" <?php if($cookie_marketing == 'agreed') { print $checked_marketing; }  ?>>
                        </span>
                    </li>
                </ul>
            </div>
            <div class="save-cookies">
                <a href="" id="cookies-agree-all" class="fusion-button dark">
                    <?php print __('Alle cookies aanvaarden') ?>
                </a>
                <p>
                    <a href="#" class="cookie-options">Voorkeuren aanpassen</a>
                </p>

                <a href="" id="cookies-agree">
                    <?php print __('Mijn voorkeur bewaren') ?>
                </a>
            </div>
        </div>

    </div>


</div>



