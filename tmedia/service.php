<?php
require_once 'namespace.php';

    $header_h1 = '<h1 role="heading"><a href="callto:7890"><span class="tcolor">techno</span><span class="mcolor">media</span></a></h1>';
    $main_block .= 'content/service/main.html';
    $more_block .= 'content/service/more.php';
    $script_block .= 'content/service/code.js';
    $css_lib = <<<EOT
                <link href="content/service/serv.css" rel="stylesheet" />
                <link href="add/botdetect/lib/botdetect/public/lbd_layout.css" rel="stylesheet" />
EOT;
    $script_lib = <<<EOT
                <script src="js/jquery.maskedinput.min.js"></script>
                <script src="js/jquery.transit.min.js"></script>
EOT;

// PHP v5.2.0+ required
session_start();

require("add/botdetect/samples/requirements.php");

// include BotDetect Captcha library files
require("botdetect.php");

// create & configure the Captcha object
$ContactCaptcha = new Captcha("ContactCaptcha");
$ContactCaptcha->UserInputID = "captchacode";
$ContactCaptcha->CodeLength = 6;
$ContactCaptcha->ImageWidth = 150;
$ContactCaptcha->ImageStyle = ImageStyle::CaughtInTheNet2;

require("content/service/sendmail.php");

    include $sitedir.'tpl/tpl.php';
?>