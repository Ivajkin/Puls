<?php

/**
 * @file
 * @author Bob Hutchinson http://drupal.org/user/52366
 * @copyright GNU GPL
 *
 * getlocations_field module theming functions.
 * using version 3 googlemaps API
 */


/**
 * Implements hook_theme().
 *
 * This lets us tell Drupal about our theme functions and their arguments.
 */
function getlocations_fields_theme() {

  return array(
    'getlocations_fields_show' => array(
      'variables' => array(
        'locations' => '',
        'settings' => '',
      ),
    ),
    'getlocations_fields_field_settings_form' => array(
      'render element' => 'form',
    ),
    'getlocations_fields_field_formatter_settings_form' => array(
      'render element' => 'form',
    ),
    'getlocations_fields_field_widget_form' => array(
      'render element' => 'form',
    ),
    'getlocations_fields_settings_form' => array(
      'render element' => 'form',
    ),
    'getlocations_fields_distance' => array(
      'variables' => array(
        'distance' => '',
        'units' => 'km',
      ),
    ),
  );

}

/**
 * @param array $variables
 *
 * @return
 *   Returns $output
 *
 */
function theme_getlocations_fields_show($variables) {
  $locations = $variables['locations'];
  $settings = $variables['settings'];

  if ($settings['display_onemap']) {
    $minmaxes = array('minlat' => 0, 'minlon' => 0, 'maxlat' => 0, 'maxlon' => 0);
  }
  else {
    $minmaxes = '';
  }

  $output = '';
  $location_ct = 0;
  foreach ($locations AS $location) {
    $output .= '<div class="location vcard">';
    if (! empty($location['name']) && $settings['display_name']) {
      $output .= '<h4>' . $location['name'] . '</h4>';
    }
    $output .= '<div class="adr">';
    if (! empty($location['street']) && $settings['display_street'] ) {
      $output .= '<div class="street-address">' . $location['street'];
      if (! empty($location['additional']) && $settings['display_additional']) {
        $output .= ' ' . $location['additional'];
      }
      $output .= '</div>';
    }
    if (! empty($location['city']) && $settings['display_city']) {
      $output .= '<span class="locality">' . $location['city'] . '</span>';
      if (! empty($location['province']) && $settings['display_province']) {
        $output .= ",&nbsp;";
      }
      else {
        $output .= "&nbsp;";
      }
    }
    if (! empty($location['province']) && $settings['display_province']) {
      $output .= '<span class="region">' . $location['province'] . '</span>';
      if (! empty($location['postal_code']) && $settings['display_postal_code']) {
        $output .= "&nbsp;";
      }
    }
    if (! empty($location['postal_code']) && $settings['display_postal_code']) {
      $output .= '<span class="postal-code">' . drupal_strtoupper($location['postal_code']) . '</span>';
    }
    if (! empty($location['country']) && $settings['display_country']) {
      if ($settings['country_full'] && drupal_strlen($location['country']) == 2 ) {
        // this is a 2 letter code, we want the full name
        $countries = getlocations_get_countries_list();
        $country = $countries[$location['country']];
        if (empty($country)) {
          $country = $location['country'];
        }
      }
      else {
        $country = $location['country'];
      }
      $output .= '<div class="country-name">' . $country . '</div>';
    }
    $output .= '</div>';
    $output .= '</div>';

    if ($settings['display_latlong']) {
      $lat = $location['latitude'];
      $lng = $location['longitude'];
      if ($settings['display_dms']) {
        $lat = theme('getlocations_latitude_dms', array('latitude' => $lat));
        $lng = theme('getlocations_longitude_dms', array('longitude' => $lng));
      }
      if ($settings['display_showmap']) {
        $output .= '<div class="getlocations_fields_latlon_wrapper_themed">';
        $output .= '<div class="getlocations_fields_lat_wrapper_themed">';
        $output .= t('Latitude') . ': ' . $lat . '</div>';
        $output .= '<div class="getlocations_fields_lon_wrapper_themed">';
        $output .= t('Longitude') . ': ' . $lng . '</div>';
        $output .= '</div>';
        $output .= '<br />';
      }
      else {
      $output .= '<div class="getlocations_fields_latlon_wrapper_themed_nomap">';
      $output .= '<div class="getlocations_fields_lat_wrapper_themed_nomap">';
      $output .= t('Latitude') . ': ' . $lat . '</div>';
      $output .= '<div class="getlocations_fields_lon_wrapper_themed_nomap">';
      $output .= t('Longitude') . ': ' . $lng . '</div>';
      $output .= '</div>';
      $output .= '<br />';
      }
    }

    if ($location['longitude'] && $location['latitude']) {
      if ($settings['display_showmap']) {
        $name = htmlspecialchars_decode(isset($location['name']) && $location['name'] ? strip_tags($location['name']) : (isset($location['title']) && $location['title'] ? strip_tags($location['title']) : ''), ENT_QUOTES);
        $base = '';
        if ($location['nid']) {
          $base = 'nid';
        }
        elseif ($location['uid']) {
          $base = 'uid';
        }
        elseif ($location['tid']) {
          $base = 'tid';
        }
        elseif ($location['cid']) {
          $base = 'cid';
        }

        $marker = $settings['map_marker'];
        if ($base == 'nid') {
          // term markers, nodes only
          $marker = getlocations_get_term_marker($location['nid'], $marker);
        }

        // per location marker
        if (isset($location['marker']) && ! empty($location['marker'])) {
          $marker = $location['marker'];
        }
        // onemap
        if ($settings['display_onemap']) {
          $latlons[] = array($location['latitude'], $location['longitude'], $location['glid'], $name, $marker, $base);
          $minmaxes = getlocations_do_minmaxes($location_ct, $location, $minmaxes);
        }
        else {
          $latlons = array($location['latitude'], $location['longitude'], $location['glid'], $name, $marker, $base);
          $map = getlocations_fields_getmap_show($settings, $latlons);
          $output .= $map;
        }
      }
      else {
        if ($settings['display_maplink']) {
          if (count($locations) > 1 ) {
            $output .= '<p>' . l(t('Show on a map'), 'getlocations/lids/' . $location['glid']) . '</p>';
          }
          else {
            if ($location['nid']) {
              $output .= '<p>' . l(t('Show on a map'), 'getlocations/node/' . $location['nid']) . '</p>';
            }
            elseif ($location['uid']) {
              $output .= '<p>' . l(t('Show on a map'), 'getlocations/user/' . $location['uid']) . '</p>';
            }
            elseif ($location['tid']) {
              $output .= '<p>' . l(t('Show on a map'), 'getlocations/term/' . $location['tid']) . '</p>';
            }
            elseif ($location['cid']) {
              $output .= '<p>' . l(t('Show on a map'), 'getlocations/comment/' . $location['cid']) . '</p>';
            }
          }
        }
      }
      $location_ct++;
    }
  }
  // onemap
  if ($settings['display_showmap'] && $settings['display_onemap'] && isset($latlons)) {
    if ($location_ct < 2 ) {
      unset($minmaxes);
      $minmaxes = '';
    }
    $map = getlocations_fields_getmap_show($settings, $latlons, $minmaxes);
    $output .= $map;
  }

  return $output;

}


