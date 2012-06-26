<?php 
/**
 * IceSlideshow Module for Joomla 1.7 By IceTheme
 * 
 * 
 * @copyright	Copyright (C) 2008 - 2012 IceTheme.com. All rights reserved.
 * @license		GNU General Public License version 2
 * 
 * @Website 	http://www.icetheme.com/Joomla-Extensions/IceSlideshow.html
 * @Support 	http://www.icetheme.com/Forums/IceSlideshow/
 *
 */
 ?>
 
 <style type="text/css">

.ice-slideshow-default,
.ice-slideshow-default .ice-main-wapper {
	 max-width:<?php echo (int)$params->get('imagemain_width',650);?>px}
 
 </style> 
 
<div id="iceslideshow<?php echo $module->id; ?>" class="ice-slideshow-default" style="height:<?php echo (int)$params->get('imagemain_height',300);?>px;">
	
	<?php if( $params->get('preload',1) ): ?>
        <div class="preload"></div>
    <?php endif; ?>
    
	<?php if( $class && $class != 'ice-bottom' ) : ?>
		<?php require( dirname(__FILE__) . DS . '_navigator.php' );?>
	<?php endif; ?>
    
    
	<!-- MAIN CONTENT --> 
	<div class="ice-main-wapper" style="height:<?php echo (int)$params->get('imagemain_height',300);?>px; <?php echo $maincss;?>">
    
		<div class="ice-proccessbar"></div>
		<?php $i = 0; foreach( $list as $row ): ?>
			<div class="ice-main-item" <?php echo (!$i) ? 'style="display:block;"' : ''?>>
				<?php echo modIceSlideshowHelper::renderItem( $row, $params  );?>
			</div> 
		<?php $i++; endforeach; ?>
        
        
		<?php if( $params->get('display_button', '') ): ?>
        <div class="ice-buttons-control">
            <div class="ice-previous"><?php echo JText::_('Previous');?></div>
            <div class="ice-next"><?php echo JText::_('Next');?></div>
        </div>    
		<?php endif; ?>
        
        
	</div>
	<!-- END MAIN CONTENT -->
    
    
    
 </div>
 <script type="text/javascript">
 	window.addEvent('domready', function() {
			$$('.hasTip').each(function(el) {
				var title = el.get('title');
				if (title) {
					var parts = title.split('::', 2);
					el.store('tip:title', parts[0]);
					el.store('tip:text', parts[1]);
				}
			});
		var JTooltips = new Tips($$('.hasTip'), { fixed: false});
	});
 </script>