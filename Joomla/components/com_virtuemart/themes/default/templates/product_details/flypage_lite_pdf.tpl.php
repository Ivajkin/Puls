<?php 
// this template must have quirky html, because HTML2PDF doesn't fully understand
// CSS and XHTML
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); ?>

<br><br>
<h1><?php echo $product_name ?></h1>
<br><br>

<table width=100%>
<tr><td width=50%><br><?php echo $product_price ?> </td>
<td width=50%><?php echo urldecode( $product_image ) ?>&nbsp;</td>
</tr>
</table>



<?php echo $product_description ?>

<?php echo $product_type ?>
<table width=100%>
<tr><td><?php echo $vendor_link ?></td></tr>
</table>

<table>
<tr><td>
<?php echo $product_reviews ?>
</td></tr>
</table>
