<?php

/**
 * @file
 * @author Bob Hutchinson http://drupal.org/user/52366
 * @copyright GNU GPL
 *
 * getlocations module theming functions.
 * using version 3 googlemaps API
 */

/**
 * Implements hook_theme().
 *
 * This lets us tell Drupal about our theme functions and their arguments.
 */
function getlocations_theme() {
  return array(
    'getlocations_show' => array(
      'variables' => array(
        'width' => '',
        'height' => '',
        'defaults' => '',
        'mapid' => '',
        'type' => '',
        'node' => '',
      ),
    ),
    'getlocations_info' => array(
      'variables' => array(
        'location' => NULL,
      ),
    ),
    'getlocations_box' => array(
      'variables' => array(
        'content' => NULL,
      ),
      'template' => 'getlocations_box',
    ),
    'getlocations_marker_box' => array(
      'variables' => array(
        'content' => NULL,
      ),
      'template' => 'getlocations_marker_box',
    ),
    'getlocations_settings_form' => array(
      'render element' => 'form',
    ),
    'getlocations_plugin_style_map_options_form' => array(
      'render element' => 'form',
    ),
    'getlocations_latitude_dms' => array(
      'variables' => array(
        'latitude' => '',
      ),
    ),
    'getlocations_longitude_dms' => array(
      'variables' => array(
        'longitude' => '',
      ),
    ),
  );
}

/**
 * All the map displays pass through here.
 *
 * @param array $variables
 *
 * @return
 *   Returns $output
 *
 */
function theme_getlocations_show($variables) {
  $width = $variables['width'];
  $height = $variables['height'];
  $defaults = $variables['defaults'];
  $mapid = $variables['mapid'];
  $type = $variables['type'];
  $node = $variables['node'];

  $output = '';
  $returnlink = FALSE;
  // return link
  if ($type == 'node' && $defaults['returnlink_page_enable']) {
    if (is_object($node)) {
      $linktext = $defaults['returnlink_page_link'];
      if ( preg_match("/%t/", $linktext)) {
        $linktext = preg_replace("/%t/", $node->title, $linktext);
      }
      $l = l($linktext, 'node/' . $node->nid);
      $returnlink = '<div class="getlocations_returnlink">' . $l . '</div>';
    }
  }
  elseif ($type == 'user' && $defaults['returnlink_user_enable']) {
    if (is_object($node)) {
      $linktext = $defaults['returnlink_user_link'];
      if ( preg_match("/%n/", $linktext)) {
        $linktext = preg_replace("/%n/", $node->name, $linktext);
      }
      $l = l($linktext, 'user/' . $node->uid);
      $returnlink = '<div class="getlocations_returnlink">' . $l . '</div>';
    }
  }
  elseif ($type == 'term' && $defaults['returnlink_term_enable'] && module_exists('taxonomy')) {
    if (is_object($node)) {
      $linktext = $defaults['returnlink_term_link'];
      if ( preg_match("/%n/", $linktext)) {
        $linktext = preg_replace("/%n/", $node->name, $linktext);
      }
      $l = l($linktext, 'taxonomy/term/' . $node->tid);
      $returnlink = '<div class="getlocations_returnlink">' . $l . '</div>';
    }
  }
  elseif ($type == 'comment' && $defaults['returnlink_comment_enable'] && module_exists('comment')) {
    if (is_object($node)) {
      $linktext = $defaults['returnlink_comment_link'];
      if ( preg_match("/%n/", $linktext)) {
        $linktext = preg_replace("/%n/", $node->subject, $linktext);
      }
      $l = l($linktext, 'comment/' . $node->cid);
      $returnlink = '<div class="getlocations_returnlink">' . $l . '</div>';
    }
  }
  if ($returnlink) {
    $output .= $returnlink;
  }

  // do map
  $output .= '<div class="getlocations_map_canvas" id="getlocations_map_canvas_' . $mapid . '" style="width: ' . $width . '; height: ' . $height . '" ></div>';
  // buttons
  $buttons = array();
  if ($defaults['trafficinfo']) {
    $buttons[] = '<input type="submit" value="' . t('Toggle traffic info') . '" title="' . t('Limited Availability') . '" id="getlocations_toggleTraffic_' . $mapid . '" class="form-submit" />';
  }
  if ($defaults['bicycleinfo']) {
    $buttons[] = '<input type="submit" value="' . t('Toggle bicycle info') . '" title="' . t('Limited Availability') . '" id="getlocations_toggleBicycle_' . $mapid . '" class="form-submit" />';
  }
  if ($defaults['transitinfo']) {
    $buttons[] = '<input type="submit" value="' . t('Toggle transit info') . '" title="' . t('Limited Availability') . '" id="getlocations_toggleTransit_' . $mapid . '" class="form-submit" />';
  }
  if ($defaults['panoramio_use'] && $defaults['panoramio_show']) {
    $buttons[] = '<input type="submit" value="' . t('Toggle panoramio') . '" id="getlocations_togglePanoramio_' . $mapid . '" class="form-submit" />';
  }
  if ($defaults['weather_use'] && $defaults['weather_show']) {
    $buttons[] = '<input type="submit" value="' . t('Toggle weather') . '" id="getlocations_toggleWeather_' . $mapid . '" class="form-submit" />';
    if ($defaults['weather_cloud']) {
      $buttons[] = '<input type="submit" value="' . t('Toggle clouds') . '" id="getlocations_toggleCloud_' . $mapid . '" class="form-submit" />';
    }
  }
  if (count($buttons)) {
    $output .= '<div class="getlocations_map_buttons container-inline">';
    $output .= implode('&nbsp;', $buttons);
    $output .= '</div>';
  }

  return $output;
}

