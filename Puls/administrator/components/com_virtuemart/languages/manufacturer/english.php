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
	'PHPSHOP_MANUFACTURER_LIST_LBL' => 'Список производителей',
	'PHPSHOP_MANUFACTURER_LIST_MANUFACTURER_NAME' => 'Название производителя',
	'PHPSHOP_MANUFACTURER_FORM_LBL' => 'Добавить информацию',
	'PHPSHOP_MANUFACTURER_FORM_CATEGORY' => 'Категория производителя',
	'PHPSHOP_MANUFACTURER_FORM_EMAIL' => 'E-mail',
	'PHPSHOP_MANUFACTURER_CAT_LIST_LBL' => 'Список категорий производителей',
	'PHPSHOP_MANUFACTURER_CAT_NAME' => 'Название категории',
	'PHPSHOP_MANUFACTURER_CAT_DESCRIPTION' => 'Описание категории',
	'PHPSHOP_MANUFACTURER_CAT_MANUFACTURERS' => 'Производители',
	'PHPSHOP_MANUFACTURER_CAT_FORM_LBL' => 'Форма категории производителей',
	'PHPSHOP_MANUFACTURER_CAT_FORM_INFO_LBL' => 'Информация о категории',
	'PHPSHOP_MANUFACTURER_CAT_FORM_NAME' => 'Название категории',
	'PHPSHOP_MANUFACTURER_CAT_FORM_DESCRIPTION' => 'Описание категории'
); $VM_LANG->initModule( 'manufacturer', $langvars );
?>