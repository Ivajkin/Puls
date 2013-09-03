<?php

/**
 * @file
 * Demonstration site installation profile.
 */

/**
 * Implements hook_install_tasks_alter().
 */
function demo_profile_install_tasks_alter(&$tasks, &$install_state) {
  // Break references.
  $install_bootstrap_full = (array) $tasks['install_bootstrap_full'];
  $install_finished = (array) $tasks['install_finished'];

  // Remove the tasks from the list and execution.
  // We cannot implement hook_install_tasks(), because we want to intercept the
  // installation process before it even begins (except database settings).
  unset(
    $tasks['install_system_module'],
    $tasks['install_bootstrap_full'],
    $tasks['install_profile_modules'],
    $tasks['install_import_locales'],
    $tasks['install_configure_form'],
    $tasks['install_import_locales_remaining'],
    $tasks['install_finished']
  );

  // Add Demonstration site profile tasks.
  // @todo Move dump path setting into separate step; store value in
  //   $install_state.
  $tasks['demo_profile_form'] = array(
    'display_name' => st('Choose snapshot'),
    'type' => 'form',
    'run' => INSTALL_TASK_RUN_IF_NOT_COMPLETED,
  );
  // Do a full bootstrap and display final message.
  $tasks['install_bootstrap_full'] = $install_bootstrap_full;
  $tasks['install_finished'] = $install_finished;
}

/**
 * Implementation of hook_form_alter().
 */
function demo_profile_form($form, &$form_state, &$install_state) {
  drupal_set_title(st('Choose snapshot'));

  // @todo
  drupal_static_reset('file_get_stream_wrappers');
  $GLOBALS['conf']['file_private_path'] = 'sites/default/private/files';

  $form['file_private_path'] = array(
    '#type' => 'textfield',
    '#title' => t('Private file system path'),
    '#default_value' => variable_get('file_private_path', ''),
    '#maxlength' => 255,
    '#description' => t('A local file system path where private files will be stored. This directory must exist and be writable by Drupal. This directory should not be accessible over the web.'),
    '#after_build' => array('system_check_directory'),
  );
  foreach(file_get_stream_wrappers(STREAM_WRAPPERS_WRITE_VISIBLE) as $scheme => $info) {
    $options[$scheme] = check_plain($info['description']);
  }
  $form['file_default_scheme'] = array(
    '#type' => 'radios',
    '#title' => t('Default download method'),
    '#default_value' => isset($options['private']) ? 'private' : key($options),
    '#options' => $options,
    // @todo
    '#value' => 'private',
    '#disabled' => TRUE,
  );

  $form['demo'] = array(
    '#type' => 'fieldset',
    '#title' => t('Demonstration site settings'),
  );
  $form['demo']['demo_dump_path'] = array(
    '#type' => 'textfield',
    '#title' => t('Dump path'),
    '#default_value' => variable_get('demo_dump_path', 'demo'),
    '#description' => t('The path where the database dumps can be found.'),
    // @todo Not implemented yet (move into separate step).
    '#disabled' => TRUE,
  );
  // @todo Validate the given path to dumps.
  // $form['#validate'][] = 'demo_admin_settings_validate';

  $options['#attributes']['class'][] = 'demo-snapshots-widget';
  $form['dump'] = array(
    '#type' => 'fieldset',
    '#title' => t('Snapshot'),
    '#description' => t('Which snapshot would you like to restore?'),
  );
  // Display the available database dumps.
  module_load_include('inc', 'demo', 'demo.admin');
  $form['dump'] += demo_profile_get_dumps();

  $form['submit'] = array('#type' => 'submit', '#value' => t('Restore'));
  return $form;
}

/**
 * Submit handler for the "install_configure" form.
 */
function demo_profile_form_submit($form, &$form_state) {
  // Restore the database dump and redirect to the homepage.
  drupal_load('module', 'demo');
  demo_reset($form_state['values']['filename'], TRUE);

  $form_state['redirect'] = '';

//  // The dump path is being changed.
//  variable_set('demo_dump_path', $form_state['values']['demo_dump_path']);
//
//  // Refresh the page so that the form refreshes.
//  header('Location: '. base_path() . 'install.php?locale='. $_GET['locale'] .'&profile=demo_profile');
}

/**
 * Half-bootstrap-aware fork of demo_get_dumps().
 *
 * @see demo_get_dumps()
 */
function demo_profile_get_dumps() {
  $fileconfig = demo_get_fileconfig();

  // Fetch list of available info files
  $files = file_scan_directory($fileconfig['dumppath'], '/\.info$/');

  foreach ($files as $file => $object) {
    $files[$file]->filemtime = filemtime($file);
    $files[$file]->filesize = filesize(substr($file, 0, -4) . 'sql');
  }

  // Sort snapshots by date (ascending file modification time)
  uasort($files, create_function('$a, $b', 'return ($a->filemtime < $b->filemtime);'));

  $options = array();
  // Forms API does not pass selected value of individual radio buttons,
  // so we manually insert an internal form value here.
  $options['filename'] = array(
    '#type' => 'value',
    '#required' => TRUE,
    '#title' => t('Snapshot'),
  );

  $options['#attributes']['class'][] = 'demo-snapshots-widget';
  $options['#attached']['js'][] = drupal_get_path('module', 'demo') . '/demo.admin.js';

  foreach ($files as $filename => $file) {
    // Build basic file info
    $files[$filename] = (array) $file;
    $info = demo_get_info($filename);

    // Convert file info for Forms API
    $option = array(
      '#type' => 'radio',
      '#name' => 'filename',
      // format_date() is not available during installation.
      '#title' => check_plain($info['filename']) . ' (' . date('Y-m-d H:i:s', $file->filemtime) . ', ' . format_size($file->filesize) . ')',
      '#description' => '',
      '#return_value' => $info['filename'],
      '#file' => $file,
      '#info' => $info,
    );
    if (!empty($info['description'])) {
      $option['#description'] .= '<p>' . $info['description'] . '</p>';
    }

    if (count($info['modules']) > 1) {
      // Remove required core modules and obvious modules from module list.
      $info['modules'] = array_diff($info['modules'], array('filter', 'node', 'system', 'user', 'demo'));
      // Sort module list alphabetically.
      sort($info['modules']);
      $option['#description'] .= t('Modules: ') . implode(', ', $info['modules']);
    }

    $options[$info['filename']] = $option;
  }

  return $options;
}

