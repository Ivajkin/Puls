<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
* The ps_authorize class, containing the payment processing code
*  for transactions with authorize.net 
*
* @version $Id: ps_authorize.php 1958 2009-10-08 20:09:57Z soeren_nb $
* @package VirtueMart
* @subpackage payment
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

class ps_authorize {

	var $payment_code = "AN";

	/**
    * Show all configuration parameters for this payment method
    * @returns boolean False when the Payment method has no configration
    */
	function show_configuration() {

		global $VM_LANG, $sess;
		$db = new ps_DB;
		$payment_method_id = vmGet( $_REQUEST, 'payment_method_id', null );
		/** Read current Configuration ***/
		require_once(CLASSPATH ."payment/".__CLASS__.".cfg.php");
    ?>
      <table class="adminform">
        <tr class="row0">
            <td class="labelcell"><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_ENABLE_AUTORIZENET_TESTMODE') ?></td>
            <td>
                <select name="AN_TEST_REQUEST" class="inputbox" >
                <option <?php if (AN_TEST_REQUEST == 'TRUE') echo "selected=\"selected\""; ?> value="TRUE"><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_YES') ?></option>
                <option <?php if (AN_TEST_REQUEST == 'FALSE') echo "selected=\"selected\""; ?> value="FALSE"><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_NO') ?></option>
                </select>
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_ENABLE_AUTORIZENET_TESTMODE_EXPLAIN') ?>
            </td>
        </tr>
        <tr class="row1">
            <td class="labelcell">Authorize.net Server Hostname</td>
            <td>
                <input type="text" name="AN_HOSTNAME" value="<?php echo defined('AN_HOSTNAME') ? AN_HOSTNAME : 'secure.authorize.net' ?>" />
            </td>
            <td>Name of the Authorize.net Server, the requests are sent to. Default Value: <strong>secure.authorize.net</strong><br />
            DO NOT CHANGE UNLESS YOU KNOW WHAT YOU'RE DOING. 
            </td>
        </tr>
        <tr class="row0">
            <td class="labelcell"><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_AUTORIZENET_USERNAME') ?></td>
            <td>
                <input type="text" name="AN_LOGIN" class="inputbox" value="<?php echo AN_LOGIN ?>" />
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_AUTORIZENET_USERNAME_EXPLAIN') ?>
            </td>
        </tr>
        <tr class="row1">
            <td class="labelcell"><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_AUTORIZENET_KEY') ?></td>
            <td>
                <a class="button" id="changekey" href="<?php $sess->purl($_SERVER['PHP_SELF']."?page=store.payment_method_keychange&pshop_mode=admin&payment_method_id=$payment_method_id") ?>" >
                <?php echo $VM_LANG->_('PHPSHOP_CHANGE_TRANSACTION_KEY') ?><a/>
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr class="row0">
            <td class="labelcell"><?php echo $VM_LANG->_('PHPSHOP_PAYMENT_CVV2') ?></td>
            <td>
                <select name="AN_CHECK_CARD_CODE" class="inputbox">
                <option <?php if (AN_CHECK_CARD_CODE == 'YES') echo "selected=\"selected\""; ?> value="YES">
                <?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_YES') ?></option>
                <option <?php if (AN_CHECK_CARD_CODE == 'NO') echo "selected=\"selected\""; ?> value="NO">
                <?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_NO') ?></option>
                </select>
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_PAYMENT_CVV2_TOOLTIP') ?></td>
        </tr>
        <tr class="row1">
            <td class="labelcell"><?php echo $VM_LANG->_('PHPSHOP_PAYMENT_AN_RECURRING') ?></td>
            <td>
                <select name="AN_RECURRING" class="inputbox">
                <option <?php if (AN_RECURRING == 'YES') echo "selected=\"selected\""; ?> value="YES">
                <?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_YES') ?></option>
                <option <?php if (AN_RECURRING == 'NO') echo "selected=\"selected\""; ?> value="NO">
                <?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_NO') ?></option>
                </select>
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_PAYMENT_AN_RECURRING_TOOLTIP') ?>
            </td>
        </tr>
        <tr class="row0">
            <td class="labelcell"><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_AUTORIZENET_AUTENTICATIONTYPE') ?></td>
            <td>
               <select name="AN_TYPE" class="inputbox">
                <option <?php if (AN_TYPE == 'AUTH_CAPTURE') echo "selected=\"selected\""; ?> value="AUTH_CAPTURE">AUTH_CAPTURE</option>
                <option <?php if (AN_TYPE == 'AUTH_ONLY') echo "selected=\"selected\""; ?> value="AUTH_ONLY">AUTH_ONLY</option>
               </select>
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_AUTORIZENET_AUTENTICATIONTYPE_EXPLAIN') ?>
            </td>
        </tr>
        <tr><td colspan="3"><hr/></td></tr>
        <tr class="row1">
            <td class="labelcell"><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_PAYMENT_ORDERSTATUS_SUCC') ?></td>
            <td>
                <select name="AN_VERIFIED_STATUS" class="inputbox" >
                <?php
                $q = "SELECT order_status_name,order_status_code FROM #__{vm}_order_status ORDER BY list_order";
                $db->query($q);
                $order_status_code = Array();
                $order_status_name = Array();

                while ($db->next_record()) {
                	$order_status_code[] = $db->f("order_status_code");
                	$order_status_name[] =  $db->f("order_status_name");
                }
                for ($i = 0; $i < sizeof($order_status_code); $i++) {
                	echo "<option value=\"" . $order_status_code[$i];
                	if (AN_VERIFIED_STATUS == $order_status_code[$i])
                	echo "\" selected=\"selected\">";
                	else
                	echo "\">";
                	echo $order_status_name[$i] . "</option>\n";
                    }?>
                    </select>
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_PAYMENT_ORDERSTATUS_SUCC_EXPLAIN') ?></td>
        </tr>
            <tr class="row0">
            <td class="labelcell"><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_PAYMENT_ORDERSTATUS_FAIL') ?></td>
            <td>
                <select name="AN_INVALID_STATUS" class="inputbox" >
                <?php
                for ($i = 0; $i < sizeof($order_status_code); $i++) {
                	echo "<option value=\"" . $order_status_code[$i];
                	if (AN_INVALID_STATUS == $order_status_code[$i])
                	echo "\" selected=\"selected\">";
                	else
                	echo "\">";
                	echo $order_status_name[$i] . "</option>\n";
                    } ?>
                    </select>
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_PAYMENT_ORDERSTATUS_FAIL_EXPLAIN') ?></td>
        </tr>

            <tr class="row1">
            <td class="labelcell"><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_AUTORIZENET_RESPCODES') ?></td>
            <td>
                <select name="AN_SHOW_ERROR_CODE" class="inputbox">
                <option <?php if (AN_SHOW_ERROR_CODE == 'YES') echo "selected=\"selected\""; ?> value="YES">
                <?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_YES') ?></option>
                <option <?php if (AN_SHOW_ERROR_CODE == 'NO') echo "selected=\"selected\""; ?> value="NO">
                <?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_NO') ?></option>
                </select>
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_AUTORIZENET_RESPCODES_EXPLAIN') ?></td>
        </tr>
        <tr class="row0">
            <td class="labelcell"><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_AUTORIZENET_EMAIL_MERCHANT') ?></td>
            <td>
                <select name="AN_EMAIL_MERCHANT" class="inputbox">
                <option <?php if (AN_EMAIL_MERCHANT == 'YES') echo "selected=\"selected\""; ?> value="YES">
                <?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_YES') ?></option>
                <option <?php if (AN_EMAIL_MERCHANT == 'NO') echo "selected=\"selected\""; ?> value="NO">
                <?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_NO') ?></option>
                </select>
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_AUTORIZENET_EMAIL_MERCHANT_EXPLAIN') ?></td>
        </tr>
        <tr class="row1">
            <td class="labelcell"><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_AUTORIZENET_EMAIL_CUSTOMER') ?></td>
            <td>
                <select name="AN_EMAIL_CUSTOMER" class="inputbox">
                <option <?php if (AN_EMAIL_CUSTOMER == 'YES') echo "selected=\"selected\""; ?> value="YES">
                <?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_YES') ?></option>
                <option <?php if (AN_EMAIL_CUSTOMER == 'NO') echo "selected=\"selected\""; ?> value="NO">
                <?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_NO') ?></option>
                </select>
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_AUTORIZENET_EMAIL_CUSTOMER_EXPLAIN') ?></td>
        </tr>
      </table>
   <?php
   // return false if there's no configuration
   return true;
	}

