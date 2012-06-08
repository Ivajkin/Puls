<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
mm_showMyFileName(__FILE__);
 ?>
 <div class="browseProductContainer">
  <h2>
  <a style="font-size:16px; font-weight:bold;" href="<?php echo $product_flypage ?>"><?php echo $product_name ?></a>
  </h2>
  <p >Стоимость: <?php echo $product_price ?></p>
  <p>&nbsp;</p>
  <div style="float:left;width:90%" >
  	<a href="<?php echo $product_flypage ?>" title="<?php echo $product_name ?>">
        <?php echo ps_product::image_tag( urldecode($product_thumb_image), 'class="browseProductImage" border="0" title="'.$product_name.'" alt="'.$product_name .'"' ) ?>
       </a>
  </div>
  
  <br style="clear:both;" />
  <p>&nbsp;</p>
  <div style="float:left;width:90%"><?php echo $product_s_desc ?></div>

  <br style="clear:both;" />
  <a href="<?php echo $product_flypage ?>">Подробнее >>></a>
  <br style="clear:both;" />

  <br style="clear:both;" />
</div>
