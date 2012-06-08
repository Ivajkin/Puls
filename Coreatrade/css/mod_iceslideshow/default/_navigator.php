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
 
 
// no direct access
defined('_JEXEC') or die;

?> 

<div class="ice-navigator-wrapper clearfix">

    <!-- NAVIGATOR -->
      <div class="ice-navigator-outer">
            <ul class="ice-navigator">
            <?php foreach( $list as $row ):?>
                <li class="hasTip" title="<?php echo substr($row->title, 0, (int) $params->get('title_max_chars',100)) ;?>"><div><span class="ice-title"><?php echo substr($row->title, 0, (int) $params->get('title_max_chars',100)) ;?></span>
                 </div></li>
             <?php endforeach; ?> 		
            </ul>
      </div>
 	<!-- END NAVIGATOR //-->
    
</div>