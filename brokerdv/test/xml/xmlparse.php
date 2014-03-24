<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 25.08.13
 * Time: 5:08
 * To change this template use File | Settings | File Templates.
 */
error_reporting(E_ALL);

require_once('../bd_3.php');

$br_room= gettbl($link, 'br_room');
$br_district= gettbl($link, 'br_district');
$br_hometype= gettbl($link, 'br_hometype');
$br_planning= gettbl($link, 'br_planning');
$br_state= gettbl($link, 'br_state');
$br_balcony= gettbl($link, 'br_balcony');
$br_lavatory= gettbl($link, 'br_lavatory');
$br_realtor= gettbl($link, 'br_realtor');
$br_acreusage= gettbl($link, 'br_acreusage');
//var_dump($br_realtor);

//broker-dv.com/?q=node/124
//Файл test.xml содержит XML-документ с корневым элементом
//и, по крайней мере, элемент /[root]/title.
//$qwe= intval($_POST['qwe']);

setlocale(LC_NUMERIC, en_US);

function set0($id, $ind) {
    global $rows;
    for ($i=0; $i< count($ind); $i++)
        if (!$rows[$id][$ind[$i]]) $rows[$id][$ind[$i]]= 0;
}
function cleanxml($book){
    $rows = array();
    global $arr;
    for ($i=2; $i<count($arr['Worksheet'][$book]['Table']['Row']); $i++) {
        $tmp= $arr['Worksheet'][$book]['Table']['Row'][$i]['Cell'];
        $tarr= array();

        //Check for empty rows
        if (!count($tmp)) continue;
        $chk= false;
        for($j=0; $j < count($tmp); $j++) {
            if ($tmp[$j]['Data'] != '')
                break;
            else if ($j == count($tmp)-1) $chk= true;
        }
        if ($chk) continue;

        for($j=0; $j < count($tmp); $j++) {
            if ($tmp[$j]['Data'] == '') $tmp[$j]['Data']= 'не задано';
            $tarr[]= $tmp[$j]['Data'];
        }
        $rows[]= $tarr;
    }
    return $rows;
}

