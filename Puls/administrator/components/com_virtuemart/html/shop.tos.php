<?php 
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: shop.tos.php 1095 2007-12-19 20:19:16Z soeren_nb $
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

$db = new ps_DB;
$ps_vendor_id = $_SESSION['ps_vendor_id'];

$q = "SELECT `vendor_id`, `vendor_terms_of_service` FROM `#__{vm}_vendor` ";
$q .= "WHERE `vendor_id`='".$ps_vendor_id."'";

$db->query($q);
$db->next_record();
$db->p("vendor_terms_of_service");

?>
