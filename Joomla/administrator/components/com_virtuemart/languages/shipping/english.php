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
	'PHPSHOP_CARRIER_LIST_LBL' => 'Shipper list',
	'PHPSHOP_RATE_LIST_LBL' => 'Shipping Rates list',
	'PHPSHOP_CARRIER_LIST_NAME_LBL' => 'Name',
	'PHPSHOP_CARRIER_LIST_ORDER_LBL' => 'Listorder',
	'PHPSHOP_CARRIER_FORM_LBL' => 'Shipper edit / create',
	'PHPSHOP_RATE_FORM_LBL' => 'Create/Edit a Shipping Rate',
	'PHPSHOP_RATE_FORM_NAME' => 'Shipping Rate description',
	'PHPSHOP_RATE_FORM_CARRIER' => 'Shipper',
	'PHPSHOP_RATE_FORM_COUNTRY' => 'Country',
	'PHPSHOP_RATE_FORM_ZIP_START' => 'ZIP range start',
	'PHPSHOP_RATE_FORM_ZIP_END' => 'ZIP range end',
	'PHPSHOP_RATE_FORM_WEIGHT_START' => 'Lowest Weight',
	'PHPSHOP_RATE_FORM_WEIGHT_END' => 'Highest Weight',
	'PHPSHOP_RATE_FORM_PACKAGE_FEE' => 'Your package fee',
	'PHPSHOP_RATE_FORM_CURRENCY' => 'Currency',
	'PHPSHOP_RATE_FORM_LIST_ORDER' => 'List Order',
	'PHPSHOP_SHIPPING_RATE_LIST_CARRIER_LBL' => 'Shipper',
	'PHPSHOP_SHIPPING_RATE_LIST_RATE_NAME' => 'Shipping Rate description',
	'PHPSHOP_SHIPPING_RATE_LIST_RATE_WSTART' => 'Weight from ...',
	'PHPSHOP_SHIPPING_RATE_LIST_RATE_WEND' => '... to',
	'PHPSHOP_CARRIER_FORM_NAME' => 'Shipper Company',
	'PHPSHOP_CARRIER_FORM_LIST_ORDER' => 'Listorder'
); $VM_LANG->initModule( 'shipping', $langvars );
?>