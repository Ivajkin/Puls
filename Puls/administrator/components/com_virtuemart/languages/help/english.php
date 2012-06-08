<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: russian.php 1071 2008-02-03 08:42:28Z alex_rus $
* @package VirtueMart
* @subpackage languages
* @copyright Copyright (C) 2004-2007 soeren - All rights reserved.
* @translator soeren
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
* http://www.alex-rus.com
* http://www.virtuemart.ru
* http://www.joomlaforum.ru
*/

global $VM_LANG;
$langvars = array (
	'CHARSET' => 'utf-8',
	'VM_HELP_YOURVERSION' => 'Ваша версия {product}',
	'VM_HELP_ABOUT' => '<span style="font-weight: bold;">
		VirtueMart</span> полностью законченное бесплатное программное обеспечение для системы Mambo и Joomla!. 
		Данное программное обеспечение состоит из компонента и более 8 модулей и мамботов/плагинов.
		В основе VirtueMart лежит скрипт магазина "phpShop" (Авторы: Edikon Corp. & сообщество <a href="http://www.virtuemart.org/" target="_blank">phpShop</a>).',
	'VM_HELP_LICENSE_DESC' => 'VirtueMart распространяется по <a href="{licenseurl}" target="_blank">{licensename} лицензии</a>.',
	'VM_HELP_TEAM' => 'Данное программное обеспечение разрабатывается небольшой командой разработчиков в свободное время.',
	'VM_HELP_PROJECTLEADER' => 'Лидер проекта',
	'VM_HELP_HOMEPAGE' => 'Домашняя страница',
	'VM_HELP_DONATION_DESC' => 'Пожалуйста, поддержите проект, перечислив небольшую сумму в адрес проекта VirtueMart. Это поможет поддерживать проект и разрабатывать новые функции.',
	'VM_HELP_DONATION_BUTTON_ALT' => 'Сделайте перевод, используя PayPal - это быстро, бесплатно и безопасно!'
); $VM_LANG->initModule( 'help', $langvars );
?>