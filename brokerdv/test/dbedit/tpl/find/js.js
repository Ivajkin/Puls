/**
 * Created with JetBrains PhpStorm.
 * User: leve_000
 * Date: 30.10.13
 * Time: 14:25
 * To change this template use File | Settings | File Templates.
 */

var ptype= 0;
var outdata= undefined;
var thispath= rootpath;
var tblcol= 4;
var tbrow= 1000;
var tbcur= 1;
var addlink= thispath+"db/savedata.php";
var odin = 0;
//var outinfo= undefined;

/*jQuery.post( thispath+"xml/xmlparse.php", {qwe: 12},
 function(data, textStatus) {
 if (textStatus == 'success') {
 log= eval('('+data+')');
 console.log(log);
 } else
 console.log(textStatus);
 });*/

jQuery('.prod_filter .selects select.ft_ptype').change(function(){
    //District fix
    dist= parseInt(jQuery('.prod_filter .selects select.ft_district.ft_visible').val())-1;
    ptype= parseInt(jQuery(this).children(':selected').val())-1;
    jQuery('.prod_filter .selects select.ft_district.ft_visible').toggleClass('ft_visible')
        .toggleClass('ft_none');
    jQuery('.prod_filter .selects select.ft_district').eq(ptype).toggleClass('ft_none')
        .toggleClass('ft_visible');
    /*jQuery('.filter_selects select.ft_district option[value*="'+dist+'"]').;*/
    //console.log(ptype);

    //Property types content managing
    if (ptype == 3 && dist == 2) {
        jQuery('input.ft_storey').parents('.ft_tcol2_fix').toggleClass('ft_visible').toggleClass('ft_none');

        jQuery('select.ft_acreusage').parent().toggleClass('ft_none').toggleClass('ft_visible');
        //jQuery('input.ft_totalarea').parent().prev().text('Площадь ');

    }
    else if (ptype == 3 && dist != 3) {
        jQuery('select.ft_room').parent().toggleClass('ft_visible').toggleClass('ft_none');
        jQuery('select.ft_hometype').parent().toggleClass('ft_visible').toggleClass('ft_none');
        jQuery('select.ft_planning').parent().toggleClass('ft_visible').toggleClass('ft_none');
        jQuery('select.ft_state').parent().toggleClass('ft_visible').toggleClass('ft_none');
        jQuery('select.ft_balcony').parent().toggleClass('ft_visible').toggleClass('ft_none');
        jQuery('select.ft_lavatory').parent().toggleClass('ft_visible').toggleClass('ft_none');
        jQuery('input.ft_livearea').parents('.ft_tcol2_fix').toggleClass('ft_visible').toggleClass('ft_none');
        jQuery('input.ft_cookarea').parents('.ft_tcol2_fix').toggleClass('ft_visible').toggleClass('ft_none');
        jQuery('input.ft_storey').parents('.ft_tcol2_fix').toggleClass('ft_visible').toggleClass('ft_none');

        jQuery('select.ft_acreusage').parent().toggleClass('ft_none').toggleClass('ft_visible');
        jQuery('input.ft_totalarea').parent().prev().text('Площадь ');

    }
    else if (ptype == 2 && dist == 3) {
        jQuery('select.ft_acreusage').parent().toggleClass('ft_visible').toggleClass('ft_none');

        jQuery('input.ft_storey').parents('.ft_tcol2_fix').toggleClass('ft_none').toggleClass('ft_visible');
        //jQuery('input.ft_totalarea').parent().prev().text('Площадь ');

    }
    else if (ptype == 2 && dist != 2) {
        jQuery('select.ft_room').parent().toggleClass('ft_visible').toggleClass('ft_none');
        jQuery('select.ft_hometype').parent().toggleClass('ft_visible').toggleClass('ft_none');
        jQuery('select.ft_planning').parent().toggleClass('ft_visible').toggleClass('ft_none');
        jQuery('select.ft_state').parent().toggleClass('ft_visible').toggleClass('ft_none');
        jQuery('select.ft_balcony').parent().toggleClass('ft_visible').toggleClass('ft_none');
        jQuery('select.ft_lavatory').parent().toggleClass('ft_visible').toggleClass('ft_none');
        jQuery('input.ft_livearea').parents('.ft_tcol2_fix').toggleClass('ft_visible').toggleClass('ft_none');
        jQuery('input.ft_cookarea').parents('.ft_tcol2_fix').toggleClass('ft_visible').toggleClass('ft_none');

        jQuery('input.ft_totalarea').parent().prev().text('Площадь ');
    }
    else if (ptype != 3 && ptype != 2 && dist == 3) {
        jQuery('select.ft_acreusage').parent().toggleClass('ft_visible').toggleClass('ft_none');
        jQuery('input.ft_totalarea').parent().prev().text('Общая площадь ');

        jQuery('select.ft_room').parent().toggleClass('ft_none').toggleClass('ft_visible');
        jQuery('select.ft_hometype').parent().toggleClass('ft_none').toggleClass('ft_visible');
        jQuery('select.ft_planning').parent().toggleClass('ft_none').toggleClass('ft_visible');
        jQuery('select.ft_state').parent().toggleClass('ft_none').toggleClass('ft_visible');
        jQuery('select.ft_balcony').parent().toggleClass('ft_none').toggleClass('ft_visible');
        jQuery('select.ft_lavatory').parent().toggleClass('ft_none').toggleClass('ft_visible');
        jQuery('input.ft_livearea').parents('.ft_tcol2_fix').toggleClass('ft_none').toggleClass('ft_visible');
        jQuery('input.ft_cookarea').parents('.ft_tcol2_fix').toggleClass('ft_none').toggleClass('ft_visible');
        jQuery('input.ft_storey').parents('.ft_tcol2_fix').toggleClass('ft_none').toggleClass('ft_visible');
    }
    else if (ptype != 3 && ptype != 2 && dist == 2) {
        jQuery('input.ft_totalarea').parent().prev().text('Общая площадь ');

        jQuery('select.ft_room').parent().toggleClass('ft_none').toggleClass('ft_visible');
        jQuery('select.ft_hometype').parent().toggleClass('ft_none').toggleClass('ft_visible');
        jQuery('select.ft_planning').parent().toggleClass('ft_none').toggleClass('ft_visible');
        jQuery('select.ft_state').parent().toggleClass('ft_none').toggleClass('ft_visible');
        jQuery('select.ft_balcony').parent().toggleClass('ft_none').toggleClass('ft_visible');
        jQuery('select.ft_lavatory').parent().toggleClass('ft_none').toggleClass('ft_visible');
        jQuery('input.ft_livearea').parents('.ft_tcol2_fix').toggleClass('ft_none').toggleClass('ft_visible');
        jQuery('input.ft_cookarea').parents('.ft_tcol2_fix').toggleClass('ft_none').toggleClass('ft_visible');
    }
});

