<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: get_final_confirmation.tpl.php 2934 2011-04-03 13:51:35Z zanardi $
* @package VirtueMart
* @subpackage templates
* @copyright Copyright (C) 2007 Soeren Eberhardt. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/
ps_checkout::show_checkout_bar();

echo $basket_html;

echo '<br />';

$varname = 'PHPSHOP_CHECKOUT_MSG_' . CHECK_OUT_GET_FINAL_CONFIRMATION;
echo '<h4>'. $VM_LANG->_($varname) . '</h4>'; //h5 tag changed to h4 tag by JK
$db = new ps_DB();

echo '<table>';
// Begin with Shipping Address
if(!ps_checkout::noShipToNecessary()) {

	$db->query("SELECT * FROM #__{vm}_user_info WHERE user_info_id='".strip_tags($_REQUEST['ship_to_info_id'])."'");
	$db->next_record();

	echo '<tr><td valign="top"><strong>'.$VM_LANG->_('PHPSHOP_ADD_SHIPTO_2') . ":</strong></td>";
	echo '<td>';
	$dbs = new ps_DB();
	$q = "SELECT * FROM #__{vm}_country WHERE country_3_code='".$db->f("country")."'";
	$dbs->query($q);
	$country_id = $dbs->f("country_id");
	$q = "SELECT * FROM #__{vm}_state WHERE country_id=".(int)$country_id." AND state_2_code='".$db->f("state")."'";
	$dbs->query($q);
	$state_name = $dbs->f("state_name");
	echo vmFormatAddress( array('name' => $db->f("first_name")." ".$db->f("last_name"),
        								'address_1' => $db->f("address_1"),
        								'address_2' => $db->f("address_2"),
        								'state' => $db->f("state"),
        								'state_name' => $state_name,
        								'zip' => $db->f("zip"),
        								'city' => $db->f("city"),
        								'country' => $db->f('country')
        							), true );
	
	echo "</td></tr>";
}

// Print out the Selected Shipping Method
if(!ps_checkout::noShippingMethodNecessary()) {

	echo '<tr><td valign="top"><strong>'.$VM_LANG->_('PHPSHOP_INFO_MSG_SHIPPING_METHOD') . ":</strong></td>";
	$rate_details = explode( "|", $shipping_rate_id );
	echo '<td>';
	foreach( $rate_details as $k => $v ) {
		// thepisu: old sample data cointaned "&gt;" instead of ">"... 
		// so we don't have to make safe if "&gt;" is found
		if (strpos($v,"&gt;")===false) {
			$v = shopMakeHtmlSafe($v);
		}
		if( $k == 3 ) {
			echo $CURRENCY_DISPLAY->getFullValue( $v )."; ";
		} elseif( $k > 0 && $k < 4) {
			echo $v.'; ';
		}
	}
	echo "</td></tr>";
}

unset( $row );
if( !isset($order_total) || $order_total > 0.00 ) {
	$payment_method_id = vmRequest::getInt( 'payment_method_id' );
	if ($payment_method_id) { // added by JK to disable showing of payment method when there is no payment method.
	
		$db->query("SELECT payment_method_id, payment_method_name FROM #__{vm}_payment_method WHERE payment_method_id='$payment_method_id'");
		$db->next_record();
		echo '<tr><td valign="top"><strong>'.$VM_LANG->_('PHPSHOP_ORDER_PRINT_PAYMENT_LBL') . ":</strong></td>";
		echo '<td>';
		echo $db->f("payment_method_name");
		echo "</td></tr>";
	} // closing payment method check by JK
}
echo '</table>';
?>
<br />
<div align="center">
    <?php echo $VM_LANG->_('PHPSHOP_CHECKOUT_CUSTOMER_NOTE') ?>:<br />
    <textarea title="<?php echo $VM_LANG->_('PHPSHOP_CHECKOUT_CUSTOMER_NOTE') ?>" cols="50" rows="5" name="customer_note"></textarea>
    <br />
    <?php
    if (PSHOP_AGREE_TO_TOS_ONORDER == '1') { ?>
        <br />
      	<input type="checkbox" name="agreed" value="1" class="inputbox" />&nbsp;&nbsp;
      	<?php 
      	$link = $mosConfig_live_site .'/index2.php?option=com_virtuemart&amp;page=shop.tos&amp;pop=1&amp;Itemid='. $Itemid;
		$text = $VM_LANG->_('PHPSHOP_I_AGREE_TO_TOS');
		echo vmPopupLink( $link, $text );
        echo '<br />';
    }
    ?>
</div>
<?php
if( VM_ONCHECKOUT_SHOW_LEGALINFO == '1' ) {
	$link = 'index2.php?option=com_content&amp;task=view&amp;id='.VM_ONCHECKOUT_LEGALINFO_LINK;
	if( class_exists('jroute')) {
		$link = JRoute::_($link);
	} else {
		$link =  sefRelToAbs( $link );
	}
	$jslink = "window.open('$link', 'win2', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no'); return false;";
		if( @VM_ONCHECKOUT_LEGALINFO_SHORTTEXT=='' || !defined('VM_ONCHECKOUT_LEGALINFO_SHORTTEXT')) {
		$text = $VM_LANG->_('VM_LEGALINFO_SHORTTEXT');
	} else {
		$text = VM_ONCHECKOUT_LEGALINFO_SHORTTEXT;
	}
	?>
    <div class="legalinfo"><?php
    	echo sprintf( $text, $link, $jslink );
    	?>
    </div><br />
    <?php
	}
    ?>
<div align="center">
<input type="submit" onclick="return( submit_order( this.form ) );" class="button" name="formSubmit" value="<?php echo $VM_LANG->_('PHPSHOP_ORDER_CONFIRM_MNU') ?>" />
</div>
<?php
if(  PSHOP_AGREE_TO_TOS_ONORDER == '1' ) {
	echo vmCommonHTML::scriptTag('', "function submit_order( form ) {
    if (!form.agreed.checked) {
        alert( \"". $VM_LANG->_('PHPSHOP_AGREE_TO_TOS',false) ."\" );
        return false;
    }
    else {
        return true;
    }
}" );
} else {
	echo vmCommonHTML::scriptTag('', "function submit_order( form ) { return true;  }" );
}
?>
