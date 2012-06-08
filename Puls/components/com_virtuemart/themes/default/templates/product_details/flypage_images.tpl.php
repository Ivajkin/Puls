<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); ?>

<?php echo $buttons_header // The PDF, Email and Print buttons ?>

<?php 
if( $this->get_cfg( 'showPathway' )) {
	echo "<div class=\"pathway\">$navigation_pathway</div>";
}
?>
<br/>
<table border="0" style="width: 100%;">
  <tbody>
	<tr>
	  <td rowspan="5" valign="top" style="text-align:center;"><br/>
	  	<?php echo urldecode( $product_image ) ?>
	  	<br/><br/>
	  	<?php if( !empty($images)) { ?>
		  	<div class="thumbnailListContainer">
		  		<h5><?php echo $VM_LANG->_('PHPSHOP_MORE_IMAGES') ?></h5>
		  		<?php 
					echo $this->vmListAdditionalImages( $product_id, $images );
		  		?>
		  	</div>
		 <?php } 	?>
	  </td>
	  <td rowspan="1" colspan="2">
	  <h1><?php echo $product_name ?> <?php echo $edit_link ?></h1>
	  </td>
	</tr>
	<?php if( $this->get_cfg('showManufacturerLink')) { ?>
		<tr>
		  <td rowspan="1" colspan="2"><?php echo $manufacturer_link ?><br /></td>
		</tr>
	<?php } ?>
	<tr>
      <td width="33%" valign="top" align="left">
      	<?php echo $product_price_lbl ?>
      	<?php echo $product_price ?><br /></td>
      <td valign="top"><?php echo $product_packaging ?><br /></td>
	</tr>
	<tr>
	  <td colspan="2">
	  	<?php echo $ask_seller ?>
	  </td>
	</tr>
	<tr>
	  <td rowspan="1" colspan="2"><hr />
	  	<?php echo $product_description ?><br/>
	  	<span style="font-style: italic;"><?php echo $file_list ?></span>
	  </td>
	</tr>
	<tr>
	  <td><?php 
	  		if( $this->get_cfg( 'showAvailability' )) {
	  			echo $product_availability; 
	  		}
	  		?><br />
	  </td>
	  <td colspan="2"><br /><?php echo $addtocart ?></td>
	</tr>
	<tr>
	  <td colspan="3"><?php echo $product_type ?></td>
	</tr>
	<tr>
	  <td colspan="3"><hr /><?php echo $product_reviews ?></td>
	</tr>
	<tr>
	  <td colspan="3"><?php echo $product_reviewform ?><br /></td>
	</tr>
	<tr>
	  <td colspan="3"><?php echo $related_products ?><br />
	   </td>
	</tr>
	<?php if( $this->get_cfg('showVendorLink')) { ?>
	<tr>
	  <td colspan="3"><div style="text-align: center;"><?php echo $vendor_link ?><br /></div><br /></td>
	</tr>
	<?php } ?>
  </tbody>
</table>
<?php if( !empty( $navigation_childlist )) { ?>
	<?php echo $VM_LANG->_('PHPSHOP_MORE_CATEGORIES') ?><br />
	<?php echo $navigation_childlist ?><br style="clear:both"/>
<?php } ?>
