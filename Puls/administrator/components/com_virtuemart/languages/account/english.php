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
	'PHPSHOP_ACC_CUSTOMER_ACCOUNT' => 'Учетная запись покупателя:',
	'PHPSHOP_ACC_UPD_BILL' => 'Здесь Вы можете изменить Вашу контактную информацию.',
	'PHPSHOP_ACC_UPD_SHIP' => 'Здесь Вы можете добавить новый адрес доставки.',
	'PHPSHOP_ACC_ACCOUNT_INFO' => 'Информация об учетной записи',
	'PHPSHOP_ACC_SHIP_INFO' => 'Информация о доставке',
	'PHPSHOP_DOWNLOADS_CLICK' => 'Щелкните по названию товара, чтобы скачать файл(ы).',
	'PHPSHOP_DOWNLOADS_EXPIRED' => 'Вы уже скачали файл(ы) максимально допустимое количество раз или период, в течение которого можно скачивать файлы, истек.'
); $VM_LANG->initModule( 'account', $langvars );
?>