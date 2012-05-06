<?php 
/**
* PayPal IPN Handler
*
* @version $Id: ipayment_notify.php 1675 2009-03-04 19:29:03Z soeren_nb $
* @package VirtueMart
* @subpackage core
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

	
if ($_POST) {
	header("HTTP/1.0 200 OK");

    global $mosConfig_absolute_path, $mosConfig_live_site, $mosConfig_lang, $database,
    $mosConfig_mailfrom, $mosConfig_fromname;
    
        /*** access Joomla's configuration file ***/
        $my_path = dirname(__FILE__);
        
        if( file_exists($my_path."/../../../configuration.php")) {
            $absolute_path = dirname( $my_path."/../../../configuration.php" );
            require_once($my_path."/../../../configuration.php");
        }
        elseif( file_exists($my_path."/../../configuration.php")){
            $absolute_path = dirname( $my_path."/../../configuration.php" );
            require_once($my_path."/../../configuration.php");
        }
        elseif( file_exists($my_path."/configuration.php")){
            $absolute_path = dirname( $my_path."/configuration.php" );
            require_once( $my_path."/configuration.php" );
        }
        else {
            die( "Joomla Configuration File not found!" );
        }
        
        $absolute_path = realpath( $absolute_path );
        
        // Set up the appropriate CMS framework
        if( class_exists( 'jconfig' ) ) {
			define( '_JEXEC', 1 );
			define( 'JPATH_BASE', $absolute_path );
			define( 'DS', DIRECTORY_SEPARATOR );
			
			// Load the framework
			require_once ( JPATH_BASE . DS . 'includes' . DS . 'defines.php' );
			require_once ( JPATH_BASE . DS . 'includes' . DS . 'framework.php' );

			// create the mainframe object
			$mainframe = & JFactory::getApplication( 'site' );
			
			// Initialize the framework
			$mainframe->initialise();
			
			// load system plugin group
			JPluginHelper::importPlugin( 'system' );
			
			// trigger the onBeforeStart events
			$mainframe->triggerEvent( 'onBeforeStart' );
			$lang =& JFactory::getLanguage();
			$mosConfig_lang = $GLOBALS['mosConfig_lang']          = strtolower( $lang->getBackwardLang() );
			// Adjust the live site path
			$mosConfig_live_site = str_replace('/administrator/components/com_virtuemart', '', JURI::base());
			$mosConfig_absolute_path = JPATH_BASE;
        } else {
        	define('_VALID_MOS', '1');
        	require_once($mosConfig_absolute_path. '/includes/joomla.php');
        	require_once($mosConfig_absolute_path. '/includes/database.php');
        	$database = new database( $mosConfig_host, $mosConfig_user, $mosConfig_password, $mosConfig_db, $mosConfig_dbprefix );
        	$mainframe = new mosMainFrame($database, 'com_virtuemart', $mosConfig_absolute_path );
        }

        // load Joomla Language File
        if (file_exists( $mosConfig_absolute_path. '/language/'.$mosConfig_lang.'.php' )) {
            require_once( $mosConfig_absolute_path. '/language/'.$mosConfig_lang.'.php' );
        }
        elseif (file_exists( $mosConfig_absolute_path. '/language/english.php' )) {
            require_once( $mosConfig_absolute_path. '/language/english.php' );
        }
    /*** END of Joomla config ***/
    
    
    /*** VirtueMart part ***/        
        require_once($mosConfig_absolute_path.'/administrator/components/com_virtuemart/virtuemart.cfg.php');
        include_once( ADMINPATH.'/compat.joomla1.5.php' );
        require_once( ADMINPATH. 'global.php' );
        require_once( CLASSPATH. 'ps_main.php' );
        
        /* @MWM1: Logging enhancements (file logging & composite logger). */
        $vmLogIdentifier = "ipayment_notify.php";
        require_once(CLASSPATH."Log/LogInit.php");
              
        /* Load the PayPal Configuration File */ 
        require_once( CLASSPATH. 'payment/ps_ipayment.cfg.php' );
        
		$debug_email_address = $vendor_mail;
		
	    // restart session
	    // Constructor initializes the session!
	    $sess = new ps_session();                        
	    
    /*** END VirtueMart part ***/
    
    // Finished Initialization of the hidden_trigger script
 
   	// Check for valid ipayment Server
	if (! preg_match('/\.ipayment\.de$/',
	  gethostbyaddr($_SERVER["REMOTE_ADDR"]))) {
	            
           $mailsubject = "iPayment Transaction on your site: Possible fraud";
           $mailbody = "Error code 506. Possible fraud. Error with REMOTE IP ADDRESS = ".$_SERVER['REMOTE_ADDR'].". 
                       The remote address of the script posting to this notify script does not match a valid iPayment Server IP Address\n
                      
           The Order ID received was: ".vmRequest::getVar('shopper_id');
           vmMail( $mosConfig_mailfrom, $mosConfig_fromname, $debug_email_address, $mailsubject, $mailbody );
           
           exit();
	}
    $order_number = vmRequest::getString('shopper_id');
	if( !empty($order_number) ) {
		$db = new ps_DB;
		// Get the Order Details from the database      
 		$qv = "SELECT `order_id`, `order_number`, `user_id`, `order_subtotal`,
               `order_total`, `order_currency`, `order_tax`, 
               `order_shipping_tax`, `coupon_discount`, `order_discount`
           FROM `#__{vm}_orders` 
           WHERE `order_number`='".$db->getEscaped($order_number)."'";
 
 		$db->query($qv);
 		if( !$db->next_record() ) exit;
 	
 		// Now check, if everything's alright here
 		$ret_param_checksum = vmRequest::getVar('ret_param_checksum');
 	
 		$ret_param_checksum_computed = md5(
 	                                           IPAYMENT_APPID
								. round($db->f('order_total')*100,0) 
								. $db->f('order_currency') 
								. vmRequest::getVar('ret_authcode') 
								. vmRequest::getVar('ret_booknr')
								. IPAYMENT_SECRET
 											);
 		if( $ret_param_checksum != $ret_param_checksum_computed ) {
 			$mailsubject = "iPayment Transaction on your site: Checksum mismatch!";
      		$mailbody = "When receiving a request from an iPayment Server we found that no correct checksum was submitted.
                 
      The Order ID received was: ".vmRequest::getVar('shopper_id');
	      	vmMail( $mosConfig_mailfrom, $mosConfig_fromname, $debug_email_address, $mailsubject, $mailbody );
	      
	      	exit();
	 	}
	 	$order_id = $db->f("order_id");
	
	 	$d['order_id'] = $order_id;
	 	$d['notify_customer'] = "Y";

 
	 //-------------------------------------------
	 // ...read the results of the verification...
	 // If SUCCESS = continue to process the TX...
	 //-------------------------------------------
	   if( vmRequest::getVar('ret_status') == 'SUCCESS' ) {
	       //----------------------------------------------------------------------
	       // If the payment_status is Completed... Get the ID for the product
	       // from the DB and email it to the customer.
	       //----------------------------------------------------------------------
	           
	       $d['order_status'] = IPAYMENT_VERIFIED_STATUS;                    
	       
	       require_once ( CLASSPATH . 'ps_order.php' );
	       $ps_order= new ps_order;
	       $ps_order->order_status_update($d);
	       $mailsubject = "iPayment Transaction on your Store";
	       $mailbody = "Hello,\n\n";
	       $mailbody .= "an iPayment transaction for you has been made on your website!\n";
	       $mailbody .= "-----------------------------------------------------------\n";
	       $mailbody .= "Transaction ID: ".vmRequest::getString('ret_trx_number')."\n";
	       $mailbody .= "Order ID: $order_id\n";
	       $mailbody .= "Order Status Code: ".$d['order_status'];
	       vmMail($mosConfig_mailfrom, $mosConfig_fromname, $debug_email_address, $mailsubject, $mailbody );
	       
	       exit;
	   }
	}

      
	$mailsubject = "iPayment Transaction on your Site";
	$mailbody = "Hello,
	an error occured while processing a iPayment transaction.
	";
	vmMail($mosConfig_mailfrom, $mosConfig_fromname, $debug_email_address, $mailsubject, $mailbody );
     
}