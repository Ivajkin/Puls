<?php

class yamaps_views_plugin_style_default_map extends views_plugin_style {
  const placemark_title = 'balloonContentHeader';
  const placemark_ballon = 'balloonContentBody';

  /**
   * Default settings for map.
   */
  function option_definition() {
    $options = parent::option_definition();
    $options['controls'] = array('default' => TRUE);
    $options['traffic'] = array('default' => FALSE);
    $options['width'] = array('default' => '100%');
    $options['height'] = array('default' => '400px');
    $options['behaviors'] = array(
      'default' => array(
        'clickZoom',
        'scrollZoom',
        'dblClickZoom',
        'drag',
        'multiTouch',
        'rightMouseButtonMagnifier',
      ),
    );

    $options['yamaps_display_options'] = array(
      'display_type' => 'map',
      'open_button_type' => 'Open map',
    );

    $options['yandex_map_field'] = array('default' => '');
    $options['baloon_title'] = array('default' => '<default>');
    $options['baloon_body'] = array('default' => '');
    $options['map_type'] = array('default' => 'yandex#map');

    $options['map_center'] = array('default' => '');
    $options['map_grouping_cat'] = array('default' => 'standart');

    return $options;
  }

  /**
   * Views Yandex.Maps settings form.
   */
  function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);
    $fields = $this->get_fields();
    $yandex_fields = $this->get_yandex_maps_fields();

    $form['controls'] = array(
      '#title' => t('Controls'),
      '#type' => 'checkbox',
      '#default_value' => $this->options['controls'],
    );
    $form['traffic'] = array(
      '#title' => t('Show traffic'),
      '#type' => 'checkbox',
      '#default_value' => $this->options['traffic'],
    );
    $form['width'] = array(
      '#title' => t('Map width'),
      '#field_suffix' => ' ' . t('in pixels or in percentage'),
      '#type' => 'textfield',
      '#default_value' => $this->options['width'],
      '#size' => 5,
      '#element_validate' => array('yamaps_field_validate_pixels_percentage'),
      '#required' => TRUE,
    );
    $form['height'] = array(
      '#title' => t('Map height'),
      '#field_suffix' => ' ' . t('in pixels or in percentage'),
      '#type' => 'textfield',
      '#default_value' => $this->options['height'],
      '#size' => 5,
      '#element_validate' => array('yamaps_field_validate_pixels_percentage'),
      '#required' => TRUE,
    );
  /**
   *  $form['map_center'] = array(
      '#title' => t('Map center'),
      '#description' => t(''),
      '#type' => 'textfield',
      '#default_value' => $this->options['map_center'],
      '#size' => 5,
      '#required' => TRUE,
    );
   */
    $form['behaviors'] = array(
      '#title' => t('Mouse event settings'),
      '#type' => 'checkboxes',
      '#options' => yamaps_get_behaviors_list(),
      '#default_value' => $this->options['behaviors'],
    );

    $form['yamaps_display_options'] = array(
      '#type' => 'fieldset',
      '#title' => t('Display options'),
      '#tree' => TRUE,
    );
    $form['yamaps_display_options']['display_type'] = array(
      '#type' => 'radios',
      '#title' => t('How display map for admins'),
      '#options' => array(
        'map' => t('Map'),
        'map_button' => t('Map opened by button click'),
        //'map_modal' => t('Map opened in modal window'),
      ),
      '#default_value' => $this->options['yamaps_display_options']['display_type'],
      '#required' => FALSE,
      '#description' => t('Type of map displaying.'),
    );
    $form['yamaps_display_options']['open_button_text'] = array(
      '#type' => 'textfield',
      '#title' => t('Button text'),
      '#default_value' => $this->options['yamaps_display_options']['open_button_text'],
      '#required' => FALSE,
      '#description' => t('Text of button that opens map.'),
    );

    /*  $form['baloon_title'] = array(
        '#title' => t('Baloon title'),
        '#type' => 'select',
        '#options' => $fields,
        '#default_value' => $this->options['baloon_title'],
      );

      $form['baloon_body'] = array(
        '#title' => t('Balloon body Field'),
        '#type' => 'select',
        '#multiple' => TRUE,
        '#options' => $fields,
        '#default_value' => $this->options['baloon_body'],
      );

    */

    $form['map_type'] = array(
      '#title' => t('Yandex Map Field'),
      '#type' => 'select',
      '#options' => array('map_type'),
      '#default_value' => $this->options['map_type'],
    );

    $form['yandex_map_field'] = array(
      '#title' => t('Yandex Map Field'),
      '#description' => t('Choose Yandex.Maps field. Add if views fields this field for the first.'),
      '#type' => 'select',
      '#options' => $yandex_fields,
      '#required' => TRUE,
      '#default_value' => $this->options['yandex_map_field'],
    );

    $form['map_grouping_cat'] = array(
      '#title' => t('Yandex group type'),
      '#type' => 'select',
      '#description' => t('Standart'),
      '#options' => array('standart' => t('standart'),),
      '#default_value' => $this->options['map_grouping_cat'],
    );


  }

  /**
   * Preparing body.
   */
  function prepare_body($body_array) {
    $output = '<div class="balloon-inner">';
    foreach ($body_array as $key => $val) {
      $output .= '<span class="' . $key . '">' . $val . '</span>';
    }
    $output .= '</div>';
    return $output;
  }

  function uses_fields() {
    $fields = parent::uses_fields();
    $yandexmap_field = '';
    if (isset($this->view->field)) {
      foreach ($this->view->field as $field_name => $field_handler) {
        if (isset($field_handler->field_info) && $field_handler->field_info['type'] == 'field_yamaps') {
          $yandexmap_field = $field_name;
          break;
        }
      }
    }
    // Excluded field.
    if (isset($this->view->field[$yandexmap_field])) {
      $this->view->field[$yandexmap_field]->options['exclude'] = TRUE;
    }
    return $fields;
  }
