<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: product.product_category_list.php 2603 2010-10-24 12:20:07Z zanardi $
* @package VirtueMart
* @subpackage html
* @copyright Copyright (C) 2004-2008 soeren - All rights reserved.
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
global $ps_product_category;

require_once( CLASSPATH . "pageNavigation.class.php" );
require_once( CLASSPATH . "htmlTools.class.php" );

$categories = ps_product_category::getCategoryTreeArray(false, $keyword ); // Get array of category objects
$result = ps_product_category::sortCategoryTreeArray( $categories );

$nrows = $size = sizeOf($categories); // Category count

$id_list = $result['id_list'];
$row_list = $result['row_list'];
$depth_list = $result['depth_list'];
$categories = $result['category_tmp'];

// Create the Page Navigation
$pageNav = new vmPageNav( $nrows, $limitstart, $limit );

for($n = $pageNav->limitstart ; $n < $nrows ; $n++) {
	@$levelcounter[$categories[$row_list[$n]]["category_parent_id"]]++;
}

// Create the List Object with page navigation
$listObj = new listFactory( $pageNav );

// print out the search field and a list heading
$listObj->writeSearchHeader($VM_LANG->_('PHPSHOP_CATEGORY_LIST_LBL'), VM_THEMEURL.'images/administration/dashboard/categories.png', $modulename, "product_category_list");

// start the list table
$listObj->startTable();

// these are the columns in the table
$columns = Array(  "#" => "width=\"20\"", 
					"<input type=\"checkbox\" name=\"toggle\" value=\"\" onclick=\"checkAll(".$pageNav->limit.")\" />" => "width=\"20\"",
					$VM_LANG->_('PHPSHOP_CATEGORY_FORM_NAME') => 'width="25%"',
					$VM_LANG->_('PHPSHOP_CATEGORY_FORM_DESCRIPTION') => 'width="30%"',
					$VM_LANG->_('PHPSHOP_PRODUCTS_LBL') => 'width="10%"',
					$VM_LANG->_('PHPSHOP_PRODUCT_LIST_PUBLISH') => 'width="5%"',
					$VM_LANG->_('PHPSHOP_MODULE_LIST_ORDER') => 'width="7%"',
					vmCommonHTML::getSaveOrderButton( min($nrows - $pageNav->limitstart, $pageNav->limit ) ) => 'width="8%"',
					$VM_LANG->_('E_REMOVE') => "width=\"5%\"",
					'Id' => ''
				);
$listObj->writeTableHeader( $columns );

$ibg = 0;
if( $pageNav->limit < $nrows )
	if( $pageNav->limitstart+$pageNav->limit < $nrows ) {
		$nrows = $pageNav->limitstart + $pageNav->limit;
	}