	function has_configuration() {
		// return false if there's no configuration
		return true;
	}

	/**
	* Returns the "is_writeable" status of the configuration file
	* @param void
	* @returns boolean True when the configuration file is writeable, false when not
	*/
	function configfile_writeable() {
		return is_writeable( CLASSPATH."payment/".__CLASS__.".cfg.php" );
	}

	/**
	* Returns the "is_readable" status of the configuration file
	* @param void
	* @returns boolean True when the configuration file is writeable, false when not
	*/
	function configfile_readable() {
		return is_readable( CLASSPATH."payment/".__CLASS__.".cfg.php" );
	}
	/**
	* Writes the configuration file for this payment method
	* @param array An array of objects
	* @returns boolean True when writing was successful
	*/
	function write_configuration( &$d ) {

		$my_config_array = array("AN_TEST_REQUEST" => $d['AN_TEST_REQUEST'],
		"AN_LOGIN" => $d['AN_LOGIN'],
		"AN_HOSTNAME" => $d['AN_HOSTNAME'],
		"AN_TYPE" => $d['AN_TYPE'],
		"AN_CHECK_CARD_CODE" => $d['AN_CHECK_CARD_CODE'],
		"AN_VERIFIED_STATUS" => $d['AN_VERIFIED_STATUS'],
		"AN_INVALID_STATUS" => $d['AN_INVALID_STATUS'],
		"AN_RECURRING" => $d['AN_RECURRING'], 
		"AN_EMAIL_MERCHANT" => $d['AN_EMAIL_MERCHANT'],
		"AN_EMAIL_CUSTOMER" => $d['AN_EMAIL_CUSTOMER'],
		"AN_SHOW_ERROR_CODE" => $d['AN_SHOW_ERROR_CODE']
		);
		$config = "<?php\n";
		$config .= "if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); \n\n";
		foreach( $my_config_array as $key => $value ) {
			$config .= "define ('$key', '$value');\n";
		}

		$config .= "?>";

		if ($fp = fopen(CLASSPATH ."payment/".__CLASS__.".cfg.php", "w")) {
			fputs($fp, $config, strlen($config));
			fclose ($fp);
			return true;
		}
		else {
			return false;
		}
	}

