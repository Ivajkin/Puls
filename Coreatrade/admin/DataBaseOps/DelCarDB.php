<?php
	require_once('DataBaseFunctions.php');
	$carId = $_POST["delete"];
	if(!is_numeric($carId) && $carId < 0){
		return false;
	}
	
	
	$inp = file_get_contents($pathTojs.'ddata.js');
	$carArray = json_decode($inp); 
	
	if($index >= count($carArray))
		return false;

	FileCorrection($pathTojs.'dtype.js', $carId, $carArray[$carId]->type);
	FileCorrection($pathTojs.'dbrand.js', $carId, $carArray[$carId]->mark);
	
	//deleting from ddata.js
	if(!strcmp($img,'0')){
		echo 'Old Image<br />';
		$data["img"] = $carArray[$carId] -> img;
	}
	else{
	foreach($carArray[$carId]->img as $value){
			deleteImage('../'.$value);
		}
	}
	unset($carArray[$carId]);
	
	$jsonData = json_encode(array_values($carArray));
	file_put_contents($pathTojs.'ddata.js', $jsonData);
?>