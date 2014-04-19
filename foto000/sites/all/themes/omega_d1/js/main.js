var pid= 0;
var pphoto= undefined;
var itwas = false;

(function ($) {

    /**
     *   Add link to PrettyPhoto
     */
    Drupal.theme.prototype.omegaD1Link = function () {

    }

    Drupal.theme.prototype.omegaD1Main = function () {
        // Create an anchor element with jQuery.
//        $(ismain).each(function(){
//            if (!$(this).hasClass('imgrow-0')) $(this).css('display', 'none');
//        });

        $('.view-display-id-top_pane .content section').click(function(){
            id= $('.view-display-id-top_pane .content section').index($(this));
            slider.destroySlider();
            $('.bxslider').empty()
                .append(Drupal.settings.imgrow[id]);

            count= Drupal.settings.imgrow[id].match(/<img/g).length;
            ww= $('.popular-container footer').width() / 5.;

            $('.popular-container footer img').stop( true, true ).transition({ x: (id*ww)+'px'}, 500, 'ease', function(){

            });
            slider.reloadSlider(sets);
            pphoto();
            Drupal.theme('omegaD1Link');

            pid= id;

//            setTimeout(function(){$('.popular-container footer img').stop( true, true ).transition({ x: (id*ww)+'px'}, 500, 'ease');}, 10);
//            $( ".popular-container footer img" ).animate({
//                left: (id>pid ? "+=" : "-=") + (id>pid ? id-pid : pid-id)*ww
//            }, 500);
//            pid= id;
/*
* $databases = array (
 'default' =>
 array (
 'default' =>
 array (
 'database' => 'core5429_foto001',
 'username' => 'core5429_foto001',
 'password' => '01remoute92',
 'host' => 'localhost',
 'port' => '',
 'driver' => 'mysql',
 'prefix' => '',
 ),
 ),
 );
* */
//            Drupal.behaviors.omegaD1ImageEffect.attach(function(){
//                setTimeout(function tmr(){
//                    if ($('.bxslider img').length == count)
//                         setTimeout(function () {slider.reloadSlider(sets);}, 10);
//                    else setTimeout(tmr, 1000);
//                }, 10);
//            });
            $('body').animate({scrollTop: $('.popular-container').position().top},500);
        });
    };

    /**
     * Image Gallery behaviors
     * @type {{attach: attach}}
     */

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
                    theme: 'light_rounded',
                    social_tools: '<div class="pp_social"><div class="goto-product"><a href="#"><span>Подробное</span> <span>описание</span></a></div><div class="twitter"><a href="http://twitter.com/share" class="twitter-share-button" data-count="none">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div><div class="facebook"><iframe src="http://www.facebook.com/plugins/like.php?locale=en_US&href='+location.href+'&amp;layout=button_count&amp;show_faces=true&amp;width=500&amp;action=like&amp;font&amp;colorscheme=light&amp;height=23" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:500px; height:23px;" allowTransparency="true"></iframe></div></div>',
                    showen: function() {
                            $('.goto-product a').attr('href', Drupal.settings.imgpath[pid]);
                    }
                });

            if (!itwas) {
                Drupal.theme('omegaD1Main');
                Drupal.theme('omegaD1Link');
                itwas= true;
            }

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
        attach: function(call) {
            //$('.view-content', c).once('img-hp', function(){
            $('.view-display-id-top_pane .field--image img').addClass('hoverBox')
                //old
                .before('<a href="#" rel="prettyPhoto466[gallery]"></a>')
                //.before('<a href="#" rel="prettyPhoto[pp_gal]"></a>')
                .each(function(){
                    $(this).appendTo($(this).prev());
                    //get id
                    //tmp= s.gallery[ $('.view-content table').index($(this).parents('table')) ][0];
                    tmp= $(this).parents('.field--image');

                    tmp2= $(this).attr('src').replace('styles/medium/public/', '').match(/.*(?=\?itok)/)[0];
                    $(this).parent().attr('href', tmp2);

                    if ($(this).parent().attr('rel') == $(this).parent().parent().attr('rel'))
                        $(this).parent().unwrap();
                });
            Drupal.theme('omegaD1ApplyGE');
            //});
            if (typeof call == 'function') call();
        }
    }

    pphoto= Drupal.behaviors.omegaD1ImageEffect.attach;


})(jQuery);