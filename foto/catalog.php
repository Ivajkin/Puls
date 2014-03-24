<?php
/**
 * Created by JetBrains PhpStorm.
 * User: leve_000
 * Date: 14.10.13
 * Time: 12:10
 * To change this template use File | Settings | File Templates.
 */
require_once 'namespace.php';

    $main_block .= 'content/allinfo/main.html';
    $script_block .= 'content/allinfo/js.js';
    $css_lib = '';
    $script_lib = '<script src="//api-maps.yandex.ru/2.1-dev/?lang=ru-RU&load=package.full"></script>';

    include $sitedir.'tpl/tpl.php';
?>