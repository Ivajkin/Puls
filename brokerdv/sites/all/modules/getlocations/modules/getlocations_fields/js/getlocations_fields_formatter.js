
/**
 * @file
 * @author Bob Hutchinson http://drupal.org/user/52366
 * @copyright GNU GPL
 *
 * Javascript functions for getlocations_fields module admin
 * jquery gee whizzery
*/
(function ($) {
  Drupal.behaviors.getlocations_fields_formatter = {
    attach: function() {

      if ($("#edit-fields-field-location-settings-edit-form-settings-trafficinfo, #edit-style-options-trafficinfo").is('input')) {
        if ($("#edit-fields-field-location-settings-edit-form-settings-trafficinfo, #edit-style-options-trafficinfo").attr('checked')) {
          $("#wrap-getlocations-trafficinfo").show();
        }
        else {
          $("#wrap-getlocations-trafficinfo").hide();
        }
        $("#edit-fields-field-location-settings-edit-form-settings-trafficinfo, #edit-style-options-trafficinfo").change(function() {
          if ($(this).attr('checked')) {
            $("#wrap-getlocations-trafficinfo").show();
          }
          else {
            $("#wrap-getlocations-trafficinfo").hide();
          }
        });
      }

      if ($("#edit-fields-field-location-settings-edit-form-settings-bicycleinfo, #edit-style-options-bicycleinfo").is('input')) {
        if ($("#edit-fields-field-location-settings-edit-form-settings-bicycleinfo, #edit-style-options-bicycleinfo").attr('checked')) {
          $("#wrap-getlocations-bicycleinfo").show();
        }
        else {
          $("#wrap-getlocations-bicycleinfo").hide();
        }
        $("#edit-fields-field-location-settings-edit-form-settings-bicycleinfo, #edit-style-options-bicycleinfo").change(function() {
          if ($(this).attr('checked')) {
            $("#wrap-getlocations-bicycleinfo").show();
          }
          else {
            $("#wrap-getlocations-bicycleinfo").hide();
          }
        });
      }

      if ($("#edit-fields-field-location-settings-edit-form-settings-transitinfo, #edit-style-options-transitinfo").is('input')) {
        if ($("#edit-fields-field-location-settings-edit-form-settings-transitinfo, #edit-style-options-transitinfo").attr('checked')) {
          $("#wrap-getlocations-transitinfo").show();
        }
        else {
          $("#wrap-getlocations-transitinfo").hide();
        }
        $("#edit-fields-field-location-settings-edit-form-settings-transitinfo, #edit-style-options-transitinfo").change(function() {
          if ($(this).attr('checked')) {
            $("#wrap-getlocations-transitinfo").show();
          }
          else {
            $("#wrap-getlocations-transitinfo").hide();
          }
        });
      }

      if ($("#edit-fields-field-location-settings-edit-form-settings-panoramio-show, #edit-style-options-panoramio-show").is('input')) {
        if ($("#edit-fields-field-location-settings-edit-form-settings-panoramio-show, #edit-style-options-panoramio-show").attr('checked')) {
          $("#wrap-getlocations-panoramio").show();
        }
        else {
          $("#wrap-getlocations-panoramio").hide();
        }
        $("#edit-fields-field-location-settings-edit-form-settings-panoramio-show, #edit-style-options-panoramio-show").change(function() {
          if ($(this).attr('checked')) {
            $("#wrap-getlocations-panoramio").show();
          }
          else {
            $("#wrap-getlocations-panoramio").hide();
          }
        });
      }

      if ($("#edit-fields-field-location-settings-edit-form-settings-weather-show, #edit-style-options-weather-show").is('input')) {
        if ($("#edit-fields-field-location-settings-edit-form-settings-weather-show, #edit-style-options-weather-show").attr('checked')) {
          $("#wrap-getlocations-weather").show();
        }
        else {
          $("#wrap-getlocations-weather").hide();
        }
        $("#edit-fields-field-location-settings-edit-form-settings-weather-show, #edit-style-options-weather-show").change(function() {
          if ($(this).attr('checked')) {
            $("#wrap-getlocations-weather").show();
          }
          else {
            $("#wrap-getlocations-weather").hide();
          }
        });

        if ($("#edit-fields-field-location-settings-edit-form-settings-weather-cloud, #edit-style-options-weather-cloud").attr('checked')) {
          $("#wrap-getlocations-weather-cloud").show();
        }
        else {
          $("#wrap-getlocations-weather-cloud").hide();
        }
        $("#edit-fields-field-location-settings-edit-form-settings-weather-cloud, #edit-style-options-weather-cloud").change(function() {
          if ($(this).attr('checked')) {
            $("#wrap-getlocations-weather-cloud").show();
          }
          else {
            $("#wrap-getlocations-weather-cloud").hide();
          }
        });
      }

    }
  };
})(jQuery);

