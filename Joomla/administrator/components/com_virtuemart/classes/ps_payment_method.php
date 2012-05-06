<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: ps_payment_method.php 2533 2010-09-11 12:49:46Z zanardi $
* @package VirtueMart
* @subpackage classes
* @copyright Copyright (C) 2004-2007 soeren - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/
define('UNKNOWN', 0);
define('MASTERCARD', 1);
define('VISA', 2);
define('AMEX', 3);
define('DINNERS', 4);
define('DISCOVER', 5);
define('ENROUTE', 6);
define('JCB', 7);
define('BANKCARD', 8);
define('SOLO_MAESTRO', 9);
define('SWITCH_MAESTRO', 10);
define('SWITCH_', 11);
define('MAESTRO ', 12);
define('UK_ELECTRON', 13);
define('SWITCHCARD', 14);

define('CC_OK', 0);
define('CC_ECALL', 1);
define('CC_EARG', 2);
define('CC_ETYPE', 3);
define('CC_ENUMBER', 4);
define('CC_EFORMAT', 5);
define('CC_ECANTYPE', 6);

class vm_ps_payment_method extends vmAbstractObject {

	// CreditCard Validation vars
	var $number = 0;
	var $type = UNKNOWN;
	var $errno = CC_OK;

	/**
	 * Validates the Input Parameters on Payment Add
	 *
	 * @param array $d
	 * @return boolean
	 */
	function validate_add(&$d) {
		global $VM_LANG;

		if (empty($d["payment_method_name"])) {
			$GLOBALS['vmLogger']->err( $VM_LANG->_('VM_PAYMENTMETHOD_ERR_NAME') );
			return False;
		}
		if (empty($d["payment_method_code"])) {
			$GLOBALS['vmLogger']->err( $VM_LANG->_('VM_PAYMENTMETHOD_ERR_CODE') );
			return False;
		}

		$d['is_creditcard'] = !empty( $d['creditcard']) ? '1' : '0';

		if (empty($d['payment_class'])) {
			$d['payment_class'] = "";
		}

		if (empty($d["payment_enabled"])) {
			$d["payment_enabled"] = "N";
		}
		if (empty($d["creditcard"])) {
			$d["accepted_creditcards"] = "";
		}
		else {
			$d["accepted_creditcards"] = "";
			foreach($d['creditcard'] as $num => $creditcard_id) {
				$d["accepted_creditcards"] .= $creditcard_id . ",";
			}
		}

		return true;
	}

	/**
	 * Validates the Input Parameters on Payment Update
	 *
	 * @param array $d
	 * @return boolean
	 */
	function validate_update(&$d) {
		global $VM_LANG;

		if (!$d["payment_method_code"]) {
			$GLOBALS['vmLogger']->err( $VM_LANG->_('VM_PAYMENTMETHOD_ERR_CODE') );
			return False;
		}
		$d['is_creditcard'] = !empty( $d['creditcard']) ? '1' : '0';

		if (empty($d['payment_class']))
		$d['payment_class'] = "";

		if (empty($d["payment_enabled"])) {
			$d["payment_enabled"] = "N";
		}
		if (empty($d["creditcard"])) {
			$d["accepted_creditcards"] = "";
		}
		else {
			$d["accepted_creditcards"] = "";
			foreach($d['creditcard'] as $num => $creditcard_id) {
				$d["accepted_creditcards"] .= $creditcard_id . ",";
			}
		}

		if (empty($d["payment_method_name"])) {
			$GLOBALS['vmLogger']->err( $VM_LANG->_('VM_PAYMENTMETHOD_ERR_NAME') );
			return False;
		}

		if (empty($d["payment_method_id"])) {
			$GLOBALS['vmLogger']->err( $VM_LANG->_('VM_PAYMENTMETHOD_UPDATE_SELECT') );
			return False;
		}

		return True;
	}

