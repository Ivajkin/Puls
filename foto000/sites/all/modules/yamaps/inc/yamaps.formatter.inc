<?php

/**
 * @file
 * Yandex.Maps field formatter.
 */

/**
 * Implements hook_field_formatter_info().
 */
function yamaps_field_formatter_info() {
  return array(
    // Dynamic map formatter.
    'field_yamaps_formatter_dynamic' => array(
      'label' => t('Dynamic'),
      'field types' => array('field_yamaps'),
      'settings' => array(
        'controls' => TRUE,
        'traffic' => TRUE,
        'width' => '100%',
        'height' => '400px',
        'behaviors' => array(
          'clickZoom',
          'dblClickZoom',
          'drag',
          'scrollZoom',
          'ruler',
          'rightMouseButtonMagnifier',
        ),
        'yamaps_display_options' => array(
          'display_type' => 'map',
          'open_button_text' => 'Open map',
        ),
      ),
    ),
    // Static map formatter.
    'field_yamaps_formatter_static' => array(
      'label' => t('Static'),
      'field types' => array('field_yamaps'),
      'settings' => array(
        's_traffic' => FALSE,
        's_width' => 640,
        's_height' => 400,
      ),
    ),
    /*// Combined map formatter.
    // @todo create logic for combined type.
    'field_yamaps_formatter_modal_window' => array(
      'label' => t('Modal window'),
      'field types' => array('field_yamaps'),
      'settings' => array(
        'original_field' => array(
          'type' => 'field_yamaps_formatter_static',
          // possible variants static_image, button,
          'field_settings' => array(),
        ),
        'modal_window_settings' => array(
          'type' => 'field_yamaps_formatter_dynamic',
          // possible variants static_image, dynamic_cart
          'field_settings' => array(),
        ),
      ),
    ),*/
  );
}

/**
 * Implements hook_field_formatter_settings_form().
 */
