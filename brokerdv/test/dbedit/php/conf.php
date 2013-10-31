<?php
/*if ( !preg_match("/MSIE/i", $_SERVER["HTTP_USER_AGENT"]) ) {
    $enc = 'UTF-8';
    header('Content-type: text/html; charset='.$enc);
    mb_internal_encoding($enc);
} else {
    $enc = 'windows-1251';
    header('Content-type: text/html; charset='.$enc);
    mb_internal_encoding($enc);
}*/
mb_internal_encoding("utf-8");
header('Content-Type: text/html; charset=utf-8');
setlocale(LC_ALL, 'ru_RU');

/************
 * Connection
 ************/

if ($_SERVER["HTTP_HOST"] != '127.0.0.1' && $_SERVER["HTTP_HOST"] && 'localhost' && $_SERVER["HTTP_HOST"] != 'broker-dv.su') {
//Remote
    $link = mysql_connect("localhost","core5429_brokusr","brokpass239") or
    die("Could not connect: " . mysql_error());
    mysql_select_db("core5429_brokdata", $link);
    mysql_set_charset("utf8");
} else {
//Local
    $link = mysql_connect("127.0.0.1","brokuser","local") or
    die("Could not connect: " . mysql_error());
    mysql_select_db("brokdata", $link);
    mysql_set_charset("utf8");
}

/************
 * Path Vars
 ***********/

$sitename= 'dbedit';
$curpath = dirname(__FILE__).DIRECTORY_SEPARATOR;

$rootpath= '';
preg_match('/.+'.$sitename.'/', $curpath, $rootpath);
$rootpath= $rootpath[0].DIRECTORY_SEPARATOR;

/************
 * Template Vars
 ***********/

$cssclass = '';
$main_block= '';
$script_block = '';
$css_lib = '';
$script_lib = '';

error_reporting(E_ALL /*& ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED*/);
?>