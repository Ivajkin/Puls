<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: shop.basket_short.php 1948 2009-09-30 14:32:48Z soeren_nb $
* @package VirtueMart
* @subpackage html
* @copyright Copyright (C) 2004-2009 soeren - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
* 
* http://virtuemart.net
*/
mm_showMyFileName( __FILE__ );

require_once(CLASSPATH. 'ps_product.php' );
$ps_product = new ps_product;
require_once(CLASSPATH. 'ps_shipping_method.php' );
require_once(CLASSPATH. 'ps_checkout.php' );
$ps_checkout = new ps_checkout;

global $CURRENCY_DISPLAY, $VM_LANG, $vars,$mosConfig_live_site, $sess, $mm_action_url;

$catid = vmGet($_REQUEST, "category_id", null);
$prodid = vmGet($_REQUEST, "product_id", null);
$page = vmGet($_REQUEST, "page", null);
$flypage = vmGet($_REQUEST, "flypage", null);
$Itemid = vmGet($_REQUEST, "Itemid", null);
$option = vmGet($_REQUEST, "option", null);
$page =vmGet( $_REQUEST, 'page', null );
$tpl = new $GLOBALS['VM_THEMECLASS']();
$cart = $_SESSION['cart'];
$saved_cart = @$_SESSION['savedcart'];
$auth = $_SESSION['auth'];
$empty_cart = false;
$minicart = array();
if ($cart["idx"] == 0) {
	$empty_cart = true;
	$checkout = false;
	$total = 0;
}
else {
	$empty_cart = false;
	$checkout = True;
	$total = $order_taxable = $order_tax = 0;
	$amount = 0;
	$weight_total = 0;
	$html="";

	// Determiine the cart direction and set vars
	if (@$_SESSION['vmCartDirection']) {
		$i=0;
		$up_limit = $cart["idx"] ;
	}
	else {
		$i=$cart["idx"]-1;
		$up_limit = -1;
	}
	$ci = 0;

	//Start loop through cart
	do
	{
		//If we are not showing the minicart start the styling of the individual products

		$price = $ps_product->get_adjusted_attribute_price($cart[$i]["product_id"],$cart[$i]["description"]);
		$price["product_price"] = $GLOBALS['CURRENCY']->convert( $price["product_price"], $price["product_currency"] );
		$amount += $cart[$i]["quantity"];
		$product_parent_id=$ps_product->get_field($cart[$i]["product_id"],"product_parent_id");
		if (@$auth["show_price_including_tax"] == 1) {
			$my_taxrate = $ps_product->get_product_taxrate($cart[$i]["product_id"] );
			$price["product_price"] *= ($my_taxrate+1);
		}
		$subtotal = round( $price["product_price"], 2 ) * $cart[$i]["quantity"];
		$total += $subtotal;
		$flypage_id = $product_parent_id;
		if($flypage_id == 0) {
			$flypage_id = $cart[$i]["product_id"];
		}
		$flypage = $ps_product->get_flypage($flypage_id);
		$category_id = vmGet( $cart[$i], 'category_id', 0 );
		if ($product_parent_id) {
			$url = $sess->url(URL . "index.php?page=shop.product_details&flypage=$flypage&product_id=$product_parent_id&category_id=$category_id");
		}
		else {
			$url = $sess->url(URL . "index.php?page=shop.product_details&flypage=$flypage&category_id=$category_id&product_id=" . $_SESSION['cart'][$i]["product_id"]);
		}
		$html = str_replace("_"," ",$ps_product->getDescriptionWithTax( $_SESSION['cart'][$i]["description"], $_SESSION['cart'][$i]["product_id"] ))." ";
		if ($product_parent_id) {
			$db_detail=$ps_product->attribute_sql($cart[$i]["product_id"],$product_parent_id);
			while ($db_detail->next_record()) {
				$html .= $db_detail->f("attribute_value") . " ";
			}
		}
		$minicart[$ci]['url'] = $url;
		$minicart[$ci]['product_name'] = shopMakeHtmlSafe($ps_product->get_field($_SESSION['cart'][$i]["product_id"], "product_name"));
		$minicart[$ci]['quantity'] = $cart[$i]["quantity"];
		$minicart[$ci]['price'] = $CURRENCY_DISPLAY->getFullValue( $subtotal );
		$minicart[$ci]['attributes'] = $html;
		if(@$_SESSION['vmCartDirection']) {
			$i++;
		}
		else {
			$i--;
		}

		$ci++;
	} while ($i != $up_limit);
	//End loop through cart


}
if( !empty($_SESSION['coupon_discount']) ) {
	$total -= $_SESSION['coupon_discount'];
}
if(!$empty_cart) {
	if ($amount > 1) {
		$total_products = $amount ." ". $VM_LANG->_('PHPSHOP_PRODUCTS_LBL');
	}
	else {
		$total_products = $amount ." ". $VM_LANG->_('PHPSHOP_PRODUCT_LBL');
	}


	$total_price = $CURRENCY_DISPLAY->getFullValue( $total );
}
// Display clear cart
$delete_cart = '';
if(@$_SESSION['vmEnableEmptyCart'] && !@$_SESSION['vmMiniCart']) {
	// Output the empty cart button
	//echo vmCommonHTML::scriptTag( $mosConfig_live_site.'/components/'.$option.'/js/wz_tooltip.js' );
	$delete_cart = "<a href=\"".$_SERVER['SCRIPT_NAME'] . "?page=shop.cart_reset&amp;option=com_virtuemart&amp;option2=$option&amp;product_id=$prodid&amp;category_id=$catid&amp;return=$page&amp;flypage=$flypage&amp;Itemid=$Itemid\" title=\"". $VM_LANG->_('PHPSHOP_EMPTY_YOUR_CART') ." \">
					<img src=\"". $mosConfig_live_site ."/images/cancel_f2.png\" width=\"12\" border=\"0\" style=\"float: right;vertical-align: middle;\" alt=\"". $VM_LANG->_('PHPSHOP_EMPTY_YOUR_CART') ." \" />
      </a>"; 
	$html1 = vmToolTip($VM_LANG->_('VM_EMPTY_YOUR_CART_TIP'), $VM_LANG->_('PHPSHOP_EMPTY_YOUR_CART'),'','',$delete_cart,true);
	$delete_cart = $html1;

}

