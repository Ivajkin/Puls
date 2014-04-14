var myMap;
var content = [
    '<h4>локация 1</h4>',
    '<ul>',
    '<li>Охотское море</li>',
    '<li>мыс Кумыс</li>',
    '</ul>',
    '<div>',
    '<a href="javascript:void(0)"><img src="/sites/all/themes/omega_d1/images/cd_same.png" style="max-width:100px" alt="Фотогаллерея" title="Фотогаллерея"></a>',
    '<a href="javascript:void(0)"><img src="/sites/all/themes/omega_d1/images/cd_same.png" style="max-width:100px" alt="Диски" title="Диски"></a>',
    '<a href="javascript:void(0)"><img src="/sites/all/themes/omega_d1/images/cd_same.png" style="max-width:100px" alt="Маршруты" title="Маршруты"></a>',
    '</div>'
].join('');

function buildlist(where, setx) {
    tmp= '';
    for (var j in setx)
        tmp+= '<li><a href="/'+ where +'/'+ (setx[j].disp_title+'/'+setx[j].title).toLowerCase().replace(' ', '-') +'"> '+
                setx[j].img +
                '<div class="field--prodtitle"><h4>'+ setx[j].title +'</h4></div> ' +
            '</a></li> ';
    return tmp;
}
function init () {
    sets= Drupal.settings.catalog;

    // Создание экземпляра карты и его привязка к контейнеру с
    // заданным id ("map").
    myMap = new ymaps.Map('YMapsID', {
        // При инициализации карты обязательно нужно указать
        // её центр и коэффициент масштабирования.
        center:[62.76, 124.64], // Москва
        zoom:4,
        type: 'yandex#hybrid'
        /*behaviors: ['default', 'scrollZoom', 'multiTouch', 'rightMouseButtonMagnifier'],*/
    });
    myMap.options.set('scrollZoomSpeed', 0.5);

    // Создаем коллекцию геообъектов и задаем опции.
    var myGeoObjects = new ymaps.GeoObjectCollection({}, {
        preset: "islands#redCircleIcon",
        strokeWidth: 4,
        geodesic: true
    });
    for(var i in sets) {
        //Create Content
        content= '<h3>Диски<h3> ' +
                    '<ul> ' +
                            buildlist('catalog' ,sets[i].product) +
                    '</ul> ' +
                 '<h3>Маршруты</h3> ' +
                    '<ul> ' +
                            buildlist('routes' ,sets[i].route) +
                    '</ul> ';

        // Добавляем в коллекцию метки и линию.
        myGeoObjects.add(new ymaps.Placemark(sets[i].place, {
                balloonContentBody: content,
                iconContent: sets[i].title
            }, {
                preset: 'islands#darkOrangeStretchyIcon'
            })
        );
        //myMap.geoObjects.add(placemark[parseInt(i)-1]);
    }

// Добавляем коллекцию на карту.
    myMap.geoObjects.add(myGeoObjects);
// Устанавливаем карте центр и масштаб так, чтобы охватить коллекцию целиком.
    myMap.setBounds(myGeoObjects.getBounds());
    //Latitude Correction Fix
    tmp= myMap.getCenter();
    myMap.setCenter([tmp[0], tmp[1]-12]);
}

(function ($) {

    Drupal.theme.prototype.omegaD1map_size= function () {
        tmp= $(document).height() -  $('html').height();
        $('#YMapsID').height(
            (tmp < 500)? 500: tmp
                +'px');
        return;
    }

    /**
     * Yandex Map Catalog behaviors
     * @type {{attach: attach}}
     */
    Drupal.behaviors.omegaD1Catalog = {
        attach: function(c, s) {
            $('.view-content', c).once('ymap', function(){
                Drupal.theme('omegaD1map_size');
                // Дождёмся загрузки API и готовности DOM.
                ymaps.ready(init);

                $('body').animate({scrollTop: $('#YMapsID').position().top},1000);

                $(window).resize(function(){
                    //map_size();
                    myMap.container.fitToViewport();
                });
            });
        }
    }
})(jQuery);