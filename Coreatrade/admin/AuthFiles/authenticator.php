<?php
	header("Content-type: text/html; charset=utf-8");
	//$action = $_POST["action"];
	//$action = 'logout';
	//$login = crypt($login, "$2a$07$SomeSaltWordsMakeItHarder$");
	
	if(isset($_POST['login'])){
		$pass = $_POST["pass"];
		$login = $_POST["login"];
		//$pass = 'pass';
		//$login = 'login';
		$cpass = crypt($pass, "$2a$07$SomeSaltWordsMakeItHarder$");
		
		$passFile = file_get_contents("passFile.php");
		$passData = json_decode($passFile);
		foreach($passData as $key => $value){
			if(!strcmp($value->login,$login) && !strcmp(crypt($pass, $value->pass),$cpass)){
				session_start(); 
				$_SESSION['userKey'] = $key;
				$_SESSION['time'] = time();
				$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
				echo 'true';
				exit;
			}
		}
		echo 'User not found';
		exit;
	}
	if($_POST["action"] == 'logout'){
		session_start();
		session_unset();
		session_destroy();
		echo 'logout';
		exit;
	}
	if (isset($_REQUEST[session_name()])){ 
		//echo 'Session name detected: '.session_name().'<br />';
		session_start();
	}
	if (isset($_SESSION['userKey']) AND $_SESSION['ip'] == $_SERVER['REMOTE_ADDR']){ 
		echo 'true';
		if(isset($_GET['address']))
			header("Location: ".$_GET['address']);
		return true;
	}
	else {
		echo 'Please login';
		header("Location: http://coreatrade.com/admin/");
		exit;
	}
?>