(function ($) {
    $(document).ready(function () {

        function setMinHeight() {
            var wrapperHeight = $('.height-check-column .fusion-column-wrapper').outerHeight();

            $('.height-check-column').css('min-height', wrapperHeight + 'px');
        }

        setMinHeight();

        $(window).resize(function() {
            setMinHeight();
        });

        setTimeout(function () {
            $('.side-menu').addClass("loaded");
        }, 800);

        function checkWidth() {
            $(window).off('scroll');

            if ($(window).width() > 1126) {
                // Bind scroll event for larger screens
                $(window).on('scroll', function () {
                    checkMenuPosition();

                    if ($(this).scrollTop() > 168) {
                        $('.fusion-menu-header').addClass('sticky-header fadeInDown animated');
                    } else {
                        $('.fusion-menu-header').removeClass('sticky-header fadeInDown animated');
                    }
                });
            } else {
                $('.fusion-menu-header').removeClass('sticky-header fadeInDown animated');
            }
        }

        function checkMenuPosition() {
            var menu = $('.home .custom-menu-row');
            var header = $('.home .fusion-menu-header');

            if ($(window).width() > 1126) {

                if (menu.length) {
                    var menuOffset = menu.offset().top;
                    var windowScroll = $(window).scrollTop();


                    if (windowScroll > menuOffset) {
                        header.addClass('sticky-active fadeInDown animated');
                    } else {
                        header.removeClass('sticky-active fadeInDown animated');
                    }
                }
            }
        }


        $(document).ready(function () {
            checkWidth();
            checkMenuPosition();
        });

        $(window).resize(function () {
            checkMenuPosition();
            checkWidth();
        });

        $(window).on('scroll resize', function () {
            checkMenuPosition();
        });


        const swiper = new Swiper('.partners-swiper', {
            slidesPerView: 6,
            spaceBetween: 30,
            direction: 'horizontal',

            loop: true,
            speed: 800,
            autoplay: {
                delay: 2000,
                disableOnInteraction: false,
            },

            freeMode: true,
            freeModeMomentum: false,
            freeModeMomentumBounce: false,

            freeModeSticky: true,

            breakpoints: {
                0: {
                    slidesPerView: 3
                },

                768: {
                    slidesPerView: 3
                },
                1024: {
                    slidesPerView: 6
                },
                1440: {
                    slidesPerView: 8
                }
            }

        });

        function setEqualHeight() {
            var maxHeight = 0;

            $('.cta-column').each(function () {
                var currentHeight = $(this).outerHeight();
                if (currentHeight > maxHeight) {
                    maxHeight = currentHeight;
                }
            });

            $('.cta-column').css('height', maxHeight + 'px');
        }

        if ($('.cta-column').length > 0) {
            setEqualHeight();
        }

        $('.insurance-button').on('click', function (e) {
            e.preventDefault();
            $('.insurance-button.active').removeClass('active');
            $(this).addClass('active');

            var currentHeight = $('.toggle-column').height();

            $('.toggle-column').css('min-height', currentHeight + 'px');

            setTimeout(function () {
                $('.panel-collapse.in').removeClass('in');
            }, 800);
        });

        $('.insu-particulieren').on('click', function () {
            $('.fusion-accordian.active').fadeOut('slow');

            setTimeout(function () {
                $('.toggle-particulieren').addClass('active');
                $('.toggle-particulieren').fadeIn('slow');
                $('.toggle-column').css('min-height', '0');
            }, 800);
        });

        $('.insu-kmo').on('click', function () {
            $('.fusion-accordian.active').fadeOut('slow');

            setTimeout(function () {
                $('.toggle-kmo').addClass('active');
                $('.toggle-kmo').fadeIn('slow');
                $('.toggle-column').css('min-height', '0');
            }, 800);
        });

        $('img').hover(function () {
            $(this).data('title', $(this).attr('title')).removeAttr('title');
        }, function () {
            $(this).attr('title', $(this).data('title'));
        });

        var page = 2;
        jQuery(function ($) {
            jQuery('body').on('click', '.loadmore', function () {
                var data = {
                    'action': 'load_posts_by_ajax',
                    'page': page,
                };
                jQuery.post(blog.ajaxurl, data, function (response) {
                    if ($.trim(response) != '') {
                        jQuery('.news-row-overview').append(response);
                        page++;
                    } else {
                        jQuery('.loadmore').hide();
                        jQuery(".no-more-post").html("Geen verdere weetjes beschikbaar");
                    }
                });
            });
        });

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
        $('.cta-column').hover(
            function () {
                $(this).addClass('cta-hover');  // mouse enters
            },
            function () {
                $(this).removeClass('cta-hover');  // mouse leaves
            }
        );


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