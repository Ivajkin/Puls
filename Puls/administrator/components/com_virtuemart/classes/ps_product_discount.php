<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
 *
 * @version $Id: ps_product_discount.php 1660 2009-02-22 17:05:02Z tkahl $
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
 * The class is is used to manage the discounts in your store.
 *
 */
class vm_ps_product_discount {
	
	/**
	 * Validates the input parameters onBeforeDiscountAdd
	 *
	 * @param array $d
	 * @return boolean
	 */
	function validate_add( $d ) {
		global $VM_LANG;
		
		if( ! $d["amount"] ) {
			$GLOBALS['vmLogger']->err( $VM_LANG->_('VM_PRODUCT_DISCOUNT_ERR_AMOUNT') );
			return False ;
		}
		if( $d["is_percent"] == "" ) {
			$GLOBALS['vmLogger']->err( $VM_LANG->_('VM_PRODUCT_DISCOUNT_ERR_TYPE') );
			return False ;
		}
		
		if( !empty($d['end_date']) && ( $d['end_date'] < $d['start_date'] ) ) {		
			$GLOBALS['vmLogger']->err( $VM_LANG->_('VM_PRODUCT_DISCOUNT_START_END_DATE') );
			return False;
		}
		
		return True ;
	}
	
	/**
	 * Validates the Input Parameters onBeforeDiscountUpdate
	 *
	 * @param array $d
	 * @return boolean
	 */
	function validate_update( $d ) {
		global $VM_LANG;
		
		if( empty($d["amount"]) ) {
			$GLOBALS['vmLogger']->err( $VM_LANG->_('VM_PRODUCT_DISCOUNT_ERR_AMOUNT') );
			return False ;
		}
		if( $d["is_percent"] == "" ) {
			$GLOBALS['vmLogger']->err( $VM_LANG->_('VM_PRODUCT_DISCOUNT_ERR_TYPE') );
			return False ;
		}
		if( ! $d["discount_id"] ) {
			$GLOBALS['vmLogger']->err( $VM_LANG->_('VM_PRODUCT_DISCOUNT_ERR_UPDATE') );
			return False ;
		}
		
		if( !empty($d['end_date']) && ( $d['end_date'] < $d['start_date'] ) ) {		
			$GLOBALS['vmLogger']->err( $VM_LANG->_('VM_PRODUCT_DISCOUNT_START_END_DATE') );
			return False;
		}
		
		return true ;
	}
	
	/**
	 * Validates the Input Parameters onBeforeDiscountUpdate
	 *
	 * @param array $d
	 * @return boolean
	 */
	function validate_delete( $discount_id ) {
		global $VM_LANG;
		
		if( ! $discount_id ) {
			$GLOBALS['vmLogger']->err( $VM_LANG->_('VM_PRODUCT_DISCOUNT_ERR_DELETE') );
			return False ;
		}
		
		return True ;
	
	}

	/**
	 * creates a new discount record
	 *
	 * @param array $d
	 * @return boolean
	 */
	function add( &$d ) {
		global $VM_LANG;
		
		$db = new ps_DB( ) ;
		
		if( ! empty( $d["start_date"] ) ) {
			$day = substr( $d["start_date"], 8, 2 ) ;
			$month = substr( $d["start_date"], 5, 2 ) ;
			$year = substr( $d["start_date"], 0, 4 ) ;
			$d["start_date"] = mktime( 0, 0, 0, $month, $day, $year ) ;
		} else {
			$d["start_date"] = 0;
		}
		if( ! empty( $d["end_date"] ) ) {
			$day = substr( $d["end_date"], 8, 2 ) ;
			$month = substr( $d["end_date"], 5, 2 ) ;
			$year = substr( $d["end_date"], 0, 4 ) ;
			$d["end_date"] = mktime( 0, 0, 0, $month, $day, $year ) ;
		} else {
			$d["end_date"] = 0;
		}

		if( ! $this->validate_add( $d ) ) {
			return False ;
		}
		
		$fields = array('amount' => (float)vmGet( $d, 'amount'), 
								'is_percent' => (int)vmGet($d, 'is_percent'), 
								'start_date' => $d["start_date"], 
								'end_date' => $d["end_date"]
								);
		$db->buildQuery('INSERT', '#__{vm}_product_discount', $fields );
		$db->query() ;
		
		$GLOBALS['vmLogger']->info( $VM_LANG->_('VM_PRODUCT_DISCOUNT_ADDED') );
		$_REQUEST['discount_id'] = $db->last_insert_id() ;
		
		return True ;
	
	}
	
