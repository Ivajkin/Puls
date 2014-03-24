<?php
require_once('bd_3.php');
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru" dir="ltr" >
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />

    <link rel="stylesheet" href="test/style.css" type="text/css" />
    <!--<link rel="stylesheet" href="css/layout.css" type="text/css" />-->
    <!--<script src="jquery-1.9.1.min.js" type="text/javascript"></script>-->
    <script type="text/javascript">
        (function ($) {
            $.fn.indexOf = function (el) {
                out = -1;
                $(this).each(function (id) {
                    //Do stuff for each element in matched set
                    if (el == $(this).val()) {
                        out = id;
                        return out;
                    }
                });
                return out;
            };
        })(jQuery);
        (function ($) {
            $.fn.getVal = function () {
                out = [];
                if ($(this).length)
                    $(this).each(function (id) {
                        //Do stuff for each element in matched set
                        if ($(this).val().match(/([A-zА-я])|(\0)/) || !$(this).val()) out.push($(this).val());
                        else {
                            tmp = parseFloat($j(this).val());
                            if (tmp % 1)
                                out.push(tmp);
                            else
                                out.push((tmp + 0));
                        }
                        //out.push($(this).val());
                    });
                return out;
            }
        })(jQuery);
    </script>

</head>
<body>
<?php
$br_room= gettbl($link, 'br_room');
$br_ptype= gettbl($link, 'br_ptype');
$br_district= gettbl($link, 'br_district');
$br_hometype= gettbl($link, 'br_hometype');
$br_planning= gettbl($link, 'br_planning');
$br_state= gettbl($link, 'br_state');
$br_balcony= gettbl($link, 'br_balcony');
$br_lavatory= gettbl($link, 'br_lavatory');
$br_acreusage= gettbl($link, 'br_acreusage');
$br_realtor= gettbl($link, 'br_realtor');

