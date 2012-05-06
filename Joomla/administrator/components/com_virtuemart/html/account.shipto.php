<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: account.shipto.php 1228 2008-02-09 01:14:55Z gregdev $
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

require_once( CLASSPATH . "ps_userfield.php" );

$mainframe->setPageTitle( $VM_LANG->_('PHPSHOP_ADD_SHIPTO_1') ." ".$VM_LANG->_('PHPSHOP_ADD_SHIPTO_2') );
      
$Itemid = $sess->getShopItemid();
$next_page = vmGet( $_REQUEST, "next_page", "account.shipping" );
$user_info_id = vmGet( $_REQUEST, "user_info_id", "" );

// Set the CMS pathway
$pathway = array();
if( stristr( $next_page, 'checkout' ) !== false ) {
	// We are in the checkout process
	$pathway[] = $vm_mainframe->vmPathwayItem( $VM_LANG->_('PHPSHOP_CHECKOUT_TITLE'), $sess->url( SECUREURL."index.php?page=$next_page") );
	$pathway[] = $vm_mainframe->vmPathwayItem( $VM_LANG->_('PHPSHOP_SHOPPER_FORM_SHIPTO_LBL') );	
} else {
	// We are in account maintenance
	$pathway[] = $vm_mainframe->vmPathwayItem( $VM_LANG->_('PHPSHOP_ACCOUNT_TITLE'), $sess->url( SECUREURL .'index.php?page=account.index' ) );
	$pathway[] = $vm_mainframe->vmPathwayItem( $VM_LANG->_('PHPSHOP_USER_FORM_SHIPTO_LBL'), $sess->url( SECUREURL."index.php?page=$next_page") );
	$pathway[] = $vm_mainframe->vmPathwayItem( $VM_LANG->_('PHPSHOP_SHOPPER_FORM_SHIPTO_LBL') );
}
$vm_mainframe->vmAppendPathway( $pathway );

// Set the internal VirtueMart pathway
$tpl = vmTemplate::getInstance();
$tpl->set( 'pathway', $pathway );
$vmPathway = $tpl->fetch( 'common/pathway.tpl.php' );
$tpl->set( 'vmPathway', $vmPathway );

$missing = vmGet( $vars, 'missing' );

if (!empty( $missing )) {
    echo "<script type=\"text/javascript\">alert('". $VM_LANG->_('CONTACT_FORM_NC',false) ."'); </script>\n";
}
$db = new ps_DB;
if (!empty($user_info_id)) {
  $q =  "SELECT * from #__{vm}_user_info WHERE user_info_id='".$database->getEscaped($user_info_id)."' ";
  $q .=  " AND user_id='".$auth['user_id']."'";
  $q .=  " AND address_type='ST'";
  $db->query($q);
  $db->next_record();
}

if( !$db->num_rows()) {
	$vars['country'] = vmGet($_REQUEST, 'country', $vendor_country);
}

$fields = ps_userfield::getUserFields( 'shipping' );

$tpl->set_vars( array('next_page' => $next_page,
					'fields' => $fields,
					'missing' => $missing,
					'vars' => $vars,
					'db' => $db,
					'user_info_id' => $user_info_id
					));
echo $tpl->fetch('pages/'.$page.'.tpl.php');

?>