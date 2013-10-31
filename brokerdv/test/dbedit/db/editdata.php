<?php
/**
 * Created by JetBrains PhpStorm.
 * User: leve_000
 * Date: 31.10.13
 * Time: 14:34
 * To change this template use File | Settings | File Templates.
 */

require_once(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'conf.php');
require_once($rootpath.'php/std.php');


if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {

    $ptype= intval($_POST['ptype']);
    $id= intval($_POST['id']);

    $table= 'br_homeinfo';
    if (intval($ptype) == 4) $table= 'br_acreinfo';
    else if (intval($ptype) == 3) $table= 'br_cominfo';

    $query = "SELECT * FROM $table WHERE id = $id";

    $result = mysql_query($query, $link);
    //var_dump($result);
    //var_dump($query);
    error_reporting(E_ALL & E_WARNING & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

    $rows= array();
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
        $rows[]= $row;
    $qres= json_encode($rows);
    echo $qres;

    mysql_free_result($result);
    exit;
}
if (isset($_GET['ajax']) && $_GET['ajax'] == 2) {
    $id= intval($_POST['id']);
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
        $query = "UPDATE br_acreinfo SET
             location = '".$qdata['location']."',
             cost = ".$qdata['cost'][0].",
             totalarea = ".$qdata['totalarea'][0].",
             fid_district = ".$qdata['district'].",
             fid_acreusage = ".$qdata['acreusage']. "
             WHERE id = $id";
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
        $query = "UPDATE br_cominfo SET
             location = '".$qdata['location']."',
             cost = ".$qdata['cost'][0].",
             totalarea = ".$qdata['totalarea'][0].",
             storey = ".$qdata['storey'].",
             fid_district = ".$qdata['district']. "
             WHERE id = $id";
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
        $query = "UPDATE br_homeinfo SET
             location = '".$qdata['location']."',
             cost = ".$qdata['cost'][0].",
             totalarea = ".$qdata['totalarea'][0].",
             livearea = ".$qdata['livearea'][0].",
             cookarea = ".$qdata['cookarea'][0].",
             storey = ".$qdata['storey'][0].",
             fid_ptype = ".$qdata['ptype'].",
             fid_room = ".$qdata['room'].",
             fid_district = ".$qdata['district'].",
             fid_hometype = ".$qdata['hometype'].",
             fid_planning = ".$qdata['planning'].",
             fid_state = ".$qdata['state'].",
             fid_balcony = ".$qdata['balcony'].",
             fid_lavatory = ".$qdata['lavatory']. "
             WHERE id = $id";
    }
    $result = mysql_query($query, $link);
    var_dump($result);
    var_dump($query);
    mysql_free_result($result);

    exit;
}




?>