/**
 * returns a location's vcard, requested by ajax
 *
 * @param array $variables
 *
 * @return
 *   Returns $output
 *
 */
function theme_getlocations_info($variables) {
  $location = $variables['location'];
  $output = '';

  $output .= '<div class="location vcard">';
  $output .= '<br />';

  // this logic gives the location name field precedence over the node title
  // similarly for users, terms and comments
  $link = FALSE;
  $l =  FALSE;
  if (isset($location['nid']) && $location['nid'] > 0) {
    if (! empty($location['name'])) {
      $link = $location['name'];
    }
    else {
      $node = node_load($location['nid']);
      $link = $node->title;
    }
    $l = l($link, 'node/' . $location['nid'], array('attributes' => array('class' => array('getlocations_infolink'), 'target' => '_parent')));
  }
  elseif (isset($location['uid']) && $location['uid'] > 0) {
    if (! empty($location['name'])) {
      $link = $location['name'];
    }
    else {
      $account = user_load($location['uid']);
      $link = $account->name;
    }
    $l = l($link, 'user/' . $location['uid'], array('attributes' => array('class' => array('getlocations_infolink'), 'target' => '_parent')));
  }
  elseif (isset($location['tid']) && $location['tid'] > 0 && module_exists('taxonomy')) {
    if (! empty($location['name'])) {
      $link = $location['name'];
    }
    else {
      $term = taxonomy_term_load($location['tid']);
      $link = $term->name;
    }
    $l = l($link, 'taxonomy/term/' . $location['tid'], array('attributes' => array('class' => array('getlocations_infolink'), 'target' => '_parent')));
  }
  elseif (isset($location['cid']) && $location['cid'] > 0 && module_exists('comment')) {
    if (! empty($location['name'])) {
      $link = $location['name'];
    }
    else {
      $comment = comment_load($location['cid']);
      $link = $comment->subject;
    }
    $l = l($link, 'comment/' . $location['cid'], array('attributes' => array('class' => array('getlocations_infolink'), 'target' => '_parent')));
  }
  if ($l) {
    $output .= '<h4>' . $l . '</h4>';
  }

  $output .= '<div class="adr">';
  if (! empty($location['street'])) {
    $output .= '<div class="street-address">' . $location['street'];
    if (! empty($location['additional'])) {
      $output .= ' ' . $location['additional'];
    }
    $output .= '</div>';
  }
  if (! empty($location['city'])) {
    $output .= '<span class="locality">' . $location['city'] . '</span>';
    if (! empty($location['province_name'])) {
      $output .= ",&nbsp;";
    }
    elseif (! empty($location['province'])) {
      $output .= ",&nbsp;";
    }
    else {
      $output .= "&nbsp;";
    }
  }
  if (isset($location['province_name']) && ! empty($location['province_name'])) {
    $output .= '<span class="region">' . $location['province_name'] . '</span>';
    if (! empty($location['postal_code'])) {
      $output .= "&nbsp;";
    }
  }
  elseif (isset($location['province']) && ! empty($location['province'])) {
    $output .= '<span class="region">' . $location['province'] . '</span>';
    if (! empty($location['postal_code'])) {
      $output .= "&nbsp;";
    }
  }
  if (! empty($location['postal_code'])) {
    $output .= '<span class="postal-code">' . drupal_strtoupper($location['postal_code']) . '</span>';
  }
  if (isset($location['country_name']) && ! empty($location['country_name'])) {
    $output .= '<div class="country-name">' . $location['country_name'] . '</div>';
  }
  elseif (isset($location['country']) && ! empty($location['country'])) {
    $output .= '<div class="country-name">' . $location['country'] . '</div>';
  }
  $output .= '</div>';
  if (isset($location['sdist'])) {
    if($location['sdist'] <> 1) {
      $unit_disp = getlocations_get_unit_names($location['sunit']);
    }
    else {
      $unit_disp = getlocations_get_unit_names($location['sunit'], 'single');
    }
    $output .= '<div class="search-distance">' . number_format(floatval($location['sdist']), 2) . ' ' . $unit_disp . '</div>';
  }
  $output .= '</div>';

  return $output;
}