/**
 * @param array $variables
 *
 * @return
 *   Returns $output
 *
 */
function theme_getlocations_fields_field_settings_form($variables) {
  $form = $variables['form'];

  $output = '';

  // try table
  $header = array();
  $no_striping = TRUE;
  $rows = array(
    array('data' =>
      array(
        array('data' => $form['input_name_width'], 'class' => array('getlocations_fields_input_cell1')),
        array('data' => $form['input_name_weight'], 'class' => array('getlocations_fields_input_cell2')),
        array('data' => $form['input_name_required'], 'class' => array('getlocations_fields_input_cell3')),
      ),
      'no_striping' => $no_striping,
    ),
    array('data' =>
      array(
        array('data' => $form['input_street_width'], 'class' => array('getlocations_fields_input_cell1')),
        array('data' => $form['input_street_weight'], 'class' => array('getlocations_fields_input_cell2')),
        array('data' => $form['input_street_required'], 'class' => array('getlocations_fields_input_cell3')),
      ),
      'no_striping' => $no_striping,
    ),

    array('data' =>
      array(
        array('data' => $form['input_additional_width'], 'class' => array('getlocations_fields_input_cell1')),
        array('data' => $form['input_additional_weight'], 'class' => array('getlocations_fields_input_cell2')),
        array('data' => $form['input_additional_required'], 'class' => array('getlocations_fields_input_cell3')),
      ),
      'no_striping' => $no_striping,
    ),

    array('data' =>
      array(
        array('data' => $form['input_city_width'], 'class' => array('getlocations_fields_input_cell1')),
        array('data' => $form['input_city_weight'], 'class' => array('getlocations_fields_input_cell2')),
        array('data' => $form['input_city_required'], 'class' => array('getlocations_fields_input_cell3')),
      ),
      'no_striping' => $no_striping,
    ),
    array('data' =>
      array(
        array('data' => $form['input_province_width'], 'class' => array('getlocations_fields_input_cell1')),
        array('data' => $form['input_province_weight'], 'class' => array('getlocations_fields_input_cell2')),
        array('data' => $form['input_province_required'], 'class' => array('getlocations_fields_input_cell3')),
      ),
      'no_striping' => $no_striping,
    ),
    array('data' =>
      array(
        array('data' => $form['input_postal_code_width'], 'class' => array('getlocations_fields_input_cell1')),
        array('data' => $form['input_postal_code_weight'], 'class' => array('getlocations_fields_input_cell2')),
        array('data' => $form['input_postal_code_required'], 'class' => array('getlocations_fields_input_cell3')),
      ),
      'no_striping' => $no_striping,
    ),
    array('data' =>
      array(
        array('data' => $form['input_country_width'], 'class' => array('getlocations_fields_input_cell1')),
        array('data' => $form['input_country_weight'], 'class' => array('getlocations_fields_input_cell2')),
        array('data' => $form['input_country_required'], 'class' => array('getlocations_fields_input_cell3')),
      ),
      'no_striping' => $no_striping,
    ),

    array('data' =>
      array(
        array('data' => $form['input_latitude_width'], 'class' => array('getlocations_fields_input_cell1')),
        array('data' => $form['input_latitude_weight'], 'class' => array('getlocations_fields_input_cell2')),
        array('data' => '', 'class' => array('getlocations_fields_input_cell3')),
      ),
      'no_striping' => $no_striping,
    ),
    array('data' =>
      array(
        array('data' => $form['input_longitude_width'], 'class' => array('getlocations_fields_input_cell1')),
        array('data' => $form['input_longitude_weight'], 'class' => array('getlocations_fields_input_cell2')),
        array('data' => '', 'class' => array('getlocations_fields_input_cell3')),
      ),
      'no_striping' => $no_striping,
    ),

  );
  $attributes = array('class' => array('getlocations_fields_input_table'));
  $table = theme('table', array(
      'header' => $header,
      'rows' => $rows,
      'attributes' => $attributes,
    )
  );
  $form['input_name_width'] = array('#markup' => $table);
  unset($form['input_name_required']);
  unset($form['input_name_weight']);
  unset($form['input_street_width']);
  unset($form['input_street_required']);
  unset($form['input_street_weight']);
  unset($form['input_additional_width']);
  unset($form['input_additional_required']);
  unset($form['input_additional_weight']);
  unset($form['input_city_width']);
  unset($form['input_city_required']);
  unset($form['input_city_weight']);
  unset($form['input_province_width']);
  unset($form['input_province_required']);
  unset($form['input_province_weight']);
  unset($form['input_postal_code_width']);
  unset($form['input_postal_code_required']);
  unset($form['input_postal_code_weight']);
  unset($form['input_country_width']);
  unset($form['input_country_required']);
  unset($form['input_country_weight']);
  unset($form['input_latitude_width']);
  unset($form['input_latitude_weight']);
  unset($form['input_longitude_width']);
  unset($form['input_longitude_weight']);

  $prefix = '<fieldset class="form-wrapper">';
  $prefix .= '<legend><span class="fieldset-legend">' . t('Enable map types') . '</span></legend>';
  $prefix .= '<div class="fieldset-wrapper">';
  $prefix .= '<div class="fieldset-description">' . t( 'Select which maps you want to be available.') . '</div>';
  $form['baselayers']['Map']['#prefix'] = $prefix;
  $form['baselayers']['Physical']['#suffix'] = '</div></fieldset>';

  $prefix = '<fieldset class="form-wrapper">';
  $prefix .= '<legend><span class="fieldset-legend">' . t('Input form settings') . '</span></legend>';
  $prefix .= '<div class="fieldset-wrapper">';
  $prefix .= '<div class="fieldset-description">' . t('Configure the input form.') . '</div>';
  $form['use_address']['#prefix'] = $prefix;
  if (module_exists('countries')) {
    $form['only_countries']['#suffix'] = '</div></fieldset>';
  }
  else {
    $form['use_country_dropdown']['#suffix'] = '</div></fieldset>';
  }

  $prefix = '<fieldset class="form-wrapper">';
  $prefix .= '<legend><span class="fieldset-legend">' . t('Input map settings') . '</span></legend>';
  $prefix .= '<div class="fieldset-wrapper">';
  $prefix .= '<div class="fieldset-description">' . t('Configure the input map.') . '</div>';
  $form['mapwidth']['#prefix'] = $prefix;
  $form['draggable']['#suffix'] = '</div></fieldset>';

  if (isset($form['input_map_marker'])) {
    $link = getlocations_markerpicker_link($form['input_map_marker']['#id'], 'i');
    $form['input_map_marker']['#field_suffix'] = '&nbsp;' . $link;
  }

  $output .= drupal_render_children($form);
  return $output;

}

