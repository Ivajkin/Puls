<?php
/**
 * @file
 * yamaps_example_feature.features.inc
 */

/**
 * Implements hook_views_api().
 */
function yamaps_example_feature_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_node_info().
 */
function yamaps_example_feature_node_info() {
  $items = array(
    'yandex_maps_example' => array(
      'name' => t('Yandex.Maps example'),
      'base' => 'node_content',
      'description' => '',
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  return $items;
}
