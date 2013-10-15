/**
 * Created with JetBrains PhpStorm.
 * User: leve_000
 * Date: 14.10.13
 * Time: 12:33
 * To change this template use File | Settings | File Templates.
 */

$.datepicker.setDefaults( $.datepicker.regional[ "ru" ] );

$(function() {
    $('.main .form-doit .rad-time input[type="text"]').eq(0).datepicker({minDate: new Date(), maxDate: "+1m +1w", dateFormat: "dd.mm.yy"});
});
//$('.main .form-doit .rad-time input[type="text"]').datepicker( $.datepicker.regional[ "ru" ] );

$(document).ready(function(){
    $('.main .form-doit span').textShadow();
});

jQuery(function($){
    $('.main .form-doit input[name="Phone"]').mask("+7 9999-999-999");
});
jQuery(function($){
    $('.main .form-doit .rad-time input[type="text"]').eq(1).mask("99:99");
});

$('.main .form-doit select').chosen({disable_search_threshold: 10, width: '150px'});

$('.main .form-doit .rad-time input[type="radio"]').change(function(){
    $('.main .form-doit .rad-time input[type="text"]').toggleClass('hidden');
});
/*$('.main .form-doit .rad-time span').click(function(){
    $('.main .form-doit .rad-time input[type="radio"]').each(function(){
        if ($(this).is(':checked')) $(this).removeAttr('checked');
        else $(this).removeAttr('checked', true);
    });
});*/

$('.main .form-doit footer button').click(function(){
    //Check required fields
    st= $('.main .form-doit>section').eq(0).find('input[type="text"]');
    fn= $('.main .form-doit>section').eq(1).find('input[type="text"]');
    dt= $('.main .form-doit>section').eq(2).find('input[type="text"]');

    msg= "";
    if (!st.eq(0).val()) {
        msg+= "* Адрес, где Вас будет ожидать такси\n\r";
        st.eq(0).css('border-color', 'red');
    } else
        st.eq(0).css('border-color', '#949493');
    if (!fn.eq(0).val()) {
        msg+= "* Адрес места назначения\n\r";
        fn.eq(0).css('border-color', 'red');
    } else
        fn.eq(0).css('border-color', '#949493');
    if (!dt.eq(1).val()) {
        msg+= "* Ваш контактный телефон\n\r";
        dt.eq(1).css('border-color', 'red');
    } else
        dt.eq(1).css('border-color', '#949493');

    if (msg)
        alert("Для продолжения Вам необходимо заполнить следующие поля\n\r"+msg);
    else
        alert("Заказ успешно оформлен! В назначеное время с Вами свяжутся специалисты нашей компании.");
});