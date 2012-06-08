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
	'PHPSHOP_VENDOR_LIST_LBL' => 'Список продавцов',
	'PHPSHOP_VENDOR_LIST_ADMIN' => 'Администрирование',
	'PHPSHOP_VENDOR_FORM_LBL' => 'Добавить информацию',
	'PHPSHOP_VENDOR_FORM_CONTACT_LBL' => 'Контактная информация',
	'PHPSHOP_VENDOR_FORM_FULL_IMAGE' => 'Логотип',
	'PHPSHOP_VENDOR_FORM_UPLOAD' => 'Загрузить логотип',
	'PHPSHOP_VENDOR_FORM_STORE_NAME' => 'Название магазина',
	'PHPSHOP_VENDOR_FORM_COMPANY_NAME' => 'Название компании',
	'PHPSHOP_VENDOR_FORM_ADDRESS_1' => 'Адрес 1',
	'PHPSHOP_VENDOR_FORM_ADDRESS_2' => 'Адрес 2',
	'PHPSHOP_VENDOR_FORM_CITY' => 'Город',
	'PHPSHOP_VENDOR_FORM_STATE' => 'Регион',
	'PHPSHOP_VENDOR_FORM_COUNTRY' => 'Страна',
	'PHPSHOP_VENDOR_FORM_ZIP' => 'Индекс',
	'PHPSHOP_VENDOR_FORM_PHONE' => 'Телефон',
	'PHPSHOP_VENDOR_FORM_CURRENCY' => 'Валюта',
	'PHPSHOP_VENDOR_FORM_CATEGORY' => 'Категория продавца',
	'PHPSHOP_VENDOR_FORM_LAST_NAME' => 'Фамилия',
	'PHPSHOP_VENDOR_FORM_FIRST_NAME' => 'Имя',
	'PHPSHOP_VENDOR_FORM_MIDDLE_NAME' => 'Отчество',
	'PHPSHOP_VENDOR_FORM_TITLE' => 'Обращение',
	'PHPSHOP_VENDOR_FORM_PHONE_1' => 'Телефон 1',
	'PHPSHOP_VENDOR_FORM_PHONE_2' => 'Телефон 2',
	'PHPSHOP_VENDOR_FORM_FAX' => 'Факс',
	'PHPSHOP_VENDOR_FORM_EMAIL' => 'E-mail',
	'PHPSHOP_VENDOR_FORM_IMAGE_PATH' => 'Путь к изображению',
	'PHPSHOP_VENDOR_FORM_DESCRIPTION' => 'Описание',
	'PHPSHOP_VENDOR_CAT_LIST_LBL' => 'Список категорий продавцов',
	'PHPSHOP_VENDOR_CAT_NAME' => 'Название категории',
	'PHPSHOP_VENDOR_CAT_DESCRIPTION' => 'Описание категории',
	'PHPSHOP_VENDOR_CAT_VENDORS' => 'Продавцы',
	'PHPSHOP_VENDOR_CAT_FORM_LBL' => 'Добавить категорию продавцов',
	'PHPSHOP_VENDOR_CAT_FORM_INFO_LBL' => 'Информация о категории',
	'PHPSHOP_VENDOR_CAT_FORM_NAME' => 'Название категории',
	'PHPSHOP_VENDOR_CAT_FORM_DESCRIPTION' => 'Описание категории'
); $VM_LANG->initModule( 'vendor', $langvars );
?>