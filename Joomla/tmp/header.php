<?php 
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
* Header file for the shop administration.
* shows all modules that are available to the user in a dropdown menu
*
* @version $Id: header.php 2574 2010-10-10 13:56:28Z zanardi $
* @package VirtueMart
* @subpackage core
* @copyright Copyright (C) 2004-2007 soeren - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
*
* http://virtuemart.net
*/
mm_showMyFileName( __FILE__ );
include_once( ADMINPATH . "version.php" );

global $error, $page, $ps_product, $ps_product_category;
$product_id = vmGet( $_REQUEST, 'product_id' );
$product_parent_id = vmGet( $_REQUEST, 'product_parent_id' );
$module_id = vmGet( $_REQUEST, 'module_id', 0 );

if( is_array( $product_id ) || $page == 'product.product_list' ) {
    $recent_product_id = "";
}
else {
    $recent_product_id = $product_id;
}
        
$mod = array();
$q = "SELECT module_name,module_perms from #__{vm}_module WHERE module_publish='Y' ";
$q .= "AND module_name <> 'checkout' ORDER BY list_order ASC";
$db->query($q);

while ($db->next_record()) {
    if ($perm->check($db->f("module_perms"))) {
        $mod[] = $db->f("module_name");
	}
}

$vm_mainframe->addStyleSheet( $mosConfig_live_site.'/components/com_virtuemart/js/admin_menu/css/menu.css');
$vm_mainframe->addScript($mosConfig_live_site.'/components/com_virtuemart/js/admin_menu/js/virtuemart_menu.js');
$vm_mainframe->addScript($mosConfig_live_site.'/components/com_virtuemart/js/admin_menu/js/nifty.js');
$vm_mainframe->addScript($mosConfig_live_site.'/components/com_virtuemart/js/admin_menu/js/fat.js');
$vm_mainframe->addScript($mosConfig_live_site.'/components/com_virtuemart/js/functions.js');

