/******Page function*****/ //ymaps-map ymaps-i-ua_js_yes
var compCount = 0;
var compVal = [];
var cblokst = false;

mainFunc = function () {
    //
    //aboutFunc();
    pageLoaded = true;
}
aboutFunc = function () {
       // Перенес в ymap-settings.js, не нашёл этот код
    setTimeout(loadYandexMap(function () { pageLoaded = true;}), 1700);
    twitshow('abouttwit');

       /*ymaps.ready(function () {
            var myMap = new ymaps.Map('ymapid', {
                center: [48.47977212117865, 135.06400249999993],
                zoom: 16
            });

            // Для добавления элемента управления на карту
            // используется поле controls, ссылающееся на
            // коллекцию элементов управления картой.
            // Добавление элемента в коллекцию производится
            // с помощью метода add().

            // В метод add можно передать строковый идентификатор
            // элемента управления и его параметры.
            myMap.controls
                // Кнопка изменения масштаба
                .add('zoomControl')
                // Список типов карты
                .add('typeSelector')
                // Кнопка изменения масштаба - компактный вариант
                // Расположим её справа
                //.add('smallZoomControl', { right: 5, top: 75 })
                // Стандартный набор кнопок
                .add('mapTools');

            // Также в метод add можно передать экземпляр класса, реализующего определенный элемент управления.
            // Например, линейка масштаба ('scaleLine')
            myMap.controls
                .add(new ymaps.control.ScaleLine())
                // В конструкторе элемента управления можно задавать расширенные
                // параметры, например, тип карты в обзорной карте
                .add(new ymaps.control.MiniMap({
                    type: 'yandex#map'
                }), { right: 0, bottom: 0 });

            if (!(myMap.behaviors.isEnabled('scrollZoom'))) {
                myMap.behaviors.enable('scrollZoom');
            }

            // При создании метки указываем ее свойства:  текст для отображения в иконке и содержимое балуна,
            // который откроется при нажатии на эту метку 135.064003, 48.478802
            myPlacemark = new ymaps.Placemark([48.478802, 135.064003], {
                // Свойства
                //iconContent: 'Щелкни по мне',
                //balloonContentHeader: 'Заголовок',
                balloonContentBody: 'ООО \"Корея Трейд\"',
                //balloonContentFooter: 'Подвал'
            }, {
                // Опции
                preset: 'twirl#blueStretchyIcon' // иконка растягивается под контент
            });

            // Добавляем метку на карту
            myMap.geoObjects.add(myPlacemark);

            //Fix height and Width
            //$j('#ymapid > ymaps').css('height', 'inherit')
            //    .css('width', 'auto');
                
        });*/
}


/***********List Generation Function***********/

function insertRow(index, data, cartype, carmark) {
    var image = '';
    if (data.img.length) {
        image = '<div class="photo">' +
                        '<a onclick="showInfo(' + index + ', \'' + data.name + '\'); return false;" href="pages/' + data.name.toLowerCase().translit() + '.html"  class="linkcheckflag" >' +
                            '<span></span>' +
                                '<img width="100px" height="75px" src="' + data.img[0] + '" alt="' + carmark + ' ' + data.name + ' Coreatrade Корея Трейд Автомобили Автобусы Спецтехника Сельхозтехника из Кореи" />' + //width="auto" height="100px"
                        '</a>' +
                    '</div>' +
                    '<div class="expimgcount">' + data.img.length + ' изображений' + '</div>';
    }
    else
        image = '<div class="expimgcount"> Нет изображений </div>';
    /*getByType(data.type, function (cartype) {
        getByMark(data.mark, function (carmark) {*/
    $j(".navcur tbody#Table").append('<tr class="explistrow0">' +
            '<td width="100px">' + image +
                '<input type="hidden" value="' + index + '">' +
            '</td>' +
            '<td width="200px;">' + '&nbsp' + '<div class="expautos_list_markmod"> <a onclick="showInfo(' + index + ', \'' + data.name + '\');  return false;" href="pages/' + data.name.toLowerCase().translit() + '.html"  class="linkcheckflag" >' + data.name + '&nbsp' + '</a></div>' +
                '<h6>' + carmark + '&nbsp' + data.model + '</h6>' +
                '<div style="position: relative;">' + cartype +
                    '<div class="link" style="top:0; left:100%; white-space:nowrap;">' +
                        '<input type="checkbox" class="comp_check" value="' + index + '" onchange="updateCompare($j(this));"/>' +
                        '<span class="compare_add" onclick="targ=$j(this).prev(); targ.prop(\'checked\', !targ.prop(\'checked\')); targ.trigger(\'onchange\');">Добавить</span>' +
                    '</div>' +
                '</div></td>' +
            '<td>' + '&nbsp' + data.year + '&nbsp' + '</td>' +
            '<td>' + '&nbsp' + data.vvv + '&nbsp' + '</td>' +
            '<td>' + '&nbsp' + data.fuel + '&nbsp' + '</td>' +
            '<td>' + '&nbsp' + data.drive + '&nbsp' + '</td>' +
            '<td>' + '&nbsp' + data.kpp + '&nbsp' + '</td>' +
            '<td>' + '&nbsp' + data.power + '&nbsp' + '</td>' +
            '<td>' + '&nbsp' + data.price + '&nbsp</td>' +
            '</tr>');
}
function updateTableCompareFix() {
    if (compVal.length)
    /*    tmp="";
    for (i = 0; i < compVal.length; i++) tmp += i + " -- " + compVal[i] + "\n\r";
    alert(tmp);*/
        for (i = 0; i < compVal.length; i++) {
            part = $j('.comp_check[value="' + compVal[i] + '"]');
            part.not(':checked').next().text('Удалить')
                                .removeClass('compare_add')
                                .addClass('compare_del');
            part.not(':checked').prop('checked', true);
        }
    if (cblokst) {
        $j('.navcur .compare_hlout').css('width', '13%');
        $j('.navcur .compare_hlin').css('display', 'block');
        $j('.navcur .compare_lc_content').replaceWith($j('.compare_lc_content').first().clone());
    }
}
function updateTable() {
    var FilterBrand = parseInt($j(".navcur #BrandOut").attr("value"));
    var FilterType = parseInt($j(".navcur #TypeOut").attr("value"));
    $j(".navcur tbody#Table").empty();
//alert(FilterBrand + ' ' + FilterType);

        if (!FilterType && !FilterBrand) {
            jgetType(function (cartype) {
                jgetBrand(function (carmark) {
                    jgetData(function (car) {
                        for (i = 0; i < car.length; i++) {
                            tmpmark = car[i].mark;
                            tmptype = car[i].type;
                            insertRow(i, car[i], cartype[tmptype].type, carmark[tmpmark].mark);
                        }
                        pageLoaded = true;
                        updateTableCompareFix();
                    });
                });
            });
            return;
        }
        if (!FilterBrand) {
            getByType(FilterType-1, function (cartype) {
                out = cartype.model;
                if (out.length) {
                    jgetBrand(function (carmark) {
                        jgetData(function (car) {
                            for (i = 0; i < out.length; i++) {
                                tmp = car[out[i]].mark;
                                insertRow(out[i], car[out[i]], cartype.type, carmark[tmp].mark);
                            }
                            pageLoaded = true;
                            updateTableCompareFix();
                        });
                    });
                } else
                    pageLoaded = true;
            });
            return;
        }
        if (!FilterType) {
            getByMark(FilterBrand-1, function (carmark) {
                out = carmark.model;
                if (out.length) {
                    jgetType(function (cartype) {
                        jgetData(function (car) {
                            for (i = 0; i < out.length; i++) {
                                tmp = car[out[i]].type;
                                insertRow(out[i], car[out[i]], cartype[tmp].type, carmark.mark);
                            }
                            pageLoaded = true;
                            updateTableCompareFix();
                        });
                    });
                } else
                    pageLoaded = true;
            });
            return;
        }
        getByType(FilterType-1, function (cartype) {
            getByMark(FilterBrand-1, function (carmark) {
                cartype.model.sort(function (a, b) { return a - b; });
                carmark.model.sort(function (a, b) { return a - b; });
                out = [];

                if (cartype.model.length > carmark.model.length) {
                    max = cartype.model;
                    min = carmark.model;
                } else {
                    min = cartype.model;
                    max = carmark.model;
                }

                for (i = max.length - 1; i >= 0 && min.length; i--)
                    for (j = min.length-1; j >= 0 ; j--) {
                        if (max[i] == min[j]) {
                            out.push(min[j]);
                            min.pop();
                        }
                    }

                if (out.length) {
                    jgetData(function (car) {
                        for (i = 0; i < out.length; i++)
                            insertRow(out[i], car[out[i]], cartype.type, carmark.mark);
                        pageLoaded = true;
                        updateTableCompareFix();
                    });
                } else
                    pageLoaded = true;
            });
        });
}

