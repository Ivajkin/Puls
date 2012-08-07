<?php
  
function utf8($value)
{
    return mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value);
}
  
$type = $_POST['type'];
$mark = $_POST['mark'];
$model = utf8($_POST['model']);
$price = utf8($_POST['price']);
$color = utf8($_POST['color']);
$year = utf8($_POST['year']);
$vvv = utf8($_POST['vvv']);
$fuel = utf8($_POST['fuel']);
$kpp = utf8($_POST['kpp']);
$drive = utf8($_POST['drive']);
$complect = utf8($_POST['complect']);
$name= utf8($_POST['name']);

$data = array(
"name" => $name,
"type" => $type,
"mark" => $mark,
"model" => $model,
"price" => $price,
"year" => $year,
"image" => array("imagePath_1", "ImagePath_2", "ImagePath_3"),
"complect" => $complect);
//$value = mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value);

$inp = file_get_contents('js/ddata.json');
$tempArray = json_decode($inp);
$tempArray[]= $data;
$jsonData = json_encode($tempArray);
file_put_contents('js/ddata.json', $jsonData);

?>