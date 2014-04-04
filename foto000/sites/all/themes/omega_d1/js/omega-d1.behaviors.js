var mediaq= 0;
var mediawidthmobile = 480;
var mediawidthmin = 768;
var mediawidth1 = 870;
var mediawidthmax = 1140;

(function ($) {

  /**
   * The recommended way for producing HTML markup through JavaScript is to write
   * theming functions. These are similiar to the theming functions that you might
   * know from 'phptemplate' (the default PHP templating engine used by most
   * Drupal themes including Omega). JavaScript theme functions accept arguments
   * and can be overriden by sub-themes.
   *
   * In most cases, there is no good reason to NOT wrap your markup producing
   * JavaScript in a theme function.
   */
  Drupal.theme.prototype.omegaD1ExampleButton = function (path, title) {
    // Create an anchor element with jQuery.
    return $('<a class="test" href="' + path + '" title="' + title + '">' + title + '</a>');
  };

  /**
   * Behaviors are Drupal's way of applying JavaScript to a page. In short, the
   * advantage of Behaviors over a simple 'document.ready()' lies in how it
   * interacts with content loaded through Ajax. Opposed to the
   * 'document.ready()' event which is only fired once when the page is
   * initially loaded, behaviors get re-executed whenever something is added to
   * the page through Ajax.
   *
   * You can attach as many behaviors as you wish. In fact, instead of overloading
   * a single behavior with multiple, completely unrelated tasks you should create
   * a separate behavior for every separate task.
   *
   * In most cases, there is no good reason to NOT wrap your JavaScript code in a
   * behavior.
   *
   * @param context
   *   The context for which the behavior is being executed. This is either the
   *   full page or a piece of HTML that was just added through Ajax.
   * @param settings
   *   An array of settings (added through drupal_add_js()). Instead of accessing
   *   Drupal.settings directly you should use this because of potential
   *   modifications made by the Ajax callback that also produced 'context'.
   */
  Drupal.behaviors.omegaD1ExampleBehavior = {
    attach: function (context, settings) {
      // By using the 'context' variable we make sure that our code only runs on
      // the relevant HTML. Furthermore, by using jQuery.once() we make sure that
      // we don't run the same piece of code for an HTML snippet that we already
      // processed previously. By using .once('foo') all processed elements will
      // get tagged with a 'foo-processed' class, causing all future invocations
      // of this behavior to ignore them.
      $('.some-selector', context).once('foo', function () {
        // Now, we are invoking the previously declared theme function using two
        // settings as arguments.
        var $anchor = Drupal.theme('omegaD1ExampleButton', settings.myExampleLinkPath, settings.myExampleLinkTitle);

        // The anchor is then appended to the current element.
        $anchor.appendTo(this);
      });
    }
  };

    /**
     * Main behaviors
     * @type {{attach: attach}}
     */
  Drupal.behaviors.omegaD1BxSlider = {
        attach: function (c, s) {
            $('.l-page', c).once('bxon', function () {

                mw = $('.l-main').width();
                slmargin = 0.05;
                slwidth = 1-slmargin;

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

                    if($('.hoverBox').length){
                        //prettyPhoto
                        $('a[rel^=\'prettyOverlay\'],a[rel^=\'prettyPhoto466\']').prettyPhoto({
                            animation_speed: 'fast',
                            show_title: false,
                            opacity: 0.6,
                            allowresize: true,
                            counter_separator_label: '/',
                            overlay_gallery: 1,
                            theme: 'dark_rounded'
                        });
                    }
                }
            });
        }
    };
  Drupal.behaviors.omegaD1Transition = {
      attach: function(c, s) {
          $('.l-header', c).once('tranc', function(){
              if (!$.support.transition)
                  $.fn.transition = $.fn.animate;
              $(".head li>a").mouseenter(function(){
                  $(this).stop(true,true);
                  $(this).transition({
                      scale: 1.3,
                      rotate: 12,
                      duration: 500,
                      easing: 'cubic-bezier(0,0.9, 0.001,2)',
                      complete: function() { /* ... */ }
                  });
              });
              $(".head li>a").mouseleave(function(){
                  $(this).stop(true,true);
                  $(this).transition({
                      scale: 1,
                      rotate: 0,
                      duration: 500,
                      easing: 'out',
                      complete: function() { /* ... */ }
                  });
              });
          });
      }
  }
  Drupal.behaviors.omegaD1Footer = {
      attach: function(c, s) {
          $('.l-footer', c).once('tm', function() {
              $(this).find(".copyright img").mouseover(function(){
                  $(this).stop(true,true);
                  $(this).transition({
                      perspective: '500px',
                      //rotateX: 360,
                      rotateY: 180,
                      duration: 500,
                      easing: 'in'
                  })
                      .transition({
                          //perspective: '500px',
                          //rotateX: 360,
                          rotateY: 0,
                          duration: 500,
                          easing: 'out'
                      });
              });
          });
      }
  }

})(jQuery);