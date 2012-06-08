<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: get_payment_method.tpl.php 1140 2008-01-09 20:44:35Z soeren_nb $
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

include_once( CLASSPATH . "payment/ps_paypal_api.cfg.php");
require_once( CLASSPATH . 'payment/ps_paypal_api.php');

ps_checkout::show_checkout_bar();

echo $basket_html;

echo '<br />';

$varname = 'PHPSHOP_CHECKOUT_MSG_' . CHECK_OUT_GET_PAYMENT_METHOD;
echo '<h4>'. $VM_LANG->_($varname) . '</h4>';

$lang = jfactory::getLanguage();
$lang_iso = str_replace( '-', '_', $lang->gettag() );
$paypal_buttonurls = array('en_US' => 'https://www.paypal.com/en_US/i/logo/PayPal_mark_60x38.gif',
									'en_GB' => 'https://www.paypal.com/en_GB/i/bnr/horizontal_solution_PP.gif',
									'de_DE' => 'https://www.paypal.com/de_DE/DE/i/logo/lockbox_150x47.gif',
									'es_ES' => 'https://www.paypalobjects.com/WEBSCR-600-20100105-1/en_US/FR/i/bnr/bnr_horizontal_solution_PP_327wx80h.gif',
									'pl_PL' => 'https://www.paypalobjects.com/WEBSCR-600-20100105-1/en_US/FR/i/bnr/bnr_horizontal_solution_PP_327wx80h.gif',
									'nl_NL' => 'https://www.paypalobjects.com/WEBSCR-600-20100105-1/en_US/FR/i/bnr/bnr_horizontal_solution_PP_327wx80h.gif',
									'fr_FR' => 'https://www.paypalobjects.com/WEBSCR-600-20100105-1/en_US/FR/i/bnr/bnr_horizontal_solution_PP_327wx80h.gif',
									'it_IT' => 'https://www.paypalobjects.com/WEBSCR-600-20100105-1/it_IT/IT/i/bnr/bnr_horizontal_solution_PP_178wx80h.gif',
									'zn_CN' => 'https://www.paypalobjects.com/WEBSCR-600-20100105-1/en_US/FR/i/bnr/bnr_horizontal_solution_PP_327wx80h.gif' );
$paypal_infolink = array('en_US' => 'https://www.paypal.com/us/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside',
									'en_GB' => 'https://www.paypal.com/uk/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside',
									'de_DE' => 'https://www.paypal.com/de/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside',
									'es_ES' => 'https://www.paypal.com/es/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside',
									'pl_PL' => 'https://www.paypal.com/pl/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside',
									'nl_NL' => 'https://www.paypal.com/nl/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside',
									'fr_FR' => 'https://www.paypal.com/fr/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside',
									'it_IT' => 'https://www.paypal.com/it/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside',
									'zn_CN' => 'https://www.paypal.com/cn/cgi-bin/webscr?cmd=xpt/Marketing/popup/OLCWhatIsPayPal-outside' );
if( !isset( $paypal_buttonurls[$lang_iso])) {
	$lang_iso = 'en_US';
}

$html = '<img id="paypalLogo" src="'.$paypal_buttonurls[$lang_iso].'" alt="PayPal Checkout Available" border="0" style="cursor:pointer;" /></a>';
$html .= '<script type="text/javascript">window.addEvent("domready", function() {
	$("paypalLogo").addEvent("click", function() {
		window.open(\''.$paypal_infolink[$lang_iso].'\',\'olcwhatispaypal\',\'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=400, height=500\');
		});
	
		window.addEvent("click", function() {
			if($("paypalExpressID_ecm").checked) 
			{
				$("paypalExpress_ecm").value="2";
			} 
			else 
			{	
				$("paypalExpress_ecm").value="";
			}
		});
	});
	</script>';
?>
	<fieldset><legend><strong>PayPal</strong></legend>
		<table border="0" cellspacing="0" cellpadding="2" width="100%">
		    <tr>
		        <td>
					<input type="hidden" id="paypalExpress_ecm" name="payment_method_ppex" value="" />
					<input type="radio" id="paypalExpressID_ecm" name="payment_method_id" value="<?php echo ps_paypal_api::getPaymentMethodId();?>" />
					<?php echo $html; ?>
		        </td>
		    </tr>
		</table>
	</fieldset>


<?php

	echo ps_checkout::list_payment_methods( $payment_method_id );

?>