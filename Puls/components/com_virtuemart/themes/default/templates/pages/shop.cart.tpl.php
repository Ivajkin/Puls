<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: shop.cart.tpl.php 1712 2009-03-30 07:38:09Z Aravot $
* @package VirtueMart
* @subpackage themes
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

echo '<h2>'. $VM_LANG->_('PHPSHOP_CART_TITLE') .'</h2>
<!-- Cart Begins here -->
';
include(PAGEPATH. 'basket.php');
echo $basket_html;
echo '<!-- End Cart --><br /><br />
';
// show Continue Shopping link when the cart is empty 
if ($cart["idx"] == 0) {
    ?>
    <?php
      if( $continue_link != '') {
       ?>
        <a href="<?php echo $continue_link ?>" class="continue_link">
       <?php echo $VM_LANG->_('PHPSHOP_CONTINUE_SHOPPING'); ?>
        </a>
       <?php
    }
    else {
      $continue_link=$sess->url($_SERVER['PHP_SELF'].'?option=com_virtuemart' ); 
    ?>
    <a href="<?php echo $continue_link ?>" class="continue_link">
    <?php echo $VM_LANG->_('PHPSHOP_CONTINUE_SHOPPING'); ?>
    </a>
    <?php
    }
}
// end Continue Shopping link	

if ($cart["idx"]) {
    ?>
    
	
	<?php
	// End if statement
}
?>