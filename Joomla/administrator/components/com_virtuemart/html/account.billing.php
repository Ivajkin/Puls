<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: account.billing.php 1813 2009-06-17 18:36:15Z Aravot $
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
require_once( CLASSPATH . "htmlTools.class.php" );

$mainframe->setPageTitle( $VM_LANG->_('PHPSHOP_USER_FORM_BILLTO_LBL') );
      
$next_page = vmGet( $_REQUEST, "next_page", "account.index");
$Itemid = $sess->getShopItemid();

$missing = vmGet( $vars, 'missing' );
if (!empty($missing)) {
	echo "<script type=\"text/javascript\"> alert('".$VM_LANG->_('CONTACT_FORM_NC')."'); </script>\n";
}

if ( VM_REGISTRATION_TYPE == 'NO_REGISTRATION' || VM_REGISTRATION_TYPE == 'OPTIONAL_REGISTRATION' && empty($d['register_account'])) {
	$q =  "SELECT * FROM #__{vm}_user_info 
			WHERE user_id='" . $auth["user_id"] . "' 
			AND address_type='BT' ";
} else {
	$q =  "SELECT * FROM #__users, #__{vm}_user_info 
			WHERE user_id='" . $auth["user_id"] . "' 
			AND user_id = id
			AND address_type='BT' ";
}
$db->query($q);
$db->next_record();

// Set the CMS pathway
$pathway = array();
if( stristr( $next_page, 'checkout' ) !== false ) {
	// We are in the checkout process
	$pathway[] = $vm_mainframe->vmPathwayItem( $VM_LANG->_('PHPSHOP_CHECKOUT_TITLE'), $sess->url( SECUREURL."index.php?page=$next_page") );
	$pathway[] = $vm_mainframe->vmPathwayItem( $VM_LANG->_('PHPSHOP_SHOPPER_FORM_SHIPTO_LBL') );	
} else {
	// We are in account maintenance
	$pathway[] = $vm_mainframe->vmPathwayItem( $VM_LANG->_('PHPSHOP_ACCOUNT_TITLE'), $sess->url( SECUREURL .'index.php?page=account.index' ) );
	$pathway[] = $vm_mainframe->vmPathwayItem( $VM_LANG->_('PHPSHOP_USER_FORM_BILLTO_LBL') );
}
$vm_mainframe->vmAppendPathway( $pathway );

// Set the internal VirtueMart pathway
$tpl = vmTemplate::getInstance();
$tpl->set( 'pathway', $pathway );
$vmPathway = $tpl->fetch( 'common/pathway.tpl.php' );
$tpl->set( 'vmPathway', $vmPathway );

// Handle NO_REGISTRATION
$skip_fields = array();
if ( VM_REGISTRATION_TYPE == 'NO_REGISTRATION' || VM_REGISTRATION_TYPE == 'OPTIONAL_REGISTRATION' && empty($d['register_account'])) {
	global $default;
	$default['email'] = $db->f('user_email');
	$skip_fields = array( 'username', 'password', 'password2' );
}

$fields = ps_userfield::getUserFields( 'account' );

$tpl->set_vars( array(
					'fields' => $fields,
					'db' => $db,
					'next_page' => $next_page,
					'missing' => $missing,
					'Itemid' => $Itemid,
					'skip_fields' => $skip_fields
					));
echo $tpl->fetch('pages/'.$page.'.tpl.php');

?>