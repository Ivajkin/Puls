<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: ps_creditcard.php 1872 2009-08-24 14:59:44Z soeren_nb $
* @package VirtueMart
* @subpackage classes
* @copyright Copyright (C) 2004-2009 soeren - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/

/**
 * The class is is used to manage the CreditCards in your store.
 *
 */
class vm_ps_creditcard {

	/**
	 * Validates the input parameters onBeforeCreditCardAdd
	 *
	 * @param array $d
	 * @return boolean
	 */
	function validate_add($d) {
		global $vmLogger, $VM_LANG;
		$db = new ps_DB;

		if (!$d["creditcard_name"]) {
			$vmLogger->err( $VM_LANG->_('VM_CREDITCARD_ERR_NAME') );
			return False;
		}
		if (!$d["creditcard_code"]) {
			$vmLogger->err( $VM_LANG->_('VM_CREDITCARD_ERR_CODE') );
			return False;
		}

		$q = "SELECT count(*) as rowcnt FROM `#__{vm}_creditcard` WHERE";
		$q .= " creditcard_name='" .  $db->getEscaped($d["creditcard_name"]) . "' OR ";
		$q .= " creditcard_code='" .  $db->getEscaped( $d["creditcard_code"]) . "'";
		$db->query( $q );
		$db->next_record();
		if ($db->f("rowcnt") > 0) {
			$vmLogger->err( $VM_LANG->_('VM_CREDITCARD_EXISTS') );
			return False;
		}
		return True;
	}


	/**
	 * Validates the input parameters onBeforeCreditCardUpdate
	 *
	 * @param array $d
	 * @return boolean
	 */
	function validate_update($d) {
		global $VM_LANG;

		if (!$d["creditcard_name"]) {
			$GLOBALS['vmLogger']->err( $VM_LANG->_('VM_CREDITCARD_ERR_NAME') );
			return False;
		}
		if (!$d["creditcard_code"]) {
			$GLOBALS['vmLogger']->err( $VM_LANG->_('VM_CREDITCARD_ERR_CODE') );
			return False;
		}

		return true;
	}

	/**
	 * Validates the input parameters onBeforeCreditCardDelete
	 *
	 * @param array $d
	 * @return boolean
	 */
	function validate_delete($d) {
		global $VM_LANG;
		if (!$d["creditcard_id"]) {
			$GLOBALS['vmLogger']->err( $VM_LANG->_('VM_CREDITCARD_ERR_DELETE_SELECT') );
			return False;
		}
		else {
			return True;
		}
	}
	
	/**
	 * creates a new Credit Card Entry
	 * 
	 * @param array $d
	 * @return boolean
	 */
	function add(&$d) {
		global $VM_LANG;
		
		$hash_secret="VMisCool";
		$db = new ps_DB;
		$timestamp = time();

		if (!$this->validate_add($d)) {
			return False;
		}
		$fields = array( 'vendor_id' => $_SESSION["ps_vendor_id"],
					'creditcard_name' => vmGet($d,'creditcard_name'),
					'creditcard_code' => vmGet($d,'creditcard_code'),
		);
		$db->buildQuery('INSERT', '#__{vm}_creditcard', $fields );
		if( $db->query() ) {
			$GLOBALS['vmLogger']->info( $VM_LANG->_('VM_CREDITCARD_ADDED') );
			$_REQUEST['creditcard_id'] = $db->last_insert_id();
			return true;	
		}
		return false;
	}

	/**
	 * Updates a given Credit Card Record
	 *
	 * @param array $d
	 * @return boolean
	 */
	function update(&$d) {
		global $VM_LANG;
		
		$db = new ps_DB;
		$timestamp = time();

		if (!$this->validate_update($d)) {
			$d["error"] = $this->error;
			return False;
		}
		$fields = array( 'vendor_id' => $_SESSION["ps_vendor_id"],
					'creditcard_name' => vmGet($d,'creditcard_name'),
					'creditcard_code' => vmGet($d,'creditcard_code'),
		);
		$db->buildQuery('UPDATE', '#__{vm}_creditcard', $fields, 'WHERE creditcard_id='.(int)$d["creditcard_id"]);
		if( $db->query() ) {
			$GLOBALS['vmLogger']->info( $VM_LANG->_('VM_CREDITCARD_UPDATED') );
			$_REQUEST['creditcard_id'] = $db->last_insert_id();
			return true;	
		}
		return false;
	}

	/**
	* Controller for Deleting Credit Card Records.
	*/
	function delete(&$d) {

		$creditcard_id = $d["creditcard_id"];

		if( is_array( $creditcard_id)) {
			foreach( $creditcard_id as $creditcard) {
				if( !$this->delete_creditcard( $creditcard, $d ))
				return false;
			}
			return true;
		}
		else {
			return $this->delete_creditcard( $creditcard_id, $d );
		}
	}
	/**
	* Deletes a Credit Card Record.
	*/
	function delete_creditcard( $creditcard_id, &$d ) {
		global $db;

		if (!$this->validate_delete($d)) {
			$d["error"]=$this->error;
			return False;
		}
		$q = "DELETE FROM #__{vm}_creditcard WHERE creditcard_id=" . (int)$creditcard_id;
		$db->query($q);
		return True;
	}

