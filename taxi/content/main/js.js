/**
 * Created with JetBrains PhpStorm.
 * User: leve_000
 * Date: 14.10.13
 * Time: 12:33
 * To change this template use File | Settings | File Templates.
 */

$.datepicker.setDefaults( $.datepicker.regional[ "ru" ] );

/********
 *
 * Date/Time Const
 */
var dtformat= "dd.mm.yyyy";
var waittime= 31;

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

$('.main .form-doit select').chosen({disable_search_threshold: 10, width: '185px'});

$('.main .form-doit .rad-time input[type="radio"]').change(function(){
    $('.main .form-doit .rad-time input[type="text"]').toggleClass('hidden');
});
/*$('.main .form-doit .rad-time span').click(function(){
    $('.main .form-doit .rad-time input[type="radio"]').each(function(){
        if ($(this).is(':checked')) $(this).removeAttr('checked');
        else $(this).removeAttr('checked', true);
    });
});*/

dt= $('.main .form-doit .rad-time input[type="text"]');
curdt= new Date();
dt.eq(0).val(curdt.format(dtformat));
hh = parseInt(curdt.getHours());
mm = parseInt(curdt.getMinutes());
dt.eq(1).val(
    ((hh == 23) ? '00' : (hh+1 < 10) ? '0'+(hh+1).toString() : hh+1)
        +':'+
    ((mm < 10) ? '0'+mm.toString()  : mm)
);

/*dt.eq(1).change(function(){
    curdt= new Date();
    hh= parseInt(curdt.getHours());
    mm= parseInt(curdt.getMinutes());
    inhh= parseInt($(this).val().split(':')[0]);
    inmm= parseInt($(this).val().split(':')[1]);
    if (inhh > 23 || inmm > 59 || isNaN(inhh) || isNaN(inmm)) {
        $(this).val(
            ((hh == 23) ? '00' : (hh+1 < 10) ? '0'+(hh+1).toString() : hh+1)
                +':'+
                ((mm < 10) ? '0'+mm.toString()  : mm)
        );
    }
    else if ( dt.eq(0).val() == curdt.format(dtformat) ) {
        if (inhh < hh || inhh-hh > 1)
            $(this).val(
                ((hh == 23) ? '00' : (hh+1 < 10) ? '0'+(hh+1).toString() : hh+1)
                    +':'+
                    ((mm < 10) ? '0'+mm.toString()  : mm)
            );
        else if ( (inhh-hh)*60 + (inmm-mm) < waittime )
            $(this).val(
                ((hh == 23) ? '00' : (hh+1 < 10) ? '0'+(hh+1).toString() : hh+1)
                    +':'+
                    ((mm < 10) ? '0'+mm.toString()  : mm)
            );
    }
});*/

$('.main .form-doit footer button').click(function(){
    //Check required fields
    st= $('.main .form-doit>section').eq(0).find('input[type="text"]');
    fn= $('.main .form-doit>section').eq(1).find('input[type="text"]');
    dt= $('.main .form-doit>section').eq(2).find('input[type="text"]');
    tm= {
        st: parseInt($('.main .form-doit input[name="rad-time"]:checked').val()),
        date: dt.eq(3),
        time: dt.eq(4)
    }

    stmsg= "";
    if (!st.eq(0).val()) {
        stmsg+= "* Адрес"; //
        st.eq(0).css('border-color', 'red');
    } else
        st.eq(0).css('border-color', '#949493');
    if (!st.eq(1).val()) {
        if (stmsg)
            stmsg+= " и дом, где Вас будет ожидать такси\n\r";
        else
            stmsg+= "* Дом, у которого Вас будет ожидать такси\n\r";
        st.eq(1).css('border-color', 'red');
    } else {
        stmsg+= ", у которого Вас будет ожидать такси\n\r";
        st.eq(1).css('border-color', '#949493');
    }

    fnmsg= "";
    if (!fn.eq(0).val()) {
        fnmsg+= "* Адрес";
        fn.eq(0).css('border-color', 'red');
    } else
        fn.eq(0).css('border-color', '#949493');
    if (!fn.eq(1).val()) {
        if (fnmsg)
            fnmsg+= " и дом места назначения\n\r";
        else
            fnmsg+= "* Дом места назначения\n\r";
        fn.eq(1).css('border-color', 'red');
    } else {
        fn.eq(1).css('border-color', '#949493');
        fnmsg+= " места назначения\n\r";
    }

    dtmsg= "";
    if (!dt.eq(1).val()) {
        dtmsg+= "* Ваш контактный телефон\n\r";
        dt.eq(1).css('border-color', 'red');
    } else
        dt.eq(1).css('border-color', '#949493');
    if (tm.st) {
        curdt= new Date();

        if (curdt.format(dtformat) > tm.date.val()) {
            dtmsg+= "* Актуальная дата\n\r";
            tm.date.css('border-color', 'red');
        }
        else {
            nowhh = parseInt(curdt.getHours());
            nowmm = parseInt(curdt.getMinutes());
            nowdd = parseInt(curdt.getDate());
            hh = parseInt(tm.time.val().split(':')[0]);
            mm = parseInt(tm.time.val().split(':')[1]);
            dd = parseInt(tm.date.val().split('.')[0]);

            /*console.log(nowhh+' '+nowmm+' '+nowdd+'\n\r'+hh+' '+mm+' '+dd);*/

            if ( dd-nowdd > 1 ) {
                /*console.log(1);*/
                tm.date.css('border-color', '#949493');
                if (tm.time.css('border-color') == "rgb(255, 0, 0)")
                    tm.time.css('border-color', '#949493');
            }
            else if ( dd-nowdd == 0)
                if ( (hh-nowhh)*60 + (mm-nowmm) < waittime ) {
                    /*console.log(2);*/
                    dtmsg+= "* Актуальное время\n\r";
                    tm.time.css('border-color', 'red');
                }
                else {
                    /*console.log(3);*/
                    tm.time.css('border-color', '#949493');
                }
            else { // dd-nowdd == 1
                if ( (hh-nowhh)*60 + (mm-nowmm) > 24*60-waittime ) {
                    /*console.log(4);*/
                    dtmsg+= "* Актуальную дату и время\n\r";
                    tm.time.css('border-color', 'red');
                    tm.date.css('border-color', 'red');
                }
                else {
                    /*console.log(5);*/
                    tm.date.css('border-color', '#949493');
                    tm.time.css('border-color', '#949493');
                }
            }
        }
    }

    msg= stmsg+fnmsg+dtmsg;
    if (msg)
        alert("Для продолжения Вам необходимо заполнить следующие поля\n\r"+msg);
    else
        alert("Заказ успешно оформлен! В назначеное время с Вами свяжутся специалисты нашей компании.");
});