function yamaps_field_formatter_settings_form($field, $instance, $view_mode, $form, &$form_state) {
  $display = $instance['display'][$view_mode];
  $settings = $display['settings'];
  $element = array();

  switch ($display['type']) {
    case 'field_yamaps_formatter_dynamic':
      $element['controls'] = array(
        '#title' => t('Show controls'),
        '#type' => 'checkbox',
        '#default_value' => $settings['controls'],
      );
      $element['traffic'] = array(
        '#title' => t('Show traffic'),
        '#type' => 'checkbox',
        '#default_value' => $settings['traffic'],
      );
      $element['width'] = array(
        '#title' => t('Map width'),
        '#field_suffix' => ' ' . t('in pixels or in percentage'),
        '#type' => 'textfield',
        '#default_value' => $settings['width'],
        '#size' => 5,
        '#element_validate' => array('yamaps_field_validate_pixels_percentage'),
        '#required' => TRUE,
      );
      $element['height'] = array(
        '#title' => t('Map height'),
        '#field_suffix' => ' ' . t('in pixels or in percentage'),
        '#type' => 'textfield',
        '#default_value' => $settings['height'],
        '#size' => 5,
        '#element_validate' => array('yamaps_field_validate_pixels_percentage'),
        '#required' => TRUE,
      );

      $element['behaviors'] = array(
        '#title' => t('Mouse event settings'),
        '#type' => 'checkboxes',
        '#options' => yamaps_get_behaviors_list(),
        '#default_value' => $settings['behaviors'],
      );

      $element['yamaps_display_options'] = array(
        '#type' => 'fieldset',
        '#title' => t('Display options'),
        '#tree' => TRUE,
      );

      $element['yamaps_display_options']['display_type'] = array(
        '#type' => 'radios',
        '#title' => t('How display map for admins'),
        '#options' => array(
          'map' => t('Map'),
          'map_button' => t('Map opened by button click'),
          //'map_modal' => t('Map opened in modal window'),
        ),
        '#default_value' => $settings['yamaps_display_options']['display_type'],
        '#required' => FALSE,
        '#description' => t('Type of map displaying.'),
      );

      $element['yamaps_display_options']['open_button_text'] = array(
        '#type' => 'textfield',
        '#title' => t('Button text'),
        '#default_value' => (isset($settings['yamaps_display_options']['open_button_text'])) ? $settings['yamaps_display_options']['open_button_text'] : 'Open map',
        '#required' => FALSE,
        '#description' => t('Text of button that opens map.'),
      );

      break;

    case 'field_yamaps_formatter_static':
      $element['s_traffic'] = array(
        '#title' => t('Show traffic'),
        '#type' => 'checkbox',
        '#default_value' => $settings['s_traffic'],
      );
      $element['s_width'] = array(

        '#title' => t('Map width'),
        '#field_suffix' => ' ' . t('pixels'),
        '#type' => 'textfield',
        '#default_value' => $settings['s_width'],
        '#size' => 5,
        '#element_validate' => array('element_validate_integer_positive'),
        '#required' => TRUE,
      );
      $element['s_height'] = array(
        '#title' => t('Map height'),
        '#field_suffix' => ' ' . t('pixels'),
        '#type' => 'textfield',
        '#default_value' => $settings['s_height'],
        '#size' => 5,
        '#element_validate' => array('element_validate_integer_positive'),
        '#required' => TRUE,
      );
      break;
    case 'field_yamaps_formatter_modal_window':
      $element['original_field_type'] = array(
        '#title' => t('Original field type'),
        '#type' => 'radios',
        '#default_value' => $settings['s_height'],
        '#options' => array(
          'button' => t('Open button'),
          'field_yamaps_formatter_static' => t('Static image'),
        ),
        '#required' => TRUE,

      );

      $element['modal_window_field_type'] = array(
        '#title' => t('Modal window field type'),
        '#type' => 'radios',
        '#default_value' => $settings['s_height'],
        '#options' => array(
          'field_yamaps_formatter_dynamic' => t('Dynamic image'),
          'field_yamaps_formatter_static' => t('Static image'),

        ),
        '#required' => TRUE,

      );
      break;
  }
  return $element;
}

/**
 * Implements hook_field_formatter_settings_summary().
 */
function yamaps_field_formatter_settings_summary($field, $instance, $view_mode) {
  $display = $instance['display'][$view_mode];
  $settings = $display['settings'];
  $summary = array();

  switch ($display['type']) {
    case 'field_yamaps_formatter_dynamic':
      $summary[] = t('Show controls: @controls', array('@controls' => $settings['controls'] ? t('Yes') : t('No')));
      $summary[] = t('Show traffic: @traffic', array('@traffic' => $settings['traffic'] ? t('Yes') : t('No')));
      $summary[] = t('Map size: @width x @height', array(
        '@width' => $settings['width'],
        '@height' => $settings['height']
      ));
      if (count($display['settings']['behaviors']) > 0) {
        $behaviors_list = yamaps_get_behaviors_list();
        $active_behaviors = array_values(array_filter($display['settings']['behaviors']));
        $prepared_behaviors_list = array();
        foreach ($active_behaviors as $key => $val) {
          $prepared_behaviors_list[] = $behaviors_list[$val];
        }
        $summary[] = t('Enabled mouse event: !list', array('!list' => implode(', ', $prepared_behaviors_list)));
      }
      else {
        $summary[] = t('All mouse event are disabled.');
      }
      $summary[] = t('Display type: @type', array('@type' => $settings['yamaps_display_options']['display_type']));
      break;

    case 'field_yamaps_formatter_static':
      $summary[] = t('Show traffic: @traffic', array('@traffic' => $settings['s_traffic'] ? t('Yes') : t('No')));
      $summary[] = t('Map size: @width x @height', array(
        '@width' => $settings['s_width'] . 'px',
        '@height' => $settings['s_height'] . 'px'
      ));
      break;
    case 'field_yamaps_formatter_modal_window':

      $summary[] = t('Original field settings: @field_settings', array('@field_settings' => ''));
      $summary[] = t('Modal window settings: @field_settings', array('@field_settings' => ''));
      break;
  }

  return implode('<br />', $summary);
}

