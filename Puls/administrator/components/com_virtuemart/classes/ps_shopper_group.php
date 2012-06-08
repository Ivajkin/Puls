<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: ps_shopper_group.php 1660 2009-02-22 17:05:02Z tkahl $
* @package VirtueMart
* @subpackage classes
* @copyright Copyright (C) 2004-2008 soeren - All rights reserved.
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
 * VirtueMart Shopper Group Handler
 *
 */
class vm_ps_shopper_group extends vmAbstractObject  {
	
	var $key = 'shopper_group_id';
	var $_required_fields = array('shopper_group_name');
	var $_table_name = '#__{vm}_shopper_group';
	
	/**
	 * Validates the Input Parameters onBeforeShopperGroupAdd
	 *
	 * @param array $d
	 * @return boolean
	 */
	function validate_add(&$d) {
		global $VM_LANG;
		
		$db = new ps_DB;
		$ps_vendor_id = $_SESSION["ps_vendor_id"];

		if (empty($d["shopper_group_name"])) {
			$GLOBALS['vmLogger']->err($VM_LANG->_('SHOPPER_GROUP_MISSING_NAME'));
			return False;
		}
		else {
			$q = "SELECT COUNT(*) as num_rows FROM #__{vm}_shopper_group";
			$q .= " WHERE shopper_group_name='" .$db->getEscaped(vmGet($d,'shopper_group_name')) . "'";
			$q .= " AND vendor_id='" . $ps_vendor_id . "'";

			$db->query($q);
			$db->next_record();
			if ($db->f("num_rows") > 0) {
				$GLOBALS['vmLogger']->err($VM_LANG->_('SHOPPER_GROUP_ALREADY_EXISTS'));
				return False;
			}
		}
		
		if (empty($d["shopper_group_discount"])) {
			$d["shopper_group_discount"] = 0;
		}

		$d["show_price_including_tax"] = isset( $d["show_price_including_tax"] ) ? $d["show_price_including_tax"] : 0;
		
		return True;
	}

	/**
	 * Validates the Input Parameters onBeforeShopperGroupUpdate
	 *
	 * @param array $d
	 * @return boolean
	 */
	function validate_update(&$d) {
		global $VM_LANG;
		
		if (!$d["shopper_group_name"]) {
			$GLOBALS['vmLogger']->err($VM_LANG->_('SHOPPER_GROUP_MISSING_NAME'));
			return False;
		}
		if (empty($d["shopper_group_discount"])) {
			$d["shopper_group_discount"] = 0;
		}

		$d["show_price_including_tax"] = isset( $d["show_price_including_tax"] ) ? $d["show_price_including_tax"] : 0;

		return True;
	}

	/**
	 * Validates the Input Parameters onBeforeShopperGroupDelete
	 *
	 * @param array $d
	 * @return boolean
	 */
	function validate_delete( $shopper_group_id, &$d) {
		global $VM_LANG;
		
		$db = new ps_DB;
		$shopper_group_id = intval( $shopper_group_id );
		if (empty($shopper_group_id)) {
			$GLOBALS['vmLogger']->err($VM_LANG->_('SHOPPER_GROUP_DELETE_SELECT'));
			return False;
		}
		// Check if the Shopper Group still has Payment Methods assigned to it
		$db->query( 'SELECT payment_method_id FROM #__{vm}_payment_method WHERE shopper_group_id='.$shopper_group_id);
		if( $db->next_record()) {			
			$GLOBALS['vmLogger']->err(str_replace('{id}',$shopper_group_id,$VM_LANG->_('SHOPPER_GROUP_DELETE_PAYMENT_METHODS_ASS')));
			return False;
		}
		// Check if there are Users in this Shopper Group
		$db->query( 'SELECT user_id FROM #__{vm}_shopper_vendor_xref WHERE shopper_group_id='.$shopper_group_id);
		if( $db->next_record()) {			
			$GLOBALS['vmLogger']->err(str_replace('{id}',$shopper_group_id,$VM_LANG->_('SHOPPER_GROUP_DELETE_USERS_ASS')));
			return False;
		}
		
		$q = 'SELECT shopper_group_id FROM #__{vm}_shopper_group WHERE shopper_group_id='. $shopper_group_id
					. " AND `default`='1'";
		$db->query($q);
		if ($db->next_record()) {
			$GLOBALS['vmLogger']->err($VM_LANG->_('SHOPPER_GROUP_DELETE_DEFAULT'));
			return False;
		}

		return True;
	}

