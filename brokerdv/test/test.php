<?php
error_reporting(E_ERROR);

mb_internal_encoding("utf-8");
header('Content-Type: text/html; charset=utf-8');

//conection:
$link = mysql_connect("localhost","brokuser","brokpass239") or
die("Could not connect: " . mysql_error());
mysql_select_db("brokbase", $link);
mysql_set_charset("utf8");

function utf8($value)
{
    return stripslashes(mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value));
}

$qdata= array(
    'ptype' => 1,
    'cost' => array(50, 100),
    'totalarea' => array(54, 98),
    'district' => 5,
    'room' => 2,
    'hometype' => 2,
    'planning' => 2,
    'state' => 2,
    'balcony' => 2,
    'lavatory' => 2,
    'livearea' => array(2, 5),
    'cookarea' => array(5, 10),
    'storey' => array(0, 999),
    'name' => $name,
    'description' => 'iyiyi',
    'photo' => 'iou',
    'address' => '989/lui',
    'location' => '0jjjji68'
);

$query = "INSERT INTO br_homeinfo VALUES ('',
'".$qdata['name']."',
'".$qdata['description']."',
 '".$qdata['photo']."',
 '".$qdata['address']."',
 '".$qdata['location']."',
 ".$qdata['cost'][0].",
 ".$qdata['totalarea'][0].",
 ".$qdata['livearea'][0].",
 ".$qdata['cookarea'][0].",
 ".$qdata['storey'][0].",
 ".$qdata['ptype'].",
 ".$qdata['room'].",
 ".$qdata['district'].",
 ".$qdata['hometype'].",
 ".$qdata['planning'].",
 ".$qdata['state'].",
 ".$qdata['balcony'].",
 ".$qdata['lavatory'].")";

$result = mysql_query($query, $link);
var_dump($result);
mysql_free_result($result);






?>