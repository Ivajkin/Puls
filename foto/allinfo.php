<?php
/**
 * Created by JetBrains PhpStorm.
 * User: leve_000
 * Date: 14.10.13
 * Time: 12:10
 * To change this template use File | Settings | File Templates.
 */
require_once 'namespace.php';

    $main_block .= 'content/catalog/main.html';
    $script_block .= 'js/js.js';
    $css_lib = <<<EOT
                    <link href="add/prettyPhoto/prettyPhoto.css" rel="stylesheet">
                    <!--<link href="add/prettyPhoto/hoverBox.css" rel="stylesheet">-->
EOT;
    $script_lib = '<script src="add/prettyPhoto/jquery.prettyPhoto.js"></script>';

    include $sitedir.'tpl/tpl.php';
?>