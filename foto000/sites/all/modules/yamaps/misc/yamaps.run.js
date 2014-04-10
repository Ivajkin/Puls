/**
 * @file
 * Run map.
 */

(function ($) {
  ymaps.ready(function () {
    var processMaps = function () {
      if (Drupal.settings.yamaps) {

        //activeMaps = [];
        for (var mapId in Drupal.settings.yamaps) {
          var options = Drupal.settings.yamaps[mapId];
          if (options.display_options.display_type === 'map_button') {
            $('#' + mapId).hide();
            $('#' + options.display_options.remove_button_id).hide();
            $('#' + options.display_options.open_button_id).bind({
              click: function () {

                mapId = $(this).attr('mapId');
                options = Drupal.settings.yamaps[mapId];
                creating_map(mapId, options);
                $('#' + options.display_options.open_button_id).hide('slow');
                $('#' + mapId).show();
                $('#' + options.display_options.remove_button_id).show();
              }
            });
          }
          else {
            creating_map(mapId, options);
          }
        }
      }
    };

    // Initialize layouts.
    $.yaMaps.initLayouts();
    processMaps();

    Drupal.behaviors.yamapsInitBehaviors = {
      attach: processMaps
    };
  });

  function creating_map(mapId, options) {
    $('#' + mapId).once('yamaps', function () {
      // If zoom and center are not set - set it from user's location.

      if (!options.init.center || !options.init.zoom) {
        var location = ymaps.geolocation;
        // Set map center.
        if (!options.init.center) {
          // Set location, defined by ip, if they not defined.
          options.init.center = [location.latitude, location.longitude];
        }
        if (!options.init.zoom) {
          options.init.zoom = location.zoom ? location.zoom : 10;
        }
      }

      // Create new map.
      var map = new $.yaMaps.YamapsMap(mapId, options);
      if (options.controls) {
        // Enable controls
        map.enableControls();
      }
      if (options.traffic) {
        // Enable traffic.
        map.enableTraffic();
      }
      // Enable plugins.
      map.enableTools();

      //activeMaps[mapId] = map;
    });
  }
})(jQuery);
