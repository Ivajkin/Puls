<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/
require_once('SimpleImage.php'); 


// Define a destination
$exportPath = 'images/catalog/' . date("Y-m-d-G"); // Relative to the root
$targetPath = '../' . $exportPath ; // Relative to the root

if (!empty($_FILES)) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	//$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
	//$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];

	// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
        $ext = strtolower($fileParts['extension']);
        //$file_ext = strrchr($filename, '.');
	
	if (in_array($ext, $fileTypes)) {
                mkdir( $targetPath, 0755);  
                //Rename
                $exportFile = $exportPath . '/' . md5($fileParts['basename']) . '.' . $ext;
                $targetFile = '../' . $exportFile;
        	//$targetFile = $targetPath . '/' . $_FILES['Filedata']['name'];

               //Image resize
               $image = new SimpleImage();
               $image->load($tempFile);
               $image->resize(800,600);
               $image->save($targetFile);

               //Add image link to temporary file
               file_put_contents('img_temp.txt', $exportFile . "\r\n", FILE_APPEND);                 

               echo '1';
	} else {
		echo 'Не правильный формат изображения. Загрузите .jpeg, .jpg, .png или .gif изображение';
	}
}
?>
