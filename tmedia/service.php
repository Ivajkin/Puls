<?php
require_once 'namespace.php';

    $header_h1 = '<h1 role="heading"><a href="callto:7890"><span class="tcolor">techno</span><span class="mcolor">media</span></a></h1>';
    $main_block .= 'content/service/main.html';
    $more_block .= '';
    $script_block .= 'content/service/code.js';
    $css_lib = '<link href="add/bxslider/jquery.bxslider.css" rel="stylesheet">';
    $script_lib = <<<EOT
        <script src="add/bxslider/jquery.bxslider.min.js"></script>
EOT;
    include $sitedir.'tpl/tpl.php';
?>