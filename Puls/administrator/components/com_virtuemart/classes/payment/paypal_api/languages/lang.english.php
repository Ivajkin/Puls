<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Restricted access' );
//PayPal NVP Errors
	//Express Checkout Errors
	$nvp_error_10411 = 'Express Checkout Session has expired. Please, restart the checkout process.';
	$nvp_error_10415 = 'The Express Checkout Session has already been used. Please, restart the checkout process.';
	$nvp_error_10416 = 'Exceeded maximum number of processing attempts for Session. Please, restart the checkout process.';
	$nvp_error_10417 = 'PayPal cannot process the payment. Please, restart the checkout process and select a different method of payment.';
	$nvp_error_10422 = 'Invalid funding through PayPal. Please, restart the checkout process and select a different PayPal funding method.';
	$nvp_error_10445 = 'Cannot process the payment. Please, try again later.';
	
	//Direct Payment Errors
	$nvp_error_10502 = 'Payment could not be processed due to an Invalid Credit Card';
	$nvp_error_10504 = 'Payment could not be processed due to an Invalid Credit Card Verification Code';
	$nvp_error_10508 = 'Payment could not be processed due to an Invalid Expiration Date';
	$nvp_error_10510 = 'Payment could not be processed due to Credit Card Type Not Supported.';
	$nvp_error_10519 = 'Payment could not be processed due to an Invalid Credit Card';
	$nvp_error_10521 = 'Payment could not be processed due to an Invalid Credit Card';
	$nvp_error_10527 = 'Payment could not be processed due to an Invalid Credit Card';
	$nvp_error_10534 = 'Payment could not be processed. Credit Card has been restricted. Please contact PayPal.';
	$nvp_error_10535 = 'Payment could not be processed due to an Invalid Credit Card';
	$nvp_error_10541 = 'Payment could not be processed. Credit Card has been restricted. Please contact PayPal.';
	$nvp_error_10562 = 'Payment could not be processed due to an Invalid Credit Card Expiration Date';
	$nvp_error_10563 = 'Payment could not be processed due to an Invalid Credit Card Expiration Date';
	$nvp_error_10566 = 'Payment could not be processed due to Credit Card Type Not Supported.';
	$nvp_error_10567 = 'Payment could not be processed due to an Invalid Credit Card';
	$nvp_error_10748 = 'Payment could not be processed due to an Invalid Credit Card Verifcation Code';
	$nvp_error_10756 = 'Payment could not be processed. Billing Address does not Match Credit Card Billing Address.';
	$nvp_error_10759 = 'Payment could not be processed due to an Invalid Credit Card.';
	$nvp_error_15001 = 'Payment could not be processed due to Excessive Failuers with Invalid Credit Card.';
	$nvp_error_15004 = 'Payment could not be processed. Credit Card Verification Code Does Not Match Credit Card';
	$nvp_error_15006 = 'Payment could not be processed. Credit Card was Declined by Issuing Bank.';
	$nvp_error_15005 = 'Payment could not be processed. Credit Card type selected does not match Credit Card Number.';
	$nvp_error_15007 = 'Payment could not be processed. Credit Card was Declined by Issuing Bank.';

$nvp_error_no_transaction = 'Error - Paypal did not complete the transaction. Please try again in a little while.';
$nvp_error_invalid_CVV = "The CVV Number was invalid.";
$nvp_order_processed = 'Your order has been processed.';
$nvp_address_error = 'There was a problem with your address.';
$nvp_no_cert = 'You currently have the payment module set to certificate mode, but you did not provide a certificate.';

//Common Phrases
	$nvp_phrase_01 = 'Please Select Your Payment Method';
	
//Common Errors
	$nvp_common_01 = 'We could not access PayPal for the one of the following reasons:<br />';
	$nvp_common_02 = '1. Merchant account settings are not setup properly.<br />';
	$nvp_common_03 = '2. Administrator has not configured the backend properly.<br /><br />';
	$nvp_common_04 = '3. You PayPal Session has timed out.<br /><br />';
	$nvp_common_05 = '4. Paypal could not process the request.<br /><br />';
	
	$nvp_common_06 = 'Please, contact the website administrator with the information above.';
	$nvp_common_07 = "We could not connect to PayPal. Please go back a step and try again. If the problem persist, please contact the website administrator.";
	$nvp_common_08 = 'Unable to verify PayPal data. Contact website administrator with the following: Unable to get PayPal purchase info.';
	
	$nvp_common_09 = "There is no need to pay as your total is: $0.00 - Please make sure you have something in the cart and then try again";

	$nvp_common_010 = "Unable to Connect to Paypal";
	$nvp_common_011 = "Invalid Credit Card Number or Credit Card Verification Code.";
	
	$nvp_common_012 = "Error - Paypal did not complete the transaction";
	$nvp_common_013 = "We did not receive a proper PayPal token to verify your payment and order.";
	$nvp_common_014 = "We only accept purchases that have a Verified PayPal Account.";
	$nvp_common_015 = "Failed to process the order. Could not find user information. Please login or start the checkout process over.";
?>