<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: ps_paypal_api.php 2011-02-19 11:35:16Z zanardi $
* @package VirtueMart
* @subpackage payment
* @copyright Copyright (C) 2011 the Virtuemart team - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/
// Change to whatever API version this module was adapted to
define( 'PAYPAL_API_VERSION', '60.0' );
/**
* This class implements the configuration panel for paypal
* If you want to change something "internal", you must modify the 'payment extra info'
* in the payment method form of the PayPal payment method
*/

class ps_paypal_api {
    var $payment_code = "PP_API";
    
    /**
    * Show all configuration parameters for this payment method
    * @returns boolean False when the Payment method has no configration
    */
    function show_configuration() {
        global $VM_LANG, $vendor_image_url;
        $db = new ps_DB();
        
        // Read current Configuration
        include_once(CLASSPATH ."payment/".__CLASS__.".cfg.php");
		$lang = jfactory::getLanguage();
		$name= $lang->getBackwardLang();
		if( file_exists(CLASSPATH ."payment/paypal_api/languages/lang.".$name.".admin.php")) {
			include_once(CLASSPATH ."payment/paypal_api/languages/lang.".$name.".admin.php");
		} else {
			include_once(CLASSPATH ."payment/paypal_api/languages/lang.english.admin.php");
		}
    ?><a href="<?php echo PAYPAL_API_PAYPAL_LOGOCENTERURL ?>" target="_blank" title="Visit the Logo Center">
		<img align="left" style="margin-left: 220px;" src="<?php echo PAYPAL_API_PAYPAL_LOGOSRC ?>" alt="PayPal Logo" border="0" /><?php
		echo PAYPAL_API_PAYPAL_LOGOCENTER ?>
		</a>
    <table class="adminform">
        <tr class="row1">
        <td><strong><?php echo  PAYPAL_API_TEXT_USERNAME ?></strong></td>
            <td>
                <input type="text" name="PAYPAL_API_API_USERNAME" class="inputbox" size="50" value="<?php  echo PAYPAL_API_API_USERNAME ?>" />
            </td>
            <td><?php echo $VM_LANG->_(PAYPAL_API_TEXT_USERNAME_EXPLAIN) ?>
            </td>
        </tr>
        <tr class="row0">
        <td><strong><?php echo  PAYPAL_API_TEXT_PASSWORD ?></strong></td>
            <td>
                <input type="text" name="PAYPAL_API_API_PASSWORD" class="inputbox" size="50" value="<?php  echo PAYPAL_API_API_PASSWORD ?>" />
            </td>
            <td><?php echo vmtooltip(PAYPAL_API_TEXT_PASSWORD_EXPLAIN) ?>
            </td>
        </tr>
        <tr class="row1">
        <td><strong><?php echo PAYPAL_API_TEXT_SIGNATURE  ?></strong></td>
            <td>
                <input type="text" name="PAYPAL_API_API_SIGNATURE" class="inputbox" size="50" value="<?php  echo PAYPAL_API_API_SIGNATURE ?>" />
            </td>
            <td><?php echo vmtooltip(PAYPAL_API_TEXT_SIGNATURE_EXPLAIN) ?>
            </td>
        </tr>
		<tr class="row0">
        <td><strong><?php echo PAYPAL_API_TEXT_ENABLE_SANDBOX ?></strong></td>
            <td>
				<?php
					$options = array( '1' => PAYPAL_API_TEXT_YES, 
									'0' =>PAYPAL_API_TEXT_NO );
				ps_html::dropdown_display( 'PAYPAL_API_DEBUG', PAYPAL_API_DEBUG, $options ); 
				?>
            </td>
            <td><?php echo vmtooltip(PAYPAL_API_TEXT_ENABLE_SANDBOX_EXPLAIN) ?>
            </td>
        </tr>
		<tr class="row1"><td><strong><?php echo PAYPAL_API_DEBUG_TEXT; ?></strong></td>
            <td><select name="PP_WPP_ERRORS" class="inputbox" >
                	<option <?php if (@PAYPAL_API_DEBUG == '1') echo "selected=\"selected\""; ?> value="1">
					<?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_YES'); ?></option>
                	<option <?php if (@PAYPAL_API_DEBUG != '1') echo "selected=\"selected\""; ?> value="0">
					<?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_NO'); ?></option>
                </select>
            </td>
            <td><?php echo PAYPAL_API_DEBUG_TEXT_EXPLAIN; ?></td>
        </tr>
        <tr class="row0">
        <td><strong><?php echo PAYPAL_API_TEXT_IMAGE_URL ?></strong></td>
            <td>
                <input type="text" name="PAYPAL_API_IMAGEURL" class="inputbox" size="100" value="<?php  echo constant('PAYPAL_API_IMAGEURL') ? constant('PAYPAL_API_IMAGEURL') : $vendor_image_url; ?>" />
            </td>
            <td><?php echo vmtooltip(PAYPAL_API_TEXT_IMAGE_URL_EXPLAIN) ?>
            </td>
        </tr>
        <tr class="row1">
        <td><strong><?php echo PAYPAL_API_TEXT_PAYMENTTYPE ?></strong></td>
            <td>
				<select name="PAYPAL_API_PAYMENTTYPE" class="inputbox" >
	                <option <?php if (@PAYPAL_API_PAYMENTTYPE == 'Sale') echo "selected=\"selected\""; ?> value="Sale"><?php echo PAYPAL_API_TEXT_PAYMENTTYPE_SALE; ?></option>
	                <option <?php if (@PAYPAL_API_PAYMENTTYPE == 'Authorization') echo "selected=\"selected\""; ?> value="Authorization"><?php echo PAYPAL_API_TEXT_PAYMENTTYPE_AUTHORIZATION; ?></option>
                </select>
            </td>
            <td><?php echo vmtooltip(PAYPAL_API_TEXT_PAYMENTTYPE_EXPLAIN) ?>
            </td>
        </tr>
        <!--<tr class="row1">
        <td><strong><?php echo PAYPAL_API_TEXT_SET_CERTIFICATE ?></strong></td>
            <td>
				<input type="text" name="PAYPAL_API_CERTIFICATE" class="inputbox" size="100" value="<?php  echo @constant('PAYPAL_API_CERTIFICATE'); ?>" />
            </td>
            <td><?php echo vmtooltip(PAYPAL_API_TEXT_SET_CERTIFICATE_EXPLAIN ) ?>
            </td>
        </tr>-->
        <tr class="row0">
        <td><strong><?php echo PAYPAL_API_CVV_TEXT ?></strong></td>
            <td>
				<?php
				$options = array( 'YES' => PAYPAL_API_TEXT_YES, 
									'NO' => PAYPAL_API_TEXT_NO );
				ps_html::dropdown_display( 'PAYPAL_API_CHECK_CARD_CODE', PAYPAL_API_CHECK_CARD_CODE, $options ); 
				?>
            </td>
            <td><?php echo vmtooltip(PAYPAL_API_CVV_TEXT_EXPLAIN) ?>
            </td>
        </tr>
		<tr class="row0"><td><strong><?php echo PAYPAL_API_TEXT_CART_BUTTON;?></strong></td>
			<td><select name="PAYPAL_API_CART_BUTTON_ON" class="inputbox">
					<option <?php if (@PAYPAL_API_CART_BUTTON_ON == '1') echo "selected=\"selected\""; ?> value="1">
					<?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_YES'); ?></option>
					<option <?php if (@PAYPAL_API_CART_BUTTON_ON != '1') echo "selected=\"selected\""; ?> value="0">
					<?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_NO'); ?></option>
				</select>
			</td>
			<td><?php echo PAYPAL_API_TEXT_CART_BUTTON_EXPLAIN;?></td>
		</tr>
        <tr class="row1">
        <td><strong><?php echo PAYPAL_API_TEXT_USE_SHIPPING ?></strong></td>
            <td>
				<?php
				$options = array( '1' => PAYPAL_API_TEXT_YES, 
									'0' =>PAYPAL_API_TEXT_NO );
				ps_html::dropdown_display( 'PAYPAL_API_USE_SHIPPING', PAYPAL_API_USE_SHIPPING, $options ); 
				?>
            </td>
            <td><?php echo vmtooltip( PAYPAL_API_TEXT_USE_SHIPPING_EXPLAIN ) ?>
            </td>
        </tr>		
		<tr class="row1"><td><strong><?php echo PAYPAL_API_TEXT_DIRECT_PAYMENT_ON;?></strong></td>
			<td><select name="PAYPAL_API_DIRECT_PAYMENT_ON" class="inputbox">
					<option <?php if (@PAYPAL_API_DIRECT_PAYMENT_ON == '1') echo "selected=\"selected\""; ?> value="1">
					<?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_YES'); ?></option>
					<option <?php if (@PAYPAL_API_DIRECT_PAYMENT_ON != '1') echo "selected=\"selected\""; ?> value="0">
					<?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_NO'); ?></option>
				</select>
			</td>
			<td><?php echo PAYPAL_API_TEXT_DIRECT_PAYMENT_EXPLAIN;?></td>
		</tr>
        <tr class="row0">
        <td><strong><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_PAYPAL_ONLYVERIFIED') ?></strong></td>
            <td>
                <select name="PAYPAL_API_VERIFIED_ONLY" class="inputbox" >
	                <option <?php if (@PAYPAL_API_VERIFIED_ONLY != '1') echo "selected=\"selected\""; ?> value="0"><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_NO') ?></option>
	                <option <?php if (@PAYPAL_API_VERIFIED_ONLY == '1') echo "selected=\"selected\""; ?> value="1"><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_YES') ?></option>
                </select>
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_PAYPAL_ONLYVERIFIED_EXPLAIN') ?></td>
        </tr>
        <tr class="row1">
            <td><strong><?php echo PAYPAL_API_TEXT_STATUS_SUCCESS ?></strong></td>
            <td>
                <select name="PAYPAL_API_VERIFIED_STATUS" class="inputbox" >
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
                      if (PAYPAL_API_VERIFIED_STATUS == $order_status_code[$i]) 
                         echo "\" selected=\"selected\">";
                      else
                         echo "\">";
                      echo $order_status_name[$i] . "</option>\n";
                    }?>
                    </select>
            </td>
            <td><?php echo PAYPAL_API_TEXT_STATUS_SUCCESS_EXPLAIN ?>
            </td>
        </tr>
        <tr class="row0">
            <td><strong><?php echo PAYPAL_API_TEXT_STATUS_PENDING ?></strong></td>
            <td>
                <select name="PAYPAL_API_PENDING_STATUS" class="inputbox" >
                <?php
                    for ($i = 0; $i < sizeof($order_status_code); $i++) {
                      echo "<option value=\"" . $order_status_code[$i];
                      if (PAYPAL_API_PENDING_STATUS == $order_status_code[$i]) 
                         echo "\" selected=\"selected\">";
                      else
                         echo "\">";
                      echo $order_status_name[$i] . "</option>\n";
                    } ?>
                    </select>
            </td>
            <td><?php echo PAYPAL_API_TEXT_STATUS_PENDING_EXPLAIN ?></td>
        </tr>
        <tr class="row1">
            <td><strong><?php echo PAYPAL_API_TEXT_STATUS_FAILED ?></strong></td>
            <td>
                <select name="PAYPAL_API_INVALID_STATUS" class="inputbox" >
                <?php
                    for ($i = 0; $i < sizeof($order_status_code); $i++) {
                      echo "<option value=\"" . $order_status_code[$i];
                      if (PAYPAL_API_INVALID_STATUS == $order_status_code[$i]) 
                         echo "\" selected=\"selected\">";
                      else
                         echo "\">";
                      echo $order_status_name[$i] . "</option>\n";
                    } ?>
                    </select>
            </td> 
            <td><?php echo PAYPAL_API_TEXT_STATUS_FAILED_EXPLAIN ?>
            </td>
        </tr>
 
