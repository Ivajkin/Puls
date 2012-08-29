/******Page function*****/ //ymaps-map ymaps-i-ua_js_yes

aboutFunc = function () {
        ymaps.ready(function () {
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
            /*$j('#ymapid > ymaps').css('height', 'inherit')
                .css('width', 'auto');*/
                
        });

    twitshow('abouttwit');
}


/***********List Generation Function***********/

function insertRow(index, data, cartype, carmark) {
    var image = '';
    if (data.img.length) {
        image = '<div class="photo">' +
                        '<a href="javascript:void(0)" onclick="showInfo(' + index + ', \'' + data.name + '\')">' +
                            '<span></span>' +
                                '<img width="100px" height="75px" src="' + data.img[0] + '" alt="' + data.name + '" />' + //width="auto" height="100px"
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
            '<td width="200px">' + '&nbsp' + '<div class="expautos_list_markmod"> <a href="javascript:void(0)" onclick="showInfo(' + index + ', \'' + data.name + '\')">' + data.name + '&nbsp' + '</a></div>' +
                '<div>' + carmark + '&nbsp' + data.model + '</div><div>' + cartype + '</div></td>' +
            '<td>' + '&nbsp' + data.year + '&nbsp' + '</td>' +
            '<td>' + '&nbsp' + data.vvv + '&nbspл.' + '</td>' +
            '<td>' + '&nbsp' + data.fuel + '&nbsp' + '</td>' +
            '<td>' + '&nbsp' + data.drive + '&nbsp' + '</td>' +
            '<td>' + '&nbsp' + data.kpp + '&nbsp' + '</td>' +
            '<td>' + '&nbsp' + data.power + '&nbsp' + '</td>' +
            '<td>' + '&nbsp' + data.price + '</td>' +
            '</tr>');
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
                        });
                    });
                }
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
                        });
                    });
                }
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
                    });
                }
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
                    '<div class="table" style="width: 8%; visibility:hidden">.</div>' +
                    '<div class="table" style="width: 85%;">' +
                        '<h2 class="h2list">Модели</h2>' +
                        '<div class="table" style="width: 20%; font-size: 1.2em">' +
                            '<span>Фильтровать по</span>' +
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
							        '<tr style="height: 50px">' +
								        '<th>Изображение</th>' +
								        '<th>Марка/Модель</th>' +
								        '<th>Год</th>' +
                                        '<th>Объём</th>' +
                                        '<th>Топливо</th>' +
                                        '<th>Привод</th>' +
                                        '<th>КПП</th>' +
                                        '<th>Мощность</th>' +
								        '<th>Цена</th>' +
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
                                                                '<img class="hoverBox" src="' + car.img[0] + '" alt="(' + carmark.mark + ') ' + car.name + '" title="(' + carmark.mark + ') ' + car.name + '" style="height: auto; width: 100%; margin-bottom: 20px" />' +
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

            tmpdata += '<p>' + car.price + '</p>' + '<p>' + carmark.mark + '</p>' + '<p>' + car.model + '</p>';
            if (car.year) { tmphead += '<p><span>Год: </span></p>'; tmpdata += '<p>' + car.year + '</p>'; }
            if (car.vvv) { tmphead += '<p><span>Объём: </span></p>'; tmpdata += '<p>' + car.vvv + '</p>'; }
            if (car.power) { tmphead += '<p><span>Мощность: </span></p>'; tmpdata += '<p>' + car.power + '</p>'; }
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
                                '<img class="hoverBox" src="' + car.img[i] + '" alt="(' + carmark.mark + ') ' + car.name + '" title="(' + carmark.mark + ') ' + car.name + '" />' +
                            '</a>' +
                        '</div>';
            $j(head + ' .hoverBox466').append(tmp);
            $j(head + ' .hoverBox466 div:nth-child(3n)').addClass('hoverBox_right');
            $j(head + ' .hoverBox466 > div').filter(function (index) { return index % 3 - 2 }).addClass('hoverBox_left');
            tmp = "";

            while (botmimgmod > 1) {
                tmp += '<div class="hoverBoxGallery466 hoverBox_bottom_left">' +
                            '<a href="' + car.img[botmimgdiv + botmimgmod - 1] + '" rel="prettyPhoto466[gallery]" title="(' + carmark.mark + ') ' + car.name + '" >' +
                                '<img class="hoverBox" src="' + car.img[botmimgdiv + botmimgmod - 1] + '" alt="(' + carmark.mark + ') ' + car.name + '" title="(' + carmark.mark + ') ' + car.name + '" />' +
                            '</a>' +
                        '</div>';
                botmimgmod--;
            }
            tmp += '<div class="hoverBoxGallery466 hoverBox_bottom_right">' +
                            '<a href="' + car.img[botmimgdiv] + '" rel="prettyPhoto466[gallery]" title="(' + carmark.mark + ') ' + car.name + '" >' +
                                '<img class="hoverBox" src="' + car.img[botmimgdiv] + '" alt="(' + carmark.mark + ') ' + car.name + '" title="(' + carmark.mark + ') ' + car.name + '" />' +
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
        });
    });
}