jQuery('.prod_filter').change(function(){
    outdata= {
        ptype: jQuery('.prod_filter select.ft_ptype').val(),
        cost: [jQuery('.prod_filter input.ft_cost').eq(0).val(),
            jQuery('.prod_filter input.ft_cost').eq(1).val()],
        totalarea: [jQuery('.prod_filter input.ft_totalarea').eq(0).val(),
            jQuery('.prod_filter input.ft_totalarea').eq(1).val()],
        district: jQuery('.prod_filter select.ft_district').eq(ptype).val(),
//1-3
        room: jQuery('.prod_filter select.ft_room').val(),
        hometype: jQuery('.prod_filter select.ft_hometype').val(),
        planning: jQuery('.prod_filter select.ft_planning').val(),
        state: jQuery('.prod_filter select.ft_state').val(),
        balcony: jQuery('.prod_filter select.ft_balcony').val(),
        lavatory: jQuery('.prod_filter select.ft_lavatory').val(),
        livearea: [jQuery('.prod_filter input.ft_livearea').eq(0).val(),
            jQuery('.prod_filter input.ft_livearea').eq(1).val()],
        cookarea: [jQuery('.prod_filter input.ft_cookarea').eq(0).val(),
            jQuery('.prod_filter input.ft_cookarea').eq(1).val()],
        storey: [jQuery('.prod_filter input.ft_storey').eq(0).val(),
            jQuery('.prod_filter input.ft_storey').eq(1).val()],
//4
        acreusage: jQuery('.prod_filter select.ft_acreusage').val()
    }
    if (!outdata.cost[1] || outdata.cost[1] == "0" || outdata.cost[1] < outdata.cost[0]) outdata.cost[1]= 2000000000;
    if (!outdata.totalarea[1] || outdata.totalarea[1] == '0' || outdata.totalarea[1] < outdata.totalarea[0]) outdata.totalarea[1]= 2000000000;
    if (!outdata.livearea[1] || outdata.livearea[1] == '0' || outdata.livearea[1] < outdata.livearea[0]) outdata.livearea[1]= 2000000000;
    if (!outdata.cookarea[1] || outdata.cookarea[1] == '0' || outdata.cookarea[1] < outdata.cookarea[0]) outdata.cookarea[1]= 2000000000;
    if (!outdata.storey[1] || outdata.storey[1] == '0' || outdata.storey[1] < outdata.storey[0]) outdata.storey[1]= 2000000000;

    if (!outdata.cost[0]) outdata.cost[0]= 0;
    if (!outdata.totalarea[0]) outdata.totalarea[0]= 0;
    if (!outdata.livearea[0]) outdata.livearea[0]= 0;
    if (!outdata.cookarea[0]) outdata.cookarea[0]= 0;
    if (!outdata.storey[0]) outdata.storey[0]= 0;

    //console.log(outdata);
    jQuery.post( thispath+"db/filter.php", outdata,
        function(data, textStatus) {
            if (textStatus == 'success') {
                log= eval('('+data+')');
                console.log(log);
                //console.log(data);
                //for id only out
                /*i=0;
                 truedata= [];
                 while(log[i]) truedata.push(parseInt(log[i++][0]));
                 console.log(truedata);*/
                //for (i=0; i < data.length; i++) console.log(data[i]);

                resultout(log);
            } else
                console.log(textStatus);
        });
    /*jQuery.ajax({
     type: "POST",
     url: "filter.php",
     data: outdata,
     contentType: "application/json; charset=utf-8",
     dataType: "json",
     complete: function(data, textStatus) {
     if (textStatus == 'success') {
     console.log(data);
     } else
     console.log(textStatus);
     }
     });*/
});

