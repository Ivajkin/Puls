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
	'PHPSHOP_TAX_LIST_LBL' => 'Список налоговых ставок',
	'PHPSHOP_TAX_LIST_STATE' => 'Регион, где действует налог',
	'PHPSHOP_TAX_LIST_COUNTRY' => 'Страна, где действует налог',
	'PHPSHOP_TAX_FORM_LBL' => 'Добавить информацию о налоговой ставке',
	'PHPSHOP_TAX_FORM_STATE' => 'Регион, где действует налог',
	'PHPSHOP_TAX_FORM_COUNTRY' => 'Страна, где действует налог',
	'PHPSHOP_TAX_FORM_RATE' => 'Ставка налога (если 18% => вводим 0.18)'
); $VM_LANG->initModule( 'tax', $langvars );
?>