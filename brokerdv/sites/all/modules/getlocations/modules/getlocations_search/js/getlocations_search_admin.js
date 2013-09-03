/**
 * @file
 * @author Bob Hutchinson http://drupal.org/user/52366
 * @copyright GNU GPL
 *
 * Javascript functions for getlocations_search admin
*/
(function ($) {

  Drupal.behaviors.getlocations_search_admin = {
    attach: function() {

      if ($("#edit-getlocations-search-defaults-method, #edit-getlocations-search-block-defaults-method").val() == 'vocab') {
        $("#getlocations_search_defaults_vocab").show();
      }
      else {
        $("#getlocations_search_defaults_vocab").hide();
      }
      $("#edit-getlocations-search-defaults-method, #edit-getlocations-search-block-defaults-method").change( function() {
        if ($(this).val() == 'vocab') {
          $("#getlocations_search_defaults_vocab").show();
        }
        else {
          $("#getlocations_search_defaults_vocab").hide();
        }
      });

      if ($("#edit-getlocations-search-defaults-restrict-by-country, #edit-getlocations-search-block-defaults-restrict-by-country").is('input')) {
        if ($("#edit-getlocations-search-defaults-restrict-by-country, #edit-getlocations-search-block-defaults-restrict-by-country").attr('checked')) {
          $("#getlocations_search_country").show();
        }
        else {
          $("#getlocations_search_country").hide();
        }
        $("#edit-getlocations-search-defaults-restrict-by-country, #edit-getlocations-search-block-defaults-restrict-by-country").change( function() {
          if ($("#edit-getlocations-search-defaults-restrict-by-country, #edit-getlocations-search-block-defaults-restrict-by-country").attr('checked')) {
            $("#getlocations_search_country").show();
          }
          else {
            $("#getlocations_search_country").hide();
          }
        });
      }

      if ($("#edit-getlocations-search-defaults-markermanagertype, #edit-getlocations-search-block-defaults-markermanagertype").val() == 1) {
        // markermanager
        $(".form-item-getlocations-search-defaults-usemarkermanager, .form-item-getlocations-search-block-defaults-usemarkermanager").show();
        $("#wrap-getlocations-clusteropts").hide();
        $("#wrap-getlocations-markeropts").show();
      }
      else if ($("#edit-getlocations-search-defaults-markermanagertype, #edit-getlocations-search-block-defaults-markermanagertype").val() == 2) {
        // markerclusterer
        $(".form-item-getlocations-search-defaults-usemarkermanager, .form-item-getlocations-search-block-defaults-usemarkermanager").hide();
        $("#wrap-getlocations-clusteropts").show();
        $("#wrap-getlocations-markeropts").hide();
      }
      else {
        // none
        $(".form-item-getlocations-search-defaults-usemarkermanager, .form-item-getlocations-search-block-defaults-usemarkermanager").hide();
        $("#wrap-getlocations-clusteropts").hide();
        $("#wrap-getlocations-markeropts").hide();
      }
      $("#edit-getlocations-search-defaults-markermanagertype, #edit-getlocations-search-block-defaults-markermanagertype").change(function() {
        if ($(this).val() == 1) {
          // markermanager
          $(".form-item-getlocations-search-defaults-usemarkermanager, .form-item-getlocations-search-block-defaults-usemarkermanager").show();
          $("#wrap-getlocations-clusteropts").hide();
          $("#wrap-getlocations-markeropts").show();
        }
        else if ($(this).val() == 2) {
          // markerclusterer
          $(".form-item-getlocations-search-defaults-usemarkermanager, .form-item-getlocations-search-block-defaults-usemarkermanager").hide();
          $("#wrap-getlocations-clusteropts").show();
          $("#wrap-getlocations-markeropts").hide();
        }
        else {
          // none
          $(".form-item-getlocations-search-defaults-usemarkermanager, .form-item-getlocations-search-block-defaults-usemarkermanager").hide();
          $("#wrap-getlocations-clusteropts").hide();
          $("#wrap-getlocations-markeropts").hide();
        }
      });

      if ($("#edit-getlocations-search-defaults-trafficinfo").is('input')) {
        if ($("#edit-getlocations-search-defaults-trafficinfo").attr('checked')) {
          $("#wrap-getlocations-trafficinfo").show();
        }
        else {
          $("#wrap-getlocations-trafficinfo").hide();
        }
        $("#edit-getlocations-search-defaults-trafficinfo").change(function() {
          if ($(this).attr('checked')) {
            $("#wrap-getlocations-trafficinfo").show();
          }
          else {
            $("#wrap-getlocations-trafficinfo").hide();
          }
        });
      }

      if ($("#edit-getlocations-search-defaults-bicycleinfo").is('input')) {
        if ($("#edit-getlocations-search-defaults-bicycleinfo").attr('checked')) {
          $("#wrap-getlocations-bicycleinfo").show();
        }
        else {
          $("#wrap-getlocations-bicycleinfo").hide();
        }
        $("#edit-getlocations-search-defaults-bicycleinfo").change(function() {
          if ($(this).attr('checked')) {
            $("#wrap-getlocations-bicycleinfo").show();
          }
          else {
            $("#wrap-getlocations-bicycleinfo").hide();
          }
        });
      }

      if ($("#edit-getlocations-search-defaults-transitinfo").is('input')) {
        if ($("#edit-getlocations-search-defaults-transitinfo").attr('checked')) {
          $("#wrap-getlocations-transitinfo").show();
        }
        else {
          $("#wrap-getlocations-transitinfo").hide();
        }
        $("#edit-getlocations-search-defaults-transitinfo").change(function() {
          if ($(this).attr('checked')) {
            $("#wrap-getlocations-transitinfo").show();
          }
          else {
            $("#wrap-getlocations-transitinfo").hide();
          }
        });
      }

      if ($("#edit-getlocations-search-defaults-panoramio-show").is('input')) {
        if ($("#edit-getlocations-search-defaults-panoramio-show").attr('checked')) {
          $("#wrap-getlocations-panoramio").show();
        }
        else {
          $("#wrap-getlocations-panoramio").hide();
        }
        $("#edit-getlocations-search-defaults-panoramio-show").change(function() {
          if ($(this).attr('checked')) {
            $("#wrap-getlocations-panoramio").show();
          }
          else {
            $("#wrap-getlocations-panoramio").hide();
          }
        });
      }

      if ($("#edit-getlocations-search-defaults-weather-show").is('input')) {
        if ($("#edit-getlocations-search-defaults-weather-show").attr('checked')) {
          $("#wrap-getlocations-weather").show();
        }
        else {
          $("#wrap-getlocations-weather").hide();
        }
        $("#edit-getlocations-search-defaults-weather-show").change(function() {
          if ($(this).attr('checked')) {
            $("#wrap-getlocations-weather").show();
          }
          else {
            $("#wrap-getlocations-weather").hide();
          }
        });

        if ($("#edit-getlocations-search-defaults-weather-cloud").attr('checked')) {
          $("#wrap-getlocations-weather-cloud").show();
        }
        else {
          $("#wrap-getlocations-weather-cloud").hide();
        }
        $("#edit-getlocations-search-defaults-weather-cloud").change(function() {
          if ($(this).attr('checked')) {
            $("#wrap-getlocations-weather-cloud").show();
          }
          else {
            $("#wrap-getlocations-weather-cloud").hide();
          }
        });
      }

      // search marker
      if ($("#edit-getlocations-search-defaults-do-search-marker").is('input')) {
        if ($("#edit-getlocations-search-defaults-do-search-marker").attr('checked')) {
          $("#wrap-getlocations-search-marker").show();
        }
        else {
          $("#wrap-getlocations-search-marker").hide();
        }
        $("#edit-getlocations-search-defaults-do-search-marker").change(function() {
          if ($(this).attr('checked')) {
            $("#wrap-getlocations-search-marker").show();
          }
          else {
            $("#wrap-getlocations-search-marker").hide();
          }
        });
      }

    }
  };
})(jQuery);
