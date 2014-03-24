<?php

// Plugin definition
$plugin = array(
  'title' => t('Two column_2'),
  'category' => t('Columns: 2'),
  'icon' => 'twocol.png',
  'theme' => 'panels_twocol',
  'css' => 'twocol.css',
  'regions' => array(
    'left' => t('Left side'),
    'right' => t('Right side')
  ),
);
