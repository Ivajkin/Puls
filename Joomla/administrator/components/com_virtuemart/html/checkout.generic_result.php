<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
* This file is called after the order has been placed by the customer
*
* @version $Id: checkout.thankyou.php 1364 2008-04-09 16:44:28Z soeren_nb $
* @package VirtueMart
* @subpackage html
* @copyright Copyright (C) 2010 soeren - All rights reserved.
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

echo '<h2>Status<h2><br /><br />';

$result = vmGet( $vars, 'result'  );

switch( $result ) {

	case 'success' :
		echo vmCommonHTML::imageTag( VM_THEMEURL .'images/button_ok.png', 'Success', 'center', '48', '48' );
		echo $VM_LANG->_('PHPSHOP_THANKYOU_SUCCESS');
		break;
		
	case 'failure':		
	?>
        <p><img src="<?php echo VM_THEMEURL ?>images/button_cancel.png" align="middle" alt="<?php echo $VM_LANG->_('VM_CHECKOUT_FAILURE'); ?>" border="0" />
        Die Bezahlung der Bestellung ist leider fehlgeschlagen.</p>
		<?php
		break;
		
	case 'cancel':
		echo '<p class="shop_warning">Die Bezahlung Ihrer Bestellung wurde abgebrochen.</p>';
		break;
	
	case 'pending':
		echo '<p class="shop_info">Die Bezahlung Ihrer Bestellung wurde initiiert, aber noch nicht abgeschlossen.</p>';
		break;
		
}
?>
<br /><br />
<br /><br />
	<a href="<?php $sess->purl(SECUREURL.basename($_SERVER['SCRIPT_NAME'])."?page=account.index") ?>" onclick="if( parent.parent.location ) { parent.parent.location = this.href.replace(/index2.php/, 'index.php' ); };">
 		<?php echo $VM_LANG->_('PHPSHOP_ACCOUNT_TITLE') ?>
 	</a>
	<br />