	/**
	 * Validates the Input Parameters on Payment Update
	 *
	 * @param array $d
	 * @return boolean
	 */
	function validate_delete(&$d) {
		global $VM_LANG;

		if (!$d["payment_method_id"]) {
			$GLOBALS['vmLogger']->err( $VM_LANG->_('VM_PAYMENTMETHOD_DELETE_SELECT') );
			return False;
		}

		return True;
	}

	/**
	 * Adds a new payment method
	 *
	 * @param array $d
	 * @return boolean
	 */
	function add(&$d) {
		global $VM_LANG;

		$ps_vendor_id = $_SESSION["ps_vendor_id"];
		$db = new ps_DB;

		if (!$this->validate_add($d)) {
			return False;
		}
		if ( !empty($d["payment_class"]) ) {
			// Here we have a custom payment class
			$payment_class = basename($d["payment_class"]);
			if( file_exists( CLASSPATH."payment/".$payment_class.".php" ) ) {
				// Include the class code and create an instance of this class
				include( CLASSPATH."payment/".$payment_class.".php" );
				if( class_exists($payment_class)) {
					$_PAYMENT = new $payment_class();
				} else {
					$GLOBALS['vmLogger']->err($VM_LANG->_('VM_PAYMENTMETHOD_CLASS_NOT_EXIST'));
					return false;
				}
			}
		}
		else {
			// ps_payment is the default payment method handler
			include( CLASSPATH."payment/ps_payment.php" );
			$_PAYMENT = new ps_payment();
		}
        if( is_callable( array( $_PAYMENT, 'write_configuration'))) {
    	    $_PAYMENT->write_configuration( $d );
        }

		if (!$d["shopper_group_id"]) {
			$q =  "SELECT shopper_group_id FROM #__{vm}_shopper_group WHERE ";
			$q .= "`default`='1' ";
			$q .= "AND vendor_id='$ps_vendor_id'";
			$db->query($q);
			$db->next_record();
			$d["shopper_group_id"] = $db->f("shopper_group_id");
		}

		$fields = array( 'vendor_id' => $ps_vendor_id,
						'payment_method_name' => vmGet($d, 'payment_method_name' ),
						'payment_class' => vmGet($d, 'payment_class' ),
						'shopper_group_id' => vmRequest::getInt('shopper_group_id'),
						'payment_method_discount' => vmRequest::getFloat('payment_method_discount'),
						'payment_method_discount_is_percent' => vmGet($d, 'payment_method_discount_is_percent'),
						'payment_method_discount_max_amount' => (float)str_replace(',', '.', $d["payment_method_discount_max_amount"]),
						'payment_method_discount_min_amount' => (float)str_replace(',', '.', $d["payment_method_discount_min_amount"]),
						'payment_method_code' => vmGet($d, 'payment_method_code'),
						'enable_processor' => vmGet($d, 'enable_processor'),
						'list_order' => vmRequest::getInt('list_order'),
						'is_creditcard' => vmGet($d, 'is_creditcard'),
						'payment_enabled' => vmGet($d, 'payment_enabled'),
						'accepted_creditcards' => vmGet($d, 'accepted_creditcards'),
						'payment_extrainfo' => vmGet( $_POST, 'payment_extrainfo', null, VMREQUEST_ALLOWRAW )
				);
		$db->buildQuery( 'INSERT', '#__{vm}_payment_method', $fields );
		$db->query();

		$_REQUEST['payment_method_id'] = $db->last_insert_id();

		return True;

	}

