<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 25.08.13
 * Time: 5:08
 * To change this template use File | Settings | File Templates.
 */

require_once('../bd_3.php');

$br_district= gettbl($link, 'br_district');
$br_acreusage= gettbl($link, 'br_acreusage');
$br_realtor= gettbl($link, 'br_realtor');
//var_dump($br_realtor);

//Файл test.xml содержит XML-документ с корневым элементом
//и, по крайней мере, элемент /[root]/title.
//$qwe= intval($_POST['qwe']);
if (file_exists('4zz.xml')) {
    $xml = simplexml_load_file('4zz.xml');
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
    //[0]=> string(109) "Россия, Хабаровский край, Хабаровск, Хабаровск, Авиагородок" [1]=> string(29) "под садоводство" [2]=> string(4) "1500" [3]=> string(23) "Балтинас Р.Р." [4]=> string(30) "Железнодорожный" }
    for ($i=0; $i<count($rows); $i++){
        for ($j=0; $j<count($br_realtor); $j++){
            if (stripos($rows[$i][3], $br_realtor[$j]['realtor']) !== false) {
                $rows[$i][3] = $br_realtor[$j]['id'];
            }
        }
        for ($j=34; $j<count($br_district); $j++){
            if (stripos($rows[$i][4], $br_district[$j]['district']) !== false) {
                $rows[$i][4] = $br_district[$j]['id'];
            }
        }
        for ($j=0; $j<count($br_acreusage); $j++){
            if (stripos($rows[$i][1], $br_acreusage[$j]['acreusage']) !== false) {
                $rows[$i][1] = $br_acreusage[$j]['id'];
            }
        }
    }
    /*for ($i=0; $i<count($rows);$i++){
        var_dump($rows[$i]);
        echo "\n\r\n\r\n\r";
    }*/
    $query = "INSERT INTO br_acreinfo VALUES <br/>";
    for ($i=0; $i<count($rows)-1; $i++){
        $query.="('', '0', '0', '0', '".$rows[$i][0]."', '0', '0', '".$rows[$i][2]."', '".$rows[$i][3]."', '".$rows[$i][4]."', '".$rows[$i][1]."'),<br/>";
    }
    echo $query;
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