?>
<!--<a  href="javascript: void(0);" class="ft_findbtn">Найти</a>-->
<!--<a  href="javascript: void(0);" class="ahref">Найти</a>-->
<div class="prod_filter">
    <div class="selects ft_block" style="width: 45%">
        <div class="ft_visible">
            <span class='text_field'>Объект недвижимости</span>
            <select class="ft_ptype ft_tcol">
                <?php
                $i= 0;
                while ($br_ptype[$i]) {
                    echo "<option value='".$br_ptype[$i]['id']." ".$br_ptype[$i]['ptype']."'>".$br_ptype[$i++]['ptype']."</option>";
                }
                ?>
            </select>
        </div>
        <div class="ft_visible">
            <span class='text_field'>Район</span>
            <div class="ft_tcol" style="display: inline;">
                <?php
                $ft_class= 'ft_visible';
                $i= 0;
                while ($br_district[$i]) {
                    echo "<select class='ft_district ".$ft_class."'>";
                    while ($br_district[$i]['fid_ptype'] == $br_district[$i+1]['fid_ptype'])
                        echo "<option  value='".$br_district[$i]['fid_ptype'].' '.$br_district[$i]['id'].' '.$br_district[$i]['district']."' >".$br_district[$i++]['district']."</option>";
                    echo "<option  value='".$br_district[$i]['fid_ptype'].' '.$br_district[$i]['id'].' '.$br_district[$i]['district']."' >".$br_district[$i++]['district']."</option>";
                    if ($br_district[$i]['fid_ptype'] > 1 ) { $ft_class= 'ft_none';}
                    echo '</select>';
                }
                ?>
            </div>
        </div>
        <div class="ft_visible">
            <span class='text_field'>Комнаты</span>
            <select class="ft_room ft_tcol">
                <?php
                $i= 0;
                while ($br_room[$i]) {
                    echo "<option value='".$br_room[$i]['id'].' '.$br_room[$i]['room']."'>".$br_room[$i++]['room']."</option>";
                }
                ?>
            </select>
        </div>
        <div class="ft_visible">
            <span class='text_field'>Тип дома</span>
            <select class="ft_hometype ft_tcol">
                <?php
                $i= 0;
                while ($br_hometype[$i]) {
                    echo "<option value='".$br_hometype[$i]['id'].' '.$br_hometype[$i]['hometype']."'>".$br_hometype[$i++]['hometype']."</option>";
                }
                ?>
            </select>
        </div>
        <div class="ft_visible">
            <span class='text_field'>Планировка</span>
            <select class="ft_planning ft_tcol">
                <?php
                $i= 0;
                while ($br_planning[$i]) {
                    echo "<option value='".$br_planning[$i]['id'].' '.$br_planning[$i]['planning']."'>".$br_planning[$i++]['planning']."</option>";
                }
                ?>
            </select>
        </div>
        <div class="ft_visible">
            <span class='text_field'>Состояние</span>
            <select class="ft_state ft_tcol">
                <?php
                $i= 0;
                while ($br_state[$i]) {
                    echo "<option value='".$br_state[$i]['id'].' '.$br_state[$i]['state']."'>".$br_state[$i++]['state']."</option>";
                }
                ?>
            </select>
        </div>
        <div class="ft_visible">
            <span class='text_field'>Балкон</span>
            <select class="ft_balcony ft_tcol">
                <?php
                $i= 0;
                while ($br_balcony[$i]) {
                    echo "<option value='".$br_balcony[$i]['id'].' '.$br_balcony[$i]['balcony']."'>".$br_balcony[$i++]['balcony']."</option>";
                }
                ?>
            </select>
        </div>
        <div class="ft_visible">
            <span class='text_field'>Сан.узел</span>
            <select class="ft_lavatory ft_tcol">
                <?php
                $i= 0;
                while ($br_lavatory[$i]) {
                    echo "<option value='".$br_lavatory[$i]['id'].' '.$br_lavatory[$i]['lavatory']."'>".$br_lavatory[$i++]['lavatory']."</option>";
                }
                ?>
            </select>
        </div>
        <div class="ft_none">
            <span class='text_field'>Назначение земли</span>
            <select class="ft_acreusage ft_tcol">
                <?php
                $i= 0;
                while ($br_acreusage[$i]) {
                    echo "<option value='".$br_acreusage[$i]['id'].' '.$br_acreusage[$i]['acreusage']."'>".$br_acreusage[$i++]['acreusage']."</option>";
                }
                ?>
            </select>
        </div>
    </div>
    <div class="inputs ft_block">
        <div class="ft_visible ft_tcol2_fix">
            <span class='text_field ft_tcol2_fixtxt'>Цена </span>
            <div class="ft_tcol2">
                <span class='text_field'>от</span>
                <input type="text" class="ft_cost">
                <span class='text_field'> до </span>
                <input type="text" class="ft_cost">
            </div>
        </div>
        <div class="ft_visible ft_tcol2_fix">
            <span class='text_field ft_tcol2_fixtxt'>Общая площадь </span>
            <div class="ft_tcol2">
                <span class='text_field'>от</span>
                <input type="text" class="ft_totalarea">
                <span class='text_field'> до </span>
                <input type="text" class="ft_totalarea">
            </div>
        </div>
        <div class="ft_visible ft_tcol2_fix">
            <span class='text_field ft_tcol2_fixtxt'>Жилая площадь </span>
            <div class="ft_tcol2">
                <span class='text_field'>от</span>
                <input type="text" class="ft_livearea">
                <span class='text_field'> до </span>
                <input type="text" class="ft_livearea">
            </div>
        </div>
        <div class="ft_visible ft_tcol2_fix">
            <span class='text_field ft_tcol2_fixtxt'>Площадь кухни </span>
            <div class="ft_tcol2">
                <span class='text_field'>от</span>
                <input type="text" class="ft_cookarea">
                <span class='text_field'> до </span>
                <input type="text" class="ft_cookarea">
            </div>
        </div>
        <div class="ft_visible ft_tcol2_fix">
            <span class='text_field ft_tcol2_fixtxt'>Этаж </span>
            <div class="ft_tcol2">
                <span class='text_field'>от</span>
                <input type="text" class="ft_storey">
                <span class='text_field'> до </span>
                <input type="text" class="ft_storey">
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<!--<div class="prod_addform" style="margin-top:5%">
            <div>
                <span class='text_field'>Название</span>
                <input type="text" class="add_name" value="0"/>
            </div>
            <div>
                <span class='text_field'>Описание</span><br />
                <textarea rows="4" cols="50" class="add_description">0</textarea>
            </div>
            <div>
                <span class='text_field'>Фото</span>
                <input type="text" class="add_photo" value="0"/>
            </div>
            <div>
                <span class='text_field'>Адрес</span>
                <input type="text" class="add_address" value="0"/>
            </div>
            <div>
                <span class='text_field'>Карта</span>
                <input type="text" class="add_location" value="0"/>
            </div>
            <div class="ft_visible">
                <span class='text_field'>Текущие агенты</span>
                <select class="add_realtor">
                    <?php
                    $i= 0;
                    while ($br_realtor[$i]) {
                        echo "<option value='".$br_realtor[$i]['id'].' '.$br_realtor[$i]['realtor']."'>".$br_realtor[$i++]['realtor']."</option>";
                    }
                    ?>
                </select>
            </div>
            <div>
                <input type="submit" class="add_save" />
            </div>
            <!--Realtor-->
