<?php 
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
* This is the theme's function file.
* It allows you to declare additional functions and classes
* that may be used in your templates 
*
* @version $Id: theme.php 2286 2010-02-01 15:28:00Z soeren_nb $
* @package VirtueMart
* @subpackage themes
* @copyright Copyright (C) 2006-2010 soeren - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/
global $mainframe;

// include the stylesheet for this template

if( vmIsJoomla('1.0') && mosGetParam($_REQUEST,'option') != VM_COMPONENT_NAME) {
	// This can only be a call from a module or mambot
	// In Joomla 1.0 it is not possible to add a JS or CSS into the HEAD from a module or content mambot,
	// using addcustomheadtag, that's why we just print the tags here
	echo vmCommonHTML::scriptTag(VM_THEMEURL.'theme.js');
	echo vmCommonHTML::linkTag(VM_THEMEURL.'theme.css');
} else {
	$vm_mainframe->addStyleSheet( VM_THEMEURL.'theme.css' );
	$vm_mainframe->addScript( VM_THEMEURL.'theme.js' );
}
class vmTheme extends vmTemplate  {
	
	function vmTheme() {
		parent::vmTemplate();
		vmCommonHTML::loadMooTools();
	}
	
	function vmBuildFullImageLink( $product ) {
		global $VM_LANG;
		
		$product_image = '';
		
		$img_attributes= 'alt="'.$product['product_name'].'"';
		
		/* Wrap the Image into an URL when applicable */
		if ( @$product["product_url"] ) {
			$product_image = "<a href=\"". $product["product_url"]."\" title=\"".$product['product_name']."\" target=\"_blank\">";
			$product_image .= ps_product::image_tag($product['product_thumb_image'], $img_attributes, 0);
			$product_image .= "</a>";
		}
		/* Show the Thumbnail with a Link to the full IMAGE */
		else {
			if( empty($product['product_full_image'] ) ) {
				$product_image = "<img src=\"".VM_THEMEURL.'images/'.NO_IMAGE."\" alt=\"".$product['product_name']."\" border=\"0\" />";
			}
			else {
				// file_exists doesn't work on remote files,
				// so returns false on remote files
				// This should fix the "Long Page generation bug"
				if( file_exists( IMAGEPATH.'product/'.$product['product_full_image'] )) {
		
					/* Get image width and height */
					if( $image_info = @getimagesize(IMAGEPATH.'product/'.$product['product_full_image'] ) ) {
						$width = $image_info[0] + 20;
						$height = $image_info[1] + 20;
					}
				}
				else {
					$width = 640;
					$height= 480;
				}
				if( stristr( $product['product_full_image'], "http" ) ) {
					$imageurl = $product['product_full_image'];
				}
				else {
					$imageurl = IMAGEURL.'product/'.rawurlencode( $VM_LANG->convert($product['product_full_image']));
				}
				/* Build the "See Bigger Image" Link */
				if( @$_REQUEST['output'] != "pdf" && $this->get_cfg('useLightBoxImages', 1 ) ) {
					$link = $imageurl;
					$text = ps_product::image_tag($product['product_thumb_image'], $img_attributes, 0)."<br/>".$VM_LANG->_('PHPSHOP_FLYPAGE_ENLARGE_IMAGE');

					$product_image = vmCommonHTML::getLightboxImageLink( $link, $text, $product['product_name'], 'product'.$product['product_id'] );
				}
				elseif( @$_REQUEST['output'] != "pdf" ) {
					$link = $imageurl;
					$text = ps_product::image_tag($product['product_thumb_image'], $img_attributes, 0)."<br/>".$VM_LANG->_('PHPSHOP_FLYPAGE_ENLARGE_IMAGE');
					// vmPopupLink can be found in: htmlTools.class.php
					$product_image = vmPopupLink( $link, $text, $width, $height );
				}
				else {
					$product_image = "<a href=\"$imageurl\" target=\"_blank\">"
									. ps_product::image_tag($product['product_thumb_image'], $img_attributes, 0)
									. "</a>";
				}
			}
		}
		return $product_image;
	}
	
	/**
	 * Builds a list of all additional images
	 *
	 * @param int $product_id
	 * @param array $images
	 * @return string
	 */
	function vmlistAdditionalImages( $product_id, $images, $title='', $limit=1000 ) {
		global $sess;
		$html = '';
		$i = 0;
		foreach( $images as $image ) { 
			$thumbtag = ps_product::image_tag( $image->file_name, 'class="browseProductImage"', 1, 'product', $image->file_image_thumb_width, $image->file_image_thumb_height );
			$fulladdress = $sess->url( 'index2.php?page=shop.view_images&amp;image_id='.$image->file_id.'&amp;product_id='.$product_id.'&amp;pop=1' );
			
			if( $this->get_cfg('useLightBoxImages', 1 )) {
				$html .= vmCommonHTML::getLightboxImageLink( $image->file_url, $thumbtag, $title ? $title : stripslashes(htmlentities($image->file_title,ENT_QUOTES)), 'product'.$product_id );
			}
			else {
				$html .= vmPopupLink( $fulladdress, $thumbtag, 640, 550 );
			}
			$html .= ' ';
			if( ++$i > $limit ) break;
		}
		return $html;
	}
	/**
	 * Builds the "more images" link
	 *
	 * @param array $images
	 */
	function vmMoreImagesLink( $images ) {
		global $mosConfig_live_site, $VM_LANG, $sess;
		/* Build the JavaScript Link */
		$url = $sess->url( "index2.php?page=shop.view_images&amp;flypage=".@$_REQUEST['flypage']."&amp;product_id=".@$_REQUEST['product_id']."&amp;category_id=".@$_REQUEST['category_id']."&amp;pop=1" );
		$text = $VM_LANG->_('PHPSHOP_MORE_IMAGES').'('.count($images).')';
		$image = vmCommonHTML::imageTag( VM_THEMEURL.'images/more_images.png', $text, '', '16', '16' );
		
		return vmPopupLink( $url, $image.'<br />'.$text, 640, 550, '_blank', '', 'screenX=100,screenY=100' );
	}

	
	// Your code here please...

}
?>