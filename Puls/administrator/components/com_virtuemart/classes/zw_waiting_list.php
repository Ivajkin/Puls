<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: zw_waiting_list.php 2492 2010-07-16 17:06:07Z soeren $
* @package VirtueMart
* @subpackage classes
* @copyright Copyright (C) 2004-2007 soeren - All rights reserved.
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
* This class is meant to manage Waiting Lists
* @copyright (C) devcompany.com  All rights reserved.
* @license MPL
*/
class vm_zw_waiting_list {

	/*
	** VALIDATION FUNCTIONS
	**
	*/

	function validate_add(&$d) {
		global $vmLogger, $VM_LANG;
		$db = new ps_DB;
		
		$q = "SELECT waiting_list_id from #__{vm}_waiting_list WHERE ";
		$q .= "notify_email='" . $d["notify_email"] . "' AND ";
		$q .= "product_id='" . $d["product_id"] . "' AND notified='0'";
		$db->query($q);
		if ($db->next_record()) {
			$vmLogger->err( $VM_LANG->_('VM_WAITING_LIST_ERR_ALREADY') );
			return False;
		}
		if (!$d["notify_email"]) {
			$vmLogger->err( $VM_LANG->_('VM_WAITING_LIST_ERR_EMAIL_ENTER') );
			return False;
		}
		if (!vmValidateEmail($d["notify_email"])) {
			$vmLogger->err( $VM_LANG->_('VM_WAITING_LIST_ERR_EMAIL_NOTVALID') );
			return False;
		}
		if (!$d["product_id"]) {
			$vmLogger->err( $VM_LANG->_('VM_WAITING_LIST_ERR_PRODUCT') );
			return False;
		}
		return True;
	}

	function validate_delete($d) {
		global $vmLogger, $VM_LANG;

		if (!$d["notify_email"]) {
			$vmLogger->err( $VM_LANG->_('VM_WAITING_LIST_DELETE_SELECT') );
			return False;
		}
		if (!vmValidateEmail($d["notify_email"])) {
			$vmLogger->err( $VM_LANG->_('VM_WAITING_LIST_ERR_EMAIL_ENTER') );
			return False;
		}
		if (!$d["product_id"]) {
			$vmLogger->err( $VM_LANG->_('VM_WAITING_LIST_DELETE_ERR_PRODUCT') );
			return False;
		}
		return True;
	}


	/**************************************************************************
	* name: add()
	* created by: mwattier
	* description: creates a new waiting list entry
	* parameters:
	* returns:
	**************************************************************************/
	function add(&$d) {
		global $auth;
		$db = new ps_DB;

		if (!$this->validate_add($d)) {
			return False;
		}
		$q = "INSERT INTO #__{vm}_waiting_list (product_id, user_id, notify_email)";
		$q .= " VALUES ('";
		$q .= $d["product_id"] . "','";
		$q .= $auth['user_id'] . "','";
		$q .= $d["notify_email"] . "')";
		$db->query($q);
		$db->next_record();
		return True;

	}

	/**************************************************************************
	* name: update()
	* created by: pablo
	* description: updates function information
	* parameters:
	* returns:
	**************************************************************************/
	function update($shopper_email,$product_id) {
		$dbu = new ps_DB;

		$qu = "UPDATE #__{vm}_waiting_list SET notified='1' WHERE ";
		$qu .= "notify_email='$shopper_email' AND ";
		$qu .= "product_id='$product_id'";
		$dbu->query($qu);
		return True;
	}

	/**************************************************************************
	* name: delete()
	* created by: pablo
	* description: Should delete a category and and categories under it.
	* parameters:
	* returns:
	**************************************************************************/
	function delete(&$d) {
		$db = new ps_DB;

		if (!$this->validate_delete($d)) {
			return False;
		}
		$q = "DELETE from #__{vm}_waiting_list where notify_email='" . $d["notify_email"] . "'";
		$q .= " AND product_id='" .$d["product_id"] ."'";
		$db->query($q);
		$db->next_record();
		return True;
		
	}
	/**************************************************************************
	* name: notify_list()
	* created by:
	* description: Will notify all people who have not been notified
	* parameters: takes the $product_id
	* returns: true
	**************************************************************************/
	function notify_list($product_id) {
		global $sess,  $mosConfig_fromname, $VM_LANG;
		
		$option = vmGet( $_REQUEST, 'option' );
		$product_id = intval($product_id);
		if ($product_id == 0) {
			return False;
		}

		$dbv = new ps_DB;
		$qt = "SELECT contact_email from #__{vm}_vendor ";
		$qt .= "WHERE vendor_id='1'";
		$dbv->query($qt);
		$dbv->next_record();
		$from_email = $dbv->f("contact_email");


		$db = new ps_DB;
		$q = 'SELECT notify_email FROM #__{vm}_waiting_list WHERE ';
		$q .= 'notified="0" AND product_id='.$product_id;
		$db->query($q);

		require_once( CLASSPATH. 'ps_product.php');
		$ps_product = new ps_product;

		while ($db->next_record()) {
			// get the product name for the e-mail
			$product_name = $ps_product->get_field($product_id, "product_name");

			// lets make the e-mail up from the info we have
			$notice_subject = sprintf($VM_LANG->_('PRODUCT_WAITING_LIST_EMAIL_SUBJECT'), $product_name);
			$flypage = $ps_product->get_flypage( $product_id );
			
			// now get the url information
			$url = URL . "index.php?page=shop.product_details&flypage=$flypage&product_id=$product_id&option=$option&Itemid=".$sess->getShopItemid();
			$notice_body = sprintf($VM_LANG->_('PRODUCT_WAITING_LIST_EMAIL_TEXT'), $product_name, $url);
			
			// send the e-mail
			$shopper_email = $db->f("notify_email");
			vmMail($from_email, $mosConfig_fromname, $shopper_email, $notice_subject, $notice_body, "");

			$this->update( $shopper_email, $product_id);

		}
		return True;
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
	class zw_waiting_list extends vm_zw_waiting_list {}
}
?>
