<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

// Define a destination
$targetFolder = '../images/catalog'; // Relative to the root and should match the upload folder in the uploader script

if (file_exists($targetFolder . '/' . $_POST['filename'])) {
	echo 1;
} else {
	echo 0;
}
?>