<?php
	require "authenticator.php";
	//$pass = $_POST["pass"];
	//$login = $_POST["login"];
	$pass = "pass";
	$login = "login";
	//read pass and login from file
	$passFile = file_get_contents("passFile.php");
	$passData = json_decode($passFile);
	
	foreach($passData as $value)
		if(!strcmp($value->login,$login)){
			echo 'already exist';
			return false;
		}
	//$sertificateData = file_get_contents("certificates");
	//$sertificateList = json_decode($sertificateData);
	
	$cpass = crypt($pass, "$2a$07$SomeSaltWordsMakeItHarder$");
	//if(strcmp(crypt()))
	//if(!strcmp(crypt("pass", $cpass),$cpass))
	//if(!strcmp(crypt($login, $login),$clogin))

	$data = array(
		"login" => $login,
		"pass" => $cpass
	);

	$passData[] = $data;
	$json = json_encode($passData);
	file_put_contents("passFile.php",$json);
	echo 'true';
?>