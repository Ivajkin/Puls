<?php
require_once 'namespace.php';

$header_h1 = '<h1 role="heading">как с <a href="callto:+79242005039"><span class="mcolor">нами</span></a> связаться</h1>';
$main_block .= 'content/contacts/main.html';
$more_block .= 'content/contacts/more.php';
$script_block .= 'content/contacts/code.js';
$css_lib = <<<EOT
                <link href="content/contacts/contacts.css" rel="stylesheet" />
                <link href="add/botdetect/lib/botdetect/public/lbd_layout.css" rel="stylesheet" />
EOT;
$script_lib = '';

  // PHP v5.2.0+ required
  session_start();

  require("add/botdetect/samples/requirements.php");

  // include BotDetect Captcha library files
  require("botdetect.php");

  // create & configure the Captcha object
  $ContactCaptcha = new Captcha("ContactCaptcha");
  $ContactCaptcha->UserInputID = "Captchacode";
    $ContactCaptcha->CodeLength = 3;
    $ContactCaptcha->ImageWidth = 150;
    $ContactCaptcha->ImageStyle = ImageStyle::CaughtInTheNet2;

    require("content/contacts/sendmail.php");

include $sitedir.'tpl/tpl.php';
?>