	/**
	 * Updates a Payment Entry
	 *
	 * @param array $d
	 * @return boolean
	 */
	function update(&$d) {
		global $VM_LANG;

		global $vmLogger, $VM_LANG;
		$ps_vendor_id = $_SESSION["ps_vendor_id"];

		$db = new ps_DB;

		if (!$this->validate_update($d)) {
			return False;
		}

		if ( !empty($d["payment_class"]) ) {
			$payment_class = basename($d["payment_class"]);
			@include( CLASSPATH."payment/".$payment_class.".php" );
			if( class_exists($payment_class)) {
				$_PAYMENT = new $payment_class();
			} else {
				$GLOBALS['vmLogger']->err($VM_LANG->_('VM_PAYMENTMETHOD_CLASS_NOT_EXIST'));
				return false;
			}
		}
		else {
			include( CLASSPATH."payment/ps_payment.php" );
			$_PAYMENT = new ps_payment();
		}
		if( $_PAYMENT->configfile_writeable() || $_PAYMENT->classname == 'ps_payment' ) {
			$_PAYMENT->write_configuration( $d );
			$vmLogger->info( $VM_LANG->_('VM_CONFIGURATION_CHANGE_SUCCESS',false) );
		}
		else {
			$vmLogger->err( sprintf($VM_LANG->_('VM_CONFIGURATION_CHANGE_FAILURE',false) , CLASSPATH."payment/".$_PAYMENT->classname.".cfg.php" ) );
			return false;
		}

		$fields = array( 'payment_method_name' => vmGet($d, 'payment_method_name' ),
						'payment_class' => vmGet($d, 'payment_class' ),
						'shopper_group_id' => vmRequest::getInt('shopper_group_id'),
						'payment_method_discount' => vmRequest::getFloat('payment_method_discount'),
						'payment_method_discount_is_percent' => vmGet($d, 'payment_method_discount_is_percent'),
						'payment_method_discount_max_amount' => (float)str_replace(',', '.', $d["payment_method_discount_max_amount"]),
						'payment_method_discount_min_amount' => (float)str_replace(',', '.', $d["payment_method_discount_min_amount"]),
						'payment_method_code' => vmGet($d, 'payment_method_code'),
						'enable_processor' => vmGet($d, 'enable_processor'),
						'list_order' => vmRequest::getInt('list_order'),
						'is_creditcard' => vmGet($d, 'is_creditcard'),
						'payment_enabled' => vmGet($d, 'payment_enabled'),
						'accepted_creditcards' => vmGet($d, 'accepted_creditcards'),
						'payment_extrainfo' => vmGet( $_POST, 'payment_extrainfo', null, VMREQUEST_ALLOWRAW )
				);
		$db->buildQuery( 'UPDATE', '#__{vm}_payment_method', $fields, 'WHERE payment_method_id='.(int)$d["payment_method_id"].' AND vendor_id='.$ps_vendor_id );
		$db->query();

		return True;
	}

	/**
	* Controller for Deleting Records.
	*/
	function delete(&$d) {

		if (!$this->validate_delete($d)) {
			return False;
		}
		$record_id = $d["payment_method_id"];

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
		$ps_vendor_id = $_SESSION["ps_vendor_id"];

		$q = 'DELETE from #__{vm}_payment_method WHERE payment_method_id='.(int)$record_id.' AND ';
		$q .= "\nvendor_id='$ps_vendor_id'";
		$db->query($q);

		return True;
	}

	/**
	 * Prints a drop-down list with all available payment methods
	 *
	 * @param int $payment_method_id
	 */
	function list_method($payment_method_id) {
		global $VM_LANG;

		$ps_vendor_id = $_SESSION["ps_vendor_id"];
		$db = new ps_DB;

		require_once(CLASSPATH.'ps_shopper_group.php');
		$ps_shopper_group = new ps_shopper_group;


		$q =  "SELECT * from #__{vm}_shopper_group WHERE ";
		$q .= "`default`='1' ";
		$q .= "AND vendor_id='$ps_vendor_id'";
		$db->query($q);
		if (!$db->num_rows()) {
			$q =  "SELECT * from #__{vm}_shopper_group WHERE ";
			$q .= "vendor_id='$ps_vendor_id'";
			$db->query($q);
		}
		$db->next_record();
		$default_shopper_group_id = $db->f("shopper_group_id");


		$q = "SELECT * from #__{vm}_payment_method WHERE ";
		$q .= "vendor_id='$ps_vendor_id' AND ";
		$q .= "shopper_group_id='$default_shopper_group_id' ";
		if ($ps_shopper_group->get_id() != $default_shopper_group_id)
		$q .= "OR shopper_group_id='".$ps_shopper_group->get_id()."' ";
		$q .= "ORDER BY list_order";
		$db->query($q);

		// Start drop down list

		$array[0] = $VM_LANG->_('PHPSHOP_SELECT');
		while ($db->next_record()) {
			$array[$db->f("payment_method_id")] = $db->f("payment_method_name");
		}
		ps_html::dropdown_display('payment_method_id', $payment_method_id, $array );

	}

