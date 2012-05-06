<?php 
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: shop.product_details.php 2409 2010-05-20 20:05:30Z soeren $
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

require_once(CLASSPATH . 'ps_product_files.php' );
require_once(CLASSPATH . 'imageTools.class.php' );
require_once(CLASSPATH . 'ps_product.php' );
$ps_product = $GLOBALS['ps_product'] = new ps_product;

require_once(CLASSPATH . 'ps_product_category.php' );
$ps_product_category = new ps_product_category;

require_once(CLASSPATH . 'ps_product_attribute.php' );
$ps_product_attribute = new ps_product_attribute;

require_once(CLASSPATH . 'ps_product_type.php' );
$ps_product_type = new ps_product_type;
require_once(CLASSPATH . 'ps_reviews.php' );

$product_id = intval( vmGet($_REQUEST, "product_id", null) );
$product_sku = $db->getEscaped( vmGet($_REQUEST, "sku", '' ) );
$category_id = vmGet($_REQUEST, "category_id", null);
$pop = (int)vmGet($_REQUEST, "pop", 0);
$manufacturer_id = vmGet($_REQUEST, "manufacturer_id", null);
$Itemid = $sess->getShopItemid();
$db_product = new ps_DB;

// Check for non-numeric product id
if (!empty($product_id)) {
	if (!is_numeric($product_id)) {
		$product_id = '';
	}
}

// Get the product info from the database
$q = "SELECT * FROM `#__{vm}_product` WHERE ";
if( !empty($product_id)) {
	$q .= "`product_id`=$product_id";
}
elseif( !empty($product_sku )) {
	$q .= "`product_sku`='$product_sku'";
}
else {
	vmRedirect( $sess->url( $_SERVER['PHP_SELF']."?keyword=".urlencode($keyword)."&category_id={$_SESSION['session_userstate']['category_id']}&limitstart={$_SESSION['limitstart']}&page=shop.browse", false, false ), $VM_LANG->_('PHPSHOP_PRODUCT_NOT_FOUND') );
}

if( !$perm->check("admin,storeadmin") ) {
	$q .= " AND `product_publish`='Y'";
	if( CHECK_STOCK && PSHOP_SHOW_OUT_OF_STOCK_PRODUCTS != "1") {
		$q .= " AND `product_in_stock` > 0 ";
	}
}
$db_product->query( $q );

// Redirect back to Product Browse Page on Error
if( !$db_product->next_record() ) {
	$vmLogger->err( $VM_LANG->_('PHPSHOP_PRODUCT_NOT_FOUND',false) );
	return;
}
if( empty($product_id)) {
	$product_id = $db_product->f('product_id');
}
$product_parent_id = (int)$db_product->f("product_parent_id");
if ($product_parent_id != 0) {
	$dbp= new ps_DB;
	$dbp->query('SELECT * FROM `#__{vm}_product` WHERE `product_id`='.$product_parent_id );
	$dbp->next_record();
}

// Create the template object
$tpl = vmTemplate::getInstance();


// Let's have a look wether the product has related products.
$q = "SELECT product_sku, related_products FROM #__{vm}_product,#__{vm}_product_relations ";
$q .= "WHERE #__{vm}_product_relations.product_id='$product_id' AND product_publish='Y' ";
$q .= "AND FIND_IN_SET(#__{vm}_product.product_id, REPLACE(related_products, '|', ',' )) LIMIT 0, 4";
$db->query( $q );
/*// This shows randomly selected products from the products table
// if you don't like to set up related products for each product
$q = "SELECT product_sku FROM #__{vm}_product ";
$q .= "WHERE product_publish='Y' AND product_id != $product_id ";
$q .= "ORDER BY RAND() LIMIT 0, 4";
$db->query( $q );*/
$related_products = '';
if( $db->num_rows() > 0 ) {
	$tpl->set( 'ps_product', $ps_product );
	$tpl->set( 'products', $db );
	$related_products = $tpl->fetch( '/common/relatedProducts.tpl.php' );
}