/**
 * @param array $variables
 *
 * @return
 *   Modifies $variables in situ
 *
 */
function template_preprocess_getlocations_box(&$variables) {

  if (module_exists('admin_menu')) {
    admin_menu_suppress();
  }
  drupal_add_js(GETLOCATIONS_PATH . '/js/getlocations_box.js');

  // Construct page title
  if (drupal_get_title()) {
    $head_title = array(strip_tags(drupal_get_title()), variable_get('site_name', 'Drupal'));
  }
  else {
    $head_title = array(variable_get('site_name', 'Drupal'));
    if (variable_get('site_slogan', '')) {
      $head_title[] = variable_get('site_slogan', '');
    }
  }
  $variables['head_title'] = implode(' | ', $head_title);
  $variables['base_path'] = base_path();
  $variables['head'] = drupal_get_html_head();
  $variables['language'] = $GLOBALS['language'];
  $variables['language']->dir = $GLOBALS['language']->direction ? 'rtl' : 'ltr';
  $variables['css'] = drupal_add_css();
  $variables['styles'] = drupal_get_css();
  $variables['scripts'] = drupal_get_js();
  $variables['title'] = drupal_get_title();
  // Closure should be filled last.
  $variables['closure'] = theme('closure');
  // Compile a list of classes that are going to be applied to the body element.
  // This allows advanced theming based on context (home page, node of certain type, etc.).
  $body_classes = array();
  // Add a class that tells us whether we're on the front page or not.
  $body_classes[] = $variables['is_front'] ? 'front' : 'not-front';
  // Add a class that tells us whether the page is viewed by an authenticated user or not.
  $body_classes[] = $variables['logged_in'] ? 'logged-in' : 'not-logged-in';
  $body_classes[] = 'no-sidebars';
  // Implode with spaces.
  $variables['body_classes'] = implode(' ', $body_classes);

}

/**
 * @param array $variables
 *
 * @return
 *   Modifies $variables in situ
 *
 */
function template_preprocess_getlocations_marker_box(&$variables) {

  if (module_exists('admin_menu')) {
    admin_menu_suppress();
  }
  drupal_add_js(GETLOCATIONS_PATH . '/js/getlocations_marker_box.js');

  $head_title = t('Getlocations markers');
  $variables['head_title'] = $head_title;
  $variables['base_path'] = base_path();
  $variables['head'] = drupal_get_html_head();
  $variables['language'] = $GLOBALS['language'];
  $variables['language']->dir = $GLOBALS['language']->direction ? 'rtl' : 'ltr';
  $variables['css'] = drupal_add_css();
  $variables['styles'] = drupal_get_css();
  $variables['scripts'] = drupal_get_js();
  $variables['title'] = $head_title;

  // Closure should be filled last.
  $variables['closure'] = theme('closure');
  // Compile a list of classes that are going to be applied to the body element.
  // This allows advanced theming based on context (home page, node of certain type, etc.).
  $body_classes = array();
  // Add a class that tells us whether we're on the front page or not.
  $body_classes[] = $variables['is_front'] ? 'front' : 'not-front';
  // Add a class that tells us whether the page is viewed by an authenticated user or not.
  $body_classes[] = $variables['logged_in'] ? 'logged-in' : 'not-logged-in';
  $body_classes[] = 'no-sidebars';
  // Implode with spaces.
  $variables['body_classes'] = implode(' ', $body_classes);

}