      </table>
    <?php
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
      global $vendor_image_url, $vmLogger;
		$lang = jfactory::getLanguage();
		$name= $lang->getBackwardLang();
		if( file_exists(CLASSPATH ."payment/paypal_api/languages/lang.".$name.".admin.php")) {
			include(CLASSPATH ."payment/paypal_api/languages/lang.".$name.".admin.php");
		} else {
			include(CLASSPATH ."payment/paypal_api/languages/lang.english.admin.php");
		}
	  if( substr( vmget($d,'PAYPAL_API_IMAGEURL'), 0, 5 ) != 'https' ) {
			$vmLogger->info( PAYPAL_API_TEXT_IMAGE_URL_WARN );
	  }
      $my_config_array = array(
                "PAYPAL_API_API_USERNAME" => vmget($d,'PAYPAL_API_API_USERNAME'),
                "PAYPAL_API_API_PASSWORD" => vmget($d,'PAYPAL_API_API_PASSWORD'),
                "PAYPAL_API_API_SIGNATURE" => vmget($d,'PAYPAL_API_API_SIGNATURE'),
                "PAYPAL_API_PAYMENTTYPE" => vmget($d,'PAYPAL_API_PAYMENTTYPE', 'Sale' ),
                "PAYPAL_API_IMAGEURL" => vmget($d,'PAYPAL_API_IMAGEURL', $vendor_image_url ),
				"PAYPAL_API_CART_BUTTON_ON" => vmget($d, 'PAYPAL_API_CART_BUTTON_ON', '1'),
				"PAYPAL_API_DIRECT_PAYMENT_ON" => vmget($d, 'PAYPAL_API_DIRECT_PAYMENT_ON', '0'),
                "PAYPAL_API_VERIFIED_ONLY" => vmget($d, 'PAYPAL_API_VERIFIED_ONLY', '1'),
                "PAYPAL_API_VERIFIED_STATUS" => $d['PAYPAL_API_VERIFIED_STATUS'],
                "PAYPAL_API_PENDING_STATUS" => $d['PAYPAL_API_PENDING_STATUS'],
                "PAYPAL_API_INVALID_STATUS" => $d['PAYPAL_API_INVALID_STATUS'],
                "PAYPAL_API_CHECK_CARD_CODE" => vmget( $d, 'PAYPAL_API_CHECK_CARD_CODE', 'YES'),
                "PAYPAL_API_CERTIFICATE" => vmget( $d, 'PAYPAL_API_CERTIFICATE' ),
                "PAYPAL_API_USE_SHIPPING" => vmrequest::getint('PAYPAL_API_USE_SHIPPING', 1),
                "PAYPAL_API_DEBUG" => vmrequest::getint('PAYPAL_API_DEBUG', 0)
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
     else
        return false;
   }
   