// GET THE PRODUCT NAME 
$product_name = shopMakeHtmlSafe(  $db_product->f("product_name") );
if( $db_product->f("product_publish") == "N" ) {
	$product_name .= " (".$VM_LANG->_('CMN_UNPUBLISHED').")";
}
$product_description = $db_product->f("product_desc");
if( (str_replace("<br />", "" , $product_description)=='') && ($product_parent_id!=0) ) {
	$product_description = $dbp->f("product_desc"); // Use product_desc from Parent Product
}
$product_description = vmCommonHTML::ParseContentByPlugins( $product_description );

// Get the CATEGORY NAVIGATION 
$navigation_pathway = "";
$navigation_childlist = "";
$pathway_appended = false;

$flypage = vmGet($_REQUEST, "flypage" );

// Each Product is assigned to one or more Categories, if category_id was omitted, we must fetch it here
if (empty($category_id) || empty( $flypage ))  {
	$q = "SELECT cx.category_id, category_flypage FROM #__{vm}_category c, #__{vm}_product_category_xref cx WHERE product_id = '$product_id' AND c.category_id=cx.category_id LIMIT 0,1";
	$db->query( $q );
	$db->next_record();
	if( !$db->f("category_id") ) {
		// The Product Has no category entry and must be a Child Product
		// So let's get the Parent Product
		$q = "SELECT product_id FROM #__{vm}_product WHERE product_id = '".$db_product->f("product_parent_id")."' LIMIT 0,1";
		$db->query( $q );
		$db->next_record();

		$q = "SELECT cx.category_id, category_flypage FROM #__{vm}_category c, #__{vm}_product_category_xref cx WHERE product_id = '".$db->f("product_id")."' AND c.category_id=cx.category_id LIMIT 0,1";
		$db->query( $q );
		$db->next_record();
	}
	$_GET['category_id'] = $category_id = $db->f("category_id");
}
$ps_product->addRecentProduct($product_id,$category_id,$tpl->get_cfg('showRecent', 5));
if( empty( $flypage )) {
	$flypage = $db->f('category_flypage') ? $db->f('category_flypage') : FLYPAGE;
}
// Flypage Parameter has old page syntax: shop.flypage
// so let's get the second part - flypage
$flypage = str_replace( 'shop.', '', $flypage);
$flypage = stristr( $flypage, '.tpl') ? $flypage : $flypage . '.tpl';

// Set up the pathway
// Retrieve the pathway items for this product's category
$category_list = array_reverse( $ps_product_category->get_navigation_list( $category_id ) );
$pathway = $ps_product_category->getPathway( $category_list );

// Add this product's name to the pathway, with no link
$item = new stdClass();
$item->name = $product_name;
$item->link = '';
$pathway[] = $item;

// Set the CMS pathway
$vm_mainframe->vmAppendPathway( $pathway );

// Set the pathway for our template
$tpl->set( 'pathway', $pathway );

$tpl->set( 'product_name', $product_name );

// Get the neighbor Products to allow navigation on product level
$neighbors = $ps_product->get_neighbor_products( !empty( $product_parent_id ) ? $product_parent_id : $product_id );
$next_product = $neighbors['next'];
$previous_product = $neighbors['previous'];
$next_product_url = $previous_product_url = '';
if( !empty($next_product) ) {
	$url_parameters = 'page=shop.product_details&product_id='.$next_product['product_id'].'&flypage='.$ps_product->get_flypage($next_product['product_id']).'&pop='.$pop;
    if( $manufacturer_id ) {
    	$url_parameters .= "&amp;manufacturer_id=" . $manufacturer_id;
    }
    if( $keyword != '') {
    	$url_parameters .= "&amp;keyword=".urlencode($keyword);
    }
	if( $pop == 1 ) {
		$next_product_url = $sess->url( $_SERVER['PHP_SELF'].'?'.$url_parameters );
	} else {
		$next_product_url = str_replace("index2","index",$sess->url( $url_parameters ));
	}
}
if( !empty($previous_product) ) {
	$url_parameters = 'page=shop.product_details&product_id='.$previous_product['product_id'].'&flypage='.$ps_product->get_flypage($previous_product['product_id']).'&pop='.$pop;
    if( $manufacturer_id ) {
    	$url_parameters .= "&amp;manufacturer_id=" . $manufacturer_id;
    }
    if( $keyword != '') {
    	$url_parameters .= "&amp;keyword=".urlencode($keyword);
    }
	if( $pop == 1 ) {
		$previous_product_url = $sess->url( $_SERVER['PHP_SELF'].'?'.$url_parameters );
	} else {
		$previous_product_url = str_replace("index2","index",$sess->url( $url_parameters ));
	}
}

