<?php
require_once 'php/conf.php';

require_once('db/bd_3.php');
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);

    $cssclass= 'dbadd';
    $main_block .= 'content/dbadd/main.php';
    $script_block .= 'content/dbadd/js.js';
    $css_lib = <<<EOT
        <link href="tpl/find/css.css" rel="stylesheet">
        <link href="content/dbadd/css.css" rel="stylesheet">
EOT;
    $script_lib = <<<EOT
        <script src="tpl/find/js.js"></script>
EOT;



    include 'tpl/main_smc.php';
?>