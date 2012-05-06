<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: ps_ipayment.php 1675 2009-03-04 19:29:03Z soeren_nb $
* @package VirtueMart
* @subpackage payment
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

/**
* This class implements the configuration panel for paypal
* If you want to change something "internal", you must modify the 'payment extra info'
* in the payment method form of the PayPal payment method
*/
class ps_ipayment {
    
    /**
    * Show all configuration parameters for this payment method
    * @returns boolean False when the Payment method has no configration
    */
    function show_configuration() {
        global $VM_LANG;
        $db = new ps_DB();
        
        /** Read current Configuration ***/
        include_once(CLASSPATH ."payment/".__CLASS__.".cfg.php");
    ?>
    <table class="adminform">
        <tr class="row0">
        <td><strong>Account/Händler ID</strong></td>
            <td>
                <input name="IPAYMENT_ACCOUNTID" class="inputbox" type="text" value="<?php echo IPAYMENT_ACCOUNTID ?>" />
            </td>
            <td>
           <?php echo vmToolTip( 'Ihre allgemeine Händler-ID (die Sie auch zum Login in den Administratorbereich bei iPayment verwenden)' ) ?>       
            </td>
        </tr>
        <tr class="row1">
        <td><strong>Anwendungs-ID/ User-ID </strong></td>
            <td>
                <input type="text" name="IPAYMENT_APPID" class="inputbox" value="<?php  echo IPAYMENT_APPID ?>" />
            </td>
            <td>
            </td>
        </tr>
        <tr class="row0">
        <td><strong>Anwendungs-Passwort/ Transaktionsuser-Passwort</strong></td>
            <td>
                <input type="text" name="IPAYMENT_APP_PASSWORD" class="inputbox" value="<?php  echo IPAYMENT_APP_PASSWORD ?>" />
            </td>
            <td>
            </td>
        </tr>
        <tr class="row1">
        <td><strong>Aktions-Passwort</strong></td>
            <td>
                <input type="text" name="IPAYMENT_ACTION_PASSWORD" class="inputbox" value="<?php  echo IPAYMENT_ACTION_PASSWORD ?>" />
            </td>
            <td>
            </td>
        </tr>
        <tr class="row0">
        <td><strong>Transaktions-Security-Key</strong></td>
            <td>
                <input type="text" name="IPAYMENT_SECRET" class="inputbox" value="<?php  echo IPAYMENT_SECRET ?>" />
            </td>
            <td>
            </td>
        </tr>
        <tr class="row1">
            <td><strong><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_PAYPAL_STATUS_SUCCESS') ?></strong></td>
            <td>
                <select name="IPAYMENT_VERIFIED_STATUS" class="inputbox" >
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
                      if (IPAYMENT_VERIFIED_STATUS == $order_status_code[$i]) 
                         echo "\" selected=\"selected\">";
                      else
                         echo "\">";
                      echo $order_status_name[$i] . "</option>\n";
                    }?>
                    </select>
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_PAYPAYL_STATUS_SUCCESS_EXPLAIN') ?>
            </td>
        </tr>
        <tr class="row0">
            <td><strong><?php echo $VM_LANG->_('VM_ADMIN_CFG_PAYPAL_STATUS_PENDING') ?></strong></td>
            <td>
                <select name="IPAYMENT_PENDING_STATUS" class="inputbox" >
                <?php
                    for ($i = 0; $i < sizeof($order_status_code); $i++) {
                      echo "<option value=\"" . $order_status_code[$i];
                      if (IPAYMENT_PENDING_STATUS == $order_status_code[$i]) 
                         echo "\" selected=\"selected\">";
                      else
                         echo "\">";
                      echo $order_status_name[$i] . "</option>\n";
                    } ?>
                    </select>
            </td>
            <td><?php echo $VM_LANG->_('VM_ADMIN_CFG_PAYPAL_STATUS_PENDING_EXPLAIN') ?></td>
        </tr>
       
        <tr class="row1">
            <td><strong><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_PAYPAL_STATUS_FAILED') ?></strong></td>
            <td>
                <select name="IPAYMENT_INVALID_STATUS" class="inputbox" >
                <?php
                    for ($i = 0; $i < sizeof($order_status_code); $i++) {
                      echo "<option value=\"" . $order_status_code[$i];
                      if (IPAYMENT_INVALID_STATUS == $order_status_code[$i]) 
                         echo "\" selected=\"selected\">";
                      else
                         echo "\">";
                      echo $order_status_name[$i] . "</option>\n";
                    } ?>
                    </select>
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_PAYPAL_STATUS_FAILED_EXPLAIN') ?>
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
      
      $my_config_array = array(
                              "IPAYMENT_ACCOUNTID" => $d['IPAYMENT_ACCOUNTID'],
                              "IPAYMENT_APPID" => $d['IPAYMENT_APPID'],
                              "IPAYMENT_APP_PASSWORD" => $d['IPAYMENT_APP_PASSWORD'],
                              "IPAYMENT_ACTION_PASSWORD" => $d['IPAYMENT_ACTION_PASSWORD'],
                              "IPAYMENT_SECRET" => $d['IPAYMENT_SECRET'],      
                              "IPAYMENT_VERIFIED_STATUS" => $d['IPAYMENT_VERIFIED_STATUS'],
                              "IPAYMENT_PENDING_STATUS" => $d['IPAYMENT_PENDING_STATUS'],
                              "IPAYMENT_INVALID_STATUS" => $d['IPAYMENT_INVALID_STATUS']
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
        return true;
    }
   
}
