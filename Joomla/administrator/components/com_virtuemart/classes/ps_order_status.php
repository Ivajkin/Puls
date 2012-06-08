<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: ps_order_status.php 2287 2010-02-02 12:20:25Z soeren_nb $
* @package VirtueMart
* @subpackage classes
* @copyright Copyright (C) 2004-2010 soeren - All rights reserved.
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
 * The class for managing order status entries
 *
 */
class vm_ps_order_status extends vmAbstractObject {

	var $_key = 'order_status_id';
	var $_table_name = '#__{vm}_order_status';
	
	var $_protected_status_codes = array( 'P', 'C', 'X' );
	
	function vm_ps_order_status() {
		$this->addRequiredField( array( 'order_status_code', 'order_status_name') );
		$this->addUniqueField( 'order_status_code');
	}
	
	/*
	** VALIDATION FUNCTIONS
	**
	*/

	function validate_add(&$d) {

		return $this->validate( $d );
	}

	function validate_update(&$d) {
		global $VM_LANG;
		
		if( !$this->validate( $d ) ) {
			return false;
		}
		$db = $this->get(intval($d["order_status_id"]));
		if( $db->f('order_status_code')) {
			$order_status_code = $db->f('order_status_code');
			// Check if the Order Status Code of protected Order Statuses is to be changed
			if( in_array( $order_status_code, $this->_protected_status_codes ) && $order_status_code != $d["order_status_code"] ) {
				$vmLogger->err( $VM_LANG->_('VM_ORDERSTATUS_CHANGE_ERR_CORE') );
				return False;
			}
			if( $order_status_code != $d["order_status_code"] ) {
				// If the order Status Code has changed, we need to update all orders with this order status to use the new Status Code
				$dbo = new ps_DB();
				$dbo->query('UPDATE #__{vm}_orders SET 
										order_status=\''.$dbo->getEscaped($d["order_status_code"]).'\'
										WHERE order_status=\''.$order_status_code.'\'');
				
			}
			return true;
		} else {
			return false;
		}
		
	}

	function validate_delete($d) {
		global $VM_LANG, $vmLogger;
		
		if (empty($d["order_status_id"])) {
			$vmLogger->err( $VM_LANG->_('VM_ORDERSTATUS_DELETE_ERR_SELECT') );
			return False;
		}
		$order_status = array();
		if( is_array( $d["order_status_id"]) ) {
			foreach( $d["order_status_id"] as $order_status_id ) {
				$order_status[] = intval($order_status_id);
			}
		} else {
			$order_status[] = intval($d["order_status_id"]);
		}
		foreach( $order_status as $order_status_id ) {
			$db = $this->get($order_status_id);
			if( $db->f('order_status_code')) {
				$order_status_code = $db->f('order_status_code');
				if( in_array( $order_status_code, $this->_protected_status_codes ) ) {
					$vmLogger->err( $VM_LANG->_('VM_ORDERSTATUS_DELETE_ERR_CORE') );
					return False;
				}
				$dbo = new ps_DB();
				$dbo->query('SELECT order_id FROM #__{vm}_orders WHERE order_status=\''.$order_status_code.'\' LIMIT 1');
				if( $dbo->next_record() ) {
					$vmLogger->err( $VM_LANG->_('VM_ORDERSTATUS_DELETE_ERR_STILL') );
					return False;
				}
			}
		}
		return True;

	}

	/**
	 * creates a new Order Status
	 * @author soeren, pablo
	 * @param array $d
	 * @return boolean
	 */
	function add(&$d) {
		global $VM_LANG;
		
		$db = new ps_DB;
		$ps_vendor_id = $_SESSION["ps_vendor_id"];

		if (!$this->validate_add($d)) {
			return False;
		}
		$fields = array( 'vendor_id' => $ps_vendor_id,
						'order_status_code' => vmGet($d, 'order_status_code' ),
						'order_status_name' => vmGet($d, 'order_status_name' ),
						'order_status_description' => vmGet($d, 'order_status_description' ),
						'list_order' => vmRequest::getInt('list_order' )
					);
		$db->buildQuery( 'INSERT', $this->_table_name, $fields );
		
		$result = $db->query();
		
		if( $result ) {
			$GLOBALS['vmLogger']->info($VM_LANG->_('VM_ORDERSTATUS_ADDED'));
			$d["order_status_id"] = $_REQUEST['order_status_id'] = $db->last_insert_id();
		} else {
			$GLOBALS['vmLogger']->err($VM_LANG->_('VM_ORDERSTATUS_ADD_FAILED'));
		}
		return $result;

	}

	/**
	 * Updates an Order Status
	 *
	 * @param array $d
	 * @return boolean
	 */
	function update(&$d) {
		global $VM_LANG;
		
		$db = new ps_DB;
		$ps_vendor_id = $_SESSION["ps_vendor_id"];

		if (!$this->validate_update($d)) {
			return False;
		}
		$fields = array(	'order_status_code' => vmGet($d, 'order_status_code' ),
						'order_status_name' => vmGet($d, 'order_status_name' ),
						'order_status_description' => vmGet($d, 'order_status_description' ),
						'list_order' => vmRequest::getInt('list_order' )
					);
		$db->buildQuery( 'UPDATE', $this->_table_name, $fields, "WHERE order_status_id=".(int)$d["order_status_id"]." AND vendor_id=$ps_vendor_id" );
		
		if( $db->query() !== false ) {
			$GLOBALS['vmLogger']->info($VM_LANG->_('VM_ORDERSTATUS_UPDATED'));
			return true;
		}
		return false;
	}

	/**
	* Controller for Deleting Records.
	*/
	function delete(&$d) {

		if (!$this->validate_delete($d)) {
			return False;
		}
		$record_id = $d["order_status_id"];

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
		$ps_vendor_id = $_SESSION["ps_vendor_id"];

		$q = 'DELETE FROM `'.$this->_table_name.'` WHERE order_status_id='.(int)$record_id;
		$q .= " AND vendor_id='$ps_vendor_id'";
		
		return $db->query($q);
	}


	function list_order_status($order_status_code, $extra="") {
		echo $this->getOrderStatusList( $order_status_code, $extra );
	}
	/**
	 * Returns a DropDown List of all available Order Status Codes
	 *
	 * @param string $order_status_code
	 * @param string $extra
	 * @return string
	 */
	function getOrderStatusList( $order_status_code, $extra="") {
		$db = new ps_DB;

		$q = "SELECT order_status_id, order_status_code, order_status_name FROM #__{vm}_order_status ORDER BY list_order";
		$db->query($q);
		$array = array();
		while ($db->next_record()) {
			$array[$db->f("order_status_code")] = $db->f("order_status_name");
		}
		return ps_html::selectList( 'order_status', $order_status_code, $array, 1, '', $extra );
	}
	/**
	 * Returns the order status name for a given order status code
	 *
	 * @param string $order_status_code
	 * @return string
	 */
	function getOrderStatusName( $order_status_code ) {
		if( empty($GLOBALS['order_status'][$order_status_code])) {
			$db = new ps_DB;
	
			$q = "SELECT order_status_id, order_status_name FROM #__{vm}_order_status WHERE `order_status_code`='".$order_status_code."'";
			$db->query($q);
			$db->next_record();
			$GLOBALS['order_status'][$order_status_code] = $db->f("order_status_name");
		}
		return $GLOBALS['order_status'][$order_status_code];
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
	class ps_order_status extends vm_ps_order_status {}
}
?>