jQuery('.prod_addform input.add_save').click(function(){
    outdata.name= '0';
    outdata.description= '0';
    outdata.photo= '0';
    outdata.address= '0';
    outdata.location= jQuery('.prod_addform .add_name').val();
    outdata.realtor= '1';

    msg= '';
    jQuery('.inputs input:even').each(function(){
        if ( !(jQuery(this).val()) ) {
            msg= 'Пожалуйста, заполните поля выделенные красным.\n\rЕсли на текущий момент у вас нет информации для заполнения, поставьте 0';
            jQuery(this).css('border-color', 'red');
        } else {
            jQuery(this).css('border-color', '#EEE');
        }
    });
    if (msg) alert(msg);

    if (addlink.match(/edit/i)) {
        outdata.id = odin;
    }
    //console.log(outdata);
    jQuery.post( addlink, outdata,
        function(data, textStatus) {
            if (textStatus == 'success') {
                alert('Информация обновлена!');
                console.log(data);
                if (addlink.match(/edit/i)) {
                    $('.panel-heading').eq(0).find('a').text('Добавление данных');
                    addlink= thispath+"db/savedata.php";
                }
                location.reload();
            } else
                alert('Операция не выполнена. Проверьте правильность ввода и подключение к интернету. При повторном повторном появлении сообщения, обратитесь в поддержку.');
                console.log(textStatus);
        });
    /*jQuery.ajax({
     type: "POST",
     url: "savedata.php",
     data: outdata,
     contentType: "application/json; charset=utf-8",
     dataType: "json",
     complete: function(data, textStatus) {
     if (textStatus == 'success') {
     console.log(data);
     } else
     console.log(textStatus);
     }
     });*/
});
jQuery('.prod_addform input.realtor_save').click(function(){
    rldata= {
        rl_fio: jQuery('.prod_addform .rl_fio').val(),
        rl_tel: jQuery('.prod_addform .rl_tel').val()
    };

    console.log(rldata);
    jQuery.post( thispath+"db/save_realtor.php", rldata,
        function(data, textStatus) {
            if (textStatus == 'success') {
                console.log(data);
            } else
                console.log(textStatus);
        });

});


