<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: checkout.index.php 2529 2010-09-05 15:48:16Z zanardi $
* @package VirtueMart
* @subpackage html
* @copyright Copyright (C) 2004-2010 VirtueMart Dev Team - All rights reserved.
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

require_once( CLASSPATH . "ps_checkout.php" );

global $mainframe, $vmLogger, $vars;

// PayPal API / Express

if( file_exists(CLASSPATH . 'payment/ps_paypal_api.php'))
{
	$lang = jfactory::getLanguage();
	$name= $lang->getBackwardLang();
	if( file_exists(CLASSPATH ."payment/paypal_api/languages/lang.".$name.".php"))
		include(CLASSPATH ."payment/paypal_api/languages/lang.".$name.".php");
	else
		include(CLASSPATH ."payment/paypal_api/languages/lang.english.php");
	
	if( file_exists(CLASSPATH . "payment/ps_paypal_api.cfg.php"))
	{		
		include_once( CLASSPATH . "payment/ps_paypal_api.cfg.php");
	}
	require_once( CLASSPATH . 'payment/ps_paypal_api.php');
	$paypalActive = ps_paypal_api::isActive();
	$ppex_checkout_details=ps_paypal_api::ppex_getCheckoutDetails();
}
else
{
	$paypalActive = false;
}

$paypal_express_checkout = vmGet($_REQUEST, 'ppex',null);
$paypal_express_checkout_payment = vmGet($_REQUEST, 'payment_method_ppex', null);
$paypal_express_checkout_cancel = vmGet($_REQUEST, 'ppex_cancel',null);
if($paypal_express_checkout_cancel) {
    //$GLOBALS['vmLogger']->warning( 'PayPal Zahlung abgebrochen!' );
	require_once( CLASSPATH . 'payment/ps_paypal_api.php');
    ps_paypal_api::destroyPaypalSession();
	$payment_method_id= $_GET['payment_method_id'] = $_REQUEST['payment_method_id'] = 0;
	if( !empty($_GET['ship_to_info_id']) && !empty( $_GET['shipping_rate_id'] ) ) {
		$_POST['checkout_this_step'][] = 'CHECK_OUT_GET_SHIPPING_ADDR';
		$_REQUEST['checkout_last_step'] = 2;
	}
}

if($paypal_express_checkout) {
	// Check for token and redirect to PayPal to get one, if not available yet
    $_SESSION['ppex_token'] = $ppex_token=ps_paypal_api::gettoken(1);
}
else if($paypal_express_checkout_payment === "2")
{
	// Check for token at payment select screen and redirect to PayPal to get one, if not available yet
	$_SESSION['ppex_token'] = $ppex_token=ps_paypal_api::gettoken(2);
}

$paypal_express_checkout = vmGet($_REQUEST, 'ppex_gecd',null);

