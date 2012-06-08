<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: ps_order.php 2437 2010-06-18 08:41:39Z soeren $
* @package VirtueMart
* @subpackage classes
* @copyright Copyright (C) 2004-2009 soeren - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/

/**
 * The class handles orders from an adminstrative perspective.  Order
 * processing is handled in the ps_checkout class.
 */
class vm_ps_order {

	/**
     * Changes the status of an order
     * @author pablo
     * @author soeren
     * @author Uli
     * 
     *
     * @param array $d
     * @return boolean
    */
	function order_status_update(&$d) {
		global $mosConfig_offset;
			global  $sess, $VM_LANG, $vmLogger;
		
		$db = new ps_DB;
		//$timestamp = time() + ($mosConfig_offset*60*60);  //Original
		$timestamp = time();  //Custom
		//$mysqlDatetime = date("Y-m-d G:i:s",$timestamp);  //Original
		$mysqlDatetime = date("Y-m-d G:i:s", $timestamp + ($mosConfig_offset*60*60));  //Custom

		if( empty($_REQUEST['include_comment'])) {
			$include_comment="N";
		}

		// get the current order status
		$curr_order_status = @$d["current_order_status"];
		$notify_customer = empty($d['notify_customer']) ? "N" : $d['notify_customer'];
		if( $notify_customer=="Y" ) {
			$notify_customer=1; 
		}
		else {
			$notify_customer=0;
		}

		$d['order_comment'] = empty($d['order_comment']) ? "" : $d['order_comment'];
		if( empty($d['order_item_id']) ) {
			// When the order is set to "confirmed", we can capture
			// the Payment with authorize.net
			if( $curr_order_status=="P" && $d["order_status"]=="C") {
				$q = "SELECT order_number,payment_class,order_payment_trans_id FROM #__{vm}_payment_method,#__{vm}_order_payment,#__{vm}_orders WHERE ";
				$q .= "#__{vm}_order_payment.order_id='".$db->getEscaped($d['order_id'])."' ";
				$q .= "AND #__{vm}_orders.order_id='".$db->getEscaped($d['order_id'])."' ";
				$q .= "AND #__{vm}_order_payment.payment_method_id=#__{vm}_payment_method.payment_method_id";
				$db->query( $q );
				$db->next_record();
				$payment_class = $db->f("payment_class");
				$d["order_number"] = $db->f("order_number");
				
				switch( $payment_class ) {
					case "ps_authorize":
				
						require_once( CLASSPATH."payment/ps_authorize.cfg.php");
						if( AN_TYPE == 'AUTH_ONLY' ) {
							require_once( CLASSPATH."payment/ps_authorize.php");
							$authorize = new ps_authorize();
							if( !$authorize->capture_payment( $d )) {
								return false;
							}
						}
						break;
					default:
							// default case for payment methods that allow to "capture" the payment
							if( is_file( CLASSPATH.'payment/'.basename($payment_class).'.php' ) ) {
								require_once( CLASSPATH.'payment/'.basename($payment_class).'.php' );
								if( !class_exists($payment_class)) break;
								$paymentObj = new $payment_class();
								
								if( !method_exists($paymentObj,'capture_payment')) break;
								
								if( !$paymentObj->capture_payment( $d )) {
									return false;
								}
							}
							break;
				}
			}
			/*
			 * This is like the test above for delayed capture only
			 * we (well, I - durian) don't think the credit card
			 * should be captured until the item(s) are shipped.
			 * In fact, VeriSign says not to capture the cards until
			 * the item ships.  Maybe this behavior should be a
			 * configurable item?
			 *
			 * When the order changes from Confirmed or Pending to
			 * Shipped, perform the delayed capture.
			 *
			 * Restricted to PayFlow Pro for now.
			 */
			if( ($curr_order_status=="P" || $curr_order_status=="C") && $d["order_status"]=="S") {
				$q = "SELECT order_number,payment_class,order_payment_trans_id FROM #__{vm}_payment_method,#__{vm}_order_payment,#__{vm}_orders WHERE ";
				$q .= "#__{vm}_order_payment.order_id='".$db->getEscaped($d['order_id'])."' ";
				$q .= "AND #__{vm}_orders.order_id='".$db->getEscaped($d['order_id'])."' ";
				$q .= "AND #__{vm}_order_payment.payment_method_id=#__{vm}_payment_method.payment_method_id";
				$db->query( $q );
				$db->next_record();
				$payment_class = $db->f("payment_class");
				if( $payment_class=="payflow_pro" ) {
					require_once( CLASSPATH."payment/payflow_pro.cfg.php");
					if( PFP_TYPE == 'A' ) {
						require_once( CLASSPATH."payment/payflow_pro.php");
						$pfp = new ps_pfp();
						$d["order_number"] = $db->f("order_number");
						if( !$pfp->capture_payment( $d )) {
							return false;
						}
					}
				}
			}
			
			/**
			 * Do capture when product is shipped
			 */
			 /*
			 if(($curr_order_status == "P" || $curr_order_status == "C") && $d["order_status"]=="S")
			 {
				$q = "SELECT order_number,payment_class,order_payment_trans_id FROM #__{vm}_payment_method,#__{vm}_order_payment,#__{vm}_orders WHERE ";
				$q .= "#__{vm}_orders.order_id='".$db->getEscaped($d['order_id'])."' ";
				$q .= "AND #__{vm}_orders.order_id=#__{vm}_order_payment.order_id ";
				$q .= "AND #__{vm}_order_payment.payment_method_id=#__{vm}_payment_method.payment_method_id";
				$db->query( $q );
				$db->next_record();
				$payment_class = strtolower(basename($db->f("payment_class")));
				if( file_exists( CLASSPATH.'payment/'.$payment_class.'.php' )) {
					require_once( CLASSPATH."payment/$payment_class.php");
					$payment = new $payment_class();
					$d["order_number"] = $db->f("order_number");
					if( is_callable( array( $payment, 'capture_payment' ))) {
						if( !$payment->capture_payment( $d )) {
							return false;
						}
					}
				}			 
			 }*/
	
			/*
			 * If a pending order gets cancelled, void the authorization.
			 *
			 * It might work on captured cards too, if we want to
			 * void shipped orders.
			 *
			 */
			if( $curr_order_status=="P" && $d["order_status"]=="X") {
				$q = "SELECT order_number,payment_class,order_payment_trans_id FROM #__{vm}_payment_method,#__{vm}_order_payment,#__{vm}_orders WHERE ";
				$q .= "#__{vm}_order_payment.order_id='".$db->getEscaped($d['order_id'])."' ";
				$q .= "AND #__{vm}_orders.order_id='".$db->getEscaped($d['order_id'])."' ";
				$q .= "AND #__{vm}_order_payment.payment_method_id=#__{vm}_payment_method.payment_method_id";
				$db->query( $q );
				$db->next_record();
				$payment_class = strtolower(basename($db->f("payment_class")));
				if( file_exists( CLASSPATH.'payment/'.$payment_class.'.php' )) {
					require_once( CLASSPATH."payment/$payment_class.php");
					$payment = new $payment_class();
					$d["order_number"] = $db->f("order_number");
					if( is_callable( array( $payment, 'void_authorization' ))) {
						if( !$payment->void_authorization( $d )) {
							return false;
						}
					}
				}
			}
			
			// Do a Refund
			if( $d['order_status']=='R' && $curr_order_status != 'R') {
				$vmLogger->debug("Initiating Refund");
				$q = 'SELECT order_number,payment_class,order_payment_trans_id FROM #__{vm}_payment_method,#__{vm}_order_payment,#__{vm}_orders WHERE ';
				$q .= '#__{vm}_orders.order_id=\''.$db->getEscaped($d['order_id']).'\' ';
				$q .= 'AND #__{vm}_orders.order_id=#__{vm}_order_payment.order_id ';
				$q .= 'AND #__{vm}_order_payment.payment_method_id=#__{vm}_payment_method.payment_method_id';
				$db->query( $q );
				$db->next_record();
				$payment_class = strtolower(basename($db->f("payment_class")));
				$vmLogger->debug('Payment Class: '.$payment_class);
				if( file_exists( CLASSPATH.'payment/'.$payment_class.'.php' )) {
					$vmLogger->debug('Found Payment Module');
					require_once( CLASSPATH."payment/$payment_class.php");
					$payment = new $payment_class();
					$d["order_number"] = $db->f("order_number");
					if( is_callable( array( $payment, 'do_refund' ))) 
					{
						$vmLogger->debug('Can call do_refund');
						if( !$payment->do_refund( $d )) {
							$vmLogger->debug('failed to do refund');
							return false;
						}
					}
				}
			}
			
			$fields =array( 'order_status'=> $d["order_status"], 
										'mdate'=> $timestamp );
			$db->buildQuery('UPDATE', '#__{vm}_orders', $fields, "WHERE order_id='" . $db->getEscaped($d["order_id"]) . "'");
			$db->query();
	
			// Update the Order History.
			$fields = array( 'order_id' => $d["order_id"],
										'order_status_code' => $d["order_status"],
										'date_added' => $mysqlDatetime,
										'customer_notified' => $notify_customer,
										'comments' => $d['order_comment']
							);
			$db->buildQuery('INSERT', '#__{vm}_order_history', $fields );
			$db->query();
	
			// Do we need to re-update the Stock Level?
			if( (strtoupper($d["order_status"]) == "X" || strtoupper($d["order_status"])=="R") 
				// && CHECK_STOCK == '1'
				&& $curr_order_status != $d["order_status"]
				) {
				// Get the order items and update the stock level
				// to the number before the order was placed
				$q = "SELECT product_id, product_quantity FROM #__{vm}_order_item WHERE order_id='".$db->getEscaped($d["order_id"])."'";
				$db->query( $q );
				$dbu = new ps_DB;
				require_once( CLASSPATH.'ps_product.php');
				// Now update each ordered product
				while( $db->next_record() ) {
					if( ENABLE_DOWNLOADS == '1' && ps_product::is_downloadable($db->f("product_id")) && VM_DOWNLOADABLE_PRODUCTS_KEEP_STOCKLEVEL == '1') {
						$q = "UPDATE #__{vm}_product  
								SET product_sales=product_sales-".$db->f("product_quantity")." 
							WHERE product_id=".$db->f("product_id");
						$dbu->query( $q );
					}
					else {
						$q = "UPDATE #__{vm}_product 
							SET product_in_stock=product_in_stock+".$db->f("product_quantity").",
								product_sales=product_sales-".$db->f("product_quantity")." 
							WHERE product_id=".$db->f("product_id");
						$dbu->query( $q );
					}
				}
			}
			// Update the Order Items' status
			$q = "SELECT order_item_id FROM #__{vm}_order_item WHERE order_id=".$db->getEscaped($d['order_id']);
			$db->query($q);
			$dbu = new ps_DB;
			while ($db->next_record()) {
				$item_id = $db->f("order_item_id");
				$fields =array( 'order_status'=> $d["order_status"], 
											'mdate'=> $timestamp );
				$dbu->buildQuery('UPDATE', '#__{vm}_order_item', $fields, "WHERE order_item_id='" .(int)$item_id . "'");
				$dbu->query();
			}
			
			if (ENABLE_DOWNLOADS == '1') {
				##################
				## DOWNLOAD MOD
				$this->mail_download_id( $d );
			}
	
			if( !empty($notify_customer) ) {
				$this->notify_customer( $d );
			}
		} elseif( !empty($d['order_item_id'])) {
				// Update the Order Items' status
				$q = "SELECT order_item_id, product_id, product_quantity FROM #__{vm}_order_item 
							WHERE order_id=".$db->getEscaped($d['order_id'])
						. ' AND order_item_id='.intval( $d['order_item_id'] );
				$db->query($q);
				$item_product_id = $db->f('product_id');
				$item_product_quantity = $db->f('product_quantity');
				require_once( CLASSPATH. 'ps_product.php' );
				if( ENABLE_DOWNLOADS == '1' && ps_product::is_downloadable($item_product_id) && VM_DOWNLOADABLE_PRODUCTS_KEEP_STOCKLEVEL == '1') {
						$q = "UPDATE #__{vm}_product  
								SET product_sales=product_sales-".$item_product_quantity." 
							WHERE product_id=".$item_product_id;
						$db->query( $q );
					}
					else {
						$q = "UPDATE #__{vm}_product 
							SET product_in_stock=product_in_stock+".$item_product_quantity.",
								product_sales=product_sales-".$item_product_quantity." 
							WHERE product_id=".$item_product_id;
						$db->query( $q );
				}
				
				$fields =array( 'order_status'=> $d["order_status"], 
											'mdate'=> $timestamp );
				$db->buildQuery('UPDATE', '#__{vm}_order_item', $fields, 'WHERE order_item_id='.intval( $d['order_item_id'] ));
				return $db->query() !== false;
		}
		return true;
	}

