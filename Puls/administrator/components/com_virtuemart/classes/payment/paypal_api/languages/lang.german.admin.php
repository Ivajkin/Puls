<?php if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Restricted access' );

define( 'PAYPAL_API_PAYPAL_LOGOSRC', 'https://www.paypal.com/de_DE/DE/i/logo/lockbox_100x31.gif' );
define( 'PAYPAL_API_PAYPAL_LOGOCENTERURL', 'https://www.paypal-deutschland.de/logocenter/' );
define( 'PAYPAL_API_PAYPAL_LOGOCENTER', 'Besuchen Sie das PayPal Logocenter' );

//Backend Administration Language
define ('PAYPAL_API_TEXT_USERNAME', 'API-Benutzername:');
define ('PAYPAL_API_TEXT_USERNAME_EXPLAIN', 'Sie finden den API-Benutzernamen in Ihrem PayPal Konto unter "Mein Profil -> API-Zugriff -> API-Berechtigung anfordern -> Fordern Sie eine API-Signatur an". Bitte beachten Sie, dass PayPal Sandbox API-Zugangsdaten nicht im Live Betrieb genutzt werden k&ouml;nnen und vice versa!');
define ('PAYPAL_API_TEXT_PASSWORD', 'API-Passwort:');
define ('PAYPAL_API_TEXT_PASSWORD_EXPLAIN', 'Sie finden das API-Passwort in Ihrem PayPal Konto unter "Mein Profil -> API-Zugriff -> API-Berechtigung anfordern -> Fordern Sie eine API-Signatur an". Bitte beachten Sie, dass PayPal Sandbox API-Zugangsdaten nicht im Live Betrieb genutzt werden k&ouml;nnen und vice versa!');
define ('PAYPAL_API_TEXT_SIGNATURE', 'Unterschrift:');
define ('PAYPAL_API_TEXT_SIGNATURE_EXPLAIN', 'Sie finden die Unterschrift in Ihrem PayPal Konto unter "Mein Profil -> API-Zugriff -> API-Berechtigung anfordern -> Fordern Sie eine API-Signatur an". Bitte beachten Sie, dass PayPal Sandbox API-Zugangsdaten nicht im Live Betrieb genutzt werden k&ouml;nnen und vice versa!');

define ('PAYPAL_API_TEXT_STATUS_SUCCESS', 'Bestellstatus f&uuml;r erfolgreiche Transaktionen');
define ('PAYPAL_API_TEXT_STATUS_SUCCESS_EXPLAIN', 'W&auml;hlen Sie den Bestellstatus der f&uuml;r abgeschlossene Transaktionen vergeben werden soll.');
define ('PAYPAL_API_TEXT_STATUS_PENDING', 'Bestellstatus f&uuml;r offene Transaktionen');
define ('PAYPAL_API_TEXT_STATUS_PENDING_EXPLAIN', 'W&auml;hlen Sie den Bestellstatus der f&uuml;r offene Transaktionen vergeben werden soll.');
define ('PAYPAL_API_TEXT_STATUS_FAILED', 'Bestellstatus f&uuml;r fehlgeschlagene Transaktionen');
define ('PAYPAL_API_TEXT_STATUS_FAILED_EXPLAIN', 'W&auml;hlen Sie den Bestellstatus der f&uuml;r fehlgeschlagene Transaktionen vergeben werden soll.');

define ('PAYPAL_API_TEXT_YES', 'Ja');
define ('PAYPAL_API_TEXT_NO', 'Nein');

define ('PAYPAL_API_TEXT_IMAGE_URL', 'URL f&uuml;r Bild in der PayPal Kopfzeile');
define ('PAYPAL_API_TEXT_IMAGE_URL_EXPLAIN', 'Geben Sie ein Bild an, das maximal 750 x 90 Pixel gro&szlig; ist. Gr&ouml;&szlig;ere Bilder werden auf diese Gr&ouml;&szlig;e zugeschnitten. Das ausgew&auml;hlte Bild wird oben links auf der Zahlungsseite angezeigt. Wir empfehlen Ihnen, nur dann ein Bild anzugeben, wenn es auf einem sicheren Server (https) gespeichert ist.');
define ('PAYPAL_API_TEXT_IMAGE_URL_WARN', 'Das Bild in der PayPal Kopfzeile sollte sollte auf einem sicheren Server gespeichert (https) werden.');

define ('PAYPAL_API_TEXT_PAYMENTTYPE_SALE', 'Sofort');
define ('PAYPAL_API_TEXT_PAYMENTTYPE_AUTHORIZATION', 'verz&ouml;gert');
define ('PAYPAL_API_TEXT_PAYMENTTYPE', 'Geldeinzug');
define ('PAYPAL_API_TEXT_PAYMENTTYPE_EXPLAIN', 'Geldeinzug - Sofort = Geld wird automatisch Ihrem PayPal Konto gutgeschrieben; Geldeinzug - verz&ouml;gert = Zahlung wird autorisiert, Geldeinzug geschieht spaeter.');

