<?php
require_once(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'conf.php');

$ptype= intval($_POST['ptype']);
$cost = array(intval($_POST['cost'][0]), intval($_POST['cost'][1]));
$totalarea = array(intval($_POST['totalarea'][0]), intval($_POST['totalarea'][1]));

$tmp= intval(explode(' ', $_POST['district'])[1]);
$tmp2= intval(explode(' ', $_POST['district'])[0]);
//local (in select) index managing
$district = ($tmp-(--$tmp2*9) == 1) ? array(1, 999) : array($tmp, $tmp);
//1-3
$tmp= intval($_POST['room']);
$room= ($tmp == 1) ? array(1, 999) : array($tmp, $tmp);

$tmp= intval($_POST['hometype']);
$hometype= ($tmp == 1) ? array(1, 999) : array($tmp, $tmp);

$tmp= intval($_POST['planning']);
$planning= ($tmp == 1) ? array(1, 999) : array($tmp, $tmp);

$tmp= intval($_POST['state']);
$state= ($tmp == 1) ? array(1, 999) : array($tmp, $tmp);

$tmp= intval($_POST['balcony']);
$balcony= ($tmp == 1) ? array(1, 999) : array($tmp, $tmp);

$tmp= intval($_POST['lavatory']);
$lavatory= ($tmp == 1) ? array(1, 999) : array($tmp, $tmp);

$livearea = array(intval($_POST['livearea'][0]), intval($_POST['livearea'][1]));
$cookarea = array(intval($_POST['cookarea'][0]), intval($_POST['cookarea'][1]));
$storey= array(intval($_POST['storey'][0]), intval($_POST['storey'][1]));
//4
$tmp= intval($_POST['acreusage']);
$acreusage= ($tmp == 1) ? array(1, 999) : array($tmp, $tmp);

switch ($ptype) {
    case 4:
    $qdata= array(
        'cost' => array($cost[0], $cost[1]),
        'totalarea' => array($totalarea[0], $totalarea[1]),
        'district' => $district,
        'acreusage' => $acreusage
    );
    $query = "SELECT * FROM br_acreinfo
       WHERE
         (cost >= ".$qdata['cost'][0]." AND cost <= ".$qdata['cost'][1].") AND
         (totalarea >= ".$qdata['totalarea'][0]." AND totalarea <= ".$qdata['totalarea'][1].") AND
         (fid_district >= ".$qdata['district'][0]." AND fid_district <= ".$qdata['district'][1].") AND
         (fid_acreusage >= ".$qdata['acreusage'][0]." AND fid_acreusage <= ".$qdata['acreusage'][1].")";
    break;
    case 3:
    $qdata= array(
        'cost' => array($cost[0], $cost[1]),
        'totalarea' => array($totalarea[0], $totalarea[1]),
        'district' => $district,
        'storey' => $storey
    );
    $query = "SELECT * FROM br_cominfo
       WHERE
         (cost >= ".$qdata['cost'][0]." AND cost <= ".$qdata['cost'][1].") AND
         (totalarea >= ".$qdata['totalarea'][0]." AND totalarea <= ".$qdata['totalarea'][1].") AND
         (storey >= ".$qdata['storey'][0]." AND storey <= ".$qdata['storey'][1].") AND
         (fid_district >= ".$qdata['district'][0]." AND fid_district <= ".$qdata['district'][1].")";
    break;
    default:
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
        'storey' => array($storey[0], $storey[1])
    );
    $query = "SELECT * FROM br_homeinfo
       WHERE
         (fid_ptype = ".$qdata['ptype'].") AND
         (cost >= ".$qdata['cost'][0]." AND cost <= ".$qdata['cost'][1].") AND
         (totalarea >= ".$qdata['totalarea'][0]." AND totalarea <= ".$qdata['totalarea'][1].") AND
         (livearea >= ".$qdata['livearea'][0]." AND livearea <= ".$qdata['livearea'][1].") AND
         (cookarea >= ".$qdata['cookarea'][0]." AND cookarea <= ".$qdata['cookarea'][1].") AND
         (storey >= ".$qdata['storey'][0]." AND storey <= ".$qdata['storey'][1].") AND
         (fid_district >= ".$qdata['district'][0]." AND fid_district <= ".$qdata['district'][1].") AND
         (fid_room >= ".$qdata['room'][0]." AND fid_room <= ".$qdata['room'][1].") AND
         (fid_hometype >= ".$qdata['hometype'][0]." AND fid_hometype <= ".$qdata['hometype'][1].") AND
         (fid_planning >= ".$qdata['planning'][0]." AND fid_planning <= ".$qdata['planning'][1].") AND
         (fid_state >= ".$qdata['state'][0]." AND fid_state <= ".$qdata['state'][1].") AND
         (fid_balcony >= ".$qdata['balcony'][0]." AND fid_balcony <= ".$qdata['balcony'][1].") AND
         (fid_lavatory >= ".$qdata['lavatory'][0]." AND fid_lavatory <= ".$qdata['lavatory'][1].")";
}
//echo $query;
//qlog= json_encode($qdata);
//echo $qlog;

$result = $link->query($query);
//echo 'result';
//var_dump($result);

echo $result;

//$tmp= array();
//$i= 0;
//while ($rows[$i]) $tmp[]= intval($rows[$i++][0]);

?>