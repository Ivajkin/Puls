<?php
if ($_GET['randomId'] != "_9w65hHlXbvnsUEi_8Hp2kmiwhD3vSkAeZcrN2zon3eiyDtZHjFPkHSm2bWK26XW") {
    echo "Access Denied";
    exit();
}

// display the HTML code:
echo stripslashes($_POST['wproPreviewHTML']);

?>  
