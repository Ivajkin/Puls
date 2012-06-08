<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); ?>

<div class="vmCartDetails<?php echo $cls_suffix; ?>">

<?php 
if(USE_AS_CATALOGUE != '1' && ($advanced_attribute != "" || $custom_attribute !="")) { ?>
    <div class="vmCartChild<?php echo $cls_suffix ?> vmRowTwo<?php echo $cls_suffix ?>">
  	<?php 
}
?>
<?php echo $drop_down ?> 
<?php
if(USE_AS_CATALOGUE != '1' && ($advanced_attribute != "" || $custom_attribute != "")) { ?>
  	<div class="vmCartAttributes<?php echo $cls_suffix ?>">
  	<?php   
  	if($advanced_attribute) {
		echo $advanced_attribute;
  	}
	if($custom_attribute) {
		echo $custom_attribute;
	}
  	?>
	</div>
	<?php 
} ?>
<?php 
if(USE_AS_CATALOGUE != '1' && ($advanced_attribute != "" || $custom_attribute !="")) { ?>
	</div>
	<?php 
}?> 
</div>
