<?php
require_once(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'php'.DIRECTORY_SEPARATOR.'conf.php');

function gettbl($link, $tbl) {
    $result = mysql_query("SELECT * FROM ".$tbl, $link);
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
        $rows[]= $row;
    //dvar_dump($rows);
    mysql_free_result($result);
    return $rows;
}

//$res= gettbl($link, "br_district");
//var_dump($res);

/* close connection */
//mysql_close($link);

?>