define ('PAYPAL_API_TEXT_ENABLE_SANDBOX', 'Sandbox Modus?');
define ('PAYPAL_API_TEXT_ENABLE_SANDBOX_EXPLAIN', 'Nutzen Sie die PayPal Sandbox zum testen Ihrer Integration. Ein PayPal Sandbox Konto er&ouml;ffnen Sie unter developer.paypal.com');

define ('PAYPAL_API_TEXT_EXPRESS_ENABLE', 'PayPal Express aktivieren');
define ('PAYPAL_API_TEXT_EXPRESS_ENABLE_EXPLAIN', 'W&auml;hlen Sie PayPal Express um PayPal im Warenkorb zu aktivieren.');

define ('PAYPAL_API_TEXT_USE_PROXY','Use Proxy?');
define ('PAYPAL_API_TEXT_USE_PROXY_EXPLAIN','Should this request be sent through a proxy server? (Some hosting accounts, like GoDaddy, require the use of a proxy.)');
define ('PAYPAL_API_TEXT_PROXY_HOST','Proxy Host');
define ('PAYPAL_API_TEXT_PROXY_HOST_EXPLAIN','Enter the host IP of your proxy server.');
define ('PAYPAL_API_TEXT_PROXY_PORT','Proxy Port');
define ('PAYPAL_API_TEXT_PROXY_PORT_EXPLAIN','Enter the port number of your proxy server.');

define('PAYPAL_API_CVV_TEXT', 'Check for CVV?');
define('PAYPAL_API_CVV_TEXT_EXPLAIN', 'Only disable if you have your merchant account setup to not request the CVV code.');

define('PAYPAL_API_DEBUG_TEXT', 'Zeige PayPal Fehlermeldungen');
define('PAYPAL_API_DEBUG_TEXT_EXPLAIN', 'Zeigt alle PayPal Fehlermeldungen. Es wird empfohlen diese im Live Betrieb abzuschalten.');

define('PAYPAL_API_TEXT_REQCONFIRMSHIPPING', 'Nur best&auml;tige Lieferadressen zulassen?');
define('PAYPAL_API_TEXT_REQCONFIRMSHIPPING_EXPLAIN', 'W&auml;hlen Sie Ja um nur best&auml;tigte Lieferadressen zuzulassen.  Diese Funktion ist nicht f&uuml;r deutsche PayPal Konten verf&uuml;gbar.');

define('PAYPAL_API_TEXT_DIRECT_PAYMENT_ON', 'Enable Direct Payment?');
define('PAYPAL_API_TEXT_DIRECT_PAYMENT_EXPLAIN', 'Only enable if you can use PayPal\'s Websites Payments Pro.');

define('PAYPAL_API_TEXT_CURRENCY', 'PayPal Default Currency:');
define('PAYPAL_API_TEXT_CURRENCY_EXPLAIN', 'PayPal now allows Canada to use the NVP API. Now you can select between CAD or USD.');

define('PAYPAL_API_TEXT_LANGUAGE', 'Language:');
define('PAYPAL_API_TEXT_LANGUAGE_EXPLAIN', 'The Language File to Use For Backend and Error Messages');

define('PAYPAL_API_TEXT_CART_BUTTON', 'PayPal Express aktivieren');
define('PAYPAL_API_TEXT_CART_BUTTON_EXPLAIN', 'W&auml;hlen Sie Ja um PayPal Express im Warenkorb zu aktivieren.');

define('PAYPAL_API_TEXT_USE_CERTIFICATE', 'Use Certificate?');
define('PAYPAL_API_TEXT_USE_CERTIFICATE_EXPLAIN', 'Use API certificate security method instead of API Signature');
define('PAYPAL_API_TEXT_SET_CERTIFICATE', 'Certificate');
define('PAYPAL_API_TEXT_SET_CERTIFICATE_EXPLAIN', 'Optional - if you enter the name of a certificate file, it will be searched in the directory /administrator/components/cm_virtuemart/classes/payment/paypal_api/certificate.');

define('PAYPAL_API_TEXT_USE_SHIPPING', 'Lieferadresse verwenden?');
define('PAYPAL_API_TEXT_USE_SHIPPING_EXPLAIN', 'W&auml;hlen Sie Nein um keine Lieferadresse Informationen an PayPal zu senden. <strong>Dies sollte nur bei digitalen G&uuml;tern verwendet werden.</strong>');
?>