	/**
	 * Adds a new Shopper Group
	 *
	 * @param array $d
	 * @return boolean
	 */
	function add(&$d) {
		global $perm, $vmLogger, $VM_LANG;
		
		$hash_secret = "virtuemart";
		if( $perm->check( "admin" ) ) {
			$vendor_id = $d["vendor_id"];
		}
		else {
			$vendor_id = $_SESSION["ps_vendor_id"];
		}

		$db = new ps_DB;
		$timestamp = time();
		$default = @$d["default"]=="1" ? "1" : "0";

		if (!$this->validate_add($d)) {
			return False;
		}
		$user_id=md5(uniqid($hash_secret));
		$fields = array('vendor_id' => $vendor_id,
						'shopper_group_name' => $d["shopper_group_name"],
						'shopper_group_desc' => $d["shopper_group_desc"],
						'shopper_group_discount' => $d["shopper_group_discount"],
						'show_price_including_tax' => $d["show_price_including_tax"],
						'default' => $default
					);
		$db->buildQuery( 'INSERT', '#__{vm}_shopper_group', $fields );
		if( $db->query() !== false ) {
			$shopper_group_id = $db->last_insert_id();
			vmRequest::setVar( 'shopper_group_id', $shopper_group_id );
			$vmLogger->info($VM_LANG->_('SHOPPER_GROUP_ADDED'));
			// Set all other shopper groups to be non-default, if this new shopper group shall be "default"	
			if ($default == "1") {
				$q = "UPDATE #__{vm}_shopper_group ";
				$q .= "SET `default`=0 ";
				$q .= "WHERE shopper_group_id !=" . $shopper_group_id;
				$q .= " AND vendor_id =$vendor_id";
				$db->query($q);
				$db->next_record();
			}
			return $_REQUEST['shopper_group_id'];
		}
		$vmLogger->err($VM_LANG->_('SHOPPER_GROUP_ADD_FAILED'));
		return false;
	}

	/**
	 * Updates an existing Shopper Group
	 *
	 * @param array $d
	 * @return boolean
	 */
	function update($d) {
		global $perm, $VM_LANG;

		if( $perm->check( "admin" ) ) {
			$vendor_id = $d["vendor_id"];
		}
		else {
			$vendor_id = $_SESSION["ps_vendor_id"];
		}
		$db = new ps_DB;
		
		$default = @$d["default"]=="1" ? "1" : "0";

		if (!$this->validate_update($d)) {
			return false;
		}
		$fields = array('vendor_id' => $vendor_id,
						'shopper_group_name' => $d["shopper_group_name"],
						'shopper_group_desc' => $d["shopper_group_desc"],
						'shopper_group_discount' => $d["shopper_group_discount"],
						'show_price_including_tax' => $d["show_price_including_tax"],
						'default' => $default
					);
		$db->buildQuery( 'UPDATE', '#__{vm}_shopper_group', $fields, 'WHERE shopper_group_id=' . (int)$d["shopper_group_id"] );
		if( $db->query() ) {
			$GLOBALS['vmLogger']->info($VM_LANG->_('SHOPPER_GROUP_UPDATED'));
			
			if ($default == "1") {
				$q = "UPDATE #__{vm}_shopper_group ";
				$q .= "SET `default`=0 ";
				$q .= "WHERE shopper_group_id !=" . $d["shopper_group_id"];
				$q .= " AND vendor_id =$vendor_id";
				$db->query($q);
				$db->next_record();
			}
			return true;
		}
		$GLOBALS['vmLogger']->err($VM_LANG->_('SHOPPER_GROUP_UPDATE_FAILED'));
		return false;
	}

	/**
	* Controller for Deleting Records.
	*/
	function delete(&$d) {

		$record_id = $d["shopper_group_id"];

		if( is_array( $record_id)) {
			foreach( $record_id as $record) {
				if( !$this->delete_record( $record, $d ))
				return false;
			}
			return true;
		}
		else {
			return $this->delete_record( $record_id, $d );
		}
	}
	/**
	* Deletes one Record.
	*/
	function delete_record( $record_id, &$d ) {
		global $db;
		$record_id = intval( $record_id );
		if ($this->validate_delete( $record_id, $d)) {
			$q = "DELETE FROM #__{vm}_shopper_group WHERE shopper_group_id=$record_id";
			$db->query($q);
			$db->next_record();

			$q = "DELETE FROM #__{vm}_shopper_vendor_xref WHERE shopper_group_id=$record_id";
			$db->query($q);
			$db->next_record();

			$q = "DELETE FROM #__{vm}_product_price WHERE shopper_group_id=$record_id";
			$db->query($q);
			$db->next_record();
			return True;
		}
		else {
			return False;
		}
	}

	/**
	 * Creates a Drop Down list of available Shopper Groups
	 *
	 * @param string $name
	 * @param int $shopper_group_id
	 * @param string $extra
	 * @return string
	 */
	function list_shopper_groups($name,$shopper_group_id='0', $extra='') {
		$ps_vendor_id = $_SESSION["ps_vendor_id"];
		global $perm;
		$db = new ps_DB;

		if( !$perm->check("admin")) {
			$q  = "SELECT shopper_group_id,shopper_group_name,vendor_id,'' AS vendor_name FROM #__{vm}_shopper_group ";
			$q .= "WHERE vendor_id = '$ps_vendor_id' ";
		}
		else {
			$q  = "SELECT shopper_group_id,shopper_group_name,#__{vm}_shopper_group.vendor_id,vendor_name FROM #__{vm}_shopper_group ";
			$q .= ",#__{vm}_vendor WHERE #__{vm}_shopper_group.vendor_id = #__{vm}_vendor.vendor_id ";
		}
		$q .= "ORDER BY shopper_group_name";
		$db->query($q);
		while ($db->next_record()) {
			$shopper_groups[$db->f("shopper_group_id")] = $db->f("shopper_group_name"); // . '; '.$db->f('vendor_name').' (Vendor ID: '.$db->f('vendor_id').")";			
		}
		return ps_html::selectList( $name, $shopper_group_id, $shopper_groups, 1, '', $extra );
	}