if( vmIsJoomla('1.0') && strstr( $_SERVER['PHP_SELF'], 'index3.php')) {
	echo $mainframe->getHead();
}
?>
<div id="vmMenu">
<div id="content-box2">
<div id="content-pad">
  <div class="sidemenu-box">
    <div class="sidemenu-pad">
		<center>
		<?php
		if( !defined('_VM_IS_BACKEND')) {
			echo '<a href="index.php" title="'.$VM_LANG->_('VM_ADMIN_BACKTOJOOMLA').'" class="vmicon vmicon-16-back" style="font-weight:bold;">'.$VM_LANG->_('BACK').'</a>
			<br /><br />'; 
		} else {
			if( $vmLayout == 'standard') {
				$tmpl = vmIsJoomla('1.5', '>=') ? 'component' : '';
				?>
				[ <strong><?php echo $VM_LANG->_('VM_ADMIN_SIMPLE_LAYOUT') ?></strong> | 
				<a href="<?php echo vmGet($_SERVER,'PHP_SELF').'?'.( !empty( $_SERVER['QUERY_STRING'] ) ? vmGet($_SERVER,'QUERY_STRING') : 'option=com_virtuemart&amp;page='.$page ).'&amp;tmpl='.$tmpl ?>&amp;vmLayout=extended"><?php echo $VM_LANG->_('VM_ADMIN_EXTENDED_LAYOUT') ?></a> ]<br />
				<?php
			} else { 
				?>
				[ <a href="<?php echo vmGet($_SERVER,'PHP_SELF').'?'.(!empty( $_SERVER['QUERY_STRING'] ) ? vmGet($_SERVER,'QUERY_STRING') : 'option=com_virtuemart&amp;page='.$page ) ?>&amp;vmLayout=standard"><?php echo $VM_LANG->_('VM_ADMIN_SIMPLE_LAYOUT') ?></a> 
				| <strong><?php echo $VM_LANG->_('VM_ADMIN_EXTENDED_LAYOUT') ?></strong> ]<br /><br />
				<?php
			}
		}
		?>
			<a href="http://virtuemart.net" target="_blank">
				<img align="middle" hspace="15" src="<?php echo IMAGEURL ?>ps_image/menu_logo.gif" alt="VirtueMart Cart Logo" />
			</a>
		
			<h2><?php echo $VM_LANG->_('PHPSHOP_ADMIN')	?></h2>
		</center>
		<div class="status-divider">
		</div>
		<div class="sidemenu" id="masterdiv2">
		<?php
		$modCount = 1;
		foreach( $mod as $module ) { 
			switch( $module ) {
				case 'admin':
			
				?>
					<h3 class="title-smenu" title="admin" onclick="SwitchMenu('<?php echo $modCount ?>')"><?php echo $VM_LANG->_('PHPSHOP_ADMIN_MOD') ?></h3>
						<div class="section-smenu">
					<ul>
					<li class="item-smenu vmicon vmicon-16-config">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=admin.show_cfg&amp;option=com_virtuemart") ?>"><?php echo $VM_LANG->_('PHPSHOP_CONFIG') ?></a>
					<hr />
					</li>
					<?php if (defined('_VM_IS_BACKEND')) { ?>
					<li class="item-smenu vmicon vmicon vmicon-16-user">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=admin.user_list") ?>"><?php echo $VM_LANG->_('PHPSHOP_USERS') ?></a>
					</li>
					<?php } ?>
					<li class="item-smenu vmicon vmicon-16-user">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=admin.usergroup_list") ?>"><?php echo $VM_LANG->_('VM_USERGROUP_LBL') ?></a>
					</li>
					<li class="item-smenu vmicon vmicon-16-content">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=admin.user_field_list") ?>"><?php echo $VM_LANG->_('VM_MANAGE_USER_FIELDS') ?></a>
					<hr />					
					</li>
					<li class="item-smenu vmicon vmicon-16-content">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=admin.country_list") ?>"><?php echo $VM_LANG->_('PHPSHOP_COUNTRY_LIST_MNU') ?></a>
					</li>
					<li class="item-smenu vmicon vmicon-16-content">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=admin.curr_list") ?>"><?php echo $VM_LANG->_('PHPSHOP_CURRENCY_LIST_MNU') ?></a>
					
						<hr />
					</li>
					
					<li class="item-smenu vmicon vmicon-16-content">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=admin.module_list") ?>"><?php echo $VM_LANG->_('PHPSHOP_MODULE_LIST_MNU') ?></a>
						<hr />
					</li>
					<?php if (!empty($module_id)) { ?>
					<hr /> 
					<li class="item-smenu vmicon vmicon-16-content">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=admin.function_list&amp;module_id=".$module_id) ?>"><?php echo $VM_LANG->_('PHPSHOP_FUNCTION_LIST_MNU') ?></a>
					</li>
					<li class="item-smenu vmicon vmicon-16-editadd">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=admin.function_form&amp;module_id=".$module_id) ?>"><?php echo $VM_LANG->_('PHPSHOP_FUNCTION_FORM_MNU') ?></a>
						<hr />
					</li>
					 <?php } ?>
					 
					<li class="item-smenu vmicon vmicon-16-content">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=admin.update_check") ?>"><?php echo $VM_LANG->_('VM_CHECK_UPDATES_MNU') ?></a>
					</li>
					</ul>
					</div>
				<?php 
				$modCount++;
				break;
				
				
				case 'store':
					?>
					<h3 class="title-smenu" title="store" onclick="SwitchMenu('<?php echo $modCount ?>')">
					<?php echo $VM_LANG->_('PHPSHOP_STORE_MOD')
					?>
					</h3>
					<div class="section-smenu">
					<ul>
					<li class="item-smenu vmicon vmicon-16-info">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=store.index") ?>"><?php echo $VM_LANG->_('PHPSHOP_STATISTIC_SUMMARY') ?></a>
					</li>
					<li class="item-smenu vmicon vmicon-16-config">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=store.store_form") ?>"><?php echo $VM_LANG->_('PHPSHOP_STORE_FORM_MNU') ?></a>
					</li>
					<?php if ($_SESSION['auth']['perms'] != "admin" && defined('_VM_IS_BACKEND')) { ?>
					<li class="item-smenu vmicon vmicon-16-user">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=store.user_list") ?>"><?php echo $VM_LANG->_('PHPSHOP_USER_LIST_MNU') ?></a>
					</li>
					<?php } ?>
					<li><hr /></li>
					<li class="item-smenu vmicon vmicon-16-content">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=store.payment_method_list") ?>"><?php echo $VM_LANG->_('PHPSHOP_PAYMENT_METHOD_LIST_MNU') ?></a>
					</li>
					<li class="item-smenu vmicon vmicon-16-editadd">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=store.payment_method_form") ?>"><?php echo $VM_LANG->_('PHPSHOP_PAYMENT_METHOD_FORM_MNU') ?></a>
					<hr />
					</li>
					<li class="item-smenu vmicon vmicon-16-content">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=store.shipping_module_list") ?>"><?php echo $VM_LANG->_('VM_SHIPPING_MODULE_LIST_LBL') ?></a>
					<hr />
					</li>
					<li class="item-smenu vmicon vmicon-16-content">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=store.creditcard_list") ?>"><?php echo $VM_LANG->_('PHPSHOP_CREDITCARD_LIST_LBL') ?></a>
					</li>
					<li class="item-smenu vmicon vmicon-16-editadd">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=store.creditcard_form") ?>"><?php echo $VM_LANG->_('PHPSHOP_CREDITCARD_FORM_LBL') ?></a>
					<hr />
					</li>
					</ul>
					</div>
				<?php 
				$modCount++;
				break;
				
				
				case 'shopper':
					?>
					<h3 class="title-smenu" title="shopper" onclick="SwitchMenu('<?php echo $modCount ?>')">
					<?php echo $VM_LANG->_('PHPSHOP_SHOPPER_MOD')
					?>
					</h3>
					<div class="section-smenu">
					<ul>
					<li class="item-smenu vmicon vmicon-16-content">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=shopper.shopper_group_list") ?>"><?php echo $VM_LANG->_('PHPSHOP_SHOPPER_GROUP_LIST_MNU') ?></a>
					</li>
					<li class="item-smenu vmicon vmicon-16-editadd">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=shopper.shopper_group_form") ?>"><?php echo $VM_LANG->_('PHPSHOP_SHOPPER_GROUP_FORM_MNU') ?></a>
					</li>
					</ul>
					</div>
				<?php 
				$modCount++;
				break;
				
				
				case 'product':
					?>
					<h3 class="title-smenu" title="product" onclick="SwitchMenu('<?php echo $modCount ?>')">
					<?php echo $VM_LANG->_('PHPSHOP_PRODUCT_MOD'); ?></h3>
					<div class="section-smenu">
					<ul>
					<?php include_class("product"); ?>
					<li><strong><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_MOD') ?></strong></li>
					<?php    
		            if (!empty($recent_product_id) && empty($_REQUEST['product_parent_id'])) { 
		               	if (!isset($return_args)) $return_args = ""; ?> 
						<li><hr /></li>
								
						<li class="item-smenu vmicon vmicon-16-content">
						<a href="<?php $sess->purl($_SERVER['PHP_SELF'] . "?pshop_mode=admin&page=product.product_attribute_list&product_id=$recent_product_id&return_args=" . urlencode($return_args)); ?>"><?php echo $VM_LANG->_('PHPSHOP_ATTRIBUTE_LIST_MNU') ?></a>
						</li>
						<li class="item-smenu vmicon vmicon-16-editadd">
						<a href="<?php $sess->purl($_SERVER['PHP_SELF'] . "?pshop_mode=admin&page=product.product_attribute_form&product_id=$recent_product_id&return_args=" . urlencode($return_args)); ?>"><?php echo $VM_LANG->_('PHPSHOP_ATTRIBUTE_FORM_MNU') ?></a>
						</li>
						<li class="item-smenu vmicon vmicon-16-editadd">
						<a href="<?php $sess->purl($_SERVER['PHP_SELF'] . "?pshop_mode=admin&page=product.product_price_list&product_id=$recent_product_id&return_args=" . urlencode($return_args)); ?>"><?php echo $VM_LANG->_('PHPSHOP_PRICE_FORM_MNU') ?></a>
						</li>
						<li class="item-smenu vmicon vmicon-16-content">
						<a href="<?php $sess->purl($_SERVER['PHP_SELF'] . "?pshop_mode=admin&page=product.product_product_type_list&product_id=$recent_product_id&return_args=" . urlencode($return_args)); ?>"><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_PRODUCT_TYPE_LIST_MNU') ?></a>
						</li>
						
						<li class="item-smenu vmicon vmicon-16-editadd">
						<a href="<?php $sess->purl($_SERVER['PHP_SELF'] . "?pshop_mode=admin&page=product.product_product_type_form&product_id=$recent_product_id&return_args=" . urlencode($return_args)); ?>"><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_PRODUCT_TYPE_FORM_MNU') ?></a>
						</li>
						<?php 
						if ($ps_product->product_has_attributes($recent_product_id)) { ?>
							<li class="item-smenu vmicon vmicon-16-editadd">
							<a href="<?php $sess->purl($_SERVER['PHP_SELF'] . "?pshop_mode=admin&page=product.product_form&product_parent_id=$recent_product_id"); ?>"><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_ADD_ITEM_MNU') ?></a>
							</li>
				            <?php 
						} ?>
						<li><hr /></li>
		            <?php 
		            }
		            elseif (!empty($product_parent_id)) { ?> 
						<li class="item-smenu vmicon vmicon-16-editadd">
						<a href="<?php @$sess->purl($_SERVER['PHP_SELF'] . "?pshop_mode=admin&page=product.product_price_list&product_id=$recent_product_id&product_parent_id=$product_parent_id&return_args=" . urlencode($return_args)); ?>"><?php echo $VM_LANG->_('PHPSHOP_PRICE_FORM_MNU') ?></a>
						</li>
						<li class="item-smenu vmicon vmicon-16-editadd">
						<a href="<?php $sess->purl($_SERVER['PHP_SELF'] . "?pshop_mode=admin&page=product.product_form&product_parent_id=" . $product_parent_id); ?>"><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_ADD_ANOTHER_ITEM_MNU') ?></a>
						</li>
						<li class="item-smenu vmicon vmicon-16-content">
						<a href="<?php @$sess->purl($_SERVER['PHP_SELF'] . "?pshop_mode=admin&page=product.product_product_type_list&product_id=$recent_product_id&product_parent_id=$product_parent_id&return_args=" . urlencode($return_args)); ?>"><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_PRODUCT_TYPE_LIST_MNU') ?></a>
						</li>
						<li class="item-smenu vmicon vmicon-16-content">
						<a href="<?php $sess->purl($_SERVER['PHP_SELF'] . "?pshop_mode=admin&page=product.product_form&product_id=" . $product_parent_id); ?>"><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_RETURN_LBL') ?></a>
						</li>
						<li><hr /></li>
			            <?php 
		            } ?>
		            
		            <li class="item-smenu vmicon vmicon-16-content">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&page=product.product_list") ?>"><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_LIST_MNU') ?></a>
					</li>
		            <li class="item-smenu vmicon vmicon-16-editadd">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&page=product.product_form") ?>"><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_MNU') ?></a>
					</li>
					<?php 
		            if( !empty($recent_product_id) ) { ?>
			            <li class="item-smenu vmicon vmicon-16-media">
						<a href="<?php $sess->purl($_SERVER['PHP_SELF'] . "?pshop_mode=admin&page=product.file_form&product_id=$recent_product_id"); ?>"><?php echo $VM_LANG->_('PHPSHOP_FILEMANAGER_ADD') ?></a>
						</li>
			            <?php 
		            } ?>
		           <li class="item-smenu vmicon vmicon-16-install">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF'] . "?pshop_mode=admin&page=product.product_inventory"); ?>"><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_INVENTORY_MNU') ?></a>
					</li>
		             <li class="item-smenu vmicon vmicon-16-content">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&page=product.specialprod") ?>"><?php echo $VM_LANG->_('PHPSHOP_SPECIAL_PRODUCTS') ?></a>
					</li>
		             <li class="item-smenu vmicon vmicon-16-content">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&page=product.folders") ?>"><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FOLDERS')  ?></a>
					<hr />			
					</li>					
		             <li class="item-smenu vmicon vmicon-16-content">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&page=product.review_list") ?>"><?php echo $VM_LANG->_('PHPSHOP_REVIEWS')  ?></a>
					<hr />			
					</li>
					 <li class="item-smenu vmicon vmicon-16-content">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&page=product.product_discount_list") ?>"><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_DISCOUNT_LIST_LBL') ?></a>
					</li>
					 <li class="item-smenu vmicon vmicon-16-editadd">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&page=product.product_discount_form") ?>"><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_DISCOUNT_FORM_MNU') ?></a>
					<hr />	
					</li>
				    <li class="item-smenu vmicon vmicon-16-content">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&page=product.product_type_list") ?>"><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_TYPE_LIST_LBL') ?></a>
					</li>
					<li class="item-smenu vmicon vmicon-16-editadd">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&page=product.product_type_form") ?>"><?php echo $VM_LANG->_('PHPSHOP_PRODUCT_PRODUCT_TYPE_FORM_MNU') ?></a>
					<hr />	
					</li>
		     		<li class="item-smenu vmicon vmicon-16-content">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&page=product.product_category_list") ?>"><?php echo $VM_LANG->_('PHPSHOP_CATEGORY_LIST_MNU') ?></a>
					</li>
					 <li class="item-smenu vmicon vmicon-16-editadd">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&page=product.product_category_form") ?>"><?php echo $VM_LANG->_('PHPSHOP_CATEGORY_FORM_MNU') ?></a>
					</li>
					</ul>
					</div>
				<?php 
				$modCount++;
				break;
				
				
				case 'order':
					?>
					<h3 class="title-smenu" title="order" onclick="SwitchMenu('<?php echo $modCount ?>')">
					<?php echo $VM_LANG->_('PHPSHOP_ORDER_MOD')
					?>
					</h3>
					<div class="section-smenu">
					<ul>
					<li class="item-smenu vmicon vmicon-16-content">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=order.order_list") ?>"><?php echo $VM_LANG->_('PHPSHOP_ORDER_LIST_MNU') ?></a>
					<hr />
					</li>
					<li class="item-smenu vmicon vmicon-16-content">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=order.order_status_list") ?>"><?php echo $VM_LANG->_('PHPSHOP_ORDER_STATUS_LIST_MNU') ?></a>
					</li>
					<li class="item-smenu vmicon vmicon-16-editadd">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=order.order_status_form") ?>"><?php echo $VM_LANG->_('PHPSHOP_ORDER_STATUS_FORM_MNU') ?></a>
					</li>
				
					</ul>
					</div>
				<?php 
				$modCount++;
				break;
				
				
				case 'vendor':
					?>
					<h3 class="title-smenu" title="vendor" onclick="SwitchMenu('<?php echo $modCount ?>')">
					<?php echo $VM_LANG->_('PHPSHOP_VENDOR_MOD')
					?>
					</h3>
					<div class="section-smenu">
					<ul>
					<li class="item-smenu vmicon vmicon-16-content">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=vendor.vendor_list") ?>"><?php echo $VM_LANG->_('PHPSHOP_VENDOR_LIST_MNU') ?></a>
					</li>
					<li class="item-smenu vmicon vmicon-16-editadd">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=vendor.vendor_form") ?>"><?php echo $VM_LANG->_('PHPSHOP_VENDOR_FORM_MNU') ?></a>
					<hr />
					</li>
					<li class="item-smenu vmicon vmicon-16-content">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=vendor.vendor_category_list") ?>"><?php echo $VM_LANG->_('PHPSHOP_VENDOR_CAT_LIST_MNU') ?></a>
					</li>
					<li class="item-smenu vmicon vmicon-16-editadd">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=vendor.vendor_category_form") ?>"><?php echo $VM_LANG->_('PHPSHOP_VENDOR_CAT_FORM_MNU') ?></a>
					</li>
					</ul>
					</div>
				<?php 
				$modCount++;
				break;
				
				
				case 'reportbasic':
					?>
					<h3 class="title-smenu" title="report" onclick="SwitchMenu('<?php echo $modCount ?>')">
					<?php echo $VM_LANG->_('PHPSHOP_REPORTBASIC_MOD')
					?>
					</h3>
					<div class="section-smenu">
					<ul>
					<li class="item-smenu vmicon vmicon-16-info">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=reportbasic.index") ?>"><?php echo $VM_LANG->_('PHPSHOP_REPORTBASIC_MOD') ?></a>
					</li>
					</ul>
					</div>
				<?php 
				$modCount++;
				break;
				
				
				case 'tax':
					?>
					<h3 class="title-smenu" title="tax" onclick="SwitchMenu('<?php echo $modCount ?>')">
					<?php echo $VM_LANG->_('PHPSHOP_TAX_MOD')
					?>
					</h3>
					<div class="section-smenu">
					<ul>
					<li class="item-smenu vmicon vmicon-16-content">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=tax.tax_list") ?>"><?php echo $VM_LANG->_('PHPSHOP_TAX_LIST_MNU') ?></a>
					</li>
					<li class="item-smenu vmicon vmicon-16-editadd">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=tax.tax_form") ?>"><?php echo $VM_LANG->_('PHPSHOP_TAX_FORM_MNU') ?></a>
					</li>
					</ul>
					</div>
				<?php 
				$modCount++;
				break;

                case "shipping":
                    ?>
					<h3 class="title-smenu" title="report" onclick="SwitchMenu('<?php echo $modCount ?>')">
					<?php echo $VM_LANG->_('PHPSHOP_SHIPPING_MOD')
					?>
					</h3>
					<div class="section-smenu">
					<ul>
                        <li class="item-smenu vmicon vmicon-16-content">
							<a href="<?php $sess->purl($_SERVER['PHP_SELF'] . "?pshop_mode=admin&page=shipping.carrier_list") ?>"><?php echo $VM_LANG->_('PHPSHOP_CARRIER_LIST_MNU') ?></a>
						</li>
                        <li class="item-smenu vmicon vmicon-16-editadd">
							<a href="<?php $sess->purl($_SERVER['PHP_SELF'] . "?pshop_mode=admin&page=shipping.carrier_form") ?>"><?php echo $VM_LANG->_('PHPSHOP_CARRIER_FORM_MNU') ?></a>
                    	</li>
                        <li class="item-smenu vmicon vmicon-16-content">
							<a href="<?php $sess->purl($_SERVER['PHP_SELF'] . "?pshop_mode=admin&page=shipping.rate_list") ?>"><?php echo $VM_LANG->_('PHPSHOP_RATE_LIST_MNU') ?></a>
						</li>
                        <li class="item-smenu vmicon vmicon-16-editadd">
							<a href="<?php $sess->purl($_SERVER['PHP_SELF'] . "?pshop_mode=admin&page=shipping.rate_form") ?>"><?php echo $VM_LANG->_('PHPSHOP_RATE_FORM_MNU') ?></a>
						</li>
					</ul>
				</div>
				<?php 
				$modCount++;
				break;
                        
                case "zone":
                    ?>
					<h3 class="title-smenu" title="report" onclick="SwitchMenu('<?php echo $modCount ?>')">
					<?php echo $VM_LANG->_('PHPSHOP_ZONE_MOD')
					?>
					</h3>
					<div class="section-smenu">
					<ul>
                        <li class="item-smenu vmicon vmicon-16-content">
                        	<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&page=zone.assign_zones") ?>"><?php echo $VM_LANG->_('PHPSHOP_ZONE_ASSIGN_MNU') ?></a>
						</li>
                        <li class="item-smenu vmicon vmicon-16-content">
                        	<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&page=zone.zone_list") ?>"><?php echo $VM_LANG->_('PHPSHOP_ZONE_LIST_MNU') ?></a>
						</li>
                        <li class="item-smenu vmicon vmicon-16-editadd">
							<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&page=zone.zone_form") ?>"><?php echo $VM_LANG->_('PHPSHOP_ZONE_FORM_MNU') ?></a>
						</li>
                    
					</ul>
				</div>
				<?php 
				$modCount++;
				break;

				case 'coupon':
					?>
					<h3 class="title-smenu" title="coupon" onclick="SwitchMenu('<?php echo $modCount ?>')">
					<?php echo $VM_LANG->_('PHPSHOP_COUPON_MOD')
					?>
					</h3>
					<div class="section-smenu">
					<ul>
					<li class="item-smenu vmicon vmicon-16-content">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=coupon.coupon_list") ?>"><?php echo $VM_LANG->_('PHPSHOP_COUPON_LIST') ?></a>
					</li>
					<li class="item-smenu vmicon vmicon-16-editadd">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=coupon.coupon_form") ?>"><?php echo $VM_LANG->_('PHPSHOP_COUPON_NEW_HEADER') ?></a>
					</li>
					</ul>
					</div>
				<?php 
				$modCount++;
				break;
				case 'manufacturer':
					?>
					<h3 class="title-smenu" title="manufacturer" onclick="SwitchMenu('<?php echo $modCount ?>')">
					<?php echo $VM_LANG->_('PHPSHOP_MANUFACTURER_MOD')
					?>
					</h3>
					<div class="section-smenu">
					<ul>
					<li class="item-smenu vmicon vmicon-16-content">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=manufacturer.manufacturer_list") ?>"><?php echo $VM_LANG->_('PHPSHOP_MANUFACTURER_LIST_MNU') ?></a>
					</li>
					<li class="item-smenu vmicon vmicon-16-editadd">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=manufacturer.manufacturer_form") ?>"><?php echo $VM_LANG->_('PHPSHOP_MANUFACTURER_FORM_MNU') ?></a>
					<hr />
					</li>
					<li class="item-smenu vmicon vmicon-16-content">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=manufacturer.manufacturer_category_list") ?>"><?php echo $VM_LANG->_('PHPSHOP_MANUFACTURER_CAT_LIST_MNU') ?></a>
					</li>
					<li class="item-smenu vmicon vmicon-16-editadd">
					<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=manufacturer.manufacturer_category_form") ?>"><?php echo $VM_LANG->_('PHPSHOP_MANUFACTURER_CAT_FORM_MNU') ?></a>
					</li>
					</ul>
					</div>
				<?php 
				$modCount++;
				break;
				
				
				case 'help':
					?>
					<h3 class="title-smenu" title="about" onclick="SwitchMenu('<?php echo $modCount ?>')">
					<?php echo $VM_LANG->_('PHPSHOP_HELP_MOD') ?></h3>
					<div class="section-smenu">
					<ul>
						<li class="item-smenu vmicon vmicon-16-info">
						<a href="<?php $sess->purl($_SERVER['PHP_SELF']."?pshop_mode=admin&amp;page=help.about");?>"><?php echo $VM_LANG->_('VM_ABOUT') ?></a>
						</li>
						<li class="item-smenu vmicon vmicon-16-help">
						<a href="http://virtuemart.net/documentation/User_Manual/index.html"><?php echo $VM_LANG->_('VM_HELP_TOPICS') ?></a>
						</li>
						<li class="item-smenu vmicon vmicon-16-language">
						<a href="http://forum.virtuemart.net/"><?php echo $VM_LANG->_('VM_COMMUNITY_FORUM') ?></a>
						</li>			
					</ul>
					<hr />
					</div>
					<?php
					$modCount++;
					break;
			}
			
		}
		?>
	</div>
	<div style="text-align:center;">
	<h5><?php echo $VM_LANG->_('VM_YOUR_VERSION') ?></h5>
	<a href="http://virtuemart.net/index2.php?option=com_versions&amp;catid=1&amp;myVersion=<?php echo @$VMVERSION->RELEASE ?>" onclick="javascript:void window.open(this.href, 'win2', 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=580,directories=no,location=no'); return false;" title="<?php echo $VM_LANG->_('VM_VERSIONCHECK_TITLE') ?>" target="_blank">
	<?php echo $VMVERSION->PRODUCT .'&nbsp;' . $VMVERSION->RELEASE .'&nbsp;'. $VMVERSION->DEV_STATUS
	?>
	</a>
	 </div>
      </div>
    </div>
  </div>
</div>
</div>
   
   
<?php 
if( $vmLayout == 'standard') {
	echo '<script type="text/javascript">
		window.onload=function(){
			Fat.fade_all();
			NiftyCheck();
			Rounded("div.sidemenu-box","all","#fff","#f7f7f7","border #ccc");
			Rounded("div.element-box","all","#fff","#fff","border #ccc");
			Rounded("div.toolbar-box","all","#fff","#fbfbfb","border #ccc");
			Rounded("div.submenu-box","all","#fff","#f2f2f2","border #ccc");
	
		}
	</script>';
}
if (!empty($error) && ($page != ERRORPAGE)) {
     echo '<br /><div class="message">'. $error.'</div><br />';
}
$db = new ps_DB();