	/**************************************************************************
	** name: process_payment()
	** created by: Soeren
	** description: process transaction with authorize.net
	** parameters: $order_number, the number of the order, we're processing here
	**            $order_total, the total $ of the order
	** returns:
	***************************************************************************/
	function process_payment($order_number, $order_total, &$d) {

		global $vendor_mail, $vendor_currency, $VM_LANG, $vmLogger;
		$database = new ps_DB;
		$ps_vendor_id = $_SESSION["ps_vendor_id"];
		$auth = $_SESSION['auth'];
		$ps_checkout = new ps_checkout;

		// Get the Configuration File for authorize.net
		require_once(CLASSPATH ."payment/".__CLASS__.".cfg.php");
		// connector class
		require_once(CLASSPATH ."connectionTools.class.php");

		// Get the Transaction Key securely from the database
		$database->query( "SELECT ".VM_DECRYPT_FUNCTION."(payment_passkey,'".ENCODE_KEY."') as passkey FROM #__{vm}_payment_method 
									WHERE payment_class='".__CLASS__."'  
										AND payment_enabled = 'Y'" );
		$transaction = $database->record[0];
		if( empty($transaction->passkey)) {
			$vmLogger->err( $VM_LANG->_('PHPSHOP_PAYMENT_ERROR',false).'. Technical Note: The required transaction key is empty! The payment method settings must be reviewed.' );
			return false;
		}

		// Get user billing information
		$dbbt = new ps_DB;

		$qt = "SELECT * FROM #__{vm}_user_info WHERE user_id=".$auth["user_id"]." AND address_type='BT'";

		$dbbt->query($qt);
		$dbbt->next_record();
		$user_info_id = $dbbt->f("user_info_id");
		if( $user_info_id != $d["ship_to_info_id"]) {
			// Get user billing information
			$dbst = new ps_DB;
			$qt = "SELECT * FROM #__{vm}_user_info WHERE user_info_id='".$d["ship_to_info_id"]."' AND address_type='ST'";
			$dbst->query($qt);
			$dbst->next_record();
		}
		else {
			$dbst = $dbbt;
		}

		// Option to send email to merchant from gateway
		if (AN_EMAIL_MERCHANT == 'NO') {
				$vendor_mail = "";
 		}
		if (AN_EMAIL_CUSTOMER == 'YES') {
			$email_customer = "TRUE";
		} else {
			$email_customer = "FALSE";
 		}
 			
		//Authnet vars to send
		$formdata = array (
		'x_version' => '3.1',
		'x_login' => AN_LOGIN,
		'x_tran_key' => $transaction->passkey,
		'x_test_request' => strtoupper( AN_TEST_REQUEST ),

		// Gateway Response Configuration
		'x_delim_data' => 'TRUE',
		'x_delim_char' => '|',
		'x_relay_response' => 'FALSE',

		// Customer Name and Billing Address
		'x_first_name' => substr($dbbt->f("first_name"), 0, 50),
		'x_last_name' => substr($dbbt->f("last_name"), 0, 50),
		'x_company' => substr($dbbt->f("company"), 0, 50),
		'x_address' => substr($dbbt->f("address_1"), 0, 60),
		'x_city' => substr($dbbt->f("city"), 0, 40),
		'x_state' => substr($dbbt->f("state"), 0, 40),
		'x_zip' => substr($dbbt->f("zip"), 0, 20),
		'x_country' => substr($dbbt->f("country"), 0, 60),
		'x_phone' => substr($dbbt->f("phone_1"), 0, 25),
		'x_fax' => substr($dbbt->f("fax"), 0, 25),

		// Customer Shipping Address
		'x_ship_to_first_name' => substr($dbst->f("first_name"), 0, 50),
		'x_ship_to_last_name' => substr($dbst->f("last_name"), 0, 50),
		'x_ship_to_company' => substr($dbst->f("company"), 0, 50),
		'x_ship_to_address' => substr($dbst->f("address_1"), 0, 60),
		'x_ship_to_city' => substr($dbst->f("city"), 0, 40),
		'x_ship_to_state' => substr($dbst->f("state"), 0, 40),
		'x_ship_to_zip' => substr($dbst->f("zip"), 0, 20),
		'x_ship_to_country' => substr($dbst->f("country"), 0, 60),

		// Additional Customer Data
		'x_cust_id' => $auth['user_id'],
		'x_customer_ip' => $_SERVER["REMOTE_ADDR"],
		'x_customer_tax_id' => $dbbt->f("tax_id"),

		// Email Settings
		'x_email' => $dbbt->f("user_email"),
		'x_email_customer' => $email_customer,
		'x_merchant_email' => $vendor_mail,

		// Invoice Information
		'x_invoice_num' => substr($order_number, 0, 20),
		'x_description' => $VM_LANG->_('PHPSHOP_ORDER_PRINT_PO_LBL'),

		// Transaction Data
		'x_amount' => $order_total,
		'x_currency_code' => $vendor_currency,
		'x_method' => 'CC',
		'x_type' => AN_TYPE,
		'x_recurring_billing' => AN_RECURRING,

		'x_card_num' => $_SESSION['ccdata']['order_payment_number'],
		'x_card_code' => $_SESSION['ccdata']['credit_card_code'],
		'x_exp_date' => ($_SESSION['ccdata']['order_payment_expire_month']) . ($_SESSION['ccdata']['order_payment_expire_year']),

		// Level 2 data
		'x_po_num' => substr($order_number, 0, 20),
		'x_tax' => substr($d['order_tax'], 0, 15),
		'x_tax_exempt' => "FALSE",
		'x_freight' => $d['order_shipping'],
		'x_duty' => 0

		);

		//build the post string
		$poststring = '';
		foreach($formdata AS $key => $val){
			$poststring .= urlencode($key) . "=" . urlencode($val) . "&";
		}
		// strip off trailing ampersand
		$poststring = substr($poststring, 0, -1);
		
		if (defined('AN_HOSTNAME')) {
   			$host = AN_HOSTNAME;
  		} else {
   			$host = 'secure.authorize.net';
  		}
				
		$result = vmConnector::handleCommunication( "https://$host:443/gateway/transact.dll", $poststring );
		
		if( !$result ) {
			$vmLogger->err('The transaction could not be completed.' );
			return false;
		}

		$c_mccomb = '|';
		$resultmm = '';
		$foundmm = 0;
		$iimm = 0;
		for($imm=0;$imm<strlen($result);$imm++) {
		        if (!$foundmm) {
		                if($result[$imm] == $c_mccomb) {
		                        $foundmm = 1;
		                        $resultmm .= $result[$imm - 1];
		                        $iimm++;
		                }
		        }
		        if ($foundmm) {
		                $resultmm .= $result[$imm];
		                $iimm++;
		        }
		}
		$response = explode("|", $resultmm);
		// Strip off quotes from the first response field
		$response[0] = str_replace( '"', '', $response[0] );
		
		$vmLogger->debug('Beginning to analyse the response from '.$host);

		// Approved - Success!
		if ($response[0] == '1') {
			$d["order_payment_log"] = $VM_LANG->_('PHPSHOP_PAYMENT_TRANSACTION_SUCCESS').": ";
			$d["order_payment_log"] .= $response[3];

			$vmLogger->debug( $d['order_payment_log']);

			// Catch Transaction ID
			$d["order_payment_trans_id"] = $response[6];

			return True;
		}
		// Payment Declined
		elseif ($response[0] == '2') {

			if (AN_SHOW_ERROR_CODE == 'YES') {
				$vmLogger->err( $response[0] . "-" . $response[1] . "-" . $response[2] . "-" .  $response[5] . "-" . $response[38] . "-" . $response[39] . "-" . $response[3] );
		   	} else {
           		$vmLogger->err( $response[3] );
			}

			$d["order_payment_log"] = $response[3];
			// Catch Transaction ID
			$d["order_payment_trans_id"] = $response[6];
			return False;
		}
		// Transaction Error
		elseif ($response[0] == '3') {

			if (AN_SHOW_ERROR_CODE == 'YES') {
				$vmLogger->err( $response[0] . "-" . $response[1] . "-" . $response[2] . "-" .  $response[5] . "-" . $response[38] . "-" . $response[39] . "-" . $response[3] );
		   	} 
		   	else {
           		$vmLogger->err( $response[3] );
			}

			$d["order_payment_log"] = $response[3];
			// Catch Transaction ID
			$d["order_payment_trans_id"] = $response[6];
			return False;

		} else if ($response[0] == '4') {
			$d["order_payment_log"] = $VM_LANG->_('PHPSHOP_PAYMENT_TRANSACTION_SUCCESS').": ";
			$d["order_payment_log"] .= $response[3];
			$vmLogger->debug( $d['order_payment_log']);
			// Catch Transaction ID
			$d["order_payment_trans_id"] = $response[6];
			return True;
		}
	}

	/**************************************************************************
	** name: capture_payment()
	** created by: Soeren
	** description: Process a previous transaction with authorize.net, Capture the Payment
	** parameters: $order_number, the number of the order, we're processing here
	** returns:
	***************************************************************************/
	function capture_payment( &$d ) {

		global $vendor_mail, $vendor_currency, $VM_LANG, $vmLogger;
		$database = new ps_DB();

		require_once(CLASSPATH ."connectionTools.class.php");
		
		/*CERTIFICATION
		Visa Test Account           4007000000027
		Amex Test Account           370000000000002
		Master Card Test Account    6011000000000012
		Discover Test Account       5424000000000015

		$host = "certification.authorize.net";
		$port = 443;
		$path = "/gateway/transact.dll";
		*/
		if( empty($d['order_number'])) {
			$vmLogger->err("Error: No Order Number provided.");
			return false;
		}
		/*** Get the Configuration File for authorize.net ***/
		require_once(CLASSPATH ."payment/".__CLASS__.".cfg.php");

		// Get the Transaction Key securely from the database
		$database->query( "SELECT ".VM_DECRYPT_FUNCTION."(payment_passkey,'".ENCODE_KEY."') as passkey FROM #__{vm}_payment_method 
									WHERE payment_class='".__CLASS__."' AND payment_enabled = 'Y'" );
		$transaction = $database->record[0];
		if( empty($transaction->passkey)) {
			$vmLogger->err($VM_LANG->_('PHPSHOP_PAYMENT_ERROR'),false);
			return false;
		}
		$db = new ps_DB;
		$q = "SELECT * FROM #__{vm}_orders, #__{vm}_order_payment WHERE ";
		$q .= "order_number='".$d['order_number']."' ";
		$q .= "AND #__{vm}_orders.order_id=#__{vm}_order_payment.order_id";
		$db->query( $q );
		if( !$db->next_record() ) {
			$vmLogger->err("Error: Order not found.");
			return false;
		}
		$expire_date = date( "my", $db->f("order_payment_expire") );

		// DECODE Account Number
		$dbaccount = new ps_DB;
		$q = "SELECT ".VM_DECRYPT_FUNCTION."(order_payment_number,'".ENCODE_KEY."')
          AS account_number from #__{vm}_order_payment WHERE order_id='".$db->f("order_id")."'";
		$dbaccount->query($q);
		$dbaccount->next_record();

		// Get user billing information
		$dbbt = new ps_DB;
		$qt = "SELECT * FROM #__{vm}_user_info WHERE user_id='".$db->f("user_id")."'";
		$dbbt->query($qt);
		$dbbt->next_record();
		$user_info_id = $dbbt->f("user_info_id");
		if( $user_info_id != $db->f("user_info_id")) {
			// Get user's alternative shipping information
			$dbst = new ps_DB;
			$qt = "SELECT * FROM #__{vm}_user_info WHERE user_info_id='".$db->f("user_info_id")."' AND address_type='ST'";
			$dbst->query($qt);
			$dbst->next_record();
		}
		else {
			$dbst = $dbbt;
		}

		//Authnet vars to send
		$formdata = array (
		'x_version' => '3.1',
		'x_login' => AN_LOGIN,
		'x_tran_key' => $transaction->passkey,
		'x_test_request' => strtoupper( AN_TEST_REQUEST ),

		// Gateway Response Configuration
		'x_delim_data' => 'TRUE',
		'x_delim_char' => '|',
		'x_relay_response' => 'FALSE',

		// Customer Name and Billing Address
		'x_first_name' => substr($dbbt->f("first_name"), 0, 50),
		'x_last_name' => substr($dbbt->f("last_name"), 0, 50),
		'x_company' => substr($dbbt->f("company"), 0, 50),
		'x_address' => substr($dbbt->f("address_1"), 0, 60),
		'x_city' => substr($dbbt->f("city"), 0, 40),
		'x_state' => substr($dbbt->f("state"), 0, 40),
		'x_zip' => substr($dbbt->f("zip"), 0, 20),
		'x_country' => substr($dbbt->f("country"), 0, 60),
		'x_phone' => substr($dbbt->f("phone_1"), 0, 25),
		'x_fax' => substr($dbbt->f("fax"), 0, 25),

		// Customer Shipping Address
		'x_ship_to_first_name' => substr($dbst->f("first_name"), 0, 50),
		'x_ship_to_last_name' => substr($dbst->f("last_name"), 0, 50),
		'x_ship_to_company' => substr($dbst->f("company"), 0, 50),
		'x_ship_to_address' => substr($dbst->f("address_1"), 0, 60),
		'x_ship_to_city' => substr($dbst->f("city"), 0, 40),
		'x_ship_to_state' => substr($dbst->f("state"), 0, 40),
		'x_ship_to_zip' => substr($dbst->f("zip"), 0, 20),
		'x_ship_to_country' => substr($dbst->f("country"), 0, 60),

		// Additional Customer Data
		'x_cust_id' => $db->f('user_id'),
		'x_customer_ip' => $dbbt->f("ip_address"),
		'x_customer_tax_id' => $dbbt->f("tax_id"),

		// Email Settings
		'x_email' => $dbbt->f("email"),
		'x_email_customer' => 'False',
		'x_merchant_email' => $vendor_mail,

		// Invoice Information
		'x_invoice_num' => substr($d['order_number'], 0, 20),
		'x_description' => '',

		// Transaction Data
		'x_amount' => $db->f("order_total"),
		'x_currency_code' => $vendor_currency,
		'x_method' => 'CC',
		'x_type' => 'PRIOR_AUTH_CAPTURE',
		'x_recurring_billing' => AN_RECURRING,

		'x_card_num' => $dbaccount->f("account_number"),
		'x_card_code' => $db->f('order_payment_code'),
		'x_exp_date' => $expire_date,
		'x_trans_id' => $db->f("order_payment_trans_id"),

		// Level 2 data
		'x_po_num' => substr($d['order_number'], 0, 20),
		'x_tax' => substr($db->f('order_tax'), 0, 15),
		'x_tax_exempt' => "FALSE",
		'x_freight' => $db->f('order_shipping'),
		'x_duty' => 0

		);

		//build the post string
		$poststring = '';
		foreach($formdata AS $key => $val){
			$poststring .= urlencode($key) . "=" . urlencode($val) . "&";
		}
		// strip off trailing ampersand
		$poststring = substr($poststring, 0, -1);
		
		if (defined('AN_HOSTNAME')) {
   			$host = AN_HOSTNAME;
  		} else {
   			$host = 'secure.authorize.net';
  		}
		
		$result = vmConnector::handleCommunication( "https://$host:443/gateway/transact.dll", $poststring );
		
		if( !$result ) {
			$vmLogger->err('We\'re sorry, but an error has occured when we tried to communicate with the authorize.net server. Please try again later, thank you.' );
			return false;
		}
		
		$response = explode("|", $result);

		// Approved - Success!
		if ($response[0] == '1') {
			$d["order_payment_log"] = $VM_LANG->_('PHPSHOP_PAYMENT_TRANSACTION_SUCCESS').": ";
			$d["order_payment_log"] .= $response[3];
			// Catch Transaction ID
			$d["order_payment_trans_id"] = $response[6];

			$q = "UPDATE #__{vm}_order_payment SET ";
			$q .="order_payment_log='".$d["order_payment_log"]."',";
			$q .="order_payment_trans_id='".$d["order_payment_trans_id"]."' ";
			$q .="WHERE order_id='".$db->f("order_id")."' ";
			$db->query( $q );

			return True;
		}
		// Payment Declined
		elseif ($response[0] == '2') {
			$vmLogger->err($response[3]);
			$d["order_payment_log"] = $response[3];
			// Catch Transaction ID
			$d["order_payment_trans_id"] = $response[6];
			return False;
		}
		// Transaction Error
		elseif ($response[0] == '3') {
			$vmLogger->err($response[3]);
			$d["order_payment_log"] = $response[3];
			// Catch Transaction ID
			$d["order_payment_trans_id"] = $response[6];
			return False;
		}
	}

}