	/**
	 * Returns all payment_methods with given selector in a Radiolist
	 *
	 * @param string $selector A String like "B" identifying a type of payment methods
	 * @param int $payment_method_id An ID to preselect
	 * @param boolean $horiz Separate Items with Spaces if true, else with <br />
	 * @return string
	 */
	function list_payment_radio($selector, $payment_method_id, $horiz) {
		global $CURRENCY_DISPLAY, $ps_checkout;
		$ps_vendor_id = $_SESSION["ps_vendor_id"];
		$auth = $_SESSION["auth"];
		$db = new ps_DB;
		if( !isset( $ps_checkout )) { $ps_checkout = new ps_checkout(); }

		require_once(CLASSPATH.'ps_shopper_group.php');
		$ps_shopper_group = new ps_shopper_group;

		$q =  "SELECT shopper_group_id from #__{vm}_shopper_group WHERE ";
		$q .= "`default`='1' ";
		$db->query($q);
		if (!$db->num_rows()) {
			$q =  "SELECT shopper_group_id from #__{vm}_shopper_group";
			$db->query($q);
		}
		$db->next_record();
		$default_shopper_group_id = $db->f("shopper_group_id");

		$q = "SELECT payment_method_id,payment_method_discount, payment_method_discount_is_percent, payment_method_name from #__{vm}_payment_method WHERE ";
		$q .= "(enable_processor='$selector') AND ";
		$q .= "payment_enabled='Y' AND ";
		$q .= "vendor_id='$ps_vendor_id' AND ";

		if ($auth["shopper_group_id"] == $default_shopper_group_id) {
			$q .= "shopper_group_id='$default_shopper_group_id' ";
		} else {
			$q .= "(shopper_group_id='$default_shopper_group_id' ";
			$q .= "OR shopper_group_id='".$auth["shopper_group_id"]."') ";
		}

		$q .= "ORDER BY list_order";
		$db->query($q);
		$has_result = false;
		// Start radio list
		while ($db->next_record()) {
			$has_result = true;
			echo "<input type=\"radio\" name=\"payment_method_id\" id=\"".$db->f("payment_method_name")."\" value=\"".$db->f("payment_method_id")."\" ";
			if( $selector == "' OR enable_processor='Y" ) {
				echo "onchange=\"javascript: changeCreditCardList();\" ";
			}
			if ((($db->f("payment_method_id") == $payment_method_id) || $db->num_rows() < 2) && !@$GLOBALS['payment_selected']) {
				echo "checked=\"checked\" />\n";
				$GLOBALS['payment_selected'] = true;
			}
			else
			echo "/>\n";
			$discount  = $ps_checkout->get_payment_discount( $db->f("payment_method_id") );
			echo "<label for=\"".$db->f("payment_method_name")."\">".$db->f("payment_method_name");
			if ($discount > 0.00) {
				echo " (- ".$CURRENCY_DISPLAY->getFullValue(abs($discount)).") \n";
			}
			elseif ($discount < 0.00) {
				echo " (+ ".$CURRENCY_DISPLAY->getFullValue(abs($discount)).") \n";
			}
			echo "</label>";
			if ($horiz) {
				echo(" ");
			} else {
				echo("<br />");
			}
		}
		return $has_result;
	}

	/**
	 * Query the payment_method Table for the given ID
	 *
	 * @param int $payment_method_id
	 * @return ps_DB
	 */
	function payment_sql($payment_method_id) {
		$db = new ps_DB;
		$q = 'SELECT * FROM #__{vm}_payment_method WHERE payment_method_id='.(int)$payment_method_id;
		$db->query($q);
		return $db;
	}