	/**
	 * updates discount information
	 *
	 * @param array $d
	 * @return boolean
	 */
	function update( &$d ) {
		global $VM_LANG;
		
		$db = new ps_DB( ) ;
		
		if( ! empty( $d["start_date"] ) ) {
			$day = substr( $d["start_date"], 8, 2 ) ;
			$month = substr( $d["start_date"], 5, 2 ) ;
			$year = substr( $d["start_date"], 0, 4 ) ;
			$d["start_date"] = mktime( 0, 0, 0, $month, $day, $year ) ;
		} else {
			$d["start_date"] = "" ;
		}
		if( ! empty( $d["end_date"] ) ) {
			$day = substr( $d["end_date"], 8, 2 ) ;
			$month = substr( $d["end_date"], 5, 2 ) ;
			$year = substr( $d["end_date"], 0, 4 ) ;
			$d["end_date"] = mktime( 0, 0, 0, $month, $day, $year ) ;
		} else {
			$d["end_date"] = "" ;
		}

		if( ! $this->validate_update( $d ) ) {
			return False ;
		}

		$fields = array('amount' => (float)vmGet( $d, 'amount'), 
								'is_percent' => (int)vmGet($d, 'is_percent'), 
								'start_date' => $d["start_date"], 
								'end_date' => $d["end_date"]
								);
		$db->buildQuery('UPDATE', '#__{vm}_product_discount', $fields, 'WHERE discount_id=' .(int)$d["discount_id"] );
		$db->query() ;
		
		$GLOBALS['vmLogger']->info( $VM_LANG->_('VM_PRODUCT_DISCOUNT_UPDATED') );
		
		return True ;
	}
	
	/**
	 * Controller for Deleting Records.
	 */
	function delete( &$d ) {
		
		$record_id = $d["discount_id"] ;
		
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
	 * Deletes one Record.
	 */
	function delete_record( $record_id, &$d ) {
		global $db ;
		
		if( ! $this->validate_delete( $record_id ) ) {
			return False ;
		}
		$q = 'DELETE FROM #__{vm}_product_discount WHERE discount_id='.(int)$record_id;
		$db->query( $q ) ;
		$q = 'UPDATE #__{vm}_product SET product_discount_id=0 WHERE product_discount_id='.(int)$record_id;
		$db->query( $q ) ;
		
		return True ;
	}

	/**
	 * Builds a select list of all discounts.
	 *
	 * @param int $discount_id
	 * @param boolean $show_dates Show the discount start and end dates
	 * @return string The html for the select list 
	 */
	function discount_list( $discount_id = 0, $show_dates = true ) {
		global $VM_LANG, $option ;
		$db = new ps_DB( ) ;
		$html = "" ;
		$db->query( "SELECT * FROM #__{vm}_product_discount" ) ;
		
		if( $db->num_rows() > 0 ) {
			$html = "<select name=\"product_discount_id\" class=\"inputbox\" onchange=\"updateDiscountedPrice();\">\n" ;
			$html .= "<option id=\"*1\" value=\"0\">" . $VM_LANG->_('PHPSHOP_INFO_MSG_VAT_ZERO_LBL') . "</option>\n" ;
			while( $db->next_record() ) {
				if( $db->f( "is_percent" ) ) {
					$id = "*" . (100 - $db->f( "amount" )) / 100 ;
				} else
					$id = "-" . $db->f( "amount" ) ;
				$selected = $db->f( "discount_id" ) == $discount_id ? "selected=\"selected\"" : "" ;
				$html .= "<option id=\"$id\" value=\"" . $db->f( "discount_id" ) . "\" $selected>" . $db->f( "amount" ) ;
				$html .= $db->f( "is_percent" ) == "1" ? "%" : $_SESSION['vendor_currency'] ;
				
				$start_date = $db->f('start_date') ? strftime( '%Y-%m-%d', $db->f('start_date') ) : '*';
				$end_date = $db->f('end_date') ? strftime( '%Y-%m-%d', $db->f('end_date') ) : '*';
				if( $show_dates ) {
					$html .= ' (' . $start_date . ' - ' . $end_date . ')';
				}
				$html .= "</option>\n" ;
			}
			$html .= "<option value=\"override\">".$VM_LANG->_('VM_PRODUCT_DISCOUNT_OVERRIDE')."</option>\n" ;
			$html .= "</select>\n" ;
		} else {
			$html = "<input type=\"hidden\" name=\"product_discount_id\" value=\"0\" />\n
      <a href=\"" . $_SERVER['PHP_SELF'] . "?option=$option&page=product.product_discount_form\" target=\"_blank\">" . $VM_LANG->_('PHPSHOP_PRODUCT_DISCOUNT_ADDDISCOUNT_TIP') . "</a>" ;
		}
		return $html ;
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
	class ps_product_discount extends vm_ps_product_discount {}
}
?>
