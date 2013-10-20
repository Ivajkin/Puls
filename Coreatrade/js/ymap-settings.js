
function loadYandexMap(call) {

    function init(){     
        myMap = new ymaps.Map ("ymapid", {
            center: [48.533928, 135.051139],
            zoom: 15,
        });

        myPlacemark = new ymaps.Placemark([48.533928, 135.051139], { 
            content: 'ООО "Корея Трейд"', 
            balloonContent: 'Тихоокеанская 150' 
        });

        myMap.geoObjects.add(myPlacemark);
    }
 
    ymaps.ready(init);
    var myMap,
        myPlacemark;

    if (call) call();
}