if (file_exists('mainv2.xml')) {
    $xml = simplexml_load_file('mainv2.xml');
    $json = json_encode($xml);
    //echo $json;
    $arr = json_decode($json, TRUE);

/********
 * 11111111
 */
    /*var_dump($arr['Worksheet'][0]['Table']['Row'][17+2]['Cell']);
    echo '<br/>';
    var_dump($arr['Worksheet'][0]['Table']['Row'][0+2]['Cell']);*/

    $rows= cleanxml(0);
    $br_ptype= 1;

    //var_dump($rows);
    //{ [0]=> array(14) { [0]=> string(1) "1" [1]=> string(5) "60000" [2]=> string(28) "Индустриальный" [3]=> string(17) "не задано" [4]=> string(17) "не задано" [5]=> string(17) "не задано" [6]=> string(17) "не задано" [7]=> string(17) "не задано" [8]=> string(2) "15" [9]=> string(17) "не задано" [10]=> string(17) "не задано" [11]=> string(17) "не задано" [12]=> string(25) "Кондакова С.А." [13]=> string(117) "Россия, Хабаровский край, Хабаровск, Хабаровск, Объединенная ул." }
    for ($i=0; $i<count($rows); $i++){
        for ($j=0; $j<count($br_realtor); $j++){
            $rows[$i][12] = mb_convert_case($rows[$i][12], MB_CASE_LOWER, "UTF-8");
            $br_realtor[$j]['realtor'] = mb_convert_case($br_realtor[$j]['realtor'], MB_CASE_LOWER, "UTF-8");
            if (mb_stripos($rows[$i][12], $br_realtor[$j]['realtor']) !== false) {
                $rows[$i][12] = $br_realtor[$j]['id'];
                break;
            }
        }
        for ($j=1; $j<10; $j++){
            /*var_dump($rows[$i][2]);
            echo '<br />';
            var_dump($br_district[$j]['district']);
            echo '<br />';
            echo '<br />';*/
            $rows[$i][2] = mb_convert_case($rows[$i][2], MB_CASE_LOWER, "UTF-8");
            $br_district[$j]['district'] = mb_convert_case($br_district[$j]['district'], MB_CASE_LOWER, "UTF-8");
            if (mb_stripos($br_district[$j]['district'], $rows[$i][2]) !== false) {
                $rows[$i][2] = $br_district[$j]['id'];
                break;
            }
        }
        for ($j=0; $j<count($br_hometype); $j++){
            $rows[$i][3] = mb_convert_case($rows[$i][3], MB_CASE_LOWER, "UTF-8");
            $br_hometype[$j]['hometype'] = mb_convert_case($br_hometype[$j]['hometype'], MB_CASE_LOWER, "UTF-8");
            if (mb_stripos($rows[$i][3], $br_hometype[$j]['hometype']) !== false) {
                $rows[$i][3] = $br_hometype[$j]['id'];
                break;
            }
        }
        for ($j=0; $j<count($br_planning); $j++){
            $rows[$i][4] = mb_convert_case($rows[$i][4], MB_CASE_LOWER, "UTF-8");
            $br_planning[$j]['planning'] = mb_convert_case($br_planning[$j]['planning'], MB_CASE_LOWER, "UTF-8");
            if (mb_stripos($rows[$i][4], mb_substr($br_planning[$j]['planning'], 0, strlen($br_planning[$j]['planning'])/2)) !== false) {
                $rows[$i][4] = $br_planning[$j]['id'];
                break;
            }
        }
        for ($j=0; $j<count($br_state); $j++){
            $rows[$i][5] = mb_convert_case($rows[$i][5], MB_CASE_LOWER, "UTF-8");
            $br_state[$j]['state'] = mb_convert_case($br_state[$j]['state'], MB_CASE_LOWER, "UTF-8");
            if (mb_stripos($rows[$i][5], $br_state[$j]['state']) !== false) {
                $rows[$i][5] = $br_state[$j]['id'];
                break;
            }
        }
        for ($j=0; $j<count($br_balcony); $j++){
            $rows[$i][6] = mb_convert_case($rows[$i][6], MB_CASE_LOWER, "UTF-8");
            $br_balcony[$j]['balcony'] = mb_convert_case($br_balcony[$j]['balcony'], MB_CASE_LOWER, "UTF-8");
            if (mb_stripos($rows[$i][6], $br_balcony[$j]['balcony']) !== false) {
                $rows[$i][6] = $br_balcony[$j]['id'];
                break;
            }
        }
        for ($j=0; $j<count($br_lavatory); $j++){
            $rows[$i][7] = mb_convert_case($rows[$i][7], MB_CASE_LOWER, "UTF-8");
            $br_lavatory[$j]['lavatory'] = mb_convert_case($br_lavatory[$j]['lavatory'], MB_CASE_LOWER, "UTF-8");
            if (mb_stripos($rows[$i][7], $br_lavatory[$j]['lavatory']) !== false) {
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
                $rows[$i][0] = mb_convert_case($rows[$i][0], MB_CASE_LOWER, "UTF-8");
                $br_room[$j]['room'] = mb_convert_case($br_room[$j]['room'], MB_CASE_LOWER, "UTF-8");
                if (mb_stripos($rows[$i][0], $br_room[$j]['room']) !== false) {
                    $rows[$i][0] = $br_room[$j]['id'];
                } else
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
    for ($i=0; $i<count($rows); $i++){
        set0($i, array(1,8,9,10,11,14));
        $query.="('', '0', '0', '0', '100', '".$rows[$i][14]."', '".$rows[$i][1]."', '".round(floatval($rows[$i][8]), 1, PHP_ROUND_HALF_UP)."', '".round(floatval($rows[$i][9]), 1, PHP_ROUND_HALF_UP)."', '".$rows[$i][11]."', '".round(floatval($rows[$i][10]), 1, PHP_ROUND_HALF_UP)."', '".$br_ptype."', '".$rows[$i][12]."', '".$rows[$i][0]."', '".$rows[$i][2]."', '".$rows[$i][3]."', '".$rows[$i][4]."', '".$rows[$i][5]."', '".$rows[$i][6]."', '".$rows[$i][7]."'),<br/>";
    }
    echo '***HOME*********'.'<br/><br/>';
    echo $query.'<br/><br/>';
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

    /********
     * 222222222
     */


    $rows= cleanxml(1);
    $br_ptype= 2;

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
            if (mb_stripos($rows[$i][12], $br_realtor[$j]['realtor']) !== false) {
                $rows[$i][12] = $br_realtor[$j]['id'];
                break;
            }
        }
        for ($j=10; $j<19; $j++){
            /*var_dump($rows[$i][2]);
            echo '<br />';
            var_dump($br_district[$j]['district']);
            echo '<br />';
            echo '<br />';*/
            $rows[$i][2] = mb_convert_case($rows[$i][2], MB_CASE_LOWER, "UTF-8");
            $br_district[$j]['district'] = mb_convert_case($br_district[$j]['district'], MB_CASE_LOWER, "UTF-8");
            if (mb_stripos($br_district[$j]['district'], $rows[$i][2]) !== false) {
                $rows[$i][2] = $br_district[$j]['id'];
                break;
            }
        }
        for ($j=0; $j<count($br_hometype); $j++){
            $rows[$i][3] = mb_convert_case($rows[$i][3], MB_CASE_LOWER, "UTF-8");
            $br_hometype[$j]['hometype'] = mb_convert_case($br_hometype[$j]['hometype'], MB_CASE_LOWER, "UTF-8");
            if (mb_stripos($rows[$i][3], $br_hometype[$j]['hometype']) !== false) {
                $rows[$i][3] = $br_hometype[$j]['id'];
                break;
            }
        }
        for ($j=0; $j<count($br_planning); $j++){
            $rows[$i][4] = mb_convert_case($rows[$i][4], MB_CASE_LOWER, "UTF-8");
            $br_planning[$j]['planning'] = mb_convert_case($br_planning[$j]['planning'], MB_CASE_LOWER, "UTF-8");
            if (mb_stripos($rows[$i][4], $br_planning[$j]['planning']) !== false) {
                $rows[$i][4] = $br_planning[$j]['id'];
                break;
            }
        }
        for ($j=0; $j<count($br_state); $j++){
            $rows[$i][5] = mb_convert_case($rows[$i][5], MB_CASE_LOWER, "UTF-8");
            $br_state[$j]['state'] = mb_convert_case($br_state[$j]['state'], MB_CASE_LOWER, "UTF-8");
            if (mb_stripos($rows[$i][5], $br_state[$j]['state']) !== false) {
                $rows[$i][5] = $br_state[$j]['id'];
                break;
            }
        }
        for ($j=0; $j<count($br_balcony); $j++){
            $rows[$i][6] = mb_convert_case($rows[$i][6], MB_CASE_LOWER, "UTF-8");
            $br_balcony[$j]['balcony'] = mb_convert_case($br_balcony[$j]['balcony'], MB_CASE_LOWER, "UTF-8");
            if (mb_stripos($rows[$i][6], $br_balcony[$j]['balcony']) !== false) {
                $rows[$i][6] = $br_balcony[$j]['id'];
                break;
            }
        }
        for ($j=0; $j<count($br_lavatory); $j++){
            $rows[$i][7] = mb_convert_case($rows[$i][7], MB_CASE_LOWER, "UTF-8");
            $br_lavatory[$j]['lavatory'] = mb_convert_case($br_lavatory[$j]['lavatory'], MB_CASE_LOWER, "UTF-8");
            if (mb_stripos($rows[$i][7], $br_lavatory[$j]['lavatory']) !== false) {
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
    for ($i=0; $i<count($rows); $i++){
        set0($i, array(1,8,9,10,11,14));
        $query.="('', '0', '0', '0', '100', '".$rows[$i][14]."', '".$rows[$i][1]."', '".round(floatval($rows[$i][8]), 1, PHP_ROUND_HALF_UP)."', '".round(floatval($rows[$i][9]), 1, PHP_ROUND_HALF_UP)."', '".$rows[$i][11]."', '".round(floatval($rows[$i][10]), 1, PHP_ROUND_HALF_UP)."', '".$br_ptype."', '".$rows[$i][12]."', '".$rows[$i][0]."', '".$rows[$i][2]."', '".$rows[$i][3]."', '".$rows[$i][4]."', '".$rows[$i][5]."', '".$rows[$i][6]."', '".$rows[$i][7]."'),<br/>";
    }
    echo '***HOME2*********'.'<br/><br/>';
    echo $query.'<br/><br/>';
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

    /********
     * 33333333
     */

    $rows= cleanxml(2);
    $br_ptype= 3;

    //[0]=> string(109) "Россия, Хабаровский край, Хабаровск, Хабаровск, Авиагородок" [1]=> string(29) "под садоводство" [2]=> string(4) "1500" [3]=> string(23) "Балтинас Р.Р." [4]=> string(30) "Железнодорожный" }
    for ($i=0; $i<count($rows); $i++){
        for ($j=0; $j<count($br_realtor); $j++){
            $rows[$i][3] = mb_convert_case($rows[$i][3], MB_CASE_LOWER, "UTF-8");
            $br_realtor[$j]['realtor'] = mb_convert_case($br_realtor[$j]['realtor'], MB_CASE_LOWER, "UTF-8");
            if (mb_stripos($rows[$i][3], $br_realtor[$j]['realtor']) !== false) {
                $rows[$i][3] = $br_realtor[$j]['id'];
                break;
            }
        }
        for ($j=19; $j<28; $j++){
            $rows[$i][4] = mb_convert_case($rows[$i][4], MB_CASE_LOWER, "UTF-8");
            $br_district[$j]['district'] = mb_convert_case($br_district[$j]['district'], MB_CASE_LOWER, "UTF-8");
            if (mb_stripos($rows[$i][4], $br_district[$j]['district']) !== false) {
                $rows[$i][4] = $br_district[$j]['id'];
                break;
            }
        }
    }
    /*for ($i=0; $i<count($rows);$i++){
        var_dump($rows[$i]);
        echo "\n\r\n\r\n\r";
    }*/
    $query = "INSERT INTO br_cominfo VALUES <br/>";
    for ($i=0; $i<count($rows); $i++){
        set0($i, array(1,2,5,6));
        $query.="('', '0', '0', '0', '100', '".$rows[$i][6]."', '".$rows[$i][5]."', '".round(floatval($rows[$i][2]), 1, PHP_ROUND_HALF_UP)."', '".$rows[$i][1]."', '".$rows[$i][3]."', '".$rows[$i][4]."'),<br/>";
    }
    echo '***COMMERCIAL*********'.'<br/><br/>';
    echo $query.'<br/><br/>';
    /*'".$qdata['name']."',
    '".$qdata['description']."',
     '".$qdata['photo']."',
     '".$qdata['address']."',
     '".$qdata['location']."',
     ".$qdata['cost'][0].",
     ".$qdata['totalarea'][0].",
    storey SMALLINT NOT NULL,
     ".$qdata['realtor'].",
     ".$qdata['district'].")";*/

    /********
     * 4444444
     */

    $rows= cleanxml(3);
    $br_ptype= 4;

    //[0]=> string(109) "Россия, Хабаровский край, Хабаровск, Хабаровск, Авиагородок" [1]=> string(29) "под садоводство" [2]=> string(4) "1500" [3]=> string(23) "Балтинас Р.Р." [4]=> string(30) "Железнодорожный" }
    for ($i=0; $i<count($rows); $i++){
        for ($j=0; $j<count($br_realtor); $j++){
            $rows[$i][3] = mb_convert_case($rows[$i][3], MB_CASE_LOWER, "UTF-8");
            $br_realtor[$j]['realtor'] = mb_convert_case($br_realtor[$j]['realtor'], MB_CASE_LOWER, "UTF-8");
            if (mb_stripos($rows[$i][3], $br_realtor[$j]['realtor']) !== false) {
                $rows[$i][3] = $br_realtor[$j]['id'];
                break;
            }
        }
        for ($j=28; $j<count($br_district); $j++){
            $rows[$i][4] = mb_convert_case($rows[$i][4], MB_CASE_LOWER, "UTF-8");
            $br_district[$j]['district'] = mb_convert_case($br_district[$j]['district'], MB_CASE_LOWER, "UTF-8");
            if (mb_stripos($rows[$i][4], $br_district[$j]['district']) !== false) {
                $rows[$i][4] = $br_district[$j]['id'];
                break;
            }
        }
        for ($j=0; $j<count($br_acreusage); $j++){
            $rows[$i][1] = mb_convert_case($rows[$i][1], MB_CASE_LOWER, "UTF-8");
            $br_acreusage[$j]['acreusage'] = mb_convert_case($br_acreusage[$j]['acreusage'], MB_CASE_LOWER, "UTF-8");
            if (mb_stripos($rows[$i][1], $br_acreusage[$j]['acreusage']) !== false) {
                $rows[$i][1] = $br_acreusage[$j]['id'];
                break;
            }
        }
    }
    /*for ($i=0; $i<count($rows);$i++){
        var_dump($rows[$i]);
        echo "\n\r\n\r\n\r";
    }*/
    $query = "INSERT INTO br_acreinfo VALUES <br/>";
    for ($i=0; $i<count($rows); $i++){
        set0($i, array(2,5,6));
        $query.="('', '0', '0', '0', '100', '".$rows[$i][6]."', '".$rows[$i][5]."', '".round(floatval($rows[$i][2]), 1, PHP_ROUND_HALF_UP)."', '".$rows[$i][3]."', '".$rows[$i][4]."', '".$rows[$i][1]."'),<br/>";
    }
    echo '***GROUND*********'.'<br/><br/>';
    echo $query.'<br/><br/>';
    /*'".$qdata['name']."',
    '".$qdata['description']."',
     '".$qdata['photo']."',
     '".$qdata['address']."',
     '".$qdata['location']."',
     ".$qdata['cost'][0].",
     ".$qdata['totalarea'][0].",
     ".$qdata['realtor'].",
     ".$qdata['district'].",
     ".$qdata['acreusage'].")";*/

} else {
    exit('Не удалось открыть файл test.xml.');
}
/*INSERT INTO  br_district
VALUES (1 ,  1, 'все'),
(NULL ,  1, 'Центральный'),
(NULL ,  1, 'Железнодорожный'),
(NULL ,  1, 'Индустриальный'),
(NULL ,  1, 'Кировский'),
(NULL ,  1, 'Краснофлотский'),
(NULL ,  1, 'Пригород'),
(NULL ,  1, 'Пригород (ЕАО)'),
(9 ,  1, 'не задано'),

(10 ,  2, 'все'),
(NULL ,  2, 'Центральный'),
(NULL ,  2, 'Железнодорожный'),
(NULL ,  2, 'Индустриальный'),
(NULL ,  2, 'Кировский'),
(NULL ,  2, 'Краснофлотский'),
(NULL ,  2, 'Пригород'),
(NULL ,  2, 'Пригород (ЕАО)'),
(18 ,  2, 'не задано'),

(19 ,  3, 'все'),
(NULL ,  3, 'Центральный'),
(NULL ,  3, 'Железнодорожный'),
(NULL ,  3, 'Индустриальный'),
(NULL ,  3, 'Кировский'),
(NULL ,  3, 'Краснофлотский'),
(NULL ,  3, 'Пригород'),
(NULL ,  3, 'Пригород (ЕАО)'),
(27 ,  3, 'не задано'),

(28 ,  4, 'все'),
(NULL ,  4, 'Центральный'),
(NULL ,  4, 'Железнодорожный'),
(NULL ,  4, 'Индустриальный'),
(NULL ,  4, 'Кировский'),
(NULL ,  4, 'Краснофлотский'),
(NULL ,  4, 'Пригород'),
(NULL ,  4, 'Пригород (ЕАО)'),
(36 ,  4, 'не задано');*/

?>