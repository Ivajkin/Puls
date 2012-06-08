<?php 
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: store.shipping_module_form.php 2545 2010-09-26 18:26:55Z zanardi $
* @package VirtueMart
* @subpackage html
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
mm_showMyFileName( __FILE__ );

$shipping_module = vmGet($_REQUEST, 'shipping_module', null);

if( $shipping_module ) {
	if( !include_once( CLASSPATH.'shipping/'.basename($shipping_module) )) {
		vmRedirect( $_SERVER['PHP_SELF']."?option=com_virtuemart&page=store.shipping_module_form", str_replace('{shipping_module}',$shipping_module,$VM_LANG->_('VM_SHIPPING_MODULE_CLASSERROR')));
	}
	else {
		$classname = basename($shipping_module,".php"); 
		if( class_exists($classname)) {
			$_SHIPPING = new $classname();
		}
	}
	$ps_html->writableIndicator(CLASSPATH."shipping/".basename($shipping_module,".php").'.cfg.php');
	
  ?>
  <div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>
  <script type="text/javascript" src="<?php echo $mosConfig_live_site;?>/includes/js/overlib_mini.js"></script>
	<table class="adminform">
	<tr>
	<td>
  
  <?php
	// Create the Form Control Object
	$formObj = new formFactory( $VM_LANG->_('VM_SHIPPING_MODULE_CONFIG_LBL') . ': '. $shipping_module );
	
	// Start the the Form
	$formObj->startForm();

  	$_SHIPPING->show_configuration();
  
  	// Write common hidden input fields
  	$formObj->hiddenField('shipping_class', basename($shipping_module,".php") );
  	$formObj->hiddenField('shipping_module', $shipping_module );
	// and close the form
	$formObj->finishForm( 'shippingmethodSave', 'store.shipping_module_form', $option );
	?>
	</td>
	</tr>
	</table>
	<?php
}
else {
	//TODO: Form for new shipping modules
}
?>
