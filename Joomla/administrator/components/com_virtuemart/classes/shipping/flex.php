<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: flex.php 1095 2007-12-19 20:19:16Z soeren_nb $
* @package VirtueMart
* @subpackage shipping
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
* 
*
* This class will charge a fixed shipping rate for orders under a minimum sales
* threshhold and a percentage of the total order price for orders over that
* threshold.
* @copyright (C) 2005 Micah Shawn
* 
*******************************************************************************
*/
class flex {
	/**
	 * Lists all available shipping rates
	 *
	 * @param array $d
	 * @return boolean
	 */
	function list_rates( &$d ) {
		global $total, $tax_total, $CURRENCY_DISPLAY;


		/** Read current Configuration ***/
		require_once(CLASSPATH ."shipping/".__CLASS__.".cfg.php");

		if ( $_SESSION['auth']['show_price_including_tax'] != 1 ) {
			$taxrate = 1;
			$order_total = $total + $tax_total;
		}
		else {
			$taxrate = $this->get_tax_rate() + 1;
			$order_total = $total;
		}

		//Charge minimum up to this value in cart
		$base_ship = $GLOBALS['CURRENCY']->convert( FLEX_BASE_AMOUNT );

		//Flat rate shipping charge up to minimum value
		$flat_charge = $GLOBALS['CURRENCY']->convert( FLEX_MIN_CHG );

		//Charge this percentage if cart value is greater than base amount
		$ship_rate_perc = (FLEX_SHIP_PERC / 100);

		//Flat rate handling fee
		$handling_fee = $GLOBALS['CURRENCY']->convert( FLEX_HAND_FEE );


		if($order_total < $base_ship) {
			$flat_charge += $handling_fee;
			$flat_charge *= $taxrate;
			$shipping_rate_id = urlencode(__CLASS__."|STD|Standard Shipping under ".$base_ship."|".$flat_charge);
			$html = "";
			$html .= "\n<input type=\"radio\" name=\"shipping_rate_id\" checked=\"checked\" id=\"flex_shipping_rate\" value=\"$shipping_rate_id\" />\n";
			$html .= "<label for=\"flex_shipping_rate\">Standard Shipping: ".$CURRENCY_DISPLAY->getFullValue($flat_charge);
			$html .= "</label>";
			
			$_SESSION[$shipping_rate_id] = 1;
		}
		else {

			$shipping_temp1 = ($order_total * $ship_rate_perc);
			$shipping_temp1 += ( $handling_fee * $taxrate );
			$shipping_rate_id = urlencode(__CLASS__."|STD|Standard Shipping over ".$base_ship."|".$shipping_temp1);
			$html = "";
			$html .= "\n<input type=\"radio\" name=\"shipping_rate_id\" id=\"flex_shipping_rate\" checked=\"checked\" value=\"$shipping_rate_id\" />\n";
			$html .= "<label for=\"flex_shipping_rate\">Standard Shipping: ";
			$html .= $CURRENCY_DISPLAY->getFullValue($shipping_temp1);
			$html .= "</label>";
			$_SESSION[$shipping_rate_id] = 1;
		}
		echo $html;
		return true;


	}
	/**
	 * Returns the rate for the selected shipping method
	 *
	 * @param array $d
	 * @return float
	 */
	function get_rate( &$d ) {

		$shipping_rate_id = $d["shipping_rate_id"];
		$is_arr = explode("|", urldecode(urldecode($shipping_rate_id)) );
		$order_shipping = (float)$is_arr[3];

		return $order_shipping;

	}


	function get_tax_rate() {

		/** Read current Configuration ***/
		require_once(CLASSPATH ."shipping/".__CLASS__.".cfg.php");

		if( intval(FLEX_TAX_CLASS)== 0 ) {
			return( 0 );
		}
		else {
			require_once( CLASSPATH. "ps_tax.php" );
			$tax_rate = ps_tax::get_taxrate_by_id( intval(FLEX_TAX_CLASS) );
			return $tax_rate;
		}
	}