/**
 * The display settings form
 *
 * @param array $variables
 *
 * @return
 *   Returns $output
 *
 */
function theme_getlocations_fields_field_formatter_settings_form($variables) {

  $form = $variables['form'];

  $output = '';
  $prefix = '<fieldset class="form-wrapper">';
  $prefix .= '<legend><span class="fieldset-legend">' . t('Enable map types') . '</span></legend>';
  $prefix .= '<div class="fieldset-wrapper">';
  $prefix .= '<div class="fieldset-description">Select which maps you want to be available.</div>';
  $form['baselayers']['Map']['#prefix'] = $prefix;
  $form['baselayers']['Physical']['#suffix'] = '</div></fieldset>';

  $form['display_showmap']['#prefix'] = '<fieldset class="getlocations_fieldset form-wrapper"><legend><span class="fieldset-legend">' . t('Address display settings') . '</span></legend><div class="fieldset-wrapper">';
  $form['country_full']['#suffix'] = '</div></fieldset>';

  $output .= drupal_render_children($form);

  return $output;

}

/**
 * The input form
 *
 * @param array $variables
 *
 * @return
 *   Returns $output
 *
 */
function theme_getlocations_fields_field_widget_form($variables) {

  $form = $variables['form'];
  $output = '';
  $mapid = NULL;
  if (isset($form['mapid']['#value'])) {
    $mapid = $form['mapid']['#value'];
    unset($form['mapid']);
  }
  if ($mapid !== NULL && isset($form['geobutton']) ) {
    $geobutton = '<p><input type="submit" value="' . t('Geocode this address') . '" title="' . t('Get the latitude and longitude for this address') . '" id="getlocations_geocodebutton_' . $mapid . '" class="form-submit" /></p>';
    $geobutton .= '<p>' . t('If you leave the address empty you can Geocode by moving the marker on the map') . '</p>';
    $form['geobutton']['#markup'] = $geobutton;
  }

  if ($mapid !== NULL && isset($form['smart_ip_button']) && module_exists('smart_ip')) {
    $smart_ip_button = '<p><input type="submit" value="' . t('Use current user position') . '" title="' . t('Get the latitude and longitude for this address from the user IP') . '" id="getlocations_smart_ip_button_' . $mapid . '" class="form-submit" /></p>';
    $form['smart_ip_button']['#markup'] = $smart_ip_button;
  }
  // html5 and google
  if (getlocations_is_mobile() && $mapid !== NULL && isset($form['geolocation_button'])) {
    $geolocation_button = '<p><input type="submit" value="' . t('Find Location') . '" title="' . t('Get the latitude and longitude for this address from the browser') . '" id="getlocations_geolocation_button_' . $mapid . '" class="form-submit" />&nbsp;<span id="getlocations_geolocation_status_' . $mapid . '" ></span></p>';
    $geolocation_button .= '<p>Use the browser to find your current location</p>';
    $form['geolocation_button']['#markup'] = $geolocation_button;
  }

  $form['latitude']['#prefix'] = '<div class="getlocations_fields_latlon_wrapper clearfix"><div class="getlocations_fields_lat_wrapper">';
  $form['latitude']['#suffix'] = '</div>';
  $form['longitude']['#prefix'] = '<div class="getlocations_fields_lon_wrapper">';
  $form['longitude']['#suffix'] = '</div></div>';

  if (isset($form['marker'])) {
    // colorbox marker
    $link = getlocations_markerpicker_link($form['marker']['#id'], 'i');
    $form['marker']['#field_suffix'] = '&nbsp;' . $link;
    $form['marker']['#prefix'] = '<div class="getlocations_fields_marker_wrapper">';
    $form['marker']['#suffix'] = '</div>';
  }

  $form['city']['#prefix'] = '<div class="getlocations_fields_city_wrapper">';
  $form['city']['#suffix'] = '</div>';
  $form['province']['#prefix'] = '<div class="getlocations_fields_province_wrapper">';
  $form['province']['#suffix'] = '</div>';
  $form['country']['#prefix'] = '<div class="getlocations_fields_country_wrapper">';
  if (isset($form['country_display'])) {
    $form['country_display']['#suffix'] = '</div>';
  }
  else {
    $form['country']['#suffix'] = '</div>';
  }

  $msg = t('To use the map to find a location, drag the marker or click on the map.');
  $map = $form['map']['#markup'];
  $form['map']['#prefix'] = '<div class="getlocations_fields_map_wrapper">';
  $form['map']['#suffix'] = '</div>';
  $form['map']['#markup'] = '<p>' . $msg . '</p><div class="getlocations_fields_map">' . $map . '</div>';

  // wrap 'fake' required in a div
  if ($mapid !== NULL && isset($form['getlocations_required'])) {
    $requireds = explode(',', $form['getlocations_required']['#value']);
    foreach ($requireds AS $id) {
      $form[$id]['#prefix'] = '<div class="getlocations_required_' . $id . '_' . $mapid . '">';
      $form[$id]['#suffix'] = '</div>';
    }
    unset($form['getlocations_required']);
  }

  $output .= drupal_render_children($form);
  return $output;

}

