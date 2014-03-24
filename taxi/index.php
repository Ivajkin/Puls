<?php
/**
 * Created by JetBrains PhpStorm.
 * User: leve_000
 * Date: 14.10.13
 * Time: 12:10
 * To change this template use File | Settings | File Templates.
 */
require_once 'namespace.php';

    $main_block .= 'content/main/main.html';
    $script_block .= 'content/main/js.js';
    $css_lib = <<<EOT
                <link href="content/main/css.css" rel="stylesheet">
                <link href="add/jqueryui/css/ui-lightness/jquery-ui-1.10.3.custom.min.css" rel="stylesheet">
                <link href="add/chosen/chosen.css" rel="stylesheet">
EOT;
    $script_lib = <<<EOT
                <script src="js/jquery.maskedinput.min.js"></script>
                <script src="js/date.format.js"></script>
                <script src="add/jqueryui/js/jquery-ui-1.10.3.custom.min.js"></script>
                <script src="add/jqueryui/js/ru/jquery.ui.datepicker-ru.min.js"></script>
                <script src="add/chosen/chosen.jquery.min.js"></script>
EOT;

    include $sitedir.'tpl/tpl.php';
?>