	/**
	 *  Validate this Shipping method by checking if the SESSION contains the key
	 * @returns boolean False when the Shipping method is not in the SESSION
	 */
	function validate( $d ) {

		$shipping_rate_id = $d["shipping_rate_id"];

		if( array_key_exists( $shipping_rate_id, $_SESSION )) {
			
			return true;
		}
		else {
			return false;
		}
	}
	/**
    * Show all configuration parameters for this Shipping method
    * @returns boolean False when the Shipping method has no configration
    */
	function show_configuration() {
		global $VM_LANG;
		/** Read current Configuration ***/
		require_once(CLASSPATH ."shipping/".__CLASS__.".cfg.php");
    ?>
      <table>
    <tr>
        <td><strong>Charge flat shipping rate to this amount:</strong></td>
		<td>
            <input type="text" name="FLEX_BASE_AMOUNT" class="inputbox" value="<?php echo FLEX_BASE_AMOUNT ?>" />
		</td>
		<td>
        <?php echo mm_ToolTip("A flat fee will be charged if the total value of the cart is less than this amount. If the value of the cart is greater than this amount, a percentage will be charged") ?> 
        </td>
    </tr>
    <tr>
        <td><strong>Minimum Shipping Charge:</strong>
		</td>
		<td>
            <input type="text" name="FLEX_MIN_CHG" class="inputbox" value="<?php echo FLEX_MIN_CHG ?>" />
		</td>
		<td>
        <?php echo mm_ToolTip("This is the flat fee to be charged if the value in the cart is less than the amount entered above.") ?>
        </td>
    </tr>
    <tr>
        <td><strong>Percentage to charge if total sale is over base:</strong>
		</td>
		<td>
            <input type="text" name="FLEX_SHIP_PERC" class="inputbox" value="<?php echo FLEX_SHIP_PERC ?>" />
		</td>
		<td>
            <?php echo mm_ToolTip("This is the percentage (of the total purchase) to be charged if the amount in the cart is greater than the amount entered above.") ?>
        </td>
    </tr>
	<tr>
        <td><strong>Fixed Handling Charge:</strong>
		</td>
		<td>
            <input type="text" name="FLEX_HAND_FEE" class="inputbox" value="<?php echo FLEX_HAND_FEE ?>" />
		</td>
		<td>
            <?php echo mm_ToolTip("If you want to use a flat fee in addition to the conditional rate, add it here, If not, set to Zero.") ?>
        </td>
    </tr>
	  <tr>
		<td><strong><?php echo $VM_LANG->_('PHPSHOP_UPS_TAX_CLASS') ?></strong></td>
		<td>
		  <?php
		  require_once(CLASSPATH.'ps_tax.php');
		  ps_tax::list_tax_value("FLEX_TAX_CLASS", FLEX_TAX_CLASS) ?>
		</td>
		<td><?php echo mm_ToolTip($VM_LANG->_('PHPSHOP_UPS_TAX_CLASS_TOOLTIP')) ?><td>
	  </tr>	
	</table>
   <?php
   // return false if there's no configuration
   return true;
	}
	/**
  * Returns the "is_writeable" status of the configuration file
  * @param void
  * @returns boolean True when the configuration file is writeable, false when not
  */
	function configfile_writeable() {
		return is_writeable( CLASSPATH."shipping/".__CLASS__.".cfg.php" );
	}

	/**
	* Writes the configuration file for this shipping method
	* @param array An array of objects
	* @returns boolean True when writing was successful
	*/
	function write_configuration( &$d ) {
	    global $vmLogger;

		$my_config_array = array("FLEX_BASE_AMOUNT" => $d['FLEX_BASE_AMOUNT'],
		"FLEX_MIN_CHG" => $d['FLEX_MIN_CHG'],
		"FLEX_SHIP_PERC" => $d['FLEX_SHIP_PERC'],
		"FLEX_TAX_CLASS" => $d['FLEX_TAX_CLASS'],
		"FLEX_HAND_FEE" => $d['FLEX_HAND_FEE']
		);
		$config = "<?php\n";
		$config .= "if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); \n\n";
		foreach( $my_config_array as $key => $value ) {
			$value = str_replace('\'', '\\\'', $value );
			$config .= "define ('$key', '$value');\n";
		}

		$config .= "?>";

		if ($fp = fopen(CLASSPATH ."shipping/".__CLASS__.".cfg.php", "w")) {
			fputs($fp, $config, strlen($config));
			fclose ($fp);
			return true;
		}
		else {
			$vmLogger->err( "Error writing to configuration file" );
			return false;
		}
	}
}


?>
