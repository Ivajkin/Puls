<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
* @version $Id: global.php 1948 2009-09-30 14:32:48Z soeren_nb $
* @package VirtueMart
* @subpackage core
* @copyright Copyright (C) 2004-2008 soeren - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
*
* http://virtuemart.net
*/

global $vendor_image,$vendor_country_2_code ,$vendor_country_3_code, $vendor_image_url, $vendor_name, $vendor_state_name,
		$vendor_address,$vendor_address_2, $vendor_url, $vendor_city,$vendor_country,$vendor_mail,$vendor_store_name, $vm_mainframe,
        $vendor_state, $vendor_zip, $vendor_phone, $vendor_currency, $vendor_store_desc, $vendor_freeshipping,
        $module_description, $VM_LANG, $vendor_currency_display_style, $vendor_full_image, $vendor_accepted_currencies,
        $vendor_address_format, $vendor_date_format;

if( @VM_ENCRYPT_FUNCTION == 'AES_ENCRYPT') {
	define('VM_DECRYPT_FUNCTION', 'AES_DECRYPT');
} else {
	define('VM_DECRYPT_FUNCTION', 'DECODE');
}
if( !defined('VM_COMPONENT_NAME')) {
	echo '<div class="shop_warning">You seem to have upgraded to a new VirtueMart Version recently.<br />
			Your Configuration File must be updated. so please proceed to the <a href="'.$_SERVER['PHP_SELF'].'?page=admin.show_cfg&amp;option=com_virtuemart">Configuration Form</a> and save the Configuration once you are done with the settings.</div>';
	define('VM_COMPONENT_NAME', 'com_virtuemart');
	define('VM_CURRENCY_CONVERTER_MODULE', 'convertECB');
	defined('VM_THEMEPATH ') or define('VM_THEMEPATH', $mosConfig_absolute_path. '/components/com_virtuemart/themes/default/');
	defined('VM_THEMEURL') or define('VM_THEMEURL', $mosConfig_live_site. '/components/com_virtuemart/themes/default/');
}
// Instantiate the MainFrame class for VirtueMart
require_once( CLASSPATH."mainframe.class.php" );
$vm_mainframe = new vmMainFrame();

if (file_exists( CLASSPATH.'currency/'.@VM_CURRENCY_CONVERTER_MODULE.'.php' )) {
	$module_filename = VM_CURRENCY_CONVERTER_MODULE;
	require_once(CLASSPATH.'currency/'.VM_CURRENCY_CONVERTER_MODULE.'.php');
	if( class_exists( $module_filename )) {
		$GLOBALS['CURRENCY'] = $CURRENCY = new $module_filename();
	}
}
else {
	require_once(CLASSPATH.'currency/convertECB.php');
	/**
	 * @global convertECB $GLOBALS['CURRENCY']
	 */
	$GLOBALS['CURRENCY'] = $CURRENCY = new convertECB();
}

// stores the exchange rate array
$GLOBALS['converter_array'] = '';

/** @global Array $product_info: Stores Product Information for re-use */
$GLOBALS['product_info'] = Array();

/** @global Array $category_info: Stores Category Information for re-use */
$GLOBALS['category_info'] = Array();

/** @global Array $category_info: Stores Vendor Information for re-use */
$GLOBALS['vendor_info'] = Array();

// load the MAIN CLASSES
// CLASSPATH is defined in the config file
require_once(CLASSPATH."ps_database.php");
require_once(CLASSPATH."ps_main.php");
require_once(CLASSPATH."request.class.php");

/* @MWM1: Load debug utility functions (currently just vmShouldDebug())
   Replaces test (DEBUG == '1') and also checks if DEBUG_IP_ADDRESS is
   enabled. */
require_once(CLASSPATH."DebugUtil.php");

/* @MWM1: Initialize Logging */
$vmLogIdentifier = 'VirtueMart';
require_once(CLASSPATH."Log/LogInit.php");