/*function updateTable() {
    var FilterBrand = parseInt($j(".navcur #BrandOut").attr("value"));
    var FilterType = parseInt($j(".navcur #TypeOut").attr("value"));
    $j(".navcur tbody#Table").empty();
//alert(FilterBrand + ' ' + FilterType);

    jgetData(function (car) {
        if (!FilterType && !FilterBrand) {
            for (i = 0; i < car.length; i++) insertRow(i, car[i]);
            return;
        }
        if (!FilterBrand) {
            for (i = 0; i < car.length; i++)
                if (FilterType - 1 == parseInt(car[i].type)) insertRow(i, car[i]);
            return;
        }
        if (!FilterType) {
            for (i = 0; i < car.length; i++)
                if (FilterBrand - 1 == parseInt(car[i].mark)) insertRow(i, car[i]);
            return;
        }
        for (i = 0; i < car.length; i++)
            if (FilterBrand - 1 == parseInt(car[i].mark) && FilterType - 1 == parseInt(car[i].type))
                insertRow(i, car[i]);
    });
}*/

function tableBase(header, markid, typeid) {
    $j(header).append(
             '<!-- Content -->' +
             '<div id="content">' +
                '<div class="wrapper">' +
                    '<div id="system-message-container"></div>' +
                    '<div class="table compare_hlout" style="width: 8%;">' +
                        '<div class="compare_hlin" style="display: none">' +
                            '<div class="compare_lc_head">' +
                                '<h4 style="text-align: center; font-size: 100%;">Сравнение характеристик</h4>' +
                            '</div>' +
                            '<div class="compare_lc_content">' +
//Compare List
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="table" style="width: 85%; position: relative;">' +
                        '<h2 class="h2list">Модели</h2>' +
                        '<div class="table" style="width: 20%; font-size: 1.2em">' +
                            '<span>Фильтровать по</span>' +
                            '<a onclick="showInfo(\'search_advance\', \'Поиск\'); return false;" href="pages/search_advance.html" class="link" style="left: 0;">Подробный поиск</a>' +
                            '<span class="link compare_lc" style="left: 20%;" onclick="showCompareInfo()">Сравнить</span>' +
                            '<span class="link compare_clean" style="left: 32.25%;" onclick="clc_clear()">Очистить</span>' +
                        '</div>' +
                        '<div class="table middle">' +
                            '<span>Марке</span>' +
                                '<select id = "BrandOut" name="Brand" size=1 onchange="updateTable();">' +
//Mark List
                                 '</select>' +
                        '</div>' +
                        '<div class="table middle">' +
				            '<span>Типу</span>' +
                             '<select id = "TypeOut" name="Type" size=1 onchange="updateTable();">' +
//Type List
                             '</select>' +
				        '</div>' +
				        '<div class="newrow"></div>' +
				        '<div id="expskins_module"></div>' +
					    '<div class="expautospro_clear"></div>' +
                        '<!--table will be here-->' +
				        '<div id = "expautospro" style="text-align:center">' +
					        '<table class="explist">' +
						        '<thead>' +
							        '<tr style="height: 50px; line-height: 1em;">' +
								        '<th>Изображение</th>' +
								        '<th>Марка/Модель</th>' +
								        '<th>Год</th>' +
                                        '<th>Объём л.</th>' +
                                        '<th>Топливо</th>' +
                                        '<th>Привод</th>' +
                                        '<th>КПП</th>' +
                                        '<th>Мощность л.с.</th>' +
								        '<th>Цена т.р.</th>' +
							        '</tr>' +
						        '</thead>' +
						        '<tbody id = "Table">' +
				                '</tbody>' +
				            '</table>' +
				        '</div>' +
					    '<!--table will be here-->      ' +
                    '</div>' +
                    '<div class="newrow"></div>' +
                 '</div><!-- Content Main -->' +
                '<!-- Bottom -->' +
                '<div id="bottom" class="clearfix">' +
                    '<div class="wrapper">' +
                        '<div id="car-logos">' +
                            '<div class="custom"  >' +
                                '<img src="images/car_logos.jpg" border="0" alt="Car Logos" width="960" height="74" />' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div><!-- Bottom -->' +
        '</div>'
        );
    $j(".navcur #BrandOut").append("<option value='0'>Все</option>");
    $j(document).ready(function () {
        // Handler for .ready() called.
        jgetBrand(function (data) {
            for (i = 1; i < data.length + 1; i++)
                $j(".navcur #BrandOut").append("<option value='" + i + "'>" + data[i - 1].mark + "</option>");
            $j(".navcur #BrandOut option[value=" + (markid + 1) + "]").attr("selected", "selected");
            $j(".navcur #TypeOut").append("<option value='0'>Все</option>");

            jgetType(function (data) {
                for (i = 1; i < data.length + 1; i++)
                    $j(".navcur #TypeOut").append("<option value='" + i + "'>" + data[i - 1].type + "</option>");
                $j(".navcur #TypeOut option[value=" + (typeid + 1) + "]").attr("selected", "selected");

                updateTable();
            });
        });
    });
}


/***********Car More Function***********/