$href = $sess->url($mm_action_url."index.php?page=shop.cart");
$href2 = $sess->url($mm_action_url."index2.php?page=shop.cart", true);
$text = $VM_LANG->_('PHPSHOP_CART_SHOW');
if( @$_SESSION['vmUseGreyBox'] ) {
	$show_cart = vmCommonHTML::getGreyboxPopUpLink( $href2, $text, '', $text, '', 500, 600, $href );
}
else {
	$show_cart = vmCommonHTML::hyperlink( $href, $text, '', $text, '' );
}

$tpl->set('minicart',$minicart);
$tpl->set('empty_cart', $empty_cart);
$tpl->set('delete_cart', $delete_cart);
$tpl->set('vmMinicart', @$_SESSION['vmMiniCart']);
$tpl->set('total_products', @$total_products);
$tpl->set('total_price', @$total_price);
$tpl->set('show_cart', @$show_cart);
$saved_cart_text = "";
if($saved_cart['idx'] != 0) {
	$saved_cart_text = "<br style=\"clear:both;\"/><a href=\"".str_replace("Itemid=26","Itemid=34",$sess->url($mm_action_url."index.php?page=shop.savedcart"))."\" class=\"savedCart\">".$VM_LANG->_('VM_RECOVER_CART')."</a>";
}
$tpl->set('saved_cart',$saved_cart_text);
echo $tpl->fetch( 'common/minicart.tpl.php');
?>
