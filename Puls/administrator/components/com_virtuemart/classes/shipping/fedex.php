<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: fedex.php 1958 2009-10-08 20:09:57Z soeren_nb $
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
global $mosConfig_live_site;

define('FEDEX_REQUEST_REFERER', $mosConfig_live_site);
define('FEDEX_REQUEST_TIMEOUT', 20);
define('FEDEX_IMG_DIR', '/tmp/');

class fedex {

    var $classname = 'fedex';

	// $config_array contains all variable names for the configuration page
    var $config_array = array (
				'FEDEX_ACCOUNT_NUMBER'
				,'FEDEX_METER_NUMBER'
				,'FEDEX_URI'
				,'FEDEX_TAX_CLASS'
				,'FEDEX_HANDLINGFEE'
				,'FEDEX_SERVICES'
				,'FEDEX_SIGNATURE_OPTION'
				,'FEDEX_SORT_ORDER'
    	);

	/** 
	 * Echos a formatted list of shipping rates.
	 * 
	 * @param array $d
	 * @return boolean
	 */
    function list_rates( &$d ) {
		global $vendor_country_2_code, $vendor_currency, $vmLogger;
		global $VM_LANG, $CURRENCY_DISPLAY;
		$db = new ps_DB;
		$dbv = new ps_DB;

		$cart = $_SESSION['cart'];
		
		// Read the fedex configuration file
		require_once(CLASSPATH ."shipping/".$this->classname.".cfg.php");
		
		// Include the main FedEx class
		require_once( CLASSPATH . 'shipping/fedex/fedexdc.php' );
		
		// Get the meter number
		if( FEDEX_METER_NUMBER=='') {
			if( !$this->update_meter_number() ) {
				$vmLogger->err( $VM_LANG->_('VM_FEDEX_ERR_METER_NUMBER',false) );
				return false;
			}
		}
		
		// Get the shopper's shipping address
		$q  = "SELECT * FROM #__{vm}_user_info, #__{vm}_country WHERE user_info_id='" . $d["ship_to_info_id"]."' AND ( country=country_2_code OR country=country_3_code)";
		$db->query($q);
		$db->next_record();

		// Get the vendor address
		$q  = "SELECT * FROM #__{vm}_vendor WHERE vendor_id='".$_SESSION['ps_vendor_id']."'";
		$dbv->query($q);
		$dbv->next_record();

		// Is this a residential delivery?
		$residential_delivery_flag = vmGet($_REQUEST, 'address_type', 'residential') == 'residential' ? 'Y' : 'N';
		
		// Is this a domestic delivery?
		$recipient_country = $db->f('country_2_code');
		$domestic_delivery = ($recipient_country == 'US' || $recipient_country == 'CA') ? true : false;

		// Get the weight total
		if( $d['weight'] > 150) {
			$d['weight'] = 150;
		}
		if( $d['weight'] < 1) {
			$d['weight'] = 1;
		}
		$order_weight = number_format( (float)$d['weight'], 1, '.', '' );
		
		// Set units
		$weight_units = (WEIGHT_UOM == 'KG') ? 'KGS' : 'LBS';
		$dimension_units = (WEIGHT_UOM == 'KG') ? 'C' : 'I';
		
		// config values
		$fed_conf = array();
		
		// create new FedExDC object
		$meter_number = defined('FEDEX_METER_NUMBER_TEMP') ? FEDEX_METER_NUMBER_TEMP : FEDEX_METER_NUMBER;
		$fed = new FedExDC( FEDEX_ACCOUNT_NUMBER, $meter_number, $fed_conf );
		
		// Set up the rate request array.
		// You can either use the FedEx tag value or the field name in the $FE_RE array
		$request_array = 
			array(
		       	'carrier_code' => ''//FDXE or FDXG or blank for both

		        ,'sender_state' => 	$dbv->f('vendor_state')
		        ,'sender_postal_code' => 	$dbv->f('vendor_zip')
		        ,'sender_country_code' =>	$vendor_country_2_code
		        
		        ,'recipient_state' =>   $db->f('state')
		        ,'recipient_postal_code' =>   $db->f('zip')
		        ,'recipient_country' =>   $db->f('country_2_code')

				,'residential_delivery_flag' => $residential_delivery_flag
				,'signature_option' => FEDEX_SIGNATURE_OPTION		        

//		        ,'dim_units' =>	$dimension_units
//		        ,'dim_height' =>	'12'
//		        ,'dim_width' =>	'24'
//		        ,'dim_length' =>	'10'

		        ,'weight_units' => $weight_units
		        ,'total_package_weight' =>	$order_weight

		        ,'drop_off_type' =>	'1'
			);
		
		// Get the rate quote
		$rate_Ret = $fed->services_rate ( $request_array );		
		
		if ($error = $fed->getError()) {
		    $vmLogger->err( $error );

		   	// Switch to StandardShipping on Error !!!
			require_once( CLASSPATH . 'shipping/standard_shipping.php' );
			$shipping = new standard_shipping();
			$shipping->list_rates( $d );
			return;
		} 
		elseif( DEBUG ) {
			echo "<pre>";
		    echo $fed->debug_str. "\n<br />";
		    print_r($rate_Ret);
		    echo "\n";
		    echo "ZONE: ".$rate_Ret[1092]."\n\n";
		
		    for ($i=1; $i<=$rate_Ret[1133]; $i++) {
		        echo "SERVICE : ".$fed->service_type($rate_Ret['1274-'.$i], $domestic_delivery)."\n";
		        echo "SURCHARGE : ".$rate_Ret['1417-'.$i]."\n";
		        echo "DISCOUNT : ".$rate_Ret['1418-'.$i]."\n";
		        echo "NET CHARGE : ".$rate_Ret['1419-'.$i]."\n";
		        echo "DELIVERY DAY : ".@$rate_Ret['194-'.$i]."\n";
		        echo "DELIVERY DATE : ".@$rate_Ret['409-'.$i]."\n\n";
		    }
		    echo "</pre>";
		}
		
		// Set the tax rate
		if ( $_SESSION['auth']['show_price_including_tax'] != 1 ) {
			$taxrate = 1;
		}
		else {
			$taxrate = $this->get_tax_rate() + 1;
		}
		
		// Write out the shipping rates
		$html = '<span class="fedex_header">' . $VM_LANG->_('VM_FEDEX_LBL_METHOD') . '</span><br />';
		
		// Get a sort order array (by cost)
		$cost_array = array();
		for ($i=1; $i<=$rate_Ret[1133]; $i++) {
			$cost_array[$i] = $rate_Ret['1419-'.$i];
		}
		if(FEDEX_SORT_ORDER == 'ASC') {
			asort($cost_array, SORT_NUMERIC);
		} else {
			arsort($cost_array, SORT_NUMERIC);
		}
		
		// Determine which services we can display
		$selected_services = explode(',', FEDEX_SERVICES);
		if($domestic_delivery) {
			$selected_services = preg_grep( '/^d/', $selected_services );
			array_walk($selected_services, create_function('&$v,$k', '$v = substr($v, 1);'));
			
			// If this is a residential delivery, then remove the business option; otherwise, remove the home delivery option.
			if($residential_delivery_flag == 'Y') {
				$remove = array("92");
				$selected_services = array_diff( $selected_services, array("92") );
			} else{
				$remove = array("90");
				$selected_services = array_diff( $selected_services, array("90") );
			}
		} else {
			$selected_services = preg_grep( '/^i/', $selected_services );
			array_walk($selected_services, create_function('&$v,$k', '$v = substr($v, 1);'));
		}
		
		// Display each rate
		foreach (array_keys($cost_array) as $i) {
			if( in_array($rate_Ret['1274-'.$i], $selected_services) ) {
				$charge = $rate_Ret['1419-'.$i] + floatval( FEDEX_HANDLINGFEE );
				$charge *= $taxrate;
				$charge_display = $CURRENCY_DISPLAY->getFullValue($charge);
				
				$shipping_rate_id = urlencode($this->classname."|FedEx|".$fed->service_type($rate_Ret['1274-'.$i], $domestic_delivery)."|".$charge);
				
				$checked = (@$d["shipping_rate_id"] == $shipping_rate_id) ? "checked=\"checked\"" : "";
				
				$html .= "\n<span class=\"ssectiontableentry".(2-$i%2)."\">";
				$html .= "\n<input type=\"radio\" id=\"$shipping_rate_id\" name=\"shipping_rate_id\" $checked value=\"$shipping_rate_id\" />\n";
				  
				$_SESSION[$shipping_rate_id] = 1;
				  
				$html .= "<label for=\"$shipping_rate_id\">".$fed->service_type($rate_Ret['1274-'.$i], $domestic_delivery)." ";
	//			if( !empty( $rate_Ret['194-'.$i] ) && !empty($rate_Ret['409-'.$i])) {
	//				$html .= ", expected delivery: ".$rate_Ret['194-'.$i].', '.$rate_Ret['409-'.$i];
	//			}
	
				$html .= "<strong>($charge_display)</strong>";
				$html .= "</label>\n";
				$html .= "</span>\n";
				$html .= "<br />\n";
			}
		}

		echo $html;

		return true;
    }
    
