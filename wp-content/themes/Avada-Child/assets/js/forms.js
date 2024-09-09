(function ($) {
    $(document).ready(function () {
        // Schadeformulier
        $('.cform .fusion-button:not(.wpcf7-submit)').parent().addClass('p-flex');

        $('.c_select').each(function () {
            $(this).find('option:first').val('');
        });

        $('.schade-form form .contact-form').each(function () {
            $(this).prepend('<div class="percent-bar"><span class="percent"></span> </div)');
        });


        $('select#schade-options').on('change', function () {
            $('.contact-form.active').removeClass('active wow fadeInUp animated');
            var value = $(this).val();
            var cf7 = $('#' + value);
            $(cf7).addClass('active wow fadeInUp animated');
            $(cf7).find('.reset-button').trigger('click');
            $(cf7).find('.cform').hide();
            $(cf7).find('.cform.one').show();


            var original_width = $(cf7).find('.cform').length;
            original_width = $(cf7).find('.percent-bar').width() / original_width
            $(cf7).find('.percent').css('width', original_width)
            $('.c_select option:first').val('');
        });

        $('.cform a.next').on('click', function () {

            var empty = $(this).closest('.cform').find('.wpcf7-validates-as-required').filter(function () {
                return this.value === "";
            });
            if (empty.length) {
                $(this).closest('.cform').find('.wpcf7-validates-as-required').addClass('red-border');
            } else {
                $(this).closest('.cform').find('.wpcf7-validates-as-required').removeClass('red-border');
                var current = $(this).closest('.cform');
                $(current).addClass('animated fadeOut');
                $(this).removeClass('red-border');

                var percent_value = $(this).closest('.contact-form').find('.cform').length;
                var percent_value_number = $(this).closest('.contact-form').find('.percent-bar').width() / percent_value;

                var percent_recent = $(this).closest('.contact-form').find('.percent').width();
                percent_recent = percent_recent + percent_value_number;
                $(this).closest('.contact-form').find('.percent').css('width', percent_recent);


                setTimeout(function () {
                    $(current).hide();
                    $(current).removeClass('animated fadeOut');
                    $(current).next('.cform').show();

                }, 1000);
            }
        });

        $('.cform a.back').on('click', function () {
            var current = $(this).closest('.cform');
            $(current).addClass('animated fadeOut');

            var percent_value = $(this).closest('.contact-form').find('.cform').length;
            var percent_value_number = $(this).closest('.contact-form').find('.percent-bar').width() / percent_value;

            var percent_recent = $(this).closest('.contact-form').find('.percent').width();
            percent_recent = percent_recent - percent_value_number;
            $(this).closest('.contact-form').find('.percent').css('width', percent_recent);

            setTimeout(function () {
                $(current).hide();
                $(current).removeClass('animated fadeOut');
                $(current).prev('.cform').show();
            }, 1000);
        });
    });

})(jQuery);