carMoreInfo = function (head, id) {

    jgetData(function (data) {
        car = data[id];
        getByMark(car.mark, function (carmark) {
            $j(head).append(

        '<!-- Content -->' +
    '<div id="content">' +
        '<div class="wrapper" style="width: 980px">' +
            '<!-- Columns Container -->' +
            '<div id="columns-container">' +
                '<div id="outer-column-container" style="border-left-width:320px; border-right-width:0">' +
                    '<div id="inner-column-container" class="clearfix">' +
                        '<div id="source-order-container">' +

                           '<!-- Middle Column -->' +
                           '<div id="middle-column">' +
                               '<div class="inside" style="padding-left:15px;">' +
                                    '<div id="system-message-container"></div>' +
                                            '<div class="expautospro_clear"></div>' +
                                          '<div id="expautospro">' +
    //Car name
                                                '<h2>' + car.name + '</h2>' +
                                            '<div id="expautos_detail">' +
                                                '<div class="expautos_detail_topname">Подробное описание</div>' +
                                                '<div class="expautos_detail_left" >' +
    //Main Image
                                                            '<a href="' + car.img[0] + '" rel="prettyPhoto466[gallery]" title="(' + carmark.mark + ') ' + car.name + '" >' +
                                                                '<img class="hoverBox" src="' + car.img[0] + '" alt="' + carmark.mark + ' ' + car.name + '  Coreatrade Корея Трейд Автомобили Автобусы Спецтехника Сельхозтехника из Кореи" title="(' + carmark.mark + ') ' + car.name + '" style="height: auto; width: 100%; margin-bottom: 20px" />' +
                                                            '</a>' +
                                                    '<div class="expautospro_clear"></div>' +
                                                    '<div class="expautos_detail_equipment">' +
    //Equipment (Complectation)
                                                        '<h3 style="font-size: 1.75em; margin-top: 0">Комплектация</h3>' +
                                                        '<div style="padding-bottom: 20px; text-align: center;">Наведите указатель на *термин для просмотра подробной информации</div>' +
    //****************Here
                                                    '</div>' +
                                                    '<div class="expautospro_clear"></div>' +
                                                '</div>' +
                                                '<div class="expautos_detail_right" style="width: 36%">' +
                                                    '<div class="moduletable_menu">' +
    //Main Info header
                                                        '<h3>Общая информация</h3>' +
                                                        '<div class="more-minfo" style="display: block; float: left; width: 50%">' +
                                                            '<p><span class="expautos_bprice">Цена: </span></p>' +
                                                            '<p><span>Марка: </span></p>' +
                                                            '<p><span>Модель: </span></p>' +
    //****************Here
                                                        '</div> ' +
    //Main Info
                                                        '<div class="more-minfo" style="display: block; float: left; width: 50%">' +
    //****************Here
                                                        '</div> ' +
                                                        '<div class="newrow"></div>' +
                                                    '</div>' +
                                                 '</div>' +
                                            '</div>' +
                                          '</div>' +
                                            '<div class="expautospro_clear"></div>' +
                                            '<div class="expautospro_botmodule"></div>' +
                               '</div>' +
                            '</div><!-- Middle Column -->         ' +

                           '<!-- Left Column Image Only-->' +
                            '<div id="left-column" style=" margin-left: -358px; width: 358px;">' +
                                '<div class="inside">' +
                                    '<div class="col-module ">' +
                                        '<div class="col-module-suffix-">' +
                                            '<div class="col-module-bottom">' +
                                                '<div class="col-module-content clearfix">' +
    //Images
                                                    '<div class="hoverBox hoverBox466">' +
                                                    '</div>' +
    //****************Here
                                                '</div>' +
                                            '</div>   ' +

                                        '</div>' +
                                    '</div>' +
                                '</div>' +
                            '</div> <!-- Left Column -->' +

                            '<!-- Left Column Std-->' +
                            '<div id="left-col-init" >' + //style="display: none"
                                '<div class="inside">' +
                                    '<div class="col-module ">' +
                                        '<div class="col-module-suffix-">' +
                                            '<div class="col-module-bottom">' +
                                                '<div class="col-module-header">' +
                                                    '<h3 class="mod-title" style="text-align: center">Автотвиты</h3>' +
                                                '</div>' +
                                                '<div class="col-module-content clearfix" id="twit' + car.name + '">' +
    '<!--Twitter Pane-->' +
                                                    '</div>' +
                                                '</div>' +

                                            '</div>   ' +
                                        '</div>' +
                                    '</div>' +
                                '</div>  <!-- Left Column -->' +

                              '<div class="clear-columns"></div>' +
                            '</div><!-- Source Order Container -->' +
                        '</div>' +
                    '</div>' +
                '</div><!-- Columns Container --> ' +
            '</div><!-- Content Main -->' +

            '<!-- Bottom -->' +
                '<div id="bottom" class="clearfix">' +
                    '<div class="wrapper">' +
                        '<div id="car-logos">' +
                            '<div class="custom"  >' +
                                '<img src="images/car_logos.jpg" border="0" alt="Car Logos" width="960" height="74" />' +
                            '</div>' +
                        '</div>                                             ' +
                    '</div>' +
                '</div><!-- Bottom -->' +
        '</div>    <!-- Content -->'
        );
            //twitshow('twit' + car.name, 'avtovesti');

            //Main Info
            //getByMark(car.mark, function (carmark) {
            tmphead = "";
            tmpdata = "";

            if (car.price) {
                prtmp = parseInt(car.price);
                tmpdata += (isNaN(prtmp)) ? '<p>' + car.price + '</p>' :
                    '<p>' + car.price + ' т.р.</p>';
            }
            tmpdata += '<p>' + carmark.mark + '</p>';
            tmpdata += '<p>' + car.model + '</p>';
            if (car.year) { tmphead += '<p><span>Год: </span></p>'; tmpdata += '<p>' + car.year + '</p>'; }
            if (car.vvv) { tmphead += '<p><span>Объём: </span></p>'; tmpdata += '<p>' + car.vvv + ' л.</p>'; }
            if (car.power) { tmphead += '<p><span>Мощность: </span></p>'; tmpdata += '<p>' + car.power + ' л.с.</p>'; }
            if (car.fuel) { tmphead += '<p><span>Топливо: </span></p>'; tmpdata += '<p>' + car.fuel + '</p>'; }
            if (car.kpp) { tmphead += '<p><span>Передача: </span></p>'; tmpdata += '<p>' + car.kpp + '</p>'; }
            if (car.drive) { tmphead += '<p><span>Привод: </span></p>'; tmpdata += '<p>' + car.drive + '</p>'; }
            if (car.door) { tmphead += '<p><span>Двери: </span></p>'; tmpdata += '<p>' + car.door + '</p>'; }
            if (car.seat) { tmphead += '<p><span>Сиденья: </span></p>'; tmpdata += '<p>' + car.seat + '</p>'; }
            if (car.color) { tmphead += '<p><span>Цвет: </span></p>'; tmpdata += '<p>' + car.color + '</p>'; }
            if (car.city) { tmphead += '<p><span>Город: </span></p>'; tmpdata += '<p>' + car.city+ '</p>'; }

            $j(head + ' .more-minfo').eq(0).append(tmphead);
            $j(head + ' .more-minfo').eq(1).append(tmpdata);
            //});

            //Equipment 
            tmp = "";
            complect = car.complect.split('\n');
            for (i = 0; i < complect.length; i++) {
                tmp += (i % 2) ?
                    '<div class="table complect">' +
                        '<div class="exp_autos_equiptable exp_autos_equip">' + complect[i] + '</div>' +
                    '</div>' +
                    '<div class="newrow"></div>'
                                :
                    '<div class="table complect">' +
                        '<div class="exp_autos_equiptable exp_autos_equip">' + complect[i] + '</div>' +
                    '</div>' +
                    '<div class="table complect_middle ">.</div>';
            }
            $j(head + ' .expautos_detail_equipment').append(tmp);

            //Image
            tmp = "";
            botmimgmod = (car.img.length - 1) % 3;
            botmimgmod = (botmimgmod) ? botmimgmod : 3;
            botmimgdiv = car.img.length - botmimgmod;
            for (i = 1; i < botmimgdiv; i++)
                tmp += '<div class="hoverBoxGallery466">' +
                            '<a href="' + car.img[i] + '" rel="prettyPhoto466[gallery]" title="(' + carmark.mark + ') ' + car.name + '" >' +
                                '<img class="hoverBox" src="' + car.img[i] + '" alt="' + carmark.mark + ' ' + car.name + ' Coreatrade Корея Трейд Автомобили Автобусы Спецтехника Сельхозтехника из Кореи" title="(' + carmark.mark + ') ' + car.name + '" />' +
                            '</a>' +
                        '</div>';
            $j(head + ' .hoverBox466').append(tmp);
            $j(head + ' .hoverBox466 div:nth-child(3n)').addClass('hoverBox_right');
            $j(head + ' .hoverBox466 > div').filter(function (index) { return index % 3 - 2 }).addClass('hoverBox_left');
            tmp = "";

            while (botmimgmod > 1) {
                tmp += '<div class="hoverBoxGallery466 hoverBox_bottom_left">' +
                            '<a href="' + car.img[botmimgdiv + botmimgmod - 1] + '" rel="prettyPhoto466[gallery]" title="(' + carmark.mark + ') ' + car.name + '" >' +
                                '<img class="hoverBox" src="' + car.img[botmimgdiv + botmimgmod - 1] + '" alt="' + carmark.mark + ' ' + car.name + ' Coreatrade Корея Трейд Автомобили Автобусы Спецтехника Сельхозтехника из Кореи" title="(' + carmark.mark + ') ' + car.name + '" />' +
                            '</a>' +
                        '</div>';
                botmimgmod--;
            }
            tmp += '<div class="hoverBoxGallery466 hoverBox_bottom_right">' +
                            '<a href="' + car.img[botmimgdiv] + '" rel="prettyPhoto466[gallery]" title="(' + carmark.mark + ') ' + car.name + '" >' +
                                '<img class="hoverBox" src="' + car.img[botmimgdiv] + '" alt="' + carmark.mark + ' ' + car.name + ' Coreatrade Корея Трейд Автомобили Автобусы Спецтехника Сельхозтехника из Кореи" title="(' + carmark.mark + ') ' + car.name + '" />' +
                            '</a>' +
                        '</div>';
            $j(head + ' .hoverBox466').append(tmp);

            //Left column top position fix
            twith = Math.ceil((car.img.length - 1) / 3);
            twith = (twith - 1) * 85 + 139;     //+15
                //colimgtop = $j('.navcur #left-column').offset().top;
                //colimgtop = $j('.navcur #left-column').height();
                $j('.navcur #left-col-init').css('margin-top', twith);
                                            //.css('display', 'block');
            twitshow('twit' + car.name, 'avtovesti');

            //prettyPhoto
            $j('a[rel^=\'prettyOverlay\'],a[rel^=\'prettyPhoto466\']').prettyPhoto({
                animation_speed: 'fast',
                show_title: false,
                opacity: 0.6,
                allowresize: true,
                counter_separator_label: '/',
                overlay_gallery: 1,
                theme: 'dark_rounded'
            });

            pageLoaded = true;
        });
    });
}

