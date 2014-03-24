<?php

    mb_internal_encoding("utf-8");
    header('Content-Type: text/html; charset=utf-8');
    setlocale(LC_ALL, 'ru_RU');
    $sitedir= '';

    //Toggle invisible class for showing header.
    $main_block= $sitedir;
    $script_block = $sitedir;
    $css_lib = '';
    $script_lib = '';

    error_reporting(E_ALL/* ^ (E_NOTICE | E_WARNING)*/);
?>