  /**************************************************************************
  ** name: process_payment()
  ** returns: 
  ***************************************************************************/
   function process_payment($order_number, $order_total, &$d) { 
		global $vendor_mail, $vendor_currency, $VM_LANG, $vmLogger;
		$_SESSION['CURL_ERROR'] = false;
		$_SESSION['CURL_ERROR_TXT'] = "";
        $ps_vendor_id = $_SESSION["ps_vendor_id"];
        $auth = $_SESSION['auth'];
		
		/* Gather the information to make the final call to
		   finalize the PayPal payment.  The variable nvpstr
		   holds the name value pairs
		   */
		require_once( CLASSPATH."payment/".__CLASS__.".cfg.php" );	
		
		$lang = jfactory::getLanguage();
		$name= $lang->getBackwardLang();
		if( file_exists(CLASSPATH ."payment/paypal_api/languages/lang.".$name.".php"))
			include(CLASSPATH ."payment/paypal_api/languages/lang.".$name.".php");
		else
			include(CLASSPATH ."payment/paypal_api/languages/lang.english.php");
		
		require_once(dirname(__FILE__).'/paypal_api/CallerService.php');
		
		$token = "";
		if ( isset( $_SESSION['ppex_token'] ) ) {
			$token = $_SESSION['ppex_token'];
		}

		$currencyCodeType 	= urlencode($GLOBALS['product_currency']);
		
		$payerID = "";
		if ( isset( $_SESSION['ppex_userdata'] ) ) {
			$payerID = urlencode($_SESSION['ppex_userdata']['payer_id']);
		}

		$IP 		= urlencode($_SERVER['REMOTE_ADDR']);
		
        // Get user billing information from the database
        $dbbt = new ps_DB;
        $qt = "SELECT * FROM #__{vm}_user_info WHERE user_id=".$auth["user_id"]." AND address_type='BT'";
        $dbbt->query($qt);
        $dbbt->next_record();
        $user_info_id = $dbbt->f("user_info_id");
        if( $user_info_id != $d["ship_to_info_id"]) {
		// There is a different shipping address than the billing address, get the shipping information
            $dbst = new ps_DB;
            $qt = "SELECT * FROM #__{vm}_user_info WHERE user_info_id='".$d["ship_to_info_id"]."' AND address_type='ST'";
            $dbst->query($qt);
            $dbst->next_record();
        }
        else {
			// Shipping address is the same as the billing address
            $dbst = $dbbt;
        }

		$payment_action = PAYPAL_API_PAYMENTTYPE;

		$ordernum = urlencode(substr($order_number, 0, 20));
		
		$requireCVV = PAYPAL_API_CHECK_CARD_CODE;
		
		//initiate our error out variables.
		$count=0;
		$errorOut = FALSE;
		$errorOut2 = FALSE;
		$displayMsg = "";
		
		//Check to see if we are coming from paypal express checkout.
		//If not we do a directpaymentrequest, otherwise we try express checkout request.
		if ( isset($_SESSION['ppex_userdata']) && is_array($_SESSION['ppex_userdata']) && isset($_SESSION['ppex_token']) )
		{
		
			//Need to test to see if the express checkout is verified if PAYPAL_API_VERIFIED_ONLY is set to accept
			//Only verified users
			if(PAYPAL_API_VERIFIED_ONLY === '1' || PAYPAL_API_VERIFIED_ONLY === 1)
			{
				if($_SESSION['ppex_userdata']['payerstatus'] === 'unverified')
				{
					$vmLogger->err($nvp_common_014);
					return false;
				}
			}
		
			$nvpreq = $this->NVP_DoExpressCheckout($d, $dbbt, $dbst, $order_total, $payment_action, $ordernum);
			
			if($nvpreq) {
				$vmLogger->debug('Doing Express Checkout Request');
				$nvpLS = $nvpreq;
				$nvpRES = hash_call("DoExpressCheckoutPayment",$nvpreq);
				
			}
			else {
				//We failed to gather the proper array, most likely do to with not having certain parameters properly filled.
				$errorOut = TRUE;
				$errorOut2 = TRUE;
				$displayMsg .= $nvp_common_010;
			}
		}
		else
		{
			$vmLogger->debug("Doing Direct Payment Request");
			$nvpreq = $this->NVP_DoDirectPaymentRequest($d, $dbbt, $dbst, $order_total, $payment_action, $ordernum, $requireCVV);
			
			if($nvpreq) {
				$nvpLS = $nvpreq;
				$nvpRES = hash_call("DoDirectPayment",$nvpreq);
			}
			else {
				$displayMsg .= $nvp_common_011;
				$d["error"] = $displayMsg;
				$vmLogger->err($displayMsg);
				return false;
			}
		}
		// Parse out all the data\
		
		if(isset($nvpRES)) {
			$ack = strtoupper($nvpRES["ACK"]);
			
			if(isset($nvpRES['REDIRECTREQUIRED']) && is_array($_SESSION['ppex_userdata']))
			{
				$_SESSION['ppex_userdata']['redirectrequired'] = $nvpRES['REDIRECTREQUIRED'];
			}
			
			if(isset($nvpRES['PROTECTIONELIGIBILITY']))
			{
				$protection = $nvpRES['PROTECTIONELIGIBILITY'];
			}
			
			//check to see if it was succesful or not. If not error out, otherwise retrieve the transaction status from paypal.
			if($ack!="SUCCESS" && $ack!="SUCCESSWITHWARNING")  
			{
				$displayMsg .= $nvp_common_012." - ".$ack." - ";
				$errorOut2 = TRUE;
			}
			else
			{		
				if(isset($nvpRES['AVSCODE'])) {$avsCode = $nvpRES['AVSCODE'];}
				if(isset($nvpRES['CVV2MATCH'])) {$cvv2Code = $nvpRES['CVV2MATCH'];}
				$transactionID = $nvpRES['TRANSACTIONID'];
				//get the transaction details array that paypal returned.
				$nvpDETAILS = $this->NVP_TransactionDetails($transactionID);
				if($nvpDETAILS)
				{
					if(isset($nvpDETAILS['PAYMENTSTATUS']))
					{
						$status = $nvpDETAILS['PAYMENTSTATUS'];
						
						if(strtolower($status) == "completed")	{
							$d['new_order_status'] = PAYPAL_API_VERIFIED_STATUS;
						}
						elseif(strtolower($status) == "pending") {
							$d['new_order_status'] = PAYPAL_API_PENDING_STATUS;
						}
						elseif(strtolower($status) == "processed") {
							$d['new_order_status'] = PAYPAL_API_VERIFIED_STATUS;
						}
						elseif(strtolower($status) == "failed") {
							$d['new_order_status'] = PAYPAL_API_INVALID_STATUS;
						}
						else {
							$d['new_order_status'] = PAYPAL_API_INVALID_STATUS;
						}
					}
					else {
						$d['new_order_status'] = PAYPAL_API_INVALID_STATUS;
					}
				}
				else {
					$d['new_order_status'] = PAYPAL_API_INVALID_STATUS;
				}
			}
			
			//if paypal sent back an error check for it and add it to our error buffer.
			while (isset($nvpRES["L_SHORTMESSAGE".$count]))  {		
				  $errorCODE    = $nvpRES["L_ERRORCODE".$count];
				  $shortMESSAGE = $nvpRES["L_SHORTMESSAGE".$count];
				  $longMESSAGE  = $nvpRES["L_LONGMESSAGE".$count]; 
				  
				if (isset($shortMESSAGE)) {
					$displayMsg .= 'SHORTMESSAGE ='.$shortMESSAGE.' - '."\n";
					$errorOut = TRUE;
				}
				if (isset($errorCODE)) {
					$displayMsg .= 'ERRORCODE ='.$errorCODE.' - '."\n";
					$errorOut = TRUE;
				}
				if (isset($longMESSAGE)) {
					$displayMsg .= 'LONGMESSAGE ='.$longMESSAGE.' - '."\n";
					$errorOut = TRUE;
				}
				
				if(isset($errorCODE)) {
					if(isset($_SESSION['ppex_userdata']) && is_array($_SESSION['ppex_userdata']) && isset($_SESSION['ppex_token'])) {
						$errorText = $this->NVP_ErrorToText($errorCODE, 'doexpress');
						
						if($errorText) {
							$vmLogger->err($errorText);
							if($errorCODE  == '10417' || $errorCODE == '10422')
							{
								ps_paypal_api::redirectToPayment($d);
							}
							return false;
						}
					}
					else {
						$errorText = $this->NVP_ErrorToText($errorCODE, 'dodirect');
						
						if($errorText) {
							$vmLogger->err($errorText);
							return false;
						}
					}
				}
				
				$count++;
			}
			
			//Check the AVS code for faulty address issues.
			if(isset($avsCode)) {
				if (($avsCode == "P") || ($avsCode == "W") || ($avsCode == "X") || ($avsCode == "Y") || ($avsCode == "Z"))
				{
					$displayMsg .= $nvp_order_processed;
				}
				else
				{
					$displayMsg .= $nvp_address_error;
					$errorOut = TRUE;
				}
			}
			
			//Check the CVV code to make sure paypal could properly use it. If not we error out.
			if($requireCVV == '1')
			{
				if (isset($cvv2Code))
				{
					if(strtoupper($cvv2Code) == "N")
					{
						$displayMsg .= $nvp_error_invalid_CVV;
						$errorOut = TRUE;
					}
				}
			}
		}
		
		//Check to see if we display errors or not. 
		//If not set to 1 we only display errors in the debug file and not on screen
		if(PAYPAL_API_DEBUG == '1')
		{
			//If we have an error we add it to the log. We return false since we had an error.
			if ($errorOut || $errorOut2) {
		        $d["error"] = $displayMsg;
		        $d["order_payment_log"] = $displayMsg;
		        // Catch Transaction ID
				if(isset($transactionID))
				{
					$d["order_payment_trans_id"] = $transactionID;
		        }
				
				$html = "<br/><span class=\"message\">".$VM_LANG->_('PHPSHOP_PAYMENT_INTERNAL_ERROR')." PayPal Pro Direct Payment Error - " . $displayMsg . "</span>";
					
				//Catch any CURL Errors
				if ($_SESSION['CURL_ERROR'] == true) { 
					$d["error"] .= "-CURL ERROR: " . $_SESSION['CURL_ERROR_TXT'];
					$d["order_payment_log"] .= "-CURL ERROR: " . $_SESSION['CURL_ERROR_TXT'];
					$html .= "<br/><span class=\"message\">-CURL ERROR: " . $_SESSION['CURL_ERROR_TXT']."</span>";
				}
					
				if(isset($nvpLS)) $displayMsg .= $nvpLS;
		       
				$vmLogger->err($displayMsg);
			}
			else
			{
				//If there was no errorOut or errorOut2 we still need to check for CURL error
				//Then display it
				if ($_SESSION['CURL_ERROR'] == true) { 
					echo "<br />" . $displayMsg . "PAYPAL ERROR: " . $_SESSION['CURL_ERROR_TXT'] . "<br /><br />" . $response; $d["error"] = "PAYPAL ERROR: " . $_SESSION['CURL_ERROR_TXT'];
				}
			}
		}
		else
		{
			if ($errorOut || $errorOut2) {
				if ($_SESSION['CURL_ERROR'] == true) {
					$displayMsg .= $_SESSION['CURL_ERROR_TXT'];
				}
				
				$vmLogger->debug($displayMsg);
			}
			else
			{
				//Even though we aren't displaying the message
				//If we do not get an errorOut or errorOut2 we still need to check for CURL error
				//If we have a CURL error write it to the debug log
				if ($_SESSION['CURL_ERROR'] == true) {
					$displayMsg .= $_SESSION['CURL_ERROR_TXT'];
					$vmLogger->debug($displayMsg);
				}
			}
		}
		
		// Catch Transaction ID
		if(isset($transactionID)) 	{
			$d["order_payment_trans_id"] = $transactionID;
		}
		else 	{
			$vmLogger->err($nvp_error_no_transaction);
			return false;
		}
		
		//if we are down this far that means the order has completed succesfully.
		$d["order_payment_log"] = "Success: " . $order_number;

		$d["order_payment_log"] .= " PayPal Transaction ID: ".$transactionID;
		
		if(isset($protection)) $d['order_payment_log'] .= " PayPal Protection Eligibility: ".$protection;

		
		//Since the order completed successfully lets go ahead and assign the payerID
		//If it is set and put into the user_info in extra_field_3
		if(isset($_SESSION['ppex_userdata']) && is_array($_SESSION['ppex_userdata']) && isset($_SESSION['ppex_userdata']['payer_id']) && isset($_SESSION['ppex_token']))
		{
			if(isset($_SESSION['ppex_userdata']['payerstatus'])) $d['order_payment_log'] .= " PayPal Payer Status: ".$_SESSION['ppex_userdata']['payerstatus'];
						
			if( $auth['user_id'] ) {
				$field = Array('extra_field_3' => $_SESSION['ppex_userdata']['payer_id']);
				
				$dbbt->buildQuery('UPDATE', '#__{vm}_user_info', $field, "WHERE user_id = ".(int)$auth['user_id']);
				$dbbt->query();
			}
		}
		
		
		$vmLogger->debug($d['order_payment_log']);
		
		return true;
	} 

	function redirectToPayment(&$d)
	{
		require_once(CLASSPATH . 'ps_checkout.php');
		
		$checkout_steps = ps_checkout::get_checkout_steps();
		
		$current_stage = 0;
		
		foreach($checkout_steps as $step)
		{
			if(in_array('CHECK_OUT_GET_PAYMENT_METHOD', $checkout_steps[$current_stage]))
			{
				break;
			}
			else
			{
				$current_stage++;
			}
		}
		
		//Request used for when going from a regular link instead of a post from form
		$_REQUEST['checkout_stage'] = $current_stage;
		$_POST['checkout_this_step'] = $checkout_steps[$current_stage];
		
		ps_paypal_api::destroyPaypalSession();
		
		vmRedirect( $sess->url( 'index.php?page=checkout.index&shipping_rate_id='.
			urlencode($d['shipping_rate_id']).'&ship_to_info_id='.$d['ship_to_info_id'].'&checkout_stage='.$current_stage, false, false ) );
	}
	
	/**
	 * Tries to reauthorize a payment
	 * @return mixed, False on failure
	 */
	function reauthorize($id, $amount, $currencyCode)
	{
		$nvpstr = "&AuthorizationID=".$id.'&Amt='.$amount.'&CurrencyCode='.$currencyCode;
		
		$resArray=hash_call("DoReauthorization",$nvpstr);
		$_SESSION['reshash']=$resArray;
		$ack = strtoupper($resArray["ACK"]);
		
		if($ack=="SUCCESS")
		{
			return $resArray['AUTHORIZATIONID'];
		}		
		
		return false;
	}
	
