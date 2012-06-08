<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: shop.registration.php 1529 2008-09-19 18:04:46Z soeren_nb $
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

$vm_mainframe->setpagetitle($VM_LANG->_('REGISTER_TITLE'));
$pathway[] = $vm_mainframe->vmPathwayItem($VM_LANG->_('REGISTER_TITLE'));
$vm_mainframe->vmAppendPathway( $pathway );

if( empty($auth['user_id']) ) {
	include( PAGEPATH . 'checkout_register_form.php' );
} else {
	vmRedirect( $sess->url( URL.'index.php?page='.HOMEPAGE, false, false ) );
}
?>