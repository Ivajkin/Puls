<?php
	/**
	 *Procedural connectivity to the MW
	 * Environment using CURL and PHP5.
	 * 
	 * The card data will work with CBA, ANZ or NAB
	 * test accounts.
	 * 
	 * Merchant Administration URL: https://camp.merchantwarrior.com
	 * 
	 * Copyright by the author
	 * @author Max Milbers
	 */

class ps_merchantWarrior{
	
	var $classname = "ps_merchantwarrior";
	var $payment_code = "MW";
	/**
	 * Shows all configuration parameters for this payment method, which is saved then in a config file
	 * @author Max Milbers
	 */
	function show_configuration() {
		global $VM_LANG, $sess;

$langvars = array (
	'PHPSHOP_ADMIN_CFG_ENABLE_MW_TESTMODE' => 'Testmodus:',
	'PHPSHOP_ADMIN_CFG_ENABLE_MW_TESTMODE_EXPLAIN' => 'Just for testing',
	'PHPSHOP_ADMIN_CFG_MW_MERCHANT_UUID' => 'Merchant UUID',
	'PHPSHOP_ADMIN_CFG_MW_MERCHANT_UUID_EXPLAIN' => 'Your unique universal ID you got from merchant warriors',
	'PHPSHOP_ADMIN_CFG_MW_API_KEY' => 'The api key to use MerchantWarrior',
	'PHPSHOP_ADMIN_CFG_MW_API_KEY_EXPLAIN' => '',	
	'PHPSHOP_ADMIN_CFG_MW_PASS_PHRASE_SETCHANGE' => 'Click here to set the passphrase',
	'PHPSHOP_ADMIN_CFG_MW_PASS_PHRASE' => 'Password', 
	'PHPSHOP_ADMIN_CFG_MW_PASS_PHRASE_EXPLAIN' => 'Password'
); $VM_LANG->initModule( 'payment', $langvars );
		
		$db = new ps_DB;
		$payment_method_id = vmGet( $_REQUEST, 'payment_method_id', null );
		/** Read current Configuration ***/
		require_once(CLASSPATH ."payment/".$this->classname.".cfg.php");
		ps_merchantWarrior::setdefault();
		
    ?>
      <table>
        <tr>
            <td><strong><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_ENABLE_MW_TESTMODE') ?></strong></td>
            <td>
                <select name="MW_TEST_REQUEST" class="inputbox" >
                <option <?php if (MW_TEST_REQUEST == 'TRUE') echo "selected=\"selected\""; ?> value="TRUE"><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_YES') ?></option>
                <option <?php if (MW_TEST_REQUEST == 'FALSE') echo "selected=\"selected\""; ?> value="FALSE"><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_NO') ?></option>
                </select>
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_ENABLE_MW_TESTMODE_EXPLAIN') ?></td>
        </tr>
        <tr>
            <td><strong><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_MW_MERCHANT_UUID') ?></strong></td>
            <td>
                <input type="text" name="MW_MERCHANT_UUID" class="inputbox" value="<?php echo MW_MERCHANT_UUID ?>" />
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_MW_MERCHANT_UUID_EXPLAIN') ?></td>
        </tr>
      <tr>
            <td><strong><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_MW_API_KEY') ?></strong></td>
            <td>
                <input type="text" name="MW_API_KEY" class="inputbox" value="<?php echo MW_API_KEY ?>" />
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_MW_API_KEY_EXPLAIN') ?></td>
        </tr>
      <tr>
		<tr>
            <td><strong><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_MW_PASS_PHRASE') ?></strong></td>
            <td>
                <a id="changekey" href="<?php $sess->purl($_SERVER['PHP_SELF']."?page=store.payment_method_keychange&pshop_mode=admin&payment_method_id=$payment_method_id") ?>" >
                <input onclick="document.location=document.getElementById('changekey').href" type="button" name="MW_API_PASS_PHRASE" value="<?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_MW_PASS_PHRASE_SETCHANGE') ?>" class="button" /><a/>
            </td>
            <td>&nbsp;</td>
        </tr>
		<tr>
            <td><strong><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_PAYMENT_ORDERSTATUS_SUCC') ?></strong></td>
            <td>
                <select name="MW_VERIFIED_STATUS" class="inputbox" >
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
                	if (MW_VERIFIED_STATUS == $order_status_code[$i])
                	echo "\" selected=\"selected\">";
                	else
                	echo "\">";
                	echo $order_status_name[$i] . "</option>\n";
                    }?>
                    </select>
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_PAYMENT_ORDERSTATUS_SUCC_EXPLAIN') ?></td>
        </tr>
            <tr>
            <td><strong><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_PAYMENT_ORDERSTATUS_FAIL') ?></strong></td>
            <td>
                <select name="MW_INVALID_STATUS" class="inputbox" >
                <?php
                for ($i = 0; $i < sizeof($order_status_code); $i++) {
                	echo "<option value=\"" . $order_status_code[$i];
                	if (MW_INVALID_STATUS == $order_status_code[$i])
                	echo "\" selected=\"selected\">";
                	else
                	echo "\">";
                	echo $order_status_name[$i] . "</option>\n";
                    } ?>
                    </select>
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_PAYMENT_ORDERSTATUS_FAIL_EXPLAIN') ?></td>
        </tr> 
      </table> <?php 

