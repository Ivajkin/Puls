<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/
require_once('SimpleImage.php'); 

if (!empty($_FILES)) {
        
	$tempFile = $_FILES['Filedata']['tmp_name'];
        $filename= strtolower ( $_FILES['Filedata']['name'] );
        $filetype= strtolower ( $_FILES['Filedata']['type'] );

        //check if contain php and kill it 
         $pos = strpos($filename,'php');
          if(!($pos === false)) {
            die('error');
          }
 
          $imageinfo = getimagesize($tempFile);
          if($imageinfo['mime'] != 'image/gif' && $imageinfo['mime'] != 'image/jpeg'&& $imageinfo['mime']      != 'image/jpg'&& $imageinfo['mime'] != 'image/png') {
              die('error 2');
           }

           //check double file type (image with comment)
           if(substr_count($filetype, '/')>1){
               die('error 3');
           }

	// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
	$fileParts = pathinfo($filename);
        $ext = strtolower($fileParts['extension']);
        //$file_ext = strrchr($filename, '.');


        // Define a destination
        $exportPath = 'images/catalog/' . date("Y-m-d"); // Relative to the root
        $targetPath = '../' . $exportPath ; // Relative to the root
	//$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
	//$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];
	
	if (in_array($ext, $fileTypes)) {
                @mkdir( $targetPath, 0755);  
                //Rename
                $exportFile = $exportPath . '/' . md5($fileParts['basename']) . '.' . $ext;
                $targetFile = '../' . $exportFile;
        	//$targetFile = $targetPath . '/' . $_FILES['Filedata']['name'];

               //Image resize
               $image = new SimpleImage();
               $image->load($tempFile);
               $image->resizeToWidth(1024);
               $image->save($targetFile);

               //Add image link to temporary file
               file_put_contents('img_temp.txt', $exportFile . "\r\n", FILE_APPEND);                 

               echo $exportFile;
	} else {
		echo 'Не правильный формат изображения. Загрузите .jpeg, .jpg, .png или .gif изображение';
	}
}
?>
