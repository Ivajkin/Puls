<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );

$quantity_in_stock = ps_product::get_field( $product_id, 'product_in_stock');

if( CHECK_STOCK == '1' && ( $quantity_in_stock < 1 ) ) {
	$button_lbl = $VM_LANG->_('VM_CART_NOTIFY');
	$button_cls = 'notify_button_module';
	$notify = true;
} else {
	$button_lbl = $VM_LANG->_('PHPSHOP_CART_ADD_TO');
	$button_cls = 'addtocart_button_module';
	$notify = false;
}

?>

<!-- The product name DIV. -->
 <?php if( $show_product_name ) : ?>
<div style="height:77px; float:left; width: 100%;line-height:14px;">
<a title="<?php echo $product_name ?>" href="<?php echo $product_link ?>"><?php echo $product_name; ?></a>
<br />
</div>
<?php endif;?>

<!-- The product image DIV. -->
<div style="height:90px;width: 100%;float:left;margin-top:-15px;">
<a title="<?php echo $product_name ?>" href="<?php echo $product_link ?>">
	<?php
		// Print the product image or the "no image available" image
		echo ps_product::image_tag( $product_thumb_image, "alt=\"".$product_name."\"");
	?>
</a>
</div>

<!-- The product price DIV. -->
<div style="width: 100%;float:left;text-align:center;">
<?php
if( !empty($price) ) {
	echo $price;
}
?>
</div>

<!-- The add to cart DIV. -->
<div style="float:left;text-align:center;width: 100%;">
<?php
if( !empty($addtocart_link) ) {
	?>
	<br />
	<form action="<?php echo  $mm_action_url ?>index.php" method="post" name="addtocart" id="addtocart">
    <input type="hidden" name="option" value="com_virtuemart" />
    <input type="hidden" name="page" value="shop.cart" />
    <input type="hidden" name="Itemid" value="<?php echo ps_session::getShopItemid(); ?>" />
    <input type="hidden" name="func" value="cartAdd" />
    <input type="hidden" name="prod_id" value="<?php echo $product_id; ?>" />
    <input type="hidden" name="product_id" value="<?php echo $product_id ?>" />
    <input type="hidden" name="quantity" value="1" />
    <input type="hidden" name="set_price[]" value="" />
    <input type="hidden" name="adjust_price[]" value="" />
    <input type="hidden" name="master_product[]" value="" />
    <input type="submit" class="<?php echo $button_cls ?>" value="<?php echo $button_lbl ?>" title="<?php echo $button_lbl ?>" />
    </form>
	<br />
	<?php
}
?>

</div>
