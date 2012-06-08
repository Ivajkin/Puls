<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: ps_checkout.php 1830 2009-06-26 20:52:15Z Aravot $
* @package VirtueMart
* @subpackage classes
* @copyright Copyright (C) 2004-2011 VirtueMart Team - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.http://goo.gl/INjCq
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/ 

define("CHECK_OUT_GET_FINAL_BASKET", 1);
define("CHECK_OUT_GET_SHIPPING_ADDR", 2);
define("CHECK_OUT_GET_SHIPPING_METHOD", 3);
define("CHECK_OUT_GET_PAYMENT_METHOD", 4);
define("CHECK_OUT_GET_FINAL_CONFIRMATION", 99);

/**
 * The class contains the shop checkout code.  It is used to checkout
 * and order and collect payment information.
 *
 */
class vm_ps_checkout {
	var $_SHIPPING = null;

	var $_subtotal = null;
	var $_shipping = null;
	var $_shipping_tax = null;
	var $_payment_discount = null;
	var $_coupon_discount = null;
	var $_order_total = null;
	/** @var string An md5 hash of print_r( $cart, true ) to check wether the checkout values have to be renewed */
	var $_cartHash;

	/**
	 * Initiate Shipping Modules
	 */
	function vm_ps_checkout() {
		global $vendor_freeshipping, $vars, $PSHOP_SHIPPING_MODULES;

		// Make a snapshot of the current checkout configuration
		$this->generate_cart_hash();

		/* Ok, need to decide if we have a free Shipping amount > 0,
		* and IF the cart total is more than that Free Shipping amount,
		* let's set Order Shipping = 0
		*/

		$this->_subtotal = $this->get_order_subtotal($vars);
		
		if( $vendor_freeshipping > 0 && $vars['order_subtotal_withtax'] >= $vendor_freeshipping) {
			$PSHOP_SHIPPING_MODULES = Array( "free_shipping" );
			include_once( CLASSPATH. "shipping/free_shipping.php" );
			$this->_SHIPPING = new free_shipping();
		}
		elseif( !empty( $_REQUEST['shipping_rate_id'] )) {

			// Create a Shipping Object and assign it to the _SHIPPING attribute
			// We take the first Part of the Shipping Rate Id String
			// which holds the Class Name of the Shipping Module
			$rate_array = explode( "|", urldecode(vmGet($_REQUEST,"shipping_rate_id")) );
			$filename = basename( $rate_array[0] );
			if( $filename != '' && file_exists(CLASSPATH. "shipping/".$filename.".php")) {
				include_once( CLASSPATH. "shipping/".$filename.".php" );
				if( class_exists($filename) ) {
					$this->_SHIPPING = new $filename();
				}
			}
		}
		//$steps = ps_checkout::get_checkout_steps();
		if(empty($_REQUEST['ship_to_info_id']) && ps_checkout::noShipToNecessary()) {

			$db = new ps_DB();

			/* Select all the ship to information for this user id and
			* order by modification date; most recently changed to oldest
			*/
			$q  = "SELECT user_info_id from `#__{vm}_user_info` WHERE ";
			$q .= "user_id='" . $_SESSION['auth']["user_id"] . "' ";
			$q .= "AND address_type='BT'";
			$db->query($q);
			$db->next_record();

			$_REQUEST['ship_to_info_id'] = $db->f("user_info_id");
		}
	}
	/**
	 * Checks if Ship To can be skipped
	 *
	 * @return boolean
	 */
	function noShipToNecessary() {
		global $cart, $only_downloadable_products;
		if( NO_SHIPTO == '1') {
			return true;
		}
		if( !isset( $cart)) $cart = ps_cart::initCart();
		
		if( ENABLE_DOWNLOADS == '1') {
			$not_downloadable = false;
			require_once( CLASSPATH .'ps_product.php');
			for($i = 0; $i < $cart["idx"]; $i++) {
				
				if( !ps_product::is_downloadable($cart[$i]['product_id']) ) {					
					$not_downloadable = true;
					break;
				}
			}
			return !$not_downloadable;
		}
		return false;
	}
	function noShippingMethodNecessary() {
		global $cart, $only_downloadable_products;
		if( NO_SHIPPING == '1') {
			return true;
		}
		
		if( !isset( $cart)) $cart = ps_cart::initCart();
		
		if( ENABLE_DOWNLOADS == '1') {
			$not_downloadable = false;
			require_once( CLASSPATH .'ps_product.php');
			for($i = 0; $i < $cart["idx"]; $i++) {
				if( !ps_product::is_downloadable($cart[$i]['product_id']) ) {
					$not_downloadable = true;
					break;
				}
			}
			return !$not_downloadable;
		}
		return false;
	}
	function noShippingNecessary() {
		return $this->noShipToNecessary() && $this->noShippingMethodNecessary();
	}
	/**
	 * Retrieve an array with all order steps and their details
	 *
	 * @return array
	 */
	function get_checkout_steps() {
		global $VM_CHECKOUT_MODULES;
		$stepnames = array_keys( $VM_CHECKOUT_MODULES );
		$steps = array();
		$i = 0;
		$last_order = 0;
		foreach( $VM_CHECKOUT_MODULES as $step ) {
			// Get the stepname from the array key
			$stepname = current($stepnames);
			next($stepnames);
			
			switch( $stepname ) {
				case 'CHECK_OUT_GET_SHIPPING_ADDR':
					if( ps_checkout::noShipToNecessary() ) $step['enabled'] = 0;
					break;
				case 'CHECK_OUT_GET_SHIPPING_METHOD':
					if( ps_checkout::noShippingMethodNecessary() ) $step['enabled'] = 0;
					break;
			}
			
			
			if( $step['enabled'] == 1 ) {
				$steps[$step['order']][] = $stepname;
			}
			
		}
		ksort( $steps );
		
		return $steps;
	}
	/**
	 * Retrieve the key name of the current checkout step
	 *
	 * @return string
	 */
	function get_current_stage() {
		$steps = ps_checkout::get_checkout_steps();
		$stage = key( $steps ); // $steps is sorted by key, so the first key is the first stage
		// First check the REQUEST parameters for other steps
		if( !empty( $_REQUEST['checkout_last_step'] ) && empty( $_POST['checkout_this_step'] )) {
			// Make sure we have an integer (max 4)
			$checkout_step = abs( min( $_REQUEST['checkout_last_step'], 4 ) );
			if( isset( $steps[$checkout_step] )) {
				return $checkout_step; // it's a valid step
			}
		}
		$checkout_step = (int)vmGet( $_REQUEST, 'checkout_stage' );
		if( isset( $steps[$checkout_step] )) {
			return $checkout_step; // it's a valid step
		}
		// Else: we have no alternative steps given by REQUEST
		while ($step = current($steps)) {
			if( !empty($_POST['checkout_this_step']) )  {
				foreach( $step as $stepname ) {
					if( in_array( $stepname, $_POST['checkout_this_step'])) {
						next($steps);
						$key = key( $steps );
						if( empty( $key )) {
							// We are beyond the last index of the array and need to go "back" to the last index
							end( $steps );
						}
						//echo "Stage: ".key( $steps );
						return key($steps);
						
					}
				}
			}
			next($steps);
		}
		return $stage;
	}
	/**
	 * Displays the "checkout bar" using the checkout bar template
	 *
	 * @param array $steps_to_do Array holding all steps the customer has to make
	 * @param array $step_msg Array containing the step messages
	 * @param int $step_count Number of steps to make
	 * @param int $highlighted_step The index of the recent step
	 */
	function show_checkout_bar($highlighted_step=null) {

		global $sess, $ship_to_info_id, $shipping_rate_id, $VM_LANG;
		
		if (SHOW_CHECKOUT_BAR != '1' || defined('VM_CHECKOUT_BAR_LOADED')) {
			return;
		}
	    // Let's assemble the steps
	    $steps = ps_checkout::get_checkout_steps();
	    $step_count = sizeof( $steps );
	    $steps_tmp = $steps;
	    $i = 0;
	    foreach( $steps as $step ) {	    	
	    	foreach( $step as $step_name ) {
	    		switch ( $step_name ) {
	    			case 'CHECK_OUT_GET_SHIPPING_ADDR':
	    				$step_msg = $VM_LANG->_('PHPSHOP_ADD_SHIPTO_2');
	    				break;
	    			case 'CHECK_OUT_GET_SHIPPING_METHOD':
	    				$step_msg = $VM_LANG->_('PHPSHOP_ISSHIP_LIST_CARRIER_LBL');
	    				break;
	    			case 'CHECK_OUT_GET_PAYMENT_METHOD':
	    				$step_msg = $VM_LANG->_('PHPSHOP_ORDER_PRINT_PAYMENT_LBL');
	    				break;
	    			case 'CHECK_OUT_GET_FINAL_CONFIRMATION':
	    				$step_msg = $VM_LANG->_('PHPSHOP_CHECKOUT_CONF_PAYINFO_COMPORDER');
	    				break;
	    		}
	    		$steps_to_do[$i][] = array('step_name' => $step_name,
	    								'step_msg' => $step_msg,
	    								'step_order' => key($steps_tmp) );
			
	    	}
    		next( $steps_tmp );
	    	$i++;
	    }
		if( !$highlighted_step  ) {
			$highlighted_step = ps_checkout::get_current_stage(); 
    	}
    	$theme = new $GLOBALS['VM_THEMECLASS']();
    	$theme->set_vars( array( 'step_count' => $step_count,
    							'steps_to_do' => $steps_to_do,
    							'steps' => $steps,
    							'highlighted_step' => $highlighted_step,
    							'ship_to_info_id' => vmGet($_REQUEST, 'ship_to_info_id'),
    							'shipping_rate_id' => vmGet( $_REQUEST, 'shipping_rate_id')
    						) );
    						
		echo $theme->fetch( 'checkout/checkout_bar.tpl.php');
		define('VM_CHECKOUT_BAR_LOADED', 1 );
	}

