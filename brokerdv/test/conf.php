<?php
//error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

mb_internal_encoding("utf-8");
header('Content-Type: text/html; charset=utf-8');
setlocale(LC_ALL, 'ru_RU');

//conection:
$link = mysql_connect("localhost","core5429_brokusr","brokpass239") or
die("Could not connect: " . mysql_error());
mysql_select_db("core5429_brokdata", $link);
mysql_set_charset("utf8");

function utf8($value)
{
    return stripslashes(mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value));
}
?>