	/**
	 * mails the Download-ID to the customer
	 * or deletes the Download-ID from the product_downloads table
	 *
	 * @param array $d
	 * @return boolean
	 */
	function mail_download_id( &$d ){

		global $sess,	$VM_LANG, $vmLogger;

		$url = URL."index.php?option=com_virtuemart&page=shop.downloads&Itemid=".$sess->getShopItemid();
		
		$db = new ps_DB();
		$db->query( 'SELECT order_status FROM #__{vm}_orders WHERE order_id='.(int)$d['order_id'] );
		$db->next_record();
		
		if (in_array($db->f("order_status"), array(ENABLE_DOWNLOAD_STATUS,'S'))) {
			$dbw = new ps_DB;
			
			$q = "SELECT order_id,user_id,download_id,file_name FROM #__{vm}_product_download WHERE";
			$q .= " order_id = '" . (int)$d["order_id"] . "'";
			$dbw->query($q);
			$dbw->next_record();
			$userid = $dbw->f("user_id");
			$download_id = $dbw->f("download_id");
			$datei=$dbw->f("file_name");
			$dbw->reset();

			if ($download_id) {

				$dbv = new ps_DB;
				$q = "SELECT * FROM #__{vm}_vendor WHERE vendor_id='1'";
				$dbv->query($q);
				$dbv->next_record();

				$db = new ps_DB;
				$q="SELECT first_name,last_name, user_email FROM #__{vm}_user_info WHERE user_id = '$userid' AND address_type='BT'";
				$db->query($q);
				$db->next_record();

				$message = $VM_LANG->_('HI',false) .' '. $db->f("first_name") .($db->f("middle_name")?' '.$db->f("middle_name") : '' ). ' ' . $db->f("last_name") . ",\n\n";
				$message .= $VM_LANG->_('PHPSHOP_DOWNLOADS_SEND_MSG_1',false).".\n";
				$message .= $VM_LANG->_('PHPSHOP_DOWNLOADS_SEND_MSG_2',false)."\n\n";

				while($dbw->next_record()) {
					$message .= basename($dbw->f("file_name")).": ".$dbw->f("download_id")
					. "\n$url&download_id=".$dbw->f("download_id")."\n\n";
				}

				$message .= $VM_LANG->_('PHPSHOP_DOWNLOADS_SEND_MSG_3',false) . DOWNLOAD_MAX."\n";
				$expire = ((DOWNLOAD_EXPIRE / 60) / 60) / 24;
				$message .= str_replace("{expire}", $expire, $VM_LANG->_('PHPSHOP_DOWNLOADS_SEND_MSG_4',false));
				$message .= "\n\n____________________________________________________________\n";
				$message .= $VM_LANG->_('PHPSHOP_DOWNLOADS_SEND_MSG_5',false)."\n";
				$message .= $dbv->f("vendor_name") . " \n" . URL."\n\n".$dbv->f("contact_email") . "\n";
				$message .= "____________________________________________________________\n";
				$message .= $VM_LANG->_('PHPSHOP_DOWNLOADS_SEND_MSG_6',false) . $dbv->f("vendor_name");


				$mail_Body = $message;
				$mail_Subject = $VM_LANG->_('PHPSHOP_DOWNLOADS_SEND_SUBJ',false);
				$from  = $dbv->f("contact_email") ? $dbv->f("contact_email") : $GLOBALS['mosConfig_mailfrom'];
				$result = vmMail( $from, $dbv->f("vendor_name"), 
						$db->f("user_email"), $mail_Subject, $mail_Body, '' );

				if ($result) {
					$vmLogger->info( $VM_LANG->_('PHPSHOP_DOWNLOADS_SEND_MSG',false). " ". $db->f("first_name") . " " . $db->f("last_name") . " ".$db->f("user_email") );
				}
				else {
					$vmLogger->warning( $VM_LANG->_('PHPSHOP_DOWNLOADS_ERR_SEND',false)." ". $db->f("first_name") . " " . $db->f("last_name") . ", ".$db->f("user_email") );
				}
			} 
		}
		elseif ( in_array(vmGet($d,'order_status'), array(DISABLE_DOWNLOAD_STATUS,'X','R'))) {
			$q = "DELETE FROM #__{vm}_product_download WHERE order_id=" . (int)$d["order_id"];
			$db->query($q);
			$db->next_record();
		}

		return true;
	}

