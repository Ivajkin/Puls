<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: admin.user_address_form.php 1589 2008-12-08 20:27:20Z soeren_nb $
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

$mainframe->setPageTitle( $VM_LANG->_('PHPSHOP_USER_FORM_ADD_SHIPTO_LBL') );

require_once( CLASSPATH . "ps_userfield.php" );

$user_id = vmrequest::getint( 'user_id' );
$user_info_id = vmGet( $_REQUEST, 'user_info_id' );
$missing = vmGet( $vars, 'missing' );
$missing_style = "color: Red; font-weight: Bold;";

if (!empty( $missing )) {
    echo "<script type=\"text/javascript\">alert('".$VM_LANG->_('CONTACT_FORM_NC')."'); </script>\n";
}
?>
<h2><?php echo $VM_LANG->_('PHPSHOP_USER_FORM_ADD_SHIPTO_LBL') ?></h2>
<?php if (!empty($user_info_id)) {
   $q = "SELECT * from #__{vm}_user_info ";
   $q .= "WHERE #__{vm}_user_info.user_info_id='$user_info_id' ";
   $db->query($q);
   $db->next_record();
}
?>
<div style="width:90%;" class="adminform">
<fieldset>
	<legend><span class="sectiontableheader"><?php echo $VM_LANG->_('PHPSHOP_SHOPPER_FORM_SHIPTO_LBL') ?></span></legend>

	<!-- Registration form -->
	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" name="adminForm">
<?php
// Display the shipping address
$fields = ps_userfield::getUserFields( 'shipping' );
ps_userfield::listUserFields( $fields, array(), $db, false );
?>

	<input type="hidden" name="option" value="com_virtuemart" />
<?php if (!empty($user_info_id)) : ?>
	<input type="hidden" name="func" value="userAddressUpdate" />
	<input type="hidden" name="user_info_id" value="<?php echo $user_info_id ?>" />
<?php else : ?>
	<input type="hidden" name="func" value="userAddressAdd" />
<?php endif; ?>
<?php if( $vmLayout == 'extended' ) : ?>
	<input type="hidden" name="ajax_request" value="1" />
<?php endif; ?>

	<input type="hidden" name="vmtoken" value="<?php echo vmSpoofValue($GLOBALS['sess']->getSessionId() ) ?>" />
	<input type="hidden" name="address_type" value="ST">
	<input type="hidden" name="page" value="<?php echo $modulename ?>.user_form"  />
	<input type="hidden" name="cache" value="0" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
	<input type="hidden" name="cid[0]" value="<?php echo $user_id; ?>" />
	</form>
</fieldset>
</div>
  