// The abstract language class
require_once( CLASSPATH."language.class.php" );
/** @global vmLanguage $GLOBALS['VM_LANG'] */
$GLOBALS['VM_LANG'] = $GLOBALS['PHPSHOP_LANG'] = new vmLanguage();
// loading common language module
$VM_LANG->load('common');

// Raise memory_limit to 16M when it is too low
// Especially the product section needs much memory
vmRaiseMemoryLimit( '16M' );
	
require_once(CLASSPATH."vmAbstractObject.class.php");
require_once(CLASSPATH."ps_cart.php");
require_once(CLASSPATH."ps_html.php");
require_once(CLASSPATH."ps_session.php");
require_once(CLASSPATH."ps_function.php");
require_once(CLASSPATH."ps_module.php");
require_once(CLASSPATH."ps_perm.php");
require_once(CLASSPATH."ps_shopper_group.php");
require_once(CLASSPATH."ps_vendor.php");
require_once(CLASSPATH.'template.class.php' );
require_once(CLASSPATH."htmlTools.class.php");
require_once(CLASSPATH."phpInputFilter/class.inputfilter.php");

// Instantiate the DB class
$db = new ps_DB();

// Instantiate the permission class
$perm = new ps_perm();
// Instantiate the HTML helper class
$ps_html = new ps_html();

// Constructor initializes the session!
$sess = new ps_session();

// Instantiate the ps_shopper_group class
$ps_shopper_group = new ps_shopper_group();
// Get default and this users's Shopper Group
$shopper_group = $ps_shopper_group->get_shoppergroup_by_id( $my->id );

// User authentication
$auth = $perm->doAuthentication( $shopper_group );
// Initialize the cart
$cart = ps_cart::initCart();
// Initialise Recent Products
$recentproducts = ps_session::initRecentProducts();
// Instantiate the module class
$ps_module = new ps_module();
// Instantiate the function class
$ps_function = new ps_function();

// Set the mosConfig_live_site to its' SSL equivalent
$GLOBALS['real_mosConfig_live_site'] = $GLOBALS['mosConfig_live_site'];
if( $_SERVER['SERVER_PORT'] == 443 || @$_SERVER['HTTPS'] == 'on' || @strstr( $page, "checkout." )) {
	// Change the global Live Site Value to HTTPS
	$GLOBALS['mosConfig_live_site'] = ereg_replace('/$','',SECUREURL);
	$mm_action_url = SECUREURL;
}
else {
	$mm_action_url = URL;
}

// Enable Mambo Debug Mode when Shop Debug is on
if( vmShouldDebug() ) {   /*@MWM1: Log/Debug enhancements */
	$GLOBALS['mosConfig_debug'] = 1;
	$database->_debug = 1;
}

	
# Some database values we will need throughout
# Get Vendor Information
// Benjamin: change this using a dynamic global variable...
$default_vendor = 1;

if( $auth['user_id'] > 0 ) {
	$db->query( 'SELECT `vendor_id` FROM `#__{vm}_auth_user_vendor` WHERE `user_id` ='.$auth['user_id'] );
	$db->next_record();
	if( $db->f( 'vendor_id' ) ) {
		$default_vendor = $db->f( 'vendor_id' );
	}
}
	
$_SESSION["ps_vendor_id"] = $ps_vendor_id = $default_vendor;

$db = ps_vendor::get_vendor_details($ps_vendor_id);

