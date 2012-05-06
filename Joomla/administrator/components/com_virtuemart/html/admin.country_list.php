<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: admin.country_list.php 1580 2008-11-30 08:38:39Z Aravot $
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
	$list  = "SELECT * FROM #__{vm}_country WHERE ";
	$count = "SELECT count(*) as num_rows FROM #__{vm}_country WHERE ";
	$q  = "(country_name LIKE '%$keyword%' OR ";
	$q .= "country_2_code LIKE '%$keyword%' OR ";
	$q .= "country_3_code LIKE '%$keyword%' ";
	$q .= ") ";
	$q .= "ORDER BY country_name ";
	$list .= $q . " LIMIT $limitstart, " . $limit;
	$count .= $q;   
}
else {
	$list  = "SELECT * FROM #__{vm}_country ";
	$list .= "ORDER BY country_name ";
	$list .= "LIMIT $limitstart, " . $limit;
	$count = "SELECT count(*) as num_rows FROM #__{vm}_country ";
}
$db->query($count);
$db->next_record();
$num_rows = $db->f("num_rows");

// Create the Page Navigation
$pageNav = new vmPageNav( $num_rows, $limitstart, $limit );

// Create the List Object with page navigation
$listObj = new listFactory( $pageNav );

// print out the search field and a list heading
$listObj->writeSearchHeader($VM_LANG->_('PHPSHOP_COUNTRY_LIST_LBL'), VM_THEMEURL.'images/administration/dashboard/countries.png', "admin", "country_list");

// start the list table
$listObj->startTable();

// these are the columns in the table
$columns = Array(  "#" => "", 
					"<input type=\"checkbox\" name=\"toggle\" value=\"\" onclick=\"checkAll(".$num_rows.")\" />" => "",
					$VM_LANG->_('PHPSHOP_COUNTRY_LIST_NAME') => "width=\"30%\"",
					$VM_LANG->_('PHPSHOP_ZONE_ASSIGN_CURRENT_LBL') => "width=\"25%\"",
					$VM_LANG->_('PHPSHOP_COUNTRY_LIST_3_CODE') => "width=\"20%\"",
					$VM_LANG->_('PHPSHOP_COUNTRY_LIST_2_CODE') => "width=\"20%\"",
					$VM_LANG->_('E_REMOVE') => "width=\"5%\""
				);
$listObj->writeTableHeader( $columns );

$db->query($list);
$i = 0;
require_once( CLASSPATH . 'ps_zone.php');

while ($db->next_record()) {
	
	
	$listObj->newRow();
	
	// The row number
	$listObj->addCell( $pageNav->rowNumber( $i ) );
		
	// The Checkbox
	$listObj->addCell( vmCommonHTML::idBox( $i, $db->f("country_id"), false, "country_id" ) );
	
	$tmp_cell = "<a href=\"". $sess->url($_SERVER['PHP_SELF'] ."?page=admin.country_form&limitstart=$limitstart&keyword=".urlencode($keyword)."&country_id=".$db->f("country_id")) ."\">";
	$tmp_cell .= $db->f("country_name") ."</a>&nbsp;&nbsp;";
	$tmp_cell .= "<a href=\"". $sess->url($_SERVER['PHP_SELF'] ."?page=admin.country_state_list&country_id=".$db->f("country_id")) ."\">[ ". $VM_LANG->_('PHPSHOP_STATE_LIST_MNU') ." ]</a>";
	$listObj->addCell( $tmp_cell );
	
	$listObj->addCell( ps_zone::zone_name($db->f("zone_id")) );
	$listObj->addCell( $db->f("country_3_code") );
	$listObj->addCell( $db->f("country_2_code") );
	
	$listObj->addCell( $ps_html->deleteButton( "country_id", $db->f("country_id"), "countryDelete", $keyword, $limitstart ) );
	
		$i++;
	
}
$listObj->writeTable();

$listObj->endTable();

$listObj->writeFooter( $keyword );
?>