 	/**
 	 * Return the rate amount
 	 *
 	 * @param array $d
 	 * @return float Shipping rate value
 	 */
	function get_rate( &$d ) {

		$shipping_rate_id = $d["shipping_rate_id"];
		$is_arr = explode("|", urldecode(urldecode($shipping_rate_id)) );
		$order_shipping = $is_arr[3];

		return $order_shipping;

	} //end function get_rate

	/**
	 * Returns the tax rate for this shipping method
	 *
	 * @return float The tax rate (e.g. 0.16)
	 */
	function get_tax_rate() {

		/** Read current Configuration ***/
		require_once(CLASSPATH ."shipping/".$this->classname.".cfg.php");

		if( intval(FEDEX_TAX_CLASS)== 0 )
		return( 0 );
		else {
			require_once( CLASSPATH. "ps_tax.php" );
			$tax_rate = ps_tax::get_taxrate_by_id( intval(FEDEX_TAX_CLASS) );
			return $tax_rate;
		}
	}

	/**
    * Validate this Shipping method by checking if the SESSION contains the key
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
	} //end function validate

	/**
    * Show all configuration parameters for this Shipping method
    * @returns boolean False when the Shipping method has no configration
    */
	function show_configuration() {

		global $VM_LANG;
		
		// Include the FedExTags class
		require_once( CLASSPATH . 'shipping/fedex/fedex-tags.php' );
		$fedextags = new FedExTags();

		// Read the fedex configuration file
		require_once(CLASSPATH ."shipping/".$this->classname.".cfg.php");

	    ?>
<div style="width:80%;padding:0 10px;">
	<table class="adminform">
		<th colspan="3"><?php echo $VM_LANG->_('VM_FEDEX_ACCOUNT_SETTINGS') ?></th>
		<tr>
			<td class="labelcell"><?php echo $VM_LANG->_('VM_FEDEX_ACCOUNT_NUMBER') ?></td>
			<td><input type="text" name="FEDEX_ACCOUNT_NUMBER" class="inputbox" value="<?php echo FEDEX_ACCOUNT_NUMBER ?>" /></td>
			<td style="width:5%;text-align:right;">&nbsp;</td>
		</tr>
		<tr>
			<td class="labelcell"><?php echo $VM_LANG->_('VM_FEDEX_METER_NUMBER') ?></td>
			<td><input type="text" name="FEDEX_METER_NUMBER" class="inputbox" value="<?php echo FEDEX_METER_NUMBER ?>" /></td>
			<td style="width:5%;text-align:right;"><?php echo mm_ToolTip($VM_LANG->_('VM_FEDEX_METER_NUMBER_TIP')) ?></td>
		</tr>
		<tr>
			<td class="labelcell"><?php echo $VM_LANG->_('VM_FEDEX_URI') ?></td>
			<td><input type="text" name="FEDEX_URI" class="inputbox" value="<?php echo FEDEX_URI ?>" size="60" /></td>
			<td style="width:5%;text-align:right;"><?php echo mm_ToolTip( $VM_LANG->_('VM_FEDEX_URI_TIP') ) ?></td>
		</tr>
	</table>
	
	<p></p>
	
	<table class="adminform">
		<th colspan="3"><?php echo $VM_LANG->_('VM_FEDEX_TAXES_FEES') ?></th>
		<tr>
			<td class="labelcell"><?php echo $VM_LANG->_('VM_FEDEX_TAX_CLASS') ?></td>
			<td>
			 <?php
			 require_once(CLASSPATH.'ps_tax.php');
			ps_tax::list_tax_value("FEDEX_TAX_CLASS", FEDEX_TAX_CLASS) ?>
			</td>
			<td style="width:5%;text-align:right;"><?php echo mm_ToolTip($VM_LANG->_('VM_FEDEX_TAX_CLASS_TOOLTIP')) ?></td>
		</tr>	
		<tr>
			<td class="labelcell"><?php echo $VM_LANG->_('VM_FEDEX_HANDLING_FEE') ?></td>
			<td><input class="inputbox" type="text" name="FEDEX_HANDLINGFEE" value="<?php echo FEDEX_HANDLINGFEE ?>" /></td>
			<td style="width:5%;text-align:right;"><?php echo mm_ToolTip($VM_LANG->_('VM_FEDEX_HANDLING_FEE_TOOLTIP')) ?></td>
		</tr>
	</table>
	
	<p></p>
	
	<table class="adminform">
		<th colspan="3"><?php echo $VM_LANG->_('VM_FEDEX_ADDITIONAL_SETTINGS') ?></th>
		<tr>
			<td class="labelcell"><?php echo $VM_LANG->_('VM_FEDEX_SERVICES') ?></td>
			<td>
				<select class="inputbox" name="FEDEX_SERVICES[]" multiple="multiple" size="<?php echo count($fedextags->FE_ST) + count($fedextags->FE_ST_INTL); ?>">
<?php
		$selected_services = explode(',', FEDEX_SERVICES);
		foreach($fedextags->FE_ST as $tag => $name) {
			$selected = in_array('d'.$tag, $selected_services) ? 'selected="selected"' : ''; 
			echo "<option value=\"d$tag\" $selected>$name</option>\n";
		}
		foreach($fedextags->FE_ST_INTL as $tag => $name) {
			$selected = in_array('i'.$tag, $selected_services) ? 'selected="selected"' : ''; 
			echo "\t\t\t\t\t<option value=\"i$tag\" $selected>$name</option>\n";
		}
?>
				</select>
			</td>
			<td style="width:5%;text-align:right;">&nbsp;</td>
		</tr>
		<tr>
			<td class="labelcell"><?php echo $VM_LANG->_('VM_FEDEX_SIGNATURE_OPTION') ?></td>
			<td>
				<select class="inputbox" name="FEDEX_SIGNATURE_OPTION">
					<option value="1" <?php if (FEDEX_SIGNATURE_OPTION == '1') echo "selected=\"selected\""; ?>><?php echo $VM_LANG->_('VM_FEDEX_SIGNATURE_OPTION_1') ?></option>
					<option value="2" <?php if (FEDEX_SIGNATURE_OPTION == '2') echo "selected=\"selected\""; ?>><?php echo $VM_LANG->_('VM_FEDEX_SIGNATURE_OPTION_2') ?></option>
					<option value="3" <?php if (FEDEX_SIGNATURE_OPTION == '3') echo "selected=\"selected\""; ?>><?php echo $VM_LANG->_('VM_FEDEX_SIGNATURE_OPTION_3') ?></option>
					<option value="4" <?php if (FEDEX_SIGNATURE_OPTION == '4') echo "selected=\"selected\""; ?>><?php echo $VM_LANG->_('VM_FEDEX_SIGNATURE_OPTION_4') ?></option>
				</select>
			</td>
			<td style="width:5%;text-align:right;">&nbsp;</td>
		</tr>
		<tr>
			<td class="labelcell"><?php echo $VM_LANG->_('VM_FEDEX_SORT_ORDER') ?></td>
			<td>
				<select class="inputbox" name="FEDEX_SORT_ORDER">
					<option value="ASC" <?php if (FEDEX_SORT_ORDER == 'ASC') echo "selected=\"selected\""; ?>><?php echo $VM_LANG->_('VM_FEDEX_SORT_ASC') ?></option>
					<option value="DESC" <?php if (FEDEX_SORT_ORDER == 'DESC') echo "selected=\"selected\""; ?>><?php echo $VM_LANG->_('VM_FEDEX_SORT_DESC') ?></option>
				</select>
			</td>
			<td style="width:5%;text-align:right;">&nbsp;</td>
		</tr>
	</table>
</div>
	   <?php
  		// return false if there's no configuration
   		return true;
	} //end function show_configuration

