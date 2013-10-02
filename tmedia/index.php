<?php
require_once 'namespace.php';

    $header_h1 = '<h1 role="heading">что такое <a href="callto:+79242005039"><span class="tcolor">techno</span><span class="mcolor">media</span></a></h1>';
    $main_block .= 'content/main/main.html';
    $more_block .= 'content/main/more.html';
    $script_block .= 'content/main/code.js';
    $css_lib = '<link href="content/main/main.css" rel="stylesheet">';
    $script_lib = '';

    include $sitedir.'tpl/tpl.php';
?>