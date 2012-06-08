<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
* PayPal IPN Result Checker
*
* @version $Id: checkout.paysbuy.php 617 2007-01-04 19:43:08Z soeren_nb $
* @package VirtueMart
* @subpackage html
* @copyright Copyright (C) 2004-2005 Soeren Eberhardt. All rights reserved.
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

if( !isset( $_REQUEST["order_id"] ) || empty( $_REQUEST["order_id"] )) {
  echo $VM_LANG->_('VM_CHECKOUT_ORDERIDNOTSET');
}
else {
	include( CLASSPATH. "payment/ps_paypal.cfg.php" );
	$order_id = intval( vmGet( $_REQUEST, "order_id" ));

	$q = "SELECT order_id, order_status FROM #__{vm}_orders WHERE ";
	$q .= "#__{vm}_orders.user_id= " . $auth["user_id"] . " ";
	$q .= "AND #__{vm}_orders.order_id= $order_id ";
	$db->query($q);
	if ($db->next_record()) {
		$order_status = $db->f("order_status");
		$d['order_id'] = $db->f("order_id");
	
	//if($_REQUEST['x_response_code'] == '1') {
		if(substr($_REQUEST['result'],0,2) == '00') {

			// UPDATE THE ORDER STATUS to 'PAID'
            $d['order_status'] = "D";
            require_once ( CLASSPATH . 'ps_order.php' );
            $ps_order= new ps_order;
            $ps_order->order_status_update($d);
			?>

	
			<img src="<?php echo VM_THEMEURL ?>images/button_ok.png" alt="Success" style="border: 0;" />
			<h2>Thanks for your payment.</h2>
			<p>The transaction was successful. You will get a confirmation e-mail for the transaction by PaySbuy. You can now continue or log in at <a href=https://www.paysbuy.com>https://www.paysbuy.com</a> to see the transaction details.'</p>
    
    <?php
      }
		else {

            // the Payment wasn't successful. Maybe the Payment couldn't
            // be verified and is pending
            // UPDATE THE ORDER STATUS to 'CANCELLED'
            $d['order_status'] = "X";
            require_once ( CLASSPATH . 'ps_order.php' );
            $ps_order= new ps_order;
            $ps_order->order_status_update($d);

			?>
			<img src="<?php echo VM_THEMEURL ?>images/button_cancel.png" alt="<?php echo $VM_LANG->_('VM_CHECKOUT_FAILURE'); ?>" style="border: 0;" />
			<h2>Payment Unsuccessful</h2>
			<p><?php echo $VM_LANG->_('PHPSHOP_PAYMENT_ERROR') ?></p>
    
    <?php
    } ?>
    <br />
     <p><a href="index.php?option=com_virtuemart&page=account.order_details&order_id=<?php echo $order_id ?>">
     <?php echo $VM_LANG->_PHPSHOP_ORDER_LINK ?></a>
     </p>
    <?php
	}
	else {
		echo "Order not found!";
	}
}
?>
