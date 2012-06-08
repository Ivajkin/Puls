<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
mm_showMyFileName(__FILE__);
 ?>
 <table width="100%" cellspacing="0" cellpadding="0" border="0" >
  <tr>
    <td >
        <a style="font-size: 16px; font-weight: bold;" href="<?php echo $product_flypage ?>"><?php echo $product_name ?></a>
    </td>
  </tr>
  <tr>
    <td align="left" nowrap ><?php echo $product_price ?></td>
  </tr>
  <tr>
    <td ><a href="<?php echo $product_flypage ?>">
          <?php echo ps_product::image_tag( urldecode($product_thumb_image), 'class="browseProductImage" border="0" title="'.$product_name.'" alt="'.$product_name .'"' ) ?>
       </a>
    </td>
  </tr>
  <tr>
    <td height="80" valign="top"><?php echo $product_s_desc ?><br />
      <a style="font-size: 9px; font-weight: bold;" href="<?php echo $product_flypage ?>">[<?php echo $product_details ?>...]</a>
    </td>
  </tr>
  <tr>
    <td ><hr /></td>
  </tr>
  <tr>
    <td ><?php echo $product_rating ?></td>
  </tr>
</table>
