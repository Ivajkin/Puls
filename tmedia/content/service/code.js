$(document).ready(function () {
    mw = $('.main').width();
    slmargin = 0.05
    slwidth = 1-slmargin;

    /*// This function runs before the slide transition starts
     var switchIndicator = function ($c, $n, currIndex, nextIndex) {

     $('.time-indicator').stop().css('width', 0);  // kills the timeline by setting it's width to zero
     };

     // This function runs after the slide transition finishes
     var startTimeIndicator = function () {

     $timeIndicator.animate({width: '100%'}, 10000);          // start the timeline animation
     };*/

    setting = {
        /****************
         * General
         ****************/
        //mode: 'horizontal', /**/
        //speed: 500, /**/
        slideMargin: slmargin * mw / 3.0,
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
        pause: 10000,
        //autoStart: true, /**/
        /*autoDirection: 'next'*/
        autoHover: true,
        /**********************
         * Carousel
         **********************/
        minSlides: 1,
        maxSlides: 3,
        moveSlides: 1,
        slideWidth: slwidth * mw / 3.0,
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
    var slider = $('#bxslider-0').bxSlider(setting);

    //startTimeIndicator(); // start the time line for the first slide

    $(window).resize(function () {
        mw = $('.main').width();
        tmp= $('body').width();
        if (tmp >= mediawidthmin && tmp < mediawidthmax) {
            setting.slideMargin = slmargin * mw / 3.0;
            setting.slideWidth = slwidth * mw / 3.0;
            slider.reloadSlider(setting);
        }
    });

    fix_resize();
});


/*
 ;(function (w, $, undefined) {

 var $box = $('#content-box')
 , $indicators = $('.goto-slide')
 , $timeIndicator = $('.time-indicator')
 , $slides = $('#content-box figure')
 , slideInterval = 3000
 , effectOptions = {
 'blindLeft': {blindCount: 15}
 , 'blindDown': {blindCount: 15}
 , 'tile3d': {tileRows: 6, rowOffset: 80}
 , 'tile': {tileRows: 6, rowOffset: 80}
 , 'perspective': 1000
 };
 // initialize the plugin with the desired settings
 settings= {
 speed: 1000
 , autoScroll: true
 , timeout: slideInterval
 , pauseOnHover: true
 , next: '.next'
 , prev: '.prev'
 , onbefore: switchIndicator
 , onafter: startTimeIndicator
 };
 $box.boxSlider(settings);

 w.jqBoxSlider.registerAnimator('3dfix', (function () {

 var adaptor = {};

 // setup slide and box css
 adaptor.initialize = function ($box, $slides, settings) {
 // cache the original css for reset or destroy
 adaptor._cacheOriginalCSS($box, 'box', settings);
 adaptor._cacheOriginalCSS($slides, 'slides', settings);

 if ('static auto'.indexOf($box.css('position')) !== -1) {
 $box.css('position', 'relative');
 }

 $slides
 .css({ position: 'relative', top: 0, left: 0, width: '200px', height: '120px' })
 .filter(':gt(0)').hide();
 $box.css({height: $slides.eq(0).height()});
 };

 // fade current out and next in
 adaptor.transition = function (settings) {
 settings.$nextSlide.fadeIn(settings.speed);
 settings.$currSlide.fadeOut(settings.speed);
 };

 // reset the original css
 adaptor.destroy = function ($box, settings) {
 $box.children().css(settings.origCSS.slides);
 $box.css(settings.origCSS.box);
 };

 return adaptor;

 }()));

 $box.boxSlider('option', 'effect', '3dfix');
 $box.boxSlider('option', 'effect', 'scrollHorz3d');

 // This function runs before the slide transition starts
 var switchIndicator = function ($c, $n, currIndex, nextIndex) {
 // kills the timeline by setting it's width to zero
 $timeIndicator.stop().css('width', 0);
 // Highlights the next slide pagination control
 $indicators.removeClass('current').eq(nextIndex).addClass('current');
 };

 // This function runs after the slide transition finishes
 var startTimeIndicator = function () {
 // start the timeline animation
 $timeIndicator.animate({width: '300px'}, slideInterval);
 };

 startTimeIndicator(); // start the time line for the first slide

 // Paginate the slides using the indicator controls
 /*$('.controls').on('click', '.goto-slide', function (ev) {
 $box.boxSlider('showSlide', $(this).data('slideindex'));
 ev.preventDefault();
 });

 }(window, jQuery || Zepto));
 $('#content-box').boxSlider( /* options */ /*);*/
