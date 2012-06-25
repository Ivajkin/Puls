<?php

/**
* Gavick News Pro GK1 - main file
* @package Joomla!
* @Copyright (C) 2009 Gavick.com
* @ All rights reserved
* @ Joomla! is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version $Revision: 1.0.0 $
**/

/**
	access restriction
**/
defined('_JEXEC') or die('Restricted access');

/**
	Loading helper class
**/

// 
require_once (dirname(__FILE__).DS.'helper.php');
// 
if (!class_exists('GK_Date')) 
{
	require_once (dirname(__FILE__).DS.'gk_classes'.DS.'date.class.php');
}
//
if (!class_exists('GK_JoomlaNews')) 
{
	require_once (dirname(__FILE__).DS.'gk_classes'.DS.'joomla.news.class.php');
}
// 
if(!class_exists('GK1NewsProLayoutParts'))
{
	require_once(JModuleHelper::getLayoutPath('mod_news_pro_gk1', 'layout.parts'));	
}
//
$helper =& new GK1NewsProHelper();
//
$helper->init();
$helper->validateVariables($params);
$helper->getDatas();
$helper->renderLayout();

?>