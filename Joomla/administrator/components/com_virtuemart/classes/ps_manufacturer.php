<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: ps_manufacturer.php 1660 2009-02-22 17:05:02Z tkahl $
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
 * The class is is used to manage the manufacturers in your store.
 *
 */
class vm_ps_manufacturer {

	/**
	 * Validates the Input Parameters onBeforeManufacturerAdd
	 *
	 * @param array $d
	 * @return boolean
	 */
	function validate_add($d) {
		global $VM_LANG;
		
		$db = new ps_DB;

		if (empty($d["mf_name"])) {
			$GLOBALS['vmLogger']->err( $VM_LANG->_('VM_MANUF_ERR_NAME') );
			return False;
		}
		else {
			$q = "SELECT count(*) as rowcnt from #__{vm}_manufacturer where";
			$q .= " mf_name='" .  $db->getEscaped($d["mf_name"]) . "'";
			$db->query($q);
			$db->next_record();
			if ($db->f("rowcnt") > 0) {
				$GLOBALS['vmLogger']->err( $VM_LANG->_('VM_MANUF_ERR_EXISTS') );
				return False;
			}
		}
		return True;
	}
	/**
	 * Validates the Input Parameters onBeforeManufacturerUpdsate
	 *
	 * @param array $d
	 * @return boolean
	 */
	function validate_update($d) {
		global $VM_LANG;
		
		if (empty($d["mf_name"])) {
			$GLOBALS['vmLogger']->err( $VM_LANG->_('VM_MANUF_ERR_NAME') );
			return False;
		}

		return true;
	}
	/**
	 * Validates the Input Parameters onBeforeManufacturerDelete
	 *
	 * @param array $d
	 * @return boolean
	 */
	function validate_delete($mf_id) {
		global $db, $VM_LANG;

		if (empty( $mf_id )) {
			$GLOBALS['vmLogger']->err( $VM_LANG->_('VM_MANUF_ERR_DELETE_SELECT') );
			return False;
		}
				$db->query( "SELECT `#__{vm}_product`.product_id, manufacturer_id  	FROM `#__{vm}_product`, `#__{vm}_product_mf_xref` WHERE manufacturer_id =".intval($mf_id)." AND `#__{vm}_product`.product_id = `#__{vm}_product_mf_xref`.product_id" );				
		if( $db->num_rows() > 0 ) {
			$GLOBALS['vmLogger']->err( $VM_LANG->_('VM_MANUF_ERR_DELETE_STILLPRODUCTS') );
			return false;
		}
		return True;

	}

	/**
	 * creates a new manufacturer record
	 *
	 * @param array $d
	 * @return boolean
	 */
	function add(&$d) {
		global $VM_LANG;
		
		$db = new ps_DB;
		
		if (!$this->validate_add($d)) {
			return false;
		}
		$fields = array( 'mf_name' => vmGet( $d, 'mf_name' ),
					'mf_email' => vmGet( $d, 'mf_email' ),
					'mf_desc' => vmGet( $d, 'mf_desc', '', VMREQUEST_ALLOWHTML ),
					'mf_category_id' => vmRequest::getInt('mf_category_id'),
					'mf_url' => vmGet( $d, 'mf_url')
		);
		$db->buildQuery('INSERT', '#__{vm}_manufacturer', $fields );
		if( $db->query() !== false ) {
			$GLOBALS['vmLogger']->info( $VM_LANG->_('VM_MANUF_ADDED') );
			$_REQUEST['manufacturer_id'] = $db->last_insert_id();
			return true;	
		}
		return false;

	}

	/**
	 * updates manufacturer information
	 *
	 * @param array $d
	 * @return boolean
	 */
	function update(&$d) {
		global $VM_LANG;
		
		$db = new ps_DB;
		
		if (!$this->validate_update($d)) {
			return False;
		}
		$fields = array( 'mf_name' => vmGet( $d, 'mf_name' ),
					'mf_email' => vmGet( $d, 'mf_email' ),
					'mf_desc' => vmGet( $d, 'mf_desc', '', VMREQUEST_ALLOWHTML ),
					'mf_category_id' => vmRequest::getInt('mf_category_id'),
					'mf_url' => vmGet( $d, 'mf_url')
		);
		$db->buildQuery('UPDATE', '#__{vm}_manufacturer', $fields, 'WHERE manufacturer_id='.(int)$d["manufacturer_id"] );
		if( $db->query() ) {
			$GLOBALS['vmLogger']->info( $VM_LANG->_('VM_MANUF_UPDATED') );
			return true;	
		}
		return false;
	}

	/**
	* Controller for Deleting Records.
	*/
	function delete(&$d) {

		$record_id = $d["manufacturer_id"];

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
		if (!$this->validate_delete($record_id)) {
			return False;
		}
		$q = 'DELETE from #__{vm}_product_mf_xref WHERE manufacturer_id='.(int)$record_id.' LIMIT 1';
		$db->query($q);
		$q = 'DELETE from #__{vm}_manufacturer WHERE manufacturer_id='.(int)$record_id.' LIMIT 1';
		$db->query($q);
		return True;
	}
	/**
	 * Prints a drop-down list of manufacturer names and their ids.
	 *
	 * @param int $manufacturer_id
	 */
	function list_manufacturer($manufacturer_id='0') {

		$db = new ps_DB;

		$q = "SELECT manufacturer_id as id,mf_name as name FROM #__{vm}_manufacturer ORDER BY mf_name";
		$db->query($q);
		$db->next_record();

		// If only one vendor do not show list
		if ($db->num_rows() == 1) {

			echo '<input type="hidden" name="manufacturer_id" value="'. $db->f("id").'" />';
			echo $db->f("name");
		}
		elseif( $db->num_rows() > 1) {
			$db->reset();
			$array = array();
			while ($db->next_record()) {
				$array[$db->f("id")] = $db->f("name");
			}
			$code = ps_html::selectList('manufacturer_id', $manufacturer_id, $array ). "<br />\n";
			echo $code;
		}
		else  {
			echo '<input type="hidden" name="manufacturer_id" value="1" />Please create at least one Manufacturer!!';
		}
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
	class ps_manufacturer extends vm_ps_manufacturer {}
}
?>