	/**
	 * Returns all CreditCards in a Radiolist
	 *
	 * @param int $payment_method_id
	 * @param boolean $horiz
	 */
	function list_cc($payment_method_id, $horiz) {
		$this->list_payment_radio("' OR enable_processor='Y",$payment_method_id, $horiz); //A bit strange :-)
	}

	/**
	 * Returns all Bank payment in a Radiolist
	 *
	 * @param int $payment_method_id
	 * @param boolean $horiz
	 */
	function list_bank($payment_method_id, $horiz) {
		$has_bank_methods = $this->list_payment_radio("B", $payment_method_id, $horiz); //A bit easier :-)
		if( $has_bank_methods ) {
			require_once( CLASSPATH . 'ps_user.php' );
			$dbu =& ps_user::getUserInfo( $_SESSION['auth']['user_id'], array( 'bank_account_holder','bank_iban','bank_account_nr','bank_sort_code','bank_name' ) );
			if( !$dbu->f('bank_account_holder') || !$dbu->f('bank_account_nr') || !$dbu->f('bank_sort_code')) {
				echo '<br />';
				require_once( CLASSPATH . 'ps_userfield.php');
				ps_userfield::listUserFields( ps_userfield::getUserfields( 'bank' ), array(), $dbu );
			}
		}
	}

	/**
	 * Returns all Payment methods which need no check
	 *
	 * @param int $payment_method_id
	 * @param boolean $horiz
	 */
	function list_nocheck($payment_method_id, $horiz) {
		$this->list_payment_radio("N",$payment_method_id, $horiz); //A bit easier :-)
	}

	/**
	 * Returns all Payment methods which a paypal - like
	 *
	 * @param int $payment_method_id
	 * @param boolean $horiz
	 */
	function list_paypalrelated($payment_method_id, $horiz) {
		$this->list_payment_radio("P",$payment_method_id, $horiz); //A bit easier :-)
	}

	/**
	* get_field public method
	* @return string
	*/
	function get_field($payment_method_id, $field_name) {

		$db = new ps_DB;

		$q = 'SELECT `'.$field_name.'` FROM `#__{vm}_payment_method` WHERE `payment_method_id`='.(int)$payment_method_id;
		$db->query($q);
		$db->next_record();
		return $db->f($field_name);
	}

	/**
	 * returns true if the payment is credit card payment
	 *
	 * @param int $payment_id
	 * @return boolean
	 */
	function is_creditcard($payment_id) {

		$db = new ps_DB;
		$q = "SELECT is_creditcard,accepted_creditcards FROM #__{vm}_payment_method\n";
		$q .= 'WHERE payment_method_id='.(int)$payment_id;
		$db->query($q);
		$db->next_record();
		$details = $db->f('accepted_creditcards');

		return $details != "";

	}

	/**
	 * Validates the Payment Method (Credit Card Number)
	 * Adapted From CreditCard Class
	 * Copyright (C) 2002 Daniel Fr�z Costa
	 *
	 * Documentation:
	 *
	 * Card Type                   Prefix           Length     Check digit
	 * - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
	 * MasterCard                  51-55            16         mod 10
	 * Visa                        4                13, 16     mod 10
	 * AMEX                        34, 37           15         mod 10
	 * Dinners Club/Carte Blanche  300-305, 36, 38  14         mod 10
	 * Discover                    6011             16         mod 10
	 * enRoute                     2014, 2149       15         any
	 * JCB                         3                16         mod 10
	 * JCB                         2131, 1800       15         mod 10
	 *
	 * More references:
	 * http://www.beachnet.com/~hstiles/cardtype.hthml.
	  *
	  * @param string $creditcard_code
	  * @param string $cardnum
	  * @return boolean
	 */
	function validate_payment($creditcard_code, $cardnum) {

		$this->number = $this->_strtonum($cardnum);
		/*
		if(!$this->detectType($this->number))
		{
		$this->errno = CC_ETYPE;
		$d['error'] = $this->errno;
		return false;
		}*/

		if(empty($this->number) || !$this->mod10($this->number))
		{
			$this->errno = CC_ENUMBER;
			$d['error'] = $this->errno;
			return false;
		}

		return true;
	}

