<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: shop.index.php 2683 2011-01-30 18:57:28Z zanardi $
* @package VirtueMart
* @subpackage html
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
require_once( CLASSPATH . 'ps_product.php');
require_once( CLASSPATH . 'ps_product_category.php');
$ps_product_category = new ps_product_category();
$ps_product = new ps_product();
// Show only top level categories and categories that are
// being published
$tpl = new $GLOBALS['VM_THEMECLASS']();
$category_childs = $ps_product_category->get_child_list(0);
$tpl->set( 'categories', $category_childs );
//echo $vendor_store_desc;
$categories = $tpl->fetch( 'common/categoryChildlist.tpl.php');
$tpl->set( 'vendor_store_desc', $vendor_store_desc );
$tpl->set( 'categories', $categories );
$tpl->set('ps_product',$ps_product);
$tpl->set('recent_products',$ps_product->recentProducts(null,$tpl->get_cfg('showRecent', 5)));

if( file_exists( CLASSPATH.'payment/ps_paypal_api.php') ) {
	require_once( CLASSPATH.'payment/ps_paypal_api.php');
	if( ps_paypal_api::getPaymentMethodId() && ps_paypal_api::isActive() ) {
		// Paypal API / Express
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

		$html = '<a href="#"><img id="paypalLogo" src="'.$paypal_buttonurls[$lang_iso].'" alt="PayPal Checkout Available" border="0" style="cursor:pointer;" /></a>';
		$html .= '<script type="text/javascript">window.addEvent("domready", function() {
			$("paypalLogo").addEvent("click", function() {
				window.open(\''.$paypal_infolink[$lang_iso].'\',\'olcwhatispaypal\',\'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=400, height=500\');
				});
			});
			</script>';
		
		$tpl->set('paypalLogo', $html);
	}
}

echo $tpl->fetch( 'common/shopIndex.tpl.php');
?>
