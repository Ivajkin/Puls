<?php
mb_internal_encoding("utf-8");
header('Content-Type: text/html; charset=utf-8');
//$loc_ry= setlocale(LC_ALL, 'ru_RU', 'ru_ru', 'ru');

/*echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>';
echo '<body>';*/

//conection:
$link = mysqli_connect("localhost","brokuser","brokpass239","brokbase") or die("Error " . mysqli_error($link));
$link->set_charset("utf8");
//echo $link->character_set_name();
//consultation:

if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}


$query = "SELECT * FROM br_district" or die("Error in the consult.." . mysqli_error($link));

//execute the query.

$result = $link->query($query);
//display information:

//var_dump($result->fetch_array(MYSQLI_ASSOC));
//echo "шгугашуыа";

while($row = $result->fetch_array(MYSQLI_ASSOC))
	$rows[] = $row;
foreach($rows as $row) {
	var_dump($row);
	echo "<br />";
	}

	/* free result set */
$result->close();
/* close connection */
$link->close();


/*<?php
    mysql_connect("localhost", "mysql_user", "mysql_password") or
        die("Could not connect: " . mysql_error());
    mysql_select_db("mydb");

    $result = mysql_query("SELECT id, name FROM mytable");

    while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
        printf ("ID: %s  Name: %s", $row[0], $row[1]);  
    }

    mysql_free_result($result);*/

	//echo '</body></html>';
	







?>