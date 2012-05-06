<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*@PaySbuy Payment Gateway in Thailand
* @version : ps_paysbuy.php 2007-10-03 Create By Surachet Seangchin
* @subpackage for payment
* @Modify by Akarawuth Tamrareang for support Joomla 1.5 Native.*/

class ps_paysbuy {
    var $classname = "ps_paysbuy";
    var $payment_code = "PaySbuy";
    /**
    * Show all configuration parameters for this payment method
    * @returns boolean False when the Payment method has no configration
    */
    function show_configuration() {
        global $VM_LANG;
        $db = new ps_DB();
        
        /** Read current Configuration ***/
        include_once(CLASSPATH ."payment/".$this->classname.".cfg.php");
    ?>
<SCRIPT type="text/javascript">
function CopyAndPaste( from, to )
{
		document.getElementsByName(to)[0].value = document.getElementsByName(from)[0].value;
}
</SCRIPT>
    <table>
        <tr>
        <td width="100"><strong>PaySbuy Email</strong></td>
            <td width="219">
			<input type="text" name="PaySbuy_EMAIL" class="inputbox" value="<?php  echo PaySbuy_EMAIL ?>" /></td>
            <td width="300">The Email-Account for your PaySbuy Payments. </td>
            <td width="118" rowspan="5"><img src="https://www.paysbuy.com/imgs/logo.gif" /></td>
			</tr>
			<tr>
		      <td  width="100"><strong>Currency</strong></td>
            <td>
			<select name="PaySbuy_CURRENCY">
              <option <?php if (@PaySbuy_CURRENCY == '0') echo "selected=\"selected\""; ?> value="0"><?php echo "THB" ?></option>
              <option <?php if (@PaySbuy_CURRENCY == '840') echo "selected=\"selected\""; ?> value="840"><?php echo "US" ?></option>
            </select>            </td>
            <td width="300">Currency  </td>
	    </tr>
			<tr>
		      <td  width="100"><strong>GateWay</strong></td>
            <td>
			<select name="PaySbuy_GATEWAY">
              <option  <?php if (@PaySbuy_GATEWAY == '0') echo "selected=\"selected\""; ?> value="0"><?php echo "PaySbuy Account" ?></option>
              <option  <?php if (@PaySbuy_GATEWAY == '1') echo "selected=\"selected\""; ?> value="1"><?php echo "Credit Card" ?></option>
            </select>            </td>
            <td width="300">Choose Gateway For Payment  </td>
	    </tr>
			<tr>
		      <td  width="100"><strong>Language</strong></td>
            <td>
			<select name="PaySbuy_LANG">
              <option <?php if (@PaySbuy_LANG == '0') echo "selected=\"selected\""; ?> value="0"><?php echo "Thai" ?></option>
              <option <?php if (@PaySbuy_LANG == '1') echo "selected=\"selected\""; ?> value="1"><?php echo "English" ?></option>
            </select>            </td>
            <td width="300">Choose Language </td>
	    </tr>
			<tr>
		      <td><strong>Form PaySbuy</strong></td>
            <td colspan="2">
<textarea name="PaySbuy_FORM" cols="80" rows="15" readonly="readonly" STYLE="display:none;">
<?php echo "<?php\n"; ?>
$url = "https://www.paysbuy.com/paynow.aspx?c=true";
if(PaySbuy_GATEWAY == "1"){
$url .= "c=true";
	if(PaySbuy_LANG == "1"){
		$url .= "&l=e";
	}
}else{
	if(PaySbuy_LANG == "1"){
		$url .= "l=e";
	}
}
$tax_total = $db->f("order_tax") + $db->f("order_shipping_tax");
$discount_total = $db->f("coupon_discount") + $db->f("order_discount");
$post_variables = Array(
"psb" => psb,
"biz" => PaySbuy_EMAIL,
"itm" => $VM_LANG->_PHPSHOP_ORDER_PRINT_PO_NUMBER.": ". $db->f("order_id"),
"inv" => $db->f("order_id"),
"amt" => round( $db->f("order_total")+$tax_total-$discount_total, 2),
"postURL" => SECUREURL ."index.php?option=com_virtuemart&page=checkout.paysbuy&order_id=".$db->f("order_id"),
"reqURL" => SECUREURL ."administrator/components/com_virtuemart/paysbuynotify.php"); 
echo '<form action="'.$url.'" method="post">';
foreach( $post_variables as $name => $value ) {
echo '<input type="hidden" name="'.$name.'" value="'.$value.'" />';
}
if(PaySbuy_CURRENCY == "840"){
echo '<input type="hidden" name="currencyCode" value="840" />';
}
echo '<input type="image" name="submit" src="https://www.paysbuy.com/images/p_click2buy.gif" border="0"/>';
echo '</form>';
?>
</textarea>
<BUTTON onClick="CopyAndPaste('PaySbuy_FORM', 'payment_extrainfo')">
Copy and Paste</BUTTON>            </td>
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
      
      $my_config_array = array(
				  "PaySbuy_EMAIL" => $d['PaySbuy_EMAIL'],
		  		  "PaySbuy_CURRENCY" => $d['PaySbuy_CURRENCY'],
				  "PaySbuy_GATEWAY" => $d['PaySbuy_GATEWAY'],
		  		  "PaySbuy_LANG" => $d['PaySbuy_LANG']
				  );
      $config = "<?php\n";
      $config .= "if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); ; \n\n";
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
  ** returns: 
  ***************************************************************************/
   function process_payment($order_number, $order_total, &$d) {
        return true;
    }
   
}