    /**
	* Should be called when an amount was authorized before and the items are shipped now
	* 
	*/
	function capture_payment( &$d, $reAuthorized = false ) {
		global $vmLogger;
		if( empty($d['order_number'])) {
			$vmLogger->err("Error: No Order Number provided.");
			return false;
		}
		
		// include the configuration file
		require_once(CLASSPATH ."payment/".__CLASS__.".cfg.php");
		if( PAYPAL_API_PAYMENTTYPE != 'Authorization' ) {
			return true;
		}
		require_once(dirname(__FILE__).'/paypal_api/CallerService.php');
		$db = new ps_DB();
		$q = "SELECT #__{vm}_orders.order_id,order_number,order_payment_trans_id,order_total,order_currency FROM #__{vm}_orders, #__{vm}_order_payment WHERE ";
		$q .= "order_number='".$d['order_number']."' ";
		$q .= "AND #__{vm}_orders.order_id=#__{vm}_order_payment.order_id";
		$db->query( $q );
		if( !$db->next_record() || ! $db->f('order_payment_trans_id') ) {
			$vmLogger->err("Error: Order or TransactionID not found.");
			return false;
		}
		$note = '';
		if( !empty($_REQUEST['include_comment'])) {
			$note= substr(strip_tags($d['order_comment']), 0, 255 );
		}
		
		//Check to see if we need to reauthorize
		//Will turn false if we can't
		$reauth = ps_paypal_api::reauthorize($db->f('order_payment_trans_id'), round($db->f('order_total'),2), $db->f('order_currency'));
	
		if($reauth === false)
		{
			$nvpstr = "&AuthorizationID=".$db->f('order_payment_trans_id').'&Amt='.round($db->f('order_total'),2).'&CompleteType=Complete&CurrencyCode='.$db->f('order_currency').'&Note='.urlencode($note);
		}
		else
		{
			$nvpstr = "&AuthorizationID=".$reauth.'&Amt='.round($db->f('order_total'),2).'&CompleteType=Complete&CurrencyCode='.$db->f('order_currency').'&Note='.urlencode($note);			
		}
		
		$order_payment_trans_id = $db->f('order_payment_trans_id');
		
		$resArray=hash_call("DoCapture",$nvpstr);
		$_SESSION['reshash']=$resArray;
		$ack = strtoupper($resArray["ACK"]);
		
		if($ack=="SUCCESS"){
			$field = Array('order_payment_trans_id' => $resArray['TRANSACTIONID']);
			
			$db->buildQuery('UPDATE', '#__{vm}_order_payment', $field, "WHERE order_payment_trans_id = '".$order_payment_trans_id."'");
			$db->query();
			
			$vmLogger->info('The Order Amount has been successfully captured by PayPal.');
			return true;
		} else {
			$vmLogger->info('Failed to capture the Order Amount from PayPal: '.$_SESSION['reshash']['L_SHORTMESSAGE0'].'. '.$_SESSION['reshash']['L_LONGMESSAGE0']);
		}
		return false;
	}
	
	function void_authorization( &$d ) {
		global $vmLogger;
		if( empty($d['order_number'])) {
			$vmLogger->err("Error: No Order Number provided.");
			return false;
		}
		
		// include the configuration file
		require_once(CLASSPATH ."payment/".__CLASS__.".cfg.php");
		if( PAYPAL_API_PAYMENTTYPE != 'Authorization' ) {
			return true;
		}
		require_once(dirname(__FILE__).'/paypal_api/CallerService.php');
		$db = new ps_DB;
		$q = "SELECT #__{vm}_orders.order_id,order_number,order_payment_trans_id,order_total FROM #__{vm}_orders, #__{vm}_order_payment WHERE ";
		$q .= "order_number='".$d['order_number']."' ";
		$q .= "AND #__{vm}_orders.order_id=#__{vm}_order_payment.order_id";
		$db->query( $q );
		if( !$db->next_record() || ! $db->f('order_payment_trans_id') ) {
			$vmLogger->err("Error: Order or TransactionID not found.");
			return false;
		}
		$note = '';
		if( !empty($_REQUEST['include_comment'])) {
			$note= substr(strip_tags($d['order_comment']), 0, 255 );
		}
		$nvpstr = "&AuthorizationID=".$db->f('order_payment_trans_id').'&Note='.urlencode($note);
		
		$resArray=hash_call("DoVoid",$nvpstr);
		$_SESSION['reshash']=$resArray;
		$ack = strtoupper($resArray["ACK"]);
		if($ack=="SUCCESS"){
			$vmLogger->info('The Transaction has been voided.');
			return true;
		} else {
		   $vmLogger->info('Failed to void the transaction: '.$_SESSION['reshash']['L_SHORTMESSAGE0'].' '.$_SESSION['reshash']['L_LONGMESSAGE0']);
		}
		return false;
	}
	/**
	 * Does a refund for a transaction
	 */
	function do_refund( &$d ) {
		global  $sess, $VM_LANG, $vmLogger;
        
		if( empty($d['order_number'])) {
			$vmLogger->err("Error: No Order Number provided.");
			return false;
		}
		
		// include the configuration file
		require_once(CLASSPATH ."payment/".__CLASS__.".cfg.php");

		require_once(dirname(__FILE__).'/paypal_api/CallerService.php');
		$db = new ps_DB;
		$q = "SELECT #__{vm}_orders.order_id,order_number,order_payment_trans_id,order_total FROM #__{vm}_orders, #__{vm}_order_payment WHERE ";
		$q .= "order_number='".$d['order_number']."' ";
		$q .= "AND #__{vm}_orders.order_id=#__{vm}_order_payment.order_id";
		$db->query( $q );
		if( !$db->next_record() || ! $db->f('order_payment_trans_id') ) {
			$vmLogger->err("Error: Order or TransactionID not found.");
			return false;
		}
		$note = '';
		if( !empty($_REQUEST['include_comment'])) {
			$note= substr(strip_tags($d['order_comment']), 0, 255 );
		}
		
		$nvpstr = "&TRANSACTIONID=".$db->f('order_payment_trans_id').'&REFUNDTYPE=Full&NOTE='.urlencode($note);

		$resArray=hash_call("RefundTransaction",$nvpstr);
		$_SESSION['reshash']=$resArray;
		$ack = strtoupper($resArray["ACK"]);
		if($ack=="SUCCESS"){
			$vmLogger->info('The Transaction has been refunded and the order has been canceled');
			return true;
		} else {
		   $vmLogger->info('Failed to refund the transaction: '.$_SESSION['reshash']['L_SHORTMESSAGE0'].' '.$_SESSION['reshash']['L_LONGMESSAGE0']);
		}
		return false;
	}
	
	/**
	 * Checks to see if the incoming address from paypal express checkout is already added
	 * If the address is not added, then it adds the address to the ship to of the user's account
	 * @param &$auth
	 * @return false on failure
	 */
	 function checkAddress(&$auth)
	 {
		global $vmLogger, $VM_LANG;
		/* Select all the ship to information for this user id and
		* order by modification date; most recently changed to oldest
		*/
		if( $auth['user_id'] ) 
		{
			$db = new ps_DB;
			$q  = "SELECT * from #__{vm}_user_info WHERE ";
			$q .= "user_id=" . (int)$auth['user_id'] . ' ';
			$q .= "AND address_type='BT'";
			$db->query($q);
			$db->next_record();
			// check if an alternative shipping address was returned from PayPal
			if( $_SESSION['ppex_userdata']['address_1'] != $db->f("address_1")
				|| $_SESSION['ppex_userdata']['city'] != $db->f("city") ) 
			{
				
				$q  = "SELECT * FROM #__{vm}_user_info i ";
				$q .= "INNER JOIN #__{vm}_country c ON (i.country=c.country_3_code) ";
				$q .= "LEFT JOIN #__{vm}_state s ON (i.state=s.state_2_code AND s.country_id=c.country_id) ";
				$q .= "WHERE user_id =" . (int)$auth['user_id'] . ' ';
				$q .= "AND address_type = 'ST' ";
				$q .= "ORDER by address_type_name, mdate DESC";
				$db->query($q);
				$add_address = true;
				if ( $db->num_rows() > 0 ) {
					while( $db->next_record() ) {
						if( $_SESSION['ppex_userdata']['address_1'] == $db->f("address_1")
							&& $_SESSION['ppex_userdata']['city'] == $db->f("city") ) {
							$add_address = false;
							break;
						}
					}
				}
				// Add the new shipping address if not yet available
				if( $add_address ) 
				{
					$fields = array( 'address_type' => 'ST',
					'address_type_name' => $_SESSION['ppex_userdata']['address_1'].', '.$_SESSION['ppex_userdata']['city'],
					'company' => $_SESSION['ppex_userdata']['company'],
					'address_1' => $_SESSION['ppex_userdata']['address_1'],
					'address_2' => vmget($_SESSION['ppex_userdata'],'address_2'),
					'city' => $_SESSION['ppex_userdata']['city'],
					'zip' => $_SESSION['ppex_userdata']['zip'],
					'country' => $_SESSION['ppex_userdata']['country'],
					'state' => $_SESSION['ppex_userdata']['state']
					);
					if( !empty( $_SESSION['ppex_userdata']['shiptoname'] )) 
					{
						$fields['first_name'] = $_SESSION['ppex_userdata']['shiptoname'];
						$fields['last_name'] = '';
					} else {
						$fields['first_name'] = $_SESSION['ppex_userdata']['first_name'];
						$fields['last_name'] = $_SESSION['ppex_userdata']['last_name'];
					}
					$fields['user_id'] = $_SESSION['auth']['user_id'];
					$fields['user_info_id'] = md5(  uniqid($_SESSION['ppex_userdata']['payer_id']) );
					$fields['address_type'] = 'ST';
					$timestamp = time();
					$fields['cdate'] = $timestamp;
					$fields['mdate'] = $timestamp;

					$db->buildQuery('INSERT', '#__{vm}_user_info', $fields  );
					if( $db->query() === false ) 
					{
						$vmLogger->err($VM_LANG->_('VM_USERADDRESS_ADD_FAILED'));
						return false;
					}
					$vmLogger->info($VM_LANG->_('VM_USERADDRESS_ADDED'));
				} 
			}
		}	 
	 }
	
