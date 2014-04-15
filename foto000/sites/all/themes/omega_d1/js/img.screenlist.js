(function ($) {

    Drupal.theme.prototype.omegaD1ApplySE = function (path, title) {
        // Create an anchor element with jQuery.
        if($('.hoverBox').length){
            //prettyPhoto old
            $('a[rel^=\'prettyOverlay\'],a[rel^=\'prettyPhoto466\']').prettyPhoto({
                animation_speed: 'fast',
                show_title: false,
                opacity: 0.6,
                default_width: 800,
                default_height: 557,
                allowresize: true,
                counter_separator_label: '/',
                overlay_gallery: 1,
                theme: 'light_rounded'
            });
//            $("a[rel^='prettyPhoto']").prettyPhoto({
//                animation_speed: 'fast',
//                show_title: false,
//                opacity: 0.6,
//                allowresize: true,
//                counter_separator_label: '/',
//                overlay_gallery: true,
//                theme: 'light_rounded'
//            });
            return true;
        }
        return false;
    };

    /**
     * Image Gallery behaviors
     * @type {{attach: attach}}
     */
  Drupal.behaviors.omegaD1ImageScreen = {
      attach: function(c, s) {
          $('.view-content', c).once('scr-hp', function(){
            if ($('.field--screen-list img').length) {
                $('.field--screen-list a').attr('href', $('.field--screen-list img').attr('src'))
                    .attr('rel', 'prettyPhoto466');
                Drupal.theme('omegaD1ApplySE');
            }
          });
      }
  }
})(jQuery);