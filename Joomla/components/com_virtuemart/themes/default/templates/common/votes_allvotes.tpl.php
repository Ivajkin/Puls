<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); ?>

<!-- The "Average Customer Rating: xxxxX (2 votes) " Part -->
<span class="contentpagetitle"><?php echo $VM_LANG->_('PHPSHOP_CUSTOMER_RATING') ?>:</span>
<br />
<img src="<?php echo VM_THEMEURL ?>images/stars/<?php echo $rating ?>.gif" align="middle" border="0" alt="<?php echo $rating ?> stars" />&nbsp;
<?php echo $VM_LANG->_('PHPSHOP_TOTAL_VOTES').": ". $allvotes; ?>
