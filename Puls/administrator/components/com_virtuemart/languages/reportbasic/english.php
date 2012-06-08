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
	'PHPSHOP_RB_INDIVIDUAL' => 'Отчет по отдельному товару',
	'PHPSHOP_RB_SALE_TITLE' => 'Отчет о продажах', // not used?
	'PHPSHOP_RB_SALES_PAGE_TITLE' => 'Анализ продаж', // not used?
	'PHPSHOP_RB_INTERVAL_TITLE' => 'Установить интервал',
	'PHPSHOP_RB_INTERVAL_MONTHLY_TITLE' => 'За текущий месяц',
	'PHPSHOP_RB_INTERVAL_WEEKLY_TITLE' => 'За неделю',
	'PHPSHOP_RB_INTERVAL_DAILY_TITLE' => 'За день',
	'PHPSHOP_RB_THISMONTH_BUTTON' => 'За текущий месяц',
	'PHPSHOP_RB_LASTMONTH_BUTTON' => 'За предыдущий месяц',
	'PHPSHOP_RB_LAST60_BUTTON' => 'За последние 60 дней',
	'PHPSHOP_RB_LAST90_BUTTON' => 'За последние 90 дней',
	'PHPSHOP_RB_START_DATE_TITLE' => 'Начало',
	'PHPSHOP_RB_END_DATE_TITLE' => 'Конец',
	'PHPSHOP_RB_SHOW_SEL_RANGE' => 'Показать выбранный период',
	'PHPSHOP_RB_REPORT_FOR' => 'Отчет за ',
	'PHPSHOP_RB_DATE' => 'Дата',
	'PHPSHOP_RB_ORDERS' => 'Заказы',
	'PHPSHOP_RB_TOTAL_ITEMS' => 'Всего продано',
	'PHPSHOP_RB_REVENUE' => 'Выручка',
	'PHPSHOP_RB_PRODLIST' => 'Список товаров'
); $VM_LANG->initModule( 'reportbasic', $langvars );
?>