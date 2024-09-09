(function ($) {
    'use strict';

    /**
     * All of the code for your public-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    $(document).ready(function ($) {

        /** Switch cookie preferences */

        $('.cookie-options').on('click', function (e) {
            e.preventDefault();
            $('#cookies-agree').toggleClass('vb-show');
            $('.cookie-preferences .content').toggleClass('vb-show');
        });

        ($('#functional').on('click', function (e) {
            e.preventDefault();
        }));

        $('.cookie-reload').on('click', function (e) {
            e.preventDefault();
            if (getCookie('crmart_cookies') == 'agreed') {
                popup.show();
            }
        });

        /** Extra client side check if cookie exist */
        var popup = $('body #cookie-popup');
        // if (getCookie('crmart_cookies') == 'agreed') {
        //     popup.hide();
        // } else {
        //     popup.fadeTo("slow", 1);
        // }

        $('a#cookies-agree-all').on('click', function (e) {
            e.preventDefault();
            setCookie('crmart_cookies', 'agreed', 365);
            setCookie('statistic_cookies', 'agreed', 365);
            setCookie('marketing_cookies', 'agreed', 365);

            animatePopup(popup);
            window.location.reload();
            return false;
        });

        $('a#cookies-agree').click(function (e) {
            e.preventDefault();
            var popup = $(this).parents('#cookie-popup');

            setCookie('crmart_cookies', 'agreed', 365);

            if ($('#functional').is(':checked')) {
                setCookie('functional_cookies', 'agreed', 365);
            }

            if ($('#statistic').is(':checked')) {
                setCookie('statistic_cookies', 'agreed', 365);
            } else  {
                setCookie('statistic_cookies', 'disagreed', 365);
            }

            if ($('#marketing').is(':checked')) {
                setCookie('marketing_cookies', 'agreed', 365);
            }else  {
                setCookie('marketing_cookies', 'disagreed', 365);
            }

            animatePopup(popup);
            window.location.reload();
            return false;
        });

        function animatePopup(popup) {
            popup.animate({
                bottom: '-' + popup.outerHeight() + 'px'
            }, 500, function () {

            });
        }

        function setCookie(name, value, days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "") + expires + "; path=/";
        }

        function getCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }

    });

})(jQuery);