	/**
	 * notifies the customer that the Order Status has been changed
	 *
	 * @param array $d
	 */
	function notify_customer( &$d ){

		global  $sess, $VM_LANG, $vmLogger;

		$url = SECUREURL."index.php?option=com_virtuemart&page=account.order_details&order_id=".urlencode($d["order_id"]).'&Itemid='.$sess->getShopItemid();

		$db = new ps_DB;
		$dbv = new ps_DB;
		$q = "SELECT vendor_name,contact_email FROM #__{vm}_vendor ";
		$q .= "WHERE vendor_id='".$_SESSION['ps_vendor_id']."'";
		$dbv->query($q);
		$dbv->next_record();

		$q = "SELECT first_name,last_name,user_email,order_status_name FROM #__{vm}_order_user_info,#__{vm}_orders,#__{vm}_order_status ";
		$q .= "WHERE #__{vm}_orders.order_id = '".$db->getEscaped($d["order_id"])."' ";
		$q .= "AND #__{vm}_orders.user_id = #__{vm}_order_user_info.user_id ";
		$q .= "AND #__{vm}_orders.order_id = #__{vm}_order_user_info.order_id ";
		$q .= "AND order_status = order_status_code ";
		$db->query($q);
		$db->next_record();

		// MAIL BODY
		$message = $VM_LANG->_('HI',false) .' '. $db->f("first_name") . ($db->f("middle_name")?' '.$db->f("middle_name") : '' ). ' ' . $db->f("last_name") . ",\n\n";
		$message .= $VM_LANG->_('PHPSHOP_ORDER_STATUS_CHANGE_SEND_MSG_1',false)."\n\n";

		if( !empty($d['include_comment']) && !empty($d['order_comment']) ) {
			$message .= $VM_LANG->_('PHPSHOP_ORDER_HISTORY_COMMENT_EMAIL',false).":\n";
			$message .= $d['order_comment'];
			$message .= "\n____________________________________________________________\n\n";
		}

		$message .= $VM_LANG->_('PHPSHOP_ORDER_STATUS_CHANGE_SEND_MSG_2',false)."\n";
		$message .= "____________________________________________________________\n\n";
		$message .= $db->f("order_status_name");

		if( VM_REGISTRATION_TYPE != 'NO_REGISTRATION' ) {
			$message .= "\n____________________________________________________________\n\n";
			$message .= $VM_LANG->_('PHPSHOP_ORDER_STATUS_CHANGE_SEND_MSG_3',false)."\n";
			$message .= $url;
		}
		$message .= "\n\n____________________________________________________________\n";
		$message .= $dbv->f("vendor_name") . " \n";
		$message .= URL."\n";
		$message .= $dbv->f("contact_email");

		$message = str_replace( "{order_id}", $d["order_id"], $message );

		$mail_Body = html_entity_decode($message);
		$mail_Subject = str_replace( "{order_id}", $d["order_id"], $VM_LANG->_('PHPSHOP_ORDER_STATUS_CHANGE_SEND_SUBJ',false));
		
		
		$result = vmMail( $dbv->f("contact_email"),  $dbv->f("vendor_name"), 
					$db->f("user_email"), $mail_Subject, $mail_Body, '' );
		
		/* Send the email */
		if ($result) {
			$vmLogger->info( $VM_LANG->_('PHPSHOP_DOWNLOADS_SEND_MSG',false). " ". $db->f("first_name") . " " . $db->f("last_name") . ", ".$db->f("user_email") );
		}
		else {
			$vmLogger->warning( $VM_LANG->_('PHPSHOP_DOWNLOADS_ERR_SEND',false).' '. $db->f("first_name") . " " . $db->f("last_name") . ", ".$db->f("user_email")." (". $result->ErrorInfo.")" );
		}
	}
	/**
	 * This function inserts the DOWNLOAD IDs for all files associated with this product
	 * so the customer can later download the purchased files
	 * @static 
	 * @since 1.1.0
	 * @param int $product_id
	 * @param int $order_id
	 * @param int $user_id
	 */
	function insert_downloads_for_product( &$d ) {
		$db = new ps_DB();
		$dbd = new ps_DB();
		if( empty( $d['product_id'] ) || empty( $d['order_id'] )) {
			return false;
		}
		
		$dl = "SELECT attribute_name,attribute_value ";
		$dl .= "FROM #__{vm}_product_attribute WHERE product_id='".$d['product_id']."'";
		$dl .= " AND attribute_name='download'";
		$db->query($dl);
		$dlnum = 0;
		while($db->next_record()) {

			$str = (int)$d['order_id'];
			$str .= $d['product_id'];
		    $str .= uniqid('download_');
			$str .= $dlnum++;
			$str .= time();

			$download_id = md5($str);

			$fields = array('product_id' => $d['product_id'], 
							'user_id' => (int)$d['user_id'], 
							'order_id' => (int)$d['order_id'], 
							'end_date' => '0', 
							'download_max' => DOWNLOAD_MAX, 
							'download_id' => $download_id, 
							'file_name' => $db->f("attribute_value")
							);
			$dbd->buildQuery('INSERT', '#__{vm}_product_download', $fields );
			$dbd->query();
		}
	}

