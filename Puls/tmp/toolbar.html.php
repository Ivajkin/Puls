<?php 
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) {
	die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
}
/**
*
* @version $Id: toolbar.html.php 1736 2009-04-22 22:56:47Z macallf $
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

$_REQUEST['keyword'] = urldecode(vmGet($_REQUEST, 'keyword', ''));
$keyword = $_REQUEST['keyword'];

class TOOLBAR_virtuemart {
	/**
	* The function to handle all default page situations
	* not responsible for lists!
	*/
    function FORMS_MENU_SAVE_CANCEL() {     
        global $vm_mainframe, $mosConfig_live_site, $mosConfig_lang, $VM_LANG, 
        		$page, $limitstart,	$vmIcons;
		$no_menu = (int)$_REQUEST['no_menu'];
		$bar = & vmToolBar::getInstance('virtuemart');		
        
        $is_iframe = vmGet( $_REQUEST, 'is_iframe', 0 );
        $product_parent_id = vmGet( $_REQUEST, 'product_parent_id', 0 );
        $product_id = vmGet( $_REQUEST, 'product_id' );
        $script = '';
        
		if( is_array( $product_id )) {
			$product_id = "";
		}
		
		// These editor arrays tell the toolbar to load correct "getEditorContents" script parts
		// This is necessary for WYSIWYG Editors like TinyMCE / mosCE / FCKEditor
        $editor1_array = Array('product.product_form' => 'product_desc', 'shopper.shopper_group_form' => 'shopper_group_desc',
								'product.product_category_form' => 'category_description', 'manufacturer.manufacturer_form' => 'mf_desc',
								'store.store_form' => 'vendor_store_desc',
								'product.product_type_parameter_form' => 'parameter_description',
								'product.product_type_form' => 'product_type_description',
								'vendor.vendor_form' => 'vendor_store_desc');
        $editor2_array = Array('store.store_form' => 'vendor_terms_of_service',
								'vendor.vendor_form' => 'vendor_terms_of_service');
		
		$editor1 = isset($editor1_array[$page]) ? $editor1_array[$page] : '';
		$editor2 = isset($editor2_array[$page]) ? $editor2_array[$page] : '';
		if( $no_menu ) {
			vmCommonHTML::loadExtJS();
		}
		$script .= '
var submitbutton = function(pressbutton){
	
	var form = document.adminForm;
	if (pressbutton == \'cancel\') {
		submitform( pressbutton );
		return;
	}	
';
        
    	if ($editor1 != '') {
			if( vmIsJoomla(1.5) ) {
				jimport('joomla.html.editor');
				$editor_type = $GLOBALS['mainframe']->getCfg('editor');
				if( $editor_type != 'none' ) {
					$editor = JEditor::getInstance();
					$script .= $editor->getContent($editor1);
				}
			} else {
				ob_start();
				getEditorContents( 'editor1', $editor1 );
				$script .= ob_get_contents(); ob_end_clean();
			}
		}
		if ($editor2 != '') {
			if( vmIsJoomla(1.5) ) {
				jimport('joomla.html.editor');
				$editor_type = $GLOBALS['mainframe']->getCfg('editor');
				if( $editor_type != 'none' ) {
					$editor = JEditor::getInstance();
					$script .= $editor->getContent($editor2);
				}
			} else {
				ob_start();
				getEditorContents( 'editor2', $editor2 );
				$script .= ob_get_contents(); ob_end_clean();
			}
		}
		if( $no_menu ) {
			$admin = defined('_VM_IS_BACKEND') ? '/administrator' : '';
			$script .= "
    // define some private variables
    var dialog, showBtn;

    var showDialog = function( content ) {
    	Ext.Msg.show( { 
            		title: '" . $VM_LANG->_('PEAR_LOG_NOTICE') . "',
            		msg: content,
            		autoCreate: true,
                    width:400,
                    height:180,
                    modal: false,
                    resizable: false,
                    buttons: Ext.Msg.OK,
                    shadow:true,
                    animEl:Ext.get( 'vm-toolbar' )
            });
        ".(DEBUG ? "" : "setTimeout('Ext.Msg.hide()', 3000);")."
    };
    
    // return a public interface
    var onSuccess = function(o,c) {
		showDialog( o.responseText );
	};
    var onFailure = function(o) {
		Ext.Msg.alert( 'Error!', 'Save action failed: ' + o.statusText );
	};
	var onCallback=function(o,s,r) {
		//if( s ) alert( 'Success' );
		//else alert( 'Failure' );
	}
	
   	Ext.Ajax.request( { method: 'POST',
   						url: '{$_SERVER['PHP_SELF']}',
   						success: onSuccess,
   						failure: onFailure,
   						callback: onCallback,
   						isUpload: true,
   						form: document.adminForm,
   						params: { no_html:1 }
   						}
   					);
	";

		}
		else {
			$script .= "\n\t\t\tsubmitform( pressbutton );\n";
		}
		
		$script .= "\t\t}\n";
		
        $vm_mainframe->addScriptDeclaration($script);
		
		if ($page == "product.product_form" && !empty($product_id)) {
			if( empty($product_parent_id) ) { 
				// add new attribute
				$href=$_SERVER['PHP_SELF']."?option=com_virtuemart&page=product.product_attribute_form&product_id=". $product_id ."&limitstart=". $limitstart."&no_menu=$no_menu";
				$alt =  $VM_LANG->_('PHPSHOP_ATTRIBUTE_FORM_MNU');
				$bar->customHref( $href, 'new', $alt );
				
			}
			else {
                // back to parent product
				$href=$_SERVER['PHP_SELF']."?option=com_virtuemart&page=product.product_form&product_id=$product_parent_id&limitstart=".$limitstart."&no_menu=$no_menu";
				$alt =  $VM_LANG->_('PHPSHOP_PRODUCT_FORM_RETURN_LBL');
				$bar->customHref( $href, $vmIcons['back_icon'], $vmIcons['back_icon2'], $alt );
				
				// new child product
				$href=$_SERVER['PHP_SELF']."?option=com_virtuemart&page=product.product_form&product_parent_id=$product_parent_id&limitstart=". $limitstart."&no_menu=$no_menu";
				$alt =  $VM_LANG->_('PHPSHOP_PRODUCT_FORM_ADD_ANOTHER_ITEM_MNU');
				$bar->customHref( $href, 'new', $alt );
				
			} 
			// Go to Price list
			$href = $_SERVER['PHP_SELF']."?page=product.product_price_list&product_id=$product_id&product_parent_id=$product_parent_id&limitstart=$limitstart&return_args=&option=com_virtuemart&no_menu=$no_menu";
			$alt =  $VM_LANG->_('PHPSHOP_PRICE_LIST_MNU');
			$bar->customHref( $href, 'new', $alt );
			
	
			// add product type
			$href= $_SERVER['PHP_SELF']."?option=com_virtuemart&page=product.product_product_type_form&product_id=$product_id&product_parent_id=$product_parent_id&limitstart=$limitstart&no_menu=$no_menu";
			$alt =  $VM_LANG->_('PHPSHOP_PRODUCT_PRODUCT_TYPE_FORM_MNU');
			$bar->customHref( $href, 'new', $alt );
			
			
			/*** Adding an item is only pssible, if the product has attributes ***/
			if (ps_product::product_has_attributes( $product_id ) ) { 
				// Add Item
				$href=$_SERVER['PHP_SELF']."?option=com_virtuemart&page=product.product_form&product_parent_id=$product_id&limitstart=$limitstart&no_menu=$no_menu";
				$alt =  $VM_LANG->_('PHPSHOP_PRODUCT_FORM_NEW_ITEM_LBL');
				$bar->customHref( $href, 'new', $alt );
				
			}
			$bar->divider();
		}
		elseif( $page == "admin.country_form" ) {
            if( !empty( $_REQUEST['country_id'] )) {
				$href= $_SERVER['PHP_SELF'] ."?option=com_virtuemart&page=admin.country_state_form&country_id=". intval($_REQUEST['country_id']) ."&limitstart=$limitstart&no_menu=$no_menu";
				$alt = $VM_LANG->_('PHPSHOP_ADD_STATE');
				$bar->customHref( $href, 'new', $alt );
				
				
				$href = $_SERVER['PHP_SELF'] ."?option=com_virtuemart&page=admin.country_state_list&country_id=". intval($_REQUEST['country_id']) ."&limitstart=$limitstart&no_menu=$no_menu";
				$alt = $VM_LANG->_('PHPSHOP_LIST_STATES');
				$bar->customHref( $href, 'new', $alt );			
				
				$bar->divider();
			}
		}		
		
		$bar->save( 'save', $VM_LANG->_('CMN_SAVE') );			
			
		//$bar->apply( 'apply', $VM_LANG->_('E_APPLY') );
        
		$bar->cancel();		
		
    }
    /**
	* The function for all page which allow adding new items
	* usually when page= *.*_list
	*/
    function LISTS_MENU_NEW() {
        global $page, $mosConfig_live_site, $VM_LANG, $limitstart;
		$bar = & vmToolBar::getInstance('virtuemart');
        $my_page = str_replace('list','form',$page);
		
        $bar->addNew( "new", $my_page, $VM_LANG->_('CMN_NEW') );
		
        if ($page == 'admin.country_state_list' && vmGet( $_SESSION, 'vmLayout', 'extended' ) == 'standard') {
			// Back to the country
			$bar->divider();
			$href = $_SERVER['PHP_SELF']. '?option=com_virtuemart&page=admin.country_list';
			$bar->customHref( $href, 'back', '&nbsp;'.$VM_LANG->_('PHPSHOP_BACK_TO_COUNTRY') );
        }
        elseif ($page == 'product.file_list') {
			// Close the window
			$bar->divider();
			$bar->cancel();
        }
   
        
    }
	/**
	* Draws a list publish button
	*/
    function LISTS_MENU_PUBLISH( $funcName ) {
		$bar = & vmToolBar::getInstance('virtuemart');
		$bar->publishList( $funcName );
		
		$bar->unpublishList( $funcName );
		
	}
	/**
	* Draws a list delete button
	*/
    function LISTS_MENU_DELETE( $funcName ) {
		$bar = & vmToolBar::getInstance('virtuemart');
		$bar->deleteList( $funcName );		
	}
	
	/** 
	* Handles special task selectors for pages
	* like the product list
	*/
	function LISTS_SPECIAL_TASKS( $page ) {
		global $mosConfig_live_site, $VM_LANG, $product_id;
		
		$bar = & vmToolBar::getInstance('virtuemart');
		switch( $page ) {
		
			case "product.product_list":
			
				if( empty($_REQUEST['product_parent_id']) ) { 
					// add new attribute
					$alt =  $VM_LANG->_('PHPSHOP_ATTRIBUTE_FORM_MNU');
					$bar->custom( 'new', "product.product_attribute_form", 'new', $alt );
					
				}
				// Go to Price list
				$alt =  $VM_LANG->_('PHPSHOP_PRICE_LIST_MNU');
				$bar->custom( 'new', "product.product_price_list", 'new', $alt );				
		
				// add product type
				$alt =  $VM_LANG->_('PHPSHOP_PRODUCT_PRODUCT_TYPE_FORM_MNU');
				$bar->custom( 'new', "product.product_product_type_form", 'new', $alt );			
		
				/*** Adding an item is only pssible, if the product has attributes ***/
				if (ps_product::product_has_attributes( $product_id ) ) { 
					// Add Item
					$alt =  $VM_LANG->_('PHPSHOP_PRODUCT_FORM_NEW_ITEM_LBL');
					$bar->custom( 'new', "product.product_child_form", 'new', $alt );
				}
				$bar->divider();
				
				if( !empty( $_REQUEST['category_id'])) {
					$alt = $VM_LANG->_('VM_PRODUCTS_MOVE_TOOLBAR');
					$bar->custom( 'move', 'product.product_move', 'move', $alt );
					
					$bar->divider();
					
				}
				break;
			
			case "admin.country_list":

					$alt = $VM_LANG->_('PHPSHOP_ADD_STATE');
					$bar->custom( 'new', "admin.country_state_form", 'new', $alt );					
					
					$alt = $VM_LANG->_('PHPSHOP_LIST_STATES');
					$bar->custom( 'new', "admin.country_state_list", 'new', $alt );
					
					$bar->divider();
					
				break;
			
		}
		
	}
	
	
	/**
	* Draws the menu for a New users
	*/
	function _NEW_USERS() {
		$bar = & vmToolBar::getInstance('virtuemart');
		$bar->save();
		$bar->cancel();
	}
	
	function _EDIT_USERS() {
		$bar = & vmToolBar::getInstance('virtuemart');
		$bar->save();
		$bar->cancel();
	}
	
	function _DEFAULT_USERS() {
		$bar = & vmToolBar::getInstance('virtuemart');
		
		$bar->addNew();
		$bar->editList();
		$bar->deleteList();
		
		$bar->custom( 'remove_as_customer', 'admin.user_list', 'remove', 'Remove as Customer' );		
	}
  
}
