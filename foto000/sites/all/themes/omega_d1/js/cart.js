/**
 * Created by storm on 4/16/14.
 */

(function ($) {

    Drupal.theme.prototype.htmlEncode= function(value){
        if (value) {
            return $('<div />').text(value).html();
        } else {
            return '';
        }
    }
    Drupal.theme.prototype.escape= function(v){
        if (v) {
            return v.trim().split('<').join('##1').split('>').join('##2');
        }
    }

    Drupal.behaviors.omegaD1CartHandle = {
        attach:function (context, settings) {
            $('.l-content h1').text('Ваши покупки');
            settings.sended= 1;

            $('.views-field-edit-quantity input[type="text"]').change(function () {
                $('#edit-submit').trigger('click');
            });
            $('.views-field-edit-quantity input[type="text"]').on('input', function(e) {
                if ($(this).val().match(/\d+/) == null) {
                    if (!$(this).hasClass('invalid')) $(this).toggleClass('invalid');
                } else
                if ($(this).hasClass('invalid'))
                    $(this).toggleClass('invalid');
            });

            $('#edit-checkout').click(function (e) {
                e.preventDefault();
                //console.log(111);
                $('#cartinfo').modal({keyboard: false})
                .on('hidden.bs.modal', function (e) {
                    if (settings.sended) {
                        //console.log(899);
                        $('table.views-table img, .views-field-edit-delete').css('display', 'none');
                        $('table.views-table img').attr('height', 0);
                        $('table.views-table img').attr('width', 0);
                        $('.views-field-edit-quantity input[type="text"]').prop('readonly', 'true');

                        datamail= {
                            name: $('#cartinfo input.text').val(),
                            phone: $('#cartinfo input.phone').val(),
                            email: $('#cartinfo input.email').val(),
                            staff: Drupal.theme('escape','<table class="views-table cols-5">'+ $('table.views-table').html() +'</table><p>'+ $('.commerce-price-formatted-components, .line-item-total').text() +'</p>')
                        };

                        $('table.views-table img').css('display', 'inline');
                        $('.views-field-edit-delete').css('display', 'inline-block');
                        $('th.views-field-edit-delete').css('display', 'table-cell');
                        $('.views-field-edit-quantity input[type="text"]').removeProp('readonly');
                        $('table.views-table img').attr('height', '130');
                        $('table.views-table img').attr('width', '150');

                        //console.log('before post');
                        $('.wait-load').css('display', 'block');
                        $.ajax({
                            type: "POST",
                            url: location.href+'?userdone=1',
                            //url: '/content/addtocartemailsend'+'?userdone=1',
                            data: {t1: JSON.stringify(datamail)},
                            asynx: false,
                            success: function(data) {
//$('body').prepend(data);
                                $('.wait-load').css('display', 'none');
                    //$.post( location.href+'?userdone=1', datamail, function(data) {
                        console.log( "success" );
                        try{
                            fback= eval('(' + data + ')');
                        }
                        catch(err) {
                            $('#error').modal({keyboard: false});
                            console.log(err);
                        }

                        if (fback.Form.isValid)
                            $('#thanks').modal()
                                .on('shown.bs.modal', function () {
                                    setTimeout(function () {
                                        $('#thanks').modal('hide');
                                    }, 5000);
                                });
                        else
                            $('#badinput').modal({keyboard: false});
                    //});
                    }});
                    }
                });
            });
            $('#cartinfo .btn-default').click(function(){settings.sended= 0;});
            $('#cartinfo .btn-primary').click(function(){

                $('#cartinfo input[type="text"]').each(function(){
                    if ($(this).css('color') == "rgb(185, 74, 72)") {
                        settings.sended= 0;
                        return;
                    } else if ($(this).val().length < 4) {
                        $(this).addClass('invalid');
                        settings.sended= 0;
                        return;
                    } else if ($(this).hasClass('invalid')) $(this).removeClass('invalid');

                    settings.sended= 1;
                });


                if (settings.sended) {
                    $('#cartinfo').modal('hide');
                }
            });
            $('#cartinfo input.email').on('input', function(e) {
                if ($(this).val().match(/.+@.+\..+/i) == null) {
                    if (!$(this).hasClass('invalid')) $(this).toggleClass('invalid');
                } else
                if ($(this).hasClass('invalid'))
                    $(this).toggleClass('invalid');
            });
            $('#cartinfo input.phone').on('input', function(e) {
                if ($(this).val().match(/[\d -]+/) == null || $(this).val().match(/\d+/g) == null || $(this).val().match(/\d+/g).join('').length < 6) {
                    if (!$(this).hasClass('invalid')) $(this).toggleClass('invalid');
                } else
                if ($(this).hasClass('invalid'))
                    $(this).toggleClass('invalid');
            });
            $('#cartinfo input.text').on('input', function(e) {
                if ($(this).val().match(/[a-zA-Zа-яА-Я -]+/) == null || $(this).val().match(/[a-zA-Zа-яА-Я]+/g).join('').length < 4) {
                    if (!$(this).hasClass('invalid')) $(this).toggleClass('invalid');
                } else
                if ($(this).hasClass('invalid'))
                    $(this).toggleClass('invalid');
            });
        }
    }
})(jQuery);

