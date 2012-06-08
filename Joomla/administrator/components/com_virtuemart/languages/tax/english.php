<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: english.php 1071 2007-12-03 08:42:28Z thepisu $
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
*/
global $VM_LANG;
$langvars = array (
	'CHARSET' => 'ISO-8859-1',
	'PHPSHOP_TAX_LIST_LBL' => 'Tax Rate List',
	'PHPSHOP_TAX_LIST_STATE' => 'Tax State or Region',
	'PHPSHOP_TAX_LIST_COUNTRY' => 'Tax Country',
	'PHPSHOP_TAX_FORM_LBL' => 'Add Tax Information',
	'PHPSHOP_TAX_FORM_STATE' => 'Tax State or Region',
	'PHPSHOP_TAX_FORM_COUNTRY' => 'Tax Country',
	'PHPSHOP_TAX_FORM_RATE' => 'Tax Rate (for 16% => fill in 0.16)'
); $VM_LANG->initModule( 'tax', $langvars );
?>