<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 25.08.13
 * Time: 5:08
 * To change this template use File | Settings | File Templates.
 */

require_once('../bd_3.php');

$br_room= gettbl($link, 'br_room');
$br_ptype= 2;
$br_district= gettbl($link, 'br_district');
$br_hometype= gettbl($link, 'br_hometype');
$br_planning= gettbl($link, 'br_planning');
$br_state= gettbl($link, 'br_state');
$br_balcony= gettbl($link, 'br_balcony');
$br_lavatory= gettbl($link, 'br_lavatory');
$br_realtor= gettbl($link, 'br_realtor');
//var_dump($br_realtor);

//Файл test.xml содержит XML-документ с корневым элементом
//и, по крайней мере, элемент /[root]/title.
//$qwe= intval($_POST['qwe']);
if (file_exists('2zz.xml')) {
    $xml = simplexml_load_file('2zz.xml');
    $json = json_encode($xml);
    //echo $json;
    $arr = json_decode($json, TRUE);

    $rows = array();
    for ($i=2; $i<count($arr['Worksheet']['Table']['Row']); $i++) {
        $tmp= $arr['Worksheet']['Table']['Row'][$i]['Cell'];
        $tarr= array();
        for($j=0; $j< count($tmp); $j++) {
            $tarr[]= $tmp[$j]['Data'];
        }
        $rows[]= $tarr;
    }
    //var_dump($rows);
    //{ [0]=> array(14) { [0]=> string(1) "1" [1]=> string(5) "60000" [2]=> string(28) "Индустриальный" [3]=> string(17) "не задано" [4]=> string(17) "не задано" [5]=> string(17) "не задано" [6]=> string(17) "не задано" [7]=> string(17) "не задано" [8]=> string(2) "15" [9]=> string(17) "не задано" [10]=> string(17) "не задано" [11]=> string(17) "не задано" [12]=> string(25) "Кондакова С.А." [13]=> string(117) "Россия, Хабаровский край, Хабаровск, Хабаровск, Объединенная ул." }
    for ($i=0; $i<count($rows); $i++){
        for ($j=0; $j<count($br_realtor); $j++){
            $rows[$i][12] = mb_convert_case($rows[$i][12], MB_CASE_LOWER, "UTF-8");
            $br_realtor[$j]['realtor'] = mb_convert_case($br_realtor[$j]['realtor'], MB_CASE_LOWER, "UTF-8");
            if (stripos($rows[$i][12], $br_realtor[$j]['realtor']) !== false) {
                $rows[$i][12] = $br_realtor[$j]['id'];
                break;
            }
        }
        for ($j=12; $j<23; $j++){
            /*var_dump($rows[$i][2]);
            echo '<br />';
            var_dump($br_district[$j]['district']);
            echo '<br />';
            echo '<br />';*/
            $rows[$i][2] = mb_convert_case($rows[$i][2], MB_CASE_LOWER, "UTF-8");
            $br_district[$j]['district'] = mb_convert_case($br_district[$j]['district'], MB_CASE_LOWER, "UTF-8");
            if (stripos($rows[$i][2], substr($br_district[$j]['district'], 0, strlen($br_district[$j]['district'])/2)) !== false) {
                $rows[$i][2] = $br_district[$j]['id'];
                break;
            }
        }
        for ($j=0; $j<count($br_hometype); $j++){
            $rows[$i][3] = mb_convert_case($rows[$i][3], MB_CASE_LOWER, "UTF-8");
            $br_hometype[$j]['hometype'] = mb_convert_case($br_hometype[$j]['hometype'], MB_CASE_LOWER, "UTF-8");
            if (stripos($rows[$i][3], $br_hometype[$j]['hometype']) !== false) {
                $rows[$i][3] = $br_hometype[$j]['id'];
                break;
            }
        }
        for ($j=0; $j<count($br_planning); $j++){
            $rows[$i][4] = mb_convert_case($rows[$i][4], MB_CASE_LOWER, "UTF-8");
            $br_planning[$j]['planning'] = mb_convert_case($br_planning[$j]['planning'], MB_CASE_LOWER, "UTF-8");
            if (stripos($rows[$i][4], $br_planning[$j]['planning']) !== false) {
                $rows[$i][4] = $br_planning[$j]['id'];
                break;
            }
        }
        for ($j=0; $j<count($br_state); $j++){
            $rows[$i][5] = mb_convert_case($rows[$i][5], MB_CASE_LOWER, "UTF-8");
            $br_state[$j]['state'] = mb_convert_case($br_state[$j]['state'], MB_CASE_LOWER, "UTF-8");
            if (stripos($rows[$i][5], $br_state[$j]['state']) !== false) {
                $rows[$i][5] = $br_state[$j]['id'];
                break;
            }
        }
        for ($j=0; $j<count($br_balcony); $j++){
            $rows[$i][6] = mb_convert_case($rows[$i][6], MB_CASE_LOWER, "UTF-8");
            $br_balcony[$j]['balcony'] = mb_convert_case($br_balcony[$j]['balcony'], MB_CASE_LOWER, "UTF-8");
            if (stripos($rows[$i][6], $br_balcony[$j]['balcony']) !== false) {
                $rows[$i][6] = $br_balcony[$j]['id'];
                break;
            }
        }
        for ($j=0; $j<count($br_lavatory); $j++){
            $rows[$i][7] = mb_convert_case($rows[$i][7], MB_CASE_LOWER, "UTF-8");
            $br_lavatory[$j]['lavatory'] = mb_convert_case($br_lavatory[$j]['lavatory'], MB_CASE_LOWER, "UTF-8");
            if (stripos($rows[$i][7], $br_lavatory[$j]['lavatory']) !== false) {
                $rows[$i][7] = $br_lavatory[$j]['id'];
                break;
            }
        }
        for ($j=0; $j<count($br_room); $j++){
            $ll= intval($rows[$i][0]);
            $rr= intval($br_room[$j]['room']);
            if ($ll < 5 && $ll == $rr) {
                $rows[$i][0] = $br_room[$j]['id'];
                break;
            } else if ($ll > 4) {
                $rows[$i][0]= 6;
                break;
            } else if ($ll == 0) {
                $rows[$i][0]= 7;
                break;
            }
        }
    }
    /*for ($i=0; $i<count($rows);$i++){
        var_dump($rows[$i]);
        echo "<br />";
    }*/
    $query = "INSERT INTO br_homeinfo VALUES <br/>";
    for ($i=0; $i<count($rows)-1; $i++){
        $query.="('', '0', '0', '0', '".$rows[$i][13]."', '0', '".$rows[$i][1]."', '".$rows[$i][8]."', '".$rows[$i][9]."', '".$rows[$i][11]."', '".$rows[$i][10]."', '".$br_ptype."', '".$rows[$i][12]."', '".$rows[$i][0]."', '".$rows[$i][2]."', '".$rows[$i][3]."', '".$rows[$i][4]."', '".$rows[$i][5]."', '".$rows[$i][6]."', '".$rows[$i][7]."'),<br/>";
    }
    echo $query;
            /*'".$qdata['name']."',
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
             ".$qdata['lavatory'].")*/
} else {
    exit('Не удалось открыть файл test.xml.');
}
/*INSERT INTO `br_district` (`id`, `fid_ptype`, `district`) VALUES
(1, 1, 'все'),
(2, 1, 'Центральный'),
(3, 1, 'Железнодорожный'),
(4, 1, 'Индустриальный'),
(5, 1, 'Кировский'),
(6, 1, 'Краснофлотский'),
(7, 1, 'Пригород (южное направление)'),
(8, 1, 'Пригород (Северное направление)'),
(9, 1, 'Пригород (Комсомольское направление)'),
(10, 1, 'Пригород (ЕАО)'),
(11, 1, 'не задано'),
(12, 2, 'все');*/

?>