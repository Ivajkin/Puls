<?php
	require_once('DataBaseFunctions.php');
	
	$dtype = file_get_contents($pathTojs.'DataBaseTemp/dtype.js');
	$dbrand = file_get_contents($pathTojs.'DataBaseTemp/dbrand.js');
	$ddata = file_get_contents($pathTojs.'DataBaseTemp/ddata.js');
	
	file_put_contents($pathTojs.'dtype.js', $dtype);
	file_put_contents($pathTojs.'dbrand.js', $dbrand);
	file_put_contents($pathTojs.'ddata.js', $ddata);
?>