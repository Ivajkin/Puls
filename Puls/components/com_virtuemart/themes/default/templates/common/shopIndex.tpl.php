<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); ?>

<script type="text/javascript" src="/paradigm/Puls/libraries/jquery-1.8.1.min.js"></script>
<script type="text/javascript">
            var $j = jQuery.noConflict();

$j(document).ready(function() {
      $j('#vmMainPage table td').each(function(index) {
          $j(this).prepend('<h2 style="text-align: center; font-size: 1.5em"></h2>');
          $j(this).find('a').prependTo($j(this).find('h2')); 
          $j(this).find('a').css('word-wrap', 'break-word');
      });
});
</script>

<?php
defined( 'vmToolTipCalled') or define('vmToolTipCalled', 1);

echo "<h3 class='componentheading'>".$VM_LANG->_('PHPSHOP_CATEGORIES')."</h3>";
echo $categories; ?>
<div class="vmRecent">
<?php echo $recent_products; ?>
</div>
<?php
// Show Featured Products
if( $this->get_cfg( 'showFeatured', 1 )) {
    /* featuredproducts(random, no_of_products,category_based) no_of_products 0 = all else numeric amount
    edit featuredproduct.tpl.php to edit layout */
    echo $ps_product->featuredProducts(true,10,false);
}
// Show Latest Products
if( $this->get_cfg( 'showlatest', 1 )) {
    /* latestproducts(random, no_of_products,month_based,category_based) no_of_products 0 = all else numeric amount
    edit latestproduct.tpl.php to edit layout */
    ps_product::latestProducts(true,10,false,false);
}
?>

<?php if( isset($paypalLogo)) : ?>
<div class="vmRecent" style="padding: 10px; text-align: center;">
	<?php echo $paypalLogo; ?>
</div>
<?php endif; ?>