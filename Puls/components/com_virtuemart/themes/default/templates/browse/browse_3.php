<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
mm_showMyFileName(__FILE__);
?>


<?php
$prod_com_path = $mosConfig_absolute_path . '/components/com_virtuemart/prod_com.php';
  if (file_exists($prod_com_path)) {
    require_once($prod_com_path);
    /*echo $pr_com_count.' '.$product_id;
    $output= '';
    $output .= '<div >'.$product_reviews.'<br>'.$product_reviewform.'</div>';
    echo $output;*/
}
 ?>

 <div class="browseProductContainer" style="padding:0; margin:0; border:0;)">
  <h2>
    <a style="font-size:16px; font-weight:bold;" href="javascript:void(0)" >
      <?php echo $product_name ?>
    </a>
  </h2>
  <p >Стоимость: <?php echo $product_price ?></p>
  <div style="float:left;width:90%" >
  	<a href="<?php echo $product_flypage ?>" title="<?php echo $product_name ?>">
        <?php echo ps_product::image_tag( urldecode($product_thumb_image), 'class="browseProductImage" border="0" title="'.$product_name.'" alt="'.$product_name .'"' ) ?>
       </a>
  </div>
  
  <br style="clear:both;" /> <!--$product_sku-->
  <div style="float:left;width:90%"><?php echo $product_s_desc ?></div>

  <br style="clear:both;" />
  <a href="<?php echo $product_flypage ?>">Подробнее >>></a>
  <br style="clear:both;" />

  <br style="clear:both;" />
</div>