	/**
	 * Checks the status of what part of the checkout we are on
	 * And sees if we need to do anything special for the express checkout
	 * @param array &$auth
	 * @param array &$checkout_steps
	 * @param int &$current_stage
	 */
	 function checkOutStatus(&$auth, &$checkout_steps, &$current_stage, &$ship_to_info_id, $ppex=0)
	 {
	 
		require_once(CLASSPATH ."payment/".__CLASS__.".cfg.php");
	 
		global $vmLogger;
		if(in_array('CHECK_OUT_GET_PAYMENT_METHOD', $checkout_steps[$current_stage]))
		{
			$vmLogger->debug('At Payment Method -> Redirecting to Confirm');
			//Fake the Credit Card Details to bypass the credit card checking of VM
			$_SESSION['ccdata']['creditcard_code'] = '242';
			$_SESSION['ccdata']['credit_card_code'] = "242";
			$_SESSION['ccdata']['order_payment_name']  = "PayPal Express";
			$_SESSION['ccdata']['order_payment_number']  = "4834879217180125";
			$_SESSION['ccdata']['order_payment_expire_month'] = 11;
			//The year date will need to be changed as the years progress
			$_SESSION['ccdata']['order_payment_expire_year'] = 2016;
			// calculate the unix timestamp for the specified expiration date
			// default the day to the 1st
			$expire_timestamp = @mktime(0,0,0,$_SESSION["ccdata"]["order_payment_expire_month"], 15,$_SESSION["ccdata"]["order_payment_expire_year"]);
			$_SESSION["ccdata"]["order_payment_expire"] = $expire_timestamp;
			
			$current_stage++;
			
			if(isset($checkout_steps[$current_stage]) == false)
			{
				$current_stage++;
				ps_paypal_api::checkOutStatus($auth, $checkout_steps, $current_stage, $ship_to_info_id, $ppex);
				return;
			}
			
			if(in_array('CHECK_OUT_GET_SHIPPING_ADDR', $checkout_steps[$current_stage]))
			{
				ps_paypal_api::checkOutStatus($auth, $checkout_steps, $current_stage, $ship_to_info_id, $ppex);
				return;
			}
			//Request used for when going from a regular link instead of a post from form
			$_REQUEST['checkout_stage'] = $current_stage;
			$_POST['checkout_this_step'] = $checkout_steps[$current_stage];
		}
		//If we are on the shipping address part and the ship_to_info_id is empty, then we need to automatically
		//Select the ship to address based on the paypal info and automatically step forward once in the stage.
		else if(in_array('CHECK_OUT_GET_SHIPPING_ADDR', $checkout_steps[$current_stage]) && empty($ship_to_info_id))
		{
			if( $auth['user_id'] ) 
			{
				$db = new ps_DB;
				$q  = "SELECT * FROM #__{vm}_user_info ";
				$q .= "WHERE user_id =" . (int)$auth['user_id'] . ' ';
				$q .= "AND address_type = 'ST' ";
				$q .= "ORDER by address_type_name, mdate DESC";
				$db->query($q);
				
				if ( $db->num_rows() > 0 ) {
					while( $db->next_record() ) {
						if( $_SESSION['ppex_userdata']['address_1'] == $db->f("address_1")
							&& $_SESSION['ppex_userdata']['city'] == $db->f("city") && $_SESSION['ppex_userdata']['zip'] == $db->f("zip")) {
							$_REQUEST['ship_to_info_id'] = $db->f('user_info_id');
							$ship_to_info_id = $db->f('user_info_id');
							$current_stage++;
							
							if(isset($checkout_steps[$current_stage]) == false)
							{
								$current_stage++;
								ps_paypal_api::checkOutStatus($auth, $checkout_steps, $current_stage, $ship_to_info_id, $ppex);
								return;
							}
							
							if(in_array('CHECK_OUT_GET_PAYMENT_METHOD', $checkout_steps[$current_stage]))
							{
								ps_paypal_api::checkOutStatus($auth, $checkout_steps, $current_stage, $ship_to_info_id, $ppex);
								return;
							}
							
							//Request used for when going from a regular link instead of a post from form
							$_REQUEST['checkout_stage'] = $current_stage;
							$_POST['checkout_this_step'] = $checkout_steps[$current_stage];
							break;
						}
					}
				}
				//We didn't find a shipping address so that means the user is using the Bill to as shipping
				else
				{
					$q  = "SELECT * FROM #__{vm}_user_info ";
					$q .= "WHERE user_id =" . (int)$auth['user_id'] . ' ';
					$q .= "AND address_type = 'BT' ";
					$q .= "ORDER by address_type_name, mdate DESC";
					$db->query($q);	
					
					if ( $db->num_rows() > 0 ) {
						while( $db->next_record() ) {
							if( $_SESSION['ppex_userdata']['address_1'] == $db->f("address_1")
								&& $_SESSION['ppex_userdata']['city'] == $db->f("city") && $_SESSION['ppex_userdata']['zip'] == $db->f("zip")) {
								$_REQUEST['ship_to_info_id'] = $db->f('user_info_id');
								$ship_to_info_id = $db->f('user_info_id');
								
								$current_stage++;
								
								if(isset($checkout_steps[$current_stage]) == false)
								{
									$current_stage++;
									ps_paypal_api::checkOutStatus($auth, $checkout_steps, $current_stage, $ship_to_info_id, $ppex);
									return;
								}
								
								if(in_array('CHECK_OUT_GET_PAYMENT_METHOD', $checkout_steps[$current_stage]))
								{
									ps_paypal_api::checkOutStatus($auth, $checkout_steps, $current_stage, $ship_to_info_id, $ppex);
									return;
								}
								
								//Request used for when going from a regular link instead of a post from form
								$_REQUEST['checkout_stage'] = $current_stage;
								$_POST['checkout_this_step'] = $checkout_steps[$current_stage];
								break;
							}
						}
					}
				}
			}
		}
		
		if((int)$ppex == 2 && PAYPAL_API_USE_SHIPPING != '1')
		{
			if( $auth['user_id'] ) 
			{
				$db = new ps_DB;
				$q  = "SELECT * FROM #__{vm}_user_info ";
				$q .= "WHERE user_id =" . (int)$auth['user_id'] . ' ';
				$q .= "AND address_type = 'ST' ";
				$q .= "ORDER by address_type_name, mdate DESC";
				$db->query($q);
				
				if ( $db->num_rows() > 0 ) {
					while( $db->next_record() ) {
						if( $_SESSION['ppex_userdata']['address_1'] == $db->f("address_1")
							&& $_SESSION['ppex_userdata']['city'] == $db->f("city") && $_SESSION['ppex_userdata']['zip'] == $db->f("zip")) {
							$_REQUEST['ship_to_info_id'] = $db->f('user_info_id');
							$ship_to_info_id = $db->f('user_info_id');
							break;
						}
					}
				}
				//We didn't find a shipping address so that means the user is using the Bill to as shipping
				else
				{
					$q  = "SELECT * FROM #__{vm}_user_info ";
					$q .= "WHERE user_id =" . (int)$auth['user_id'] . ' ';
					$q .= "AND address_type = 'BT' ";
					$q .= "ORDER by address_type_name, mdate DESC";
					$db->query($q);	
					
					if ( $db->num_rows() > 0 ) {
						while( $db->next_record() ) {
							if( $_SESSION['ppex_userdata']['address_1'] == $db->f("address_1")
								&& $_SESSION['ppex_userdata']['city'] == $db->f("city") && $_SESSION['ppex_userdata']['zip'] == $db->f("zip")) {
								$_REQUEST['ship_to_info_id'] = $db->f('user_info_id');
								$ship_to_info_id = $db->f('user_info_id');
								break;
							}
						}
					}
				}
			}
		}
	 }
	
	
	/**
	 * Gets the User ID based on Username from Joomla
	 * Used internally in ppex_userLogin
	 * @param string $username
	 * @return int
	 */
	function ppex_getUserID($username)
	{
		global $vmLogger;
		
		if(empty($username))
		{
			$vmLogger->debug('Error: Cannot get user id without a username');
			return 0;
		}
		
		$db = new ps_DB();
		$dbb = new ps_DB();
		$q = "SELECT * FROM #__users WHERE username = '".$db->getEscaped($username)."'";
		$db->query($q);
		
		if($db->num_rows() > 0)
		{
			$db->next_record();
			$uid = $db->f('id');
			
			if(!empty($uid))
			{
				return $uid;
			}
		}	
		
		return 0;
	}
	
	/**
	 * Used to log the user into Virtuemart
	 * Meant for private use by this class
	 * @return bool
	 */
	function ppex_userLoginVirtuemart(&$auth)
	{
		global $vmLogger;
		
		if(!isset($_SESSION['ppex_userdata']['payer_id']))
		{
			$vmLogger->debug('Error: Cannot login without a payer id');
			return false;
		}
		
		$payerID = $_SESSION['ppex_userdata']['payer_id'];
		
		$db = new ps_DB();
		$dbb = new ps_DB();
		$q = "SELECT * FROM #__{vm}_user_info WHERE extra_field_3 = '".$db->getEscaped($payerID)."' ORDER by mdate DESC";
		$db->query($q);
		
		if($db->num_rows() > 0)
		{
			$db->next_record();
			$uid = $db->f('user_id');
			$email = $db->f('user_email');
			
			if(!empty($uid) && !empty($email))
			{
				$auth['user_id'] = $uid;
				$auth['username'] = $email;
				$_SESSION['auth'] = $auth;
				return true;
			}
		}	
		
		return false;
	}
	
	/**
	 * Logins the user into Virtuemart and Joomla if Possible
	 * Used incase the $payerID is associated with a non account register
	 * in Virtuemart. Otherwise the authentication plugin for Virtuemart PayPal
	 * will login via Joomla.
	 *
	 * @param string $payerID
	 * @return bool 
	 */
	function ppex_userLogin(&$auth)
	{
		global $mainframe, $vmLogger;
		
		if(!isset($_SESSION['ppex_userdata']['payer_id']))
		{
			$vmLogger->debug('Error: Cannot login without a payer id');
			return false;
		}
		
		$vmLogger->debug('Trying to Login...');
		$username = ps_paypal_api::ppex_getUsername($_SESSION['ppex_userdata']['payer_id']);
		
		$vmLogger->debug('Retrieved Username: '.$username);
		
		//If we have a username then try to login with it. Otherwise, login only in Virtuemart
		if($username !== false)
		{
			if(vmIsJoomla('1.5'))
			{
				$vmLogger->debug('Using Joomla Login');
				if($mainframe->login(array('username' => $username, 'password' => $_SESSION['ppex_userdata']['payer_id'])))
				{
					$auth['user_id'] = ps_paypal_api::ppex_getUserID($username);
					$auth['username'] = $username;
					$_SESSION['auth'] = $auth;
					return true;
				}
			}
			else
			{
				$vmLogger->debug('Joomla is not 1.5 - Trying to login to just Virtuemart');
				$auth['user_id'] = ps_paypal_api::ppex_getUserID($username);
				$auth['username'] = $username;
				$_SESSION['auth'] = $auth;
				return true;
			}
		}
		else
		{
			$vmLogger->debug('No Username Found - Trying to use Virtuemart Login');
			return ps_paypal_api::ppex_userLoginVirtuemart($auth);
		}

		return false;
	}
	
