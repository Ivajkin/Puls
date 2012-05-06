<?php 
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: toolbar.virtuemart.html.php 1736 2009-04-22 22:56:47Z macallf $
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
$no_menu = vmGet( $_REQUEST, 'no_menu', 0 );


global $vmIcons;
$vmIcons['back_icon'] = $mosConfig_live_site."/administrator/images/back.png";
$vmIcons['back_icon2'] = $mosConfig_live_site."/administrator/images/back_f2.png";
$vmIcons['cancel_icon'] = $mosConfig_live_site."/administrator/images/cancel.png";
$vmIcons['cancel_icon2'] = $mosConfig_live_site."/administrator/images/cancel_f2.png";	
$vmIcons['new_icon'] = $mosConfig_live_site."/administrator/images/new.png";
$vmIcons['new_icon2'] = $mosConfig_live_site."/administrator/images/new_f2.png";
$vmIcons['save_icon'] = $mosConfig_live_site."/administrator/images/save.png";
$vmIcons['save_icon2'] = $mosConfig_live_site."/administrator/images/save_f2.png";
$vmIcons['delete_icon'] = $mosConfig_live_site."/administrator/images/delete.png";
$vmIcons['delete_icon2'] = $mosConfig_live_site."/administrator/images/delete_f2.png";
$vmIcons['publish_icon'] = $mosConfig_live_site."/administrator/images/publish.png";
$vmIcons['publish_icon2'] = $mosConfig_live_site."/administrator/images/publish_f2.png";	
$vmIcons['unpublish_icon'] = $mosConfig_live_site."/administrator/images/unpublish.png";
$vmIcons['unpublish_icon2'] = $mosConfig_live_site."/administrator/images/unpublish_f2.png";	
$vmIcons['apply_icon'] = $mosConfig_live_site."/administrator/images/apply.png";
$vmIcons['apply_icon2'] = $mosConfig_live_site."/administrator/images/apply_f2.png";

class MENU_virtuemart {
	/**
	* The function to handle all default page situations
	* not responsible for lists!
	*/
    function FORMS_MENU_SAVE_CANCEL() {     
        global $mosConfig_absolute_path,$mosConfig_live_site, $mosConfig_lang, $VM_LANG, 
        		$product_id, $page, $limitstart,	$mosConfig_editor, $vmIcons;

		$bar = & JToolBar::getInstance('toolbar');

        $product_id = vmGet( $_REQUEST, 'product_id', 0 );
        $no_menu = vmGet( $_REQUEST, 'no_menu', 0 );
        $is_iframe = vmGet( $_REQUEST, 'is_iframe', 0 );
        $product_parent_id = vmGet( $_REQUEST, 'product_parent_id', 0 );
        
        $script = '';
        $clone_product = vmRequest::getInt( 'clone_product', 0 );
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
			vmCommonHTML::loadExtjs();
		}
		$script .= '<script type="text/javascript">
        	function submitbutton(pressbutton) {
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

   // the second argument is true to indicate file upload.
   YAHOO.util.Connect.setForm(form, true);
   
    var showDialog = function( content ) {
    	Ext.MessageBox.show( { 
            		title: '" . $VM_LANG->_('PEAR_LOG_NOTICE') . "',
            		msg: content,
            		autoCreate: true,
                    width:400,
                    height:180,
                    modal: false,
                    resizable: false,
                    buttons: Ext.MessageBox.OK,
                    shadow:true,
                    animEl:Ext.get( 'vm-toolbar' )
            });
        setTimeout('Ext.MessageBox.hide()', 3000);
    };
    
    // return a public interface
    var callback = {
    	success: function(o) {
    		//Ext.DomHelper.insertHtml( document.body, o.responseText );
    		showDialog( o.responseText );
    	},
    	failure: function(o) {
    		Ext.DomHelper.append( document.body, { tag: 'div', id: 'vmLogResult', html: 'Save action failed: ' + o.statusText } );
    		showDialog( o.responseText );
    	},
        upload : function(o){
            //Ext.DomHelper.insertHtml( 'beforeEnd', document.body, o.responseText );
    		showDialog( o.responseText );
        }
    };
    
   	var cObj = YAHOO.util.Connect.asyncRequest('POST', '{$_SERVER['PHP_SELF']}', callback);
	
			\n";

		}
		else {
			$script .= "\n\t\t\tsubmitform( pressbutton );\n";
		}
		
		$script .= "\t\t}
		</script>";
		
        $bar->appendButton( 'Custom', $script );		
		
		vmMenuBar::startTable();
		