	/**
	 * Creates a Checkbox-List with all Credit Card Names
	 *
	 * @param string $selected: a comma-delimited list of creditcard_IDs, assigned to this payment method
	 */
	function creditcard_checkboxes( $selected ) {

		if (!empty( $selected ))
		$selected_arr = explode( ",", $selected);
		else
		$selected_arr = Array();
		$db = new ps_DB;
		$q = "SELECT creditcard_name, creditcard_id FROM #__{vm}_creditcard WHERE vendor_id='".$_SESSION['ps_vendor_id']."'";
		$db->query( $q );
		$html = "";
		$i = 0;
		while( $db->next_record() ) {
			$html .= "<input type=\"checkbox\" name=\"creditcard[]\"  id=\"creditcard$i\" value=\"".$db->f("creditcard_id")."\" class=\"inputbox\" ";
			if (in_array($db->f("creditcard_id"), $selected_arr)) {
				$html .= "checked=\"checked\"";
			}
			$html .= "/>";
			$html .= "<label for=\"creditcard$i\">".$db->f("creditcard_name")."</label><br/>";
			$i++;
		}

		echo $html;
	}

	/**
	 * Creates a Drop Down - List of Credit Card Records
	 *
	 * @param int $payment_method_id
	 */
	function creditcard_selector( $payment_method_id="" ) {

		$db = new ps_DB;

		/*** Select all credit card records ***/
		if(empty($payment_method_id)) {
			$q = "SELECT creditcard_name, creditcard_id,creditcard_code FROM #__{vm}_creditcard WHERE vendor_id='".$_SESSION['ps_vendor_id']."'";
		}
		/*** Get only accepted credit cards records ***/
		else {
			$q = 'SELECT accepted_creditcards FROM #__{vm}_payment_method WHERE payment_method_id='.(int)$payment_method_id;
			$db->query( $q );
			$db->next_record();
			$cc_array = explode( ",", $db->f("accepted_creditcards"));
			$q = "SELECT creditcard_name,creditcard_id,creditcard_code FROM #__{vm}_creditcard WHERE vendor_id='".$_SESSION['ps_vendor_id']."' AND (";
			foreach ( $cc_array as $idx => $creditcard_id ) {
				$q .= "creditcard_id='$creditcard_id' ";
				if( $idx+1 < sizeof( $cc_array )) $q.= "OR ";
				else $q .= ")";
			}
		}
		$db->query( $q );

		while( $db->next_record() ) {
			$array[$db->f("creditcard_code")] = $db->f("creditcard_name");
		}
		echo ps_html::selectList('creditcard_code', '', $array );
	}

	/**
	 * Build a Credit Card list for each CreditCard Payment Method
	 * Uses JavsScript from mambojavascript: changeDynaList()
	 *
	 * @param ps_DB $db_cc
	 * @return string
	 */
	function creditcard_lists( &$db_cc ) {
		global $mainframe;
		if( vmIsJoomla('1.5') ) {
			$document = JFactory::getDocument();
			$document->addScript('includes/js/joomla.javascript.js');
		}
		$db = new ps_DB;

		$db_cc->next_record();
		// Build the Credit Card lists for each CreditCard Payment Method
		$script = "<script language=\"javascript\" type=\"text/javascript\">\n";
		$script .= "<!--\n";
		$script .= "var originalOrder = '1';\n";
		$script .= "var originalPos = '".$db_cc->f("payment_method_name")."';\n";
		$script .= "var orders = new Array();	// array in the format [key,value,text]\n";
		$i = 0;
		$db_cc->reset();

		while( $db_cc->next_record() ) {
			$accepted_creditcards = explode( ",", $db_cc->f("accepted_creditcards") );
			$cards = Array();
			foreach( $accepted_creditcards as $value ) {
				if( !empty( $value)) {
					$q = 'SELECT creditcard_code,creditcard_name FROM #__{vm}_creditcard WHERE creditcard_id='.(int)$value;
					$db->query( $q );
					$db->next_record();

					$cards[$db->f('creditcard_code')] = shopMakeHtmlSafe( $db->f('creditcard_name') );
				}
			}
			foreach( $cards as $code => $name ) {
				$script .= "orders[".$i++."] = new Array( '".addslashes($db_cc->f("payment_method_name"))."','$code','$name' );\n";
			}

			}
			$script .= "function changeCreditCardList() { \n";
			$script .= "var selected_payment = null;
      for (var i=0; i<document.adminForm.payment_method_id.length; i++)
         if (document.adminForm.payment_method_id[i].checked)
            selected_payment = document.adminForm.payment_method_id[i].id;\n";
			$script .="changeDynaList('creditcard_code',orders,selected_payment, originalPos, originalOrder);\n";
			$script .="}\n";
			$script .="//-->\n";
			$script .="</script>\n";
			$script .= '<noscript>'.ps_html::selectList('creditcard_code', key($cards), $cards ).'</noscript>';
			return $script;
		}
	}

	// Check if there is an extended class in the Themes and if it is allowed to use them
	// If the class is called outside Virtuemart, we have to make sure to load the settings
	// Thomas Kahl - Feb. 2009
	if (!defined('VM_ALLOW_EXTENDED_CLASSES') && file_exists(dirname(__FILE__).'/../virtuemart.cfg.php')) {
		include_once(dirname(__FILE__).'/../virtuemart.cfg.php');
	}
	// If settings are loaded, extended Classes are allowed and the class exisits...
	if (defined('VM_ALLOW_EXTENDED_CLASSES') && defined('VM_THEMEPATH') && VM_ALLOW_EXTENDED_CLASSES && file_exists(VM_THEMEPATH.'user_class/'.basename(__FILE__))) {
		// Load the theme-user_class as extended
		include_once(VM_THEMEPATH.'user_class/'.basename(__FILE__));
	} else {
		// Otherwise we have to use the original classname to extend the core-class
		class ps_creditcard extends vm_ps_creditcard {}
	}
?>
