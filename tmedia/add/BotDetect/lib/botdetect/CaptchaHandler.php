<?php
session_start();

LBD_HttpHelper::FixEscapedQuerystrings();
LBD_HttpHelper::CheckForIgnoredRequests();


// There are several Captcha commands accessible through the Http interface;
// first we detect which of the valid commands is the current Http request for.
if (!array_key_exists('get', $_GET) || !LBD_StringHelper::HasValue($_GET['get'])) {
  LBD_HttpHelper::BadRequest('command');
}
$commandString = LBD_StringHelper::Normalize($_GET['get']);
$command = LBD_CaptchaHttpCommand::FromQuerystring($commandString);
switch ($command) {
  case LBD_CaptchaHttpCommand::GetImage:
    GetImage();
    break;
  case LBD_CaptchaHttpCommand::GetSound:
    GetSound();
    break;
  case LBD_CaptchaHttpCommand::GetValidationResult:
    GetValidationResult();
    break;
  default:
    LBD_HttpHelper::BadRequest('command');
    break;
}


// Returns the Captcha image binary data
function GetImage() {

  // saved data for the specified Captcha object in the application
  $captcha = GetCaptchaObject();
  if (is_null($captcha)) {
    LBD_HttpHelper::BadRequest('captcha');
  }

  // identifier of the particular Captcha object instance
  $instanceId = GetInstanceId();
  if (is_null($instanceId)) {
    LBD_HttpHelper::BadRequest('instance');
  }

  while (ob_get_length()) {
    ob_end_clean();
  }
  ob_start();
  try {
    // response headers
    LBD_HttpHelper::DisallowCache();

    // MIME type
    $mimeType = $captcha->ImageMimeType;
    header("Content-Type: {$mimeType}");

    // we don't support content chunking, since image files
    // are regenerated randomly on each request
    header('Accept-Ranges: none');

    // disallow audio file search engine indexing
    header('X-Robots-Tag: noindex, nofollow, noarchive, nosnippet');

    // image generation
    $rawImage = $captcha->GetImage($instanceId);

    // record generated Captcha code for validation
    $captcha->Save();

    $length = strlen($rawImage);
    header("Content-Length: {$length}");
    echo $rawImage;
  } catch (Exception $e) {
    header('Content-Type: text/plain');
    echo $e->getMessage();
  }
  ob_end_flush();
  exit;
}


function GetSound() {
  if (Detect_iOS_ChunkedRequest()) {
    // iPhone/iPad sound issues workaround:
    // we need a completely different Captcha sound
    // Http workflow
    Get_iOS_ChunkedSound();
  } else {
    // when javascript is disabled in the iOS browser, it won't be
    // detected using the above check; so this is the only point where
    // we can detect the first request after the sound icon is clicked
    // in the iOS browser with javascript disabled
    Clear_iOS_SoundData();

    GetNormalSound();
  }
}


// Returns the Captcha sound binary data
function GetNormalSound() {

  // saved data for the specified Captcha object in the application
  $captcha = GetCaptchaObject();
  if (is_null($captcha)) {
    LBD_HttpHelper::BadRequest('captcha');
  }

  // identifier of the particular Captcha object instance
  $instanceId = GetInstanceId();
  if (is_null($instanceId)) {
    LBD_HttpHelper::BadRequest('instance');
  }

  while (ob_get_length()) {
    ob_end_clean();
  }
  ob_start();
  try {
    // sound generation & raw bytes output
    $rawSound = $captcha->GetSound($instanceId);
    $length = strlen($rawSound);

    // response headers
    LBD_HttpHelper::SmartDisallowCache();

    // MIME type
    $mimeType = $captcha->SoundMimeType;
    header("Content-Type: {$mimeType}");
    header('Content-Transfer-Encoding: binary');

    if (!array_key_exists('d', $_GET)) { // javascript player not used, we send the file directly as a download
      $downloadId = LBD_CryptoHelper::GenerateGuid();
      header("Content-Disposition: attachment; filename=captcha_{$downloadId}.wav");
    }

    // we don't support content chunking, since audio files
    // are regenerated randomly on each request
    header('Accept-Ranges: none');

    // disallow audio file search engine indexing
    header('X-Robots-Tag: noindex, nofollow, noarchive, nosnippet');

    header("Content-Length: {$length}");
    echo $rawSound;
  } catch (Exception $e) {
    header('Content-Type: text/plain');
    echo $e->getMessage();
  }
  ob_end_flush();
  exit;
}


