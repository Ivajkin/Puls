<?php
if( ! defined( '_VALID_MOS' ) && ! defined( '_JEXEC' ) )
	die( 'Direct Access to ' . basename( __FILE__ ) . ' is not allowed.' ) ;
/**
 *
 * @version $Id: ps_tax.php 1660 2009-02-22 17:05:02Z tkahl $
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

class vm_ps_tax extends vmAbstractObject  {
	var $key = 'rax_rate_id';
	var $_table_name = '#__{vm}_tax_rate';
	
	/**
	 * Validates the input values before adding an item
	 *
	 * @param arry $d The _REQUEST array
	 * @return boolean True on success, false on failure
	 */
	function validate_add( &$d ) {
		global $vmLogger, $VM_LANG;
		$valid = true ;
		$db = new ps_DB( ) ;
		if( TAX_MODE != '1' && $d['tax_state'] != ' - ' ) {
			$q = "SELECT tax_rate_id from #__{vm}_tax_rate WHERE tax_state='" . $d["tax_state"] . "'" ;
			$db->query( $q ) ;
			if( $db->next_record() ) {
				$vmLogger->err( $VM_LANG->_('VM_TAX_ERR_STATE_LISTED') ) ;
				$valid = False ;
			}
		}
		/**
		if (!$d["tax_state"]) {
			$vmLogger->err( 'You must enter a state or region for this tax rate.' );
			$valid = False;
		}
		 */
		if( empty($d["tax_country"]) ) {
			$vmLogger->err( $VM_LANG->_('VM_TAX_ERR_COUNTRY') ) ;
			$valid = False ;
		}
		require_once( CLASSPATH.'ps_country.php');
		$ps_country = new ps_country();
		$country_db = $ps_country->get_country_by_code($d["tax_country"]);
		if( $country_db === false ) {
			$vmLogger->err( $VM_LANG->_('VM_TAX_ERR_COUNTRY_NOTEXIST') );
			return false;
		}
		if( empty($d["tax_rate"]) ) {
			$vmLogger->err( $VM_LANG->_('VM_TAX_ERR_TAXRATE') ) ;
			$valid = False ;
		}
		$d["tax_rate"] = floatval( str_replace( ',', '.', $d['tax_rate'] ) ) ;
		if( $d["tax_rate"] > 1.0 ) {
			$d["tax_rate"] = $d["tax_rate"] / 100 ;
		}
		
		return $valid ;
	}
	/**
	 * Validates the input values before updating an item
	 *
	 * @param arry $d The _REQUEST array
	 * @return boolean True on success, false on failure
	 */
	function validate_update( &$d ) {
		global $vmLogger, $VM_LANG;
		
		$db = new ps_DB( ) ;
		
		if( ! $d["tax_rate_id"] ) {
			$vmLogger->err( $VM_LANG->_('VM_TAX_ERR_UPDATE_SELECT') ) ;
			return False ;
		}
		/**
		if (!$d["tax_state"]) {
			$vmLogger->err( 'You must enter a state or region for this tax rate.' );
			return False;
		 */
		if( empty($d["tax_country"]) ) {
			$vmLogger->err( $VM_LANG->_('VM_TAX_ERR_COUNTRY') ) ;
			return False ;
		}
		require_once( CLASSPATH.'ps_country.php');
		$ps_country = new ps_country();
		$country_db = $ps_country->get_country_by_code($d["tax_country"]);
		if( $country_db === false ) {
			$vmLogger->err( $VM_LANG->_('VM_TAX_ERR_COUNTRY_NOTEXIST') );
			return false;
		}
		if( empty($d["tax_rate"]) ) {
			$vmLogger->err( $VM_LANG->_('VM_TAX_ERR_TAXRATE') ) ;
			return False ;
		}
		$d["tax_rate"] = floatval( str_replace( ',', '.', $d['tax_rate'] ) ) ;
		if( $d["tax_rate"] > 1.0 ) {
			$d["tax_rate"] = $d["tax_rate"] / 100 ;
		}
		return True ;
	}
	/**
	 * Validates the input values before deleting an item
	 *
	 * @param arry $d The _REQUEST array
	 * @return boolean True on success, false on failure
	 */
	function validate_delete( $d ) {
		global $vmLogger, $VM_LANG;
		
		if( ! $d["tax_rate_id"] ) {
			$vmLogger->err( $VM_LANG->_('VM_TAX_ERR_DELETE_SELECT') ) ;
			return False ;
		}
		
		return True ;
	
	}
	
	/**
	 * Creates a new tax record
	 * @author pablo
	 *
	 * @param arry $d The _REQUEST array
	 * @return boolean True on success, false on failure
	 */
	function add( &$d ) {
		global $VM_LANG;
		$db = new ps_DB( ) ;
		$ps_vendor_id = $_SESSION["ps_vendor_id"] ;
		$timestamp = time() ;
		
		if( ! $this->validate_add( $d ) ) {
			return False ;
		}
		$fields = array('vendor_id' => $ps_vendor_id, 
								'tax_state' => vmget( $d, 'tax_state' ), 
								'tax_country' => vmget( $d, 'tax_country' ),
								'tax_rate' => $d["tax_rate"], 
								'mdate' => $timestamp
								);
		$db->buildQuery('INSERT', $this->getTable(), $fields );
		if( $db->query() !== false ) {
			$_REQUEST['tax_rate_id'] = $db->last_insert_id() ;
			$GLOBALS['vmLogger']->info($VM_LANG->_('VM_TAX_ADDED'));
			return True ;
		}
		$GLOBALS['vmLogger']->err($VM_LANG->_('VM_TAX_ADD_FAILED'));
		return false;	
	}
	
	/**
	 * Updates a tax record
	 * @author pablo
	 *
	 * @param arry $d The _REQUEST array
	 * @return boolean True on success, false on failure
	 */
	function update( &$d ) {
		global $VM_LANG;
		$db = new ps_DB( ) ;
		$ps_vendor_id = $_SESSION["ps_vendor_id"] ;
		$timestamp = time() ;
		
		if( ! $this->validate_update( $d ) ) {
			return False ;
		}
		$fields = array('vendor_id' => $ps_vendor_id, 
								'tax_state' => vmget( $d, 'tax_state' ), 
								'tax_country' => vmget( $d, 'tax_country' ),
								'tax_rate' => $d["tax_rate"], 
								'mdate' => $timestamp
								);
		$db->buildQuery('UPDATE', $this->getTable(), $fields, 'WHERE tax_rate_id=' . $d["tax_rate_id"] . ' AND vendor_id='.$ps_vendor_id );
		if( $db->query() !== false ) {
			$GLOBALS['vmLogger']->info($VM_LANG->_('VM_TAX_UPDATED'));
			return True ;
		}
		$GLOBALS['vmLogger']->err($VM_LANG->_('VM_TAX_UPDATE_FAILED'));
		return false;	
		
	}
	
	/**
	 * Controller for Deleting Records.
	 */
	function delete( &$d ) {
		
		if( ! $this->validate_delete( $d ) ) {
			return False ;
		}
		
		$record_id = $d["tax_rate_id"] ;
		
		if( is_array( $record_id ) ) {
			foreach( $record_id as $record ) {
				if( ! $this->delete_record( $record, $d ) )
					return false ;
			}
			return true ;
		} else {
			return $this->delete_record( $record_id, $d ) ;
		}
	}
	/**
	 * Deletes one tax record.
	 */
	function delete_record( $record_id, &$d ) {
		global $db ;
		$ps_vendor_id = $_SESSION["ps_vendor_id"] ;
		
		$q = 'DELETE FROM #__{vm}_tax_rate WHERE tax_rate_id='.(int)$record_id;
		$q .= " AND vendor_id=$ps_vendor_id LIMIT 1" ;
		$db->query( $q );
		return True ;
	}
	
	/**
	 * creates a HTML List of the tax values
	 *
	 * @param string $select_name the name of the select form
	 * @param int $selected_value_id ID of the selected Item
	 * @param string $on_change
	 * @return array An array with all Tax Rates
	 */
	function list_tax_value( $select_name, $selected_value_id, $on_change = '' ) {
		global $VM_LANG ;
		$db = new ps_DB( ) ;
		
		// Get list of Values
		$q = "SELECT `tax_rate_id`, `tax_rate`  FROM `#__{vm}_tax_rate` ORDER BY `tax_rate` DESC, `tax_rate_id` ASC" ;
		$db->query( $q ) ;
		
		if( $on_change != '' ) {
			$on_change = " onchange=\"$on_change\"" ;
		}
		$ratesArr[0] = $VM_LANG->_( 'PHPSHOP_INFO_MSG_VAT_ZERO_LBL' ) ;
		
		$tax_rates = Array( ) ;
		while( $db->next_record() ) {
			$tax_rates[$db->f( "tax_rate_id" )] = $db->f( "tax_rate" ) ;
			$ratesArr[$db->f( "tax_rate_id" )] = $db->f( "tax_rate_id" ) . " (" . $db->f( "tax_rate" ) * 100 . "%)" ;
		}
		ps_html::dropdown_display( $select_name, $selected_value_id, $ratesArr, 1, '', $on_change ) ;
		
		return $tax_rates ;
	}
	
	/**
	 * Return the Tax Rate with the given id
	 *
	 * @param int $tax_rate_id
	 * @return float
	 */
	function get_taxrate_by_id( $tax_rate_id ) {
		
		$db = new ps_DB( ) ;
		
		$q = "SELECT tax_rate FROM #__{vm}_tax_rate WHERE tax_rate_id='$tax_rate_id'" ;
		$db->query( $q ) ;
		if( $db->next_record() ) {
			$rate = $db->f( "tax_rate" ) ;
			return $rate ;
		} else
			return (0) ;
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
	class ps_tax extends vm_ps_tax {}
}

?>