/***********Car Compare Function***********/
carCompareBase = function (head, obj) {

    $j(head).append(

    '<!-- Content -->' +
    '<div id="content" style="padding-top: 40px; padding-bottom: 0; width: 300%; ">' +
    '<div class="clearfix cmp_dataentry" style="clear: both; margin-left: 15em; ">' +
                                  '<div class="cmp_head" >' +
                                        '<h3 style="visibility: hidden; font-size: 100%; font-size: 1.5em;">.</h3>' +
                                        '<span class="link" style="left: 0; top: 0;" onclick="showCompareInfo()">Обновить</span>' +
                                    '<div>' +
                                        '<div class="expautos_detail_left" style="width:100%">' +
                                            '<div class="expautospro_clear"></div>' +
                                            '<div class="moduletable_menu">' +
    //Main Info header
                                                '<div class="more-minfo" style="display: block; float: left;">' +
                                                    '<p><span class="expautos_bprice">Цена: </span></p>' +
                                                    '<p><span>Марка: </span></p>' +
                                                    '<p><span>Модель: </span></p>' +
                                                    '<p><span>Год: </span></p>' +
                                                    '<p><span>Объём: </span></p>' +
                                                    '<p><span>Мощность: </span></p>' +
                                                    '<p><span>Топливо: </span></p>' +
                                                    '<p><span>Передача: </span></p>' +
                                                    '<p><span>Привод: </span></p>' +
                                                    '<p><span>Двери: </span></p>' +
                                                    '<p><span>Сиденья: </span></p>' +
                                                    '<p><span>Цвет: </span></p>' +
                                                    '<p><span>Город: </span></p>' +
                                                    '<p style="margin-top: 30px;"><span>Комплектация: </span></p>' +
                                                '</div> ' +
                                                 '<div class="newrow"></div>' +
                                            '</div>' +
                                            '<div class="expautospro_clear"></div>' +
                                        '</div>' +
                                    '</div>' +
                                  '</div>' +
    //Dynamic fill START
    //Dynamic fill END
    '</div><!-- Content Main -->' /*+

    '<!-- Bottom -->' +
    '<div id="bottom" class="clearfix">' +
        '<div class="wrapper" style="margin-left: 20px;">' +
            '<div id="car-logos">' +
                '<div class="custom"  >' +
                    '<img src="images/car_logos.jpg" border="0" alt="Car Logos" width="960" height="74" />' +
                '</div>' +
            '</div>                                             ' +
        '</div>' +
    '</div><!-- Bottom -->' +
    '</div>    <!-- Content -->'*/
    );

    carCompareInfo(obj);
}
carCompareInfo = function (ids) {
   
    jgetData(function (data) {
        jgetBrand(function (carmark) {
            for (id = 0; id < ids.length; id++) {
                //alert(id + '  ' + data[parseInt(ids[id])] + 1000);
                car = data[parseInt(ids[id])];
                //$j(head + ' .cmp_dataentry').append('<p>78834848' + carmark[car.mark].mark + '</p>');
                
                //Main Info
                tmpdata = "";
                if (car.price) {
                    prtmp = parseInt(car.price);
                    tmpdata += (isNaN(prtmp)) ? '<p>' + car.price + '</p>' :
                        '<p>' + car.price + ' т.р.</p>';
                }
                tmpdata += '<p>' + carmark[car.mark].mark + '</p>';
                tmpdata += '<p>' + car.model + '</p>';
                if (car.year) tmpdata += '<p>' + car.year + '</p>'; 
                if (car.vvv) tmpdata += '<p>' + car.vvv + ' л.</p>';
                if (car.power) tmpdata += '<p>' + car.power + ' л.с.</p>';
                if (car.fuel) tmpdata += '<p>' + car.fuel + '</p>';
                if (car.kpp) tmpdata += '<p>' + car.kpp + '</p>';
                if (car.drive) tmpdata += '<p>' + car.drive + '</p>';
                if (car.door) tmpdata += '<p>' + car.door + '</p>';
                if (car.seat) tmpdata += '<p>' + car.seat + '</p>';
                if (car.color) tmpdata += '<p>' + car.color + '</p>';
                if (car.city) tmpdata += '<p>' + car.city + '</p>';

                //Equipment 
                tmp = "";
                complect = car.complect.split('\n');
                for (i = 0; i < complect.length; i++) {
                    tmp += (i % 2) ?
                        '<div class="table complect">' +
                            '<div class="exp_autos_equiptable exp_autos_equip">' + complect[i] + '</div>' +
                        '</div>' +
                        '<div class="newrow"></div>'
                                    :
                        '<div class="table complect">' +
                            '<div class="exp_autos_equiptable exp_autos_equip">' + complect[i] + '</div>' +
                        '</div>' +
                        '<div class="table complect_middle ">.</div>';
                }

                $j('.cmp_dataentry').append(
    //Dynamic fill START
                                  '<div class="cmp_maintb">' +
                                        '<h3 style="font-size: 100%; font-size: 1.5em;">' + car.name +
    //Car.name
                                        '</h3>' +
                                        '<input type="hidden" value="' + ids[id] + '">' +
                                    '<div>' +
                                        '<div class="expautos_detail_left" style="width:100%">' +
                                            '<div class="expautospro_clear"></div>' +
                                            '<div class="moduletable_menu">' +
                                                '<div class="more-minfo" style="display: block; float: left; width: 50%;">' +
    //Main Image
                                                    '<a href="' + car.img[0] + '" rel="prettyPhoto466[gallery]" title="(' + carmark[car.mark].mark + ') ' + car.name + '" >' +
                                                        '<img class="hoverBox cmp_mainimg" src="' + car.img[0] + '" alt="(' + carmark[car.mark].mark + ') ' + car.name + '" title="(' + carmark[car.mark].mark + ') ' + car.name + '" />' +
                                                    '</a>' +
                                                    '<div style="position:relative;">' +
                                                        '<div class="link" style="top:0; white-space:nowrap;">' +
                                                            '<input type="checkbox" checked="checked" class="comp_mainbtn" value="' + ids[id] + '" onclick="targ=$j(\'.comp_check[value=\\\'' + ids[id] + '\\\']\').first(); targ.prop(\'checked\', !targ.prop(\'checked\')); updateCompare(targ); " />' +
                                                                '<span class="compare_del" onclick="targ=$j(\'.comp_check[value=\\\'' + ids[id] + '\\\']\').first(); targ.prop(\'checked\', !targ.prop(\'checked\')); updateCompare(targ); ">Удалить</span>' +
                                                        '</div>' +
                                                    '</div>' +
                                                '</div>' +
    //Main Info
                                                '<div class="more-minfo" style="display: block; float: left; width: 50%;">' + tmpdata +
    //****************Here
                                                '</div> ' +
                                                 '<div class="newrow"></div>' +
                                            '</div>' +
    //Equipment (Complectation)
                                            '<div class="expautos_detail_equipment" style="margin-top:30px;">' +
                                                 '<h3 style="font-size: 100%; font-size: 1.5em; margin-bottom:15px;">' + car.name +
            //Car.name
                                                '</h3>' + tmp +
    //****************Here
                                            '</div>' +
                                            '<div class="expautospro_clear"></div>' +
                                        '</div>' +
                                    '</div>' +
                                  '</div>'
    //Dynamic fill END
                );

                //prettyPhoto
                $j('a[rel^=\'prettyOverlay\'],a[rel^=\'prettyPhoto466\']').prettyPhoto({
                    animation_speed: 'fast',
                    show_title: false,
                    opacity: 0.6,
                    allowresize: true,
                    counter_separator_label: '/',
                    overlay_gallery: 1,
                    theme: 'dark_rounded'
                });
            }
        });
    });
}

