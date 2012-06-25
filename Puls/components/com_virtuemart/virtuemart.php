<?php 
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: virtuemart.php 3457 2011-06-07 20:03:23Z zanardi $
* @package VirtueMart
* @subpackage core
* @copyright Copyright (C) 2004-2008 soeren - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/

global $mosConfig_absolute_path, $product_id, $vmInputFilter, $vmLogger;

/* Load the virtuemart main parse code */
require_once( dirname(__FILE__) . '/virtuemart_parser.php' );
$my_page= explode ( '.', $page );
$modulename = $my_page[0];
$pagename = $my_page[1];

$is_popup = vmRequest::getBool( 'pop' );

// Page Navigation Parameters
$limit = intval( $vm_mainframe->getUserStateFromRequest( "viewlistlimit{$page}", 'limit', $mosConfig_list_limit ) );
$limitstart = intval( $vm_mainframe->getUserStateFromRequest( "view{$keyword}{$category_id}{$pagename}limitstart", 'limitstart', 0 )) ;

/* Get all the other parameters */
$search_category= vmRequest::getInt( 'search_category' );
// Display just the naked page without toolbar, menu and footer?
$only_page = vmRequest::getInt('only_page', 0 );

if( PSHOP_IS_OFFLINE == '1' && !$perm->hasHigherPerms('storeadmin') ) {
    echo PSHOP_OFFLINE_MESSAGE;
}
else {
	if( PSHOP_IS_OFFLINE == '1' ) {
		echo '<h2>'.$VM_LANG->_('OFFLINE_MODE').'</h2>';
	}
	if( $is_popup ) {
		echo "<style type='text/css' media='print'>.vmNoPrint { display: none }</style>";
		echo vmCommonHTML::PrintIcon('', true, ' '.$VM_LANG->_('CMN_PRINT') );
	}
	
	// The Vendor ID is important
	$ps_vendor_id = $_SESSION['ps_vendor_id'];

	// The authentication array
	$auth = $_SESSION['auth'];
	$no_menu = vmRequest::getInt('no_menu', 0 );

	// Timer Start
	if ( vmShouldDebug() ) { /*@MWM1: Log/Debug enhancements */
		$start = utime();
		$GLOBALS["mosConfig_debug"] = 1;
	}

	// update the cart because something could have
	// changed while running a function
	$cart = $_SESSION["cart"];


	if (( !$pagePermissionsOK || !$funcParams ) && $_REQUEST['page'] != 'checkout.index') {

		if( !$pagePermissionsOK && defined('_VM_PAGE_NOT_AUTH') ) {
			$page = 'checkout.login_form';
			echo '<br/><br/>'.$VM_LANG->_('DO_LOGIN').'<br/><br/>';
		}
		elseif( !$pagePermissionsOK && defined('_VM_PAGE_NOT_FOUND') ) {
			$page = HOMEPAGE;
		}
		else {
			$page = $_SESSION['last_page'];
		}
	}

	$my_page= explode ( '.', $page );
	$modulename = $my_page[0];
	$pagename = $my_page[1];

	// For there's no errorpage to display the error,
	// we must echo it before the page is loaded
	if (!empty($error) && $page != ERRORPAGE) {
		echo '<span class="shop_error">'.$error.'</span>';
	}

	/*****************************
	** FRONTEND ADMIN - MOD
	**/
	if ( vmIsAdminMode()
		&& $perm->check("admin,storeadmin")
		&& ((!stristr($my->usertype, "admin") ^ PSHOP_ALLOW_FRONTENDADMIN_FOR_NOBACKENDERS == '' )
			|| stristr($my->usertype, "admin")
			)
		&& !stristr($page, "shop.")
	) {
		
		define( '_FRONTEND_ADMIN_LOADED', '1' );
		
		if( vmIsJoomla(1.5) ) {
			$editor =& JFactory::getEditor();
			echo $editor->initialise();
		} else {
			$mainframe->loadEditor = 1;
			require_once( $mosConfig_absolute_path."/editor/editor.php" );
			initEditor();
		}

		$editor1_array = Array('product.product_form' => 'product_desc',
		'product.product_category_form' => 'category_description',
		'store.store_form' => 'vendor_store_desc',
		'vendor.vendor_form' => 'vendor_store_desc');
		$editor2_array = Array('store.store_form' => 'vendor_terms_of_service',
		'vendor.vendor_form' => 'vendor_terms_of_service');
		editorScript(isset($editor1_array[$page]) ? $editor1_array[$page] : '', isset($editor2_array[$page]) ? $editor2_array[$page] : '');
		
		$vm_mainframe->addStyleSheet( VM_THEMEURL .'admin.css' );
		$vm_mainframe->addStyleSheet( VM_THEMEURL .'admin.styles.css' );
		$vm_mainframe->addScript( "$mosConfig_live_site/components/$option/js/functions.js" );
		echo '<table style="width:100%;table-layout:fixed;"><tr>';
		if( $no_menu != "1" ) {
			$vmLayout = 'standard';
			echo '<td valign="top" width="15%">';
			// The admin header with dropdown menu
			include( ADMINPATH."header.php" );
			echo '</td>';
		}
		echo '<td width="80%" valign="top" style="border: 1px solid silver;padding:4px;">';
		include( ADMINPATH."toolbar.virtuemart.php" );
		echo '<br style="clear:both;" />';

	}
	/**
	** END: FRONTEND ADMIN - MOD
	*****************************/

	// Here is the most important part of the whole Shop:
	// LOADING the requested page for displaying it to the customer.
        // I have wrapped it with a function, because it becomes
        // cacheable that way.
        // It's just an "include" statement which loads the page
        $vmDoCaching = ($page=="shop.browse" || $page=="shop.product_details") 
                        && (empty($keyword) && empty($keyword1) && empty($keyword2));
		
        // IE6 PNG transparency fix
        $vm_mainframe->addScript( "$mosConfig_live_site/components/$option/js/sleight.js" );

		echo '<div id="vmMainPage">'."\n";
		
		// Load requested PAGE
		// added/mod by JK to support user pages
		$user_path=VM_THEMEPATH.'user_pages/';
		if( file_exists( $user_path.$modulename.".".$pagename.".php" )) {
			if( $only_page) {
				require_once( CLASSPATH . 'connectionTools.class.php' );
				vmConnector::sendHeaderAndContent( 200 );
				if( $func ) echo vmCommonHTML::getSuccessIndicator( $ok, $vmDisplayLogger ); /*@MWM1: Log/Debug enhancements*/
				include( $user_path.$modulename.".".$pagename.".php" );
				// Exit gracefully
				$vm_mainframe->close(true);
			}
			include( $user_path.$modulename.".".$pagename.".php" );
		}
		elseif( file_exists( PAGEPATH.$modulename.".".$pagename.".php" )) {
		// added/mod by JK to support user pages ends
			if( $only_page) {
				require_once( CLASSPATH . 'connectionTools.class.php' );
				vmConnector::sendHeaderAndContent( 200 );
				if( $func ) echo vmCommonHTML::getSuccessIndicator( $ok, $vmDisplayLogger ); /*@MWM1: Log/Debug enhancements*/
				include( PAGEPATH.$modulename.".".$pagename.".php" );
				// Exit gracefully 
				$vm_mainframe->close(true);
			}
			include( PAGEPATH.$modulename.".".$pagename.".php" );
		}
		elseif( file_exists( PAGEPATH . HOMEPAGE.'.php' )) {
			include( PAGEPATH . HOMEPAGE.'.php' );
		}
	    else {
	        include( PAGEPATH.'shop.index.php');
	    }
	    if ( !empty($mosConfig_caching) && $vmDoCaching) {
	        echo '<span class="small">'.$VM_LANG->_('LAST_UPDATED').': '.strftime( $vendor_date_format ).'</span>';
	    }
	    
	    echo "\n<div id=\"statusBox\" style=\"text-align:center;display:none;visibility:hidden;\"></div></div>\n";
	    
	    if(SHOWVERSION && !$is_popup) {
			include(PAGEPATH ."footer.php");
	    }

		// Set debug option on/off
		if (vmShouldDebug()) {  /*@MWM1: Log/Debug enhancements */
			$end = utime();
			$runtime = $end - $start;
			
			include( PAGEPATH . "shop.debug.php" );
		}

}
$vm_mainframe->close();
?>