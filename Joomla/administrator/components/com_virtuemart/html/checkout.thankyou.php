<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
* This file is called after the order has been placed by the customer
*
* @version $Id: checkout.thankyou.php 2423 2010-06-02 21:33:48Z zanardi $
* @package VirtueMart
* @subpackage html
* @copyright Copyright (C) 2004-2010 soeren - All rights reserved.
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

require_once(CLASSPATH.'ps_product.php');

if( file_exists( CLASSPATH . "payment/ps_paypal_api.cfg.php" )) {
	//Include the paypal api so we can clear the data from the session
	include_once( CLASSPATH . "payment/ps_paypal_api.cfg.php");
	require_once( CLASSPATH . 'payment/ps_paypal_api.php');
}
global $mainframe, $vmLogger;

$ps_product= new ps_product;
$Itemid = $sess->getShopItemid();

global $vendor_currency, $user, $vars;

// Order_id is returned by checkoutComplete function
$order_id = $db->getEscaped(vmGet($vars, 'order_id' ) );

$print = vmRequest::getInt('print', 0);

/** Retrieve User Email **/
$q  = "SELECT * FROM `#__{vm}_order_user_info` WHERE `order_id`='$order_id' AND `address_type`='BT'";
$db->query( $q );
$db->next_record();
$old_user = '';
if( !empty( $user ) && is_object($user)) {
	$old_user = $user;
}
$user = $db->record[0];
$dbbt = $db->_clone( $db );

$user->email = $db->f("user_email");

/** Retrieve Order & Payment Info **/
$db = new ps_DB;
$q  = "SELECT * FROM (`#__{vm}_order_payment` LEFT JOIN `#__{vm}_payment_method` ";
$q .= "ON `#__{vm}_payment_method`.`payment_method_id`  = `#__{vm}_order_payment`.`payment_method_id`), `#__{vm}_orders` ";
$q .= "WHERE `#__{vm}_order_payment`.`order_id`='$order_id' ";
$q .= "AND `#__{vm}_orders`.`user_id`=" . $auth["user_id"] . " ";
$q .= "AND `#__{vm}_orders`.`order_id`='$order_id' ";
$db->query($q);
	
/**Check to see if we need to redirect the user for GiroPay**/
if(isset($_SESSION['ppex_userdata']) && isset($_SESSION['ppex_userdata']['redirectrequired']))
{
	$doRedirect = $_SESSION['ppex_userdata']['redirectrequired'];
}
else
{
    $doRedirect = 'false';
}

if(isset($_SESSION['ppex_token']))
{
    $token = $_SESSION['ppex_token'];
}
else
{
    $token = "";
}

/**Clear the PayPal API Session if it is set**/
if(isset($_SESSION['ppex_token']) || isset($_SESSION['ppex_userdata']))
{
	ps_paypal_api::destroyPaypalSession();
}

$vmLogger->debug("Redirect Required: ".$doRedirect);

//If redirect is true we need to do a redirect back to PayPal
if(strtolower($doRedirect) === 'true' || $doRedirect === true)
{
	$vmLogger->debug("Redirecting...");
	$DOMAIN = PAYPAL_API_DEBUG == 1 ? 'www.sandbox.paypal.com' : 'www.paypal.com';
    $payPalURL = 'https://'.$DOMAIN.'/webscr?cmd=_complete-express-checkout&token='.$token;
    header("Location: ".$payPalURL);
	$mainframe->close();
}
	
$tpl = new $GLOBALS['VM_THEMECLASS']();
$tpl->set( 'order_id', $order_id );
$tpl->set( 'ps_product', $ps_product );
$tpl->set( 'vendor_currency', $vendor_currency );
$tpl->set( 'user', $user );
$tpl->set( 'dbbt', $dbbt );
$tpl->set( 'db', $db );
	
echo $tpl->fetch( "pages/$page.tpl.php" );


if( !empty($old_user) && is_object($old_user)) {
	$user = $old_user;
}
?>
