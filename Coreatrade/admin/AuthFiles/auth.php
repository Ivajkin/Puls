<?php
	if (isset($_REQUEST[session_name()])){ 
		//echo 'Session name detected: '.session_name().'<br />';
		session_start();
	}
	if (isset($_SESSION['userKey']) AND $_SESSION['ip'] == $_SERVER['REMOTE_ADDR']){ 
		echo 'true';
		return true;
	}
	else {
		echo 'Please login';
		exit;
	}
?>