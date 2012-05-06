<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: account.shipto.tpl.php 2543 2010-09-26 15:29:25Z zanardi $
* @package VirtueMart
* @subpackage templates
* @copyright Copyright (C) 2007 Soeren Eberhardt. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/
/*****************************
** Checkout Bar Feature
**/
?>
<div class="pathway"><?php echo $vmPathway; ?></div>
<div style="float:left;width:90%;text-align:right;"> 
    <span>
    	<a href="#" onclick="if( submitregistration() ) { document.adminForm.submit(); return false;}">
    		<img border="0" src="images/save_f2.png" name="submit" alt="<?php echo $VM_LANG->_('CMN_SAVE') ?>" />
    	</a>
    </span>
    <span style="margin-left:10px;">
    	<a href="<?php $sess->purl( SECUREURL."index.php?page=$next_page") ?>">
    		<img src="images/back_f2.png" alt="<?php echo $VM_LANG->_('BACK') ?>" border="0" />
    	</a>
    </span>
</div>
<?php
/**
** End Checkout Bar Feature
*****************************/
?>
<fieldset>
        <legend><span class="sectiontableheader"><?php echo $VM_LANG->_('PHPSHOP_SHOPPER_FORM_SHIPTO_LBL') ?></span></legend>
        
<br />
<?php echo $VM_LANG->_('PHPSHOP_SHIPTO_TEXT') ?>
<br /><br /><br />

<div style="width:90%;">
<?php
ps_userfield::listUserFields( $fields, array(), $db );
?>

  <input type="hidden" name="option" value="com_virtuemart" />
  <input type="hidden" name="Itemid" value="<?php echo $Itemid ?>" />
  <input type="hidden" name="page" value="<?php echo $next_page ?>" />
  <input type="hidden" name="next_page" value="<?php echo $next_page ?>" />
  <input type="hidden" name="vmtoken" value="<?php echo vmspoofvalue( $sess->getSessionId() ) ?>" />
<?php
   if (!empty($user_info_id)) { ?>
      <input type="hidden" name="func" value="userAddressUpdate" />
      <input type="hidden" name="user_info_id" value="<?php echo $user_info_id ?>" />
<?php 
   }
   else { ?>
      <input type="hidden" name="func" value="userAddressAdd" />
<?php 
    } ?>
  <input type="hidden" name="user_id" value="<?php echo $auth["user_id"] ?>" />
  <input type="hidden" name="address_type" value="ST" />
  

    
  <br/>
  </form>
<?php
  if (!empty($user_info_id)) { ?>
    <div style="float:left;width:45%;text-align:center;"> 
      <form action="<?php echo SECUREURL ?>index.php" method="post">
        <input type="hidden" name="option" value="com_virtuemart" />
        <input type="hidden" name="page" value="<?php echo $next_page ?>" />
        <input type="hidden" name="next_page" value="<?php echo $next_page ?>" />
        <input type="hidden" name="func" value="useraddressdelete" />
        <input type="hidden" name="user_info_id" value="<?php echo $user_info_id ?>" />
        <input type="hidden" name="user_id" value="<?php echo $auth["user_id"] ?>" />
        <input type="submit" class="button" name="submit" value="<?php echo $VM_LANG->_('E_REMOVE') ?>" />
      </form>
    </div>
<?php 
  } ?>
  </div>
  </fieldset>
