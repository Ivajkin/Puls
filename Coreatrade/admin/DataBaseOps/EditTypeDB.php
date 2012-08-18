<?php
	require_once('DataBaseFunctions.php');
	
	$type = trim(utf8($_POST['add']));
	$typeId = $_POST['typeId'];
	if(!$type || !is_numeric($typeId)){
		echo "Error<br />";
		return false;
	}
	
	$models = array();
	$data= array(
	    "type" => $type,
	    "model" => $models);
	
	$file = file_get_contents($pathTojs.'dtype.js');
	$array = json_decode($file);
	$array[$typeId] = $data;
	$jsonData = json_encode($array);
	file_put_contents($pathTojs.'dtype.js', $jsonData);
?>