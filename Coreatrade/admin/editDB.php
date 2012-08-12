<?php

$carId= $_POST['edit'];
if(!is_numeric($carId)){
	echo $_POST['edit'];
	echo "false";
	return false;
}
function utf8($value)
{
    return mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value);
}
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
$type = $_POST['type'];
$mark = $_POST['mark'];
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
if (file_exists('img_temp.txt')) {
   $imgstr = utf8(file_get_contents('img_temp.txt'));
   $imgstr = rtrim($imgstr);
   $img= explode( "\r\n" ,$imgstr);
   unlink('img_temp.txt');
} 
else $img= 0;
$complect = utf8($_POST['complect']);

$data= array(
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
    "img" => $img,
    "complect" => $complect);

//$value = mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value);

//to Ddata
$inp = file_get_contents('../js/ddata.js');
$tempArray = json_decode($inp);           
//---
//unset($tempArray[$index));

//to DType        
updateInArray('../js/dtype.js',$carId,$tempArray[$carId]->type,$type);
//to DBrand
updateInArray('../js/dbrand.js',$carId,$tempArray[$carId]->mark,$mark);
$tempArray[$carId] = $data;
$jsonData = json_encode($tempArray);
file_put_contents('../js/ddata.js', $jsonData);

/*var_dump($_FILES['imgfile']['name']);
var_dump($_FILES['imgfile']['type']);
var_dump($_FILES['imgfile']['tmp_name']);
var_dump($_FILES['imgfile']['error']);
var_dump($_FILES['imgfile']['size']);

$imgs= rearrange( $_FILES['imgfile'] );

var_dump($imgs);*/

?>