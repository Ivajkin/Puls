<?php
/**
 * @file
 * yamaps_example_feature.features.field_base.inc
 */

/**
 * Implements hook_field_default_field_bases().
 */
function yamaps_example_feature_field_default_field_bases() {
  $field_bases = array();

  // Exported field_base: 'body'
  $field_bases['body'] = array(
    'active' => 1,
    'cardinality' => 1,
    'deleted' => 0,
    'entity_types' => array(
      0 => 'node',
    ),
    'field_name' => 'body',
    'foreign keys' => array(
      'format' => array(
        'columns' => array(
          'format' => 'format',
        ),
        'table' => 'filter_format',
      ),
    ),
    'indexes' => array(
      'format' => array(
        0 => 'format',
      ),
    ),
    'locked' => 0,
    'module' => 'text',
    'settings' => array(),
    'translatable' => 0,
    'type' => 'text_with_summary',
  );

  // Exported field_base: 'field_yandex_maps_example_field'
  $field_bases['field_yandex_maps_example_field'] = array(
    'active' => 1,
    'cardinality' => 1,
    'deleted' => 0,
    'entity_types' => array(),
    'field_name' => 'field_yandex_maps_example_field',
    'foreign keys' => array(),
    'indexes' => array(),
    'locked' => 0,
    'module' => 'yamaps',
    'settings' => array(
      'display_options' => array(
        'display_type' => 'map_button',
        'open_button_text' => 'Open map example text',
      ),
    ),
    'translatable' => 0,
    'type' => 'field_yamaps',
  );

  return $field_bases;
}