$tpl->set( 'next_product', $next_product );
$tpl->set( 'next_product_url', $next_product_url );
$tpl->set( 'previous_product', $previous_product );
$tpl->set( 'previous_product_url', $previous_product_url );

$parent_id_link = $db_product->f("product_parent_id");
$return_link = "";
if ($parent_id_link <> 0 ) {
	$q = "SELECT product_name FROM #__{vm}_product WHERE product_id = '$product_parent_id' LIMIT 0,1";
	$db->query( $q );
	$db->next_record();
	$product_parent_name = $db->f("product_name");
	$return_link = "&nbsp;<a class=\"pathway\" href=\"";
	$return_link .= $sess->url($_SERVER['PHP_SELF'] . "?page=shop.product_details&product_id=$parent_id_link");
	$return_link .= "\">";
	$return_link .= $product_parent_name;
	$return_link .= "</a>";
	$return_link .= " ".vmCommonHTML::pathway_separator()." ";
}
$tpl->set( 'return_link', $return_link );

// Create the pathway for our template
$navigation_pathway = $tpl->fetch( 'common/pathway.tpl.php');

if ($ps_product_category->has_childs($category_id) ) {
	$category_childs = $ps_product_category->get_child_list($category_id);
	$tpl->set( 'categories', $category_childs );
	$navigation_childlist = $tpl->fetch( 'common/categoryChildlist.tpl.php');
}

// Set Dynamic Page Title
if( function_exists('mb_substr')) {
	$page_title = mb_substr($product_name, 0, 64, vmGetCharset() );
} else {
	$page_title = substr($product_name, 0, 64 );
	
}
$vm_mainframe->setPageTitle( @html_entity_decode( $page_title, ENT_QUOTES, vmGetCharset() ));

// Prepend Product Short Description Meta Tag "description"
if( vmIsJoomla('1.5')) {
	$document = JFactory::getDocument();
	$document->setDescription(strip_tags( $db_product->f("product_s_desc")));
} else {
	$mainframe->prependMetaTag( "description", strip_tags( $db_product->f("product_s_desc")) );
}


// Show an "Edit PRODUCT"-Link
if ($perm->check("admin,storeadmin")) {
	$edit_link = '<a href="'. $sess->url( 'index2.php?page=product.product_form&next_page=shop.product_details&product_id='.$product_id).'">
      <img src="'.$mosConfig_live_site.'/images/M_images/edit.png" alt="'. $VM_LANG->_('PHPSHOP_PRODUCT_FORM_EDIT_PRODUCT') .'" border="0" /></a>';
}
else {
	$edit_link = "";
}

// LINK TO MANUFACTURER POP-UP
$manufacturer_id = $ps_product->get_manufacturer_id($product_id);
$manufacturer_name = $ps_product->get_mf_name($product_id);
$manufacturer_link = "";
if( $manufacturer_id && !empty($manufacturer_name) ) {
	$link = "$mosConfig_live_site/index2.php?page=shop.manufacturer_page&amp;manufacturer_id=$manufacturer_id&amp;output=lite&amp;option=com_virtuemart&amp;Itemid=".$Itemid;
	$text = '( '.$manufacturer_name.' )';
	$manufacturer_link .= vmPopupLink( $link, $text );

	// Avoid JavaScript on PDF Output
	if( @$_REQUEST['output'] == "pdf" )
	$manufacturer_link = "<a href=\"$link\" target=\"_blank\" title=\"$text\">$text</a>";
}
// PRODUCT PRICE
if (_SHOW_PRICES == '1') { 
	if( $db_product->f("product_unit") && VM_PRICE_SHOW_PACKAGING_PRICELABEL) {
		$product_price_lbl = "<strong>". $VM_LANG->_('PHPSHOP_CART_PRICE_PER_UNIT').' ('.$db_product->f("product_unit")."):</strong>";
	}
	else {
		$product_price_lbl = "<strong>". $VM_LANG->_('PHPSHOP_CART_PRICE'). ": </strong>";
	}
	$product_price = $ps_product->show_price( $product_id );
}
else {
	$product_price_lbl = "";
	$product_price = "";
}
// @var array $product_price_raw The raw unformatted Product Price in Float Format
$product_price_raw = $ps_product->get_adjusted_attribute_price($product_id);
		
