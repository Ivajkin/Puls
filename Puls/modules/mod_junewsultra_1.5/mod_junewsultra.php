<?php
/**
* @package Joomla! 1.5.x
* @author 2008-2010 (c)  Denys Nosov (aka Dutch)
* @author web-site: www.joomla-ua.org
* @copyright This module is licensed under a Creative Commons Attribution-Noncommercial-No Derivative Works 3.0 License.
**/

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');

$list = modJUNewsUltraHelper::getList($params);

if (!count($list)) {
	return;
}

$template = $params->def('template', 'default.php');
$template = str_replace('.php', '', $template);

//READ FULL
$all_in = $params->def('all_in', '0') ;
$link_all_in = trim( $params->get( 'link_all_in', '' ) );
$text_all_in = trim( $params->get( 'text_all_in', 'All in section/category...' ) );
$class_all_in = trim( $params->get( 'class_all_in', 'small' ) );

$layoutpath = JModuleHelper::getLayoutPath('mod_junewsultra', $template );

if( file_exists($layoutpath) ) {
    require($layoutpath);
} else {
    echo JText::_("<strong>Template <span style=\"color: green;\">$template</span> do is not found!</strong><br />Please, upload new template to <em>modules/mod_breakingnews/tmpl</em> folder or select other template from back-end!");
}

if ($all_in==1) {
    echo '<a class="'.$class_all_in.'" href="'.$link_all_in.'">'.$text_all_in.'</a>';
}

if( $params->def('copy') ) {
    echo '<span style="clear:both;text-align:right;display:block;line-height:10px;width: 100%;font-size:9px;"><a href="http://www.joomla-ua.org" style="color:#ccc;text-decoration:none;" title="Joomla! Україна" target="_blank" >Joomla! Україна</a></span>';
}
?>