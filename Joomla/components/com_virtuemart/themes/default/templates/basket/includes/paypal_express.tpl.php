<?php 
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id:couponField.tpl.php 431 2006-10-17 21:55:46 +0200 (Di, 17 Okt 2006) soeren_nb $
* @package VirtueMart
* @subpackage themes
* @copyright Copyright (C) 2008 soeren - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
* @author Erich Vinson
* http://virtuemart.net
*/

mm_showMyFileName( __FILE__ );

require_once( CLASSPATH . 'payment/ps_paypal_api.cfg.php');
require_once( CLASSPATH . 'payment/ps_paypal_api.php');

if($_REQUEST['page']=='shop.cart')
{
	ps_paypal_api::destroyPaypalSession();
}

// Paypal API / Express 
// ToDo: Replace Text with Language Variable
if (!defined('_MIN_POV_REACHED')) { ?>
    <span style="font-weight:bold;"><?php echo $VM_LANG->_('PHPSHOP_CHECKOUT_ERR_MIN_POV2') . " ".$CURRENCY_DISPLAY->getFullValue($_SESSION['minimum_pov']) ?></span>
<?php } else {
    if ($_REQUEST['page']=='shop.cart' && (PAYPAL_API_CART_BUTTON_ON == '1' || PAYPAL_API_CART_BUTTON_ON == 1)) {
        $paypal_express_href = $sess->url( $_SERVER['PHP_SELF'].'?page=checkout.index&ssl_redirect=1&ppex=1', true);
        echo '<a href="'.$paypal_express_href.'"><img src="https://www.paypal.com/'.$ppex_img_iso.'/i/btn/btn_xpressCheckout.gif" align="left" style="float: right;" alt="Paypal Express Checkout" title="Click here for Paypal Express Checkout!" align="right"></a>';
    }
}
?>