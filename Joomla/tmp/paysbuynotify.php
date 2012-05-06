<?php
   if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
	if ($_POST) {
    header("Status: 200 OK");
    define('_VALID_MOS', '1');
    global $mosConfig_absolute_path, $mosConfig_live_site, $mosConfig_lang, $database,
    $mosConfig_mailfrom, $mosConfig_fromname;
    
    /*** access Joomla's configuration file ***/
        $my_path = dirname(__FILE__);
        
        if( file_exists($my_path."/../../../configuration.php")) {
            require_once($my_path."/../../../configuration.php");
        }
        elseif( file_exists($my_path."/../../configuration.php")){
            require_once($my_path."/../../configuration.php");
        }
        elseif( file_exists($my_path."/configuration.php")){
            require_once( $my_path."/configuration.php" );
        }
        else
            die( "Joomla Configuration File not found!" );
        
        include_once( $my_path.'/compat.joomla1.5.php' );
        
        if( class_exists( 'jconfig')) {
			define( '_JEXEC', 1 );
			define('JPATH_BASE', $mosConfig_absolute_path );
			
			require_once ( JPATH_BASE .'/includes/defines.php' );
			require_once ( JPATH_BASE .'/includes/application.php' );
			require_once ( JPATH_BASE. '/includes/database.php');
			// create the mainframe object
			$mainframe = new JSite();
			
			// set the configuration
			$mainframe->setConfiguration(JPATH_CONFIGURATION . DS . 'configuration.php');
			
			// load system plugin group
			JPluginHelper::importPlugin( 'system' );
			
			// trigger the onStart events
			$mainframe->triggerEvent( 'onBeforeStart' );
			
			// create the session
			$mainframe->setSession( $mainframe->getCfg('live_site').$mainframe->getClientId() );
			$database =& JFactory::getDBO();
        }
        else {
        	
        	require_once($mosConfig_absolute_path. '/includes/database.php');
        	$database = new database( $mosConfig_host, $mosConfig_user, $mosConfig_password, $mosConfig_db, $mosConfig_dbprefix );
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
        require_once( CLASSPATH. 'ps_main.php');
        
		require_once( CLASSPATH. "language.class.php" );
		require_once(CLASSPATH."Log/Log.php");
		$vmLoggerConf = array(
			'buffering' => true
			);
		/**
		 * This Log Object will help us log messages and errors
		 * See http://pear.php.net/package/Log
		 * @global Log vmLogger
		 */
		$vmLogger = &vmLog::singleton('display', '', '', $vmLoggerConf, PEAR_LOG_TIP);
		$GLOBALS['vmLogger'] =& $vmLogger;
		        /* load the VirtueMart Language File */
        if (file_exists( ADMINPATH. 'languages/'.$mosConfig_lang.'.php' ))
          require_once( ADMINPATH. 'languages/'.$mosConfig_lang.'.php' );
        else
          require_once( ADMINPATH. 'languages/english.php' );
		          /* Load the VirtueMart database class */
        require_once( CLASSPATH. 'ps_database.php' );
			    // restart session
	    require_once(CLASSPATH."ps_session.php");
	
	    // Constructor initializes the session!
	    $sess = new ps_session();                
			    // Include globals; for this, $db is needed, as is htmlTools.class.php
	    $db = new ps_DB;
	    require_once( CLASSPATH. 'htmlTools.class.php' );
	    require_once( ADMINPATH. 'global.php' );
    /*** END VirtueMart part ***/

    $result  =  trim(stripslashes($_POST['result']));
    $amt =  trim(stripslashes(@$_POST['amt']));
if(gethostbyname('paysbuy.com')==$_SERVER['REMOTE_ADDR']){
if(substr($result,0,2) == "00"){
	$invoice = substr($result,2,strlen($result)-2);
	  $qv = "SELECT `order_id`, `order_number`, `user_id`, `order_subtotal`,
                    `order_total`, `order_currency`, `order_tax`, 
                    `order_shipping_tax`, `coupon_discount`, `order_discount`
                FROM `#__{vm}_orders` 
                WHERE `order_number`='".$invoice."'";
      $db = new ps_DB;
      $db->query($qv);
      $db->next_record();
      $order_id = $db->f("order_id");
      $d['order_id'] = $order_id;
      $d['notify_customer'] = "Y";
	  $amount_check = $db->f("order_total");
	  if($amount_check == $amt){
                     if(eregi ("00", $result)) {
                    $d['order_status'] = "Comfirmed";           
		            require_once ( CLASSPATH . 'ps_order.php' );
					$ps_order= new ps_order;
                    $ps_order->order_status_update($d);
					  }
					  }
                }else{
                    $d['order_status'] = "Cancelled";           
		            require_once ( CLASSPATH . 'ps_order.php' );
					$ps_order= new ps_order;
                    $ps_order->order_status_update($d);
				}
}else{
	echo "IP INCORRECT";
}
	?>