		if ($page == "product.product_form" && !empty($product_id) && $clone_product!=1) {
			if( empty($product_parent_id) ) { 
				// add new attribute
				$href=$_SERVER['PHP_SELF']."?option=com_virtuemart&page=product.product_attribute_form&product_id=". $product_id ."&limitstart=". $limitstart."&no_menu=$no_menu";
				$alt = "&nbsp;". $VM_LANG->_('PHPSHOP_ATTRIBUTE_FORM_MNU');
				vmMenuBar::customHref( $href, $vmIcons['new_icon'], $vmIcons['new_icon2'], $alt );
				vmMenuBar::spacer();
			}
			else {
                // back to parent product
				$href=$_SERVER['PHP_SELF']."?option=com_virtuemart&page=product.product_form&product_id=$product_parent_id&limitstart=".$limitstart."&no_menu=$no_menu";
				$alt = "&nbsp;". $VM_LANG->_('PHPSHOP_PRODUCT_FORM_RETURN_LBL');
				vmMenuBar::customHref( $href, $vmIcons['back_icon'], $vmIcons['back_icon2'], $alt );
				vmMenuBar::spacer();
				// new child product
				$href=$_SERVER['PHP_SELF']."?option=com_virtuemart&page=product.product_form&product_parent_id=$product_parent_id&limitstart=". $limitstart."&no_menu=$no_menu";
				$alt = "&nbsp;". $VM_LANG->_('PHPSHOP_PRODUCT_FORM_ADD_ANOTHER_ITEM_MNU');
				vmMenuBar::customHref( $href, $vmIcons['new_icon'], $vmIcons['new_icon2'], $alt );
				vmMenuBar::spacer();
			} 
			// Go to Price list
			$href = $_SERVER['PHP_SELF']."?page=product.product_price_list&product_id=$product_id&product_parent_id=$product_parent_id&limitstart=$limitstart&return_args=&option=com_virtuemart&no_menu=$no_menu";
			$alt = "&nbsp;". $VM_LANG->_('PHPSHOP_PRICE_LIST_MNU');
			vmMenuBar::customHref( $href, $vmIcons['new_icon'], $vmIcons['new_icon2'], $alt );
			vmMenuBar::spacer();
	
			// add product type
			$href= $_SERVER['PHP_SELF']."?option=com_virtuemart&page=product.product_product_type_form&product_id=$product_id&product_parent_id=$product_parent_id&limitstart=$limitstart&no_menu=$no_menu";
			$alt = "&nbsp;". $VM_LANG->_('PHPSHOP_PRODUCT_PRODUCT_TYPE_FORM_MNU');
			vmMenuBar::customHref( $href, $vmIcons['new_icon'], $vmIcons['new_icon2'], $alt );
			vmMenuBar::spacer();
			
			/*** Adding an item is only pssible, if the product has attributes ***/
			if (ps_product::product_has_attributes( $product_id ) ) { 
				// Add Item
				$href=$_SERVER['PHP_SELF']."?option=com_virtuemart&page=product.product_form&product_parent_id=$product_id&limitstart=$limitstart&no_menu=$no_menu";
				$alt = "&nbsp;". $VM_LANG->_('PHPSHOP_PRODUCT_FORM_NEW_ITEM_LBL');
				vmMenuBar::customHref( $href, $vmIcons['new_icon'], $vmIcons['new_icon2'], $alt );
				vmMenuBar::spacer();
			}
			vmMenuBar::divider();
		}
		elseif( $page == "admin.country_form" ) {
            if( !empty( $_REQUEST['country_id'] )) {
				$href= $_SERVER['PHP_SELF'] ."?option=com_virtuemart&page=admin.country_state_form&country_id=". intval($_REQUEST['country_id']) ."&limitstart=$limitstart&no_menu=$no_menu";
				$alt = "&nbsp;".$VM_LANG->_('PHPSHOP_ADD_STATE');
				vmMenuBar::customHref( $href, $vmIcons['new_icon'], $vmIcons['new_icon2'], $alt );
				vmMenuBar::spacer();
				
				$href = $_SERVER['PHP_SELF'] ."?option=com_virtuemart&page=admin.country_state_list&country_id=". intval($_REQUEST['country_id']) ."&limitstart=$limitstart&no_menu=$no_menu";
				$alt = "&nbsp;".$VM_LANG->_('PHPSHOP_LIST_STATES');
				vmMenuBar::customHref( $href, $vmIcons['new_icon'], $vmIcons['new_icon2'], $alt );
				vmMenuBar::spacer();
				
				vmMenuBar::divider();
			}
		}
		vmMenuBar::spacer();
		
		vmMenuBar::save( 'save', $VM_LANG->_('CMN_SAVE') );
		if( $no_menu == 0 ) {
			vmMenuBar::spacer();
			
			vmMenuBar::apply( 'apply', $VM_LANG->_('E_APPLY') );
		}
		if( (strstr( @$_SERVER['HTTP_REFERER'], $page ) || strstr( @$_SERVER['HTTP_REFERER'], $_SERVER['PHP_SELF'] )) && $no_menu && !$is_iframe ) {
			// offer a back button
			vmMenuBar::spacer();
			vmMenuBar::back();
		}
        vmMenuBar::spacer();
		vmMenuBar::cancel();

