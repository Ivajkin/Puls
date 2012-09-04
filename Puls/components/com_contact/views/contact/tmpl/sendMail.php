<?php
	function utf8($value)
	{
		return stripslashes(mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value));
	}
	$to = "serega.khv@gmail.com";
	$message = utf8($_POST["message"]);
	$name = $_POST["name"];
	$title = $_POST["subject"];
	$mail = trim($_POST["mail"]);
	$headers = "";
	$headers = "Content-type: text/plain; charset=utf-8"."\n";
	//$headers .= 'From: '.$mail."\n";
	$headers .= "From: \"$name\" <$mail>"."\n";
	$headers .= "Reply-To: $mail"."\n";
	$headers .= "X-Mailer: PHP/" . phpversion();
	mail($to, $title, $message, $headers ); 
	if($_POST["email_copy"])
		mail($mail, "Copy: ".$title, $message, $headers); 
?>