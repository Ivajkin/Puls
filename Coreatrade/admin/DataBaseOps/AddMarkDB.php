<?php
	require_once('DataBaseFunctions.php');
	$targetFolder = '/upload/img'; // Relative to the root
	
	$mark = trim(utf8($_POST['add']));
	if(!$mark){
		return false;
	}
	$img = trim(utf8($_POST['srcImage']));
	
	if(!$img || strcmp(mb_substr($img, 0, 7), "http://")){
		if (file_exists('../img_temp.txt')) {
		   $imgstr = utf8(file_get_contents('../img_temp.txt'));
		   $img = '../'.rtrim($imgstr);
		   //$img= '../'.explode( "\r\n" ,$imgstr);
		   unlink('../img_temp.txt');
		}
		else $img = 0;
	}
	echo $img;
	$models = array();
	$data= array(
	    "mark" => $mark,  
	    "img" => $img,
	    "model" => $models);
	    
	addToDatabase($pathTojs.'dbrand.js', $mark, $data);
?>