/**
 * Implements hook_field_formatter_view().
 */
function yamaps_field_formatter_view($entity_type, $entity, $field, $instance, $langcode, $items, $display) {

  $element = array();
  $settings = $display['settings'];

  switch ($display['type']) {

    case 'field_yamaps_formatter_dynamic':
      $maps = array();
      foreach ($items as $delta => $item) {

        // Map id.
        $id = drupal_html_id(implode('-', array(
          'ymap',
          $field['field_name'],
          $delta
        )));

        // Unique map button id.
        $open_button_id = drupal_html_id(implode('-', array(
          $id,
          'open_button'
        )));

        // Coordinates of map center.
        $coords = drupal_json_decode($item['coords']);

        // Map initialization parameters.
        $maps[$id] = array(
          'init' => array(
            'center' => $coords['center'],
            'zoom' => $coords['zoom'],
            'type' => $item['type'] ? $item['type'] : 'yandex#map',
            'behaviors' => array_values(array_filter($settings['behaviors'])),
          ),
          'display_options' => array(
            'display_type' => $settings['yamaps_display_options']['display_type'],
            'open_button_text' => $settings['yamaps_display_options']['open_button_text'],
            'open_button_id' => $open_button_id,
          ),
          'controls' => $settings['controls'],
          'traffic' => $settings['traffic'],
          'placemarks' => drupal_json_decode($item['placemarks']),
          'lines' => drupal_json_decode($item['lines']),
          'polygons' => drupal_json_decode($item['polygons']),
          'routes' => drupal_json_decode($item['routes']),
          'edit' => FALSE,
        );

        // Return map container div.

        $element[$delta]['map_container'] = array(
          '#type' => 'html_tag',
          '#tag' => 'div',
          '#attributes' => array(
            'id' => $id,
            'style' => 'width:' . $settings['width'] . ';height:' . $settings['height'],
          ),
          '#value' => '',
        );

        if (isset($settings['yamaps_display_options']['display_type']) && $settings['yamaps_display_options']['display_type'] == 'map_button') {
          $element[$delta]['open_button_text'] = array(
            '#type' => 'html_tag',
            '#tag' => 'div',
            '#value' => t((isset($settings['yamaps_display_options']['open_button_text'])) ? $settings['yamaps_display_options']['open_button_text'] : 'Open button'),
            '#attributes' => array(
              'id' => $open_button_id,
              'class' => 'open_yamap_button',
              'mapId' => $id,
            ),
          );
        }
      }

      // Adding map to js and load library.
      drupal_add_js(array('yamaps' => $maps), 'setting');
      drupal_add_library('yamaps', 'yamaps.full');
      break;

    case 'field_yamaps_formatter_static':
      foreach ($items as $delta => $item) {
        // Map id and parameters.
        $id = drupal_html_id(implode('-', array(
          'ymap',
          $field['field_name'],
          $delta
        )));
        $coords = drupal_json_decode($item['coords']);
        $params = array();
        $url = 'http://static-maps.yandex.ru/1.x/';
        $params['ll'] = end($coords['center']) . ',' . reset($coords['center']);
        $params['z'] = $coords['zoom'];
        $params['size'] = $settings['s_width'] . ',' . $settings['s_height'];

        // 2.x to 1.x types.
        $map_types = array(
          'yandex#map' => 'map',
          'yandex#satellite' => 'sat',
          'yandex#hybrid' => 'sat,skl',
          'yandex#publicMap' => 'pmap',
          'yandex#publicMapHybrid' => 'sat,pskl',
        );
        $params['l'] = $map_types[$item['type'] ? $item['type'] : 'yandex#map'];
        if ($settings['s_traffic']) {
          $params['l'] .= ',trf';
        }

        // 2.x to 1.x colors.
        $colors21 = array(
          'blue' => 'bl',
          'lightblue' => 'lb',
          'night' => 'nt',
          'darkblue' => 'db',
          'green' => 'gn',
          'white' => 'wt',
          'red' => 'rd',
          'orange' => 'or',
          'darkorange' => 'do',
          'yellow' => 'yw',
          'violet' => 'vv',
          'pink' => 'pn',
        );
        // 2.x to hex colors.
        $colors = array(
          'blue' => '006cff',
          'lightblue' => '66c7ff',
          'night' => '004056',
          'darkblue' => '00339a',
          'green' => '33cc00',
          'white' => 'ffffff',
          'red' => 'ff0000',
          'orange' => 'ffb400',
          'darkorange' => 'ff6600',
          'yellow' => 'ffea00',
          'violet' => 'b832fd',
          'pink' => 'fd32fb',
        );

        // Placemarks.
        if ($item['placemarks']) {
          $pt = array();
          foreach (drupal_json_decode($item['placemarks']) as $placemark) {
            $pm = end($placemark['coords']) . ',' . reset($placemark['coords']) . ',';
            $pm .= 'pm2';
            $pm .= $colors21[$placemark['params']['color']];
            $pm .= 'm';
            $pt[] = $pm;
          }
          $params['pt'] = implode('~', $pt);
        }

        // Lines and polygons.
        $pl = array();
        if ($item['lines']) {
          foreach (drupal_json_decode($item['lines']) as $line) {
            $opts = $line['params'];
            $pm = 'c:' . $colors[$opts['strokeColor']] . dechex(255 * $opts['opacity']) . ',';
            $pm .= 'w:' . $opts['strokeWidth'] . ',';
            $c = array();
            foreach ($line['coords'] as $coords) {
              $c[] = end($coords);
              $c[] = reset($coords);
            }
            $pm .= implode(',', $c);
            $pl[] = $pm;
          }
        }
        if ($item['polygons']) {
          foreach (drupal_json_decode($item['polygons']) as $polygon) {
            $opts = $polygon['params'];
            $opa = dechex(255 * $opts['opacity']);
            $pm = 'c:' . $colors[$opts['strokeColor']] . $opa . ',';
            $pm .= 'f:' . $colors[$opts['fillColor']] . $opa . ',';
            $pm .= 'w:' . $opts['strokeWidth'] . ',';
            $c = array();
            foreach ($polygon['coords'] as $coords_array) {
              foreach ($coords_array as $coords) {
                $c[] = end($coords);
                $c[] = reset($coords);
              }
            }
            $pm .= implode(',', $c);
            $pl[] = $pm;
          }
        }
        if (!empty($pl)) {
          $params['pl'] = implode('~', $pl);
        }

        // Return map container div with image.
        $element[$delta] = array(
          '#type' => 'html_tag',
          '#tag' => 'div',
          '#attributes' => array(
            'id' => $id,
            'style' => 'width:' . $settings['s_width'] . 'px;height:' . $settings['s_height'] . 'px',
          ),
          '#value' => theme('image', array(
            'path' => url($url, array('query' => $params, 'external' => TRUE)),
            'width' => $settings['s_width'],
            'height' => $settings['s_height'],
            'title' => t('Yandex.Map'),
          )),
        );
      }
      break;
    case 'field_yamaps_formatter_modal_window':
      //@todo create logic for combined type.
      break;
  }
  return $element;
}

/**
 * Returning behaviors list.
 */
function yamaps_get_behaviors_list() {
  return array(
    'clickZoom' => t('Click Zoom'),
    'scrollZoom' => t('Scroll Zoom'),
    'dblClickZoom' => t('Double click zoom'),
    'drag' => t('Click and drag'),
    'multiTouch' => t('Multi Touch support'),
    'ruler' => t('Ruler'),
    'rightMouseButtonMagnifier' => t('Right mouse button magnifier'),
  );
}
