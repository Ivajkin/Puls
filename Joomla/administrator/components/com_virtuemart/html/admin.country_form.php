<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: admin.country_form.php 1958 2009-10-08 20:09:57Z soeren_nb $
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

require_once( CLASSPATH. 'ps_zone.php');
$ps_zone = new ps_zone;
$country_id = vmGet( $_REQUEST, 'country_id' );
$option = empty($option)?vmGet( $_REQUEST, 'option', 'com_virtuemart'):$option;

if (!empty( $country_id )) {
  $q = "SELECT * from #__{vm}_country WHERE country_id='$country_id'";
  $db->query($q);
  $db->next_record();
}

$funcname = !empty($country_id) ? "countryUpdate" : "countryAdd";

// Create the Form Control Object
$formObj = new formFactory( $VM_LANG->_('PHPSHOP_COUNTRY_LIST_ADD') );

// Start the the Form
$formObj->startForm();
// Add necessary hidden fields
$formObj->hiddenField( 'country_id', $country_id );
?>
<table class="adminform">
	<tr> 
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr> 
		<td width="24%" align="right"><?php echo $VM_LANG->_('PHPSHOP_COUNTRY_LIST_NAME') ?>:</td>
		<td width="76%"> 
			<input type="text" class="inputbox" name="country_name" value="<?php $db->sp("country_name") ?>" />
		</td>
	</tr>
	<tr> 
		<td width="24%" align="right"><?php echo $VM_LANG->_('PHPSHOP_ZONE_ASSIGN_CURRENT_LBL') ?>:</td>
		<td width="76%"><?php echo $ps_zone->list_zones('zone_id',$db->f('zone_id'));  ?></td>
	</tr>
	<tr> 
		<td width="24%" align="right"><?php echo $VM_LANG->_('PHPSHOP_COUNTRY_LIST_2_CODE') ?>:</td>
		<td width="76%"> 
			<input type="text" class="inputbox" name="country_2_code" value="<?php $db->sp("country_2_code") ?>" />
		</td>
	</tr>
	<tr> 
		<td width="24%" align="right"><?php echo $VM_LANG->_('PHPSHOP_COUNTRY_LIST_3_CODE') ?>:</td>
		<td width="76%"> 
			<input type="text" class="inputbox" name="country_3_code" value="<?php $db->sp("country_3_code") ?>" />
		</td>
	</tr>
</table>
<?php
// Write common hidden input fields
// and close the form
$formObj->finishForm( $funcname, 'admin.country_list', $option );
?>