for($n = $pageNav->limitstart ; $n < $nrows ; $n++) {

	if( !isset($row_list[$n])) $row_list[$n] = $n;
	if( !isset($depth_list[$n])) $depth_list[$n] = 0;
	
	$catname = shopMakeHtmlSafe( $categories[$row_list[$n]]["category_name"] );
	
	$listObj->newRow();
	
	// The row number
	$listObj->addCell( $pageNav->rowNumber( $ibg ) );
	
	// The Checkbox
	$listObj->addCell( vmCommonHTML::idBox( $ibg, $categories[$row_list[$n]]["category_child_id"], false, "category_id" ) );
	
	// Which category depth level we are in?
	$repeat = $depth_list[$n]+1;
	$tmp_cell = str_repeat("&nbsp;&nbsp;&nbsp;", $repeat ) 
				. "&#095&#095;|" . $repeat ."|&nbsp;"
				."<a href=\"". $_SERVER['PHP_SELF'] . "?option=com_virtuemart&page=product.product_category_form&category_id=" . $categories[$row_list[$n]]["category_child_id"]. "&category_parent_id=" . $categories[$row_list[$n]]["category_parent_id"]."\">"
				. $catname
				. "</a>";
	$listObj->addCell( $tmp_cell );
	
	$desc = strlen( $categories[$row_list[$n]]["category_description"] ) > 255 ? mm_ToolTip( $categories[$row_list[$n]]["category_description"], $VM_LANG->_('PHPSHOP_CATEGORY_FORM_DESCRIPTION') ) :$categories[$row_list[$n]]["category_description"];
	$listObj->addCell( "&nbsp;&nbsp;". $desc );
	
	$listObj->addCell( ps_product_category::product_count( $categories[$row_list[$n]]["category_child_id"] )
						."&nbsp;<a href=\"". $_SERVER['PHP_SELF'] . "?page=product.product_list&category_id=" . $categories[$row_list[$n]]["category_child_id"]."&option=com_virtuemart"
						. "\">[ ".$VM_LANG->_('PHPSHOP_SHOW')." ]</a>"
					);
	// Publish / Unpublish
	$tmp_cell = "<a href=\"". $sess->url( $_SERVER['PHP_SELF']."?page=product.product_category_list&category_id=".$categories[$row_list[$n]]["category_child_id"]."&func=changePublishState" );
	if ($categories[$row_list[$n]]["category_publish"]=='N') {
		$tmp_cell .= "&task=publish\">";
	} 
	else { 
		$tmp_cell .= "&task=unpublish\">";
	}
	$tmp_cell .= vmCommonHTML::getYesNoIcon ( $categories[$row_list[$n]]["category_publish"] );
	$tmp_cell .= "</a>";
	$listObj->addCell( $tmp_cell );

	// Order Up and Down Icons
	if( $keyword == '' ) {
		// This must be a big cheat, because we're working on sorted arrays,
		// not on database information
		// Check for predecessors and brothers and sisters
		$upCondition = $downCondition = false;
		if( !isset( $levels[$depth_list[$n]+1] ))
			$levels[$depth_list[$n]+1] = 1;
					
		// check row immediately before or after
		if( $categories[$row_list[$n]]["category_parent_id"] == @$categories[$row_list[$n-1]]["category_parent_id"]) {
			$upCondition = true;
		}
		if( $categories[$row_list[$n]]["category_parent_id"] == @$categories[$row_list[$n+1]]["category_parent_id"] ) {
			$downCondition = true;
		}
				
		if( !$downCondition || !$upCondition ) {
			
			// $levelcounter[$categories[$row_list[$n]]["category_parent_id"]]
			//		is total of my sister categories (same level, same parent id)
			// $depth_list[$n] + 1
			//		is my depth (1 = root, 2 = first child ...)
			// $levels[ $depth_list[$n] + 1 ]
 			//		is my position amongst all categories of the same level ( WITH NO CHECK FOR PARENT ID, HOW CAN THIS WORK? But it does )
			
			if( $levelcounter[$categories[$row_list[$n]]["category_parent_id"]] > $levels[$depth_list[$n]+1] ) {
				$downCondition = true;
				if( $levels[$depth_list[$n]+1] > 1 ) {
					$upCondition = true;
				}
			}
			if( $levelcounter[$categories[$row_list[$n]]["category_parent_id"]] == $levels[$depth_list[$n]+1] ) {
				$upCondition = true;
				$downCondition = false;
			}
			//if( $levelcounter[$categories[$row_list[$n]]["category_parent_id"]] < $levels[$depth_list[$n]+1] ) {
			//	$downCondition = false;
			//	$upCondition = false;
			//}
		}
		
		$levels[$depth_list[$n]+1]++;
		
		$listObj->addCell( $pageNav->orderUpIcon( $ibg, $upCondition, 'orderup', $VM_LANG->_('CMN_ORDER_UP'), $page, 'reorder' )
							. '&nbsp;'
							.$pageNav->orderDownIcon( $ibg, $levelcounter[$categories[$row_list[$n]]["category_parent_id"]], $downCondition, 'orderdown', $VM_LANG->_('CMN_ORDER_DOWN'), $page, 'reorder' )
						);
						
		$listObj->addCell( vmCommonHTML::getOrderingField( $categories[$row_list[$n]]["list_order"] ) );
	} else {
		$listObj->addCell( '&nbsp;' );
		$listObj->addCell( '&nbsp;' );		
	}
	$listObj->addCell( $ps_html->deleteButton( "category_id", $categories[$row_list[$n]]["category_child_id"], "productCategoryDelete", $keyword, $limitstart ) );
	
	$listObj->addCell( $categories[$row_list[$n]]["category_child_id"] );
	
	$ibg++;
}

$listObj->writeTable();

$listObj->endTable();

$listObj->writeFooter( $keyword );
?>
