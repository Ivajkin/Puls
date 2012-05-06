<?php 
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: coupon.coupon_list.php 1793 2009-05-15 21:34:54Z tkahl $
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
  
	$list  = "SELECT * FROM #__{vm}_coupons WHERE ";
	$count = "SELECT count(*) as num_rows FROM #__{vm}_coupons WHERE ";
	$q  = "(coupon_code LIKE '%$keyword%' OR ";
	$q .= "coupon_value LIKE '%$keyword%' ";
	$q .= ") ";
	$q .= "ORDER BY coupon_id ";
	$list .= $q . " LIMIT $limitstart, " . $limit;
	$count .= $q;   
}
else  {
	$list  = "SELECT * FROM #__{vm}_coupons ";
	$list .= "ORDER BY coupon_id ";
	$list .= "LIMIT $limitstart, " . $limit;
	$count = "SELECT count(*) as num_rows FROM #__{vm}_coupons ";
}
$db->query($count);
$db->next_record();
$num_rows = $db->f("num_rows");
  
// Create the Page Navigation
$pageNav = new vmPageNav( $num_rows, $limitstart, $limit );

// Create the List Object with page navigation
$listObj = new listFactory( $pageNav );

// print out the search field and a list heading
$listObj->writeSearchHeader($VM_LANG->_('PHPSHOP_COUPON_LIST'), IMAGEURL."ps_image/percentage.png", $modulename, "coupon_list");

// start the list table
$listObj->startTable();

// these are the columns in the table
$columns = Array(  "#" => "width=\"20\"", 
					"<input type=\"checkbox\" name=\"toggle\" value=\"\" onclick=\"checkAll(".$num_rows.")\" />" => "width=\"20\"",
					$VM_LANG->_('PHPSHOP_COUPON_CODE_HEADER') => '',
					$VM_LANG->_('PHPSHOP_COUPON_PERCENT_TOTAL') => '',
					$VM_LANG->_('PHPSHOP_COUPON_TYPE') => '',
					$VM_LANG->_('PHPSHOP_COUPON_VALUE_HEADER') => '',
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
	$listObj->addCell( vmCommonHTML::idBox( $i, $db->f("coupon_id"), false, "coupon_id" ) );
    
	$tmp_cell = "<a href=\"". $sess->url($_SERVER['PHP_SELF']."?page=coupon.coupon_form&limitstart=$limitstart&keyword=".urlencode($keyword)."&coupon_id=" . $db->f("coupon_id")) ."\">".$db->f("coupon_code")."</a>";
	$listObj->addCell( $tmp_cell );
    
	$tmp_cell = $db->f("percent_or_total")=='total' ? $VM_LANG->_('PHPSHOP_COUPON_TOTAL') : $VM_LANG->_('PHPSHOP_COUPON_PERCENT');
	$listObj->addCell( $tmp_cell );
	
    $tmp_cell = $db->f("coupon_type")=='gift' ? $VM_LANG->_('PHPSHOP_COUPON_TYPE_GIFT') : $VM_LANG->_('PHPSHOP_COUPON_TYPE_PERMANENT');
	$listObj->addCell( $tmp_cell );
	
    $listObj->addCell( $db->f("coupon_value"));
	
	$listObj->addCell( $ps_html->deleteButton( "coupon_id", $db->f("coupon_id"), "couponDelete", $keyword, $limitstart ) );

	$i++;

}

$listObj->writeTable();

$listObj->endTable();

$listObj->writeFooter( $keyword );
?>