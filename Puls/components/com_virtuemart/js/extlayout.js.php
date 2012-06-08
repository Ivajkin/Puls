<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
* This file provides the Ext Layout for VirtueMart Administration
* It is located here, because this provides an easy way to include it using the standard VirtueMart Call
* and allows to keep the current Session.
*
* @version $Id: compat.joomla1.5.php 1133 2008-01-08 20:40:56Z gregdev $
* @package VirtueMart
* @subpackage core
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
while(@ob_end_clean());

header( 'Content-Type: application/x-javascript');

$mod = array();
$q = "SELECT module_name,module_perms from #__{vm}_module WHERE module_publish='Y' ";
$q .= "AND module_name <> 'checkout' ORDER BY list_order ASC";
$db->query($q);

while ($db->next_record()) {
    if ($perm->check($db->f("module_perms"))) {
        $mod[] = $db->f("module_name");
	}
}
$menu_items = assembleMenuItems($mod);
include( ADMINPATH.'version.php');

echo "
if( typeof Ext == \"undefined\" ) {
	document.location=\"index2.php?option=".VM_COMPONENT_NAME."&vmLayout=standard&usefetchscript=0\";
}
// Check if this Window is a duplicate and opens in an iframe
if( parent.vmLayout ) {
	if( typeof parent.vmLayout.loadPage == \"function\" ) {
		// then load the pure page, not again the whole VirtueMart Admin interface
		parent.vmLayout.loadPage();
	}
}
function classClicked(e, target) {
    alert( 'klick!');
	if (target.target!='_top' && target.target!='_blank') {
		e.stopEvent();
        Ext.getCmp('west-panel').showPanel('vmPage');
        loadPage(target.href );
	}
}
function showButtonMenu( btn, e ) {
	btn.showMenu();
}
function hideButtonMenu( btn, e ) {
	btn.hideMenu();
}";
echo '
function vmLayoutInit() {	    
    try{ Ext.get("header-box").hide(); } catch(e) {} // Hide the Admin Menu under Joomla! 1.5
    try{ Ext.get("wrapper").hide(); } catch(e) {} // Hide the Admin Menu under Joomla! 1.0
            
    // initialize state manager, we will use cookies
	Ext.state.Manager.setProvider(new Ext.state.CookieProvider());
	';
	$html = 'var toolbarItems = [';
	$i = 0;
	$itemCount = count( $menu_items );
	foreach( $menu_items as $item ) {
		$html .= '{ xtype:"tbbutton",
		                //listeners: {"mouseover": { fn: showButtonMenu }},
	                    text:"'.$item['title'].'",
			           menu: new Ext.menu.Menu({
			            	items: [';
		$j = 0;
		$linkCount = count( $item['links'] );
		foreach( $item['links'] as $link ) {
			if( $link['text'] == '-' ) {
				$html .= "'-'";
			} else {
				$url = strncmp($link['href'], 'http', 4 ) === 0 ? $link['href'] : $sess->url('index2.php?pshop_mode=admin&'.$link['href'], false, false );
				$title = isset( $link['title'] ) ? ' title="'.$link['title'].'"' : '';
				$html .= "{ text: \"{$link['text']}\",
								itemCls: \"{$link['iconCls']}\",
								style: \"padding-left: 0px;font-weight: bold;background-repeat: no-repeat;\",
								handler: new Function(\"loadPage( \'$url\' )\")
							}";
			}
			if( ++$j < $linkCount ) $html .= ',';
		}
		$html .= ']
					})
					}';
		if( ++$i < $itemCount ) $html .= ',"-",';
	}
	$html .= '];';
	echo $html;
	
	echo '
    var viewport = new Ext.Viewport({
			layout:"border",
			items:[{
			    region:"center",
			    layout:"fit",
			    items:[{
			        layout:"fit",
			        items:[{
							xtype:"tabpanel",
					        deferredRender:false,
					        activeTab:0,
					        id: "center-panel",
					    	listeners: {
							    "tabchange" : {
							        fn: function(tabpanel, panel) { parent.document.title=panel.title },
							        scope: this
							    }
							 },
					        items:[{
					        	xtype: "panel",
								layout: "fit",
								id: "vmpage-panel",
								title: "'.addslashes($VM_LANG->_('VM_ADMIN_PANELTITLE')).'",
								closable:false,
								contentEl: "vmPage"
							}]
			              
			          }]
			      }]
			  },{
	        	xtype: "panel",
	        	bbar: toolbarItems,
			    region:"north",
			    height: 105,
			    html:"<div style=\"background: url('.VM_THEMEURL.'/images/administration/header_bg.png) repeat-x;\">" +
			    		 "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" + 
			    		 "<a href=\"http://virtuemart.net\" target=\"_blank\"><img src=\"'.VM_THEMEURL.'/images/administration/header_logo.png\" alt=\"VirtueMart Logo\" /></a>" +
						"<a href=\"index2.php\" title=\"'.$VM_LANG->_('VM_ADMIN_BACKTOJOOMLA').'\" class=\"vmicon vmicon-16-back\" style=\"vertical-align: middle;font-weight:bold;\">'.$VM_LANG->_('VM_ADMIN_BACKTOJOOMLA').'</a>" +
						"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" +
			 			"[ <a href=\"index2.php?option=com_virtuemart&amp;vmLayout=standard\">'. $VM_LANG->_('VM_ADMIN_SIMPLE_LAYOUT') .'</a> | <strong>'. $VM_LANG->_('VM_ADMIN_EXTENDED_LAYOUT').'</strong> ]" +
						"</div>"
			  }]
			}
    );
 }';
			