	/**
	 * Gets the username from joomla if there is one associated to the paypal express payerID
	 * @param string $payerID
	 * @return string, False on failure
	 */
	function ppex_getUsername($payerID)
	{
		global $vmLogger;
	
		if(empty($payerID))
		{	
			$vmLogger->debug("Error: No PayerID Given");
			return false;
		}
		
		$db = new ps_DB();
		$dbb = new ps_DB();
		$q = "SELECT * FROM #__{vm}_user_info WHERE extra_field_3 = '".$db->getEscaped($payerID)."' ORDER by mdate DESC";
		$db->query($q);
		
		if($db->num_rows() > 0)
		{
			while($db->next_record())
			{
				$uid = $db->f('user_id');
				
				//Now lets try and see if the uid has a real username with joomla
				$q2 = "SELECT * FROM #__users WHERE `id` = '".$db->getEscaped($uid)."'";
				$dbb->query($q2);
				
				if($dbb->num_rows() > 0)
				{
					$dbb->next_record();
					$username = $dbb->f('username');
					
					if(!empty($username)) return $username;
				}
			}
		}
		
		return false;
	}
	
   function ppex_getCheckoutDetails() {

        if( file_exists( CLASSPATH ."payment/".__CLASS__.".cfg.php")) {
			include_once(CLASSPATH ."payment/".__CLASS__.".cfg.php");
		}
		if(isset($_SESSION['ppex_token']) && !isset($_SESSION['ppex_userdata']))
		{
			$token =urlencode( $_SESSION['ppex_token'] );
			/* Build a second API request to PayPal, using the token as the
			ID to get the details on the payment authorization
			*/
			$nvpstr="&TOKEN=".$token;

			/* Make the API call and store the results in an array.  If the
			call was a success, show the authorization details, and provide
			an action to complete the payment.  If failed, show the error
			*/
			require_once(dirname(__FILE__).'/paypal_api/CallerService.php');
			$resArray=hash_call("GetExpressCheckoutDetails",$nvpstr);
			$db = new ps_DB();
			$country_code_num = strlen($resArray['SHIPTOCOUNTRYCODE']);
			if( $country_code_num == 2 ) {
			$db->query( "SELECT `country_3_code` FROM `#__{vm}_country` WHERE `country_2_code`='".$resArray['SHIPTOCOUNTRYCODE']."'");
				$db->next_record();
				$resArray['SHIPTOCOUNTRYCODE']=$db->f('country_3_code') ? $db->f('country_3_code') : $resArray['SHIPTOCOUNTRYCODE']; 
			}
			$country_code_num = strlen($resArray['COUNTRYCODE']);
			if( $country_code_num == 2 ) {
			$db->query( "SELECT `country_3_code` FROM `#__{vm}_country` WHERE `country_2_code`='".$resArray['COUNTRYCODE']."'");
				$db->next_record();
				$resArray['COUNTRYCODE']=$db->f('country_3_code') ? $db->f('country_3_code') : $resArray['COUNTRYCODE']; 
			}
			
			$d['country'] = $_REQUEST['country'] = $resArray['COUNTRYCODE'];
			$_SESSION['ppex_reshash']=$resArray;
			
			$_SESSION['ppex_userdata']['shiptoname']=$resArray['SHIPTONAME'];
			$_SESSION['ppex_userdata']['email']=$resArray['EMAIL'];
			$_SESSION['ppex_userdata']['company']=vmGet($resArray,'BUSINESS');
			$_SESSION['ppex_userdata']['first_name']=$resArray['FIRSTNAME'];
			$_SESSION['ppex_userdata']['last_name']=$resArray['LASTNAME'];
			$_SESSION['ppex_userdata']['middle_name']='';
			$_SESSION['ppex_userdata']['address_1']=$resArray['SHIPTOSTREET'];
			if (array_key_exists('SHIPTOSTREET2', $resArray)) {
			    $_SESSION['ppex_userdata']['address_2']=$resArray['SHIPTOSTREET2'];
			}
			else {
			    $_SESSION['ppex_userdata']['address_2'] = '';
			}
			$_SESSION['ppex_userdata']['city']=$resArray['SHIPTOCITY'];
			$_SESSION['ppex_userdata']['zip']=$resArray['SHIPTOZIP'];
			$_SESSION['ppex_userdata']['country']=$resArray['SHIPTOCOUNTRYCODE'];
			$_SESSION['ppex_userdata']['phone_1']='';
			$_SESSION['ppex_userdata']['state']='';
			if(!empty($resArray['SHIPTOSTATE']))
				$_SESSION['ppex_userdata']['state'] = $resArray['SHIPTOSTATE']; 
			if( !empty( $resArray['PHONENUM'])) {
				$_SESSION['ppex_userdata']['phone_1']=$resArray['PHONENUM'];
			}
			$_SESSION['ppex_userdata']['phone_2']='';
			$_SESSION['ppex_userdata']['fax']='';
			$_SESSION['ppex_userdata']['payer_id']= $resArray['PAYERID'];
			
			if(isset($resArray['REDIRECTREQUIRED'])) {
				$_SESSION['ppex_userdata']['redirectrequired'] = $resArray['REDIRECTREQUIRED'];
			}
			
			$_SESSION['ppex_userdata']['payerstatus'] = $resArray['PAYERSTATUS'];
		
			$ack = strtoupper($resArray["ACK"]);
			return $resArray;
		}
   }

   function ppex_getUser($resArray) {
   
        include_once(CLASSPATH ."payment/".__CLASS__.".cfg.php");
       // User is already logged in
       if ($perm->is_registered_customer($auth['user_id'])) {
           
       } else {
           // Check if user exists
           $db->query( "SELECT `user_id` FROM `#__users` WHERE `email`=".$resArray['EMAIL']."'");
           $db->next_record(); 
       }
   }
   
   //Gets the payment method id
   function getPaymentMethodId($classname='ps_paypal_api') {
       $db = new ps_DB();
       $db->query( "SELECT `payment_method_id` FROM `#__{vm}_payment_method` WHERE `payment_class`='$classname' AND `payment_enabled`='Y'");
       $db->next_record();
       $retval=$db->f('payment_method_id'); 
       return $retval;
   }
   
   //Gets the payment method name
   function getPaymentMethodName($classname='ps_paypal_api')
   {
       $db = new ps_DB();
       $db->query( "SELECT `payment_method_name` FROM `#__{vm}_payment_method` WHERE `payment_class`='$classname' AND `payment_enabled`='Y'");
       $db->next_record();
       $retval=$db->f('payment_method_name'); 
       return $retval;
   }
   
   //Checks to see if the payment method is active
   function isActive($classname='ps_paypal_api') {
       $db = new ps_DB();
       $db->query( "SELECT `payment_enabled`, `payment_method_id` FROM `#__{vm}_payment_method` WHERE `payment_class`='$classname'");
       $db->next_record();
       $retval=$db->f('payment_enabled'); 
       return $retval == 'Y';
   }
   
   //Createst the NVP string for all items in the cart
   function getCartnvpstr( &$order_totals=array() ) {
		global $auth, $VM_LANG;
		$cart = $_SESSION['cart'];
        require_once(CLASSPATH. 'ps_product.php' );
        $ps_product = new ps_product;
		
        $ret_str="";
        $item_total = 0;
        for ($i=0;$i<$cart["idx"];$i++) {
            // Product PRICE
            $price = $ps_product->get_adjusted_attribute_price($cart[$i]["product_id"], $cart[$i]["description"]);
			
			// Convert to product currency if necessary
            $product_price = $GLOBALS['CURRENCY']->convert( $price["product_price"], $price["product_currency"] );
			
            // SUBTOTAL CALCULATION
            $ret_str.="&L_AMT".$i."=".round($product_price,2);
            $ret_str.="&L_QTY".$i."=".$cart[$i]["quantity"];
            $ret_str.="&L_NAME".$i."=".urlencode($ps_product->get_field($_SESSION['cart'][$i]["product_id"], "product_name"));
            $item_total += round($product_price,2) * $cart[$i]["quantity"];
		}
		if( !empty($order_totals['coupon_discount'] ) ) {
			// Discount is the difference left after order total has been reduced by subtotal, tax, shipping and shipping tax
			$discount = round($order_totals['order_total'], 2)	
								- $item_total
								- round($order_totals['order_tax'], 2)
								- $order_totals['order_shipping']
								- $order_totals['order_shipping_tax'];
            // add discount as line item
            $ret_str.="&L_AMT".$i."=".round($discount,2);
            $ret_str.="&L_QTY".$i."=1";
            $ret_str.="&L_NAME".$i."=".urlencode($VM_LANG->_('PHPSHOP_COUPON_DISCOUNT'));
            
			$item_total += $discount;
		}
		$order_totals['item_total'] = round($item_total, 2);
		$ret_str.="&ITEMAMT=".round($item_total, 2);
		//die( $ret_str );
		return $ret_str;
   }
   
   function destroyPaypalSession($varname="ppex_") {
       $checklen=strlen($varname);
       if ($checklen>3) {
           foreach ($_SESSION as $key => $value) {
            if(substr($key,0,$checklen)===$varname) {
                $_SESSION[$key]=null;
                unset($_SESSION[$key]);   
            }
           }
       }
   }
   