	/**
  * Returns the "is_writeable" status of the configuration file
  * @param void
  * @returns boolean True when the configuration file is writeable, false when not
  */
	function configfile_writeable() {
		return is_writeable( CLASSPATH."shipping/".$this->classname.".cfg.php" );
	} //end function configfile_writable

	/**
	* Writes the configuration file for this shipping method
	* @param array An array of objects
	* @returns boolean True when writing was successful
	*/
	function write_configuration( &$d ) {
	    global $vmLogger;

		$my_config_array = array();
		foreach( $this->config_array as $config_key ) {
			$my_config_array[$config_key] = isset($d[$config_key]) ? $d[$config_key] : '';
		}

		$config = "<?php\n";
		$config .= "if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); \n\n";
		foreach( $my_config_array as $key => $value ) {
			if($key == 'FEDEX_SERVICES' && is_array($value) ) {
				$config .= "define ('$key', '".implode(',', $value)."');\n";
			} else {
				$config .= "define ('$key', '$value');\n";
			}
		}

		$config .= "?>";

		if ($fp = fopen(CLASSPATH ."shipping/".$this->classname.".cfg.php", "w")) {
			fputs($fp, $config, strlen($config));
			fclose ($fp);
			return true;
		}
		else {
			$vmLogger->err( "Error writing to configuration file" );
			return false;
		}
	} //end function write_configuration
   
