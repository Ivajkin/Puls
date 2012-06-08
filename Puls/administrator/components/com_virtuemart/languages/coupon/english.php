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
	'PHPSHOP_COUPON_EDIT_HEADER' => 'Изменить купон',
	'PHPSHOP_COUPON_CODE_HEADER' => 'Код',
	'PHPSHOP_COUPON_PERCENT_TOTAL' => 'Процент или Сумма',
	'PHPSHOP_COUPON_TYPE' => 'Тип купона',
	'PHPSHOP_COUPON_TYPE_TOOLTIP' => 'Подарочный Купон будет удален после его использования, постоянный купон можно использовать все время.',
	'PHPSHOP_COUPON_TYPE_GIFT' => 'Подарочный купон',
	'PHPSHOP_COUPON_TYPE_PERMANENT' => 'Постоянный купон',
	'PHPSHOP_COUPON_VALUE_HEADER' => 'Значение',
	'PHPSHOP_COUPON_PERCENT' => 'Процент',
	'PHPSHOP_COUPON_TOTAL' => 'Сумма'
); $VM_LANG->initModule( 'coupon', $langvars );
?>