		vmMenuBar::spacer();
		vmMenuBar::endTable();
    }
    /**
	* The function for all page which allow adding new items
	* usually when page= *.*_list
	*/
    function LISTS_MENU_NEW() {
        global $page, $mosConfig_live_site, $VM_LANG, $limitstart, $vmIcons;

        $my_page = str_replace('list','form',$page);
		
        vmMenuBar::addNew( "new", $my_page, $VM_LANG->_('CMN_NEW') );
		
        if ($page == 'admin.country_state_list') {
			// Back to the country
			vmMenuBar::divider();
			$href = $_SERVER['PHP_SELF']. '?option=com_virtuemart&page=admin.country_list';
			vmMenuBar::customHref( $href, $vmIcons['back_icon'], $vmIcons['back_icon2'], '&nbsp;'.$VM_LANG->_('PHPSHOP_BACK_TO_COUNTRY') );
        }
        elseif ($page == 'product.file_list') {
			// Close the window
			vmMenuBar::divider();
			vmMenuBar::cancel();
        }
   
        vmMenuBar::spacer();
    }
	/**
	* Draws a list publish button
	*/
    function LISTS_MENU_PUBLISH( $funcName ) {
		
		vmMenuBar::publishList( $funcName );
		vmMenuBar::spacer();
		vmMenuBar::unpublishList( $funcName );
		vmMenuBar::spacer();
	}
	/**
	* Draws a list delete button
	*/
    function LISTS_MENU_DELETE( $funcName ) {
		
		vmMenuBar::deleteList( $funcName );
		
	}
	
	/** 
	* Handles special task selectors for pages
	* like the product list
	*/
	function LISTS_SPECIAL_TASKS( $page ) {
		global $mosConfig_live_site, $VM_LANG, $product_id, $vmIcons;
		switch( $page ) {
		
			case "product.product_list":
			
				if( empty($_REQUEST['product_parent_id']) ) { 
					// add new attribute
					$alt = "&nbsp;". $VM_LANG->_('PHPSHOP_ATTRIBUTE_FORM_MNU');
					vmMenuBar::custom( "", "product.product_attribute_form", $vmIcons['new_icon'], $vmIcons['new_icon2'], $alt );
					vmMenuBar::spacer();
				}
				// Go to Price list
				$alt = "&nbsp;". $VM_LANG->_('PHPSHOP_PRICE_LIST_MNU');
				vmMenuBar::custom( "", "product.product_price_list", $vmIcons['new_icon'], $vmIcons['new_icon2'], $alt );
				
				vmMenuBar::spacer();
		
				// add product type
				$alt = "&nbsp;". $VM_LANG->_('PHPSHOP_PRODUCT_PRODUCT_TYPE_FORM_MNU');
				vmMenuBar::custom( "", "product.product_product_type_form", $vmIcons['new_icon'], $vmIcons['new_icon2'], $alt );
				
				vmMenuBar::spacer();
		
				/*** Adding an item is only pssible, if the product has attributes ***/
				if (ps_product::product_has_attributes( $product_id ) ) { 
					// Add Item
					$alt = "&nbsp;". $VM_LANG->_('PHPSHOP_PRODUCT_FORM_NEW_ITEM_LBL');
					vmMenuBar::custom( "", "product.product_child_form", $vmIcons['new_icon'], $vmIcons['new_icon2'], $alt );
				}
				vmMenuBar::divider();
				vmMenuBar::spacer();
				if( !empty( $_REQUEST['category_id'])) {
					$alt = $VM_LANG->_('VM_PRODUCTS_MOVE_TOOLBAR');
					vmMenuBar::custom( 'move', 'product.product_move', $mosConfig_live_site.'/administrator/images/move.png', $mosConfig_live_site.'/administrator/images/move_f2.png', $alt );
					vmMenuBar::spacer();
					vmMenuBar::divider();
					vmMenuBar::spacer();
				}
				break;
			
			case "admin.country_list":

					$alt = "&nbsp;".$VM_LANG->_('PHPSHOP_ADD_STATE');
					vmMenuBar::custom( "", "admin.country_state_form", $vmIcons['new_icon'], $vmIcons['new_icon2'], $alt );
					vmMenuBar::spacer();
					
					$alt = "&nbsp;".$VM_LANG->_('PHPSHOP_LIST_STATES');
					vmMenuBar::custom( "", "admin.country_state_list", $vmIcons['new_icon'], $vmIcons['new_icon2'], $alt );
					vmMenuBar::spacer();
					vmMenuBar::divider();
					vmMenuBar::spacer();
				break;
			
			default:
			
		}
		
	}
	
	
	/**
	* Draws the menu for a New users
	*/
	function _NEW_USERS() {
		vmMenuBar::startTable();
		vmMenuBar::save();
		vmMenuBar::cancel();
		vmMenuBar::spacer();
		vmMenuBar::endTable();
	}
	
	function _EDIT_USERS() {
		vmMenuBar::startTable();
		vmMenuBar::save();
		vmMenuBar::cancel();
		vmMenuBar::spacer();
		vmMenuBar::endTable();
	}
	
	function _DEFAULT_USERS() {
		vmMenuBar::startTable();
		vmMenuBar::addNew();
		vmMenuBar::editList();
		vmMenuBar::deleteList();
		vmMenuBar::spacer();
		vmMenuBar::custom( 'remove_as_customer', 'admin.user_list', IMAGEURL .'ps_image/remove_as_customer.png', IMAGEURL .'ps_image/remove_as_customer_f2.png' );
		vmMenuBar::spacer();
		vmMenuBar::endTable();
	}
  
}
?>
