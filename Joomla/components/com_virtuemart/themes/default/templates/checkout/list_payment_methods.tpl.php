<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: list_payment_methods.tpl.php 1332 2008-03-28 22:24:05Z thepisu $
* @package VirtueMart
* @subpackage templates
* @copyright Copyright (C) 2007-2008 soeren - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/

if( $nocc_payments &&  $cc_payments ) {
	echo '<table><tr valign="top"><td width="50%">';
}
        
if ($cc_payments==true) { 
  	?>
	<fieldset><legend><strong><?php echo $VM_LANG->_('PHPSHOP_CHECKOUT_PAYMENT_CC') ?></strong></legend>
		<table border="0" cellspacing="0" cellpadding="2" width="100%">
		    <tr>
		        <td colspan="2">
		        	<?php $ps_payment_method->list_cc($payment_method_id, false) ?>
		        </td>
		    </tr>
		    <tr>
		        <td colspan="2"><strong>&nbsp;</strong></td>
		    </tr>
		    <tr>
		        <td nowrap width="10%" align="right"><?php echo $VM_LANG->_('VM_CREDIT_CARD_TYPE'); ?>:</td>
		        <td>
		        <?php echo $ps_creditcard->creditcard_lists( $db_cc ); ?>
		        <script language="Javascript" type="text/javascript"><!--
				writeDynaList( 'class="inputbox" name="creditcard_code" size="1"',
				orders, originalPos, originalPos, originalOrder );
				//-->
				</script>
		<?php 
		            $db_cc->reset();
		            $payment_class = $db_cc->f("payment_class");
		            $require_cvv_code = "YES";
		            if(file_exists(CLASSPATH."payment/$payment_class.php") && file_exists(CLASSPATH."payment/$payment_class.cfg.php")) {
		                require_once(CLASSPATH."payment/$payment_class.php");
		                require_once(CLASSPATH."payment/$payment_class.cfg.php");
		                $_PAYMENT = new $payment_class();
		                if( defined( $_PAYMENT->payment_code.'_CHECK_CARD_CODE' ) ) {
		                	$require_cvv_code = strtoupper( constant($_PAYMENT->payment_code.'_CHECK_CARD_CODE') );
		                }
		            }
		?>      </td>
		    </tr>
		    <tr valign="top">
		        <td nowrap width="10%" align="right">
		        	<label for="order_payment_name"><?php echo $VM_LANG->_('PHPSHOP_CHECKOUT_CONF_PAYINFO_NAMECARD') ?>:</label>
		        </td>
		        <td>
		        <input type="text" class="inputbox" id="order_payment_name" name="order_payment_name" value="<?php if(!empty($_SESSION['ccdata']['order_payment_name'])) echo $_SESSION['ccdata']['order_payment_name'] ?>" autocomplete="off" />
		        </td>
		    </tr>
		    <tr valign="top">
		        <td nowrap width="10%" align="right">
		        	<label for="order_payment_number"><?php echo $VM_LANG->_('PHPSHOP_CHECKOUT_CONF_PAYINFO_CCNUM') ?>:</label>
		        </td>
		        <td>
		        <input type="text" class="inputbox" id="order_payment_number" name="order_payment_number" value="<?php if(!empty($_SESSION['ccdata']['order_payment_number'])) echo $_SESSION['ccdata']['order_payment_number'] ?>" autocomplete="off" />
		        </td>
		    </tr>
		<?php if( $require_cvv_code == "YES" ) { 
					$_SESSION['ccdata']['need_card_code'] = 1;	
			?>
		    <tr valign="top">
		        <td nowrap width="10%" align="right">
		        	<label for="credit_card_code">
		        		<?php echo vmToolTip( $VM_LANG->_('PHPSHOP_CUSTOMER_CVV2_TOOLTIP'), '', '', '', $VM_LANG->_('PHPSHOP_CUSTOMER_CVV2_TOOLTIP_TITLE') ) ?>:
		        	</label>
		        </td>		        		
		        <td>
		            <input type="text" class="inputbox" id="credit_card_code" name="credit_card_code" value="<?php if(!empty($_SESSION['ccdata']['credit_card_code'])) echo $_SESSION['ccdata']['credit_card_code'] ?>" autocomplete="off" />
		        
		        </td>
		    </tr>
		<?php } ?>
		    <tr>
		        <td nowrap width="10%" align="right"><?php echo $VM_LANG->_('PHPSHOP_CHECKOUT_CONF_PAYINFO_EXDATE') ?>:</td>
		        <td><?php 
		        $ps_html->list_month("order_payment_expire_month", @$_SESSION['ccdata']['order_payment_expire_month']);
		        echo "/";
		        $ps_html->list_year("order_payment_expire_year", @$_SESSION['ccdata']['order_payment_expire_year']) ?>
		       </td>
		    </tr>
    	</table>
    </fieldset>
  <?php  
}

if( $nocc_payments &&  $cc_payments ) {
	echo '</td><td width="50%">';
}

if ($nocc_payments==true) {
    if ($cc_payments==true) { 
    	$title = $VM_LANG->_('PHPSHOP_CHECKOUT_PAYMENT_OTHER');
    }
    else {
    	$title = $VM_LANG->_('PHPSHOP_ORDER_PRINT_PAYMENT_LBL');
    }
    	
   ?>
    <fieldset><legend><strong><?php echo $title ?></strong></legend>
		<table border="0" cellspacing="0" cellpadding="2" width="100%">
		    <tr>
		        <td colspan="2"><?php 
		            $ps_payment_method->list_nocheck($payment_method_id,  false); 
		            $ps_payment_method->list_bank($payment_method_id,  false);
		            $ps_payment_method->list_paypalrelated($payment_method_id,  false); ?>
		        </td>
		    </tr>
		 </table>
	</fieldset>
	<?php
}

if( $nocc_payments &&  $cc_payments ) {
	echo '</td></tr></table>';
}
  ?>
