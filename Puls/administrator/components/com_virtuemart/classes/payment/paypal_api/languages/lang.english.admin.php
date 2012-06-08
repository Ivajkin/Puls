<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Restricted access' );

define( 'PAYPAL_API_PAYPAL_LOGOSRC', 'https://www.paypal.com/en_US/i/logo/PayPal_mark_50x34.gif' );
define( 'PAYPAL_API_PAYPAL_LOGOCENTERURL', 'https://www.paypal.com/logocenter/' );
define( 'PAYPAL_API_PAYPAL_LOGOCENTER', 'Visit the PayPal Logocenter' );

//Backend Administration Language
define ('PAYPAL_API_TEXT_USERNAME', 'API Username:');
define ('PAYPAL_API_TEXT_USERNAME_EXPLAIN', 'You will find the API Username in your PayPal account at "Profile -> API Access -> Request API credentials -> Request API signature". Please note that Sandbox API credentials cannot be used in Live and vice versa!');
define ('PAYPAL_API_TEXT_PASSWORD', 'API Password:');
define ('PAYPAL_API_TEXT_PASSWORD_EXPLAIN', 'You will find the API Password in your PayPal account at "Profile -> API Access -> Request API credentials -> Request API signature". Please note that Sandbox API credentials cannot be used in Live and vice versa!');
define ('PAYPAL_API_TEXT_SIGNATURE', 'Signature:');
define ('PAYPAL_API_TEXT_SIGNATURE_EXPLAIN', 'You will find the Signature in your PayPal account at "Profile -> API Access -> Request API credentials -> Request API signature". Please note that Sandbox API credentials cannot be used in Live and vice versa!');

define ('PAYPAL_API_TEXT_STATUS_SUCCESS', 'Order status for successful transactions');
define ('PAYPAL_API_TEXT_STATUS_SUCCESS_EXPLAIN', 'Select the status you want the order set to for successful transactions.');
define ('PAYPAL_API_TEXT_STATUS_PENDING', 'Order status for pending transactions');
define ('PAYPAL_API_TEXT_STATUS_PENDING_EXPLAIN', 'Select the status you want the order set to for pending transactions.');
define ('PAYPAL_API_TEXT_STATUS_FAILED', 'Order status for failed transactions');
define ('PAYPAL_API_TEXT_STATUS_FAILED_EXPLAIN', 'Select the status you want the order set to for failed transactions.');

define ('PAYPAL_API_TEXT_YES', 'Yes');
define ('PAYPAL_API_TEXT_NO', 'No');

define ('PAYPAL_API_TEXT_IMAGE_URL', 'Header Image URL');
define ('PAYPAL_API_TEXT_IMAGE_URL_EXPLAIN', 'Please specify an image that is a maximum size of 750 pixels wide by 90 pixels high. Larger images will be cut to this size. The image you choose will appear at the top left of the payment page. We recommend providing an image only if it is stored on a secure (https) server.');
define ('PAYPAL_API_TEXT_IMAGE_URL_WARN', 'The URL of the image that will be displayed on the PayPal Pages should be a https URL.');

define ('PAYPAL_API_TEXT_PAYMENTTYPE_SALE', 'Sale');
define ('PAYPAL_API_TEXT_PAYMENTTYPE_AUTHORIZATION', 'Authorization');
define ('PAYPAL_API_TEXT_PAYMENTTYPE', 'Payment Type');
define ('PAYPAL_API_TEXT_PAYMENTTYPE_EXPLAIN', 'Payment Type - Sale=Instant Capture, Authorization = Authorize now, Capture Payment later');

define ('PAYPAL_API_TEXT_ENABLE_SANDBOX', 'Sandbox Mode?');
define ('PAYPAL_API_TEXT_ENABLE_SANDBOX_EXPLAIN', 'Use sandbox account for testing your integration. The PayPal Sandbox is accessable at developer.paypal.com');

define ('PAYPAL_API_TEXT_EXPRESS_ENABLE', 'Enable PayPal Express Checkout?');
define ('PAYPAL_API_TEXT_EXPRESS_ENABLE_EXPLAIN', 'Check to enable PayPal Express Checkout Button in your shopping cart.');

define ('PAYPAL_API_TEXT_USE_PROXY','Use Proxy?');
define ('PAYPAL_API_TEXT_USE_PROXY_EXPLAIN','Should this request be sent through a proxy server? (Some hosting accounts, like GoDaddy, require the use of a proxy.)');
define ('PAYPAL_API_TEXT_PROXY_HOST','Proxy Host');
define ('PAYPAL_API_TEXT_PROXY_HOST_EXPLAIN','Enter the host IP of your proxy server.');
define ('PAYPAL_API_TEXT_PROXY_PORT','Proxy Port');
define ('PAYPAL_API_TEXT_PROXY_PORT_EXPLAIN','Enter the port number of your proxy server.');

define('PAYPAL_API_CVV_TEXT', 'Check for CVV?');
define('PAYPAL_API_CVV_TEXT_EXPLAIN', 'Only disable if you have your merchant account setup to not request the CVV code.');

define('PAYPAL_API_DEBUG_TEXT', 'Show PayPal Errors');
define('PAYPAL_API_DEBUG_TEXT_EXPLAIN', 'Shows all PayPal errors at checkout. Recommended off when your site goes Live.');

define('PAYPAL_API_TEXT_REQCONFIRMSHIPPING', 'Require Confirmed Shipping Address?');
define('PAYPAL_API_TEXT_REQCONFIRMSHIPPING_EXPLAIN', 'Setting this to YES will make PayPal check for a confirmed address on file when using Express Checkout. This overrides your merchant account settings.');

define('PAYPAL_API_TEXT_DIRECT_PAYMENT_ON', 'Enable Direct Payment?');
define('PAYPAL_API_TEXT_DIRECT_PAYMENT_EXPLAIN', 'Only enable if you can use PayPal\'s Websites Payments Pro.');

define('PAYPAL_API_TEXT_CURRENCY', 'PayPal Default Currency:');
define('PAYPAL_API_TEXT_CURRENCY_EXPLAIN', 'PayPal now allows Canada to use the NVP API. Now you can select between CAD or USD.');

define('PAYPAL_API_TEXT_LANGUAGE', 'Language:');
define('PAYPAL_API_TEXT_LANGUAGE_EXPLAIN', 'The Language File to Use For Backend and Error Messages');

define('PAYPAL_API_TEXT_CART_BUTTON', 'Enable Express Checkout button?');
define('PAYPAL_API_TEXT_CART_BUTTON_EXPLAIN', 'Setting this to YES will enable PayPal Express Checkout Button in your shopping cart.');

define('PAYPAL_API_TEXT_USE_CERTIFICATE', 'Use Certificate?');
define('PAYPAL_API_TEXT_USE_CERTIFICATE_EXPLAIN', 'Use API certificate security method instead of API Signature');
define('PAYPAL_API_TEXT_SET_CERTIFICATE', 'Certificate');
define('PAYPAL_API_TEXT_SET_CERTIFICATE_EXPLAIN', 'Optional - if you enter the name of a certificate file, it will be searched in the directory /administrator/components/com_virtuemart/classes/payment/paypal_api/certificate.');

define('PAYPAL_API_TEXT_USE_SHIPPING', 'Use Shipping Information?');
define('PAYPAL_API_TEXT_USE_SHIPPING_EXPLAIN', 'If enabled, the payment module will use the shipping address provided from the User\'s Virtuemart Account. <strong>Turn this off if you plan to distribute your goods through digital means.</strong>');
?>
