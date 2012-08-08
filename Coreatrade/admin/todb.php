<?php
  
function utf8($value)
{
    return mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value);
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
$img = utf8(file_get_contents('img_temp.txt'));
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
    "img" => explode ( "\r\n" ,$img),
    "complect" => $complect);

unlink('img_temp.txt');
//$value = mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value);

$inp = file_get_contents('../js/ddata.js');
$tempArray = json_decode($inp);
$tempArray[]= $data;
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


<!--<html>
<head><title>123></title>
<script type="javascript"
</head>
<body></body>
</html>-->
