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
	'PHPSHOP_ADMIN_CFG_PRICES_INCLUDE_TAX' => 'Показать цены, включая налоги?',
	'PHPSHOP_ADMIN_CFG_PRICES_INCLUDE_TAX_EXPLAIN' => 'Включите эту опцию, чтобы показывать цены с учётом налога. Если опция выключена, то цены будут показаны без налога.',
	'PHPSHOP_SHOPPER_FORM_ADDRESS_LABEL' => 'Адрес',
	'PHPSHOP_SHOPPER_GROUP_LIST_LBL' => 'Группы покупателей',
	'PHPSHOP_SHOPPER_GROUP_LIST_NAME' => 'Название группы',
	'PHPSHOP_SHOPPER_GROUP_LIST_DESCRIPTION' => 'Описание группы',
	'PHPSHOP_SHOPPER_GROUP_FORM_LBL' => 'Добавить группу покупателей',
	'PHPSHOP_SHOPPER_GROUP_FORM_NAME' => 'Название группы',
	'PHPSHOP_SHOPPER_GROUP_FORM_DESC' => 'Описание группы',
	'PHPSHOP_SHOPPER_GROUP_FORM_DISCOUNT' => 'Скидка для группы по-умолчанию (в %)',
	'PHPSHOP_SHOPPER_GROUP_FORM_DISCOUNT_TIP' => 'Положительное значение Х означает: если товару не назначена цена для этой группы покупателей, то цена по-умолчанию уменьшается на Х%. Отрицательное значение имеет противоположный эффект.'
); $VM_LANG->initModule( 'shopper', $langvars );
?>