/*jQuery('.panel-title a').click(function(){
    jQuery('.panel-collapse.collapse').removeClass('in');
});*/
jQuery('#accordion').on('show.bs.collapse', function (e) {
    // do something…
    jQuery('.panel-collapse.collapse.in').toggleClass('in');
});
jQuery('#accordion').on('shown.bs.collapse', function (e) {
    // do something…
    obj= jQuery('.panel-collapse.in');
    if (obj.prev().find('a').attr('href') == '#collapseOne') {

        jQuery('input.ft_cost').eq(1).hide();
        jQuery('input.ft_livearea').eq(1).css('visibility', 'hidden');
        jQuery('input.ft_cookarea').eq(1).css('visibility', 'hidden');
        jQuery('input.ft_totalarea').eq(1).css('visibility', 'hidden');
        jQuery('input.ft_storey').eq(1).css('visibility', 'hidden');

    } else {
        jQuery('input.ft_cost').eq(1).show();
        jQuery('input.ft_livearea').eq(1).css('visibility', 'visible');
        jQuery('input.ft_cookarea').eq(1).css('visibility', 'visible');
        jQuery('input.ft_totalarea').eq(1).css('visibility', 'visible');
        jQuery('input.ft_storey').eq(1).css('visibility', 'visible');
    }
});
/*jQuery('#accordion').on('hidden.bs.collapse', function (e) {
    // do something…
    jQuery('.panel-collapse:not(.in)').toggleClass('in');
});*/

