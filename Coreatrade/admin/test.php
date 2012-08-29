<?php
	header("Content-type: text/html; charset=utf-8");
	//$action = $_POST["action"];
	//$action = 'logout';
	//$login = crypt($login, "$2a$07$SomeSaltWordsMakeItHarder$");
	
	if(isset($_GET['login'])){
		$pass = $_GET["pass"];
		$login = $_GET["login"];
		$cpass = crypt($pass, "$2a$07$SomeSaltWordsMakeItHarder$");
		
		$passFile = file_get_contents("AuthFiles/passFile.php");
		$passData = json_decode($passFile);
		foreach($passData as $key => $value){
			if(!strcmp($value->login,$login) && !strcmp(crypt($pass, $cpass),$value->pass)){
				session_start(); 
				echo $value->pass.'<br />';
				echo $cpass.'<br />';
				echo strcmp(crypt($pass, $cpass),$value->pass);
				$_SESSION['userKey'] = $key;
				$_SESSION['time'] = time();
				$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
				echo 'true';
				exit;
			}
			echo '<br />';
		}
		echo 'User not found';
		exit;
	}
?>