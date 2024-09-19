(function ($) {
    $(document).ready(function () {

        // Get the Swiper container
        var swiperContainer = $('.reviews-swiper .swiper-wrapper');

        // Set the height of slides to the maximum height
        function setSlideHeight() {
            var maxHeight = 0;
            swiperContainer.find('.swiper-slide').each(function () {
                var slideHeight = $(this).outerHeight();
                if (slideHeight > maxHeight) {
                    maxHeight = slideHeight;
                }
            });
            swiperContainer.find('.swiper-slide').css('height', maxHeight + 'px');
        }

        const swiper = new Swiper('.reviews-swiper', {
            slidesPerView: 1,
            spaceBetween: 0,
            direction: 'horizontal',

            loop: true,
            // speed: 5000,
            // autoplay: true,
            mousewheel: {
                invert: false,
            },

            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },

            on: {
                init: setSlideHeight, // Set initial height
                slideChange: setSlideHeight // Update height on slide change
            },

        });

    });

})(jQuery);