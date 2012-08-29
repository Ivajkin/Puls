<?php
	require_once('DataBaseFunctions.php');
	$targetFolder = '/upload/img'; // Relative to the root
	
	$mark = trim(utf8($_POST['add']));
	$markId = $_POST['markId'];
	if(!$mark || !is_numeric($markId)){
		echo 'Error<br />';
		return false;
	}
	$img = trim(utf8($_POST['srcImage']));
	//if(!strcmp(mb_substr($img, 0, 7), "http://"))
	
	/*if(!$img || strcmp(mb_substr($img, 0, 7), "http://")){
		if (file_exists('../img_temp.txt')) {
		   $imgstr = utf8(file_get_contents('../img_temp.txt'));
		   $img = '../'.rtrim($imgstr);
		  // $img = explode( "\r\n" ,$imgstr);
		   unlink('../img_temp.txt');
		}
		else $img = 0;
	}*/
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
	echo $img.'<br />';
	$models = array();
	$data= array(
	    "mark" => $mark,  
	    "img" => $img,
	    "model" => $models);
	
	$file = file_get_contents($pathTojs.'dbrand.js');
	$array = json_decode($file);
	if($img === 0){
		echo 'Old Image<br />';
		$data["img"] = $array[$markId] -> img;
	}
	else
		deleteImage($array[$markId] -> img);
	$array[$markId] = $data;
	$jsonData = json_encode($array);
	file_put_contents($pathTojs.'dbrand.js', $jsonData);
?>