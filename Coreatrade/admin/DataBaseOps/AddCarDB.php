<?php
	require_once('DataBaseFunctions.php');

/*function getNormalizedFILES()
{
    $newfiles = array();
    foreach($_FILES as $fieldname => $fieldvalue)
        foreach($fieldvalue as $paramname => $paramvalue)
            foreach((array)$paramvalue as $index => $value)
                $newfiles[$fieldname][$index][$paramname] = $value;
    return $newfiles;
} 

function rearrange( $arr ){
    foreach( $arr as $key => $all ){
        foreach( $all as $i => $val ){
            $new[$i][$key] = $val;   
        }   
    }
    return $new;
} */

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

//$value = mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value);

//to Ddata
$inp = file_get_contents($pathTojs.'/ddata.js');
$tempArray = json_decode($inp);
$car_id = count($tempArray);  
        
        //some additions
		$imageList = $_POST['images'];
		$imageDelList = $_POST['imagesDel'];
		$data["img"] = $imageList;
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
$tempArray[]= $data;

$jsonData = json_encode($tempArray);
file_put_contents($pathTojs.'ddata.js', $jsonData);

//to DType
$dtypefile = file_get_contents($pathTojs.'dtype.js');
$dtype = json_decode($dtypefile );            
array_push($dtype[ intval($type)]->model, $car_id);
$jsonData = json_encode($dtype);
file_put_contents($pathTojs.'dtype.js', $jsonData);

//to DBrand
$dbrandfile = file_get_contents($pathTojs.'dbrand.js');
$dbrand = json_decode($dbrandfile );
array_push($dbrand[ intval($mark)]->model, $car_id);
$jsonData = json_encode($dbrand);
file_put_contents($pathTojs.'dbrand.js', $jsonData);

?>