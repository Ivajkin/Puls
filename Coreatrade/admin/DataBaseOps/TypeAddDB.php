<?php
	require_once('DataBaseFunctions.php');
	$mark = $_POST['type'];

	
	$models = array();
	$data= array(
	    "mark" => $type,
	    "model" => $models);
	    
	add($pathTojs.'/dbrand.js', $type, $data)	
?>