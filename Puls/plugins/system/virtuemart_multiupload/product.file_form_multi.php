<?php

/*
 *  This file is part of the Joomla Extension VirtueMart_Multiupload.
 *
 *  VirtueMart_Multiupload is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  VirtueMart_Multiupload is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with VirtueMart_Multiupload.  If not, see <http://www.gnu.org/licenses/>.
 *
 *  @copyright Copyright (C) 2010- Markus Harmsen
 *  @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */
 
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to ' . basename(__FILE__) . ' is not allowed.' ); 

require_once( CLASSPATH . "ps_product_files.php" );

$product_id = JRequest::getInt('product_id');
$option     = empty($option) ? JRequest::getString('option', 'com_virtuemart') : $option;

$q = 'SELECT product_id, product_name, product_full_image as file_name, product_thumb_image as file_name2 FROM #__{vm}_product WHERE product_id=' . $product_id; 
$db->query($q);  
$db->next_record();

$product_name   = '<a href="' . $_SERVER['PHP_SELF'] . '?option=' . $option . '&amp;product_id=' . $product_id . '&amp;page=product.product_form">' . $db->f('product_name') . '</a>';
$title          = '<img src="' . JURI::root(true) . '/administrator/images/backup.png" width="48" height="48" align="center" alt="Product List" border="0" />Multiupload' . ' for product "' . $product_name . '"';

$document = &JFactory::getDocument();
$document->addScript    (JURI::root(true) . '/plugins/system/virtuemart_multiupload/swfupload.js'          );
$document->addScript    (JURI::root(true) . '/plugins/system/virtuemart_multiupload/swfupload.queue.js'    );
$document->addScript    (JURI::root(true) . '/plugins/system/virtuemart_multiupload/fileprogress.js'       );
$document->addScript    (JURI::root(true) . '/plugins/system/virtuemart_multiupload/handlers.js'           );
$document->addStyleSheet(JURI::root(true) . '/plugins/system/virtuemart_multiupload/default.css'           );

$vmtoken    = vmSpoofValue($GLOBALS['sess']->getSessionId());
$formObj    = &new formFactory($title);

$session    = &JFactory::getSession();
$jsessname  = $session->getName();
$jsessid    = $session->getId();

unset( $db->record );
?>

<div id="content">
    <form id="virtuemart_multiupload_form" action="." method="post" enctype="multipart/form-data">
        <p>Images will be additional Images</p>
        <div id="divStatus">0 Files Uploaded</div><br />
        
        <div class="fieldset flash" id="fsUploadProgress">
          <span class="legend">Upload Queue</span>
        </div><br>

        <div class="fieldset flash">
            <span class="legend">Options</span><br>
            
            <label for="file_resize_fullimage"><?php echo $VM_LANG->_('VM_FILES_FORM_RESIZE_IMAGE'); ?></label>
            <input type="checkbox" class="inputbox" id="file_resize_fullimage" name="file_resize_fullimage" checked="checked" value="1" onclick="setResizeParam()" />
            
            <div id="fullsizes" style="padding-top: 10px;">&nbsp;&nbsp;&nbsp;
                <?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_HEIGHT');?>: <input id="fullimage_height" type="text" name="fullimage_height" value="500" class="inputbox" onchange="setResizeParam()" />&nbsp;&nbsp;&nbsp;
                <?php echo $VM_LANG->_('PHPSHOP_PRODUCT_FORM_WIDTH');?>: <input id="fullimage_width" type="text" name="fullimage_width" value="500" class="inputbox" onchange="setResizeParam()" />
            </div>
        </div>
        
        <div>
          <span id="spanButtonPlaceHolder"></span>
          <input id="btnCancel" type="button" value="Cancel All Uploads" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 29px;" />
        </div>
    </form>
</div>

<script type="text/javascript">
//<![CDATA[
  var swfu;
  window.onload = function () {
    swfu = new SWFUpload({
      upload_url        : '<?php echo JURI::root(); ?>administrator/index3.php',
      flash_url         : '<?php echo JURI::root(); ?>plugins/system/virtuemart_multiupload/swfupload.swf',
      file_post_name    : 'file_upload',
      
      post_params: {
        '<?php echo $jsessname; ?>' : '<?php echo $jsessid; ?>',
        'product_id'                : '<?php echo $product_id; ?>',
        'file_type'                 : 'image',
        'upload_dir'                : 'IMAGEPATH',
        'file_resize_fullimage'     : '1',
        'fullimage_height'          : '500',
        'fullimage_width'           : '500',
        'file_create_thumbnail'     : '1',
        'thumbimage_height'         : '<?php echo PSHOP_IMG_HEIGHT; ?>',
        'thumbimage_width'          : '<?php echo PSHOP_IMG_WIDTH; ?>',
        'file_published'            : '1',
        'vmtoken'                   : '<?php echo $vmtoken; ?>',
        'func'                      : 'uploadProductFile',
        'page'                      : 'product.file_list',
        'option'                    : 'com_virtuemart',
        'ajax_request'              : '1',
        'only_page'                 : '1',
        'pshop_admin'               : 'admin',
        'format'                    : 'raw'
      },
    
      // File Upload Settings
      file_size_limit               : '2 MB',
      file_types                    : '*.jpg',
      file_types_description        : 'JPG Images',
      file_upload_limit             : '0',
    
      // Button settings
      button_image_url              : '<?php echo JURI::root(); ?>includes/js/ThemeOffice/add_section.png',
      button_width                  : 180,
      button_height                 : 18,
      button_placeholder_id         : 'spanButtonPlaceHolder',
      button_text                   : '<span class="button">Select Images <span class="buttonSmall">(2 MB Max)</span></span>',
      button_text_style             : '.button { font-family: Verdana, Arial, sans-serif; font-size: 12pt; } .buttonSmall { font-size: 10pt; }',
      button_text_top_padding       : 0,
      button_text_left_padding      : 18,
      button_window_mode            : SWFUpload.WINDOW_MODE.TRANSPARENT,
      button_cursor                 : SWFUpload.CURSOR.HAND,
    
      // The event handler functions are defined in handlers.js
      file_queued_handler           : fileQueued,
      file_queue_error_handler      : fileQueueError,
      file_dialog_complete_handler  : fileDialogComplete,
      upload_start_handler          : uploadStart,
      upload_progress_handler       : uploadProgress,
      upload_error_handler          : uploadError,
      upload_success_handler        : uploadSuccess,
      upload_complete_handler       : uploadComplete,
      queue_complete_handler        : queueComplete,
    
      custom_settings : {
        progressTarget              : 'fsUploadProgress',
        cancelButtonId              : 'btnCancel'
      },
    
      debug: false
    });
  };
  
  function setResizeParam() {
    var useResizeElement    = document.getElementById('file_resize_fullimage');
    var widthElement        = document.getElementById('fullimage_width');
    var heightElement       = document.getElementById('fullimage_height');
    
    swfu.addPostParam('file_resize_fullimage',  useResizeElement.checked ? '1' : '0');
    swfu.addPostParam('fullimage_width',        widthElement.value);
    swfu.addPostParam('fullimage_height',       heightElement.value);
  }
//]]>
</script>
