<?php
require_once 'namespace.php';

    $header_h1 = '<h1 role="heading"><a href="callto:7890"><span class="tcolor">techno</span><span class="mcolor">media</span></a></h1>';
    $main_block .= 'content/about/about.html';
    $more_block .= 'content/about/about_more.html';
    $script_block .= 'content/about/code.js';
    $css_lib = '<link href="content/about/about.css" rel="stylesheet">';
    $script_lib = '';

    include $sitedir.'tpl/tpl.php';
?>