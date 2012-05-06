<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/*
 * @version $Id: order.label_signature.php 1958 2009-10-08 20:09:57Z soeren_nb $
 * @package VirtueMart
 * @subpackage html
 */
mm_showMyFileName(__FILE__);

$order_id = vmGet($_REQUEST, 'order_id', null);
if (!is_numeric($order_id))
	die(str_replace('order_id',$order_id,$VM_LANG->_('VM_ORDER_LABEL_ORDERID_NOTVALID')));

$db = new ps_DB;

$q = "SELECT shipper_class, have_signature, signature_image ";
$q .= "FROM #__{vm}_shipping_label ";
$q .= "WHERE order_id='" . $order_id . "'";
$db->query($q);
if (!$db->next_record())
	die($VM_LANG->_('VM_ORDER_LABEL_NOTFOUND'));

if (!$db->f('have_signautre'))
	die($VM_LANG->_('VM_ORDER_LABEL_SIGNATURENEVER'));

include_once(CLASSPATH . "shipping/" . $db->f("shipper_class") . ".php");
eval("\$ship_class = new " . $db->f("shipper_class") . "();");
if (!is_callable(array($ship_class, 'get_signature_image')))
	die(str_replace('{ship_class}',$ship_class,$VM_LANG->_('VM_ORDER_LABEL_CLASSCANNOT')));

echo $ship_class->get_signature_image($order_id);
?>
