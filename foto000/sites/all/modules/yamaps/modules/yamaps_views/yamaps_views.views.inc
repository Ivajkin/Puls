<?php

/**
 * Implementation of hook_views_plugins().
 */
function yamaps_views_views_plugins() {
  return array(
    'module' => 'yamaps_views',
    'style' => array(
      'default_map' => array(
        'title' => t('Yandex Maps'),
        'help' => t('Display Yandex.Map'),
        'handler' => 'yamaps_views_plugin_style_default_map',
        'path' => drupal_get_path('module', 'yamaps_views') . '/handlers',
        'uses options' => TRUE,
        'uses row plugin' => TRUE,
        'uses fields' => TRUE,
        'uses grouping' => FALSE,
        'even empty' => TRUE,
        'type' => 'normal',
      ),
    ),
  );
}