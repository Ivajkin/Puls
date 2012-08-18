<?php	
	require_once('DataBaseFunctions.php');
	$typeId = $_POST["delete"];
	
	if(!is_numeric($typeId) && $typeId < 0){
		echo 'Error<br />';
		return false;
	}
	
	
	$dtypefile= file_get_contents($pathTojs.'dtype.js');
	$dtype = json_decode($dtypefile);
	
	if($typeId >= count($dtype)){
		echo 'Error<br />';
		return false;
	}
	echo "Type #".$typeId." with name ".utf8($dtype[$typeId]->mark)." will be removed<br />";
	$modelsForDelete = $dtype[$typeId]->model;
	rsort($modelsForDelete, SORT_NUMERIC);
	
	if(count($modelsForDelete) > 0){
		//deleting from ddata
		echo 'There is '.count($modelsForDelete).' cars to delete<br />';
		$inp = file_get_contents($pathTojs.'ddata.js');
		$carDB = json_decode($inp); 
		$dbrandfile = file_get_contents($pathTojs.'dbrand.js');
		$dbrand = json_decode($dbrandfile);
		foreach($modelsForDelete as $key => $value){
			FileCorrectionWFO($dbrand, $value, $carDB[$value]->mark);
			FileCorrectionWFO($dtype, $value, $carDB[$value]->type);
			echo $value.' car was removed'.'<br />';
			foreach($carDB[$value] -> img as $value){
				unlink($value);
			}
			unset($carDB[$value]);
		}
		//$carDB = array_values($carDB);
		$jsonCars = json_encode(array_values($carDB));
		file_put_contents($pathTojs.'ddata.js', $jsonCars );
		$jsonResult = json_encode($dbrand);
		file_put_contents($pathTojs.'dbrand.js', $jsonResult);

	}
	unset($dtype[$typeId]);
	$jsonResult = json_encode(array_values($dtype));
	file_put_contents($pathTojs.'dtype.js', $jsonResult);
?>