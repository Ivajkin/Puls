<?php
mb_internal_encoding("utf-8");
header('Content-Type: text/html; charset=utf-8');

//conection:
$link = mysqli_connect("localhost","brokuser","brokpass239","brokbase") or die("Error " . mysqli_error($link));
$link->set_charset("utf8");
//echo $link->character_set_name();
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

function gettbl(&$link, $tbl) {
	$query = "SELECT * FROM ".$tbl or die("Error in the consult.." . mysqli_error($link));
	//execute the query.
	$result = $link->query($query);
	while($row = $result->fetch_array(MYSQLI_ASSOC))
		$rows[] = $row;
/*foreach($rows as $row) {
	var_dump($row);
	echo "<br />";
	}*/
	/* free result set */
	$result->close();
	return $rows;
}

$dist= gettbl($link, "br_district");
var_dump($dist);

/* close connection */
$link->close();

?>