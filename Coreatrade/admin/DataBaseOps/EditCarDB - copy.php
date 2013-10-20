<?php

$carId = $_POST['edit'];
if(!is_numeric($carId)){
	echo $_POST['edit'];
	echo "false";
	return false;
}
require_once('DataBaseFunctions.php');

function updateInArray($filename,$carId,$index,$newIndex){
	if($index != $newIndex){
		$file= file_get_contents($filename);
		$DB = json_decode($file);
		if(count($DB[$index]->model) > 0){
			foreach($DB[$index]->model as $key => $value){
				if($value == $carId){
					echo $value.' deleted from '.$index.' on '.$key.'<br />';
					unset($DB[$index]->model[$key]);
					break;
				}
			}
			$DB[$index]->model = array_values($DB[$index]->model);
		}
		echo $carId.' added to '.$newIndex.'<br />';
		array_push($DB[$newIndex]->model, intval($carId));
		$jsonData = json_encode($DB);
		file_put_contents($filename, $jsonData);
	}
}

	// Define a destination
	$targetFolder = '/upload/img'; // Relative to the root
	
	//Elements
	$name= utf8($_POST['name']);
	$type = intval($_POST['type']);
	$mark = intval($_POST['mark']);
	$model = utf8($_POST['model']);
	$price = utf8($_POST['price']);
	$color = utf8($_POST['color']);
	$year = utf8($_POST['year']);
	$power = utf8($_POST['power']); 
	$vvv = utf8($_POST['vvv']);
	$fuel = utf8($_POST['fuel']);
	$kpp = utf8($_POST['kpp']);
	$drive = utf8($_POST['drive']);
	$door = utf8($_POST['door']); 
	$seat = utf8($_POST['seat']);
	$city = utf8($_POST['city']);
	$img = 0;
	$complect = utf8($_POST['complect']);
	

	$data = array(
	    "name" => $name,
	    "type" => $type,
	    "mark" => $mark,  
	    "model" => $model, 
	    "price" => $price,
	    "color" => $color,
	    "year" => $year, 
	    "power" => $power, 
	    "vvv" => $vvv, 
	    "fuel" => $fuel, 
	    "kpp" => $kpp, 
	    "drive" => $drive,
	    "door" => $door,
	    "seat" => $seat,
	    "city" => $city,
	    "img" => $img,
	    "complect" => str_replace(">****",">",$complect));
	
	//to Ddata
	$inp = file_get_contents($pathTojs.'ddata.js');
	$carDB = json_decode($inp);    
	
	//some additions
		$imageList = $_POST['images'];
		$imageDelList = $_POST['imagesDel'];
		$data["img"] = array_unique($imageList);
		if(count($imageDelList) > 0){
			foreach($imageDelList as $value){
				deleteImage('../'.$value);
			}
		}
		//$data["img"] += $imageList;
		//$data["img"] = array_merge($data["img"], $imageList);
		if(count($data["img"]) == 0)
			$data["img"] = 0;
	//End of additions       
	/*if($img == 0){
		echo 'Old Image<br />';
		$data["img"] = $carDB[$carId] -> img;
	}
	else{
		echo 'New Image<br />';
		foreach($carDB[$carId] -> img as $value){
			deleteImage('../'.$value);
		}
	}*/
	//to DType        
	updateInArray($pathTojs.'dtype.js',$carId,$carDB[$carId]->type,$type);
	//to DBrand
	updateInArray($pathTojs.'dbrand.js',$carId,$carDB[$carId]->mark,$mark);
	
	$carDB[$carId] = $data;
	$jsonData = json_encode($carDB);
	file_put_contents($pathTojs.'ddata.js', $jsonData);
	/*Delete images part
	imageList = $_POST['images'];
	imageDelList = $_POST['imagesDel'];
	if (file_exists('../img_temp.txt')) {
		$imgstr = utf8(file_get_contents('../img_temp.txt'));
		$imgstr = rtrim($imgstr);
		$img = explode( "\r\n" ,$imgstr);
		for(i = count(imageList)-1; i >= 0; --i){
			$id = array_search($imageList[i], $img);
			if($id !== false)
				unset($img[id]);
		}
		array_values($img);
		$imgstr = implode( "\r\n" ,$img);
		file_put_contents('../img_temp.txt',);
	} 

	$data["img"] = array_diff($carDB[$carId] -> img, imageDelList);
	foreach($imageDelList as $value){
		deleteImage('../'.$value);
	}
	$data["img"] += imageList;
	*/
?>