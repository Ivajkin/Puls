<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: product.product_product_type_list.php 1747 2009-04-28 17:13:39Z macallf $
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
require_once( CLASSPATH . "pageNavigation.class.php" );
require_once( CLASSPATH . "htmlTools.class.php" );
$temp = vmGet($_REQUEST, 'product_id', 0);

$product_parent_id = vmGet($_REQUEST, 'product_parent_id', 0);

global $ps_product_type;

if(sizeof($temp) > 1) {
	$product_id=$temp;
	$title2 = $VM_LANG->_('VM_PRODUCT_PRODUCT_TYPE_ADD_MULTIPLE_PRODUCTS');
} else {
	$product_id = $temp[0];
}

$q  = "SELECT  * FROM #__{vm}_product_type,#__{vm}_product_product_type_xref ";
$q .= "WHERE #__{vm}_product_type.product_type_id=#__{vm}_product_product_type_xref.product_type_id ";
$q .= "AND product_id = ('".$product_id."') ";
$q .= "ORDER BY product_type_list_order asc ";
$db->setQuery($q);   
$db->query();

// Create the List Object with page navigation

$listObj = new listFactory( );

$title = $VM_LANG->_('PHPSHOP_PRODUCT_PRODUCT_TYPE_LIST_LBL');
if(isset($title2)) {
	$title .=$title2;
}
elseif (!empty($product_parent_id)) {
  $title .= " ".$VM_LANG->_('PHPSHOP_ITEM').": ";
} else {
  $title .= " ".$VM_LANG->_('PHPSHOP_PRODUCT').": ";
}

$url = $_SERVER['PHP_SELF'] . "?page=$modulename.product_form&product_id=$product_id&product_parent_id=$product_parent_id";
if(!is_array($product_id)) {
	$title .= "<a href=\"" . $sess->url($url) . "\">". $ps_product->get_field($product_id,"product_name")."</a>";
}
// print out the search field and a list heading
$listObj->writeSearchHeader( $title, IMAGEURL."ps_image/categories.gif", $modulename, "product_list");

// start the list table
$listObj->startTable();

// these are the columns in the table
$columns = Array(  "#" => "width=\"20\"", 
					"<input type=\"checkbox\" name=\"toggle\" value=\"\" onclick=\"checkAll(".$db->num_rows().")\" />" => "width=\"20\"",
					$VM_LANG->_('PHPSHOP_PRODUCT_TYPE_FORM_NAME') => 'width="25%"',
					$VM_LANG->_('PHPSHOP_PRODUCT_TYPE_FORM_DESCRIPTION') => 'width="30%"',
					$VM_LANG->_('PHPSHOP_PRODUCT_TYPE_FORM_PARAMETERS') => 'width="15%"',
					$VM_LANG->_('PHPSHOP_PRODUCTS_LBL') => 'width="15%"',
					$VM_LANG->_('E_REMOVE') => "width=\"10%\""
				);

$listObj->writeTableHeader( $columns );

$i = 0;
while ($db->next_record()) {
	$product_count = $ps_product_type->product_count($db->f("product_type_id"));
	$parameter_count = $ps_product_type->parameter_count($db->f("product_type_id"));
	
	$listObj->newRow();
	
	// The row number
	$listObj->addCell( $i+1 );
	
	// The Checkbox
	$listObj->addCell( vmCommonHTML::idBox( $i, $db->f("product_type_id"), false, "product_type_id" ) );
	
	$tmp_cell = "<a href=\"" . $_SERVER['PHP_SELF'] . "?option=com_virtuemart&page=product.product_type_form&product_type_id=" . $db->f("product_type_id"). "\">". $db->f("product_type_name") . "</a>";
	$listObj->addCell( $tmp_cell );
	
	$listObj->addCell( $db->f("product_type_description"));
      
	$tmp_cell = $parameter_count . " " . $VM_LANG->_('PHPSHOP_PARAMETERS_LBL') 
			. " <a href=\"". $_SERVER['PHP_SELF'] . "?option=com_virtuemart&page=product.product_type_parameter_list&product_type_id=". $db->f("product_type_id") . "\">[ ".$VM_LANG->_('PHPSHOP_SHOW')." ]</a>";
	$listObj->addCell( $tmp_cell );
	
	$tmp_cell = $product_count ." ". $VM_LANG->_('PHPSHOP_PRODUCTS_LBL')
			."&nbsp;<a href=\"". $_SERVER['PHP_SELF'] . "?option=com_virtuemart&page=product.product_list&product_type_id=" . $db->f("product_type_id"). "\">[ ".$VM_LANG->_('PHPSHOP_SHOW')." ]</a>";
	$listObj->addCell( $tmp_cell );
	
	$listObj->addCell( $ps_html->deleteButton( "product_type_id", $db->f("product_type_id"), "productProductTypeDelete", $keyword, $limitstart, "&product_id=". $product_id ) );

	$i++;
}
$listObj->writeTable();

$listObj->endTable();

$listObj->writeFooter( $keyword, "&product_id=".$product_id );
?>