/**
 * Created by storm on 5/2/14.
 */

var pid= 0;
var id= 0;
var back= false;

(function ($) {

    $('[id*="m"].modal').on('hide.bs.modal', function (e) {
        if (back) {
            tmp= id;
            id= pid;
            pid= tmp;

            back= false;
        }
        if (id != pid) {
            $('#m-'+id).modal();
        }
    });
    $('[id*="m"].modal').find('.c-1>div').click(function(e){
        pid= (id != pid)? id : 0 ;
        id= id + ($('#m-'+id+' .c-1>div').index($(this)) + 1);

        $('#m-'+pid).modal('hide');
    });
    $('[id*="m"].modal').find('.c-10>div').click(function(e){
        pid= (id != pid)? id : 0 ;
        id= id + ($('#m-'+id+' .c-10>div').index($(this)) + 1);

        $('#m-'+pid).modal('hide');
    });
    $('[id*="m"].modal').find('.c-100>div').click(function(){
        pid= (id != pid)? id : 0 ;
        id= id + ($('#m-'+id+' .c-100>div').index($(this)) + 1) * 10;
        tmp=0;

        $('#m-'+pid).modal('hide');
    });
    $('[id*="m"].modal').find('.btn-primary.back').click(function(){
        back= true;
        $('#m-'+id).modal('hide');
    });

    $('.m-base>div').click(function(){

        pid= 0;
        id= ($('.m-base > div').index($(this)) + 1) * 100;
        $('#m-'+id).modal();
    });
    $('button.add').click(function(){
        tmp= $(this).parents('.object');
        tmp.clone(true).insertAfter(tmp);
    });
    $('button.del').click(function(){
        tmp= $(this).parents('.object');
        tmp.remove();
    });

    /**
     *
     * @param label
     * @param value
     * @param place: 0 - Потолок, 1 - Стена, 2 - Пол
     */
    row_add= function(label, value, place) {
        base= $('#m-100000 .modal-body section');

        base.eq(place).find('tbody').append('<tr><td>'+ label +'</td><td>'+ value +'</td></tr>');
    }
    $('.btn-success').click(function(){
        pid= (id != pid)? id : 0 ;
        id= 100000;

        $('#m-100000 .modal-body section tbody').empty();

        switch(pid) {
            case 110:
                w= $('#m-110 .modal-body>div').eq(0).find('input').eq(0).val().replace(',', '.');
                l= $('#m-110 .modal-body>div').eq(0).find('input').eq(1).val().replace(',', '.');
                h= $('#m-110 .modal-body>div').eq(1).find('input').val().replace(',', '.');
                dl= $('#m-110 .modal-body>div').eq(2).find('input').eq(0).val().replace(',', '.');
                dh= $('#m-110 .modal-body>div').eq(2).find('input').eq(1).val().replace(',', '.');

                row_add('Устройство реечного потолка «Люкссалон»', 620 * w*l, 0);
                row_add('Обработка поверхностей грунтом', 50 * 2*(l*h+w*h) - dl*dh, 1);
                row_add('Частичное оштукатуривание стен', 850 * 2*(l*h+w*h) - dl*dh, 1);
                row_add('Укладка плитки на стены', 1200 * 2*(l*h+w*h) - dl*dh, 1);
                row_add('Обработка поверхности грунтом', 50 * w*l - dl, 2);
                row_add('Выравнивание пола самовыравнивающей мастикой ', 360*w*l - dl, 2);
                row_add('Гидроизоляция пола «Гидроизол»', 75 * w*l - dl, 2);
                row_add('Укладка плитки на пол', 1200 * w*l - dl, 2);
                break;
            case 121: break;
        }

        $('#m-'+pid).modal('hide');
    });



})(jQuery);