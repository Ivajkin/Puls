<?php

/*function utf8mail($to,$s,$body,$from_name="x",$from_a = "info@x.com", $reply="info@x.com")
{
    $s= "=?utf-8?b?".base64_encode($s)."?=";
    $headers = "MIME-Version: 1.0\r\n";
    $headers.= "From: =?utf-8?b?".base64_encode($from_name)."?= <".$from_a.">\r\n";
    $headers.= "Content-Type: text/plain;charset=utf-8\r\n";
    //$headers.= "Reply-To: $reply\r\n";
    $headers.= "X-Mailer: PHP/" . phpversion();
    return mail($to, $s, $body, $headers);
}*/
function mail_utf8($to, $subject = '(No subject)', $message = '', $from) {
    $header = 'MIME-Version: 1.0' . "\n" . 'Content-type: text/plain; charset=UTF-8'
        . "\n" . 'From: Yourname <' . $from . ">\n";
    mail($to, '=?UTF-8?B?'.base64_encode($subject).'?=', $message, $header);
}
// we are processing a valid submited form
function sendMessage($name, $email, $phone, $ckbox, $message) {
    // TODO: send email only after configuring your email server settings

    $to = "info@tmedia.pro";
    //$res= preg_match('/(?=@).*/i',$to);
    /*$cod_page= '';
    if ($res[0] == '@mail.ru' || $res[0] == '@inbox.ru' || $res[0] == '@list.ru' || $res[0] == '@bk.ru') {
        $cod_page= 'windows-1251';
    } else {
        $cod_page= 'utf-8';
    }*/
    $subject = "=?utf-8?b?".base64_encode("Новая заявка от tmedia.pro")."?=";

    $headers = "MIME-Version: 1.0\r\n";
    $headers.= "From: =?utf-8?b?".base64_encode($name)."?= <".$email.">\r\n";
    $headers.= "Content-Type: text/plain;charset=windows-1251\r\n";
    //$headers.= "Reply-To: $reply\r\n";
    $headers.= "X-Mailer: PHP/" . phpversion();

    // создаем наше сообщение
    $body = 'Имя отправителя: '.$name."\n\r".
        'Контактный email: '.$email."\n\r".
        'Контактный телефон: '.$phone."\n\r".
        'Интересующие услуги:'."\n\r";
    $i=0;
    while ($ckbox[$i]) $body.= "\t\t*  ".$ckbox[$i++]."\n\r";
    $body.= "\n\r\n\r\n\r".$message;
    //$body = wordwrap($body, 70);

    return mail($to, $subject, iconv('utf-8', 'windows-1251//IGNORE', $body), $headers);

}

// form a proper validation response to be assigned to a element
// validation response to the field is an array of following:
// "isValid" - status
// "validContent" - optionaly filtered content
// "validationMessage" - message for the user
// Note that both valid and non-valid messages can be supplied.
// This array format is a convention between both serverside methods
// and clientside JS methods.
function setElementValidationResult($status, $validContent = null, $validationMessage = null) {
    $result['isValid'] = $status;
    if ($status && $validContent) $result['validContent'] = $validContent;
    if ($validationMessage) $result['validationMessage'] = $validationMessage;

    return $result;
}

// validate the Captcha
function ValidateCaptchacode($code) {
    global $ContactCaptcha;
    /*$ContactCaptcha = new Captcha("ContactCaptcha");
    $ContactCaptcha->UserInputID = "captchacode";*/
    global $request;
    $instanceId = $request["CaptchaInstanceId"];
    // We want to check if the user already solved the Captcha for this message
    $isHuman = $ContactCaptcha->IsSolved;

    /*=====================$isHuman = false for prevent chrome cookie policy=========================
=======================================================================
*/
    if (!$isHuman) {
        // Validate the captcha
        // Both the user entered $code and $instanceId are used.
        $isHuman = $ContactCaptcha->Validate($code, $instanceId);
    }
    if ($isHuman === true) {
        return setElementValidationResult(true, null, "Solved!");
    } else {
        return setElementValidationResult(false, null, "Please retype the code.");
    }
}

// name validation
function ValidateName($name) {
    $name = substr(htmlspecialchars(trim($name)), 0, 100);
    if (strlen($name) > 2) {
        return setElementValidationResult(true, $name);
    } else {
        return setElementValidationResult(false, $name, "Please enter your name.");
    };
}

