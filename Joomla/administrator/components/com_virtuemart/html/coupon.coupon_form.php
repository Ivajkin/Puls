<?php 
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: coupon.coupon_form.php 1961 2009-10-12 20:18:00Z Aravot $
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

$coupon_id = vmGet( $_REQUEST, 'coupon_id', null );
$option = empty($option)?vmGet( $_REQUEST, 'option', 'com_virtuemart'):$option;

if ( $coupon_id ) {
	$q = "SELECT * FROM #__{vm}_coupons WHERE coupon_id='$coupon_id'";
	$db->query($q);
	$db->next_record();
	if( $db->f("coupon_type")=="gift") {
		$selected[0] = "selected=\"selected\"";
		$selected[1] = "";
	}
	else {
		$selected[1] = "selected=\"selected\"";
		$selected[0] = "";
	}
	$title = $VM_LANG->_('PHPSHOP_COUPON_EDIT_HEADER');

}
else {
	$selected[0] = "selected=\"selected\"";
	$selected[1] = "";
	$title = $VM_LANG->_('PHPSHOP_COUPON_NEW_HEADER');
}
//First create the object and let it print a form heading
$formObj = new formFactory( $title );
//Then Start the form
$formObj->startForm();

?>

  <table class="adminform">
    <tr> 
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr> 
      <td width="24%"><div align="right"><?php echo $VM_LANG->_('PHPSHOP_COUPON_COUPON_HEADER') ?>:</div></td>
      <td width="76%"> 
        <input type="text" class="inputbox" name="coupon_code" value="<?php $db->sp("coupon_code") ?>" />
      </td>
    </tr>
    <tr> 
      <td width="24%"><div align="right"><?php echo $VM_LANG->_('PHPSHOP_COUPON_PERCENT_TOTAL') ?>:</div></td>
      <td width="76%"> 
        <input type="radio" class="inputbox" name="percent_or_total" value="percent" <?php if($db->sf("percent_or_total")=='percent' || empty($coupon_id)) echo "checked=\"checked\""; ?> />
        <?php echo $VM_LANG->_('PHPSHOP_COUPON_PERCENT') ?>&nbsp;&nbsp;&nbsp;
        <?php echo mm_ToolTip( $VM_LANG->_('PHPSHOP_PRODUCT_DISCOUNT_ISPERCENT_TIP') ); ?><br />
        <input type="radio" class="inputbox" name="percent_or_total" value="total" <?php if($db->sf("percent_or_total")=='total') echo "checked=\"checked\""; ?> />
        <?php echo $VM_LANG->_('PHPSHOP_COUPON_TOTAL') ?>
      </td>
    </tr>
    <tr> 
      <td width="24%"><div align="right"><?php echo $VM_LANG->_('PHPSHOP_COUPON_TYPE') ?>:</div></td>
      <td width="76%">
        <select class="inputbox" name="coupon_type">
          <option value="gift" <?php echo $selected[0] ?>>
            <?php echo $VM_LANG->_('PHPSHOP_COUPON_TYPE_GIFT') ?>
          </option>
          <option value="permanent" <?php echo $selected[1] ?>>
            <?php echo $VM_LANG->_('PHPSHOP_COUPON_TYPE_PERMANENT') ?>
          </option>
        </select>
        <?php echo mm_ToolTip( $VM_LANG->_('PHPSHOP_COUPON_TYPE_TOOLTIP') ); ?>
      </td>
    </tr>
    <tr> 
      <td width="24%"><div align="right"><?php echo $VM_LANG->_('PHPSHOP_COUPON_VALUE') ?>:</div></td>
      <td width="76%"> 
        <input type="text" class="inputbox" name="coupon_value" value="<?php $db->sp("coupon_value"); ?>" />
      </td>
    </tr>
    <tr> 
      <td valign="top" colspan="2" align="right">&nbsp; </td>
    </tr>   
  </table>
<?php 
$funcname = !empty( $coupon_id ) ? 'couponUpdate' : 'couponAdd';

// Add necessary hidden fields
$formObj->hiddenField( 'coupon_id', $coupon_id );

// Write your form with mixed tags and text fields
// and finally close the form:
$formObj->finishForm( $funcname, $modulename.'.coupon_list', $option );
?>
