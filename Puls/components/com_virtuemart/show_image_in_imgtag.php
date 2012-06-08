<?php
/**
* Image Resizer & img Tag "Filler"
*
* @author Andreas Martens <heyn@plautdietsch.de>
* @author Patrick Teague <webdude@veslach.com>
*
* @version $Id: show_image_in_imgtag.php 1646 2009-02-16 19:40:16Z tkahl $
* @package VirtueMart
* @subpackage core
* @copyright Copyright (C) 2004-2008 soeren - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/
define('_VALID_MOS', 1);

// Get the Joomla! configuration file
$config_file = '../../configuration.php';
include_once( $config_file );

if( isset($_REQUEST['mosConfig_absolute_path'])) die();

if( !isset( $mosConfig_absolute_path ) ) {
// We are in J! 1.5
	define( '_JEXEC', 1 );
	$mosConfig_absolute_path = dirname( $config_file );
}

include_once("../../administrator/components/com_virtuemart/virtuemart.cfg.php");

$resize_image = true;
// check if dynamic thumbnails are disabled or the GD Library is not available
if( PSHOP_IMG_RESIZE_ENABLE == '') {
	$resize_image = false;
}
elseif (!extension_loaded('gd') && !dl('gd.so')) {
	$resize_image = false;
}
 
include( CLASSPATH . "ps_main.php");
	
if( $resize_image ) {
	//	Image2Thumbnail will resize your images
	include( CLASSPATH . "class.img2thumb.php");
}

$basefilename = @basename(urldecode($_REQUEST['filename']));
$filenames[] = IMAGEPATH."product/".$basefilename;
$resized_filenames[] = IMAGEPATH."product/resized/".$basefilename;
$filenames[] = IMAGEPATH."category/".$basefilename;
$resized_filenames[] = IMAGEPATH."category/resized/".$basefilename;
$newxsize = (int)@$_REQUEST['newxsize'] == 0 ? PSHOP_IMG_WIDTH : (int)@$_REQUEST['newxsize'];
$newysize = (int)@$_REQUEST['newysize'] == 0 ? PSHOP_IMG_WIDTH : (int)@$_REQUEST['newysize'];

// Don't allow sizes beyond 600 pixels
$newxsize = min( $newxsize, 600 );
$newysize = min( $newysize, 600 );

//Don't allow sizes under 40 pixels
$newxsize = max( $newxsize, 40 );
$newysize = max( $newysize, 40 );

if( $newxsize < $newysize ) {
	// Don't let $newxsize be smaller than 55% of $newysize
	$newxsize = max( $newxsize, 0.55 * $newysize );
}
elseif( $newysize < $newxsize ) {
	// Don't let $newysize be smaller than 55% of $newxsize
	$newysize = max( $newysize, 0.55 * $newxsize );
}
$maxsize = false;
$bgred = 255;
$bggreen = 255;
$bgblue = 255;

/*
if( !isset($fileout) )
	$fileout="";
if( !isset($maxsize) )
	$maxsize=0;
*/

/* Minimum security */
$file_exists = false;
$i = 0;
foreach ( $filenames as $file ) {
	if( file_exists( $file ) ) {
		$file_exists = true;
		$filename = $file;
		break;
	} elseif( file_exists($resized_filenames[$i])) {
		$file_exists = true;
		$filename = $resized_filenames[$i];
		break;		
	}
	++$i;
}
$file_exists or die('File does not exist');

$filename2 = $resized_filenames[$i];

$fileinfo = pathinfo( $filename );
$file = str_replace(".".$fileinfo['extension'], "", $fileinfo['basename']);
// In class.img2thumb in the function NewImgShow() the extension .jpg will be added to .gif if imagegif does not exist.

// If the image is a gif, and imagegif() returns false then make the extension ".gif.jpg"

if( $fileinfo['extension'] == "gif") {
  if( function_exists("imagegif") ) {
    $ext = ".".$fileinfo['extension'];
    $noimgif="";
  }
  else {
    $ext = ".jpg";
    $noimgif = ".".$fileinfo['extension'];
  }
} 
else {
  $ext =  ".".$fileinfo['extension'];
  $noimgif="";
}

if( $resize_image ) {
	if( file_exists($filename2)) { 
		$fileout = $filename2;
	} else {
		$fileout = dirname( $filename2 ) .'/'.$file."_".$newxsize."x".$newysize.$noimgif.$ext;
	}
} else {
	$fileout = $filename;
}
// Tell the user agent to cache this script/stylesheet for an hour
$age = 3600;
header( 'Expires: '.gmdate( 'D, d M Y H:i:s', time()+ $age ) . ' GMT' );
header( 'Cache-Control: max-age='.$age.', must-revalidate' );

if( file_exists( $fileout ) ) {
	// Try to delete the resized image if the original file is newer
	if (filemtime($fileout) < filemtime($filename)) @unlink($fileout);
}

if( file_exists( $fileout ) ) {
  /* We already have a resized image
  * So send the file to the browser */
  	switch(strtolower($ext))
		{
			case ".gif":
				header ("Content-type: image/gif");
				readfile($fileout);
				break;
			case ".jpg":
				header ("Content-type: image/jpeg");
				readfile($fileout);
				break;
			case ".png":
				header ("Content-type: image/png");
				readfile($fileout);
				break;
		}
}
else {
 	/* We need to resize the image and Save the new one (all done in the constructor) */
  	$neu = new Img2Thumb($filename,$newxsize,$newysize,$fileout,$maxsize,$bgred,$bggreen,$bgblue);
  	
  	/* Send the file to the browser */
  	switch($ext)
		{
			case ".gif":
				header ("Content-type: image/gif");
				readfile($fileout);
				break;
			case ".jpg":
				header ("Content-type: image/jpeg");
				readfile($fileout);
				break;
			case ".png":
				header ("Content-type: image/png");
				readfile($fileout);
				break;
		}
}
?>