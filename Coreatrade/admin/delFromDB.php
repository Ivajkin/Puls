<?php

	$index = $_POST["delete"];
	if(!is_numeric($index) && $index < 0){
		return false;
	}
	
	function deleteFromArray($index, $array, $arrIndex){
		if(count($array[$arrIndex]->model) > 0){
			foreach($array[$arrIndex]->model as $key => $value){
				if($value == $index){
					echo 'Current value: '.$value.'<br />';
					unset($array[$arrIndex]->model[$key]);
					
					break;
				}
			}$array[$arrIndex]->model = array_values($array[$arrIndex]->model);
			foreach($array as $key => $value){
				foreach($value->model as $mKey => $mValue){
					if($mValue > $index){
						echo 'Correction from '.$mValue.' to '.intval($mValue - 1);
						$array[$key]->model[$mKey] = intval($mValue) - 1;
						echo ' ('.$mValue.')<br />';
					}
				}
			}
		}
		return $array;
	}
	
	$inp = file_get_contents('../js/ddata.js');
	$tempArray = json_decode($inp); 
	
	if($index >= count($tempArray))
		return false;
	//echo $index;
	//echo count($tempArray);

	//deleting from dtype
	$dtypefile = file_get_contents('../js/dtype.js');
	$dtype = json_decode($dtypefile);            
	$carType = $tempArray[$index]->type;
	
	$dtype = deleteFromArray($index,$dtype,$carType);
	$jsonData = json_encode($dtype);
	file_put_contents('../js/dtype.js', $jsonData);
	
	//deletind from dbrand
	$dbrandfile = file_get_contents('../js/dbrand.js');
	$dbrand = json_decode($dbrandfile);
	$carBrand = $tempArray[$index]->mark;
	$dbrand = deleteFromArray($index,$dbrand,$carBrand);
	$jsonData = json_encode($dbrand);
	file_put_contents('../js/dbrand.js', $jsonData);
	
	//deleting from ddata.js
	unset($tempArray[$index]);
	$jsonData = json_encode(array_values($tempArray));
	file_put_contents('../js/ddata.js', $jsonData);
?>