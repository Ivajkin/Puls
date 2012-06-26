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
    <?php echo $row->mainImage; ?>

   
        
			<?php if($params->get("show_captions", 1)){ ?>
            
            	
                 <div class="ice-description">
				
                     <h3 class="ice-title">
                     
                     <?php if($params->get('show_readmore','0')) { ?>
                         <a <?php echo $target;?>  href="<?php echo $row->link;?>" title="<?php echo $row->title;?>">
                      <?php } ?>
    
                        <?php echo $row->title; ?>
                    
                    <?php if($params->get('show_readmore','0')) { ?>   
                         </a>
                     <?php } ?>
                     
                     </h3>
                 
                 
					<?php echo $row->description;?>
                
                
                </div>

			<?php } ?>
            
	