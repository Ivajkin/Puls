textarea_resize= function(){
    true_h= 0.975103734439834;
    true_w= 2-0.9928678676888749;
    tw= $('.allend .feedback-form textarea').width()+
        parseFloat($('.allend .feedback-form textarea').css('padding-right'))+
        parseFloat($('.allend .feedback-form textarea').css('padding-left'));

    $('.allend .feedback-form>div').eq(2).css('height',
        $('.allend .feedback-form>div').eq(0).height()*true_h+'px');

    $('.allend .feedback-form textarea').css('height', (
        $('.allend .feedback-form>div').eq(2).height()- ( $('.allend .feedback-form .chk-wrapper').height() + parseInt($('.allend .feedback-form .chk-wrapper').css('margin-bottom')) +
                                                            $('.allend .feedback-form button').height() +
                                                            parseInt($('.allend .feedback-form textarea').css('padding-top')) )
    )+'px');
    $('.allend .feedback-form button').css('width', (tw*true_w+1)+'px');

    console.log(tw*true_w);
}
captcha_resize= function(){
    coef= 2-50/52;
    obj= $('.LBD_CaptchaImageDiv img');

    baseh= obj.parents('.LBD_CaptchaDiv').height();
    obj.css('height', (coef*baseh)+'px');
}

jQuery(function($){
    $('.feedback-form input[name="Phone"]').mask("+7 9999-999-999");
});

$('.feedback-form input[type="text"], .feedback-form textarea').focus(function(){
    if ($(this).val() == $(this).data('def'))
        $(this).val('');
});
$('.feedback-form input[type="text"], .feedback-form textarea').focusout(function(){
    if (!$(this).val()) {
        $(this).val($(this).data('def'));
    }
});

$('.feedback-form button').mouseenter(function(){
    $(this).css('background-color', '#620C72');
});
$('.feedback-form button').mouseleave(function(){
    $(this).css('background-color', '#580068');
});
$('.feedback-form button').mousedown(function(){
    $(this).css({
        'background-color': '#74038A',
        '-webkit-box-shadow': '0px 0px 40px rgba(133, 33, 112, 0.75)',
        '-moz-box-shadow':    '0px 0px 40px rgba(133, 33, 112, 0.75)',
        'box-shadow':         '0px 0px 40px rgba(133, 33, 112, 0.75)'
    });
});
$('.feedback-form button').mouseup(function(){
    $(this).css({
        'background-color': '#580068',
        '-webkit-box-shadow': 'none',
        '-moz-box-shadow':    'none',
        'box-shadow':         'none'
    });
});
$('.feedback-form button').click(function(index){
    msg= '';
    $('.feedback-form input[type="text"]').each(function(){
        if( $(this).val() == $(this).data('def')) {
            msg+= '*  '+$(this).data('def')+'\n\r';
            $(this).css('border-color', 'red');
        } else
            $(this).css('border-color', '#C8C8C8');
    });
    if (msg.length) {
        alert('Пожалуйста заполните следующие поля:\n\r'+msg);
        return;
    }

    msg= $('.feedback-form input[name="Email"]').val().match(/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i);
    if (!msg) {
        $('.feedback-form input[name="Email"]').css('border-color', 'red');
        alert('Указанный e-mail не соответствует формату.\n\rПожалуйста введите его снова.');
        return;
    } else
        $('.feedback-form input[name="Email"]').css('border-color', '#C8C8C8');

    gopost();
});

$(document).ready(function () {
    textarea_resize();
    captcha_resize();
});
$(window).resize(function () {
    textarea_resize();
    captcha_resize();
});



// Define the name of the Captcha field.
// It serves to access BotDetect Captcha client-side API later.
// http://captcha.com/doc/php/api/captcha-client-side-reference.html
var captchaname = '.feedback-form input[name="captchacode"]';
var captchaUserInputId = 'captchacode';
// AJAX argument is added to differentiate from regular POST.
var validationUrl = "service.php?AJAX=1";
// Collect form elements we want to handle.
var formElements = $('.feedback-form input[type="text"], .feedback-form textarea');
//var form = $('#contactForm');

