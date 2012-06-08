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
	'PHPSHOP_CARRIER_LIST_LBL' => 'Список вариантов доставки',
	'PHPSHOP_RATE_LIST_LBL' => 'Список тарифов доставки',
	'PHPSHOP_CARRIER_LIST_NAME_LBL' => 'Название',
	'PHPSHOP_CARRIER_LIST_ORDER_LBL' => 'Порядок отображения',
	'PHPSHOP_CARRIER_FORM_LBL' => 'Добавить/изменить вариант доставки',
	'PHPSHOP_RATE_FORM_LBL' => 'Добавить/Изменить тариф доставки',
	'PHPSHOP_RATE_FORM_NAME' => 'Описание тарифа доставки',
	'PHPSHOP_RATE_FORM_CARRIER' => 'Вариант доставки',
	'PHPSHOP_RATE_FORM_COUNTRY' => 'Страна',
	'PHPSHOP_RATE_FORM_ZIP_START' => 'Начало диапазона почтовых индексов',
	'PHPSHOP_RATE_FORM_ZIP_END' => 'Конец диапазона почтовых индексов',
	'PHPSHOP_RATE_FORM_WEIGHT_START' => 'Минимальный вес',
	'PHPSHOP_RATE_FORM_WEIGHT_END' => 'Максимальный вес',
	'PHPSHOP_RATE_FORM_PACKAGE_FEE' => 'Стоимость упаковки',
	'PHPSHOP_RATE_FORM_CURRENCY' => 'Валюта',
	'PHPSHOP_RATE_FORM_LIST_ORDER' => 'Порядок отображения',
	'PHPSHOP_SHIPPING_RATE_LIST_CARRIER_LBL' => 'Вариант доставки',
	'PHPSHOP_SHIPPING_RATE_LIST_RATE_NAME' => 'Описание тарифа доставки',
	'PHPSHOP_SHIPPING_RATE_LIST_RATE_WSTART' => 'Вес от ...',
	'PHPSHOP_SHIPPING_RATE_LIST_RATE_WEND' => '... до',
	'PHPSHOP_CARRIER_FORM_NAME' => 'Компания-доставки',
	'PHPSHOP_CARRIER_FORM_LIST_ORDER' => 'Порядок отображения'
); $VM_LANG->initModule( 'shipping', $langvars );
?>