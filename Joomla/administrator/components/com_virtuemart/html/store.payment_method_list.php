<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: store.payment_method_list.php 1227 2008-02-08 12:09:50Z soeren_nb $
* @package VirtueMart
* @subpackage html
* @copyright Copyright (C) 2004-2007 soeren - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/
mm_showMyFileName( __FILE__ );
require_once( CLASSPATH . "pageNavigation.class.php" );
require_once( CLASSPATH . "htmlTools.class.php" );

if (!empty($keyword)) {
	$list  = "SELECT * FROM #__{vm}_payment_method LEFT JOIN #__{vm}_shopper_group ";
	$list .= "ON #__{vm}_payment_method.shopper_group_id=#__{vm}_shopper_group.shopper_group_id WHERE ";
	$count = "SELECT count(*) as num_rows FROM #__{vm}_payment_method LEFT JOIN #__{vm}_shopper_group ";
	$count .= "ON #__{vm}_payment_method.shopper_group_id=#__{vm}_shopper_group.shopper_group_id WHERE ";
	$q  = "(#__{vm}_payment_method.payment_method_name LIKE '%$keyword%' ";
	$q .= "AND #__{vm}_payment_method.vendor_id='$ps_vendor_id' ";
	$q .= ") ";
	$q .= "ORDER BY #__{vm}_payment_method.list_order,#__{vm}_payment_method.payment_method_name ";
	$list .= $q . " LIMIT $limitstart, " . $limit;
	$count .= $q;   
}
else {
	$q = "";
	$list = "SELECT * FROM #__{vm}_payment_method LEFT JOIN #__{vm}_shopper_group ";
	$list .= "ON #__{vm}_payment_method.shopper_group_id=#__{vm}_shopper_group.shopper_group_id WHERE ";
	$count = "SELECT count(*) as num_rows FROM #__{vm}_payment_method LEFT JOIN #__{vm}_shopper_group ";
	$count .= "ON #__{vm}_payment_method.shopper_group_id=#__{vm}_shopper_group.shopper_group_id WHERE ";
	$q .= "#__{vm}_payment_method.vendor_id='$ps_vendor_id' ";
	$list .= $q;
	$list .= "ORDER BY #__{vm}_payment_method.list_order,#__{vm}_payment_method.payment_method_name ";
	$list .= "LIMIT $limitstart, " . $limit;
	$count .= $q;
}
$db->query($count);
$db->next_record();
$num_rows = $db->f("num_rows");
  
// Create the Page Navigation
$pageNav = new vmPageNav( $num_rows, $limitstart, $limit );

// Create the List Object with page navigation
$listObj = new listFactory( $pageNav );

// print out the search field and a list heading
$listObj->writeSearchHeader($VM_LANG->_('PHPSHOP_PAYMENT_METHOD_LIST_LBL'), VM_THEMEURL.'images/administration/dashboard/payment.png', $modulename, "payment_method_list");

// start the list table
$listObj->startTable();

// these are the columns in the table
$columns = Array(  "#" => "width=\"20\"", 
					"<input type=\"checkbox\" name=\"toggle\" value=\"\" onclick=\"checkAll(".$num_rows.")\" />" => "width=\"20\"",
					$VM_LANG->_('PHPSHOP_PAYMENT_METHOD_LIST_NAME') => '',
					$VM_LANG->_('PHPSHOP_PAYMENT_METHOD_LIST_CODE') => '',
					$VM_LANG->_('PHPSHOP_PAYMENT_METHOD_LIST_DISCOUNT') => '',
					$VM_LANG->_('PHPSHOP_PAYMENT_METHOD_LIST_SHOPPER_GROUP') => '',
					$VM_LANG->_('PHPSHOP_PAYMENT_METHOD_LIST_ENABLE_PROCESSOR') => '',
					$VM_LANG->_('PHPSHOP_ISSHIP_LIST_PUBLISH_LBL') => '',
					$VM_LANG->_('E_REMOVE') => "width=\"5%\""
				);
$listObj->writeTableHeader( $columns );

$db->query($list);
$i = 0;
while ($db->next_record()) { 

	$listObj->newRow();
	
	// The row number
	$listObj->addCell( $pageNav->rowNumber( $i ) );
	
	// The Checkbox
	$listObj->addCell( vmCommonHTML::idBox( $i, $db->f("payment_method_id"), false, "payment_method_id" ) );

	$url = $_SERVER['PHP_SELF'] . "?page=$modulename.payment_method_form&limitstart=$limitstart&keyword=".urlencode($keyword)."&payment_method_id=".$db->f("payment_method_id");
	$tmp_cell = "<a href=\"" . $sess->url($url) . "\">". $db->f("payment_method_name")."</a>";
	$listObj->addCell( $tmp_cell );
	
	$listObj->addCell(  $db->f("payment_method_code") );
	if( $db->f('payment_method_discount_is_percent')) {
		$tmp_cell = $db->f("payment_method_discount").'%';
	}
	else {
		$tmp_cell = $GLOBALS['CURRENCY_DISPLAY']->getFullValue( $db->f("payment_method_discount") );
	}
	$listObj->addCell( $tmp_cell );
	
	$shopper_group_name = $db->f("shopper_group_name");
	$tmp_cell = empty( $shopper_group_name ) ? '' : $shopper_group_name;
    $listObj->addCell( $tmp_cell );
    
	$enable_processor = $db->f("enable_processor");
	switch($enable_processor) { 
		case "Y": 
			$tmp_cell = $VM_LANG->_('PHPSHOP_PAYMENT_FORM_USE_PP');
			break;
		case "N":
			$tmp_cell = $VM_LANG->_('PHPSHOP_PAYMENT_FORM_AO');
			break;
		case "B":
			$tmp_cell = $VM_LANG->_('PHPSHOP_PAYMENT_FORM_BANK_DEBIT');
			break;
		case "P":
			$tmp_cell = $VM_LANG->_('VM_PAYMENT_FORM_FORMBASED');
			break;
		default:
			$tmp_cell = $VM_LANG->_('PHPSHOP_PAYMENT_FORM_CC');
			break;
	}
	$listObj->addCell( $tmp_cell );
    
	
	$tmpcell = "<a href=\"". $sess->url( $_SERVER['PHP_SELF']."?page=$page&payment_method_id=".$db->f("payment_method_id")."&func=changePublishState" );
	if ($db->f("payment_enabled")=='N') {
		$tmpcell .= "&task=publish\">";
	} 
	else { 
		$tmpcell .= "&task=unpublish\">";
	}
	$tmpcell .= vmCommonHTML::getYesNoIcon( $db->f("payment_enabled"), $VM_LANG->_('CMN_PUBLISH'), $VM_LANG->_('CMN_UNPUBLISH') );
	$tmpcell .= "</a>";
	$listObj->addCell( $tmpcell );
	
	$listObj->addCell( $ps_html->deleteButton( "payment_method_id", $db->f("payment_method_id"), "paymentMethodDelete", $keyword, $limitstart ) );

	$i++;
}
$listObj->writeTable();

$listObj->endTable();

$listObj->writeFooter( $keyword );
?>