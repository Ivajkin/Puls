/**
 * Created by storm on 4/13/14. Riverside / Sound Couture /One Ok Rock / Foreground Eclipse / Flaming June -- Muteki no Soldier / coldrain // Aikawa Nanase / Kokia / Afilia Saga
 */
var slider= undefined;
var sets= undefined;

(function ($) {
    Drupal.behaviors.omegaD1BxSlider = {
    attach: function (c, s) {
        $('.l-page', c).once('bxon', function () {

            mw = $('.l-main').width();
            slmargin = 0.05;
            slwidth = 1-slmargin;

            if (location.href.match(/catalog/)) {
                $('.l-content .field--image').unwrap();
            } else if ($(s.ismain).parent().hasClass('field-content'))
                $(s.ismain).unwrap();

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

            sets = {
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
                    if (!$('.bx-controls-direction').hasClass('invisible'))
                        $('.bx-controls-direction').toggleClass('invisible');
                    $('.slider-viewport')
                        .mouseenter(function (e) {
                            e.preventDefault();
                            if ($('.bx-controls-direction').hasClass('invisible'))
                                $('.bx-controls-direction').toggleClass('invisible');
                        })
                        .mouseleave(function (e) {
                            e.preventDefault();
                            if (!$('.bx-controls-direction').hasClass('invisible'))
                                $('.bx-controls-direction').toggleClass('invisible');
                        });
                }/*,
                 onSlideBefore: switchIndicator,
                 onSlideAfter: startTimeIndicator*/

            };
            if ($('.bxslider').length) {
                slider = $('.bxslider').bxSlider(sets);

                //startTimeIndicator(); // start the time line for the first slide

                $(window).resize(function () {
                    mw = $('.slider').width();
                    tmp= $('body').width();
                    //if (tmp >= mediawidthmin && tmp < mediawidthmax) {
                    sets.slideMargin = slmargin * mw / 5.0;
                    sets.slideWidth = slwidth * mw / 5.0;
                    slider.reloadSlider(sets);
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