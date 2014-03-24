var myMap;

map_size();

// Дождёмся загрузки API и готовности DOM.
ymaps.ready(init);

function init () {
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

    var myPlacemark = new ymaps.Placemark(myMap.getCenter(), {
        balloonContentBody: [
            '<h4>локация 1</h4>',
            '<ul>',
            '<li>Охотское море</li>',
            '<li>мыс Кумыс</li>',
            '</ul>',
            '<div>',
            '<a href="javascript:void(0)"><img src="img/cd_same.png" alt="Фотогаллерея" title="Фотогаллерея"></a>',
            '<a href="javascript:void(0)"><img src="img/Cd_same.png" alt="Диски" title="Диски"></a>',
            '<a href="javascript:void(0)"><img src="img/cd_same.png" alt="Маршруты" title="Маршруты"></a>',
            '</div>'
        ].join('')
    }, {
        preset: 'islands#darkOrangeDotIcon'
    });

    myMap.geoObjects.add(myPlacemark);

}

function map_size() {
    $('.catalog #YMapsID').height(
        ( $(document).height() -  $('html').height() )
            +'px');
}

$(window).resize(function(){
    //map_size();
    myMap.container.fitToViewport();
});