// email validaton
function ValidateEmail($email) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email= substr(htmlspecialchars(trim($email)), 0, 50);
        return setElementValidationResult(true, $email);
    } else {
        return setElementValidationResult(false, $email, "This email is not valid.");
    }
}

// email Phone
function ValidatePhone($phone) {
    $phone = htmlspecialchars(trim($phone));
    $lenstd= strlen("+7 4212-456-789");
    $len= strlen($phone);
    if ($len > $lenstd || preg_match("/[^+0-9 -]+/i",$phone))
        return setElementValidationResult(false, $phone, "Please enter your phone.");
    else
        return setElementValidationResult(true, $phone);
}

// message validation
function ValidateMessage($message) {
    $message = substr(htmlspecialchars(trim($message)), 0, 1000000);
    $headerInjection = preg_match("/(bcc:|cc:|content\-type:)/i", $message);
    if (!strlen($message))  return setElementValidationResult(false, $message, "Please renter your message.");
    if (!$headerInjection) {
        return setElementValidationResult(true, $message);
    } else {
        return setElementValidationResult(false, $message, "Please renter your message.");
    }
}


// remember user input if validation fails
function getValue($fieldName) {
    $value = '';
    if (isset($_REQUEST[$fieldName])) {
        $value = $_REQUEST[$fieldName];
    }
    return $value;
}


// Validation message helper function used in HTML
// the validation result is stored in a global array
function showValidationMessage($element) {
    global $validationResult;

    $message = "";
    $messageClass[] = "validatorMessage";

    if (is_array($validationResult) && array_key_exists($element, $validationResult)) {
        $elementStatus = $validationResult[$element];

        if ($elementStatus['isValid'] == false) {
            $messageClass[] = "incorrect";
        } elseif ($elementStatus['isValid'] == true) {
            $messageClass[] = "correct";
        }

        if (array_key_exists('validationMessage', $elementStatus) && $elementStatus['validationMessage'] != null) {
            $message = $elementStatus['validationMessage'];
        }
    }

    $validator = $element . "ValidatorMessage";
    $messageClass = implode(" ", $messageClass);

    $messageHtml = '<span class="' . $messageClass . '" id="' . $validator . '">' . $message . '</span>';

    return $messageHtml;
}



// An array of validation responses
$validationResult = array();

// Type of request we are interested in. $_POST, $_GET or both - $_REQUEST
$request = $_REQUEST;
if (count($request)) {
    foreach($request as $formItem => $value) {
        $validationMethod = "Validate" . ucfirst($formItem);
        if (is_callable($validationMethod)) {
            // This is an array of validation result arrays for each field
            // see setElementValidationResponse() for more details.
            $validationResult[$formItem] = call_user_func($validationMethod, $value);
        }
    }

    // Total form validation result
    $isFormValid = true;
    foreach($validationResult as $formItem){
        $isFormValid = $isFormValid && $formItem['isValid'];
    }

    // Respond according to results and request type

    // Regular response to POST form submission

    // We want to make sure our Captcha is solved before continuing
    if (!$isFormValid || !$ContactCaptcha->IsSolved) {
        // Form validation failed, show our error message
        $validationResult['Form'] = setElementValidationResult(false, null, "Please review your input.");
    } else {
        // We send the message with content from the validator
        // Additional sanitization should be implemented along with the validation
        $isSent = sendMessage($validationResult['Name']['validContent'], $validationResult['Email']['validContent'],  $validationResult['Phone']['validContent'], $request['ckbox'], $validationResult['Message']['validContent']);
        if ($isSent === true) {
            // each message requires a new Captcha challenge
            $ContactCaptcha->Reset();
            $validationResult['Form'] = setElementValidationResult(true, null, 'Your message was sent.');
        } else {
            $validationResult['Form'] = setElementValidationResult(false, null, "The server had problems sending your message.");
        }
    }

    if (isset($_GET['AJAX']) && $_GET['AJAX'] == 1) {
    // JSON response when AJAX parameter is passed
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');

        echo json_encode($validationResult);
        // Terminate further output after JSON
        exit;
    }
}
?>