   /*
    * Gets the paypal express token and does a redirect to paypal if successful
	* $ppex is the type of express checkout: 1 is the regular ECS and 2 is from the select payment
	* part of the checkout
	*/
   function gettoken($ppex=1, $order_total=0) {
		global $mainframe, $vendor_currency, $vars;
        include_once(CLASSPATH ."payment/".__CLASS__.".cfg.php");
		
	   require_once(dirname(__FILE__).'/paypal_api/CallerService.php');
	   require_once(CLASSPATH.'ps_checkout.php');
	   $serverName = $_SERVER['SERVER_NAME'];
	   $serverPort = $_SERVER['SERVER_PORT'];
		if ( $serverPort == 443 ) {
			$protocol = 'https://';
		} else {
			$protocol = 'http://';
		}
		$url = dirname($protocol . $serverName . ':' . $serverPort . $_SERVER['REQUEST_URI'] );

	 /* The returnURL is the location where buyers return when a
		payment has been succesfully authorized.
		The cancelURL is the location buyers are sent to when they hit the
		cancel button during authorization of payment during the PayPal flow
		*/
		$ship_to_info_id = vmGet( $_REQUEST, 'ship_to_info_id');
		$shipping_rate_id = urldecode(vmGet( $_REQUEST, "shipping_rate_id", null ));
		$payment_method_id = vmGet( $_REQUEST, 'payment_method_id');
		$checkout_this_step = ps_checkout::get_current_stage();
	   $returnURL =urlencode($url.'/index.php?page=checkout.index&option=com_virtuemart&checkout_stage='.$checkout_this_step.'&ship_to_info_id='.$ship_to_info_id.'&shipping_rate_id='.urlencode($shipping_rate_id).'&ppex_gecd='.$ppex);
	   
	   $lastpage = vmGet( $_SERVER, 'HTTP_REFERER' );
	   if( strpos( $lastpage, 'page=shop.cart') !== false ) {
			$cancelURL =urlencode($url.'/index.php?page=shop.cart&option=com_virtuemart&ppex_cancel=1');
	   } else {
			$cancelURL =urlencode($url.'/index.php?page=checkout.index&option=com_virtuemart&checkout_stage='.($checkout_this_step-1).'&ship_to_info_id='.$ship_to_info_id.'&shipping_rate_id='.$shipping_rate_id.'&ppex_cancel=1');
	   }
	   $gp_returnURL =urlencode($url.'/index.php?page=checkout.generic_result&option=com_virtuemart&result=success');
	   $gp_cancelURL =urlencode($url.'/index.php?page=checkout.generic_result&option=com_virtuemart&result=cancel');
	   $bank_pending_URL =urlencode($url.'/index.php?page=checkout.generic_result&option=com_virtuemart&result=pending' );

		require_once(CLASSPATH. 'ps_checkout.php' );
		$ps_checkout = new ps_checkout;
		$order_totals = $ps_checkout->calc_order_totals( $vars );
		
		$useshipping = PAYPAL_API_USE_SHIPPING;
		
	   $lang = jfactory::getLanguage();
		$lang_arr = explode( '-', $lang->gettag() );
		$localecode = strtoupper( $lang_arr[1] );

	   $nvpstr= "&CALLBACKTIMEOUT=4&CALLBACK=&ReturnUrl=".$returnURL
						."&CANCELURL=".$cancelURL 
						."&HDRIMG=".PAYPAL_API_IMAGEURL 
						."&GIROPAYCANCELURL=".$gp_cancelURL
						."&GIROPAYSUCCESSURL=".$gp_returnURL
						."&BANKTXNPENDINGURL=".$bank_pending_URL
						."&LOCALECODE=$localecode&CURRENCYCODE=$vendor_currency&PAYMENTACTION=".PAYPAL_API_PAYMENTTYPE;
		if( !empty( $ship_to_info_id) && $useshipping == '1') {
			$dbst = new ps_DB();
			$qt = "SELECT * FROM #__{vm}_user_info WHERE user_info_id='". $ship_to_info_id . "'";
			$dbst->query($qt);
			if( $dbst->next_record() ) {
				$db_new = new ps_DB;
				$db_new->query("SELECT `country_2_code` FROM `#__{vm}_country` WHERE `country_3_code`='" . substr($dbst->f("country"), 0, 60) . "'");
				$db_new->next_record();
				$nvpstr .= '&ADDROVERRIDE=1&SHIPTONAME='.urlencode($dbst->f('first_name').' ' . $dbst->f('last_name'))
							."&SHIPTOSTREET=".urlencode($dbst->f('address_1'))
							."&SHIPTOSTREET2=".urlencode($dbst->f('address_2'))
							."&SHIPTOCITY=".urlencode($dbst->f('city'))
							."&SHIPTOSTATE=".urlencode($dbst->f('state'))
							."&SHIPTOZIP=".urlencode($dbst->f('zip'))
							."&SHIPTOCOUNTRYCODE=".urlencode($db_new->f('country_2_code'));
			}
		}
		$nvpstr .= ps_paypal_api::getCartnvpstr( $order_totals );
		
		$amt = round($order_totals['order_total'], 2);
		$shippingamt = round($order_totals['order_shipping']+$order_totals['order_shipping_tax'],2);
		//$taxamt = $order_totals['order_tax'];
		// to avoid rounding issues, calculates tax as a differenct between total amount and other items
		$taxamt = $amt - $shippingamt - $order_totals['item_total'];
		
		$nvpstr .= 
						"&AMT=$amt"
						."&TAXAMT=$taxamt"
						."&SHIPPINGAMT=$shippingamt";
				
		
	 /* Make the call to PayPal to set the Express Checkout token
		If the API call succeded, then redirect the buyer to PayPal
		to begin to authorize payment.  If an error occured, show the
		resulting errors
		*/
	   $resArray=hash_call("SetExpressCheckout",$nvpstr);
	   
	   $_SESSION['reshash']=$resArray;
	   
	   $ack = strtoupper($resArray["ACK"]);
	   if($ack=="SUCCESS"){
				// Redirect to paypal.com here
				$token = urldecode($resArray["TOKEN"]);
				$_SESSION['ppex_token']=$token;
				$DOMAIN = PAYPAL_API_DEBUG == 1 ? 'www.sandbox.paypal.com' : 'www.paypal.com';
				if((int)$ppex == 2)
				{
					$payPalURL = 'https://'.$DOMAIN.'/webscr?cmd=_express-checkout&useraction=commit&token='.$token;
				}
				else
				{
					$payPalURL = 'https://'.$DOMAIN.'/webscr?cmd=_express-checkout&token='.$token;
				}
				header("Location: ".$payPalURL);
				$mainframe->close();
	   } else  {
			//Redirecting to APIError.php to display errors.
			$location = $url."/index.php?option=com_virtuemart&page=shop.cart&ppex_error=1";
			if( $_SERVER['SERVER_NAME'] == 'localhost' ) {
				echo "<pre>";print_r($resArray);echo "</pre>";
				echo "<p>".str_replace( "&", "<br />&",$nvpstr )."</p>";
			}
			$error = ps_paypal_api::NVP_ErrorToText( $resArray['L_ERRORCODE0'], 'setexpress' );
			if(  empty($error )) {
				 // Unkown PayPal Error
				$GLOBALS['vmLogger']->err( 'PayPal returned: '.$resArray['L_LONGMESSAGE0'] );
			} else {
				$GLOBALS['vmLogger']->err( $error  );
			}
			//header("Location: $location");
			//$mainframe->close();
	   }

       
   }
	//NVP DoExpressCheckout
	function NVP_DoExpressCheckout(&$d, $dbbt, $dbst, $order_total, $payment_action, $ordernum) {
		global $vendor_mail, $vendor_currency, $VM_LANG;
		
		//Check to make sure that we have the token from paypal and the paypal payer ID. Otherwise we return false.
		if(isset($_SESSION['ppex_token']) && isset($_SESSION['ppex_userdata']['payer_id'])) {
		
			$token = urlencode($_SESSION['ppex_token']);
			$payerID = urlencode($_SESSION['ppex_userdata']['payer_id']);
			
			//Gather all needed info to build the nvp request.
			$subject = urlencode('');
			$payer = urlencode($dbbt->f("user_email"));
			$first_name = urlencode(substr($dbbt->f("first_name"),0,50));
			$last_name = urlencode(substr($dbbt->f("last_name"), 0, 50));
			$currency_type = urlencode($GLOBALS['product_currency']);
			$ps_checkout = new ps_checkout();
			$order_totals = $ps_checkout->calc_order_totals( $d );
			

			$useshipping = PAYPAL_API_USE_SHIPPING;
			
			$db_new = new ps_DB;
			$db_new->query("SELECT `country_2_code` FROM `#__{vm}_country` WHERE `country_3_code`='" . substr($dbbt->f("country"), 0, 60) . "'");
			$db_new->next_record();

			$address_street1 = urlencode(substr($dbbt->f("address_1"), 0, 60));
			$address_city = urlencode(substr($dbbt->f("city"), 0, 40));
			$address_state = urlencode(substr($dbbt->f("state"), 0, 40));
			$address_country = urlencode($db_new->f("country_2_code"));
			$address_zip = urlencode(substr($dbbt->f("zip"), 0, 20));
			if( $dbbt->f("country") != $dbst->f("country") ) {
				$query_str = "SELECT `country_2_code` FROM `#__{vm}_country` WHERE `country_3_code`='" . substr($dbst->f("country"), 0, 60) . "'";
				$db_new->query($query_str);
				$db_new->next_record();
			}
			$ship_name = urlencode(trim(substr($dbst->f("first_name"), 0, 50).' '.substr($dbst->f("last_name"), 0, 50)));
			$ship_street1 = urlencode(substr($dbst->f("address_1"), 0, 60));
			$ship_street2 = urlencode(substr($dbst->f("address_2"), 0, 60));
			$ship_city = urlencode(substr($dbst->f("city"), 0, 40));
			$ship_state = urlencode(substr($dbst->f("state"), 0, 40));
			$ship_country = urlencode($db_new->f("country_2_code"));
			$ship_zip = urlencode(substr($dbst->f("zip"), 0, 20));
			
			//build the nvp request with all the data we have gathered.
			$nvpreq = "&TOKEN=$token&PAYERID=$payerID&PAYMENTACTION=$payment_action&IPADDRESS=".$_SERVER['REMOTE_ADDR'];

			if(isset($_SESSION['ppex_cart_ecm']))
			{
				$buttonSource = "Virtuemart_Cart_ECM";
			}
			else
			{
				$buttonSource = "Virtuemart_Cart_ECS";
			}
			
			$nvpreq .= '&NOTIFYURL='.urlencode(SECUREURL .'administrator/components/com_virtuemart/notify.php');
			$nvpreq .= "&CURRENCYCODE=$currency_type&DESC=$subject&INVNUM=$ordernum&BUTTONSOURCE=$buttonSource";
		
			$nvpreq .= ps_paypal_api::getCartnvpstr( $order_totals );
			
			$amt = round($order_totals['order_total'], 2);
			$shippingamt = round($order_totals['order_shipping']+$order_totals['order_shipping_tax'],2);
			$taxamt = $amt - $order_totals['item_total'] - $shippingamt;
			$nvpreq .= 
						"&AMT=$amt"
						."&TAXAMT=$taxamt"
						."&SHIPPINGAMT=$shippingamt";
			
			if($useshipping == '1') {
				$nvpreq .= "&SHIPTONAME=$ship_name"
								."&SHIPTOSTREET=$ship_street1"
								."&SHIPTOSTREET2=$ship_street2"
								."&SHIPTOCITY=$ship_city"
								."&SHIPTOSTATE=$ship_state"
								."&SHIPTOZIP=$ship_zip"
								."&SHIPTOCOUNTRYCODE=$ship_country";
			}
			
			return $nvpreq;
		}
		else {
			return false;
		}
	}

