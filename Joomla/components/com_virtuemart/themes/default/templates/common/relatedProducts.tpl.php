<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); ?>

<hr/>

<h3><?php echo $VM_LANG->_('PHPSHOP_RELATED_PRODUCTS_HEADING') ?></h3>
 
<table width="100%" align="center">
	<tr>
    <?php 
    while( $products->next_record() ) { ?>
      	<td valign="top">
      		<?php echo $ps_product->product_snapshot( $products->f('product_sku') ) ?>
      	</td>
	<?php 
    }
	?>
    </tr>
</table> 
