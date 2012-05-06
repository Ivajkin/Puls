<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/****************************************************
CallerService.php
****************************************************/

/**
  * hash_call: Function to perform the API call to PayPal using API signature
  * @methodName is name of API  method.
  * @nvpStr is nvp string.
  * returns an associtive array containing the response from the server.
*/
function hash_call($methodName,$nvpStr) {
	global $vmLogger;
    $API_UserName=PAYPAL_API_API_USERNAME;
    $API_Password=PAYPAL_API_API_PASSWORD;
    $API_Signature=PAYPAL_API_API_SIGNATURE;
	
    $version=PAYPAL_API_VERSION;
	
	$nvpreq="METHOD=".urlencode($methodName)."&VERSION=".urlencode($version)."&PWD=".urlencode($API_Password)."&USER=".urlencode($API_UserName);
	
	if(PAYPAL_API_CERTIFICATE != '' && file_exists( CLASSPATH.'payment/paypal_api/certificate/'.PAYPAL_API_CERTIFICATE )) {
		$tc = PAYPAL_API_CERTIFICATE;
		//Check to make sure we have a certificate file
		if(empty($tc) === false)
		{
			$certLoc = CLASSPATH.'payment/paypal_api/certificate/'.PAYPAL_API_CERTIFICATE;
			curl_setopt($ch, CURLOPT_SSLCERT, $certLoc);
		}	
	
		//Check for sandbox mode
		if(PAYPAL_API_DEBUG == '1') {
			$API_Endpoint = 'https://api.sandbox.paypal.com/nvp'; 
		}
		else {
			$API_Endpoint = 'https://api.paypal.com/nvp';
		}
	}
	else {
		//Check for sandbox mode
		if(PAYPAL_API_DEBUG == '1') {
			$API_Endpoint = 'https://api-3t.sandbox.paypal.com/nvp';
		}
		else {
			$API_Endpoint = 'https://api-3t.paypal.com/nvp';
		}
		$nvpreq .= "&SIGNATURE=".urlencode($API_Signature);
		
	}
	
	$nvpreq .= $nvpStr;
	
	//setting the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	//turning off the server and peer verification(TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_POST, 1);
	
	if(  VM_PROXY_URL != '' ) {
		curl_setopt ($ch, CURLOPT_PROXY, VM_PROXY_URL.":".VM_PROXY_PORT); 
	}
	
	//NVPRequest for submitting to server
	$nvpreq="METHOD=".urlencode($methodName)."&VERSION=".urlencode($version)."&PWD=".urlencode($API_Password)."&USER=".urlencode($API_UserName)."&SIGNATURE=".urlencode($API_Signature).$nvpStr;

	//setting the nvpreq as POST FIELD to curl
	curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpreq);

	//getting response from server
	$response = curl_exec($ch);
	
	//Write Logs for PayPal Certification
	//writeToLog($nvpreq, $response, $methodName);
	
	//convrting NVPResponse to an Associative Array
	$nvpResArray=deformatNVP($response);
	$nvpReqArray=deformatNVP($nvpreq);
	$_SESSION['nvpReqArray']=$nvpReqArray;

	if (curl_errno($ch)) {
		// moving to display page to display curl errors
		  $vmLogger->err( curl_errno($ch) .' - '.curl_error($ch) );
		  return false;
	 } else {
		//closing the curl
		curl_close($ch);
	 }

	return $nvpResArray;
}

/** This function will take NVPString and convert it to an Associative Array and it will decode the response.
  * It is usefull to search for a particular key and displaying arrays.
  * @nvpstr is NVPString.
  * @nvpArray is Associative Array.
  */
function deformatNVP($nvpstr) {

	$intial=0;
 	$nvpArray = array();


	while(strlen($nvpstr)){
		//postion of Key
		$keypos= strpos($nvpstr,'=');
		//position of value
		$valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);

		/*getting the Key and Value values and storing in a Associative Array*/
		$keyval=substr($nvpstr,$intial,$keypos);
		$valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
		//decoding the respose
		$nvpArray[urldecode($keyval)] =urldecode( $valval);
		$nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
     }
	return $nvpArray;
}

function writeToLog($request, $response, $type)
{
	file_put_contents('paypal_logs.txt', 'Type: '.$type.' Request ('.date('c').'): '.$request."\n", FILE_APPEND);
	file_put_contents('paypal_logs.txt', 'Type: '.$type.' Response ('.date('c').'): '.$response."\n", FILE_APPEND);
}
