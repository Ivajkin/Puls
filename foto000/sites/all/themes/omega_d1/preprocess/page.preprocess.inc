<?php

/**
 * Implements hook_preprocess_page().
 */
function omega_d1_preprocess_page(&$variables) {
  // You can use preprocess hooks to modify the variables before they are passed
  // to the theme function or template file.

    //var_dump($_GET);
    $thp= drupal_get_path('theme', 'omega_d1');

    drupal_add_js("/".$thp."/js/vendor/jquery.transit.min.js");
    drupal_add_js("/".$thp."/js/omega-d1.behaviors.js");

    if ( arg(0) == 'fpp') {
        drupal_add_js("/sites/all/libraries/prettyPhoto/old/jquery.prettyPhoto.js");
        drupal_add_css("sites/all/libraries/prettyPhoto/old/prettyPhoto.css");
        drupal_add_js("/".$thp."/js/main.js");

        drupal_add_css('sites/all/libraries/bxslider/jquery.bxslider.css');
        drupal_add_js('/sites/all/libraries/bxslider/jquery.bxslider.min.js');
        drupal_add_js("/".$thp."/js/slcool.js");
    }
    if ( arg(0) == 'gallery' || arg(0) == 'albums' ) {
        //old
        drupal_add_js("/sites/all/libraries/prettyPhoto/old/jquery.prettyPhoto.js");
        drupal_add_css("sites/all/libraries/prettyPhoto/old/prettyPhoto.css");
        //drupal_add_js("/sites/all/libraries/prettyPhoto/js/jquery.prettyPhoto.js");
        //drupal_add_css("sites/all/libraries/prettyPhoto/css/jquery.prettyPhoto.css");

        drupal_add_js("/".$thp."/js/img.gallery.js");
        return;
    }
    if ( arg(0) == 'catalog' && arg(1) === null ) {
        drupal_add_js('//api-maps.yandex.ru/2.1-dev/?lang=ru-RU&load=package.full', 'external');
        drupal_add_js("/".$thp."/js/catalog.js");
        return;
    }
    if ( arg(0) == 'catalog' && arg(1) !== null ) {
        drupal_add_js("/sites/all/libraries/prettyPhoto/old/jquery.prettyPhoto.js");
        drupal_add_css("sites/all/libraries/prettyPhoto/old/prettyPhoto.css");
        drupal_add_js("/".$thp."/js/img.screenlist.js");
        drupal_add_js("/".$thp."/js/img.gallery.js");

        drupal_add_css('sites/all/libraries/bxslider/jquery.bxslider.css');
        drupal_add_js('/sites/all/libraries/bxslider/jquery.bxslider.min.js');
        drupal_add_js("/".$thp."/js/slcool.js");

        //Set Page Title (for SEO)
        //drupal_set_title(arg(2)); FAILED: has been set page title (body) insteed head
        //Use vars array for proper manipulation
        return;
    }

    //Alias specific for node
    $n_alias= drupal_lookup_path('alias', $_GET['q']);
    if ( preg_match('/^albums/', $n_alias) ) {
        drupal_add_js("/sites/all/libraries/prettyPhoto/old/jquery.prettyPhoto.js");
        drupal_add_css("sites/all/libraries/prettyPhoto/old/prettyPhoto.css");
        drupal_add_js("/".$thp."/js/img.gallery.js");
        return;
    }
}
