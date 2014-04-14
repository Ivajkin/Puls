/**
 * Created by storm on 4/13/14.
 */
(function ($) {
    Drupal.behaviors.omegaD1BxSlider = {
    attach: function (c, s) {
        $('.l-page', c).once('bxon', function () {

            mw = $('.l-main').width();
            slmargin = 0.05;
            slwidth = 1-slmargin;

            if (location.href.match(/catalog/)) {
                $('.l-content .field--image').unwrap();
            }

            /**
             *
             * This function runs before the slide transition starts
             var switchIndicator = function ($c, $n, currIndex, nextIndex) {

        $('.time-indicator').stop().css('width', 0);  // kills the timeline by setting it's width to zero
        };

             This function runs after the slide transition finishes
             var startTimeIndicator = function () {

        $timeIndicator.animate({width: '100%'}, 10000);          // start the timeline animation
        };
             * @param $c
             * @param $n
             * @param currIndex
             * @param nextIndex
             */

            setting = {
                /****************
                 * General
                 ****************/
                //mode: 'horizontal', /**/
                //speed: 500, /**/
                slideMargin: slmargin * mw / 5.0,
                //startSlide: 0, /**/
                /*slideSelector*/
                //infiniteLoop: true, /**/
                //responsive: true, /**/
                //useCSS: true, /**/
                //preloadImages: 'visible', /**/
                //touchEnabled: true, /**/
                //swipeThreshold: 50, /**/
                //oneToOneTouch: true, /**/
                //preventDefaultSwipeX: true, /****/
                /********************
                 * Pager
                 ********************/
                pager: false,
                /*******************
                 * Controls
                 *******************/
                //controls: true, /**/
                /*nextText: 'Next',
                 prevText: 'Prev'*/
                /*nextSelector:
                 prevSelector:
                 */
                /******************
                 * Auto
                 ******************/
                auto: true,
                pause: 5000,
                //autoStart: true, /**/
                /*autoDirection: 'next'*/
                autoHover: true,
                /**********************
                 * Carousel
                 **********************/
                minSlides: 3,
                maxSlides: 5,
                moveSlides: 3,
                slideWidth: slwidth * mw / 5.0,
                /**********************
                 * Callbacks
                 ******************/
                onSliderLoad: function (curIndex) {
                    $('.bx-controls-direction').toggleClass('invisible');
                    $('.slider-viewport')
                        .mouseenter(function () {
                            $('.bx-controls-direction').toggleClass('invisible');
                        })
                        .mouseleave(function () {
                            $('.bx-controls-direction').toggleClass('invisible');
                        });
                }/*,
                 onSlideBefore: switchIndicator,
                 onSlideAfter: startTimeIndicator*/

            };
            if ($('.bxslider').length) {
                var slider = $('.bxslider').bxSlider(setting);

                //startTimeIndicator(); // start the time line for the first slide

                $(window).resize(function () {
                    mw = $('.slider').width();
                    tmp= $('body').width();
                    //if (tmp >= mediawidthmin && tmp < mediawidthmax) {
                    setting.slideMargin = slmargin * mw / 5.0;
                    setting.slideWidth = slwidth * mw / 5.0;
                    slider.reloadSlider(setting);
                    //}
                });

//                    if($('.hoverBox').length){
//                        //prettyPhoto
//                        $('a[rel^=\'prettyOverlay\'],a[rel^=\'prettyPhoto466\']').prettyPhoto({
//                            animation_speed: 'fast',
//                            show_title: false,
//                            opacity: 0.6,
//                            allowresize: true,
//                            counter_separator_label: '/',
//                            overlay_gallery: 1,
//                            theme: 'dark_rounded'
//                        });
//                    }
            }
        });
    }
};
})(jQuery);