<!--<div>
    <span class='text_field'>ФИО агента</span>
    <input type="text" class="rl_fio" />
</div>
<div>
    <span class='text_field'>телефон</span>
    <input type="text" class="rl_tel" />
</div>
<div>
    <input type="submit" class="realtor_save" />
</div>
</div>-->
<div class="prod_out">

</div>
<!--JavaScript Block-->
<script  type="text/javascript">
var ptype= 0;
var outdata= undefined;
var thispath="/test/";
var sitepath="/";
var tblcol= 4;
var tbrow= 15;
var tbcur= 1;
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
    jQuery.post( thispath+"filter.php", outdata,
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
    outdata.name= jQuery('.prod_addform .add_name').val();
    outdata.description= jQuery('.prod_addform .add_description').val();
    outdata.photo= jQuery('.prod_addform .add_photo').val();
    outdata.address= jQuery('.prod_addform .add_address').val();
    outdata.location= jQuery('.prod_addform .add_location').val();
    outdata.realtor= jQuery('.prod_addform .add_realtor').val();

    //console.log(outdata);
    jQuery.post( thispath+"savedata.php", outdata,
        function(data, textStatus) {
            if (textStatus == 'success') {
                console.log(data);
            } else
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
    jQuery.post( thispath+"save_realtor.php", rldata,
        function(data, textStatus) {
            if (textStatus == 'success') {
                console.log(data);
            } else
                console.log(textStatus);
        });

});
//view-node
resultout= function(data){
    //console.log('resout');
    //console.log(data);
    i=0;
    fullview= '<div class="prod_out"><div class="prod_view">';
    if (!data.length) {
        jQuery('.prod_out').replaceWith('<div class="prod_out">'+
            'РЕЗУЛЬТАТОВ НЕ НАЙДЕНО, ПОПРОБУЙТЕ ИЗМЕНИТЬ ЗАПРОС.'+
            '</div>'
        );
        return;
    }
    while (data[i] && i < 4) {
        fullview+=
            ((i == 3) ? '<div class="view_column" style="margin-right: 0">' : '<div class="view_column">')
                +
                '<a href="/?q=node/88" class="ahref">' +
                '<img src="'+data[i]['photo']+'" width="268" height="161" alt="">' +
                '</a>'+
                '<p><a href="'+data[i]['location']+'" class="ahref" style="text-transform: uppercase">'+'Название'+'</a>'+
                '<span style="display: block; text-transform: uppercase;">Пояснение</span>'+
                '</p>'+
                '<div>Описание</div>'+
                '<div style="font-weight: 600; font-size: 25px; margin-top: 20px;">'+data[i]['cost']+' р.</div>'+
//                '<a href="/?q=node/88" style="padding: 10px 17px 11px 20px; font-size: 14px; float: none;">Подробнее</a>' +
                '</div>';
        i++;
    }
    fullview+=  '</div></div>';
    //console.log(fullview);
    jQuery('.prod_out').replaceWith(fullview);

    jQuery('.prod_out').append('<br /><br /><div class="prod_table"><div class="table_head clearfix"></div></div>');
    //for
    for (i=0; i<tblcol; i++)
        jQuery('.prod_table .table_head').append('<div class="table_hcolumn" style="margin-top: 30px;">Заголовок '+i+'</div>');
    inds= [];
    tbody= '';
    tmp2= '';
    switch (ptype) {
        case 0:
            jQuery('.prod_table .table_hcolumn').eq(0).text('Адрес');
            jQuery('.prod_table .table_hcolumn').eq(1).text('Количество комнат');
            jQuery('.prod_table .table_hcolumn').eq(2).text('Этаж');
            jQuery('.prod_table .table_hcolumn').eq(3).text('Цена');
            inds.push('address', 'fid_room', 'storey', 'cost');
            for (i=0; i<data.length && i<tbrow; i++) {
                tbody+= '<div class="table_row clearfix"><div class="table_column">'+data[i][inds[0]]+'</div>';

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
            jQuery('.prod_table .table_hcolumn').eq(0).text('Адрес');
            jQuery('.prod_table .table_hcolumn').eq(1).text('Материал стен');
            jQuery('.prod_table .table_hcolumn').eq(2).text('Площадь');
            jQuery('.prod_table .table_hcolumn').eq(3).text('Цена');
            inds.push('address', 'fid_hometype', 'totalarea', 'cost');
            for (i=0; i<data.length && i<tbrow; i++) {
                tbody+= '<div class="table_row clearfix"><div class="table_column">'+data[i][inds[0]]+'</div>';

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
            jQuery('.prod_table .table_hcolumn').eq(0).text('Адрес');
            jQuery('.prod_table .table_hcolumn').eq(1).text('Площадь');
            jQuery('.prod_table .table_hcolumn').eq(2).text('Этаж');
            jQuery('.prod_table .table_hcolumn').eq(3).text('Цена');
            inds.push('address', 'totalarea', 'storey', 'cost');
            tbody+= '<div class="table_column">'+data[i][inds[0]]+'</div>';
            tbody+= '<div class="table_column">'+data[i][inds[1]]+'</div>';
            tbody+= '<div class="table_column">'+data[i][inds[2]]+'</div>';
            tbody+= '<div class="table_column">'+data[i][inds[3]]+'</div></div>';
            break;
        case 3:
            jQuery('.prod_table .table_hcolumn').eq(0).text('Адрес');
            jQuery('.prod_table .table_hcolumn').eq(1).text('Площадь');
            jQuery('.prod_table .table_hcolumn').eq(2).text('Назначение земли');
            jQuery('.prod_table .table_hcolumn').eq(3).text('Цена');
            inds.push('address', 'totalarea', 'fid_acreusage', 'cost');
            for (i=0; i<data.length && i<tbrow; i++) {
                tbody+= '<div class="table_row clearfix"><div class="table_column">'+data[i][inds[0]]+'</div>';

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

    /*tbody+= (!(i % 4)) ?
     '<div class="table_column">Запись '+i+'</div></div>'
     :
     (i % 4 == 1) ?
     '<div class="table_row clearfix"><div class="table_column">Запись '+i+'</div>'
     :
     '<div class="table_column">Запись '+i+'</div>';*/
    //if (tblcol*30 % 4) tbody+= '</div>';
    jQuery('.prod_table .table_body').append(tbody);
    jQuery('.prod_table').append('<a href="/?q=allproduct" style="padding: 10px 17px 11px 20px; font-size: 14px; float: none;">Вся недвижимость</a>');
    jQuery('.prod_table .table_row').click(function(){document.location="/?q=node/88";});
}
/*jQuery('.prod_aleft, .prod_aright').mouseenter(function(){
 console.log(123);
 jQuery('.prod_aleft img, .prod_aright img').css('visibility', 'visible');
 });*/
</script>
</body>
</html>