function Get_iOS_ChunkedSound() {
  // detect the first request after a new sound icon click, and clear any stored data
  // to avoid reusing the same sound endlessly within a session
  // javascript player adds a timestamp querystring param ("&d=..."), so it can be detected by it
  $isJavaScriptPlayerRequest = (array_key_exists('d', $_GET) && LBD_StringHelper::HasValue($_GET['d']));
  if ($isJavaScriptPlayerRequest) {
    // when javascript is enabled, we can detect the first request because the timestamp changed
    $soundClickId = LBD_StringHelper::Normalize($_GET['d']);
    $prevSoundClickId = LBD_Persistence_Load('prevSoundClickId');
    if (0 != strcasecmp($soundClickId, $prevSoundClickId)) {
      Clear_iOS_SoundData();
      LBD_Persistence_Save('prevSoundClickId', $soundClickId); // on first request, save for future checks
    }
  }

  // sound byte subset
  $range = GetSoundByteRange();
  $rangeStart = $range['start'];
  $rangeEnd = $range['end'];
  $rangeSize = $rangeEnd - $rangeStart;

  // full sound bytes
  $soundBytes = Get_iOS_SoundData();
  if (is_null($soundBytes)) { return; }

  $totalSize = strlen($soundBytes) - 1;

  // initial iOS 6.0.1 testing; leaving as fallback since we can't be sure it won't happen again:
  // we depend on observed behavior of invalid range requests to detect
  // end of sound playback, cleanup and tell AppleCoreMedia to stop requesting
  // invalid "bytes=rangeEnd-rangeEnd" ranges in an infinite(?) loop
  if ($rangeStart == $rangeEnd || $rangeEnd > $totalSize) {
    Clear_iOS_SoundData();
    LBD_HttpHelper::BadRequest('invalid byte range');
  }

  while (ob_get_length()) {
    ob_end_clean();
  }
  ob_start();
  try {

    // partial content response with the requested byte range
    header('HTTP/1.1 206 Partial Content');
    $mimeType = $captcha->SoundMimeType;
    header("Content-Type: {$mimeType}");
    header('Content-Transfer-Encoding: binary');
    header('X-Robots-Tag: noindex, nofollow, noarchive, nosnippet');
    header('Accept-Ranges: bytes');
    header("Content-Length: {$rangeSize}");
    header("Content-Range: bytes {$rangeStart}-{$rangeEnd}/{$totalSize}");

    if (!array_key_exists('d', $_GET)) { // javascript player not used, we send the file directly as a download
      $downloadId = LBD_CryptoHelper::GenerateGuid();
      header("Content-Disposition: attachment; filename=captcha_{$downloadId}.wav");
    }

    LBD_HttpHelper::SmartDisallowCache();

    $rangeBytes = substr($soundBytes, $rangeStart, $rangeSize);
    echo $rangeBytes;
  } catch (Exception $e) {
    header('Content-Type: text/plain');
    echo $e->getMessage();
  }
  ob_end_flush();
  exit;
}


// Instead of relying on unreliable user agent checks, we detect the iOS sound
// requests by the Http headers they will always contain
function Detect_iOS_ChunkedRequest() {
  $detected = false;
  if (array_key_exists('HTTP_X_PLAYBACK_SESSION_ID', $_SERVER) &&
      LBD_StringHelper::HasValue($_SERVER['HTTP_X_PLAYBACK_SESSION_ID']) &&
      array_key_exists('HTTP_RANGE', $_SERVER) &&
      LBD_StringHelper::HasValue($_SERVER['HTTP_RANGE'])) {
    $detected = true;
  }
  return $detected;
}


function GetSoundByteRange() {
  // chunked requests must include the desired byte range
  $rangeStr = $_SERVER['HTTP_RANGE'];
  if (!LBD_StringHelper::HasValue($rangeStr)) {
    return;
  }

  $matches = array();
  preg_match_all('/bytes=([0-9]+)-([0-9]+)/', $rangeStr, $matches);
  return array(
    'start' => (int) $matches[1][0],
    'end'   => (int) $matches[2][0]
  );
}


