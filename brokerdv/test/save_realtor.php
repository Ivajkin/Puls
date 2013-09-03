<?php
require_once('conf.php');

$rlfio= utf8($_POST['rl_fio']);
$rltel= utf8($_POST['rl_tel']);

    $query = "INSERT INTO br_realtor VALUES ('',
            '".$rlfio."',
             ".$rltel.")";

 $result = mysql_query($query, $link);
var_dump($result);
mysql_free_result($result);

?>