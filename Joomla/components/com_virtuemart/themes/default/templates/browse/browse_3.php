<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
mm_showMyFileName(__FILE__);
 ?>
 <div class="browseProductContainer">
  <h2>
  <a style="font-size:16px; font-weight:bold;" href="<?php echo $product_flypage ?>"><?php echo $product_name ?></a>
  </h2>
  <p ><?php echo $product_price ?></p>
  <div style="float:left;width:90%" >
  	<a href="<?php echo $product_flypage ?>" title="<?php echo $product_name ?>">
        <?php echo ps_product::image_tag( urldecode($product_thumb_image), 'class="browseProductImage" border="0" title="'.$product_name.'" alt="'.$product_name .'"' ) ?>
       </a>
  </div>
  
  <br style="clear:both;" />
  
  <div style="float:left;width:90%"><?php echo $product_s_desc ?></div>
  
  <a href="<?php echo $product_flypage ?>">[<?php echo $product_details ?>...]</a>
  <br style="clear:both;" />
  <div style="float:left;width:90%;margin-top: 3px;">
      <?php echo $product_rating ?>
  </div>
  <div style="float:left;width:90%;margin-top: 3px;"><?php echo $form_addtocart ?>
  </div>
  <br style="clear:both;" />
</div>
