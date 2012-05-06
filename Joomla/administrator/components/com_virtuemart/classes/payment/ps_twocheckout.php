<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: ps_twocheckout.php 1751 2009-05-01 06:32:33Z macallf $
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
* The ps_twocheckout class for transactions with 2Checkout 
 */
class ps_twocheckout {

    var $payment_code = "TWOCO";
    var $classname = "ps_twocheckout";
  
    /**
    * Show all configuration parameters for this payment method
    * @returns boolean False when the Payment method has no configration
    */
    function show_configuration() { 
    
      global $VM_LANG;
      $database = new ps_DB();
      /** Read current Configuration ***/
      require_once(CLASSPATH ."payment/".$this->classname.".cfg.php");
    ?>
      <table>
        <tr>
            <td><strong><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_2CHECKOUT_LOGIN') ?></strong></td>
            <td>
                <input type="text" name="TWOCO_LOGIN" class="inputbox" value="<?php echo TWOCO_LOGIN ?>" />
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_2CHECKOUT_LOGIN_EXPLAIN') ?></td>
        </tr>
        <tr>
            <td><strong><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_2CHECKOUT_SECRETWORD') ?></strong></td>
            <td>
                <input type="text" name="TWOCO_SECRETWORD" class="inputbox" value="<?php echo TWOCO_SECRETWORD ?>" />
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_2CHECKOUT_SECRETWORD_EXPLAIN') ?></td>
        </tr>
        <tr>
            <td><strong><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_PAYMENT_ORDERSTATUS_SUCC') ?></strong></td>
            <td>
                <select name="TWOCO_VERIFIED_STATUS" class="inputbox" >
                <?php
                    $q = "SELECT order_status_name,order_status_code FROM #__{vm}_order_status ORDER BY list_order";
                    $database->query($q);
                    $rows = $database->record;
                    $order_status_code = Array();
                    $order_status_name = Array();
                    
                    foreach( $rows as $row ) {
                      $order_status_code[] = $row->order_status_code;
                      $order_status_name[] =  $row->order_status_name;
                    }
                    for ($i = 0; $i < sizeof($order_status_code); $i++) {
                      echo "<option value=\"" . $order_status_code[$i];
                      if (TWOCO_VERIFIED_STATUS == $order_status_code[$i]) 
                         echo "\" selected=\"selected\">";
                      else
                         echo "\">";
                      echo $order_status_name[$i] . "</option>\n";
                    }?>
                    </select>
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_PAYMENT_ORDERSTATUS_SUCC_EXPLAIN') ?>
            </td>
        </tr>
            <tr>
            <td><strong><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_PAYMENT_ORDERSTATUS_FAIL') ?></strong></td>
            <td>
                <select name="TWOCO_INVALID_STATUS" class="inputbox" >
                <?php
                    for ($i = 0; $i < sizeof($order_status_code); $i++) {
                      echo "<option value=\"" . $order_status_code[$i];
                      if (TWOCO_INVALID_STATUS == $order_status_code[$i]) 
                         echo "\" selected=\"selected\">";
                      else
                         echo "\">";
                      echo $order_status_name[$i] . "</option>\n";
                    } ?>
                    </select>
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_PAYMENT_ORDERSTATUS_FAIL_EXPLAIN') ?>
            </td>
        </tr>
        <tr>
            <td><strong><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_2CHECKOUT_MERCHANT_NOTIF') ?></strong></td>
            <td>
                <select name="TWOCO_MERCHANT_EMAIL" class="inputbox" >
                  <option <?php if (TWOCO_MERCHANT_EMAIL == 'True') echo "selected=\"selected\""; ?> value="True"><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_YES') ?></option>
                  <option <?php if (TWOCO_MERCHANT_EMAIL == 'False') echo "selected=\"selected\""; ?> value="False"><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_NO') ?></option>
                </select>
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_2CHECKOUT_MERCHANT_NOTIF_EXPLAIN') ?></td>
        </tr>
        <tr>
            <td><strong><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_ENABLE_AUTORIZENET_TESTMODE') ?></strong></td>
            <td>
                <select name="TWOCO_TESTMODE" class="inputbox" >
                  <option <?php if (TWOCO_TESTMODE == 'Y') echo "selected=\"selected\""; ?> value="Y"><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_YES') ?></option>
                  <option <?php if (TWOCO_TESTMODE == 'N') echo "selected=\"selected\""; ?> value="N"><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_NO') ?></option>
                </select>
            </td>
            <td><?php echo $VM_LANG->_('PHPSHOP_ADMIN_CFG_ENABLE_AUTORIZENET_TESTMODE_EXPLAIN') ?></td>
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
      return is_writeable( CLASSPATH."payment/".$this->classname.".cfg.php" );
   }
   
  /**
	* Returns the "is_readable" status of the configuration file
	* @param void
	* @returns boolean True when the configuration file is writeable, false when not
	*/
   function configfile_readable() {
      return is_readable( CLASSPATH."payment/".$this->classname.".cfg.php" );
   }   
  /**
	* Writes the configuration file for this payment method
	* @param array An array of objects
	* @returns boolean True when writing was successful
	*/
   function write_configuration( &$d ) {
      
      $my_config_array = array("TWOCO_LOGIN" => $d['TWOCO_LOGIN'],
                                "TWOCO_SECRETWORD" => $d['TWOCO_SECRETWORD'],
                                "TWOCO_VERIFIED_STATUS" => $d['TWOCO_VERIFIED_STATUS'],
                                "TWOCO_INVALID_STATUS" => $d['TWOCO_INVALID_STATUS'],
                                "TWOCO_TESTMODE" => $d['TWOCO_TESTMODE'],
                               "TWOCO_MERCHANT_EMAIL" => $d['TWOCO_MERCHANT_EMAIL']
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
     else
        return false;
   }
   
  /**************************************************************************
  ** name: process_payment()
  ** created by: soeren
  ** description: 
  ** parameters: $order_number, the number of the order, we're processing here
  **            $order_total, the total $ of the order
  ** returns: 
  ***************************************************************************/
   function process_payment($order_number, $order_total, &$d) {

      return true;

   }
   
   
}