   		return true;
	}
	
	function setdefault(){
		
		if(!defined('MW_TEST_REQUEST')) define ('MW_TEST_REQUEST', 'TRUE');
		if(!defined('MW_MERCHANT_UUID')) define ('MW_MERCHANT_UUID', '');
		if(!defined('MW_API_KEY')) define ('MW_API_KEY', '');
		if(!defined('MW_VERIFIED_STATUS')) define ('MW_VERIFIED_STATUS', 'C');
		if(!defined('MW_INVALID_STATUS')) define ('MW_INVALID_STATUS', 'X');
		
		//This later used for refund and for token payment
//		$db = new ps_DB;
//		$db->query( "CREATE TABLE IF NOT EXISTS `#__{vm}_order_mw_data` (
//		  `mw_id` int(11) NOT NULL auto_increment,
//		  `order_id` varchar(11) NOT NULL default '0',
//		  `card_id` varchar(11) NOT NULL default '0',
//		  `card_key` blob NOT NULL default '',
//		  `transaction_id` blob NOT NULL default '',
//		  PRIMARY KEY  (`mw_id`)
//		) TYPE=MyISAM COMMENT='Holds the history of MerchantWarrior processed payment';" );

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
	
		$my_config_array = array("MW_TEST_REQUEST" => $d['MW_TEST_REQUEST'],
		"MW_MERCHANT_UUID" => $d['MW_MERCHANT_UUID'],
		"MW_API_KEY" => $d['MW_API_KEY'],
		"MW_VERIFIED_STATUS" => $d['MW_VERIFIED_STATUS'],
		"MW_INVALID_STATUS" => $d['MW_INVALID_STATUS']
		);
		$config = "<?php\n";
		$config .= "if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); \n\n";
		foreach( $my_config_array as $key => $value ) {
			$config .= "define ('$key', '$value');\n";
		}

		$config .= "?>";

		if ($fp = fopen(CLASSPATH ."payment/".$this->classname.".cfg.php", "w")) {
			fputs($fp, $config, strlen($config));
			fclose ($fp);
			return true;
		}
		else {
			return false;
		}
		
   }
   
	 /**
     * Parses the response. Gives back false or true
     * 
     * @author Max Milbers
     */
	function process_payment($order_number, $order_total, &$d) {

	global $vendor_mail, $vendor_currency, $VM_LANG, $vmLogger;
	$database = new ps_DB;
	$ps_vendor_id = $_SESSION["ps_vendor_id"];
	$auth = $_SESSION['auth'];
			
	// From payflow
	require_once(CLASSPATH ."payment/".$this->classname.".cfg.php");
	// connector class
	require_once(CLASSPATH ."connectionTools.class.php");
	
		// Get the Password securely from the database
		$query = "SELECT ".VM_DECRYPT_FUNCTION."(payment_passkey,'".ENCODE_KEY."') as passkey FROM #__{vm}_payment_method WHERE payment_class='".$this->classname."' AND shopper_group_id='".$auth['shopper_group_id']."'";
		$database->query( $query );
		$transaction = $database->record[0];

		if( empty($transaction->passkey)) {
			$vmLogger->err( $VM_LANG->_('PHPSHOP_PAYMENT_ERROR',false).'. Technical Note: The required password is empty! The payment method settings must be reviewed.' );
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
		

		$tempstr = $_SESSION['ccdata']['order_payment_number'] . $order_total . date('YmdGis');
		$request_id = md5($tempstr);		
	
		// Setup POST data
		$postData['method'] = 'processCard'; 
		$postData['merchantUUID'] = MW_MERCHANT_UUID; 
		$postData['apiKey'] = MW_API_KEY;
//		$postData['transactionAmount'] = round($order_total,2); 
		$postData['transactionAmount'] = '77.00'; //for testing purposes
		$postData['transactionCurrency'] = $vendor_currency; 
		$postData['transactionProduct'] = $request_id; //The requestID should do the job
		$postData['customerName'] = $dbbt->f("first_name") .' '.$dbbt->f("last_name"); 
		$postData['customerCountry'] = $dbbt->f("country"); 
		$postData['customerState'] = $dbbt->f("state")=='-' ? 'noState' : $dbbt->f("state");
		$postData['customerCity'] = $dbbt->f("city"); 
		$postData['customerAddress'] = $dbbt->f("address_1"); 
		$postData['customerPostCode'] = $dbbt->f("zip"); 
		$postData['customerPhone'] = $dbbt->f("phone_1"); 
		$postData['customerEmail'] = $dbbt->f("user_email"); 
		$postData['customerIP'] = getenv('REMOTE_ADDR'); //attention spoofing cant be detected
		$postData['paymentCardNumber'] = $_SESSION['ccdata']['order_payment_number'];  //strange, but true, the order_payment_number is the number of the creditcard
		$postData['paymentCardName'] = $_SESSION['ccdata']['order_payment_name'];
		$postData['paymentCardExpiry'] = ($_SESSION['ccdata']['order_payment_expire_month']) . substr($_SESSION['ccdata']['order_payment_expire_year'], 2 );
		$postData['hash'] = ps_merchantwarrior::calculateHash($postData,$transaction->passkey);

		//build the post string
		$poststring = '';
		foreach($postData AS $key => $val){
			$poststring .= urlencode($key) . "=" . urlencode($val) . "&";
		}
		// strip off trailing ampersand
		$poststring = substr($poststring, 0, -1);
		
		if(MW_TEST_REQUEST=='FALSE') {
			//live
			$host = 'api.merchantwarrior.com';
		} else  {
			//test
			$host = 'base.merchantwarrior.com';
		}
		
		//Maybe unnecessary
		$headers[] = "X-VPS-Timeout: 60";
		$headers[] = "X-VPS-VIT-OS-Name: ".PHP_OS;  // Name of your OS
		$headers[] = "X-VPS-VIT-OS-Version: ".PHP_OS;  // OS Version
		$headers[] = "X-VPS-VIT-Client-Type: PHP/cURL";  // What you are using
		$headers[] = "X-VPS-VIT-Client-Version: 0.01";  // For your info
	
		$responseData = vmConnector::handleCommunication( "https://$host:443/post/", $poststring, $headers );
		
		if( !$responseData ) {
			$vmLogger->err('Unable to connect with server. The transaction could not be completed.' );
			return false;
		}
				
		// Parse the XML and create a SimpleXMLObject+
	    $result = simplexml_load_string($responseData);
	    // Convert the result from a SimpleXMLObject into an array+
	    $result = (array)$result;

		$parsed=ps_merchantwarrior::parseResponse($result);	
		$d['order_payment_trans_id'] = $parsed['transactionID'];
		
		ps_merchantwarrior::loadLang();
		if(!$parsed['responseCode']){
		 	$vmLogger->info($VM_LANG->_('PHPSHOP_MW_0'));
			
			//This is needed for refunding and token payment, but the save function must be somewhere different
//		 	$database->query( 'INSERT INTO `#__{vm}_order_mw_data` (`order_id`, `transaction_id`) VALUES ("'.intval( vmGet( $_REQUEST, "order_id" )).'","'.$parsed['transactionID'].'");' );
		 	return true;
		 }else {
		 	$errorCode = substr($parsed['responseMessage'],5,3);
		 	$vmLogger->err($VM_LANG->_('PHPSHOP_MW_'.$parsed['responseCode']). ' '.$VM_LANG->_('PHPSHOP_MW_ERROR_'.$errorCode));
			return false;
		 }

	}
    
    /**
     * Parses the response. Gives back false or true
     * 
     * @author Max Milbers
     */
    function parseResponse($result){

		// Extract the transactionID or set the variable to null if one 
    	// wasn't returned+
		$parsed['transactionID'] = (isset($result['transactionID']) ? $result['transactionID'] : 0);
		
		$parsed['responseCode'] = (isset($result['responseCode']) ? $result['responseCode'] : -5);
		$parsed['responseMessage'] = (isset($result['responseMessage']) ? $result['responseMessage'] : 0);
		
		$parsed['authCode'] = (isset($result['authCode']) ? $result['authCode'] : 0);
		$parsed['authMessage'] = (isset($result['authMessage']) ? $result['authMessage'] : 0);
		$parsed['authResponseCode'] = (isset($result['authResponseCode']) ? $result['authResponseCode'] : 0);
		$parsed['authSettledDate'] = (isset($result['authsettleddate']) ? $result['authsettleddate'] : 0);
		
		$parsed['cardID'] = (isset($result['cardID']) ? $result['cardID'] : 0);
		$parsed['cardName'] = (isset($result['cardName']) ? $result['cardName'] : 0);
		$parsed['cardKey'] = (isset($result['cardKey']) ? $result['cardKey'] : 0);
		
		$parsed['cardExpiryMonth'] = (isset($result['cardExpiryMonth']) ? $result['cardExpiryMonth'] : 0);
		$parsed['cardExpiryYear'] = (isset($result['cardExpiryYear']) ? $result['cardExpiryYear'] : 0);

		$parsed['cardNumberFirst'] = (isset($result['cardNumberFirst']) ? $result['cardNumberFirst'] : 0);
		$parsed['cardNumberLast'] = (isset($result['cardNumberLast']) ? $result['cardNumberLast'] : 0);
		$parsed['cardAdded'] = (isset($result['cardAdded']) ? $result['cardAdded'] : 0);

		return $parsed;
    }


	/**
	 * Generates and returns the request hash after being
	 * provided with the postData array.
	 *
	 * @param array $postData
	 */
	function calculateHash(array $postData = array(),$passkey)
	{
		// Check the amount param
		if (!isset($postData['transactionAmount']) || !strlen($postData['transactionAmount']))
		{
			exit('Missing or blank amount field in postData array.');
		}
		
		// Check the currency param
		if (!isset($postData['transactionCurrency']) || !strlen($postData['transactionCurrency']))
		{
			exit('Missing or blank currency field in postData array.');
		}
		$concat = strtolower($passkey. $postData['merchantUUID']. $postData['transactionAmount'] . $postData['transactionCurrency']);

		// Generate & return the hash
		return md5($concat);
	}
	
	/**
     * Does the refunding
     * 
     * This methods needs that every transaction id is saved. Atm this does not happen, so this function does not work atm.
     * 
     * @author Max Milbers
     */
	function do_refund(&$d){
		
		global $vendor_currency,$vmLogger,$VM_LANG;
		
		if( empty($d['order_number'])) {
			$vmLogger->err("Error: No Order Number provided.");
			return false;
		}
		
		// include the configuration file
		require_once(CLASSPATH ."payment/".__CLASS__.".cfg.php");

		$db = new ps_DB;
		$q = "SELECT #__{vm}_orders.order_id,order_number,order_payment_trans_id,order_total FROM #__{vm}_orders, #__{vm}_order_payment WHERE ";
		$q .= "order_number='".$d['order_number']."' ";
		$q .= "AND #__{vm}_orders.order_id=#__{vm}_order_payment.order_id";
		$db->query( $q );
		if( !$db->next_record() || ! $db->f('order_payment_trans_id') ) {
			$vmLogger->err("Error: Order or TransactionID not found.");
			return false;
		}
		
		$postData['method'] = 'refundCard'; 
		$postData['merchantUUID'] = MW_MERCHANT_UUID; 
		$postData['apiKey'] = MW_API_KEY;
		$postData['transactionAmount'] = $db->f('order_total');
		$postData['transactionCurrency'] = $vendor_currency; 
		$postData['transactionID'] = $db->f('order_payment_trans_id');
		$postData['refundAmount'] = $db->f('order_total');
		$postData['hash'] = ps_merchantwarrior::calculateHash($postData,$transaction->passkey);
		
		$poststring = '';
		foreach($postData AS $key => $val){
			$poststring .= urlencode($key) . "=" . urlencode($val) . "&";
		}
		
		if(MW_TEST_REQUEST=='FALSE') {
			//live
			$host = 'api.merchantwarrior.com';
		} else  {
			//test
			$host = 'base.merchantwarrior.com';
		}
		
		//Maybe unnecessary
		$headers[] = "X-VPS-Timeout: 60";
		$headers[] = "X-VPS-VIT-OS-Name: ".PHP_OS;  // Name of your OS
		$headers[] = "X-VPS-VIT-OS-Version: ".PHP_OS;  // OS Version
		$headers[] = "X-VPS-VIT-Client-Type: PHP/cURL";  // What you are using
		$headers[] = "X-VPS-VIT-Client-Version: 0.01";  // For your info
	
		$responseData = vmConnector::handleCommunication( "https://$host:443/post/", $poststring, $headers );
		
		if( !$responseData ) {
			$vmLogger->err('Unable to connect with server. The transaction could not be completed.' );
			return false;
		}
				
		// Parse the XML and create a SimpleXMLObject+
	    $result = simplexml_load_string($responseData);
	    // Convert the result from a SimpleXMLObject into an array+
	    $result = (array)$result;
		
		$parsed=ps_merchantwarrior::parseResponse($result);
		
		ps_merchantwarrior::loadLang();
		if(!$parsed['responseCode']){
		 	$vmLogger->info($VM_LANG->_('PHPSHOP_MW_0'));
		 	return true;
		 }else {
		 	$errorCode = substr($parsed['responseMessage'],5,3);
		 	$vmLogger->err($VM_LANG->_('PHPSHOP_MW_'.$parsed['responseCode']). ' '.$VM_LANG->_('PHPSHOP_MW_ERROR_'.$errorCode));
			return false;
		 }
		 
	}
	
//	function capture_payment(){
//		
//	}
//	
//	function void_authorization(){
//		
//	}

	function loadLang(){
		global $VM_LANG;
		$langvars = array (
    		'PHPSHOP_MW_-5' => 'Internal MWE or Virtuemart error (API Response was invalid).',
			'PHPSHOP_MW_-4' => 'Internal MWE error (contact MWE support).',
			'PHPSHOP_MW_-3' => 'One of the required fields was not submitted.',
			'PHPSHOP_MW_-2' => 'One of the submitted fields was invalid.',
			'PHPSHOP_MW_-1' => 'Invalid authentication credentials supplied.',
			'PHPSHOP_MW_0' => 'Transaction Approved.',
			'PHPSHOP_MW_1' => 'Transaction could not be processed (server error).',	
			'PHPSHOP_MW_2' => 'Transaction declined – contact issuing bank. Check entered date and number of creditcard',
			'PHPSHOP_MW_3' => 'No reply from processing host (timeout).', 
			'PHPSHOP_MW_4' => 'Card has expired.',
			'PHPSHOP_MW_5' => 'Insufficient Funds.',
			'PHPSHOP_MW_6' => 'Error communicating with bank.',
			'PHPSHOP_MW_7' => 'Bank rejected request.',
			'PHPSHOP_MW_8' => 'Bank declined transaction – type not supported.',
			'PHPSHOP_MW_9' => 'Bank declined transaction – do not contact bank.',
			'PHPSHOP_MW_ERROR_001' => 'Required field missing.',
			'PHPSHOP_MW_ERROR_002' => 'Invalid amount.',
			'PHPSHOP_MW_ERROR_003' => 'Invalid currency.',
			'PHPSHOP_MW_ERROR_004' => 'Invalid email.',
			'PHPSHOP_MW_ERROR_005' => 'Invalid name.',
			'PHPSHOP_MW_ERROR_006' => 'Invalid expiry.',
			'PHPSHOP_MW_ERROR_007' => 'Invalid card number.',
			'PHPSHOP_MW_ERROR_008' => 'Invalid auth details.',
			'PHPSHOP_MW_ERROR_009' => 'Invalid merchantUUID.',
			'PHPSHOP_MW_ERROR_010' => 'Invalid passphrase.',
			'PHPSHOP_MW_ERROR_011' => 'Invalid transactionID.',
			'PHPSHOP_MW_ERROR_012' => 'Invalid transaction.',
			'PHPSHOP_MW_ERROR_013' => 'Currency mismatch.',
			'PHPSHOP_MW_ERROR_014' => 'Invalid refund amount.',
			'PHPSHOP_MW_ERROR_015' => 'Refund exceeds transaction amount.',
			'PHPSHOP_MW_ERROR_016' => 'Transaction already reversed.',
			'PHPSHOP_MW_ERROR_017' => 'Invalid verification hash.'
		); $VM_LANG->initModule( 'payment', $langvars );
	}
	
}
	
