<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: admin.country_state_form.php 1961 2009-10-12 20:18:00Z Aravot $
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

$state_id = vmGet( $_REQUEST, 'state_id', null );
$country_id = vmGet( $_REQUEST, 'country_id', null );
$option = empty($option)?vmGet( $_REQUEST, 'option', 'com_virtuemart'):$option;

if( is_array( $country_id ))
	$country_id = $country_id[0];
	
if( !empty( $state_id )) {
  $q = "SELECT * FROM #__{vm}_state WHERE state_id = '$state_id' AND country_id='$country_id'";
  $db->query($q);
  $db->next_record();
}
//First create the object and let it print a form heading
$formObj = new formFactory(  );
//Then Start the form
$formObj->startForm();
?>
<table class="adminform">
    <tr> 
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr> 
      <td width="24%" align="right"><?php echo $VM_LANG->_('PHPSHOP_STATE_LIST_NAME') ?>:</td>
      <td width="76%"> 
        <input type="text" class="inputbox" name="state_name" value="<?php $db->sp("state_name") ?>" />
      </td>
    </tr>
    <tr> 
      <td width="24%" align="right"><?php echo $VM_LANG->_('PHPSHOP_STATE_LIST_2_CODE') ?>:</td>
      <td width="76%"> 
        <input type="text" class="inputbox" name="state_2_code" value="<?php $db->sp("state_2_code") ?>" />
      </td>
    </tr>
        <tr> 
      <td width="24%" align="right"><?php echo $VM_LANG->_('PHPSHOP_STATE_LIST_3_CODE') ?>:</td>
      <td width="76%"> 
        <input type="text" class="inputbox" name="state_3_code" value="<?php $db->sp("state_3_code") ?>" />
      </td>
    </tr>
    
  </table>  
<?php
  
// Add necessary hidden fields
$formObj->hiddenField( 'state_id', $state_id );
$formObj->hiddenField( 'country_id', $country_id );

$funcname = !empty($state_id) ? "stateUpdate" : "stateAdd";

// Write your form with mixed tags and text fields
// and finally close the form:
$formObj->finishForm( $funcname, 'admin.country_state_list', $option );
?>
