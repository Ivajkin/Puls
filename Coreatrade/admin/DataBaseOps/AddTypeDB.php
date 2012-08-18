<?php
	require_once('DataBaseFunctions.php');
	
	$type = trim(utf8($_POST['add']));
	if(!$type){
		echo "Error";
		return false;
	}
	
	$models = array();
	$data= array(
	    "type" => $type,
	    "model" => $models);
	    
	addToDatabase($pathTojs.'dtype.js', $type, $data);
	return true;
?>