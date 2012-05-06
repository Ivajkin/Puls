<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
mm_showMyFileName(__FILE__);
 ?>
 <div style="width:100%;padding: 0px 3px 3px 3px;">
  <h2>
  <a style="font-size:16px; font-weight:bold;" href="<?php echo $product_flypage ?>"><?php echo $product_name ?></a>
  </h2>
    <div style="float:left;width:32%" >
    	<a href="<?php echo $product_flypage ?>">
          <?php echo ps_product::image_tag( urldecode($product_thumb_image), 'class="browseProductImage" border="0" title="'.$product_name.'" alt="'.$product_name .'"' ) ?>
       </a>
    </div>
    <div style="float:left;width:60%"><?php echo $product_s_desc ?><br />
      <a href="<?php echo $product_flypage ?>">[<?php echo $product_details ?>...]</a>
    </div>
  <br style="clear:both;" />
  <p><?php echo $product_price ?></p>
  <div style="float:left;width:60%">
      <?php echo $product_rating ?>
  </div>
  <div style="float:left;width:32%"><?php echo $form_addtocart ?>
  </div>
  <br style="clear:both;" />
</div>