//view-node
resultout= function(data){

    jQuery('.prod_out').empty();
    if (!data.length) {
        jQuery('.prod_out').append('РЕЗУЛЬТАТОВ НЕ НАЙДЕНО, ПОПРОБУЙТЕ ИЗМЕНИТЬ ЗАПРОС.');
        return;
    }

    jQuery('.prod_out').append(
        '<div class="prod_aleft"><img src="'+thispath+'img/arrow_left.png" alt="" /></div>'+
        '<div class="prod_table"><div class="table_count clearfix">Страница 1/'+(Math.floor(data.length / tbrow)+1)+'</div><div class="table_head clearfix"></div></div>'+
        '<div class="prod_aright"><img src="'+thispath+'img/arrow_right.png" alt="" /></div>');
    //for
    for (i=0; i<tblcol; i++)
        jQuery('.prod_table .table_head').append('<div class="table_hcolumn" style="">Заголовок '+i+'</div>');
    inds= [];
    tbody= '';
    tmp2= '';
    dstmax= 9;
    switch (ptype) {
        case 0:
            jQuery('.prod_table .table_hcolumn').eq(0).text('Район');
            jQuery('.prod_table .table_hcolumn').eq(1).text('Количество комнат');
            jQuery('.prod_table .table_hcolumn').eq(2).text('Этаж');
            jQuery('.prod_table .table_hcolumn').eq(3).text('Цена');
            inds.push('fid_district', 'fid_room', 'storey', 'cost');
            for (i=0; i<data.length && i<tbrow; i++) {
                tmp= parseInt(data[i][inds[0]]) % dstmax;
                tmp2= jQuery('.prod_filter select.ft_district').eq(ptype).children('option');
                if (tmp)
                    tmp= tmp2.eq(tmp-1).val().split(' ')[2];
                else
                    tmp= tmp2.eq(dstmax-1).val().split(' ')[2];
                tbody+= '<div class="table_row clearfix" data-location="'+data[i]['id']+'"><div class="table_column">'+tmp+'</div>';

                tmp= jQuery('.prod_filter select.ft_'+inds[1].split('_')[1]);
                tmp2= undefined;
                for (j=0; j<tmp.find('option').length; j++) {
                    if (parseInt(tmp.find('option').eq(j).val()) == parseInt(data[i][inds[1]])) {
                        tmp2= tmp.find('option').eq(j).val().split(' ');
                        break;
                    }
                }
                tmp3= "";
                for(j=1; j<tmp2.length; j++)
                    tmp3+= tmp2[j]+' ';

                tbody+= '<div class="table_column">'+tmp3+'</div>';
                tbody+= '<div class="table_column">'+data[i][inds[2]]+'</div>';
                tbody+= '<div class="table_column">'+data[i][inds[3]]+'</div></div>';
            }
            break;
        case 1:
            jQuery('.prod_table .table_hcolumn').eq(0).text('Район');
            jQuery('.prod_table .table_hcolumn').eq(1).text('Материал стен');
            jQuery('.prod_table .table_hcolumn').eq(2).text('Площадь');
            jQuery('.prod_table .table_hcolumn').eq(3).text('Цена');
            inds.push('fid_district', 'fid_hometype', 'totalarea', 'cost');
            for (i=0; i<data.length && i<tbrow; i++) {
                tmp= parseInt(data[i][inds[0]]) % dstmax;
                tmp2=  jQuery('.prod_filter select.ft_district').eq(ptype).children('option');
                if (tmp)
                    tmp= tmp2.eq(tmp-1).val().split(' ')[2];
                else
                    tmp= tmp2.eq(dstmax-1).val().split(' ')[2];
                tbody+= '<div class="table_row clearfix" data-location="'+data[i]['id']+'"><div class="table_column">'+tmp+'</div>';

                tmp= jQuery('.prod_filter select.ft_'+inds[1].split('_')[1]);
                tmp2= undefined;
                for (j=0; j<tmp.find('option').length; j++) {
                    if (parseInt(tmp.find('option').eq(j).val()) == parseInt(data[i][inds[1]])) {
                        tmp2= tmp.find('option').eq(j).val().split(' ');
                        break;
                    }
                }
                tmp3= "";
                for(j=1; j<tmp2.length; j++)
                    tmp3+= tmp2[j]+' ';

                tbody+= '<div class="table_column">'+tmp3+'</div>';
                tbody+= '<div class="table_column">'+data[i][inds[2]]+'</div>';
                tbody+= '<div class="table_column">'+data[i][inds[3]]+'</div></div>';
            }
            break;
        case 2:
            for (i=0; i<data.length && i<tbrow; i++) {
                jQuery('.prod_table .table_hcolumn').eq(0).text('Район');
                jQuery('.prod_table .table_hcolumn').eq(1).text('Площадь');
                jQuery('.prod_table .table_hcolumn').eq(2).text('Этаж');
                jQuery('.prod_table .table_hcolumn').eq(3).text('Цена');
                inds.push('fid_district', 'totalarea', 'storey', 'cost');
                tmp= parseInt(data[i][inds[0]]) % dstmax;
                tmp2= jQuery('.prod_filter select.ft_district').eq(ptype).children('option');
                if (tmp)
                    tmp= tmp2.eq(tmp-1).val().split(' ')[2];
                else
                    tmp= tmp2.eq(dstmax-1).val().split(' ')[2];
                tbody+= '<div class="table_row clearfix" data-location="'+data[i]['id']+'"><div class="table_column">'+tmp+'</div>';
                tbody+= '<div class="table_column">'+data[i][inds[1]]+'</div>';
                tbody+= '<div class="table_column">'+data[i][inds[2]]+'</div>';
                tbody+= '<div class="table_column">'+data[i][inds[3]]+'</div></div>';
            }
            break;
        case 3:
            jQuery('.prod_table .table_hcolumn').eq(0).text('Район');
            jQuery('.prod_table .table_hcolumn').eq(1).text('Площадь');
            jQuery('.prod_table .table_hcolumn').eq(2).text('Назначение земли');
            jQuery('.prod_table .table_hcolumn').eq(3).text('Цена');
            inds.push('fid_district', 'totalarea', 'fid_acreusage', 'cost');
            for (i=0; i<data.length && i<tbrow; i++) {
                tmp= parseInt(data[i][inds[0]]) % dstmax;
                tmp2= jQuery('.prod_filter select.ft_district').eq(ptype).children('option');
                if (tmp)
                    tmp= tmp2.eq(tmp-1).val().split(' ')[2];
                else
                    tmp= tmp2.eq(dstmax-1).val().split(' ')[2];
                tbody+= '<div class="table_row clearfix" data-location="'+data[i]['id']+'"><div class="table_column">'+tmp+'</div>';

                tmp= jQuery('.prod_filter select.ft_'+inds[2].split('_')[1]);
                tmp2= undefined;
                for (j=0; j<tmp.find('option').length; j++) {
                    if (parseInt(tmp.find('option').eq(j).val()) == parseInt(data[i][inds[2]])) {
                        tmp2= tmp.find('option').eq(j).val().split(' ');
                        break;
                    }
                }
                tmp3= "";
                for(j=1; j<tmp2.length; j++)
                    tmp3+= tmp2[j]+' ';

                tbody+= '<div class="table_column">'+data[i][inds[1]]+'</div>';
                tbody+= '<div class="table_column">'+tmp3+'</div>';
                tbody+= '<div class="table_column">'+data[i][inds[3]]+'</div></div>';
            }
            break;
    }
    console.log(tmp2);
    jQuery('.prod_table').append('<div class="table_body"></div>');


    jQuery('.prod_table .table_body').append(tbody);
    jQuery('.prod_table .table_row').click(function(){
        /*if (isNaN ( parseInt(jQuery(this).data('location')) ))
            document.location= jQuery(this).data('location');
        else
            document.location="/?q=node/"+jQuery(this).data('location');*/

        odin= $(this).data('location');
        jQuery('#myModal').modal();
    });
    jQuery('button.btn-primary').click(function(){
        jQuery('#myModal').modal('hide');
        jQuery.post( thispath+"db/deldata.php", {id: odin, ptype: jQuery('.prod_filter select.ft_ptype').val()},
            function(data, textStatus) {
                if (textStatus == 'success') {
                    alert('Операция выполнена!');
                    console.log(data);
                    location.reload();
                } else
                    alert('Информация не удалена. Попробуйте повторить операцию, проверьте подключение к интернету. При повторном повторном появлении сообщения, обратитесь в поддержку.');
                console.log(textStatus);
            });
    });
    jQuery('button.btn-secondary').click(function(){
        jQuery('#myModal').modal('hide');
        jQuery.post( thispath+"db/editdata.php?ajax=1", {id: odin, ptype: jQuery('.prod_filter select.ft_ptype').val()},
            function(data, textStatus) {
                if (textStatus == 'success') {

                    log= eval('('+data+')');
                    console.log(log);

                    editout(log);

                } else
                    alert('Информация не изменена. Попробуйте повторить операцию, проверьте подключение к интернету. При повторном повторном появлении сообщения, обратитесь в поддержку.');
                console.log(textStatus);
            });
    });
}

