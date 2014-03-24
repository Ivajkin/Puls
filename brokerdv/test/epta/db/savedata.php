<?php
require_once(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'conf.php');
require_once($rootpath.'php/std.php');

$ptype= intval($_POST['ptype']);
$cost = array(intval($_POST['cost'][0]), intval($_POST['cost'][1]));
$totalarea = array(intval($_POST['totalarea'][0]), intval($_POST['totalarea'][1]));
$district = intval(explode(' ', $_POST['district'])[1]);
//1-3
$room= intval($_POST['room']);
$hometype= intval($_POST['hometype']);
$planning= intval($_POST['planning']);
$state= intval($_POST['state']);
$balcony= intval($_POST['balcony']);
$lavatory= intval($_POST['lavatory']);
$livearea = array(intval($_POST['livearea'][0]), intval($_POST['livearea'][1]));
$cookarea = array(intval($_POST['cookarea'][0]), intval($_POST['cookarea'][1]));
$storey= array(intval($_POST['storey'][0]), intval($_POST['storey'][1]));
//4
$acreusage= intval($_POST['acreusage']);
//savedata
$realtor= intval($_POST['realtor']);
$name= utf8($_POST['name']);
$description= utf8($_POST['description']);
$photo= utf8($_POST['photo']);
$address= utf8($_POST['address']);
$location= utf8($_POST['location']);

if (intval($ptype) == 4) {
    $qdata= array(
        'cost' => array($cost[0], $cost[1]),
        'totalarea' => array($totalarea[0], $totalarea[1]),
        'district' => $district,
        'acreusage' => $acreusage,
        'name' => $name,
        'description' => $description,
        'photo' => $photo,
        'address' => $address,
        'location' => $location,
        'realtor' => $realtor
    );
    $query = "INSERT INTO br_acreinfo VALUES ('',
            '".$qdata['name']."',
            '".$qdata['description']."',
             '".$qdata['photo']."',
             '".$qdata['address']."',
             '".$qdata['location']."',
             ".$qdata['cost'][0].",
             ".$qdata['totalarea'][0].",
             ".$qdata['realtor'].",
             ".$qdata['district'].",
             ".$qdata['acreusage'].")";
} else if (intval($ptype) == 3) {
    $qdata= array(
        'cost' => array($cost[0], $cost[1]),
        'totalarea' => array($totalarea[0], $totalarea[1]),
        'district' => $district,
        'storey' => $storey,
        'name' => $name,
        'description' => $description,
        'photo' => $photo,
        'address' => $address,
        'location' => $location,
        'realtor' => $realtor
    );
    $query = "INSERT INTO br_cominfo VALUES ('',
            '".$qdata['name']."',
            '".$qdata['description']."',
             '".$qdata['photo']."',
             '".$qdata['address']."',
             '".$qdata['location']."',
             ".$qdata['cost'][0].",
             ".$qdata['totalarea'][0].",
             ".$qdata['storey'].",
             ".$qdata['realtor'].",
             ".$qdata['district'].")";
} else {
    $qdata= array(
        'ptype' => $ptype,
        'cost' => array($cost[0], $cost[1]),
        'totalarea' => array($totalarea[0], $totalarea[1]),
        'district' => $district,
        'room' => $room,
        'hometype' => $hometype,
        'planning' => $planning,
        'state' => $state,
        'balcony' => $balcony,
        'lavatory' => $lavatory,
        'livearea' => array($livearea[0], $livearea[1]),
        'cookarea' => array($cookarea[0], $cookarea[1]),
        'storey' => array($storey[0], $storey[1]),
        'name' => $name,
        'description' => $description,
        'photo' => $photo,
        'address' => $address,
        'location' => $location,
        'realtor' => $realtor
    );
    // )  ;-->
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
             ".$qdata['realtor'].",
             ".$qdata['room'].",
             ".$qdata['district'].",
             ".$qdata['hometype'].",
             ".$qdata['planning'].",
             ".$qdata['state'].",
             ".$qdata['balcony'].",
             ".$qdata['lavatory'].")";
}
$result = $link->query($query);
var_dump($result);
var_dump($query);
?>