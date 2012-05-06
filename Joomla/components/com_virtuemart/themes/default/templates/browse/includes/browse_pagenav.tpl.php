<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
mm_showMyFileName(__FILE__); ?>
<?php if(!@is_object( $pagenav)) return;  ?>
<!-- BEGIN PAGE NAVIGATION -->
<div align="center">
	<?php $pagenav->writePagesLinks( $search_string ); ?>
	<?php 
	if( $show_limitbox ) { ?>
		<br/><br/>
		<form action="<?php echo $search_string ?>" method="post">
			<?php echo $VM_LANG->_('PN_DISPLAY_NR') ?>&nbsp;&nbsp;
			<?php $pagenav->writeLimitBox( $search_string ); ?>
			
			<noscript><input class="button" type="submit" value="<?php echo $VM_LANG->_('PHPSHOP_SUBMIT') ?>" /></noscript>
		
		</form>
	<?php
	}
	$pagenav->writePagesCounter();
	?>
</div>
<!-- END PAGE NAVIGATION -->