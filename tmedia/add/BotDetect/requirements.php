<?php 
  // check required libraries / extensions: gdlib & mbstring
  $LBD_Error = false;
  $LBD_ErrorMsg = '';
  if(!function_exists('mb_strlen')) {
    $LBD_Error = true;
    $LBD_ErrorMsg .= '<p>ERROR: BotDetect requires the <code>mbstring</code> module. You can read more about installing/enabling it at: <a href="http://php.net/manual/en/book.mbstring.php" target="_blank">http://php.net/manual/en/book.mbstring.php</a>.</p>';
  }
  if(!function_exists('imagecreatetruecolor')) {
    $LBD_Error = true;
    $LBD_ErrorMsg .= '<p>ERROR: BotDetect requires the GD library. You can read more about installing/enabling it at: <a href="http://php.net/manual/en/book.image.php" target="_blank">http://php.net/manual/en/book.image.php</a>.</p>';
  }
  if ($LBD_Error) {
    echo $LBD_ErrorMsg;
    exit;
  }
?>