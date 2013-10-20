<?php

function utf8($value)
{
    return stripslashes(mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value));
}

//setlocale(LC_ALL, 'en_US.UTF-8');

$htmldata = utf8($_POST['htmldata']);
$htmlname = utf8($_POST['htmlname']);
if (strpos($htmlname, '.php') === false && strpos($htmlname, '.cgi') === false && strpos($htmlname, '.htm') === false) {
    echo $htmlname;
    $path= "";
    file_put_contents($path.$htmlname.'.html', $htmldata);
}
?>
