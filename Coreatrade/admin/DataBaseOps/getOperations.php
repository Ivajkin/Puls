<?php
	//require_once('DataBaseFunctions.php');
	$pathTojs = '../../js/';
	function getFromDB($filename, $fisrt, $length){
		$dbfile = file_get_contents($filename);
		$db = json_decode($dbfile); 
		$count = count($db);
		if($first <= -1)
			$first = $count-1;
		$output = array_slice($db, $fisrt, $length);
		return $output;
	}

	$first = $_GET['first'];
	$end = $_GET['end'];
	$length = $_GET['length'];
	if(isset($end) && !isset($length) && $end >= 0)
		$length = $end - $first + 1;
	$operation = $_GET['operation'];
	if(!isset($first))
		$first = 0;	
	if(isset($operation))
		$operation = 'car';
	$operation = $_POST['operation'];
	if(strcmp($operation,'car'))
		$result = getFromDB($pathTojs.'ddata.js',$first,$length);
	else if(strcmp($operation,'type'))
		$result = getFromDB($pathTojs.'dtype.js',$first,$length);
	else if(strcmp($operation,'brand'))
		$result = getFromDB($pathTojs.'dbrand.js',$first,$length);
	echo json_encode($result);
?>