if(isset($_SESSION['ppex_userdata']) && is_array($_SESSION['ppex_userdata']) && isset($_SESSION['ppex_token']) && $paypalActive) {

	if(!isset($auth['user_id']) || $auth['user_id'] <= 0)
	{
		ps_paypal_api::ppex_userLogin($auth);
	}

	ps_paypal_api::checkAddress($auth);
	
    $ship_to_info_id = vmGet( $_REQUEST, 'ship_to_info_id');
    $shipping_rate_id = urldecode(vmGet( $_REQUEST, "shipping_rate_id", null ));
	
    $paypal_api_payment_method_id = $payment_method_id = ps_paypal_api::getPaymentMethodId();
	
	$_REQUEST['payment_method_id'] = $payment_method_id;
	
    $Itemid = $sess->getShopItemid();
    if( $auth['user_id'] > 0 ) {
        $show_basket = true;
    } else {
        $show_basket = false;
    }
	
    $current_stage = ps_checkout::get_current_stage();
    $checkout_steps = ps_checkout::get_checkout_steps();
    /*if ($shipping_rate_id && $ship_to_info_id && $payment_method_id && !isset($_GET['checkout_stage'])) {
		$current_stage=count($checkout_steps);
	} elseif( $ship_to_info_id && $payment_method_id && empty($shipping_rate_id) && isset( $_GET['checkout_last_step'] ) && !isset($_GET['checkout_stage'])) {
		$_POST['checkout_this_step'] = $checkout_steps[$current_stage];
		$current_stage++;
	}*/

	if(isset($_SESSION['ppex_userdata']['payer_id']))
	{
		ps_paypal_api::checkOutStatus($auth, $checkout_steps, $current_stage, $ship_to_info_id, $paypal_express_checkout);
	}
} else {
    $ship_to_info_id = vmGet( $_REQUEST, 'ship_to_info_id');
    $shipping_rate_id = urldecode(vmGet( $_REQUEST, "shipping_rate_id", null ));
    $payment_method_id = vmGet( $_REQUEST, 'payment_method_id');
	
	if( file_exists(CLASSPATH . "payment/ps_paypal_api.cfg.php")) {
		include_once( CLASSPATH . "payment/ps_paypal_api.cfg.php");
		require_once( CLASSPATH . 'payment/ps_paypal_api.php');
		$paypal_api_payment_method_id = ps_paypal_api::getPaymentMethodId('ps_paypal_api');
	}
	
    $Itemid = $sess->getShopItemid();

    /* Decide, which Checkout Step is the next one 
    * $checkout_this_step controls the step thru the checkout process
    * we have the following steps

    * -CHECK_OUT_GET_SHIPPING_ADDR
    * let the user choose a shipto address

    * -CHECK_OUT_GET_SHIPPING_METHOD
    * let the user choose a shipto metho for the ship to address

    * -CHECK_OUT_GET_PAYMENT_METHOD
    * let the user choose a payment method

    * -CHECK_OUT_GET_FINAL_CONFIRMATION
    * shows a total summary including all payments, taxes, fees etc. and let the user confirm
    */
    if( $auth['user_id'] > 0 ) {
	    $show_basket = true;
    } else {
	    $show_basket = false;
    }
    $current_stage = ps_checkout::get_current_stage();
    $checkout_steps = ps_checkout::get_checkout_steps();

}

if( !empty( $paypal_api_payment_method_id ) && in_array('CHECK_OUT_GET_PAYMENT_METHOD', $checkout_steps[$current_stage]) ) {
    if($paypalActive)
	{
		// Paypal API / Express
		echo '<script type="text/javascript">window.addEvent("domready", function() {
		$$(\'label\').each( function(el) { if(el.htmlFor == "'.ps_paypal_api::getPaymentMethodName().'") { el.innerHTML = "Credit Card";} });
		});</script>';
	}
}

if( in_array('CHECK_OUT_GET_FINAL_CONFIRMATION', $checkout_steps[$current_stage]) ) {
    $next_page = 'checkout.thankyou';
    if( sizeof($checkout_steps[$current_stage]) > 1 ) {
    	include_once( PAGEPATH . 'basket.php' );
    } else {
    	include_once( PAGEPATH . 'ro_basket.php' );
    }
} else {
	$next_page = 'checkout.index';	
	include_once( PAGEPATH . 'basket.php' );
}

// Get the zone quantity after it has been calculated in the basket 
$zone_qty = vmGet( $vars, 'zone_qty');

