<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: admin.usergroup_form.php 1961 2009-10-12 20:18:00Z Aravot $
* @package VirtueMart
* @subpackage html
* @copyright Copyright (C) 2004-2007 soeren - All rights reserved.
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
require_once(CLASSPATH.'usergroup.class.php');
$usergroup = new vmUserGroup();

$group_id = (int)vmGet( $_REQUEST, 'group_id', 0 );
$option = empty($option)?vmGet( $_REQUEST, 'option', 'com_virtuemart'):$option;

if (!empty( $group_id )) {
  $db = $usergroup->get_group($group_id);
} else {
}

$funcname = !empty($group_id) ? "usergroupUpdate" : "usergroupAdd";

// Create the Form Control Object
$formObj = new formFactory( $VM_LANG->_('VM_USERGROUP_FORM_LBL') );

// Start the the Form
$formObj->startForm();
// Add necessary hidden fields
$formObj->hiddenField( 'group_id', $group_id );
?>
<table class="adminform">
	<tr> 
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr> 
		<td width="24%" align="right"><?php echo $VM_LANG->_('VM_USERGROUP_NAME') ?>:</td>
		<td width="76%"> 
			<input type="text" class="inputbox" name="group_name" value="<?php $db->sp("group_name") ?>" />
		</td>
	</tr>
	<tr> 
		<td width="24%" align="right"><?php echo $VM_LANG->_('VM_USERGROUP_LEVEL') ?>:</td>
		<td width="76%"> 
			<input type="text" class="inputbox" name="group_level" value="<?php $db->sp("group_level") ?>" />
			<?php echo vmToolTip( $VM_LANG->_('VM_USERGROUP_LEVEL_TIP') ); ?>
		</td>
	</tr>
</table>
<?php
// Write common hidden input fields
// and close the form
$formObj->finishForm( $funcname, 'admin.usergroup_list', $option );
?>