// Change Packaging - Begin
// PRODUCT PACKAGING
if (  $db_product->f("product_packaging") ) {
	$packaging = $db_product->f("product_packaging") & 0xFFFF;
	$box = ($db_product->f("product_packaging") >> 16) & 0xFFFF;
	$product_packaging = "";
	if ( $packaging ) {
		$product_packaging .= $VM_LANG->_('PHPSHOP_PRODUCT_PACKAGING1').$packaging;
		if( $box ) $product_packaging .= "<br/>";
	}
	if ( $box ) {
		$product_packaging .= $VM_LANG->_('PHPSHOP_PRODUCT_PACKAGING2').$box;
	}

	$product_packaging = str_replace("{unit}",$db_product->f("product_unit")?$db_product->f("product_unit") : $VM_LANG->_('PHPSHOP_PRODUCT_FORM_UNIT_DEFAULT'),$product_packaging);
}
else {
	$product_packaging = "";
}
// Change Packaging - End

// PRODUCT IMAGE
$product_full_image = $product_parent_id!=0 && !$db_product->f("product_full_image") ?
$dbp->f("product_full_image") : $db_product->f("product_full_image"); // Change
$product_thumb_image = $product_parent_id!=0 && !$db_product->f("product_thumb_image") ?
$dbp->f("product_thumb_image") : $db_product->f("product_thumb_image"); // Change

/* MORE IMAGES ??? */
$files = ps_product_files::getFilesForProduct( $product_id );

$more_images = "";
if( !empty($files['images']) ) {
	$more_images = $tpl->vmMoreImagesLink( $files['images'] );
}
// Does the Product have files?
$file_list = ps_product_files::get_file_list( $files['product_id'] );

$product_availability = '';

if( @$_REQUEST['output'] != "pdf" ) {
	// Show the PDF, Email and Print buttons
	$tpl->set('option', $option);
	$tpl->set('category_id', $category_id );
	$tpl->set('product_id', $product_id );
	$buttons_header = $tpl->fetch( 'common/buttons.tpl.php' );
	$tpl->set( 'buttons_header', $buttons_header );

	// AVAILABILITY 
	// This is the place where it shows: Availability: 24h, In Stock: 5 etc.
	// You can make changes to this functionality in the file: classes/ps_product.php
	$product_availability = $ps_product->get_availability($product_id);
}
$product_availability_data = $ps_product->get_availability_data($product_id);

/** Ask seller a question **/
$ask_seller_href = $sess->url( $_SERVER ['PHP_SELF'].'?page=shop.ask&amp;flypage='.@$_REQUEST['flypage']."&amp;product_id=$product_id&amp;category_id=$category_id" );
$ask_seller_text = $VM_LANG->_('VM_PRODUCT_ENQUIRY_LBL');
$ask_seller = '<a class="button" href="'. $ask_seller_href .'">'. $ask_seller_text .'</a>';

/* SHOW RATING */
$product_rating = "";
if (PSHOP_ALLOW_REVIEWS == '1') {
	$product_rating = ps_reviews::allvotes( $product_id );
}

$product_reviews = $product_reviewform = "";
/* LIST ALL REVIEWS **/
if (PSHOP_ALLOW_REVIEWS == '1') {
	/*** Show all reviews available ***/
	$product_reviews = ps_reviews::product_reviews( $product_id );
	/*** Show a form for writing a review ***/

	if( $auth['user_id'] > 0 ) {
		$product_reviewform = ps_reviews::reviewform( $product_id );
	}
}

/* LINK TO VENDOR-INFO POP-UP **/
$vend_id = $ps_product->get_vendor_id($product_id);
$vend_name = $ps_product->get_vendorname($product_id);

$link = "$mosConfig_live_site/index2.php?page=shop.infopage&amp;vendor_id=$vend_id&amp;output=lite&amp;option=com_virtuemart&amp;Itemid=".$Itemid;
$text = $VM_LANG->_('PHPSHOP_VENDOR_FORM_INFO_LBL');
$vendor_link = vmPopupLink( $link, $text );