function theme_getlocations_settings_form($variables) {

  $form = $variables['form'];

  $output = '';
  $prefix = '<fieldset class="form-wrapper">';
  $prefix .= '<legend><span class="fieldset-legend">' . t('Enable map types') . '</span></legend>';
  $prefix .= '<div class="fieldset-wrapper">';
  $prefix .= '<div class="fieldset-description">Select which maps you want to be available.</div>';
  $form['getlocations_default']['baselayers']['Map']['#prefix'] = $prefix;
  $form['getlocations_default']['baselayers']['Physical']['#suffix'] = '</div></fieldset>';
  $form['getlocations_default']['returnlink_page_link']['#prefix'] = '<div id="wrap-page-link">';
  $form['getlocations_default']['returnlink_page_link']['#suffix'] = '</div>';
  if (isset($form['getlocations_default']['returnlink_user_link'])) {
    $form['getlocations_default']['returnlink_user_link']['#prefix'] = '<div id="wrap-user-link">';
    $form['getlocations_default']['returnlink_user_link']['#suffix'] = '</div>';
  }
  if (isset($form['getlocations_default']['returnlink_term_link'])) {
    $form['getlocations_default']['returnlink_term_link']['#prefix'] = '<div id="wrap-term-link">';
    $form['getlocations_default']['returnlink_term_link']['#suffix'] = '</div>';
  }
  if (isset($form['getlocations_default']['returnlink_comment_link'])) {
    $form['getlocations_default']['returnlink_comment_link']['#prefix'] = '<div id="wrap-comment-link">';
    $form['getlocations_default']['returnlink_comment_link']['#suffix'] = '</div>';
  }
  if (isset($form['getlocations_colorbox']['width']) && isset($form['getlocations_colorbox']['height'])) {
    $form['getlocations_colorbox']['width']['#prefix'] = '<div id="wrap-getlocations-colorbox">';
    $form['getlocations_colorbox']['height']['#suffix'] = '</div>';
  }
  if (isset($form['getlocations_colorbox']['marker_width']) && isset($form['getlocations_colorbox']['marker_height'])) {
    $form['getlocations_colorbox']['marker_width']['#prefix'] = '<div id="wrap-getlocations-marker-colorbox">';
    $form['getlocations_colorbox']['marker_height']['#suffix'] = '</div>';
  }

  // marker colorbox
  if (module_exists('colorbox')) {
    $getlocations_colorbox = getlocations_colorbox_settings();
    if ($getlocations_colorbox['marker_enable']) {
      $link = getlocations_markerpicker_link($form['getlocations_default']['node_map_marker']['#id'], 'n');
      $form['getlocations_default']['node_map_marker']['#field_suffix'] = '&nbsp;' . $link;

      if (isset($form['getlocations_default']['user_map_marker'])) {
        $link = getlocations_markerpicker_link($form['getlocations_default']['user_map_marker']['#id'], 'u');
        $form['getlocations_default']['user_map_marker']['#field_suffix'] = '&nbsp;' . $link;
      }
      if (module_exists('taxonomy')) {
        if (isset($form['getlocations_default']['vocabulary_map_marker'])) {
          $link = getlocations_markerpicker_link($form['getlocations_default']['vocabulary_map_marker']['#id'], 'v');
          $form['getlocations_default']['vocabulary_map_marker']['#field_suffix'] = '&nbsp;' . $link;
        }
        if (isset($form['getlocations_default']['term_map_marker'])) {
          $link = getlocations_markerpicker_link($form['getlocations_default']['term_map_marker']['#id'], 'v');
          $form['getlocations_default']['term_map_marker']['#field_suffix'] = '&nbsp;' . $link;
        }
      }
      if (isset($form['getlocations_default']['comment_map_marker'])) {
        $link = getlocations_markerpicker_link($form['getlocations_default']['comment_map_marker']['#id'], 'c');
        $form['getlocations_default']['comment_map_marker']['#field_suffix'] = '&nbsp;' . $link;
      }
      $link = getlocations_markerpicker_link($form['getlocations_default']['input_map_marker']['#id'], 'i');
      $form['getlocations_default']['input_map_marker']['#field_suffix'] = '&nbsp;' . $link;

      if ($types = getlocations_get_types()) {
        foreach ($types AS $type => $name) {
          $field_names = getlocations_get_fieldname2($type, 'node');
          foreach ($field_names AS $field_name) {
            if (isset($form['getlocations_node_marker']['content_type'][$type]['field_name'][$field_name]['map_marker'])) {
              $link = getlocations_markerpicker_link($form['getlocations_node_marker']['content_type'][$type]['field_name'][$field_name]['map_marker']['#id'], 'n');
              $form['getlocations_node_marker']['content_type'][$type]['field_name'][$field_name]['map_marker']['#field_suffix'] = '&nbsp;' . $link;
            }
          }
        }
      }
      if (module_exists('taxonomy')) {
        if ($types = getlocations_get_vocabularies()) {
          foreach ($types AS $type => $name) {
            if (isset($form['getlocations_vocabulary_marker']['vocabulary'][$type]['map_marker'])) {
              $link = getlocations_markerpicker_link($form['getlocations_vocabulary_marker']['vocabulary'][$type]['map_marker']['#id'], 'v');
              $form['getlocations_vocabulary_marker']['vocabulary'][$type]['map_marker']['#field_suffix'] = '&nbsp;' . $link;
            }
          }
        }

        // term
        $getlocations_term_marker = variable_get('getlocations_term_marker', array('enable' => 0, 'vids' => 0, 'max_depth' => ''));
        if ($getlocations_term_marker['enable'] && $getlocations_term_marker['vids']) {
          $depth = is_numeric($getlocations_term_marker['max_depth']) && $getlocations_term_marker['max_depth'] > 0 ? $getlocations_term_marker['max_depth'] : NULL;
          $vids = $getlocations_term_marker['vids'];
          foreach ($vids AS $vid) {
            $terms = taxonomy_get_tree($vid, 0, $depth);
            $tct = 0;
            foreach ($terms AS $term) {
              $tid = $term->tid;
              if (isset($form['getlocations_term_marker']['vid'][$vid]['term'][$tid]['map_marker'])) {
                $link = getlocations_markerpicker_link($form['getlocations_term_marker']['vid'][$vid]['term'][$tid]['map_marker']['#id'], 'n');
                $form['getlocations_term_marker']['vid'][$vid]['term'][$tid]['map_marker']['#field_suffix'] = '&nbsp;' . $link;
                if (! $tct) {
                  $v = taxonomy_vocabulary_load($vid);
                  $vocab_name = $v->name;
                  $form['getlocations_term_marker']['vid'][$vid]['term'][$tid]['map_marker']['#prefix'] = t('Terms for %name', array('%name' => $vocab_name));
                }
              }
              $tct++;
            }
          }
        }
      }
    }
  }

  $form['getlocations_default']['useclustermanager']['#prefix'] = '<div id="wrap-getlocations-clusteropts">';
  $form['getlocations_default']['markerclusterer_minsize']['#suffix'] = '</div>';

  $form['getlocations_default']['usemarkermanager']['#prefix'] = '<div id="wrap-getlocations-markeropts">';
  $form['getlocations_default']['maxzoom']['#suffix'] = '</div>';

  $output .= drupal_render_children($form);
  return $output;
}