	/**
	 * detectType method: returns card type in number format
	 *
	 * @param string $cardnum
	 * @return boolean
	 */
	function detectType($cardnum = 0){
		if($cardnum)
		$this->number = $this->_strtonum($cardnum);
		if(!$this->number) {
			$this->errno = CC_ECALL;
			return UNKNOWN;
		}

		if(preg_match("/^5[1-5]\d{14}$/", $this->number)) {
			$this->type = MASTERCARD;
		}
		elseif(preg_match("/^4(\d{12}|\d{15})$/", $this->number)) {
			$this->type = VISA;
		}
		else if(preg_match("/^3[47]\d{13}$/", $this->number)) {
			$this->type = AMEX;
		}
		else if(preg_match("/^[300-305]\d{11}$/", $this->number) || preg_match("/^3[68]\d{12}$/", $this->number)) {
			$this->type = DINNERS;
		}
		elseif (ereg ('^6334[5-9].{11}$', $this->number) || ereg ('^6767[0-9].{11}$', $this->number)) {
			$this->type = SOLO_MAESTRO;
		}
		elseif (ereg ('^564182[0-9].{9}$', $this->number) || ereg ('^6333[0-4].{11}$', $this->number) || ereg ('^6759[0-9].{11}$', $this->number)) {
			$this->type= SWITCH_MAESTRO;
		}
		elseif (ereg ('^49030[2-9].{10}$', $this->number) || ereg ('^49033[5-9].{10}$', $this->number) || ereg ('^49110[1-2].{10}$', $this->number) || ereg ('^49117[4-9].{10}$', $this->number) || ereg ('^49118[0-2].{10}$', $this->number) || ereg ('^4936[0-9].{11}$', $this->number)) {
			$this->type = SWITCH_;
		}
		//failing earlier 6xxx xxxx xxxx xxxx checks then its a Maestro card
		elseif (ereg ('^6[0-9].{14}$', $this->number) || ereg ('^5[0,6-8].{14}$', $this->number)) {
			$this->type = MAESTRO;
		}
		elseif (ereg ('^450875[0-9].{9}$', $this->number)
					|| ereg ('^48440[6-8].{10}$', $this->number)
					|| ereg ('^48441[1-9].{10}$', $this->number)
					|| ereg ('^4844[2-4].{11}$', $this->number)
					|| ereg ('^48445[0-5].{10}$', $this->number)
					|| ereg ('^4917[3-5].{11}$', $this->number)
					|| ereg ('^491880[0-9].{9}$', $this->number)) {
			$this->type= UK_ELECTRON;
		}
		//DB 18-07-05
		else if(preg_match("/^6\d{15,21}$/", $this->number)) {
			$this->type = SWITCHCARD;
		}
		else if(preg_match("/^6011\d{12}$/", $this->number)) {
			$this->type = DISCOVER;
		}
		else if(preg_match("/^5610\d{12}$/", $this->number)) {
			$this->type = BANKCARD;
		}
		else if(preg_match("/^2(014|149)\d{11}$/", $this->number)) {
			$this->type = ENROUTE;
		}
		else if(preg_match("/^3\d{15}$/", $this->number) ||  preg_match("/^(2131|1800)\d{11}$/", $this->number)) {
			$this->type = JCB;
		}

		if(!$this->type) {
			$this->errno = CC_ECANTYPE;
			return UNKNOWN;
		}
		return $this->type;
	}

	/*
	* detectTypeString
	*   return string of card type
	*/
	function detectTypeString($cardnum = 0) {
		if(!$cardnum) {
			if(!$this->type)
			$this->errno = CC_EARG;
		}
		else {
			$this->type = $this->detectType($cardnum);
		}

		if(!$this->type) {
			$this->errno = CC_ETYPE;
			return NULL;
		}

		switch($this->type) {
			case MASTERCARD:
				return "MASTERCARD";
			case VISA:
				return "VISA";
			case AMEX:
				return "AMEX";
			case DINNERS:
				return "DINNERS";
			case DISCOVER:
				return "DISCOVER";
			case ENROUTE:
				return "ENROUTE";
			case JCB:
				return "JCB";
			default:
				$this->errno = CC_ECANTYPE;
				return NULL;
		}
	}

