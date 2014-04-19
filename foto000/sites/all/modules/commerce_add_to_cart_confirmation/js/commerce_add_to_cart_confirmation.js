(function ($) {
    Drupal.theme.prototype.atcloaded= function() {
//        $('.messages--commerce-add-to-cart-confirmation').attr('id', 'atcform')
//            .css('display', 'none')
//            .append('<a href="#atcform" rel="atcform"></a>');
//        $('#atcform [rel="atcform"]').prettyPhoto({
//            animation_speed: 'fast',
//            show_title: false,
//            opacity: 0.6,
//            default_width: 800,
//            default_height: 557,
//            allowresize: true,
//            counter_separator_label: '/',
//            overlay_gallery: 1,
//            theme: 'light_rounded'
//        });
//        setTimeout(function(){$('#atcform [rel="atcform"]').trigger('click');}, 500);

        setTimeout(function tmr(){
            if ($('#userinfo').length)
                setTimeout(function() {
                    $('#userinfo').modal({keyboard: false})
                        .on('hidden.bs.modal', function (e) {
                            $('.messages--commerce-add-to-cart-confirmation').remove();
                        });
                    while ($('#userinfo img').parent().attr('rel') == 'prettyPhoto466[gallery]')
                        $('#userinfo img').unwrap();
                    $('#userinfo .btn-primary').click(function(){
                        location.assign("/cart");
                    });
                }, 10);
            else setTimeout(tmr, 1000);
        }, 10);


    }

  Drupal.behaviors.commerce_add_to_cart_confirmation_overlay = {
    attach:function (context, settings) {
//      if ($('.commerce-add-to-cart-confirmation').length > 0) {
//        // Add the background overlay.
//        $('body').append("<div class=\"commerce_add_to_cart_confirmation_overlay\"></div>");
//
//        // Enable the close link.
//        $('.commerce-add-to-cart-confirmation-close').live('click touchend', function(e) {
//          e.preventDefault();
//          $('.commerce-add-to-cart-confirmation').remove();
//          $('.commerce_add_to_cart_confirmation_overlay').remove();
//        });
//      }

//        jQuery('[id|=edit-submit]').click(function(){
//            setTimeout(function tmr(){
//                if (jQuery('.messages--commerce-add-to-cart-confirmation').length) {
//                    setTimeout(function(){
//
//                    }, 10);
//                } else
//                    setTimeout(tmr, 1000);
//            } ,10);
//        });

    }
  }
})(jQuery);

