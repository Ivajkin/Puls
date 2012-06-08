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
	'PHPSHOP_ZONE_ASSIGN_COUNTRY_LBL' => 'Страна',
	'PHPSHOP_ZONE_ASSIGN_ASSIGN_LBL' => 'Назначить региону',
	'PHPSHOP_ASSIGN_ZONE_PG_LBL' => 'Назначить регионы',
	'PHPSHOP_ZONE_FORM_NAME_LBL' => 'Название региона',
	'PHPSHOP_ZONE_FORM_DESC_LBL' => 'Описание региона',
	'PHPSHOP_ZONE_FORM_COST_PER_LBL' => 'Региональная стоимость за единицу',
	'PHPSHOP_ZONE_FORM_COST_LIMIT_LBL' => 'Лимит стоимости для региона',
	'PHPSHOP_ZONE_LIST_LBL' => 'Список регионов',
	'PHPSHOP_ZONE_LIST_NAME_LBL' => 'Название региона',
	'PHPSHOP_ZONE_LIST_DESC_LBL' => 'Описание региона',
	'PHPSHOP_ZONE_LIST_COST_PER_LBL' => 'Региональная стоимость за единицу',
	'PHPSHOP_ZONE_LIST_COST_LIMIT_LBL' => 'Лимит стоимости для региона',
	'VM_ZONE_ASSIGN_PERITEM' => 'За позицию',
	'VM_ZONE_ASSIGN_LIMIT' => 'Лимит',
	'VM_ZONE_EDITZONE' => 'Редактировать эту зону'
); $VM_LANG->initModule( 'zone', $langvars );
?>