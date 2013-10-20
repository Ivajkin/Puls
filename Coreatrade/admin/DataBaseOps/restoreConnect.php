<?
	require_once('DataBaseFunctions.php');
	$brandFile = file_get_contents($pathTojs.'dbrand.js');
	$brand = json_decode($brandFile );
	$carsFile = file_get_contents($pathTojs.'ddata.js');
	$cars = json_decode($carsFile);
	foreach($cars as $key => $value){
		if(!isMember($brand[ intval($value->mark)]->model, $key))
			array_push($brand[ intval($value->mark)]->model, $key);
	}
	$endFile = json_encode($brand);
	file_put_contents($pathTojs.'dbrand.js', $endFile );
?>