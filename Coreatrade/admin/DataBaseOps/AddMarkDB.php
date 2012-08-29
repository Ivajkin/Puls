<?php
	require_once('DataBaseFunctions.php');
	$targetFolder = '/upload/img'; // Relative to the root
	
	$mark = trim(utf8($_POST['add']));
	if(!$mark){
		return false;
	}
	$img = trim(utf8($_POST['srcImage']));
	
	if(strlen($img) < 4)
		$img = 0;
	else if(strcmp(mb_substr($img, 0, 7), "http://")){
		if (!file_exists('../'.$img)) {
		   //$imgstr = utf8(file_get_contents('../img_temp.txt'));
		   //$img = '../'.rtrim($imgstr);
		   //$img= '../'.explode( "\r\n" ,$imgstr);
		   //unlink('../img_temp.txt');
		   $img = 0;
		}
	}
	$models = array();
	$data= array(
	    "mark" => $mark,  
	    "img" => $img,
	    "model" => $models);
	    
	addToDatabase($pathTojs.'dbrand.js', $mark, $data);
?>