	/**
	 * Called to validate the form values before the order is stored
	 * 
	 * @author gday
	 * @author soeren
	 * 
	 * @param array $d
	 * @return boolean
	 */
	function validate_form(&$d) {
		global $VM_LANG, $PSHOP_SHIPPING_MODULES, $vmLogger;

		$db = new ps_DB;

		$auth = $_SESSION['auth'];
		$cart = $_SESSION['cart'];

		if (!$cart["idx"]) {
			$q  = "SELECT order_id FROM #__{vm}_orders WHERE user_id='" . $auth["user_id"] . "' ";
			$q .= "ORDER BY cdate DESC";
			$db->query($q);
			$db->next_record();
			$d["order_id"] = $db->f("order_id");
			return False;
		}
		if( PSHOP_AGREE_TO_TOS_ONORDER == '1' ) {
			if( empty( $d["agreed"] )) {
				$vmLogger->warning( $VM_LANG->_('PHPSHOP_AGREE_TO_TOS',false) );
				return false;
			}
		}

		if ( !ps_checkout::noShippingMethodNecessary() ) {
			if ( !$this->validate_shipping_method($d) ) {
				return False;
			}
		}
		if ( !$this->validate_payment_method( $d, false )) {
			return false;
		}
		if( CHECK_STOCK == '1' ) {
			for($i = 0; $i < $cart["idx"]; $i++) {

				$quantity_in_stock = ps_product::get_field($cart[$i]["product_id"], 'product_in_stock');
				$product_name = ps_product::get_field($cart[$i]["product_id"], 'product_name');
				if( $cart[$i]["quantity"] > $quantity_in_stock ) {
					$vmLogger->err( 'The Quantity for the Product "'.$product_name.'" in your Cart ('.$cart[$i]["quantity"].') exceeds the Quantity in Stock ('.$quantity_in_stock.'). 
												We are very sorry for this Inconvenience, but you you need to lower the Quantity in Cart for this Product.');
					return false;
				}
			}
		}
		// calculate the unix timestamp for the specified expiration date
		// default the day to the 1st
		$expire_timestamp = @mktime(0,0,0,$_SESSION["ccdata"]["order_payment_expire_month"], 15,$_SESSION["ccdata"]["order_payment_expire_year"]);
		$_SESSION["ccdata"]["order_payment_expire"] = $expire_timestamp;

		return True;
	}

	/**
	 * Validates the variables prior to adding an order
	 *
	 * @param array $d
	 * @return boolean
	 */
	function validate_add(&$d) {
		global $auth, $VM_LANG, $vmLogger;

		require_once(CLASSPATH.'ps_payment_method.php');
		$ps_payment_method = new ps_payment_method;
		
		if( empty( $auth['user_id'] ) ) {
			$vmLogger->err('Sorry, but it is not possible to order without a User ID. 
										Please contact the Store Administrator if this Error occurs again.');
			return false;
		}
		if (!ps_checkout::noShipToNecessary()) {
			if (empty($d["ship_to_info_id"])) {
				$vmLogger->err( $VM_LANG->_('PHPSHOP_CHECKOUT_ERR_NO_SHIPTO',false) );
				return False;
			}
		}
		/*
		if (!$d["payment_method_id"]) {
			$vmLogger->err( $VM_LANG->_('PHPSHOP_CHECKOUT_MSG_4',false) );
			return False;
		}*/
		if ($ps_payment_method->is_creditcard(@$d["payment_method_id"])) {

			if (empty($_SESSION["ccdata"]["order_payment_number"])) {
				$vmLogger->err( $VM_LANG->_('PHPSHOP_CHECKOUT_ERR_NO_CCNR',false) );
				return False;
			}

			if(!$ps_payment_method->validate_payment($d["payment_method_id"],
					$_SESSION["ccdata"]["order_payment_number"])) {
				$vmLogger->err( $VM_LANG->_('PHPSHOP_CHECKOUT_ERR_CCNUM_INV',false) );
				return False;
			}

			if(empty( $_SESSION["ccdata"]["order_payment_expire"])) {
				$vmLogger->err( $VM_LANG->_('PHPSHOP_CHECKOUT_ERR_CCDATE_INV',false) );
				return False;
			}
		}

		return True;
	}

	function validate_shipto(&$d) {
		//TODO to be implemented
	}
	/**
	 * Called to validate the shipping_method
	 *
	 * @param array $d
	 * @return boolean
	 */
	function validate_shipping_method(&$d) {
		global $VM_LANG, $PSHOP_SHIPPING_MODULES, $vmLogger;
		
		if( empty($d['shipping_rate_id']) ) {
			$vmLogger->err( $VM_LANG->_('PHPSHOP_CHECKOUT_ERR_NO_SHIP',false) );
			return false;
		}
		
		if( is_callable( array($this->_SHIPPING, 'validate') )) {
			
			if(!$this->_SHIPPING->validate( $d )) {
				$vmLogger->err( $VM_LANG->_('PHPSHOP_CHECKOUT_ERR_OTHER_SHIP',false) );
				return false;
			}
		}
		return true;
	}

	/**
	 * Called to validate the payment_method
	 * If payment with CreditCard is used, than the Data must be in stored in the session
	 * This has be done to prevent sending the CreditCard Number back in hidden fields
	 * If the parameter $is_test is true the Number Visa Creditcard number 4111 1111 1111 1111
	 *
	 * @param array $d
	 * @param boolean $is_test
	 * @return boolean
	 */
	function validate_payment_method(&$d, $is_test) {
		global $VM_LANG, $vmLogger, $order_total;

		$auth = $_SESSION['auth'];
		$cart = $_SESSION['cart'];
		
		// We don't need to validate a payment method when
		// the user has no order total he should pay
		if( empty( $_REQUEST['order_total'])) {
			
			if( isset( $d['order_total'])) {
				if( round( $d['order_total'], 2 ) <= 0.00 ) {
					return true;
				}
			}
			if( isset($order_total) && $order_total <= 0.00 ) {
				return true;
			}
		}
		if (!isset($d["payment_method_id"]) || $d["payment_method_id"]==0 ) {
			$vmLogger->err( $VM_LANG->_('PHPSHOP_CHECKOUT_ERR_NO_PAYM',false) );
			return false;
		}
		require_once(CLASSPATH.'ps_payment_method.php');
		$ps_payment_method = new ps_payment_method;

		$dbp = new ps_DB; //DB Payment_method

		// Now Check if all needed Payment Information are entered
		// Bank Information is found in the User_Info
		$w  = "SELECT `enable_processor` FROM `#__{vm}_payment_method` WHERE ";
		$w .= "payment_method_id=" .  (int)$d["payment_method_id"];
		$dbp->query($w);
		$dbp->next_record();
		
		if (($dbp->f("enable_processor") == "Y") 
			|| ($dbp->f("enable_processor") == "")) {

			// Creditcard
			if (empty( $_SESSION['ccdata']['creditcard_code']) ) {
				$vmLogger->err( $VM_LANG->_('VM_CHECKOUT_ERR_CCTYPE') );
				return false;
			}

			// $_SESSION['ccdata'] = $ccdata;
			// The Data should be in the session
			if (!isset($_SESSION['ccdata'])) { //Not? Then Error
				$vmLogger->err( $VM_LANG->_('PHPSHOP_CHECKOUT_ERR_NO_CCDATA',false) );
				return False;
			}

			if (!$_SESSION['ccdata']['order_payment_number']) {
				$vmLogger->err( $VM_LANG->_('PHPSHOP_CHECKOUT_ERR_NO_CCNR_FOUND',false) );
				return False;
			}

			// CREDIT CARD NUMBER CHECK
			// USING THE CREDIT CARD CLASS in ps_payment
			if(!$ps_payment_method->validate_payment( $_SESSION['ccdata']['creditcard_code'], $_SESSION['ccdata']['order_payment_number'])) {
				$vmLogger->err( $VM_LANG->_('PHPSHOP_CHECKOUT_ERR_NO_CCDATE',false) );
				return False;
			}

			if (!$is_test) {
				$payment_number = preg_replace("/ |-/", "", $_SESSION['ccdata']['order_payment_number']);
				if ($payment_number == "4111111111111111") {
					$vmLogger->warning( $VM_LANG->_('PHPSHOP_CHECKOUT_ERR_TEST',false) );
					return False;
				}
			}
			if(!empty($_SESSION['ccdata']['need_card_code']) && empty($_SESSION['ccdata']['credit_card_code'])) {
				$vmLogger->err( $VM_LANG->_('PHPSHOP_CUSTOMER_CVV2_ERROR',false) );
				return False;
			}
			if(!$_SESSION['ccdata']['order_payment_expire_month']) {
				$vmLogger->err( $VM_LANG->_('PHPSHOP_CHECKOUT_ERR_NO_CCMON',false) );
				return False;
			}
			if(!$_SESSION['ccdata']['order_payment_expire_year']) {
				$vmLogger->err( $VM_LANG->_('PHPSHOP_CHECKOUT_ERR_NO_CCYEAR',false) );
				return False;
			}
			$date = getdate( time() );
			if ($_SESSION['ccdata']['order_payment_expire_year'] < $date["year"] or
			($_SESSION['ccdata']['order_payment_expire_year'] == $date["year"] and
			$_SESSION['ccdata']['order_payment_expire_month'] < $date["mon"])) {
				$vmLogger->err( $VM_LANG->_('PHPSHOP_CHECKOUT_ERR_CCDATE_INV',false) );
				return False;
			}
			return True;
		}
		elseif ($dbp->f("enable_processor") == "B") {
			$_SESSION['ccdata']['creditcard_code'] = "";
			$_SESSION['ccdata']['order_payment_name']  = "";
			$_SESSION['ccdata']['order_payment_number']  = "";
			$_SESSION['ccdata']['order_payment_expire_month'] = "";
			$_SESSION['ccdata']['order_payment_expire_year'] = "";
			// Bank Account
			require_once( CLASSPATH . 'ps_user.php' );
			$dbu =& ps_user::getUserInfo( $auth["user_id"], array( 'bank_account_holder','bank_iban','bank_account_nr','bank_sort_code','bank_name' ) ); 

			if ( $dbu->f("bank_account_holder") == "" || $dbu->f("bank_account_nr") =="" ) {
				if( !empty($d['bank_account_holder']) && !empty($d['bank_account_nr'])) {
					// Insert the given data
					$fields = array( 'bank_account_holder' => $d['bank_account_holder'],
							'bank_account_nr' => $d['bank_account_nr'],
							'bank_sort_code' => $d['bank_sort_code'],
							'bank_name' => $d['bank_name'],
							'bank_iban' => $d['bank_iban']
							);
					ps_user::setUserInfo( $fields, $auth["user_id"] );

					$dbu =& ps_user::getUserInfo( $auth["user_id"], array( 'bank_account_holder','bank_iban','bank_account_nr','bank_sort_code','bank_name' ) ); 
				}
				else {
					$vmLogger->err( $VM_LANG->_('PHPSHOP_CHECKOUT_ERR_NO_USER_DATA',false) );
					return False;
				}
			}
			if ($dbu->f("bank_account_holder") == ""){
				$vmLogger->err( $VM_LANG->_('PHPSHOP_CHECKOUT_ERR_NO_BA_HOLDER_NAME',false) );
				return False;
			}
			if (($dbu->f("bank_iban") == "") and
			($dbu->f("bank_account_nr") =="")) {
				$vmLogger->err( $VM_LANG->_('PHPSHOP_CHECKOUT_ERR_NO_IBAN',false) );
				return False;
			}
			if ($dbu->f("bank_iban") == "") {
				if ($dbu->f("bank_account_nr") == ""){
					$vmLogger->err( $VM_LANG->_('PHPSHOP_CHECKOUT_ERR_NO_BA_NUM',false) );
					return False;
				}
				if ($dbu->f("bank_sort_code") == ""){
					$vmLogger->err( $VM_LANG->_('PHPSHOP_CHECKOUT_ERR_NO_BANK_SORT',false) );
					return False;
				}
				if ($dbu->f("bank_name") == ""){
					$vmLogger->err( $VM_LANG->_('PHPSHOP_CHECKOUT_ERR_NO_BANK_NAME',false) );
					return False;
				}
			}
		}
		else {
			$_SESSION['ccdata']['creditcard_code'] = '';
			$_SESSION['ccdata']['order_payment_name']  = "";
			$_SESSION['ccdata']['order_payment_number']  = "";
			$_SESSION['ccdata']['order_payment_expire_month'] = "";
			$_SESSION['ccdata']['order_payment_expire_year'] = "";
		}
		// Enter additional Payment check procedures here if neccessary

		return True;
	}

	/**
	 * Update order details
	 * CURRENTLY UNUSED
	 *
	 * @param array $d
	 * @return boolean
	 */
	function update(&$d) {
		global $vmLogger;
		
		$db = new ps_DB;
		$timestamp = time();


		if ($this->validate_update($d)) {
			return True;
		}
		else {
			$vmLogger->err( $this->error );
			return False;
		}
	}

	/**
	 * Control Function for the Checkout Process
	 * @author Ekkhard Domning
	 * @author soeren
	 * @param array $d
	 * @return boolean
	 */
	function process(&$d) {
		global $checkout_this_step, $sess,$VM_LANG, $vmLogger;
		$ccdata = array();

		if( empty($d["checkout_this_step"]) || !is_array(@$d["checkout_this_step"])) {
			$vmLogger->err( $VM_LANG->_('PHPSHOP_CHECKOUT_ERR_NO_VALID_STEP',false) );
			return false;
		}
		
		foreach($d["checkout_this_step"] as $checkout_this_step) {
		
			switch($checkout_this_step) {
				
				case 'CHECK_OUT_GET_FINAL_BASKET' :
					break;
	
				case 'CHECK_OUT_GET_SHIPPING_ADDR' :		
					// The User has choosen a Shipping address
					if (empty($d["ship_to_info_id"])) {
						$vmLogger->err( $VM_LANG->_('PHPSHOP_CHECKOUT_ERR_NO_SHIPTO',false) );
						unset( $_POST['checkout_this_step']);
						return False;
					}
					break;
	
				case 'CHECK_OUT_GET_SHIPPING_METHOD':
					// The User has choosen a Shipping method
					if (!$this->validate_shipping_method($d)) {
						unset( $_POST['checkout_this_step']);
						return false;
					}
					break;
	
				case 'CHECK_OUT_GET_PAYMENT_METHOD':
					
					// The User has choosen a payment method
					$_SESSION['ccdata']['order_payment_name'] = @$d['order_payment_name'];
					// VISA, AMEX, DISCOVER....
					$_SESSION['ccdata']['creditcard_code'] = @$d['creditcard_code'];
					$_SESSION['ccdata']['order_payment_number'] = @$d['order_payment_number'];
					$_SESSION['ccdata']['order_payment_expire_month'] = @$d['order_payment_expire_month'];
					$_SESSION['ccdata']['order_payment_expire_year'] = @$d['order_payment_expire_year'];
					// 3-digit Security Code (CVV)
					$_SESSION['ccdata']['credit_card_code'] = @$d['credit_card_code'];
		
					if (!$this->validate_payment_method($d, false)) { //Change false to true to Let the user play with the VISA Testnumber
						unset( $_POST['checkout_this_step']);
						return false;
					}
					
					break;
	
				case 'CHECK_OUT_GET_FINAL_CONFIRMATION':
	
					// The User wants to order now, validate everything, if OK than Add immeditialtly
					return( $this->add( $d ) );
	
				default:
					$vmLogger->crit( "CheckOut step ($checkout_this_step) is undefined!" );
					return false;
	
			} // end switch
		}
		return true;
	} // end function process

	/**
	 * Prints the List of all shipping addresses of a user
	 *
	 * @param unknown_type $user_id
	 * @param unknown_type $name
	 * @param unknown_type $value
	 */
	function ship_to_addresses_radio($user_id, $name, $value) {
		echo ps_checkout::list_addresses( $user_id, $name, $value );
	}
	/**
	 * Creates a Radio List of all shipping addresses of a user
	 *
	 * @param int $user_id
	 * @param string $name
	 * @param string $value
	 */
	function list_addresses( $user_id, $name, $value ) {
		global $sess,$VM_LANG;

		$db = new ps_DB;

		/* Select all the ship to information for this user id and
		* order by modification date; most recently changed to oldest
		*/
		$q  = "SELECT * from #__{vm}_user_info WHERE ";
		$q .= "user_id=" . (int)$user_id . ' ';
		$q .= "AND address_type='BT'";
		$db->query($q);
		$db->next_record();

		$bt_user_info_id = $db->f("user_info_id");

		$q  = "SELECT * FROM #__{vm}_user_info i ";
		$q .= "INNER JOIN #__{vm}_country c ON (i.country=c.country_3_code) ";
		$q .= "LEFT JOIN #__{vm}_state s ON (i.state=s.state_2_code AND s.country_id=c.country_id) ";
		$q .= "WHERE user_id =" . (int)$user_id . ' ';
		$q .= "AND address_type = 'ST' ";
		$q .= "ORDER by address_type_name, mdate DESC";

		$db->query($q);
		
		$theme = vmTemplate::getInstance();
		$theme->set_vars(array('db' => $db,
								'user_id' => $user_id,
								'name' => $name,
								'value' => $value,
								'bt_user_info_id' => $bt_user_info_id,
						 	)
						 );

		echo $theme->fetch( 'checkout/list_shipto_addresses.tpl.php');
	}

	/**
	 * Fetches the address information for the currently logged in user
	 *
	 * @param string $address_type Can be BT (Bill To) or ST (Shipto address)
	 */
	function display_address($address_type='BT') {
		$auth = $_SESSION['auth'];
		
		$address_type = $address_type == 'BT' ? $address_type : 'ST';
		
		$db = new ps_DB;
		$q  = "SELECT * FROM #__{vm}_user_info i ";
		$q .= "INNER JOIN #__{vm}_country c ON (i.country=c.country_3_code OR i.country=c.country_2_code) ";
		$q .= "LEFT JOIN #__{vm}_state s ON (i.state=s.state_2_code AND s.country_id=c.country_id) ";
		$q .= "WHERE user_id='" . $auth["user_id"] . "' ";
		$q .= "AND address_type='BT'";
		$db->query($q);
		$db->next_record();
		$theme = new $GLOBALS['VM_THEMECLASS']();
		$theme->set('db', $db );
		
		return $theme->fetch('checkout/customer_info.tpl.php');
		
	}
	/**
	 * Lists Shipping Methods of all published Shipping Modules
	 *
	 * @param string $ship_to_info_id
	 * @param string $shipping_method_id
	 */
	function list_shipping_methods( $ship_to_info_id=null, $shipping_method_id=null ) {
		global $PSHOP_SHIPPING_MODULES, $vmLogger, $auth, $weight_total;
		
		if( empty( $ship_to_info_id )) {
		    // Get the Bill to user_info_id
		    $database = new ps_DB();
		    $database->setQuery( "SELECT user_info_id FROM #__{vm}_user_info WHERE user_id=".$auth['user_id']." AND address_type='BT'" );
		    $vars["ship_to_info_id"] = $_REQUEST['ship_to_info_id'] = $database->loadResult();
		} else {
			$vars['ship_to_info_id'] = $ship_to_info_id;
		}
		$vars['shipping_rate_id'] = $shipping_method_id;
		$vars["weight"] = $weight_total;
		$vars['zone_qty'] = vmRequest::getInt( 'zone_qty', 0 );
		$i = 0;

		$theme = new $GLOBALS['VM_THEMECLASS']();
		$theme->set_vars(array('vars' => $vars,
								'PSHOP_SHIPPING_MODULES' => $PSHOP_SHIPPING_MODULES
						 	)
						 );

		echo $theme->fetch( 'checkout/list_shipping_methods.tpl.php');
		
	}
	/**
	 * Lists the payment methods of all available payment modules
	 * @static 
	 * @param int $payment_method_id
	 */
	function list_payment_methods( $payment_method_id=0 ) {

		global $order_total, $sess, $VM_CHECKOUT_MODULES;
		$ps_vendor_id = $_SESSION['ps_vendor_id'];
		$auth = $_SESSION['auth'];
		
		$ship_to_info_id = vmGet( $_REQUEST, 'ship_to_info_id' );
		$shipping_rate_id = vmGet( $_REQUEST, 'shipping_rate_id' );
		
        require_once(CLASSPATH . 'ps_payment_method.php');
        $ps_payment_method = new ps_payment_method;
		require_once( CLASSPATH. 'ps_creditcard.php' );
	    $ps_creditcard = new ps_creditcard();
	    $count = 0;

		// Do we have Credit Card Payments?
		$exclude_ppapi = '';
   		if ( PAYPAL_API_DIRECT_PAYMENT_ON == 0 ) {
			$exclude_ppapi = "AND #__{vm}_payment_method.payment_method_code <> 'PP_API' ";
		}
		$db_cc  = new ps_DB;
		$q = "SELECT * from #__{vm}_payment_method,#__{vm}_shopper_group WHERE ";
		$q .= "#__{vm}_payment_method.shopper_group_id=#__{vm}_shopper_group.shopper_group_id ";
		$q .= "AND (#__{vm}_payment_method.shopper_group_id='".$auth['shopper_group_id']."' ";
		$q .= "OR #__{vm}_shopper_group.default='1') ";
		$q .= "AND (enable_processor='' OR enable_processor='Y') ";
		$q .= "AND payment_enabled='Y' ";
		$q .= "AND #__{vm}_payment_method.vendor_id='$ps_vendor_id' ";
		$q .= $exclude_ppapi;
		$q .= " ORDER BY list_order";
		$db_cc->query($q);
		if ($db_cc->num_rows()) {
			$first_payment_method_id = $db_cc->f("payment_method_id");
			$count += $db_cc->num_rows();
		    $cc_payments=true;
		}
		else {
		    $cc_payments=false;
		}
		
		$db_nocc  = new ps_DB;
		$q = "SELECT * from #__{vm}_payment_method,#__{vm}_shopper_group WHERE ";
		$q .= "#__{vm}_payment_method.shopper_group_id=#__{vm}_shopper_group.shopper_group_id ";
		$q .= "AND (#__{vm}_payment_method.shopper_group_id='".$auth['shopper_group_id']."' ";
		$q .= "OR #__{vm}_shopper_group.default='1') ";
		$q .= "AND (enable_processor='B' OR enable_processor='N' OR enable_processor='P') ";
		$q .= "AND payment_enabled='Y' ";
		$q .= "AND #__{vm}_payment_method.vendor_id='$ps_vendor_id' ";
		$q .= " ORDER BY list_order";
		$db_nocc->query($q);
		if ($db_nocc->next_record()) {
		    $nocc_payments=true;
		    $first_payment_method_id = $db_nocc->f("payment_method_id");
		    $count += $db_nocc->num_rows();
		    $db_nocc->reset();
		}
		else {
		    $nocc_payments=false;
		}
		
		// Is PayPal API enabled
	    $db_pp  = new ps_DB;
		$q = "SELECT * from #__{vm}_payment_method,#__{vm}_shopper_group WHERE ";
		$q .= "#__{vm}_payment_method.shopper_group_id=#__{vm}_shopper_group.shopper_group_id ";
		$q .= "AND (#__{vm}_payment_method.shopper_group_id='".$auth['shopper_group_id']."' ";
		$q .= "OR #__{vm}_shopper_group.default='1') ";
		$q .= "AND #__{vm}_payment_method.payment_method_code = 'PP_API'  ";
		$q .= "AND payment_enabled='Y' ";
		$q .= "AND #__{vm}_payment_method.vendor_id='$ps_vendor_id' ";
		$db_pp->query($q);
		if ($db_pp->next_record()) {
		    $pp_payment=true;
		    $first_payment_method_id = $db_pp->f("payment_method_id");
		} else {
		    $pp_payment=false;
		}

        // Redirect to the last step when there's only one payment method
		if( $VM_CHECKOUT_MODULES['CHECK_OUT_GET_PAYMENT_METHOD']['order'] != $VM_CHECKOUT_MODULES['CHECK_OUT_GET_FINAL_CONFIRMATION']['order'] ) {
			// order of the following two redirections swapped by JK to ensure there is no payment method where there is no order_total 
			if( isset($order_total) && $order_total <= 0.00 ) {
				// In case the order total is less than or equal zero, we don't need a payment method
				vmRedirect($sess->url(SECUREURL.basename($_SERVER['PHP_SELF'])."?page=checkout.index&ship_to_info_id=$ship_to_info_id&shipping_rate_id=".urlencode($shipping_rate_id)."&checkout_stage=".$VM_CHECKOUT_MODULES['CHECK_OUT_GET_FINAL_CONFIRMATION']['order'], false, false),"");
			}
			elseif ($count <= 1 && $cc_payments==false) {
				vmRedirect($sess->url(SECUREURL.basename($_SERVER['PHP_SELF'])."?page=checkout.index&payment_method_id=$first_payment_method_id&ship_to_info_id=$ship_to_info_id&shipping_rate_id=".urlencode($shipping_rate_id)."&checkout_stage=".$VM_CHECKOUT_MODULES['CHECK_OUT_GET_FINAL_CONFIRMATION']['order'], false, false ),"");
			}
		}
		$theme = new $GLOBALS['VM_THEMECLASS']();
		$theme->set_vars(array('db_nocc' => $db_nocc,
								'db_cc' => $db_cc,
								'nocc_payments' => $nocc_payments,
								'payment_method_id' => $payment_method_id,
								'first_payment_method_id' => $first_payment_method_id,
								'count' => $count,
								'cc_payments' => $cc_payments,
								'ps_creditcard' => $ps_creditcard,
								'ps_payment_method' => $ps_payment_method
						 	)
						 );

		echo $theme->fetch( 'checkout/list_payment_methods.tpl.php');
		
	}
	/**
	 * This is the main function which stores the order information in the database
	 * 
	 * @author gday, soeren, many others!
	 * @param array $d The REQUEST/$vars array
	 * @return boolean
	 */
	function add( &$d ) {
		global $order_tax_details, $afid, $VM_LANG, $auth, $my, $mosConfig_offset,
		$vmLogger, $vmInputFilter, $discount_factor;

		$ps_vendor_id = $_SESSION["ps_vendor_id"];
		
		$cart = $_SESSION['cart'];

		require_once(CLASSPATH. 'ps_payment_method.php' );
		$ps_payment_method = new ps_payment_method;
		require_once(CLASSPATH. 'ps_product.php' );
		$ps_product= new ps_product;
		require_once(CLASSPATH.'ps_cart.php');
		$ps_cart = new ps_cart;

		$db = new ps_DB;

		/* Set the order number */
		$order_number = $this->get_order_number();

		$totals = $this->calc_order_totals( $d );
		extract( $totals );
		
		//$timestamp = time() + ($mosConfig_offset*60*60);  //Original
		$timestamp = time();  //Custom
		
		if (!$this->validate_form($d)) {
			return false;
		}

		if (!$this->validate_add($d)) {
			return false;
		}

		// make sure Total doesn't become negative
		if( $order_total < 0 ) $order_total = 0;

		$order_total = round( $order_total, 2);


		$vmLogger->debug( '-- Checkout Debug--
		
Subtotal: '.$order_subtotal.'
Taxable: '.$order_taxable.'
Payment Discount: '.$payment_discount.'
Coupon Discount: '.$coupon_discount.'
Shipping: '.$order_shipping.'
Shipping Tax : '.$order_shipping_tax.'
Tax : '.$order_tax.'
------------------------
Order Total: '.$order_total.'
----------------------------' 
		);

		// Check to see if Payment Class File exists	
		$payment_class = $ps_payment_method->get_field($d["payment_method_id"], "payment_class");
		$enable_processor = $ps_payment_method->get_field($d["payment_method_id"], "enable_processor");
		$d['new_order_status'] = 'P'; // This is meant to be updated by a payment modules' process_payment method
		if (file_exists(CLASSPATH . "payment/$payment_class.php") ) {
			if( !class_exists( $payment_class )) {
				include( CLASSPATH. "payment/$payment_class.php" );
			}

			$_PAYMENT = new $payment_class();
			if (!$_PAYMENT->process_payment($order_number,$order_total, $d)) {
				$vmLogger->err( $VM_LANG->_('PHPSHOP_PAYMENT_ERROR',false)." ($payment_class)" );
				$_SESSION['last_page'] = "checkout.index";
				$_REQUEST["checkout_next_step"] = CHECK_OUT_GET_PAYMENT_METHOD;
				return False;
			}
		}

		else {
			$d["order_payment_log"] = $VM_LANG->_('PHPSHOP_CHECKOUT_MSG_LOG');
		}

		// Remove the Coupon, because it is a Gift Coupon and now is used!!
		if( @$_SESSION['coupon_type'] == "gift" ) {
			$d['coupon_id'] = $_SESSION['coupon_id'];
			include_once( CLASSPATH.'ps_coupon.php' );
			ps_coupon::remove_coupon_code( $d );
		}
		
		// Get the IP Address
		if (!empty($_SERVER['REMOTE_ADDR'])) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		else {
			$ip = 'unknown';
		}
		
		// Collect all fields and values to store them!
		$fields = array(
			'user_id' => $auth["user_id"], 
			'vendor_id' => $ps_vendor_id, 
			'order_number' => $order_number, 
			'user_info_id' =>  $d["ship_to_info_id"], 
			'ship_method_id' => @urldecode($d["shipping_rate_id"]),
			'order_total' => $order_total, 
			'order_subtotal' => $order_subtotal, 
			'order_tax' => $order_tax, 
			'order_tax_details' => serialize($order_tax_details), 
			'order_shipping' => $order_shipping,
			'order_shipping_tax' => $order_shipping_tax, 
			'order_discount' => $payment_discount, 
			'coupon_discount' => $coupon_discount,
			'coupon_code' => @$_SESSION['coupon_code'],
			'order_currency' => $GLOBALS['product_currency'], 
			'order_status' => 'P', 
			'cdate' => $timestamp,
			'mdate' => $timestamp,
			'customer_note' => htmlspecialchars(vmRequest::getString('customer_note','', 'POST', 'none' ), ENT_QUOTES ),
			'ip_address' => $ip
			);

		// Insert the main order information
		$db->buildQuery( 'INSERT', '#__{vm}_orders', $fields );
		$result = $db->query();

		$d["order_id"] = $order_id = $db->last_insert_id();
		if( $result === false || empty( $order_id )) {
			$vmLogger->crit( 'Adding the Order into the Database failed! User ID: '.$auth["user_id"] );
			return false;
		}

	    // Insert the initial Order History.	    
		$mysqlDatetime = date("Y-m-d G:i:s", $timestamp);
		
		$fields = array(
					'order_id' => $order_id,
					'order_status_code' => 'P',
					'date_added' => $mysqlDatetime,
					'customer_notified' => 1,
					'comments' => ''
				  );
		$db->buildQuery( 'INSERT', '#__{vm}_order_history', $fields );
		$db->query();

		/**
	    * Insert the Order payment info 
	    */
		$payment_number = str_replace(array(' ','|','-'), '', @$_SESSION['ccdata']['order_payment_number']);

		$d["order_payment_code"] = @$_SESSION['ccdata']['credit_card_code'];

		// Payment number is encrypted using mySQL encryption functions.
		$fields = array(
					'order_id' => $order_id, 
					'payment_method_id' => $d["payment_method_id"], 
					'order_payment_log' => @$d["order_payment_log"], 
					'order_payment_trans_id' => $vmInputFilter->safeSQL( @$d["order_payment_trans_id"] )
				  );
		if( !empty( $payment_number ) && VM_STORE_CREDITCARD_DATA == '1' ) {
			// Store Credit Card Information only if the Store Owner has decided to do so
			$fields['order_payment_code'] = $d["order_payment_code"];
			$fields['order_payment_expire'] = @$_SESSION["ccdata"]["order_payment_expire"];
			$fields['order_payment_name'] = @$_SESSION["ccdata"]["order_payment_name"];
			$fields['order_payment_number'] = VM_ENCRYPT_FUNCTION."( '$payment_number','" . ENCODE_KEY . "')";
			$specialfield = array('order_payment_number');
		} else {
			$specialfield = array();
		}
		$db->buildQuery( 'INSERT', '#__{vm}_order_payment', $fields, '', $specialfield );
		$db->query();

		/**
		* Insert the User Billto & Shipto Info
		*/
		// First: get all the fields from the user field list to copy them from user_info into the order_user_info
		$fields = array();
		require_once( CLASSPATH . 'ps_userfield.php' );
		$userfields = ps_userfield::getUserFields('', false, '', true, true );
		foreach ( $userfields as $field ) {
    		if ($field->name=='email') $fields[] = 'user_email'; 
    		else $fields[] = $field->name;			
		}
		$fieldstr = implode( ',', $fields );
		// Save current Bill To Address
		$q = "INSERT INTO `#__{vm}_order_user_info` 
			(`order_info_id`,`order_id`,`user_id`,address_type, ".$fieldstr.") ";
		$q .= "SELECT NULL, '$order_id', '".$auth['user_id']."', address_type, ".$fieldstr." FROM #__{vm}_user_info WHERE user_id='".$auth['user_id']."' AND address_type='BT'";
		$db->query( $q );

		// Save current Ship to Address if applicable
		$q = "INSERT INTO `#__{vm}_order_user_info` 
			(`order_info_id`,`order_id`,`user_id`,address_type, ".$fieldstr.") ";
		$q .= "SELECT NULL, '$order_id', '".$auth['user_id']."', address_type, ".$fieldstr." FROM #__{vm}_user_info WHERE user_id='".$auth['user_id']."' AND user_info_id='".$d['ship_to_info_id']."' AND address_type='ST'";
		$db->query( $q );

		/**
    	* Insert all Products from the Cart into order line items; 
    	* one row per product in the cart 
    	*/
		$dboi = new ps_DB;

		for($i = 0; $i < $cart["idx"]; $i++) {

			$r = "SELECT product_id,product_in_stock,product_sales,product_parent_id,product_sku,product_name ";
			$r .= "FROM #__{vm}_product WHERE product_id='".$cart[$i]["product_id"]."'";
			$dboi->query($r);
			$dboi->next_record();

			$product_price_arr = $ps_product->get_adjusted_attribute_price($cart[$i]["product_id"], $cart[$i]["description"]);
			$product_price = $GLOBALS['CURRENCY']->convert( $product_price_arr["product_price"], $product_price_arr["product_currency"] );

			if( empty( $_SESSION['product_sess'][$cart[$i]["product_id"]]['tax_rate'] )) {
				$my_taxrate = $ps_product->get_product_taxrate($cart[$i]["product_id"] );
			}
			else {
				$my_taxrate = $_SESSION['product_sess'][$cart[$i]["product_id"]]['tax_rate'];
			}
			// Attribute handling
			$product_parent_id = $dboi->f('product_parent_id');
			$description = '';
			if( $product_parent_id > 0 ) {
				
				$db_atts = $ps_product->attribute_sql( $dboi->f('product_id'), $product_parent_id );
				while( $db_atts->next_record()) {
					$description .=	$db_atts->f('attribute_name').': '.$db_atts->f('attribute_value').'; ';
				}
			}
			
			$description .= $ps_product->getDescriptionWithTax($_SESSION['cart'][$i]["description"], $dboi->f('product_id'));
			
			$product_final_price = round( ($product_price *($my_taxrate+1)), 2 );

			$vendor_id = $ps_vendor_id;
			
			$fields = array('order_id' => $order_id, 
									'user_info_id' => $d["ship_to_info_id"],
									'vendor_id' => $vendor_id, 
									'product_id' => $cart[$i]["product_id"], 
									'order_item_sku' => $dboi->f("product_sku"), 
									'order_item_name' => $dboi->f("product_name"), 
									'product_quantity' => $cart[$i]["quantity"], 
									'product_item_price' => $product_price, 
									'product_final_price' => $product_final_price, 		
									'order_item_currency' => $GLOBALS['product_currency'],
									'order_status' => 'P', 
									'product_attribute' => $description, 
									'cdate' => $timestamp, 
									'mdate' => $timestamp
						);
			$db->buildQuery( 'INSERT', '#__{vm}_order_item', $fields );
			$db->query();

			// Update Stock Level and Product Sales, decrease - no matter if in stock or not!
			$q = "UPDATE #__{vm}_product ";
			$q .= "SET product_in_stock = product_in_stock - ".(int)$cart[$i]["quantity"];
			$q .= " WHERE product_id = '" . $cart[$i]["product_id"]. "'";
			$db->query($q);

			$q = "UPDATE #__{vm}_product ";
			$q .= "SET product_sales= product_sales + ".(int)$cart[$i]["quantity"];
			$q .= " WHERE product_id='".$cart[$i]["product_id"]."'";
			$db->query($q);
			// Update stock of parent product, if all child products are sold, thanks Ragnar Brynjulfsson
			if ($dboi->f("product_parent_id") != 0) {
				$q = "SELECT COUNT(product_id) ";
				$q .= "FROM #__{vm}_product ";
				$q .= "WHERE product_parent_id = ".$dboi->f("product_parent_id");
				$q .= " AND product_in_stock > 0";
				$db->query($q);
				$db->next_record();
				if (!$db->f("COUNT(product_id)")) {
					$q = "UPDATE #__{vm}_product ";
					$q .= "SET product_in_stock = 0 ";
					$q .= "WHERE product_id = ".$dboi->f("product_parent_id")." LIMIT 1";
					$db->query($q);
			  }
			}
		}

		######## BEGIN DOWNLOAD MOD ###############
		if( ENABLE_DOWNLOADS == "1" ) {
			require_once( CLASSPATH.'ps_order.php');
			for($i = 0; $i < $cart["idx"]; $i++) {
				// only handle downloadable products here
				if( ps_product::is_downloadable($cart[$i]["product_id"])) {
					$params = array('product_id' => $cart[$i]["product_id"], 'order_id' => $order_id, 'user_id' => $auth["user_id"] );
					ps_order::insert_downloads_for_product( $params );
					
					if( @VM_DOWNLOADABLE_PRODUCTS_KEEP_STOCKLEVEL == '1' ) {
						// Update the product stock level back to where it was.
						$q = "UPDATE #__{vm}_product ";
						$q .= "SET product_in_stock = product_in_stock + ".(int)$cart[$i]["quantity"];
						$q .= " WHERE product_id = '" .(int)$cart[$i]["product_id"]. "'";
						$db->query($q);
					}
				}
			}
		}
		################## END DOWNLOAD MOD ###########

		// Export the order_id so the checkout complete page can get it
		$d["order_id"] = $order_id;

		/*
		 * Let the shipping module know which shipping method
		 * was selected.  This way it can save any information
		 * it might need later to print a shipping label.
		 */
		if( is_callable( array($this->_SHIPPING, 'save_rate_info') )) {
			$this->_SHIPPING->save_rate_info($d);
		}

		// Now as everything else has been done, we can update
		// the Order Status if the Payment Method is
		// "Use Payment Processor", because:
		// Payment Processors return false on any error
		// Only completed payments return true!
		$update_order = false;
		if( $order_total == 0.00 ) { // code moved out of $_PAYMENT check as no payment will be needed when $order_total=0.0
					// If the Order Total is zero, we can confirm the order to automatically enable the download
					$d['order_status'] = ENABLE_DOWNLOAD_STATUS;
					$update_order = true;
		} elseif (isset($_PAYMENT)) {
		  if( $enable_processor == "Y" || stristr($_PAYMENT->payment_code, '_API' ) !== false ) {
				if( $d['new_order_status'] != 'P' ) {
					$d['order_status'] = $d['new_order_status'];
					$update_order = true;
				} elseif( defined($_PAYMENT->payment_code.'_VERIFIED_STATUS')) {
					$d['order_status'] = constant($_PAYMENT->payment_code.'_VERIFIED_STATUS');
					$update_order = true;
				}
			}
		} 
		if ( $update_order ) {
			require_once(CLASSPATH."ps_order.php");
			$ps_order = new ps_order();
			$ps_order->order_status_update($d);
		}
		

		// Send the e-mail confirmation messages
		$this->email_receipt($order_id);

		// Reset the cart (=empty it)
		$ps_cart->reset();
        $_SESSION['savedcart']['idx']=0;
        $ps_cart->saveCart();

		// Unset the payment_method variables
		$d["payment_method_id"] = "";
		$d["order_payment_number"] = "";
		$d["order_payment_expire"] = "";
		$d["order_payment_name"] = "";
		$d["credit_card_code"] = "";
		// Clear the sensitive Session data
		$_SESSION['ccdata']['order_payment_name']  = "";
		$_SESSION['ccdata']['order_payment_number']  = "";
		$_SESSION['ccdata']['order_payment_expire_month'] = "";
		$_SESSION['ccdata']['order_payment_expire_year'] = "";
		$_SESSION['ccdata']['credit_card_code'] = "";
		$_SESSION['coupon_discount'] = "";
		$_SESSION['coupon_id'] = "";
		$_SESSION['coupon_redeemed'] = false;
		
		$_POST["payment_method_id"] = "";
		$_POST["order_payment_number"] = "";
		$_POST["order_payment_expire"] = "";
		$_POST["order_payment_name"] = "";
		/*
		if( empty($my->id) && !empty( $auth['user_id'])) {
			require_once(CLASSPATH.'ps_user.php');
			ps_user::logout();
		}
		*/
		return True;
	}

	/**
	 * Create an order number using the session id, session
	 * name, and the current unix timestamp.
	 *
	 * @return string
	 */
	function get_order_number() {
		global $auth;

		/* Generated a unique order number */

		$str = session_id();
		$str .= (string)time();

		$order_number = $auth['user_id'] .'_'. md5($str);

		return substr($order_number, 0, 32);
	}
	/**
         * Stores the md5 hash of the recent cart in the var _cartHash
         *
         */
	function generate_cart_hash() {
		$this->_cartHash = $this->get_new_cart_hash();
	}
	
	function get_order_total( &$d ) {
		global $discount_factor;
		$totals = $this->calc_order_totals($d);
		return $totals['order_total'];
	}
	
	/**
	 * Calculates the current order totals and fills an array with all the values
	 *
	 * @param array $d
	 * @return array
	 */
	function calc_order_totals( &$d ) {
		global $discount_factor, $mosConfig_offset;
		
		$totals = array();
		
		/* sets _subtotal */
		$totals['order_subtotal'] = $tmp_subtotal = $this->calc_order_subtotal($d);
		
		$totals['order_taxable'] = $this->calc_order_taxable($d);
		
		if( !empty($d['payment_method_id'])) {
			$totals['payment_discount'] = $d['payment_discount'] = $this->get_payment_discount($d['payment_method_id'], $totals['order_subtotal']);
		} else {
			$totals['payment_discount'] = $d['payment_discount'] = 0.00;
		}

		/* DISCOUNT HANDLING */
		if( !empty($_SESSION['coupon_discount']) ) {
			$totals['coupon_discount'] = floatval($_SESSION['coupon_discount']);
		}
		else {
			$totals['coupon_discount'] = 0.00;
		}

		// make sure Total doesn't become negative
		if( $tmp_subtotal < 0 ) $totals['order_subtotal'] = $tmp_subtotal = 0;
		if( $totals['order_taxable'] < 0 ) $totals['order_taxable'] = 0;

		// from now on we have $order_tax_details
		$d['order_tax'] = $totals['order_tax'] = round( $this->calc_order_tax($totals['order_taxable'], $d), 2 );
		
		if( is_object($this->_SHIPPING) ) {
			/* sets _shipping */
			$d['order_shipping'] = $totals['order_shipping'] = round( $this->calc_order_shipping( $d ), 2 );

			/* sets _shipping_tax
			* btw: This is WEIRD! To get an exactly rounded value we have to convert
			* the amount to a String and call "round" with the string. */
			$d['order_shipping_tax'] = $totals['order_shipping_tax'] = round( strval($this->calc_order_shipping_tax($d)), 2 );
		}
		else {
			$d['order_shipping'] = $totals['order_shipping'] = $totals['order_shipping_tax'] = $d['order_shipping_tax'] = 0.00;
		}

		$d['order_total'] = $totals['order_total'] = 	$tmp_subtotal 
											+ $totals['order_tax']
											+ $totals['order_shipping']
											+ $totals['order_shipping_tax']
											- $totals['coupon_discount']
											- $totals['payment_discount'];
		
		$totals['order_tax'] *= $discount_factor;

		return $totals;
	}
	/**
         * Generates the md5 hash of the recent cart / checkout constellation
         *
         * @return unknown
         */
	function get_new_cart_hash() {

		return md5( print_r( $_SESSION['cart'], true)
		. vmGet($_REQUEST,'shipping_rate_id')
		. vmGet($_REQUEST,'payment_method_id')
		);

	}

	/**
         * Returns the recent subtotal
         *
         * @param array $d
         * @return float The current order subtotal
         */
	function get_order_subtotal( &$d ) {

		if( $this->_subtotal === null ) {
			$this->_subtotal = $this->calc_order_subtotal( $d );
		}
		else {
			if( $this->_cartHash != $this->get_new_cart_hash() ) {
				// Need to re-calculate the subtotal
				$this->_subtotal = $this->calc_order_subtotal( $d );
			}
		}
		return $this->_subtotal;
	}

	/**************************************************************************
	** name: calc_order_subtotal()
	** created by: gday
	** description:  Calculate the order subtotal for the current order.
	**               Does not include tax or shipping charges.
	** parameters: $d
	** returns: sub total for this order
	***************************************************************************/
	function calc_order_subtotal( &$d ) {
		global $order_tax_details;
		
		$order_tax_details = array();
		$d['order_subtotal_withtax'] = 0;
		$d['payment_discount'] = 0;
		$auth = $_SESSION['auth'];
		$cart = $_SESSION['cart'];
		$order_subtotal = 0;

		require_once(CLASSPATH.'ps_product.php');
		$ps_product= new ps_product;

		for($i = 0; $i < $cart["idx"]; $i++) {
			$my_taxrate = $ps_product->get_product_taxrate($cart[$i]["product_id"] );
			$price = $ps_product->get_adjusted_attribute_price($cart[$i]["product_id"], $cart[$i]["description"]);
			$product_price = $product_price_tmp = $GLOBALS['CURRENCY']->convert( $price["product_price"], @$price["product_currency"] );
			
			if( $auth["show_price_including_tax"] == 1 ) {
				$product_price = round( ($product_price *($my_taxrate+1)), 2 );
				$product_price *= $cart[$i]["quantity"];
				
				$d['order_subtotal_withtax'] += $product_price;
				$product_price = $product_price /($my_taxrate+1);
				$order_subtotal += $product_price;
				
			}
			else {
				$order_subtotal += $product_price * $cart[$i]["quantity"];
				
				$product_price = round( ($product_price *($my_taxrate+1)), 2 );
				$product_price *= $cart[$i]["quantity"];
				$d['order_subtotal_withtax'] += $product_price;
				$product_price = $product_price /($my_taxrate+1);
			}
			if( MULTIPLE_TAXRATES_ENABLE ) {
				// Calculate the amounts for each tax rate
				if( !isset( $order_tax_details[$my_taxrate] )) {
					$order_tax_details[$my_taxrate] = 0;
				}
				$order_tax_details[$my_taxrate] += $product_price_tmp*$my_taxrate*$cart[$i]["quantity"];
			}
		}

		return($order_subtotal);
	}


	/**
	 * Calculates the taxable order subtotal for the order.
	 * If an item has no weight, it is non taxable.
	 * @author Chris Coleman
	 * @param array $d
	 * @return float Subtotal
	 */
	function calc_order_taxable($d) {
		$auth = $_SESSION['auth'];
		$cart = $_SESSION['cart'];

		$subtotal = 0.0;
		
		require_once(CLASSPATH.'ps_product.php');
		$ps_product= new ps_product;
		require_once(CLASSPATH.'ps_shipping_method.php');

		$db = new ps_DB;

		for($i = 0; $i < $cart["idx"]; $i++) {
			
			$skip_tax = false; // do we skip this product due to zero percent tax rate?	
			$tax_rate_id = $ps_product->get_field($cart[$i]["product_id"],'product_tax_id');
			if($tax_rate_id != '0'){
				// look up the tax rate
				$q = "SELECT tax_rate FROM #__{vm}_tax_rate WHERE tax_rate_id='$tax_rate_id'";
				$db->query($q);			
				if($db->num_rows() > 0){
					$tax_rate = $db->f('tax_rate');					
					if($tax_rate==0){
						$skip_tax = true;
					}
				}
			}	
			
			$price = $ps_product->get_adjusted_attribute_price($cart[$i]["product_id"], $cart[$i]["description"]);
			$product_price = $GLOBALS['CURRENCY']->convert( $price["product_price"], $price['product_currency'] );
			$item_weight = ps_shipping_method::get_weight($cart[$i]["product_id"]) * $cart[$i]['quantity'];

			if (($item_weight != 0 or TAX_VIRTUAL=='1') && !$skip_tax ){
				$subtotal += $product_price * $cart[$i]["quantity"];
			}
		}
		return($subtotal);
	}
	
	/**
	 * Calculate the tax charges for the current order.
	 * You can switch the way, taxes are calculated:
	 * either based on the VENDOR address,
	 * or based on the ship-to address.
	 * ! Creates the global $order_tax_details
	 *
	 * @param float $order_taxable
	 * @param array $d
	 * @return float
	 */
	function calc_order_tax($order_taxable, $d) {
		global $order_tax_details, $discount_factor;
		$total = 0; 
		$order_tax=0;
		$auth = $_SESSION['auth'];
		$ps_vendor_id = $_SESSION["ps_vendor_id"];
		$db = new ps_DB;
		$ship_to_info_id = vmGet( $_REQUEST, 'ship_to_info_id');
		
		
		require_once(CLASSPATH.'ps_tax.php');
		$ps_tax = new ps_tax;
		
		$discount_factor = 1;
		
			// Shipping address based TAX
		if ( !ps_checkout::tax_based_on_vendor_address () ) {
			$q = "SELECT state, country FROM #__{vm}_user_info ";
			$q .= "WHERE user_info_id='".$ship_to_info_id. "'";
			$db->query($q);
			$db->next_record();
			$state = $db->f("state");
			$country = $db->f("country");
			$q = "SELECT * FROM #__{vm}_tax_rate WHERE tax_country='$country' ";
			if( !empty($state)) {
				$q .= "AND (tax_state='$state' OR tax_state=' $state ')";
			}
			$db->query($q);
			if ($db->next_record()) {
				$rate = $order_taxable * floatval( $db->f("tax_rate") );
				if (empty($rate)) {
					$order_tax = 0.0;
                } else {
                    $cart = $_SESSION['cart'];
					$order_tax = 0.0;
                    if( (!empty( $_SESSION['coupon_discount'] ) || !empty( $d['payment_discount'] ))
                        && PAYMENT_DISCOUNT_BEFORE == '1' ) {

                        require_once(CLASSPATH.'ps_product.php');
                        $ps_product= new ps_product;

                        for($i = 0; $i < $cart["idx"]; $i++) {
                            $item_weight = ps_shipping_method::get_weight($cart[$i]["product_id"]) * $cart[$i]['quantity'];

                            if ($item_weight !=0 or TAX_VIRTUAL) {
                                $price = $ps_product->get_adjusted_attribute_price($cart[$i]["product_id"], $cart[$i]["description"]);
                                $price['product_price'] = $GLOBALS['CURRENCY']->convert( $price['product_price'], $price['product_currency']);
                                $tax_rate = $db->f("tax_rate");

                                $use_coupon_discount= @$_SESSION['coupon_discount'];
                                //if( !empty( $_SESSION['coupon_discount'] )) {
                                //    if( $auth["show_price_including_tax"] == 1 ) {
                                //        $use_coupon_discount = $_SESSION['coupon_discount'] / ($tax_rate+1);
                                //    }
                                //}
                                $factor = (100 * ($use_coupon_discount + @$d['payment_discount'])) / $this->_subtotal;
                                $price["product_price"] = $price["product_price"] - ($factor * $price["product_price"] / 100);
                                @$order_tax_details[$tax_rate] += $price["product_price"] * $tax_rate * $cart[$i]["quantity"];

                                $order_tax += $price["product_price"] * $tax_rate * $cart[$i]["quantity"];
                                $total += $price["product_price"] * $cart[$i]["quantity"];
                            } else {
                                $order_tax += 0.0;
                            }
                        }
                    } else {
                        $order_tax = $rate;
                    }
                }
            } else {
                $order_tax = 0.0;
            }
            $order_tax_details[$db->f('tax_rate')] = $order_tax;
        }
		// Store Owner Address based TAX
		else {

				// Calculate the Tax with a tax rate for every product
				$cart = $_SESSION['cart'];
				$order_tax = 0.0;
				$total = 0.0;
				if( (!empty( $_SESSION['coupon_discount'] ) || !empty( $d['payment_discount'] ))
					&& PAYMENT_DISCOUNT_BEFORE == '1' ) {
					// We need to recalculate the tax details when the discounts are applied
					// BEFORE taxes - because they affect the product subtotals then
					$order_tax_details = array();
				}
				require_once(CLASSPATH.'ps_product.php');
				$ps_product= new ps_product;
				require_once(CLASSPATH.'ps_shipping_method.php');

				for($i = 0; $i < $cart["idx"]; $i++) {
					$item_weight = ps_shipping_method::get_weight($cart[$i]["product_id"]) * $cart[$i]['quantity'];

					if ($item_weight !=0 or TAX_VIRTUAL) {
						$price = $ps_product->get_adjusted_attribute_price($cart[$i]["product_id"], $cart[$i]["description"]);
						$price['product_price'] = $GLOBALS['CURRENCY']->convert( $price['product_price'], $price['product_currency']);
						$tax_rate = $ps_product->get_product_taxrate($cart[$i]["product_id"]);
				
						if( (!empty( $_SESSION['coupon_discount'] ) || !empty( $d['payment_discount'] ))
							&& PAYMENT_DISCOUNT_BEFORE == '1' ) {
							$use_coupon_discount= @$_SESSION['coupon_discount'];
							if( !empty( $_SESSION['coupon_discount'] )) {
								if( $auth["show_price_including_tax"] == 1 ) {
									$use_coupon_discount = $_SESSION['coupon_discount'] / ($tax_rate+1);
								}
							}
							$factor = (100 * ($use_coupon_discount + @$d['payment_discount'])) / $this->_subtotal;
							$price["product_price"] = $price["product_price"] - ($factor * $price["product_price"] / 100);
							@$order_tax_details[$tax_rate] += $price["product_price"] * $tax_rate * $cart[$i]["quantity"];
						}
						
						$order_tax += $price["product_price"] * $tax_rate * $cart[$i]["quantity"];
						$total += $price["product_price"] * $cart[$i]["quantity"];
					}
				}

				if( (!empty( $_SESSION['coupon_discount'] ) || !empty( $d['payment_discount'] ))
					&& PAYMENT_DISCOUNT_BEFORE != '1' ) {
						
					// Here we need to re-calculate the Discount
					// because we assume the Discount is "including Tax"
					$discounted_total = @$d['order_subtotal_withtax'] - @$_SESSION['coupon_discount'] - @$d['payment_discount'];
					
					if( $discounted_total != @$d['order_subtotal_withtax'] && @$d['order_subtotal_withtax'] > 0.00) {
						$discount_factor = $discounted_total / $d['order_subtotal_withtax'];
						
						foreach( $order_tax_details as $rate => $value ) {
							$order_tax_details[$rate] = $value * $discount_factor;
						}
					}
					
				}
				if( is_object($this->_SHIPPING) ) {
					$taxrate = $this->_SHIPPING->get_tax_rate();
					if( $taxrate ) {
						$rate = $this->_SHIPPING->get_rate( $d );
						if( $auth["show_price_including_tax"] == 1 ) {
							@$order_tax_details[$taxrate] += $rate - ($rate / ($taxrate+1));
						}
						else {
							@$order_tax_details[$taxrate] += $rate * $taxrate;
						}
					}
				}


		}
		return( round( $order_tax, 2 ) );
	}
  
	/**************************************************************************
	** name: calc_order_shipping()
	** created by: soeren
	** description:  Get the Shipping costs WITHOUT TAX
	** parameters: $d,
	** returns: a decimal number, excluding taxes
	***************************************************************************/
	function calc_order_shipping( &$d ) {

		$auth = $_SESSION['auth'];

		$shipping_total = $this->_SHIPPING->get_rate( $d );
		$shipping_taxrate = $this->_SHIPPING->get_tax_rate();

		// When the Shipping rate is shown including Tax
		// we have to extract the Tax from the Shipping Total
		// before returning the value
		if( $auth["show_price_including_tax"] == 1 ) {
			$d['shipping_tax'] = $shipping_total - ($shipping_total / ($shipping_taxrate+1));
			$d['shipping_total'] = $shipping_total - $d['shipping_tax'];
		}
		else {
			$d['shipping_tax'] = $shipping_total * $shipping_taxrate;
			$d['shipping_total'] = $shipping_total;
		}
		$d['shipping_tax'] = $GLOBALS['CURRENCY']->convert( $d['shipping_tax'] );
		$d['shipping_total'] = $GLOBALS['CURRENCY']->convert( $d['shipping_total'] );
		
		return $d['shipping_total'];
	}




	/**************************************************************************
	** name: calc_order_shipping_tax()
	** created by: Soeren
	** description:  Calculate the tax for the shipping of the current order
	** Assumes that the function calc_order_shipping has been called before
	** parameters: $d
	** returns: Tax for the shipping of this order
	***************************************************************************/
	function calc_order_shipping_tax($d) {

		return $d['shipping_tax'];

	}

	/**************************************************************************
	** name: get_vendor_currency()
	** created by: gday
	** description:  Get the currency type used by the $vendor_id
	** parameters: $vendor_id - vendor id to return currency type
	** returns: Currency type for this vendor
	***************************************************************************/
	function get_vendor_currency($vendor_id) {
		$db = new ps_DB;

		$q = "SELECT vendor_currency FROM #__{vm}_vendor WHERE vendor_id='$vendor_id'";

		$db->query($q);
		$db->next_record();

		$currency = $db->f("vendor_currency");

		return($currency);
	}


	/**************************************************************************
	** name: get_payment_discount()
	** created by: soeren
	** description:  Get the discount for the selected payment
	** parameters: $payment_method_id
	** returns: Discount as a decimal if found
	**          0 if nothing is found
	***************************************************************************/
	function get_payment_discount( $payment_method_id, $subtotal = '' ) {
		
		if( empty( $payment_method_id )) {
			return 0;
		}
		$db = new ps_DB();
		//MOD ei
		// There is a special payment method, which fee is depend on subtotal
		// it is a type of cash on delivery
		// comment soeren: Payment methods can implement their own method
		// how to calculate the discount: the function "get_payment_rate"
		// should return a float value from the payment class
		require_once(CLASSPATH.'ps_payment_method.php');
		$ps_payment_method = new ps_payment_method;

		$payment_class = $ps_payment_method->get_field($payment_method_id, "payment_class");

		// Check to see if Payment Class File exists
		if (file_exists(CLASSPATH . "payment/$payment_class.php") ) {

			require_once( CLASSPATH. "payment/$payment_class.php" );
			eval( "\$_PAYMENT = new $payment_class();" );

			if(is_callable(array($payment_class, 'get_payment_rate'))) {
				return $_PAYMENT->get_payment_rate($subtotal);
			}
		}
		//End of MOD ei

		// If a payment method has no special way of calculating a discount,
		// let's do this on our own from the payment_method_discount settings
		$q = 'SELECT `payment_method_discount`,`payment_method_discount_is_percent`,`payment_method_discount_max_amount`, `payment_method_discount_min_amount`
                                FROM `#__{vm}_payment_method` WHERE payment_method_id='.$payment_method_id;
		$db->query($q);$db->next_record();

		$discount = $db->f('payment_method_discount');
		$is_percent = $db->f('payment_method_discount_is_percent');

		if( !$is_percent ) {
			// Standard method: absolute amount
			if (!empty($discount)) {
				return(floatval( $GLOBALS['CURRENCY']->convert($discount)));
			}
			else {
				return(0);
			}
		}
		else {

			if( $subtotal === '') {
				$subtotal = $this->get_order_subtotal( $vars );
			}

			// New: percentage of the subtotal, limited by minimum and maximum
			$max = $db->f('payment_method_discount_max_amount');
			$min = $db->f('payment_method_discount_min_amount');
			$value = (float) ($discount/100) * $subtotal;

			if( abs($value) > $max && $max > 0 ) {
				$value = -$max;
			}
			elseif( abs($value) < $min && $min > 0 ) {
				$value = -$min;
			}
			return $value;
		}

	}

	/**
    * Create a receipt for the current order and email it to
    * the customer and the vendor.
    * @author gday
    * @author soeren
    * @param int $order_id
    * @return boolean True on success, false on failure
    */
	function email_receipt($order_id) {
		global $sess, $ps_product, $VM_LANG, $CURRENCY_DISPLAY, $vmLogger,
		$mosConfig_fromname, $mosConfig_lang, $database;

		$ps_vendor_id = vmGet( $_SESSION, 'ps_vendor_id', 1 );
		$auth = $_SESSION["auth"];

		require_once( CLASSPATH.'ps_order_status.php');
		require_once( CLASSPATH.'ps_userfield.php');
		require_once(CLASSPATH.'ps_product.php');
		$ps_product = new ps_product;

		// Connect to database and gather appropriate order information
		$db = new ps_DB;
		$q  = "SELECT * FROM #__{vm}_orders WHERE order_id='$order_id'";
		$db->query($q);
		$db->next_record();
		$user_id = $db->f("user_id");
		$customer_note = $db->f("customer_note");
		$order_status = ps_order_status::getOrderStatusName($db->f("order_status") );

		$dbbt = new ps_DB;
		$dbst = new ps_DB;

		$qt = "SELECT * FROM #__{vm}_user_info WHERE user_id='".$user_id."' AND address_type='BT'";
		$dbbt->query($qt);
		$dbbt->next_record();

		$qt = "SELECT * FROM #__{vm}_user_info WHERE user_info_id='". $db->f("user_info_id") . "'";
		$dbst->query($qt);
		$dbst->next_record();

		$dbv = new ps_DB;
		$qt = "SELECT * from #__{vm}_vendor ";
		/* Need to decide on vendor_id <=> order relationship */
		$qt .= "WHERE vendor_id = '".$ps_vendor_id."'";
		$dbv->query($qt);
		$dbv->next_record();

		$dboi = new ps_DB;
		$q_oi = "SELECT * FROM #__{vm}_product, #__{vm}_order_item, #__{vm}_orders ";
		$q_oi .= "WHERE #__{vm}_product.product_id=#__{vm}_order_item.product_id ";
		$q_oi .= "AND #__{vm}_order_item.order_id='$order_id' ";
		$q_oi .= "AND #__{vm}_orders.order_id=#__{vm}_order_item.order_id";
		$dboi->query($q_oi);

		$db_payment = new ps_DB;
		$q  = "SELECT op.payment_method_id, pm.payment_method_name FROM #__{vm}_order_payment as op, #__{vm}_payment_method as pm
              WHERE order_id='$order_id' AND op.payment_method_id=pm.payment_method_id";
		$db_payment->query($q);
		$db_payment->next_record();

		if ($auth["show_price_including_tax"] == 1) {

			$order_shipping = $db->f("order_shipping");
			$order_shipping += $db->f("order_shipping_tax");
			$order_shipping_tax = 0;
			$order_tax = $db->f("order_tax") + $db->f("order_shipping_tax");
		}
		else {

			$order_shipping = $db->f("order_shipping");
			$order_shipping_tax = $db->f("order_shipping_tax");
			$order_tax = $db->f("order_tax");
		}
		$order_total = $db->f("order_total");
		$order_discount = $db->f("order_discount");
		$coupon_discount = $db->f("coupon_discount");

		// Email Addresses for shopper and vendor
		// **************************************
		$shopper_email = $dbbt->f("user_email");
		$shopper_name = $dbbt->f("first_name")." ".$dbbt->f("last_name");

		$from_email = $dbv->f("contact_email");

		$shopper_subject = $dbv->f("vendor_name") . " ".$VM_LANG->_('PHPSHOP_ORDER_PRINT_PO_LBL',false)." - " . $db->f("order_id");
		$vendor_subject = $dbv->f("vendor_name") . " ".$VM_LANG->_('PHPSHOP_ORDER_PRINT_PO_LBL',false)." - " . $db->f("order_id");

		$shopper_order_link = $sess->url( SECUREURL ."index.php?page=account.order_details&order_id=$order_id", true, false );
		$vendor_order_link = $sess->url( SECUREURL ."index2.php?page=order.order_print&order_id=$order_id&pshop_mode=admin", true, false );

		/**
		 * Prepare the payment information, including Credit Card information when not empty
		 */
		$payment_info_details = $db_payment->f("payment_method_name");
		if( !empty( $_SESSION['ccdata']['order_payment_name'] )
			&& !empty($_SESSION['ccdata']['order_payment_number'])) {
	  		$payment_info_details .= '<br />'.$VM_LANG->_('PHPSHOP_CHECKOUT_CONF_PAYINFO_NAMECARD',false).': '.$_SESSION['ccdata']['order_payment_name'].'<br />';
	  		$payment_info_details .= $VM_LANG->_('PHPSHOP_CHECKOUT_CONF_PAYINFO_CCNUM',false).': '.$this->asterisk_pad($_SESSION['ccdata']['order_payment_number'], 4 ).'<br />';
	  		$payment_info_details .= $VM_LANG->_('PHPSHOP_CHECKOUT_CONF_PAYINFO_EXDATE',false).': '.$_SESSION['ccdata']['order_payment_expire_month'].' / '.$_SESSION['ccdata']['order_payment_expire_year'].'<br />';
		}
		// Convert HTML into Text
		$payment_info_details_text = str_replace( '<br />', "\n", $payment_info_details );
		
		// Get the Shipping Details
		$shipping_arr = explode("|", urldecode(vmGet($_REQUEST,"shipping_rate_id")) );
		
		// Headers and Footers
		// ******************************
		// Shopper Header
		$shopper_header = $VM_LANG->_('PHPSHOP_CHECKOUT_EMAIL_SHOPPER_HEADER1',false)."\n";
		
		$legal_info_title = '';
		$legal_info_html = '';
		// Get the legal information about the returns/order cancellation policy
		if( @VM_ONCHECKOUT_SHOW_LEGALINFO == '1' ) {
			$article = intval(@VM_ONCHECKOUT_LEGALINFO_LINK);
			if( $article > 0 ) {
				$db_legal = new ps_DB();
				// Get the content article, which contains the Legal Info
				$db_legal->query( 'SELECT id, title, introtext FROM #__content WHERE id='.$article );
				$db_legal->next_record();
				if( $db_legal->f('introtext') ) {
					$legal_info_title = $db_legal->f('title');
					$legal_info_text = strip_tags( str_replace( '<br />', "\n", $db_legal->f('introtext') ));
					$legal_info_html = $db_legal->f('introtext');
				}
			}
		}
		//Shopper Footer
		$shopper_footer = "\n\n".$VM_LANG->_('PHPSHOP_CHECKOUT_EMAIL_SHOPPER_HEADER2',false)."\n";
		if( VM_REGISTRATION_TYPE != 'NO_REGISTRATION' ) {
			$shopper_footer .= "\n\n".$VM_LANG->_('PHPSHOP_CHECKOUT_EMAIL_SHOPPER_HEADER5',false)."\n";
			$shopper_footer .= $shopper_order_link;
		}
		$shopper_footer .= "\n\n".$VM_LANG->_('PHPSHOP_CHECKOUT_EMAIL_SHOPPER_HEADER3',false)."\n";
		$shopper_footer .= "Email: " . $from_email;
		// New in version 1.0.5
		if( @VM_ONCHECKOUT_SHOW_LEGALINFO == '1' && !empty( $legal_info_title )) {
			$shopper_footer .= "\n\n____________________________________________\n";
			$shopper_footer .= $legal_info_title."\n";
			$shopper_footer .= $legal_info_text."\n";
		}
		
		// Vendor Header
		$vendor_header = $VM_LANG->_('PHPSHOP_CHECKOUT_EMAIL_SHOPPER_HEADER4',false)."\n";

		// Vendor Footer
		$vendor_footer = "\n\n".$VM_LANG->_('PHPSHOP_CHECKOUT_EMAIL_SHOPPER_HEADER5',false)."\n";
		$vendor_footer .= $vendor_order_link;

		$vendor_email = $from_email;

		/////////////////////////////////////
		// set up text mail
		//

		// Main Email Message Purchase Order
		// *********************************
		$shopper_message  = "\n".$VM_LANG->_('PHPSHOP_ORDER_PRINT_PO_LBL',false)."\n";
		$shopper_message .= "------------------------------------------------------------------------\n";
		$shopper_message .= $VM_LANG->_('PHPSHOP_ORDER_PRINT_PO_NUMBER',false).": " . $db->f("order_id") . "\n";
		$shopper_message .= $VM_LANG->_('PHPSHOP_ORDER_PRINT_PO_DATE',false).":   ";
		$shopper_message .= strftime( $VM_LANG->_('DATE_FORMAT_LC'), $db->f("cdate") ) . "\n";
		$shopper_message .= $VM_LANG->_('PHPSHOP_ORDER_PRINT_PO_STATUS',false).": ";
				
		$shopper_message .= $order_status."\n\n";
				
		// BillTo Fields		
		$registrationfields = ps_userfield::getUserFields('registration', false, '', false, true );
		foreach( $registrationfields as $field ) {
			if( $field->name == 'email') $field->name = 'user_email';
			if( $field->name == 'delimiter_sendregistration' || $field->type == 'captcha') continue;
			
			if( $field->type == 'delimiter') {
				$shopper_message .= ($VM_LANG->_($field->title) != '' ? $VM_LANG->_($field->title) : $field->title)."\n";
				$shopper_message .= "--------------------\n\n";
			} else {
				$shopper_message .= ($VM_LANG->_($field->title) != '' ? $VM_LANG->_($field->title) : $field->title).':    ';
				$shopper_message .= $dbbt->f($field->name) . "\n";
			}
		}
		
		// Shipping Fields
		$shopper_message .= "\n\n";
		$shopper_message .= $VM_LANG->_('PHPSHOP_ORDER_PRINT_SHIP_TO_LBL')."\n";
		$shopper_message .= "-------\n\n";
		
		$shippingfields = ps_userfield::getUserFields('shipping', false, '', false, true );
		foreach( $shippingfields as $field ) {			
			
			if( $field->type == 'delimiter') {
				$shopper_message .= ($VM_LANG->_($field->title) != '' ? $VM_LANG->_($field->title) : $field->title)."\n";
				$shopper_message .= "--------------------\n\n";
			} else {
				$shopper_message .= ($VM_LANG->_($field->title) != '' ? $VM_LANG->_($field->title) : $field->title).':    ';
				$shopper_message .= $dbst->f($field->name) . "\n";
			}
		}
		
		$shopper_message .= "\n\n";

		$shopper_message .= $VM_LANG->_('PHPSHOP_ORDER_PRINT_ITEMS_LBL',false)."\n";
		$shopper_message .= "-----------";
		$sub_total = 0.00;
		while($dboi->next_record()) {
			$shopper_message .= "\n\n";
			$shopper_message .= $VM_LANG->_('PHPSHOP_PRODUCT',false)."  = ";
			if ($dboi->f("product_parent_id")) {
				$shopper_message .= $dboi->f("order_item_name") . "\n";
				$shopper_message .= "SERVICE  = ";
			}
			$shopper_message .= $dboi->f("product_name") . "; ".$dboi->f("product_attribute") ."\n";
			$shopper_message .= $VM_LANG->_('PHPSHOP_ORDER_PRINT_QUANTITY',false)." = ";
			$shopper_message .= $dboi->f("product_quantity") . "\n";
			$shopper_message .= $VM_LANG->_('PHPSHOP_ORDER_PRINT_SKU',false)."      = ";
			$shopper_message .= $dboi->f("order_item_sku") . "\n";

			$shopper_message .= $VM_LANG->_('PHPSHOP_ORDER_PRINT_PRICE',false)."    = ";
			if ($auth["show_price_including_tax"] == 1) {
				$sub_total += ($dboi->f("product_quantity") * $dboi->f("product_final_price"));
				$shopper_message .= $CURRENCY_DISPLAY->getFullValue($dboi->f("product_final_price"), '', $db->f('order_currency'));
			} else {
				$sub_total += ($dboi->f("product_quantity") * $dboi->f("product_item_price"));
				$shopper_message .= $CURRENCY_DISPLAY->getFullValue($dboi->f("product_item_price"), '', $db->f('order_currency'));
			}
		}

		$shopper_message .= "\n\n";

		$shopper_message .= $VM_LANG->_('PHPSHOP_ORDER_PRINT_SUBTOTAL',false)." = ";
		$shopper_message .= $CURRENCY_DISPLAY->getFullValue($sub_total, '', $db->f('order_currency'))."\n";

		if ( PAYMENT_DISCOUNT_BEFORE == '1') {
			if( !empty($order_discount)) {
				if ($order_discount > 0) {
					$shopper_message .= $VM_LANG->_('PHPSHOP_PAYMENT_METHOD_LIST_DISCOUNT',false)." = ";
					$shopper_message .= "- ".$CURRENCY_DISPLAY->getFullValue(abs($order_discount), '', $db->f('order_currency')) . "\n";
				} else {
					$shopper_message .= $VM_LANG->_('PHPSHOP_FEE',false)." = ";
					$shopper_message .= "+ ".$CURRENCY_DISPLAY->getFullValue(abs($order_discount), '', $db->f('order_currency')) . "\n";
				}
			}
			if( !empty($coupon_discount)) {
				/* following 2 lines added by Erich for coupon hack */
				$shopper_message .= $VM_LANG->_('PHPSHOP_COUPON_DISCOUNT',false) . ": ";
				$shopper_message .= $CURRENCY_DISPLAY->getFullValue($coupon_discount, '', $db->f('order_currency')) . "\n";
			}
		}

		if ($auth["show_price_including_tax"] != 1) {
			$shopper_message .= $VM_LANG->_('PHPSHOP_ORDER_PRINT_TOTAL_TAX',false)."      = ";
			$shopper_message .= $CURRENCY_DISPLAY->getFullValue($order_tax, '', $db->f('order_currency')) . "\n";
		}
		$shopper_message .= $VM_LANG->_('PHPSHOP_ORDER_PRINT_SHIPPING',false)." = ";
		$shopper_message .= $CURRENCY_DISPLAY->getFullValue($order_shipping, '', $db->f('order_currency')) . "\n";
		if( !empty($order_shipping_tax)) {
			$shopper_message .= $VM_LANG->_('PHPSHOP_ORDER_PRINT_SHIPPING_TAX',false)."   = ";
			$shopper_message .= $CURRENCY_DISPLAY->getFullValue($order_shipping_tax, '', $db->f('order_currency'));
		}
		$shopper_message .= "\n\n";
		if ( PAYMENT_DISCOUNT_BEFORE != '1') {
			if( !empty($order_discount)) {
				if ($order_discount > 0) {
					$shopper_message .= $VM_LANG->_('PHPSHOP_PAYMENT_METHOD_LIST_DISCOUNT',false)." = ";
					$shopper_message .= "- ".$CURRENCY_DISPLAY->getFullValue(abs($order_discount), '', $db->f('order_currency')) . "\n";
				} else {
					$shopper_message .= $VM_LANG->_('PHPSHOP_FEE',false)." = ";
					$shopper_message .= "+ ".$CURRENCY_DISPLAY->getFullValue(abs($order_discount), '', $db->f('order_currency')) . "\n";
				}
			}
			if( !empty($coupon_discount)) {
				/* following 2 lines added by Erich for coupon hack */
				$shopper_message .= $VM_LANG->_('PHPSHOP_COUPON_DISCOUNT',false) . ": ";
				$shopper_message .= $CURRENCY_DISPLAY->getFullValue($coupon_discount, '', $db->f('order_currency')) . "\n";
			}
		}
		$shopper_message .= $VM_LANG->_('PHPSHOP_ORDER_PRINT_TOTAL',false)."    = ";
		$shopper_message .= $CURRENCY_DISPLAY->getFullValue($order_total, '', $db->f('order_currency'));

		if ($auth["show_price_including_tax"] == 1) {
			$shopper_message .= "\n---------------";
			$shopper_message .= "\n";
			$shopper_message .= $VM_LANG->_('PHPSHOP_ORDER_PRINT_TOTAL_TAX',false)."      = ";
			$shopper_message .= $CURRENCY_DISPLAY->getFullValue($order_tax, '', $db->f('order_currency')) . "\n";
		}
		if( $db->f('order_tax_details') ) {
			$shopper_message .= str_replace( '<br />', "\n", ps_checkout::show_tax_details( $db->f('order_tax_details'), $db->f('order_currency') ));
		}
		// Payment Details
		$shopper_message .= "\n\n------------------------------------------------------------------------\n";
		$shopper_message .= $payment_info_details_text;
		
		// Shipping Details
		if( is_object($this->_SHIPPING) ) {
			$shopper_message .= "\n\n------------------------------------------------------------------------\n";
			$shopper_message .= $VM_LANG->_('PHPSHOP_ORDER_PRINT_SHIPPING_LBL',false).":\n";
			$shopper_message .= $shipping_arr[1]." (".$shipping_arr[2].")";
		}
		// Customer Note
		$shopper_message .= "\n\n------------------------------------------------------------------------\n";
		$shopper_message .= "\n".$VM_LANG->_('PHPSHOP_ORDER_PRINT_CUSTOMER_NOTE',false)."\n";
		$shopper_message .= "---------------";
		$shopper_message .= "\n";
		if( !empty( $customer_note )) {
			$shopper_message .= $customer_note."\n";
		}
		else {
			$shopper_message .= " ./. \n";
		}
		$shopper_message .= "------------------------------------------------------------------------\n";
		
		// Decode things like &euro; => €
		$shopper_message = vmHtmlEntityDecode( $shopper_message );
		
		// End of Purchase Order
		// *********************

		//
		//END: set up text mail
		/////////////////////////////////////
		// Send text email
		//
		if (ORDER_MAIL_HTML == '0') {

			$msg = $shopper_header . $shopper_message . $shopper_footer;

			// Mail receipt to the shopper
			vmMail( $from_email, $mosConfig_fromname, $shopper_email, $shopper_subject, $msg, "" );

			$msg = $vendor_header . $shopper_message . $vendor_footer;

			// Mail receipt to the vendor
			vmMail($from_email, $mosConfig_fromname, $vendor_email, $vendor_subject,	$msg, "" );

		}

		////////////////////////////
		// set up the HTML email
		//
		elseif (ORDER_MAIL_HTML == '1') {

			$dboi->query($q_oi);

			// Create Template Object 
			$template = vmTemplate::getInstance();
			
			if ($order_discount > 0) {
				$order_discount_lbl = $VM_LANG->_('PHPSHOP_PAYMENT_METHOD_LIST_DISCOUNT');
				$order_discount_plusminus = '-';
			} else {
				$order_discount_lbl = $VM_LANG->_('PHPSHOP_FEE');
				$order_discount_plusminus = '+';
			}
			if ($coupon_discount > 0) {
				$coupon_discount_lbl = $VM_LANG->_('PHPSHOP_PAYMENT_METHOD_LIST_DISCOUNT');
				$coupon_discount_plusminus = '-';
			} else {
				$coupon_discount_lbl = $VM_LANG->_('PHPSHOP_FEE');
				$coupon_discount_plusminus = '+';
			}

			if( is_object($this->_SHIPPING) ) {
				$shipping_info_details = stripslashes($shipping_arr[1])." (".stripslashes($shipping_arr[2]).")";
			}
			else {
				$shipping_info_details = ' ./. ';
			}
			// These are a lot of vars to import for the email confirmation
			$template->set_vars(array(
														'is_email_to_shopper' => true,
														'db' => $db,
														'dboi' => $dboi,
														'dbbt' => $dbbt,
														'dbst' => $dbst,
														'ps_product' => $ps_product,
														'shippingfields' => $shippingfields,
														'registrationfields' => $registrationfields,
														'order_id' => $order_id,
														'order_discount' => $order_discount,
														'order_discount_lbl' => $order_discount_lbl,
														'order_discount_plusminus' => $order_discount_plusminus,			
														'coupon_discount' => $coupon_discount,
														'coupon_discount_lbl' => $coupon_discount_lbl,
														'coupon_discount_plusminus' => $coupon_discount_plusminus,
														'order_date' => $VM_LANG->convert( vmFormatDate($db->f("cdate"), $VM_LANG->_('DATE_FORMAT_LC') )),
														'order_status' => $order_status,
														'legal_info_title' => $legal_info_title,
														'legal_info_html' => $legal_info_html,
														'order_link' => $shopper_order_link,
			
														'payment_info_lbl' => $VM_LANG->_('PHPSHOP_ORDER_PRINT_PAYINFO_LBL'),
														'payment_info_details' => $payment_info_details,
														'shipping_info_lbl' => $VM_LANG->_('PHPSHOP_ORDER_PRINT_SHIPPING_LBL'),
														'shipping_info_details' => $shipping_info_details,
			
														'from_email' => $from_email,
														'customer_note' => nl2br($customer_note),
														'order_header_msg' => $shopper_header,
			
														'order_subtotal' => $CURRENCY_DISPLAY->getFullValue($sub_total, '', $db->f('order_currency')),
														'order_shipping' => $CURRENCY_DISPLAY->getFullValue($order_shipping, '', $db->f('order_currency')),
														'order_tax' => $CURRENCY_DISPLAY->getFullValue($order_tax, '', $db->f('order_currency')). ps_checkout::show_tax_details( $db->f('order_tax_details'), $db->f('order_currency') ),
														'order_total' => $CURRENCY_DISPLAY->getFullValue($order_total, '', $db->f('order_currency')),
			
											));
			$shopper_html = $template->fetch('order_emails/confirmation_email.tpl.php');
			
			// Reset the list of order items for use in the vendor email
			$dboi->reset();
			
			// Override some vars for the vendor email, so we can use the same template
			$template->set_vars(array(
														'order_header_msg' => $vendor_header,
														'order_link' => $vendor_order_link,
														'is_email_to_shopper' => false
											));
											
			$vendor_html = $template->fetch('order_emails/confirmation_email.tpl.php');


			/*
			* Add the text, html and embedded images.
			* The name of the image should match exactly
			* (case-sensitive) to the name in the html.
			*/
			$shopper_mail_Body = $shopper_html;
			$shopper_mail_AltBody = $shopper_header . $shopper_message . $shopper_footer;

			$vendor_mail_Body = $vendor_html;
			$vendor_mail_AltBody = $vendor_header . $shopper_message . $vendor_footer;

			$imagefile = pathinfo($dbv->f("vendor_full_image"));
			$extension = $imagefile['extension'] == "jpg" ? "jpeg" : "jpeg";

			$EmbeddedImages[] = array(	'path' => IMAGEPATH."vendor/".$dbv->f("vendor_full_image"),
								'name' => "vendor_image", 
								'filename' => $dbv->f("vendor_full_image"),
								'encoding' => "base64",
								'mimetype' => "image/".$extension );

			
			$shopper_mail = vmMail( $from_email, $mosConfig_fromname, $shopper_email, $shopper_subject, $shopper_mail_Body, $shopper_mail_AltBody, true, null, null, $EmbeddedImages);

			$vendor_mail = vmMail( $from_email, $mosConfig_fromname, $vendor_email, $vendor_subject, $vendor_mail_Body, $vendor_mail_AltBody, true, null, null, $EmbeddedImages, null, $shopper_email);

			if ( !$shopper_mail || !$vendor_mail ) {
				
				$vmLogger->debug( 'Something went wrong while sending the order confirmation email to '.$from_email.' and '.$shopper_email );
				return false;
			}
			//
			// END: set up and send the HTML email
			////////////////////////////////////////
		}

		return true;

	} // end of function email_receipt()



	/**
	 * Return $str with all but $display_length at the end as asterisks.
	 * @author gday
	 *
	 * @param string $str The string to mask
	 * @param int $display_length The length at the end of the string that is NOT masked
	 * @param boolean $reversed When true, masks the end. Masks from the beginning at default
	 * @return string The string masked by asteriks
	 */
	function asterisk_pad($str, $display_length, $reversed = false) {

		$total_length = strlen($str);

		if($total_length > $display_length) {
			if( !$reversed) {
				for($i = 0; $i < $total_length - $display_length; $i++) {
					$str[$i] = "*";
				}
			}
			else {
				for($i = $total_length-1; $i >= $total_length - $display_length; $i--) {
					$str[$i] = "*";
				}
			}
		}

		return($str);
	}

	/**
	 * Displays the order_tax_details array when it contains
	 * more than one 
	 * @param mixed $details
	 * @return string
	 */
	function show_tax_details( $details, $currency = ''  ) {
		global $discount_factor, $CURRENCY_DISPLAY, $VM_LANG;
		
		if( !isset( $discount_factor) || !empty($_REQUEST['discount_factor'])) {
			$discount_factor = 1;
		}
		$auth = $_SESSION['auth'];
		if( !is_array( $details )) {
			$details = @unserialize( $details );
			if( !is_array($details)) {
				return false;
			}
		}
		$html_rate = '';
		$html = '';
		if( sizeof( $details) > 1 ) {
			foreach ($details as $rate => $value ) {
				if( !$auth['show_price_including_tax']) {
					$value /= $discount_factor;
				}
				if ( !empty($value) ){
				$rate = str_replace( '-', $CURRENCY_DISPLAY->decimal, $rate )*100;
				$html_rate .= $CURRENCY_DISPLAY->getFullValue( $value, 5, $currency ).' ('.$rate.'% '.$VM_LANG->_('PHPSHOP_CART_TAX').')<br />';
				}
			}
			if ( !empty( $html_rate )){
				$html = '<br />'.$VM_LANG->_('VM_TAXDETAILS_LABEL').':<br />'.$html_rate ;
			}
		}
		return $html;
	}
	
	/*
	* @abstract This function is very useful to round totals with definite decimals.
	*
	* @param float   $value
	* @param integer $dec
	* @return float
	*/
	function approx( $value, $dec = 2 ) {
		$value += 0.0;
		$unit  = floor( $value * pow( 10, $dec + 1 ) ) / 10;
		$round = round( $unit );
		return $round / pow( 10, $dec );
	}



	/**
	 * If the customer is in the EU then tax should be charged according to the
	 *  vendor's address, and this function will return true.
	 */
	function tax_based_on_vendor_address ($ship_to_info_id = '') {
		global $__tax_based_on_vendor_address;
		global $vmLogger;
	
		if (!isset ($__tax_based_on_vendor_address)) {
			$__tax_based_on_vendor_address = ps_checkout::_tax_based_on_vendor_address ($ship_to_info_id);
			if ($__tax_based_on_vendor_address)
				$vmLogger->debug ('calculating tax based on vendor address');
			else
				$vmLogger->debug ('calculating tax based on shipping address');
		}
		return $__tax_based_on_vendor_address;
	}
	
	function _tax_based_on_vendor_address ($ship_to_info_id = '') {
		global $auth;
		global $vmLogger;
	
		switch (TAX_MODE) {
		case '0':
			return false;
	
		case '1':
			return true;
	
		case '17749':

			$ship_to_info_id = !empty($ship_to_info_id)? $ship_to_info_id : vmGet( $_REQUEST, 'ship_to_info_id');

			$db = new ps_DB;
			$q  = "SELECT country FROM #__{vm}_user_info WHERE user_info_id='" . $ship_to_info_id ."'";
			$db->query($q);
			$db->next_record();
			$ship_country = $db->f("country");

			if ( !array_key_exists ('country', $auth) && empty( $ship_country ) ) {
				$vmLogger->debug ('shopper\'s country is not known; defaulting to vendor-based tax');
				return true;
			}

			if ( $ship_to_info_id ) {
				$vmLogger->debug ('shopper shipping in ' . $ship_country);
				$auth_country = $ship_country;
			}
			else {
				$vmLogger->debug ('shopper is in ' . $auth['country']);
				$auth_country = $auth['country'];
			}
				return ps_checkout::country_in_eu_common_vat_zone ( $auth_country );

		default:
			$vmLogger->warning ('unknown TAX_MODE "' . TAX_MODE . '"');
			return true;
		}
	}
	
	function country_in_eu_common_vat_zone ($country) {
		$eu_countries = array ('AUT', 'BGR', 'BEL', 'CYP', 'CZE', 'DEU', 'DNK', 'ESP', 'EST', 
								'FIN', 'FRA', 'FXX', 'GBR', 'GRC', 'HUN', 'IRL', 'ITA', 'LVA', 'LTU', 
								'LUX', 'MLT', 'NLD', 'POL', 'PRT', 'ROM', 'SVK', 'SVN', 'SWE');
		return in_array ($country, $eu_countries);
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
	class ps_checkout extends vm_ps_checkout {}
}
?>
