(function ($) {
    $(document).ready(function () {

        $('img').hover(function () {
            $(this).data('title', $(this).attr('title')).removeAttr('title');
        }, function () {
            $(this).attr('title', $(this).data('title'));
        });

        $(window).scroll(function() {
            if ($(this).scrollTop() > 168) {
                // Add the class when scrolled more than 168px
                $('.fusion-menu-header').addClass('sticky-header fadeInDown animated');
            } else {
                // Remove the class if scrolled less than 168px
                $('.fusion-menu-header').removeClass('sticky-header fadeInDown animated');
            }
        });

        function checkMenuPosition() {
            var menu = $('.home .custom-menu-row');
            var header = $('.home .fusion-menu-header');

            if (menu.length) {
                var menuOffset = menu.offset().top; // Get the top position of the custom-menu-row
                var windowScroll = $(window).scrollTop(); // Get the current scroll position

                // If the user has scrolled past the top of custom-menu-row
                if (windowScroll > menuOffset) {
                    header.addClass('sticky-active fadeInDown animated'); // Add class when out of view
                } else {
                    header.removeClass('sticky-active fadeInDown animated'); // Remove class when in view
                }
            }
        }

        // Check on scroll and window resize
        $(window).on('scroll resize', function () {
            checkMenuPosition();
        });

        // Initial check on page load
        checkMenuPosition();

        // Mobile menu
        $('.fusion-flyout-menu-toggle').on('click', function (e) {
            e.preventDefault();
            $('.fusion-header').removeAttr('style');
            $('.fusion-flyout-mobile-menu-icons').toggleClass('change');

            $('.fusion-flyout-menu-bg').toggleClass('active-bg');
            $('.fusion-flyout-menu .fusion-menu').toggleClass('fly');
            $('.custom-caret').remove();

            $('.side-menu').toggleClass('no-z');
            $('.fusion-header').toggleClass('no-z');

            if ($('.fusion-mobile-nav-holder li').hasClass('menu-item-has-children')) {
                $('.fusion-flyout-menu .fusion-menu .menu-item-has-children a').append('<span class="fusion-caret custom-caret"><i class="fusion-dropdown-indicator" aria-hidden="true"></i></span>');
                $('.fusion-flyout-menu .fusion-menu .menu-item-has-children a .custom-caret').on('click', function (e) {
                    e.preventDefault();

                    $(this).toggleClass('rotate');
                    $(this).closest('li').find('.sub-menu').toggleClass('open');

                });
            } else {
                // Leave empty
            }
        });

        // Legal menu
        $('.legal-content:first-child').addClass('active fadeInUp animated');
        $('.legal-menu li:first-child a').addClass('active');

        $('.legal-menu a').on('click', function (e) {

            if ($(this).attr('target') == '_blank') {
                // Leave empty
            } else {
                e.preventDefault();
                $('.legal-menu a.active').removeClass('active');
                $(this).addClass('active');
                $('.legal-content').removeClass('active fadeInUp');

                var menu_id = $(this).attr('href');
                var hash = menu_id.split('#')[1];
                menu_id = $('#' + hash);

                menu_id.addClass('active fadeInUp animated');
            }
        });

        // Form submit
        $('.wpcf7-submit').on('click', function () {
            $('.contact-form').prepend('<div class="loading-spinner"><img src="/wp-content/themes/Avada-Child/assets/images/Spinner.gif"> </div>');
            setTimeout(function () {
                if ($('.wpcf7-acceptance-as-validation').hasClass('sent')) {
                    // $('.wpcf7-response-output.success').remove();
                    // $('.loading-spinner').hide();
                }

                if ($('.wpcf7-acceptance-as-validation').hasClass('invalid')) {
                    $('.loading-spinner').hide();
                }

            }, 5000);
        });

        $('.schade .wpcf7-submit').on('click', function () {
            setTimeout(function () {
                if ($('.wpcf7-acceptance-as-validation').hasClass('sent')) {
                    $('.fusion-alert.fusion-success .fusion-alert-content-wrapper .fusion-alert-content').text('Bedankt, uw bericht is succesvol verzonden.');
                }
            }, 5000);
        });

        // $('.fusion-flyout-menu').append('<div class="menu-cover-title">Menu</div>');
        // *********************************************************************************

        // Equal heights
        $.fn.equalHeights = function () {
            var max_height = 0;
            $(this).each(function () {
                max_height = Math.max($(this).height(), max_height);
            });
            $(this).each(function () {
                $(this).height(max_height);
            });
        };

        $('.equal-height').equalHeights();

        // Cijfers en indexen
        $('.insufeed-category').click(function (e) {
            e.preventDefault();

            $(this).siblings('.cijfers-content-container').slideToggle();

        });

        // Lord icons
        if ($('lord-icon').length) {

            $('.trigger-hover').on('mouseenter', function (e) {
                $(this).find('lord-icon').attr('trigger', 'loop');
            });

            $('.trigger-hover').on('mouseleave', function () {
                $(this).find('lord-icon').attr('trigger', '');
            });
        }

        // News clicktrough
        $('.latest-news-item .card').on('click', function () {
            var url = $(this).find('a').attr('href');
            window.location = url;
        });


        // Animations

        var t = 0.2;
        $('.delay').each(function (i) {
            $(this).css('animation-delay', t + 's');
            t = t + 0.2;
        });

        // Animation Callup (always on bottom of this script !!!!)
        WOW.prototype.addBox = function (element) {
            this.boxes.push(element);
        };

        // Init WOW.js and get instance
        var wow = new WOW();
        wow.init();

        // Attach scrollSpy to .wow elements for detect view exit events,
        // then reset elements and add again for animation
        $('.wow').on('scrollSpy:exit', function () {
            $(this).css({
                'visibility': 'hidden',
                'animation-name': 'none'
            }).removeClass('animated');
            wow.addBox(this);
        }).scrollSpy();

        new WOW().init();

        // *********************************************************************************

    });

})(jQuery);