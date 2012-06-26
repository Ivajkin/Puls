
/****************************************************************************************\
 **   @name		EXP Autos  2.0                                                  **
 **   @package          Joomla 1.6                                                      **
 **   @author		EXP TEAM::Alexey Kurguz (Grusha)                                **
 **   @copyright	Copyright (C) 2005 - 2011  EXP TEAM::Alexey Kurguz (Grusha)     **
 **   @link             http://www.feellove.eu                                          **
 **   @license		Commercial License                                              **
 \****************************************************************************************/


    var geocoder;
    var map;
    var infowindow = new google.maps.InfoWindow();
    var markers = [];

    function initialize() {
    geocoder = new google.maps.Geocoder();
    var myLatlng = new google.maps.LatLng(explat, explong);
    var myOptions = {
      zoom: expzoom,
      center: myLatlng,
      mapTypeId: expmapTypeId
    }
    map = new google.maps.Map(document.getElementById(exp_map_canvas), myOptions);
    if(explat && explong){
        placeMarker(myLatlng);
    }
    google.maps.event.addListener(map, "click", function(event) {
        if(expclick == 1){
            clearMarkers();
            placeMarker(event.latLng);
            if(typeof latid != 'undefined' && typeof longid != 'undefined'){
                document.getElementById(latid).value = event.latLng.lat();
                document.getElementById(longid).value = event.latLng.lng();
            }
        }
    });
    
    
  }
    function placeMarker(location) {
      //alert(location);
      var marker = new google.maps.Marker({
          position: location,
          map: map
      });
      codeLatLng(location,marker);
      markers.push(marker);
      map.setCenter(location);
    } 
    
    function clearMarkers(){
      for(var i=0; i<markers.length; i++){
        markers[i].setMap(null);
      }
      markers.length = 0;

    };
    
    function codeStreet(selTag,expzoom) {
        var val = selTag;
        if(expzoom){
            expzoombystreet = expzoom;
        }
        searchByAdress(val,expzoombystreet);
    }
    function findAddress(selTag) {
        var val = selTag.options[selTag.selectedIndex].text;
        searchByAdress(val,expzoombycst);
    }
    function findLangLong() {
        var langval = document.getElementById(latid).value;
        var longval = document.getElementById(longid).value;
        var myLatlngn = new google.maps.LatLng(langval, longval);
            clearMarkers();
            placeMarker(myLatlngn);
            map.setCenter(myLatlngn);
            map.setZoom(expzoombystreet);
    }
    
    function searchByAdress(vartext,zoomvalexp){
        var address = vartext;
        geocoder.geocode( { 'address': address}, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
            clearMarkers();
            placeMarker(results[0].geometry.location);
            if(typeof latid != 'undefined' && typeof longid != 'undefined'){
                document.getElementById(latid).value = results[0].geometry.location.lat();
                document.getElementById(longid).value = results[0].geometry.location.lng();
            }
            map.setCenter(results[0].geometry.location);
            map.setZoom(zoomvalexp);
          } else {
            alert(expalert + status);
          }
        });
    }
    function codeLatLng(explatlng,marker) {
    geocoder.geocode({'latLng': explatlng}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        if (results[1]) {          
          infowindow.setContent(results[1].formatted_address);
              infowindow.open(map,marker);
            google.maps.event.addListener(marker, 'click', function() {
              infowindow.open(map,marker);
            });
        } else {
          alert("No results found");
        }
      } else {
        alert("Geocoder failed due to: " + status);
      }
    });
  }

  google.maps.event.addDomListener(window, 'load', initialize);