gopost= function(){
    var postData = {};
    formElements.each( function(){
        if(this.id == captchaUserInputId){
            // In case of our Captcha field, we also send the InstanceId
            captchaUserInputField = $('#' + captchaUserInputId).get(0);
            postData["CaptchaInstanceId"] = captchaUserInputField.Captcha.InstanceId;
        }
        postData[$(this).attr('name')] = $(this).val();
    });
    postData['ckbox']= [];
    $('.feedback-form .chk-box input[type="checkbox"]:checked').parent().each(function(){
        postData['ckbox'].push($(this).text());
    });
console.log('BEFORE');
    console.log(postData);
    $.post(validationUrl, postData, postValidation);
    return false;
}
function postValidation(data, status){
    console.log(status);
    console.log(data);
    if (data[captchaUserInputId]){

        // Get the Captcha instance, as per client side API
        captcha = $('#' + captchaUserInputId).get(0).Captcha;


        if(data[captchaUserInputId]["isValid"]){
            $(captchaname).css('border-color', '#C8C8C8');
            // We disable the Captcha entry if the user already solved it
            //$("#" + captchaUserInputId).attr("disabled", "disabled");
            //$("#" + captchaUserInputId).parent().remove();
        }else{
            $(captchaname).css('border-color', 'red');
            // We want to get another image if the Captcha validation failed.
            // User gets one try per image.
            captcha.ReloadImage();
            alert('Код с картинки введён неверно. Пожалуйста, попробуйте ещё раз.');
            return;
        }
    }

    if (data["Form"] && data["Form"]["isValid"]){
        //$("#SubmitButton").attr("disabled", "disabled");
        alert('Сообщение успешно отправлено!');
        captcha.ReloadImage();
    } else {
        delete data["Form"];
        //console.log(data);
        for (val in data)
            if (!data[val]['isValid']) {
                alert('Введённые данные слишком большие или не соответствуют допустимому формату.Попробуйте:'+
                    '\n\r* указать другой e-mail' +
                    '\n\r* уменьшить сообщение,'+
                    '\n\r* вместо имени и отчества использовать инициалы'
                );
                return;
            }
        alert('Указанный адрес электроной почты недоступен или не существует. Пожалуйста попробуйте снова или укажите другой e-mail.')
    }
    //updateValidatorMessages(data);
}


$(document).ready(function () {

    /*tmp= captcha.PostReloadImage;
    captcha.PostReloadImage= function() {
        tmp();
        captcha_resize();

    }*/

    $('.feedback-form .LBD_CaptchaDiv, .feedback-form .LBD_CaptchaImageDiv, .feedback-form .LBD_CaptchaIconsDiv').removeAttr('style');

    $('.feedback-form .LBD_CaptchaImageDiv a').attr({'href': 'javascript:void(0)', 'target': '_self', 'title': 'Нажмите для обновления картинки'})
        .click(function(){
            ContactCaptcha.ReloadImage();
            return false;
        });
    /*tmpfunc= ContactCaptcha.ReloadImage;
     ContactCaptcha.ReloadImage= function(){
     $('.feedback-form .LBD_CaptchaImageDiv img').appendTo('.feedback-form .LBD_CaptchaImageDiv a');
     tmpfunc();
     }
     $('.feedback-form .LBD_CaptchaImageDiv').ready(function () {
     $('.feedback-form .LBD_CaptchaImageDiv img').appendTo('.feedback-form .LBD_CaptchaImageDiv');
     });*/
});

/*******************
 *********MAIN PART CODE************
 **************************/

infostatus= true;
infoclick= function(event){
    i= 0;
    fxtime= 800;
    sectcount=7;

    $('.main article>div').toggleClass('sect-more-wrapper');
    if (infostatus) {
        $('.main article section').not($(this)).slideToggle('slow', function(){
            if (i++ == sectcount) {
                $(event.currentTarget).toggleClass('sect-more')
                    .toggleClass('clearfix')
                    .children('div').fadeToggle('slow', function(){
                        infostatus= !infostatus;
                        fix_resize();
                        $(event.currentTarget).children('h3').transition ({
                            'left': '0%'
                        }, 800,
                            'cubic-bezier(0,0.9,0.3,1)',
                           function(){$(this).css('position', 'static');}
                        );
                    });
            }
        });
    } else {
        $(this).children('div').fadeToggle('slow', function(){
            $(event.currentTarget).toggleClass('sect-more')
                .toggleClass('clearfix');
            $('.main article section').not($(event.currentTarget)).slideToggle('slow', function(){
                if (i++ == sectcount) {
                    infostatus= !infostatus;
                    fix_resize();
                }
            });
        });
    }
}
    $('.main article section').on('click', infoclick);
    $('.main article section>div').on('click', function(event){
        event.stopImmediatePropagation();
        //9$(this).css('background', '#fff');
    });

$('.main section .site-ext>div').mouseenter( function(){
    idprev= parseInt($('.main section .site-ext>div.hovered').data('id'));
    idnext= parseInt($(this).data('id'));
    hidetime= 600;

    $('.main section .site-ext>div.hovered').toggleClass('hovered');
    $(this).toggleClass('hovered');

    $('.main section .site-data>div').eq(idprev).fadeOut(hidetime, function(){
        $('.main section .site-data>div').eq(idnext).fadeIn(hidetime*0.7, function(){
            fix_resize();
        });
    });
});

/*function animateScroll(name) {
    $(name).animate({"scrollTop":(Math.floor(Math.random()*3000))},400);
}
$(document).ready(function () {
    $(".main section .site-data").niceScroll(".main section .site-data",{
        cursorcolor:"#800098",
        cursoropacitymin: 0,
        cursoropacitymax: 0.8,
        cursorborderradius: 0,
        touchbehavior: true,
        hwacceleration: true,
        autohidemode: true,
        oneaxismousemode: 'vertical'
    });*/
    /*(".allend article").scroll(function(e) {
     $(".allend").html($(".allend article").scrollTop());
     });*/

/*})*/