updateCompare = function (cobj) {
    if (cobj.prop('checked')) {
        compCount++;
        cobj.next().text('Удалить')
                    .toggleClass('compare_add')
                    .toggleClass('compare_del');
        part = $j('.comp_check[value="' + cobj.val() + '"]');
        part.not(':checked').next().text('Удалить')
                            .removeClass('compare_add')
                            .addClass('compare_del');
        part.not(':checked').prop('checked', true);

        compVal.push(cobj.val());
        id = parseInt(compVal[compVal.length - 1]);
        carname = cobj.parents('td').find('a').text();
        if ($j('.compare_lc_content').length == 1) {
            $j('.compare_lc_content').append(
                '<p>' +
                    '<input type="checkbox" class="comp_boxck" checked="checked" value="' + id + '" onclick="targ=$j(\'.comp_check[value=\\\'' + id + '\\\']\').first(); targ.prop(\'checked\', !targ.prop(\'checked\')); updateCompare(targ); "/>' +
                    carname +
                '</p>'
                );
        }
        else {
            $j('.compare_lc_content').first().append(
                '<p>' +
                    '<input type="checkbox" class="comp_boxck" checked="checked" value="' + id + '" onclick="targ=$j(\'.comp_check[value=\\\'' + id + '\\\']\').first(); targ.prop(\'checked\', !targ.prop(\'checked\')); updateCompare(targ); "/>' +
                    carname +
                '</p>'
            );
            $j('.compare_lc_content').not(':first').replaceWith($j('.compare_lc_content').first().clone());
        }
    }
    else {
        compCount--;
        cobj.next().text('Добавить')
                    .toggleClass('compare_add')
                    .toggleClass('compare_del');
        part = $j('.comp_check[value="' + cobj.val() + '"]:checked');
        part.next().text('Добавить')
                            .removeClass('compare_del')
                            .addClass('compare_add');
        part.prop('checked', false);

        compVal.splice(compVal.indexOf(cobj.val()), 1);
        $j('.comp_boxck[value="' + cobj.val() + '"]').parents('p').remove();
        if ($j('.comp_mainbtn').length)
            $j('.comp_mainbtn[value="' + cobj.val() + '"]').parents('.cmp_maintb').hide(function () {
                $j('.comp_mainbtn[value="' + cobj.val() + '"]').parents('.cmp_maintb').remove();
            });
    }

    if (compCount > 1 && !cblokst) {
        $j('.compare_hlout').css('width', '13%');
        $j('.compare_hlin').css('display', 'block');
        cblokst = true;
    } else if (!compCount) {
        $j('.compare_hlout').css('width', '8%');
        $j('.compare_hlin').css('display', 'none');
        cblokst = false;
    }

}

clc_clear = function () {
    $j('.comp_check').next().text('Добавить')
                            .removeClass('compare_del')
                            .addClass('compare_add');
    $j('.comp_check').prop('checked', false);
    $j('.compare_lc_content').empty();

    compCount = 0;
    compVal = [];
    cblokst = false;

    $j('.compare_hlout').css('width', '8%');
    $j('.compare_hlin').css('display', 'none');
}

showCompareInfo = function () {
    if ($j('.cmp_dataentry').length) {
        $j('.cmp_maintb').remove();
        carCompareInfo(compVal);
        showInfo(compVal, 'compare');
    } else
        showInfo(compVal, 'compare');
};

/**********Additional Search Page Function*******************/

slock = false;

(function ($j) {
    $j.fn.indexOf = function (el) {
        out = -1;
        $j(this).each(function (id) {
            //Do stuff for each element in matched set
            if (el == $j(this).val()) {
                out = id;
                return out;
            }
        });
        return out;
    };
})(jQuery);
(function ($j) {
    $j.fn.getVal = function () {
        out = [];
        if ($j(this).length)
            $j(this).each(function (id) {
                //Do stuff for each element in matched set
                if ($j(this).val().match(/([A-zА-я])|(\0)/) || !$j(this).val()) out.push($j(this).val());
                else {
                    tmp = parseFloat($j(this).val());
                    if (tmp % 1)
                        out.push(tmp);
                    else
                        out.push((tmp + 0));
                }
                //out.push($j(this).val());
            });
        return out;
    }
})(jQuery);

