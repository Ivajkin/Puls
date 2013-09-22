/*fix_mh= function() {
 ah= $('.main>article').height();
 dh= $('.main>div').height();

 $('.main').css('height', '900px');
 }

 $(document).ready(function(){fix_mh();    fix_resize();});
 $(window).resize(function(){fix_mh();    fix_resize();});*/

$(document).ready(function () {
    $('.main>div>div').mouseenter( function(){
        idprev= parseInt($('.main div.hovered').data('id'));
        idnext= parseInt($(this).data('id'));
        hidetime= 600;

        $('.main div.hovered').toggleClass('hovered');
        $(this).toggleClass('hovered');

        $('.main section').eq(idprev).fadeOut(hidetime, function(){
            $('.main section').eq(idnext).fadeIn(hidetime*0.7);
        });
    });
})
;