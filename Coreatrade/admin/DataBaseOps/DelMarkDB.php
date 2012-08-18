<?php
	require_once('DataBaseFunctions.php');
	$markId = $_POST["delete"];
	
	if(!is_numeric($markId) && $markId < 0){
		return false;
	}
	
	
	
	$dbrandfile = file_get_contents($pathTojs .'dbrand.js');
	$dbrand = json_decode($dbrandfile);
	
	if($markId >= count($dbrand)){
		return false;
	}
	echo "Brand #".$markId." with name ".$dbrand [$markId]->mark." will be removed<br />";
	$modelsForDelete = $dbrand[$markId]->model;
	rsort($modelsForDelete, SORT_NUMERIC);
	
	if(count($modelsForDelete) > 0){
		//deleting from ddata
		echo 'There is '.count($modelsForDelete).' cars to delete<br />';
		$inp = file_get_contents($pathTojs.'ddata.js');
		$carDB = json_decode($inp); 
		$dtypefile = file_get_contents($pathTojs.'dtype.js');
		$dtype = json_decode($dtypefile);
		foreach($modelsForDelete as $key => $value){
			FileCorrectionWFO($dtype, $value, $carDB[$value]->type);
			FileCorrectionWFO($dbrand, $value, $carDB[$value]->mark);
			echo $value.' car was removed'.'<br />';
			foreach($carDB[$value] -> img as $value){
				deleteImage('../'.$value);
			}
			unset($carDB[$value]);
		}
		//$carDB = array_values($carDB);
		$jsonCars = json_encode(array_values($carDB));
		file_put_contents($pathTojs.'ddata.js', $jsonCars );
		$jsonResult = json_encode($dtype);
		file_put_contents($pathTojs.'dtype.js', $jsonResult);

	}
	deleteImage($dbrand[$markId] -> img);
	unset($dbrand[$markId]);
	$jsonResult = json_encode(array_values($dbrand));
	file_put_contents($pathTojs.'dbrand.js', $jsonResult);
?>