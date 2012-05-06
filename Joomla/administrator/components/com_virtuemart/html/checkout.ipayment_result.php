<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
* PayPal IPN Result Checker
*
* @version $Id: checkout.ipayment_result.php 1675 2009-03-04 19:29:03Z soeren_nb $
* @package VirtueMart
* @subpackage html
* @copyright Copyright (C) 2009 soeren - All rights reserved.
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

if( !isset( $_REQUEST["shopper_id"] ) || empty( $_REQUEST["shopper_id"] )) {
	echo $VM_LANG->_('VM_CHECKOUT_ORDERIDNOTSET');
}
else {
	include( CLASSPATH. "payment/ps_ipayment.cfg.php" );
	$order_number = vmrequest::getVar('shopper_id');

	$q = 'SELECT order_id,order_total,order_status,order_currency FROM #__{vm}_orders WHERE ';
	$q .= '#__{vm}_orders.user_id= '. $auth["user_id"] . "\n";
	$q .= 'AND #__{vm}_orders.order_number=\''.$db->getEscaped($order_number)."'";
	$db->query($q);
	if ($db->next_record()) {
		if( vmRequest::getVar('ret_status') == 'SUCCESS' ) {
			
			?> 
        <img src="<?php echo VM_THEMEURL ?>images/button_ok.png" align="middle" alt="<?php echo $VM_LANG->_('VM_CHECKOUT_SUCCESS'); ?>" border="0" />
        <h2><?php echo $VM_LANG->_('PHPSHOP_PAYMENT_TRANSACTION_SUCCESS') ?></h2>
    
    <?php
		}
		else { ?>
        <img src="<?php echo VM_THEMEURL ?>images/button_cancel.png" align="middle" alt="<?php echo $VM_LANG->_('VM_CHECKOUT_FAILURE'); ?>" border="0" />
        <span class="message"><?php echo $VM_LANG->_('PHPSHOP_PAYMENT_ERROR') ?></span>
    
    <?php
    	}
      ?>
    <br />
     <p><a href="index.php?option=com_virtuemart&page=account.order_details&order_id=<?php $db->p('order_id') ?>">
     <?php echo $VM_LANG->_('PHPSHOP_ORDER_LINK') ?></a>
     </p>
    <?php
	}
	else {
		echo $VM_LANG->_('VM_CHECKOUT_ORDERNOTFOUND') . '!';
	}
}

