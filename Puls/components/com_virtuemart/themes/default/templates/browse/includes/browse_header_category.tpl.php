<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
mm_showMyFileName(__FILE__);?>

<?php readfile(JPATH_COMPONENT.DS.'prod.js');?>
<!--<script type="text/javascript" src="components/com_virtuemart/prod.js"></script>-->

<script type="text/javascript" > 
   var tmp= "<?php echo $tmp[1] ?>"; //$images
   window.addEvent('domready', function() {
      //alert("<?php phpversion() ?>");
   });
</script>


<h3><?php echo $browsepage_lbl; ?> 
	<?php 
	if( $this->get_cfg( 'showFeedIcon', 1 ) && (VM_FEED_ENABLED == 1) ) { ?>
	<a href="index.php?option=<?php echo VM_COMPONENT_NAME ?>&amp;page=shop.feed&amp;category_id=<?php echo $category_id ?>" title="<?php echo $VM_LANG->_('VM_FEED_SUBSCRIBE_TOCATEGORY_TITLE') ?>">
	<img src="<?php echo VM_THEMEURL ?>images/feed-icon-14x14.png" align="middle" alt="feed" border="0"/></a>
	<?php 
	} ?>
</h3>

<div style="text-align:left;">
	<?php echo $navigation_childlist; ?>
</div>
<?php if( trim(str_replace( "<br />", "" , $desc)) != "" ) { ?>

		<div style="width:100%;float:left;">
			<?php echo $desc; ?>
		</div>
		<br class="clr" /><br />
		<?php
     }
?>

<!-- require_once(JPATH_COMPONENT.DS.'themes'.DS.'default'.DS.'templates'.DS.'product_details'.DS.'flypage.tpl.php');-->