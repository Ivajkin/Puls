<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: english.php 1071 2007-12-03 08:42:28Z thepisu $
* @package VirtueMart
* @subpackage languages
* @copyright Copyright (C) 2004-2007 soeren - All rights reserved.
* @translator soeren
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/
global $VM_LANG;
$langvars = array (
	'CHARSET' => 'ISO-8859-1',
	'PHPSHOP_ORDER_PRINT_PAYMENT_LOG_LBL' => 'Payment Log',
	'PHPSHOP_ORDER_PRINT_SHIPPING_PRICE_LBL' => 'Shipping Price',
	'PHPSHOP_ORDER_STATUS_LIST_CODE' => 'Order Status Code',
	'PHPSHOP_ORDER_STATUS_LIST_NAME' => 'Order Status Name',
	'PHPSHOP_ORDER_STATUS_FORM_LBL' => 'Order Status',
	'PHPSHOP_ORDER_STATUS_FORM_CODE' => 'Order Status Code',
	'PHPSHOP_ORDER_STATUS_FORM_NAME' => 'Order Status Name',
	'PHPSHOP_ORDER_STATUS_FORM_LIST_ORDER' => 'List Order',
	'PHPSHOP_COMMENT' => 'Comment',
	'PHPSHOP_ORDER_LIST_NOTIFY' => 'Notify Customer?',
	'PHPSHOP_ORDER_LIST_NOTIFY_ERR' => 'Please change the Order Status first!',
	'PHPSHOP_ORDER_HISTORY_INCLUDE_COMMENT' => 'Include this comment?',
	'PHPSHOP_ORDER_HISTORY_DATE_ADDED' => 'Date Added',
	'PHPSHOP_ORDER_HISTORY_CUSTOMER_NOTIFIED' => 'Customer Notified?',
	'PHPSHOP_ORDER_STATUS_CHANGE' => 'Order Status Change',
	'PHPSHOP_ORDER_LIST_PRINT_LABEL' => 'Print Label',
	'PHPSHOP_ORDER_LIST_VOID_LABEL' => 'Void Label',
	'PHPSHOP_ORDER_LIST_TRACK' => 'Track',
	'VM_DOWNLOAD_STATS' => 'DOWNLOAD STATS',
	'VM_DOWNLOAD_NOTHING_LEFT' => 'no downloads remaining',
	'VM_DOWNLOAD_REENABLE' => 'Re-Enable Download',
	'VM_DOWNLOAD_REMAINING_DOWNLOADS' => 'Remaining Downloads',
	'VM_DOWNLOAD_RESEND_ID' => 'Resend Download ID',
	'VM_EXPIRY' => 'Expiry',
	'VM_UPDATE_STATUS' => 'Update Status',
	'VM_ORDER_LABEL_ORDERID_NOTVALID' => 'Please provide a valid, numeric, Order ID, not "{order_id}"',
	'VM_ORDER_LABEL_NOTFOUND' => 'Order record not found in shipping label database.',
	'VM_ORDER_LABEL_NEVERGENERATED' => 'Label has not been generated yet',
	'VM_ORDER_LABEL_CLASSCANNOT' => 'Class {ship_class} cannot get label images, why are we here?',
	'VM_ORDER_LABEL_SHIPPINGLABEL_LBL' => 'Shipping Label',
	'VM_ORDER_LABEL_SIGNATURENEVER' => 'Signature was never retrieved',
	'VM_ORDER_LABEL_TRACK_TITLE' => 'Track',
	'VM_ORDER_LABEL_VOID_TITLE' => 'Void Label',
	'VM_ORDER_LABEL_VOIDED_MSG' => 'Label for waybill {tracking_number} has been voided.',
	'VM_ORDER_PRINT_PO_IPADDRESS' => 'IP-ADDRESS',
	'VM_ORDER_STATUS_ICON_ALT' => 'Status Icon',
	'VM_ORDER_PAYMENT_CCV_CODE' => 'CVV Code',
	'VM_ORDER_NOTFOUND' => 'Order not found! It may have been deleted.',
	'PHPSHOP_ORDER_EDIT_ACTIONS' => 'Actions',
	'PHPSHOP_ORDER_EDIT' => 'Change Order Details',
	'PHPSHOP_ORDER_EDIT_ADD' => 'Add',
	'PHPSHOP_ORDER_EDIT_ADD_PRODUCT' => 'Add Product',
	'PHPSHOP_ORDER_EDIT_EDIT_ORDER' => 'Change Order',
	'PHPSHOP_ORDER_EDIT_ERROR_QUANTITY_MUST_BE_HIGHER_THAN_0' => 'Quantity must be greater than 0.',
	'PHPSHOP_ORDER_EDIT_PRODUCT_ADDED' => 'The Product was added to the Order',
	'PHPSHOP_ORDER_EDIT_PRODUCT_DELETED' => 'The Product was removed from this Order',
	'PHPSHOP_ORDER_EDIT_QUANTITY_UPDATED' => 'Quantity has been updated',
	'PHPSHOP_ORDER_EDIT_RETURN_PARENTS' => 'back to Parent Product',
	'PHPSHOP_ORDER_EDIT_CHOOSE_PRODUCT' => 'Select a Product',
	'PHPSHOP_ORDER_CHANGE_UPD_BILL' => 'Change Billto Address',
	'PHPSHOP_ORDER_CHANGE_UPD_SHIP' => 'Change Shipto Address',
	'PHPSHOP_ORDER_EDIT_SOMETHING_HAS_CHANGED' => ' has been changed',
	'PHPSHOP_ORDER_EDIT_CHOOSE_PRODUCT_BY_SKU' => 'Select SKU'
); $VM_LANG->initModule( 'order', $langvars );
?>