// Avoid JavaScript on PDF Output
if( @$_REQUEST['output'] == "pdf" )
$vendor_link = "<a href=\"$link\" target=\"_blank\" title=\"$text\">$text</a>";

if ($product_parent_id!=0 && !$ps_product_type->product_in_product_type($product_id)) {
	$product_type = $ps_product_type->list_product_type($product_parent_id);
}
else {
	$product_type = $ps_product_type->list_product_type($product_id);
}


$recent_products = $ps_product->recentProducts($product_id,$tpl->get_cfg('showRecent', 5));
/**
* This has changed since VM 1.1.0  
* Now we have a template object that can use all variables 
* that we assign here.
* 
* Example: If you run
* $tpl->set( "product_name", $product_name );
* The variable "product_name" will be available in the template under this name
* with the value of $product_name
* 
* */

// This part allows us to copy ALL properties from the product table
// into the template
$productData = $db_product->get_row();
$productArray = get_object_vars( $productData );

$productArray["product_id"] = $product_id;
$productArray["product_full_image"] = $product_full_image; // to display the full image on flypage
$productArray["product_thumb_image"] = $product_thumb_image;
$productArray["product_name"] = shopMakeHtmlSafe($productArray["product_name"]);

$tpl->set( 'productArray', $productArray );
foreach( $productArray as $property => $value ) {
	$tpl->set( $property, $value);
}
// Assemble the thumbnail image as a link to the full image
// This function is defined in the theme (theme.php)
$product_image = $tpl->vmBuildFullImageLink( $productArray );

$tpl->set( "product_id", $product_id );
$tpl->set( "product_name", $product_name );
$tpl->set( "product_image", $product_image );
$tpl->set( "more_images", $more_images );
$tpl->set( "images", $files['images'] );
$tpl->set( "files", $files['files'] );
$tpl->set( "file_list", $file_list );
$tpl->set( "edit_link", $edit_link );
$tpl->set( "manufacturer_link", $manufacturer_link );
$tpl->set( "product_price", $product_price );
$tpl->set( "product_price_lbl", $product_price_lbl );
$tpl->set( 'product_price_raw', $product_price_raw );
$tpl->set( "product_description", $product_description );

/* ADD-TO-CART */
$tpl->set( 'manufacturer_id', $manufacturer_id );
$tpl->set( 'flypage', $flypage );
$tpl->set( 'ps_product_attribute', $ps_product_attribute );
$addtocart = $tpl->fetch('product_details/includes/addtocart_form.tpl.php' );

$tpl->set( "addtocart", $addtocart );
// Those come from separate template files
$tpl->set( "navigation_pathway", $navigation_pathway );
$tpl->set( "navigation_childlist", $navigation_childlist );
$tpl->set( "product_reviews", $product_reviews );
$tpl->set( "product_reviewform", $product_reviewform );
$tpl->set( "product_availability", $product_availability );
$tpl->set( "product_availability_data", $product_availability_data );

$tpl->set( "related_products", $related_products );
$tpl->set( "vendor_link", $vendor_link );
$tpl->set( "product_type", $product_type ); // Changed Product Type
$tpl->set( "product_packaging", $product_packaging ); // Changed Packaging
$tpl->set( "ask_seller_href", $ask_seller_href ); // Product Enquiry!
$tpl->set( "ask_seller_text", $ask_seller_text ); // Product Enquiry!
$tpl->set( "ask_seller", $ask_seller ); // Product Enquiry!
$tpl->set( "recent_products", $recent_products); // Recent products

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

		$html = '<img id="paypalLogo" src="'.$paypal_buttonurls[$lang_iso].'" alt="PayPal Checkout Available" border="0" style="cursor:pointer;" /></a>';
		$html .= '<script type="text/javascript">window.addEvent("domready", function() {
			$("paypalLogo").addEvent("click", function() {
				window.open(\''.$paypal_infolink[$lang_iso].'\',\'olcwhatispaypal\',\'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=400, height=500\');
				});
			});
			</script>';
		
		$tpl->set('paypalLogo', $html);
	}
}
/* Finish and Print out the Page */
echo $tpl->fetch( '/product_details/'.$flypage . '.php' );

?>