	/**
	 * Handles a download Request
	 *
	 * @param array $d
	 * @return boolean
	 */
	function download_request(&$d) {
		global  $download_id, $VM_LANG, $vmLogger;

		$db = new ps_DB;
		$download_id = $db->getEscaped( vmGet( $d, "download_id" ) );

		$q = "SELECT * FROM #__{vm}_product_download WHERE";
		$q .= " download_id = '$download_id'";

		$db->query($q);
		$db->next_record();

		$download_id = $db->f("download_id");
		$file_name = $db->f("file_name");
		if( strncmp($file_name, 'http', 4 ) !== 0) {
			$datei = DOWNLOADROOT . $file_name;
		} else {
			$datei = $file_name;
		}
		$download_max = $db->f("download_max");
		$end_date = $db->f("end_date");
		$zeit=time();

		if (!$download_id) {
			$vmLogger->err( $VM_LANG->_('PHPSHOP_DOWNLOADS_ERR_INV',false) );
			return false;
			//vmRedirect("index.php?option=com_virtuemart&page=shop.downloads", $d["error"]);
		}

		elseif ($download_max=="0") {
			$q ="DELETE FROM #__{vm}_product_download";
			$q .=" WHERE download_id = '" . $download_id . "'";
			$db->query($q);
			$db->next_record();
			$vmLogger->err( $VM_LANG->_('PHPSHOP_DOWNLOADS_ERR_MAX',false) );
			return false;
			//vmRedirect("index.php?option=com_virtuemart&page=shop.downloads", $d["error"]);
		}

		elseif ($end_date!="0" && $zeit > $end_date) {
			$q ="DELETE FROM #__{vm}_product_download";
			$q .=" WHERE download_id = '" . $download_id . "'";
			$db->query($q);
			$db->next_record();
			$vmLogger->err( $VM_LANG->_('PHPSHOP_DOWNLOADS_ERR_EXP',false) );
			return false;
			//vmRedirect("index.php?option=com_virtuemart&page=shop.downloads", $d["error"]);
		}
		require_once(CLASSPATH.'connectionTools.class.php');
		
		$download_count = true;
		
		if ( @file_exists( $datei ) ){
			// Check if this is a request for a special range of the file (=Resume Download)
			$range_request = vmConnector::http_rangeRequest( filesize($datei), false );
			if( $range_request[0] == 0 ) {
				// this is not a request to resume a download,
				$download_count = true;
			} else {
				$download_count = false;
			}
		} else {
			$download_count = false;
		}

		// Parameter to check if the file should be removed after download, which is only true,
		// if we have a remote file, which was transferred to this server into a temporary file
		$unlink = false;
		
		if( strncmp($datei, 'http', 4 ) === 0) {
			require_once( CLASSPATH.'ps_product_files.php');
			$datei_local = ps_product_files::getRemoteFile($datei);
			if( $datei_local !== false ) {
				$datei = $datei_local;
				$unlink = true;
			} else {
				$vmLogger->err( $VM_LANG->_('VM_DOWNLOAD_FILE_NOTFOUND',false) );
				return false;
			}
		}
		else {
			// Check, if file path is correct
			// and file is
			if ( !@file_exists( $datei ) ){
				$vmLogger->err( $VM_LANG->_('VM_DOWNLOAD_FILE_NOTFOUND',false) );
				return false;
				//vmRedirect("index.php?option=com_virtuemart&page=shop.downloads", $d["error"]);
			}
			if ( !@is_readable( $datei ) ) {
				$vmLogger->err( $VM_LANG->_('VM_DOWNLOAD_FILE_NOTREADABLE',false) );
				return false;
				//vmRedirect("index.php?option=com_virtuemart&page=shop.downloads", $d["error"]);
			}
		}
		if( $download_count ) {
			// decrement the download_max to limit the number of downloads
			$q ="UPDATE `#__{vm}_product_download` SET";
			$q .=" `download_max`=`download_max` - 1";
			$q .=" WHERE download_id = '" .$download_id. "'";
			$db->query($q);
			$db->next_record();
		}
		if ($end_date=="0") {
			// Set the Download Expiry Date, so the download can expire after DOWNLOAD_EXPIRE seconds
			$end_date=time('u') + DOWNLOAD_EXPIRE;
			$q ="UPDATE #__{vm}_product_download SET";
			$q .=" end_date=$end_date";
			$q .=" WHERE download_id = '" . $download_id . "'";
			$db->query($q);
			$db->next_record();
		}
		
		if (ereg('Opera(/| )([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT'])) {
			$UserBrowser = "Opera";
		}
		elseif (ereg('MSIE ([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT'])) {
			$UserBrowser = "IE";
		} else {
			$UserBrowser = '';
		}
		$mime_type = ($UserBrowser == 'IE' || $UserBrowser == 'Opera') ? 'application/octetstream' : 'application/octet-stream';

		// dump anything in the buffer
		while( @ob_end_clean() );

		vmConnector::sendFile( $datei, $mime_type, basename($file_name) );
		
		if( $unlink ) {
			// remove the temporarily downloaded remote file
			@unlink( $datei );
		}
		$GLOBALS['vm_mainframe']->close(true);
			
	}

	/**
	 * Shows the list of the orders of a user in the account mainenance section
	 *
	 * @param string $order_status Filter by order status (A=all, C=confirmed, P=pending,...)
	 * @param int $secure Restrict the order list to a specific user id (=1) or not (=0)?
	 */
	function list_order($order_status='A', $secure=0 ) {
		global $VM_LANG, $CURRENCY_DISPLAY, $sess, $limit, $limitstart, $keyword, $mm_action_url;

		$ps_vendor_id = $_SESSION["ps_vendor_id"];
		$auth = $_SESSION['auth'];
		require_once( CLASSPATH .'ps_order_status.php');
		require_once( CLASSPATH .'htmlTools.class.php');
		require_once( CLASSPATH .'pageNavigation.class.php');
		$db = new ps_DB;
		$dbs = new ps_DB;
		
		$listfields = 'o.order_id,o.cdate,order_total,o.order_status,order_currency';
		$countfields = 'count(*) as num_rows';
		$count = "SELECT $countfields FROM #__{vm}_orders o ";
		$list = "SELECT DISTINCT $listfields FROM #__{vm}_orders o ";
		$q = "WHERE o.vendor_id='$ps_vendor_id' ";
		if ($order_status != "A") {
			$q .= "AND order_status='$order_status' ";
		}
		if ($secure) {
			$q .= "AND user_id='" . $auth["user_id"] . "' ";
		}
		if( !empty( $keyword )) {
			$count .= ', #__{vm}_order_item oi ';
			$list .= ', #__{vm}_order_item oi ';
			$q .= "AND (order_item_sku LIKE '%".$keyword."%' ";
			$q .= "OR order_number LIKE '%".$keyword."%' ";
			$q .= "OR o.order_id=".(int)$keyword.' ';
			$q .= "OR order_item_name LIKE '%".$keyword."%') ";
			$q .= "AND oi.order_id=o.order_id ";
		}
		$q .= "ORDER BY o.cdate DESC";
		$count .= $q;

		$db->query($count);
		$db->next_record();
		$num_rows = $db->f('num_rows');
		if( $num_rows == 0 ) {
			echo "<span style=\"font-style:italic;\">".$VM_LANG->_('PHPSHOP_ACC_NO_ORDERS')."</span>\n";
			return;
		}
		$pageNav = new vmPageNav( $num_rows, $limitstart, $limit );

		$list .= $q .= " LIMIT ".$pageNav->limitstart.", $limit ";
		$db->query( $list );
		
		$listObj = new listFactory( $pageNav );

		if( $num_rows > 0 ) {
			// print out the search field and a list heading
			$listObj->writeSearchHeader( '', '', 'account', 'index');
		}
		// start the list table
		$listObj->startTable();

		$listObj->writeTableHeader( 3 );

		while ($db->next_record()) {

			$order_status = ps_order_status::getOrderStatusName($db->f("order_status"));

			$listObj->newRow();

			$tmp_cell = "<a href=\"". $sess->url( $mm_action_url."index.php?page=account.order_details&order_id=".$db->f("order_id") )."\">\n";
			$tmp_cell .= "<img src=\"".IMAGEURL."ps_image/goto.png\" height=\"32\" width=\"32\" align=\"middle\" border=\"0\" alt=\"".$VM_LANG->_('PHPSHOP_ORDER_LINK')."\" />&nbsp;".$VM_LANG->_('PHPSHOP_VIEW')."</a><br />";
			$listObj->addCell( $tmp_cell );

			$tmp_cell = "<strong>".$VM_LANG->_('PHPSHOP_ORDER_PRINT_PO_DATE').":</strong> " . vmFormatDate($db->f("cdate"), "%d. %B %Y");
			$tmp_cell .= "<br /><strong>".$VM_LANG->_('PHPSHOP_ORDER_PRINT_TOTAL').":</strong> " . $CURRENCY_DISPLAY->getFullValue($db->f("order_total"), '', $db->f('order_currency'));
			$listObj->addCell( $tmp_cell );

			$tmp_cell = "<strong>".$VM_LANG->_('PHPSHOP_ORDER_PRINT_PO_STATUS').":</strong> ".$order_status;
			$tmp_cell .= "<br /><strong>".$VM_LANG->_('PHPSHOP_ORDER_PRINT_PO_NUMBER').":</strong> " . sprintf("%08d", $db->f("order_id"));
			$listObj->addCell( $tmp_cell );
		}
		$listObj->writeTable();
		$listObj->endTable();
		if( $num_rows > 0 ) {
			$listObj->writeFooter( $keyword, '&Itemid='.$sess->getShopItemid() );
		}

	}

	/**
	 * Validate form values prior to delete
	 *
	 * @param int $order_id
	 * @return boolean
	 */
	function validate_delete($order_id) {
		global $VM_LANG;
		
		$db = new ps_DB;

		if(empty( $order_id )) {
			$GLOBALS['vmLogger']->err($VM_LANG->_('VM_ORDER_DELETE_ERR_ID'));
			return False;
		}

		return True;
	}

	/**
	* Controller for Deleting Records.
	*/
	function delete(&$d) {

		$record_id = $d["order_id"];

		if( is_array( $record_id)) {
			foreach( $record_id as $record) {
				if( !$this->delete_record( $record, $d ))
				return false;
			}
			return true;
		}
		else {
			return $this->delete_record( $record_id, $d );
		}
	}
	/**
	* Deletes one Record.
	*/
	function delete_record( $record_id, &$d ) {
		global $db;
		$record_id = intval( $record_id );
		if ($this->validate_delete($record_id)) {
			
			$dbu = new ps_db();
			// 	Get the order items and update the stock level
			// to the number before the order was placed
			$q = "SELECT order_status, product_id, product_quantity FROM #__{vm}_order_item WHERE order_id=$record_id";
			$db->query( $q );
			require_once( CLASSPATH .'ps_product.php' );
			// Now update each ordered product
			while( $db->next_record() ) {
				if( in_array( $db->f('order_status'), array('P', 'X', 'R') )) continue;
				
				if( ENABLE_DOWNLOADS == '1' && ps_product::is_downloadable($db->f("product_id")) && VM_DOWNLOADABLE_PRODUCTS_KEEP_STOCKLEVEL == '1') {
					$q = "UPDATE #__{vm}_product  
							SET product_sales=product_sales-".$db->f("product_quantity")." 
						WHERE product_id=".$db->f("product_id");
					$dbu->query( $q );
				}
				else {
					$q = "UPDATE #__{vm}_product 
						SET product_in_stock=product_in_stock+".$db->f("product_quantity").",
							product_sales=product_sales-".$db->f("product_quantity")." 
						WHERE product_id=".$db->f("product_id");
					$dbu->query( $q );
				}
			}
		
			$q = "DELETE from #__{vm}_orders where order_id='$record_id'";
			$db->query($q);

			$q = "DELETE from #__{vm}_order_item where order_id='$record_id'";
			$db->query($q);

			$q = "DELETE from #__{vm}_order_payment where order_id='$record_id'";
			$db->query($q);

			$q = "DELETE from #__{vm}_product_download where order_id='$record_id'";
			$db->query($q);
			
			$q = "DELETE from #__{vm}_order_history where order_id='$record_id'";
			$db->query($q);
			
			$q = "DELETE from #__{vm}_order_user_info where order_id='$record_id'";
			$db->query($q);
			
			$q = "DELETE FROM #__{vm}_shipping_label where order_id=$record_id";
			$db->query($q);

			return True;
		}
		else {
			return False;
		}
	}
	/**
	 * Creates the order navigation on the order print page
	 *
	 * @param int $order_id
	 * @return boolean
	 */
	function order_print_navigation( $order_id=1 ) {
		global $sess, $modulename, $VM_LANG;

		$navi_db = new ps_DB;

		$navigation = "<div align=\"center\">\n<strong>\n";
		$q = "SELECT order_id FROM #__{vm}_orders WHERE ";
		$q .= "order_id < '$order_id' ORDER BY order_id DESC";
		$navi_db->query($q);
		$navi_db->next_record();
		if ($navi_db->f("order_id")) {
			$url = $_SERVER['PHP_SELF'] . "?page=$modulename.order_print&order_id=";
			$url .= $navi_db->f("order_id");
			$navigation .= "<a class=\"pagenav\" href=\"" . $sess->url($url) . "\">&lt; " .$VM_LANG->_('ITEM_PREVIOUS')."</a> | ";
		} else
		$navigation .= "<span class=\"pagenav\">&lt; " .$VM_LANG->_('ITEM_PREVIOUS')." | </span>";

		$q = "SELECT order_id FROM #__{vm}_orders WHERE ";
		$q .= "order_id > '$order_id' ORDER BY order_id";
		$navi_db->query($q);
		$navi_db->next_record();
		if ($navi_db->f("order_id")) {
			$url = $_SERVER['PHP_SELF'] . "?page=$modulename.order_print&order_id=";
			$url .= $navi_db->f("order_id");
			$navigation .= "<a class=\"pagenav\" href=\"" . $sess->url($url) ."\">". $VM_LANG->_('ITEM_NEXT')."  &gt;</a>";
		} else {
			$navigation .= "<span class=\"pagenav\">".$VM_LANG->_('ITEM_NEXT')." &gt;</span>";
		}

		$navigation .= "\n<strong>\n</div>\n";

		return $navigation;
	}

}

// Check if there is an extended class in the Themes and if it is allowed to use them
// If the class is called outside Virtuemart, we have to make sure to load the settings
// Thomas Kahl - Feb. 2009
if (!defined('VM_ALLOW_EXTENDED_CLASSES') && file_exists(dirname(__FILE__).'/../virtuemart.cfg.php')) {
	include_once(dirname(__FILE__).'/../virtuemart.cfg.php');
}
// If settings are loaded, extended Classes are allowed and the class exisits...
if (defined('VM_ALLOW_EXTENDED_CLASSES') && defined('VM_THEMEPATH') && VM_ALLOW_EXTENDED_CLASSES && file_exists(VM_THEMEPATH.'user_class/'.basename(__FILE__))) {
	// Load the theme-user_class as extended
	include_once(VM_THEMEPATH.'user_class/'.basename(__FILE__));
} else {
	// Otherwise we have to use the original classname to extend the core-class
	class ps_order extends vm_ps_order {}
}


$ps_order = new ps_order;
?>