	function update_meter_number() {
		global $vendor_name,$vendor_address,$vendor_city,$vendor_state,$vendor_zip,
			$vendor_country_2_code, $vendor_phone, $vmLogger;
			
		$fed = new FedExDC( FEDEX_ACCOUNT_NUMBER );
		$db = new ps_DB();
		$db->query( ('SELECT `contact_first_name`, `contact_last_name` FROM `#__{vm}_vendor` WHERE `vendor_id` ='.intval($_SESSION['ps_vendor_id'])));
		$db->next_record();
	    $aRet = $fed->subscribe(
		    array(
		        1 => uniqid( 'vmFed_' ), // Don't really need this but can be used for ref
		        4003 => $db->f('contact_first_name').' '.$db->f('contact_last_name'),
		        4008 => $vendor_address,
		        4011 => $vendor_city,
		        4012 => $vendor_state,
		        4013 => $vendor_zip,
		        4014 => $vendor_country_2_code,
		        4015 => $vendor_phone
		    )
		);
	    if ($error = $fed->getError() ) {
		    $vmLogger->err( $error );
		    return false;
	    }
	    $meter_number = $aRet[498];

		foreach( $this->config_array as $config_key ) {
			$d[$config_key] = constant($config_key);
		}

	   	$d['FEDEX_METER_NUMBER'] = $meter_number;

		$this->write_configuration( $d );
		
		define( 'FEDEX_METER_NUMBER_TEMP', $meter_number );
		
		return true;
	}
	
}

?>