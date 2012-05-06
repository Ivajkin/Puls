<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
mm_showMyFileName(__FILE__); ?>

<div align="right">
    <form action="<?php echo $mm_action_url."index.php?option=com_virtuemart&amp;page=shop.parameter_search_form&amp;product_type_id=$product_type_id&amp;Itemid=" . $_REQUEST['Itemid'] ?>" method="post" name="back">
        <?php 
        echo $ps_product_type->get_parameter_form($product_type_id);
        ?>	  
      		<strong><?php
      		echo $VM_LANG->_('PHPSHOP_PARAMETER_SEARCH_IN_CATEGORY').": ".$ps_product_type->get_name($product_type_id);
        ?></strong>&nbsp;&nbsp;<br/>
	  <input type="submit" class="button" id="<?php echo $VM_LANG->_('PHPSHOP_PARAMETER_SEARCH_CHANGE_PARAMETERS') ?>" name="edit" value="<?php echo $VM_LANG->_('PHPSHOP_PARAMETER_SEARCH_CHANGE_PARAMETERS') ?>" />
	</form>
</div>