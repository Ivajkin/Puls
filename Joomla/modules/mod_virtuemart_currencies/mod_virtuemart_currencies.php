<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
* Currency Selector Module
*
* NOTE: THIS MODULE REQUIRES THE VIRTUEMART COMPONENT!
/*
* @version $Id: mod_virtuemart_currencies.php 2586 2010-10-17 15:28:58Z zanardi $
* @package VirtueMart
* @subpackage modules
*
* @copyright (C) 2007-2009 soeren - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* VirtueMart is Free Software.
* VirtueMart comes with absolute no warranty.
*
* www.virtuemart.net
*/

global $mosConfig_absolute_path, $sess, $option, $page, $ps_html, $vendor_accepted_currencies;

// Load the virtuemart main parse code
if( file_exists(dirname(__FILE__).'/../../components/com_virtuemart/virtuemart_parser.php' )) {
	require_once( dirname(__FILE__).'/../../components/com_virtuemart/virtuemart_parser.php' );
} else {
	require_once( dirname(__FILE__).'/../components/com_virtuemart/virtuemart_parser.php' );
}

$text_before = $params->get( 'text_before', '');
$currencies = @explode( ',', $params->get( 'product_currency', $vendor_accepted_currencies ) );
$vendor_currencies = @explode( ',', $vendor_accepted_currencies );
if( count( $currencies ) < count( $vendor_currencies )) {
	$currencies = $vendor_currencies;
}
$db = new ps_DB();
$db->query( 'SELECT currency_id, currency_code, currency_name FROM `#__{vm}_currency` WHERE FIND_IN_SET(`currency_code`, \''.implode(',',$currencies).'\') ORDER BY `currency_name`' );#

//$currencies = explode( ',', $currencies );
//$db->query( 'SELECT currency_id, currency_code, currency_name FROM `#__{vm}_currency` ORDER BY `currency_name`' );
unset( $currencies );

while( $db->next_record()) {
	$currencies[$db->f('currency_code')] = $db->f('currency_name');
}

$sess = new ps_session;
    
?>
<!-- Currency Selector Module -->
<?php echo $text_before ?>
<form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="post">
	<br />
	<?php
	if( !empty( $_POST )) {
		foreach( $_POST as $key => $val ) {
			if( $key == 'product_currency' ) continue;
			if( is_array($val) ) {
				if( $key == 'checkout_this_step' ) {
					foreach( $val as $value ) {
						echo '<input type="hidden" name="'.$key.'[]" value="'.htmlspecialchars($value, ENT_QUOTES)."\" />\n";
					}
				} 
				continue;

			} 
			$key = htmlspecialchars($key, ENT_QUOTES);
			$val = htmlspecialchars($val, ENT_QUOTES);
			echo "<input type=\"hidden\" name=\"$key\" value=\"$val\" />\n";
		}
	}
	elseif( !empty( $_GET )) {
		
		foreach( $_GET as $key => $val ) {
			if( $key == 'product_currency' ) continue;
			if( is_array($val) ) {
				if( $key == 'checkout_this_step' ) {
					foreach( $val as $value ) {
						echo '<input type="hidden" name="'.$key.'[]" value="'.htmlspecialchars($value, ENT_QUOTES)."\" />\n";
					}
				} 
				continue;

			}
			
			$key = htmlspecialchars($key, ENT_QUOTES);
			echo "<input type=\"hidden\" name=\"$key\" value=\"".htmlspecialchars($val, ENT_QUOTES)."\" />\n";
		}
	}
	echo $ps_html->selectList( 'product_currency', $GLOBALS['product_currency'], $currencies, 1, '', 'style="width:130px;"' );
	?>
	<input type="hidden" name="do_coupon" value="yes" />
    <input class="button" type="submit" name="submit" value="<?php echo 'Change Currency' ?>" />
</form>