function Get_iOS_SoundData() {
  // since we need to keep the same Captcha sound across all chunked
  // requests, it is only generated when a new (non-duplicate) playback
  // session is started in a request
  $loaded = Load_iOS_SoundData();
  if (is_null($loaded)) {
    // generate sound file & store it for subsequent requests
    $soundBytes = Generate_iOS_SoundData();
    Save_iOS_SoundData($soundBytes);
    return $soundBytes;
  } else {
    // already have the sound file, must sent partial content
    return $loaded;
  }
}

function Generate_iOS_SoundData() {
  $captcha = GetCaptchaObject();
  if (is_null($captcha)) {
    LBD_HttpHelper::BadRequest('captcha');
  }

  $instanceId = GetInstanceId();
  if (is_null($instanceId)) {
    LBD_HttpHelper::BadRequest('instance');
  }

  $rawSound = $captcha->GetSound($instanceId);
  return $rawSound;
}

// we persist the chunked sound across requests in a temp file
function Save_iOS_SoundData($p_SoundBytes) {
  file_put_contents(GetCacheFilename(), $p_SoundBytes);
}

function Load_iOS_SoundData() {
  if (is_readable(GetCacheFilename())) {
    return file_get_contents(GetCacheFilename());
  }
  return null;
}

function Clear_iOS_SoundData() {
  if (is_readable(GetCacheFilename())) {
    unlink(GetCacheFilename());
  }
}

function GetCacheFilename() {
  return LBD_ServerHelper::CombinePaths(sys_get_temp_dir(), 'cached_iOS_SoundData' . session_id());
}


// Used for client-side validation, returns Captcha validation result as JSON
function GetValidationResult() {

  // saved data for the specified Captcha object in the application
  $captcha = GetCaptchaObject();
  if (is_null($captcha)) {
    LBD_HttpHelper::BadRequest('captcha');
  }

  // identifier of the particular Captcha object instance
  $instanceId = GetInstanceId();
  if (is_null($instanceId)) {
    LBD_HttpHelper::BadRequest('instance');
  }

  // code to validate
  $userInput = GetUserInput();

  while (ob_get_length()) {
    ob_end_clean();
  }
  ob_start();
  try {
    // response MIME type & headers
    header('Content-Type: text/javascript');
    header('X-Robots-Tag: noindex, nofollow, noarchive, nosnippet');

    // JSON-encoded validation result
    $result = false;
     if (isset($userInput) && (isset($instanceId))) {
      $result = $captcha->Validate($userInput, $instanceId, LBD_ValidationAttemptOrigin::Client);
      $captcha->Save();
    }
    $resultJson = GetJsonValidationResult($result);
    echo $resultJson;
  } catch (Exception $e) {
    header('Content-Type: text/plain');
    echo $e->getMessage();
  }
  ob_end_flush();
  exit;
}


// gets Captcha instance according to the CaptchaId passed in querystring
function GetCaptchaObject() {
  $captchaId = LBD_StringHelper::Normalize($_GET['c']);
  if (!LBD_StringHelper::HasValue($captchaId) ||
      !LBD_CaptchaBase::IsValidCaptchaId($captchaId)) {
    return;
  }

  $captcha = new LBD_CaptchaBase($captchaId);
  return $captcha;
}


// extract the exact Captcha code instance referenced by the request
function GetInstanceId() {
  $instanceId = LBD_StringHelper::Normalize($_GET['t']);
  if (!LBD_StringHelper::HasValue($instanceId) ||
      !LBD_CaptchaBase::IsValidInstanceId($instanceId)) {
    return;
  }
  return $instanceId;
}


// extract the user input Captcha code string from the Ajax validation request
function GetUserInput() {
  $input = null;

  if (isset($_GET['i'])) {
    // BotDetect built-in Ajax Captcha validation
    $input = LBD_StringHelper::Normalize($_GET['i']);
  } else {
    // jQuery validation support, the input key may be just about anything,
    // so we have to loop through fields and take the first unrecognized one
    $recognized = array('get', 'c', 't');
    foreach($_GET as $key => $value) {
      if (!in_array($key, $recognized)) {
        $input = $value;
        break;
      }
    }
  }

  return $input;
}

// encodes the Captcha validation result in a simple JSON wrapper
function GetJsonValidationResult($p_Result) {
  $resultStr = ($p_Result ? 'true': 'false');
  return $resultStr;
}

?>