$_SESSION['minimum_pov'] = $db->f("vendor_min_pov"); 
$vendor_name = $db->f("vendor_name");
$vendor_store_name = $db->f("vendor_store_name");
$vendor_mail = $db->f("contact_email");
$vendor_url = $db->f("vendor_url");
$vendor_freeshipping = $db->f("vendor_freeshipping");
$vendor_image = "<img border=\"0\" src=\"" .IMAGEURL ."vendor/" . $db->f("vendor_full_image") . "\" alt=\"" . shopMakeHtmlSafe($vendor_name) . "\" />";
$vendor_full_image = $db->f("vendor_full_image");
$vendor_image_url = IMAGEURL."vendor/".$db->f("vendor_full_image");
$vendor_address = $db->f("vendor_address_1");
$vendor_address_2 = $db->f("vendor_address_2");
$vendor_city = $db->f("vendor_city");
$vendor_state = $db->f("vendor_state");
$vendor_state_name = $db->f("state_name");
$vendor_state = empty($vendor_state) ? "" : $db->f("vendor_state");
$vendor_country = $db->f("vendor_country");
$vendor_country_2_code = $db->f("country_2_code");
$vendor_country_3_code = $db->f("country_3_code");
$vendor_zip = $db->f("vendor_zip");
$vendor_phone = $db->f("vendor_phone");
$vendor_store_desc = $db->f("vendor_store_desc");
$vendor_currency = $db->f("vendor_currency");
$vendor_currency_display_style = $db->f("vendor_currency_display_style");
$vendor_accepted_currencies = $db->f("vendor_accepted_currencies");
$vendor_address_format = $db->f('vendor_address_format');
$vendor_date_format = $db->f('vendor_date_format');
$_SESSION["vendor_currency"] = $vendor_currency;

// see /classes/currency_convert.php
vmSetGlobalCurrency();

$currency_display = ps_vendor::get_currency_display_style( $vendor_currency_display_style );
if( $GLOBALS['product_currency'] != $vendor_currency ) {
	$currency_display["symbol"] = $GLOBALS['product_currency'];
}
/** load Currency Display Class **/
require_once( CLASSPATH.'currency/class_currency_display.php' );
/**
 *  @global CurrencyDisplay $GLOBALS['CURRENCY_DISPLAY']
 *  @global CurrencyDisplay $CURRENCY_DISPLAY
 */
$CURRENCY_DISPLAY = $GLOBALS['CURRENCY_DISPLAY'] = new CurrencyDisplay($currency_display["id"], $currency_display["symbol"], $currency_display["nbdecimal"], $currency_display["sdecimal"], $currency_display["thousands"], $currency_display["positive"], $currency_display["negative"]);
	
// Include the theme
if( file_exists( VM_THEMEPATH.'theme.php' )) {
	include( VM_THEMEPATH.'theme.php' );
}
elseif( file_exists( $mosConfig_absolute_path.'/components/'.$option.'/themes/default/theme.php' )) {
	include( $mosConfig_absolute_path.'/components/'.$option.'/themes/default/theme.php' );
}
else {
	$vmLogger->crit( 'Theme file not found.' );
	return;
}
$GLOBALS['VM_THEMECLASS'] = 'vmTheme';

/**
 * Returns the variable names of all global variables in VM
 *
 * @return array
 */
function vmGetGlobalsArray() {
	static $vm_globals = array(  'perm', 'page', 'sess', 'func', 'cart', 'VM_LANG', 'PSHOP_SHIPPING_MODULES', 'VM_BROWSE_ORDERBY_FIELDS', 
					'VM_MODULES_FORCE_HTTPS', 'vmLogger', 'CURRENCY_DISPLAY', 'CURRENCY', 'ps_html', 
					'ps_vendor_id', 'keyword', 'ps_payment_method', 'pagename', 'modulename', 
					'vars', 'auth', 'ps_checkout', 'vendor_image','vendor_country_2_code','vendor_country_3_code', 'vendor_state_name',
					'vendor_image_url', 'vendor_name', 'vendor_address', 'vendor_address_2', 'vendor_city','vendor_country','vendor_mail',
					'vendor_store_name', 'vendor_state', 'vendor_zip', 'vendor_phone', 'vendor_currency', 'vendor_store_desc', 
					'vendor_freeshipping', 'vendor_currency_display_style', 'vendor_freeshipping', 'vendor_date_format', 'vendor_address_format',
					'mm_action_url', 'limit', 'limitstart', 'vmInputFilter', 'mainframe', 'mosConfig_lang',
					'option', 'my', 'Itemid', 'mosConfig_live_site', 'mosConfig_absolute_path' );
	return $vm_globals;
}
?>