function tableSearch(header) {
    $j(header).append(
             '<!-- Content -->' +
             '<div id="content">' +
                '<div class="wrapper">' +
                    '<div id="system-message-container"></div>' +
                    '<div class="table compare_hlout" style="width: 8%;">' +
                        '<div class="compare_hlin" style="display: none">' +
                            '<div class="compare_lc_head">' +
                                '<h4 style="text-align: center; font-size: 100%;">Сравнение характеристик</h4>' +
                            '</div>' +
                            '<div class="compare_lc_content">' +
//Compare List
                            '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="table" style="width: 85%; position: relative;">' +
                        '<h2 class="h2list" style="margin-bottom: 30px;">Модели</h2>' +
                        '<div class="table" style="width: 20%; font-size: 1.2em">' +
                            //'<a class="link" style="left:0; top: 100px" onclick="seoTrans(); return false;" href="ddee33.html" >поиск</a>' +
                            //'<a href="#" class="link" style="left: 0;">Подробный поиск</a>' +
                            '<span class="link compare_lc" style="left: 0;" onclick="showCompareInfo()">Сравнить</span>' +
                            '<span class="link compare_clean" style="left: 12.25%;" onclick="clc_clear()">Очистить</span>' +
                        '</div>' +
//Prices
                        '<h5 class="table">Цена</h5><span class="h5note">(т. р.)</span>' +
                        '<div class="find_prices table">' +
                            '<div class="table">' +
                                '<input type="radio" checked="checked" value="1" name="findprice" class="findprice table" />' +
                                '<span class="numberhead">' +
                                    '<span class="label">от</span>' +
                                    '<input type="text" class="numberinput fromprice" >' +
	                                '<span class="numbtnbase">' +
		                                '<button class="numberbtn btnup" >&#708;</button>' +
		                                '<button class="numberbtn btndown" >&#709;</button>' +
	                                '</span>' +
                                '</span>' +
                                '<span class="numberhead">' +
                                    '<span class="label">до</span>' +
                                    '<input type="text" class="numberinput ofprice" >' +
	                                '<span class="numbtnbase">' +
		                                '<button class="numberbtn btnup" >&#708;</button>' +
		                                '<button class="numberbtn btndown" >&#709;</button>' +
	                                '</span>' +
                                '</span>' +
                            '</div>' +
                            '<div class="newrow"></div>' +
                            '<div><input type="radio" value="0" name="findprice" class="findprice" /><span>Договорная</span></div>' +
                        '</div>' +
                        '<button style="margin-left: 5%;" onclick="findclear();">Сбросить</button>' +
                        '<div style="clear: both;"></div>' +
                        '<div class="table find_marks" style="width: 14%;">' +
                            '<h5>Марка</h5>' +
//Mark List
                        '</div>' +
                        '<div class="table find_types" style="width: 17%;">' +
				            '<h5>Тип</h5>' +
//Type List
				        '</div>' +
//From/Of Head
                        '<div class="fromof_findhead table">' +
                            '<h5 class="table">Год</h5>' +
                            '<div class="newrow"></div>' +
                            '<h5 class="table">Мощность</h5><span class="h5note">(л. с.)</span>' +
                            '<div class="newrow"></div>' +
                            '<h5 class="table">Объём</h5><span class="h5note">(л.)</span>' +
                            '<div class="newrow"></div>' +
                            '<h5 class="table">Двери</h5></span>' +
                            '<div class="newrow"></div>' +
                            '<h5 class="table">Сиденья</h5>' +
                            '<div class="newrow"></div>' +
                        '</div>' +

                        '<div class="fromof_findcol table">' +
//From/Of Year
                            '<div class="find_years">' +
                                    '<span class="numberhead">' +
                                        '<span class="label">от</span>' +
                                        '<input type="text" class="numberinput fromyear" >' +
	                                    '<span class="numbtnbase">' +
		                                    '<button class="numberbtn btnup" >&#708;</button>' +
		                                    '<button class="numberbtn btndown" >&#709;</button>' +
	                                    '</span>' +
                                    '</span>' +
                                    '<span class="numberhead">' +
                                        '<span class="label">до</span>' +
                                        '<input type="text" class="numberinput ofyear" >' +
	                                    '<span class="numbtnbase">' +
		                                    '<button class="numberbtn btnup" >&#708;</button>' +
		                                    '<button class="numberbtn btndown" >&#709;</button>' +
	                                    '</span>' +
                                    '</span>' +
                                '<div class="newrow"></div>' +
                            '</div>' +
//From/Of power
                            '<div class="find_powers">' +
                                    '<span class="numberhead">' +
                                        '<span class="label">от</span>' +
                                        '<input type="text" class="numberinput frompower" >' +
	                                    '<span class="numbtnbase">' +
		                                    '<button class="numberbtn btnup" >&#708;</button>' +
		                                    '<button class="numberbtn btndown" >&#709;</button>' +
	                                    '</span>' +
                                    '</span>' +
                                    '<span class="numberhead">' +
                                        '<span class="label">до</span>' +
                                        '<input type="text" class="numberinput ofpower" >' +
	                                    '<span class="numbtnbase">' +
		                                    '<button class="numberbtn btnup" >&#708;</button>' +
		                                    '<button class="numberbtn btndown" >&#709;</button>' +
	                                    '</span>' +
                                    '</span>' +
                                '<div class="newrow"></div>' +
                            '</div>' +
//From/Of vvv
                            '<div class="find_vvvs">' +
                                    '<span class="numberhead">' +
                                        '<span class="label">от</span>' +
                                        '<input type="text" class="numberinput fromvvv" >' +
	                                    '<span class="numbtnbase">' +
		                                    '<button class="numberbtn btnup" >&#708;</button>' +
		                                    '<button class="numberbtn btndown" >&#709;</button>' +
	                                    '</span>' +
                                    '</span>' +
                                    '<span class="numberhead">' +
                                        '<span class="label">до</span>' +
                                        '<input type="text" class="numberinput ofvvv" >' +
	                                    '<span class="numbtnbase">' +
		                                    '<button class="numberbtn btnup" >&#708;</button>' +
		                                    '<button class="numberbtn btndown" >&#709;</button>' +
	                                    '</span>' +
                                    '</span>' +
                                '<div class="newrow"></div>' +
                            '</div>' +
//From/Of door
                            '<div class="find_doors">' +
                                    '<span class="numberhead">' +
                                        '<span class="label">от</span>' +
                                        '<input type="text" class="numberinput fromdoor" >' +
	                                    '<span class="numbtnbase">' +
		                                    '<button class="numberbtn btnup" >&#708;</button>' +
		                                    '<button class="numberbtn btndown" >&#709;</button>' +
	                                    '</span>' +
                                    '</span>' +
                                    '<span class="numberhead">' +
                                        '<span class="label">до</span>' +
                                        '<input type="text" class="numberinput ofdoor" >' +
	                                    '<span class="numbtnbase">' +
		                                    '<button class="numberbtn btnup" >&#708;</button>' +
		                                    '<button class="numberbtn btndown" >&#709;</button>' +
	                                    '</span>' +
                                    '</span>' +
                                '<div class="newrow"></div>' +
                            '</div>' +
//From/Of seat
                            '<div class="find_seats">' +
                                    '<span class="numberhead">' +
                                        '<span class="label">от</span>' +
                                        '<input type="text" class="numberinput fromseat" >' +
	                                    '<span class="numbtnbase">' +
		                                    '<button class="numberbtn btnup" >&#708;</button>' +
		                                    '<button class="numberbtn btndown" >&#709;</button>' +
	                                    '</span>' +
                                    '</span>' +
                                    '<span class="numberhead">' +
                                        '<span class="label">до</span>' +
                                        '<input type="text" class="numberinput ofseat" >' +
	                                    '<span class="numbtnbase">' +
		                                    '<button class="numberbtn btnup" >&#708;</button>' +
		                                    '<button class="numberbtn btndown" >&#709;</button>' +
	                                    '</span>' +
                                    '</span>' +
                                '<div class="newrow"></div>' +
                            '</div>' +
                            '<div class="table find_fuels" style="margin-right: 40px">' +
				                '<h5>Топливо</h5>' +
//Fuel List
				            '</div>' +
                            '<div class="table find_kpps">' +
				                '<h5>КПП</h5>' +
//Kpp List
				            '</div>' +
                        '</div>' +
                        '<div class="table" >' +
                            '<div class="find_drives">' +
				                '<h5>Привод</h5>' +
//Drives List
				            '</div>' +
                        '</div>' +
                        '<div class="table" style="width: 13; margin-left: 1%" >' +
                            '<div class="find_citys">' +
				                '<h5>Город</h5>' +
//City List
                                
				            '</div>' +
                        '</div>' +

                        '<div class="newrow"></div>' +
				        '<div id="expskins_module"></div>' +
					    '<div class="expautospro_clear"></div>' +
                        '<!--table will be here-->' +
				        '<div id = "expautospro" style="text-align:center">' +
					        '<table class="explist">' +
						        '<thead>' +
							        '<tr style="height: 50px; line-height: 1em;">' +
								        '<th>Изображение</th>' +
								        '<th>Марка/Модель</th>' +
								        '<th>Год</th>' +
                                        '<th>Объём л.</th>' +
                                        '<th>Топливо</th>' +
                                        '<th>Привод</th>' +
                                        '<th>КПП</th>' +
                                        '<th>Мощность л.с.</th>' +
								        '<th>Цена т.р.</th>' +
							        '</tr>' +
						        '</thead>' +
						        '<tbody id = "Table">' +
				                '</tbody>' +
				            '</table>' +
				        '</div>' +
					    '<!--table will be here-->      ' +
                    '</div>' +
                    '<div class="newrow"></div>' +
                 '</div><!-- Content Main -->' +
                '<!-- Bottom -->' +
                '<div id="bottom" class="clearfix">' +
                    '<div class="wrapper">' +
                        '<div id="car-logos">' +
                            '<div class="custom"  >' +
                                '<img src="images/car_logos.jpg" border="0" alt="Car Logos" width="960" height="74" />' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div><!-- Bottom -->' +
        '</div>'
        );
    $j(document).ready(function () {
        jQuery(function ($j) {
            $j('.numberhead .fromprice').mask('?999999');
            $j('.numberhead .ofprice').mask('?999999');

            $j('.numberhead .fromyear').mask('9999');
            $j('.numberhead .ofyear').mask('9999');

            $j('.numberhead .frompower').mask('?999');
            $j('.numberhead .ofpower').mask('?999');

            $j('.numberhead .fromdoor').mask('9');
            $j('.numberhead .ofdoor').mask('9');

            $j('.numberhead .fromseat').mask('9?9');
            $j('.numberhead .ofseat').mask('9?9');
        });
        //$j('.numberinput').change(function () { console.log($j(this).val()); });

		numcountInt= function(e) {
			tmp= $j(e.target).parents('.numberhead').find('input');
			num= parseInt(tmp.val());
			tmp.val((num + e.data.delta >= 0) ? (num + e.data.delta) : 0);
		    //console.log(num+10);
			dosearch();
		}
		numcountFloat= function(e) {
			tmp= $j(e.target).parents('.numberhead').find('input');
			num= parseFloat(tmp.val().replace(',','.'));
			tmp.val((num + e.data.delta >= 0) ? (num + e.data.delta).toFixed(1) : 0);
		    //console.log(num+10);
			dosearch();
		}
        $j('.numbtnbase .btnup').not('.find_vvvs .btnup').on('mousedown', {delta: 1}, numcountInt);
		$j('.find_vvvs .btnup').on('click', {delta: 0.1}, numcountFloat);
		
        $j('.numbtnbase .btndown').not('.find_vvvs .btndown').on('click', {delta: -1}, numcountInt);
        $j('.find_vvvs .btndown').on('click', { delta: -0.1 }, numcountFloat);

		var dt = {
		    fuel: [],
		    kpp: [],
		    drive: [],
            city: []
		};

		jgetBrand(function (dmark) {
            for (i = 0; i < dmark.length; i++)
                $j('.find_marks').append('<div><input type="checkbox" class="findmark" value="' + i + '"><span>' + dmark[i].mark + '</span></div>');
        });
        jgetType(function (dtype) {
            for (i = 0; i < dtype.length; i++)
                $j('.find_types').append('<div><input type="checkbox" class="findtype" value="' + i + '"><span>' + dtype[i].type + '</span></div>');
        });
        jgetData(function (data) {
            for (i = 0; i < data.length; i++) {
                tmp = data[i].fuel;
                if (dt.fuel.indexOf(tmp) == -1) {
                    dt.fuel.push(tmp);
                    $j('.find_fuels').append('<div><input type="checkbox" class="findfuel" value="' + tmp + '"><span>' + tmp + '</span></div>');
                }
                tmp = data[i].kpp;
                if (dt.kpp.indexOf(tmp) == -1) {
                    dt.kpp.push(tmp);
                    $j('.find_kpps').append('<div><input type="checkbox" class="findkpp" value="' + tmp + '"><span>' + tmp + '</span></div>');
                }
                tmp = data[i].drive.toLowerCase();
                if (dt.drive.indexOf(tmp) == -1) {
                    dt.drive.push(tmp);
                    $j('.find_drives').append('<div><input type="checkbox" class="finddrive" value="' + tmp + '"><span>' + tmp + '</span></div>');
                }
                tmp = data[i].city.split(' ');
                if (dt.kpp.indexOf(tmp[0]) == -1) {
                    dt.kpp.push(tmp[0]);
                    $j('.find_citys').append('<div><input type="checkbox" class="findcity" value="' + tmp[0] + '"><span  title="' + data[i].city + '">' + tmp[0] + '</span></div>');
                }
            }
            //dosearch();
            $j('.numberhead .numberinput').change(function () { dosearch(); });
            $j('.find_marks input').change(function () { dosearch(); });
            $j('.find_types input').change(function () { dosearch(); });
            $j('.find_fuels input').change(function () { dosearch(); });
            $j('.find_kpps input').change(function () { dosearch(); });
            $j('.find_drives input').change(function () { dosearch(); });
            $j('.find_citys input').change(function () { dosearch(); });
            $j('.findprice').change(function () { dosearch(); });
        });
    });
}
function findclear() {
    $j(".navcur tbody#Table").empty();
    $j('.numberhead .numberinput').val('');
    $j('.find_marks input').prop('checked', false);
    $j('.find_types input').prop('checked', false);
    $j('.find_fuels input').prop('checked', false);
    $j('.find_kpps input').prop('checked', false);
    $j('.find_drives input').prop('checked', false);
    $j('.find_citys input').prop('checked', false);
    $j('.findprice').eq(0).prop('checked', true);
}
function dosearch() {
    setTimeout(function waitloop() {
        if ((typeof slock) == 'undefined') return;
        if (!slock) {
            slock = true;
            
            chdt = undefined;
            chdt = {
                mark: $j('.findmark:checked').getVal(),
                type: $j('.findtype:checked').getVal(),
                drive: $j('.finddrive:checked').getVal(),
                kpp: $j('.findkpp:checked').getVal(),
                fuel: $j('.findfuel:checked').getVal(),
                city: $j('.findcity:checked').getVal(),
                //From/Of
                price: (parseInt($j('.findprice:checked').val())) ? $j('.find_prices .numberinput').getVal() : 0,
                year: $j('.find_years .numberinput').getVal(),
                power: $j('.find_powers .numberinput').getVal(),
                vvv: $j('.find_vvvs .numberinput').getVal(),
                door: $j('.find_doors .numberinput').getVal(),
                seat: $j('.find_seats .numberinput').getVal()
            }
            console.log(chdt);

            if (!chdt.mark.length && !chdt.type.length && !chdt.drive.length && !chdt.kpp.length && !chdt.fuel.length && !chdt.city.length
                && ((typeof chdt.price) == "object" && !chdt.price[0] && !chdt.price[1])
                && !chdt.year[0] && !chdt.year[1] && !chdt.power[0] && !chdt.power[1]
                && !chdt.vvv[0] && !chdt.vvv[1] && !chdt.door[0] && !chdt.door[1]
                && !chdt.seat[0] && !chdt.seat[1]) {
                pageLoaded = false;
                $j(".navcur tbody#Table").empty();
                $j(".navcur tbody#Table").append('<tr class="explistrow0">' +
                        '<td width="100%">Инфoрмация не найдена в базе данных. Пожалуйста попробуйте другой запрос.</td></tr>');
                pageLoaded = true;
                updateTableCompareFix();
                slock = false;
                return;
            }

            jgetData(function (data) {
                outid = [];

                for (i = 0; i < data.length; i++) {

                    if (chdt.mark.length) 
                        if (chdt.mark.indexOf(data[i].mark) == -1)
                            continue;
                    if (chdt.type.length) 
                        if (chdt.type.indexOf(data[i].type) == -1)
                            continue;

                    if (chdt.fuel.length)
                        if (chdt.fuel.indexOf(data[i].fuel) == -1)
                            continue;
                    if (chdt.kpp.length)
                        if (chdt.kpp.indexOf(data[i].kpp) == -1)
                            continue;
                    if (chdt.drive.length)
                        if (chdt.drive.indexOf(data[i].drive) == -1)
                            continue;
                    if (chdt.city.length)
                        if (chdt.city.indexOf(data[i].city.split(' ')[0]) == -1)
                            continue;

                    if (chdt.price == 0) {
                        if (data[i].price.toLowerCase().match(/([договорp]+)|([аask]+)/) == null)
                            continue;
                    }
                    else if (chdt.price[0] || chdt.price[1]) {
                        min = (chdt.price[0]) ? parseInt(chdt.price[0]) : 0;
                        max = (chdt.price[1]) ? parseInt(chdt.price[1]) : 999999;
                        if (min > max) continue;
                        tmp = (data[i].price) ? parseInt(data[i].price) : 0;
                        if (isNaN(tmp)) continue; 
                        if (min < max) 
                            if (tmp < min || tmp > max) continue;
                        if (min == max) 
                            if (tmp != max) continue;
                    }
                    if (chdt.year[0] || chdt.year[1]) {
                        min = (chdt.year[0]) ? parseInt(chdt.year[0]) : 0;
                        max = (chdt.year[1]) ? parseInt(chdt.year[1]) : 3000;
                        if (min > max) continue;
                        if (min < max) {
                            tmp = parseInt(data[i].year);
                            if (isNaN(tmp)) {
                                if (parseInt(data[i].year.split('-')[0]) > max) continue;
                            }
                            else {
                                if (!data[i].year) tmp = 0;
                                if (tmp < min || tmp > max) continue;
                            }
                        }
                        if (min == max) {
                            tmp = (data[i].year) ? parseInt(data[i].year) : 0;
                            if (isNaN(tmp)) {
                                if (parseInt(data[i].year.split('-')[0]) > max) continue;
                            }
                            else {
                                if (!data[i].year) tmp = 0;
                                if (tmp != max) continue;
                            }
                        }
                    }
                    if (chdt.vvv[0] || chdt.vvv[1]) {
                        min = (chdt.vvv[0]) ? parseFloat(chdt.vvv[0]) : 0;
                        if (min < 0) min = 0;
                        max = (chdt.vvv[1]) ? parseFloat(chdt.vvv[1]) : 300;
                        if (min > max) continue;
                        tmp = (data[i].vvv) ? parseFloat(data[i].vvv.replace(/,/,".")) : 0;
                        if (isNaN(tmp)) continue;
                        if (min < max) 
                            if (tmp < min || tmp > max) continue; 
                        if (min == max) 
                            if (tmp != max) continue;
                    }
                    if (chdt.power[0] || chdt.power[1]) {
                        min = (chdt.power[0]) ? parseInt(chdt.power[0]) : 0;
                        max = (chdt.power[1]) ? parseInt(chdt.power[1]) : 1000;
                        if (min > max) continue;
                        tmp = (data[i].power) ? parseInt(data[i].power) : 0;
                        if (isNaN(tmp)) continue;
                        if (min < max) 
                            if (tmp < min || tmp > max) continue; 
                        if (min == max) 
                            if (tmp != max) continue;
                    }
                    if (chdt.door[0] || chdt.door[1]) {
                        min = (chdt.door[0]) ? parseInt(chdt.door[0]) : 0;
                        max = (chdt.door[1]) ? parseInt(chdt.door[1]) : 100;
                        if (min > max) continue;
                        tmp = (data[i].door) ? parseInt(data[i].door) : 0;
                        if (isNaN(tmp)) continue;
                        if (min < max) 
                            if (tmp < min || tmp > max) continue;
                        if (min == max) 
                            if (tmp != max) continue;
                        
                    }
                    if (chdt.seat[0] || chdt.seat[1]) {
                        min = (chdt.seat[0]) ? parseInt(chdt.seat[0]) : 0;
                        max = (chdt.seat[1]) ? parseInt(chdt.seat[1]) : 100;
                        if (min > max) continue;
                        tmp = (data[i].seat) ? parseInt(data[i].seat) : 0;
                        if (isNaN(tmp)) continue;
                        if (min < max) 
                            if (tmp < min || tmp > max) continue;
                        if (min == max) 
                            if (tmp != max) continue;
                    }
                    outid.push(i);
                }
                console.log(outid);

                pageLoaded = false;
                $j(".navcur tbody#Table").empty();

                if (outid.length)
                    jgetType(function (cartype) {
                        jgetBrand(function (carmark) {
                            for (i = 0; i < outid.length; i++) {
                                tmpmark = data[outid[i]].mark;
                                tmptype = data[outid[i]].type;
                                insertRow(outid[i], data[outid[i]], cartype[tmptype].type, carmark[tmpmark].mark);
                            }
                            pageLoaded = true;
                            updateTableCompareFix();
                            slock = false;
                        });
                    });
                else {
                    //$j(".navcur tbody#Table").empty();
                    $j(".navcur tbody#Table").append('<tr class="explistrow0">' +
                        '<td width="100%">Инфoрмация не найдена в базе данных. Пожалуйста попробуйте другой запрос.</td></tr>');
                    pageLoaded = true;
                    updateTableCompareFix();
                    slock = false;
                }
            });

            //setTimeout(waitloop, 2000);
        } else {
            setTimeout(waitloop, 2000);
        }
    }, 0);
}

/*
name
type
mark
model
price
color
year
power
vvv
fuel
kpp
drive
door
seat
city
img
complect
*/