	/**
	 * Retrieves the Shopper Group ID of the currently logged-in User
	 *
	 * @return int
	 */
	function get_id() {
		$auth = $_SESSION['auth'];

		$db = new ps_DB;

		$q =  "SELECT #__{vm}_shopper_group.shopper_group_id FROM #__{vm}_shopper_group,#__{vm}_shopper_vendor_xref ";
		$q .= "WHERE #__{vm}_shopper_vendor_xref.user_id='" . $auth["user_id"] . "' ";
		$q .= "AND #__{vm}_shopper_group.shopper_group_id=#__{vm}_shopper_vendor_xref.shopper_group_id";
		$db->query($q);
		$db->next_record();

		return $db->f("shopper_group_id");


	}

	/**
	 * Retrieves the Shopper Group Info of the SG specified by $id
	 *
	 * @param int $id
	 * @param boolean $default_group
	 * @return array
	 */
  	function get_shoppergroup_by_id($id, $default_group = false) {
    	
    	$ps_vendor_id = vmGet($_SESSION, 'ps_vendor_id', 1 );
    	$db = new ps_DB;

    	$q =  "SELECT #__{vm}_shopper_group.shopper_group_id, show_price_including_tax, `default`, shopper_group_discount 
    		FROM `#__{vm}_shopper_group`";
    	if( !empty( $id ) && !$default_group) {
      		$q .= ",`#__{vm}_shopper_vendor_xref`";
      		$q .= " WHERE #__{vm}_shopper_vendor_xref.user_id='" . $id . "' AND ";
      		$q .= "#__{vm}_shopper_group.shopper_group_id=#__{vm}_shopper_vendor_xref.shopper_group_id";
    	}
    	else {
    		$q .= " WHERE #__{vm}_shopper_group.vendor_id='$ps_vendor_id' AND `default`='1'";
    	}
    	$db->query($q);
    	if ($db->next_record()){ //not sure that is is filled in database (Steve)
            $group["shopper_group_id"] = $db->f("shopper_group_id");
            $group["shopper_group_discount"] = $db->f("shopper_group_discount");
            $group["show_price_including_tax"] = $db->f("show_price_including_tax");
            $group["default_shopper_group"] = $db->f("default");
        }
        else {
			$q = "SELECT #__{vm}_shopper_group.shopper_group_id, show_price_including_tax, `default`, shopper_group_discount 
    				FROM `#__{vm}_shopper_group`
    				WHERE #__{vm}_shopper_group.vendor_id='$ps_vendor_id' AND `default`='1'";
			$db->query($q);
			$db->next_record();
			$group["shopper_group_id"] = $db->f("shopper_group_id");
            $group["shopper_group_discount"] = $db->f("shopper_group_discount");
            $group["show_price_including_tax"] = $db->f("show_price_including_tax");
            $group["default_shopper_group"] = $db->f("default");
	    	
        }
    	return $group;
  	}
  	/**
  	 * Creates superglobals with the information regarding the default shopper group
  	 *
  	 */
  	function makeDefaultShopperGroupInfo() {
  		$vendor_id  =$_SESSION['ps_vendor_id'];
  		
		if( empty($GLOBALS['vendor_info'][$vendor_id]['default_shopper_group_id']) ) {
			$db = new ps_DB;
			// Get the default shopper group id for this vendor
			$q = "SELECT shopper_group_id,shopper_group_discount FROM #__{vm}_shopper_group WHERE ";
			$q .= "vendor_id='$vendor_id' AND `default`='1'";
			$db->query( $q );
			$db->next_record();
			$GLOBALS['vendor_info'][$vendor_id]['default_shopper_group_id'] = $default_shopper_group_id = $db->f("shopper_group_id");
			$GLOBALS['vendor_info'][$vendor_id]['default_shopper_group_discount']= $default_shopper_group_discount = $db->f("shopper_group_discount");
			unset( $db );
		}
  	}
	/**
	 * Retrieves the Customer Number of the user specified by ID
	 *
	 * @param int $id
	 * @return string
	 */
	function get_customer_num($id) {

		$db = new ps_DB;

		$q =  "SELECT customer_number FROM #__{vm}_shopper_vendor_xref ";
		$q .= "WHERE user_id='" . $id . "' ";
		$db->query($q);
		$db->next_record();

		return $db->f("customer_number");

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
	class ps_shopper_group extends vm_ps_shopper_group {}
}

$ps_shopper_group = new ps_shopper_group;

?>