	/*
	* getCardNumber
	*   returns card number, only digits
	*/
	function getCardNumber(){
		if(!$this->number){
			$this->errno = CC_ECALL;
			return 0;
		}
		return $this->number;
	}

	/*
	* errno method
	*   return error number
	*/
	function errno(){
		return $this->errno;
	}

	/*
	* mod10 method - Luhn check digit algorithm
	*   return 0 if true and !0 if false
	*/
	function mod10( $card_number ){

		$digit_array = array ();
		$cnt = 0;

		//Reverse the card number
		$card_temp = strrev ( $card_number );

		//Multiple every other number by 2 then ( even placement )
		//Add the digits and place in an array
		for ( $i = 1; $i <= strlen ( $card_temp ) - 1; $i = $i + 2 ) {
			//multiply every other digit by 2
			$t = substr ( $card_temp, $i, 1 );
			$t = $t * 2;
			//if there are more than one digit in the
			//result of multipling by two ex: 7 * 2 = 14
			//then add the two digits together ex: 1 + 4 = 5
			if ( strlen ( $t ) > 1 ) {
				//add the digits together
				$tmp = 0;
				//loop through the digits that resulted of
				//the multiplication by two above and add them
				//together
				for ( $s = 0; $s < strlen ( $t ); $s++ ) {
					$tmp = substr ( $t, $s, 1 ) + $tmp;
				}
			}
			else{  // result of (* 2) is only one digit long
				$tmp = $t;
			}
			//place the result in an array for later
			//adding to the odd digits in the credit card number
			$digit_array [ $cnt++ ] = $tmp;
		}
		$tmp = 0;

		//Add the numbers not doubled earlier ( odd placement )
		for ( $i = 0; $i <= strlen ( $card_temp ); $i = $i + 2 ) {
			$tmp = substr ( $card_temp, $i, 1 ) + $tmp;
		}

		//Add the earlier doubled and digit-added numbers to the result
		$result = $tmp + array_sum ( $digit_array );

		//Check to make sure that the remainder
		//of dividing by 10 is 0 by using the modulas
		//operator
		return ( $result % 10 == 0 );

	}

	/*
	* resetCard method
	*   clear only cards information
	*/
	function resetCard() {
		$this->number = 0;
		$this->type = 0;
	}

	/*
	* strError method
	*   return string error
	*/
	function strError() {
		switch($this->errno) {
			case CC_ECALL:
				return "Invalid call for this method";
			case CC_ETYPE:
				return "Invalid card type";
			case CC_ENUMBER:
				return "Invalid card number";
			case CC_EFORMAT:
				return "Invalid format";
			case CC_ECANTYPE:
				return "Cannot detect the type of your card";
			case CC_OK:
				return "Success";
		}
	}

	/*
	* _strtonum private method
	*   return formated string - only digits
	*/
	function _strtonum($string) {
		$nstr = "";
		for($i=0; $i< strlen($string); $i++) {
			if(!is_numeric($string{$i}))
			continue;
			$nstr = "$nstr".$string{$i};
		}
		return $nstr;
	}
	/**
	 * Lists all available payment classes in the payment directory
	 *
	 * @param string $name
	 * @param string $preselected
	 * @return string
	 */
	function list_available_classes( $name, $preselected='ps_payment' ) {

		$files = vmReadDirectory( CLASSPATH."payment/", ".php", true, true);
		$array = array();
        foreach ($files as $file) {
            $file_info = pathinfo($file);
            $filename = $file_info['basename'];
            if( stristr($filename, '.cfg')) { continue; }
            $array[basename($filename, '.php' )] = basename($filename, '.php' );
        }
        return ps_html::selectList( $name, $preselected, $array );
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
	class ps_payment_method extends vm_ps_payment_method {}
}
?>