jQuery('.prod_filter').trigger('change');

var editout= function(data){

        //jQuery('.prod_filter select.ft_ptype').val(data[0].ptype);
        jQuery('.prod_filter input.ft_cost').eq(0).val(data[0].cost);

        jQuery('.prod_filter input.ft_totalarea').eq(0).val(data[0].totalarea);
        jQuery('.prod_filter select.ft_district').eq(ptype).children('option').each( function() {
                if ( parseInt($(this).val().split(' ')[1]) == parseInt(data[0].fid_district) ) {
                    jQuery(this).parent().val($(this).val());
                    return;
                }
        });
//1-3
    if (data[0].fid_room)
        jQuery('.prod_filter select.ft_room option').each(function(){
            if ($(this).val().split(' ')[0] == data[0].fid_room) {
                $(this).parent().val($(this).val());
                return;
            }
        });
    if (data[0].fid_hometype)
        jQuery('.prod_filter select.ft_hometype option').each(function(){
            if ($(this).val().split(' ')[0] == data[0].fid_hometype) {
                $(this).parent().val($(this).val());
                return;
            }
        });
    if (data[0].fid_planning)
        jQuery('.prod_filter select.ft_planning option').each(function(){
            if ($(this).val().split(' ')[0] == data[0].fid_planning) {
                $(this).parent().val($(this).val());
                return;
            }
        });
    if (data[0].fid_state)
        jQuery('.prod_filter select.ft_state option').each(function(){
            if ($(this).val().split(' ')[0] == data[0].fid_state) {
                $(this).parent().val($(this).val());
                return;
            }
        });
    if (data[0].fid_balcony)
        jQuery('.prod_filter select.ft_balcony option').each(function(){
            if ($(this).val().split(' ')[0] == data[0].fid_balcony) {
                $(this).parent().val($(this).val());
                return;
            }
        });
    if (data[0].fid_lavatory)
        jQuery('.prod_filter select.ft_lavatory option').each(function(){
            if ($(this).val().split(' ')[0] == data[0].fid_lavatory) {
                $(this).parent().val($(this).val());
                return;
            }
        });
        jQuery('.prod_filter input.ft_livearea').eq(0).val(data[0].livearea);
        jQuery('.prod_filter input.ft_cookarea').eq(0).val(data[0].cookarea);
        jQuery('.prod_filter input.ft_storey').eq(0).val(data[0].storey);
//4
    if (data[0].fid_acreusage)
        jQuery('.prod_filter select.ft_acreusage option').each(function(){
            if ($(this).val().split(' ')[0] == data[0].fid_acreusage) {
                $(this).parent().val($(this).val());
                return;
            }
        });

    jQuery('.prod_addform .add_name').val(data[0].location);

    location.assign('#');
    jQuery('.panel-collapse').each(function(){
        $(this).toggleClass('in');
    });
    jQuery('#accordion').trigger('shown.bs.collapse');

    $('.panel-heading').eq(0).find('a').text('Режим редактирования');
    addlink = thispath+"db/editdata.php?ajax=2";

}

/*jQuery('.prod_aleft, .prod_aright').mouseenter(function(){
 console.log(123);
 jQuery('.prod_aleft img, .prod_aright img').css('visibility', 'visible');
 });*/

/*jQuery(document).ready(function(){
 jQuery('a.follow-link.follow-link-twitter.follow-link-site').attr('href', 'http://vk.com/broker_dv')
 });*/

