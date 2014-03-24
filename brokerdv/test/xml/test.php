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


if (isset($_GET['AJAX']) && $_GET['AJAX'] == 1) {
    // JSON response when AJAX parameter is passed
    if (file_exists('mainv2.xml')) {
        $xml = simplexml_load_file('mainv2.xml');
        $json = json_encode($xml);
        echo $json;
        //$arr = json_decode($json, TRUE);
    } else {
        exit('Не удалось открыть файл test.xml.');
    }
    // Terminate further output after JSON
    exit;
}

//echo setlocale(LC_ALL, array ('ru', 'ru_RU', 'ru_RU.CP1251', 'rus_RUS.1251', 'russia'));

$tmp= mb_convert_case("ЁЁЁ№№№", MB_CASE_LOWER, "UTF-8");
echo $tmp;
$tmp= mb_convert_case($tmp, MB_CASE_UPPER, "UTF-8");
echo $tmp;
?>

<script src="../jquery-1.7.2.js"></script>
<script>
    $.post( "test.php?AJAX=1", '0', function( data, textStatus ) {
        //$( ".result" ).html( data );
        console.log(textStatus);

        xmlout= JSON.parse(data);
        console.log(xmlout);
    }, 'text');
</script>