/*
  function render_fields($result) {
    // @todo recheck
   = parent::render_fields($result);
  }
*/
  /**
   * Preparing data for render.
   */
  function pre_render($result) {

  }

  /**
   * Rendering data.
   */
  function render() {
    if (isset($this->view->live_preview) && $this->view->live_preview) {
      return t('Selected style are not compatible with live preview.');
    }

    //@todo remove.
    $yandexmap_field = '';
    foreach ($this->view->field as $field_name => $field_handler) {
      if (isset($field_handler->field_info) && $field_handler->field_info['type'] == 'field_yamaps') {
        $yandexmap_field = $field_name;
        break;
      }
    }

    // No yandex maps field error message.
    if (!$yandexmap_field) {
      return t('Add Yandex.Maps field to fields list.');
    }

    // Excluded field.
    $this->view->field[$yandexmap_field]->options['exclude'] = TRUE;
    $this->render_fields($this->view->result);

    $objects = array();
    $map_coords = array('lattitude' => array(), 'longitude' => array());
    foreach ($this->view->result as $row_index => $row) {

      $prepared_placemarks = array();
      $yandexmap_field_name = 'field_' . $this->options['yandex_map_field'];

      foreach ($row->$yandexmap_field_name as $placemark) {
        $maps_coords_item = drupal_json_decode($placemark['raw']['coords']);
        if (count($maps_coords_item['center']) > 0) {
          $map_coords['lattitude'][] = $maps_coords_item['center'][0];
          $map_coords['longitude'][] = $maps_coords_item['center'][1];
        }

        $prepared_placemarks += drupal_json_decode($placemark['raw']['placemarks']);
      }
      $marker_title = '';


      if ($this->options['baloon_title']) {
        if ($this->options['baloon_title'] !== '<default>') {
          $marker_title = $this->get_field($row_index, $this->options['baloon_title']);
          $marker_title = strip_tags($marker_title);
          $marker_title = decode_entities($marker_title);
          foreach ($prepared_placemarks as &$placemark) {
            $placemark['params'][$this::placemark_title] = $marker_title;
          }
        }
      }

      if ($this->options['baloon_body']) {
        foreach ($this->options['baloon_body'] as $bkey => $bval) {
          $ballon_body[] = $this->get_field($row_index, $bval);
        }
        $ballon_body = $this->prepare_body($ballon_body);

      }

      $this->view->row_index = $row_index;
      $marker_content = $this->row_plugin->render($row);

      unset($ballon_body);
      foreach ($prepared_placemarks as $placemark) {
        $objects[] = $placemark;
      }
    }
    unset($this->view->row_index);

    // Map information.

    // Unique map id.
    $id = drupal_html_id(implode('-', array(
      $this->plugin_name,
      $this->view->name,
      $this->view->current_display
    )));
    $open_button_id = drupal_html_id(implode('-', array($id, 'open_button')));

    // Map initialization parameters.
    $map = array(
      'init' => array(
        'center' => array(53.9182775224, 27.5093221557),
        'type' => 'yandex#map',
        'behaviors' => array_values(array_filter($this->options['behaviors'])),
      ),
      'display_options' => array(
        'display_type' => (isset($this->options['yamaps_display_options']['display_type'])) ? $this->options['yamaps_display_options']['display_type'] : 'map',
        'open_button_text' => t((isset($this->options['yamaps_display_options']['open_button_text'])) ? $this->options['yamaps_display_options']['open_button_text'] : 'Open map'),
        'open_button_id' => $open_button_id,
      ),
      'controls' => $this->options['controls'],
      'traffic' => $this->options['traffic'],
      'placemarks' => $objects,
      'lines' => NULL,
      'polygons' => NULL,
      'routes' => NULL,
      'edit' => FALSE,
    );
    if (!empty($this->options['map_center'])) {
      $map['init']['center_geocode'] = $this->options['map_center'];
    }
    // Adding map to js.
    drupal_add_js(array('yamaps' => array($id => $map)), 'setting');

    // Load library.
    drupal_add_library('yamaps', 'yamaps.full');
    $views_output = array();
    $views_output[] = array(
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => array(
        'id' => $id,
        'style' => 'width: 100%;height:400px',
      ),
      '#value' => '',
    );

    if (isset($this->options['yamaps_display_options']['display_type']) && $this->options['yamaps_display_options']['display_type'] == 'map_button') {
      $views_output['open_map_button'] = array(
        '#type' => 'html_tag',
        '#tag' => 'div',
        '#value' => t((isset($this->options['yamaps_display_options']['open_button_text'])) ? $this->options['yamaps_display_options']['open_button_text'] : 'Open map'),
        '#attributes' => array(
          'id' => $open_button_id,
          'class' => 'open_yamap_button',
          'mapId' => $id,
        ),
      );
    }

    return $views_output;
  }

  /**
   * Getting fields.
   *
   * @return array
   */
  function get_fields() {
    $field_names = array(
      '' => t('<none>'),
      '<default>' => t('Default baloon value')
    );
    $fields = $this->display->handler->get_handlers('field');

    foreach ($fields as $id => $handler) {
      if ($handler->field_info['module'] !== 'yamaps') {
        $field_names[$id] = $handler->ui_name(FALSE);
      }
    }
    return $field_names;
  }

  /**
   * Getting yandex maps fields.
   * @return array
   */
  function get_yandex_maps_fields() {
    $field_names = array('' => t('<none>'),);
    $fields = $this->display->handler->get_handlers('field');
    foreach ($fields as $id => $handler) {
      if ($handler->field_info['module'] == 'yamaps') {
        $field_names[$id] = $handler->ui_name(FALSE);
      }
    }
    return $field_names;
  }
}