function theme_getlocations_plugin_style_map_options_form($variables) {
  $form = $variables['form'];

  if (module_exists('colorbox')) {
    $getlocations_colorbox = getlocations_colorbox_settings();
    if ($getlocations_colorbox['marker_enable']) {
      if (isset($form['node_map_marker'])) {
        $link = getlocations_markerpicker_link($form['node_map_marker']['#id'], 'n');
        $form['node_map_marker']['#field_suffix'] = '&nbsp;' . $link;
      }
      $getlocations_node_marker = variable_get('getlocations_node_marker', array('enable' => 0));
      if ($getlocations_node_marker['enable']) {
        if ($types = getlocations_get_types()) {
          foreach ($types AS $type => $name) {
            $field_names = getlocations_get_fieldname2($type, 'node');
            foreach ($field_names AS $field_name) {
              $mkey = 'node_marker__' . $type . '__' . $field_name;
              $link = getlocations_markerpicker_link($form[$mkey]['#id'], 'n');
              $form[$mkey]['#field_suffix'] = '&nbsp;' . $link;
            }
          }
        }
      }
      if (isset($form['user_map_marker'])) {
        $link = getlocations_markerpicker_link($form['user_map_marker']['#id'], 'n');
        $form['user_map_marker']['#field_suffix'] = '&nbsp;' . $link;
      }

      if (module_exists('taxonomy')) {
        if (isset($form['vocabulary_map_marker'])) {
          $link = getlocations_markerpicker_link($form['vocabulary_map_marker']['#id'], 'v');
          $form['vocabulary_map_marker']['#field_suffix'] = '&nbsp;' . $link;
        }
        $getlocations_vocabulary_marker = variable_get('getlocations_vocabulary_marker', array('enable' => 0));
        if ($getlocations_vocabulary_marker['enable']) {
          foreach ($types AS $type => $name) {
            $field_name = getlocations_get_fieldname($type, 'taxonomy_term');
            $mkey = 'vocabulary_marker_' . $field_name;
            $link = getlocations_markerpicker_link($form[$mkey]['#id'], 'n');
            $form[$mkey]['#field_suffix'] = '&nbsp;' . $link;
          }
        }
        if (isset($form['term_map_marker'])) {
          $link = getlocations_markerpicker_link($form['term_map_marker']['#id'], 'v');
          $form['term_map_marker']['#field_suffix'] = '&nbsp;' . $link;
        }
        $getlocations_term_marker = variable_get('getlocations_term_marker', array('enable' => 0, 'vids' => 0, 'max_depth' => ''));
        if ($getlocations_term_marker['enable'] && $getlocations_term_marker['vids']) {
          $depth = is_numeric($getlocations_term_marker['max_depth']) && $getlocations_term_marker['max_depth'] > 0 ? $getlocations_term_marker['max_depth'] : NULL;
          $vids = $getlocations_term_marker['vids'];
          foreach ($vids AS $vid) {
            $terms = taxonomy_get_tree($vid, 0, $depth);
            foreach ($terms AS $term) {
              $name = $term->name;
              $tid = $term->tid;
              $mkey = 'term_marker_' . $tid;
              $link = getlocations_markerpicker_link($form[$mkey]['#id'], 'n');
              $form[$mkey]['#field_suffix'] = '&nbsp;' . $link;
            }
          }
        }
      }

      if (isset($form['comment_map_marker'])) {
        $link = getlocations_markerpicker_link($form['comment_map_marker']['#id'], 'n');
        $form['comment_map_marker']['#field_suffix'] = '&nbsp;' . $link;
      }
    }
  }

  $output = drupal_render_children($form);
  return $output;

}

/**
 * Display a coordinate.
 * from location module
 */
/**
 * @param array $variables
 *
 * @return
 *   Returns $output
 *
 */
function theme_getlocations_latitude_dms($variables) {
  $latitude = $variables['latitude'];
  $output = '';
  list($degrees, $minutes, $seconds, $negative) = getlocations_dd_to_dms($latitude);
  $output .= $degrees . "&deg; " . $minutes . "&#39; " . $seconds . "&#34; ";
  if (!$negative) {
    $output .= 'N';
  }
  else {
    $output .= 'S';
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
function theme_getlocations_longitude_dms($variables) {
  $longitude = $variables['longitude'];
  $output = '';
  list($degrees, $minutes, $seconds, $negative) = getlocations_dd_to_dms($longitude);
  $output .= $degrees . "&deg; " . $minutes . "&#39; " . $seconds . "&#34; ";
  if (!$negative) {
    $output .= 'E';
  }
  else {
    $output .= 'W';
  }
  return $output;
}