/**
 * The settings form
 *
 * @param array $variables
 *
 * @return
 *   Returns $output
 *
 */
function theme_getlocations_fields_settings_form($variables) {
  $form = $variables['form'];

  $output = '';

  // try table
  $header = array();
  $no_striping = TRUE;
  $rows = array(
    array('data' =>
      array(
        array('data' => $form['getlocations_fields_defaults']['input_name_width'], 'class' => array('getlocations_fields_input_cell1')),
        array('data' => $form['getlocations_fields_defaults']['input_name_weight'], 'class' => array('getlocations_fields_input_cell2')),
        array('data' => $form['getlocations_fields_defaults']['input_name_required'], 'class' => array('getlocations_fields_input_cell3')),
      ),
      'no_striping' => $no_striping,
    ),
    array('data' =>
      array(
        array('data' => $form['getlocations_fields_defaults']['input_street_width'], 'class' => array('getlocations_fields_input_cell1')),
        array('data' => $form['getlocations_fields_defaults']['input_street_weight'], 'class' => array('getlocations_fields_input_cell2')),
        array('data' => $form['getlocations_fields_defaults']['input_street_required'], 'class' => array('getlocations_fields_input_cell3')),
      ),
      'no_striping' => $no_striping,
    ),
    array('data' =>
      array(
        array('data' => $form['getlocations_fields_defaults']['input_additional_width'], 'class' => array('getlocations_fields_input_cell1')),
        array('data' => $form['getlocations_fields_defaults']['input_additional_weight'], 'class' => array('getlocations_fields_input_cell2')),
        array('data' => $form['getlocations_fields_defaults']['input_additional_required'], 'class' => array('getlocations_fields_input_cell3')),
      ),
      'no_striping' => $no_striping,
    ),
    array('data' =>
      array(
        array('data' => $form['getlocations_fields_defaults']['input_city_width'], 'class' => array('getlocations_fields_input_cell1')),
        array('data' => $form['getlocations_fields_defaults']['input_city_weight'], 'class' => array('getlocations_fields_input_cell2')),
        array('data' => $form['getlocations_fields_defaults']['input_city_required'], 'class' => array('getlocations_fields_input_cell3')),
      ),
      'no_striping' => $no_striping,
    ),
    array('data' =>
      array(
        array('data' => $form['getlocations_fields_defaults']['input_province_width'], 'class' => array('getlocations_fields_input_cell1')),
        array('data' => $form['getlocations_fields_defaults']['input_province_weight'], 'class' => array('getlocations_fields_input_cell2')),
        array('data' => $form['getlocations_fields_defaults']['input_province_required'], 'class' => array('getlocations_fields_input_cell3')),
      ),
      'no_striping' => $no_striping,
    ),
    array('data' =>
      array(
        array('data' => $form['getlocations_fields_defaults']['input_postal_code_width'], 'class' => array('getlocations_fields_input_cell1')),
        array('data' => $form['getlocations_fields_defaults']['input_postal_code_weight'], 'class' => array('getlocations_fields_input_cell2')),
        array('data' => $form['getlocations_fields_defaults']['input_postal_code_required'], 'class' => array('getlocations_fields_input_cell3')),
      ),
      'no_striping' => $no_striping,
    ),
    array('data' =>
      array(
        array('data' => $form['getlocations_fields_defaults']['input_country_width'], 'class' => array('getlocations_fields_input_cell1')),
        array('data' => $form['getlocations_fields_defaults']['input_country_weight'], 'class' => array('getlocations_fields_input_cell2')),
        array('data' => $form['getlocations_fields_defaults']['input_country_required'], 'class' => array('getlocations_fields_input_cell3')),
      ),
      'no_striping' => $no_striping,
    ),

    array('data' =>
      array(
        array('data' => $form['getlocations_fields_defaults']['input_latitude_width'], 'class' => array('getlocations_fields_input_cell1')),
        array('data' => $form['getlocations_fields_defaults']['input_latitude_weight'], 'class' => array('getlocations_fields_input_cell2')),
        array('data' => '', 'class' => array('getlocations_fields_input_cell3')),
      ),
      'no_striping' => $no_striping,
    ),
    array('data' =>
      array(
        array('data' => $form['getlocations_fields_defaults']['input_longitude_width'], 'class' => array('getlocations_fields_input_cell1')),
        array('data' => $form['getlocations_fields_defaults']['input_longitude_weight'], 'class' => array('getlocations_fields_input_cell2')),
        array('data' => '', 'class' => array('getlocations_fields_input_cell3')),
      ),
      'no_striping' => $no_striping,
    ),

  );
  $attributes = array('class' => array('getlocations_fields_input_table'));
  $table = theme('table', array(
      'header' => $header,
      'rows' => $rows,
      'attributes' => $attributes,
    )
  );
  $form['getlocations_fields_defaults']['input_name_width'] = array('#markup' => $table);
  unset($form['getlocations_fields_defaults']['input_name_required']);
  unset($form['getlocations_fields_defaults']['input_name_weight']);
  unset($form['getlocations_fields_defaults']['input_street_width']);
  unset($form['getlocations_fields_defaults']['input_street_required']);
  unset($form['getlocations_fields_defaults']['input_street_weight']);
  unset($form['getlocations_fields_defaults']['input_additional_width']);
  unset($form['getlocations_fields_defaults']['input_additional_required']);
  unset($form['getlocations_fields_defaults']['input_additional_weight']);
  unset($form['getlocations_fields_defaults']['input_city_width']);
  unset($form['getlocations_fields_defaults']['input_city_required']);
  unset($form['getlocations_fields_defaults']['input_city_weight']);
  unset($form['getlocations_fields_defaults']['input_province_width']);
  unset($form['getlocations_fields_defaults']['input_province_required']);
  unset($form['getlocations_fields_defaults']['input_province_weight']);
  unset($form['getlocations_fields_defaults']['input_postal_code_width']);
  unset($form['getlocations_fields_defaults']['input_postal_code_required']);
  unset($form['getlocations_fields_defaults']['input_postal_code_weight']);
  unset($form['getlocations_fields_defaults']['input_country_width']);
  unset($form['getlocations_fields_defaults']['input_country_required']);
  unset($form['getlocations_fields_defaults']['input_country_weight']);
  unset($form['getlocations_fields_defaults']['input_latitude_width']);
  unset($form['getlocations_fields_defaults']['input_latitude_weight']);
  unset($form['getlocations_fields_defaults']['input_longitude_width']);
  unset($form['getlocations_fields_defaults']['input_longitude_weight']);

  $prefix = '<fieldset class="form-wrapper">';
  $prefix .= '<legend><span class="fieldset-legend">' . t('Default Input form settings') . '</span></legend>';
  $prefix .= '<div class="fieldset-wrapper">';
  $prefix .= '<div class="fieldset-description">' . t('Configure the default input form.') . '</div>';
  $form['getlocations_fields_defaults']['use_address']['#prefix'] = $prefix;
  $form['getlocations_fields_defaults']['use_country_dropdown']['#suffix'] = '</div></fieldset>';

  $prefix = '<fieldset class="form-wrapper">';
  $prefix .= '<legend><span class="fieldset-legend">' . t('Default Address display settings') . '</span></legend>';
  $prefix .= '<div class="fieldset-wrapper">';
  $prefix .= '<div class="fieldset-description">' . t('Configure the default address display.') . '</div>';
  $form['getlocations_fields_defaults']['display_showmap']['#prefix'] = $prefix;
  $form['getlocations_fields_defaults']['country_full']['#suffix'] = '</div></fieldset>';

  $output .= drupal_render_children($form);
  return $output;

}

function theme_getlocations_fields_distance($variables) {
  $distance = $variables['distance'];
  $shortunit = $variables['shortunit'];
  // also available
  // $longunit = $variables['longunit'];
  $output = '';
  $output .= number_format($distance, 2) . ' ' . $shortunit;
  return $output;
}

/**
 * Theme preprocess function for getlocations_fields_distance.
 */
function template_preprocess_getlocations_fields_distance(&$variables) {
  $units = $variables['units'];
  unset($variables['units']);
  $variables['shortunit'] = $units;
  $variables['longunit'] = getlocations_get_unit_names($units, 'plurals');
  $variables['distance'] = (float)$variables['distance'];
}
