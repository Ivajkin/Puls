<?php
if (isset($_SERVER['QUERY_STRING'])) @header('Location: '.$_SERVER['QUERY_STRING']);
?>