echo "
function loadPage(page){

	if( !page || page == '' ) {
        defaultpage = \"index3.php?option=com_virtuemart&page=store.index\";
        page = Ext.state.Manager.get( \"vmlastpage\", defaultpage );
	}
	if( page.indexOf( \"virtuemart.net\" ) == -1 ) {
        php_self = page.replace(/index2.php/, 'index3.php');
        php_self = php_self.replace(/index.php/, 'index3.php');
        Ext.get('vmPage').dom.src = php_self + '&only_page=1&no_menu=1';
	} else {
        Ext.get('vmPage').dom.src = page;
	}         
    Ext.state.Manager.set( 'vmlastpage', page );
}
if( Ext.isIE ) {
	Ext.EventManager.addListener( window, 'load', vmLayoutInit );
}
else {
	Ext.onReady( vmLayoutInit );
}
";
/**
 * Assembles the Adminsitrator Menu Items into an array
 *
 * @param array $mods
 * @return array
 */
function assembleMenuItems( $mods ) {
	global $VM_LANG, $mosConfig_absolute_path, $mosConfig_live_site;
	$modCount = 1;
	$modules = array();
	
	foreach( $mods as $module ) { 
		switch( $module ) {
			case 'admin':
				$modules[$module]['title'] = $VM_LANG->_('PHPSHOP_ADMIN_MOD');
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-config',
																			'href' => 'page=admin.show_cfg',
																			'text' => $VM_LANG->_('PHPSHOP_CONFIG')
																			);
				if (defined('_VM_IS_BACKEND')) {
					$modules[$module]['links'][] = array('text' => '-' );
					$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-user',
																			'href' => 'page=admin.user_list',
																			'text' => $VM_LANG->_('PHPSHOP_USERS')
																			);
				}
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-user',
																			'href' => 'page=admin.usergroup_list',
																			'text' => $VM_LANG->_('VM_USERGROUP_LBL')
																			);
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-content',
																			'href' => 'page=admin.user_field_list',
																			'text' => $VM_LANG->_('VM_MANAGE_USER_FIELDS')
																			);
				$modules[$module]['links'][] = array('text' => '-' );
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-content',
																			'href' => 'page=admin.country_list',
																			'text' => $VM_LANG->_('PHPSHOP_COUNTRY_LIST_MNU')
																			);
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-content',
																			'href' => 'page=admin.curr_list',
																			'text' => $VM_LANG->_('PHPSHOP_CURRENCY_LIST_MNU')
																			);
				$modules[$module]['links'][] = array('text' => '-' );
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-content',
																			'href' => 'page=admin.module_list',
																			'text' => $VM_LANG->_('PHPSHOP_MODULE_LIST_MNU')
																			);
				$modules[$module]['links'][] = array('text' => '-' );
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-content',
																			'href' => 'page=admin.update_check',
																			'text' => $VM_LANG->_('VM_CHECK_UPDATES_MNU')
																			);
				
				  
				$modCount++;
				break;
			
			
			case 'store':
				$modules[$module]['title'] = $VM_LANG->_('PHPSHOP_STORE_MOD');
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-info',
																			'href' => 'page=store.index',
																			'text' => $VM_LANG->_('PHPSHOP_STATISTIC_SUMMARY')
																			);
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-config',
																			'href' => 'page=store.store_form',
																			'text' => $VM_LANG->_('PHPSHOP_STORE_FORM_MNU')
									);
				if ($_SESSION['auth']['perms'] != "admin" && defined('VM_IS_BACKEND')) {		
					$modules[$module]['links'][] = array('text' => '-' );						
					$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-user',
																			'href' => 'page=store.user_list',
																			'text' => $VM_LANG->_('PHPSHOP_USER_LIST_MNU')
																			);
				}
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-content',
																			'href' => 'page=store.payment_method_list',
																			'text' => $VM_LANG->_('PHPSHOP_PAYMENT_METHOD_LIST_MNU')
																			);
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-editadd',
																			'href' => 'page=store.payment_method_form',
																			'text' => $VM_LANG->_('PHPSHOP_PAYMENT_METHOD_FORM_MNU')
																			);
				$modules[$module]['links'][] = array('text' => '-' );
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-content',
																			'href' => 'page=store.shipping_module_list',
																			'text' => $VM_LANG->_('VM_SHIPPING_MODULE_LIST_LBL')
																			);
				$modules[$module]['links'][] = array('text' => '-' );
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-content',
																			'href' => 'page=store.creditcard_list',
																			'text' => $VM_LANG->_('PHPSHOP_CREDITCARD_LIST_LBL')
																			);						
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-editadd',
																			'href' => 'page=store.creditcard_form',
																			'text' => $VM_LANG->_('PHPSHOP_CREDITCARD_FORM_LBL')
																			);
				$modules[$module]['links'][] = array('text' => '-' );
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-content',
																			'href' => 'page=store.export_list',
																			'text' => $VM_LANG->_('VM_ORDER_EXPORT_MODULE_LIST_MNU')
																			);																
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-editadd',
																			'href' => 'page=store.export_form',
																			'text' => $VM_LANG->_('VM_ORDER_EXPORT_MODULE_FORM_MNU')
																			);		
																			
				$modCount++;
				break;
			
			
			case 'shopper':
				$modules[$module]['title'] = $VM_LANG->_('PHPSHOP_SHOPPER_MOD');
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-content',
																			'href' => 'page=shopper.shopper_group_list',
																			'text' => $VM_LANG->_('PHPSHOP_SHOPPER_GROUP_LIST_MNU')
																			);
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-editadd',
																			'href' => 'page=shopper.shopper_group_form',
																			'text' => $VM_LANG->_('PHPSHOP_SHOPPER_GROUP_FORM_MNU')
																			);
																			
				$modCount++;
				break;
			
			case 'product':
				include_class("product");
				
				$modules[$module]['title'] = $VM_LANG->_('PHPSHOP_PRODUCT_MOD');
				
				// Check for CSVImproved Installation!
				$text = $VM_LANG->_('CSVIMPROVED_TITLE');
	           	if( file_exists($mosConfig_absolute_path.'/administrator/components/com_csvimproved/admin.csvimproved.php')) {
	           		$url = $mosConfig_live_site . '/administrator/index2.php?option=com_csvimproved';
					$extra = 'onclick="document.location=this.href"';
	           		$title = str_replace('"','&quot;',$VM_LANG->_('CSVIMPROVED_TITLE'));
	           	} else {
	           		$url = 'http://www.csvimproved.com/index.php?option=com_ionfiles&Itemid=2';
	           		$extra = 'target="_blank"';
	           		$title = str_replace('"','&quot;',$VM_LANG->_('CSVIMPROVED_NEEDINSTALL'));
	           	}
		           	
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-import',
																			'href' => $url,
																			'title' => $title,
																			'text' => $text,
																			'extra' => $extra
																			); 
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-content',
																			'href' => 'page=product.product_list',
																			'text' => $VM_LANG->_('PHPSHOP_PRODUCT_LIST_MNU')
																			); 
																			
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-editadd',
																			'href' => 'page=product.product_form',
																			'text' => $VM_LANG->_('PHPSHOP_PRODUCT_FORM_MNU')
																			);
				$modules[$module]['links'][] = array('text' => '-' );
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-install',
																			'href' => 'page=product.product_inventory',
																			'text' => $VM_LANG->_('PHPSHOP_PRODUCT_INVENTORY_MNU')
																			); 
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-content',
																			'href' => 'page=product.specialprod',
																			'text' => $VM_LANG->_('PHPSHOP_SPECIAL_PRODUCTS')
																			); 
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-content',
																			'href' => 'page=product.folders',
																			'text' => $VM_LANG->_('PHPSHOP_PRODUCT_FOLDERS')
																			); 
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-content',
																			'href' => 'page=product.review_list',
																			'text' => $VM_LANG->_('PHPSHOP_REVIEWS')
																			);
				$modules[$module]['links'][] = array('text' => '-' );
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-content',
																			'href' => 'page=product.product_discount_list',
																			'text' => $VM_LANG->_('PHPSHOP_PRODUCT_DISCOUNT_LIST_LBL')
																			);
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-editadd',
																			'href' => 'page=product.product_discount_form',
																			'text' => $VM_LANG->_('PHPSHOP_PRODUCT_DISCOUNT_FORM_MNU')
																			);
				$modules[$module]['links'][] = array('text' => '-' );
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-content',
																			'href' => 'page=product.product_type_list',
																			'text' => $VM_LANG->_('PHPSHOP_PRODUCT_TYPE_LIST_LBL')
																			);
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-editadd',
																			'href' => 'page=product.product_type_form',
																			'text' => $VM_LANG->_('PHPSHOP_PRODUCT_PRODUCT_TYPE_FORM_MNU')
																			);
				$modules[$module]['links'][] = array('text' => '-' );
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-content',
																			'href' => 'page=product.product_category_list',
																			'text' => $VM_LANG->_('PHPSHOP_CATEGORY_LIST_MNU')
																			);
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-editadd',
																			'href' => 'page=product.product_category_form',
																			'text' => $VM_LANG->_('PHPSHOP_CATEGORY_FORM_MNU')
																			);	
				$modCount++;
				break;
			
			
			case 'order':
				$modules[$module]['title'] = $VM_LANG->_('PHPSHOP_ORDER_MOD');
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-content',
																			'href' => 'page=order.order_list',
																			'text' => $VM_LANG->_('PHPSHOP_ORDER_LIST_MNU')
																			);
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-content',
																			'href' => 'page=order.order_status_list',
																			'text' => $VM_LANG->_('PHPSHOP_ORDER_STATUS_LIST_MNU')
																			);
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-editadd',
																			'href' => 'page=order.order_status_form',
																			'text' => $VM_LANG->_('PHPSHOP_ORDER_STATUS_FORM_MNU')
																			);
																			
				$modCount++;
				break;
			
			
			case 'vendor':
				
				$modules[$module]['title'] = $VM_LANG->_('PHPSHOP_VENDOR_MOD');
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-content',
																			'href' => 'page=vendor.vendor_list',
																			'text' => $VM_LANG->_('PHPSHOP_VENDOR_LIST_MNU')
																			);
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-editadd',
																			'href' => 'page=vendor.vendor_form',
																			'text' => $VM_LANG->_('PHPSHOP_VENDOR_FORM_MNU')
																			);
				$modules[$module]['links'][] = array('text' => '-' );
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-content',
																			'href' => 'page=vendor.vendor_category_list',
																			'text' => $VM_LANG->_('PHPSHOP_VENDOR_CAT_LIST_MNU')
																			);
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-editadd',
																			'href' => 'page=vendor.vendor_category_form',
																			'text' => $VM_LANG->_('PHPSHOP_VENDOR_CAT_FORM_MNU')
																			);
				
				$modCount++;
				break;
			
			
			case 'reportbasic':
				$modules[$module]['title'] = $VM_LANG->_('PHPSHOP_REPORTBASIC_MOD');
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-info',
																			'href' => 'page=reportbasic.index',
																			'text' => $VM_LANG->_('PHPSHOP_REPORTBASIC_MOD')
																			);
				
				$modCount++;
				break;
			
			
			case 'tax':
				$modules[$module]['title'] = $VM_LANG->_('PHPSHOP_TAX_MOD');
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-content',
																			'href' => 'page=tax.tax_list',
																			'text' => $VM_LANG->_('PHPSHOP_TAX_LIST_MNU')
																			);
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-editadd',
																			'href' => 'page=tax.tax_form',
																			'text' => $VM_LANG->_('PHPSHOP_TAX_FORM_MNU')
																			);
 
				$modCount++;
				break;

                case "shipping":
				$modules[$module]['title'] = $VM_LANG->_('PHPSHOP_SHIPPING_MOD');
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-content',
																			'href' => 'page=shipping.carrier_list',
																			'text' => $VM_LANG->_('PHPSHOP_CARRIER_LIST_MNU')
																			);
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-editadd',
																			'href' => 'page=shipping.carrier_form',
																			'text' => $VM_LANG->_('PHPSHOP_CARRIER_FORM_MNU')
																			);
				$modules[$module]['links'][] = array('text' => '-' );
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-content',
																			'href' => 'page=shipping.rate_list',
																			'text' => $VM_LANG->_('PHPSHOP_RATE_LIST_MNU')
																			);
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-editadd',
																			'href' => 'page=shipping.rate_form',
																			'text' => $VM_LANG->_('PHPSHOP_RATE_FORM_MNU')
																			);
																			
				$modCount++;
				break;
                        
                case "zone":
				$modules[$module]['title'] = $VM_LANG->_('PHPSHOP_ZONE_MOD');
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-content',
																			'href' => 'page=zone.assign_zones',
																			'text' => $VM_LANG->_('PHPSHOP_ZONE_ASSIGN_MNU')
																			);
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-content',
																			'href' => 'page=zone.zone_list',
																			'text' => $VM_LANG->_('PHPSHOP_ZONE_LIST_MNU')
																			);
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-editadd',
																			'href' => 'page=zone.zone_form',
																			'text' => $VM_LANG->_('PHPSHOP_ZONE_FORM_MNU')
																			);	
			
				$modCount++;
				break;

			case 'coupon':
				$modules[$module]['title'] = $VM_LANG->_('PHPSHOP_COUPON_MOD');
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-content',
																			'href' => 'page=coupon.coupon_list',
																			'text' => $VM_LANG->_('PHPSHOP_COUPON_LIST')
																			);
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-editadd',
																			'href' => 'page=coupon.coupon_form',
																			'text' => $VM_LANG->_('PHPSHOP_COUPON_NEW_HEADER')
																			);
			
				$modCount++;
				break;
				
			case 'export':
				$modules[$module]['title'] = 'Export';
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-content',
																			'href' => 'page=export.index',
																			'text' => 'Export Manager'
																			);
				$modCount++;
				break;
			
			case 'manufacturer':
				$modules[$module]['title'] = $VM_LANG->_('PHPSHOP_MANUFACTURER_MOD');
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-content',
																			'href' => 'page=manufacturer.manufacturer_list',
																			'text' => $VM_LANG->_('PHPSHOP_MANUFACTURER_LIST_MNU')
																			);
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-editadd',
																			'href' => 'page=manufacturer.manufacturer_form',
																			'text' => $VM_LANG->_('PHPSHOP_MANUFACTURER_FORM_MNU')
																			);
				$modules[$module]['links'][] = array('text' => '-' );
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-content',
																			'href' => 'page=manufacturer.manufacturer_category_list',
																			'text' => $VM_LANG->_('PHPSHOP_MANUFACTURER_CAT_LIST_MNU')
																			);
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-editadd',
																			'href' => 'page=manufacturer.manufacturer_category_form',
																			'text' => $VM_LANG->_('PHPSHOP_MANUFACTURER_CAT_FORM_MNU')
																			);
			
				$modCount++;
				break;
			
			case 'help':
				$modules[$module]['title'] = $VM_LANG->_('PHPSHOP_HELP_MOD');
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-info',
																			'href' => 'page=help.about',
																			'text' => $VM_LANG->_('VM_ABOUT')
																			);
																			
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-help',
																			'href' => 'http://virtuemart.net/documentation/User_Manual/index.html',
																			'text' => $VM_LANG->_('VM_HELP_TOPICS')
																			);
				$modules[$module]['links'][] = array('iconCls' => 'vmicon vmicon-16-language',
																			'href' => 'http://forum.virtuemart.net/',
																			'text' => $VM_LANG->_('VM_COMMUNITY_FORUM')
																			);
				
				$modCount++;
				break;
			}
			
		}
		return $modules;
}
?>