	//NVP DoDirectPayment Request
	function NVP_DoDirectPaymentRequest(&$d, $dbbt, $dbst, $order_total, $payment_action, $ordernum, $requireCVV){
		global $vendor_mail, $vendor_currency, $VM_LANG;
		
		if(isset($_SESSION['ccdata']['order_payment_number'])) {
		
			$cc_first_digit = substr($_SESSION['ccdata']['order_payment_number'], 0, 1);
			$cc_first_2_digits = substr($_SESSION['ccdata']['order_payment_number'], 0, 2);

				// Figure out the card type.
				switch ($cc_first_digit) {
				 case "4" : $cc_type = urlencode("Visa");
							break;
				 case "5" : $cc_type = urlencode("MasterCard");
							break;
				 case "3" :
					switch ($cc_first_2_digits) {
						case "34" : $cc_type = urlencode("Amex");
									break;
						case "37" : $cc_type = urlencode("Amex");
									break;
						case "30" : $cc_type = urlencode("Discover");
									break;
						case "36" : $cc_type = urlencode("Discover");
									break;
						case "38" : $cc_type = urlencode("Discover");
									break;
						default : return false;
									break;
					}
					break;
				 case "6" : $cc_type = urlencode("Discover");
					break;
				 default : return false;
							break;
				}

			//Gather all required data	
			
			//Remove any dashes or spaces in the credit card number
			$tmp_number = str_replace('-', '', $_SESSION['ccdata']['order_payment_number']);
			$tmp_number = str_replace(' ', '', $tmp_number);
			
			$cc_number = urlencode($tmp_number);
			if(isset($_SESSION['ccdata']['credit_card_code'])) 	{
				$cc_cvv2 = urlencode($_SESSION['ccdata']['credit_card_code']);
			}
			else 	{
				if($requireCVV == 'YES') {
					return false;
				}
			}
			$cc_expires_month = $_SESSION['ccdata']['order_payment_expire_month'];
			$cc_expires_year = $_SESSION['ccdata']['order_payment_expire_year'];
			//$cc_owner = ($_SESSION['ccdata']['order_payment_name']);

			//$cc_first = urlencode(substr($cc_owner, 0,(strrpos($cc_owner, " "))));
			//$cc_last = urlencode(substr($cc_owner,(strrpos($cc_owner, ' ') + 1),strlen($cc_owner)));
			$cc_expDate = urlencode($cc_expires_month.$cc_expires_year);
			
			$subject = urlencode('');
			$payer = urlencode($dbbt->f("user_email"));
			$first_name = urlencode(substr($dbbt->f("first_name"),0,50));
			$last_name = urlencode(substr($dbbt->f("last_name"), 0, 50));
			$currency_type = $GLOBALS['product_currency'];
			
			$ps_checkout = new ps_checkout();
			$order_totals = $ps_checkout->calc_order_totals( $d );
			
			$tax_total = round($d['order_tax'],2);
			
			$ship_total = isset($d['shipping_total']) ? round($d['shipping_total'],2) : 0;

			$useshipping = PAYPAL_API_USE_SHIPPING;
			
			$db_new = new ps_DB;
			$query_str = "SELECT * FROM #__{vm}_country WHERE country_3_code='" . substr($dbbt->f("country"), 0, 60) . "'";
			$db_new->setQuery($query_str);
			$db_new->query();
			$db_new->next_record();

			$address_street1 = urlencode(substr($dbbt->f("address_1"), 0, 60));
			$address_city = urlencode(substr($dbbt->f("city"), 0, 40));
			$address_state = urlencode(substr($dbbt->f("state"), 0, 40));
			$address_country = urlencode($db_new->f("country_2_code"));
			$address_zip = urlencode(substr($dbbt->f("zip"), 0, 20));

			$query_str = "SELECT * FROM #__{vm}_country WHERE country_3_code='" . substr($dbst->f("country"), 0, 60) . "'";
			$db_new->setQuery($query_str);
			$db_new->query();
			$db_new->next_record();

			$ship_name = urlencode(trim(substr($dbst->f("first_name"), 0, 50).' '.substr($dbst->f("last_name"), 0, 50)));
			$ship_street1 = urlencode(substr($dbst->f("address_1"), 0, 60));
			$ship_street2 = urlencode(substr($dbst->f("address_2"), 0, 60));
			$ship_city = urlencode(substr($dbst->f("city"), 0, 40));
			$ship_state = urlencode(substr($dbst->f("state"), 0, 40));
			$ship_country = urlencode($db_new->f("country_2_code"));
			$ship_zip = urlencode(substr($dbst->f("zip"), 0, 20));
			
			//Begin putting together our NVP Request
			$nvpreq = "&PAYMENTACTION=$payment_action"
							."&IPADDRESS=".$_SERVER['REMOTE_ADDR']
							."&CREDITCARDTYPE=$cc_type"
							."&ACCT=$cc_number"
							."&EXPDATE=$cc_expDate"
							."&EMAIL=$payer"
							."&FIRSTNAME=$first_name"
							."&LASTNAME=$last_name";
			
			if($requireCVV == 'YES') {
				if(isset($cc_cvv2)) 	{
					$nvpreq .= "&CVV2=$cc_cvv2";
				}
				else {
					return false;
				}
			}
			
			$nvpreq .= "&STREET=$address_street1"
							."&CITY=$address_city"
							."&STATE=$address_state"
							."&COUNTRYCODE=$address_country"
							."&ZIP=$address_zip"
							."&SHIPPINGAMT=$ship_total";
			
			$nvpreq .= "&CURRENCYCODE=$currency_type&TAXAMT=$tax_total&DESC=$subject&INVNUM=$ordernum&BUTTONSOURCE=Virtuemart_Cart_DP";
					
			$nvpreq .= ps_paypal_api::getCartnvpstr( $order_totals );
			
			//Put together Shipping NVP request
			$nvpreq .= "&AMT=$order_total";
			
			
			if($useshipping == '1') {
				$nvpreq .= "&SHIPTONAME=$ship_name"
							."&SHIPTOSTREET=$ship_street1"
							."&SHIPTOSTREET2=$ship_street2"
							."&SHIPTOCITY=$ship_city"
							."&SHIPTOSTATE=$ship_state"
							."&SHIPTOZIP=$ship_zip"
							."&SHIPTOCOUNTRYCODE=$ship_country";
			}
			
			//return response to ps_paypal_wpp.php
			return $nvpreq;
		}
		else
		{
			return false;
		}
}

	//Request Transaction Details
	function NVP_TransactionDetails($transID) {	
		//very simple nvp request to get the transaction details of a previous purchase.
		$nvpreq = "&TRANSACTIONID=$transID";
		
		$nvpRES = hash_call("GetTransactionDetails",$nvpreq);
		
		$ack = strtoupper($nvpRES["ACK"]);
		
		//Return our response array if the command was succesful.
		if($ack!="SUCCESS" && $ack!="SUCCESSWITHWARNING")  
		{
			return false;
		}
		else
		{
			return $nvpRES;
		}
		
	}
	function NVP_ErrorToText($errorCode, $type) {
		$lang = jfactory::getLanguage();
		$name= $lang->getBackwardLang();
		if( file_exists(CLASSPATH ."payment/paypal_api/languages/lang.".$name.".php")) {
			include(CLASSPATH ."payment/paypal_api/languages/lang.".$name.".php");
		} else {
			include(CLASSPATH ."payment/paypal_api/languages/lang.english.php");
		}
		$errorText = '';
		if($errorCode != "" && $type != "")
		{
			switch($type)
			{
				case 'setexpress':
					
					switch($errorCode)
					{
						case '10411':
							return $nvp_error_10411;
							break;
						case '10415':
							return $nvp_error_10415;
							break;
						default:
							return false;
							break;
					}
					break;
				case 'getexpress':
					switch($errorCode)
					{
						case '10411':
							return $nvp_error_10411;
							break;
						case '10415':
							return $nvp_error_10415;
							break;
						case '10416':
							return $nvp_error_10416;
							break;
						default:
							return false;
							break;					
					}
					break;
				case 'doexpress':
					switch($errorCode) {
						case '10411':
							return $nvp_error_10411;
							break;
						case '10415':
							return $nvp_error_10415;
							break;
						case '10416':
							return $nvp_error_10416;
							break;
						case '10417':
							return $nvp_error_10417;
							break;
						case '10422':
							return $nvp_error_10422;
							break;
						case '10445':
							return $nvp_error_10445;
							break;
						default:
							return false;
							break;					
					}
					break;
				case 'dodirect':
					switch($errorCode) {
						case '10502':
							return $nvp_error_10502;
							break;
						case '10504':
							return $nvp_error_10504;
							break;
						case '10508':
							return $nvp_error_10508;
							break;
						case '10510':
							return $nvp_error_10510;
							break;
						case '10519':
							return $nvp_error_10519;
							break;
						case '10521':
							return $nvp_error_10521;
							break;
						case '10527':
							return $nvp_error_10527;
							break;
						case '10534':
							return $nvp_error_10534;
							break;
						case '10535':
							return $nvp_error_10535;
							break;
						case '10541':
							return $nvp_error_10541;
							break;
						case '10562':
							return $nvp_error_10562;
							break;
						case '10563':
							return $nvp_error_10563;
							break;
						case '10566':
							return $nvp_error_10566;
							break;
						case '10567':
							return $nvp_error_10567;
							break;
						case '10748':
							return $nvp_error_10748;
							break;
						case '10756':
							return $nvp_error_10756;
							break;
						case '10759':
							return $nvp_error_10759;
							break;
						case '15001':
							return $nvp_error_15001;
							break;
						case '15004':
							return $nvp_error_15004;
							break;
						case '15006':
							return $nvp_error_15006;
							break;
						case '15005':
							return $nvp_error_15005;
							break;
						case '15007':
							return $nvp_error_15007;
							break;
						default:
							return false;
							break;
					}
					break;
				default:
					return false;
					break;
			}
		}
		else
		{
			return false;
		}
	}
}
