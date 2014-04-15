(function ($) {

    Drupal.theme.prototype.omegaD1ApplyGE = function () {
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
  Drupal.behaviors.omegaD1ImageEffect = {
      attach: function(c, s) {
          $('.view-content', c).once('img-hp', function(){
            $('.field--image img').addClass('hoverBox')
                //old
                .before('<a href="#" rel="prettyPhoto466[gallery]"></a>')
                //.before('<a href="#" rel="prettyPhoto[pp_gal]"></a>')
                .each(function(){
                    $(this).appendTo($(this).prev());
                    //get id
                    //tmp= s.gallery[ $('.view-content table').index($(this).parents('table')) ][0];
                    tmp= $(this).parents('.field--image');

                    if (location.href.match(/\/album/) || location.href.match(/\/gallery/)) {
                        $(this).parent().attr('href', tmp.data('full'));
                        tmp.removeAttr('data-full');
                    }
                    else /*if (location.href.match('catalog'))*/ {
                        tmp2= $(this).attr('src').replace('styles/medium/public/', '').match(/.*(?=\?itok)/)[0];
                        $(this).parent().attr('href', tmp2);

                        if ($(this).parent().attr('rel') == $(this).parent().parent().attr('rel'))
                            $(this).parent().unwrap();
                    }
                });
            Drupal.theme('omegaD1ApplyGE');
          });
      }
  }
})(jQuery);