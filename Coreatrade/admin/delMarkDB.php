<?php

	$markId= $_POST["delete"];
	if(!is_numeric($markId) && $markId< 0){
		return false;
	}
	
	function isMember($array, $var){
		foreach($array as $value){
			if($var == $value){
				return true;
			}	
		}
		return false;
	}
	
	$dbrandfile = file_get_contents('../js/dbrand.js');
	$dbrand = json_decode($dbrandfile);
	
	if($markId >= count($dbrand)){
		return false;
	}
	$modelsForDelete = $dbrand[$markId]->model;
	
	if(count($modelsForDelete) > 0){
		//deleting from ddata
		$inp = file_get_contents('../js/ddata.js');
		$carDB = json_decode($inp); 
		
		foreach($carDB as $key => $value){
			if(isMember($modelsForDelete, $key)){
				echo $key.' car was removed'.'<br />';
				unset($carDB[$key]);
			}
		}
		
		//deleting from dtype
		$dtypefile = file_get_contents('../js/dtype.js');
		$dtype = json_decode($dtypefile);  
		foreach($dtype as $key => $value){
			foreach($value->model as $mKey => $mValue){
				if(isMember($modelsForDelete, $mValue)){
					echo $mValue.' car was deleted from Types on '.$mKey.' from '.$value->type.'('.$key.')<br />';
					unset($dtype[$key]->model[$mKey]);
				}
			}
			$dtype[$key]->model = array_values($dtype[$key]->model);
		}
	
		unset($dbrand[$markId]);
	} 
?>