//Check for express checkout from paypal
if(isset($_SESSION['ppex_userdata']) && is_array($_SESSION['ppex_userdata']) && isset($_SESSION['ppex_token']) && $paypalActive) 
{
	//If the $paypal_express_checkout is equal to 2
	//Then we just came from paypal express which originated from 
	//The payment selection screen
	if((int)$paypal_express_checkout == 2)
	{
		if(in_array('CHECK_OUT_GET_FINAL_CONFIRMATION', $checkout_steps[$current_stage]))
		{
			if($perm->is_registered_customer($auth['user_id']))
			{
				
	     		//Create our array like the form
				//Just in case so we don't mess anything up when
				//doing ps_checkout->process()
				$checkoutData = Array('option' => 'com_virtuemart',
									  'Itemid' => $Itemid,
									  'user_id' => (int)$auth['user_id'],
									  'page' => 'checkout.thankyou',
									  'func' => 'checkoutProcess',
									  'zone_qty' => $zone_qty,
									  'ship_to_info_id' => $ship_to_info_id,
									  'shipping_rate_id' => urlencode($shipping_rate_id),
									  'ship_method_id' => $shipping_rate_id,
									  'payment_method_id' => $payment_method_id,
									  'checkout_last_step' => '4',
									  'checkout_this_step' => array('CHECK_OUT_GET_FINAL_CONFIRMATION'));
				
				
				//Make sure to set the request variables before creating a new ps_checkout();
				//Just in case
				$_REQUEST['shipping_rate_id'] = urlencode($shipping_rate_id);
				$_REQUEST['ship_method_id'] = $shipping_rate_id;
				$_REQUEST['user_id'] = (int)$auth['user_id'];
				$_REQUEST['zone_qty'] = $zone_qty;
				$_REQUEST['ship_to_info_id'] = $ship_to_info_id;
				$_REQUEST['payment_method_id'] = $payment_method_id;
				
				//Set our $_SESSION variable for DoExpressCheckout
				//So we know which way we came from
				$_SESSION['ppex_cart_ecm'] = '1';
				
				$checkout = new ps_checkout();
				//Try to process the order
				//On Success redirect to checkout.thankyou
				if($checkout->process($checkoutData))
				{
					vmRedirect( $sess->url( 'index.php?page=checkout.thankyou&order_id='.$checkoutData['order_id'], false, false ) );
				}
			}
			else
			{
				$vmLogger->err(@$nvp_common_015);
			}
		}
	}
}

$theme = new $GLOBALS['VM_THEMECLASS']();

$theme->set_vars( // Import these values into the template files
	array( 'zone_qty' => $zone_qty,
			'ship_to_info_id' => $ship_to_info_id,
			'shipping_rate_id' => $shipping_rate_id,
			'current_stage' => $current_stage,
			'payment_method_id' => $payment_method_id,
			'weight_total' => $weight_total,
			'Itemid' => $Itemid
			)
	);
	
