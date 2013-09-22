<?php

/**
 * @file
 * Plugin to handle the 'page_logo' content type which allows the
 * logo of the site to be embedded into a panel.
 */

/**
 * Plugins are described by creating a $plugin array which will be used
 * by the system that includes this file.
 */
$plugin = array(
  'title' => t('Site logo'),
  'single' => TRUE,
  'icon' => 'icon_page.png',
  'description' => t('Add the logo trail as content.'),
  'category' => t('Page elements'),
  'render last' => TRUE,
);

/**
 * Output function for the 'page_logo' content type.
 *
 * Outputs the logo for the current page.
 */
function ctools_page_logo_content_type_render($subtype, $conf, $panel_args) {
  $logo = theme_get_setting('logo');
  $block = new stdClass();

  if (!empty($logo)) {
    $image = '<img src="' . $logo . '" alt="' . t('�������') . '" />';
    $block->content = l($image, '', array('html' => TRUE, 'attributes' => array('rel' => 'home', 'id' => 'logo', 'title' => t('�������'))));
  }

  return $block;
}