if ($cart["idx"] > 0) {
	
	echo '<h3>'. $VM_LANG->_('PHPSHOP_CHECKOUT_TITLE') .'</h3>';
	
    if (!defined('_MIN_POV_REACHED')) {
    	echo $basket_html;
    	?>
        <div align="center">
            <script type="text/javascript">alert('<?php echo $VM_LANG->_('PHPSHOP_CHECKOUT_ERR_MIN_POV',false) ?>');</script>
            <strong><?php echo $VM_LANG->_('PHPSHOP_CHECKOUT_ERR_MIN_POV') ?></strong><br />
            <strong><?php echo $VM_LANG->_('PHPSHOP_CHECKOUT_ERR_MIN_POV2') . " ".$CURRENCY_DISPLAY->getFullValue($_SESSION['minimum_pov']) ?></strong>
        </div><?php
        return;
    }
    
    // We have something in the Card so move on
    if ($perm->is_registered_customer($auth['user_id'])) { // user is logged in and a registered customer
	
	$basket_html .= '<form action="'. $sess->url( SECUREURL."index.php?page=".$next_page."&checkout_last_step=".$current_stage) .'" method="post" name="adminForm">  
		<input type="hidden" name="option" value="com_virtuemart" />
		<input type="hidden" name="Itemid" value="'. $Itemid .'" />
		<input type="hidden" name="user_id" value="'. $auth['user_id'] .'" />
		<input type="hidden" name="page" value="'. $next_page .'" />
		<input type="hidden" name="func" value="checkoutProcess" />
		<input type="hidden" name="zone_qty" value="'. $zone_qty .'" />
        <input type="hidden" name="ship_to_info_id" value="'. $ship_to_info_id .'" />
        <input type="hidden" name="shipping_rate_id" value="'. urlencode($shipping_rate_id) .'" />
        <input type="hidden" name="payment_method_id" value="'. $payment_method_id .'" />
        <input type="hidden" name="checkout_last_step" value="'. $current_stage .'" />';
		
		$theme->set( 'basket_html', $basket_html );
	    
	    // CHECK_OUT_GET_SHIPPING_ADDR
	    // Lets the user pick or add an alternative Shipping Address
	    if( in_array('CHECK_OUT_GET_SHIPPING_ADDR', $checkout_steps[$current_stage]) ) {
			echo '<a name="CHECK_OUT_GET_SHIPPING_ADDR"></a>';
			echo $theme->fetch( 'checkout/get_shipping_address.tpl.php');
			$theme->set('basket_html', '');
        }
        // CHECK_OUT_GET_SHIPPING_METHOD
        // Let the user pick a shipping method
        if( in_array('CHECK_OUT_GET_SHIPPING_METHOD', $checkout_steps[$current_stage]) ) {   
        	echo '<a name="CHECK_OUT_GET_SHIPPING_METHOD"></a>';
        	echo $theme->fetch( 'checkout/get_shipping_method.tpl.php');
			$theme->set('basket_html', '');
        }
        
        // -CHECK_OUT_GET_PAYMENT_METHOD
        // let the user choose a payment method
        if( in_array('CHECK_OUT_GET_PAYMENT_METHOD', $checkout_steps[$current_stage]) ) {   
        	echo '<a name="CHECK_OUT_GET_PAYMENT_METHOD"></a>';
				
				if(!empty( $paypal_api_payment_method_id ) && $paypalActive)
				{
					echo $theme->fetch('checkout/get_payment_method_paypal_ex.tpl.php');
				}
				else
				{
					echo $theme->fetch( 'checkout/get_payment_method.tpl.php');
				}
				
			$theme->set('basket_html', '');
        } 
        // -CHECK_OUT_GET_FINAL_CONFIRMATION
        // shows a total summary including all payments, taxes, fees etc. 
        if( in_array('CHECK_OUT_GET_FINAL_CONFIRMATION', $checkout_steps[$current_stage]) ) {   
        	echo '<a name="CHECK_OUT_GET_FINAL_CONFIRMATION"></a>';
			// Now let the user confirm
			echo $theme->fetch( 'checkout/get_final_confirmation.tpl.php');
			$theme->set('basket_html', '');
        }
        ?>
    <br /><?php 
		foreach( $checkout_steps[$current_stage] as $this_step ) {	
			echo '<input type="hidden" name="checkout_this_step[]" value="'.$this_step.'" />';
		}
		
 		// Set Dynamic Page Title: "Checkout: Step x of x"
		$ii = 0;
		for( $i = 1; $i < 5; $i++ ) {
			if( isset( $checkout_steps[$i] ) ) {
				$ii += 1;
				if( in_array($this_step, $checkout_steps[$i] ) ) {
					$mainframe->setPageTitle( sprintf( $VM_LANG->_('VM_CHECKOUT_TITLE_TAG'), $ii, count($checkout_steps) ));
					break;
				}
			}
		}
		
        if( !in_array('CHECK_OUT_GET_FINAL_CONFIRMATION', $checkout_steps[$current_stage]) ) {
         	?>
                <div align="center">
                <input type="submit" class="button" name="formSubmit" value="<?php echo $VM_LANG->_('PHPSHOP_CHECKOUT_NEXT');?> &gt;&gt;" />
                </div>
            <?php 
		}
		// Close the Checkout Form, which was opened in the first checkout template using the variable $basket_html
		echo '</form>';

         if( !in_array('CHECK_OUT_GET_FINAL_CONFIRMATION', $checkout_steps[$current_stage]) ) {
                echo "<script type=\"text/javascript\"><!--
                    function submit_order( form ) { return true; }
                    --></script>";
            }
        }
        
        else {
			
          if (!empty($auth['user_id'])) {
            // USER IS LOGGED IN, BUT NO REGISTERED CUSTOMER
            // WE NEED SOME ADDITIONAL INFORMATION HERE,
            // SO REDIRECT HIM TO shop/shopper_add
      		$vmLogger->info( $VM_LANG->_('PHPSHOP_NO_CUSTOMER',false) );
      
            include(PAGEPATH. 'checkout_register_form.php');
          }
      
          else { 
          	// user is not logged in
			echo $theme->fetch( 'checkout/login_registration.tpl.php' );
          }
    }
}
else {
	vmRedirect( $sess->url( 'index.php?page=shop.cart', false, false ) );
}
